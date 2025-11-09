<?php

namespace models;

require_once(__DIR__ . '/../api/controles/db.php');
require_once(__DIR__ . '/../api/controles/checkLogout.php');

checkLogoutapi();

class TMDB
{
    const APP_IMAGE_POSTER = "https://www.themoviedb.org/t/p/w300";
    const APP_IMAGE_BACKDROP = "https://www.themoviedb.org/t/p/w500";
    const TMDB_API_KEY = '66d600a2e10bb528752724cddadf6f8c';


    public static function getSerie($tmdbId)
    {
        $url = "https://api.themoviedb.org/3/tv/{$tmdbId}?api_key=" . self::TMDB_API_KEY . "&language=pt-BR";
        $data = self::curlGETResponse($url);

        $genreNames = [];
        foreach ($data['genres'] as $genre) {
            $genreNames[] = $genre['name'];
        }
        $creditsUrl = "https://api.themoviedb.org/3/tv/{$tmdbId}/credits?api_key=" . self::TMDB_API_KEY . "&language=pt-BR";
        $creditsJson = file_get_contents($creditsUrl);
        $creditsData = json_decode($creditsJson, true);

        $castNames = array_column(array_slice($creditsData["cast"], 0, 5), "name");
        $cast = implode(", ", $castNames);

        $directorNames = array_column(array_slice($creditsData["crew"], 0, 5), "name");
        $directors = implode(", ", $directorNames);

        return [
            'tmdb_id' => $tmdbId,
            'nome' => $data['name'],
            'director' => $directors,
            'year' => date("Y", strtotime($data["first_air_date"])),
            'releasedate' => $data["first_air_date"],
            'plot' => $data['overview'],
            'logo' => self::APP_IMAGE_POSTER . $data['poster_path'],
            'rating_5based' => 5,
            'cast' => $cast,
            'rating' => $data['vote_average'] ?? 4,
            'original_name' => $data["original_name"] ?? $data['name'],
            'genre' => implode(',', $genreNames ?? []),
            'backdrop_path' => empty($data['backdrop_path']) ? self::APP_IMAGE_BACKDROP . $data['poster_path'] : self::APP_IMAGE_BACKDROP . $data['backdrop_path'],
        ];
    }

    public static function getFilme($tmdbId)
    {
        $url = "https://api.themoviedb.org/3/movie/{$tmdbId}?api_key=" . self::TMDB_API_KEY . "&language=pt-BR";
        $data = self::curlGETResponse($url);

        $genreNames = [];
        foreach ($data['genres'] as $genre) {
            $genreNames[] = $genre['name'];
        }
        $creditsUrl = "https://api.themoviedb.org/3/movie/{$tmdbId}/credits?api_key=" . self::TMDB_API_KEY . "&language=pt-BR";
        $creditsJson = file_get_contents($creditsUrl);
        $creditsData = json_decode($creditsJson, true);

        $castNames = array_column(array_slice($creditsData["cast"], 0, 5), "name");
        $cast = implode(", ", $castNames);

        $directorNames = array_column(array_slice($creditsData["crew"], 0, 5), "name");
        $directors = implode(", ", $directorNames);
        $minutes = isset($data["runtime"]) && is_numeric($data["runtime"]) ? (int)$data["runtime"] : 0;
        $duration = gmdate("H:i:s", $minutes * 60);
        
        return [
            'tmdb_id' => $tmdbId,
            'nome' => $data['title'],
            'director' => $directors,
            'year' => date("Y", strtotime($data["release_date"])),
            'releasedate' => $data["release_date"],
            'original_title' => $data["original_title"] ?? $data['title'],
            'plot' => $data['overview'],
            'logo' => self::APP_IMAGE_POSTER . $data['poster_path'],
            'rating_5based' => 5,
            'actors' => $cast,
            'duration' => $duration,
            'rating' => $data['vote_average'] ?? 4,
            'genre' => implode(',', $genreNames ?? []),
            'backdrop_path' => empty($data['backdrop_path']) ? self::APP_IMAGE_BACKDROP . $data['poster_path'] : self::APP_IMAGE_BACKDROP . $data['backdrop_path'],
        ];
    }

    private static function curlGETResponse($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}