<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure checkLogout.php is correctly included.
if (file_exists('./api/controles/checkLogout.php')) {
    require_once('./api/controles/checkLogout.php');
    checkLogout(); // This should handle session validation
} else {
    // error_log("Warning: checkLogout.php not found. Session/logout functionality may be affected.");
}

if (isset($_GET['sair'])) {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_unset();
    session_destroy();

    header('Location: ./index.php');
    exit();
}

// Initialize session variables with defaults if not set
$username = $_SESSION['username'] ?? 'Usuário';
$nivel_admin = (int)($_SESSION['nivel_admin'] ?? 0);
$plano_admin = (int)($_SESSION['plano_admin'] ?? 1);
if ($nivel_admin === 1) {
    $creditos = '∞'; // Infinity symbol for admin
} else {
    $creditos = $_SESSION['creditos'] ?? 0;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CYBER PLAYER</title>
    <link rel="shortcut icon" href="./img/icon.png">
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" type="text/css" href="css/menu.css">

    <style>
        /* css/futuristic-theme.css EMBEDDED */
        :root {
            --futuristic-bg: #101217;
            --futuristic-sidebar-bg: #1a1d24;
            --futuristic-header-bg: #1a1d24;
            --futuristic-text: #c0c0d0;
            --futuristic-text-muted: #707080;
            --futuristic-accent: #00aeff;
            --futuristic-accent-hover: #00c6ff;
            --futuristic-border: rgba(0, 174, 255, 0.15);
            --futuristic-active-bg: rgba(0, 174, 255, 0.1);
            --futuristic-font: 'Inter', 'Segoe UI', Roboto, sans-serif;
            --sidebar-width-collapsed: 70px;
            --sidebar-width-expanded: 250px;
            --header-height: 3.75em;
            --border-radius: 6px;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: var(--futuristic-font);
            background-color: var(--futuristic-bg);
            color: var(--futuristic-text);
            margin: 0;
            padding-top: var(--header-height);
            padding-left: var(--sidebar-width-collapsed);
            transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 400;
        }
        

        .main-header {
            background-color: var(--futuristic-header-bg);
            height: var(--header-height);
            border-bottom: 1px solid var(--futuristic-border);
            padding: 0 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .main-header .navbar-brand .logo {
            transition: transform 0.3s ease;
        }
        .main-header .navbar-brand .text-logo {
            color: var(--futuristic-text);
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }
        .main-header .menu-toggle-btn {
            color: var(--futuristic-accent);
            border: none;
            font-size: 1.2rem;
            padding: 0.5rem 0.75rem;
        }
        .main-header .menu-toggle-btn:hover,
        .main-header .menu-toggle-btn:focus {
            color: var(--futuristic-accent-hover);
            background-color: rgba(0, 174, 255, 0.1);
            box-shadow: none;
        }
        .main-header .header-profile-user {
            width: 32px;
            height: 32px;
            border: 2px solid var(--futuristic-accent);
        }
        .main-header .dropdown-toggle {
            color: var(--futuristic-text);
        }
        .main-header .dropdown-toggle::after {
            color: var(--futuristic-accent);
        }
        .main-header .dropdown-menu {
            background-color: #232730;
            border: 1px solid var(--futuristic-border);
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            padding: 0.5rem 0;
        }
        .main-header .dropdown-item {
            color: var(--futuristic-text);
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .main-header .dropdown-item:hover,
        .main-header .dropdown-item:focus {
            background-color: var(--futuristic-active-bg);
            color: var(--futuristic-accent-hover);
        }
        .main-header .dropdown-item i {
            opacity: 0.8;
        }
        .main-header .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.7em;
            font-weight: 500;
        }

        .navigation-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width-collapsed);
            height: calc(100vh - var(--header-height));
            background-color: var(--futuristic-sidebar-bg);
            border-right: 1px solid var(--futuristic-border);
            overflow-x: hidden;
            overflow-y: auto;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1020;
            scrollbar-width: thin;
            scrollbar-color: var(--futuristic-accent) transparent;
        }
        .navigation-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .navigation-sidebar::-webkit-scrollbar-thumb {
            background-color: var(--futuristic-accent);
            border-radius: 3px;
        }
        .navigation-sidebar::-webkit-scrollbar-track {
            background-color: transparent;
        }

        body.sidebar-expanded .navigation-sidebar {
            width: var(--sidebar-width-expanded);
        }
        body.sidebar-expanded {
            padding-left: var(--sidebar-width-expanded);
        }

        .sidebar-profile {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid var(--futuristic-border);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
            transform: translateY(-10px);
        }
        body.sidebar-expanded .sidebar-profile {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition-delay: 0.1s;
        }
        .sidebar-profile .profile-pic-wrapper img {
            width: 70px;
            height: 70px;
            border: 3px solid var(--futuristic-accent);
            box-shadow: 0 0 15px rgba(0, 174, 255, 0.3);
        }
        .sidebar-profile .profile-name {
            color: var(--futuristic-text);
            font-size: 1rem;
            font-weight: 600;
        }
        .sidebar-profile .profile-role {
            color: var(--futuristic-accent);
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        .sidebar-profile .stat-item {
            font-size: 0.8rem;
            margin-top: 8px;
            color: var(--futuristic-text-muted);
        }
        .sidebar-profile .stat-item .badge {
            font-weight: 500;
        }

        .nav-menu {
            padding-top: 10px;
        }

        .nav-item .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 23px;
            color: var(--futuristic-text-muted);
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            transition: background-color 0.2s ease, color 0.2s ease, padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 48px;
            position: relative;
        }

        body.sidebar-expanded .nav-item .nav-link {
             padding: 10px 20px;
        }

        .nav-item .nav-link .icon {
            font-size: 1.1rem;
            min-width: 24px;
            text-align: center;
            transition: margin-right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-right: 0;
        }
        body.sidebar-expanded .nav-item .nav-link .icon {
            margin-right: 15px;
        }

        .nav-item .nav-link .text {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out 0.05s, visibility 0.2s ease-in-out 0.05s;
            font-weight: 500;
        }
        body.sidebar-expanded .nav-item .nav-link .text {
            opacity: 1;
            visibility: visible;
        }

        .nav-item .nav-link:hover,
        .nav-item.active > .nav-link {
            color: var(--futuristic-accent-hover);
            background-color: var(--futuristic-active-bg);
        }
        .nav-item.active > .nav-link {
            font-weight: 600;
        }

        .nav-item .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0;
            width: 4px;
            background-color: var(--futuristic-accent);
            transition: height 0.2s ease;
            border-top-right-radius: 2px;
            border-bottom-right-radius: 2px;
        }
        .nav-item.active > .nav-link::before {
            height: 70%;
        }
        .nav-item .nav-link:hover::before {
            height: 50%;
        }

        .nav-item.has-submenu > .nav-link .submenu-arrow {
            margin-left: auto;
            transition: transform 0.3s ease, opacity 0.2s ease-in-out 0.05s;
            opacity: 0;
            visibility: hidden;
            font-size: 0.8em;
        }
        body.sidebar-expanded .nav-item.has-submenu > .nav-link .submenu-arrow {
             opacity: 0.7;
             visibility: visible;
        }
        .nav-item.has-submenu.open > .nav-link .submenu-arrow {
            transform: rotate(90deg);
            opacity: 1;
        }

        .submenu {
            list-style: none;
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: rgba(0,0,0,0.15);
        }
        body.sidebar-expanded .submenu {
            padding-left: 24px;
        }

        .nav-item.has-submenu.open > .submenu {
            max-height: 1000px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .submenu .nav-link {
            padding-top: 8px;
            padding-bottom: 8px;
            font-size: 0.88rem;
            height: auto;
            color: var(--futuristic-text-muted);
        }
        body.sidebar-expanded .submenu .nav-link {
            padding-left: 35px;
        }
        .submenu .nav-item .nav-link .icon {
            font-size: 0.9em;
            min-width: 20px;
        }
        body.sidebar-expanded .submenu .nav-item .nav-link .icon {
            margin-right: 12px;
        }

        .submenu .nav-link:hover,
        .submenu .nav-item.active > .nav-link {
            color: var(--futuristic-accent);
            background-color: rgba(0, 174, 255, 0.05);
        }
        .submenu .nav-item.active > .nav-link {
            font-weight: 500;
        }
        .submenu .nav-item .nav-link::before {
            display: none;
        }
        body.sidebar-expanded .submenu .nav-item.active > .nav-link::before {
            display: block;
            height: 6px; width: 6px; border-radius: 50%; left: 15px;
            top: 50%; transform: translateY(-50%);
        }

        .page-content {
            padding: 0;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (max-width: 991.98px) {
            body:not(.sidebar-expanded-persist) {
                padding-left: 0 !important;
            }
            body:not(.sidebar-expanded-persist) .navigation-sidebar {
                left: calc(-1 * var(--sidebar-width-expanded));
                width: var(--sidebar-width-expanded);
                box-shadow: 5px 0 15px rgba(0,0,0,0.2);
            }
            body.sidebar-expanded .navigation-sidebar {
                left: 0;
            }
            body.sidebar-expanded::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 1019;
            }
        }
    </style>

</head>

<body>
    <header class="fixed-top navbar navbar-expand-lg main-header">
        <div class="container-fluid header-container">
            <div class="d-flex align-items-center">
                <button class="btn btn-icon-only me-2 menu-toggle-btn" type="button" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                    <img alt="lightning logo" src="./img/logo.png" width="35" height="35" class="logo me-2">
                    <span class="text-logo d-none d-sm-inline">CYBER PLAYER</span>
                </a>
            </div>
            <div class="d-flex align-items-center">
                <div class="badge bg-success">
            <span class="j_credits" style="margin-right: 5px; opacity: 1;" id="creditos"> </span> Créditos
			</div>
                <div class="dropdown">
                    <button class="btn header-item waves-effect dropdown-toggle" type="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="./img/logo.png" alt="<?php echo htmlspecialchars($username); ?>">
                        <span class="d-none d-xl-inline-block ms-1"> <?php echo htmlspecialchars($username); ?> </span>
                        <i class="fas fa-chevron-down d-none d-xl-inline-block ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <?php if ($nivel_admin === 1) : ?>
                            <li><a class="dropdown-item" href="#" onclick='modal_master("api/revendedores.php", "edite_admin", "edite")'><i class="fas fa-user-shield me-2"></i>Editar Admin</a></li>
                        <?php elseif ($nivel_admin === 0) : ?>
                            <li><a class="dropdown-item" href="#" onclick='modal_master("api/revendedores.php", "edite_admin_revenda", "edite")'><i class="fas fa-key me-2"></i>Editar Senha</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="?sair"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <nav class="navigation-sidebar">
        <div class="sidebar-profile">
            <div class="profile-pic-wrapper">
                <img class="img-fluid rounded-circle" src="./img/logo.png" alt="User Profile">
            </div>
            <h5 class="profile-name text-uppercase mt-2 mb-0"><?php echo htmlspecialchars($username); ?></h5>
            <small class="profile-role text-uppercase" id="tipo_admin"><?php echo ($nivel_admin === 1) ? 'Administrador' : 'Revendedor'; ?></small>
            <div class="profile-stats mt-2">
                <div class="stat-item">
                    
                <div class="stat-item" id="vencimento-sidebar">
                    </div>
            </div>
        </div>

        <ul class="nav-menu list-unstyled">
            <li class="nav-item" data-page-id="dashboard">
                <a href="dashboard.php" class="nav-link">
                    <span class="icon"><i class="fas fa-chart-line"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <?php if ($nivel_admin === 1) : ?>
                <li class="nav-item has-submenu" data-parent-id="conteudos">
                    <a href="#" class="nav-link submenu-toggle">
                        <span class="icon"><i class="fas fa-boxes-stacked"></i></span>
                        <span class="text">Conteúdos</span>
                        <i class="fas fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="submenu list-unstyled">
                        <li class="nav-item" data-page-id="categorias"><a href="categorias.php" class="nav-link"><i class="fas fa-tags me-2"></i>Categorias</a></li>
                        <li class="nav-item" data-page-id="pacotes"><a href="pacotes.php" class="nav-link"><i class="fas fa-box-open me-2"></i>Pacotes</a></li>
                        <li class="nav-item" data-page-id="canais"><a href="canais.php" class="nav-link"><i class="fas fa-tv me-2"></i>Canais</a></li>
                        <li class="nav-item" data-page-id="filmes"><a href="filmes.php" class="nav-link"><i class="fas fa-film me-2"></i>Filmes</a></li>
                        <li class="nav-item" data-page-id="serie"><a href="serie.php" class="nav-link"><i class="fas fa-photo-film me-2"></i>Séries</a></li>
                        <li class="nav-item" data-page-id="uploud"><a href="uploud.php" class="nav-link"><i class="fas fa-upload me-2"></i>Upload</a></li>
                    </ul>
                </li>
                <li class="nav-item has-submenu" data-parent-id="ferramentas">
                    <a href="#" class="nav-link submenu-toggle">
                        <span class="icon"><i class="fas fa-tools"></i></span>
                        <span class="text">Ferramentas</span>
                        <i class="fas fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="submenu list-unstyled">
                        <li><a class="nav-link" href="#" onclick='modal_master("api/categorias.php", "delete_tudo", "tudo", "msg_info", "aguarde", "10000")'><i class="fas fa-trash-alt text-danger me-2"></i>Deletar Tudo</a></li>
                        <li><a class="nav-link" href="#" onclick='modal_master("api/categorias.php", "delete_tudo", "canais", "msg_info", "aguarde", "10000")'><i class="fas fa-trash-alt text-danger me-2"></i>Deletar Canais</a></li>
                        <li><a class="nav-link" href="#" onclick='modal_master("api/categorias.php", "delete_tudo", "filmes", "msg_info", "aguarde", "10000")'><i class="fas fa-trash-alt text-danger me-2"></i>Deletar Filmes</a></li>
                        <li><a class="nav-link" href="#" onclick='modal_master("api/categorias.php", "delete_tudo", "series", "msg_info", "aguarde", "10000")'><i class="fas fa-trash-alt text-danger me-2"></i>Deletar Séries</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="nav-item has-submenu" data-parent-id="clientes">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span class="text">Clientes</span>
                    <i class="fas fa-chevron-right submenu-arrow"></i>
                </a>
                <ul class="submenu list-unstyled">
                    <li class="nav-item" data-page-id="clientes"><a href="clientes.php" class="nav-link"><i class="fas fa-user-check me-2"></i>Clientes</a></li>
                    <li class="nav-item" data-page-id="testes"><a href="testes.php" class="nav-link"><i class="fas fa-user-clock me-2"></i>Testes</a></li>
                    <?php if ($plano_admin !== 1) : ?>
                        <li class="nav-item" data-page-id="revendedores"><a href="revendedores.php" class="nav-link"><i class="fas fa-user-tie me-2"></i>Revendedores</a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <li class="nav-item has-submenu" data-parent-id="configuracoes">
                <a href="#" class="nav-link submenu-toggle">
                    <span class="icon"><i class="fas fa-cogs"></i></span>
                    <span class="text">Configurações</span>
                    <i class="fas fa-chevron-right submenu-arrow"></i>
                </a>
                <ul class="submenu list-unstyled">
                    <li class="nav-item" data-page-id="planos"><a href="planos.php" class="nav-link"><i class="fas fa-box-open me-2"></i>Planos</a></li>
                </ul>
            </li>
            <?php if ($nivel_admin === 1) : ?>
                            <li class="nav-item" data-page-id="backup">
                    <a href="backup.php" class="nav-link" target="_blank" rel="noopener noreferrer">
                        <span class="icon"><i class="fas fa-database"></i></span>
                        <span class="text">Backup</span>
                    </a>
                </li>
            <?php endif; ?>    
            
        </ul>
    </nav>

        <!-- O conteúdo de cada página será inserido aqui -->
    </main>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <script src="//cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="./js/sweetalert2.js"></script>
    <script src="./js/custom.js"></script>

    <script>
        // js/menu.js EMBEDDED
        $(document).ready(function () {
            const body = $('body');
            const sidebar = $('.navigation-sidebar');
            const menuToggleBtn = $('.menu-toggle-btn');
            const pageContent = $('.page-content');

            const currentPageFile = window.location.pathname.split('/').pop().replace(/\.(php|html)$/, '');
            const sidebarExpandedKey = 'sidebarExpandedState';
            const sidebarExpandedPersistKey = 'sidebarExpandedPersistState';

            function setSidebarState(expanded, persist = false) {
                if (expanded) {
                    body.addClass('sidebar-expanded');
                    localStorage.setItem(sidebarExpandedKey, 'true');
                    if (persist) body.addClass('sidebar-expanded-persist');
                } else {
                    body.removeClass('sidebar-expanded');
                    localStorage.setItem(sidebarExpandedKey, 'false');
                    if (persist) body.removeClass('sidebar-expanded-persist');
                }
                if (persist) {
                    localStorage.setItem(sidebarExpandedPersistKey, expanded ? 'true' : 'false');
                }
            }

            menuToggleBtn.on('click', function (e) {
                e.preventDefault();
                const isCurrentlyExpanded = body.hasClass('sidebar-expanded');
                const persistToggle = window.innerWidth < 992;
                setSidebarState(!isCurrentlyExpanded, persistToggle);
            });

            body.on('click', function(e) {
                if (body.hasClass('sidebar-expanded') && window.innerWidth < 992) {
                    if ($(e.target).closest('.navigation-sidebar').length === 0 && $(e.target).closest('.menu-toggle-btn').length === 0) {
                         setSidebarState(false, true);
                    }
                }
            });

            let initialStateExpanded = localStorage.getItem(sidebarExpandedKey);
            let persistedState = localStorage.getItem(sidebarExpandedPersistKey);

            if (window.innerWidth < 992) {
                if (persistedState === 'true') {
                    setSidebarState(true);
                } else {
                    setSidebarState(false);
                }
            } else {
                body.removeClass('sidebar-expanded-persist');
                if (initialStateExpanded === 'false') {
                    setSidebarState(false);
                } else {
                    setSidebarState(true);
                }
            }

            function setActiveMenuItem() {
                $('.nav-menu .nav-item').removeClass('active open');
                let foundActive = false;
                let activeItemSelector = null;

                $('.nav-menu .submenu .nav-item').each(function () {
                    const $item = $(this);
                    if ($item.data('page-id') === currentPageFile) {
                        $item.addClass('active');
                        const $parentSubmenu = $item.closest('.has-submenu');
                        if ($parentSubmenu.length) {
                            $parentSubmenu.addClass('active open');
                        }
                        foundActive = true;
                        activeItemSelector = $item;
                        return false; 
                    }
                });
                
                if (!foundActive) {
                    $('.nav-menu > .nav-item:not(.has-submenu)').each(function() {
                        const $item = $(this);
                        if ($item.data('page-id') === currentPageFile) {
                            $item.addClass('active');
                            foundActive = true;
                            activeItemSelector = $item;
                            return false;
                        }
                    });
                }

                if (!foundActive) {
                     $('.nav-menu > .nav-item.has-submenu').each(function () {
                        const $parentItem = $(this);
                        const parentId = $parentItem.data('parent-id');
                        
                        const pageGroups = {
                            'conteudos': ['categorias', 'pacotes', 'canais', 'filmes', 'serie', 'uploud', 'divisor-m3u'],
                            'ferramentas': [],
                            'clientes': ['clientes', 'testes', 'revendedores', 'sub-revenda'],
                            'configuracoes': ['admin', 'servidores', 'planos']
                        };

                        if (pageGroups[parentId] && pageGroups[parentId].includes(currentPageFile)) {
                            $parentItem.addClass('active open');
                            $parentItem.find('.submenu .nav-item').each(function() {
                                if ($(this).data('page-id') === currentPageFile) {
                                    $(this).addClass('active');
                                    activeItemSelector = $(this);
                                    return false;
                                }
                            });
                            foundActive = true;
                            return false;
                        }
                    });
                }

                if (!foundActive && (currentPageFile === 'dashboard' || currentPageFile === '' || currentPageFile === 'index')) {
                    const $dashboardItem = $('.nav-menu .nav-item[data-page-id="dashboard"]');
                    $dashboardItem.addClass('active');
                    activeItemSelector = $dashboardItem;
                }
                
                if (activeItemSelector && activeItemSelector.length && sidebar.length && sidebar[0].scrollHeight > sidebar[0].clientHeight) {
                    const itemTop = activeItemSelector.position().top;
                    const itemHeight = activeItemSelector.outerHeight();
                    const sidebarScrollTop = sidebar.scrollTop();
                    const sidebarHeight = sidebar.height();

                    if (itemTop < 0 || (itemTop + itemHeight) > sidebarHeight) {
                         sidebar.animate({
                            scrollTop: sidebarScrollTop + itemTop - (sidebarHeight / 2) + (itemHeight / 2)
                        }, 300);
                    }
                }
            }

            setActiveMenuItem();

            $('.nav-menu .has-submenu > .submenu-toggle').on('click', function (e) {
                e.preventDefault();
                const $parentLi = $(this).closest('.has-submenu');
                const shouldOpen = !$parentLi.hasClass('open');

                if (!body.hasClass('sidebar-expanded')) {
                    setSidebarState(true, window.innerWidth < 992);
                    setTimeout(() => {
                        if (shouldOpen) {
                            openSubmenu($parentLi);
                        }
                    }, 250);
                } else {
                    if (shouldOpen) {
                        openSubmenu($parentLi);
                    } else {
                        closeSubmenu($parentLi);
                    }
                }
            });

            function openSubmenu($submenuParent) {
                $submenuParent.addClass('open');
            }

            function closeSubmenu($submenuParent) {
                $submenuParent.removeClass('open');
            }
        });
    </script>

</body>
</html>