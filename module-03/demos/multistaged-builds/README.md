# Démo Multi-Stage Builds

- [Démo Multi-Stage Builds](#démo-multi-stage-builds)
	- [Approche naïve](#approche-naïve)
	- [Utiliser le Multi-Stage Build](#utiliser-le-multi-stage-build)
	- [Conclusion](#conclusion)
	- [Références](#références)


Une démo qui illustre le [Multi-Stage builds](https://docs.docker.com/build/building/multi-stage/) et son utilité.

On veut déployer une application web écrite en Go.

## Approche naïve

1. **Créer** le `Dockerfile-multi` suivant :

~~~dockerfile
FROM golang:1.23

RUN apt-get update && \
    apt-get install -y --no-install-recommends git && \
    rm -rf /var/lib/apt/lists/*

RUN CGO_ENABLED=0 go install -ldflags="-s -w" github.com/adriaandejonge/helloworld@latest

EXPOSE 8080
CMD ["/go/bin/helloworld"]
~~~


2. Build

~~~bash
docker build -f Dockerfile-multi -t example/naive .
~~~

Quelle taille fait l'image `example/naive` ? Presque 1 Go ! Pourquoi ?


## Utiliser le Multi-Stage Build

1. Créer le `Dockerfile-multi` suivant (**même programme**) :

~~~dockerfile
# Même service (même programme) que précédemment (code Go est fourni directement ici)
# ---- Build stage ----
FROM golang:1.23 AS builder
WORKDIR /app
# Code source embarqué
COPY <<'EOF' main.go
package main
import (
	"fmt"
	"net/http"
)
func helloHandler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintln(w, "Hello World from Go in minimal Docker container")
}
func main() {
	http.HandleFunc("/", helloHandler)
	fmt.Println("Started, serving at 8080")
	err := http.ListenAndServe(":8080", nil)
	if err != nil {
		panic("ListenAndServe: " + err.Error())
	}
}
EOF
# Compilation statique
RUN CGO_ENABLED=0 go build -trimpath -ldflags="-s -w" -o /hello ./main.go
# ---- Runtime stage ----
FROM scratch
COPY --from=builder /hello /hello
ENTRYPOINT ["/hello"]
~~~

2. Construire l'image et inspecter :

~~~bash
docker build -f Dockerfile-multi -t example/multistaged-builds .
# Inspecter taille de l'image
docker images
# Inspecter le nombre de layers
docker inspect image xample/multistaged-builds
~~~

L'image ne fait que quelques Mo, normal il n'y a que l'executable !

Inspecter le contenu de chaque couche avec Docker Desktop ou `docker history example/multistaged-builds`. On remarque que seules les couches à partir du dernier `FROM` sont contenues dans l'image finale. La première partie de l'image n'a servi qu'a build de manière *statique* le binaire Go (dans une image plus lourde, avec le compilateur Go et autres dépendances).

L'image finale ne contient et ne sert *que* le binaire.

## Conclusion

En conclusion, le *Multi-Staged Builds* **peut permettre de produire des images (drastiquement) plus petites**, avec le strict nécessaire, lorsque des étapes de build intermédiaires sont nécessaires.

## Références

- [Multi-stage builds](https://docs.docker.com/build/building/multi-stage/)
