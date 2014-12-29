# SHS Textpattern Plugins

My Collection of Textpattern Plugins

## [shs_webmention](shs_twittercards)

This plugin for Textpattern 4.5.4 implements a receiver for the webmention specification. Webmention is a simple technology to notify any URL when you link to it on your site. For more information about Webmentions see [https://indiewebcamp.com/webmention](https://indiewebcamp.com/webmention).

## [shs_twittercard](shs_twittercard)

This is a plugin for Textpattern 4.5.4. This plugin implements the Twitter Cards format. [Twitter Cards](https://dev.twitter.com/docs/cards) is a meta data format to improve the user experience sharing websites on Twitter.

The only tag defined in this plugin is <txp:shs_twittercards>. It works only inside a single article scope. In article lists you need to implement Twitter Cards manually.

## [shs_xhtmlhelpers](shs_xhtmlhelpers)

This plugin defines a number of tags to handle the differences between HTML and XHTML. With this Tags you can generate XML declarations, DOC types, namespaces, language attributes and content type meta tags. The difference to handwritten markup is the fallback to SGML based HTML instead of XHTML in case of user agents don’t accept XHTML.
