<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session pour 
// pour créer une "sécurité" des données 
if (isset($_SESSION['user'])) {

    // Récupérer les données de l'utilisateur à partir de la session
    $user_session_info = $_SESSION['user'];
    $user_email = $user_session_info['email'];

    // Messages d'information dans la console php
    error_log("User récupéré sur Formulaire_modification.php");

    //Récupération des données pour effectuer le traitement à partir des données de formulaire
    $num_article = $_POST['num_article'];

    // Chemin complet du fichier JSON de l'article
    $nom_fichier = '../data/' . md5($user_email) . '/article-' . $num_article . "/article-" . $num_article . '.json';

    // Vérifie si le fichier existe
    if (file_exists($nom_fichier)) {
        // Lit le contenu du fichier JSON
        $contenu = file_get_contents($nom_fichier);

        // Décodage des données JSON
        $article = json_decode($contenu, true);
    } else {
        // Le fichier n'existe pas, redirige vers le profil
        return header("Location: ../Utilisateur/Profil_Utilisateur.php");
    }

    // Récupère les valeurs transmises via la variable $article 
    $titre = $article['titre'];
    $categorie = $article['categorie'];
    $instructions = $article['instructions'];
    $num_article = $article['numero_article'];
    $images_filepaths[]= $article['images'];
    $videos_filepaths[]= $article['videos'];
} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    return header('Location: ../Utilisateur/Connexion.php');
}
?>

<!-- Ici commence le code de la page html -->

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
    <!-- Section pour la barre de navigation -->
    <header id="header">
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li>
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="../Utilisateur/Profil_Utilisateur.php">Profil</a>
                    <?php } else { ?>
                        <a href="../Utilisateur/Connection.php">Connexion</a>/<a href="../Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Section qui affiche dans un formulaire les données de l'article que l'on souhaite modifier -->
        <fieldset>
            <legend>Modifier l'article</legend>
            <form action="./envoye_new_donner.php" method="post" enctype="multipart/form-data">
                <label for="titre">Titre :</label>
                <!-- Utilise les variables PHP pour remplir les valeurs des champs -->
                <input type="text" id="titre" name="titre" required value="<?php echo $titre; ?>">

                <label for="categorie">Catégorie :</label>
                <select id="categorie" name="categorie" required>
                    <option value="">Sélectionner une catégorie</option>
                    <option value="Technologie" <?php if ($categorie == "Technologie") echo "selected"; ?>>Technologie</option>
                    <option value="Santé" <?php if ($categorie == "Santé") echo "selected"; ?>>Santé</option>
                    <option value="Cuisine" <?php if ($categorie == "Cuisine") echo "selected"; ?>>Cuisine</option>
                    <option value="Autre" <?php if ($categorie == "Autre") echo "selected"; ?>>Autre</option>
                </select>

                <label for="instructions">Instructions :</label>
                <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>

                <!-- NE FONCTIONNE PAS -->
                <!-- 
                <!- Section pour modifier DES images // taille max du fichier on ajoutera après maxlength="5242880" ->
                <label for="image">Image :</label>
                <input type="file" name="images[]" multiple> 

                <!- Section pour modifier LA vidéo ->
                <label for="video">Vidéo :</label>
                <input type="file" id="video" name="video" accept="video/*"> 
-->

                <input type="hidden" name="email" value="<?php echo $user_session_info['email']; ?>">
                <input type="hidden" name="num_article" value="<?php echo $num_article; ?>">

                <input type="submit" name="envoyer" value="Modifier">
            </form>
        </fieldset>
    </main>
    <footer>
        <!-- Section qui affiche les auteurs du site web -->
        <p>
            <small>
                Copyrights 2024 - Luc Letailleur et Thomas Herriau
            </small>
        </p>
    </footer>
</body>

</html>