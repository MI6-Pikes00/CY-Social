<?php
session_start();

$numero_article = $_POST['num_article'];
echo $numero_article . "<br>";

function getOneArticle($nb_article)
{
    // Initialisation d'un tableau pour stocker les articles
    $articles = array();

    // Chemin du répertoire contenant les dossiers des utilisateurs
    $users_directory = './data/';
    // Parcours de tous les dossiers d'utilisateurs
    $users_folders = glob($users_directory . '*', GLOB_ONLYDIR);
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
                // Décodage des données JSON
                $article_data = json_decode($content, true);

                // Ajout de l'article au tableau d'articles
                $articles[] = $article_data;
            } else {
                echo "Erreur lors de la lecture du fichier $article_file.<br>";
            }
        }
        echo $article_file . "<br>";
    }

    // Retourne le tableau d'articles
    return $articles;
}

$articleOne = getOneArticle($numero_article);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/global-style.css">
    <title>Article</title>
</head>

<body>
    <header>
        <a href="Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="./Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="./Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li>
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="./Utilisateur/Profil_Utilisateur.php">Profil</a>
                    <?php } else { ?>
                        <a href="./Utilisateur/Connection.php">Connexion</a>/<a href="./Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <?php foreach ($articleOne as $article) : ?>
            <div class="container_titre">
                <h1><?php echo $article['titre']; ?></h1>
            </div>
            <div class="container_catégorie">
                <h2><?php echo $article['categorie']; ?></h2>
            </div>
            <div class="container_main">
                <div class="carre affiche_text">
                    <p><?php echo $article['instructions']; ?></p>
                </div>
                <div class="carre affiche_image">
                    <p>Container pour les images</p>
                </div>
            </div>
        <?php endforeach; ?>
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