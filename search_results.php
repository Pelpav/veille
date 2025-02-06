<?php
require 'db.php';
session_start();

$search = isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : '';

// Requête pour récupérer les ressources et leurs thématiques
$query = "
    SELECT resources.*, themes.theme_nom 
    FROM resources 
    LEFT JOIN themes ON resources.theme_id = themes.theme_id 
    WHERE resource_titre LIKE ? OR resource_desc LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%']);
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Résultats de recherche pour "<?= $search ?>"</h2>
<a href="index.php">Retour à l'accueil</a>

<h3>Liste des ressources</h3>
<ul>
    <?php if (count($resources) > 0): ?>
        <?php foreach ($resources as $resource): ?>
            <li>
                <?= htmlspecialchars($resource["resource_titre"]) ?> 
                (Thématique : <?= htmlspecialchars($resource["theme_nom"]) ?>)
                <a href="edit_resource.php?id=<?= $resource['resource_id'] ?>">Modifier</a>
                <a href="delete_resource.php?id=<?= $resource['resource_id'] ?>">Supprimer</a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Aucune ressource trouvée.</li>
    <?php endif; ?>
</ul>