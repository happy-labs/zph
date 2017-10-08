FROM ubuntu:latest
MAINTAINER erangaeb bandara<erangaeb@gmail.com>

# Install apache, PHP, and supplimentary programs. openssh-server, curl, and lynx-cur are for debugging the container.
RUN apt-get update && apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive apt-get -y install \
    apache2 php7.0 php7.0-mysql libapache2-mod-php7.0 curl lynx-cur php7.0-xml php-curl

# Enable apache mods.
RUN a2enmod php7.0
RUN a2enmod rewrite

# Update the PHP.ini file, enable <? ?> tags and quieten logging.
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.0/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php/7.0/apache2/php.ini

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Node Env Variabes
ENV NODE_HOST msc.scorelab.org
ENV NODE_PORT 3000
ENV NODE_PROTOCOL http

# Payment Env Variables
ENV PGW_HOST msc.scorelab.org
ENV PGW_PORT 4000
ENV PGW_PROTOCOL http

ENV PGW_END_POINT https://sampath.paycorp.com.au/rest/service/proxy/
ENV PGW_AUTH_TOKEN ef8aff82-bae4-4706-b3c3-87f72de2e2b9
ENV PGW_HMAC_SECRET 77MHMnVPQEyDGspe

# to give sampath to test the payment we need test, prod mode
# test mode will redirect to test php pages
ENV PGW_MODE test

# Expose apache.
EXPOSE 80

# Copy this repo into place.
ADD www /var/www/site

# Update the default apache site with the config we created.
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND
