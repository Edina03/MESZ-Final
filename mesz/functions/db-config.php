<?php
$dsn = 'mysql:host=localhost;dbname=mesz';
$username = 'mesz';
$password = 'pVg3iMiCMcosICm';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}