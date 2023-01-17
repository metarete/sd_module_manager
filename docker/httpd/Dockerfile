ARG HTTP_APACHE_VERSION

#
# Immagine di riferimento
#
FROM bitnami/apache:$HTTP_APACHE_VERSION

#
# Variabili dal docker-compose
#
ARG HOST_USER
ARG HOST_GROUP
ARG HOST_USERNAME

USER 0

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
    sudo \
    tzdata && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

#
# UID e GID di lavoro del server apache (v. httpd.conf)
#
RUN groupadd -g $HOST_GROUP $HOST_USERNAME && \
    useradd -u $HOST_USER -g $HOST_GROUP $HOST_USERNAME
    
RUN mkdir /opt/bitnami/apache/tmp

ENV TZ=Europe/Rome
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone