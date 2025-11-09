<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Atualizar Filmes TMDb</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background: #f0f4f8;
      font-family: "Segoe UI", sans-serif;
      padding: 40px;
    }
    .container {
      margin-top: 60px;
      max-width: 800px;
      height: 500;

    }
    .card {
      border: none;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      border-radius: 16px;
    }
    .btn-primary, .btn-secondary {
      border-radius: 30px;
      padding: 10px 30px;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4">
    <h2 class="mb-4 text-center">Atualizar Informações da TMDb</h2>
    <form id="tmdbForm">
      <p>Escolha uma das opções abaixo para atualizar os dados dos filmes registrados.</p>
      <div class="text-center mt-4 d-grid gap-3">
        <button type="button" id="atualizar_tmdb_id" class="btn btn-secondary">🔍 Atualizar TMDb ID</button>
        <button type="button" id="atualizar_dados" class="btn btn-primary">🎬 Atualizar Filmes</button>
      </div>
    </form>
    <div id="result"></div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#atualizar_tmdb_id').click(function() {
      $.post('tmdb_functions.php', { atualizar_tmdb_id: true }, function(response) {
        $('#result').html(response);
      });
    });

    $('#atualizar_dados').click(function() {
      $.post('tmdb_functions.php', { atualizar_dados: true }, function(response) {
        $('#result').html(response);
      });
    });
  });
</script>

</body>
</html>