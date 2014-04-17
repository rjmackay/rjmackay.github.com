---
date: 3013-05-27
layout: post
title: "V3 Frontend thoughts in progress"
tagline: "Tying the API to the browser"
tags:
- ushahidi
- ui
- architecture
-
---

The Ushahidi v3 API has started to reach a basic workable point: it handles posts (with revisions, translations and updates), forms, attributes, tags and sets. We can do a rough import of data from Ushahidi 2.x. There's more work to do on but I think we've reached a stage where its valuable to start prototyping frontend UI, not just perfecting the API.

To this end I've started working on the 'frontend architecture' feature: finding and wiring up the basic peices for rendering our UI.

## Some requirements

* UI should be theme-able - while I don't intend to have fully working themes yet, choices made now should account for overriding the design via themes later.
* Data should be loaded via the API. This might be loaded using XHR from JS, or loaded via and internal request in PHP. But we should be fetching data through the API, not by hitting models or the database directly.
* User data should be escaped by default - this is more specific version of [secure by default](http://en.wikipedia.org/wiki/Secure_by_default). In reality this means whatever templating engine we use should escape auto escape variables for HTML output.

Taking those a little further. Further - less solid - requirements:

* ?? Templates should use minimal logic. Ushahidi 2.x templates are full of logic that should really be captured elsewhere, this makes life harder for theming and leads to more breakages between version. 3.x should seperate application and presentation logic: this is part of the drive for splitting API and UI.
* Templates should be shared between PHP and JS. This means we can render a page in PHP but handle partial updates in JS, without having to write the template twice.
* CSS and JS handling should be centralized: this is driven by the requirement for themeing. If themes are to be able to override any and all CSS/JS then there needs to be some central handling.

### On Build process

My research has led me to a lot of build processes for compressing CSS and JS, compiling LESS/SCSS files, pre-processing images. Many of these are great, and could improve performance and ease development. However we need to consider ease of deployment. As much as possible Ushahidi should remain a single self contained application. Our build process should either be handled automatically in PHP or optional, ie. if you need a ruby gem to compile CSS and JS files, we should also be able to serve the uncompiled source version. If we use LESS or SCSS, we should compile this through PHP, not require nodejs, etc.

## General design goals

* Easy to deploy
	* While we should support complex scalable architectures, part of what has made Ushahidi successful is its ease of deployment. Ushahidi should be deployable as a single web app with a minimal set of external requirements.
	* It is tempting to split into an API and Frontend as seperate applications, or spin search out to ApacheSolr, etc .. but while these might be possible, they should not be required.
* Secure by default
	* As much as possible Ushahidi should protect the security and privacy of both deployers and reporters by default.
	* This includes supporting easy, usable options for anonymity and pseudonymity
	* Encouraging deployers to consider privacy and security in the context of their project
* Mobile friendly
	* Responses to this take multiple forms: mobile first responsive design, mobile apps, offline web apps
* Web Security best practice
	* This is the basics: OWASP top 10 and other application hardening.
