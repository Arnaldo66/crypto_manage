crypto_manage
=============

A Symfony project created on August 23, 2017

BUG
  - test if price is nul (btc && euro && dollars) .. dont take
  - if not null transform value from btc or euro or dollars
  - Responsive mobile not good.
TODO:
  - Show in more value and possiblity in currency graph
  - Add and amelioration test performances
  - Activate cron for order method "limit"
  - Test view (title, description)
  - multilanguage - french - english - spanish (Voir comment faire au mieux SEO ?)
  - Create a widget with 10 interesting crypto. This widget is parametrable by user on his account
  - remove page 'premiers pas'. Application has to be simple to use and not  need eplication
  - do some tutorial to create account coinbase, kraken, litebit
  - do a page for darknet
  - do a page for mining if I understand some day
  - Becareful ! limit the functionality. Not to much(alert, simulation, information is enought)
  - do best design
  - do best google work (microformat ...)
  - do API working
  - do application mobile(ionic)
  - google+ && facebook
  - symfony workflow state machine to process order
    check real currency and not amout if he change with js && change total with this value
    pass arg and not all the container service
  - create separate command and launch it by here check price alerts ? or listener ? Observer ?
  - history why stop on prod and remove limit after fix
  - change trade entity and controller to wallet.


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
