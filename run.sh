#!/bin/bash

# Elaxi Control Panel CLI
#
# Este script fornece uma interface de linha de comando para gerenciar
# o ambiente de desenvolvimento da aplicação Elaxi.

# --- Configurações ---
PHP_HOST="127.0.0.1"
PHP_PORT="8000"
SERVER_LOG_FILE="server.log"
SERVER_PID_FILE=".server.pid"

# --- Cores e Estilos ---
C_RESET='\033[0m'
C_RED='\033[0;31m'
C_GREEN='\033[0;32m'
C_YELLOW='\033[0;33m'
C_BLUE='\033[0;34m'
C_PURPLE='\033[0;35m'
C_CYAN='\033[0;36m'
C_WHITE='\033[0;37m'
C_BOLD='\033[1m'

# --- Funções Auxiliares ---

# Exibe um spinner de carregamento
spinner() {
    local pid=$1
    local delay=0.1
    local spinstr='|/-\'
    echo -n "  "
    while [ "$(ps a | awk '{print $1}' | grep $pid)" ]; do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    printf "    \b\b\b\b"
}

# Exibe uma mensagem de sucesso
success() {
    echo -e "${C_GREEN}${C_BOLD}✔ SUCESSO:${C_RESET} $1"
}

# Exibe uma mensagem de erro
error() {
    echo -e "${C_RED}${C_BOLD}✖ ERRO:${C_RESET} $1"
}

# Exibe uma mensagem de informação
info() {
    echo -e "${C_CYAN}${C_BOLD}ℹ INFO:${C_RESET} $1"
}

# Exibe o banner da CLI
show_banner() {
    clear
echo -e "${C_PURPLE}${C_BOLD}"
echo "___________.____       _____  ____  ___.___ "
echo "\_   _____/|    |     /  _  \ \   \/  /|   |"
echo " |    __)_ |    |    /  /_\  \ \     / |   |"
echo " |        \|    |___/    |    \/     \ |   |"
echo "/_______  /|_______ \____|__  /___/\  \|___|"
echo "        \/         \/       \/      \_/      "
echo -e "          ${C_WHITE}ELAXI Control Panel CLI${C_RESET}"
echo ""
}

# Verifica se o servidor está em execução
is_server_running() {
    if [ -f "$SERVER_PID_FILE" ]; then
        local pid=$(cat "$SERVER_PID_FILE")
        if ps -p $pid > /dev/null; then
            return 0 # Servidor está rodando
        else
            # O arquivo PID existe, mas o processo não. Limpando.
            rm "$SERVER_PID_FILE"
            return 1
        fi
    fi
    return 1 # Servidor não está rodando
}

# --- Funções de Gerenciamento ---

# Verifica e instala dependências
check_dependencies() {
    info "Verificando dependências..."
    
    # Verifica o PHP
    if ! command -v php &> /dev/null; then
        error "PHP não encontrado. Por favor, instale o PHP e tente novamente."
        return 1
    fi

    # Verifica o MySQL/MariaDB client
    if ! command -v mysql &> /dev/null; then
        error "MySQL/MariaDB client não encontrado."
        info "Tentando instalar o 'mariadb-server'. Isso pode pedir sua senha (sudo)."
        
        # Tenta detectar o gerenciador de pacotes
        if command -v apt-get &> /dev/null; then
            sudo apt-get update
            sudo apt-get install -y mariadb-server
        elif command -v yum &> /dev/null; then
            sudo yum install -y mariadb-server
        else
            error "Não foi possível detectar o gerenciador de pacotes (apt/yum). Por favor, instale o 'mariadb-server' manualmente."
            return 1
        fi

        if ! command -v mysql &> /dev/null; then
            error "A instalação falhou. Por favor, instale o 'mariadb-server' manualmente."
            return 1
        fi
        success "MariaDB Server instalado com sucesso."
    fi
    
    success "Todas as dependências foram atendidas."
    return 0
}

# Inicia o banco de dados e o configura se necessário
start_database() {
    info "Verificando o status do serviço de banco de dados..."

    # Extrai as credenciais do db.php
    DB_HOST=$(grep '$endereco =' api/controles/db.php | sed -e 's/.*"\(.*\)".*/\1/')
    DB_NAME=$(grep '$banco =' api/controles/db.php | sed -e 's/.*"\(.*\)".*/\1/')
    DB_USER=$(grep '$dbusuario =' api/controles/db.php | sed -e 's/.*"\(.*\)".*/\1/')
    DB_PASS=$(grep '$dbsenha =' api/controles/db.php | sed -e 's/.*"\(.*\)".*/\1/')
    SQL_FILE="createDB/elaxi.sql"

    # Tenta conectar ao servidor de banco de dados por 15 segundos
    local retries=15
    while ! mysqladmin ping -u"$DB_USER" -p"$DB_PASS" --silent; do
        retries=$((retries-1))
        if [ $retries -le 0 ]; then
            error "Não foi possível conectar ao servidor MySQL/MariaDB."
            info "Por favor, inicie o serviço de banco de dados em outro terminal."
            info "Exemplos: ${C_YELLOW}sudo systemctl start mariadb${C_RESET} ou ${C_YELLOW}sudo service mysql start${C_RESET}"
            return 1
        fi
        echo -n "."
        sleep 1
    done
    echo ""
    success "Serviço de banco de dados está ativo! Aperte enter."

    # Verifica se o banco de dados existe
    if mysql -u"$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME;" > /dev/null 2>&1; then
        success "Banco de dados '$DB_NAME' já existe e está pronto."
    else
        info "Banco de dados '$DB_NAME' não encontrado. Tentando criar e importar..."
        
        if [ ! -f "$SQL_FILE" ]; then
            error "Arquivo de importação '$SQL_FILE' não encontrado. Não é possível configurar o banco de dados."
            return 1
        fi

        # Cria o banco de dados
        if mysql -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE $DB_NAME;"; then
            info "Banco de dados '$DB_NAME' criado com sucesso."
            
            # Importa o arquivo SQL
            info "Importando dados de '$SQL_FILE'..."
            (mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE") &
            spinner $!
            
            if [ $? -eq 0 ]; then
                success "Dados importados com sucesso para '$DB_NAME'."
            else
                error "Falha ao importar dados para '$DB_NAME'."
                return 1
            fi
        else
            error "Falha ao criar o banco de dados '$DB_NAME'."
            return 1
        fi
    fi
    return 0
}

# Inicia o servidor PHP
start_server() {
    if is_server_running; then
        error "O servidor já está em execução."
        info "URL: http://$PHP_HOST:$PHP_PORT"
        return
    fi

    # Roda a verificação e configuração do banco de dados
    if ! start_database; then
        # A função start_database já exibe a mensagem de erro
        return
    fi

    info "Iniciando o servidor PHP em segundo plano..."
    php -S "$PHP_HOST:$PHP_PORT" > "$SERVER_LOG_FILE" 2>&1 &
    local pid=$!
    echo $pid > "$SERVER_PID_FILE"
    (sleep 1) &
    spinner $!

    if is_server_running; then
        success "Servidor iniciado com sucesso!"
        info "URL de acesso: ${C_YELLOW}http://$PHP_HOST:$PHP_PORT${C_RESET}"
        info "Logs do servidor estão em: ${C_WHITE}${SERVER_LOG_FILE}${C_RESET}"
    else
        error "Falha ao iniciar o servidor. Verifique os logs:"
        cat "$SERVER_LOG_FILE"
    fi
}

# Para o servidor PHP
stop_server() {
    if ! is_server_running; then
        error "O servidor não está em execução."
        return
    fi

    info "Parando o servidor PHP..."
    local pid=$(cat "$SERVER_PID_FILE")
    kill $pid
    (sleep 1) &
    spinner $!
    rm "$SERVER_PID_FILE"
    success "Servidor parado com sucesso."
}

# Visualiza os logs do servidor em tempo real
view_logs() {
    if [ ! -f "$SERVER_LOG_FILE" ]; then
        error "Arquivo de log não encontrado. Inicie o servidor primeiro."
        return
    fi
    info "Exibindo logs em tempo real. Pressione ${C_YELLOW}CTRL+C${C_RESET} para sair."
    sleep 1
    tail -f "$SERVER_LOG_FILE"
}

# Limpa o cache da aplicação
clear_cache() {
    local cache_script="api/limpar-cache.php"
    if [ ! -f "$cache_script" ]; then
        error "Script de limpeza de cache não encontrado em '$cache_script'."
        return
    fi

    info "Executando script de limpeza de cache..."
    (sleep 1) & # Simula um delay
    spinner $!
    
    local output=$(php "$cache_script")
    
    success "Script executado."
    info "Saída: ${C_WHITE}$output${C_RESET}"
}

# --- Menu Principal ---
main_menu() {
    while true; do
        show_banner
        
        echo -e "${C_BOLD}O que você gostaria de fazer?${C_RESET}"
        echo ""
        echo -e "  ${C_YELLOW}1)${C_RESET} Iniciar Servidor"
        echo -e "  ${C_YELLOW}2)${C_RESET} Parar Servidor"
        echo -e "  ${C_YELLOW}3)${C_RESET} Ver Logs do Servidor"
        echo -e "  ${C_YELLOW}4)${C_RESET} Limpar Cache da Aplicação"
        echo -e "  ${C_YELLOW}5)${C_RESET} Verificar Dependências"
        echo -e "  ${C_RED}6)${C_RESET} Sair"
        echo ""

        if is_server_running; then
            local pid=$(cat "$SERVER_PID_FILE")
            echo -e "  ${C_GREEN}STATUS: Servidor ONLINE (PID: $pid) em http://$PHP_HOST:$PHP_PORT${C_RESET}"
        else
            echo -e "  ${C_RED}STATUS: Servidor OFFLINE${C_RESET}"
        fi
        
        echo ""
        read -p "Escolha uma opção: " choice

        case $choice in
            1) start_server ;;
            2) stop_server ;;
            3) view_logs ;;
            4) clear_cache ;;
            5) check_dependencies ;;
            6)
                if is_server_running; then
                    stop_server
                fi
                info "Saindo. Até logo!"
                exit 0
                ;;
            *) error "Opção inválida. Tente novamente." ;;
        esac
        echo ""
        read -p "Pressione Enter para continuar..."
    done
}

# --- Ponto de Entrada do Script ---
main_menu
