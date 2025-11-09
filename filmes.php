<?php
session_start();
if (isset($_SESSION['nivel_admin']) && $_SESSION['nivel_admin'] == 0) {
    header("Location: ./clientes.php");
    exit();
}
require_once("menu.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --success-hover: #059669;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --warning-color: #f59e0b;
            --warning-hover: #d97706;
            --info-color: #06b6d4;
            --info-hover: #0891b2;
            
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --bg-card: #1e293b;
            --bg-hover: #475569;
            
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            --border-color: #334155;
            --border-hover: #475569;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Base styles */
        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Main container */
        main {
            padding: 1.5rem;
            max-width: 100%;
            margin-top: 3rem;
        }

        .content-container {
            background-color: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-color);
            padding: 2rem;
            margin: 0 auto;
        }

        /* Header section */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Add button */
        .btn-add {
            background-color: var(--success-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease-in-out;
            box-shadow: var(--shadow-sm);
        }

        .btn-add:hover {
            background-color: var(--success-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-add:active {
            transform: translateY(0);
        }

        /* Table container */
        .table-container {
            background-color: var(--bg-secondary);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            overflow-x: auto; /* Adicionado para responsividade */
            -webkit-overflow-scrolling: touch; /* Rolagem suave em iOS */
        }

        /* Table styles */
        #data_table {
            width: 100%;
            margin: 0;
            background-color: transparent;
            border-collapse: separate;
            border-spacing: 0;
        }

        #data_table thead {
            background-color: var(--bg-tertiary);
        }

        #data_table thead th {
            padding: 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            white-space: nowrap;
        }

        #data_table thead th:last-child {
            border-right: none;
        }

        #data_table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
        }

        #data_table tbody td:last-child {
            border-right: none;
        }

        #data_table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        #data_table tbody tr:hover {
            background-color: var(--bg-hover);
        }

        #data_table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem;
            border-radius: 6px;
            border: none;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }

        .btn-edit {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-edit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background-color: var(--danger-hover);
            transform: translateY(-1px);
        }

        /* Badge styles */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge-movie {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-series {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-adult {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-normal {
            background-color: rgba(148, 163, 184, 0.1);
            color: var(--text-muted);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .badge-hd {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        .badge-4k {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        /* Movie icon - igual aos canais */
        .movie-icon {
            width: 120px;
            height: 140px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border-color);
        }

        /* Modal styles corrigidas */
        .modal-content {
            background-color: #2c3e50 !important;
            border: 1px solid #34495e !important;
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            color: #ffffff !important;
        }

        .modal-header {
            background-color: #1abc9c !important;
            border-bottom: 1px solid #16a085 !important;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
            color: #ffffff !important;
        }

        .modal-title {
            color: #ffffff !important;
            font-weight: 600;
            font-size: 1.125rem;
            margin: 0;
        }

        .modal-body {
            background-color: #34495e !important;
            padding: 1.5rem;
            color: #ffffff !important;
        }

        .modal-footer {
            background-color: #2c3e50 !important;
            border-top: 1px solid #34495e !important;
            border-radius: 0 0 12px 12px;
            padding: 1.5rem;
        }

        /* Estilos dos campos de formulário no modal */
        .modal-body .form-label {
            color: #ffffff !important;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            background-color: #4a5f7a !important;
            border: 1px solid #5a6c7d !important;
            border-radius: 6px;
            color: #ffffff !important;
            padding: 0.75rem;
            transition: all 0.2s ease-in-out;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            outline: none;
            border-color: #1abc9c !important;
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2) !important;
            background-color: #4a5f7a !important;
        }

        .modal-body .form-control::placeholder {
            color: #bdc3c7 !important;
        }

        /* Botões do modal */
        .modal-footer .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .modal-footer .btn-primary {
            background-color: #1abc9c !important;
            border-color: #1abc9c !important;
            color: #ffffff !important;
        }

        .modal-footer .btn-primary:hover {
            background-color: #16a085 !important;
            border-color: #16a085 !important;
        }

        .modal-footer .btn-secondary {
            background-color: #e74c3c !important;
            border-color: #e74c3c !important;
            color: #ffffff !important;
        }

        .modal-footer .btn-secondary:hover {
            background-color: #c0392b !important;
            border-color: #c0392b !important;
        }

        /* Botão de fechar */
        .btn-close {
            background: none !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            opacity: 1 !important;
        }

        .btn-close:hover {
            color: #f8f9fa !important;
            transform: scale(1.1);
        }

        /* Textareas específicos */
        .modal-body textarea.form-control {
            background-color: #4a5f7a !important;
            border: 1px solid #5a6c7d !important;
            color: #ffffff !important;
            min-height: 120px;
            resize: vertical;
        }

        /* Select específicos */
        .modal-body select.form-select option {
            background-color: #4a5f7a !important;
            color: #ffffff !important;
        }

        /* Input de data */
        .modal-body input[type="date"].form-control {
            background-color: #4a5f7a !important;
            border: 1px solid #5a6c7d !important;
            color: #ffffff !important;
        }

        .modal-body input[type="date"].form-control::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Input de número */
        .modal-body input[type="number"].form-control {
            background-color: #4a5f7a !important;
            border: 1px solid #5a6c7d !important;
            color: #ffffff !important;
        }

        /* Grupos de input */
        .modal-body .input-group .form-control {
            background-color: #4a5f7a !important;
            border: 1px solid #5a6c7d !important;
            color: #ffffff !important;
        }

        .modal-body .input-group-text {
            background-color: #5a6c7d !important;
            border: 1px solid #5a6c7d !important;
            color: #ffffff !important;
        }

        /* DataTables custom styling */
        .dataTables_wrapper {
            font-family: 'Inter', sans-serif;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 0.5rem 0.75rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .dataTables_wrapper .dataTables_length select {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 0.5rem;
            margin: 0 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--border-hover);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            main {
                padding: 1rem;
                margin-top: 1.5rem;
                margin-top: 3rem;
            }

            .content-container {
                padding: 1rem;
                border-radius: 8px;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .page-title {
                font-size: 1.5rem;
                text-align: center;
            }

            .btn-add {
                justify-content: center;
            }

            #data_table thead th,
            #data_table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-action {
                min-width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

<main class="container-fluid">
    <div class="content-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Filmes</h1>
                <p class="page-subtitle">Gerencie todos os filmes do sistema</p>
            </div>
            <button type="button" onclick="buscarTMDBPeloNomeDoFilme()" class="btn btn-outline-success">Puxar TMDB</button>
            <button type="button" onclick="atualizarDetalhesTMDB()" class="btn btn-outline-success">Puxar Info</button>
            <button type="button" class="btn-add" onclick='modal_master("api/filmes.php", "adicionar_filmes", "add")'>
                <i class="fas fa-plus"></i>
                Adicionar Filme
            </button>
        </div>

        <div class="table-container">
            <table id="data_table" class="display" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="min-width: 75px;">#</th>
                        <th>Nome</th>
                        <th>Icon</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th style="font-size: small;">Adulto</th>
                        <th style="min-width: 120px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTable will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="//cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<script src="./js/sweetalert2.js"></script>
<script src="./js/custom.js"></script>
<script src="./js/datatablevod.js?sfd"></script>

<!-- Modal Master -->
<div class="modal fade" id="modal_master" tabindex="-1" aria-labelledby="modal_master" aria-hidden="true" style="backdrop-filter: blur(5px) grayscale(1);">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="d-block modal-header" id="modal_master-header">
                <h5 class="float-start modal-title" id="modal_master-titulo"></h5>
                <button type="button" class="fa btn text-white fa-close fs-6 float-end" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="modal_master_form" onsubmit="event.preventDefault();" autocomplete="off">
                <div id="modal_master-body" class="modal-body overflow-auto" style="max-height: 60vh;"></div>
                <div id="modal_master-footer" class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalProgresso"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
     background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center;">

    <div style="background: #fff; padding: 20px 20px 30px; border-radius: 10px; width: 420px; max-width: 90%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3); position: relative; font-family: Arial, sans-serif;">

        <!-- Botao de fechar -->
        <button onclick="fecharModalProgresso()"
                style="position: absolute; top: 10px; right: 10px; background: transparent; border: none;
                font-size: 20px; cursor: pointer;">✖
        </button>

        <h3 style="margin: 0 0 20px; text-align: center; font-size: 20px; color: #333;">
            Atualizando Filmes
        </h3>

        <!-- Barra de progresso -->
        <div style="background: #eee; border-radius: 6px; height: 25px; margin-bottom: 15px; overflow: hidden;">
            <div id="barraProgresso"
                 style="height: 100%; width: 0%; background: linear-gradient(to right, #28a745, #43d77d);
                 text-align: center; color: white; line-height: 25px; font-weight: bold; transition: width 0.4s;">
                0 / 0
            </div>
        </div>

        <!-- Lista de progresso -->
        <div id="progressoLista"
             style="max-height: 200px; overflow-y: auto; font-size: 14px; border: 1px solid #ccc; padding: 10px;
             border-radius: 5px; background: #f9f9f9;">
            <!-- Itens de progresso serao add aqui -->
        </div>

        <!-- Botao de cancelar -->
        <button onclick="cancelModalProgresso()"
                style="margin-top: 20px; width: 100%; padding: 10px; background: #dc3545; color: white;
                border: none; border-radius: 5px; cursor: pointer; font-size: 15px;">
            Cancelar Atualização
        </button>
    </div>
</div>


<div id="tmdbProgressModal"
     style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.7); z-index:9999;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:30px 20px 20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.3); width:320px; text-align:center; position:relative;">
        <button onclick="fecharTMDBModal()"
                style="position:absolute; top:10px; right:10px; background:transparent; border:none; font-size:18px; cursor:pointer;">
            ✖
        </button>
        <h3 id="tmdbStatus" style="margin-bottom:20px;">Iniciando atualização...</h3>
        <div style="height:20px; background:#eee; border-radius:10px; overflow:hidden; box-shadow:inset 0 1px 3px rgba(0,0,0,0.1);">
            <div id="tmdbProgressBar"
                 style="height:100%; width:0%; background:linear-gradient(to right, #4caf50, #81c784); transition:width 0.3s ease;"></div>
        </div>
        <button onclick="fecharTMDBModal()"
                style="margin-top:15px; background:#e53935; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer;">
            Cancelar
        </button>
    </div>
</div>

<script>

    function fecharModalProgresso() {
        document.getElementById('modalProgresso').style.display = 'none';
        cancelarAtualizacao = true;
    }

    function cancelModalProgresso() {
        document.getElementById('modalProgresso').style.display = 'none';
        cancelarAtualizacao = true;
    }


    let cancelarAtualizacao = false;

    function fecharTMDBModal() {
        document.getElementById('tmdbProgressModal').style.display = 'none';
        cancelarAtualizacao = true;
    }

    async function atualizarDetalhesTMDB() {
    cancelarAtualizacao = false;
    document.getElementById('tmdbProgressModal').style.display = 'block';

    const response = await fetch('api/tmdb.php?action=atualizar_infos');
    const filmes = await response.json();
    const total = filmes.length;

    const blockSize = 200;
    let startIndex = parseInt(localStorage.getItem('atualizacao_index')) || 0;

    async function processarBloco() {
        if (cancelarAtualizacao || startIndex >= total) {
            document.getElementById('tmdbStatus').innerText =
                cancelarAtualizacao ? '❌ Atualização cancelada' : `✅ Todos os filmes foram atualizados!`;
            localStorage.removeItem('atualizacao_index');
            return;
        }

        const bloco = filmes.slice(startIndex, startIndex + blockSize);
        let atual = 0;

        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        for (const filme of bloco) {
            if (cancelarAtualizacao) {
                document.getElementById('tmdbStatus').innerText = '❌ Atualização cancelada';
                return;
            }

            const tmdbId = filme.tmdb_id;
            const localId = filme.id;

            try {
                const data = await fetch(`api/tmdb.php?get_filmes=${tmdbId}`);
                const info = await data.json();

                await fetch('api/tmdb.php?action=salvar_detalhes', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id: localId,
                        titulo: info.nome,
                        description: info.plot,
                        plot: info.plot,
                        stream_icon: info.logo,
                        backdrop_path: info.backdrop_path,
                        release_date: info.releasedate,
                        duration: info.duration,
                        duration_secs: 0,
                        rating: info.rating,
                        rating_5based: info.rating_5based,
                        age: info.adult ? '18+' : 'Livre',
                        year: info.year ? info.year : 'Livre',
                        genre: info.genre,
                        actors: info.actors,
                        country: '',
                        director: info.director,
                        runtime: info.runtime,
                        youtube_trailer: ''
                    })
                });

                atual++;
                const globalIndex = startIndex + atual;
                const percent = ((globalIndex / total) * 100).toFixed(1);

                document.getElementById('tmdbStatus').innerText = `Atualizando ${globalIndex} de ${total}...`;
                document.getElementById('tmdbProgressBar').style.width = percent + '%';

                localStorage.setItem('atualizacao_index', globalIndex);
                await delay(300);

            } catch (e) {
                console.error(`Erro ao processar TMDb ID ${tmdbId}`, e);
            }
        }

        startIndex += atual;
        await processarBloco();
    }

    await processarBloco();
}

    async function buscarTMDBPeloNomeDoFilme() {
        const modal = document.getElementById('modalProgresso');
        const barra = document.getElementById('barraProgresso');
        const lista = document.getElementById('progressoLista');

        modal.style.display = 'flex';
        lista.innerHTML = '';
        barra.style.width = '0%';
        barra.innerText = '0 / 0';

        try {
            const res = await fetch('api/tmdb.php?action=listar');
            const filmes = await res.json();

            const total = filmes.length;
            let atual = 0;

            for (const filme of filmes) {
                const linha = document.createElement('div');
                linha.textContent = `🔄 ${filme.name}`;
                lista.appendChild(linha);

                try {
                    const tmdb_id = await buscarTMDBId(filme.name);
                    if (tmdb_id) {
                        await fetch('api/tmdb.php?action=atualizar', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: `id=${filme.id}&tmdb_id=${tmdb_id}`
                        });
                        linha.textContent = `✅ ${filme.name} → TMDB ID: ${tmdb_id}`;
                        linha.style.color = 'green';
                    } else {
                        linha.textContent = `❌ ${filme.name} não encontrado`;
                        linha.style.color = 'red';
                    }
                } catch (e) {
                    linha.textContent = `⚠️ Erro ao buscar ${filme.name}`;
                    linha.style.color = 'orange';
                }

                atual++;
                const perc = Math.round((atual / total) * 100);
                barra.style.width = perc + '%';
                barra.innerText = `${atual} / ${total}`;
            }

            const final = document.createElement('div');
            final.innerHTML = `<strong>🎉 Processamento concluído: ${total} filmes</strong>`;
            lista.appendChild(final);
        } catch (erro) {
            lista.innerHTML = '<div style="color: red;">Erro ao buscar lista de filmes.</div>';
        }
    }
 
    function limparNome(nome) {
  nome = nome.replace(/\(.*?\)/g, '');
  nome = nome.replace(/\[.*?\]/g, '');
  nome = nome.replace(/\b(19|20)\d{2}\b/g, '');
  nome = nome.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  nome = nome.replace(/[^a-zA-Z0-9\s]/g, '');
  nome = nome.replace(/\b(LEG|Leg|leg|Legendado)\b/g, '');
  nome = nome.replace(/\[LEG\]/gi, '');
  nome = nome.replace(/\(LEG\)/gi, '');
  nome = nome.replace(/\(\d{4}\)/g, '');
  nome = nome.replace(/\s\d{4}$/, '');
  nome = nome.replace(/\b4K\b/gi, '');
  nome = nome.replace(/\s+/g, ' ').trim();
  return nome.toLowerCase();
}

function extrairAno(nome) {
  const anoMatch = nome.match(/\b(19|20)\d{2}\b/);
  return anoMatch ? anoMatch[0] : null;
}

async function buscarTMDBId(nomeOriginal) {
  const chave = '66d600a2e10bb528752724cddadf6f8c';
  const nomeFiltrado = limparNome(nomeOriginal);
  const anoExtraido = extrairAno(nomeOriginal);

  const url = `https://api.themoviedb.org/3/search/movie?api_key=${chave}&query=${encodeURIComponent(nomeFiltrado)}&language=pt-BR&include_adult=true`;
  const res = await fetch(url);
  const json = await res.json();
  const resultados = json.results || [];

  if (anoExtraido) {
    const exatoComAno = resultados.find(r => {
      const nomeTMDB = limparNome(r.title || '');
      return (nomeTMDB === nomeFiltrado || limparNome(r.original_title || '') === nomeFiltrado) &&
             r.release_date?.startsWith(anoExtraido);
    });
    if (exatoComAno) return exatoComAno.id;
  }

  const exatoSemAno = resultados.find(r => {
    const nomeTMDB = limparNome(r.title || '');
    return nomeTMDB === nomeFiltrado || limparNome(r.original_title || '') === nomeFiltrado;
  });
  if (exatoSemAno) return exatoSemAno.id;

  if (anoExtraido) {
    const parcialComAno = resultados.find(r => {
      const nomeTMDB = limparNome(r.title || '');
      return (nomeTMDB.includes(nomeFiltrado) || limparNome(r.original_title || '').includes(nomeFiltrado)) &&
             r.release_date?.startsWith(anoExtraido);
    });
    if (parcialComAno) return parcialComAno.id;
  }

  return resultados[0]?.id ?? null;
}

document.addEventListener('DOMContentLoaded', function() {
    // Wait for datatablevod.js to fully load and initialize
    setTimeout(function() {
        enhanceExistingTable();
    }, 1000);
    
    function enhanceExistingTable() {
        // Only enhance visual aspects, don't touch functionality
        enhanceTableVisuals();
        
        // Re-apply enhancements after DataTable redraws
        if ($.fn.DataTable.isDataTable('#data_table')) {
            $('#data_table').on('draw.dt', function() {
                setTimeout(enhanceTableVisuals, 100);
            });
        }
    }
    
    function enhanceTableVisuals() {
        // Enhance type column with badges
        $('#data_table tbody td:nth-child(5)').each(function() {
            const text = $(this).text().toLowerCase().trim();
            if (!$(this).find('.badge').length) {
                if (text.includes('movie') || text.includes('filme')) {
                    $(this).html('<span class="badge badge-movie">FILME</span>');
                } else if (text.includes('series') || text.includes('série')) {
                    $(this).html('<span class="badge badge-series">SÉRIE</span>');
                } else if (text.includes('hd')) {
                    $(this).html('<span class="badge badge-hd">HD</span>');
                } else if (text.includes('4k')) {
                    $(this).html('<span class="badge badge-4k">4K</span>');
                } else if (text) {
                    $(this).html('<span class="badge badge-normal">' + text.toUpperCase() + '</span>');
                }
            }
        });
        
        // Enhance adult column with badges
        $('#data_table tbody td:nth-child(6)').each(function() {
            const text = $(this).text().trim();
            if (!$(this).find('.badge').length) {
                if (text == '1' || text.toLowerCase() == 'sim' || text.toLowerCase() == 'yes') {
                    $(this).html('<span class="badge badge-adult">ADULTO</span>');
                } else {
                    $(this).html('<span class="badge badge-normal">NORMAL</span>');
                }
            }
        });

        // Enhance icon column
        $('#data_table tbody td:nth-child(3)').each(function() {
            const $cell = $(this);
            const imgSrc = $cell.find('img').attr('src');
            if (imgSrc && !$cell.find('.movie-icon').length) {
                $cell.html(`<img src="${imgSrc}" alt="Icon" class="movie-icon">`);
            }
        });
        
        // Enhance existing action buttons (don't recreate them)
        $('#data_table tbody td:nth-child(7) button').each(function() {
            const $btn = $(this);
            if (!$btn.hasClass('btn-action')) {
                // Identify button type and apply appropriate class
                const text = $btn.text().toLowerCase();
                const classes = $btn.attr('class') || '';
                
                if (text.includes('edit') || text.includes('editar') || classes.includes('edit')) {
                    $btn.addClass('btn-action btn-edit');
                    if (!$btn.find('i').length) {
                        $btn.html('<i class="fas fa-edit"></i>');
                    }
                } else if (text.includes('delete') || text.includes('excluir') || text.includes('apagar') || classes.includes('delete')) {
                    $btn.addClass('btn-action btn-delete');
                    if (!$btn.find('i').length) {
                        $btn.html('<i class="fas fa-trash"></i>');
                    }
                }
            }
        });
        
        // Wrap action buttons in container if not already wrapped
        $('#data_table tbody td:nth-child(7)').each(function() {
            if (!$(this).find('.action-buttons').length) {
                const buttons = $(this).find('button');
                if (buttons.length > 0) {
                    buttons.wrapAll('<div class="action-buttons"></div>');
                }
            }
        });
    }
    
    // Enhanced button loading animation (don't interfere with original onclick)
    $(document).on('click', '#data_table .btn-action', function(e) {
        const btn = $(this);
        const originalHTML = btn.html();
        
        // Add loading state
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        // Restore button after action completes
        setTimeout(() => {
            btn.html(originalHTML);
            btn.prop('disabled', false);
        }, 2000);
    });
    
    console.log('🎯 Filmes - Estrutura padrão aplicada!');
});
</script>

</body>
</html>