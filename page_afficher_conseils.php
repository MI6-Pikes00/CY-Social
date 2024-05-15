<!-- Ici le php permet d'afficher suivant un article suivant son numéro -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Récupère le numéro de l'article envoyé via le formulaire POST
$numero_article = $_POST['num_article'];

//Fonction qui permet d'aller cherche un article en fonction de son numéro transmis
function getOneArticle($nb_article)
{
    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Initialisation d'un tableau pour stocker les articles
    $articles = array();

    // Chemin du répertoire contenant les dossiers des utilisateurs
    $users_directory = './data/';

    // Parcours de tous les dossiers d'utilisateurs
    $users_folders = glob($users_directory . '*', GLOB_ONLYDIR);

    // Parcours de tous les dossiers d'utilisateurs pour trouver l'article en question
    foreach ($users_folders as $user_folder) {

        // Récupération de l'email de l'utilisateur à partir du nom du dossier
        $user_email = basename($user_folder);

        // Chemin complet du dossier utilisateur pour cet article
        $article_file = $user_folder . '/article-' . $nb_article . '.json';

        // Vérifie si le fichier de l'article existe
        if (file_exists($article_file)) {

            // Récupération du contenu du fichier
            $content = file_get_contents($article_file);

            // Vérification si la lecture du fichier s'est bien passée
            if ($content !== false) {

                // Décodage des données JSON et ajout dans une variable $article
                $article_data = json_decode($content, true);

                // Message d'information
                error_log("Récupération des données de l'article terminé.");

                // Retourne les données de l'articles
                return $article_data;
            } else {

                // Affichage d'un message d'erreur si on ne peut pas obtenir les données présente dans le fichier
                echo "Erreur lors de la lecture du fichier $article_file.<br>";
            }
        }
        // Si l'article n'est pas présent dans le dossier il passe a l'utilisateur suivant
    }
}
// Variables ou sont stockées les informations de l'article pour pouvoir les affichées ensuite
$article = getOneArticle($numero_article);
?>

<!-- Ici commence le code de la page html -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/global-style.css">
    <!-- Permet un affichage dynamique suivant l'article sélectionner -->
    <title><?php echo $article['titre']; ?></title>
</head>

<body>
    <!-- Section pour la barre de navigation -->
    <header>
        <a href="Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="./Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="./Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li>
                    <!-- On regarde si une session est ouvert avec isset si oui on lui donne la possibilité de voir son profil -->
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="./Utilisateur/Profil_Utilisateur.php">Profil</a>

                        <!-- Sinon on affiche les options pour se connecter ou s’inscrire -->
                    <?php } else { ?>
                        <a href="./Utilisateur/Connection.php">Connexion</a>/<a href="./Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Section qui permet d'afficher le contenue de l'article -->

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



            <!-- IMAGE ET/OU VIDEO À VENIR  -->




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