---
layout: post
published: false
title: Building custom blocks in Ushahidi
tagline: a rough guide
tags:
- ushahidi
- code
hidden: false
---

{% include JB/setup %}

I've been meaning to write this up for a while. Adding custom blocks to Ushahidi is a fairly common request
but generally requires a developer, or at least some basic coding skills. However for some basic use cases
it should be possible to add a block with a bit of cut and paste scripting.

To add a block you'll need to create a plugin. This is actually less complicated than it sounds.
In your Ushahidi install find the directory 'plugins' and create a new folder. The structure is like this:

<pre>
/plugins
  /my-custom-blocks
    readme.txt
    /hooks
      register_category_blocks.php
    /views
      category_block.php
</pre>

In more complicated plugins we might also have libraries, controllers, helpers or anything else that exists
in the usual Kohana module structure. But for this simple case lets still to just hooks and views.

## readme.txt
The readme file is required for Ushahidi to pick up the plugin at all.

## hooks/register_category_blocks.php

{% highlight php %}
{% include snippets/register_category_blocks.php %}
{% endhighlight %}

## views/category_block.php

{% highlight php+html %}
{% include snippets/category_wildlife_block.php %}
{% endhighlight %}

Grab the [Gist](http://gist.github.com/3291463) of all the example code.

See also: [wiki docs on creating Ushahidi plugins](https://wiki.ushahidi.com/display/WIKI/Plugins+-+Developers+Guide)
