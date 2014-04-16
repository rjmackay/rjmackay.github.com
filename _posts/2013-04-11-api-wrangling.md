---
layout: post
title: "API wrangling"
tagline: "Designing the API for Ushahidi 3.0"
tags: [ushahidi, api]
---

Following on from my [Building 3.0](http://blog.ushahidi.com/2013/03/21/building-ushahidi-3-0/) blog post, here's another update on Ushahidi 3.0.

We're currently still pushing on finishing the 3.0 API, or at least the first cut at it. The original due date for this was March 30, but thats slipped to mid April. Conversations have quieted down a little as we get to work on *getting things done*. Thats good, but means we need to keep working hard to keep the community up to date on our progress.

## API design primer

Designing an API seemed simple.. but once you get into the weeds and start building things there are a lot of questions to answer. I've done a chunk of reading as I tried to figure out 1. what a proper RESTful approach would look like, 2. what's normal practice, ie. where do developers often cut corners? why? is this good or bad?

A few valuable resources:

* [Web API Design](http://info.apigee.com/Portals/62317/docs/web%20api.pdf) from apigee
* [Whitehouse API standards](https://github.com/WhiteHouse/api-standards)

## Progress

The last couple of weeks have been dominated by the thousands of tiny decisions made at each step of building the API. I've tried to keep the wiki updated with the [general patterns](https://wiki.ushahidi.com/display/WIKI/REST+API#RESTAPI-GeneralPatterns) of our API. This gives a few guidelines

* What methods to use, and how to accomodate more complex queries (ie. search)
* What HTTP response codes to use
* What to cover in functional tests
* How to expose relations and links beteen resources

We've got the basics in now: forms, with their attributes and group, and posts. This gives us enough to start experimenting, but there are still a lot of loose ends. The next things we're looking at are: [sets](https://github.com/ushahidi/Lamu/issues/3), [tags](https://github.com/ushahidi/Lamu/issues/14), [users](https://github.com/ushahidi/Lamu/issues/17) - along with their roles and permissions - and extensions to posts - adding [revisions](https://github.com/ushahidi/Lamu/issues/29), [translations](https://github.com/ushahidi/Lamu/issues/28) and [updates](https://github.com/ushahidi/Lamu/issues/26). I've built out most of the [translations and revisions support](https://github.com/ushahidi/Lamu/pull/41) already, but it still needs polish and documentation.

## Authentication

This is probably the next major decision. How do we handle API authentication? do we use OAuth? 1.0 or 2.0?
Where do we handle actual user login/registration?… the entire app is going to be built on the API.

At the moment I'm leaning towards OAuth 2.0, primarily because its what [Swiftriver uses](https://wiki.ushahidi.com/display/WIKI/SwiftRiver+API+Authentication), and consistency between products is important. However OAuth 2.0 has some issues:

* the editor for the spec [withdrew](http://hueniverse.com/2012/07/oauth-2-0-and-the-road-to-hell/) [saying](http://hueniverse.com/2012/07/on-leaving-oauth/) OAuth 2 had failed
* there are a few ways to [mess](http://www.theregister.co.uk/2011/02/02/facebook_plugs_authentication_flaw/) [up](http://stephensclafani.com/2011/04/06/oauth-2-0-csrf-vulnerability/) [an](http://www.thread-safe.com/2012/02/more-on-oauth-implicit-flow-application.html) [implementation](http://homakov.blogspot.co.nz/2012/07/saferweb-most-common-oauth2.html) security wise
* and one implementation isn't guaranteed to be interoperable with another

That said the many _almost-oauth_ APIs out there probably have more, and similar problems: rolling our own is not an option.

OAuth 2 is probably still going to get a lot of use, and there are [good resources](https://blog.apigee.com/taglist/oauth) appearing on how to do it right, and a couple of good [oauth2](https://github.com/andreassolberg/jso) [clients](https://github.com/adoy/PHP-OAuth2).

One [recommendation](https://blog.apigee.com/detail/oauth_is_it_worth_the_effort/) that stuck out was "If your API can require HTTPS, use OAuth 2.0 with bearer tokens. Otherwise, use OAuth 1.0a". I need to dig into the details of this, but given deployers won't always use SSL this is worth considering.

## Stumbling blocks

In my last post I mentioned adding support for other databases, particularly PostgreSQL/PostGIS. I've already hit the first snag with this: [minion migrations](https://github.com/kohana-minion/tasks-migrations) doesn't handle other database engines **at all**. There are a few forks on github that try to solve this, but its mixed in with some other major changes. This isn't a show stopper, but there's some unpicking to be done and for now we're concentrating on building a working API.

If anyone has working PostGIS knowledge and whats to have a go at getting this working, grab [the code](https://github.com/ushahidi/Lamu), [leave a note](https://github.com/ushahidi/Lamu/issues/25) and hopefully send a pull request. I'm happy to walk you through the existing code and answer questions.