# Démo - Construire sa propre image

Une démo qui illustre la construction de sa propre image.

Cloner le dépôt Git [`docker-node-hello`](https://github.com/enokd/docker-node-hello)

~~~bash
git clone https://github.com/enokd/docker-node-hello.git
cd docker-node-hello
#Inspecter le contenu (y compris fichiers cachés sauf .git)
tree -a -I .git
~~~

Le Dockerfile est cassé (*plus maintenu depuis 10 ans !*). Le réparer. **Voici la version corrigée** (à copier/coller dans le `Dockerfile`) :


~~~dockerfile
# VERSION 0.2
# DOCKER-VERSION 0.3.4
# To build:
# 1. Install docker (http://docker.io)
# 2. Checkout source: git@github.com:gasi/docker-node-hello.git
# 3. Build container: docker build .

FROM centos:8

#Fix l'URL des dépots (mirror.centos.org n'existe plus depuis le 1er Juin 2024)
RUN sed -i s/mirror.centos.org/vault.centos.org/g /etc/yum.repos.d/CentOS-*.repo
RUN sed -i s/^#.*baseurl=http/baseurl=http/g /etc/yum.repos.d/CentOS-*.repo
RUN sed -i s/^mirrorlist=http/#mirrorlist=http/g /etc/yum.repos.d/CentOS-*.repo

#Update
RUN yum upgrade -y

# Install Node.js and npm
RUN curl -sL https://rpm.nodesource.com/setup_20.x | bash -
RUN dnf install nodejs -y

# App
ADD . /src
# Install app dependencies
RUN cd /src; npm install

EXPOSE  8080
CMD ["node", "/src/index.js"]
~~~



Build :

~~~bash
docker build -t example/docker-node-hello:7 .
~~~

Lancer le conteneur :

~~~bash
docker run -d -p 5001:8080 example/docker-node-hello:7
~~~

Inspecter, tester et arrêter :

~~~bash
docker ps
curl http://127.0.0.1:5001
docker stop <ID container>
~~~
