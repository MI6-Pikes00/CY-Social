<?php
// Inclure le fichier contenant les fonctions de recherche
include './Recherche.php';

// Vérifier si le formulaire de recherche a été soumis
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['q'])) {
    // Récupérer le terme de recherche depuis le formulaire
    $mot_cle = $_GET['q'];

    // Chemin relatif vers le dossier 'data'
    $data_folder = '../data';

    // Utilisation de la fonction de recherche pour trouver les articles correspondants
    $articles_trouves = rechercher_articles($mot_cle, $data_folder);

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
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/formulaire_dynamique.php">Donner un conseils</a></li>
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
            <?php foreach ($articles_trouves as $article) : ?>
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