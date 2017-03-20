---
title: Handling login events with Sentry and AngularJS
date: 2017-03-20
author: Robbie Mackay
tags:
  - sentry
  - angularjs
categories:
  - learned
---

I added [Sentry](sentry.io) event tracking to [RollCall](rollcall.io) this morning. As part of
that I added some simple handling of login and logout events to set the current
user in the Sentry meta data.

First we simple add Raven and - if we have a DSN set - configure it:

{% highlight javascript %}
import Raven from 'raven-js';

// Load raven if configured
if (RAVEN_DSN) {
  Raven
    .config(RAVEN_DSN, {
      release: GIT_COMMIT,
      environment: ENVIRONMENT
    })
    .addPlugin(require('raven-js/plugins/angular'), angular)
    .install();
}
{% endhighlight %}

There's some minor sugar in there to add both the environment and current git commit.
Both of these are set during the build process.

To wire up log in/out events I added this little service

{% highlight javascript %}
class RavenService {
  constructor(user, $rootScope, Raven) {
    "ngInject";
    this.user = user;
    this.$rootScope = $rootScope;
    this.Raven = Raven;
  }

  init() {
    this.$rootScope.$on('auth.login', this.handleLogin.bind(this));
    this.$rootScope.$on('auth.logout', this.handleLogout.bind(this));

    if (this.user && this.user.isLoggedIn) {
      this.handleLogin();
    }
  }

  handleLogin() {
    this.Raven.setUserContext({
      username: this.user.name,
      email: this.user.email,
      id: this.user.id
    });
  }

  handleLogout() {
    this.Raven.setUserContext({});
  }

}

export default RavenService;
{% endhighlight %}

And tied it all together in a simple module:

{% highlight javascript %}
import Raven from 'raven-js';

// Load raven if configured
if (RAVEN_DSN) {
  Raven
    .config(RAVEN_DSN, {
      release: GIT_COMMIT,
      environment: ENVIRONMENT
    })
    .addPlugin(require('raven-js/plugins/angular'), angular)
    .install();
}

let ravenModule = angular.module('app.raven', [
  'ngRaven',
  userModule
])

.factory('Raven', () => {
  return Raven;
})

.service({
  ravenService
})

.run(['ravenService', (ravenService) => {
  if (RAVEN_DSN) {
    ravenService.init();
  }
}])

.name;

export default ravenModule;
{% endhighlight %}

A few things to note:

- I'm calling `ravenService.init()` in a run() block because that way we know that
  `user` should be ready and we can check it starting state.
- We already fire `auth.login` and `auth.logout` events elsewhere in our code so
  I know those just work.