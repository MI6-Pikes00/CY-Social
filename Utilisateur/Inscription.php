<!-- Ici le php sert a s'inscrire sur CY-Social il redirige ensuite vers le portail de connexion -->
<?php
// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $prenom = $_POST['prenom'];    
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    // $age = $_POST['age'];
    // $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    // $confirm_password = $_POST['confirm_password'];

    // Messages d'information dans la console php
    error_log("Données du formulaire récupéré");

    // Vérification si les mots de passe correspondent
    /* if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
    */
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
                $ligne = array($prenom, $nom, $email, $password);
                //$ligne = array($prenom, $nom, $email, $age, $telephone, $password);

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
    //}
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
        
        <div class="container_connection">
            <div class="container_connection_bgd_img_left">
                <img src="../Ressources/connexion-image-sized.jpg" alt="connexion-image">
            </div>
            <div class="container_connection_form_right">
                <form name="#" method="post">
                    
                    <div class="profile-picture" onclick="document.getElementById('file-input').click();">
                        <input type="file" id="file-input" accept="image/*" style="display: none;" onchange="loadFile(event)">
                    </div>
                    
                    <div class="name-fields">
                        <div class="field">
                            <label for="ufname">Prénom</label>
                            <input type="text" name="prenom" required>
                        </div>
                        <div class="field">
                            <label for="uname">Nom</label>
                            <input type="text" name="nom" required>
                        </div>
                    </div>

                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>

                    <label for="pswd">Mot de passe</label>
                    <input type="password" id="pswd" name="password" placeholder="6+ characters" style="letter-spacing: 1px; margin-bottom: 30px; text-indent: 3px" required>

                    <button type="submit">Create account</button>

                    <span class="pswd" style="text-align: center; margin-top: 18px">Déjà membre ? <a href="Connection.php">Se connecter</a></span>
                </form>
                <script>
                    function loadFile(event) {
                        var image = document.querySelector('.profile-picture');
                        image.style.backgroundImage = 'url(' + URL.createObjectURL(event.target.files[0]) + ')';
                    }
                </script>
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