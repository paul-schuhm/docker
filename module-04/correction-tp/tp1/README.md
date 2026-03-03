## Correction TP 3

Utilisation de conteneurs atomiques, échangeant les données produites via un volume/bind mount. Un premier processus doit générer des rapports, un deuxième doit les approuver. On souhaite séparer ces deux tâches dans deux conteneurs isolés.

### 1ère tâche : build-reports

Voir le [Dockerfile](./Dockerfile-build) et le code source du programme (sh) [build-reports](./build-reports).

~~~bash
chmod +x build-reports
docker build -t build-reports -f Dockerfile-build .
# Passage en argument
docker run --rm -it build-reports 10
#Déclencher l'erreur
docker run --rm -it build-reports
#Lister les conteneurs qui ont terminé sur une erreur (exit 1 ici)
docker ps -a -q --filter 'exited=1'
# Sans supprimer le conteneur pour recup les fichiers
docker run -it build-reports 10
# Récupérer depuis le conteneur arrêté les fichiers (docker cp)
docker cp <id>:/reports .
~~~

Pour persister et traiter les données générées par le conteneur, on utilise un "volume" (ou un *bind mount* plus précisémment ici). Ainsi le cycle de vie des données devient indépendant du cycle de vie du conteneur (je peux le supprimer sans soucis par ex) :

~~~bash
#On y stockera les rapports générés
mkdir -p vol_reports
docker run --rm -v ./vol_reports:/reports build-reports 20
ls vol_reports
~~~

### 2è tâche : approve-reports

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


 ## Annexe

Script `build-reports`:

~~~bash
#!/bin/sh

# Vérifier si l'argument n'est pas fourni
if [ -z "$1" ]; then
    echo "Erreur: indiquer le nombre de rapports à préparer et archiver. Stop."
    exit 1
fi

# Utiliser la valeur passée en argument pour n
n="$1"

# Création du dossier "reports" s'il n'existe pas
mkdir -p reports
rm reports/*

# Génération des fichiers
i=1
while [ "$i" -le "$n" ]; do
    filename="reports/report$(printf "%04d" "$i").txt"
    echo "REPORT $i" > "$filename"
    i=$((i+1))
done

# Archivage du dossier "reports"
tar -cf reports.tar reports
mv reports.tar reports/

echo "Préparation des $n rapports et archivage terminés."
~~~


 Script `approve-reports`:

 ~~~bash
 #!/bin/sh

#Extrait les fichiers dans le repertoire reports
#directement dans le repertoire courant
tar -xf reports/reports.tar --strip-components=1 -C reports

# Approbation des rapports
i=1
n=$(ls reports/ | grep "report.*\.txt" | wc -l)

while [ "$i" -le "$n" ]; do
    filename="reports/report$(printf "%04d" "$i").txt"
    echo "Approved" >> "$filename"
    i=$((i+1))
done

echo "Approbation des $n rapports terminée."
~~~

`Dockerfile-approve`

~~~Dockerfile
FROM alpine:latest
WORKDIR /
ADD ./approve-reports /approve-reports
ENTRYPOINT ["./approve-reports"]
~~~

`Dockerfile-build`

~~~Dockerfile
FROM alpine:latest
WORKDIR /
ADD ./build-reports /build-reports
ENTRYPOINT ["./build-reports"]
~~~