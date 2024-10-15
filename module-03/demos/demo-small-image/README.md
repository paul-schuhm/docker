# Démo : Petites images

## Une très petite image, basée sur scratch

Une très petite image basée sur [l'image scratch](https://hub.docker.com/_/scratch/). Cette image isole un serveur web écrit en Go. Le binaire est déjà préparé (`bin/helloworld`). 

> Le binaire est statique, compilé avec les options suivantes : `GO111MODULE=off CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -o ./bin/helloworld ./helloworld.go`

> Exemple basé sur le billet [Create the smallest possible Docker container](https://xebia.com/blog/create-the-smallest-possible-docker-container/), de Adriaan de Jonge

> On ne peut plus exécuter `docker run -d -p 8080:8080 adejonge/helloworld` directement car l'image est dépréciée par le Docker Engine. Il faut la reconstruire nous même.

~~~bash
#1. Construire l'image et lancer le conteneur
docker build -t adejonge/helloworld .
docker run -d -p 8080:8080 adejonge/helloworld
#2. Tester le conteneur
curl http://localhost:8080
#3. Inspecter l'image
docker images | grep adejonge/helloworld
#4. Inspecter le conteneur avec docker container (commande pour gérer les conteneurs)
#docker container --help
#Récupérer l'ID
docker container ls -l
#5. Exporter les fichiers contenus dans l'image dans un tarball
docker export caabe86eab84 -o web-app.tar
#6. Inspecter le contenu de l'archive
tar -tvf web-app.tar
#7. Inspecter la taille de l'archive, la taille du binaire
du -hs bin/ webapp.tar 
~~~

## Comparée avec une image Alpine Linux


~~~bash
#7. Comparaison avec une image Alpine Linux
docker pull alpine:latest
docker images | grep alpine/latest
# docker inspect : Return low-level information on Docker objects
docker image inspect alpine:latest
docker run -it --rm alpine /bin/sh
#Inspecter l’intérieur du conteneur
#ls -al; ls -al sbin bin
#exit
# Afficher la taille de l'image en bytes
docker image inspect alpine:latest --format='{{.Size}}'
~~~

## Conclusion

L'image alpine fait quasiment la même taille que le binaire standalone `helloworld` (~7BM). Si on embarquait le binaire dans cette image, l'image ferait le double.

Les images n'ont besoin de contenir que les fichiers requis pour faire tourner l'application conteneurisée sur le kernel de la machine hôte
et rien d'autre.

Garder des images de petite taille pour améliorer le workflow avec Docker.