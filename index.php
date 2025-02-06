<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Ajout d'une thématique
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_theme"])) {
    $theme_nom = htmlspecialchars($_POST["theme_nom"]);
    $theme_desc = htmlspecialchars($_POST["theme_desc"]);
    $stmt = $pdo->prepare("INSERT INTO themes (theme_nom, theme_desc) VALUES (?, ?)");
    $stmt->execute([$theme_nom, $theme_desc]);
}

// Suppression d'une thématique
if (isset($_GET["delete"])) {
    $theme_id = intval($_GET["delete"]);
    $stmt = $pdo->prepare("DELETE FROM themes WHERE theme_id = ?");
    $stmt->execute([$theme_id]);
    header("Location: index.php");
    exit();
}

// Récupération des thématiques
$themes = $pdo->query("SELECT * FROM themes")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Bienvenue, <?= $_SESSION["user_nom"] ?>!</h2>
<a href="logout.php">Déconnexion</a>

<h3>Ajouter une thématique</h3>
<form method="post">
    <input type="text" name="theme_nom" placeholder="Nom de la thématique" required>
    <textarea name="theme_desc" placeholder="Description"></textarea>
    <button type="submit" name="ajouter_theme">Ajouter</button>
</form>

<form method="get" action="search_results.php">
    <input type="text" name="search" placeholder="Rechercher une ressource" required>
    <button type="submit">Rechercher</button>
</form>

<h3>Liste des thématiques</h3>
<ul>
    <?php foreach ($themes as $theme): ?>
    <li>
        <?= htmlspecialchars($theme["theme_nom"]) ?>
        <a href="index.php?delete=<?= $theme['theme_id'] ?>">Supprimer</a>
        <a href="edit_theme.php?id=<?= $theme['theme_id'] ?>">Modifier</a>
        <a href="resources.php?theme_id=<?= $theme['theme_id'] ?>">Voir ressources</a>
    </li>
    <?php endforeach; ?>
</ul>