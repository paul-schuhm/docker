# Correction TP 1

## Partie 1 - Utiliser l'image officielle du serveur web Apache


1. `docker images`
2. `docker pull httpd:latest`. L'image fait 167MB. `docker pull httpd:2.4-alpine`. L'image fait 61.6MB (env 3x plus petite)
3. Voir [Dockerfile](./ubuntu-apache/Dockerfile). Par défaut, [le document root d'un serveur apache](https://httpd.apache.org/docs/2.2/fr/mod/core.html#documentroot) est `/usr/local/apache/htdocs`. `docker images` pour check, `docker inspect image www:1` pour vérifier les métadonnées.
4. `ENV DEST /usr/local/apache2/htdocs` puis `ADD ./www $DEST`
5. A la racine du projet, `docker build -t www:1 .`
6. d`ocker inspect www:1`. Sous clef "Labels", on retrouve author. Sous la clef Config on trouve "ExposedPorts" 80/tcp. C'est le port indiqué avec l'instruction `EXPOSE` également.
7. `docker run -d -p 8089:80 www:1`
8. `curl localhost:8089`
9. `docker ps`
10. `docker stop $(docker ps -lq)`
11. [Si on inspecte le code source de l'image (Dockerfile)](https://github.com/docker-library/httpd/blob/89aed068235d9a480f245e03edf038621ab8ed8f/2.4/Dockerfile), on voit que `httpd` est basée sur une Debian "slim".


## Partie 2 - Partir d'une image Ubuntu

3. L'instruction `ENTRYPOINT` dans un fichier `Dockerfile` est utilisée pour configurer une commande qui sera exécutée lorsque le conteneur Docker démarre. Cela définit le point d'entrée principal pour le conteneur, la commande ou le script qui sera exécuté lorsque le conteneur est lancé.
7. `ubuntu-www                    1            872084cbe39c   2 seconds ago       **238MB**`
9.  Il faut préférer ici `www`, plus légère, basée sur Debian slim. Mais cela depend de notre *use case*. La distribution Ubuntu va emporter avec elle d'autres binaires et libs dont on peut avoir besoin pour d'autres sous-processus ou pour le processus principal. Les deux images n'offrent pas du tout le même environnement pour le processus. C'est pourquoi il faut bien réfléchir en amont **à quoi doit servir le conteneur**.