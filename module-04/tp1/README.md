## Correction TP 3

### build-reports

Voir le [Dockerfile](./Dockerfile-build) et le code source du programme (sh) [build-reports](./build-reports).

~~~bash
chmod +x build-reports
docker build -t build-reports -f Dockerfile-build .
# Passage en argument
docker run --rm build-reports 10
#Déclencher l'erreur
docker run --rm build-reports
#Lister les conteneurs qui ont terminé sur une erreur (exit 1 ici)
docker ps -a -q --filter 'exited=1'
~~~

Avec un volume

~~~bash
docker run --rm -v ./vol_reports:/reports build-reports 20
ls vol_reports
~~~

### approve-reports

Voir le [Dockerfile](./Dockerfile-approve) et le code source du programme (sh) [approve-reports](./approve-reports).

~~~bash
chmod +x approve-reports
docker build -t approve-reports -f Dockerfile-approve .
docker run --rm -v ./vol_reports:/reports approve-reports
~~~

> Pour debug le script, on peut override l'entrypoint avec l'option `--entrypoint` : `docker run --rm -it -v ./vol_reports:/reports --entrypoint sh approve-reports` ouvrir un shell interactif à la place d’exécuter le script défini dans le Dockerfile.

Remarquez que le volume `vol_reports` est la propriété de `root` car il est crée, au moment de la création du volume, par docker, qui a les droits root. Si vous ne voulez pas que le volume appartienne à `root`, le plus simple est de créer le point de montage du volume (dossier `vol_reports`) **avant** avec le propriétaire/droits que vous désirez (votre user par exemple).

<!-- 
Si le repertoire vol_reports n'existe pas : docker le cree avec les droits root
S'il est crée en avance, docker l'utilise et le dossier conserve les droits définis sur la machine hote.
 -->
