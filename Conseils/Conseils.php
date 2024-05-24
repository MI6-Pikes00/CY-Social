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

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier) && is_dir($dossier)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier . '/*', GLOB_ONLYDIR);

        // Liste des types de dossiers à vérifier
        $types_dossiers = ['article-', 'video-', 'citation-'];

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Parcours des types de dossiers
            foreach ($types_dossiers as $type_dossier) {
                // Récupération de tous les sous-dossiers spécifiques (articles, vidéos, citations) dans le dossier utilisateur
                $sous_dossiers = glob($dossier_utilisateur . '/' . $type_dossier . '*', GLOB_ONLYDIR);

                // Parcours de chaque sous-dossier spécifique
                foreach ($sous_dossiers as $sous_dossier) {
                    // Récupération de tous les fichiers JSON dans le sous-dossier
                    $fichiers = glob($sous_dossier . '/*.json');

                    // Parcours de chaque fichier pour récupérer les données
                    foreach ($fichiers as $fichier) {
                        // Récupération du contenu du fichier
                        $contenu = file_get_contents($fichier);

                        // Vérification si la lecture du fichier s'est bien passée
                        if ($contenu !== false) {
                            // Décodage des données JSON
                            $article = json_decode($contenu, true);

                            // Ajout de l'article au tableau d'articles
                            $articles[] = $article;
                        } else {
                            // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                            error_log("Erreur lors de la lecture du fichier $fichier.");
                        }
                    }
                }
            }
        }
    }

    // Retourne le tableau d'articles
    return $articles;
}

$articles = getAllArticles();

function calculerMoyenne($notes) {
    if (empty($notes)) {
        return 0;
    }

    $total = array_sum($notes);
    $nombreDeNotes = count($notes);

    return $total / $nombreDeNotes;
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
                <li><a href="formulaire_dynamique.php">Donner un conseils</a></li>
                <li>
                    <!-- Permet d'afficher un bouton d'action différents selon si un utilisateur est connecté à un compte -->
                    <?php if (isset($_SESSION['utilisateur'])) { ?>
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
            <?php foreach ($articles as $article) : ?>
                <fieldset>
                    <legend><?php echo htmlspecialchars($article['titre']); ?></legend>
                    <?php if (isset($article['categorie'])) : ?>
                        <p><b>Catégorie: </b> <?php echo htmlspecialchars($article['categorie']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($article['notes'])) : ?>
                        <p> <b> Moyenne des notes: </b> <?php echo number_format(calculerMoyenne($article['notes']), 2); ?></p>
                    <?php endif; ?>
                    <div>
                        <p><b>Contenue :</b></p>
                        <?php
                        $instructions = $article['instructions'];
                        if (strlen($instructions) > 300) {
                            $instructions = substr($instructions, 0, 300) . '...';
                        }
                        echo htmlspecialchars($instructions);
                        ?>
                    </div>
                    <form action="../Article_management/page_afficher_conseils.php" method="post">
                        <input type="hidden" name="id_article" value="<?php echo htmlspecialchars($article['numero_article']); ?>">
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