<?php
/***
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Информация о версии
 * @package Joostina
 */
class coreVersion {
	/** @var строка CMS*/
	public static $CMS = 'Joostina';
	/** @var версия*/
	public static $CMS_ver = '1.3.1 re-volution 1';
	/** @var int Номер сборки*/
	public static $BUILD = '$: 7**';
	/** @var string Дата*/
	public static $RELDATE = '05:05:2010';
	/** @var string Время*/
	public static $RELTIME = '03:04';
	/** @var string Текст авторских прав*/
	public static $COPYRIGHT = 'Авторские права &copy; 2007-2010 Joostina Team. Все права защищены.';
	/** @var string URL*/
	public static $URL = '<a href="http://www.joostina.ru" target="_blank" title="Система создания и управления сайтами Joostina CMS">Joostina!</a> - бесплатное и свободное программное обеспечение для создания сайтов, распространяемое по лицензии GNU/GPL.';
	/** @var string для реального использования сайта установите = 1 для демонстраций = 0: 1 используется по умолчанию*/
	public static $SITE = 1;
	/** @var string Whether site has restricted functionality mostly used for demo sites: 0 is default*/
	public static $RESTRICT = 0;
	/** @var string Whether site is still in development phase (disables checks for /installation folder) - should be set to 0 for package release: 0 is default*/
	public static $SVN = 1;
	/** @var string ссылки на сайты поддержки*/
	public static $SUPPORT = 'Поддержка: <a href="http://www.joostina.ru" target="_blank" title="Официальный сайт CMS Joostina">www.joostina.ru</a> | <a href="http://www.joomlaportal.ru" target="_blank" title="Joomla! CMS по-русски">www.joomlaportal.ru</a> | <a href="http://www.joom.ru" target="_blank" title="Русский дом Joomla">www.joom.ru</a> | <a href="http://www.joomla.ru" target="_blank" title="Бесплатная система управления сайтом Joomla!">www.joomla.ru</a>';

	// получение переменных окружения информации осистеме
	public static function get($name) {
		return self::$$name;
	}
}

// небольшая нежелательная заглушка
// TODO убрать к 1.3.3
class joomlaVersion extends coreVersion{}