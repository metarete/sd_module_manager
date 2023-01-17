ARG PHP_VERSION

#
# Immagine di riferimento
#
FROM php:$PHP_VERSION

# Use the default production configuration
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# easy php extension installation
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

#
# Variabili dal docker-compose
#
ARG HOST_USER
ARG HOST_GROUP
ARG HOST_USERNAME

USER 0

#CMD mkdir /app

#
# Installa pacchetti utili
#
RUN apt-get update && \
    apt-get install -y \
    ne \
    wget \
    tar \
    xz-utils \
    unzip \
    git \
    sudo \
    graphviz && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* 

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions apcu bcmath bz2 calendar exif gd gettext gmp imap intl ldap mcrypt mysqli opcache openssl pcntl pdo_mysql soap sockets tidy xmlrpc xsl zip
    
#
# Aggiunta completa dell'utente non-root
#
RUN groupadd -f --gid $HOST_GROUP $HOST_USERNAME && \
    useradd --create-home --home-dir /home/$HOST_USERNAME --shell /bin/bash --uid $HOST_USER --gid $HOST_GROUP --password $(echo "$HOST_USERNAME" | openssl passwd -1 -stdin) $HOST_USERNAME && \
    usermod -aG sudo $HOST_USERNAME && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* 

#
# Sistema scrittura in directory per utente non-root
#
#RUN chown -R $HOST_USER:$HOST_GROUP /opt/bitnami/php/logs && chown -R $HOST_USER:$HOST_GROUP /opt/bitnami/php/tmp

#
# Get composer binary
#
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ENV TZ=Europe/Rome
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


RUN apt-get update && sudo apt-get install -y gnupg && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && sudo apt-get install -y yarn && \
    curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo bash - && \
    apt-get install -y nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

#
# Change to working directory
#
WORKDIR /app

#
# Utente e gruppo di lavoro dell'immagine
#
USER $HOST_USER:$HOST_GROUP
