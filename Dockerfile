# You should not be editing this file.

# To override this value, set "php" in libops.yml
ARG PHP_VERSION=8.2

FROM us-docker.pkg.dev/libops-images/shared/php-${PHP_VERSION}:main

COPY --chown=3000:1080 . /code/

RUN cd /code && \
    composer install && \
    rm -rf /code/.composer
