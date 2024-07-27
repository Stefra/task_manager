<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/task_manager');
}

// Détecter l'environnement (local ou Heroku)
if (getenv('JAWSDB_URL')) {
    $dbparts = parse_url(getenv('JAWSDB_URL'));

    $hostname = $dbparts['host'];
    $username = $dbparts['user'];
    $password = $dbparts['pass'];
    $database = ltrim($dbparts['path'], '/');
} else {
    // Configuration locale
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'task_manager';
}

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}
?>
