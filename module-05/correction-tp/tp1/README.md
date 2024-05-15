# Correction


- [Correction](#correction)
  - [Un point sur les systèmes de montage](#un-point-sur-les-systèmes-de-montage)
    - [Bind mounts](#bind-mounts)
    - [Volumes (nommés)](#volumes-nommés)
  - [Misc.](#misc)
  - [Références](#références)


1. `docker volume create localvolume`
2. `docker volume inspect localvolume`. Sous la clef, `Mountpoint`, on voit que le volume est stocké sur le path `/var/lib/docker/volumes/localvolume/_data/`
3. *Il n'est pas recommandé* d'écrire directement dans le path du volume crée par Docker, mieux vaut passer par Docker pour initialiser un volume :

~~~bash
#À éviter ! Nécessite les droits super-utilisateur, peut casser le système de fichiers des volumes de Docker.
echo "The Doors of Durin, Lord of Moria. Speak, friend, and enter. I, Narvi, made them. Celebrimbor of Hollin drew these signs." > /var/lib/docker/volumes/localvolume/_data/
~~~

**Méthode à privilégier** :

~~~bash
docker volume create localvolume
#Initialisation des données
echo "The Doors of Durin, Lord of Moria. Speak, friend, and enter. I, Narvi, made them. Celebrimbor of Hollin drew these signs." > index.html
#creation d'un conteneur temporaire
docker create -v localvolume:/usr/share/caddy/site --name tmp caddy:2.7.6
#copie du fichier dans le volume en passant par le conteneur
docker cp index.html tmp:/usr/share/caddy/site
docker rm tmp
~~~

> On voit qu'un volume crée et géré entièrement par Docker n'est pas idéal si on veut initialiser nous-même LES données. Dans ce cas là, on préférera créer un dossier **nous même**, pré peuplé de données,  et l'associer au conteneur via un *bind mount* en indiquant son path. Le problème c'est que ce ne sera pas un volume docker, et on ne bénéficiera pas de [tous ses avantages](https://docs.docker.com/storage/volumes/).

Variation avec l'option `--mount` (plus verbeuse, plus d'options que `--volume` ou `-v`) :

~~~bash
docker create \
--mount 'type=volume,src=localvolume,dst=/usr/share/caddy/site' \
--name tmp-with-mount \
caddy:2.7.6
~~~

> On utilisera `--volume` par la suite

4. `docker inspect caddy:2.7.6-alpine`, on voit que plusieurs ports sont exposés, dont le port `80/tcp` pour les requêtes http;
5. En démarrant un premier conteneur vide
~~~bash
docker run -d -p 8089:80 caddy:2.7.6-alpine
~~~
et en se rendant à l'url `localhost:8089`, on apprend que caddy sert par défaut les fichiers sur le path `/var/www/html/`

Exploration avec le shell interactif :

- root path (fichier servie sur l'URL /) : `/usr/share/caddy/`
- Configuration du routeur : `/etc/caddy/Caddyfile`

6. Le fichier de configuration de Caddy est `/etc/caddy/Caddyfile`. Dedans on y retrouve la définition du `root` qui pointe sur le path par défaut du site statique servi (par défaut `/usr/share/caddy`). On peut modifier la valeur de root en `/usr/share/caddy/site` et y placer un fichier `index.html` de test. Il faut [recharger la config](https://caddyserver.com/docs/command-line#caddy-reload) avec `caddy reload -c /etc/caddy/Caddyfile`;
7. Supprimer le dernier conteneur en une seule commande avec `docker rm -f $(docker ps -lq)`;
8. Créer un fichier `Caddyfile` avec la configuration suivante :

~~~yaml
:80 {
	# Set this path to your site's directory. On fait pointer l'url root sur notre site
    # qui contient le message
	root * /usr/share/caddy/site
	# Enable the static file server.
	file_server
}
~~~

Puis, lancer enfin le conteneur `moria`

~~~bash
#creation du conteneur avec le volume initialisé
docker run -d -p 80:80  \
-v localvolume:/usr/share/caddy/site \
-v $PWD/Caddyfile:/etc/caddy/Caddyfile \
--name moria \
caddy:2.7.6
~~~


Tester :

~~~bash
curl localhost:80
~~~

11. Mettre en pause (`docker pause`) suspend temporairement l'exécution d'un conteneur, ce qui signifie que tous les processus à l'intérieur du conteneur sont arrêtés (ici, pas de réponse de notre serveur web, le client attend jusqu'au timeout). Cela peut êter utile pour figer le monde pour analyser les logs, libérer temporairement des ressources, debug, etc. On relance le conteneur avec `docker unpause`.

13. On peut limiter la mémoire avec `docker run -m`
~~~bash
docker run -d -p 80:80  -v localvolume:/usr/share/caddy/site -v $PWD/Caddyfile:/etc/caddy/Caddyfile --name moria --memory 20m caddy:2.7.6
~~~
On voit que le conteneur a besoin de 10m environ. Si on lui en donne moins que nécessaire, comme 6m :

~~~bash
docker run -d -p 80:80  -v localvolume:/usr/share/caddy/site -v $PWD/Caddyfile:/etc/caddy/Caddyfile --name moria --memory 6m caddy:2.7.6
~~~

Et qu'on inspecte les conteneurs en cours d'execution avec `docker ps`, [on voit qu'il a été stoppé par le kernel](https://docs.docker.com/config/containers/resource_constraints/#understand-the-risks-of-running-out-of-memory) (OOME : Out Of Memory Exception). C'est pourquoi si l'on limite la mémoire d'un conteneur il faut le monitorer et mesurer sa consommation pour éviter qu'il ne tombe, ainsi que son service.

## Un point sur les systèmes de montage

### Bind mounts

Sur [un montage par liaison](https://docs.docker.com/storage/bind-mounts/) (*bind mounts*), le contenu du dossier de destination dans le conteneur **sera remplacé** par le contenu du dossier source sur l'hôte au moment du montage.

~~~bash
#Bind mount (sans volume docker)
docker run -v /chemin/source:/chemin/destination mon_image IMAGE
~~~

> Cela peut porter à confusion car on peut faire un *bind mount* aussi avec l'option `-v`(`--volume`) dédiée aux volumes. En réalité, c'est bien un bind mount, que l'on ferait aussi [avec l'option `--mount`](https://docs.docker.com/reference/cli/docker/container/run/#mount)

### Volumes (nommés)

Sur [un montage de volume nommé](https://docs.docker.com/storage/volumes/) (*volumes*), le contenu du volume existant est préservé, et le contenu du dossier source sur l'hôte **est copié dans le volume** lorsqu'il est monté pour la première fois

~~~bash
docker volume create monvolume
docker run -v monvolume:/chemin/destination mon_image IMAGE
~~~

## Misc.

~~~bash
#Ouvrir un shell interactif (sh) sur le dernier conteneur lancé
docker exec -it $(docker ps -ql) sh
~~~

## Références

- [Add bind mounts or volumes using the --mount flag](https://docs.docker.com/reference/cli/docker/container/run/#mount)
- [Publish all exposed ports (-P, --publish-all)](https://docs.docker.com/reference/cli/docker/container/run/#publish-all)
- [caddy2 official image](https://hub.docker.com/_/caddy)
- [caddy2 doc officielle](https://caddyserver.com/docs/)
- [Runtime options with Memory, CPUs, and GPUs](https://docs.docker.com/config/containers/resource_constraints/)