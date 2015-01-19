FROM dockerfile/ubuntu

RUN apt-get update &&\
	apt-get install -y apache2 php5 libapache2-mod-php5 php5-curl curl

ADD . /var/www/html/tresor-pap

WORKDIR /var/www/html/tresor-pap

RUN cp /var/www/html/tresor-pap/apache-conf/tresor-pap.conf /etc/apache2/sites-available &&\
	curl -sS https://getcomposer.org/installer | php &&\
	php composer.phar install &&\
	chown -R www-data:www-data /var/www/html/tresor-pap &&\
	a2dissite 000-default &&\
	a2ensite tresor-pap

EXPOSE 80

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]