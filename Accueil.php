<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY-Social</title>
    <link rel="stylesheet" type="text/css" href="./css/global-style.css">
</head>

<body>

    
    <header id="header">
        <a href="Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="./Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="./Conseils/Formulaire_soumission.php">Donner un conseil</a></li>
                <li><a href="./Utilisateur/Connection.php" class="connexion-border"><b>Connexion</b></a></li>
            </ul>
        </nav>
    </header>
    

    <main>
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



        <section>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae hic non nemo, porro nesciunt deleniti voluptatem delectus ut maiores neque commodi sunt voluptatum exercitationem facilis quo harum similique blanditiis. Quo!</p>
        </section>
        <!-- Rectangle des articles les plus populaires -->
        <section class="popular-articles">
            <h2>Les plus populaires</h2>
            <p class="carre"> Contiendra les articles les plus populaires </p>
        </section>

        <!-- Rectangle des articles les plus récents -->
        <section class="recent-articles">
            <h2>Les plus récents</h2>
            <p class="carre"> Contiendra les articles les plus récents </p>
        </section>

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