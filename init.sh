#!/bin/bash
if [ -d "web/images/currency-logo" ]; then
  echo 'Already exists'
  exit
fi
echo 'Preparing database && test database:'
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console init:fill-params-table

echo 'Get all currencies && logo: could take 5 mn ..'
php bin/console cron:create-currency-value-moment

echo 'Install assets'
php bin/console assets:install --symlink web
php bin/console assets:install --symlink web -e prod
php bin/console assetic:dump
php bin/console assetic:dump -e prod
