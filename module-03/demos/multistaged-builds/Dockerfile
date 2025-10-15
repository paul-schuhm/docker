#Quel est le problème ici ?!
FROM golang:1.23

RUN apt-get update && \
    apt-get install -y --no-install-recommends git && \
    rm -rf /var/lib/apt/lists/*

RUN CGO_ENABLED=0 go install -ldflags="-s -w" github.com/adriaandejonge/helloworld@latest

EXPOSE 8080
CMD ["/go/bin/helloworld"]
