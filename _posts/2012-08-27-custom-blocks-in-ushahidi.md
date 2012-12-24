---
layout: post
published: true
title: Building custom blocks in Ushahidi
tagline: a rough guide
tags:
- ushahidi
- code
hidden: true
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

## So how does this work?

The hook 'register_category_blocks.php' is included by Ushahidi (and Kohana). This hook registers the 'Wildlife Reports' block with Ushahidi core by calling ```blocks::register()```.

```// Array of block params
$block = array(
  "classname" => "category_wildlife_block", // Must match class name aboce
	"name" => "Wildlife Reports",
	"description" => "List the 10 latest reports in the wildlife category"
);
// register block with core, this makes it available to users 
blocks::register($block);
```

The array passed to ```blocks::register()``` tells Ushahidi the plugin name, description, and where to find the plugin content.

When the plugin is rendered, Ushahidi will call ```category_wildlife_blocks::block()```.

``` 
// Load the reports block view
$content = new View('blocks/category_wildlife_block'); // CHANGE THIS IF YOU WANT A DIFFERENT VIEW

// ID of the category we're looking for
$category_id = 7; // CHANGE THIS

// Get Reports
$content->incidents = ORM::factory('incident')
	->with('location')
	->join('incident_category', 'incident.id', 'incident_category.incident_id')
	->where('incident_active', '1')
	->where('category_id', $category_id)
	->limit('10')
	->orderby('incident_date', 'desc')
	->find_all();

echo $content;
```

This loads the view ```blocks/category_wildlife_block.php``` and passes it a list of incidents: the last 10 incidents with category ID = 7.

## Tweaking it

* Change the category ID - just change line 24 of 'register_category_blocks.php'. You might also want to change the name and class for the block too.
* Change the html in 'category_wildlife_block.php' - if you want to tweak the output slightly you can easily change it here
* Filter the incidents by something else - change ```where()``` call on line 30 of 'register_category_blocks.php'
* Load images or similar - I'll post an example for this in the new year...

## See also

Grab the [Gist](http://gist.github.com/3291463) of all the example code.

See also: [wiki docs on creating Ushahidi plugins](https://wiki.ushahidi.com/display/WIKI/Plugins+-+Developers+Guide)
