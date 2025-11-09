<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Elaxi</title>
  <link rel="shortcut icon" href="./img/icon.png">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts - Poppins para tipografia moderna -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    :root {
      --primary-purple: #6366f1;
      --primary-purple-dark: #4f46e5;
      --secondary-purple: #8b5cf6;
      --accent-green: #10b981;
      --accent-yellow: #f59e0b;
      --accent-pink: #ec4899;
      
      --bg-dark: #0f0f23;
      --bg-card: #1a1a2e;
      --bg-input: #16213e;
      --bg-overlay: rgba(26, 26, 46, 0.95);
      
      --text-white: #ffffff;
      --text-light: #e2e8f0;
      --text-muted: #94a3b8;
      --text-dark: #1e293b;
      
      --border-light: rgba(255, 255, 255, 0.1);
      --border-accent: rgba(99, 102, 241, 0.3);
      
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
      
      --radius-sm: 0.375rem;
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --radius-xl: 1rem;
      --radius-2xl: 1.5rem;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 50%, var(--bg-dark) 100%);
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
      color: var(--text-white);
      line-height: 1.6;
    }

    /* Loading Screen */
    .loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    .loading-screen.hidden {
      opacity: 0;
      visibility: hidden;
    }

    .loading-logo {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
      border-radius: var(--radius-xl);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2rem;
      box-shadow: var(--shadow-glow);
      animation: logoFloat 2s ease-in-out infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 3px solid rgba(255, 255, 255, 0.1);
      border-top: 3px solid var(--primary-purple);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 1rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-text {
      color: var(--text-light);
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .loading-subtext {
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    /* Animated background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 60%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
      animation: backgroundPulse 15s ease-in-out infinite;
      z-index: -1;
    }

    @keyframes backgroundPulse {
      0%, 100% { opacity: 0.5; transform: scale(1); }
      50% { opacity: 0.8; transform: scale(1.05); }
    }

    /* Grid pattern */
    .grid-pattern {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0.03;
      background-image: 
        linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
      background-size: 50px 50px;
      z-index: -1;
      animation: gridMove 20s linear infinite;
    }

    @keyframes gridMove {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }

    .main-content {
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .main-content.visible {
      opacity: 1;
    }

    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      position: relative;
    }

    .login-card {
      background: var(--bg-overlay);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-2xl);
      backdrop-filter: blur(20px);
      box-shadow: var(--shadow-xl), var(--shadow-glow);
      padding: 3rem;
      width: 100%;
      max-width: 450px;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple), var(--accent-green));
      border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
    }

    .login-card:hover {
      border-color: var(--border-accent);
      transform: translateY(-5px);
      box-shadow: var(--shadow-xl), 0 0 30px rgba(99, 102, 241, 0.4);
    }

    .logo-section {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .logo-container {
      display: inline-flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .logo-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
      position: relative;
    }

    .logo-icon::after {
      content: '';
      position: absolute;
      inset: -2px;
      background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
      border-radius: var(--radius-lg);
      z-index: -1;
      opacity: 0.3;
      filter: blur(8px);
    }

    .logo-text {
      font-size: 2rem;
      font-weight: 800;
      background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.025em;
    }

    .logo-subtitle {
      color: var(--text-muted);
      font-size: 0.875rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    .welcome-section {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .welcome-title {
      color: var(--text-white);
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      letter-spacing: -0.025em;
    }

    .welcome-subtitle {
      color: var(--text-muted);
      font-size: 0.95rem;
      line-height: 1.6;
      max-width: 320px;
      margin: 0 auto;
    }

    .form-group {
      margin-bottom: 1.75rem;
      position: relative;
    }

    .form-label {
      color: var(--text-light);
      font-weight: 600;
      margin-bottom: 0.75rem;
      font-size: 0.9rem;
      display: block;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .form-control {
      background: var(--bg-input);
      border: 2px solid var(--border-light);
      border-radius: var(--radius-lg);
      color: var(--text-white);
      padding: 1rem 1.25rem;
      font-size: 0.95rem;
      width: 100%;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      font-weig      /* background: rgba(22, 33, 62, 0.8); */
      /* border-color: var(--primary-purple); */
      /* box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 0 20px rgba(99, 102, 241, 0.2); */
      /* outline: none; */
      /* transform: translateY(-2px); */}

    .form-control::placeholder {
      color: var(--text-muted);
      font-weight: 400;
    }

    .password-container {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-muted);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: var(--radius-sm);
      transition: all 0.3s ease;
    }

    .password-toggle:hover {
      color: var(--primary-purple);
      background: rgba(99, 102, 241, 0.1);
      transform: translateY(-50%) scale(1.1);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-dark));
      border: none;
      border-radius: var(--radius-lg);
      color: var(--text-white);
      font-weight: 700;
      padding: 1rem 2rem;
      font-size: 1rem;
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-xl), 0 0 25px rgba(99, 102, 241, 0.4);
      background: linear-gradient(135deg, var(--primary-purple-dark), var(--secondary-purple));
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:active {
      transform: translateY(-1px);
    }

    .btn-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    .btn-loading {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
    }

    .btn-spinner {
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(15, 15, 35, 0.95);
      backdrop-filter: blur(20px);
      border-top: 1px solid var(--border-light);
      padding: 1.25rem 0;
      z-index: 100;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .footer-brand {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .footer-logo {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
    }

    .footer-text {
      color: var(--text-white);
      font-weight: 700;
      font-size: 1rem;
    }

    .footer-links {
      display: flex;
      gap: 2rem;
      align-items: center;
    }

    .footer-link {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: var(--radius-md);
    }

    .footer-link:hover {
      color: var(--primary-purple);
      background: rgba(99, 102, 241, 0.1);
      transform: translateY(-2px);
    }

    .copyright {
      color: var(--text-muted);
      font-size: 0.8rem;
      text-align: center;
      width: 100%;
      margin-top: 1rem;
      font-weight: 400;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .auth-container {
        padding: 1rem;
      }
      
      .login-card {
        padding: 2rem;
      }
      
      .logo-text {
        font-size: 1.75rem;
      }
      
      .welcome-title {
        font-size: 1.5rem;
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
        padding: 0 1rem;
      }
      
      .footer-links {
        justify-content: center;
        gap: 1rem;
      }
    }

    /* Accessibility */
    .form-control:focus,
    .btn-login:focus,
    .password-toggle:focus,
    .footer-link:focus {
      outline: 2px solid var(--primary-purple);
      outline-offset: 2px;
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
      :root {
        --border-light: rgba(255, 255, 255, 0.3);
        --border-accent: rgba(99, 102, 241, 0.6);
      }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>

<body>
  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="loading-logo">
      <i class="fas fa-server" style="color: white; font-size: 32px;"></i>
    </div>
    <div class="loading-spinner"></div>
    <div class="loading-text">Carregando Sistema</div>
    <div class="loading-subtext">Aguarde enquanto preparamos tudo para você...</div>
  </div>

  <div class="grid-pattern"></div>
  
  <div class="main-content" id="mainContent">
    <div class="auth-container">
      <div class="login-card">
        <div class="logo-section">
          <div class="logo-container">
            <div class="logo-icon">
              <i class="fas fa-server" style="color: white; font-size: 24px;"></i>
            </div>
          <div class="logo-text">Elaxi</div>
          </div>
          <div class="logo-subtitle">Sistema de Controle Premium</div>
        </div>
        
        <div class="welcome-section">
          <h1 class="welcome-title">Acesso Restrito</h1>
          <p class="welcome-subtitle">Painel exclusivo para administradores e revendedores autorizados do sistema</p>
        </div>

        <form id="login_form" onsubmit="event.preventDefault();">
          <input name="login" type="hidden" id="login" value="1">
          
          <div class="form-group">
            <label for="username" class="form-label">Usuário</label>
            <input 
              name="username" 
              type="text" 
              class="form-control" 
              id="username" 
              placeholder="Digite seu nome de usuário"
              required
              autocomplete="username"
            >
          </div>
          
          <div class="form-group">
            <label for="password-input" class="form-label">Senha</label>
            <div class="password-container">
              <input 
                name="password" 
                type="password" 
                class="form-control" 
                id="password-input" 
                placeholder="Digite sua senha de acesso"
                required
                autocomplete="current-password"
              >
              <button 
                type="button" 
                class="password-toggle" 
                id="password-toggle"
                onclick="togglePassword()"
                aria-label="Mostrar/ocultar senha"
              >
                <i class="fas fa-eye" id="eye-icon"></i>
              </button>
            </div>
          </div>
          
          <button type="submit" class="btn-login" onclick="enviardados('login_form')" id="login-btn">
            <span id="btn-text">Acessar Sistema</span>
            <span id="btn-loading" class="btn-loading" style="display: none;">
              <span class="btn-spinner"></span>
              Autenticando...
            </span>
          </button>
        </form>
      </div>
    </div>


  </div>

  <script>
    var solicitacaoPendente = false;

    // Loading screen management
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.getElementById('loadingScreen').classList.add('hidden');
        document.getElementById('mainContent').classList.add('visible');
      }, 4000); // 4 seconds loading time
    });

    function togglePassword() {
      const passwordInput = document.getElementById('password-input');
      const eyeIcon = document.getElementById('eye-icon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }

    function enviardados(id_formulario) {
      if (solicitacaoPendente) {
        SweetAlert2('Aguarde! Processando solicitação anterior.', 'warning');
        return;
      }

      // Enhanced validation
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password-input').value;
      
      if (!username) {
        SweetAlert2('Por favor, digite seu nome de usuário.', 'warning');
        document.getElementById('username').focus();
        return;
      }
      
      if (!password) {
        SweetAlert2('Por favor, digite sua senha de acesso.', 'warning');
        document.getElementById('password-input').focus();
        return;
      }

      if (username.length < 3) {
        SweetAlert2('Nome de usuário deve ter pelo menos 3 caracteres.', 'warning');
        document.getElementById('username').focus();
        return;
      }

      solicitacaoPendente = true;
      
      // Show loading state
      const loginBtn = document.getElementById('login-btn');
      loginBtn.disabled = true;
      document.getElementById('btn-text').style.display = 'none';
      document.getElementById('btn-loading').style.display = 'inline-flex';

      // Serialize form data
      var dados = $("#" + id_formulario).serialize();
      
      $.ajax({
        type: "GET",
        url: "./api/login.php",
        data: dados,
        timeout: 15000, // 15 seconds timeout
        success: function(response) {
          if (response.trim() === '') {
            SweetAlert2('Resposta do servidor vazia. Tente novamente.', 'error');
          } else {
            try {
              var jsonResponse = JSON.parse(response);
              SweetAlert2(jsonResponse.title, jsonResponse.icon);
              
              if (jsonResponse.url && jsonResponse.icon !== 'error') {
                setTimeout(function() {
                  window.location.href = jsonResponse.url;
                }, parseInt(jsonResponse.time, 10) || 2000);
              }
            } catch (e) {
              console.error('Erro ao processar resposta:', e);
              SweetAlert2('Erro ao processar resposta do servidor.', 'error');
            }
          }
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição:', status, error);
          if (status === 'timeout') {
            SweetAlert2('Tempo limite excedido. Verifique sua conexão e tente novamente.', 'error');
          } else if (xhr.status === 0) {
            SweetAlert2('Erro de conexão. Verifique sua internet.', 'error');
          } else {
            SweetAlert2('Erro na comunicação com o servidor. Tente novamente.', 'error');
          }
        },
        complete: function() {
          solicitacaoPendente = false;
          // Hide loading state
          const loginBtn = document.getElementById('login-btn');
          loginBtn.disabled = false;
          document.getElementById('btn-text').style.display = 'inline';
          document.getElementById('btn-loading').style.display = 'none';
        }
      });
    }

    function SweetAlert2(title, icon) {
      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: 'rgba(26, 26, 46, 0.95)',
        color: '#ffffff',
        iconColor: getIconColor(icon),
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });
      
      Toast.fire({
        icon: icon,
        title: title
      });
    }

    function getIconColor(icon) {
      switch(icon) {
        case 'success': return '#10b981';
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        case 'info': return '#6366f1';
        default: return '#6366f1';
      }
    }

    // Enhanced form handling
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('login_form');
      const inputs = form.querySelectorAll('input[required]');
      
      // Real-time validation
      inputs.forEach(input => {
        input.addEventListener('blur', function() {
          validateField(this);
        });
        
        input.addEventListener('input', function() {
          if (this.classList.contains('is-invalid')) {
            validateField(this);
          }
        });
      });
      
      // Enter key submission
      form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          enviardados('login_form');
        }
      });

      // Auto-focus on first input after loading
      setTimeout(function() {
        document.getElementById('username').focus();
      }, 2500);
    });

    function validateField(field) {
      const value = field.value.trim();
      
      if (!value) {
        field.classList.add('is-invalid');
        field.style.borderColor = '#ef4444';
        field.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
        return false;
      } else {
        field.classList.remove('is-invalid');
        field.style.borderColor = '';
        field.style.boxShadow = '';
        return true;
      }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      // ESC key to clear form
      if (e.key === 'Escape') {
        document.getElementById('login_form').reset();
        document.getElementById('username').focus();
      }
      
      // Ctrl+Enter to submit
      if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        enviardados('login_form');
      }
    });

    // Prevent multiple rapid submissions
    document.getElementById('login_form').addEventListener('submit', function(e) {
      e.preventDefault();
      if (!solicitacaoPendente) {
        enviardados('login_form');
      }
    });
  </script>
</body>
</html>