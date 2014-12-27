<?php
	// Copyright 2012, 2014 Sebastian Spautz
	
	// "Textpattern Twitter Cards Plugin" is free software: you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation, either version 3 of the License, or
    // any later version.

    // This program is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with this program.  If not, see http://www.gnu.org/licenses/.
	
	// --------------------
	
	// This software includes the PHP library UrlToAbsolute [http://sourceforge.net/projects/absoluteurl/]. 
	// This library is licensed under the following BSD license: 

	 // * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
	 // * All rights reserved.
	 // *
	 // * Redistribution and use in source and binary forms, with or without
	 // * modification, are permitted provided that the following conditions
	 // * are met:
	 // *
	 // *	* Redistributions of source code must retain the above copyright
	 // *	  notice, this list of conditions and the following disclaimer.
	 // *
	 // *	* Redistributions in binary form must reproduce the above
	 // *	  copyright notice, this list of conditions and the following
	 // *	  disclaimer in the documentation and/or other materials provided
	 // *	  with the distribution.
	 // *
	 // *	* Neither the names of David R. Nadeau or NadeauSoftware.com, nor
	 // *	  the names of its contributors may be used to endorse or promote
	 // *	  products derived from this software without specific prior
	 // *	  written permission.
	 // *
	 // * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
	 // * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
	 // * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
	 // * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	 // * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
	 // * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
	 // * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 // * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	 // * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
	 // * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
	 // * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
	 // * OF SUCH DAMAGE.
	 // *
	 // *
	 // * This is a BSD License approved by the Open Source Initiative (OSI).
	 // * See:  http://www.opensource.org/licenses/bsd-license.php
?>
<?php

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ('abc' is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'shs_twittercards';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
$plugin['allow_html_help'] = 0;

$plugin['version'] = '0.2.1';
$plugin['author'] = 'Sebastian Spautz';
$plugin['author_uri'] = 'http://human-injection.de/';
$plugin['description'] = 'Creates Meta-Elements to include Twitter Cards into your pages.';

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

h1. Textpattern Twitter Cards Plugin (Version 0.2.´1)

This plugin for Textpattern 4.5.4 implements the Twitter Cards format (the version from 2013-05-08). "Twitter Cards":https://dev.twitter.com/docs/cards is a meta data format to improve the user experience sharing websites on Twitter. For more information about Twitter Cards see https://dev.twitter.com/docs/cards.

The only tag defined in this plugin is @<txp:shs_twittercards>@. It works only inside a single article scope. In article lists you need to implement Twitter Cards manually.

h2. Basic Usage

The basic usage of @<txp:shs_twittercards />@ creates some basic Meta-Elements filled width coressponding Textpattern fields:

bc. <meta name="twitter:card" content="summary">
<meta name="twitter:url" content="{Permalink of the article}">
<meta name="twitter:title" content="{Title of the article}">
<meta name="twitter:description" content="{Exceprt of the article (HTML element are striped out)}">
<meta name="twitter:image" content="{Article image (if exists)}">

h2. Attributes

Each Property in Twitter Cards corresponds with an Attribute for @<txp:shs_twittercards>@:

|_. Property |_. Attribute |_. Values |_. Defaults |
|-(general).
| twitter:card | cardtype | summary, summary_large_image, photo, app or player | summary |
| twitter:site | site | a twitter Name | empty |
| twitter:site:id | siteid | a twitter user ID | empty |
| twitter:creator | creator | a twitter Name | empty |
| twitter:creator:id | creatorid | a twitter user ID | empty |
|-(summary).
| twitter:title | title | a raw text | article title |
| twitter:description | description | a text | excerpt of the article (HTML element are striped out) |
| twitter:image | image | a picture url | article picture if exitst |
|-(photo).
| twitter:image:width | imagewidth | a integer | empty |
| twitter:image:height | imageheight | a integer | emtpy |
|-(app).
| twitter:app:id:iphone | appidiphone | App Store ID | empty |
| twitter:app:name:iphone | appnameiphone | a string | empty |
| twitter:app:url:iphone | appurliphone | u url | empty |
| twitter:app:id:ipad | appidipad | App Store ID | empty |
| twitter:app:name:ipad | appnameipad | a string | empty |
| twitter:app:url:ipad | appurlipad | a url | empty |
| twitter:app:id:googleplay | appidgoogleplay | Google Play ID | empty |
| twitter:app:name:googleplay | appnamegoogleplay | a string | empty |
| twitter:app:url:googleplay | appurlgoogleplay | a url | empty |
|-(player).
| twitter:player | player | a url | empty |
| twitter:player:width | playerwidth | a integer | empty |
| twitter:player:height | playerheight | a integer | emtpy |
| twitter:player:stream | playerstream | a video url | emtpy |
| twitter:player:stream:content_type | playerstreamcontenttype | a MIME type | emtpy |

h2. Special Attributes

|_. Attribute |_. Values |_. Defaults |_. Description |
| onlytwitteragent | 0 or 1 | 1 | if set to _1_ renders meta elements only for HTTP user agent "Twitterbot" |
| imagedefault | a picture url | empty | if ther is no article image the given url is used for twitter:image |

h2. License

h3. Main Source Code

This software (without included parts, see below) is licensed under the following GPL license:

pre.  * Copyright 2012, 2014 Sebastian Spautz
 *
 * Textpattern Twitter Cards Plugin is free software: you can redistribute it and/or modify
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

h3. Inserted Source Codes

This software includes the PHP library "UrlToAbsolute":http://sourceforge.net/projects/absoluteurl/. 
The library is licensed under the following BSD license: 

pre.  * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *	* Redistributions of source code must retain the above copyright
 *	  notice, this list of conditions and the following disclaimer.
 *
 *	* Redistributions in binary form must reproduce the above
 *	  copyright notice, this list of conditions and the following
 *	  disclaimer in the documentation and/or other materials provided
 *	  with the distribution.
 *
 *	* Neither the names of David R. Nadeau or NadeauSoftware.com, nor
 *	  the names of its contributors may be used to endorse or promote
 *	  products derived from this software without specific prior
 *	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
 * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
 * OF SUCH DAMAGE.
 *
 *
 * This is a BSD License approved by the Open Source Initiative (OSI).
 * See:  http://www.opensource.org/licenses/bsd-license.php

h2. ChangeLog
* _0.1.0:_ First Release
* _0.1.1:_ Fix a bug setting twitter:image to the dokuments permalink
* _0.1.2:_ Fix a bug width case sensitve attribute names
* _0.1.3:_ Restructuring the code to get the twitter:image value
* _0.1.4:_ Some Refactoring (compatible width Textpattern 4.5.1)
* _0.2.0:_ Add card type *summary_large_image* to documentation; implement card type *app* and the *deep-linking feature* of twitter cards
* _0.2.1:_ Check if Title or Description are empty strings
# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---
function shs_twittercards($atts) {
	//Works only for single articles
	if ( !$GLOBALS['is_article_list']  ) {
		
		//Set Parameters to default Values
		extract(lAtts(array(
			'onlytwitteragent' => '1',
			'cardtype' => 'summary',
			'site' => '',
			'siteid' => '',
			'creator' => '',
			'creatorid' => '',
			//'url' => processTags('permlink', ''),
			'description' => $GLOBALS['thisarticle']['excerpt'],
			'title' => $GLOBALS['thisarticle']['title'],
			'image' => '',
			'imagedefault' => '',
			'imagewidth' => '',
			'imageheight' => '',
			
			'appidiphone' => '',
			'appnameiphone' => '',
			'appurliphone' => '',
			'appidipad' => '',
			'appnameipad' => '',
			'appurlipad' => '',
			'appidgoogleplay' => '',
			'appnamegoogleplay' => '',
			'appurlgoogleplay' => '',
			
			'player' => '',
			'playerwidth' => '',
			'playerheight' => '',
			'playerstream' => '',
			'playerstreamcontenttype' => ''
			), $atts));
		
		// Check the title for empty string
		if ($title == '') {
			$title = 'Unkown Title';
		}
		
		// Check the description for empty string
		if ($description == '') {
			$description = 'Nothing to describe!';
		}
		
		// Check the article_image for
		//  a) a picture is set
		if ($image != '') {
			$image_url = $image;
		}
		//  b) an article image exists
		else if ($GLOBALS['thisarticle']['article_image'] != '')
		{
			$image_url = $GLOBALS['thisarticle']['article_image'];
		}
		//  c) a default image is set
		else if ($imagedefault != '') {
			$image_url = $imagedefault;
		}
		else {
			$image_url = '';
		}
		
		//Convert the actual image_url value to an absolute url
		if ($image_url != '')
		{
			// a) a picture ID
			if (is_numeric($image_url)) {
				$image_url = processTags('image_url', 'id="'.$image_url.'" link="0"');
			}
			// b) a relative URL (absolute URL's starts width 'http://' or 'https://')
			else if (!strpos($image_url, 'http://') 
			         && !strpos($image_url, 'https://')) {
				$baseUrl = $_SERVER['HTTP_HOST'] . $GLOBALS['pretext']['request_uri'];
				if (array_key_exists('HTTPS', $_SERVER)) {
					$baseUrl = 'https://' . $baseUrl;
				}
				else {
					$baseUrl = 'http://' . $baseUrl;
				}
				$image_url = url_to_absolute($baseUrl, $image_url);
			}
			// c) a absolute URL
			else {
				//do nothing
				//$image_url = $image_url
			}
		}
		
		// Test if rendering of a Twitter Card is necessary 
		if ( isTwitterAgent() || $onlytwitteragent == '0') {
			// Create the Twitter Card Meta Elements
			$returnvalue .= '<!-- Twitter Card --> ';
			$returnvalue .= '<meta name="twitter:card" content="'.$cardtype.'" /> ';
			
			if ($site != '' && $siteid == '') 
				$returnvalue = $returnvalue.'<meta name="twitter:site" content="'.$site.'" /> ';
			if ($siteid != '') 
				$returnvalue = $returnvalue.'<meta name="twitter:site:id" content="'.$siteid.'" /> ';
			if ($creator != '' && $creatorid == '') 
				$returnvalue = $returnvalue.'<meta name="twitter:creator" content="'.$creator.'" /> ';
			if ($creatorid != '') 
				$returnvalue = $returnvalue.'<meta name="twitter:creator:id" content="'.$creatorid.'" /> ';
			
			$returnvalue .= renderAppProperties($appiphone, $appipad, $appgoogleplay);
			
			//URL is no longer part of the specification
			//if ($url != '') 
			//	$returnvalue = $returnvalue.'<meta name="twitter:url" content="'.$url.'" /> ';
			
			if ($description != '') 
				$returnvalue = $returnvalue.'<meta name="twitter:description" content="'.trim(strip_tags($description)).'" /> ';
			if ($title != '') 
				$returnvalue = $returnvalue.'<meta name="twitter:title" content="'.$title.'" /> ';		
			
			if ($image_url != '') {
				$returnvalue = $returnvalue.'<meta name="twitter:image" content="'.$image_url.'" /> ';
				if ($imagewidth != '') {
					$returnvalue = $returnvalue.'<meta name="twitter:image:width" content="'.$imagewidth.'" /> ';
				}
				if ($imageheight != '') {
					$returnvalue = $returnvalue.'<meta name="twitter:image:height" content="'.$imageheight.'" /> ';
				}
			}
			
			
			
			if ($player != '') {
				$returnvalue = $returnvalue.'<meta name="twitter:player:height" content="'.$player.'" /> ';
				$returnvalue = $returnvalue.'<meta name="twitter:player:width" content="'.$playerwidth.'" /> ';
				$returnvalue = $returnvalue.'<meta name="twitter:player:height" content="'.$playerheight.'" /> ';
			}
			if ($playerstream != '') {
				$returnvalue = $returnvalue.'<meta name="twitter:player:stream" content="'.$playerstream.'" /> ';
				$returnvalue = $returnvalue.'<meta name="twitter:player:stream:content_type" content="'.$playerstreamcontenttype.'" /> ';
			}
				
			return $returnvalue;
		}
		else {
			return "<!-- no TwitterCard include for normal Browsers -->";
		}
	}
}

function renderAppProperties($idIPhoneApp, $nameIPhoneApp, $urlIPhoneApp
		, $idIPadApp, $nameIPadApp, $urlIPadApp
		, $idGoolePlaystoreApp, $nameGoolePlaystoreApp, $urlGoolePlaystoreApp) {
		
	$returnvalue = '<!-- Twitter App/Deep linking properties--> ';    
	if ($idIPhoneApp != '') {
		$returnvalue .= '<meta name="twitter:app:id:iphone" content="'. $idIPhoneApp .'"> ';
	}
	if ($nameIPhoneApp != '') {
	  $returnvalue .= '<meta name="twitter:app:name:iphone" content="'. $nameIPhoneApp .'"> ';
	}
	if ($urlIPhoneApp != '') {
	  $returnvalue .= '<meta name="twitter:app:url:iphone" content="'. $urlIPhoneApp .'"> ';
	}
	if ($idIPadApp != '') {
		$returnvalue .= '<meta name="twitter:app:id:ipad" content="'. $idIPadApp .'"> ';
	}
	if ($nameIPadApp != '') {
	  $returnvalue .= '<meta name="twitter:app:name:ipad" content="'. $nameIPadApp .'"> ';
	}
	if ($urlIPhoneApp != '') {
	  $returnvalue .= '<meta name="twitter:app:url:ipad" content="'. $urlIPadApp .'"> ';
	}
	if ($idGoolePlaystoreApp != '') {
		$returnvalue .= '<meta name="twitter:app:id:googleplay" content="'. $idGoolePlaystoreApp .'"> ';
	}
	if ($nameGoolePlaystoreApp != '') {
	  $returnvalue .= '<meta name="twitter:app:name:googleplay" content="'. $nameGoolePlaystoreApp .'"> ';
	}
	if ($urlGoolePlaystoreApp != '') {
	  $returnvalue .= '<meta name="twitter:app:url:googleplay" content="'. $urlGoolePlaystoreApp .'"> ';
	}
	return $returnvalue;
}

function isTwitterAgent() {
	if ( stristr($_SERVER["HTTP_USER_AGENT"],"Twitterbot") ) {
		return true;
	}
	else {
		return false;
	}
}


/** Following code is copied from http://sourceforge.net/projects/absoluteurl/ and Licensed by the following BSD License. */
/**
 * Edited by Nitin Kr. Gupta, publicmind.in
 */

/**
 * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *	* Redistributions of source code must retain the above copyright
 *	  notice, this list of conditions and the following disclaimer.
 *
 *	* Redistributions in binary form must reproduce the above
 *	  copyright notice, this list of conditions and the following
 *	  disclaimer in the documentation and/or other materials provided
 *	  with the distribution.
 *
 *	* Neither the names of David R. Nadeau or NadeauSoftware.com, nor
 *	  the names of its contributors may be used to endorse or promote
 *	  products derived from this software without specific prior
 *	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
 * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
 * OF SUCH DAMAGE.
 */

/*
 * This is a BSD License approved by the Open Source Initiative (OSI).
 * See:  http://www.opensource.org/licenses/bsd-license.php
 */

/**
 * Combine a base URL and a relative URL to produce a new
 * absolute URL.  The base URL is often the URL of a page,
 * and the relative URL is a URL embedded on that page.
 *
 * This function implements the "absolutize" algorithm from
 * the RFC3986 specification for URLs.
 *
 * This function supports multi-byte characters with the UTF-8 encoding,
 * per the URL specification.
 *
 * Parameters:
 * 	baseUrl		the absolute base URL.
 *
 * 	url		the relative URL to convert.
 *
 * Return values:
 * 	An absolute URL that combines parts of the base and relative
 * 	URLs, or FALSE if the base URL is not absolute or if either
 * 	URL cannot be parsed.
 */
function url_to_absolute( $baseUrl, $relativeUrl )
{
	// If relative URL has a scheme, clean path and return.
	$r = split_url( $relativeUrl );
	if ( $r === FALSE )
		return FALSE;
	if ( !empty( $r['scheme'] ) )
	{
		if ( !empty( $r['path'] ) && $r['path'][0] == '/' )
			$r['path'] = url_remove_dot_segments( $r['path'] );
		return join_url( $r );
	}

	// Make sure the base URL is absolute.
	$b = split_url( $baseUrl );
	if ( $b === FALSE || empty( $b['scheme'] ) || empty( $b['host'] ) )
		return FALSE;
	$r['scheme'] = $b['scheme'];

	// If relative URL has an authority, clean path and return.
	if ( isset( $r['host'] ) )
	{
		if ( !empty( $r['path'] ) )
			$r['path'] = url_remove_dot_segments( $r['path'] );
		return join_url( $r );
	}
	unset( $r['port'] );
	unset( $r['user'] );
	unset( $r['pass'] );

	// Copy base authority.
	$r['host'] = $b['host'];
	if ( isset( $b['port'] ) ) $r['port'] = $b['port'];
	if ( isset( $b['user'] ) ) $r['user'] = $b['user'];
	if ( isset( $b['pass'] ) ) $r['pass'] = $b['pass'];

	// If relative URL has no path, use base path
	if ( empty( $r['path'] ) )
	{
		if ( !empty( $b['path'] ) )
			$r['path'] = $b['path'];
		if ( !isset( $r['query'] ) && isset( $b['query'] ) )
			$r['query'] = $b['query'];
		return join_url( $r );
	}

	// If relative URL path doesn't start with /, merge with base path
	if ( $r['path'][0] != '/' )
	{
		$base = mb_strrchr( $b['path'], '/', TRUE, 'UTF-8' );
		if ( $base === FALSE ) $base = '';
		$r['path'] = $base . '/' . $r['path'];
	}
	$r['path'] = url_remove_dot_segments( $r['path'] );
	return join_url( $r );
}

/**
 * Filter out "." and ".." segments from a URL's path and return
 * the result.
 *
 * This function implements the "remove_dot_segments" algorithm from
 * the RFC3986 specification for URLs.
 *
 * This function supports multi-byte characters with the UTF-8 encoding,
 * per the URL specification.
 *
 * Parameters:
 * 	path	the path to filter
 *
 * Return values:
 * 	The filtered path with "." and ".." removed.
 */
function url_remove_dot_segments( $path )
{
	// multi-byte character explode
	$inSegs  = preg_split( '!/!u', $path );
	$outSegs = array( );
	foreach ( $inSegs as $seg )
	{
		if ( $seg == '' || $seg == '.')
			continue;
		if ( $seg == '..' )
			array_pop( $outSegs );
		else
			array_push( $outSegs, $seg );
	}
	$outPath = implode( '/', $outSegs );
	if ( $path[0] == '/' )
		$outPath = '/' . $outPath;
	// compare last multi-byte character against '/'
	if ( $outPath != '/' &&
		(mb_strlen($path)-1) == mb_strrpos( $path, '/', 'UTF-8' ) )
		$outPath .= '/';
	return $outPath;
}


/**
 * This function parses an absolute or relative URL and splits it
 * into individual components.
 *
 * RFC3986 specifies the components of a Uniform Resource Identifier (URI).
 * A portion of the ABNFs are repeated here:
 *
 *	URI-reference	= URI
 *			/ relative-ref
 *
 *	URI		= scheme ":" hier-part [ "?" query ] [ "#" fragment ]
 *
 *	relative-ref	= relative-part [ "?" query ] [ "#" fragment ]
 *
 *	hier-part	= "//" authority path-abempty
 *			/ path-absolute
 *			/ path-rootless
 *			/ path-empty
 *
 *	relative-part	= "//" authority path-abempty
 *			/ path-absolute
 *			/ path-noscheme
 *			/ path-empty
 *
 *	authority	= [ userinfo "@" ] host [ ":" port ]
 *
 * So, a URL has the following major components:
 *
 *	scheme
 *		The name of a method used to interpret the rest of
 *		the URL.  Examples:  "http", "https", "mailto", "file'.
 *
 *	authority
 *		The name of the authority governing the URL's name
 *		space.  Examples:  "example.com", "user@example.com",
 *		"example.com:80", "user:password@example.com:80".
 *
 *		The authority may include a host name, port number,
 *		user name, and password.
 *
 *		The host may be a name, an IPv4 numeric address, or
 *		an IPv6 numeric address.
 *
 *	path
 *		The hierarchical path to the URL's resource.
 *		Examples:  "/index.htm", "/scripts/page.php".
 *
 *	query
 *		The data for a query.  Examples:  "?search=google.com".
 *
 *	fragment
 *		The name of a secondary resource relative to that named
 *		by the path.  Examples:  "#section1", "#header".
 *
 * An "absolute" URL must include a scheme and path.  The authority, query,
 * and fragment components are optional.
 *
 * A "relative" URL does not include a scheme and must include a path.  The
 * authority, query, and fragment components are optional.
 *
 * This function splits the $url argument into the following components
 * and returns them in an associative array.  Keys to that array include:
 *
 *	"scheme"	The scheme, such as "http".
 *	"host"		The host name, IPv4, or IPv6 address.
 *	"port"		The port number.
 *	"user"		The user name.
 *	"pass"		The user password.
 *	"path"		The path, such as a file path for "http".
 *	"query"		The query.
 *	"fragment"	The fragment.
 *
 * One or more of these may not be present, depending upon the URL.
 *
 * Optionally, the "user", "pass", "host" (if a name, not an IP address),
 * "path", "query", and "fragment" may have percent-encoded characters
 * decoded.  The "scheme" and "port" cannot include percent-encoded
 * characters and are never decoded.  Decoding occurs after the URL has
 * been parsed.
 *
 * Parameters:
 * 	url		the URL to parse.
 *
 * 	decode		an optional boolean flag selecting whether
 * 			to decode percent encoding or not.  Default = TRUE.
 *
 * Return values:
 * 	the associative array of URL parts, or FALSE if the URL is
 * 	too malformed to recognize any parts.
 */
function split_url( $url, $decode=FALSE)
{
	// Character sets from RFC3986.
	$xunressub     = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
	$xpchar        = $xunressub . ':@% ';

	// Scheme from RFC3986.
	$xscheme        = '([a-zA-Z][a-zA-Z\d+-.]*)';

	// User info (user + password) from RFC3986.
	$xuserinfo     = '((['  . $xunressub . '%]*)' .
	                 '(:([' . $xunressub . ':%]*))?)';

	// IPv4 from RFC3986 (without digit constraints).
	$xipv4         = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

	// IPv6 from RFC2732 (without digit and grouping constraints).
	$xipv6         = '(\[([a-fA-F\d.:]+)\])';

	// Host name from RFC1035.  Technically, must start with a letter.
	// Relax that restriction to better parse URL structure, then
	// leave host name validation to application.
	$xhost_name    = '([a-zA-Z\d-.%]+)';

	// Authority from RFC3986.  Skip IP future.
	$xhost         = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
	$xport         = '(\d*)';
	$xauthority    = '((' . $xuserinfo . '@)?' . $xhost .
		         '?(:' . $xport . ')?)';

	// Path from RFC3986.  Blend absolute & relative for efficiency.
	$xslash_seg    = '(/[' . $xpchar . ']*)';
	$xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
	$xpath_rel     = '([' . $xpchar . ']+' . $xslash_seg . '*)';
	$xpath_abs     = '(/(' . $xpath_rel . ')?)';
	$xapath        = '(' . $xpath_authabs . '|' . $xpath_abs .
			 '|' . $xpath_rel . ')';

	// Query and fragment from RFC3986.
	$xqueryfrag    = '([' . $xpchar . '/?' . ']*)';

	// URL.
	$xurl          = '^(' . $xscheme . ':)?' .  $xapath . '?' .
	                 '(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';


	// Split the URL into components.
	if ( !preg_match( '!' . $xurl . '!', $url, $m ) )
		return FALSE;

	if ( !empty($m[2]) )		$parts['scheme']  = strtolower($m[2]);

	if ( !empty($m[7]) ) {
		if ( isset( $m[9] ) )	$parts['user']    = $m[9];
		else			$parts['user']    = '';
	}
	if ( !empty($m[10]) )		$parts['pass']    = $m[11];

	if ( !empty($m[13]) )		$h=$parts['host'] = $m[13];
	else if ( !empty($m[14]) )	$parts['host']    = $m[14];
	else if ( !empty($m[16]) )	$parts['host']    = $m[16];
	else if ( !empty( $m[5] ) )	$parts['host']    = '';
	if ( !empty($m[17]) )		$parts['port']    = $m[18];

	if ( !empty($m[19]) )		$parts['path']    = $m[19];
	else if ( !empty($m[21]) )	$parts['path']    = $m[21];
	else if ( !empty($m[25]) )	$parts['path']    = $m[25];

	if ( !empty($m[27]) )		$parts['query']   = $m[28];
	if ( !empty($m[29]) )		$parts['fragment']= $m[30];

	if ( !$decode )
		return $parts;
	if ( !empty($parts['user']) )
		$parts['user']     = rawurldecode( $parts['user'] );
	if ( !empty($parts['pass']) )
		$parts['pass']     = rawurldecode( $parts['pass'] );
	if ( !empty($parts['path']) )
		$parts['path']     = rawurldecode( $parts['path'] );
	if ( isset($h) )
		$parts['host']     = rawurldecode( $parts['host'] );
	if ( !empty($parts['query']) )
		$parts['query']    = rawurldecode( $parts['query'] );
	if ( !empty($parts['fragment']) )
		$parts['fragment'] = rawurldecode( $parts['fragment'] );
	return $parts;
}


/**
 * This function joins together URL components to form a complete URL.
 *
 * RFC3986 specifies the components of a Uniform Resource Identifier (URI).
 * This function implements the specification's "component recomposition"
 * algorithm for combining URI components into a full URI string.
 *
 * The $parts argument is an associative array containing zero or
 * more of the following:
 *
 *	"scheme"	The scheme, such as "http".
 *	"host"		The host name, IPv4, or IPv6 address.
 *	"port"		The port number.
 *	"user"		The user name.
 *	"pass"		The user password.
 *	"path"		The path, such as a file path for "http".
 *	"query"		The query.
 *	"fragment"	The fragment.
 *
 * The "port", "user", and "pass" values are only used when a "host"
 * is present.
 *
 * The optional $encode argument indicates if appropriate URL components
 * should be percent-encoded as they are assembled into the URL.  Encoding
 * is only applied to the "user", "pass", "host" (if a host name, not an
 * IP address), "path", "query", and "fragment" components.  The "scheme"
 * and "port" are never encoded.  When a "scheme" and "host" are both
 * present, the "path" is presumed to be hierarchical and encoding
 * processes each segment of the hierarchy separately (i.e., the slashes
 * are left alone).
 *
 * The assembled URL string is returned.
 *
 * Parameters:
 * 	parts		an associative array of strings containing the
 * 			individual parts of a URL.
 *
 * 	encode		an optional boolean flag selecting whether
 * 			to do percent encoding or not.  Default = true.
 *
 * Return values:
 * 	Returns the assembled URL string.  The string is an absolute
 * 	URL if a scheme is supplied, and a relative URL if not.  An
 * 	empty string is returned if the $parts array does not contain
 * 	any of the needed values.
 */
function join_url( $parts, $encode=FALSE)
{
	if ( $encode )
	{
		if ( isset( $parts['user'] ) )
			$parts['user']     = rawurlencode( $parts['user'] );
		if ( isset( $parts['pass'] ) )
			$parts['pass']     = rawurlencode( $parts['pass'] );
		if ( isset( $parts['host'] ) &&
			!preg_match( '!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'] ) )
			$parts['host']     = rawurlencode( $parts['host'] );
		if ( !empty( $parts['path'] ) )
			$parts['path']     = preg_replace( '!%2F!ui', '/',
				rawurlencode( $parts['path'] ) );
		if ( isset( $parts['query'] ) )
			$parts['query']    = rawurlencode( $parts['query'] );
		if ( isset( $parts['fragment'] ) )
			$parts['fragment'] = rawurlencode( $parts['fragment'] );
	}

	$url = '';
	if ( !empty( $parts['scheme'] ) )
		$url .= $parts['scheme'] . ':';
	if ( isset( $parts['host'] ) )
	{
		$url .= '//';
		if ( isset( $parts['user'] ) )
		{
			$url .= $parts['user'];
			if ( isset( $parts['pass'] ) )
				$url .= ':' . $parts['pass'];
			$url .= '@';
		}
		if ( preg_match( '!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'] ) )
			$url .= '[' . $parts['host'] . ']';	// IPv6
		else
			$url .= $parts['host'];			// IPv4 or name
		if ( isset( $parts['port'] ) )
			$url .= ':' . $parts['port'];
		if ( !empty( $parts['path'] ) && $parts['path'][0] != '/' )
			$url .= '/';
	}
	if ( !empty( $parts['path'] ) )
		$url .= $parts['path'];
	if ( isset( $parts['query'] ) )
		$url .= '?' . $parts['query'];
	if ( isset( $parts['fragment'] ) )
		$url .= '#' . $parts['fragment'];
	return $url;
}

/**
 * This function encodes URL to form a URL which is properly 
 * percent encoded to replace disallowed characters.
 *
 * RFC3986 specifies the allowed characters in the URL as well as
 * reserved characters in the URL. This function replaces all the 
 * disallowed characters in the URL with their repective percent 
 * encodings. Already encoded characters are not encoded again,
 * such as '%20' is not encoded to '%2520'.
 *
 * Parameters:
 * 	url		the url to encode.
 *
 * Return values:
 * 	Returns the encoded URL string. 
 */
function encode_url($url) {
  $reserved = array(
    ":" => '!%3A!ui',
    "/" => '!%2F!ui',
    "?" => '!%3F!ui',
    "#" => '!%23!ui',
    "[" => '!%5B!ui',
    "]" => '!%5D!ui',
    "@" => '!%40!ui',
    "!" => '!%21!ui',
    "$" => '!%24!ui',
    "&" => '!%26!ui',
    "'" => '!%27!ui',
    "(" => '!%28!ui',
    ")" => '!%29!ui',
    "*" => '!%2A!ui',
    "+" => '!%2B!ui',
    "," => '!%2C!ui',
    ";" => '!%3B!ui',
    "=" => '!%3D!ui',
    "%" => '!%25!ui',
  );

  $url = rawurlencode($url);
  $url = preg_replace(array_values($reserved), array_keys($reserved), $url);
  return $url;
}



# --- END PLUGIN CODE ---

?>
