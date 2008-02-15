<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/
require(dirname(__FILE__).'/../../../die.php');

if( defined( '_LANG_QUICKICONS' )){
	return;
}else{
	define( '_LANG_QUICKICONS', 1 );

	// common
	define( '_QI_LNG',				'ru' ); // de - tr - etc. ....
	define( '_QI_BTN_LNG',			'ru_RU' ); // do not change!
	define( '_QI_QUICKICONS',		'Кнопки быстрого доступа');
	define( '_QI_CMN_ACCESS',		'Доступ');
	define( '_QI_SEARCH',			'Поиск');
	define( '_QI_TITLE',			'Заголовок' ); // (for title.tag)
	define( '_QI_MOD_MGMNT',		'Управление модулем' ); // new 2.0.5

	// header
	define( '_QI_HDR_MGMNT',		'Управление');

	// Toolbar
	define('_QI_PUBLISH', 			'Публиковать');
	define('_QI_UNPUBLISH', 		'Скрыть');
	define('_QI_NEW',				'Добавить');
	define('_QI_EDIT',				'Изменить');
	define('_QI_DELETE',			'Удалить');
	define('_QI_SAVE',				'Сохранить');
	define('_QI_APPLY',				'Применить');
	define('_QI_CANCEL',			'Отменить');

	// QickIcons List
	define('_QI_NAME',				'Имя');
	define('_QI_PUBLISHED',			'Опубликовано');
	define('_QI_UNPUBLISHED',		'Не опубликовано');
	define('_QI_REORDER',			'Упорядочить');
	define('_QI_ORDER',				'Порядок');
	define('_QI_SAVE_ORDER',		'Сохранить порядок');
	define('_QI_TARGET',			'Ссылка');
	define('_QI_ORDER_UP',			'Вверх');
	define('_QI_ORDER_DOWN',		'Вниз');

	// Edit/New QuickIcon
	define('_QI_DETAIL',			'Детали');
	define('_QI_DETAIL_EDIT',		'Редактирование');
	define('_QI_DETAIL_NEW',		'Создание');
	define('_QI_DETAIL_PREFIX',		'Префикс');
	define('_QI_DETAIL_POSTFIX',	'Постфикс');
	define('_QI_DETAIL_TEXT',		'Текст');
	define('_QI_DETAIL_NEW_WINDOW',	'В новом окне');
	define('_QI_DETAIL_YES',		'Да');
	define('_QI_DETAIL_NO',			'Нет');
	define('_QI_DETAIL_ORDER',		'Расположить после');
	define('_QI_DETAIL_ICON',		'Картинка');
	define('_QI_DETAIL_CHOOSE_ICON','Выбрать картинку');

	define( '_QI_ACCESSKEY',		'Сочетание клавиш' );
	define( '_QI_DISPLAY',			'Показывать как' );

	// fonts
	define( '_QI_FONT_BOLD',		'Жирно' );
	define( '_QI_FONT_ITALIC',		'Наклонно' );
	define( '_QI_FONT_UNDERLINE',	'Подчеркнуто' );

	// Others
	define('_QI_MSG_SUC_DELETED', 	'Кнопки успешно удалены' );
	define('_QI_MSG_CHOOSE_ICON', 	'Нажмите на кнопку для выбора' );
	define('_QI_MSG_TEXT', 			'Пожалуйста, введите поле Текст' );
	define('_QI_MSG_TARGET', 		'Требуется ссылка' );
	define('_QI_MSG_ICON', 			'Требуется картинка' );
	define( '_QI_CMT_CHECK',		'Проверять компонент' );
	define( '_QI_CMT_NAME_TO_CHECK','Имя и путь' );
	define( '_QI_CHECK_VERSION',	'Текущая&nbsp;версия' );
	define( '_QI_ERR_NO_TARGET',	'Не выбрана ссылка' );

	// module
	define( '_QI_MOD_ACCESSKEY',	'Сочетание клавиш: ALT +' );
	define( '_QI_MOD_NO_COM',		'Компонент отсутствует и должен быть установлен' );

	// msgs
	define( '_QI_MSG_NEW_ORDER_SAVED',	'Новый порядок сохранен' );

	// tips
	define( '_QI_TIP_TARGET',		'Ссылка для вызова сайта или компонента.<br />Для компонентов внутри системы ссылка должна быть подобной: <br />index2.php?option=com_joomlastats&task=stats  [ joomlastats - компонент, &task=stats вызов определённой функции компонента ].<br />Внешние ссылки должны быть <strong>абсолютными ссылками</strong> (например: http://www....)!');
	define( '_QI_TIP_CMT_CHECK',	'<strong>Опционально</strong><br />Будет осуществляться проверка на доступность компонента перед переходом.');
	define( '_QI_TIP_CM_PATH',		'<strong>Опционально</strong><br />root/administrator/components/ - фиксированный путь<br />Добавляется: <strong>имя_компонента/файл.php</strong>,<br />полная ссылка могла бы выглядеть так: :<br />../administrator/components/com_NAME/FILE_TO_CHECK.php');
	define( '_QI_TIP_CM_PATH_CHECK','Для облегчения формирования ссылки здесь содержится список всех установленных компонент.<br />К ссылке добавляется следующая часть [ com_xxxxx ] <strong>и имя компонента, который будет проверяться</strong>!<br />Полная ссылка могла бы быть похожа на::<br />com_costumquickicons/admin.customquickicons.php' );
	define( '_QI_TIP_DETAIL_NEW_WINDOW','Ссылка будет открыта в новом окне');
	define( '_QI_TIP_TITLE',		'<strong>Опционально</strong><br />Здесь вы можете определить текст для всплывающей подсказки.<br />Это свойство очень важно заполнить если вы выбрали отображение только картинки!');
	define( '_QI_TIP_FONT',			'Отметьте необходимые свойства, если хотите изменить формат написания текста ссылки');
	define( '_QI_TIP_ACCESSKEY',	'Определите символ быстрого доступа (шорткат) для этой кнопки.<br />Введите здесь символ который будет использоваться для доступа к функции этой кнопки при нажатии его совместно с клавишей ALT. ВНИМАНИЕ: Этот символ <strong>должен быть уникальным</strong>!');
	define('_QI_TIP_ICON', 			'Пожалуйста, выберите картинку для этой кнопки. Если хотите загрузить собственную картинку для кнопки, то она должна быть загружена в ../administrator/images - ../images ../images/icons' );

	// tabs
	define( '_QI_TABS_GENERAL',		'Общее' );
	define( '_QI_TABS_TEXT',		'Текст');
	define( '_QI_TABS_DISPLAY',		'Отображение' );
	define( '_QI_TABS_CHECK',		'Проверка');
	define( '_QI_TABS_ABOUT',		'О нас...' );

	// title & alt
	define( '_QI_TIT_EDIT_ENTRY',	'Нажмите для редактирования элемента' );
	define( '_QI_TIT_CHOOSE_ICON',	'Нажмите для выбора картинки (откроется в новом окне)' );

	// select lists
		// display
	define( '_QI_DISPLAY_ICON_TEXT','Картинка и текст');
	define( '_QI_DISPLAY_TEXT',		'Только текст');
	define( '_QI_DISPLAY_ICON',		'Только значок');

	// install
	define( '_QI_INST_NEW_ARTICLE',		'Добавить статью/новость' );
	define( '_QI_INST_NEW_ARTICLE_ALT',	'Добавить статью/новость' );
	define( '_QI_INST_SECTIONS',		'Разделы' );
	define( '_QI_INST_SECTIONS_ALT',	'Управление разделами' );
	define( '_QI_INST_FRONTPAGE',		'Главная страница' );
	define( '_QI_INST_FRONTPAGE_ALT',	'Управление объектами главной страницы' );
	define( '_QI_INST_ARTICLE',			'Все содержимое сайта' );
	define( '_QI_INST_ARTICLE_ALT',		'Управление объектами содержимого' );
	define( '_QI_INST_ST_CONTENT',		'Статичное содержимое' );
	define( '_QI_INST_ST_CONTENT_ALT',	'Управление объектами статичного содержимого' );
	define( '_QI_INST_MEDIA',			'Медиа' );
	define( '_QI_INST_MEDIA_ALT',		'Управление медиа файлами' );
	define( '_QI_INST_CATEGORIES',		'Категории' );
	define( '_QI_INST_CATEGORIES_ALT',	'Управление категориями' );
	define( '_QI_INST_WASTE',			'Корзина' );
	define( '_QI_INST_WASTE_ALT',		'Управление корзиной удаленных объектов' );
	define( '_QI_INST_MENUS',			'Меню' );
	define( '_QI_INST_MENUS_ALT',		'Управление объектами меню' );
	define( '_QI_INST_LANGUAGE',		'Языковые пакеты' );
	define( '_QI_INST_LANGUAGE_ALT',	'Управление языковыми файлами' );
	define( '_QI_INST_CONFIG',			'Глобальная конфигурация' );
	define( '_QI_INST_CONFIG_ALT',		'Глобальная конфигурация сайта' );
	define( '_QI_INST_USER',			'Пользователи' );
	define( '_QI_INST_USER_ALT',		'Управление пользователями' );

	// install msgs
	define( '_QI_INST_ERROR',			'В процессе установки компонента возникли некоторые ошибки!');
	define( '_QI_INST_SUCCESS',			'Компонент успешно установлен');
	define( '_QI_INST_DB_ENTRIES',		'База данных');
	define( '_QI_INST_TXT1',			'CustomQuickIcons - расширение для Joomla 1.x который заменяет стандартный модуль <strong style="color:red;"><em>mod_quickicon</em></strong>.<br />Ключевые возможности:<br /><ul><li>Простое управление кнопками быстрого доступа</li><li>Добавление новых кнопок быстрого доступа</li><li>Удаление ненужных кнопок быстрого доступа</li><li>Ссылки на страницы других сайтов в панели быстрого доступа</li><li>Выбор типа отображения кнопок</li><li>Управление доступом</li><li>Встроенная система помощи</li><li>и т.д.</li></ul>');
	define( '_QI_INST_TXT2',			'CQI - CustomQuickIcons успешно установлен'); // changed 2.0.5

	define( '_QI_INST_MSG_BU_OLD_TABLE','Таблицы от предыдущей версии успешно сохранены' ); // new 2.0.5
	define( '_QI_INST_MSG_NEW_TABLE',	'Новые таблицы созданы в базе данных' ); // new 2.0.5
	define( '_QI_INST_MSG_DB_ENTRIES',	'Изменения в базе данных завершены' ); // new 2.0.5
	define( '_QI_INST_MSG_MOD_FILE',	'Файл модуля %s успешно скопирован' ); // new 2.0.5
	define( '_QI_INST_MSG_MOD_REGGED',	'Модуль CQI успешно зарегистрирован' ); // new 2.0.5

	define( '_QI_INST_ERR_COPY_MOD_FILE','ОШИБКА: Копирование файла модуля не удалось скопировать' ); // new 2.0.5
	define( '_QI_INST_ERR_TARGET_DIR',	'ОШИБКА: Не удалось создать необходимую директорию' ); // new 2.0.5

	// alt
	define( '_QI_INST_ALT_WEBSITE',		'Официальный сайт QuickIcons');
	define( '_QI_INST_ALT_ACT_VERSION',	'Последняя версия');
	define( '_QI_INST_ALT_BUGTRACKER',	'Сообщить об ошибке');
	define( '_QI_INST_ALT_FORUM',		'Форум');
	define( '_QI_INST_ALT_EMAIL',		'Email');

	//  errors
	define( '_QI_ERR_NO_MOD_INSTALLED',	'Модуль <em>mod_customquickicons</em> не установлен!' );
	define( '_QI_ERR_MOD_INCORR_POS',	'Модуль [ mod_customquickicons ] не опубликован в позиции [ icon ]' ); // new 2.0.5

	// support (new 2.0.5)
	define( '_QI_SUPP1',			'Если Вы находите этот компонентный полезным и удовлетворены его работой, то возможно вы захотите поддержать наш проект, чтобы мы могли продолжать его поддержку и развитие. В любом случае благодарим Вас!' );
	define( '_QI_SUPP2',			'<br /><br />Вы можете послать деньги через один из указанных шлюзов оплаты.<br />Любая сумма буден нам полезна.' );
	define( '_QI_SUPP_BTN_TITLE',	'Поддержка CQI' );
	define( '_QI_SUPP_HEAD_TITLE',	'CustomQuickIcons - поддержка индивидуальной панели быстрого доступа' );
	define( '_QI_SUPP_INP_TXT',		'Поддержка CustomQuickIcons' );
	define( '_QI_SUPP_BTN_SUBMIT',	'Да, я хочу получить поддержку' );
	define( '_QI_SUPP_TXT_PAY_W_MB','Платеж через Moneybookers' );

	// readme (new 2.0.5)
	define( '_QI_RM_VERSION',	'Версия' );
	define( '_QI_RM_BY',		'By' );
	define( '_QI_RM_COPYR',		'Copyright' );
	define( '_QI_RM_LICENSE',	'Лицензия <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="GPL in English">GPL in English</a>' );
	define( '_QI_RM_BASED',		'Основан на скрипте' );
	define( '_QI_RM_NO_MOD',	'ВНИМАНИЕ:</span> Для функционирования компонента необходимо загрузить модуль <strong>mod_customquickicons</strong> (смотрите ниже), который должен быть установлен и опубликован на сайте, а оригинальный модуль необходимо снять с публикации!' );
	define( '_QI_RM_TRANS_BY',	'Переведено' );
	define( '_QI_RM_TRANS_HU',	'Болгарский' );
	define( '_QI_RM_NEW_TRANS',	'Если Вы сделали перевод на любой другой язык, то перейдите в %s и пришлите файл перевода' );
	define( '_QI_RM_DELETE',	'ВНИМАНИЕ: Если вы удалите этот компонент, то таблицы в базе данных будут сохранены! Модуль CQI будет удален, а стандартный модуль будет активирован.' );
	// joostina
	define( '_QI_ICO',	'Значок' );
}
?>