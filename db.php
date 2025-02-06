<?php
$host = 'localhost';
$dbname = 'veille_info';
$username = 'root'; // À remplacer par votre identifiant MySQL
$password = ''; // À adapter selon votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>