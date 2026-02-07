#!/bin/bash
# Script para monitorar assets do Vue sem os avisos de depreciação

docker compose exec -it node node node_modules/webpack/bin/webpack.js \
    --config=node_modules/laravel-mix/setup/webpack.config.js \
    --watch \
    --no-stats
