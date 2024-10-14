# Guide : *What is a container ?*

1. Dans Docker Desktop, inspecter les *Layers* (cliquer sur le lien de l'image) de l'image. Voit la Layer 15 : `COPY /app/build /usr/share/nginx/html`. Aller dans `Files` et suivre le chemin. On tombe sur les sources du site web. `"You ran your first container"` est dans une des sources JS (injectée via la manip du DOM), comme l'effet de particules, `index.html` le document HTML. Le tout est servi par un serveur web nginx.
2. On peut effectuer le même travail avec le client docker en une seule commande :

~~~bash
docker run -d -p 8080:80 docker/welcome-to-docker
~~~

avec les options :

- `-d|--detach` pour mettre l'execution du conteneur en arrière-plan;
- `-p|--publish` pour publier un port du conteneur sur le port de la machine hôte (i.e mapper un port de la machine hote (`8080`) au port du conteneur (`80`))

4. Pour arrêter et supprimer le conteneur :

~~~bash
#Liste les conteneurs en cours d'execution. Noter son id
docker ps -a
#Arrêter le conteneur
docker stop <conteneur id>
#Supprimer le conteneur
docker rm <conteneur id>
~~~

## Références

- [What is a container ?](https://docs.docker.com/guides/walkthroughs/what-is-a-container//), guide de la documentation officielle
