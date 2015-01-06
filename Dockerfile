FROM dockerfile/ubuntu

RUN sudo apt-get update &&\
		sudo apt-get install -y apache2 &&\
		sudo apt-get install -y php5 &&\
		sudo apt-get install -y libapache2-mod-php5 &&\
		sudo apt-get install -y php5-curl &&\
		sudo apt-get install -y curl

ADD . /opt/tresor-pap
WORKDIR /opt/tresor-pap

RUN sudo cp /opt/tresor-pap/apache-conf/tresor-pap.conf /etc/apache2/sites-available/tresor-pap.conf &&\
		sudo cp /opt/tresor-pap/apache-conf/apache2.conf /etc/apache2/apache2.conf &&\
		sudo cp -r /opt/tresor-pap/ /var/www/html/ &&\
		sudo chown -R www-data:www-data /var/www/html/tresor-pap/ &&\
		cd /var/www/html/tresor-pap/ &&\
		sudo chmod -R 777 /var/www/html/tresor-pap/ &&\
		sudo curl -sS https://getcomposer.org/installer | php &&\
		sudo php composer.phar install &&\
		sudo chown -R www-data:www-data /var/www/html/tresor-pap/ &&\
		sudo chmod -R 755 /var/www/html/tresor-pap/
		sudo a2ensite tresor-pap.conf &&\
		sudo service apache2 restart 

WORKDIR /var/www/html/tresor-pap

EXPOSE 80

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]