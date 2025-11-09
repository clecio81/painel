<?php
session_start();
header('Content-Type: application/json');

// Verifica se o usuário é admin
if (!isset($_SESSION['nivel_admin']) || $_SESSION['nivel_admin'] != 1) {
    echo json_encode([
        "title" => "Acesso Negado",
        "msg" => "Você não tem permissão para acessar esta página.",
        "icon" => "error"
    ]);
    exit();
}

// Inclui a conexão
require_once(__DIR__ . '/controles/db.php');
$pdo = conectar_bd();
if (!$pdo) {
    echo json_encode([
        "title" => "Erro de Conexão",
        "msg" => "Não foi possível conectar ao banco de dados.",
        "icon" => "error"
    ]);
    exit();
}

// Função para retornar JSON e encerrar
function respond($title, $msg, $icon = "success", $reload = false) {
    echo json_encode(compact('title','msg','icon','reload'));
    exit();
}
// LISTAR PACOTES
if (isset($_POST['listar_pacotes'])) {
    $stmt = $pdo->prepare("SELECT id, pacote FROM pacotes ORDER BY id ASC");
    $stmt->execute();
    $pacotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna sempre um array JSON, mesmo vazio
    echo json_encode($pacotes);
    exit();
}
// ADICIONAR NOVO PACOTE
if (isset($_POST['add_pacote_action'])) {
    $nome = trim($_POST['nome_pacote'] ?? '');
    if (!$nome) respond("Erro", "O nome do pacote não pode estar vazio.", "error");

    $stmt = $pdo->prepare("INSERT INTO pacotes (pacote, admin_id) VALUES (:pacote, :admin_id)");
    $stmt->execute([
        'pacote' => $nome,
        'admin_id' => $_SESSION['admin_id'] ?? 0
    ]);

    respond("Sucesso", "Pacote criado com sucesso!", "success", true);
}

// EDITAR NOME DO PACOTE
if (isset($_POST['editar_pacote_action'])) {
    $pacote_id = intval($_POST['pacote_id'] ?? 0);
    $novo_nome = trim($_POST['novo_nome'] ?? '');
    if (!$pacote_id || !$novo_nome) respond("Erro", "Dados inválidos.", "error");

    $stmt = $pdo->prepare("UPDATE pacotes SET pacote = :pacote WHERE id = :id");
    $stmt->execute([
        'pacote' => $novo_nome,
        'id' => $pacote_id
    ]);

    respond("Sucesso", "Nome do pacote atualizado!", "success");
}

// EXCLUIR PACOTE
if (isset($_POST['excluir_pacote'])) {
    $pacote_id = intval($_POST['pacote_id'] ?? 0);
    if (!$pacote_id) respond("Erro", "Pacote inválido.", "error");

    $stmt = $pdo->prepare("DELETE FROM pacotes WHERE id = :id");
    $stmt->execute(['id' => $pacote_id]);

    // Também remover as categorias associadas
    $stmt = $pdo->prepare("DELETE FROM pacote_categorias WHERE pacote_id = :id");
    $stmt->execute(['id' => $pacote_id]);

    respond("Sucesso", "Pacote excluído com sucesso!", "success", true);
}

// EDITAR NOME DO PACOTE
if (isset($_POST['editar_pacote_action'])) {
    $pacote_id = intval($_POST['pacote_id'] ?? 0);
    $novo_nome = trim($_POST['novo_nome'] ?? '');
    if (!$pacote_id || !$novo_nome) respond("Erro", "Dados inválidos.", "error");

    $stmt = $pdo->prepare("UPDATE pacotes SET nome = :nome WHERE id = :id");
    $stmt->execute(['nome' => $novo_nome, 'id' => $pacote_id]);

    respond("Sucesso", "Nome do pacote atualizado!", "success");
}

// SALVAR CATEGORIAS DE PACOTE
if (isset($_POST['salvar_categorias_pacote'])) {
    $pacote_id = intval($_POST['pacote_id'] ?? 0);
    if (!$pacote_id) respond("Erro", "Pacote inválido.", "error");

    // Remove todas as associações atuais
    $stmt = $pdo->prepare("DELETE FROM pacote_categorias WHERE pacote_id = :id");
    $stmt->execute(['id' => $pacote_id]);

    // Insere novamente as categorias selecionadas na ordem enviada
    $categorias = $_POST['categorias'] ?? [];
    $ordem = json_decode($_POST['ordem_categorias'] ?? '[]', true);

    foreach ($ordem as $index => $cat_id) {
        if (in_array($cat_id, $categorias)) {
            $stmt = $pdo->prepare("INSERT INTO pacote_categorias (pacote_id, categoria_id, ordem) VALUES (:pacote_id, :cat_id, :ordem)");
            $stmt->execute(['pacote_id' => $pacote_id, 'cat_id' => $cat_id, 'ordem' => $index]);
        }
    }

    respond("Sucesso", "Categorias do pacote salvas!", "success");
}

// Se nenhum endpoint for chamado
respond("Erro", "Ação inválida.", "error");