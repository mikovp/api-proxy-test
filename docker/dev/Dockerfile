FROM php:8-fpm-alpine

RUN apk add --no-cache mysql-client msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu sqlite sqlite-dev

RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install intl && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    docker-php-ext-install zip && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo_sqlite && \ 
    apk del build-essentials && rm -rf /usr/src/php*

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

# Identité du service
ENV WEB_USERNAME www-data
ENV WEB_GROUPNAME www-data

# Ensure Username and Groupname are present
# www-data on Alpine (uid=82, gid=82) --> switch to 33 as in Debian
#          remove (xfs:33) user before.
RUN set -eux \
 && sed -i -e "/:33:/d" -e "s/:82/:${WEB_GID:=33}/g" /etc/group \
 && sed -i -e "/:33:/d" -e "s/:82/:${WEB_UID:=33}/g" /etc/passwd \
 && install -d -o ${WEB_USERNAME} -g ${WEB_GROUPNAME} -m 775 /var/www/

 COPY start.sh /usr/local/bin/start
 RUN chmod u+x /usr/local/bin/start
 
 # USER $user
 CMD ["/usr/local/bin/start"]