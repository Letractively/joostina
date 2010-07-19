<?php
/***
* @package Joostina
* @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
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
class joomlaVersion {
/** @var строка Продукт */
var $PRODUCT = 'Joomla!';
/** @var строка CMS */
var $CMS = 'Joostina';
/** @var версия */
var $CMS_ver = '1.2.1';
/** @var int Номер основной версии */
var $RELEASE = '1.0';
/** @var строка  статус разработки */
var $DEV_STATUS = '';
/** @var int Подверсия */
var $DEV_LEVEL = '15';
/** @var int Номер сборки */
var $BUILD = '$: 191';
/** @var string Кодовое имя */
var $CODENAME = '&beta; 4';
/** @var string Дата */
var $RELDATE = '18.06.2010';
/** @var string Время */
var $RELTIME = '23:57';
/** @var string Временная зона */
var $RELTZ = '+5 GMT';
/** @var string Текст авторских прав */
var $COPYRIGHT = 'Авторские права &copy; 2008&ndash;2010 Joostina Team. Все права защищены.';
/** @var string URL*/
var $URL = '<a href="http://www.joostina.ru" target="_blank" title="Система создания и управления сайтами Joostina CMS">Joostina!</a> - свободное программное обеспечение, распространяемое по лицензии GNU/GPL.';
/** @var string для реального использования сайта установите = 1 для демонстраций = 0: 1 используется по умолчанию */
var $SITE = 1;
/** @var string Whether site has restricted functionality mostly used for demo sites: 0 is default */
var $RESTRICT = 0;
/** @var string Whether site is still in development phase (disables checks for /installation folder) - should be set to 0 for package release: 0 is default */
var $SVN = 0;
/** @var string ссылки на сайты поддержки */
var $SUPPORT = 'Поддержка: <a href="http://www.joostina.ru" target="_blank" title="Официальный сайт CMS Joostina">www.joostina.ru</a> | <a href="http://www.joomlaportal.ru" target="_blank" title="Joomla! CMS по-русски">www.joomlaportal.ru</a> | <a href="http://www.joom.ru" target="_blank" title="Русский дом Joomla">www.joom.ru</a> | <a href="http://www.joomla.ru" target="_blank" title="Бесплатная система управления сайтом Joomla!">www.joomla.ru</a>';
/** @return string Длинный формат версии */
function getLongVersion() {
return $this->CMS.' '.$this->RELEASE.'. '.$this->CMS_ver.' ['.$this->CODENAME.'] '.$this->RELDATE.' '.$this->RELTIME.' '.$this->RELTZ;
}
/** @return string Краткий формат версии */
function getShortVersion() {
return $this->RELEASE.'.'.$this->DEV_LEVEL;
}
/** @return string Version suffix for help files */
function getHelpVersion() {
if($this->RELEASE > '1.0') {
return '.'.str_replace('.','',$this->RELEASE);
} else {
return '';
}
}
}
$_VERSION = new joomlaVersion();
$version= $_VERSION->CMS.' '.$_VERSION->CMS_ver.' '.$_VERSION->DEV_STATUS.' ['.$_VERSION->CODENAME.'] '.$_VERSION->RELDATE.' '.$_VERSION->RELTIME.' '.$_VERSION->RELTZ;
$jostina_ru= $_VERSION->CMS.' '.$_VERSION->CMS_ver.'.'.$_VERSION->DEV_STATUS.' ['.$_VERSION->CODENAME.'] '.$_VERSION->RELDATE.' '.$_VERSION->RELTIME.' '.$_VERSION->RELTZ.'<br />'.$_VERSION->SUPPORT;
?>