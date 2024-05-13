<?php
session_start();

// Vérifier si l'utilisateur est connecté en vérifiant si les informations de l'utilisateur sont présentes dans la session
if (isset($_SESSION['user'])) {
    // Récupérer les données de l'utilisateur à partir de la session
    $user = $_SESSION['user'];
} else {
    // Rediriger l'utilisateur vers la page de connexion si les informations de l'utilisateur ne sont pas présentes dans la session
    header('Location: ../Accueil.php');
    exit;
}

// Traitement du formulaire de modification s'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $user['nom'] = $_POST['nom'];
    $user['prenom'] = $_POST['prenom'];
    $user['email'] = $_POST['email'];
    $user['age'] = $_POST['age'];
    $user['telephone'] = $_POST['telephone'];
    $user['password'];

    // Chemin complet du fichier CSV dans le dossier 'user-information'
    $nom_fichier = '../data/' . md5($user['email']) . '/user-info.csv';
    echo " nom fichier: $nom_fichier";

    // Modifier les données dans le fichier CSV
    if (file_exists($nom_fichier)) {
        $handle = fopen($nom_fichier, 'w');
        fputcsv($handle, $user);
        fclose($handle);
        echo "Les données ont été mises à jour avec succès.";
    } else {
        echo "Erreur : le fichier utilisateur n'existe pas.";
    }
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
    <header id="header">
        <a href="../Accueil.php" class="logo">CY-Social</a>
        <nav>
            <ul>
                <li><a href="../Accueil.php">Accueil</a></li>
                <li><a href="../Conseils/Conseils.php">Nos conseils</a></li>
                <li><a href="../Conseils/Formulaire_soumission.php">Donner un conseils</a></li>
                <li><a href="Deconnexion.php">Déconnexion</a></li>
                <li><input type="text" placeholder="Rechercher..."><input type="submit" name="rechercher" value="Rechercher" /></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Profil Utilisateur</h1>

        <div class="container_user">
            <fieldset class="formulaire">
                <legend>Information personnel</legend>
                <form action="" method="post">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>"><br>

                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>"><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br>

                    <label for="age">Âge:</label>
                    <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>"><br>

                    <label for="telephone">Téléphone:</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo $user['telephone']; ?>"><br>

                    <input type="submit" value="Modifier">
                </form>
            </fieldset>

            <fieldset class="article">
                <legend>Mes articles</legend>
                <!--
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, corporis nostrum asperiores debitis inventore modi deserunt dignissimos veritatis, accusantium, aspernatur molestias in eum. Fugiat similique ad blanditiis quam aut neque doloribus tempora aliquam exercitationem eaque architecto provident repudiandae, quasi libero dolores dignissimos recusandae atque laborum porro placeat molestias quis ipsa! Officia, natus hic quod rem, quas aspernatur odio sapiente sit facilis nobis, accusamus neque. Sapiente fugit veritatis perspiciatis consequuntur nam dignissimos neque aperiam porro vel deserunt incidunt accusantium veniam necessitatibus molestias rem, distinctio a odit, hic accusamus quasi, voluptatibus nobis magni! Numquam ad, cupiditate culpa obcaecati animi autem fugiat a temporibus facilis blanditiis necessitatibus unde sit vitae pariatur, illum quasi doloribus eos corrupti? Doloremque velit inventore alias? Delectus sunt unde ad ipsam dignissimos obcaecati, laudantium expedita perferendis, voluptas modi hic reprehenderit iusto deserunt saepe veniam nisi cumque? Eligendi, vero porro impedit nobis voluptatibus ratione recusandae eveniet distinctio, nulla aspernatur nisi iste deleniti quibusdam aut mollitia repudiandae nihil modi doloremque dignissimos omnis, ut accusantium explicabo atque est! Et sunt vero sint officia voluptas quod eius. Nemo, adipisci iusto quo nisi exercitationem deleniti eveniet blanditiis eum, deserunt omnis nesciunt laborum animi labore culpa illo, saepe recusandae. Dolores ab necessitatibus a reiciendis impedit perspiciatis blanditiis vero aut corporis dignissimos! Ullam doloremque optio eum sed officiis ipsam itaque velit, dignissimos dolorem dicta hic illo ipsum voluptate sunt? Dolorem, quam! Blanditiis id iure autem velit dolorem, aut accusamus commodi nisi, omnis quam vel quod reiciendis quidem assumenda repellat quia cumque exercitationem et fugit rerum ea eveniet! Iusto deserunt magnam provident consectetur hic incidunt, nam possimus autem cum sed dicta quas ea eveniet ut tenetur, doloribus natus suscipit delectus. Itaque doloribus quibusdam assumenda fugiat id rerum natus nostrum soluta reiciendis, laboriosam at asperiores necessitatibus mollitia aspernatur laudantium exercitationem optio fuga dolore doloremque? Accusantium laboriosam, modi ipsam deserunt a natus, corrupti rem totam quidem maiores asperiores enim libero beatae doloribus quisquam laborum quam laudantium deleniti aspernatur est distinctio dolor. Vero ratione est sapiente, quisquam odit recusandae maiores fugiat? Fugiat nobis maiores veniam esse earum neque? Optio atque veniam dolore minima consequuntur ut debitis? Placeat fuga quis exercitationem, facere velit soluta doloribus cum, harum, magni in modi ipsa minima delectus! Autem, dignissimos. Ab perspiciatis quasi eum commodi aperiam rem facilis voluptas maiores repudiandae dolores temporibus laborum libero pariatur delectus, nostrum eveniet amet assumenda sit officiis earum magnam non? Nostrum voluptate ratione illum pariatur suscipit officiis quasi distinctio, possimus quisquam temporibus et neque soluta molestiae deleniti facere, sint atque tempora earum eos voluptatum natus veniam tenetur rerum? Maxime officiis dolores doloremque autem odit ex temporibus reiciendis eius dolore veniam totam exercitationem nisi, praesentium quod laboriosam repudiandae beatae ratione quidem culpa atque minus numquam! Fugit repellat nostrum cupiditate laudantium quo at? Iste ipsum eum architecto nobis? Quibusdam deleniti eveniet iste praesentium id excepturi commodi magni placeat quod nisi ea quas odio minima laborum error consequatur, quia, voluptas ut eum eaque accusamus ipsa asperiores. Vel veniam voluptatum numquam voluptatibus facere explicabo illum nulla quidem dolores quis. Voluptatum recusandae consectetur est. Consequuntur adipisci aut earum, odit aliquam vel quasi nam praesentium facilis, ratione nesciunt repellat eos doloremque. At, eius. Debitis, cupiditate voluptatem? Quidem amet necessitatibus enim aliquid eaque odit perferendis dolorem tenetur libero ab, praesentium ducimus quis ullam. Veniam, alias unde eum facere tempora error vel rerum rem earum qui. Nam nobis sint, minima provident id porro suscipit eveniet nemo, ipsum voluptate earum, possimus doloribus unde praesentium exercitationem quam dolores consequatur. Eaque consequuntur suscipit soluta animi officia eligendi sequi temporibus cupiditate dignissimos esse! Iusto, autem optio saepe voluptatem delectus aliquid rerum neque mollitia alias? Et quae quos modi alias corporis voluptates incidunt, repudiandae, autem vel eaque facere nisi sunt explicabo minus id ex quisquam dolore fuga nihil suscipit! Voluptatum nisi eveniet quod labore quibusdam ullam laborum quas doloremque, ipsa temporibus natus eligendi incidunt dicta. Nulla error possimus, consectetur facilis sint recusandae natus hic dolorum deserunt, distinctio ea dolorem repellat sit similique quo commodi iste ipsam praesentium quaerat molestias! Accusantium, perspiciatis reiciendis rem voluptatum ipsa facere iure quaerat ab sed molestias, itaque corrupti unde facilis repellendus incidunt consectetur nulla, harum eius odio excepturi totam possimus consequuntur? Aliquam, quos! Eos amet ab quibusdam pariatur veniam eius, praesentium, dolores officia, cumque voluptate ipsum impedit unde consectetur? Vitae odit velit nemo atque ducimus consectetur eveniet, quaerat incidunt placeat minima delectus officiis perspiciatis qui ut non unde praesentium mollitia amet aperiam ipsa quibusdam id cupiditate. Alias dolores quia eaque architecto, maxime assumenda error odio vel! Eligendi rerum expedita, laborum mollitia, fugit iste maiores, iure ut dolor reiciendis quo blanditiis ab repudiandae obcaecati nam ex. Fugit voluptatum totam quo quidem tempore omnis repudiandae sunt iure, saepe architecto deleniti, impedit, officiis rem tempora explicabo magni consequuntur voluptate cumque minus. Consectetur, pariatur illum asperiores ea sunt possimus iusto est expedita corrupti? Consequuntur, itaque laboriosam sint quo doloribus, veniam ratione repellat nemo ullam dolorum quia eveniet? Quisquam maxime cupiditate assumenda totam deleniti illo quae, molestiae dolor! Pariatur adipisci repellendus, dignissimos voluptatum culpa dolore veniam necessitatibus omnis explicabo nostrum tempora in officiis beatae laudantium ipsum sit perferendis vero doloribus recusandae. Quod hic a, perspiciatis at animi architecto numquam quas dignissimos aliquam delectus ducimus accusantium modi, excepturi ratione illum vero quia rem dolorum suscipit eos cumque? Provident quasi reiciendis excepturi perferendis illo a, odio dolores, eveniet aspernatur officia voluptas reprehenderit ullam. Consectetur laudantium excepturi, est distinctio ex animi tempore minima non facere nesciunt quis nihil modi doloribus libero necessitatibus ea explicabo deserunt voluptate facilis molestiae consequatur culpa illo. Velit aliquam, assumenda odio incidunt beatae minima numquam debitis animi illo dolorem non rem sunt dolore placeat illum, id voluptatem soluta ullam et at reprehenderit inventore eius iusto obcaecati! Assumenda officia quam facilis adipisci vitae, quod, dolores corrupti earum itaque doloribus exercitationem fugit consequatur libero delectus. Excepturi dolore, nobis voluptatum, quas odio illum hic praesentium impedit necessitatibus at id quis aliquam nihil quae amet illo vero ea perferendis repellat officiis provident beatae ex corrupti quisquam! Autem molestiae rerum id unde ullam placeat, obcaecati eos, aliquam earum, fugiat a numquam dolores dolor ad? Adipisci, eveniet quia?</p>
        -->
            </fieldset>
        </div>

    </main>
    <footer>
        <p>
            <small>
                Copyrights 2024 - Luc Letailleur eet Thomas Herriau
            </small>
        </p>
    </footer>
</body>

</html>