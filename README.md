# DBLP

## Installation

>
1. Cloner le REPO GitHub
Ouvrez un terminal à l'endroit souhaité.
```git clone https://github.com/zenpoxa/dblp```

>
2. Aller à la racine du REPO
```cd dblp```

>
3. Lancer la base de données localement (docker)
```docker-compose up -d --build```
Deux ports vont être créés :
    - http://localhost:8080 (la base de données)
    - http://localhost (l'application)

>
4. Se connecter à la base
Dans un navigateur, rendez-vous à http://localhost:8080.
Système : **PostgreSQL**
Serveur : **db**
Utilisateur : **root**
Mot de passe : **mdp**
Base de données : **postgres**

>
5. Initialiser la base de données
Copiez le contenu du fichier '/dblp/script-bdd.sql'.
Collez le contenu copié dans l'onglet *Requête SQL*, situé en haut à gauche dans le http://localhost:8080.
Cliquez sur *Exécuter*

>
6. Insérer les données
Dans un navigateur, rendez-vous à http://localhost/filldb.
Attendre le chargement de la page, avec les paramètres de base (personnalisables facilement au début du fichier '/dblp/filldb.php') l’exécution peut prendre jusqu'à ~ 8 minutes...

>
7. Facultatif : rechercher les coordonnées des affiliations pour la base
Dans un navigateur,  rendez-vous à http://localhost/getcoordinates.
Laisser le fichier tourner en fond, il recherche des coordonnées pour les associer aux publications de la base de dononées pour afficher sur la carte (page 0) les points des différentes affiliations des auteurs et co-auteurs de l'IRISA.
Si l'exécution s'arrête avec un message qui n'est pas : RÉCUPÉRATION TERMINÉE, vous êtes pouvez poursuivre le chargement en rafraîchissant la page (http://localhost/getcoordinates).

>
8. Application prête
Enfin, dans un navigateur, rendez-vous à l'adresse http://localhost.
Vous pouvez désormais découvrir notre travail !

>
9. Fermer l'application
Pour fermer l'application, rentrez la commande ```docker-compose down```. Cela fermera votre serveur web local, mais pas d'inquiétudes pour les données chargées. Elles sont persistentes grâce aux volumes docker.

## Résolution de problèmes

Si vous rencontrez des problèmes lors du build docker, voici des indications pour aider à les résoudre.

- Si vous avez une version de docker-compose récente, privilégiez la commande ```docker compose``` par rapport ```docker-compose```.
- Avant de lancer les containers, assurez-vous qu'ils ne tournent pas déjà en exécutant la commande ```docker-compose down```.
