<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start()
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
                <li><a href="./Conseils/Formulaire_soumission.php">Donner un conseil</a></li>
                <!-- Permet d'afficher un bouton d'action différents selon si un utilisateur est connecté à un compte -->
                <li>
                    <?php if (isset($_SESSION['user'])) { ?>
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
                <form action="" method="get" class="search-bar" autocomplete="off">
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
                    <h2 class="article-title">Titre de l'article</h2>
                    <div class="article-container">
                    <img src="chemin-vers-votre-image.jpg" alt="Aperçu de l'article" class="article-preview-image">
                    <button class="learn-more-button">En savoir plus...</button>
                    </div>
                </div>
                
                <div class="citation-preview-section">
                    <div class="citation-header">READ ALSO</div>
                    <div class="citation-content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate</p>
                    </div>
                    <div class="citation-footer">
                        <div class="pp-citation-image">
                            <img src="../Ressources/profil-picture.png" alt="Round Image">
                        </div>
                        <div class="citation-author">
                            <span class="author-nickname">Nickname Name</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="video-preview-section">
                <iframe src="../Ressources/video" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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