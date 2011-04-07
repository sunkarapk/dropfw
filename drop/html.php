<?php
/**
 * dropFW(tm) :  PHP Web Development Framework (http://www.suncoding.com)
 * Copyright 2010, Sun Web Dev, Inc.
 *
 * Licensed under The GPLv3 License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010, Sun Web Dev, Inc. (http://www.suncoding.com)
 * @version	1.0.0
 * @author	Pavan Kumar Sunkara
 * @license	GPLv3
 */

class Html extends Object {

/*************************************************************************
 * Public variables
 *************************************************************************/
/**#@+
 * @access public
 */
/**
 * html tags used by this helper.
 *
 * @var array
 */
	var $tags = array(
		'meta' => '<meta%s/>',
		'metalink' => '<link href="%s"%s/>',
		'link' => '<a href="%s"%s>%s</a>',
		'mailto' => '<a href="mailto:%s" %s>%s</a>',
		'form' => '<form %s>',
		'formend' => '</form>',
		'input' => '<input name="%s" %s/>',
		'textarea' => '<textarea name="%s" %s>%s</textarea>',
		'hidden' => '<input type="hidden" name="%s" %s/>',
		'checkbox' => '<input type="checkbox" name="%s" %s/>',
		'checkboxmultiple' => '<input type="checkbox" name="%s[]"%s />',
		'radio' => '<input type="radio" name="%s" id="%s" %s />%s',
		'selectstart' => '<select name="%s"%s>',
		'selectmultiplestart' => '<select name="%s[]"%s>',
		'selectempty' => '<option value=""%s>&nbsp;</option>',
		'selectoption' => '<option value="%s"%s>%s</option>',
		'selectend' => '</select>',
		'optiongroup' => '<optgroup label="%s"%s>',
		'optiongroupend' => '</optgroup>',
		'checkboxmultiplestart' => '',
		'checkboxmultipleend' => '',
		'password' => '<input type="password" name="%s" %s/>',
		'file' => '<input type="file" name="%s" %s/>',
		'file_no_model' => '<input type="file" name="%s" %s/>',
		'submit' => '<input type="submit" %s/>',
		'submitimage' => '<input type="image" src="%s" %s/>',
		'button' => '<input type="%s" %s/>',
		'image' => '<img src="%s" %s/>',
		'tableheader' => '<th%s>%s</th>',
		'tableheaderrow' => '<tr%s>%s</tr>',
		'tablecell' => '<td%s>%s</td>',
		'tablerow' => '<tr%s>%s</tr>',
		'block' => '<div%s>%s</div>',
		'blockstart' => '<div%s>',
		'blockend' => '</div>',
		'tag' => '<%s%s>%s</%s>',
		'tagstart' => '<%s%s>',
		'tagend' => '</%s>',
		'para' => '<p%s>%s</p>',
		'parastart' => '<p%s>',
		'label' => '<label for="%s"%s>%s</label>',
		'fieldset' => '<fieldset%s>%s</fieldset>',
		'fieldsetstart' => '<fieldset><legend>%s</legend>',
		'fieldsetend' => '</fieldset>',
		'legend' => '<legend>%s</legend>',
		'css' => '<link rel="%s" type="text/css" href="%s" %s/>',
		'style' => '<style type="text/css"%s>%s</style>',
		'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
		'ul' => '<ul%s>%s</ul>',
		'ol' => '<ol%s>%s</ol>',
		'li' => '<li%s>%s</li>',
		'error' => '<div%s>%s</div>',
		'javascriptblock' => '<script type="text/javascript"%s>%s</script>',
		'javascriptstart' => '<script type="text/javascript">',
		'javascriptlink' => '<script type="text/javascript" src="%s"%s></script>',
		'javascriptend' => '</script>'
	);

/**
 * Parameter array.
 *
 * @var array
 */
	var $params = array();
/**
 * Enter description here...
 *
 * @var array
 */
	var $data = null;

/**
 * Document type definitions
 *
 * @var	array
 * @access private
 */
	var $__docTypes = array(
		'html4-strict'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
		'html4-trans'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
		'html4-frame'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
		'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'xhtml-trans' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'xhtml-frame' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'xhtml11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
	);

/**
 * Returns a doctype string.
 *
 * Possible doctypes:
 *   + html4-strict:  HTML4 Strict.
 *   + html4-trans:  HTML4 Transitional.
 *   + html4-frame:  HTML4 Frameset.
 *   + xhtml-strict: XHTML1 Strict.
 *   + xhtml-trans: XHTML1 Transitional.
 *   + xhtml-frame: XHTML1 Frameset.
 *   + xhtml11: XHTML1.1.
 *
 * @param  string $type Doctype to use.
 * @return string Doctype.
 */
	function docType($type = 'xhtml-strict') {
		if (isset($this->__docTypes[$type])) {
			return $this->__docTypes[$type];
		}
		return null;
	}
/**
 * Creates a link to an external resource and handles basic meta tags
 *
 * @param  string  $type The title of the external resource
 * @param  mixed   $url   The address of the external resource or string for content attribute
 * @param  array   $attributes Other attributes for the generated tag. If the type attribute is html, rss, atom, or icon, the mime-type is returned.
 * @param  boolean $inline If set to false, the generated tag appears in the head tag of the layout.
 * @return string
 */
	function meta($type, $url = null, $attributes = array()) {
		if (!is_array($type)) {
			$types = array(
				'rss'	=> array('type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => $type, 'link' => $url),
				'atom'	=> array('type' => 'application/atom+xml', 'title' => $type, 'link' => $url),
				'icon'	=> array('type' => 'image/x-icon', 'rel' => 'icon', 'link' => $url),
				'keywords' => array('name' => 'keywords', 'content' => $url),
				'description' => array('name' => 'description', 'content' => $url),
			);
			
			if ($type === 'icon' && $url === null) {
				$types['icon']['link'] = WEBIMG.'favicon.ico';
			}

			if (isset($types[$type])) {
				$type = $types[$type];
			} elseif (!isset($attributes['type']) && $url !== null) {
				if (is_array($url) && isset($url['ext'])) {
					$type = $types[$url['ext']];
				} else {
					$type = $types['rss'];
				}
			} elseif (isset($attributes['type']) && isset($types[$attributes['type']])) {
				$type = $types[$attributes['type']];
				unset($attributes['type']);
			} else {
				$type = array();
			}
		} elseif ($url !== null) {
			$inline = $url;
		}
		$attributes = array_merge($type, $attributes);
		$out = null;

		if (isset($attributes['link'])) {
			if (isset($attributes['rel']) && $attributes['rel'] === 'icon') {
				$out = sprintf($this->tags['metalink'], $attributes['link'], $this->_parseAttributes($attributes, array('link'), ' ', ' '));
				$attributes['rel'] = 'shortcut icon';
			} else {
				$attributes['link'] = $this->url($attributes['link'], true);
			}
			$out .= sprintf($this->tags['metalink'], $attributes['link'], $this->_parseAttributes($attributes, array('link'), ' ', ' '));
		} else {
			$out = sprintf($this->tags['meta'], $this->_parseAttributes($attributes, array('type')));
		}

		return $out;
	}
/**
 * Returns a charset META-tag.
 *
 * @param  string  $charset The character set to be used in the meta tag. Example: "utf-8".
 * @return string A meta tag containing the specified character set.
 */
	function charset($charset = "UTF-8") {
		return sprintf($this->tags['charset'], (!empty($charset) ? $charset : 'utf-8'));
	}
/**
 * Creates an HTML link.
 *
 * If $url starts with "http://" this is treated as an external link. Else,
 * it is treated as a path to controller/action and parsed
 *
 * If the $url is empty, $title is used instead.
 *
 * @param  string  $title The content to be wrapped by <a> tags.
 * @param  mixed   $url relative URL or array of URL parameters, or external URL (starts with http://)
 * @param  array   $htmlAttributes Array of HTML attributes.
 * @param  string  $confirmMessage JavaScript confirmation message.
 * @param  boolean $escapeTitle	Whether or not $title should be HTML escaped.
 * @return string	An <a /> element.
 */
	function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if ($url !== null) {
			$url = $this->url($url);
		} else {
			$url = $this->url($title);
			$title = $url;
			$escapeTitle = false;
		}

		if (isset($htmlAttributes['escape']) && $escapeTitle == true) {
			$escapeTitle = $htmlAttributes['escape'];
		}

		if ($escapeTitle === true) {
			$title = Sanitize::hsc($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($htmlAttributes['confirm'])) {
			$confirmMessage = $htmlAttributes['confirm'];
			unset($htmlAttributes['confirm']);
		}
		if ($confirmMessage) {
			$confirmMessage = str_replace("'", "\'", $confirmMessage);
			$confirmMessage = str_replace('"', '\"', $confirmMessage);
			$htmlAttributes['onclick'] = "return confirm('{$confirmMessage}');";
		} elseif (isset($htmlAttributes['default']) && $htmlAttributes['default'] == false) {
			if (isset($htmlAttributes['onclick'])) {
				$htmlAttributes['onclick'] .= ' event.returnValue = false; return false;';
			} else {
				$htmlAttributes['onclick'] = 'event.returnValue = false; return false;';
			}
			unset($htmlAttributes['default']);
		}
		return sprintf($this->tags['link'], $url, $this->_parseAttributes($htmlAttributes), $title);
	}
/**
 * Creates a link element for CSS stylesheets.
 *
 * @param mixed $path The name of a CSS style sheet or an array containing names of
 *   CSS stylesheets. If `$path` is prefixed with '/', the path will be relative to the webroot
 *   of your application. Otherwise, the path will be relative to your CSS path, usually webroot/css.
 * @param string $rel Rel attribute. Defaults to "stylesheet". If equal to 'import' the stylesheet will be imported.
 * @param array $htmlAttributes Array of HTML attributes.
 * @param boolean $inline If set to false, the generated tag appears in the head tag of the layout.
 * @return string CSS <link /> or <style /> tag, depending on the type of link.
 */
	function css($path, $rel = null, $htmlAttributes = array()) {
		if (is_array($path)) {
			$out = '';
			foreach ($path as $i) {
				$out .= "\n\t" . $this->css($i, $rel, $htmlAttributes);
			}
			if ($inline)  {
				return $out . "\n";
			}
			return;
		}

		if (strpos($path, '://') !== false) {
			$url = $path;
		} else {
			$url = WEBCSS.$path.'.css';
		}

		if ($rel == 'import') {
			$out = sprintf($this->tags['style'], $this->_parseAttributes($htmlAttributes, null, '', ' '), '@import url(' . $url . ');');
		} else {
			if ($rel == null) {
				$rel='stylesheet';
			}
			$out = sprintf($this->tags['css'], $rel, $url, $this->_parseAttributes($htmlAttributes, null, '', ' '));
		}

		return $out;
	}

/**
 * Returns one or many `<script>` tags depending on the number of scripts given.
 *
 * If the filename is prefixed with "/", the path will be relative to the base path of your
 * application.  Otherwise, the path will be relative to your JavaScript path, usually webroot/js.
 *
 * Can include one or many Javascript files.
 *
 * ### Options
 *
 * - `inline` - Whether script should be output inline or into scripts_for_layout.
 * - `once` - Whether or not the script should be checked for uniqueness. If true scripts will only be
 *   included once, use false to allow the same script to be included more than once per request.
 *
 * @param mixed $url String or array of javascript files to include
 * @param mixed $options Array of options, and html attributes see above. If boolean sets $options['inline'] = value
 * @return mixed String of `<script />` tags or null if $inline is false or if $once is true and the file has been
 *   included before.
 * @access public
 * @link http://book.cakephp.org/view/1589/script
 */
	function script($url, $options = array()) {
		$options = array_merge(array('once' => true), $options);
		if (is_array($url)) {
			$out = '';
			foreach ($url as $i) {
				$out .= "\n\t" . $this->script($i, $options);
			}
			return null;
		}
		if ($options['once'] && isset($this->__includedScripts[$url])) {
			return null;
		}
		$this->__includedScripts[$url] = true;

		if (strpos($url, '://') === false) {
			$url = WEBJS.$url.'.js';
		}

		$attributes = $this->_parseAttributes($options, array('once'), ' ');
		$out = sprintf($this->tags['javascriptlink'], $url, $attributes);

		return $out;
	}

/**
 * Wrap $script in a script tag.
 *
 * ### Options
 *
 * - `safe` (boolean) Whether or not the $script should be wrapped in <![CDATA[ ]]>
 * - `inline` (boolean) Whether or not the $script should be added to $scripts_for_layout or output inline
 *
 * @param string $script The script to wrap
 * @param array $options The options to use.
 * @return mixed string or null depending on the value of `$options['inline']`
 * @access public
 */
	function scriptBlock($script, $options = array()) {
		$options += array('safe' => true);
		if ($options['safe']) {
			$script  = "\n" . '//<![CDATA[' . "\n" . $script . "\n" . '//]]>' . "\n";
		}
		unset($options['safe']);
		$attributes = $this->_parseAttributes($options, ' ', ' ');

		return sprintf($this->tags['javascriptblock'], $attributes, $script);
	}

/**
 * Begin a script block that captures output until HtmlHelper::scriptEnd()
 * is called. This capturing block will capture all output between the methods
 * and create a scriptBlock from it.
 *
 * ### Options
 *
 * - `safe` Whether the code block should contain a CDATA
 * - `inline` Should the generated script tag be output inline or in `$scripts_for_layout`
 *
 * @param array $options Options for the code block.
 * @return void
 * @access public
 */
	function scriptStart($options = array()) {
		$options += array('safe' => true);
		$this->_scriptBlockOptions = $options;
		ob_start();
		return null;
	}

/**
 * End a Buffered section of Javascript capturing.
 * Generates a script tag inline or in `$scripts_for_layout` depending on the settings
 * used when the scriptBlock was started
 *
 * @return mixed depending on the settings of scriptStart() either a script tag or null
 * @access public
 */
	function scriptEnd() {
		$buffer = ob_get_clean();
		$options = $this->_scriptBlockOptions;
		$this->_scriptBlockOptions = array();
		return $this->scriptBlock($buffer, $options);
	}

/**
 * Builds CSS style data from an array of CSS properties
 *
 * @param array $data Style data array
 * @param boolean $inline Whether or not the style block should be displayed inline
 * @return string CSS styling data
 */
	function style($data, $inline = true) {
		if (!is_array($data)) {
			return $data;
		}
		$out = array();
		foreach ($data as $key=> $value) {
			$out[] = $key.':'.$value.';';
		}
		if ($inline) {
			return implode(' ', $out);
		}
		return implode("\n", $out);
	}

/**
 * Creates a formatted IMG element.
 *
 * @param string $path Path to the image file, relative to the app/webroot/img/ directory.
 * @param array	$options Array of HTML attributes.
 * @return string
 */
	function image($path, $options = array()) {
		if (strpos($path, '://') === false) {
			if ($path[0] !== '/') {
				$path = WEBIMG . $path;
			}
		}

		if (!isset($options['alt'])) {
			$options['alt'] = '';
		}

		$url = false;
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}

		$image = sprintf($this->tags['image'], $path, $this->_parseAttributes($options, null, '', ' '));
		if ($url) {
			return sprintf($this->tags['link'], $this->url($url), null, $image);
		}
		return $image;
	}
/**
 * Returns a row of formatted and named TABLE headers.
 *
 * @param array $names		Array of tablenames.
 * @param array $trOptions	HTML options for TR elements.
 * @param array $thOptions	HTML options for TH elements.
 * @return string
 */
	function tableHeaders($names, $trOptions = null, $thOptions = null) {
		$out = array();
		foreach ($names as $arg) {
			$out[] = sprintf($this->tags['tableheader'], $this->_parseAttributes($thOptions), $arg);
		}
		return sprintf($this->tags['tablerow'], $this->_parseAttributes($trOptions), implode(' ', $out));
	}
/**
 * Returns a formatted string of table rows (TR's with TD's in them).
 *
 * @param array $data		Array of table data
 * @param array $oddTrOptions HTML options for odd TR elements if true useCount is used
 * @param array $evenTrOptions HTML options for even TR elements
 * @param bool $useCount adds class "column-$i"
 * @param bool $continueOddEven If false, will use a non-static $count variable, so that the odd/even count is reset to zero just for that call
 * @return string	Formatted HTML
 */
	function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true) {
		if (empty($data[0]) || !is_array($data[0])) {
			$data = array($data);
		}

		if ($oddTrOptions === true) {
			$useCount = true;
			$oddTrOptions = null;
		}

		if ($evenTrOptions === false) {
			$continueOddEven = false;
			$evenTrOptions = null;
		}

		if ($continueOddEven) {
			static $count = 0;
		} else {
			$count = 0;
		}

		foreach ($data as $line) {
			$count++;
			$cellsOut = array();
			$i = 0;
			foreach ($line as $cell) {
				$cellOptions = array();

				if (is_array($cell)) {
					$cellOptions = $cell[1];
					$cell = $cell[0];
				} elseif ($useCount) {
					$cellOptions['class'] = 'column-' . ++$i;
				}
				$cellsOut[] = sprintf($this->tags['tablecell'], $this->_parseAttributes($cellOptions), $cell);
			}
			$options = $this->_parseAttributes($count % 2 ? $oddTrOptions : $evenTrOptions);
			$out[] = sprintf($this->tags['tablerow'], $options, implode(' ', $cellsOut));
		}
		return implode("\n", $out);
	}
/**
 * Returns a formatted block tag, i.e DIV, SPAN, P.
 *
 * @param string $name Tag name.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $attributes Additional HTML attributes of the DIV tag
 * @param boolean $escape If true, $text will be HTML-escaped
 * @return string The formatted tag element
 */
	function tag($name, $text = null, $attributes = array(), $escape = false) {
		if ($escape) {
			$text = h($text);
		}
		if (!is_array($attributes)) {
			$attributes = array('class' => $attributes);
		}
		if ($text === null) {
			$tag = 'tagstart';
		} else {
			$tag = 'tag';
		}
		return sprintf($this->tags[$tag], $name, $this->_parseAttributes($attributes, null, ' ', ''), $text, $name);
	}
/**
 * Returns a formatted DIV tag for HTML FORMs.
 *
 * @param string $class CSS class name of the div element.
 * @param string $text String content that will appear inside the div element.
 *   If null, only a start tag will be printed
 * @param array $attributes Additional HTML attributes of the DIV tag
 * @param boolean $escape If true, $text will be HTML-escaped
 * @return string The formatted DIV element
 */
	function div($class = null, $text = null, $attributes = array(), $escape = false) {
		if ($class != null && !empty($class)) {
			$attributes['class'] = $class;
		}
		return $this->tag('div', $text, $attributes, $escape);
	}
/**
 * Returns a formatted P tag.
 *
 * @param string $class CSS class name of the p element.
 * @param string $text String content that will appear inside the p element.
 * @param array $attributes Additional HTML attributes of the P tag
 * @param boolean $escape If true, $text will be HTML-escaped
 * @return string The formatted P element
 */
	function para($class, $text, $attributes = array(), $escape = false) {
		if ($escape) {
			$text = h($text);
		}
		if ($class != null && !empty($class)) {
			$attributes['class'] = $class;
		}
		if ($text === null) {
			$tag = 'parastart';
		} else {
			$tag = 'para';
		}
		return sprintf($this->tags[$tag], $this->_parseAttributes($attributes, null, ' ', ''), $text);
	}
/**
 * Build a nested list (UL/OL) out of an associative array.
 *
 * @param array $list Set of elements to list
 * @param array $attributes Additional HTML attributes of the list (ol/ul) tag or if ul/ol use that as tag
 * @param array $itemAttributes Additional HTML attributes of the list item (LI) tag
 * @param string $tag Type of list tag to use (ol/ul)
 * @return string The nested list
 * @access public
 */
	function nestedList($list, $attributes = array(), $itemAttributes = array(), $tag = 'ul') {
		if (is_string($attributes)) {
			$tag = $attributes;
			$attributes = array();
		}
		$items = $this->__nestedListItem($list, $attributes, $itemAttributes, $tag);
		return sprintf($this->tags[$tag], $this->_parseAttributes($attributes, null, ' ', ''), $items);
	}
/**
 * Internal function to build a nested list (UL/OL) out of an associative array.
 *
 * @param array $list Set of elements to list
 * @param array $attributes Additional HTML attributes of the list (ol/ul) tag
 * @param array $itemAttributes Additional HTML attributes of the list item (LI) tag
 * @param string $tag Type of list tag to use (ol/ul)
 * @return string The nested list element
 * @access private
 * @see nestedList()
 */
	function __nestedListItem($items, $attributes, $itemAttributes, $tag) {
		$out = '';

		$index = 1;
		foreach ($items as $key => $item) {
			if (is_array($item)) {
				$item = $key . $this->nestedList($item, $attributes, $itemAttributes, $tag);
			}
			if (isset($itemAttributes['even']) && $index % 2 == 0) {
				$itemAttributes['class'] = $itemAttributes['even'];
			} else if (isset($itemAttributes['odd']) && $index % 2 != 0) {
				$itemAttributes['class'] = $itemAttributes['odd'];
			}
			$out .= sprintf($this->tags['li'], $this->_parseAttributes(array_diff_key($itemAttributes, array_flip(array('even', 'odd'))), null, ' ', ''), $item);
			$index++;
		}
		return $out;
	}

/**
 * Returns a space-delimited string with items of the $options array. If a
 * key of $options array happens to be one of:
 *	+ 'compact'
 *	+ 'checked'
 *	+ 'declare'
 *	+ 'readonly'
 *	+ 'disabled'
 *	+ 'selected'
 *	+ 'defer'
 *	+ 'ismap'
 *	+ 'nohref'
 *	+ 'noshade'
 *	+ 'nowrap'
 *	+ 'multiple'
 *	+ 'noresize'
 *
 * And its value is one of:
 *	+ 1
 *	+ true
 *	+ 'true'
 *
 * Then the value will be reset to be identical with key's name.
 * If the value is not one of these 3, the parameter is not output.
 *
 * @param  array  $options Array of options.
 * @param  array  $exclude Array of options to be excluded.
 * @param  string $insertBefore String to be inserted before options.
 * @param  string $insertAfter  String to be inserted ater options.
 * @return string
 */
	function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null) {
		if (is_array($options)) {
			$options = array_merge(array('escape' => true), $options);

			if (!is_array($exclude)) {
				$exclude = array();
			}
			$keys = array_diff(array_keys($options), array_merge((array)$exclude, array('escape')));
			$values = array_intersect_key(array_values($options), $keys);
			$escape = $options['escape'];
			$attributes = array();

			foreach ($keys as $index => $key) {
				$attributes[] = $this->__formatAttribute($key, $values[$index], $escape);
			}
			$out = implode(' ', $attributes);
		} else {
			$out = $options;
		}
		return $out ? $insertBefore . $out . $insertAfter : '';
	}
/**
 * @param  string $key
 * @param  string $value
 * @return string
 * @access private
 */
	function __formatAttribute($key, $value, $escape = true) {
		$attribute = '';
		$attributeFormat = '%s="%s"';
		$minimizedAttributes = array('compact', 'checked', 'declare', 'readonly', 'disabled', 'selected', 'defer', 'ismap', 'nohref', 'noshade', 'nowrap', 'multiple', 'noresize');
		if (is_array($value)) {
			$value = '';
		}
		
		if (in_array($key, $minimizedAttributes)) {
			if ($value === 1 || $value === true || $value === 'true' || $value == $key) {
				$attribute = sprintf($attributeFormat, $key, $key);
			}
		} else {
			$attribute = sprintf($attributeFormat, $key, ($escape ? htmlspecialchars($value, ENT_QUOTES) : $value));
		}
		return $attribute;
	}

/**
 * @param string url
 * @return string url
 */
	function url($url, $full=true) {
		if(is_array($url)) {
			$str = BASE;
			if(isset($url['controller'])) {
				$str.= DS.$url['controller'];
				array_shift($url);
			} else
				$str.= DS.CONTROLLER;
			if(isset($url['action'])) {
				$str.= DS.$url['action'];
				array_shift($url);
			} else
				$str.= DS.ACTION;
			if(count($url)>0)
				$str.= DS.implode('/',$url);
			return $str;
		} else if (substr($url, 0, 7) == 'http://') {
			return $url;
		} else {
			return BASE.$url;
		}
	}

}

?>
