---
layout: page
title: Robbie Mackay
tagline: Software Developer and Eco Geek, Works for <a href="http://www.ushahidi.com">Ushahidi</a>.
---



## Posts

<ul class="posts">
  {% for post in site.posts %}
    {% unless post.hidden %}
    <li>
      <h3><span>{{ post.date | date_to_string }}</span> &raquo; <a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></h3>
      {{ post.content | strip_html | truncatewords:75}}
      <p><a href="{{ post.url }}">Read more...</a></p>
    </li>
    {% endunless %}
  {% endfor %}
</ul>

