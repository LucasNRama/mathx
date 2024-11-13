FROM php:8.2-fpm

ENV USER=www
ENV GROUP=www
ENV TZ=America/Fortaleza

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    mbstring \
    exif\
    pcntl\
    bcmath\
    gd \
    pdo\
    pdo_pgsql

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Setup working directory
WORKDIR /var/www/html

# ID USER AND GROUP
ARG UID
ARG GID

# Create User and Group
RUN groupadd -g ${GID} ${GROUP} && useradd -u ${UID} -ms /bin/bash -g ${GROUP} ${USER}

# Grant Permissions
RUN chown -R ${USER} /var/www/html

# Select User
# USER ${USER}

# Copy permission to selected user
# COPY --chown=${USER}:${GROUP} . .

EXPOSE 9000

CMD ["php-fpm"]
