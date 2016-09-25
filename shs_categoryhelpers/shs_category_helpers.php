<?php
	// Copyright 2013, 2016 Sebastian Spautz
	
	// "Sebastians Textpattern Category Helpers" is free software: you can redistribute it and/or modify
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
$plugin['name'] = 'shs_category_helpers';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
$plugin['allow_html_help'] = 0;

$plugin['version'] = '1.1.0';
$plugin['author'] = 'Sebastian Spautz';
$plugin['author_uri'] = 'http://human-injection.de/';
$plugin['description'] = 'Helper-Tags to handle categories';

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

h1. Sebastians Textpattern Category Helpers (Version 1.1.0)

This plugin for Textpattern 4.5.4 defines some Tags to generate category relatet markup for my own Weblog human-injection.de.

Currently it generates a link for each ancestor of the two article categories and combine them.

h2. License

This software is licensed under the following GPL license:

pre.  * Copyright 2013 Sebastian Spautz
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

h2. ChangeLog
* _1.1.0:_ Register the custom tags to avoid a warning in debug mode of Textpattern
* _1.0.0:_ First Release
# --- END PLUGIN HELP ---

<?php
}

# --- BEGIN PLUGIN CODE ---
function shs_ancestors_categories($atts, $thing='') {
	//Works only for single articles
	if ( !$GLOBALS['is_article_list']  ) {
		global $pretext, $thisarticle;

		extract(lAtts(array(
			'class' => '',
			'link' => 1,
			'title' => 1,
			'section' => '',
			'this_section' => 0,
			'type' => 'article',
		), $atts));

		$category1 = ($thisarticle["category1"]) ? $thisarticle["category1"] : '';
		$category2 = ($thisarticle["category2"]) ? $thisarticle["category2"] : '';

		$out = '';

		if ($category1) {
		  $tree = getTreePath(doSlash($category1), $type);
		  for ($i = 1; $i < count($tree)-1; $i++) {
			 $out .= category(array(
			'class' => $class."_level".$tree[$i]["level"],
			'link' => 1,
			'title' => 1,
			'section' => $section,
			'this_section' => $this_section,
			'name' => $tree[$i]['name'],
			'type' => $type,), $thing);
			 $out .= " <span>⊇</span> ";
		  }
		  $out .= category(array(
			'class' => $class."_level".$tree[count($tree)-1]["level"],
			'link' => 1,
			'title' => 1,
			'section' => $section,
			'this_section' => $this_section,
			'name' => $tree[count($tree)-1]['name'],
			'type' => $type,), $thing);
		}

		if ($category1 && $category2) {
		  $out .= " <span>∩</span> ";
		}

		if ($category2) {
		  $tree = array_reverse(getTreePath(doSlash($category2), $type));
		  $out .= category(array(
			'class' => $class."_level".$tree[0]["level"],
			'link' => 1,
			'title' => 1,
			'section' => $section,
			'this_section' => $this_section,
			'name' => $tree[0]['name'],
			'type' => $type,), $thing);
		  for ($i = 1; $i < count($tree)-1; $i++) {
			 $out .=" <span>⊆</span> ";
			 $out .= category(array(
			'class' => $class."_level".$tree[$i]["level"],
			'link' => 1,
			'title' => 1,
			'section' => $section,
			'this_section' => $this_section,
			'name' => $tree[$i]['name'],
			'type' => $type,) , $thing);
		  }
		}

		return $out;
	}
} //end of function shs_ancestors_categories($atts)

if (class_exists('\Textpattern\Tag\Registry')) {
	Txp::get('\Textpattern\Tag\Registry')
		->register('shs_ancestors_categories')
	;
}
# --- END PLUGIN CODE ---
?>
