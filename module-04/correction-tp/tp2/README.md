# Correction TP 5

~~~bash
docker run --name some-mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:8.3
docker exec -it some-mysql /bin/bash
~~~

Dans le conteneur ;

~~~bash
#utiliser la variable d'environnement directement (`env`)
mysql -uroot -p"$MYSQL_ROOT_PASSWORD"
#Ou saisir le password de root passé en variable d'environnement au conteneur (MYSQL_ROOT_PASSWORD)
mysql -uroot -p
~~~

Créer la base, une table et insérer les données 

~~~sql
CREATE DATABASE mydb;
CREATE TABLE mydb.user(id INTEGER NOT NULL);
INSERT INTO mydb.user(id) VALUES (1),(2),(3);
-- TABLE est un alias MySQL pour SELECT * FROM
TABLE mydb.user;
exit;
~~~

5. Non, car pas de volume, mémoire d'un conteneur volatile. Oui si l'on se relance au conteneur, les données de la base sont toujours présentes. Arrêter un conteneur ne supprime pas sa mémoire interne. Tant que le conteneur n'est pas supprimé on peut toujours accéder aux données. Arrêter un conteneur **sans le supprimer** ne provoque pas la perte de données.

6. Créer un dossier `data` et lancer le conteneur en attachant le volume

~~~bash
docker run --name mysql8 -v "$PWD/data"/:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:8.3
~~~

> Attention, il faut fournir le chemin **absolu** du dossier servant de volume.

10. Dump :

~~~bash
docker exec some-mysql sh -c 'exec mysqldump --databases mydb -uroot -p"$MYSQL_ROOT_PASSWORD"' > "$PWD/dump.sql"
~~~

Remarques :

- On utilise la syntaxe `sh -c 'exec ...'` pour échapper correctement les caractères spéciaux;
- L'option `--databases` permet ici de dump également l'instruction `CREATE DATABASE` et pas seulement le contenu de la base;

Envoyer un jeu de requêtes en batch mode (ici le drop de la base) via l'entrée standard (`stdin`) :

~~~bash
docker exec -i some-mysql sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < drop-mydb.sql
~~~

Ici **il ne faut pas oublier** [l'option `-i`](https://docs.docker.com/reference/cli/docker/container/run/#interactive) qui permet de laisser l'entrée standard stdin du conteneur ouverte pour qu'on puisse lui envoyer des données (ici le script `drop-mydb.sql`) via l'entrée standard (terminal sur l'hôte).

Restaurer la base :

~~~bash
docker exec -i some-mysql sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < dump.sql
~~~

> Conteneuriser une base de données peut être utile pour rendre l'environnement de l'application complètement cohérent (pas juste les sources de l'appli qui utilise la base). Cela permet de mettre la base de données dans l'environnement de Docker et de profiter, via les volumes, de la même souplesse pour reproduire le même environnement sur différentes machines (en déplaçant les volumes, facilement manipulables via la plateforme Docker).

## Références

- [Page de l'image docker officielle de MySQL](https://hub.docker.com/_/mysql)