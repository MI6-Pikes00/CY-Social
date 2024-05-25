<?php

function calculerMoyenne($notes) {
    if (empty($notes)) {
        return 0;
    }

    $total = array_sum($notes);
    $nombreDeNotes = count($notes);

    return $total / $nombreDeNotes;
}

function get_all_articles($data_folder)
{
    $articles = array();

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($data_folder) && is_dir($data_folder)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($data_folder . '/*', GLOB_ONLYDIR);

        // Liste des types de dossiers à vérifier
        $types_dossiers = ['article-', 'video-', 'citation-'];

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Parcours des types de dossiers
            foreach ($types_dossiers as $type_dossier) {
                // Récupération de tous les sous-dossiers spécifiques (articles, vidéos, citations) dans le dossier utilisateur
                $sous_dossiers = glob($dossier_utilisateur . '/' . $type_dossier . '*', GLOB_ONLYDIR);

                // Parcours de chaque sous-dossier spécifique
                foreach ($sous_dossiers as $sous_dossier) {
                    // Récupération de tous les fichiers JSON dans le sous-dossier
                    $fichiers = glob($sous_dossier . '/*.json');

                    // Parcours de chaque fichier pour récupérer les données
                    foreach ($fichiers as $fichier) {
                        // Récupération du contenu du fichier
                        $contenu = file_get_contents($fichier);

                        // Vérification si la lecture du fichier s'est bien passée
                        if ($contenu !== false) {
                            // Décodage des données JSON
                            $article = json_decode($contenu, true);

                            // Ajout de l'article au tableau d'articles
                            $articles[] = $article;
                        } else {
                            // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                            error_log("Erreur lors de la lecture du fichier $fichier.");
                        }
                    }
                }
            }
        }
    }

    return $articles;
}

function rechercher_articles($mot_cle, $data_folder)
{
    // Récupération de tous les articles
    $articles = get_all_articles($data_folder);
    $resultats = array();

    // Parcours de chaque article pour vérifier la présence du mot clé
    foreach ($articles as $article) {
        // Vérification de la présence du mot clé dans le titre, la catégorie ou les instructions de l'article
        if (
            (isset($article['type']) && stripos($article['type'], $mot_cle) !== false) ||
            (isset($article['titre']) && stripos($article['titre'], $mot_cle) !== false) ||
            (isset($article['categorie']) && stripos($article['categorie'], $mot_cle) !== false) ||
            (isset($article['instructions']) && stripos($article['instructions'], $mot_cle) !== false)
        ) {
            // Ajout de l'article correspondant au tableau des résultats
            $resultats[] = $article;
        }
    }

    return $resultats;
}

function obtenir_dernier_article($dossier_donnees)
{
    $dernier_article = null;
    $dernier_temps = 0;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers d'articles dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/article-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier d'articles
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier d'articles
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier d'article pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $article = json_decode($contenu, true);

                        // Vérification de la date de création de l'article
                        if (isset($article['date_creation'])) {
                            $temps = strtotime($article['date_creation']);
                            // Mise à jour de l'article le plus récent
                            if ($temps > $dernier_temps) {
                                $dernier_temps = $temps;
                                $dernier_article = $article;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $dernier_article;
}

function obtenir_derniere_citation($dossier_donnees)
{
    $derniere_citation = null;
    $dernier_temps = 0;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers de citations dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/citation-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier de citations
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier de citation
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier de citation pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $citation = json_decode($contenu, true);

                        // Vérification de la date de création de la citation
                        if (isset($citation['date_creation'])) {
                            $temps = strtotime($citation['date_creation']);
                            // Mise à jour de la citation la plus récente
                            if ($temps > $dernier_temps) {
                                $dernier_temps = $temps;
                                $derniere_citation = $citation;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $derniere_citation;
}

function obtenir_derniere_video($dossier_donnees)
{
    $derniere_video = null;
    $dernier_temps = 0;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers de vidéos dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/video-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier de vidéos
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier de vidéos
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier de vidéo pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $video = json_decode($contenu, true);

                        // Vérification de la date de création de la vidéo
                        if (isset($video['date_creation'])) {
                            $temps = strtotime($video['date_creation']);
                            // Mise à jour de la vidéo la plus récente
                            if ($temps > $dernier_temps) {
                                $dernier_temps = $temps;
                                $derniere_video = $video;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $derniere_video;
}

function obtenir_meilleur_article($dossier_donnees)
{
    $meilleur_article = null;
    $meilleure_note = -1;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers d'articles dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/article-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier d'articles
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier d'articles
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier d'article pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $article = json_decode($contenu, true);

                        // Vérification de la note de l'article
                        if (isset($article['notes']) && is_array($article['notes'])) {
                            $moyenne_note = array_sum($article['notes']) / count($article['notes']);
                            // Mise à jour de l'article avec la meilleure note
                            if ($moyenne_note > $meilleure_note) {
                                $meilleure_note = $moyenne_note;
                                $meilleur_article = $article;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $meilleur_article;
}

function obtenir_meilleure_video($dossier_donnees)
{
    $meilleure_video = null;
    $meilleure_note = -1;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers de vidéos dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/video-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier de vidéos
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier de vidéos
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier de vidéo pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $video = json_decode($contenu, true);

                        // Vérification de la note de la vidéo
                        if (isset($video['notes']) && is_array($video['notes'])) {
                            $moyenne_note = array_sum($video['notes']) / count($video['notes']);
                            // Mise à jour de la vidéo avec la meilleure note
                            if ($moyenne_note > $meilleure_note) {
                                $meilleure_note = $moyenne_note;
                                $meilleure_video = $video;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $meilleure_video;
}

function obtenir_meilleure_citation($dossier_donnees)
{
    $meilleure_citation = null;
    $meilleure_note = -1;

    // Vérifie si le dossier 'data' existe et est un dossier
    if (file_exists($dossier_donnees) && is_dir($dossier_donnees)) {
        // Récupération de tous les sous-dossiers (utilisateurs) dans le dossier 'data'
        $utilisateurs = glob($dossier_donnees . '/*', GLOB_ONLYDIR);

        // Parcours de chaque sous-dossier (utilisateur)
        foreach ($utilisateurs as $dossier_utilisateur) {
            // Récupération de tous les sous-dossiers de citations dans le dossier utilisateur
            $sous_dossiers = glob($dossier_utilisateur . '/citation-*', GLOB_ONLYDIR);

            // Parcours de chaque sous-dossier de citations
            foreach ($sous_dossiers as $sous_dossier) {
                // Récupération de tous les fichiers JSON dans le sous-dossier de citations
                $fichiers = glob($sous_dossier . '/*.json');

                // Parcours de chaque fichier de citation pour récupérer les données
                foreach ($fichiers as $fichier) {
                    // Récupération du contenu du fichier
                    $contenu = file_get_contents($fichier);

                    // Vérification si la lecture du fichier s'est bien passée
                    if ($contenu !== false) {
                        // Décodage des données JSON
                        $citation = json_decode($contenu, true);

                        // Vérification de la note de la citation
                        if (isset($citation['notes']) && is_array($citation['notes']) && count($citation['notes']) > 0) {
                            $moyenne_note = array_sum($citation['notes']) / count($citation['notes']);
                            // Mise à jour de la citation avec la meilleure note
                            if ($moyenne_note > $meilleure_note) {
                                $meilleure_note = $moyenne_note;
                                $meilleure_citation = $citation;
                            }
                        }
                    } else {
                        // Affichage d'un message d'erreur si on ne peut pas obtenir les données présentes dans le fichier
                        error_log("Erreur lors de la lecture du fichier $fichier.");
                    }
                }
            }
        }
    }

    return $meilleure_citation;
}
