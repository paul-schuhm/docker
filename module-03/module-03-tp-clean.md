# Docker 1 - Système de conteneurs - TP Module 3 et Module 4 : Travailler avec les Images Docker et les volumes

```{=html}
<hr>
```
Paul Schuhmacher

Module: Docker

```{=html}
<hr>
```
-   [Docker 1 - Système de conteneurs - TP Module 3 et Module 4 :
    Travailler avec les Images Docker et les
    volumes](#docker-1---système-de-conteneurs---tp-module-3-et-module-4--travailler-avec-les-images-docker-et-les-volumes)
    -   [TP 1 Gestion des images : Serveur web
        Apache](#tp-1-gestion-des-images--serveur-web-apache)
        -   [Partie 1 - Utiliser l'image officielle du serveur web
            Apache](#partie-1---utiliser-limage-officielle-du-serveur-web-apache)
        -   [Partie 2 - Partir d'une image
            Ubuntu](#partie-2---partir-dune-image-ubuntu)
    -   [TP 2 : Optimisation d'image](#tp-2--optimisation-dimage)
    -   [TP 3 : Les images, les fichiers et les
        volumes](#tp-3--les-images-les-fichiers-et-les-volumes)
    -   [TP 4 : Base de données SQL](#tp-4--base-de-données-sql)
    -   [TP 5 : Guide sur un langage
        spécifique](#tp-5--guide-sur-un-langage-spécifique)

## TP 1 Gestion des images : Serveur web Apache

[Apache HTTP Server](https://fr.wikipedia.org/wiki/Apache_HTTP_Server)
est un serveur web populaire, maintenu depuis 1996 par l'[Apache
Software
Foundation](https://fr.wikipedia.org/wiki/Apache_Software_Foundation),
co-fondée par [Roy
Fiedling](https://fr.wikipedia.org/wiki/Roy_Fielding). Roy Fiedling est
connu pour être l'auteur de la spécification HTTP 1.1 et de
l'*architecture du web* (contraintes REST), aux côtés de [Tim
Berners-Lee](https://fr.wikipedia.org/wiki/Tim_Berners-Lee). Ses
contributions ont été cruciales pour permettre au web de survivre à son
propre succès.

Nous allons nous en servir pour servir un site web minimal\*.

> \*Apache est un gros serveur web, il vaut mieux avoir des bonnes
> raisons de s'en servir. Il existe de nombreux serveurs web beaucoup
> plus légers et simples à configurer pour servir des petits services
> web.

### Partie 1 - Utiliser l'image officielle du serveur web Apache

1.  **Listez** les images disponibles sur votre machine.
2.  **Allez** sur [le hub de Docker Inc.](https://hub.docker.com/) et
    **cherchez** une [image Apache
    officielle](https://hub.docker.com/_/httpd) (aussi connu sous le nom
    `httpd`) puis **téléchargez** la dernière version standard avec
    `docker`. Quelle taille fait l'image ? **Télécharger** sa version
    *alpine*. **Comparez** sa taille avec l'image téléchargée
    précédemment.
3.  Nous allons nous servir de l'image standard comme base pour servir
    un site web statique. **Créer** un répertoire `apache` et placez-y :
    1.  Un fichier `Dockerfile`
    2.  Un dossier `www` contenant un fichier `index.html`. La page web
        contient une structure HTML 5 valide avec un titre principal "TP
        1 : Gestion des images" :

``` html
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
```

3.  À l'aide des instructions `FROM` et `ADD`, **construisez** votre
    image à partir de l'image standard d'Apache et ajoutez le code
    source de votre site web au système de fichiers de l'image. Dans
    quel répertoire faut-il placer les sources sur un serveur web Apache
    (le `Document Root`) ? **Inspecter** la documentation de l'image
    pour trouver le *path* correct sur l'image (emplacement où déposer
    le site web sur le système de fichiers)
4.  **Déclarer** une variable d'environnement `DEST` avec l'instruction
    `ENV` pour définir le path de destination identifié précédemment.
    **Utiliser** cette variable ensuite dans l'instruction `ADD`.
5.  Une fois le `Dockerfile` terminé, **construire** l'image. **Nommer
    et *tager*** l'image pour que son nom soit `www:1` et **ajouter**
    une méta donnée `author` avec votre nom/pseudo à l'image.
6.  **Vérifier** que votre image est bien présente dans le Docker
    Engine. **Inspecter** l'image : **vérifier** que la métadonnée
    `author` a bien été ajoutée et **trouver** le port tcp qui sera
    exposé par le conteneur.
7.  Une fois l'image créée, nous allons l'exécuter. A partir de l'image
    `www`, **lancer un conteneur** en mappant un port de votre machine
    (par exemple `8089`) au port exposé `80` (réservé pour le protocole
    http par convention) du conteneur.
8.  **Tester** que le site web est bien servi en émettant une requête
    HTTP à l'adresse `localhost:8089` (avec votre navigateur favori,
    [cURL](https://curl.se/), etc.);
9.  **Afficher** les conteneurs en cours d'exécution;
10. **Arrêter** le conteneur. **Afficher** tous les conteneurs (même
    ceux à l'arrêt). **Supprimer** le conteneur;
11. A partir de quelle image est *composée* l'image standard d'Apache ?

### Partie 2 - Partir d'une image Ubuntu

Nous allons maintenant créer *notre propre image* qui va nous permettre
de lancer un serveur web Apache. Pour cela, nous allons construire notre
propre image à partir d'une image Ubuntu.

1.  **Créer** un nouveau dossier `ubuntu-apache` et placez-y un nouveau
    fichier `Dockerfile` ainsi que le site web (dossier `www`) [de la
    partie
    1](#partie-1---utiliser-limage-officielle-du-serveur-web-apache).
2.  Dans le `Dockerfile` placez-y les commandes suivantes :

``` dockerfile
FROM ubuntu:latest

#Recharge la liste des paquets (apt), install les timezones et installe le serveur web Apache
RUN apt-get update && apt-get install -y tzdata && apt-get install -y apache2

#Définition des variables d'environnement. 
#Info: www-data est l'utilisateur d'apache
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2

#Construction des répertoires
RUN mkdir -p $APACHE_RUN_DIR $APACHE_LOCK_DIR $APACHE_LOG_DIR

ENTRYPOINT [ "/usr/sbin/apache2" ]
CMD ["-D", "FOREGROUND"]
EXPOSE 80
```

3.  À quoi sert l'instruction `ENTRYPOINT` ? [Consulter la documentation
    pour en apprendre
    plus](https://docs.docker.com/reference/dockerfile/). **Explorer**
    la différence entre `CMD` et `ENTRYPOINT`.
4.  **Construire** l'image, que l'on appellera `ubuntu-www:1`.
5.  **Lancer** le conteneur, en réalisant le même *mapping* de port que
    précédemment.
6.  **Tester** que le serveur web fonctionne en émettant une requête
    HTTP à l'adresse `localhost:8089` (avec votre navigateur favori,
    [cURL](https://curl.se/), etc.);
7.  **Comparer** la taille de cette image avec l'image `www:1`. Pourquoi
    l'image `ubuntu-www:1` est de plus grande taille que l'image `www:1`
    ?
8.  **Ajouter** le code source du site web sur le système de fichiers de
    l'image. Sur cette image, on placera les sources dans le répertoire
    `/var/www/html`. Où faut-il placer de préférence cette instruction
    dans le `Dockerfile` ? Pourquoi ?
9.  **Construire** la nouvelle image avec le nom `ubuntu-www:2`.
    **Tester** que votre site web est bien servi en émettant une requête
    HTTP à l'adresse `localhost:8089`;
10. **Arrêter** le conteneur et **supprimer** le.
11. En conclusion, quelle image faut-il privilégier entre `www` et
    `ubuntu-www` ? **Justifier**.

## TP 2 : Optimisation d'image

1.  **Écrire** le code source du jeu *"Guess my number"* en C (ou
    générer-le si cela ne vous intéresse pas). Dans ce jeu, l'ordinateur
    choisit un nombre aléatoire entre 1 et 100, et le joueur doit
    deviner ce nombre en faisant des propositions. Le programme donne
    des indications si la proposition est trop haute, trop basse ou
    correcte. Le joueur a 10 essais avant le game over.
2.  Voici un `Dockerfile` basé sur [l'image officielle
    gcc](https://hub.docker.com/_/gcc) (compilateur C de GNU) permettant
    d'executer le programme du jeu dans un conteneur

``` dockerfile
FROM gcc:4.9
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
RUN gcc -o guess-my-number main.c
CMD ["./guess-my-number"]
```

1.  **Construire** l'image `guess:1` à partir du `Dockerfile`.
2.  **Executer** le conteneur pour le tester

``` bash
docker run -it guess:1
```

3.  À quoi servent les options `-t` et `-i` ? Pourquoi faut-il les
    utiliser ici ?
4.  **Inspecter** la taille de l'image.
5.  À l'aide de la technique du *Multi-Stage Builds*, **optimiser**
    l'image finale `guess:2`. Que peut-on également améliorer dans le
    `Dockerfile` initial pour optimiser le temps de build ? Comparer les
    tailles finales des images `guess:1` et `guess:2`. *Tip: pour
    compiler le binaire de manière statique (et le rendre standalone) :
    `gcc -o guess-my-number -static main.c`*
6.  *Bonus:* Pourquoi est-ce utile de créer un binaire *static* du
    programme ? Quels sont les avantages et inconvénients d'un binaire
    compilé statiquement par rapport à un binaire compilé dynamiquement
    ?

## TP 3 : Les images, les fichiers et les volumes

1.  À partir de [alpine](https://hub.docker.com/_/alpine),
    **construire** une image `build-reports` qui va exécuter le
    programme `build-reports` suivant (shell, C, python, etc. choisissez
    votre langage favori !) : le programme génère `10` fichiers texte
    dans un dossier `reports` nommés `report0001`, `report0002`, etc.
    contenant chacun le titre `REPORT 1`, `REPORT 2`, etc. sur la
    première ligne. Une fois la génération terminée, le dossier
    `reports` est archivé sous forme de tarball (avec tar) sous le nom
    `reports/reports.tar` et affiche le message
    `"Préparation des rapports et archivage terminés."` sur la sortie
    standard. Pour cela, crée un fichier `Dockerfile-build`.
2.  Le programme doit à présent générer `n` fichiers, où `n` **est passé
    en argument à l'exécution de l'image** (`docker run`). Sa valeur
    provient d'un autre service/conteneur. Si aucune valeur n'est
    fournie, le conteneur doit s'arrêter avec un code de retour égal à
    `1` pour indiquer une erreur. **Modifier** le programme et l'image
    pour y parvenir.
3.  **Executer** le conteneur sans argument pour déclencher l'erreur.
    Avec `docker`, comment lister uniquement les conteneurs qui se sont
    arrêtés sur un code retour égal à 1 ?
4.  Où se trouvent les rapports générés et l'archive ? Est-il possible
    de les récupérer ? Pourquoi ?
5.  **Executer** l'image **en associant un volume** au conteneur de
    sorte à récupérer les rapports sur la machine hôte. On créera un
    volume dans le dossier courant du projet sous le nom `vol_reports`.
    A la fin de l'execution du conteneur, on doit y retrouver l'archive
    `reports.tar` contenant les rapports.
6.  On aimerait à présent déployer *un autre service* qui va utiliser
    ces rapports pour les inspecter, les compléter. **Construire** une
    autre image `approve-reports` pour ce service à partir d'un second
    Dockerfile que l'on nommera `Dockerfile-approve`. Le programme doit
    désarchiver le tarball `reports.tar`, *append* dans chaque rapport
    le texte suivant : `Approved` et afficher le message
    `"Approbation des $n rapports terminée."`. Pour cela, on
    *réutilisera* le volume crée précédemment.

## TP 4 : Base de données SQL

Rendez-vous sur [la page de l'image docker officielle de
MySQL](https://hub.docker.com/_/mysql), et explorez la documentation.

1.  **Lancer** un conteneur, nommé `some-mysql` avec la version `8.3` de
    MySQL;
2.  **Ouvrir** un shell interactif sur le conteneur;
3.  Depuis le conteneur, **connectez-vous** au serveur MySQL avec le
    client mysql : `mysql -u<user> -p`, où `-p` ouvre un prompt pour
    saisir le mot de passe de l'utilisateur mysql;
4.  **Créer** une base de données `mydb` avec une table user(**id**):

``` sql
CREATE DATABASE mydb;
CREATE TABLE mydb.user(id INTEGER NOT NULL);
INSERT INTO mydb.user(id) VALUES (1),(2),(3);
-- TABLE est un alias MySQL pour SELECT * FROM
TABLE mydb.user;
```

5.  **Fermer** la session (`exit`). **Arrêter** le conteneur. Les
    données sont-elles persistées sur le disque de la machine hôte ?
    Sont-elles toujours présentes et accessibles si l'on redémarre le
    conteneur ? **Relancer le même conteneur** et inspecter la base de
    données pour vous en assurer. **Supprimer** le conteneur. Les
    données sont-elles encore présentes ?
6.  Pour persister les données, **créer** un dossier local `data`;
7.  **Créer** et lancez un nouveau conteneur, nommé `mysql8` en
    associant le *volume* (*bind mount*) `data` au conteneur;
8.  **Recréer** la base de données minimale `mydb`;
9.  **Arrêter** et **supprimer** le conteneur `mysql8`;
10. **Recréer** un conteneur `mysql8` en lui associant le volume.
    **Inspecter** la base de données pour vous assurer que les données
    sont bien présentes;
11. **Dump** la base de données `mydb` sur la machine hôte dans un
    fichier `dump-mydb.sql`;
12. Quitter le conteneur `mysql8` **sans l'arrêter** et lancer un
    nouveau conteneur `mysql8-friend` avec le volume également associé;
13. **Ouvrez** deux shells dans deux terminaux, l'un sur `mysql8` et
    l'autre sur `mysql8-friend`. **Manipuler** la base avec l'un
    (insérer des données, créer une table) et inspecter avec l'autre et
    vice versa;
14. **Arrêter** le conteneur `mysql8-friend`;
15. **Créer** un fichier `drop-mydb.sql` avec le contenu suivant :

``` sql
DROP DATABASE IF EXISTS mydb;
```

**Executer** le script SQL via le conteneur (`docker exec`) **sans
ouvrir de session interactive**, en utilisant le *batch mode* :
`mysql -uroot -p < votre-script.sql`

15. **Restaurer** ensuite la base avec votre script de dump
    `dump-mydb.sql`.

## TP 5 : Guide sur un langage spécifique

**Choisissez** votre langage favori parmi ceux proposés et [suivre le
guide officiel](https://docs.docker.com/language/) jusqu'à l'étape *Run
your tests*. Ce guide vous fera construire une image spécifique à votre
techno favorite, exécuter un conteneur, effectuer les tests et plus
encore.

Lorsque vous avez fini, **publiez** votre image sur votre hub. Faites la
**tester** par vos collègues.

> Vous pouvez continuer si vous le souhaitez. Nous verrons ensemble dans
> le cours le CI/CD avec Docker et Github Actions.
