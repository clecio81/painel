<?php
session_start();
require_once("menu.php"); // Seu menu.php é carregado aqui
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Testes</title> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main class="container-fluid">
    <div id="main-content-wrapper">
        <h4 class="align-items-center d-flex justify-content-between mb-4 text-muted text-uppercase">
            LISTAR Clientes em Teste <button type="button" class="btn btn-outline-success" onclick='modal_master("api/testes.php", "adicionar_testes", "add")'>
                <i class="fas fa-plus"></i> Adicionar
            </button>
        </h4>
        
        <table id="data_table" class="display overflow-auto table" style="width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Usuario</th>
                    <th>Status</th>
                    <th>Vencimento</th>
                    <th style="min-width: 191px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</main>

</script> <script src="./js/sweetalert2.js"></script>
<script src="./js/datatabletestes.js?sfd"></script> <script src="./js/custom.js"></script>


<div class="modal fade" id="modal_master" tabindex="-1" aria-labelledby="modal_master" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="d-block modal-header" id="modal_master-header">
                <h5 class="float-start modal-title" id="modal_master-titulo"></h5>
                <button type="button" class="fa btn text-white fa-close fs-6 float-end" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="modal_master_form" onsubmit="event.preventDefault();" autocomplete="off">
                <div id="modal_master-body" class="modal-body overflow-auto" style="max-height: 421px;"></div>
                <div id="modal_master-footer" class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>
<script>
function copyText(elementId) {
    var preElement = document.getElementById(elementId);
    var range = document.createRange();
    range.selectNodeContents(preElement);
    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand('copy');
    selection.removeAllRanges();
    //alert('Texto copiado para a área de transferência!'); // Comentado, SweetAlert é usado
    SweetAlert3('Texto copiado para a área de transferência!', 'success');
}
</script>

</body>
</html>