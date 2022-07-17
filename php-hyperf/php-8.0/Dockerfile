FROM yansongda/php:8.0-cli-alpine

LABEL maintainer="yansongda <me@yansongda.cn>"

WORKDIR /www

RUN set -ex \
    && echo "swoole.use_shortname = 'Off'" >> /etc/php8/conf.d/50_swoole.ini \
    && echo "memory_limit = 1024M" >> /etc/php8/conf.d/00_default.ini \
    && echo "post_max_size = 100M" >> /etc/php8/conf.d/00_default.ini \
    && echo "upload_max_filesize = 100M" >> /etc/php8/conf.d/00_default.ini \
    && echo "date.timezone = Asia/Shanghai" >> /etc/php8/conf.d/00_default.ini

EXPOSE 8080 8888 8889

ENTRYPOINT ["php", "/www/bin/hyperf.php", "start"]
