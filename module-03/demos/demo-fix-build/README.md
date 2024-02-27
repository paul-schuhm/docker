# Démo

Une démo sur la construction d'une image, sur les couches, le `.dockerignore` et l'utilisation du cache.

Cloner le dépôt Git [`docker-node-hello`](https://github.com/enokd/docker-node-hello)

~~~bash
git clone https://github.com/enokd/docker-node-hello
~~~

Inspecter

~~~bash
cd docker-node-hello
#Inspecter le contenu (y compris fichiers cachés sauf .git)
tree -a -I .git
~~~

> Le .git a été supprimé ici. Re clone le dépôt si nécessaire.

~~~bash
#1. Construire l'image avec un nom et un tag (format name:tag), avec l'option -t
docker build -t example/docker-node:latest .
~~~

Erreur à fix (image non maintenue/abandonnée depuis 10 ans, ça arrive...) :

~~~dockerfile
# FROM    centos:centos6
FROM    centos:centos7 

# Enable EPEL for Node.js
# RUN rpm -Uvh https://mirror.in2p3.fr/pub/epel/epel-release-latest-7.noarch.rpm
RUN rpm -Uvh https://mirror.in2p3.fr/pub/epel/epel-release-latest-7.noarch.rpm
# Install Node.js and npm
RUN yum install -y -q npm

# App
ADD . /src
# Install app dependencies
RUN cd /src; npm install

EXPOSE  8080
CMD ["node", "/src/index.js"]
~~~

~~~bash
#2. Fixer l'image. Build.
docker build -t example/docker-node:latest .
#3. Relancer une nouvelle fois le build. Observer les couches chargées à partir du cache
docker build -t example/docker-node:latest .
#4. Relancer le build apres avoir modifié le code source de l'app Node.js. Observer les couches rechargées depuis le cache
docker build -t example/docker-node:latest .
#(opt) Construire l'image sans utiliser (les couches) en cache
docker build -t example/docker-node:latest --no-cache .
~~~

> Essayer de déplacer l'instruction `ADD` plus haut dans le Dockerfile. Observer les couches qui doivent être reconstruites.

Une fois l'image construite avec succès, lancer un conteneur à partir d'elle

~~~bash
#Lancer un conteneur à partir de l'image
docker run -d -p 5001:8080 example/docker-node-hello:latest
#Check
docker ps
#Test
curl http://127.0.0.1:5001
#Ouvrir un shell (bash) interactif à l’intérieur du conteneur (on y reviendra)
docker exec -it <ID|nom du conteneur> /bin/bash
#Stopper le conteneur
docker stop {ID du conteneur}
curl http://127.0.0.1:5001
~~~