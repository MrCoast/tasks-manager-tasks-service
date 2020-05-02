FROM php:7.4-fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add git bash
RUN docker-php-ext-install pdo_mysql
RUN ln -sf /bin/bash /bin/sh
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
RUN git clone git://github.com/djoos/Symfony2-coding-standard.git /tmp/Symfony2-coding-standard
CMD composer install ; ./vendor/bin/phpcs --config-set installed_paths /tmp/Symfony2-coding-standard ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm 
EXPOSE 9000
