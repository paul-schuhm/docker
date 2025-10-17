# Docker 1 - Système de conteneurs - TP Module 3 : Utiliser, créer et optimiser des images

<hr>

Paul Schuhmacher

Octobre 2025

Module: Docker

<hr>

## TP 1 :  Utiliser et créer des images en pratique avec un projet web Apache

Dans ce TP, vous allez apprendre à **construire** et **exécuter** des images Docker (conteneurs) permettant de servir un site web statique avec le serveur web Apache. Vous commencerez par utiliser l’image officielle `httpd`, puis vous construirez **votre propre image** à partir d’Ubuntu afin de comparer ces deux approches.

> Remarque : [Apache HTTP Server](https://fr.wikipedia.org/wiki/Apache_HTTP_Server) est un serveur web populaire, maintenu depuis 1996 par l'[Apache Software Foundation](https://fr.wikipedia.org/wiki/Apache_Software_Foundation), co-fondée par [Roy Fiedling](https://fr.wikipedia.org/wiki/Roy_Fielding). Roy Fiedling est connu pour être l'auteur de la spécification HTTP 1.1 et de l'*architecture du web* (contraintes REST), aux côtés de [Tim Berners-Lee](https://fr.wikipedia.org/wiki/Tim_Berners-Lee). Ses contributions ont été cruciales pour permettre au web de survivre à son propre succès. 

> *Apache est un "gros" serveur web, il vaut mieux avoir des bonnes raisons de s'en servir. Il existe de nombreux serveurs web beaucoup plus légers et simples à configurer pour servir des petits services web (Nginx, Caddy, etc.).

### Partie 1 - Utiliser l'image officielle du serveur web Apache

1. **Listez** les images disponibles sur votre machine.
2. **Cherchez** sur [le hub de Docker Inc.](https://hub.docker.com/) l'[image officielle Apache](https://hub.docker.com/_/httpd) (appelée `httpd`). **Téléchargez** la dernière version standard avec `docker`. **Mesurer** la taille de l'image ?
3. **Télécharger** ensuite sa version *alpine*. **Comparez** les deux tailles. Laquelle est la plus petite ?
4. Nous allons utiliser l'image standard pour héberger un site web statique. **Créez** un répertoire `apache` et placez-y :
   1. Un fichier `Dockerfile`
   2. Un dossier `www` contenant le fichier `index.html` (à adapter à votre convenance !):

~~~html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker-Module3-TP1</title>
</head>
<body>
    <h1>TP 1 : Gestion des images</h1>
</body>
</html>
~~~
5. Dans votre `Dockerfile`, utiliser l’instruction `FROM` pour partir de l’image standard d’Apache, puis ajouter le contenu du site web avec l’instruction `ADD`.**Chercher** dans la documentation de l’image `httpd` où se trouve le `Document Root `(répertoire où Apache sert les fichiers, là où placer votre site web).

6. **Définir** une variable d’environnement `DEST` avec l'instruction `ENV` pour stocker le chemin du *Document Root*, puis réutiliser cette variable dans l’instruction `ADD` afin de copier le site au bon emplacement.

7. **Construire** l’image et la nommer/taguer `www:1`. Ajouter une métadonnée `author` avec votre nom ou pseudo.
8.  **Vérifier** que votre image est bien présente, **inspecter** ses métadonnées (dont `author`) et identifier le port exposé par l’image (port HTTP par défaut).
9.  **Exécuter** un conteneur à partir de cette image en mappant le port `8089` de votre machine sur le port `80` du conteneur.
10. **Tester** l’accès au site web à l'adresse [http://localhost:8089](http://localhost:8089) (avec votre navigateur ou avec [cURL](https://curl.se/))
11. **Lister** les conteneurs en cours d’exécution;
12. **Arrêter** puis **supprimer** le conteneur.
13. Enfin, **identifier** à partir de quelle image de base est construite l’image officielle `httpd` d'Apache (sur quelle image est-elle basée ?).

### Partie 2 - En construisant son image à partir d'une image Ubuntu

Nous allons maintenant créer *notre propre image* qui va nous permettre de lancer un serveur web Apache. Pour cela, nous allons construire notre propre image à partir d'une image Ubuntu.

1. **Créer** un nouveau dossier `ubuntu-apache` et placez-y un nouveau fichier `Dockerfile` ainsi que le site web (dossier `www`) [de la partie 1](#partie-1---utiliser-limage-officielle-du-serveur-web-apache).
2. Copier le contenu suivant dans votre `Dockerfile` :

~~~dockerfile
FROM ubuntu:latest

#Recharge la liste des paquets (apt), installe les timezones et installe le serveur web Apache
RUN apt-get update \
&& apt-get install -y tzdata \
&& apt-get install -y apache2 \
&& rm -rf /var/cache/apt/archives /var/lib/apt/lists

#Définition des variables d'environnement. 
#Info: www-data est l'utilisateur d'apache
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_LOG_DIR=/var/log/apache2
ENV APACHE_PID_FILE=/var/run/apache2.pid
ENV APACHE_RUN_DIR=/var/run/apache2
ENV APACHE_LOCK_DIR=/var/lock/apache2

#Construction des répertoires
RUN mkdir -p $APACHE_RUN_DIR $APACHE_LOCK_DIR $APACHE_LOG_DIR

ENTRYPOINT [ "/usr/sbin/apache2" ]
CMD ["-D", "FOREGROUND"]
EXPOSE 80
~~~

3. **Chercher** à quoi sert l'instruction `ENTRYPOINT` ? **Comparer** son rôle avec celui de `CMD` [en consultant la doc](https://docs.docker.com/reference/dockerfile/).
4. **Construire** l'image et la nommer `ubuntu-www:1`.
5. **Lancer** le conteneur, en réalisant le même *mapping* de port que précédemment.
6. **Tester** à nouveau l'accès à votre site à l'adresse `localhost:8089` (avec votre navigateur favori, [cURL](https://curl.se/), etc.);
7. **Comparer** la taille de `ubuntu-www:1` et `www:1`. **Expliquer** pourquoi l’image basée sur Ubuntu est *significativement* plus volumineuse.
8. **Ajouter** ensuite le site web dans l’image (dans `/var/www/html`).**Réfléchir** à l’endroit idéal pour placer cette instruction `ADD` dans le Dockerfile et justifier votre choix.
9. **Construire** la nouvelle sous le nom de `ubuntu-www:2`. **Tester** le bon fonctionnement du site;
10. **Arrêter** et **supprimer** le conteneur.
11. **Conclure** : quelle image est la plus pertinente entre `www` (basée sur `httpd`) et `ubuntu-www` (basée sur ubuntu + apache2) ? **Justifiez** votre choix.


## TP 2 : Optimisation d'image

1. **Écrivez** le code source du jeu *"Guess my number"* en C (ou générez-le automatiquement si vous ne souhaitez pas le coder à la main). Dans ce jeu, l'ordinateur choisit un nombre aléatoire entre 1 et 100 compris, et le joueur doit deviner ce nombre en faisant des propositions (à soumettre au clavier, via l'entrée standard). Le programme donne des indications si la proposition est trop haute, trop basse ou correcte. Le joueur a 10 essais avant le game over.
2. Voici un `Dockerfile` minimal basé sur [l'image officielle gcc](https://hub.docker.com/_/gcc) permettant de compiler et d'exécuter le programme dans un conteneur :

~~~dockerfile
FROM gcc:4.9
#copier le code source main.c dans l'image
COPY main.c /usr/src/myapp
WORKDIR /usr/src/myapp
#compiler le programme et produire le binaire
RUN gcc -o guess-my-number main.c
#Exécuter le binaire
CMD ["./guess-my-number"]
~~~

3. **Construisez** l'image `guess:1` à partir du `Dockerfile`.
4. **Lancez** un conteneur à partir de cette image pour tester le programme :

~~~bash
docker run -it guess:1
~~~

5. **Expliquez** le rôle des options `-t` et `-i` ? Pourquoi sont-elles nécessaires dans ce cas ?
6. **Inspectez** la taille de l'image. **Comparez-la** à la taille de l'exécutable.
7. À l’aide de la technique [du *Multi-Stage Builds*](https://docs.docker.com/build/building/multi-stage/), **optimisez** le `Dockerfile` afin de produire une image finale plus légère nommée `guess:2`, où l'on se sera débarrassé des dépendances liées à la phase de compilation (build).

> "Ship **artifacts**, not build environments" (Kesley Hightower)

8. Que peut-on également améliorer dans le `Dockerfile` proposé plus haut pour optimiser le temps de build et mieux utiliser le cache ? *Tip: pour générer un binaire autonome (standalone), vous pouvez le compiler statiquement : `gcc -o guess-my-number -static main.c`*.
9. **Comparer** les tailles finales des images `guess:1` et `guess:2`. 
10. **Supprimez** les images `guess:1` et `guess:2`.
11. *Bonus:* Pourquoi peut-il être intéressant de créer un binaire statique ? Quels sont les avantages et les inconvénients d’un binaire compilé statiquement par rapport à un binaire compilé dynamiquement ?


## TP 4 : Guide sur un langage spécifique

**Choisissez** votre langage favori parmi ceux proposés et [suivre le guide officiel](https://docs.docker.com/language/) jusqu'à l'étape *Run your tests*. Ce guide vous fera construire une image spécifique à votre techno favorite, exécuter un conteneur, effectuer les tests et plus encore.

Lorsque vous avez fini, **publiez** votre image sur votre hub. Faites-la **tester** par vos collègues.

 > Vous pouvez continuer si vous le souhaitez. Nous verrons ensemble dans le cours le CI/CD avec Docker et Github Actions.