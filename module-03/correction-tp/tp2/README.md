# Correction TP 2

## Version non optimisée

~~~bash
docker build -t guess:1 -f Dockerfile .
docker images
docker run -it guess:1
~~~

La taille de l'image est de **1.37 GB** ! Ça fait beaucoup !

## Version optimisée

[Voir le Dockerfile](./Dockerfile-optimise).

On utilise le *Multi-Stage Builds*, compile le programme en *static* et crée une image basée sur [l'image vide scratch](https://hub.docker.com/_/scratch/).

~~~bash
docker build -t guess:2 -f Dockerfile-optimise .
docker images
docker run -it guess:2
~~~

On place l'instruction qui copie le code vers la fin du `Dockerfile` (pour favoriser l'usage du cache lors de la phase de build). En effet, si une instruction modifie l'image, toutes les couches suivantes doivent être rebuild.

La taille de l'image est de 969 kB ! On a gagné plus d'un facteur 1000 !

5. Quelques petites choses :
- `COPY` copie tout y compris d'éventuels fichiers inutiles dans le repertoire du projet. Il vaut mieux copier que ce qui est nécessaire ou définir un fichier `.dockerignore`
- Il faut placer l'instruction `COPY` vers la fin du `Dockerfile` pour réutiliser les couches en cache précédentes.

6. *Bonus:* Pourquoi est-ce utile de créer un binaire static du programme ? Quels sont les avantages et inconvénients ? 

Le binaire *static* embarque tout le code binaire de ses dépendances (le code binaire des librairies standard comme `scanf`, `printf` etc.). Cela alourdit considérablement le binaire (regarder sa taille) mais lui permet d'être autonome. Sur l'image `SCRATCH`, il n'y a pas la lib standard du C (shared lib en .so) donc si le programme était compilé de manière dynamique (par défaut) il ne pourrait pas s'executer car il chercherait le code de la lib standard sans le trouver sur le système de fichiers du conteneur. L'avantage c'est de faire un binaire *standalone* et de pouvoir l'embarquer dans une image de petite taille (moins de 1MB contre 1.5GB de l'image gcc !). L'inconvénient, en général, c'est que le binaire est plus lourd et qu'un binaire dynamiquement lié qui lui, partage le code binaire de ses dépendances avec les autres programmes, donc ca économise de la mémoire.