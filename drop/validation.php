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

class Validation extends Object {

/**
 * Set the the value of methods $check param.
 *
 * @var string
 * @access public
 */
	public static $check = null;

/**
 * Set to a valid regular expression in the class methods.
 * Can be set from $regex param also
 *
 * @var string
 * @access public
 */
	public static $regex = null;

/**
 * Some complex patterns needed in multiple places
 *
 * @var array
 * @access private
 */
	public static $__pattern = array(
		'hostname' => '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)'
	);

/**
 * Some class methods use a country to determine proper validation.
 * This can be passed to methods in the $country param
 *
 * @var string
 * @access public
 */
	public static $country = null;

/**
 * Some class methods use a deeper validation when set to true
 *
 * @var string
 * @access public
 */
	public static $deep = null;

/**
 * Some class methods use the $type param to determine which validation to perfom in the method
 *
 * @var string
 * @access public
 */
	public static $type = null;

/**
 * Constructor.
 */
	function __construct() {}

/**
 * Checks that a string contains something other than whitespace
 *
 * Returns true if string contains something other than whitespace
 *
 * $check can be passed as an array:
 * array('check' => 'valueToCheck');
 *
 * @param mixed $check Value to check
 * @return boolean Success
 * @access public
 */
	public static function notEmpty($check) {
		self::__reset();
		self::$check = $check;

		if (is_array($check)) {
			self::_extract($check);
		}

		if (empty(self::$check) && self::$check != '0') {
			return false;
		}
		self::$regex = '/[^\s]+/m';
		return self::_check();
	}

/**
 * Checks that a string contains only integer or letters
 *
 * Returns true if string contains only integer or letters
 *
 * $check can be passed as an array:
 * array('check' => 'valueToCheck');
 *
 * @param mixed $check Value to check
 * @return boolean Success
 * @access public
 */
	public static function alphaNumeric($check) {
		self::__reset();
		self::$check = $check;

		if (is_array($check)) {
			self::_extract($check);
		}

		if (empty(self::$check) && self::$check != '0') {
			return false;
		}
		self::$regex = '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu';
		return self::_check();
	}

/**
 * Checks that a string length is within s specified range.
 * Spaces are included in the character count.
 * Returns true is string matches value min, max, or between min and max,
 *
 * @param string $check Value to check for length
 * @param integer $min Minimum value in range (inclusive)
 * @param integer $max Maximum value in range (inclusive)
 * @return boolean Success
 * @access public
 */
	public static function between($check, $min, $max) {
		$length = mb_strlen($check);
		return ($length >= $min && $length <= $max);
	}

/**
 * Returns true if field is left blank -OR- only whitespace characters are present in it's value
 * Whitespace characters include Space, Tab, Carriage Return, Newline
 *
 * $check can be passed as an array:
 * array('check' => 'valueToCheck');
 *
 * @param mixed $check Value to check
 * @return boolean Success
 * @access public
 */
	public static function blank($check) {
		self::__reset();
		self::$check = $check;

		if (is_array($check)) {
			self::_extract($check);
		}

		self::$regex = '/[^\\s]/';
		return !self::_check();
	}

/**
 * Validation of credit card numbers.
 * Returns true if $check is in the proper credit card format.
 *
 * @param mixed $check credit card number to validate
 * @param mixed $type 'all' may be passed as a sting, defaults to fast which checks format of most major credit cards
 *    if an array is used only the values of the array are checked.
 *    Example: array('amex', 'bankcard', 'maestro')
 * @param boolean $deep set to true this will check the Luhn algorithm of the credit card.
 * @param string $regex A custom regex can also be passed, this will be used instead of the defined regex values
 * @return boolean Success
 * @access public
 * @see Validation::_luhn()
 */
	public static function cc($check, $type = 'fast', $deep = false, $regex = null) {
		self::__reset();
		self::$check = $check;
		self::$type = $type;
		self::$deep = $deep;
		self::$regex = $regex;

		if (is_array($check)) {
			self::_extract($check);
		}
		self::$check = str_replace(array('-', ' '), '', self::$check);

		if (mb_strlen(self::$check) < 13) {
			return false;
		}

		if (!is_null(self::$regex)) {
			if (self::_check()) {
				return self::_luhn();
			}
		}
		$cards = array(
			'all' => array(
				'amex' => '/^3[4|7]\\d{13}$/',
				'bankcard' => '/^56(10\\d\\d|022[1-5])\\d{10}$/',
				'diners'   => '/^(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})$/',
				'disc'     => '/^(?:6011|650\\d)\\d{12}$/',
				'electron' => '/^(?:417500|4917\\d{2}|4913\\d{2})\\d{10}$/',
				'enroute'  => '/^2(?:014|149)\\d{11}$/',
				'jcb'      => '/^(3\\d{4}|2100|1800)\\d{11}$/',
				'maestro'  => '/^(?:5020|6\\d{3})\\d{12}$/',
				'mc'       => '/^5[1-5]\\d{14}$/',
				'solo'     => '/^(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?$/',
				'switch'   => '/^(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)$/',
				'visa'     => '/^4\\d{12}(\\d{3})?$/',
				'voyager'  => '/^8699[0-9]{11}$/'
			),
			'fast'   => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/'
		);

		if (is_array(self::$type)) {
			foreach (self::$type as $value) {
				self::$regex = $cards['all'][strtolower($value)];

				if (self::_check()) {
					return self::_luhn();
				}
			}
		} elseif (self::$type == 'all') {
			foreach ($cards['all'] as $value) {
				self::$regex = $value;

				if (self::_check()) {
					return self::_luhn();
				}
			}
		} else {
			self::$regex = $cards['fast'];

			if (self::_check()) {
				return self::_luhn();
			}
		}
	}

/**
 * Used to compare 2 numeric values.
 *
 * @param mixed $check1 if string is passed for a string must also be passed for $check2
 *    used as an array it must be passed as array('check1' => value, 'operator' => 'value', 'check2' -> value)
 * @param string $operator Can be either a word or operand
 *    is greater >, is less <, greater or equal >=
 *    less or equal <=, is less <, equal to ==, not equal !=
 * @param integer $check2 only needed if $check1 is a string
 * @return boolean Success
 * @access public
 */
	public static function comparison($check1, $operator = null, $check2 = null) {
		if (is_array($check1)) {
			extract($check1, EXTR_OVERWRITE);
		}
		$operator = str_replace(array(' ', "\t", "\n", "\r", "\0", "\x0B"), '', strtolower($operator));

		switch ($operator) {
			case 'isgreater':
			case '>':
				if ($check1 > $check2) {
					return true;
				}
				break;
			case 'isless':
			case '<':
				if ($check1 < $check2) {
					return true;
				}
				break;
			case 'greaterorequal':
			case '>=':
				if ($check1 >= $check2) {
					return true;
				}
				break;
			case 'lessorequal':
			case '<=':
				if ($check1 <= $check2) {
					return true;
				}
				break;
			case 'equalto':
			case '==':
				if ($check1 == $check2) {
					return true;
				}
				break;
			case 'notequal':
			case '!=':
				if ($check1 != $check2) {
					return true;
				}
				break;
			default:
				Error::validationComparison($operator);
				break;
		}
		return false;
	}

/**
 * Used when a custom regular expression is needed.
 *
 * @param mixed $check When used as a string, $regex must also be a valid regular expression.
 *								As and array: array('check' => value, 'regex' => 'valid regular expression')
 * @param string $regex If $check is passed as a string, $regex must also be set to valid regular expression
 * @return boolean Success
 * @access public
 */
	public static function custom($check, $regex = null) {
		self::__reset();
		self::$check = $check;
		self::$regex = $regex;
		if (is_array($check)) {
			self::_extract($check);
		}
		if (self::$regex === null) {
			Error::validationCustom($regex);
			return false;
		}
		return self::_check();
	}

/**
 * Date validation, determines if the string passed is a valid date.
 * keys that expect full month, day and year will validate leap years
 *
 * @param string $check a valid date string
 * @param mixed $format Use a string or an array of the keys below. Arrays should be passed as array('dmy', 'mdy', etc)
 * 					Keys: dmy 27-12-2006 or 27-12-06 separators can be a space, period, dash, forward slash
 * 							mdy 12-27-2006 or 12-27-06 separators can be a space, period, dash, forward slash
 * 							ymd 2006-12-27 or 06-12-27 separators can be a space, period, dash, forward slash
 * 							dMy 27 December 2006 or 27 Dec 2006
 * 							Mdy December 27, 2006 or Dec 27, 2006 comma is optional
 * 							My December 2006 or Dec 2006
 * 							my 12/2006 separators can be a space, period, dash, forward slash
 * @param string $regex If a custom regular expression is used this is the only validation that will occur.
 * @return boolean Success
 * @access public
 */
	public static function date($check, $format = 'ymd', $regex = null) {
		self::__reset();
		self::$check = $check;
		self::$regex = $regex;

		if (!is_null(self::$regex)) {
			return self::_check();
		}

		$regex['dmy'] = '%^(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.|\\x20)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.|\\x20)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.|\\x20)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
		$regex['mdy'] = '%^(?:(?:(?:0?[13578]|1[02])(\\/|-|\\.|\\x20)31)\\1|(?:(?:0?[13-9]|1[0-2])(\\/|-|\\.|\\x20)(?:29|30)\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:0?2(\\/|-|\\.|\\x20)29\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\\/|-|\\.|\\x20)(?:0?[1-9]|1\\d|2[0-8])\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
		$regex['ymd'] = '%^(?:(?:(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\\/|-|\\.|\\x20)(?:0?2\\1(?:29)))|(?:(?:(?:1[6-9]|[2-9]\\d)?\\d{2})(\\/|-|\\.|\\x20)(?:(?:(?:0?[13578]|1[02])\\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\\2(?:0?[1-9]|1\\d|2[0-8]))))$%';
		$regex['dMy'] = '/^((31(?!\\ (Feb(ruary)?|Apr(il)?|June?|(Sep(?=\\b|t)t?|Nov)(ember)?)))|((30|29)(?!\\ Feb(ruary)?))|(29(?=\\ Feb(ruary)?\\ (((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))|(0?[1-9])|1\\d|2[0-8])\\ (Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)\\ ((1[6-9]|[2-9]\\d)\\d{2})$/';
		$regex['Mdy'] = '/^(?:(((Jan(uary)?|Ma(r(ch)?|y)|Jul(y)?|Aug(ust)?|Oct(ober)?|Dec(ember)?)\\ 31)|((Jan(uary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sept|Nov|Dec)(ember)?)\\ (0?[1-9]|([12]\\d)|30))|(Feb(ruary)?\\ (0?[1-9]|1\\d|2[0-8]|(29(?=,?\\ ((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))))\\,?\\ ((1[6-9]|[2-9]\\d)\\d{2}))$/';
		$regex['My'] = '%^(Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)[ /]((1[6-9]|[2-9]\\d)\\d{2})$%';
		$regex['my'] = '%^(((0[123456789]|10|11|12)([- /.])(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))$%';

		$format = (is_array($format)) ? array_values($format) : array($format);
		foreach ($format as $key) {
			self::$regex = $regex[$key];

			if (self::_check() === true) {
				return true;
			}
		}
		return false;
	}

/**
 * Time validation, determines if the string passed is a valid time.
 * Validates time as 24hr (HH:MM) or am/pm ([H]H:MM[a|p]m)
 * Does not allow/validate seconds.
 *
 * @param string $check a valid time string
 * @return boolean Success
 * @access public
 */

	public static function time($check) {
		self::__reset();
		self::$check = $check;
		self::$regex = '%^((0?[1-9]|1[012])(:[0-5]\d){0,2}([AP]M|[ap]m))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$%';
		return self::_check();
	}

/**
 * Boolean validation, determines if value passed is a boolean integer or true/false.
 *
 * @param string $check a valid boolean
 * @return boolean Success
 * @access public
 */
	public static function boolean($check) {
		$booleanList = array(0, 1, '0', '1', true, false);
		return in_array($check, $booleanList, true);
	}

/**
 * Checks that a value is a valid decimal. If $places is null, the $check is allowed to be a scientific float
 * If no decimal point is found a false will be returned. Both the sign and exponent are optional.
 *
 * @param integer $check The value the test for decimal
 * @param integer $places if set $check value must have exactly $places after the decimal point
 * @param string $regex If a custom regular expression is used this is the only validation that will occur.
 * @return boolean Success
 * @access public
 */
	public static function decimal($check, $places = null, $regex = null) {
		self::__reset();
		self::$regex = $regex;
		self::$check = $check;

		if (is_null(self::$regex)) {
			if (is_null($places)) {
				self::$regex = '/^[-+]?[0-9]*\\.{1}[0-9]+(?:[eE][-+]?[0-9]+)?$/';
			} else {
				self::$regex = '/^[-+]?[0-9]*\\.{1}[0-9]{'.$places.'}$/';
			}
		}
		return self::_check();
	}

/**
 * Validates for an email address.
 *
 * @param string $check Value to check
 * @param boolean $deep Perform a deeper validation (if true), by also checking availability of host
 * @param string $regex Regex to use (if none it will use built in regex)
 * @return boolean Success
 * @access public
 */
	public static function email($check, $deep = false, $regex = null) {
		self::__reset();
		self::$check = $check;
		self::$regex = $regex;
		self::$deep = $deep;

		if (is_array($check)) {
			self::_extract($check);
		}

		if (is_null(self::$regex)) {
			self::$regex = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . self::$__pattern['hostname'] . '$/i';
		}
		$return = self::_check();

		if (self::$deep === false || self::$deep === null) {
			return $return;
		}

		if ($return === true && preg_match('/@(' . self::$__pattern['hostname'] . ')$/i', self::$check, $regs)) {
			if (function_exists('getmxrr') && getmxrr($regs[1], $mxhosts)) {
				return true;
			}
			if (function_exists('checkdnsrr') && checkdnsrr($regs[1], 'MX')) {
				return true;
			}
			return is_array(gethostbynamel($regs[1]));
		}
		return false;
	}

/**
 * Check that value is exactly $comparedTo.
 *
 * @param mixed $check Value to check
 * @param mixed $comparedTo Value to compare
 * @return boolean Success
 * @access public
 */
	public static function equalTo($check, $comparedTo) {
		return ($check === $comparedTo);
	}

/**
 * Check that value has a valid file extension.
 *
 * @param mixed $check Value to check
 * @param array $extensions file extenstions to allow
 * @return boolean Success
 * @access public
 */
	public static function extension($check, $extensions = array('gif', 'jpeg', 'png', 'jpg')) {
		if (is_array($check)) {
			return self::extension(array_shift($check), $extensions);
		}
		$extension = strtolower(array_pop(explode('.', $check)));
		foreach ($extensions as $value) {
			if ($extension == strtolower($value)) {
				return true;
			}
		}
		return false;
	}

/**
 * Validation of an IP address.
 *
 * Valid IP version strings for type restriction are:
 * - both: Check both IPv4 and IPv6, return true if the supplied address matches either version
 * - IPv4: Version 4 (Eg: 127.0.0.1, 192.168.10.123, 203.211.24.8)
 * - IPv6: Version 6 (Eg: ::1, 2001:0db8::1428:57ab)
 *
 * @param string $check The string to test.
 * @param string $type The IP Version to test against
 * @return boolean Success
 * @access public
 */
	public static function ip($check, $type = 'both') {
		$success = false;
		$type = strtolower($type);
		if ($type === 'ipv4' || $type === 'both') {
			$success |= self::_ipv4($check);
		}
		if ($type === 'ipv6' || $type === 'both') {
			$success |= self::_ipv6($check);
		}
		return $success;
	}

/**
 * Validation of IPv4 addresses.
 *
 * @param string $check IP Address to test
 * @return boolean Success
 * @access protected
 */
	public static function _ipv4($check) {
		if (function_exists('filter_var')) {
			return filter_var($check, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4)) !== false;
		}
		self::__populateIp();
		self::$check = $check;
		self::$regex = '/^' . self::$__pattern['IPv4'] . '$/';
		return self::_check();
	}

/**
 * Validation of IPv6 addresses.
 *
 * @param string $check IP Address to test
 * @return boolean Success
 * @access protected
 */
	public static function _ipv6($check) {
		if (function_exists('filter_var')) {
			return filter_var($check, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV6)) !== false;
		}
		self::__populateIp();
		self::$check = $check;
		self::$regex = '/^' . self::$__pattern['IPv6'] . '$/';
		return self::_check();
	}

/**
 * Checks whether the length of a string is greater or equal to a minimal length.
 *
 * @param string $check The string to test
 * @param integer $min The minimal string length
 * @return boolean Success
 * @access public
 */
	public static function minLength($check, $min) {
		$length = mb_strlen($check);
		return ($length >= $min);
	}

/**
 * Checks whether the length of a string is smaller or equal to a maximal length..
 *
 * @param string $check The string to test
 * @param integer $max The maximal string length
 * @return boolean Success
 * @access public
 */
	public static function maxLength($check, $max) {
		$length = mb_strlen($check);
		return ($length <= $max);
	}

/**
 * Checks that a value is a monetary amount.
 *
 * @param string $check Value to check
 * @param string $symbolPosition Where symbol is located (left/right)
 * @return boolean Success
 * @access public
 */
	public static function money($check, $symbolPosition = 'left') {
		self::$check = $check;

		if ($symbolPosition == 'right') {
			self::$regex = '/^(?!0,?\d)(?:\d{1,3}(?:([, .])\d{3})?(?:\1\d{3})*|(?:\d+))((?!\1)[,.]\d{2})?(?<!\x{00a2})\p{Sc}?$/u';
		} else {
			self::$regex = '/^(?!\x{00a2})\p{Sc}?(?!0,?\d)(?:\d{1,3}(?:([, .])\d{3})?(?:\1\d{3})*|(?:\d+))((?!\1)[,.]\d{2})?$/u';
		}
		return self::_check();
	}

/**
 * Validate a multiple select.
 *
 * Valid Options
 *
 * - in => provide a list of choices that selections must be made from
 * - max => maximun number of non-zero choices that can be made
 * - min => minimum number of non-zero choices that can be made
 *
 * @param mixed $check Value to check
 * @param mixed $options Options for the check.
 * @return boolean Success
 * @access public
 */
	public static function multiple($check, $options = array()) {
		$defaults = array('in' => null, 'max' => null, 'min' => null);
		$options = array_merge($defaults, $options);
		$check = array_filter((array)$check);
		if (empty($check)) {
			return false;
		}
		if ($options['max'] && count($check) > $options['max']) {
			return false;
		}
		if ($options['min'] && count($check) < $options['min']) {
			return false;
		}
		if ($options['in'] && is_array($options['in'])) {
			foreach ($check as $val) {
				if (!in_array($val, $options['in'])) {
					return false;
				}
			}
		}
		return true;
	}

/**
 * Checks if a value is numeric.
 *
 * @param string $check Value to check
 * @return boolean Succcess
 * @access public
 */
	public static function numeric($check) {
		return is_numeric($check);
	}

/**
 * Check that a value is a valid phone number.
 *
 * @param mixed $check Value to check (string or array)
 * @param string $regex Regular expression to use
 * @param string $country Country code (defaults to 'all')
 * @return boolean Success
 * @access public
 */
	public static function phone($check, $regex = null, $country = 'all') {
		self::$check = $check;
		self::$regex = $regex;
		self::$country = $country;
		if (is_array($check)) {
			self::_extract($check);
		}

		if (is_null(self::$regex)) {
			switch (self::$country) {
				case 'us':
				case 'all':
				case 'can':
				// includes all NANPA members. see http://en.wikipedia.org/wiki/North_American_Numbering_Plan#List_of_NANPA_countries_and_territories
					self::$regex  = '/^(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}$/';
				break;
			}
		}
		if (empty(self::$regex)) {
			return self::$_pass('phone', $check, $country);
		}
		return self::$_check();
	}

/**
 * Checks that a given value is a valid postal code.
 *
 * @param mixed $check Value to check
 * @param string $regex Regular expression to use
 * @param string $country Country to use for formatting
 * @return boolean Success
 * @access public
 */
	public static function postal($check, $regex = null, $country = null) {
		self::$check = $check;
		self::$regex = $regex;
		self::$country = $country;
		if (is_array($check)) {
			self::_extract($check);
		}
		if (empty($country)) {
			self::$country = 'us';
		}

		if (is_null(self::$regex)) {
			switch (self::$country) {
				case 'uk':
					self::$regex  = '/\\A\\b[A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2}\\b\\z/i';
					break;
				case 'ca':
					self::$regex  = '/\\A\\b[ABCEGHJKLMNPRSTVXY][0-9][A-Z] ?[0-9][A-Z][0-9]\\b\\z/i';
					break;
				case 'it':
				case 'de':
					self::$regex  = '/^[0-9]{5}$/i';
					break;
				case 'be':
					self::$regex  = '/^[1-9]{1}[0-9]{3}$/i';
					break;
				case 'us':
					self::$regex  = '/\\A\\b[0-9]{5}(?:-[0-9]{4})?\\b\\z/i';
					break;
			}
		}
		if (empty(self::$regex)) {
			return self::_pass('postal', $check, $country);
		}
		return self::_check();
	}

/**
 * Validate that a number is in specified range.
 * if $lower and $upper are not set, will return true if
 * $check is a legal finite on this platform
 *
 * @param string $check Value to check
 * @param integer $lower Lower limit
 * @param integer $upper Upper limit
 * @return boolean Success
 * @access public
 */
	public static function range($check, $lower = null, $upper = null) {
		if (!is_numeric($check)) {
			return false;
		}
		if (isset($lower) && isset($upper)) {
			return ($check > $lower && $check < $upper);
		}
		return is_finite($check);
	}

/**
 * Checks that a value is a valid Social Security Number.
 *
 * @param mixed $check Value to check
 * @param string $regex Regular expression to use
 * @param string $country Country
 * @return boolean Success
 * @access public
 */
	public static function ssn($check, $regex = null, $country = null) {
		self::$check = $check;
		self::$regex = $regex;
		self::$country = $country;
		if (is_array($check)) {
			self::_extract($check);
		}

		if (is_null(self::$regex)) {
			switch (self::$country) {
				case 'dk':
					self::$regex  = '/\\A\\b[0-9]{6}-[0-9]{4}\\b\\z/i';
					break;
				case 'nl':
					self::$regex  = '/\\A\\b[0-9]{9}\\b\\z/i';
					break;
				case 'us':
					self::$regex  = '/\\A\\b[0-9]{3}-[0-9]{2}-[0-9]{4}\\b\\z/i';
					break;
			}
		}
		if (empty(self::$regex)) {
			return self::_pass('ssn', $check, $country);
		}
		return self::_check();
	}

/**
 * Checks that a value is a valid uuid - http://tools.ietf.org/html/rfc4122
 * 
 * @param string $check Value to check
 * @return boolean Success
 * @access public
 */
	public static function uuid($check) {
		self::$check = $check;
		self::$regex = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
		return self::_check();
	}

/**
 * Checks that a value is a valid URL according to http://www.w3.org/Addressing/URL/url-spec.txt
 *
 * The regex checks for the following component parts:
 *
 * - a valid, optional, scheme
 * - a valid ip address OR
 *   a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt
 *   with an optional port number
 * - an optional valid path
 * - an optional query string (get parameters)
 * - an optional fragment (anchor tag)
 *
 * @param string $check Value to check
 * @param boolean $strict Require URL to be prefixed by a valid scheme (one of http(s)/ftp(s)/file/news/gopher)
 * @return boolean Success
 * @access public
 */
	public static function url($check, $strict = false) {
		self::__populateIp();
		self::$check = $check;
		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~') . '\/0-9a-z]|(%[0-9a-f]{2}))';
		self::$regex = '/^(?:(?:https?|ftps?|file|news|gopher):\/\/)' . (!empty($strict) ? '' : '?') .
			'(?:' . self::$__pattern['IPv4'] . '|\[' . self::$__pattern['IPv6'] . '\]|' . self::$__pattern['hostname'] . ')' .
			'(?::[1-9][0-9]{0,4})?' .
			'(?:\/?|\/' . $validChars . '*)?' .
			'(?:\?' . $validChars . '*)?' .
			'(?:#' . $validChars . '*)?$/i';
		return self::_check();
	}

/**
 * Checks if a value is in a given list.
 *
 * @param string $check Value to check
 * @param array $list List to check against
 * @return boolean Succcess
 * @access public
 */
	public static function inList($check, $list) {
		return in_array($check, $list);
	}

/**
 * Runs an user-defined validation.
 *
 * @param mixed $check value that will be validated in user-defined methods.
 * @param object $object class that holds validation method
 * @param string $method class method name for validation to run
 * @param array $args arguments to send to method
 * @return mixed user-defined class class method returns
 * @access public
 */
	public static function userDefined($check, $object, $method, $args = null) {
		return call_user_func_array(array(&$object, $method), array($check, $args));
	}

/**
 * Attempts to pass unhandled Validation locales to a class starting with $classPrefix
 * and ending with Validation.  For example $classPrefix = 'nl', the class would be
 * `NlValidation`.
 *
 * @param string $method The method to call on the other class.
 * @param mixed $check The value to check or an array of parameters for the method to be called.
 * @param string $classPrefix The prefix for the class to do the validation.
 * @return mixed Return of Passed method, false on failure
 * @access protected
 **/
	public static function _pass($method, $check, $classPrefix) {
		$className = ucwords($classPrefix) . 'Validation';
		if (!class_exists($className)) {
			Error::validationClass($className);
			return false;
		}
		if (!is_callable(array($className, $method))) {
			Error::validationMethod($method,$className);
			return false;
		}
		$check = (array)$check;
		return call_user_func_array(array($className, $method), $check);
	}

/**
 * Runs a regular expression match.
 *
 * @return boolean Success of match
 * @access protected
 */
	public static function _check() {
		if (preg_match(self::$regex, self::$check)) {
			return true;
		} else {
			return false;
		}
	}

/**
 * Get the values to use when value sent to validation method is
 * an array.
 *
 * @param array $params Parameters sent to validation method
 * @return void
 * @access protected
 */
	public static function _extract($params) {
		extract($params, EXTR_OVERWRITE);

		if (isset($check)) {
			self::$check = $check;
		}
		if (isset($regex)) {
			self::$regex = $regex;
		}
		if (isset($country)) {
			self::$country = mb_strtolower($country);
		}
		if (isset($deep)) {
			self::$deep = $deep;
		}
		if (isset($type)) {
			self::$type = $type;
		}
	}

/**
 * Luhn algorithm
 *
 * @see http://en.wikipedia.org/wiki/Luhn_algorithm
 * @return boolean Success
 * @access protected
 */
	public static function _luhn() {
		if (self::$deep !== true) {
			return true;
		}
		if (self::$check == 0) {
			return false;
		}
		$sum = 0;
		$length = strlen(self::$check);

		for ($position = 1 - ($length % 2); $position < $length; $position += 2) {
			$sum += self::$check[$position];
		}

		for ($position = ($length % 2); $position < $length; $position += 2) {
			$number = self::$check[$position] * 2;
			$sum += ($number < 10) ? $number : $number - 9;
		}

		return ($sum % 10 == 0);
	}

/*
 * Lazily popualate the IP address patterns used for validations
 *
 * @return void
 * @access private
 */
	public static function __populateIp() {
		if (!isset(self::$__pattern['IPv6'])) {
			$pattern  = '((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}';
			$pattern .= '(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})';
			$pattern .= '|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})';
			$pattern .= '(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)';
			$pattern .= '{4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
			$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}';
			$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|';
			$pattern .= '((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}';
			$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
			$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4})';
			$pattern .= '{0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)';
			$pattern .= '|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]';
			$pattern .= '\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4})';
			$pattern .= '{1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?';

			self::$__pattern['IPv6'] = $pattern;
		}
		if (!isset(self::$__pattern['IPv4'])) {
			$pattern = '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])';
			self::$__pattern['IPv4'] = $pattern;
		}
	}

/**
 * Reset internal variables for another validation run.
 *
 * @return void
 * @access private
 */
	public static function __reset() {
		self::$check = null;
		self::$regex = null;
		self::$country = null;
		self::$deep = null;
		self::$type = null;
	}

}

?>
