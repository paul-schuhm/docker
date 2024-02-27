# Démo : Les couches sont additives

On construit une image basée sur Fedora d'application web avec le serveur web d'Apache :

~~~bash
docker build -t size1 -f Dockerfile-large .
docker history size1
~~~

Voici la sortie :

~~~bash
2cde07ab32fc   20 seconds ago   CMD ["/usr/sbin/httpd" "-DFOREGROUND"]          0B        buildkit.dockerfile.v0
<missing>      20 seconds ago   RUN /bin/sh -c dnf install -y httpd # buildk…   318MB     buildkit.dockerfile.v0
<missing>      3 days ago       /bin/sh -c #(nop)  CMD ["/bin/bash"]            0B        
<missing>      3 days ago       /bin/sh -c #(nop) ADD file:cdbc7eeb8f0a47497…   177MB     
<missing>      11 months ago    /bin/sh -c #(nop)  ENV DISTTAG=f39container …   0B        
<missing>      2 years ago      /bin/sh -c #(nop)  LABEL maintainer=Clement …   0B  
~~~

La ligne 177MB est l'ajout des fichiers de l'image Fedora. Ok.

Par contre l'instruction pour installer serveur web `httpd` (Apache web server) a crée une couche de 318 MB ! C'est énorme. C'est pas la taille du programme httpd. La taille vient du cache du gestionnaire de paquet de Fedora `dnf`. On veut supprimer le cache alors avec `dfn clean all` (inutile de l'embarquer dans l'image)

~~~dockerfile
#Nouvelle version
FROM fedora
RUN dnf install -y httpd
RUN dnf clean all
CMD ["/usr/sbin/httpd", "-DFOREGROUND"]
~~~

~~~bash
docker build -t size2 -f Dockerfile-clean .
docker history size2
~~~

Sortie :

~~~bash
IMAGE          CREATED          CREATED BY                                      SIZE      COMMENT
bd6558e2d809   2 seconds ago    CMD ["/usr/sbin/httpd" "-DFOREGROUND"]          0B        buildkit.dockerfile.v0
<missing>      2 seconds ago    RUN /bin/sh -c dnf clean all # buildkit         66kB      buildkit.dockerfile.v0
<missing>      11 minutes ago   RUN /bin/sh -c dnf install -y httpd # buildk…   318MB     buildkit.dockerfile.v0
<missing>      3 days ago       /bin/sh -c #(nop)  CMD ["/bin/bash"]            0B        
<missing>      3 days ago       /bin/sh -c #(nop) ADD file:cdbc7eeb8f0a47497…   177MB     
<missing>      11 months ago    /bin/sh -c #(nop)  ENV DISTTAG=f39container …   0B        
<missing>      2 years ago      /bin/sh -c #(nop)  LABEL maintainer=Clement …   0B 
~~~

La couche précédente fait toujours la même taille. **Une couche ne peut pas être modifiée !**

Solution : **mettre l'install et le clean du cache sur la même instruction**, même couche (avant qu'elle ne soit *forgée*). Pour cela **utiliser l'opérateur `&&`** et `\` (passage à la ligne, lisibilité) :

~~~dockerfile
FROM fedora
RUN dnf install -y httpd && \
    dnf clean all
CMD ["/usr/sbin/httpd", "-DFOREGROUND"]
~~~

~~~bash
docker build -t size3 -f Dockerfile-better .
docker history size3
~~~

Sortie :

~~~bash
IMAGE          CREATED          CREATED BY                                      SIZE      COMMENT
eb1aeea3e1f7   17 seconds ago   CMD ["/usr/sbin/httpd" "-DFOREGROUND"]          0B        buildkit.dockerfile.v0
<missing>      17 seconds ago   RUN /bin/sh -c dnf install -y httpd &&     d…   46.4MB    buildkit.dockerfile.v0
<missing>      3 days ago       /bin/sh -c #(nop)  CMD ["/bin/bash"]            0B        
<missing>      3 days ago       /bin/sh -c #(nop) ADD file:cdbc7eeb8f0a47497…   177MB     
<missing>      11 months ago    /bin/sh -c #(nop)  ENV DISTTAG=f39container …   0B        
<missing>      2 years ago      /bin/sh -c #(nop)  LABEL maintainer=Clement …   0B   
~~~

La couche ne fait plus **que 46.4MB** ! Beaucoup mieux !