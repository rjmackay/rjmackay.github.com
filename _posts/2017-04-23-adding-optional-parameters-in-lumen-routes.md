---
layout: post
title: Adding optional parameters in Lumen routes
author: Robbie Mackay
date: '2017-04-23T17:52:15+00:00'
categories:
- learned
tags:
- lumen
- php
---
I've been working on migrating [Ushahidi](https://www.ushahidi.com) from [Kohana](https://kohanaframework.org/) to [Lumen](https://lumen.laravel.com) recently. Lumen uses a [FastRoute](https://github.com/nikic/FastRoute) instead of Laravels core router. This is faster than the core route, but also more limited.


Initially reading the [documentation](https://lumen.laravel.com/docs/5.4/routing) ~~it appears not to support optional URL parameters~~ **Optional URL parameters are now included in the routing docs**. But digging into the Fast Route docs it turns out there is some support:


<blockquote>
parts of the route enclosed in [...] are considered optional, so that <code>/foo[bar]</code> will match both <code>/foo</code> and <code>/foobar</code>. Optional parts are only supported in a trailing position, not in the middle of a route.
</blockquote>

So you can do something like

```
$app->get('/config[/{id}]', 'SomeController');
```

It also turns out that [Lumen doesn't strip trailing slashes](https://github.com/laravel/lumen-framework/issues/608) when parsing URLs so the optional parameters were a useful workaround. I have a few routes like

```
$app->get('/config[/]', 'ConfigController@index');
```