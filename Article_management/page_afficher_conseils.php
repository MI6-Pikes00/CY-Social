<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Initialise la variable pour le chemin du fichier
$chemin_fichier = "";

// Vérifie si le numéro de l'article est défini soit dans POST soit dans GET
if (isset($_POST['id_article'])) {
    $numero_article = $_POST['id_article'];
} elseif (isset($_GET['id_article'])) {
    $numero_article = $_GET['id_article'];
} else {
    // Si le numéro de l'article n'est pas défini, termine le script avec un message d'erreur
    die("Numéro de l'article non défini.");
}

// Fonction qui permet d'aller chercher un article en fonction de son numéro transmis
function getOneArticle($id_article, &$chemin_fichier)
{
    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Chemin du répertoire contenant les dossiers des utilisateurs
    $utilisateurs_chemin = '../data/';

    // Parcours de tous les dossiers d'utilisateurs
    $utilisateurs_fichiers = glob($utilisateurs_chemin . '*', GLOB_ONLYDIR);

    // Liste des types de dossiers à vérifier
    $types_dossiers = ['article-', 'video-', 'citation-'];

    // Parcours de tous les dossiers d'utilisateurs pour trouver l'article en question
    foreach ($utilisateurs_fichiers as $utilisateur_fichier) {
        error_log("fichier :  $utilisateur_fichier.");

        // Parcours des types de dossiers
        foreach ($types_dossiers as $type_dossier) {
            // Chemin complet du fichier JSON de l'article
            $chemin_fichier_json = $utilisateur_fichier . '/' . $type_dossier . $id_article . '/' . $type_dossier . $id_article . '.json';
            $chemin_fichier = $chemin_fichier_json;

            // Vérifie si le fichier JSON de l'article existe
            if (file_exists($chemin_fichier_json)) {
                // Récupération du contenu du fichier
                $contenu = file_get_contents($chemin_fichier_json);

                // Vérification si la lecture du fichier s'est bien passée
                if ($contenu !== false) {
                    // Décodage des données JSON et retourne les données de l'article
                    return json_decode($contenu, true);
                } else {
                    // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                    error_log("Erreur lors de la lecture du fichier $chemin_fichier_json.");
                    return null;
                }
            } else {
                // Affichage d'un message d'erreur si le fichier JSON de l'article n'existe pas
                error_log("Le fichier $chemin_fichier_json n'existe pas, utilisateur suivant ...");
            }
        }
    }

    // Retourne null si aucun fichier JSON n'a été trouvé
    return null;
}

// Variables où sont stockées les informations de l'article pour pouvoir les afficher ensuite
$article = getOneArticle($numero_article, $chemin_fichier);

// Formate la date de création de l'article
$date_creation_formatted = "Date non disponible";
if (isset($article['date_creation']) && !empty($article['date_creation'])) {
    $date_creation = DateTime::createFromFormat('Y-m-d H:i:s', $article['date_creation']);
    if ($date_creation !== false) {
        $date_creation_formatted = $date_creation->format('d/m/Y');
    } else {
        $date_creation_formatted = "Format de date invalide";
    }
}

// Calcule la moyenne des notes si elles existent
$moyenne_note = 0;
if (!empty($article['notes']) && count($article['notes']) > 0) {
    $total_notes = array_sum($article['notes']);
    $nombre_notes = count($article['notes']);
    $moyenne_note = $total_notes / $nombre_notes;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <title><?php echo htmlspecialchars($article['titre'] ?? 'Titre non disponible'); ?></title>
</head>

<body>
    <header>
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/formulaire_dynamique.php">Donner un conseil</a></li>
                <li>
                    <?php if (isset($_SESSION['utilisateur'])) { ?>
                        <a href="../Utilisateur/Profil_Utilisateur.php">Profil</a>
                    <?php } else { ?>
                        <a href="../Utilisateur/Connection.php">Connexion</a>/<a href="../Utilisateur/Inscription.php">Inscription</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container-main-article">
            <div class="article-titre">
                <h1><?php echo htmlspecialchars($article['titre'] ?? 'Titre non disponible'); ?></h1>
            </div>
            <span class="article-author">par <?php echo htmlspecialchars($article['auteur'] ?? 'Auteur inconnu'); ?>, le <?php echo $date_creation_formatted; ?></span>

            <!-- Section qui permet d'afficher la catégorie de l'article -->
            <?php if (isset($article['categorie']) && !empty($article['categorie'])) { ?>
                <div class="container_categorie">
                    <h2><?php echo htmlspecialchars($article['categorie']); ?></h2>
                </div>
            <?php } ?>

            <!-- Section qui permet d'afficher le texte de l'article -->
            <div class="article_text">
                <p><?php echo htmlspecialchars($article['instructions'] ?? ''); ?></p>
                <?php if (isset($article['images']) && !empty($article['images'][0])) { ?>
                    <div class="first-image">
                        <img src="<?php echo htmlspecialchars($article['images'][0]); ?>" alt="Première image de l'article">
                    </div>
                <?php } ?>
            </div>
            <br>
            <!-- SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->
            <div class="container_media">
                <?php if (isset($article['images']) && count($article['images']) > 1) { ?>
                    <h3>Images :</h3>
                    <div class="additional-images-container">
                        <?php foreach (array_slice($article['images'], 1) as $image) { ?>
                            <img class="additional-image" src="<?php echo htmlspecialchars($image); ?>" alt="Image de l'article">
                        <?php } ?>
                    </div>
                <?php } ?>
                <br>
                <?php if (isset($article['video']) && !empty($article['video'])) { ?>
                    <h3>Vidéo(s) :</h3>
                    <center><!-- Centrage de la vidéo sur la page -->
                        <video controls width="70%" style="margin-bottom: 15px">
                            <source src="<?php echo htmlspecialchars($article['video']); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </center>
                <?php } ?>
            </div>
            <!-- FIN DE LA SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->

            <!-- SECTION COMMENTAIRE ET NOTE -->
            <fieldset id="commentaires_et_notes">
                <legend>Commentaires et Notes :</legend>
                <?php
                if (empty($article['notes'])) {
                    echo '<p style="margin-left: 1%;" class="no-comments">Aucune note sur ce post pour le moment.</p>';
                } else {
                    echo "<p style='margin-left: 1%;'>Note moyenne: " . number_format($moyenne_note, 1) . "/5</p>";
                }
                ?>
                <div class="comment-container">
                    <?php
                    if (empty($article['commentaires'])) {
                        echo '<p class="no-comments">Aucun commentaire sur ce post pour le moment.</p>';
                    } else {
                        // Boucle sur chaque commentaire
                        foreach ($article['commentaires'] as $index => $commentaire) {
                            $comment = html_entity_decode(htmlspecialchars($commentaire['commentaire'] ?? ''));
                            $rating = html_entity_decode(htmlspecialchars($article['notes'][$index] ?? ''));
                            $name = html_entity_decode(htmlspecialchars($commentaire['utilisateur'] ?? ''));
                    ?> <!-- htmlspecialchars(...) permet de prévenir les attaques XSS -->
                            <div class="comment-box">
                                <p class="comment-text"><?php echo $comment; ?></p>
                                <p class="comment-rating">Note: <?php echo $rating; ?>/5</p>
                                <p class="comment-author"><?php echo $name; ?></p>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </fieldset>

            <form action="./note_commentaire.php" method="post">
                <label for="note">Note :</label>
                <select name="note" id="note">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <input type="hidden" name="chemin_fichier" value="<?php echo htmlspecialchars($chemin_fichier); ?>">
                <input type="hidden" name="id_article" value="<?php echo htmlspecialchars($numero_article); ?>">

                <label for="commentaire">Commentaire :</label>
                <textarea name="commentaire" id="commentaire" placeholder="300 caractères max."></textarea>

                <button type="submit">Envoyer</button>
            </form>

        </div>

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


http://localhost:8080/Article_management/page_afficher_conseils.php?id_article=664f6cb9331f6?id_article=664f6cb9331f6