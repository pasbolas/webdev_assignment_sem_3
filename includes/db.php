<?php
    $host = 'localhost';
    $db   = 'librarydb';
    $user = 'root';
    $pass = '';


    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    // i used this tutorial for pdo https://phpdelusions.net/pdo btw
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }

?>
