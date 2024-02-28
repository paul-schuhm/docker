FROM ubuntu:latest

#Recharge la liste des paquets (apt), install les timezones et installe le serveur web Apache
RUN apt-get update \
&& apt-get install -y tzdata \
&& apt-get install -y apache2 

#Définition des variables d'environnement. 
#Info: www-data est l'utilisateur d'apache
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
#Construction des répertoires
RUN mkdir -p $APACHE_RUN_DIR $APACHE_LOCK_DIR $APACHE_LOG_DIR
#Ajout des sources 
ADD www /var/www/html
ENTRYPOINT [ "/usr/sbin/apache2" ]
CMD ["-D", "FOREGROUND"]
EXPOSE 80
