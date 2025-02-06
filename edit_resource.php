<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: resources.php");
    exit();
}

$resource_id = intval($_GET["id"]);

// Récupérer les informations de la ressource
$stmt = $pdo->prepare("SELECT * FROM resources WHERE resource_id = ?");
$stmt->execute([$resource_id]);
$resource = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resource) {
    echo "Ressource introuvable.";
    exit();
}

// Mise à jour de la ressource
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier_ressource"])) {
    $titre = htmlspecialchars($_POST["resource_titre"]);
    $desc = htmlspecialchars($_POST["resource_desc"]);
    $url = filter_var($_POST["resource_url"], FILTER_VALIDATE_URL);

    if ($url) {
        $stmt = $pdo->prepare("UPDATE resources SET resource_titre = ?, resource_desc = ?, resource_url = ? WHERE resource_id = ?");
        $stmt->execute([$titre, $desc, $url, $resource_id]);
        header("Location: resources.php?theme_id=" . $resource["theme_id"]);
        exit();
    } else {
        echo "URL invalide.";
    }
}
?>

<h2>Modifier la ressource</h2>
<a href="resources.php?theme_id=<?= $resource["theme_id"] ?>">Retour</a>

<form method="post">
    <input type="text" name="resource_titre" value="<?= htmlspecialchars($resource["resource_titre"]) ?>" required>
    <textarea name="resource_desc"><?= htmlspecialchars($resource["resource_desc"]) ?></textarea>
    <input type="url" name="resource_url" value="<?= htmlspecialchars($resource["resource_url"]) ?>" required>
    <button type="submit" name="modifier_ressource">Modifier</button>
</form>