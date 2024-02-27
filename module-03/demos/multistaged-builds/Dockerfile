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