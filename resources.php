<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["theme_id"])) {
    header("Location: index.php");
    exit();
}

$theme_id = intval($_GET["theme_id"]);

// Récupérer le nom de la thématique
$stmt = $pdo->prepare("SELECT theme_nom FROM themes WHERE theme_id = ?");
$stmt->execute([$theme_id]);
$theme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$theme) {
    echo "Thématique introuvable.";
    exit();
}

// Ajout d'une ressource
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_ressource"])) {
    $titre = htmlspecialchars($_POST["resource_titre"]);
    $desc = htmlspecialchars($_POST["resource_desc"]);
    $url = filter_var($_POST["resource_url"], FILTER_VALIDATE_URL);

    if ($url) {
        $stmt = $pdo->prepare("INSERT INTO resources (resource_titre, resource_desc, resource_url, theme_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titre, $desc, $url, $theme_id]);
        header("Location: resources.php?theme_id=$theme_id");
        exit();
    } else {
        echo "URL invalide.";
    }
}

// Suppression d'une ressource
if (isset($_GET["delete"])) {
    $resource_id = intval($_GET["delete"]);
    $stmt = $pdo->prepare("DELETE FROM resources WHERE resource_id = ?");
    $stmt->execute([$resource_id]);
    header("Location: resources.php?theme_id=$theme_id");
    exit();
}

// Récupération des ressources
$stmt = $pdo->prepare("SELECT * FROM resources WHERE theme_id = ?");
$stmt->execute([$theme_id]);
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Ressources pour <?= htmlspecialchars($theme["theme_nom"]) ?></h2>
<a href="index.php">Retour</a>

<h3>Ajouter une ressource</h3>
<form method="post">
    <input type="text" name="resource_titre" placeholder="Titre de la ressource" required>
    <textarea name="resource_desc" placeholder="Description"></textarea>
    <input type="url" name="resource_url" placeholder="URL" required>
    <button type="submit" name="ajouter_ressource">Ajouter</button>
</form>

<h3>Liste des ressources</h3>
<ul>
    <?php foreach ($resources as $resource): ?>
    <li>
        <a href="<?= htmlspecialchars($resource["resource_url"]) ?>" target="_blank">
            <?= htmlspecialchars($resource["resource_titre"]) ?>
        </a>
        <a href="resources.php?theme_id=<?= $theme_id ?>&delete=<?= $resource['resource_id'] ?>">Supprimer</a>
        <a href="edit_resource.php?id=<?= $resource['resource_id'] ?>">Modifier</a>
    </li>
    <?php endforeach; ?>
</ul>