<!-- Ici le php permet de connecter un user qui est inscrit via son mail et mot de passe
Elle ajoute tout les information nécessaire dans la session-->
<?php
// Création de la session pour dire que l'user est connecté
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
                if ($ligne[2] === $email && $ligne[5] === $password) { // L'email est en index 2 et le mot de passe en index 5 (vous pouvez ajuster cela selon votre structure de fichier CSV)

                    // Message d'information que l'user est bien connecter a son compte
                    error_log("Connexion réussie pour $email.");

                    // Stocke toutes les données de l'utilisateur dans la session pour des utilisations future
                    $_SESSION['user'] = array(
                        'nom' => $ligne[0],
                        'prenom' => $ligne[1],
                        'email' => $ligne[2],
                        'age' => $ligne[3],
                        'telephone' => $ligne[4],
                        'password' => $ligne[5]
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
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="Connection.php">Connexion</a>/<a href="Inscription.php">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container_connection">
            <fieldset style="width: 25%;">
                <!-- Formulaire de connection qui va exécuter le scripts si dessus quand on clique sur le bouton submit -->
                <legend>Connection</legend>
                <form name="#" method="post">
                    <input type="email" name="email" placeholder="Email" required="required" />
                    <input type="password" name="password" placeholder="Mot de passe" required="required">
                    <input type="submit" name="inscription" value="Se connecter" />
                </form>
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