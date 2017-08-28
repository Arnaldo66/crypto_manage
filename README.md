crypto_manage
=============

A Symfony project created on August 23, 2017

What you need:
  - phpunit
  - mysql
  - sqlite3

A list of what I need in a first time:
  - Create specific database for insert value of different crypto currency
  - Create cron for get api information from the site: https://coinmarketcap.com/api/
  - Create a crawler for get a largest history a any crypto
  - In a basic front page show the 50 best currency at the moment

In a second time create a back office for manage information about any currency:
  - link to buy, history ...

After that:
  - Create a complete user system
  - Create a configurable alert system (mail,sms) for inform user when a specific currency up/down

  Idea:
  - best up down avg in hours, days, week, year, all
  - best down " " " "
  - for cache (redis, memcached ..) puts moment value in cache for front. Reload it by cron
