<?php
session_start();
// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session
if (isset($_SESSION['user'])) {
    // Récupérer les données de l'utilisateur à partir de la session
    $user = $_SESSION['user'];
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
    $user['age'] = $_POST['age'];
    $user['telephone'] = $_POST['telephone'];
    $user['password'];

    // Chemin complet du fichier CSV dans le dossier 'user-information'
    $nom_fichier = '../data/' . md5($user['email']) . '/user-info.csv';
    echo " nom fichier: $nom_fichier";

    // Modifier les données dans le fichier CSV
    if (file_exists($nom_fichier)) {
        $handle = fopen($nom_fichier, 'w');
        fputcsv($handle, $user);
        fclose($handle);
        echo "Les données ont été mises à jour avec succès.";
    } else {
        echo "Erreur : le fichier utilisateur n'existe pas.";
    }
}

function getArticles($user_email)
{
    // Chemin relatif vers le dossier 'data'
    $dossier = '../data';

    // Chemin complet du dossier utilisateur
    $dossier_utilisateur = $dossier . '/' . md5($user_email);

    // Initialisation d'un tableau pour stocker les articles
    $articles = array();

    // Vérifie si le dossier utilisateur existe
    if (file_exists($dossier_utilisateur) && is_dir($dossier_utilisateur)) {
        // Récupération de tous les fichiers dans le dossier utilisateur
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

    // Retourne le tableau d'articles
    return $articles;
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
    <header id="header">
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
                <li><input type="text" placeholder="Rechercher..."><input type="submit" name="rechercher" value="Rechercher" /></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Profil Utilisateur</h1>

        <div class="container_user">
            <fieldset class="formulaire">
                <legend>Information personnel</legend>
                <form action="" method="post">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>"><br>

                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>"><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br>

                    <label for="age">Âge:</label>
                    <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>"><br>

                    <label for="telephone">Téléphone:</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo $user['telephone']; ?>"><br>

                    <input type="submit" value="Modifier">
                </form>
            </fieldset>

            <fieldset class="article">
                <legend>Mes articles</legend>
                <?php foreach (getArticles($user['email']) as $article) : ?>
                    <fieldset>
                        <legend><?php echo $article['titre']; ?></legend>
                        <p>Catégorie: <?php echo $article['categorie']; ?></p>
                        <div><?php echo $article['instructions']; ?></div>
                        <div class="container_button_modifiez_supprimer">
                            <form action="../page_afficher_conseils.php" method="post">
                                <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                                <button type="submit" name="submit" class="bouton_voir">Voir</button>
                            </form>
                            <form action="../Article_management/modifier_article.php" method="post">
                                <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
                                <input type="hidden" name="num_article" value="<?php echo $article['numero_article']; ?>">
                                <button type="submit" name="submit" class="bouton_modifier">Modifier</button>
                            </form>
                            <form action="../Article_management/supprimer_article.php" method="post">
                                <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
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
        <p>
            <small>
                Copyrights 2024 - Luc Letailleur eet Thomas Herriau
            </small>
        </p>
    </footer>
</body>

</html>