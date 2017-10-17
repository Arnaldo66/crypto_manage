crypto_manage
=============

A Symfony project created on August 23, 2017

BUG
  - test if price is nul (btc && euro && dollars) .. dont take
  - if not null transform value from btc or euro or dollars
  - Responsive mobile not good.
TODO:
  - About page
  - First step page
  - alert functionnality
  - back office admin (users, currency, contact send)
  - Best page currency with graph
  - Add and amelioration test performances
  - cron for order method "limit"
  - Test view (title, description)
  - multilanguage - french - english - spanish (Voir comment faire au mieux SEO ?)
  - Create a widget with 10 interesting crypto. This widget is parametrable by user on his account
  - vérifier qu'on est bien le titulaire du wallet pour y accéder
  Idea:
  - for cache (redis, memcached ..) puts moment value in cache for front. Reload it by cron


  INSTALLATION
  - min version php 5.5.9
  - mysql && sqlite3
  - composer
  - phpunit

  - git clone git@github.com:Arnaldo66/crypto_manage.git
  - cd crypto_manage && composer install
  - create app/config/paramameter.yml file with you information
  - create phpunit.xml file from phpunit.xml.dist
  - php bin/console doctrine:database:create
  - php bin/console doctrine:database:create - test
  - php bin/console doctrine:schema:update --force
  - php bin/console doctrine:schema:update --force -e test
  - php bin/console cron:create-currency-value-moment
  - php bin/console assets:install --symlink web
  - php bin/console assets:install --symlink web -e prod
  - php bin/console assetic:dump
  - php bin/console assetic:dump -e prod
  - php bin/console server:run
