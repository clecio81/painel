<?php
require_once('./api/controles/db.php');

if (conectar_bd()) {
    echo "✅ Conexão com sucesso!";
} else {
    echo "❌ Falhou a conexão.";
}