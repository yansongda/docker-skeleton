FROM yansongda/php:cli-8.5-alpine

LABEL maintainer="yansongda <me@yansongda.cn>"

WORKDIR /www

RUN set -ex \
    && echo "swoole.use_shortname = 'Off'" >> /usr/local/etc/php/php.ini \
    && sed -i 's/memory_limit = 512M/memory_limit = 1024M/g' /usr/local/etc/php/php.ini

EXPOSE 8080 8888 8889

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
