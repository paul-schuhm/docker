# Démo Multi-Stage Builds

- [Démo Multi-Stage Builds](#démo-multi-stage-builds)
  - [Utiliser](#utiliser)
  - [Conclusion](#conclusion)
  - [Références](#références)


Une démo qui illustre le [Multi-Stage builds](https://docs.docker.com/build/building/multi-stage/) et son utilité.

## Utiliser

1. Créer le Dockerfile suivant :

~~~dockerfile
FROM golang:1.21
WORKDIR /src
# Code source go directement embarqué (syntaxe Heredoc)
# dans le Dockerfile pour l'exemple
COPY <<EOF ./main.go
package main

import "fmt"

func main() {
  fmt.Println("hello, world")
}
EOF
#Compilation
RUN go build -o /bin/hello ./main.go

FROM scratch
COPY --from=0 /bin/hello /bin/hello
CMD ["/bin/hello"]
~~~

2. Construire l'image et inspecter :

~~~bash
docker build -t example/multistaged-builds .
# Inspecter taille de l'image
docker images
# Inspecter le nombre de layers
docker inspect image xample/multistaged-builds
~~~

Inspecter le contenu de chaque couche avec Docker Desktop. On remarque que seule les couches à partir du dernier `FROM` sont contenues dans l'image. La première partie de l'image n'a servi qu'a build de manière *static* le binaire go (dans une image plus lourde, avec le compilateur Go et autres dépendances).

L'image finale ne sert *que* le binaire.

## Conclusion

En conclusion, le *Multi-Staged Builds* permet de produire des images plus petites, avec le strict nécessaire, lorsque des étapes de build intermédiaires sont nécessaires.

## Références

- [Multi-stage builds](https://docs.docker.com/build/building/multi-stage/)