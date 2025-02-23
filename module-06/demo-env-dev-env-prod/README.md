# Démo : plusieurs environnements par composition (merge) de fichiers compose

- [Démo : plusieurs environnements par composition (merge) de fichiers compose](#démo--plusieurs-environnements-par-composition-merge-de-fichiers-compose)
  - [Lancer le projet](#lancer-le-projet)
    - [En env de dev (défaut)](#en-env-de-dev-défaut)
  - [En env de prod](#en-env-de-prod)
  - [Publier l'image](#publier-limage)


## Lancer le projet

Il existe deux environnements d'execution des conteneurs :

- `dev` (par défaut ici);
- `prod`

Chaque environnement a son fichier d'environnement:

- dev : `.env`
- prod : `.env.prod`

On utilise le mécanisme de *merge* pour générer à la volée le fichier compose de prod.

### En env de dev (défaut)

~~~
#check interpolation des vars d'environnement
docker compose config
#run en env de dv
docker compose up -d
~~~

## En env de prod

~~~
#check
docker compose -f compose.yaml -f compose.prod.yaml config
#run en env de prod
docker compose -f compose.yaml -f compose.prod.yaml up -d
~~~

## Publier l'image

Lorsque j'ai terminé de développer l'application web, je crée l'image avec un numéro de version et les variables d'env de la prod :

~~~
docker build -t mon-app:1.0.0 --build-arg ENV_FILE=.env.prod .
~~~

Publier sur un registre avec `docker push`.