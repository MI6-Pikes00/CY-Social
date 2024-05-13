<?php
// Vérification si l'email de connexion a été passé en paramètre
if (isset($_GET['email'])) {
    // Récupération de l'email de connexion depuis les paramètres GET
    $email = $_GET['email'];

    // Vous pouvez maintenant utiliser l'email de connexion dans cette page
    echo "L'utilisateur connecté est : $email";
} else {
    echo "Aucun email de connexion passé en paramètre.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY-Social</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
</head>
<body>
    <header id="header">
        <a href="#" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="Connection.php">Connexion</a>/<a href="Inscription.php">Inscription</a></li>
                <li><input type="text" placeholder="Rechercher..."><input type="submit" name="rechercher" value="Rechercher" /></li>
            </ul>
        </nav>
    </header>
    <main>
       <p>A venir ...</p>
    </main>
    <footer>
        <p>
            <small>
                Copyrights 2024 - Luc Letailleur eet Thomas Herriau
            </small>
        </p>
    </footer>
</body>
</html>