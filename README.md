# CY-Social

CY-Social est un projet réalisé dans le cadre du semestre 2 de la deuxième année de prépa intégrée. Il s'agit d'un site web permettant aux utilisateurs de consulter, créer des articles, laisser des commentaires, ainsi que de créer un compte pour publier et gérer leurs propres articles.

## Configuration de php.ini

Pour assurer le bon fonctionnement de CY-Social, veuillez modifier le fichier `php.ini` avec les paramètres suivants :

```ini
memory_limit = 256M
post_max_size = 50M
upload_max_filesize = 30M
```

Assurez-vous que ces valeurs sont correctement configurées pour éviter tout problème lié à la taille des fichiers téléchargés ou des données postées sur le site.

## Fonctionnalités

- Consultation d'articles : Les utilisateurs peuvent parcourir les articles existants pour découvrir du contenu intéressant.
- Création d'articles : Les utilisateurs ont la possibilité de rédiger et de publier leurs propres articles sur la plateforme.
- Commentaires : Les utilisateurs peuvent laisser des commentaires sur les articles pour interagir avec leur contenu.
- Gestion de compte : Les utilisateurs peuvent créer un compte sur CY-Social pour gérer leurs articles publiés et leurs commentaires.

## Installation

1. Clonez ce dépôt sur votre machine locale.
2. Assurez-vous que votre environnement PHP est configuré avec les paramètres spécifiés dans `php.ini`.
3. Importez la base de données fournie dans le dossier `database`.
4. Configurez les informations de la base de données dans le fichier `config.php`.
5. Lancez l'application sur votre serveur local.

## Contribution

Les contributions sont les bienvenues ! Si vous souhaitez améliorer CY-Social, n'hésitez pas à soumettre une demande de pull avec vos modifications.

## Auteurs

- [Thomas Herriau](lien_vers_le_profil_github)
- [Luc Letailleur](lien_vers_le_profil_github)