---
layout: post
title: "Automating Behat and Mink tests with Travis CI"
tagline: "Better testing for Ushahidi 3.0"
tags: [ushahidi, testing]
---
{% include JB/setup %}

I was excited to see Github now shows [build status branches](https://github.com/blog/1484-check-the-status-of-your-branches). Even more so when I [submitted code](https://github.com/bshaffer/oauth2-server-php/pull/112) to an upstream library and was surprised by the tests results appearing in my pull request.

So I jumped in to get it running for Ushahidi 3.0

We've been running some tests through Jenkins already, but I've largely been doing manual runs locally. [Travis-CI](https://travis-ci.org) integrates with Github automatically, so I figured that was the easiest path to integration.

First impression: **its really really easy to get started**, and the docs were great.

## Getting started

* Log in with Github, turn on builds for the repo
* Add .travis.yml file, configure the language, and PHP version to use
* Push to github
* Wait for builds to run

### Installing Behat, Mink and Guzzle.

We use [composer](http://getcomposer.org) to install behat, mink and guzzle. I had just been doing this locally anyway so that turned out to be easy. Just add:

{% highlight yaml %}
before_script:
  - composer install
{% endhighlight %}

### Running PHPUnit and Behat

By default Travis-CI would just run PHPUnit. We use both PHPUnit and Behat so we have to run both. That's as simple 2 extra lines in the config

{% highlight yaml %}
script:
  - ./bin/behat --config application/tests/behat.yml
  - phpunit -c application/tests/phpunit.xml
{% endhighlight %}

## Further config

The next steps get little trickier. 

Bootstrapping the DB wasn't too bad. Add mysql to 'services' and add a couples of 'before_script' steps:

{% highlight yaml %}
services:
  - mysql
before_script:
  # db setup
  - mysql -e 'create database lamu_test;'
  - mv application/config/database.travis application/config/database.php
  - ./minion --task=migrations:run --up
{% endhighlight %}

### API tests

We're running API tests by hitting a web server with [Guzzle](http://guzzlephp.org). This means I need a web server running in my Travis-CI build. Theres a few docs on doing this for other projects but not so much PHP, and the [examples](https://gist.github.com/roderik/3123962) I found were ugly at best.

In the end I'm running the built in PHP dev server, and restricting builds to PHP 5.4.

### Final script

A few other bits of bootstrapping and I had working builds:

{% highlight yaml %}
language: php
php:
  - "5.4"
services:
  - mysql
before_script:
  # install behat, mink etc
  - composer install --no-interaction --prefer-source # Have to prefer source or hit github rate limit
  - git submodule update --init --recursive           # not really needed but makes sure submodules are there
  - mkdir application/cache application/logs          # create cache and log dirs
  - chmod 777 application/cache application/logs      # set permission
  # db setup
  - mysql -e 'create database lamu_test;'
  - mv application/config/database.travis application/config/database.php
  - mv application/config/auth.travis application/config/auth.php
  - ./minion --task=migrations:run --up
  # webserver setup
  - php -S localhost:8000 httpdocs/index.php &
  - sleep 3
script:
  - ./bin/behat --config application/tests/behat.yml  # run behat
  - phpunit -c application/tests/phpunit.xml          # run php unit
{% endhighlight %}

## Not quite done..

I quickly realised that my code actually had some issues:

### PHP 5.4

I've been building on PHP 5.3, so immediatey hit a bunch of 'Array to string conversion' bugs in PHP 5.4. The main culprit for this was a bug in Kohana 3.3, so I've ported a rough fix from the develop branch.

### Case sensitivity

I develop on OS X using a Vagrant box that mounts project code over NFS. The side effect of this is that my filesystem *isn't really case sensitive*. Kohana uses PSR-0 autoloading, so the filename and class names need to match.
The Travis builds run on a standard Ubuntu box: I suddenly hit errors about missing classes, etc.

Fixing this was a bit slow and painful but actually a win in the long run. The first linux user to fire up the code base would have hit these issues immediately!

