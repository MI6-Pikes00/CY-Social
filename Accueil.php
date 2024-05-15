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
                <?php if (isset($_SESSION['user'])) { ?>
                    <a href="./Utilisateur/Profil_Utilisateur.php" class="connexion-border"><b>Profil</b></a>
                <?php } else { ?>
                    <a href="./Utilisateur/Connection.php" class="connexion-border"><b>Connexion</b></a>
                <?php } ?>
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

        <section class="popular-articles">
            <h2>Les plus populaires</h2>
            <p class="carre"> Contiendra les articles les plus populaires </p>
        </section>

        <!-- Section des articles les plus récents -->

        <section class="recent-articles">
            <h2>Les plus récents</h2>
            <p class="carre"> Contiendra les articles les plus récents </p>
        </section>

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