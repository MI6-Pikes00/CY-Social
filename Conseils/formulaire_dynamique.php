<?php
// Démarre la session PHP pour permettre le stockage de données de session
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['utilisateur'])) {
    // Redirection vers la page de connexion
    header("Location: ../Utilisateur/Connection.php");
    exit;
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
        <fieldset style="margin-top: 5%;">
            <legend>Soumettre un nouvel article</legend>
            <form name="form1" method="post" action="">
            <select name="selection" onchange="this.form.submit()">
                <option value="" <?php if (!isset($_POST['selection'])) echo "selected"; ?>>Veuillez sélectionner un type</option>
                <option value="1" <?php if (isset($_POST['selection']) && $_POST['selection'] == 1) echo "selected"; ?>>Article</option>
                <option value="2" <?php if (isset($_POST['selection']) && $_POST['selection'] == 2) echo "selected"; ?>>Citation</option>
                <option value="3" <?php if (isset($_POST['selection']) && $_POST['selection'] == 3) echo "selected"; ?>>Vidéo</option>
            </select>
        </form>
        <?php
        if (isset($_POST['selection'])) {
            if ($_POST['selection'] == 1) {
                echo "<form name='form2' method='post' action='./envoie_formulaire.php' enctype='multipart/form-data'>";
                echo "<label for='titre'>Titre :</label><br>";
                echo "<input type='text' id='titre' name='titre' required><br>";
                echo "<label for='categorie'>Catégorie :</label><br>";
                echo "<select id='categorie' name='categorie' required>";
                echo "<option value=''>Sélectionner une catégorie</option>";
                echo "<option value='Cuisine'>Cuisine</option>";
                echo "<option value='Loisirs'>Loisirs</option>";
                echo "<option value='Maison & Jardin'>Maison & Jardin</option>";
                echo "<option value='Mode & Beauté'>Mode & Beauté</option>";
                echo "<option value='Santé'>Santé</option>";
                echo "<option value='Technologie'>Technologie</option>";
                echo "<option value='Voyages'>Voyages</option>";
                echo "<option value='Autre'>Autre</option>";
                echo "</select><br>";
                echo "<label for='instructions'>Votre texte :</label><br>";
                echo "<textarea id='instructions' name='instructions' required></textarea><br>";
                echo "<label for='image'>Image :</label><br>";
                echo "<input type='file' name='images[]' multiple><br>";
                echo "<label for='video'>Vidéo :</label><br>";
                echo "<input type='file' id='video' name='video' accept='video/*'><br>";
                echo "<input type='hidden' name='type' value='article'>";
                echo "<input type='submit' value='Soumettre'>";
                echo "</form>";
            } elseif ($_POST['selection'] == 2) {
                echo "<form name='form2' method='post' action='./envoie_formulaire.php' enctype='multipart/form-data'>";
                echo "<label for='titre'>Titre :</label><br>";
                echo "<input type='text' id='titre' name='titre' required><br>";
                echo "<label for='instructions'>explication :</label><br>";
                echo "<textarea id='instructions' name='instructions' required></textarea><br>";
                echo "<input type='hidden' name='type' value='citation'>";
                echo "<input type='submit' value='Soumettre'>";
                echo "</form>";
            } elseif ($_POST['selection'] == 3) {
                echo "<form name='form2' method='post' action='./envoie_formulaire.php' enctype='multipart/form-data'>";
                echo "<label for='titre'>Titre :</label><br>";
                echo "<input type='text' id='titre' name='titre' required><br>";
                echo "<label for='video'>Vidéo :</label><br>";
                echo "<input type='file' id='video' name='video' accept='video/*' required><br>";
                echo "<label for='instructions'>Résumé :</label><br>";
                echo "<textarea id='instructions' name='instructions' required maxlength='300'></textarea><br>";
                echo "<input type='hidden' name='type' value='video'>";
                echo "<input type='submit' value='Soumettre'>";
                echo "</form>";
            }
        }
        ?>
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
