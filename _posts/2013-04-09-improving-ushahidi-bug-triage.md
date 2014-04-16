---
layout: post
published: true
title: Improving Ushahidi Bug Triage
tags:
- ushahidi
---

I've been thinking around how we manage our bug and features backlogs.
The current Ushahidi 2.x backlogs have 144 features, and 47 Open bugs (even more last week before our bug fix sprint). At this size these start to get a little unweildy and feel overwhelming.

My previous experience with Kanban and other agile methods has demonstrated that limiting work-in-progress, can make life for developers and customers much easier. It reduces the number of things you need to think about, and the amount of time spent on work that hasn't yet shipped.

I recently read [joelonsoftware](http://www.joelonsoftware.com/items/2012/07/09.html) talking about software inventory and I've been thinking about how this could be applied to Ushahidi.
We're building open source software, and delivering a long lived product, not just a one off project, so not everything transfers perfectly.

For instance _"Do not allow more than two weeks (in fix time) of bugs to get into the bug database"_ just doesn't work for us. Our users can add bugs directly themselves and we have no desire to change that, and our pace is slower as our resources are stretched.

However we still have a  large amount of what could be described as 'software inventory' thats built or managed by the core team. Bug queues, feature queues, partially complete code branches, completed work waiting for QA, completed work waiting for release. We need all of these, but when they grow too large they become significantly less useful. Looking at a list of 144 features.. its much harder to know which ones are important, similarly with bugs: we still have issues in the system from 6 months ago.

For bug tracking: **what matters is are the bugs in github valid, fixable, and current.**

Some issues which have been in the system for many months, with little activity fail on both being not current, and probably no longer valid.
Other bugs are not fixable, not because its not technically possible, but because they're low priorities (can be worked around) and our resources are limited - we could leave these bugs in the system, but a more honest response might be to close these and mark them as 'wont fix'.

Feature backlogs are a little different: if our users are happy to invest time in detailing a feature they need thats great. If that leads to large detailed backlog thats ok. What we still need to avoid is expending developer time scoping a feature that we don't have time to build. Ushahidi 2.x is now a feature freeze so this is less of an issue, however I have no doubt we're going to have to look at this again with Ushahidi 3.0.

I've also been guilty of expanding the feature backlog myself by dropping every idea I have into a 2 sec feature ticket. This is useful initially, but over time its just filling the backlog with things that aren't really useful, and maybe just unneeded by our users.

Those watching the github issues will have noticed a recent flurry of closing tickets.. this isn't a major change in behaviour. But it reflects a recent bug fix sprint and a push to close invalid bugs as mentioned above.

**You closed my bug, but its not fixed. What should I do?**

If you think we've closed an issue thats still important for you, just let us know: comment on the ticket (and maybe reopen it). The worst thing you could do here would be to ignore it, and decide we don't care. We simply don't always know if you're waiting patiently on an issues, or is you've forgotten about it and moved on. Give us an update!

