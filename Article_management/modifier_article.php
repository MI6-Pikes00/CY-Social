<?php
//Récupération des données pour effectuer le traitement
$user_email = $_POST['email'];
$num_article = $_POST['num_article'];

// Chemin relatif vers le dossier 'data'
$dossier = '../data';

// Chemin complet du fichier JSON dans le dossier utilisateur
$nom_fichier = $dossier . '/' . md5($user_email) . '/article-' . $num_article . '.json';

// Vérifie si le fichier existe
if (file_exists($nom_fichier)) {
    // Lit le contenu du fichier JSON
    $contenu = file_get_contents($nom_fichier);

    // Décodage des données JSON
    $article = json_decode($contenu, true);

    // Ajoute les valeurs de l'email et du numéro d'article à la chaîne de requête
    $query_string = http_build_query(array_merge($article, ['email' => $user_email, 'num_article' => $num_article]));

    // Redirige vers la page de soumission de formulaire avec les données de l'article
    header("Location: Formulaire_modification.php?" . $query_string);
    exit;
} else {
    // Le fichier n'existe pas, redirige vers une page d'erreur ou effectue d'autres actions nécessaires
    header("Location: erreur.php");
    exit;
}
?>

