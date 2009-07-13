<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2009 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();



DEFINE('_LAST_UPDATED','Последнее обновление');
DEFINE('_CHECKED_IN_ITEMS','');  
DEFINE('_LEGEND','История');
DEFINE('_HEADER_AUTHOR','Автор');
DEFINE('_HEADER_SUBMITTED','Написан');
DEFINE('_HEADER_HITS','Просмотров');
DEFINE('_E_WARNUSER','Пожалуйста, нажмите кнопку "Отмена" или "Сохранить", чтобы покинуть эту страницу');
DEFINE('_E_WARNTITLE','Содержимое должно иметь заголовок');
DEFINE('_E_WARNTEXT','Содержимое должно иметь вводный текст');
DEFINE('_E_TITLE','Заголовок:');
DEFINE('_E_INTRO','Вводный текст');
DEFINE('_E_MAIN','Основной текст');
DEFINE('_E_MOSIMAGE','Вставить тег {mosimage}');
DEFINE('_E_GALLERY_IMAGES','Галерея изображений');
DEFINE('_CONTENT_IMAGES','Изображения к тексту');
DEFINE('_EDIT_IMAGE','Параметры изображения');
DEFINE('_E_NO_IMAGE','Без изображения');
DEFINE('_E_INSERT','Вставить');
DEFINE('_E_UP','Выше');
DEFINE('_E_DOWN','Ниже');
DEFINE('_E_SOURCE','Название файла:');
DEFINE('_E_ALIGN','Расположение:');
DEFINE('_E_ALT','Альтернативный текст:');
DEFINE('_E_BORDER','Рамка:');
DEFINE('_CAPTION_POSITION','Положение подписи');
DEFINE('_CAPTION_ALIGN','Выравнивание подписи');
DEFINE('_CAPTION_WIDTH','Ширина подписи');
DEFINE('_E_APPLY','Применить');
DEFINE('_E_STATE','Состояние:');
DEFINE('_E_AUTHOR_ALIAS','Псевдоним автора:');
DEFINE('_E_ACCESS_LEVEL','Уровень доступа:');
DEFINE('_E_ORDERING','Порядок:');
DEFINE('_E_START_PUB','Дата начала публикации:');
DEFINE('_E_FINISH_PUB','Дата окончания публикации:');
DEFINE('_E_SHOW_FP','Показывать на главной странице:');
DEFINE('_E_HIDE_TITLE','Скрыть заголовок:');
DEFINE('_E_METADATA','Мета-тэги');
DEFINE('_E_M_DESC','Описание:');
DEFINE('_E_M_KEY','Ключевые слова:');
DEFINE('_E_SUBJECT','Тема:');
DEFINE('_E_EXPIRES','Дата истечения:');
DEFINE('_E_ABOUT','Об объекте:');
DEFINE('_E_LAST_MOD','Последнее изменение:');
DEFINE('_E_REGISTERED','Только для зарегистрированных пользователей');
DEFINE('_E_ITEM_SAVED','Успешно сохранено!');
DEFINE('_ITEM_PREVIOUS','&laquo; ');
DEFINE('_ITEM_NEXT',' &raquo;');
DEFINE('_KEY_NOT_FOUND','Ключ не найден');
DEFINE('_SECTION_ARCHIVE_EMPTY','В этом разделе архива сейчас нет объектов. Пожалуйста, зайдите позже');
DEFINE('_CATEGORY_ARCHIVE_EMPTY','В этой категории архива сейчас нет объектов. Пожалуйста, зайдите позже');
DEFINE('_HEADER_SECTION_ARCHIVE','Архив разделов');
DEFINE('_HEADER_CATEGORY_ARCHIVE','Архив категорий');
DEFINE('_ARCHIVE_SEARCH_FAILURE','Не найдено архивных записей для %s %s'); // значения месяца, а затем года
DEFINE('_ARCHIVE_SEARCH_SUCCESS','Найдены архивные записи для %s %s'); // значения месяца, а затем года
DEFINE('_ORDER_DROPDOWN_DA','Дата (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_DD','Дата (по убыванию)');
DEFINE('_ORDER_DROPDOWN_TA','Название (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_TD','Название (по убыванию)');
DEFINE('_ORDER_DROPDOWN_HA','Просмотры (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_HD','Просмотры (по убыванию)');
DEFINE('_ORDER_DROPDOWN_AUA','Автор (по возрастанию)');
DEFINE('_ORDER_DROPDOWN_AUD','Автор (по убыванию)');
DEFINE('_ORDER_DROPDOWN_O','По порядку');
DEFINE('_YOU_HAVE_NO_CONTENT','Нет добавленного Вами содержимого');
DEFINE('_CONTENT_IS_BEING_EDITED_BY_OTHER_PEOPLE','Содержимое сейчас редактируется другим человеком');
DEFINE('_EMPTY_BLOG','Нет объектов для отображения!');
DEFINE('_COM_CONTENT_USER_NOT_FOUND','Извините, пользователь не найден');