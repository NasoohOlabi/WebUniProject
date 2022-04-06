<?php

// singleton
class Language
{
	private static $texts;
	static $direction = 'ltr';
	static $lang = 'en';

	public static function init()
	{
		global $SwitchLanguageTo;
		if (isset($SwitchLanguageTo)) {
			self::$lang = $SwitchLanguageTo;
		} elseif (isset($_COOKIE['lang'])) {
			self::$lang = $_COOKIE['lang'];
		} else {
			self::$lang = 'en';
		}
		Language::$direction = (Language::$lang == 'ar') ? 'rtl' : 'ltr';
		if (!isset($_COOKIE['lang'])) {
			setcookie('lang', 'en', 30);
		}
		if (Language::$lang === 'ar') {
			require 'application/views/Languages/Arabic.php';
			Language::$texts = $texts;
			Language::$direction = $direction;
			Language::$lang = $lang;
		}
	}

	static function t($key)
	{
		if (Language::$lang === 'en')
			return $key;
		elseif (Language::$lang === 'ar') {
			return Language::$texts[$key];
		}
	}
}
simpleLog("language called");
Language::init();
