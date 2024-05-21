<!-- Ici le php à plusieurs fonction, premièrement il vérifie qu'un utilisateur soit bien connecter puis il permet d'afficher ses information, ses articles, de pourvoir les modifiers -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session
if (isset($_SESSION['user'])) {
    // Récupérer les données de l'utilisateur à partir de la session
    $user_session_info = $_SESSION['user'];
    // Messages d'information dans la console php
    error_log("User récupéré sur Profil_Utilisateur.php");
} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    header('Location: ../Accueil.php');
    exit;
}

// Traitement du formulaire de modification s'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $user['nom'] = $_POST['nom'];
    $user['prenom'] = $_POST['prenom'];
    $user['email'] = $_POST['email'];

    // Récupérer le mot de passe de l'utilisateur
    $user['password'] = $user_session_info['password'];

    // Chemin complet du fichier CSV dans le dossier 'user-information'
    $nom_fichier = '../data/' . md5($user['email']) . '/user-info.csv';

    // Modifier les données dans le fichier CSV
    if (file_exists($nom_fichier)) {
        // Ouvre le fichier de destination pour écrire en écrasant les anciennes données
        $handle = fopen($nom_fichier, 'w');

        // Écrit les données en langage csv pour les accent et caractère spéciaux pour une meilleur retranscription par la suite
        fputcsv($handle, $user);

        // Enregistre puis ferme le fichier
        fclose($handle);

        // Messages d'information dans la console php
        error_log("Les données ont été mises à jour avec succès.");
    } else {
        // Messages d'information dans la console php
        error_log("Erreur : le fichier utilisateur n'existe pas.");
    }
    // CHANGER LES INFORMATION DE SESSION, RECONNECTER L'USER AUTOMATIQUEMENT
}

// Fonction qui va récupérer tout les articles de l'utilisateur via son e-mail
function getArticles($user_email)
{
    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Chemin complet du dossier utilisateur
    $dossier_utilisateur = '../data/' . md5($user_email);

    // Initialisation d'un tableau pour stocker les articles
    $articles = array();

    // Vérifie si le dossier utilisateur existe
    if (file_exists($dossier_utilisateur) && is_dir($dossier_utilisateur)) {

        // Récupération de tous les dossiers d'articles dans le dossier utilisateur
        $dossiers_articles = glob($dossier_utilisateur . '/article-*', GLOB_ONLYDIR);

        // Parcours de chaque dossier d'article pour récupérer les données des articles
        foreach ($dossiers_articles as $dossier_article) {
            // Récupération du chemin du fichier JSON de l'article
            $nom_fichier = $dossier_article . '/' . basename($dossier_article) . '.json';

            // Vérifie si le fichier JSON de l'article existe
            if (file_exists($nom_fichier)) {
                // Récupération du contenu du fichier
                $contenu = file_get_contents($nom_fichier);

                // Décodage des données JSON
                $article = json_decode($contenu, true);

                // Ajout de l'article au tableau d'articles
                $articles[] = $article;
            }
        }

        // Message d'information
        error_log("Récupération des données de l'article terminé.");

        // Retourne le tableau d'articles
        return $articles;
    } else {
        // Message d'erreur
        error_log("Le dossier $dossier_utilisateur n'existe pas");
        return $articles; // Retourne un tableau vide en cas d'erreur
    }
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
                <li><a href="Deconnexion.php">Déconnexion</a></li>
                </ul>
        </nav>
    </header>
    <main>
        <!-- Section qui affiche dans un formulaire les informations de l'utilisateur et ses articles -->
        <h1>Profil Utilisateur</h1>

        <div class="container_user">
            <!-- Permet d'afficher et de modifier les informations d'utilisateur -->
            <fieldset class="formulaire">
                <legend>Information personnel</legend>
                <form action="" method="post">
                    <label for="nom">Nom:</label>
                    <!-- Utilise les variables PHP pour remplir les valeurs des champs -->
                    <input type="text" id="nom" name="nom" value="<?php echo $user_session_info['nom']; ?>"><br>

                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $user_session_info['prenom']; ?>"><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user_session_info['email']; ?>"><br>

                    <input type="submit" value="Modifier">
                </form>
            </fieldset>
            <!-- Affichage les différents articles, et option pour les visualiser, les modifier ou les supprimer -->
            <fieldset class="article">
                <legend>Mes articles</legend>
                <?php foreach (getArticles($user_session_info['email']) as $article) : ?>
                    <fieldset>
                        <legend><?php echo $article['titre']; ?></legend>
                        <p>Catégorie: <?php echo $article['categorie']; ?></p>
                        <div><?php echo $article['instructions']; ?></div>
                        
                        <!-- Section de formulaire pour faire des buttons interactif -->
                        <div class="container_button_modifiez_supprimer">
                            <!-- Button pour voir -->
                            <form action="../Article_management/page_afficher_conseils.php" method="post">
                                <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                                <button type="submit" name="submit" class="bouton_voir">Voir</button>
                            </form>
                            <!-- Button pour modifier -->
                            <form action="../Article_management/Formulaire_modification.php" method="post">
                                <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                                <button type="submit" name="submit" class="bouton_modifier">Modifier</button>
                            </form>
                            <!-- Button pour supprimer -->
                            <form action="../Article_management/supprimer_article.php" method="post">
                                <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                                <button type="submit" name="submit" class="bouton_supprimer">Supprimer</button>
                            </form>
                        </div>
                    </fieldset>
                    <br>
                <?php endforeach; ?>
            </fieldset>
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