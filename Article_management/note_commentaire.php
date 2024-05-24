<?php
$numero_article = $_POST['id_article'];
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    // Rediriger vers la page précédente avec l'article spécifique
    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../Accueil.php';
    $redirect_url = $previous_page . '?id_article=' . $numero_article;

    header("Location: $redirect_url");
    exit();
}

$utilisateur = $_SESSION['utilisateur'];
$chemin_fichier = $_POST['chemin_fichier'];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenu = file_get_contents($chemin_fichier);
    $article = json_decode($contenu, true);
    $note = isset($_POST['note']) ? intval($_POST['note']) : null;
    $commentaire = isset($_POST['commentaire']) ? htmlspecialchars($_POST['commentaire']) : '';

    if ($note !== null) {
        if (!isset($article['notes'])) {
            $article['notes'] = [];
        }
        $article['notes'][] = $note;
    }

    // Ajouter le commentaire si présent
    if (!empty($commentaire)) {
        if (!isset($article['commentaires'])) {
            $article['commentaires'] = [];
        }
        $article['commentaires'][] = [
            'utilisateur' => $utilisateur['nom'] . ' ' . $utilisateur['prenom'],
            'commentaire' => $commentaire
        ];
    }

    $article_json = json_encode($article, JSON_PRETTY_PRINT);

    // Vérification du chemin du fichier JSON avant de l'utiliser
    if (empty($chemin_fichier)) {
        die("Chemin du fichier JSON non défini.");
    }

    // Enregistrement des informations de l'article au format JSON
    if (file_put_contents($chemin_fichier, $article_json) === false) {
        die("Erreur lors de l'enregistrement des données.");
    }

    // Rediriger vers la page précédente avec l'article spécifique
    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../Accueil.php';
    $redirect_url = $previous_page . '?id_article=' . $numero_article;

    header("Location: $redirect_url");
    exit();
}


?>