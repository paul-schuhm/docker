# Docker - TP Module 8 : Déploiement dans l'écosystème Docker

<hr>

Paul Schuhmacher

Avril 2026

Module: Docker

<hr>


## TP 1 : Pipeline CI/CD avec Github Actions d'une application web

1. Suivre [le guide officiel PHP language-specific guide](https://docs.docker.com/guides/php/). Notez bien l'usage du Mutli-Staged build et des séquences de la CI.
2. Enrichissez la CI en ajoutant les étapes suivantes :
   1. Scan de l'image de production avec Docker Scout ;
   2. Analyse du code source avec SonarQube
   3. Test *externe* de l'image de production. Pour cela, **instancier une base de données de test** et effectuer le test donné dans l'étape suivante :

~~~bash
 name: Exemple test image de prod - HTTP smoke test
        run: |
          for i in {1..30}; do
            curl -f http://localhost:8080/database.php | grep "message-" && exit 0
            sleep 1
            done
          exit 1
~~~

Où faut-il placer ce test dans la *pipeline* Github Actions ?

## Ressources utiles

- [Events that trigger workflows](https://docs.github.com/en/actions/reference/workflows-and-actions/events-that-trigger-workflows), documentation de Github Actions
- [Scan your code with SonarQube](https://github.com/marketplace/actions/official-sonarqube-scan), documentation officielle de Github Actions
- [Adding analysis to GitHub Actions workflow](https://docs.sonarsource.com/sonarqube-server/devops-platform-integration/github-integration/adding-analysis-to-github-actions-workflow)
- [act](https://github.com/nektos/act), test les Github Action localement avant de les executer sur Github
- [actionlint](https://github.com/rhysd/actionlint), analyseur statique des fichiers de Github Actions