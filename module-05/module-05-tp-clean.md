# Docker 1 - Système de conteneurs - TP Module 4 et Module 5 : Dynamique des conteneurs, inspecter, RESTful API

<hr>

Paul Schuhmacher

Module: Docker

<hr>
- [Docker 1 - Système de conteneurs - TP Module 4 et Module 5 : Dynamique des conteneurs, inspecter, RESTful API](#docker-1---système-de-conteneurs---tp-module-4-et-module-5--dynamique-des-conteneurs-inspecter-restful-api)
  - [TP 1 : Manipuler les volumes, dynamique et monitoring d'un conteneur](#tp-1--manipuler-les-volumes-dynamique-et-monitoring-dun-conteneur)
    - [Découverte du serveur Caddy](#découverte-du-serveur-caddy)
    - [Préparation d'un volume pour le développement du site web](#préparation-dun-volume-pour-le-développement-du-site-web)
    - [Contraintes à l'execution et monitoring du conteneur](#contraintes-à-lexecution-et-monitoring-du-conteneur)
  - [TP 2 : RESTful API du Docker Engine](#tp-2--restful-api-du-docker-engine)
    - [Quelques clients HTTP](#quelques-clients-http)

## TP 1 : Manipuler les volumes, dynamique et monitoring d'un conteneur

> Découvrir également le serveur web [Caddy 2](https://en.wikipedia.org/wiki/Caddy_(web_server)), un serveur web écrit en Go, léger et efficace.

### Découverte du serveur Caddy

1. **Télécharger** l'image [l'image officielle de caddy2](https://hub.docker.com/_/caddy), un serveur web open source performant et léger écrit en Go. **Inspecter**-là pour regarder les ports ouverts indiqués. Trouver le port auquel il faudra mapper le port de votre machine hôte pour servir un site web;
2. **Démarrer** un premier conteneur à partir de l'image (sans volume) et inspecter la sortie avec votre navigateur préféré. **En déduire** où monter le volume pour servir votre fichier `content`. [**Lire** la documentation](https://caddyserver.com/docs/) et l'exemple pour comprendre comment utiliser l'image.
3. **Ouvrir** un shell interactif sur le conteneur (avec `sh`). **Découvrir** où se situe le fichier de configuration de Caddy notamment son *routeur* (mapping *METHODE HTTP+URL* => path du fichier à servir). Le fichier à chercher se nomme `Caddyfile`;
4. **Découvrir** sous la clef `root` du fichier de configuration *le path par défaut*. Y créer un dossier `site` et y placer un fichier `index.html` avec le contenu `"hello, world"`. Toujours depuis le conteneur [**recharger la configuration** du serveur](https://caddyserver.com/docs/command-line#caddy-reload). **Tester** que `site/index.html` est bien servi sur l'URL racine (`/`). *Tip: vous avez l'éditeur `vi` accessible sur le conteneur pour éditer les fichiers*
5. Pourquoi sur la page officielle est-il indiqué (section `Building your own Caddy-based image`) : `note: never use the :latest tag in a production site` ?

> Il est toujours utile d'inspecter avec un shell interactif un nouveau système en faisant des tests rapides, avant de préparer un Dockerfile ou la création de conteneurs et le bind de volumes.


### Préparation d'un volume pour le développement du site web

Nous allons préparer un volume et y placer nos sources (ici un simple fichier HTML). Ainsi, nous pourrons développer le site (éditer le fichier HTML) sans reconstruire l'image à chaque modification. 

Lorsqu'un volume est entièrement géré par Docker, nous n'avons pas (par design) accès au système de fichier sous-jacent. Pour le manipuler on peut passer par un conteneur en associant le volume à ce dernier.

1. **Créer** un volume nommé `localvolume`;
2. **Déterminer** l'emplacement du contenu du volume sur votre système de fichiers;
3. **Écrire** un fichier `index.html` dans le volume `localvolume` avec le contenu `"The Doors of Durin, Lord of Moria. Speak, friend, and enter. I, Narvi, made them. Celebrimbor of Hollin drew these signs."`. Pour cela, **créer un conteneur** à partir de l'image de caddy en lui associant le volume, puis copier le fichier `index.html` dans le conteneur avec la commande `docker cp`.
4. **Arrêter** et **supprimer** ce conteneur (le dernier lancé) **en une seule commande**. *Tip: combiner deux commandes `docker` pour y parvenir.*

Nous le voyons, lorsque nous gérons un ensemble de fichiers que l'on veut persister et gérer nous même (comme les sources), un volume entièrement géré par Docker n'est pas très commode. Il est plus simple de binder nous même le dossier local contenant nos sources et de le gérer nous même.

> Un volume entièrement géré par Docker est plus approprié lorsque 1) l'on veut que les données persistantes soient entièrement intégrées au worklow Docker (un volume peut être déplacé comme une image) 2) les données sont produites durant l'execution du conteneur. Sinon, utiliser un bind est plus commode.

5. **Démarrer** un conteneur `moria`, en background, à partir de l'image caddy
   1. 1ère méthode : En lui associant le volume `localvolume` [avec l'option --mount](https://docs.docker.com/reference/cli/docker/container/run/#mount) sur le path déterminé précédemment pour monter notre site web;
   2. 2ème méthode (à privilégier ici) : En faisant un bind du fichier `index.html` directement;
<!-- 3.  En lui montant un *bind-mound* pour un fichier de configuration `Caddyfile`, afin de charger la bonne configuration testée précédemment. -->

> [La *Moria*](https://fr.wikipedia.org/wiki/Moria_(Terre_du_Milieu)), ou Khazad-dûm, est une ancien royaume nain situé sous les montagnes de Brumes, en Terres du milieu, univers fictif développé et écrit par Tolkien (et son fils Christopher). Dans le seigneur des anneaux, la compagnie se retrouve notamment face aux portes de la Moria qui restent désespérément closes...

### Contraintes à l'execution et monitoring du conteneur

6.  **Mettre en pause** le conteneur `moria` avec le client `docker`. **Essayer** de le requêter avec cURL sur le port TCP ouvert. Que constatez-vous ? *Tip: vous pouvez définir un timeout de n secondes avec `curl --max-time n url`*. **Enlever** la pause du conteneur. A quoi sert la mise en pause d'un conteneur ?
7.  **Ouvrez** un second terminal dans lequel vous **afficherez** les statistiques sur le conteneur `moria`. Dans le premier terminal, envoyer des requêtes pour observer les stats (notamment l'I/O). Observer la mémoire utilisée par le conteneur (sans limites imposées).
8.  **Redémarrer le conteneur avec une limite de mémoire de 10M**. Vérifier que la limite est en place. Essayer de redémarrer avec une mémoire de **5M**. Que s'est-il passé ?
9.  Que fait l'instruction `docker run -P` ? **Essayer** avec un conteneur démarré à partir de l'image de caddy.
10. *Bonus* : sur le conteneur caddy, utiliser PHP et écrire un script qui sera associé à l'URL `POST /password` avec le body `password=mellon`. Le script doit vérifier le mot de passe fourni. Si le mot de passe est correct, le serveur doit répondre avec un code status 200 et le document HTML *"(Les portes s'ouvrent)"*, avec *"(Rien ne se passe)"* sinon.

<!-- <img src="./assets/moria.png" height="200"> -->
## TP 2 : RESTful API du Docker Engine

1.  A l'aide **uniquement** de [l'API web RESTful du Docker
    Engine](https://docs.docker.com/engine/api/) : **construire(build)**
    une image, **créer** un conteneur, **afficher** les stats d'un
    conteneur, **arrêter** un conteneur, **supprimer** un conteneur,
    etc. (et essayez d'autres actions).

Pour ce faire, vous pouvez utiliser [cURL](https://curl.se/), avec
[jq](https://jqlang.github.io/jq/), un équivalent de sed dédié à JSON
(*pretty-print*, filter, edit, etc.) [ou python (extension
jsontool)](https://pypi.org/project/jsontool/) :

    #curl via le socket
    curl --unix-socket /var/run/docker.sock URL
    #prettyfier le json avec jq
    curl --unix-socket /var/run/docker.sock URL | jq
    #prettyfier le json avec python et l'extension json
    curl --unix-socket /var/run/docker.sock URL | python -mjson.tool

ou votre client HTTP favori.

2.  Quelles possibilités s'offrent à nous pour protéger l'API du Docker
    Engine ?

> Nous y reviendrons dans le Module 07.

### Quelques clients HTTP

-   [cURL](https://curl.se/), un outil en ligne de commande pour le
    transfert de données via des URL. Peut être utilisé comme un client
    HTTP. Attention, cURL sur Windows et cURL sur les systèmes UNIX
    (GNU/Linux et macOS) *ne sont pas implémentées pareil et ont des
    options différentes*. **Tous les exemples de cURL sur le dépôt et
    dans les supports de cours sont au format UNIX** (à adapter pour
    Windows), sinon [installer la
    WSL](https://learn.microsoft.com/fr-fr/windows/wsl/install) et
    utiliser cURL depuis votre instance GNU/Linux
-   Sur Windows,
    [Invoke-RestMethod](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.utility/invoke-restmethod?view=powershell-7.3),
    un client HTTP intégré à PowerShell. **Ouvrir une invite de commande
    PowerShell** pour l'utiliser
-   [Postman](https://www.postman.com/), l'artillerie lourde. Nécessite
    de créer un compte

[Voir la référence de l'API (version actuelle :
1.44)](https://docs.docker.com/engine/api/v1.44/)
