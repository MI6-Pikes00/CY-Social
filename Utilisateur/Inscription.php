<!-- Ici le php sert a s'inscrire sur CY-Social il redirige ensuite vers le portail de connexion -->
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

    // Messages d'information dans la console php
    error_log("Données du formulaire récupéré");

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {

        // Chemin complet du fichier CSV dans le dossier 'data'
        $dossier_utilisateur = '../data/' . md5($email);

        // Vérifie si le dossier utilisateur existe, sinon le crée
        if (!file_exists($dossier_utilisateur)) {
            mkdir($dossier_utilisateur, 0777, true); // Crée le dossier avec les permissions 0777
        }

        // Chemin complet du fichier CSV dans le dossier utilisateur
        $nom_fichier = $dossier_utilisateur . '/user-info.csv'; // Utilise l'email pour le nom de fichier, mais vous pouvez utiliser un autre identifiant unique

        // Vérifie si l'utilisateur existe déjà
        if (file_exists($nom_fichier)) {
            // Messages d'information dans la console php 
            error_log("L'utilisateur existe déjà.");
            // Renvoie vers le formulaire de connexion
            return header("Location: ./Connection.php");
        } else {

            // Ouvre le fichier CSV en mode écriture, en créant le fichier s'il n'existe pas
            $handle = fopen($nom_fichier, 'a');

            // Vérifie si l'ouverture du fichier a réussi
            if ($handle !== false) {
                // Écriture des données dans le fichier CSV
                $ligne = array($nom, $prenom, $email, $age, $telephone, $password);

                // Écrit les données en langage csv pour les accent et caractère spéciaux pour une meilleur retranscription par la suite
                fputcsv($handle, $ligne);

                // Fermeture du fichier
                fclose($handle);

                // Message de confirmation de l'enregistrement de l'user
                error_log("Les données ont été enregistrées dans le fichier $nom_fichier avec succès.");
            } else {
                echo "Erreur lors de l'ouverture du fichier $nom_fichier.";
            }
        }
    }
    // Envoie vers le formulaire de connexion une fois l'inscription terminé
    return header("Location: ./Connection.php");
} else {
    // Gestion d'un accès direct au script
    echo "Erreur : Accès non autorisé.";
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
        <div class="container_inscription">
            <fieldset class="formulaire">
                <legend>Inscription</legend>
                <!-- Formulaire d’inscription avec des inputs de type différents suivant l'information que l'on veut collecté et
                    qui va exécuter le scripts si dessus quand on clique sur le bouton submit -->
                <form name="inscription" method="post" action="#">
                    <input type="text" name="nom" placeholder="Nom" required="required" />
                    <input type="text" name="prenom" placeholder="Prénom" required="required" />
                    <input type="email" name="email" placeholder="Email" required="required" />
                    <input type="number" name="age" placeholder="Age" min="18" max="100" required="required" />
                    <input type="tel" name="telephone" placeholder="Téléphone" />
                    <input type="password" name="password" placeholder="Mot de passe" required="required">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required="required">
                    <input type="submit" name="inscription" value="S'inscrire" />
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