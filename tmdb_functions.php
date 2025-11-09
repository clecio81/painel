<?php
require_once(__DIR__ . '/api/controles/db.php');
ob_start();

$pdo = conectar_bd();

function fetchTmdbData($tmdb_id) {
    $apiKey = '50dcb709df1cd8ab0d6399ea2de9c04e';
    $url = "https://api.themoviedb.org/3/movie/{$tmdb_id}?language=pt-BR&api_key={$apiKey}";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

function buscarTmdbIdPorNome($nome) {
    $apiKey = '50dcb709df1cd8ab0d6399ea2de9c04e';
    $query = urlencode($nome);
    $url = "https://api.themoviedb.org/3/search/movie?language=pt-BR&api_key={$apiKey}&query={$query}";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $dados = json_decode($response, true);
    return $dados['results'][0]['id'] ?? null;
}
function renderProgressBar() {
    // Limpa qualquer buffer existente
    while (ob_get_level() > 0) ob_end_clean();
    
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Atualizando Dados</title>
        <style>
            .progress-container {
                margin: 20px;
                font-family: Arial, sans-serif;
            }
            #status_text {
                margin-bottom: 10px;
            }
            .progress-bar {
                background-color: #ddd;
                border-radius: 5px;
                height: 20px;
                width: 100%;
                max-width: 600px;
                overflow: hidden;
            }
            #progress_bar {
                height: 100%;
                width: 0%;
                background-color: #4caf50;
                transition: width 0.3s;
            }
            .alert {
                padding: 15px;
                margin-top: 20px;
                border-radius: 4px;
            }
            .alert-success {
                background-color: #dff0d8;
                color: #3c763d;
            }
            .alert-info {
                background-color: #d9edf7;
                color: #31708f;
            }
        </style>
    </head>
    <body>
    <div class="progress-container">
        <div id="status_text">Progresso: 0</div>
        <div class="progress-bar">
            <div id="progress_bar"></div>
        </div>
    </div>
    <div id="result"></div>
HTML;
    ob_flush();
    flush();
}

if (isset($_POST['atualizar_dados'])) {
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
    }
    ini_set('zlib.output_compression', 0);
    ini_set('implicit_flush', 1);
    
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    $stmt = $pdo->query("SELECT id, tmdb_id FROM streams WHERE tmdb_id IS NOT NULL");
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($filmes);
    $atualizados = 0;

    renderProgressBar();

    foreach ($filmes as $index => $filme) {
        $dados = fetchTmdbData($filme['tmdb_id']);
        $percentual = round((($index + 1) / $total) * 100);

        if (!$dados || isset($dados['status_code'])) {
            echo "<script>
                document.getElementById('progress_bar').style.width = '{$percentual}%';
                document.getElementById('status_text').innerHTML = 'Progresso: ".($index+1)."/$total (Pulando - TMDB ID inválido)';
            </script>";
            ob_flush();
            flush();
            usleep(50000);
            continue;
        }

        $update = $pdo->prepare("UPDATE streams SET
            name = :titulo,
            description = :descricao,
            plot = :plot,
            backdrop_path = :banner,
            stream_icon = :poster,
            release_date = :ano,
            rating = :nota,
            duration = :duracao,
            genre = :generos
            WHERE id = :id
        ");

        $update->execute([
            ':titulo' => $dados['title'] ?? '',
            ':descricao' => $dados['overview'] ?? '',
            ':plot' => $dados['overview'] ?? '',
            ':banner' => 'https://image.tmdb.org/t/p/original' . ($dados['backdrop_path'] ?? ''),
            ':poster' => 'https://image.tmdb.org/t/p/w500' . ($dados['poster_path'] ?? ''),
            ':ano' => $dados['release_date'] ?? '',
            ':nota' => $dados['vote_average'] ?? 0,
            ':duracao' => $dados['runtime'] ?? 0,
            ':generos' => implode(', ', array_map(fn($g) => $g['name'], $dados['genres'] ?? [])),
            ':id' => $filme['id']
        ]);

        $atualizados++;
        echo "<script>
            document.getElementById('progress_bar').style.width = '{$percentual}%';
            document.getElementById('status_text').innerHTML = 'Progresso: ".($index+1)."/$total (Atualizando: ".addslashes($dados['title'] ?? '').")';
        </script>";
        ob_flush();
        flush();
        usleep(50000);
    }

    echo "<div class='alert alert-success' id='final-result'>Filmes atualizados com dados da TMDb: {$atualizados} de {$total}</div>";
    echo "</body></html>";
}

if (isset($_POST['atualizar_tmdb_id'])) {
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
    }
    ini_set('zlib.output_compression', 0);
    ini_set('implicit_flush', 1);
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    $stmt = $pdo->query("SELECT id, name FROM streams WHERE tmdb_id IS NULL OR tmdb_id = ''");
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($filmes);
    $encontrados = 0;

    renderProgressBar();

    foreach ($filmes as $index => $filme) {
        $novo_id = buscarTmdbIdPorNome($filme['name']);
        $percentual = round((($index + 1) / $total) * 100);

        if ($novo_id) {
            $update = $pdo->prepare("UPDATE streams SET tmdb_id = :tmdb_id WHERE id = :id");
            $update->execute([':tmdb_id' => $novo_id, ':id' => $filme['id']]);
            $encontrados++;
            echo "<script>
                document.getElementById('progress_bar').style.width = '{$percentual}%';
                document.getElementById('status_text').innerHTML = 'Progresso: ".($index+1)."/$total (Atualizando: ".addslashes($filme['name'])." - ID: $novo_id)';
            </script>";
        } else {
            echo "<script>
                document.getElementById('progress_bar').style.width = '{$percentual}%';
                document.getElementById('status_text').innerHTML = 'Progresso: ".($index+1)."/$total (Pulando: ".addslashes($filme['name'])." - Não encontrado)';
            </script>";
        }

        ob_flush();
        flush();
        usleep(50000); // 50ms
    }

    echo "<div class='alert alert-info' id='final-result'>TMDb IDs atualizados automaticamente: {$encontrados} de {$total}</div>";
    echo "</body></html>";
}
?>