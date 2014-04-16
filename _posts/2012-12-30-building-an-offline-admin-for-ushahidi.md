---
date: 2013-12-30
layout: post
published: false
title: "Building an offline admin for Ushahidi"
tagline: ""
tags:
- ushahidi
- code
- html5
---

# Building an HTML5 offline plugin for Ushahidi

## Basic structure

It's built like a normal plugin but super simple - theres only 1 page to serve since we're building a single page offline app.

* plugins/offline
  * controllers
    * offline.php
    * ?
  * css
  * js
    * ushahidi.app.js
    * ushahidi.models.js
    * ushahidi.views.js
  * views
    * index.html

Thats a cut down list of files. I've skipped a bunch of JS libraries that we're relying on. The key ones are: backbonejs, and backbone.offline (handles storing reports in localStorage)

The offline app is a "single page app" that replicates the key pieces of the Ushahidi admin: reports and messages. Its built on backbone.js, with backbone.offline handling the localStorage and syncing. I'm using the appcache manifest to store the app JS/HTML/CSS when offline.

The single offline controller serves the app to /offline and the appcache manifest to offline/index.appcache (link).
/offline is specified as the fallback url for 'admin' and 'admin/*'
* and /api are specified in the  NETWORK section to ensure normal Ushahidi requests and api still work as usual.


## Difficulties

### The Ushahidi API

I started out developing this against the built in Ushahidi API... but its a mess, especially if you're using backbonejs.

Backbone expects unique URLs per model, and collection. So something like

* /api/reports
* /api/reports/100

... and it expects a simple JSON object with just model attributes at the top level

```
{
'id': 1,
'title': 'something'
},
{
'id': 2,
'title': 'something else'
}
```

but the Ushahidi api response looks more like

```
{
payload: {
domain: "https://mydomain.com",
incidents: [
{
incident: {
incidentid: "1",
incidenttitle: "Paoua : La menace d’attaque de la ville fait déplacer la population",
incidentdescription: "Paoua, 23 octobre 2012 (RJDH) ",
incidentdate: "2012-10-23 16:33:00",
incidentmode: "1",
incidentactive: "1",
incidentverified: "1",
locationid: "999",
locationname: "Paoua, Central African Republic",
locationlatitude: "7.243101",
locationlongitude: "16.43861"
},
{
incident: {
incidentid: "2",
incidenttitle: "Ndélé : Des sinistrés reçoivent une assistance",
incidentdescription: "Ndélé.",
incidentdate: "2012-10-23 16:27:00",
incidentmode: "1",
incidentactive: "1",
incidentverified: "1",
locationid: "998",
locationname: "N'Délé, Central African Republic",
locationlatitude: "8.410221",
locationlongitude: "20.651291"
},
}
],
error: {
code: "0",
message: "Pas d'erreur"
}
}
```

You can work around this by implementing a custom Backbone.sync() to call the correct urls, and custom parse() method on collections to handle weird json returned.
I started down this path and it worked OK for fetching reports, but I hit trouble when I wanted to save them back to the server. Why? The Ushahidi API expects a different format when creating/updating reports from what it returns. More madness!

My solution: hack a quick rest api plugin, that follows more RESTful principles.

* Separate URLs for different resources: incidents, categories, users.
* Recognises GET/PUT/POST/DELETE
*

## Appcache Manifest

Had lost of issues with caching, and cache not refreshing.

* far future cache expires
  * worked around using .htaccess hack and embedding md5 in the url
  * behaves different in firefox vs chrome. Firefox honors the cache headers. MUST change file names
* Manage the cache through JS
  * In theory not required
  * in practice: must have for sanity and good debugging in firefox
* Fallback URLs
  * falls back for other errors: 50x 40x .. major problem when on the same domain as other URLS


Lessons: it pays to get this right BEFORE you ever deploy. Fixing cached code can be near impossible.

