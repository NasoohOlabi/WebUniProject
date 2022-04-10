<?php

// singleton
class Language
{
	private static $texts;
	static $direction = 'ltr';
	static $lang = 'en';

	private function __construct()
	{
	} 

	public static function init()
	{
		if (isset($_GET['lang'])) {
			setcookie('lang', $_GET['lang'], time() + (86400 * 30), "/");
			$SwitchLanguageTo = $_GET['lang'];
			unset($_GET['lang']);
			header("Location: " . URL);
			return;
		}
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
		if (Language::$lang === 'en') {
			$key = humanize($key);
			return $key;
		} elseif (Language::$lang === 'ar') {
			if (isset(Language::$texts[$key]))
				return Language::$texts[$key];
			else {
				simpleLog("Language::t($key) not found", 'lang');
				return $key;
			}
		}
	}
}
Language::init();
