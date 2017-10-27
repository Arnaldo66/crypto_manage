#!/bin/bash
php bin/console assetic:dump -e prod;
php bin/console cache:clear --no-warmup -e prod;
php bin/console doctrine:cache:clear-metadata -e prod;
