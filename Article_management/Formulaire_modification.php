<?php
session_start();

$chemin_fichier = "";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: ../Utilisateur/Connection.php");
    exit();
}

$utilisateur_session_info = $_SESSION['utilisateur'];
$utilisateur_email = $utilisateur_session_info['email'];
$numero_article = $_POST['id_article'];

function getOneArticle($id_article, &$chemin_fichier)
{
    error_log("Récupération des données de l'article en cours...");

    $utilisateurs_chemin = '../data/';
    $utilisateurs_fichiers = glob($utilisateurs_chemin . '*', GLOB_ONLYDIR);
    $types_dossiers = ['article-', 'video-', 'citation-'];

    foreach ($utilisateurs_fichiers as $utilisateur_fichier) {
        error_log("fichier :  $utilisateur_fichier.");

        foreach ($types_dossiers as $type_dossier) {
            $chemin_fichier_json = $utilisateur_fichier . '/' . $type_dossier . $id_article . '/' . $type_dossier . $id_article . '.json';
            $chemin_fichier = $chemin_fichier_json;

            if (file_exists($chemin_fichier_json)) {
                $contenu = file_get_contents($chemin_fichier_json);

                if ($contenu !== false) {
                    $article_data = json_decode($contenu, true);
                    $article_data['type'] = rtrim($type_dossier, '-'); // Ajoute le type d'article
                    return $article_data;
                } else {
                    error_log("Erreur lors de la lecture du fichier $chemin_fichier_json.");
                    return null;
                }
            } else {
                error_log("Le fichier $chemin_fichier_json n'existe pas, utilisateur suivant ...");
            }
        }
    }

    return null;
}

$article = getOneArticle($numero_article, $chemin_fichier);

if (!$article || empty($chemin_fichier)) {
    die("Article non trouvé ou chemin du fichier JSON non défini.");
}

// Variables de l'article
$type = $article['type'];
$titre = $article['titre'];
$categorie = isset($article['categorie']) ? $article['categorie'] : '';
$instructions = isset($article['instructions']) ? $article['instructions'] : '';
$image = isset($article['images']) ? $article['images'] : '';
$video = isset($article['video']) ? $article['video'] : '';
$notes = isset($article['notes']) ? $article['notes'] : [];
$commentaires = isset($article['commentaires']) ? $article['commentaires'] : [];

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
        <fieldset>
            <legend>Modifier l'article</legend>
            <form action="./envoye_new_donner.php" method="post" enctype="multipart/form-data">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required value="<?php echo $titre; ?>">

                <?php if ($type === 'article' || $type === 'video') { ?>
                    <label for="categorie">Catégorie :</label>
                    <select id="categorie" name="categorie" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="Cuisine" <?php if ($categorie == "Cuisine") echo "selected"; ?>>Cuisine</option>
                        <option value="Loisirs" <?php if ($categorie == "Loisirs") echo "selected"; ?>>Loisirs</option>
                        <option value="Maison & Jardin" <?php if ($categorie == "Maison & Jardin") echo "selected"; ?>>Maison & Jardin</option>
                        <option value="Mode & Beauté" <?php if ($categorie == "Mode & Beauté") echo "selected"; ?>>Mode & Beauté</option>
                        <option value="Santé" <?php if ($categorie == "Santé") echo "selected"; ?>>Santé</option>
                        <option value="Technologie" <?php if ($categorie == "Technologie") echo "selected"; ?>>Technologie</option>
                        <option value="Voyages" <?php if ($categorie == "Voyages") echo "selected"; ?>>Voyages</option>
                        <option value="Autre" <?php if ($categorie == "Autre") echo "selected"; ?>>Autre</option>
                    </select>
                <?php } ?>

                <?php if ($type === 'article' || $type === 'video') { ?>
                    <label for="instructions">Instructions :</label>
                    <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>
                <?php } ?>

                <?php if ($type === 'citation') { ?>
                    <label for="instructions">Instructions :</label>
                    <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>
                <?php } ?>

                <?php if ($type === 'video') { ?>
                    <label for="instructions">Instructions :</label>
                    <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>
                <?php } ?>

                <?php if ($type === 'article') { ?>
                    <label for="image">Image :</label>
                    <input type="file" name="images[]" multiple>

                    <label for="video">Vidéo :</label>
                    <input type="file" id="video" name="video" accept="video/*">
                <?php } ?>

                <?php if ($type === 'video') { ?>
                    <label for="video">Vidéo :</label>
                    <input type="file" id="video" name="video" accept="video/*">
                <?php } ?>

                <!-- Champs cachés pour les notes -->
                <?php if (!empty($notes)) { ?>
                    <?php foreach ($notes as $index => $note) { ?>
                        <input type="hidden" name="notes[]" value="<?php echo $note; ?>">
                    <?php } ?>
                <?php } ?>

                <!-- Champs cachés pour les commentaires -->
                <?php if (!empty($commentaires)) { ?>
                    <?php foreach ($commentaires as $index => $commentaire) { ?>
                        <input type="hidden" name="commentaires[<?php echo $index; ?>][utilisateur]" value="<?php echo $commentaire['utilisateur']; ?>">
                        <input type="hidden" name="commentaires[<?php echo $index; ?>][commentaire]" value="<?php echo $commentaire['commentaire']; ?>">
                    <?php } ?>
                <?php } ?>

                <input type="hidden" name="email" value="<?php echo $utilisateur_session_info['email']; ?>">
                <input type="hidden" name="id_article" value="<?php echo $numero_article; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">

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