<!-- Ici le php à plusieurs fonction, premièrement il vérifie qu'un utilisateur soit bien connecter puis il permet d'afficher ses information, ses articles, de pourvoir les modifiers -->
<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session
if (isset($_SESSION['utilisateur'])) {
    // Récupérer les données de l'utilisateur à partir de la session
    $utilisateur_session_info = $_SESSION['utilisateur'];
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
    $utilisateur['nom'] = $_POST['nom'];
    $utilisateur['prenom'] = $_POST['prenom'];
    $utilisateur['email'] = $_POST['email'];

    // Récupérer le mot de passe de l'utilisateur
    $utilisateur['password'] = $utilisateur_session_info['password'];

    // NE FONCTIONNE PAS ENCORE
    // 
    // $image_chemin_fichier = "";
    // if (!empty($_FILES['fichier_racine']['nom'])) {
    //     $image_nom = basename($_FILES['fichier_racine']['nom']);
    //     $image_tmp = $_FILES['fichier_racine']['tmp_nom'];
    //     $image_chemin = $dossier_utilisateur . '/' . $image_nom;

    //     // Déplacement du fichier vidéo vers le dossier approprié
    //     if (move_uploaded_file($image_tmp, $image_chemin)) {
    //         $image_chemin_fichier = $image_chemin;
    //         error_log("Le fichier vidéo $image_nom a été chargé avec succès.");
    //     } else {
    //         error_log("Erreur lors du chargement du fichier vidéo $image_nom");
    //     }
    // }

    // $utilisateur['profil_image'] = $image_chemin_fichier;

    $utilisateur['profil_image'] = $utilisateur_session_info['profil_image'];

    // Chemin complet du fichier CSV dans le dossier 'user-info'
    $nom_fichier = '../data/' . md5($utilisateur['email']) . '/user-info.csv';

    // Modifier les données dans le fichier CSV
    if (file_exists($nom_fichier)) {
        // Ouvre le fichier de destination pour écrire en écrasant les anciennes données
        $handle = fopen($nom_fichier, 'w');

        // Écrit les données en langage csv pour les accent et caractère spéciaux pour une meilleur retranscription par la suite
        fputcsv($handle, $utilisateur);

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
function getUserArticles($utilisateur_email)
{
    // Message d'information
    error_log("Récupération des données de l'article en cours...");

    // Chemin complet du dossier utilisateur
    $dossier_utilisateur = '../data/' . md5($utilisateur_email);

    // Initialisation d'un tableau pour stocker les articles
    $articles = array();

    // Vérifie si le dossier utilisateur existe
    if (file_exists($dossier_utilisateur) && is_dir($dossier_utilisateur)) {

        // Récupération de tous les dossiers d'articles dans le dossier utilisateur
        $dossiers_articles = glob($dossier_utilisateur . '/*', GLOB_ONLYDIR);

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
                <li><a href="../Conseils/formulaire_dynamique.php">Donner un conseils</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>

        <div class="container_utilisateur">
            <!-- Permet d'afficher et de modifier les informations d'utilisateur -->
            <fieldset class="container-info-perso">
                <legend>Vos informations</legend>
                <form action="" method="post">

                    <?php if (!empty($utilisateur_session_info['profil_image'])) { ?>
                        <img src="<?php echo $utilisateur_session_info['profil_image']; ?>" alt="Description de l'image" class="image95x95">
                    <?php } else { ?>
                        <div class="profile-image" onclick="document.getElementById('fichier_racine').click();">
                            <input type="file" id="fichier_racine" name="fichier_racine" accept="image/*" style="display: none;" onchange="loadFile(event)">
                        </div>
                    <?php } ?>

                    <label for="nom">Nom:</label>
                    <!-- Utilise les variables PHP pour remplir les valeurs des champs -->
                    <input type="text" id="nom" name="nom" value="<?php echo $utilisateur_session_info['nom']; ?>"><br>

                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $utilisateur_session_info['prenom']; ?>"><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $utilisateur_session_info['email']; ?>"><br>

                    <input type="submit" value="Modifier">
                </form>
            </fieldset>
            <!-- Affichage les différents articles, et option pour les visualiser, les modifier ou les supprimer -->
            <fieldset class="container-article-perso">
                <legend>Mes articles</legend>
                <?php foreach (getUserArticles($utilisateur_session_info['email']) as $article) : ?>
                    <fieldset>
                        <legend style="font-size: 20px"><?php echo $article['titre']; ?></legend>
                        <?php if (isset($article['categorie'])) { ?>
                            <p style="padding:5px">Catégorie: <?php echo $article['categorie']; ?></p>
                        <?php } ?>

                        <div class="preview-article-perso-content">
                            <?php
                                $instructions = $article['instructions'];
                                if (strlen($instructions) > 300) {
                                    $instructions = substr($instructions, 0, 300) . '...';
                                }
                                echo htmlspecialchars($instructions);
                            ?>
                        </div>
                        
                        <!-- Section de formulaire pour faire des buttons interactif -->

                        <?php $articleId = $article['numero_article']; ?>
                        <!-- Button pour supprimer -->
                            <form id="deleteForm-<?php echo $articleId; ?>" action="../Article_management/supprimer_article.php" method="post">
                                <input type="hidden" name="id_article" value="<?php echo $articleId; ?>">
                                <button type="button" class="bouton_supprimer" onclick="showPopup('<?php echo $articleId; ?>')">
                                    <img src="../Ressources/trash-icon.png" alt="Supprimer" class="icon-supprimer">
                                </button>
                            </form>
                        
                            <!-- Fenêtre de confirmation de suppression -->
                            <div id="confirmationPopup-<?php echo $articleId; ?>" class="popup" style="display: none;">
                                <div class="popup-content">
                                    <h2>Confirmez-vous la suppression ?</h2>
                                    <p>Si vous confirmez, votre publication sera <br> définitivement effacée</p>
                                    <div class="popup-buttons">
                                        <button class="btn-supprimer" onclick="confirmAction('<?php echo $articleId; ?>')">
                                            <img src="../Ressources/trash-icon.png" alt="Supprimer" class="icon-supprimer">
                                            <b>Supprimer</b>
                                        </button>
                                        <button class="btn-annuler" onclick="cancelAction('<?php echo $articleId; ?>')">
                                            <img src="../Ressources/cancel-icon.png" alt="Annuler" class="icon-annuler">
                                            <b>Annuler</b>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <!-- Script pour confirmer la suppression du post -->
                        <script>
                            function showPopup(articleId) {
                                document.getElementById('confirmationPopup-' + articleId).style.display = 'flex';
                            }

                            function confirmAction(articleId) {
                                document.getElementById('deleteForm-' + articleId).submit();
                            }

                            function cancelAction(articleId) {
                                document.getElementById('confirmationPopup-' + articleId).style.display = 'none';
                            }
                        </script>


                        <!-- Button pour voir -->
                        <form action="../Article_management/page_afficher_conseils.php" method="post">
                            <input type="hidden" name="id_article" value="<?php echo $article['numero_article']; ?>">
                            <button type="submit" name="submit" class="bouton_voir">Voir</button>
                        </form>
                        <!-- Button pour modifier -->
                        <form action="../Article_management/Formulaire_modification.php" method="post">
                            <input type="hidden" name="id_article" value="<?php echo $article['numero_article']; ?>">
                            <button type="submit" name="submit" class="bouton_modifier">Modifier</button>
                        </form>


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