#!/bin/bash

# Limpa e recarrega o cache de configuração do Laravel
php artisan config:cache

# Executa as migrations forçadas
php artisan migrate --force
