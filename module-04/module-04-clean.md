# Docker 1 - Système de conteneurs - TP Module 4 : Travailler avec les conteneurs Docker

```{=html}
<hr>
```
Paul Schuhmacher

Février 2024

Module: Docker

```{=html}
<hr>
```
## TP 1 : Partagez des données entre services via des volumes

1.  **Construisez** une image `build-reports` à partir de
    [alpine](https://hub.docker.com/_/alpine). Cette image devra
    exécuter un programme (écrit dans le langage de votre choix : Bash,
    C, Python, PHP, Node.js, etc.) dont le comportement est le suivant :
    1.  Le programme génère `10` fichiers texte dans un dossier
        `reports/` nommés `report0001`, `report0002`;
    2.  Chaque fichier contient le texte `REPORT 1`, `REPORT 2`, etc.
        sur la première ligne;
    3.  Une fois la génération terminée, le dossier `reports/` est
        archivé au format `tar` sous le nom `reports/reports.tar`
    4.  Le programme affiche sur la sortie standard le message suivant :
        `"Préparation des rapports et archivage terminés."` .

Pour cela, **créez un fichier** `Dockerfile-build`. **Testez** votre
image via un conteneur.

2.  **Modifiez** le programme afin qu'il puisse générer un nombre `n`
    variable de rapports : le nombre `n` doit être **fourni en argument
    lors de l'exécution du conteneur** (`docker run`). Si aucun argument
    n'est passé, le programme doit s'arrêter avec un code de retour `1`
    pour signaler une erreur. **Adaptez** le Dockerfile pour prendre en
    compte cette évolution.
3.  **Exécutez** le conteneur sans argument pour provoquer
    volontairement une erreur. Avec `docker`, comment lister uniquement
    les conteneurs qui se sont arrêtés sur un code retour égal à 1 ?
4.  **Analysez** **où** sont stockés les rapports générés et l'archive
    `reports.tar`. Est-il possible de les récupérer depuis l'hôte ?
    Pourquoi ?
5.  **Exécutez** à nouveau l'image `build-reports`, cette fois **en
    associant un volume** pour pouvoir facilement récupérer l'archive
    contenant les rapports sur la machine hôte :
    1.  **Créez** un dossier `vol_reports` dans le répertoire courant du
        projet.
    2.  **Montez** ce dossier comme volume lors du lancement du
        conteneur.
    3.  À la fin de l'exécution, vous devez retrouver dans
        `vol_reports/` le fichier `reports.tar` contenant les rapports
        générés.
6.  On aimerait à présent déployer *un autre service* qui va utiliser
    ces rapports pour les traiter : inspecter, compléter, etc..
    **Construire** une seconde image `approve-reports` à partir d'un
    nouveau `Dockerfile` nommé `Dockerfile-approve`. Ce service doit :
    1.  Extraire le contenu de l'archive `reports.tar`
    2.  Ajouter à la fin de chaque rapport (*append*) le texte suivant :
        `Approved`
    3.  Afficher le message `"Approbation des $n rapports terminée."`

Pour stocker le travail réalisé par ce second service, on réutilisera le
volume `vol_reports` créé précédemment.

## TP 2 : Base de données SQL et volumes

Rendez-vous sur [la page de l'image docker officielle de
MySQL](https://hub.docker.com/_/mysql), et explorez la documentation.

1.  **Lancez** un conteneur, nommé `some-mysql` avec la version `8.3` de
    MySQL;
2.  **Ouvrez** un programme *shell* interactif (sh ou bash) sur le
    conteneur;
3.  Depuis ce shell, **connectez-vous** au serveur MySQL à l'aide du
    client mysql :

``` bash
mysql -u<user> -p
```

où `-p` ouvre un prompt pour saisir le mot de passe de l'utilisateur
mysql.

4.  **Créer** une base de données `mydb` et une table `user(id)` avec
    quelques données :

``` sql
CREATE DATABASE mydb;
CREATE TABLE mydb.user(id INTEGER NOT NULL);
INSERT INTO mydb.user(id) VALUES (1),(2),(3);
-- TABLE est un alias MySQL pour SELECT * FROM
TABLE mydb.user;
```

5.  **Fermez** la session MySQL (`exit`) puis **arrêtez** le conteneur.
    a.  Où sont **persistées** les données de la base ? \*
    b.  Sont-elles toujours présentes et accessibles si vous redémarrez
        le conteneur ?
    c.  **Relancez le même conteneur** et **vérifiez**.
    d.  **Supprimez** ensuite le conteneur. Les données sont-elles
        encore présentes ?
6.  Pour rendre les données persistantes, **créez** un dossier local
    `data`.
7.  **Lancez** un nouveau conteneur nommé `mysql8` en associant ce
    dossier *"en bind mount"* au répertoire de données MySQL du
    conteneur.
8.  **Recréez** la base de données minimale `mydb` avec la même table et
    les mêmes données. **Inspectez** la base de données pour vous
    assurer que les données sont bien présentes;
9.  **Réalisez** un **dump** (sauvegarde au format SQL) la base de
    données `mydb` sur la machine hôte dans un fichier `dump-mydb.sql`
    avec le programme `mysqldump` présent dans le conteneur;
10. **Arrêtez** et **supprimez** le conteneur. Les données sont-elles
    encore présentes ? **Relancez** un conteneur avec le volume (comme à
    la question 7) pour le vérifier.
11. Peut on associer un volume à plusieurs conteneurs ? **Essayez** en
    lançant un nouveau conteneur `mysql8-friend` avec le même volume.
    **Ouvrez** deux shells dans deux terminaux, l'un sur `mysql8` et
    l'autre sur `mysql8-friend`. **Manipulez** la base avec l'un
    (insérer des données, créer une table, etc.) et **inspectez** avec
    l'autre et vice versa;
12. **Arrêtez** et **supprimer** le conteneur `mysql8-friend` **en une
    seule commande**;
13. **Créez** un fichier `drop-mydb.sql` avec le contenu suivant :

``` sql
DROP DATABASE IF EXISTS mydb;
```

**Exécutez** le script SQL via le conteneur (`docker exec`) **sans
ouvrir de shell interactif**, en utilisant le *batch mode* :

``` bash
docker exec -i mysql8 mysql -uroot -p < drop-mydb.sql
```

14. De la même manière, **restaurez** la base à partir du dump
    `dump-mydb.sql`.
