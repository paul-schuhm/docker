# Démo : Une méthode pour debug une construction d'image

On part du code source de l'image [welcome-to-docker](https://github.com/docker/welcome-to-docker)

1. Introduire volontairement un problème dans le `Dockerfile` pour amener à l'échec de la fabrication de l'image :

~~~dockerfile
# On introduit une erreur volontairement RUN npm install => RUN npm intall (typo)
#RUN npm install \
#    && npm install -g serve \
#    && npm run build \
#    && rm -fr node_modules

RUN npm intall \
    && npm install -g serve \
    && npm run build \
    && rm -fr node_modules
~~~

2. On lance le build (en désactivant temporairement BuildKit, le nouveau Builder de Docker, qui n'affiche plus les hash des images intermédiaires) :

~~~bash
DOCKER_BUILDKIT=0 docker build -t debug-image .
~~~

3. On identifie la dernière image qui a été construite (dernière instruction du Dockerfile) **qui a fonctionné** et on construit lance un conteneur jetable **à partir de cette image** dans laquelle on ouvre un shell interactif :

~~~bash
#Si l'id de la dernière image est ba3eeb9eac1e
docker run --rm -ti ba3eeb9eac1e /bin/sh
~~~

4. On dispose à présent d'une version de l'image **avant** que le pb n'apparaisse. On peut fix l'instruction du Dockerfile, faire des test. Quand on est satisfait, on relance le build

~~~bash
docker build -t debug-image .
~~~
