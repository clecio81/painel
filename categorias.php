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
    <title>Categorias - Admin</title>
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

        .badge-live {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(6, 182, 212, 0.2);
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

        .badge-yes {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-no {
            background-color: rgba(148, 163, 184, 0.1);
            color: var(--text-muted);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        /* Modal styles */
        .modal-content {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background-color: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.125rem;
            margin: 0;
        }

        .modal-body {
            padding: 1.5rem;
            color: var(--text-primary);
        }

        .modal-footer {
            background-color: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 12px 12px;
            padding: 1.5rem;
        }

        .btn-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .btn-close:hover {
            color: var(--text-primary);
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

        /* Form styles for modal */
        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 0.75rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background-color: var(--bg-secondary);
        }

        .form-select {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 0.75rem;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>
<body>

<main class="container-fluid">
    <div class="content-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Categorias</h1>
                <p class="page-subtitle">Gerencie todas as categorias do sistema</p>
            </div>
            <button type="button" class="btn-add" onclick='modal_master("api/categorias.php", "add_categoria", "add")'>
                <i class="fas fa-plus"></i>
                Nova Categoria
            </button>
        </div>

        <div class="table-container">
            <table id="data_table" class="display" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="min-width: 75px;">#</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Adulto</th>
                        <th>BG SSIPTV</th>
                        <th style="min-width: 120px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="//cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="./js/sweetalert2.js"></script>
<script src="./js/custom.js"></script>
<script src="./js/categorias.js?sfd"></script>

<div class="modal fade" id="modal_master" tabindex="-1" aria-labelledby="modal_master" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modal_master-header">
                <h5 class="modal-title" id="modal_master-titulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="modal_master_form" onsubmit="event.preventDefault();" autocomplete="off">
                <div id="modal_master-body" class="modal-body" style="max-height: 421px; overflow-y: auto;"></div>
                <div id="modal_master-footer" class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for categorias.js to fully load and initialize
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
        $('#data_table tbody td:nth-child(3)').each(function() {
            const text = $(this).text().toLowerCase().trim();
            if (!$(this).find('.badge').length) {
                if (text.includes('live') || text.includes('vivo')) {
                    $(this).html('<span class="badge badge-live">AO VIVO</span>');
                } else if (text.includes('movie') || text.includes('filme')) {
                    $(this).html('<span class="badge badge-movie">FILME</span>');
                } else if (text.includes('series') || text.includes('série')) {
                    $(this).html('<span class="badge badge-series">SÉRIE</span>');
                } else if (text) {
                    $(this).html('<span class="badge badge-normal">' + text.toUpperCase() + '</span>');
                }
            }
        });
        
        // Enhance adult column with badges
        $('#data_table tbody td:nth-child(4)').each(function() {
            const text = $(this).text().trim();
            if (!$(this).find('.badge').length) {
                if (text == '1' || text.toLowerCase() == 'sim' || text.toLowerCase() == 'yes') {
                    $(this).html('<span class="badge badge-adult">ADULTO</span>');
                } else {
                    $(this).html('<span class="badge badge-no">NORMAL</span>');
                }
            }
        });

        // Enhance BG SSIPTV column with badges
        $('#data_table tbody td:nth-child(5)').each(function() {
            const text = $(this).text().trim();
            if (!$(this).find('.badge').length) {
                if (text == '1' || text.toLowerCase() == 'sim' || text.toLowerCase() == 'yes') {
                    $(this).html('<span class="badge badge-yes">SIM</span>');
                } else {
                    $(this).html('<span class="badge badge-no">NÃO</span>');
                }
            }
        });
        
        // Enhance existing action buttons (don't recreate them)
        $('#data_table tbody td:nth-child(6) button').each(function() {
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
        $('#data_table tbody td:nth-child(6)').each(function() {
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
    
    console.log('🎯 Categorias - Estrutura padrão aplicada!');
});
</script>

</body>
</html>