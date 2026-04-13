# Docker - TP Module 6 : Docker Compose

<hr>

Paul Schuhmacher

Février 2024

Module: Docker

<hr>

<!-- 
TP : env multiprojets local avec trafik. Transformer le dépôt en guide.
TP : organisation d'un réseau
TP : application web plusieurs composants
TP : Volume distant sur Google drive ? avec rclone
 -->

# TP 1 : Premiers pas avec Compose, pratique et parcours (orienté) de la documentation

[Suivre le guide officiel](https://docs.docker.com/compose/gettingstarted/).

> Prenez votre temps pour **parcourir** la doc, de **tester** les mécanismes approchés et découverts sur un projet multi-conteneurs **minimal**

1. Dans quel format doit être écrit le fichier de configuration `compose` ?
2. Quel argument est utilisé dans le fichier de configuration `compose` pour configurer les ports du conteneur et les associer à un port de l'hôte ?
3. Quelle commande permet de lister tous les process créés par Docker Compose ?
4. Docker Compose peut faire appel à des fichiers `Dockerfile` pour créer les services, vrai ou faux ?
5. Le fichier `compose` doit être publié avec les sources du projet, comme le `Dockerfile`, vrai ou faux ?
6. Que signifie l'instruction `build : .` dans le fichier `compose` ?
7. Le fichier `compose` permet de définir des variables d'environnement, vrai ou faux ? Si oui, comment ? Si oui, ces variables d'environnement surchargent-elles (*override*) celles potentiellement définies dans le Dockerfile ?
8. A quoi servent les fichiers `.env` ? Où doivent-ils être placés par rapport au fichier `compose` ?
9. Qu'est ce que l'[interpolation](https://docs.docker.com/compose/compose-file/12-interpolation/) des variables d'environnement ? Comment vérifier (quelle commande) qu'au moment de la création des conteneurs les variables interpolées ont bien fonctionné ?
10. Faut-il prévoir un fichier `compose` pour l’environnement de développement et un autre pour l'environnement de production ?
11. Quels sont les trois mécanismes mis à disposition par Docker pour travailler facilement avec plusieurs fichiers `compose` ?
12. À quoi servent [les profils de Compose ?](https://docs.docker.com/compose/profiles/)
13. A quoi servent [les secrets dans les fichiers Compose ?](https://docs.docker.com/compose/use-secrets/)
14. Dans un environnement de développement *dockerisé*, que faut-il faire pour tester la nouvelle version/incrément d'un projet à chaque fois que l'on modifie ses sources (dev) ? Est-ce viable selon vous ? Quelles solutions existent pour rendre la boucle de développement (modification, execution, observation) plus courte dans un environnement *dockerisé* ? Quelle méthode est à privilégier aujourd'hui ?


Conseils de lecture : 

- [Parcourir le guide](https://docs.docker.com/compose/);
- [Why use Compose?](https://docs.docker.com/compose/intro/features-uses/);
- [How Compose works](https://docs.docker.com/compose/compose-application-model/);
- [Use secrets](https://docs.docker.com/compose/use-secrets/)
- [Profiles](https://docs.docker.com/compose/profiles/)
- [Compose FAQ](https://docs.docker.com/compose/faq/);


<!-- 
TP sur : 
- mécanismes d'extend, include et merge. Profils. 
- Redéploiement sans downtime
 -->

## TP 2 : Réseaux de conteneurs et publication de ports

1. [Suivre le guide officiel sur la manipulation des réseaux de conteneurs](https://docs.docker.com/engine/network/drivers/bridge/).
2. [Suivre le guide officiel Networking in Compose](https://docs.docker.com/compose/how-tos/networking/)
3. [Suivre ce TP/guide en ligne](https://cours.hadrienpelissier.fr/02-docker/3-tp_reseaux/), d'Elie Gavoty et Hadrien Pélissier

## TP 3 : Projet web

Mettre en place et déployer avec compose le projet constitué des services suivants :

- Un **service de base de données**, pour que notre système ait un système de mémoire. Utiliser une base de données SQL (MySQL, PostgreSQL, MariaDB, etc.). Un volume devra lui être attaché.
- Un **service web**, dans la technologie de votre choix, qui interroge la base de données et affiche le résultat d'une requête SQL sur une page web.

> Faites une base de données minimale au choix. **L'important c'est que les deux services communiquent.**

Penser à **utiliser les fichiers d'environnement et les secrets à bon escient**.

## TP 4 : Setup projet web multi-services

> Le tp est moins spécifié volontairement pour vous laisser libre d'utiliser vos technologies préférées. Ce qui nous intéresse ici ce sont les contraintes sur les environnements et la configuration docker.

### Services

Mettre en place et déployer avec Compose le projet constitué des services suivants :

- Un service qui sert une *application cliente* (par exemple un document HTML statique, une app JS, etc.), il héberge le *front-end* de l'application;
- Un service *back-end*, qui traite les de l'application cliente et héberge la logique métier de l'application. Suggestions: PHP, Python, Node.js, Go, C#, etc. 
- Un service de base de données, pour que notre système ait une mémoire permanente. Utiliser une base de données relationnelle (MySQL, PostgreSQL, MariaDB, etc.). Un volume devra lui être attaché;
- Un service d'administration de base de données, **pour le développement uniquement** (Ex: [adminer](https://www.adminer.org/), [phpMyAdmin](https://www.phpmyadmin.net/), etc. );

### Réseau

- Le service client doit exposer un port tcp accessible depuis l’extérieur du réseau privé (il faut bien pouvoir y accéder !);
- Le service back expose un port tcp accessible depuis l'extérieur du réseau privé (pour être accessible à l'application cliente);
- La service de base de données **ne doit pas** être accessible en dehors du réseau privé (accessible uniquement au back);

### Environnements

> On le simulera ici uniquement en local

**Préparer deux environnements** (fichiers .env, fichiers compose) :

- **Développement** : 
  - Credentials bdd dédiés
  - Présence du service d'administration de base de données et du service mail ;
- **Production** : 
  - Credentials bdd dédiés
  - Pas de service d'administration de base de données, ni SMTP. 
  - Pas d'information de debug affichée sur la sortie, gestionnaire d'exceptions/erreurs avec redirection vers des logs
  - Etc.;

Utiliser [une stratégie au choix pour la gestion du déploiement propre à chaque environnement (fichiers compose)](https://docs.docker.com/compose/how-tos/multiple-compose-files/) (`compose up`) : merge, extend, includes, profiles.

### Liens utiles

- [Modify your Compose file for production](https://docs.docker.com/compose/how-tos/production/#modify-your-compose-file-for-production);
- [Use Compose in production](https://docs.docker.com/compose/how-tos/multiple-compose-files/)

### Bonus

Ajouter un service SMTP mail pour tester l'envoi d'email depuis le backend, **pour le développement uniquement**. (Ex: [mailhog](https://github.com/mailhog/MailHog)).


### Tester

Faites communiquer les services :

- Envoyer un email depuis le service backend, vérifier qu'il est bien attrapé par le service mail ;
- Mettre un lien (balise `a`) sur le service frontend qui interroge le service backend et retourne un résultat contenant des informations issues de la base de données ;
- Etc.


> Nous verrons par la suite comment déployer ce projet (publication d'images, etc.)

<!-- ## TP 4 : Volume distant (avancé)

Développer un service (ou reprendre celui crée dans le TP 3 Module 3) qui doit persister des données dans un volume, **sauf que le volume doit être distant** (hébergé sur une autre machine que l'hôte). Par exemple, en utilisant [rclone](https://rclone.org/) et [son plugin Docker Volume](https://rclone.org/docker/) et un drive, ou sur un host docker *remote*.


## TP 5 : Reverse-proxy et configuration locale

> A venir... -->

## Pratique supplémentaire

- [Suivre un guide](https://docs.docker.com/guides/), choisissez votre techno préférée (Node.js, PHP, Go, C#, etc.) et suivre un guide. Assez complet du dev à la prod. **Recommandé**