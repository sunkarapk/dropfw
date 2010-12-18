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

class I18n extends Object {

/**
 * Instance of the L10n class for localization
 *
 * @var L10n
 * @access public
 */
	var $l10n = null;

/**
 * Current domain of translation
 *
 * @var string
 * @access public
 */
	var $domain = null;

/**
 * Current language used for translations
 *
 * @var string
 * @access private
 */
	var $__lang = null;

/**
 * Translation strings for a specific domain read from the .mo or .po files
 *
 * @var array
 * @access private
 */
	var $__domains = array();

/**
 * Set to true when I18N::__bindTextDomain() is called for the first time.
 * If a translation file is found it is set to false again
 *
 * @var boolean
 * @access private
 */
	var $__noLocale = false;

/**
 * Return a static instance of the I18n class
 *
 * @return object I18n
 * @access public
 */
	function __construct() {
		$this->l10n = new L10n();
	}

/**
 * Used by the translation functions in basics.php
 * Can also be used like I18n::translate();
 *
 * @param string $singular String to translate
 * @param string $plural Plural string (if any)
 * @param string $domain Domain The domain of the translation.  Domains are often used by plugin translations
 * @param string $category Category The integer value of the category to use.
 * @param integer $count Count Count is used with $plural to choose the correct plural form.
 * @return string translated string.
 * @access public
 */
	function translate($singular, $plural = null, $domain = null, $count = null) {
		if (strpos($singular, "\r\n") !== false) {
			$singular = str_replace("\r\n", "\n", $singular);
		}
		if ($plural !== null && strpos($plural, "\r\n") !== false) {
			$plural = str_replace("\r\n", "\n", $plural);
		}

		$language = Configure::read('Config.language');

		if (($this->__lang && $this->__lang !== $language) || !$this->__lang) {
			$lang = $this->l10n->get($language);
			$this->__lang = $lang;
		}

		if (is_null($domain)) {
			$domain = 'default';
		}
		// $this->domain = "eng/default"
		$this->domain = $this->l10n->locale . DS . $domain;

		if (!isset($this->__domains[$this->__lang][$domain])) {
			$this->__bindTextDomain($domain);
		}

		if (!isset($count)) {
			$plurals = 0;
		} elseif (!empty($this->__domains[$this->__lang][$domain]["%plural-c"]) && $this->__noLocale === false) {
			$header = $this->__domains[$this->__lang][$domain]["%plural-c"];
			$plurals = $this->__pluralGuess($header, $count);
		} else {
			if ($count != 1) {
				$plurals = 1;
			} else {
				$plurals = 0;
			}
		}

		if (!empty($this->__domains[$this->__lang][$domain][$singular])) {
			if (($trans = $this->__domains[$this->__lang][$domain][$singular]) || ($plurals) && ($trans = $this->__domains[$this->__lang][$domain][$plural])) {
				if (is_array($trans)) {
					if (isset($trans[$plurals])) {
						$trans = $trans[$plurals];
					}
				}
				if (strlen($trans)) {
					return $trans;
				}
			}
		}

		if (!empty($plurals)) {
			return $plural;
		}
		return $singular;
	}

/**
 * Attempts to find the plural form of a string.
 *
 * @param string $header Type
 * @param integrer $n Number
 * @return integer plural match
 * @access private
 */
	function __pluralGuess($header, $n) {
		if (!is_string($header) || $header === "nplurals=1;plural=0;" || !isset($header[0])) {
			return 0;
		}

		if ($header === "nplurals=2;plural=n!=1;") {
			return $n != 1 ? 1 : 0;
		} elseif ($header === "nplurals=2;plural=n>1;") {
			return $n > 1 ? 1 : 0;
		}

		if (strpos($header, "plurals=3")) {
			if (strpos($header, "100!=11")) {
				if (strpos($header, "10<=4")) {
					return $n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);
				} elseif (strpos($header, "100<10")) {
					return $n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);
				}
				return $n % 10 == 1 && $n % 100 != 11 ? 0 : ($n != 0 ? 1 : 2);
			} elseif (strpos($header, "n==2")) {
				return $n == 1 ? 0 : ($n == 2 ? 1 : 2);
			} elseif (strpos($header, "n==0")) {
				return $n == 1 ? 0 : ($n == 0 || ($n % 100 > 0 && $n % 100 < 20) ? 1 : 2);
			} elseif (strpos($header, "n>=2")) {
				return $n == 1 ? 0 : ($n >= 2 && $n <= 4 ? 1 : 2);
			} elseif (strpos($header, "10>=2")) {
				return $n == 1 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);
			}
			return $n % 10 == 1 ? 0 : ($n % 10 == 2 ? 1 : 2);
		} elseif (strpos($header, "plurals=4")) {
			if (strpos($header, "100==2")) {
				return $n % 100 == 1 ? 0 : ($n % 100 == 2 ? 1 : ($n % 100 == 3 || $n % 100 == 4 ? 2 : 3));
			} elseif (strpos($header, "n>=3")) {
				return $n == 1 ? 0 : ($n == 2 ? 1 : ($n == 0 || ($n >= 3 && $n <= 10) ? 2 : 3));
			} elseif (strpos($header, "100>=1")) {
				return $n == 1 ? 0 : ($n == 0 || ($n % 100 >= 1 && $n % 100 <= 10) ? 1 : ($n % 100 >= 11 && $n % 100 <= 20 ? 2 : 3));
			}
		} elseif (strpos($header, "plurals=5")) {
			return $n == 1 ? 0 : ($n == 2 ? 1 : ($n >= 3 && $n <= 6 ? 2 : ($n >= 7 && $n <= 10 ? 3 : 4)));
		}
	}

/**
 * Binds the given domain to a file in the specified directory.
 *
 * @param string $domain Domain to bind
 * @return string Domain binded
 * @access private
 */
	function __bindTextDomain($domain) {
		$this->__noLocale = true;
		$merge = array();

		foreach ($this->l10n->languagePath as $lang) {
			$file = LOCALE.$lang.DS.$domain;
			$localeDef = LOCALE.$lang;

			if (file_exists($fn = "$file.mo")) {
				$this->__loadMo($fn, $domain);
				$this->__noLocale = false;
				break;
			} elseif (file_exists($fn = "$file.po") && ($f = fopen($fn, "r"))) {
				$this->__loadPo($f, $domain);
				$this->__noLocale = false;
				break;
			} elseif (is_file($localeDef) && ($f = fopen($localeDef, "r"))) {
				$this->__loadLocaleDefinition($f, $domain);
				$this->__noLocale = false;
				return $domain;
			}
		}

		if (empty($this->__domains[$this->__lang][$domain])) {
			$this->__domains[$this->__lang][$domain] = array();
			return $domain;
		}

		if ($head = $this->__domains[$this->__lang][$domain][""]) {
			foreach (explode("\n", $head) as $line) {
				$header = strtok($line,":");
				$line = trim(strtok("\n"));
				$this->__domains[$this->__lang][$domain]["%po-header"][strtolower($header)] = $line;
			}

			if (isset($this->__domains[$this->__lang][$domain]["%po-header"]["plural-forms"])) {
				$switch = preg_replace("/(?:[() {}\\[\\]^\\s*\\]]+)/", "", $this->__domains[$this->__lang][$domain]["%po-header"]["plural-forms"]);
				$this->__domains[$this->__lang][$domain]["%plural-c"] = $switch;
				unset($this->__domains[$this->__lang][$domain]["%po-header"]);
			}
			$this->__domains = Set::pushDiff($this->__domains, $merge);

			if (isset($this->__domains[$this->__lang][$domain][null])) {
				unset($this->__domains[$this->__lang][$domain][null]);
			}
		}
		return $domain;
	}

/**
 * Loads the binary .mo file for translation and sets the values for this translation in the var I18n::__domains
 *
 * @param resource $file Binary .mo file to load
 * @param string $domain Domain where to load file in
 * @access private
 */
	function __loadMo($file, $domain) {
		$data = file_get_contents($file);

		if ($data) {
			$header = substr($data, 0, 20);
			$header = unpack("L1magic/L1version/L1count/L1o_msg/L1o_trn", $header);
			extract($header);

			if ((dechex($magic) == '950412de' || dechex($magic) == 'ffffffff950412de') && $version == 0) {
				for ($n = 0; $n < $count; $n++) {
					$r = unpack("L1len/L1offs", substr($data, $o_msg + $n * 8, 8));
					$msgid = substr($data, $r["offs"], $r["len"]);
					unset($msgid_plural);

					if (strpos($msgid, "\000")) {
						list($msgid, $msgid_plural) = explode("\000", $msgid);
					}
					$r = unpack("L1len/L1offs", substr($data, $o_trn + $n * 8, 8));
					$msgstr = substr($data, $r["offs"], $r["len"]);

					if (strpos($msgstr, "\000")) {
						$msgstr = explode("\000", $msgstr);
					}
					$this->__domains[$this->__lang][$domain][$msgid] = $msgstr;

					if (isset($msgid_plural)) {
						$this->__domains[$this->__lang][$domain][$msgid_plural] =& $this->__domains[$this->__lang][$domain][$msgid];
					}
				}
			}
		}
	}

/**
 * Loads the text .po file for translation and sets the values for this translation in the var I18n::__domains
 *
 * @param resource $file Text .po file to load
 * @param string $domain Domain to load file in
 * @return array Binded domain elements
 * @access private
 */
	function __loadPo($file, $domain) {
		$type = 0;
		$translations = array();
		$translationKey = "";
		$plural = 0;
		$header = "";

		do {
			$line = trim(fgets($file));
			if ($line == "" || $line[0] == "#") {
				continue;
			}
			if (preg_match("/msgid[[:space:]]+\"(.+)\"$/i", $line, $regs)) {
				$type = 1;
				$translationKey = stripcslashes($regs[1]);
			} elseif (preg_match("/msgid[[:space:]]+\"\"$/i", $line, $regs)) {
				$type = 2;
				$translationKey = "";
			} elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && ($type == 1 || $type == 2 || $type == 3)) {
				$type = 3;
				$translationKey .= stripcslashes($regs[1]);
			} elseif (preg_match("/msgstr[[:space:]]+\"(.+)\"$/i", $line, $regs) && ($type == 1 || $type == 3) && $translationKey) {
				$translations[$translationKey] = stripcslashes($regs[1]);
				$type = 4;
			} elseif (preg_match("/msgstr[[:space:]]+\"\"$/i", $line, $regs) && ($type == 1 || $type == 3) && $translationKey) {
				$type = 4;
				$translations[$translationKey] = "";
			} elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 4 && $translationKey) {
				$translations[$translationKey] .= stripcslashes($regs[1]);
			} elseif (preg_match("/msgid_plural[[:space:]]+\".*\"$/i", $line, $regs)) {
				$type = 6;
			} elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 6 && $translationKey) {
				$type = 6;
			} elseif (preg_match("/msgstr\[(\d+)\][[:space:]]+\"(.+)\"$/i", $line, $regs) && ($type == 6 || $type == 7) && $translationKey) {
				$plural = $regs[1];
				$translations[$translationKey][$plural] = stripcslashes($regs[2]);
				$type = 7;
			} elseif (preg_match("/msgstr\[(\d+)\][[:space:]]+\"\"$/i", $line, $regs) && ($type == 6 || $type == 7) && $translationKey) {
				$plural = $regs[1];
				$translations[$translationKey][$plural] = "";
				$type = 7;
			} elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 7 && $translationKey) {
				$translations[$translationKey][$plural] .= stripcslashes($regs[1]);
			} elseif (preg_match("/msgstr[[:space:]]+\"(.+)\"$/i", $line, $regs) && $type == 2 && !$translationKey) {
				$header .= stripcslashes($regs[1]);
				$type = 5;
			} elseif (preg_match("/msgstr[[:space:]]+\"\"$/i", $line, $regs) && !$translationKey) {
				$header = "";
				$type = 5;
			} elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 5) {
				$header .= stripcslashes($regs[1]);
			} else {
				unset($translations[$translationKey]);
				$type = 0;
				$translationKey = "";
				$plural = 0;
			}
		} while (!feof($file));
		fclose($file);
		$merge[""] = $header;
		return $this->__domains[$this->__lang][$domain] = array_merge($merge ,$translations);
	}

/**
 * Parses a locale definition file following the POSIX standard
 *
 * @param resource $file file handler
 * @param string $domain Domain where locale definitions will be stored
 * @return void
 * @access private
 */
	function __loadLocaleDefinition($file, $domain = null) {
		$comment = '#';
		$escape = '\\';
		$currentToken = false;
		$value = '';
		while ($line = fgets($file)) {
			$line = trim($line);
			if (empty($line) || $line[0] === $comment) {
				continue;
			}
			$parts = preg_split("/[[:space:]]+/",$line);
			if ($parts[0] === 'comment_char') {
				$comment = $parts[1];
				continue;
			}
			if ($parts[0] === 'escape_char') {
				$escape = $parts[1];
				continue;
			}
			$count = count($parts);
			if ($count == 2) {
				$currentToken = $parts[0];
				$value = $parts[1];
			} elseif ($count == 1) {
				$value .= $parts[0];
			} else {
				continue;
			}

			$len = strlen($value) - 1;
			if ($value[$len] === $escape) {
				$value = substr($value, 0, $len);
				continue;
			}

			$mustEscape = array($escape . ',' , $escape . ';', $escape . '<', $escape . '>', $escape . $escape);
			$replacements = array_map('crc32', $mustEscape);
			$value = str_replace($mustEscape, $replacements, $value);
			$value = explode(';', $value);
			$this->__escape = $escape;
			foreach ($value as $i => $val) {
				$val = trim($val, '"');
				$val = preg_replace_callback('/(?:<)?(.[^>]*)(?:>)?/', array(&$this, '__parseLiteralValue'), $val);
				$val = str_replace($replacements, $mustEscape, $val);
				$value[$i] = $val;
			}
			if (count($value) == 1) {
				$this->__domains[$this->__lang][$domain][$currentToken] = array_pop($value);
			} else {
				$this->__domains[$this->__lang][$domain][$currentToken] = $value;
			}
		}
	}

/**
 * Auxiliary function to parse a symbol from a locale definition file
 *
 * @param string $string Symbol to be parsed
 * @return string parsed symbol
 * @access private
 */
	function __parseLiteralValue($string) {
		$string = $string[1];
		if (substr($string, 0, 2) === $this->__escape . 'x') {
			$delimiter = $this->__escape . 'x';
			return join('', array_map('chr', array_map('hexdec',array_filter(explode($delimiter, $string)))));
		}
		if (substr($string, 0, 2) === $this->__escape . 'd') {
			$delimiter = $this->__escape . 'd';
			return join('', array_map('chr', array_filter(explode($delimiter, $string))));
		}
		if ($string[0] === $this->__escape && isset($string[1]) && is_numeric($string[1])) {
			$delimiter = $this->__escape;
			return join('', array_map('chr', array_filter(explode($delimiter, $string))));
		}
		if (substr($string, 0, 3) === 'U00') {
			$delimiter = 'U00';
			return join('', array_map('chr', array_map('hexdec', array_filter(explode($delimiter, $string)))));
		}
		if (preg_match('/U([0-9a-fA-F]{4})/', $string, $match)) {
			return Sanitize::ascii(array(hexdec($match[1])));
		}
		return $string;
	}
}

?>
