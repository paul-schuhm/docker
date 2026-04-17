# Module 6 Compose - TP 4 : Setup projet web multi-services Correction

## Points abordés

- Compose adapté à plusieurs environnements (*developement* et *production*), [avec méthode de merge de fichiers compose](https://docs.docker.com/compose/how-tos/multiple-compose-files/merge/)
- [Fichiers .env pour chaque environnement](https://docs.docker.com/compose/how-tos/environment-variables/) (`.env` pour developement par défaut)
- [*Multi-stage* builds](https://docs.docker.com/build/building/multi-stage/) dans les `Dockerfile*` pour construire des images adaptées à chaque environnement, contenant le strict nécessaire
- Mode watch pour l'environnement de développement

> Ce n'est qu'une proposition, il y a plusieurs façons d'organiser ses configurations.

## Environnements

### Développement

- Application cliente ;
- Application serveur
  - endpoint check config PHP (/phpinfo.php) ;
  - fichier de config de php (php.ini) dédié au debug
- Base de données MariaDB
- Mailcatcher (Mailhog)
- Outil d'administration de bdd avec interface graphique (Adminer)

### Production

- Application cliente ;
- Application serveur ;
  - fichier de config de php (php.ini) adapté à la production
- Base de données MariaDB

## Lancer le projet en env de dev

~~~bash
#check
docker compose -f compose.yaml -f compose.dev.yaml up config
docker compose -f compose.yaml -f compose.dev.yaml up --build watch
~~~

Accéder à l'application cliente sur `http://localhost:3000`

## Arrêter le projet (et supprimer les volumes)

~~~bash
docker compose -f compose.yaml -f compose.dev.yaml down -v
~~~

## Lancer le projet en env de prod

~~~bash
#check
docker compose -f compose.yaml -f compose.prod.yaml --env-file .env.prod config
docker compose -f compose.yaml -f compose.prod.yaml --env_file .env.prod up --build
~~~

Accéder à l'application cliente sur `http://localhost:3000`

## Build les images pour publication sur dépôt (pour la mise en production)

~~~bash
docker compose -f compose.yaml -f compose.prod.yaml --env-file .env.prod build front back
~~~

Lister les images build pour la prod :

~~~bash
docker images | grep -E '(^front|^back)'
~~~

Le numéro de version est défini dans .env.prod mais peut être passé également au moment du build

~~~bash
VERSION=1.0 docker compose -f compose.yaml -f compose.prod.yaml --env-file .env.prod build front back
~~~

On peut à présent *push* ces images sur un registre pour les déployer.