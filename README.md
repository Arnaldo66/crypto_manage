crypto_manage
=============

A Symfony project created on August 23, 2017

TODO:
  - historique page + export
  - New detail wallet page
  - phpdoc
  - change menu
  - do best google work (microformat ...)
  - Test view (title, description)
  - do page mining, page faucet with affiliation.
  - multilanguage - french - english - spanish (Voir comment faire au mieux SEO ?)
  - Create a widget with 10 interesting crypto. This widget is parametrable by user on his account
  - do some tutorial to create account coinbase, kraken, litebit
  - do a page for darknet
  - do a page for mining if I understand some day
  - Becareful ! limit the functionality. Not to much(alert, simulation, information is enought)
  - do API working
  - do application mobile(ionic)
  - google+ && facebook
  - symfony workflow state machine to process order
    check real currency and not amout if he change with js && change total with this value
  - create separate command and launch it by here check price alerts ? or listener ? Observer ?
  - change trade entity and controller to wallet.
  - test better cron
  - Add and amelioration test performances

  INSTALLATION
  - min version php 5.5.9
  - mysql && sqlite3
  - composer
  - phpunit

  - git clone git@github.com:Arnaldo66/crypto_manage.git
  - cd crypto_manage && composer install
  - create app/config/paramameter.yml file with you information
  - create phpunit.xml file from phpunit.xml.dist
  - bash init.sh
  - php bin/console server:run
