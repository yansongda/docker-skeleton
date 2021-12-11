FROM registry.cn-shenzhen.aliyuncs.com/yansongda/php-fpm:8.0-alpine

LABEL maintainer="yansongda <me@yansongda.cn>"

WORKDIR /www

COPY php.ini /usr/local/etc/php/

RUN rm -rf /usr/local/etc/php/conf.d/docker-php-ext-grpc.ini

EXPOSE 8080 8888 8889

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
