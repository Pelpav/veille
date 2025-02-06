// edit_theme.php
<?php
require 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Récupération de l'ID du thème
if (isset($_GET["id"])) {
    $theme_id = intval($_GET["id"]);
    $stmt = $pdo->prepare("SELECT * FROM themes WHERE theme_id = ?");
    $stmt->execute([$theme_id]);
    $theme = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$theme) {
        header("Location: index.php");
        exit();
    }
}

// Mise à jour d'une thématique
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modifier_theme"])) {
    $theme_nom = htmlspecialchars($_POST["theme_nom"]);
    $theme_desc = htmlspecialchars($_POST["theme_desc"]);
    $stmt = $pdo->prepare("UPDATE themes SET theme_nom = ?, theme_desc = ? WHERE theme_id = ?");
    $stmt->execute([$theme_nom, $theme_desc, $theme_id]);
    header("Location: index.php");
    exit();
}
?>

<h2>Modifier la thématique</h2>
<form method="post">
    <input type="text" name="theme_nom" value="<?= htmlspecialchars($theme["theme_nom"]) ?>" required>
    <textarea name="theme_desc" required><?= htmlspecialchars($theme["theme_desc"]) ?></textarea>
    <button type="submit" name="modifier_theme">Modifier</button>
</form>