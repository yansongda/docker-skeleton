FROM registry.cn-shenzhen.aliyuncs.com/yansongda/php-fpm:7.3

LABEL maintainer="yansongda <me@yansongda.cn>"

ARG app_env=prod

ENV APP_ENV=${app_env:-"prod"}

WORKDIR /www

COPY php.ini /usr/local/etc/php/
COPY . /www
COPY .env.${APP_ENV} /www/.env

RUN  cd /www \
    && composer install \
    && composer clearcache \
    && composer dump-autoload -o \
    && composer init-proxy

EXPOSE 9501

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
