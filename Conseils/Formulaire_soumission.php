<!-- ICI le php à plusieurs fonction, premièrement il vérifie qu'un utilisateur soit bien connecter puis il permet de soumettre le formulaire qui permet l'enregistrement d'article -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user'])) {
    // Redirection vers la page de connexion
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

// Récupération des informations de l'utilisateur connecté
$user = $_SESSION['user'];
$user_email = $user['email']; // Supposons que l'email soit utilisé comme identifiant de l'utilisateur


// Chemin complet du dossier utilisateur
$dossier_utilisateur = '../data/' . md5($user_email);

// Vérifie si le dossier utilisateur existe, sinon le crée
if (!file_exists($dossier_utilisateur)) {
    header("Location: ../Utilisateur/Connection.php");
    exit;
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $titre = $_POST['titre'];
    $categorie = $_POST['categorie'];
    $instructions = $_POST['instructions'];

    // Génération d'un identifiant unique pour l'article
    $num_article = uniqid();

    // Date et heure de création de l'article
    $date_creation = date("Y-m-d H:i:s");

    // Nom de l'auteur de l'article
    $auteur = $user['nom'] . ' ' . $user['prenom'];

    // Chemin complet du fichier JSON dans le dossier utilisateur avec un numéro aléatoire unique
    $nom_fichier = $dossier_utilisateur . '/' . "article-" . $num_article . '.json';

    // Traitement de l'image (NE FONCTIONNE PAS POUR L'INSTANT)
    $image_nom = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_dossier = "uploads/$user_email/images/";
    move_uploaded_file($image_tmp, $image_dossier . $num_article . "_" . $image_nom);

    // Traitement de la vidéo (NE FONCTIONNE PAS POUR L'INSTANT)
    $video_nom = $_FILES['video']['name'];
    $video_tmp = $_FILES['video']['tmp_name'];
    $video_dossier = "uploads/$user_email/videos/";
    move_uploaded_file($video_tmp, $video_dossier . $num_article . "_" . $video_nom);

    // Enregistrement des informations de l'article au format JSON
    $article_data = array(
        'numero_article' => $num_article,
        'date_creation' => $date_creation,
        'auteur' => $auteur,
        'titre' => $titre,
        'categorie' => $categorie,
        'instructions' => $instructions
    );
    $article_json = json_encode($article_data);
    file_put_contents($nom_fichier, $article_json);

    // Redirection après soumission de l'article pour l'instant juste un message
    error_log("Article bien enregistrer");
    return header('Location: ../Utilisateur/Profil_Utilisateur.php');
} else {
    // Redirection si le formulaire n'a pas été soumis pour l'instant juste un message
    echo "Formulaire non envoyé";
}
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
        <fieldset>
            <legend>Créer un nouvel article</legend>
            <!-- Formulaire pour ajouter un article avec des inputs de type différents suivant l'information que l'on veut collecté et
                    qui va exécuter le scripts si dessus quand on clique sur le bouton submit -->
            <form action="#" method="post" enctype="multipart/form-data">

                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>

                <!-- Menu avec plusieurs option de catégorie -->
                <label for="categorie">Catégorie :</label>
                <select id="categorie" name="categorie" required>
                    <option value="">Sélectionner une catégorie</option>
                    <option value="Technologie">Technologie</option>
                    <option value="Santé">Santé</option>
                    <option value="Cuisine">Cuisine</option>
                    <option value="Autre">Autre</option>
                </select>

                <!-- Section pour rentrer le texte -->
                <label for="instructions">Instructions :</label>
                <textarea id="instructions" name="instructions" required></textarea>

                <!-- Section pour rentrer UNE image -->
                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*">

                <!-- Section pour rentrer UNE vidéo -->
                <label for="video">Vidéo :</label>
                <input type="file" id="video" name="video" accept="video/*">

                <input type="submit" name="envoyer" value="Soumettre">
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