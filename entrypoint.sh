#!/bin/bash
set -e

# garante que a pasta exista e tenha permissão
mkdir -p /var/www/html/uploads
chmod -R 777 /var/www/html/uploads

# inicia o apache
exec apache2-foreground
