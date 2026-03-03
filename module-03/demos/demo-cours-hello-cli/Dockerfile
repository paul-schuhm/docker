# Image de base officielle légère sert de base
FROM node:24-alpine

# Répertoire de travail dans l'image
WORKDIR /usr/src/app

# Copier uniquement les fichiers nécessaires dans le syst de fichiers de l'image
COPY package.json ./

# Installer les dépendances (production uniquement)
RUN npm i --omit=dev

# Copier le code source
COPY app.js .

# Rendre le script exécutable
RUN chmod +x app.js

# Définir le point d’entrée
ENTRYPOINT ["node", "app.js"]