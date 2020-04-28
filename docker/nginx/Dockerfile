FROM nginx:1.17.5-alpine

MAINTAINER BP

ARG DOMAIN
ARG VHOST_FILE
ARG PHP_FPM_UPSTREAM
ARG NGINX_ROOT_FOLDER="/srv/itse/system/public"

RUN adduser -D -H -u 1000 -s /bin/bash -Gwww-data www-data
COPY ./nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /etc/nginx/sites-available
RUN mkdir -p /etc/nginx/sites-enabled

COPY ${VHOST_FILE} /etc/nginx/sites-available/${VHOST_FILE}

RUN sed -i "s/domain\.tld/${DOMAIN}/g" /etc/nginx/sites-available/${VHOST_FILE}
RUN sed -i "s/fastcgi_pass.*/fastcgi_pass ${PHP_FPM_UPSTREAM};/g" /etc/nginx/sites-available/${VHOST_FILE}
RUN sed -i "/root.*/root ${NGINX_ROOT_FOLDER};/g" /etc/nginx/sites-available/${VHOST_FILE}

RUN ln -s /etc/nginx/sites-available/${VHOST_FILE} /etc/nginx/sites-enabled/${VHOST_FILE}

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

WORKDIR /etc/nginx

CMD ["nginx", "-g", "daemon off;"]