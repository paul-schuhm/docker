# Démo sur les différentes options de `compose`

On repart de l'exemple donné dans [Try Docker Compose](https://docs.docker.com/compose/gettingstarted/) de la doc officielle.

Créer votre propre projet et préparez-le.

- [Démo sur les différentes options de `compose`](#démo-sur-les-différentes-options-de-compose)
  - [YAML](#yaml)
  - [up](#up)
  - [Développement : Watch des sources **sans** volume](#développement--watch-des-sources-sans-volume)
  - [Variables d'environnement](#variables-denvironnement)
  - [Secrets](#secrets)
  - [Utiliser les profiles (activation/désactivation de services)](#utiliser-les-profiles-activationdésactivation-de-services)
  - [Rediriger les logs](#rediriger-les-logs)
  - [Override les variables d'environnement de Docker Compose](#override-les-variables-denvironnement-de-docker-compose)
  - [Un seul ou plusieurs fichiers ? Démo d'une méthode basée sur le merge](#un-seul-ou-plusieurs-fichiers--démo-dune-méthode-basée-sur-le-merge)
  - [Docker compose et l'orchestration](#docker-compose-et-lorchestration)


## YAML

Utiliser un linter pour détecter les erreurs, par exemple `yamllint`

~~~bash
`yamllint compose.yml`
~~~

## up

~~~bash
docker compose up
docker compose ps
docker volume ls
#Voir quel volume est attaché à un conteneur
docker inspect <id conteneur> | grep Mounts -A 10
~~~

Regarder les conteneurs (le nom par défaut : nom du projet-nom du service-d'un identifiant incrémenté)

> Si pas de nom de projet, nom du repertoire courant

On voit qu'un [volume anonyme](https://docs.docker.com/reference/dockerfile/#volume) a été crée. Il a été crée par redis (Dockerfile de son image contient instruction `VOLUME`), aller voir le code source de son image (`Dockerfile`) pour vous en convaincre.

## Développement : Watch des sources **sans** volume

~~~yaml
---
services:
  web:
    build: .
    ports:
      - "8000:5000"
    #Plus besoin de ce hack ici
    # volumes: #volume anonyme
    # - .:/code
    develop:
    watch:
      - path: ./
        action: sync
        target: /code
    environment:
      FLASK_DEBUG: "true"
  redis:
    image: "redis:alpine"
~~~

~~~bash
#Lancer le projet
docker compose up -d
#Lancer le watch
docker compose watch
~~~

Puis modifier les sources, et requêter le serveur web pour tester le reload.

## Variables d'environnement

**Placer les var. d'env. dans un fichier externe** : plus simple à maintenir, allège le fichier compose, **centralise les valeurs**

Créer un fichier d'env `.env`

> Chargé auto par Docker Compose (comportement par défaut, override dans varible d'env COMPOSE_ENV_FILES)

~~~INI
MA_VARIABLE=FOO
FLASK_DEBUG=false
~~~

~~~yaml
  web:
    build: .
    ports:
      - "8000:5000"
    # volumes: #volume anonyme
    # - .:/code
    environment:
      FLASK_DEBUG: ${FLASK_DEBUG}
      MA_VARIABLE: ${MA_VARIABLE}
~~~

Inspecter variable d'env sur le conteneur :

~~~bash
docker compose up
docker exec <nom> env
~~~

`docker compose up` recrée un conteneur si sa config (compose) change. Si la variable d'environnement interpolée change dans le `.env`, `docker compose` **le détecte et recrée aussi le conteneur** ! Cool.

Changer :

~~~INI
FLASK_DEBUG=true
~~~

puis 

`docker compose up`

> Observer la reconstruction automatique du conteneur au changement d'une variable d'environnement.

> La variable d'env du `.env` n'est injectée que si elle est utilisée dans le compose ou par le conteneur.

> Sur un dépôt on créera un fichier `.env.dist` avec des configs par défaut. Chaque personne pull le dépôt, fait une copie `cp .env.dist .env` et définit ses propres variables d’environnement en fonction de ses besoins. Si pousser des modifs : les mettre dans le `.env.dist.` (partager avec le reste de l'équipe). Sinon, ignorer le `.env` (local a la machine, au dev) en le mettant dans le `.gitignore`.

`docker compose config` pour checker l'interpolation

## Secrets

Créer un fichier `password` avec `my-secret-password`, la valeur à protéger le plus possible:

~~~bash
echo 'my-secret-password' > secret

~~~yaml
services:
  web:
    build: .
    ports:
      - "8000:5000"
    environment:
      FLASK_DEBUG: ${FLASK_DEBUG}
      MA_VARIABLE: ${MA_VARIABLE}
    secrets:
        - my_secret

  redis:
    image: "redis:alpine"
#Nouvelle section
secrets:
    my_secret:
        file: ./password
~~~

Modifier le code source :

~~~python
import time
import os

import redis
from flask import Flask

app = Flask(__name__)
cache = redis.Redis(host='redis', port=6379)

def get_hit_count():
    retries = 5
    while True:
        try:
            return cache.incr('hits')
        except redis.exceptions.ConnectionError as exc:
            if retries == 0:
                raise exc
            retries -= 1
            time.sleep(0.5)

@app.route('/')
def hello():
    count = get_hit_count()
    # Chemin vers le fichier secret
    secret_file_path = "/run/secrets/my_secret"  # Ce chemin peut varier selon le système d'exploitation

    # Vérifier si le fichier secret existe
    if os.path.exists(secret_file_path):
        # Lire le contenu du fichier secret
        with open(secret_file_path, 'r') as secret_file:
            secret_value = secret_file.read().strip()
    else:
        secret_value = 'not found, sorry'
    return 'Coucou  ! I have seen this {} times. Voici le secret : {}\n'.format(count, secret_value)
~~~

Reconstruire l'image

~~~bash
docker compose build web
docker compose up -d
#Test
curl localhost:8000
~~~

## Utiliser les profiles (activation/désactivation de services)

Modifier le compose

~~~yaml
---
services:
  web:
    build: .
    ports:
      - "8000:5000"
    # volumes: #volume anonyme
    # - .:/code
    environment:
      FLASK_DEBUG: ${FLASK_DEBUG}
      MA_VARIABLE: ${MA_VARIABLE}
    # deploy:
    #   mode: replicated
    #   replicas: 3
    secrets:
      - my_secret
    profiles: [frontend]
    develop:
      watch:
        - path: ./
          action: sync
          target: /code

  redis:
    image: "redis:alpine"
~~~


Test :

~~~bash
#Les 2 s'activent (web et redis)
docker compose --profile frontend up
#Que redis
docker compose up
~~~

## Rediriger les logs

~~~bash
#Rediriger les logs des events dans un fichier en tache de fond
docker compose events > dengine.log &
~~~

## Override les variables d'environnement de Docker Compose

Voir les variables

Dans un fichier `.env` a la racine du projet :

~~~INI
COMPOSE_PROFILES=frontend
~~~

## Un seul ou plusieurs fichiers ? Démo d'une méthode basée sur le merge

https://docs.docker.com/compose/multiple-compose-files/merge/

Créer un fichier `compose.dev.yaml` et  `compose.prod.yaml` en plus du fichier de base `compose.yaml`.

~~~yaml
#compose.dev.yaml
---
services:
  web:
    environment:
      MA_VARIABLE: ${MA_VARIABLE_DEV}
~~~

~~~yaml
#compose.prod.yaml
---
services:
  web:
    environment:
      MA_VARIABLE: ${MA_VARIABLE_PROD}
~~~

Et toujours un de base `compose.yaml` (config par défaut, a ne jamais utiliser seul)

~~~yaml
#compose.yaml
---
services:
  web:
    build: .
    ports:
      - "8000:5000"
    environment:
      FLASK_DEBUG: ${FLASK_DEBUG}
      MA_VARIABLE: ${MA_VARIABLE_PROD}
    secrets:
      - my_secret
    profiles: [frontend]
    develop:
      watch:
        - path: ./
          action: sync
          target: /code
  redis:
    image: "redis:alpine"
    profiles: [debug]
secrets:
  my_secret:
    file: ./password
~~~

> Remarque : ici je n'ai qu'un fichier d'environnement (pour les besoins de la démo), mais il en faudrait deux : `prod.env`, `dev.env`, et chaque fichier `compose` ferait référence à son propre fichier d’environnement sous [l'attribut `env_file`](https://docs.docker.com/compose/compose-file/05-services/#env_file).

~~~bash
#Lancer en env de prod
docker compose -f compose.yml -f compose.prod.yml up -d
docker exec -it composetest-web-1 env
#Lancer en env de dev
docker compose -f compose.yml -f compose.dev.yml up -d
docker exec -it composetest-web-1 env
~~~

> L'ordre est **important** : docker compose -f compose.prod.yml -f compose.yml est != de docker compose -f compose.yml -f compose.prod.yml

Le mieux c'est d'essayer *vous-même* les différentes méthodes (heritage, include, merge, combinaison des mécanismes,...) :
- Faites un **mini projet minimal** pour chaque méthode (avec 2 ou 3 services par exemple)
- Simuler la variation d'un paramètre d'environnement. Faites les modifs nécessaires (port, path, etc.)
- Regarder ce que vous devez modifier et quels fichiers vous allez modifier
- Une fois terminés, regardez ce que vous allez *push* comme changements sur un depot (utiliser git, ou un outil de diff pour vous assister)
- Regarder ce que vous allez *pull* comme changements depuis le dépôt;
- Regardez les erreurs que vous pouvez rencontrer, commettre;
- Regarder les commandes `docker compose up` que vous devez lancer, si vous devez les modifier

Choisir la méthode **qui fait le plus sens pour vous** et **votre organisation**. C'est une décision à débattre et trouver un compromis (chacun·e ses préférences).


## Docker compose et l'orchestration

Au delà de docker compose up qui est capable de détecter n'importe quel rebuild à faire en fonction des changements des sources, compose offre également `deploy`, `replica` et `restart`

~~~bash
services:
  web:
    build: .
    ports:
      - "8000:5000"
    # volumes: #volume anonyme
    # - .:/code
    environment:
      FLASK_DEBUG: "true"
    deploy:
      mode: replicated
      replicas: 3
~~~

~~~bash
docker compose up
docker compose ps
~~~

Si on combine `deploy` avec `restart`, on peut créer un *pool* de conteneurs issus d'un service **dont le nombre est déterminé avec certitude à l'execution et dans le temps** (disponibilité renforcée)

~~~bash
services:
  web:
    build: .
    ports:
      - "8000:5000"
    # volumes: #volume anonyme
    # - .:/code
    environment:
      FLASK_DEBUG: "true"
    deploy:
      mode: replicated
      replicas: 3
    restart: always #par exemple
~~~

Kill un conteneur du service `web` et *observez*. Vous devriez toujours en avoir 3 (un autre de crée). Regardez les stats ou les events pour mieux voir ce qu'il se passe.