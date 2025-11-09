<?php

session_start();

// Verifica se a sessão está iniciada e se a variável de sessão existe e tem o valor desejado
if (isset($_SESSION['nivel_admin']) && $_SESSION['nivel_admin'] == 0) {
    // Redireciona para clientes.php
    header("Location: ./clientes.php");
    exit(); // Termina o script após o redirecionamento
}

require_once("menu.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de M3U - Futuro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Body and Container Styles */
        body {
            background: linear-gradient(135deg, #0d1a2a, #1a0d2a); /* Dark gradient background */
            color: #e0e0e0; /* Light gray text */
            font-family: 'Segoe UI', Arial, sans-serif; /* Modern, clean font */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            padding: 2rem;
            background-color: rgba(15, 25, 40, 0.7); /* Slightly transparent dark background */
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.2), 0 0 60px rgba(128, 0, 128, 0.1); /* Neon glow effect */
            backdrop-filter: blur(5px); /* Subtle blur for depth */
            border: 1px solid rgba(0, 255, 255, 0.3);
            margin-bottom: 2rem; /* Add some margin to the bottom of the main container */
        }

        /* Card Styles */
        .card {
            background-color: rgba(25, 35, 50, 0.8); /* Darker card background */
            border: 1px solid rgba(0, 255, 255, 0.4);
            border-radius: 10px !important;
            transition: all 0.3s ease;
            overflow: hidden; /* Ensure content doesn't overflow rounded corners */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 255, 255, 0.3);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-innerBody {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space out icon and text */
        }

        .card-innerBody .float-start {
            min-width: 60px;
            min-height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 255, 255, 0.1); /* Light accent background for icons */
            border-radius: 50%; /* Circular icons */
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        .card-innerBody .float-start i {
            color: #00ffff; /* Neon cyan for icons */
            font-size: 2.5rem; /* Larger icons */
            text-shadow: 0 0 5px #00ffff;
        }

        .card-label {
            color: #a0a0a0 !important; /* Muted text for labels */
            font-size: 0.9em !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-text {
            color: #ffffff !important; /* Bright white for numbers */
            font-size: 2.2rem !important; /* Larger numbers */
            font-weight: bold;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        .card-footer {
            background-color: rgba(30, 45, 60, 0.7); /* Slightly lighter footer */
            border-top: 1px solid rgba(0, 255, 255, 0.2);
            font-size: 0.8em;
            color: #b0b0b0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
        }

        .card-footer small {
            color: #b0b0b0 !important;
        }

        /* Progress Bar Styles */
        .progress {
            background-color: #3a4a5a; /* Darker track */
            border-radius: 5px;
            height: 25px !important; /* Slightly thinner */
            overflow: hidden;
            border: 1px solid rgba(0, 255, 255, 0.3);
        }

        .progress-bar {
            background-color: #00ffff; /* Neon cyan fill */
            color: #1a1a1a !important; /* Dark text on bar */
            font-weight: bold;
            font-size: 0.85em;
            text-shadow: 0 0 3px #00ffff;
            transition: width 0.4s ease-in-out;
            animation: pulse-progress 2s infinite alternate; /* Subtle pulse */
        }

        @keyframes pulse-progress {
            from { box-shadow: 0 0 5px #00ffff; }
            to { box-shadow: 0 0 10px #00ffff, 0 0 15px rgba(0, 255, 255, 0.3); }
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            border: none; /* Remove default bootstrap border */
        }

        .btn-primary {
            background-color: #00ffff; /* Neon cyan */
            color: #1a1a1a; /* Dark text */
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
        }

        .btn-primary:hover {
            background-color: #00e6e6; /* Slightly darker on hover */
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 255, 255, 0.7);
        }

        .btn-warning {
            background-color: #ffcc00; /* Amber */
            color: #1a1a1a;
        }

        .btn-warning:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #00ff80; /* Neon green */
            color: #1a1a1a;
        }

        .btn-success:hover {
            background-color: #00e673;
            transform: translateY(-2px);
        }

        .btn svg {
            fill: #1a1a1a; /* Ensure icon color matches text on futuristic buttons */
            margin-right: 5px;
        }

        /* Modal Styles */
        .modal-content {
            background: linear-gradient(135deg, #1a2a3a, #0a1a2a); /* Darker modal background */
            border-radius: 15px;
            border: 1px solid rgba(0, 255, 255, 0.4);
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.3);
            color: #e0e0e0;
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 255, 255, 0.2);
            color: #00ffff;
            padding: 1.5rem;
            position: relative; /* For absolute positioning of close button */
        }

        .modal-header .btn-close {
            filter: invert(1); /* Make close button visible on dark background */
            position: absolute;
            right: 15px;
            top: 15px;
            background-color: transparent;
            border: none;
            color: #00ffff; /* Neon color for close button */
            font-size: 1.5rem;
            opacity: 1; /* Ensure it's fully visible */
            transition: transform 0.2s ease;
        }

        .modal-header .btn-close:hover {
            transform: scale(1.2);
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem; /* Reduced from 2rem */
        }

        /* Modal Body/Drop Area adjustments for height */
        #dropArea, #dropArea2 { /* dropArea2 is the div with buttons in first modal */
            border: 2px dashed #00ffff !important; /* Neon dashed border */
            background-color: rgba(25, 35, 50, 0.7);
            color: #a0a0a0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            min-height: 180px !important; /* Adjusted from 250px/300px to be smaller */
            padding: 1.5rem !important; /* Reduce padding slightly */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative; /* For inner close button positioning */
        }

        /* Specific to the URL modal's dropArea2, if it has a different ID for clarity */
        #dropArea2_url { /* Ensure your HTML element for URL modal drop area has this ID */
            border: 2px dashed #00ffff !important; /* Neon dashed border */
            background-color: rgba(25, 35, 50, 0.7);
            color: #a0a0a0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            min-height: 180px !important; /* Adjusted for consistency */
            padding: 1.5rem !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        #dropArea.hover, #dropArea2.hover, #dropArea2_url.hover {
            border-color: #00ff80 !important; /* Green on hover */
            background-color: rgba(35, 50, 70, 0.8);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            color: #ffffff;
        }

        #dropArea h6, #dropArea2 h6, #dropArea2_url h6 {
            color: #00ffff;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
            margin-bottom: 1rem; /* Reduced margin */
        }

        /* Modal Buttons Specific */
        #btnSelectFile, #openSecondModal {
            background: rgba(0, 255, 255, 0.1);
            border: 1px solid #00ffff;
            color: #00ffff;
            padding: 1rem; /* Reduced from 1.5rem */
            margin: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100px; /* Reduced from 120px */
        }

        #btnSelectFile:hover, #openSecondModal:hover {
            background: rgba(0, 255, 255, 0.2);
            color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
            transform: translateY(-3px);
        }

        #btnSelectFile svg, #openSecondModal svg {
            fill: #00ffff;
            width: 35px; /* Slightly smaller icons */
            height: 35px;
            margin-bottom: 8px; /* Slightly less margin */
            transition: fill 0.3s ease;
        }
        #btnSelectFile:hover svg, #openSecondModal:hover svg {
            fill: #ffffff;
        }

        #btnSelectFile span, #openSecondModal span {
            font-size: 1em; /* Slightly smaller text */
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* URL Input Specific */
        .form-control {
            background-color: #334455;
            border: 1px solid #00ffff;
            color: #e0e0e0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #445566;
            border-color: #00ff80;
            box-shadow: 0 0 10px rgba(0, 255, 128, 0.5);
            color: #ffffff;
        }

        /* Radio Buttons */
        .form-check-input {
            background-color: #3a4a5a;
            border: 1px solid #00ffff;
            appearance: none; /* Hide default radio button */
            width: 1.2em;
            height: 1.2em;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: #00ffff;
            border-color: #00ffff;
            box-shadow: 0 0 5px #00ffff;
        }

        .form-check-input:checked::before {
            content: '';
            display: block;
            width: 0.6em;
            height: 0.6em;
            background-color: #1a1a1a; /* Dark dot in center */
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-check-label {
            color: #e0e0e0;
            font-size: 0.95em;
            margin-left: 0.3rem; /* Reduced margin */
        }

        .form-label {
            font-size: 0.9em; /* Smaller font size for form labels */
        }

        /* Author text */
        #autor1, #autor2 {
            color: #a0a0a0;
            margin-top: 1rem;
            padding-bottom: 1rem;
        }

        #autor1 a, #autor2 a {
            color: #00ffff; /* Neon link color */
            text-decoration: none;
            transition: color 0.2s ease;
        }

        #autor1 a:hover, #autor2 a:hover {
            color: #ffffff;
            text-shadow: 0 0 5px #00ffff;
        }

        /* Adjustments for absolute positioning of close button in modals */
        #modal_arquivo .modal-body #dropArea .btn-close {
            top: 5px;
            right: 5px;
        }

        /* For the URL modal's specific drop area */
        #modal_url .modal-body #dropArea2_url .btn-close {
            top: 5px; /* Adjusted as needed */
            right: 5px;
            position: absolute; /* Ensure it's absolutely positioned within its parent */
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="status" class="mt-3 row">
            <div class="container text-center">
                <div class="" id="controles">
                    <p id="partCount"></p>
                    <button class="btn btn-primary" id="openFirstModal">
                        <svg aria-hidden="true" focusable="false" fill="#607d8b" width="27" height="25" viewBox="0 0 27 25">
                            <path d="M5.586 9.288a.313.313 0 0 0 .282.176h4.84v3.922c0 1.514 1.25 2.24 2.792 2.24 1.54 0 2.79-.726 2.79-2.24V9.464h4.84c.122 0 .23-.068.284-.176a.304.304 0 0 0-.046-.324L13.735.106a.316.316 0 0 0-.472 0l-7.63 8.857a.302.302 0 0 0-.047.325z"></path>
                            <path d="M24.3 5.093c-.218-.76-.54-1.187-1.208-1.187h-4.856l1.018 1.18h3.948l2.043 11.038h-7.193v2.728H9.114v-2.725h-7.36l2.66-11.04h3.33l1.018-1.18H3.907c-.668 0-1.06.46-1.21 1.186L0 16.456v7.062C0 24.338.676 25 1.51 25h23.98c.833 0 1.51-.663 1.51-1.482v-7.062L24.3 5.093z"></path>
                        </svg>
                        Abrir o upload
                    </button>
                    <button id="pauseBtn" class="btn btn-warning">Pausar</button>
                    <button id="resumeBtn" class="btn btn-success" disabled>Continuar</button>
                </div>
                <div class="mt-4"></div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-hourglass-half"></i>
                            </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Tempo Total Estimado:</p>
                                <h4 class="card-text text-end" id="tempo_Total_Estimado">0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="float-start text-muted">Tempo Decorrido:</small>
                        <small class="text-black float-end ml-auto" id="tempo_Decorrido"></small>
                    </div>
                    <div class="card-footer">
                        <small class="float-start text-muted">Tempo Restante:</small>
                        <small class="text-black float-end ml-auto" id="tempo_Restante"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-8 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-clipboard-check"></i>
                            </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Envios</p>
                                <h4 class="card-text text-end" id="totalRequests">0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="align-items-center card-footer d-flex position-relative">
                        <small class="float-start mb-0 me-2 text-muted">Leitura do arquivo:</small>
                        <div class="flex-grow-1 progress">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                    <div class="align-items-center card-footer d-flex position-relative">
                        <small class="float-start mb-0 me-2 text-muted">Progresso da parte atual:</small>
                        <div class="flex-grow-1 progress">
                            <div id="partProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-link"></i>
                            </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Total de URLs Enviadas</p>
                                <h4 class="card-text text-end" id="add_urls">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-tv"></i>
                            </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Canais Adicionados</p>
                                <h4 class="card-text text-end" id="canais">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-film"></i>
                            </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Filmes Adicionados</p>
                                <h4 class="card-text text-end" id="filmes">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-video"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Séries Adicionadas</p>
                                <h4 class="card-text text-end" id="series_adicionando">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-grip-lines"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Temporadas Adicionadas</p>
                                <h4 class="card-text text-end" id="temporadas_adicionando">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-compact-disc"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Episódios Adicionados</p>
                                <h4 class="card-text text-end" id="episodios_adicionando">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-clone"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Conteúdo Duplicado</p>
                                <h4 class="card-text text-end" id="exitente">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-bug"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">Erros</p>
                                <h4 class="card-text text-end" id="Erro">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4">
                <div class="border-0 card h-100 rounded-0 shadow-sm">
                    <div class="card-body">
                        <div class="card-innerBody align-items-center">
                            <div class="float-start justify-content-center text-black-50">
                                <i class="fa-3x fa-solid fa-calendar-alt"></i> </div>
                            <div class="float-end ml-auto">
                                <p class="card-label small text-muted text-end">EPG Adicionado</p>
                                <h4 class="card-text text-end" id="epg_adicionando">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="completionModal" tabindex="-1" aria-labelledby="completionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completionModalLabel">Processamento Concluído</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    O processamento do arquivo foi concluído com sucesso.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_arquivo" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="align-content-center m-2 modal-body p-0 text-center" id="dropArea" style="border-radius: 3px; z-index: 1;">
                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; font-size: 33px; top: 5px; right: 5px; color: white;">
                        <span>×</span></button>
                    <h6>Arraste e solte o arquivo M3U aqui</h6>
                </div>
                <div class="align-content-center m-2 modal-body p-5" id="dropArea2" style="border-radius: 3px;">
                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; font-size: 33px; top: -13px; right: -33px; color: white;">
                        <span>×</span>
                    </button>
                    <h6 class="text-center">Arraste arquivos, ou importe de</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <button class="btn text-center w-100 h-100" id="btnSelectFile">
                                <input hidden type="file" class="form-control" id="m3uFile" accept=".m3u,audio/x-mpegurl">
                                <svg aria-hidden="true" focusable="false" fill="#607d8b" width="27" height="25" viewBox="0 0 27 25">
                                    <path d="M5.586 9.288a.313.313 0 0 0 .282.176h4.84v3.922c0 1.514 1.25 2.24 2.792 2.24 1.54 0 2.79-.726 2.79-2.24V9.464h4.84c.122 0 .23-.068.284-.176a.304.304 0 0 0-.046-.324L13.735.106a.316.316 0 0 0-.472 0l-7.63 8.857a.302.302 0 0 0-.047.325z"></path>
                                    <path d="M24.3 5.093c-.218-.76-.54-1.187-1.208-1.187h-4.856l1.018 1.18h3.948l2.043 11.038h-7.193v2.728H9.114v-2.725h-7.36l2.66-11.04h3.33l1.018-1.18H3.907c-.668 0-1.06.46-1.21 1.186L0 16.456v7.062C0 24.338.676 25 1.51 25h23.98c.833 0 1.51-.663 1.51-1.482v-7.062L24.3 5.093z"></path>
                                </svg>
                                <span>Meu Dispositivo</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn text-center w-100 h-100" id="openSecondModal">
                                <svg aria-hidden="true" focusable="false" width="23" height="23" viewBox="0 0 23 23">
                                    <path d="M20.485 11.236l-2.748 2.737c-.184.182-.367.365-.642.547-1.007.73-2.107 1.095-3.298 1.095-1.65 0-3.298-.73-4.398-2.19-.275-.365-.183-1.003.183-1.277.367-.273 1.008-.182 1.283.183 1.191 1.642 3.482 1.915 5.13.73a.714.714 0 0 0 .367-.365l2.75-2.737c1.373-1.46 1.373-3.74-.093-5.108a3.72 3.72 0 0 0-5.13 0L12.33 6.4a.888.888 0 0 1-1.283 0 .88.88 0 0 1 0-1.277l1.558-1.55a5.38 5.38 0 0 1 7.605 0c2.29 2.006 2.382 5.564.274 7.662zm-8.979 6.294L9.95 19.081a3.72 3.72 0 0 1-5.13 0c-1.467-1.368-1.467-3.74-.093-5.108l2.75-2.737.366-.365c.824-.547 1.74-.82 2.748-.73 1.008.183 1.833.639 2.382 1.46.275.365.917.456 1.283.182.367-.273.458-.912.183-1.277-.916-1.186-2.199-1.915-3.573-2.098-1.374-.273-2.84.091-4.031 1.004l-.55.547-2.749 2.737c-2.107 2.189-2.015 5.655.092 7.753C4.727 21.453 6.101 22 7.475 22c1.374 0 2.749-.547 3.848-1.55l1.558-1.551a.88.88 0 0 0 0-1.278c-.367-.364-1.008-.456-1.375-.09z" fill="#FF814F" fill-rule="nonzero"></path>
                                </svg>
                                <span>Link</span>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 mt-4">
                        <label class="form-label" for="contentType">Tipo de Conteúdo para Importar:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contentType" id="uploadAll" value="all" checked>
                            <label class="form-check-label" for="uploadAll">Todos</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contentType" id="uploadLive" value="live">
                            <label class="form-check-label" for="uploadLive">Canais (Ao Vivo)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contentType" id="uploadMovies" value="movie">
                            <label class="form-check-label" for="uploadMovies">Filmes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="contentType" id="uploadSeries" value="series">
                            <label class="form-check-label" for="uploadSeries">Séries</label>
                        </div>
                    </div>
                </div>
                <h6 class="text-center">Modificado por: Cyber Player</h6>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_url" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="d-flex justify-content-between modal-header text-center">
                    <button class="btn btn-sm btn-primary" id="backToFirstModal">Voltar</button>
                    <p class="position-absolute start-50 translate-middle" style="top: 28px; ">Importar por Link</p>
                </div>
                <div class="align-content-center m-2 modal-body p-5" id="dropArea2_url" style="border-radius: 3px;">
                    <button type="button" class="btn-close p-0" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; font-size: 33px; top: 5px; right: 5px; color: white;">
                        <span>×</span>
                    </button>
                    <div class="row text-center">
                        <div class="mb-3">
                            <input type="url" class="form-control" id="m3uUrl" placeholder="Digite a URL do arquivo .m3u">
                        </div>
                        <div class="mb-3 mt-4">
                            <label class="form-label" for="contentTypeUrl">Tipo de Conteúdo para Importar:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contentTypeUrl" id="uploadAllUrl" value="all" checked>
                                <label class="form-check-label" for="uploadAllUrl">Todos</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contentTypeUrl" id="uploadLiveUrl" value="live">
                                <label class="form-check-label" for="uploadLiveUrl">Canais (Ao Vivo)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contentTypeUrl" id="uploadMoviesUrl" value="movie">
                                <label class="form-check-label" for="uploadMoviesUrl">Filmes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="contentTypeUrl" id="uploadSeriesUrl" value="series">
                                <label class="form-check-label" for="uploadSeriesUrl">Séries</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary" id="processFileBtn">Importar</button>
                        </div>
                        <p id="result"></p>
                    </div>
                </div>
                <h6 class="text-center">Modificado por: Cyber Player</h6>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/uploud.js"></script>
</body>
</html>