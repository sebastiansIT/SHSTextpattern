<?php
	// Copyright 2014, 2016, 2018 Sebastian Spautz
	
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

$plugin['version'] = '0.4.1';
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

h1. Textpattern Webmention Plugin (Version 0.4.1)

This plugin for Textpattern 4.6.2 implements a receiver for the webmention specification. 
Webmention is a simple technology to notify any URL when you link to it on your site. 
For more information about Webmentions see "https://indiewebcamp.com/webmention":https://indiewebcamp.com/webmention.

h2. Basic Usage

The first part of this plugin is the receiver @shs_webmention_receive()@. 
It accepts request to the URL-path _webmention.php_ in your textpattern 
installation. This requests must submit a @source@ and a @target@ parameter. 
With this parameters the receiver creates a new comment in the database.

If the target of a mention is a article list (search result, category listing ec.) 
the comment is attached to a hard coded dummy article (ID 84). Please change this ID.

The source is checked for a link to the target. Also it checks the type of the webmention. The following values in class or rel attributes are checked:

* in-reply-to
* u-in-reply-to
* u-like-of
* u-repost-of

Second part of the plugin is a Tag (@<txp:shs_webmention_discovery />@). This 
tag generates the Markup and HTTP header for the endpoint discovery. Please 
use it in you page template to activate Webmention on your site.

h2. Tags and Attributes

h3. txp:shs_webmention_discovery

Generate markup for webmention endpoint discovery. There are no attributes 
for this tag available.

bc. <txp:shs_webmention_discovery />

bc. <link rel="webmention" href="http://human-injection.de/webmention.php"/>

h2. TODO

* Get Informations from Source (microformats ec.)
* Implement Frontend to send Webmentions
* Make it configurable
* Follow redirects

h2. License

This software is licensed under the following GPL license:

pre.  * Copyright 2014, 2016, 2018 Sebastian Spautz
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

* _0.4.1:_ Declare local variables to avoid PHP notices
* _0.4.0:_ Register the custom tag to avoid a warning in debug mode of Textpattern
* _0.3.0:_ Checks Type of webmention (reply, like, repost or simple link)
* _0.2.2:_ Refactoring code
* _0.2.1:_ Fix a simple bug
* _0.2.0:_ Updates existing webmentions instead of generating dublicates
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
	$values = array();
	$responseMessage = '';
	
	if(shs_webmention_receiver_called() == false) {
		return;
	}
	# Get source and target from request
	$values['targetUrl'] = gps('target');
	$values['sourceUrl'] = gps('source');
	# Check Parameters
	if ($values['sourceUrl'] == '' || $values['targetUrl'] == '') {
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
		exit();
	}
	# Get content from source
	$sourceContent = shs_getSource($values['sourceUrl']);
	# generate parameters for new/updated textpattern comment
	$values['parentId'] = shs_getArticleId($values['targetUrl'], 84); //Change second parameter to the id of your dummy article for all mentions targeting a article list or other content of your blog
	$values['commentId'] = shs_getCommentId($values['sourceUrl'], $values['parentId']); //If a comment allways represent this webmention the ID of this comment is detected
	$values['ip'] = doSlash(serverset('REMOTE_ADDR'));
	$values['status'] = 0; //Please Moderate the generated Comment
	$values['name'] = 'Webmention';
	$values['email'] = 'webmention@'.$_SERVER['SERVER_NAME'];
	$values['commentMessage'] = '<div class="webmention">%%content%%</div>';
	
	# Spam-Check
	#if (stristr($sourceContent, $values['targetUrl'])) {
	if (preg_match_all('/<a[^>]*href=["|\']'.str_replace(array('.', '/'), array('\.', '\/'), $values['targetUrl']).'["|\'][^>]*>/', $sourceContent, $links) > 0) {
		$types = array();
		for ($i = 0; $i < count($links[0]); $i++) {
			//General Rel/Class-Value selector: (rel|class)=["|\']((\w|-)*(\s(\w|-)*)*)["|\']
			if (preg_match_all('/(rel|class)=["|\']((\w|-)*\s?)*(u-)?in-reply-to(\s?(\w|-)*)*["|\']/', $links[0][$i], $rel) > 0) {
				$values['types'][] = "reply";
				$values['commentMessage'] = str_replace('%%content%%', '<p class="reply"><a rel="nofollow" href="'.$values['sourceUrl'].'">'.$values['sourceUrl'].'</a> is a reply to <a href="'.$values['targetUrl'].'">this Article</a>.</p>%%content%%', $values['commentMessage']);
			}
			if (preg_match_all('/(rel|class)=["|\']((\w|-*)\s?)*u-like-of(\s?(\w|-)*)*["|\']/', $links[0][$i], $rel) > 0) {
				$values['types'][] = "like";
				$values['commentMessage'] = str_replace('%%content%%', '<p class="like"><a href="'.$values['targetUrl'].'">this Article</a> is liked on <a rel="nofollow" href="'.$values['sourceUrl'].'">'.$values['sourceUrl'].'</a>.</p>%%content%%', $values['commentMessage']);
			}
			if (preg_match_all('/(rel|class)=["|\']((\w|-)*\s?)*u-repost-of(\s?(\w|-)*)*["|\']/', $links[0][$i], $rel) > 0) {
				$values['types'][] = "repost";
				$values['commentMessage'] = str_replace('%%content%%', '<p class="repost"><a rel="nofollow" href="'.$values['sourceUrl'].'">'.$values['sourceUrl'].'</a> is a repost of <a href="'.$values['targetUrl'].'">this Page</a>.</p>%%content%%', $values['commentMessage']);
			}
		}
		if (count($values['types']) == 0) {
			$values['types'][] = "link";
			$values['commentMessage'] = doSlash(str_replace('%%content%%', '<p><a href="'.$values['targetUrl'].'">this Page</a> is linked on <a rel="nofollow" href="'.$values['sourceUrl'].'">'.$values['sourceUrl'].'</a>.</p>', $values['commentMessage']));
		} else {
			$values['commentMessage'] = doSlash(str_replace('%%content%%', '', $values['commentMessage']));
		}
		
		$responseMessage = 'Thanks for your mention from '.$values['sourceUrl'].' to '.$values['targetUrl'].'.';
		$responseMessage .= " ".count($links)." found in source.";
		$responseMessage .= " Following types of webmention found in source: ".implode(", ",$values['types']);
	} else {
		$values['status'] = -1; //Junk
        $responseMessage = 'There is no reference on '.$values['sourceUrl'].' to '.$values['targetUrl'].'. This mention is classified as junk.';
    }	
	shs_saveComment($values);
	# Generate HTTP-Response
	header($_SERVER['SERVER_PROTOCOL'] . ' 202 Accepted');
	header('Content-type: text/plain');
	echo $responseMessage;
	exit();
}
#-- END Implementation

#-- BEGIN Discovery
function shs_webmention_discovery($atts) {
	global $prefs;
    $returnvalue = '';
    
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
function shs_getArticleId($targetUrl, $default) {
	$articleUrlTitle = explode('?', $targetUrl);
	$articleUrlTitle = explode('/', $articleUrlTitle[0]);
	$articleUrlTitle = array_reverse($articleUrlTitle);
	$detectedParent = safe_row( "ID", "textpattern", "url_title='".$articleUrlTitle[0]."'", false );
	if ($detectedParent != false) {
		return $detectedParent["ID"];
	} else {
		return $default;
	}
}
function shs_getCommentId($sourceUrl, $parentId) {
	$detectedComment = safe_row( "discussid", "txp_discuss", "parentid='".$parentId."' AND web='".$sourceUrl."' AND message LIKE '<div class=\"webmention\">%'", false );
	if ($detectedComment != false) {
		return $detectedComment["discussid"];
	} else {
		return -1;
	}
}
function shs_saveComment($values) {
	if ($values['commentId'] <= 0) { # Insert new
		$rs = safe_insert( 
			"txp_discuss", 
			"parentid  = ".$values['parentId'].", 
			name          = '".$values['name']."', 
			email      = '".$values['email']."', 
			web          = '".$values['sourceUrl']."', 
			ip          = '".$values['ip']."', 
			message   = '".$values['commentMessage']."',
			visible   = ".$values['status'].", 
			posted      = now()" 
		); 
	} else { # Update existing
		$rs = safe_update( 
			"txp_discuss", 
			"name          = '".$values['name']."',  
			email      = '".$values['email']."',  
			web          = '".$values['sourceUrl']."', 
			ip          = '".$values['ip']."', 
			message   = '".$values['commentMessage']."',
			visible   = ".$values['status'].", 
			posted      = now()",
			"discussid = ".$values['commentId']
		); 
	}
}
function shs_getSource($sourceUrl) {
	global $pretext;
	
	ob_start();
	$ch = curl_init($sourceUrl);
	curl_setopt($ch,CURLOPT_USERAGENT,$pretext['sitename']);
	curl_setopt($ch,CURLOPT_HEADER,0);
	$ok = curl_exec($ch);
	curl_close($ch);
	$sourceContent = ob_get_contents();
	ob_end_clean();
	
	return $sourceContent;
}
#function shs_parse_microformats($source) {
	# https://pin13.net/mf2/?url=http%3A%2F%2Fhuman-injection.de%2Farticles%2Ftwittercards
	# https://mf2py.herokuapp.com/parse?url=http%3A%2F%2Fhuman-injection.de%2Farticles%2Ftwittercards
	#ob_start();
	#$ch = curl_init("https://mf2py.herokuapp.com/parse?url=".$source);
	#curl_setopt($ch,CURLOPT_USERAGENT,$pretext['sitename']);
	#curl_setopt($ch,CURLOPT_HEADER,0);
	#$ok = curl_exec($ch);
	#curl_close($ch);
	#$microformatsContent = ob_get_contents();
	#ob_end_clean();
	
	#$microformatsContent = json_decode($microformatsContent, true);
	#$result = array();
	
	#for ($i = 0; i < count($microformatsContent['items']); $i++) {
	#	$item = $microformatsContent['items'][$i];
	#	if (in_array('h-entry', $item['type']) == true) {
	#		$result['title'] = $item['properties']['name'][0];
	#	}
	#}
	#return $result;
#}
#-- END Helper Functions --

if (class_exists('\Textpattern\Tag\Registry')) {
	Txp::get('\Textpattern\Tag\Registry')
		->register('shs_webmention_discovery')
	;
}

# --- END PLUGIN CODE ---
?>
