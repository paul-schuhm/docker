# Guide : *What is a container ?*

1. Dans Docker Desktop, inspecter les *Layers* (cliquer sur le lien de l'image) de l'image. Voit la Layer 15 : `COPY /app/build /usr/share/nginx/html`. Aller dans `Files` et suivre le chemin. On tombe sur les sources du site web. `"You ran your first container"` est dans une des sources JS (injectée via la manip du DOM), comme l'effet de particules, `index.html` le document HTML. Le tout est servi par un serveur web nginx.

## Références

- [What is a container ?](https://docs.docker.com/guides/walkthroughs/what-is-a-container//), guide de la documentation officielle
