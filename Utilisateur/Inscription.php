<?php
// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Chemin relatif vers le dossier 'user-information'
        $dossier = 'user-information';

        // Crée le dossier s'il n'existe pas déjà
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        // Chemin complet du fichier CSV dans le dossier 'user-information'
        $nom_fichier = $dossier . '/' . md5($email) . '.csv'; // Utilise l'email pour le nom de fichier, mais vous pouvez utiliser un autre identifiant unique

        // Vérifie si l'utilisateur existe déjà
        if (file_exists($nom_fichier)) {
            echo "L'utilisateur existe déjà.";
        } else {
            // Ouvre le fichier CSV en mode écriture, en créant le fichier s'il n'existe pas
            $handle = fopen($nom_fichier, 'a');

            // Vérifie si l'ouverture du fichier a réussi
            if ($handle !== false) {
                // Écriture des données dans le fichier CSV
                $ligne = array($nom, $prenom, $email, $age, $telephone, $password);
                fputcsv($handle, $ligne);

                // Fermeture du fichier
                fclose($handle);

                echo "Les données ont été enregistrées dans le fichier $nom_fichier avec succès.";
            } else {
                echo "Erreur lors de l'ouverture du fichier $nom_fichier.";
            }
        }
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
                <legend>Inscription</legend>
                    <input type="text" name="nom" placeholder="Nom" required="required"/>
                    <input type="text" name="prenom" placeholder="Prénom" required="required"/>
                    <input type="text" name="email" placeholder="Email" required="required"/>
                    <input type="number" name="age" placeholder="Age" min="18" max="100" required="required"/>
                    <input type="tel" name="telephone" placeholder="Téléphone"/>
                    <input type="password" name="password" placeholder="Mot de passe" required="required">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required="required">
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