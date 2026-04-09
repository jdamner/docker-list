FROM composer:2 AS deps
WORKDIR /app
COPY composer.json ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

FROM php:8.4-cli-alpine
WORKDIR /app
COPY --from=deps /app/vendor ./vendor
COPY . .
RUN adduser -D -H appuser
USER appuser
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "index.php"]
