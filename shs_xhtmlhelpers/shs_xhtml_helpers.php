<?php
	// Copyright 2011 Sebastian Spautz
	
	// "Textpattern XHTML Helper Plugin" is free software: you can redistribute it and/or modify
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
$plugin['name'] = 'shs_xhtml_helpers';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
$plugin['allow_html_help'] = 0;

$plugin['version'] = '0.8.3';
$plugin['author'] = 'Sebastian Spautz';
$plugin['author_uri'] = 'http://human-injection.de/';
$plugin['description'] = 'Define Tags to handle XML-Declaration, DOCTYPE and other specifice elements of XHTML.';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
# $plugin['order'] = 5;

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = 0;

if (!defined('txpinterface'))
	@include_once('zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---

h1. Textpattern XHTML-Helpers

This plugin defines a number of tags to handle the differences between HTML and XHTML.
With this Tags you can generate XML declarations, DOC types, namespaces, language attributes and content
type meta tags. The difference to handwritten markup is the fallback to SGML based HTML instead of
XHTML in case of user agents don't accept XHTML.

h2. XML Declaration

@<txp:shs_xml_declaration/>@ generates a XML-Declaration when XHTML is accepted. Using this Tag the Mime-Type in the HTTP-Response is automaticly set to "appllication/xhtml+xml".

h2. Document Type Definition

The second tag <txp:shs_doctype /> generates a DTD(Document Type Definition)-Declaration. You can select between different HTML and XHTML DTDs. With the attribute _xhtmltype_ you can select the XHTML-DTD to generate if XHTML is acceped by the user agend. As a fallback you can spezifie an old HTML-DTD with the attribute _htmltype_.

The Following Values are accepted for both attributes:
|Attribute Value|DTD Declaration|
|html_4_transitional|<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> |
|html_4_strict|<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">|
|xhtml_1_0_transitional|<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">|
|xhtml_1_0_strict|<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">|
|xhtml_1_1|<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">|
|xhtml_mathml_svg|<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">|
|html5|<!DOCTYPE html>|

h3. Example

bc. <txp:shs_doctype html="html_4_strict" xhtml="xhtml_1_0_strict" />

h2. Namespaces

With @<txp:shs_namespace />@ namespace declarations are generated if XHTML is accepted by the user agent. Otherwise, nothing is added to your page.

By default the XHTML namespace "http://www.w3.org/1999/xhtml" is printet out. In additional you can add SVG, MathML, XLink ore other namespace declarations by using the 
attributes _svg_, _mathml_, _xlink_ and _other_. 

The Attributes _svg_, _mathml_ and _xlink_ can use as an boolean attributes. A Value of 0 hide the namspace declaration, 
a value of 1 generates a declaration with a prefix of *svg*, *m* or *xlink*. Alterative you can set your own prefix as the attributes value: @mathml="math"@. 

With the Attribute _other_ you can add additional namespaces to your page.

Use this Tag inside your <HTML> tag.

h3. Example

bc. <html <txp:shs_namespace svg="1" mathml="math" other='exp="http://example.org/namespace"' /> >

h2. Language Attributes

@<txp:shs_language />@ generates the Attributes "xml:lang" and "lang" in XHTML mode and only "lang" in HTML mode.
The attributes value is set by the _lang_ attribute of <txp:shs_language />.

You can use this Tag in your <HTML> tag.

h3. Example

bc. <html <txp:shs_language lang="de"> >

h2. Meta Tag: Content Type

Most Pages contains an <META> tag declaring the mime typ. With <txp:shs_content_type /> such a tag is generated. 

In XHMTL mode it is *application/xhtml+xml*: @<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />@.

For Browsers acceptin HTML only it looks like @<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />@

If you using HTMl5 you can also generate the simplified version @<meta charset="UTF-8" />@ by using the Attribute _html5_ with a value of "1".

Use this tag in your <HEAD> tag.

h3. Examples

bc.. <head>
   <txp:shs_content_type />
</head>

bc.. <head>
   <txp:shs_content_type html5="1" />
</head>

h2. Conditional Tag

The content of the conditional tag <txp:shs_if_xhtml_accepted> is parsed if the user agend accept XHTML. 
 
h2. Example

bc.. <txp:shs_xml_declaration />

<txp:shs_doctype htmltype="html_4_strict"
	xhtmltype="xhtml_1_0_strict" />

<html <txp:shs_namespace svg="1" mathml="math" other='exp="http://example.org/namespace"' /> <txp:shs_language lang="de-DE" />>

<head>
   <txp:shs_content_type />
   ...
<head>
<body>
   ...
   <txp:shs_if_xhtml_accepted>
      ...
   <txp:else />
      ...
   <txp:shs_if_xhtml_accepted>
   ...
</body>
</html>

h2. Licence

This software is licensed under the following GPL license:

pre.  * Copyright 2011 Sebastian Spautz
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


# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

function shs_xml_declaration($atts) {
	if (!in_array('manipulateTypeCallback', ob_list_handlers() ))
		ob_start('manipulateTypeCallback');
	
	if ( isXhtmlAccepted() ) {
		return '<?xml version="1.0" encoding="utf-8" ?>';
	}
}

function shs_doctype($atts) {
	extract(lAtts(array(
		'htmltype' => 'html_4_strict',
		'xhtmltype' => 'xhtml_1_0_strict'), $atts));

	$docTypeDeclaration = array(
		'html_4_transitional' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"'.' \n\t'.'"http://www.w3.org/TR/html4/loose.dtd">',
		'html_4_strict' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'." \n\t".'"http://www.w3.org/TR/html4/strict.dtd">',
		'xhtml_1_0_transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'.' \n\t'.'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'xhtml_1_0_strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'." \n\t".'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'xhtml_1_1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"'.' \n\t'.'"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
		'xhtml_mathml_svg' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN"'." \n\t".'"http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">',
		'html5' => '<!DOCTYPE html>'
	);
	
	if ( isXhtmlAccepted() ) {
		return $docTypeDeclaration[$xhtmltype];
	}
	else {
		return $docTypeDeclaration[$htmltype];
	}
}

function shs_namespace($atts) {
	extract(lAtts(array(
		'mathml' => '0',
		'svg' => '0',
		'xlink' => '0',
		'other' => '' ), $atts));

	$namespaces = array(
		'mathml' => 'http://www.w3.org/1998/Math/MathML',
		'svg' => 'http://www.w3.org/2000/svg',
		'xhtml' => 'http://www.w3.org/1999/xhtml',
		'xlink' => 'http://www.w3.org/1999/xlink'
	);
	
	if ( isXhtmlAccepted() ) {
		$returnvalue = 'xmlns="'.$namespaces['xhtml'].'"';
		if ($mathml == '1') 
			$returnvalue = $returnvalue.' xmlns:m="'.$namespaces['mathml'].'"';
		else if ( $mathml != '0' )
			$returnvalue = $returnvalue.' xmlns:'.$mathml.'="'.$namespaces['mathml'].'"';
		if ($svg == '1') 
			$returnvalue = $returnvalue.' xmlns:svg="'.$namespaces['svg'].'"';
		else if ( $svg != '0' )
			$returnvalue = $returnvalue.' xmlns:'.$svg.'="'.$namespaces['svg'].'"';
		if ($xlink == '1') 
			$returnvalue = $returnvalue.' xmlns:xlink="'.$namespaces['xlink'].'"';
		else if ( $xlink != '0' )
			$returnvalue = $returnvalue.' xmlns:'.$xlink.'="'.$namespaces['xlink'].'"';
		else if ($svg != '0')
			$returnvalue = $returnvalue.' xmlns:xlink="'.$namespaces['xlink'].'"';
		$returnvalue = $returnvalue.' '.$other;
		
		return $returnvalue;
	}
	else {
		return '';
	}
}

function shs_language($atts) {
	extract(lAtts(array(
		'lang' => 'en'
	 ), $atts));
	
	if ( isXhtmlAccepted() ) {
		return 'lang="'.$lang.'" xml:lang="'.$lang.'"';
	}
	else {
		return 'lang="'.$lang.'"';
	}
}

function shs_content_type($atts) {
	extract(lAtts(array(
		'html5' => '0'
	 ), $atts));
	
	
	if ( isXhtmlAccepted() ) {
		if ( $html5 == '1' ) return '<meta charset="UTF-8" />';
		else return '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />';
	}
	else {
		if ( $html5 == '1' ) return '<meta charset="UTF-8">';
		else return '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	}
}

function shs_if_xhtml_accepted($atts, $thing) {
	return parse(EvalElse($thing, isXhtmlAccepted));
}

function isXhtmlAccepted() {
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
		return true;
	}
	else {
		return false;
	}
}

function manipulateTypeCallback($buffer) {	
	if ( isXhtmlAccepted() ) {
		header("content-type: application/xhtml+xml; charset=utf-8");
	}
	else {
		header("content-type: text/html; charset=utf-8");
	}
	return $buffer;
}

# --- END PLUGIN CODE ---
?>
