<img src="http://human-injection.de/images/26.png" title="Logo of SHS Textpattern Plugins" style="float:left;" />

# SHS Textpattern Plugins

My Collection of Textpattern Plugins

* [German Version](http://human-injection.de/projects/OwnTextpatternPlugins)

## [shs_webmention](shs_webmention)

This plugin for Textpattern 4.5.4 implements a receiver for the webmention specification. Webmention is a simple technology to notify any URL when you link to it on your site. For more information about Webmentions see [https://indiewebcamp.com/webmention](https://indiewebcamp.com/webmention).

* [Manual](http://lab.human-injection.de/webmention/)
* [Installation](http://lab.human-injection.de/webmention/shs_webmention.php)

## [shs_twittercard](shs_twittercard)

This is a plugin for Textpattern 4.5.4. This plugin implements the Twitter Cards format. [Twitter Cards](https://dev.twitter.com/docs/cards) is a meta data format to improve the user experience sharing websites on Twitter.

The only tag defined in this plugin is <txp:shs_twittercards>. It works only inside a single article scope. In article lists you need to implement Twitter Cards manually.

* [Manual](http://lab.human-injection.de/twittercards/)
* [Implementation Details (in German)](http://human-injection.de/articles/twittercards)
* [Installation](http://lab.human-injection.de/twittercards/shs_twittercards.php.txt)

## [shs_xhtmlhelpers](shs_xhtmlhelpers)

This plugin defines a number of tags to handle the differences between HTML and XHTML. With this Tags you can generate XML declarations, DOC types, namespaces, language attributes and content type meta tags. The difference to handwritten markup is the fallback to SGML based HTML instead of XHTML in case of user agents don’t accept XHTML.

* [Manual](http://lab.human-injection.de/textpatternxhtmlhelper/)
* [Installation](http://lab.human-injection.de/textpatternxhtmlhelper/shs_xhtml_helpers.php)

## [shs_categoryhelpers](shs_categoryhelpers)

This plugin for Textpattern 4.5.4 defines some Tags to generate category relatet markup for my [own Weblog](human-injection.de).

Currently it generates a link for each ancestor of the two article categories and combine them.

# Resources on creating Textpattern plugins
* [Overview](http://textbook.textpattern.net/wiki/index.php?title=Plugin_Development_Topics)
* [Tutorial](http://thresholdstate.com/articles/3975/anatomy-of-a-textpattern-plugin-part-1)
* [Mailinglist for plugin devs](http://lists.textpattern.com/)
* [Developer resources (further links, tools and help](http://forum.textpattern.com/viewtopic.php?id=9881)


