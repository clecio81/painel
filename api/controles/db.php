<?php
header("Access-Control-Allow-Origin: *"); 
date_default_timezone_set('America/Sao_Paulo');
header("Server: nginx");

function conectar_bd() {
    $endereco = "127.0.0.1"; // Use TCP em vez de socket
    $banco = "elaxiDB"; 
    $dbusuario = "root"; 
    $dbsenha = ""; 

    try {
        $conexao = new PDO("mysql:host=$endereco;port=3306;dbname=$banco", $dbusuario, $dbsenha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch(PDOException $e) {
        echo "❌ Erro ao conectar: " . $e->getMessage() . "❌ Falhou a conexão.";
        return null;
    }
}