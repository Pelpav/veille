<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["user_password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_nom"] = $user["user_nom"];
        header("Location: index.php");
        exit();
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>

<form method="post">
    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
<p>Pas encore inscrit ? <a href="register.php">Inscrivez-vous ici</a></p>