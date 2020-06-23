FROM registry.cn-shenzhen.aliyuncs.com/yansongda/php-fpm:7.3

LABEL maintainer="yansongda <me@yansongda.cn>"

ARG build_env=prod

ENV BUILD_ENV=${build_env:-"prod"} \
    SCAN_CACHEABLE=(true)

WORKDIR /www

COPY php.ini /usr/local/etc/php/
COPY . /www

RUN echo ${BUILD_ENV} && cd /www \
    && composer install --no-dev -o \
    && composer clearcache \
    && php bin/hyperf.php

EXPOSE 9501 9502 9503

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
