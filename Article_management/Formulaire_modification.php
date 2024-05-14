<?php
// Vérifie si des données ont été transmises via l'URL
if (isset($_GET['titre']) && isset($_GET['categorie']) && isset($_GET['instructions'])) {
    // Récupère les valeurs transmises via l'URL
    $titre = htmlspecialchars($_GET['titre']);
    $categorie = htmlspecialchars($_GET['categorie']);
    $instructions = htmlspecialchars($_GET['instructions']);
    $user_email = htmlspecialchars($_GET['email']);
    $num_article = htmlspecialchars($_GET['num_article']);

} else {
    // Si aucune donnée n'a été transmise, initialise les variables à une valeur par défaut
    $titre = "";
    $categorie = "";
    $instructions = "";
    $user_email = "";
    $num_article = "";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY-Social</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/form_design.css">
</head>

<body>
    <header id="header">
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="Conseils.php">Nos conseils</a></li>
                <li><a href="Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="../Utilisateur/Connection.php">Connexion</a>/<a href="../Utilisateur/Inscription.php">Inscription</a></li>
                <li>
                    <!-- Ajoutez un formulaire autour de l'élément input pour envoyer la recherche via POST -->
                    <form action="votre_script_php.php" method="post">
                        <input type="text" placeholder="Rechercher..." name="recherche">
                        <input type="submit" name="rechercher" value="Rechercher">
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <fieldset>
            <legend>Modifier l'article</legend>
            <!-- Modifiez l'action du formulaire pour qu'il envoie les données à votre script PHP -->
            <form action="envoye_new_donner.php" method="post" enctype="multipart/form-data">
                <label for="titre">Titre :</label>
                <!-- Utilisez les variables PHP pour remplir les valeurs des champs -->
                <input type="text" id="titre" name="titre" required value="<?php echo $titre; ?>">

                <label for="categorie">Catégorie :</label>
                <select id="categorie" name="categorie" required>
                    <option value="">Sélectionner une catégorie</option>
                    <option value="Technologie" <?php if ($categorie == "Technologie") echo "selected"; ?>>Technologie</option>
                    <option value="Santé" <?php if ($categorie == "Santé") echo "selected"; ?>>Santé</option>
                    <option value="Cuisine" <?php if ($categorie == "Cuisine") echo "selected"; ?>>Cuisine</option>
                    <option value="Autre" <?php if ($categorie == "Autre") echo "selected"; ?>>Autre</option>
                    <!-- Ajoutez d'autres options de catégorie selon vos besoins -->
                </select>

                <label for="instructions">Instructions :</label>
                <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>

                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*">

                <label for="video">Vidéo :</label>
                <input type="file" id="video" name="video" accept="video/*">

                <input type="hidden" name="email" value="<?php echo $user_email; ?>">
                <input type="hidden" name="num_article" value="<?php echo $num_article; ?>">

                <input type="submit" name="envoyer" value="Modifier">
            </form>
        </fieldset>
    </main>
    <footer>
        <p>
            <small>
                Copyrights 2024 - Luc Letailleur et Thomas Herriau
            </small>
        </p>
    </footer>
</body>

</html>