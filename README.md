# DBLP

## Installation

1. Cloner le REPO GitHub
Ouvrez un terminal à l'endroit souhaité.
```git clone https://github.com/zenpoxa/dblp```

2. Aller à la racine du REPO
```cd dblp```

3. Lancer la base de données localement (docker)
```docker-compose up -d```
Deux ports vont se créer : 
- http://localhost:8080 (la base de données)
- http://localhost (l'application)

4. Se connecter à la base
Dans un navigateur rendez-vous à http://localhost:8080.
Système : **PostgreSQL**
Serveur : **db**
Utilisateur : **root**
Mot de passe : **mdp**
Base de données : **postgres**

5. Initialiser la base de données
Copiez le contenu du fichier '/dblp/script-bdd.sql'.
Collez le contenu copié dans l'onglet *Requête SQL*, situé en haut à gauche dans le http://localhost:8080.
Cliquez sur *Exécuter*

6. Insérer les données
Dans un navigateur rendez-vous à http://localhost/filldb.
Attendre le chargement de la page, avec les paramètres de bases (personnalisables facilement au début du fichier '/dblp/filldb.php') l’exécution peut prendre jusqu'à ~ 8 minutes...

7. Facultatif : Rechercher les coordonnées des affiliations pour la base
Dans un navigateur rendez-vous à http://localhost/getcoordinates.
Laisser le fichier tourner en fond, il recherche des coordonnées pour les associer aux publications de notre base pour afficher sur la carte (page 0) les points des différentes affiliations des auteurs et co-auteurs de l'IRISA.
Si l'exécution s'arrête avec un message qui n'est pas : RÉCUPÉRATION TERMINÉ, vous êtes libre de relancer le chargement (http://localhost/getcoordinates).

8. Application prête
Enfin, dans un navigateur rendez-vous à http://localhost.
Vous pouvez désormais découvrir notre travail !