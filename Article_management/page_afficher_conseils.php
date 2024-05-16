<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Récupère le numéro de l'article envoyé via le formulaire POST
$numero_article = $_POST['num_article'];

//Fonction qui permet d'aller chercher un article en fonction de son numéro transmis
function getOneArticle($num_article)
{
    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Chemin du répertoire contenant les dossiers des utilisateurs
    $users_directory = '../data/';

    // Parcours de tous les dossiers d'utilisateurs
    $users_folders = glob($users_directory . '*', GLOB_ONLYDIR);

    // Parcours de tous les dossiers d'utilisateurs pour trouver l'article en question
    foreach ($users_folders as $user_folder) {
        error_log("fichier :  $user_folder.");

        // Chemin complet du fichier JSON de l'article
        $nom_fichier_json = $user_folder. '/article-' . $num_article . '/article-' . $num_article . '.json';

        // Vérifie si le fichier JSON de l'article existe
        if (file_exists($nom_fichier_json)) {
            // Récupération du contenu du fichier
            $contenu = file_get_contents($nom_fichier_json);

            // Vérification si la lecture du fichier s'est bien passée
            if ($contenu !== false) {
                // Décodage des données JSON et retourne les données de l'article
                return json_decode($contenu, true);
            } else {
                // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                error_log("Erreur lors de la lecture du fichier $nom_fichier_json.");
                return null;
            }
        } else {
            // Affichage d'un message d'erreur si le fichier JSON de l'article n'existe pas
            error_log("Le fichier $nom_fichier_json n'existe pas, utilisateur suivant ...");
        }
    }
}

// Variables où sont stockées les informations de l'article pour pouvoir les afficher ensuite
$article = getOneArticle($numero_article);
?>

<!-- Ici commence le code de la page HTML -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <!-- Permet un affichage dynamique suivant l'article sélectionné -->
    <title><?php echo $article['titre']; ?></title>
</head>

<body>
    <!-- Section pour la barre de navigation -->
    <header>
        <a href="Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseil</a></li>
                <li>
                    <!-- On regarde si une session est ouverte avec isset si oui on lui donne la possibilité de voir son profil -->
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="../Utilisateur/Profil_Utilisateur.php">Profil</a>
                    <?php } else { ?>
                        <!-- Sinon on affiche les options pour se connecter ou s’inscrire -->
                        <a href="../Utilisateur/Connection.php">Connexion</a>/<a href="../Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Section qui permet d'afficher le contenu de l'article -->
    <main>
        <!-- Section qui permet d'afficher le titre de l'article -->
        <div class="container_titre">
            <h1><?php echo $article['titre']; ?></h1>
        </div>

        <!-- Section qui permet d'afficher la catégorie de l'article -->
        <div class="container_catégorie">
            <h2><?php echo $article['categorie']; ?></h2>
        </div>

        <!-- Section qui permet d'afficher le texte et les images de l'article -->
        <div class="container_main">
            <div class="carre affiche_text">
                <p><?php echo $article['instructions']; ?></p>
            </div>

            <!-- SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->
            <div class="container_media">
                <h3>Images :</h3>
                <?php foreach ($article['images'] as $image) { ?>
                    <img src="<?php echo $image; ?>" alt="Image de l'article">
                <?php } ?>
                <h3>Vidéos :</h3>
                <?php foreach ($article['videos'] as $video) { ?>
                    <video controls>
                        <source src="<?php echo $video; ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                <?php } ?>
            </div>
            <!-- FIN DE LA SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->

        </div>

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

<?php

?>