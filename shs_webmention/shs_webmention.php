<?php
	// Copyright 2014 Sebastian Spautz
	
	// "Textpattern Webmention Plugin" is free software: you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation, either version 3 of the License, or
    // any later version.

    // This program is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with this program.  If not, see http://www.gnu.org/licenses/.
?>
<?php

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ('abc' is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'shs_webmention';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
$plugin['allow_html_help'] = 0;

$plugin['version'] = '0.1.0';
$plugin['author'] = 'Sebastian Spautz';
$plugin['author_uri'] = 'http://human-injection.de/';
$plugin['description'] = 'Implements a receiver for webmentions.';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = 0;

// Plugin 'flags' signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use.
//if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
//if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

if (!defined('txpinterface'))
	@include_once('zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---

h1. Textpattern Webmention Plugin (Version 0.1.0)

This plugin for Textpattern 4.5.4 implements a receiver for the webmention specification. 
Webmention is a simple technology to notify any URL when you link to it on your site. 
For more information about Webmentions see "https://indiewebcamp.com/webmention":https://indiewebcamp.com/webmention.

h2. Basic Usage

The first part of this plugin is the receiver @shs_webmention_receive()@. 
It accepted request to the URL-path _webmention.php_ in your textpattern 
installation. This requests must submit a @source@ and a @target@ parameter. 
With this parameters the receiver creates a new comment in the database.

If the target of a mention is a article list (searchresult, category listing ec.) 
the comment is attached to a hard coded dummy article (ID 84). Please change this ID.

Second part of the plugin is a Tag (@<txp:shs_webmention_discovery />@). This 
tag generates the Markup and HTTP header for the endpoint discovery. Please 
use it in you page template to activate Webmention on you site.

h2. Tags and Attributes

h3. txp:shs_webmention_discovery

Generates Markup for webmention endpoint discovery. There are no attributes 
for this tag.

bc. <txp:shs_webmention_discovery />

bc. <link rel="webmention" href="http://human-injection.de/webmention.php"/>

h2. TODO

* Get Informations from Source (microformats ec.)
* Implement Frontent to send Webmentions
* Make it configurable
* Follow Redirects

h2. License

This software is licensed under the following GPL license:

pre.  * Copyright 2014 Sebastian Spautz
 *
 * Textpattern Webmention Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * see http://www.gnu.org/licenses/.

This Plugin use some code from https://gist.github.com/adactio/6484118.
 
h2. ChangeLog

* _0.1.0:_ First Release
# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

# -- REGISTER textpattern callback functions
register_callback('shs_webmention_receive','textpattern');
#-- END Register callbacks

#-- BEGIN Receiver Implementation
function shs_webmention_receive() {
	if(shs_webmention_receiver_called() == false) {
		return;
	}
	# Get source and target from request
	$target = gps('target');
	$source = gps('source');
	# Check Parameters
	if ($source == '' || $target == '') {
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
		exit();
	}
	# Get content from source
	ob_start();
	$ch = curl_init($source);
	curl_setopt($ch,CURLOPT_USERAGENT,$pretext['sitename']);
	curl_setopt($ch,CURLOPT_HEADER,0);
	$ok = curl_exec($ch);
	curl_close($ch);
	$sourceContent = ob_get_contents();
	ob_end_clean();
	
	$ip = doSlash(serverset('REMOTE_ADDR'));
	$parentId = 84;
	$parentUrlTitle = explode('?', $target);
	$parentUrlTitle = explode('/', $parentUrlTitle[0]);
	$parentUrlTitle = array_reverse($parentUrlTitle);
	$detectedParent = safe_row ( "ID", "textpattern", "url_title='".$parentUrlTitle[0]."'", false );
	if ($detectedParent != false) {
		$parentId = $detectedParent["ID"];
	}
	$status = 0; //Please Moderate this Comment
	$commentMessage = doSlash('<a href="'.$source.'">'.$source.'</a> has send a webmention to '.$target.'.');
	#$commentMessage = doSlash(markup_comment('Webmention from \"'.$source.'\":'.$source));
	$responseMessage = '';
	
	if (stristr($sourceContent, $target)) { # Source contains the target URL
		$responseMessage = 'Thanks for your mention from '.$source.' to '.$target.'.';
		
	} else { # Mention is possibly junk
		$status = -1; //Junk
        $responseMessage = 'There is no reference on '.$source.' to '.$target.'. This mention is classified as junk.';
    }
	$rs = safe_insert( 
			"txp_discuss", 
			"parentid  = ".$parentId.", 
			name          = 'Webmention', 
			email      = 'webmention@human-injection.de', 
			web          = '".$source."', 
			ip          = '".$ip."', 
			message   = '".$commentMessage."',
			visible   = ".$status.", 
			posted      = now()" 
		); 
		
	#generate HTTP-Response
	header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');
	header('Content-type: text/plain');
	echo $responseMessage;
	exit();
}
#-- END Implementation

#-- BEGIN Discovery
function shs_webmention_discovery($atts) {
	global $prefs;
	header('Link:<http://'.$prefs['siteurl'].'/webmention.php>; rel="webmention"', false);
	$returnvalue .= '<!-- Webmention Discovery --> ';
	$returnvalue .= '<link rel="webmention" href="http://'.$prefs['siteurl'].'/webmention.php" />';
	return $returnvalue;
}
#-- END Discovery

# -- BEGIN Helper Functions --
function shs_webmention_receiver_called() {
	global $pretext;
	
	$uri = $pretext['request_uri'];
	$uri = explode('?',$uri);
	$uri = explode('/',$uri[0]);
	$uri = array_reverse($uri);
	# check if url is relevant for the receiver
	if(in_array($uri[0],array('webmention.php'))) {
		return true;
	}
	# check if POST and GET parameter are relevant for the receiver
	if(gps('webmention') == 'webmention') {
		return true;
	}
	# else
	return false;
}
#-- END Helper Functions --

# --- END PLUGIN CODE ---

?>
