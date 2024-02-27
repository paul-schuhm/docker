# Démo : Différence entre CMD et ENTRYPOINT

- [Démo : Différence entre CMD et ENTRYPOINT](#démo--différence-entre-cmd-et-entrypoint)
  - [CMD](#cmd)
  - [ENTRYPOINT](#entrypoint)
  - [Combinaison de ENTRYPOINT et CMD](#combinaison-de-entrypoint-et-cmd)
  - [En conclusion](#en-conclusion)
  - [Références](#références)


[`CMD`](https://docs.docker.com/reference/dockerfile/#cmd) et [`ENTRYPOINT`](https://docs.docker.com/reference/dockerfile/#entrypoint) sont deux instructions similaires mais avec des différences notables. Le but est de pouvoir les *combiner* pour avoir le plus de flexibilité possible. Cela peut porter à confusion.

Garder en tête que :

- `CMD` est là pour donner un comportement ou des arguments *par défaut* au conteneur (il **peut** définir un executable)
- `ENTRYPOINT` est là pour spécifier le comportement (il **doit** définir un executable) 

## CMD

Cette instruction spécifie la commande par défaut à exécuter lorsque le conteneur est lancé. Elle peut être écrasée par une commande spécifiée lors du lancement du conteneur (sur la CLI). Si une instruction CDM est utilisée sous une forme de liste (avec des crochets), elle peut être remplacée par la commande spécifiée au moment de l'execution.

~~~bash
docker build -t demo-cmd -f Dockerfile .
docker run demo-cmd
#Override l'instruction CMD
docker run demo-cmd echo "hello, world!"
docker run demo-cmd ls
~~~

L'instruction `CMD` a trois formes possibles :

- `CMD ["executable","param1","param2"]` (*exec form*)
- `CMD ["param1","param2"]` (*exec form*, as default parameters pour ENTRYPOINT)
- `CMD command param1 param2` (*shell form*)

>**There can only be one CMD instruction in a Dockerfile**. If you list more than one CMD, **only the last** one takes effect.

>The purpose of a `CMD` is to *provide defaults* for an executing container. These defaults *can* include an executable, or they can omit the executable, in which case you **must** specify an `ENTRYPOINT` instruction as well.

Donc `CMD` peut être utilisé pour définir un executable et arguments, ou seulement des arguments. Dans ce cas, il **faut** un `ENTRYPOINT`.

Si on consulte la documentation

~~~bash
man docker run
~~~

on peut y lire

> `[COMMAND]` (de `docker run IMAGE [COMMAND]`) allows you to overwrite the default entrypoint of the image that is set in the Dockerfile. The ENTRYPOINT of an image is similar to a COMMAND because it specifies what executable to run when the container starts, but it is  (purposely)  more  difficult  to override. The ENTRYPOINT gives a container its default nature or behavior, so that when you set an ENTRYPOINT you can run the container as if it were that binary, complete with default options, and you can pass in more options via the COMMAND. But, sometimes an  operator may  want to run something else inside the container, so you can override the default ENTRYPOINT at runtime by using a --entrypoint and a string to specify the new ENTRYPOINT.

## ENTRYPOINT

Comme `CMD`, `ENTRYPOINT` permet de définir quel executable lancer quand le conteneur démarre. Il peut prendre deux formes :

- *exec form* : `ENTRYPOINT ["executable","param1","param2"]`
- *shell form* : `ENTRYPOINT command param1 param2`

Contrairement à `CMD`, `ENTRYPOINT` **doit toujours définir un exécutable** (alors que `CMD` peut seulement définir des arguments).

Les arguments passés en ligne de commande a `docker run <image>` seront **ajoutés à la fin** (append) de la liste des arguments de l'*exec form* d'`ENTRYPOINT`.

~~~dockerfile
ENTRYPOINT ["echo","foo"]
~~~

et `docker run <image> bar`, le conteneur affichera "foo bar".

Démo :

~~~bash
docker build -t demo-entrypoint -f Dockerfile-entrypoint .
#Utiliser les arguments par défaut
docker run demo-entrypoint
#Utilise les arguments fournis via la CLI : append
docker run demo-entrypoint bar
~~~

## Combinaison de ENTRYPOINT et CMD

On peut combiner les deux. Dans ce cas `ENTRYPOINT` définit l'executable, et `CMD` fournit des arguments.

~~~dockerfile
FROM alpine:3.19
ENTRYPOINT ["echo", "default arg ENTRYPOINT"]
CMD ["arg1 CMD", "arg2 CMD"]
~~~

Si on lance ce conteneur sans arguments, il utilisera les arguments fournis à `ENTRYPOINT` et ceux fournis par `CMD` :

~~~bash
docker build -t demo-cmd-and-entrypoint -f Dockerfile-entrypoint-cmd .
docker run demo-cmd-and-entrypoint 
~~~

affiche `default arg ENTRYPOINT arg1 CMD arg2 CMD`.

Si des arguments sont fournis, ils *override* ceux de `CMD` mais pas ceux d'`ENTRYPOINT`, ils sont *append* :

~~~bash
docker run demo-cmd-and-entrypoint foo bar
~~~

affiche `default arg foo bar CMD`.


> On peut également *override* l'**executable** d'`ENTRYPOINT` avec l'option `--entry-point` si nécessaire.


## En conclusion

Dans un `Dockerfile`, il faut **soit** un seul `CMD` *exec form* **soit** un `ENTRYPOINT`. On peut passer des arguments en plus en ligne de commande `docker run IMAGE [COMMAND]` :

- Si c'est `CMD` qui lance l'executable, on peut override **toute l'instruction** `CMD` (executable et arguments);
- Si c'est `ENTRYPOINT` qui lance l'executable (sans `CMD`), on peut *append* des arguments;
- Si c'est `ENTRYPOINT` qui lance l'executable et `CMD` qui définit les arguments par défaut, les arguments de `CMD` seront *append* ceux d'`ENTRYPOINT`. Si on fournit des arguments, les arguments de `CMD` seront *override* mais pas ceux d'`ENTRYPOINT`

## Références

- [CMD](https://docs.docker.com/reference/dockerfile/#cmd)
- [ENTRYPOINT](https://docs.docker.com/reference/dockerfile/#entrypoint)
- [Shell and exec form](https://docs.docker.com/reference/dockerfile/#shell-and-exec-form), les deux syntaxes pour les instructions RUN, CMD et ENTRYPOINT