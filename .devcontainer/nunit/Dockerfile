FROM unit:1.32.1-php8.3

ARG USERID=1000
ARG GROUPID=1000

RUN groupadd -g $GROUPID shop-user \
    && useradd -u $USERID -g $GROUPID shop-user \
    && mkdir /home/shop-user \
    && chown shop-user:shop-user /home/shop-user

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libpq-dev \
        git \
        curl \
        unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_pgsql

COPY config.json docker-entrypoint.d/config.json

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

RUN chown -R shop-user:shop-user /app