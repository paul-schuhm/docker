# Docker 1 - Système de conteneurs - TP Module 2 : Premiers pas avec Docker

```{=html}
<hr>
```
Paul Schuhmacher

Module: Docker

```{=html}
<hr>
```
-   [Docker 1 - Système de conteneurs - TP Module 2 : Premiers pas avec
    Docker](#docker-1---système-de-conteneurs---tp-module-2--premiers-pas-avec-docker)
    -   [Pré-requis](#pré-requis)
        -   [Sur Windows](#sur-windows)
    -   [TP 1 : *Qu'est ce qu'un conteneur
        ?*](#tp-1--quest-ce-quun-conteneur-)
    -   [TP 2 : *How do I run a container
        ?*](#tp-2--how-do-i-run-a-container-)
    -   [TP 3 *Run Docker Hub Images*](#tp-3-run-docker-hub-images)
    -   [TP 4 Récapitulatif](#tp-4-récapitulatif)

## Pré-requis

### Sur Windows

[Installer WSL2](https://learn.microsoft.com/fr-fr/windows/wsl/install)
et [Docker
Desktop](https://docs.docker.com/desktop/install/windows-install/). Dans
Docker Desktop, penser à bien utiliser l'instance de GNU/Linux installée
avec WSL2 (Réglages/Ressources/WSL).

Sur votre instance GNU/Linux (Ubuntu, Debian, etc.), ajouter votre
utilisateur au groupe `docker` pour éviter d'avoir à `sudo` toute
commande

``` bash
#ajouter l'utilisateur foo au groupe docker
sudo usermod -aG docker foo
#afficher les groupes auquel appartient l'utilisateur courant (docker doit s'afficher)
groups
#tester (accès à la socket)
docker run hello-world
```

> Pour d'autres détails d'installation, voir le support de cours module
> 2 ou vous rendre sur [la documentation
> officielle](https://docs.docker.com/get-docker/)

## TP 1 : *Qu'est ce qu'un conteneur ?*

Suivre le guide officiel [What is a container
?](https://docs.docker.com/guides/walkthroughs/what-is-a-container/).

Questions supplémentaires :

1.  **Trouver** le code source du site web dans l'image.

## TP 2 : *How do I run a container ?*

Suivre le guide officiel [Run a
container](https://docs.docker.com/guides/walkthroughs/run-a-container/),
**puis** répondez aux questions suivantes :

Pour répondre aux questions, **vous devez uniquement utiliser** le
client `docker` (CLI)

> Vous pouvez utiliser Docker Desktop et sa GUI uniquement pour tester
> vos idées ou inspecter ce que vous faites

1.  **Essayer** de supprimer l'image `welcome-to-docker`. Que se
    passe-t-il ? Comment régler le problème ? Ne supprimez **pas**
    l'image.
2.  **Modifier** le code source de l'app. Changer le message affiché
    `'You ran your first container.'` par `'Hello, world !'`. Relancer
    le conteneur. Qu'observez-vous ? Pourquoi ?
3.  **Reconstruire** l'image précédemment supprimée et relancer le
    conteneur. Quelles étapes de construction sont relancées ? Tester.
4.  Est-il possible de **Lancer** plusieurs conteneurs à partir de la
    même image ? Essayer.
5.  Dans un terminal, **utiliser** le client `docker`. Comment
    **afficher** la liste des commandes disponibles de `docker` ?
    Comment **afficher** l'aide sur une commande spécifique ?
    **Trouver** un moyen de lister les conteneurs et les images
    présentes dans le Docker Engine.
6.  Quelle taille fait le code source de l'image ? Quelle taille fait
    l'image `welcome-to-docker` ? **Expliquer** la différence. *Tip:
    utiliser la commande UNIX disk usage `du`. Pour en savoir plus:
    (`man du`)*;
7.  A l'aide de la documentation de `docker`, **trouver** le moyen de
    redémarrer le dernier conteneur lancé.
8.  **Inspecter** le `Dockerfile`. **Listez** les instructions et
    découvrez à quoi elles servent [en vous servant de la
    documentation](https://docs.docker.com/reference/dockerfile/#dockerfile-reference).
9.  **Changer** le code source pour le remettre dans son état initial
    (message `'You ran your first container.'`). Si vous reconstruisez
    l'image, Docker va essayer de la charger depuis le cache. Trouver un
    moyen de reconstruire l'image (`docker build`) **sans avoir recours
    au cache**. Nommer cette image comme la précédente, soit
    `welcome-to-docker`. **Construire** l'image. **Inspecter** la liste
    de vos images. Qu'observez-vous ? **Lancer** un conteneur avec
    `docker` à partir de la *nouvelle* image. **Supprimer** l'ancienne
    image avec `docker` (*dangling image*);
10. **Lancer** un nouveau conteneur nommé `just-another-container` à
    partir de l'image `welcome-to-docker`
11. Le conteneur exécuté un serveur web. Un serveur web est [un
    processus
    daemonisé](https://fr.wikipedia.org/wiki/Daemon_(informatique))
    auquel doit être associé un port pour récupérer des données. Comment
    savoir à quel port de la machine hôte le port du conteneur *doit*
    être associé pour que tout fonctionne correctement ?

## TP 3 *Run Docker Hub Images*

Suivre le guide officiel [Run Docker Hub
images](https://docs.docker.com/guides/walkthroughs/run-hub-images/).

## TP 4 Récapitulatif

Suivre le guide [Get Started
Guide](https://docs.docker.com/get-started/) **jusqu'à la partie 3**
(incluse).
