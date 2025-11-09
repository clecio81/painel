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
    <title>Listar Canais - Admin</title>
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

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-live {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(6, 182, 212, 0.2);
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

        /* Channel icon */
        .channel-icon {
            width: 48px;
            height: 48px;
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
                <h1 class="page-title">Canais</h1>
                <p class="page-subtitle">Gerencie todos os canais do sistema</p>
            </div>
            <button type="button" class="btn-add" onclick='modal_master("api/canais.php", "adicionar_canal", "add")'>
                <i class="fas fa-plus"></i>
                Adicionar Canal
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
            </table>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="//cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<script src="./js/sweetalert2.js"></script>
<script src="./js/datatablecanais.js?sfd"></script>
<script src="./js/custom.js"></script>

<div class="modal fade" id="modal_master" tabindex="-1" aria-labelledby="modal_master" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modal_master-header">
                <h5 class="float-start modal-title" id="modal_master-titulo"></h5>
                <button type="button" class="fa btn text-white fa-close fs-6 float-end" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="modal_master_form" onsubmit="event.preventDefault();" autocomplete="off">
                <div id="modal_master-body" class="modal-body overflow-auto" style="max-height: 421px;"></div>
                <div id="modal_master-footer" class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>