<?php
session_start();
require_once('./controles/db.php');
require_once('./controles/series.php');
require_once('./controles/checkLogout.php');
require_once('../models/TMDB.php');
require_once('../functions.php');
header('Content-Type: application/json; charset=utf-8');

checkLogoutapi();
$pdo = conectar_bd();

if (isset($_GET['get_series'])) {
    $tmdbId = $_GET['get_series'];
    echo json_encode(\models\TMDB::getSerie($tmdbId));
    exit();
}

if (isset($_GET['get_filmes'])) {
    $tmdbId = $_GET['get_filmes'];
    echo json_encode(\models\TMDB::getFilme($tmdbId));
    exit();
}
if ($_GET['action'] === 'listar') {
    try {
        $stmt = $pdo->query("SELECT id, name FROM streams WHERE tmdb_id IS NULL");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

if ($_GET['action'] === 'atualizar' && isset($_POST['id'], $_POST['tmdb_id'])) {
    $stmt = $pdo->prepare("UPDATE streams SET tmdb_id = ? WHERE id = ?");
    $stmt->execute([$_POST['tmdb_id'], $_POST['id']]);
    echo json_encode(['success' => true]);
    exit();

}
if ($_GET['action'] === 'atualizar_infos') {
    header('Content-Type: application/json');

    try {
        $stmt = $pdo->query("SELECT id, tmdb_id FROM streams WHERE tmdb_id IS NOT NULL");
        $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($filmes);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}
if ($_GET['action'] === 'salvar_detalhes') {
    $stmt = $pdo->prepare("
        UPDATE streams SET 
            name = ?, 
            description = ?, 
            plot = ?, 
            stream_icon = ?, 
            backdrop_path = ?, 
            releaseDate = ?, 
            duration = ?, 
            duration_secs = ?, 
            rating = ?, 
            rating_5based = ?, 
            age = ?, 
            year = ?, 
            actors = ?, 
            genre = ?, 
            country = ?, 
            director = ?, 
            youtube_trailer = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['titulo'],
        $_POST['description'],
        $_POST['plot'],
        $_POST['stream_icon'],
        $_POST['backdrop_path'],
        $_POST['release_date'],
        $_POST['duration'],
        $_POST['duration_secs'],
        $_POST['rating'],
        $_POST['rating_5based'],
        $_POST['age'],
        $_POST['year'],
        $_POST['actors'],
        $_POST['genre'],
        $_POST['country'],
        $_POST['director'],
        $_POST['youtube_trailer'],
        $_POST['id']
    ]);

    echo json_encode(['success' => true]);
    exit();
}