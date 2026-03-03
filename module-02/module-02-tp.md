# Conteneurisation - TP Module 2 : Premiers pas avec Docker 

<hr>

Paul Schuhmacher

Octobre 2025

<hr>


## TP 1 : *Qu'est ce qu'un conteneur ?*

> Ce TP a pour objectif de comprendre concrètement ce qu’est un conteneur Docker, en exécutant et en manipulant votre premier conteneur à partir d’une image simple, apprendre quelques commandes du client `docker` (CLI).

**Suivre** le guide officiel [What is a container ?](https://docs.docker.com/guides/walkthroughs/what-is-a-container/). Utilisez dans un premier temps Docker Desktop (outil avec interface graphique) 

Questions supplémentaires :

1. À l'aide de Docker Desktop, **explorer** le contenu du conteneur et **retrouver** le code source du site web.
2. Dans Docker Desktop, supprimer le conteneur précédemment crée, ainsi que l'image `welcome-to-docker`.
3. Reproduisez les étapes du guide (construction, lancement, test, arrêt) **uniquement avec des commandes docker** (et non l’interface graphique de Docker Desktop). **Listez** les commandes utilisées. Pour cela, consultez la commande `docker run`, soit :
   - depuis la documentation client (`docker run --help`); 
   - [depuis la documentation en ligne](https://docs.docker.com/reference/cli/docker/container/run/).
4. À l’aide de la CLI, **arrêtez** et **supprimez** le conteneur précédemment crée.
5. **Notez** les commandes que vous avez utilisées et **décrivez** en une phrase à quoi elles servent.

## TP 2 : *How do I run a container ?*

> Ce TP a pour objectif de comprendre concrètement ce qu’est un conteneur Docker, en exécutant et en manipulant votre premier conteneur à partir d’une image simple, faire la **distinction** entre image et conteneur, découvrir le docker engine, apprendre quelques commandes du client `docker` (CLI).

<!-- 
15/10/24 : Cette ressource a été supprimée, incluant le guide et le lien vers le dépot du code source de l'image
Suivre le guide officiel [Run a container](https://docs.docker.com/guides/walkthroughs/run-a-container/), *puis* répondez aux questions suivantes : 
-->

0. [Télécharger le code source de l'image](https://github.com/docker/welcome-to-docker) sur Github.
1. **Construisez** l’image à partir du `Dockerfile` et nommez-la `welcome-to-docker`.
2. **Lancez** un conteneur à partir de cette image.
3. **Essayez** ensuite de supprimer l'image `welcome-to-docker` :
   1. Que se passe-t-il ? 
   2. Que faudrait-il faire pour supprimer l'image ? (Ne supprimez **pas** l'image pour l'instant).
4. **Modifiez** le code source de l'application : changez le message affiché `'You ran your first container.'` par `'Hello, world !'` ou un texte de votre choix. **Redémarrer** le conteneur. 
   1. Que constatez-vous ? 
   2. L'application affiche-t-elle le nouveau texte ? Pourquoi ?
5. **Reconstruisez** l'image et relancez le conteneur. L’application affiche-t-elle désormais le nouveau texte ?
6. Est-il possible de lancer plusieurs conteneurs à partir d'une *même* image ? 
   1. **Essayer de lancer** plusieurs conteneurs à partir de la même image.
   2. Que **remarquez**-vous ?
7. Dans un terminal, **avec** le client `docker` trouvez :
   1. Comment **afficher** la liste des commandes disponibles de `docker`
   2. Comment **obtenir l’aide** sur une commande spécifique
   3. Les commandes pour **lister les conteneurs** et les **images** présentes dans le Docker Engine
8.  **Inspectez** le fichier `Dockerfile` :
    1.  **Listez** les instructions;
    2.  **Expliquez** le rôle de chaque **instruction** [en vous servant de la documentation](https://docs.docker.com/reference/dockerfile/#dockerfile-reference).
9. Quelle taille fait *le code source de l'image* ? Quelle taille fait l'*image* `welcome-to-docker` ? Comment **expliquer** la différence observée ?
10. **Restaurez** le code source initial (message `'You ran your first container.'`) : 
    1.  Si vous reconstruisez l'image, Docker réutilise son **cache**. Trouvez un moyen de **reconstruire l’image en ignorant le cache** (`docker build`). **Nommer cette image comme la précédente**, soit `welcome-to-docker`. A quoi sert le cache ?
    2.  **Inspectez** à nouveau la liste de vos images. Qu'observez-vous ? 
    3.  **Supprimez** l'*ancienne* image devenue *dangling* (non référencée) avec la commande `docker` appropriée.
11. **Créez** un nouveau conteneur nommé `just-another-container`, basé sur l'image `welcome-to-docker`.
12. **Trouvez** la commande pour mettre **en pause** ce conteneur.
13. **Relancez** le conteneur mis en pause.
14. Enfin, **supprimez** tous les conteneurs crées dans ce TP. **Supprimer** l'image.


## TP 3 *Run Docker Hub Images* 

Suivre le guide officiel [Run Docker Hub images](https://docs.docker.com/guides/walkthroughs/run-hub-images/).

## TP 4 Récapitulatif

Suivre le guide [Get Started Guide](https://docs.docker.com/get-started/) **jusqu'à la partie 3** (incluse).

## Aller plus loin

- [Faire les autres guides](https://docs.docker.com/guides/get-started/);
- Explorer les commandes de `docker` et les tester;
- Modifier le `Dockerfile`, tester d'autres instructions et le code source de l'application servie par l'image `welcome-to-docker`;
- [Parcourir la documentation](https://docs.docker.com/reference/).
