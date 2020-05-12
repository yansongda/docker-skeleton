FROM registry.cn-shenzhen.aliyuncs.com/yansongda/php-fpm:7.2

LABEL maintainer="yansongda <me@yansongda.cn>"

ARG build_env=prod

ENV BUILD_ENV=${build_env:-"prod"}

WORKDIR /www

COPY php.ini /usr/local/etc/php/
COPY . /www
COPY .env.${BUILD_ENV} /www/.env

RUN echo ${BUILD_ENV} && cd /www \
    && composer install --no-dev -o

EXPOSE 9501 9502 9503

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
