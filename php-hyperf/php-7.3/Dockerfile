FROM registry.cn-shenzhen.aliyuncs.com/yansongda/php-fpm:7.3

LABEL maintainer="yansongda <me@yansongda.cn>"

WORKDIR /www

COPY php.ini /usr/local/etc/php/

RUN rm -rf /usr/local/etc/php/conf.d/docker-php-ext-grpc.ini
