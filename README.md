crypto_manage
=============

A Symfony project created on August 23, 2017

What you need:
  - phpunit
  - mysql
  - sqlite3
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
