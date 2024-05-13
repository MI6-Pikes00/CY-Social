<?php
// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Chemin relatif vers le dossier 'user-information'
    $dossier = 'user-information';

    // Chemin complet du fichier CSV dans le dossier 'user-information'
    $nom_fichier = $dossier . '/' . md5($email) . '.csv';

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
                    echo "Connexion réussie pour $email.";
                    header('Location: Profil_Utilisateur.php?email=' . urlencode($email));
                    exit;
                    // Ici, vous pouvez rediriger l'utilisateur vers une autre page ou effectuer d'autres actions nécessaires après la connexion
                }
            }

            // Fermeture du fichier
            fclose($handle);
        } else {
            echo "Erreur lors de l'ouverture du fichier $nom_fichier.";
        }
    } else {
        echo "Aucun compte trouvé pour $email.";
    }
} else {
    // Gestion d'un accès direct au script
    echo "Erreur : Accès non autorisé.";
}
?>


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
    <header id="header">
        <a href="#" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="Connection.php">Connexion</a>/<a href="Inscription.php">Inscription</a></li>
                <li><input type="text" placeholder="Rechercher..."><input type="submit" name="rechercher" value="Rechercher" /></li>
            </ul>
        </nav>
    </header>
    <main>
        <form name="inscription" method="post" action="#">
            <fieldset>
                <legend>Connection</legend>
                    <input type="text" name="email" placeholder="Email" required="required"/>
                    <input type="password" name="password" placeholder="Mot de passe" required="required">
                    <input type="submit" name="inscription" value="S'inscrire"/>
            </fieldset>
        </form>
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