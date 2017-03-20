---
title: Auto deploying middleman via Travis-CI
date: 2016-07-16 01:02 UTC
author: Robbie Mackay
tags:
  - travis-ci
  - deployment
categories:
  - learned
---

I spun up a [Today I Learned site](http://til.robbiemackay.com) based on [Today Icelab Learned](http://til.icelab.com.au/). But I wanted a simple set up where any push would auto deploy, and I didn't have to run some other service to do that. So a bit of quick Travis hacking with the help of [this article](http://jeremy.jousse.fr/blog/2016-02-08-using-travis-to-deploy-middleman-on-github-pages.html) and Travis is now building the site and deploying to the `gh-pages` branch.

The main additions are were adding NodeJS (as well as Ruby) and running `bin/setup` during install

## Adding NodeJS to our ruby build

It turns out Travis already include NodeJS and nvm. So getting the version we want is super simple:

{% highlight yaml %}
before_install:
  - nvm install node
{% endhighlight %}

Here's the final `.travis.yml`:

{% highlight ruby %}
rvm:
- 2.3.0
branches:
  only:
  - master
cache: bundler
before_install:
  - nvm install node
install:
  - bin/setup
before_script:
- git config --global user.name '${GIT_NAME}'
- git config --global user.email '${GIT_EMAIL}'
- git remote set-url origin https://rjmackay:${GH_TOKEN}@github.com/rjmackay/til.git
script:
- bundle exec middleman deploy
env:
  global:
  - GIT_NAME: Your name
  - GIT_EMAIL: you@email.com
  - secure: # Insert encrypted GH_TOKEN here
{% endhighlight %}