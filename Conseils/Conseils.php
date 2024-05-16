<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Fonction qui récupère tous les articles présent dans la base de donnée pour ensuite pour les afficher
function getAllArticles()
{
    // Chemin relatif vers le dossier 'data'
    $dossier = '../data';

    // Initialisation d'un tableau pour stocker tous les articles
    $articles = array();

    // Vérifie si le dossier 'data' existe
    if (file_exists($dossier) && is_dir($dossier)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les fichiers d'articles dans le dossier utilisateur
            $fichiers = glob($dossier_utilisateur . '/article-*');

            // Parcours de chaque fichier pour récupérer les données des articles
            foreach ($fichiers as $fichier) {
                // Récupération du contenu du fichier
                $contenu = file_get_contents($fichier);

                // Décodage des données JSON
                $article = json_decode($contenu, true);

                // Ajout de l'article au tableau d'articles
                $articles[] = $article;
            }
        }
    }

    // Retourne le tableau d'articles
    return $articles;
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
    <link rel="stylesheet" href="../css/form_design.css">
</head>

<body>
    <!-- Section pour la barre de navigation -->
    <header id="header">
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="Conseils.php">Nos conseils</a></li>
                <li><a href="Formulaire_soumission.php">Donner un conseils</a></li>
                <li>
                    <!-- Permet d'afficher un bouton d'action différents selon si un utilisateur est connecté à un compte -->
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a href="../Utilisateur/Profil_Utilisateur.php">Profil</a>
                    <?php } else { ?>
                        <a href="../Utilisateur/Connection.php">Connexion</a>/<a href="./Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Affichage les différents articles, et option pour les visualiser -->
        <fieldset class="article">
            <legend>Mes articles</legend>
            <?php foreach (getAllArticles() as $article) : ?>
                <fieldset>
                    <legend><?php echo $article['titre']; ?></legend>
                    <p>Catégorie: <?php echo $article['categorie']; ?></p>
                    <div><?php echo $article['instructions']; ?></div>
                    <form action="../Article_management/page_afficher_conseils.php" method="post">
                        <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                        <button type="submit" name="submit" class="bouton_voir">Voir</button>
                    </form>
                </fieldset>
                <br>

            <?php endforeach; ?>

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