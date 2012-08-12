---
layout: post
title: Going further on coding standards and best practices
tags:
- Everything else
status: draft
type: post
published: false
meta:
  _edit_last: '1'
---
Ushahidi has long had a set of coding standards published on the wiki: https://wiki.ushahidi.com/display/WIKI/Coding+Style+Guide
and while we still haven't got all the old code updated to match these, they're largely adhered to on any new code added.

However, recently we've seen a lot of bugs and a lot of security issues, many of these are historical issue that have been around for a long time. But still its a concern and I think we should be thinking about how to avoid anything similar in future. The problem with current coding standards is obvious from the name: its a style guide. Its purely cosmetic and says nothing about the quality of code being written. Here's some basic points to start off a real list of standards:

**Always use query binding**
<div>Kohana's DB library supports query binding, as should pretty much any decent DB library.</div>
<div></div>
<div>Warning signs</div>
<ul>
	<li>"WHERE x =".$_GET['param']
<ul>
	<li>Clearly broken. This is a classic SQLi risk</li>
</ul>
</li>
	<li>Using sprint in sql - its a warning
<ul>
	<li>This is a pretty good sign that what you were really looking for is query binding, not string formatting.</li>
	<li>Sprintf is NOT an alternative to escaping and is really just bad practice for SQL.</li>
</ul>
</li>
	<li>where($filter)
<ul>
	<li>What's being passed in $filter? who knows..</li>
	<li>It MIGHT get automatically escaped if its formatted like array('id' =&gt; 2)
but it won't if its more like array('ID = 2')</li>
	<li>I didn't notice this issue at first when searching the code base to fix SQLi issues so I'm still working on fixing all of these.</li>
</ul>
</li>
</ul>
<div>**If the tool isn't good enough, fix it**</div>
Kohana's core library have query binding, a query builder and an ORM but we often skip the smart side of these due to issues with complex SQL expression and handling MySQL Spatial data.
The problem with falling back to Kohana's query binding for large complex queries is that it doesn't use named binding, all the params are represented by ? and so the params array must be passed in order. This is fine if you've got a simple query ie:

$db-&gt;query("Select * from incidents WHERE id = ?", $id);

But if you're constructing a large query with many params, especially when dealing with lat and lon which are easily mixed up anyway, this gets unweildy. Solution? Fix the tool
In our latest development code I've now backported some Kohana 3.0 style DB functions to Ushahidi. First up: named query binding

$db-&gt;query("Select * from incidents WHERE id = :id", array(':id', $id));

&nbsp;

&nbsp;
