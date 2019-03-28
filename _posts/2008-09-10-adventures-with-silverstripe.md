---
layout: post
title: Adventures with Silverstripe
tags:
- bug
- cms
- hamish campbell
- php
- silverstripe
- Software
status: publish
type: post
published: true
meta:
  _edit_last: '3372398'
---
I've known about <a href="http://www.silverstripe.com">Silverstripe</a> for a while but haven't had much of a chance to try it. For past projects I've opted for <a title="JojoCMS" href="http://jojocms.org">JojoCMS</a> instead, because I know one of the lead developers personally and the way Jojo works makes sense to me (little details like being able to set up my database structure by creating a DB table first and building the admin from that - it's the other way round in Silverstripe)

However, I recently gained various motivations to try Silverstripe out.

So I started by grabbing the latest version (2.2.2) and a stash of modules of their download page (Gallery, Blog, Flickr and a few others).

The initial setup was very fast and easy, I was up and going in 1 minute.

However when I started looking at the blog module I hit some trouble - given I'm thinking of moving my blog into Silverstripe and using it to build a personal portfolio site - this wasn't a good sign.

After a couple of reinstalls, lots of trail and error, and some much appreciated help from Hamish Campbell (<a href="http://all.or.nothing.net.nz">all.or.nothing.net.nz</a>), I managed to trace the bug to an issue with the BB Code parsing library being broken by the <a href="http://pear.php.net">PEAR</a> library which it was originally based on. It's an issue thats been <a href="http://www.silverstripe.com/site-builders-forum/flat/166025">found</a> by <a href="http://www.silverstripe.com/site-builders-forum/flat/79332">others</a> before, but hasn't been narrowed down since it depends on the environment and gives no error message.

Now -<em> 2 days and a fair amount of hacking later</em> - I've reduced my fix to a couple of lines and submitted a <a href="http://open.silverstripe.com/ticket/2790">patch</a> to Silverstripe.

And I haven't even started writing my own code yet!
