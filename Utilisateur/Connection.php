<!-- Ici le php permet de connecter un utilisateur qui est inscrit via son mail et mot de passe
Elle ajoute tout les information nécessaire dans la session-->
<?php
// Création de la session pour dire que l'utilisateur est connecté
session_start();

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Chemin complet du fichier CSV dans le dossier 'data'
    $dossier_utilisateur = '../data/' . md5($email);

    // Vérifie si le dossier utilisateur existe, sinon le crée
    if (!file_exists($dossier_utilisateur)) {
        mkdir($dossier_utilisateur, 0777, true); // Crée le dossier avec les permissions 0777
    }

    // Chemin complet du fichier CSV dans le dossier utilisateur
    $nom_fichier = $dossier_utilisateur . '/user-info.csv';

    // Vérifie si le fichier utilisateur existe
    if (file_exists($nom_fichier)) {
        // Ouvre le fichier CSV en mode lecture
        $handle = fopen($nom_fichier, 'r');

        // Vérifie si l'ouverture du fichier a réussi
        if ($handle !== false) {
            // Lit chaque ligne du fichier CSV
            while (($ligne = fgetcsv($handle, 1000, ',')) !== false) {
                // Vérifie si l'email et le mot de passe correspondent
                if ($ligne[2] === $email && $ligne[3] === $password) { // L'email est en index 2 et le mot de passe en index 5 (vous pouvez ajuster cela selon votre structure de fichier CSV)

                    // Message d'information que l'utilisateur est bien connecter a son compte
                    error_log("Connexion réussie pour $email.");

                    // Stocke toutes les données de l'utilisateur dans la session pour des utilisations future
                    $_SESSION['utilisateur'] = array(
                        'nom' => $ligne[0],
                        'prenom' => $ligne[1],
                        'email' => $ligne[2],
                        'password' => $ligne[3],
                        'profil_image' => $ligne[4]
                    );

                    // Redirige l'utilisateur vers la page de profil
                    return header('Location: Profil_Utilisateur.php');
                }
            }
            // Si l'utilisateur n'est pas trouvé
            echo "Identifiants incorrects.";

            // Fermeture du fichier
            fclose($handle);
        } else {
            echo "Erreur lors de l'ouverture du fichier $nom_fichier.";
        }
    } else {
        echo "Aucun compte trouvé pour $email.";
    }
}
?>

<!-- Ici commence le code de la page html -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global-style.css">
    <link rel="stylesheet" href="../css/form_design.css">
    <title>Connexion</title>
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
                <li><a href="Connection.php">Connexion</a>/<a href="Inscription.php">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <main>

        <div class="container_connection">
            <div class="container_connection_bgd_img_left">
                <img src="../Ressources/connexion-image-sized.jpg" alt="connexion-image">
            </div>
            <div class="container_connection_form_right">
                <form name="#" method="post">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>

                    <label for="pswd">Mot de passe</label>
                    <input type="password" id="pswd" name="password" style="letter-spacing: 1px" required>

                    <span class="pswd">Mot de passe oublié ? <a href="#">Cliquez ici</a></span>

                    <button type="submit">Se connecter</button>

                    <span class="pswd" style="text-align: center; margin-top: 18px">Pas encore inscrit ? <a href="Inscription.php">Créer un compte</a></span>
                </form>
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