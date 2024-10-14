# How do I run a container ?

[Suivre le guide officiel](https://docs.docker.com/guides/walkthroughs/run-a-container/).

## Réponses aux questions

1. Il est *impossible* de supprimer une image liée à un conteneur *en cours d’exécution*. Le Docker Engine refuse et vous invite à stopper d'abord le conteneur.
2. Pour supprimer l'image, **stopper** le conteneur lié à l'image avec Docker Desktop.
3. Après modification du code source (fichier source `src/App.js`), si l'on redémarre le conteneur on observe que le message affiché par la page web est toujours le même. En effet, le conteneur a été crée à partir de l'image construite avant la modification du code source, cette image embarque donc le code source dans son état initial. Si l'on souhaite modifier le code source, *il faut reconstruire l'image*. Une fois construite, *une image ne peut pas être modifiée* (artefact standalone). C'est ce que l'on veut : **les images Docker sont immutables** ce qui permet la reproductibilité des environnements. Il faut donc *reconstruire une autre image* et relancer un conteneur à partir de celle-ci pour servir le code source modifié.
4. Il est tout à fait possible de lancer plusieurs conteneurs uniques à partir d'une même image (c'est l'intérêt également !). On voit donc apparaître plusieurs conteneurs, chacun avec son port mappé à un port différent de la machine hôte. 
5. Si on tape `docker` sans arguments dans le terminal, on voit la liste des commandes. Pour afficher le manuel de `docker` : `man docker`.  Pour afficher l'aide sur une commande : `docker [commande] --help`. Ainsi, on remarque que `docker ps` permet de lister les conteneurs en cours d'exécution, `docker images` les images présentes dans le Docker Engine. `docker ps -a` permet de lister tous les conteneurs (même ceux stoppés)
6. `docker images` indique que l'image `welcome-to-docker` fait `226MB`. Si l'on se place à la racine du code source de l'image téléchargé sur Github, `du -hs welcome-to-docker` nous indique que le code source fait seulement `1.3MB`. Cette différence s'explique par le fait que l'image est construite à partir d'une image `FROM node:18-alpine` qui elle même contient de nombreuses dépendances et de couches pour préparer l'environnement isolé de l'application node (dependencies, configurations, scripts, binaries, etc.)
7. `docker restart [nom du conteneur]`. Si aucun nom n'est donné au démarrage (via docker ou Docker Desktop), Docker Engine donne un nom aléatoire au conteneur.
8. Voici les instructions que l'on trouve dans le Dockerfile :
   1. `FROM` : Crée une couche de build supplémentaire à partir d'une image de base (composition d'images)
   2. `WORKIDR` : Change le repertoire courant (équivalent de `cd`)
   3. `COPY` : Copie des fichiers et des repertoires
   4. `RUN` : Execute une commande shell
   5. `EXPOSE` : Décrit sur quel port votre application écoute (port mappé au port de la machine hôte)
   6. `CMD` : Définit la commande à exécuter quand le conteneur sera exécuté
9. Le Docker Engine va utiliser les images en cache s'il le peut. Pour forcer la reconstruction d'une image existante il faut outrepasser le cache. On peut voir cette commande avec docker : `docker build --no-cache -t .`. Si on reconstruit l'image et liste les images, on remarque que l'ancienne image portant le même nom *n'a plus de nom* (vérifier que c'est elle avec son ID) ! Elle est marquée comme `dangling image` car elle n'a plus de nom. Le nom d'une image dans le Docker Engine doit être **unique**. Pour supprimer l'image, on peut utiliser `docker rmi {nom image|ID de l'image}`. Comme l'image n'a pas de nom, on ve servir de son ID (hash unique).
10. Pour lancer un conteneur avec docker : `docker run -d -p {port machine hote}:{port conteneur} [--name NOM_CONTENEUR] IMAGE`. Ici `docker run -d -p 8089:3000 --name just-another-container welcome-to-docker`. L'option `-d` permet de détacher l'exécution du conteneur du shell, le passer en arrière-plan (bg). L'option `-p host:container` permet de mapper les ports manuellement.

> On apprendre à utiliser ces commandes et d'autres durant le cours.

<!-- 
ID d'une image est un hachache SHA-256 généré à partir du contenu de l'image.

Pourquoi node_modules supprimé dans le Dockerfile ?

La suppression des modules Node.js est une pratique courante dans les images Docker pour réduire la taille de l'image finale. Une fois que les dépendances ont été installées et que l'application a été construite avec succès, les modules ne sont plus nécessaires dans l'image.
La logique derrière cela est que l'image Docker résultante doit être légère et contenir uniquement ce qui est nécessaire pour exécuter l'application, sans conserver les dépendances de développement qui sont nécessaires uniquement pendant la phase de construction. Derriere build il y a react-scripts build. Lorsque vous exécutez la commande react-scripts build dans un projet React, elle génère un ensemble de fichiers statiques, y compris un fichier JavaScript (généralement appelé main.js ou quelque chose de similaire) qui contient le code de votre application React ainsi que toutes les dépendances nécessaires provenant du dossier node_modules.

Ne pas changer le port 3000. Sûrement défini dans l'image alpine quelquepart (pas trouvé)

 -->

## Références

- [How do I run a container?](https://docs.docker.com/guides/walkthroughs/run-a-container/), guide de la documentation officielle
- [Dockerfile reference](https://docs.docker.com/reference/dockerfile/), liste des instructions que l'on peut utiliser dans un Dockerfile