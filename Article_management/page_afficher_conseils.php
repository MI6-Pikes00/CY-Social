<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

$chemin_fichier = "";

// Vérifie si le numéro de l'article est défini soit dans POST soit dans GET
if (isset($_POST['id_article'])) {
    $numero_article = $_POST['id_article'];
} elseif (isset($_GET['id_article'])) {
    $numero_article = $_GET['id_article'];
} else {
    die("Numéro de l'article non défini.");
}

//Fonction qui permet d'aller chercher un article en fonction de son numéro transmis
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

$date_creation = DateTime::createFromFormat('Y-m-d H:i:s', $article['date_creation'])->format('d/m/Y');

if (!empty($article['notes'])) {
    // Calcul de la moyenne des notes
    $moyenne_note = 0;
    if (isset($article['notes']) && count($article['notes']) > 0) {
        $total_notes = array_sum($article['notes']);
        $nombre_notes = count($article['notes']);
        $moyenne_note = $total_notes / $nombre_notes;
    }
}

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
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/formulaire_dynamique.php">Donner un conseil</a></li>
                <li>
                    <!-- On regarde si une session est ouverte avec isset si oui on lui donne la possibilité de voir son profil -->
                    <?php if (isset($_SESSION['utilisateur'])) { ?>
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
        <!-- Structure de tout l'article -->
        <div class="container-main-article">
            <!-- Section qui permet d'afficher le titre de l'article -->
            <div class="article-titre">
                <h1><?php echo $article['titre']; ?></h1>
            </div>

            <span class="article-author">par <?php echo $article['auteur'] ?> , le <?php echo $date_creation ?></span>

            <!-- Section qui permet d'afficher la catégorie de l'article -->
            <?php if (isset($article['categorie']) && !empty($article['catégorie'])) { ?>
                <div class="container_catégorie">
                    <h2><?php echo $article['categorie']; ?></h2>
                </div>
            <?php } ?>

            <!-- Section qui permet d'afficher le texte et les images de l'article -->
            <div class="article_text">
                <p><?php echo $article['instructions']; ?></p>
            </div>
            <br>
            <!-- SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->
            <div class="container_media">
                <?php if (isset($article['images']) && !empty($article['images'])) { ?>
                    <h3>Images :</h3>
                    <?php foreach ($article['images'] as $image) { ?>
                        <img style="margin: 0 10px 15px" src="<?php echo $image; ?>" alt="Image de l'article">
                    <?php } ?>
                <?php } ?>
                <br>
                <?php if (isset($article['video']) && !empty($article['video'])) { ?>
                    <h3>Vidéo(s) :</h3>
                    <center><!--  Centrage de la vidéo sur la page  -->
                        <video controls width="70%" style="margin-bottom: 15px">
                            <source src="<?php echo $article['video']; ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </center>
                <?php } ?>
            </div>
            <!-- FIN DE LA SECTION POUR AFFICHER LES IMAGES ET VIDÉOS DE L'ARTICLE -->

            <!-- SECTION COMMENTAIRE ET NOTE -->

            <fieldidset id="commentaires_et_notes">
                <legend>Commentaires et Notes :</legend>
                <div class="comment-container">
                    <?php 
                    if (empty($article['commentaires'])) {
                        echo '<p class="no-comments">Aucun commentaire sur ce post pour le moment.</p>';
                    } else {
                        echo "<p>Note moyenne:$moyenne_note/5</p>";
                        // Boucle sur chaque commentaire            
                        foreach ($article['commentaires'] as $index => $commentaire) {
                            $comment = html_entity_decode(htmlspecialchars($commentaire['commentaire'])) ;
                            $rating = html_entity_decode(htmlspecialchars($article['notes'][$index]));
                            $name = html_entity_decode(htmlspecialchars($commentaire['utilisateur']));
                    ?>         <!-- html_entity_decode(...) permet d'afficher correctement le symbole “ ' ”
                                tandis que htmlspecialchars(...) permet de prévenir les attaques XSS -->
                        <div class="comment-box">
                            <p class="comment-text"><?php echo $comment; ?></p>
                            <p class="comment-rating">Note: <?php echo htmlspecialchars($rating); ?>/5</p>
                            <p class="comment-author"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </fieldset>

            <form action="./note_commentaire.php" method="post">
                <!-- Champ pour la note -->
                <label for="note">Note :</label>
                <select name="note" id="note">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <!-- Champ caché pour l'ID de l'article et chemin -->
                <input type="hidden" name="chemin_fichier" value="<?php echo $chemin_fichier; ?>">
                <input type="hidden" name="id_article" value="<?php echo $numero_article; ?>">

                <!-- Champ pour le commentaire -->
                <label for="commentaire">Commentaire :</label>
                <textarea name="commentaire" id="commentaire" placeholder="300 caractères max."></textarea>

                <!-- Bouton de soumission -->
                <button type="submit">Envoyer</button>
            </form>

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