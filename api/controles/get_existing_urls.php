<?php
header("Access-Control-Allow-Origin: *"); 
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
header("Server: nginx");

// Função de conexão
function conectar_bd() {
    $endereco = "127.0.0.1";
    $banco = "elaxiDB"; 
    $dbusuario = "root"; 
    $dbsenha = ""; 

    try {
        $conexao = new PDO("mysql:host=$endereco;port=3306;dbname=$banco;charset=utf8mb4", $dbusuario, $dbsenha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "❌ Erro ao conectar: " . $e->getMessage()
        ]);
        return null;
    }
}

// Conecta ao banco
$pdo = conectar_bd();
if (!$pdo) exit();

// Consulta URLs existentes
try {
    // Substitua 'conteudos' pelo nome da sua tabela e 'url' pelo nome da coluna real
    $stmt = $pdo->query("SELECT link FROM streams WHERE link IS NOT NULL AND link != ''");
    $urls = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Retorna JSON
    echo json_encode(["link" => $urls ?? []]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Erro ao consultar o banco de dados: " . $e->getMessage()
    ]);
}
?>