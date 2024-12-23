# Démo : Travailler avec plusieurs fichiers *compose*

On se base ici [sur la méthode de merge](https://docs.docker.com/compose/how-tos/multiple-compose-files/merge/) pour bâtir des fichiers compose (d'orchestration et de configuration de nos services) propres à chaque environnement.

## Lancer le projet (environnement dev)

~~~bash
cp .env.dist .env
docker compose -f compose.yaml -f compose.dev.yaml up -d
~~~

## Lancer le projet (environnement prod)

~~~bash
cp .env.dist .env
docker compose -f compose.yaml -f compose.prod.yaml up -d
~~~

## Build

Quand on a fini le développement, on peut build l'image (artefact à publier sur le registre ou à déployer)

~~~bash
docker compose -f compose.yaml -f compose.dev.yaml build
~~~

## Simplifier l'interface utilisateur

Pour simplifier la configuration, le build, le lancement des conteneurs, le relancement, etc.

Créer:

1. Des alias `alias="docker..."`. Les persister dans le fichier de config du shell (par ex .bashrc);
2. Un script shell;
3. Un [Makefile](./Makefile)


## Références utiles

- [Use multiple Compose files](https://docs.docker.com/compose/how-tos/multiple-compose-files/)
- [Make](https://fr.wikipedia.org/wiki/Make)
