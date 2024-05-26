<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();
include './Article_management/Recherche.php';
$article_recent = obtenir_dernier_article("./data");
$citation_recent = obtenir_derniere_citation("./data");
$video_recent = obtenir_derniere_video("./data");
$article_populaire = obtenir_meilleur_article("./data");
$citation_populaire = obtenir_meilleure_citation("./data");
$video_populaire = obtenir_meilleure_video("./data");



// $article_populaire = trier_articles("meilleure_note", "./data");
// $article_p = $article_populaire[1];

?>
<!-- Ici commence le code de la page html -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY-Social</title>
    <link rel="stylesheet" type="text/css" href="./css/global-style.css">
</head>

<body>

    <!-- Section pour la barre de navigation -->
    <header id="header">
        <a href="./Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="./Accueil.php">Accueil</a></li>
                <li><a href="./Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="./Conseils/formulaire_dynamique.php">Donner un conseil</a></li>
                <!-- Permet d'afficher un bouton d'action différents selon si un utilisateur est connecté à un compte -->
                <li>
                    <?php if (isset($_SESSION['utilisateur'])) { ?>
                        <a href="./Utilisateur/Profil_Utilisateur.php" class="connexion-border"><b>Mon compte</b></a>
                    <?php } else { ?>
                        <a href="./Utilisateur/Connection.php" class="connexion-border"><b>Connexion</b></a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
    </header>


    <main>
        <!-- Section qui affiche l'image et la barre de recherche -->

        <div class="welcome">
            <h1>Bienvenue sur CY-Social</h1>

            <div class="search-container">
                <form action="./Article_management/Recherche_traitement.php" method="get" class="search-bar" autocomplete="off">
                    <input type="text" name="q" placeholder="Conseil...">
                    <button type="submit"><img src="./Ressources/search.png"></button>
            </div>
            </form>
        </div>

        </div>

        <!-- Section des articles les plus populaires -->
        <!-- Nav Bar pour catégorie d'articles, vidéos, citations tendances... -->
        <div class="topic-section">
            <ul class="topic-section-list">
                <li><a href="#" class="active">Tendances</a></li>
                <li><a href="#">Cuisine</a></li>
                <li><a href="#">Sport</a></li>
                <li><a href="#">Mode</a></li>
                <li><a href="#">Voyage</a></li>
                <li><a href="#"><img src="./Ressources/filter-icon-static.png" alt="Filter Icon" class="filter-icon"></a></li>
            </ul>
        </div>

        <div class="container-popular">
            <div class="top-sections">
                <div class="article-preview-section">
                    <h2 class="article-title"><?php echo $article_populaire['titre']; ?></h2>
                    <div class="article-container">
                        <?php
                        $instructions = $article_populaire['instructions'];
                        if (strlen($instructions) > 300) {
                            $instructions = substr($instructions, 0, 300) . '...';
                        }
                        echo htmlspecialchars($instructions);
                        ?>
                        <?php if (isset($article_populaire['images']) && !empty($article_populaire['images'])) { ?>
                            <?php foreach ($article_populaire['images'] as $image) { ?>
                                <img style="margin: 0 10px 15px max-width: 50%; max-height: 50%;" src="<?php echo str_replace('../', './', $image) ?>" alt="Image de l'article">
                            <?php } ?>
                        <?php } ?>
                        <br>
                        <?php if (isset($article_populaire['video']) && !empty($article_populaire['video'])) { ?>
                            <h3>Vidéo(s) :</h3>
                            <center><!--  Centrage de la vidéo sur la page  -->
                                <video controls width="70%" style="margin-bottom: 15px">
                                    <source src="<?php echo $article_populaire['video']; ?>" type="video/mp4">
                                    Votre navigateur ne supporte pas la lecture de vidéos.
                                </video>
                            </center>
                        <?php } ?>
                        <div class="article-container-note">
                            <?php
                            // Vérifie s'il y a des notes
                            if (empty($article_populaire['notes'])) {
                                // Affiche un message si aucun commentaire n'est présent
                                error_log("Aucune note sur ce post pour le moment");
                            } else {
                                echo '<div class="note-circle">' . calculerMoyenne($article_populaire['notes']) . '/5</div>';
                            }
                            ?>
                        </div>
                        <?php echo " <a href='./Article_management/page_afficher_conseils.php?id_article=" . htmlspecialchars($article_populaire['numero_article']) . "'> <button class='learn-more-button'>En savoir plus...</button> </a>" ?>
                    </div>
                </div>

                <!-- Section de prévisualisation de la citation la plus récente -->
                <a style="text-decoration: none;" href="./Article_management/page_afficher_conseils.php?id_article=<?php echo htmlspecialchars($citation_recent['numero_article']); ?>">
                    <div class="citation-preview-section">
                        <div class="citation-header">À regarder</div>
                        <div class="citation-content">
                            <h3><?php echo $citation_recent['titre']; ?></h3>
                            <p><?php echo $citation_recent['instructions']; ?></p>
                        </div>
                        <div class="citation-footer">
                            <div class="pp-citation-image">
                                <?php if (!empty($citation_recent['profil_image'])) { ?>
                                    <img src="<?php echo $citation_recent['profil_image']; ?>" alt="Photo de Profil">
                                <?php } else { ?>
                                    <img src="./Ressources/profil-picture.png" alt="Photo de Profil">
                                <?php } ?>
                            </div>
                            <div class="citation-author">
                                <span class="author-nickname"><?php echo $citation_recent['auteur']; ?></span>
                            </div>
                        </div>
                        <div class="citation-note">
                            <?php
                            // Vérifie s'il y a des notes
                            if (!empty($citation_recent['notes'])) {
                                echo '<div class="note-cercle">' . calculerMoyenne($citation_recent['notes']) . '/5</div>';
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </div>

            <div class="video-preview-section">
                <video src="<?php echo str_replace('../', './', $video_populaire['video']); ?>" controls>
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
            </div>

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