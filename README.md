# Docker

Sources (exercices, corrections, démos, etc.) du module d'enseignement Docker.

- [Docker](#docker)
  - [Modules](#modules)
    - [Module 2 : Premiers pas](#module-2--premiers-pas)
    - [Module 3 : Images](#module-3--images)
    - [Module 4 et 5 : Inspecter et intervenir sur les conteneurs](#module-4-et-5--inspecter-et-intervenir-sur-les-conteneurs)
    - [Module 6 : Compose](#module-6--compose)
  - [Module 7 : Déploiement](#module-7--déploiement)
  - [Références, aller plus loin](#références-aller-plus-loin)


[Voir la progression pédagogique](./progression.md).

## Modules

### Module 2 : Premiers pas

- [Accéder aux sujets de TP](./module-02/module-02-tp-clean.md)
- [Accéder aux corrections, commentaires sur les TPs](./module-02/tps-corrections/)

### Module 3 : Images

- [Accéder aux démos commentées](./module-03/demos/)
- [Accéder aux sujets de TP](./module-03/module-03-tp-clean.md)
- [Accéder aux corrections, commentaires sur les TPs](./module-03/correction-tp/)

### Module 4 et 5 : Inspecter et intervenir sur les conteneurs

- [Accéder aux sujets de TP](./module-05/module-05-tp-clean.md)
- [Accéder aux corrections, commentaires sur les TPs](./module-05/correction-tp/)

### Module 6 : Compose

- [Accéder à une démo sur la gestion de plusieurs environnements](./module-06/demo-env-dev-env-prod/)
- [Accéder à une démo sur les différentes options de compose (profiles, merge, etc.)](./module-06/demo-services-options/);
- [Accéder à une démo de conteneurization d'une app Node.js, basée sur le guide officiel](./module-06/demo-dev-node/)
- [Accéder à une démo pour travailler avec plusieurs fichiers compose (live)](./module-06/demo-live/)

## Module 7 : Déploiement

- [Accéder au dépôt sur un exemple de stratégie CI/CD, déploiement et discussions sur différentes méthodes possibles](https://github.com/paul-schuhm/docker-workflow-cicd)

## Références, aller plus loin

<img src="./assets/docker-up-and-running-2nd.jpeg" height=200><img src="./assets/os-three-easy-pieces.jpg" height=200>


- [Open Container Initiative (OCI)](https://opencontainers.org/), gouvernance pour maintenir et garantir des standards ouverts sur les formats de conteneurs et de leurs environnement d'exécution. En font partie Docker Inc., Red Hat, Google, etc.
- [Docker: Up & Running: Shipping Reliable Containers in Production, 3rd edition](https://www.amazon.fr/Docker-Shipping-Reliable-Containers-Production/dp/1098131827/ref=pd_sbs_d_sccl_3_2/261-8003303-3459731), de Sean P Kane (Auteur), Karl Matthias (Auteur), publié chez O'Reilly, 2023. La deuxième édition (bien que quelques exemples/dépôts cassés et quelques éléments datés) est très bien également. La progression de ce cours est en grande partie basée sur la progression de cet ouvrage.
- [Docker Deep Dive: Zero to Docker in a single book (Mastering Containers 1) (English Edition)](), auto édité, de Nigel Poulton, 2016. Je n'ai pas (encore) parcouru ou utilisé ce livre. Reviews encourageantes. À voir...
- [Operating Systems, three easy pieces](https://pages.cs.wisc.edu/~remzi/OSTEP/), ou le *Comet OS Book*, de Remzi H. Arpaci-Dusseau and Andrea C. Arpaci-Dusseau (University of Wisconsin-Madison), publié par l'université du Wisconsin, 2008, continuellement mis à jour. Accessible en ligne. *Une référence* sur les systèmes d'exploitation. Voir le chapitre sur la virtualisation.
- [Un serveur HTTP de moins de 20 Ko](https://lafor.ge/http-smol/), article sur la création d'une image d'un serveur web de la plus petite taille possible
- [Positive Affirmations for Site Reliability Engineers](https://www.youtube.com/watch?v=ia8Q51ouA_s), de Krazam
- [Best Practices Around Production Ready Web Apps with Docker Compose](https://nickjanetakis.com/blog/best-practices-around-production-ready-web-apps-with-docker-compose), de [Nick Janetakis](https://nickjanetakis.com/about). Publié en 2021, des choses ont changé sur docker compose depuis mais reste pertinent sur de nombreux points 
- [12 Fractured Apps](https://medium.com/@kelseyhightower/12-fractured-apps-1080c73d481c#.smga9216i), de [Kesley Highttower](https://en.wikipedia.org/wiki/Kelsey_Hightower). Un classique. Manifeste sur l'utilisation des conteneurs. "*Ship artifacts, not build environments*"