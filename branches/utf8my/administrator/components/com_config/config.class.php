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
require(dirname(__FILE__).'/../../die.php');

/**
* @package Joostina
* @subpackage Config
*/
class mosConfig {
// Site Settings
	/** @var int */
	var $config_offline			= null;
	/** @var string */
	var $config_offline_message	= null;
	/** @var string */
	var $config_error_message	= null;
	/** @var string */
	var $config_sitename		= null;
	/** @var string */
	var $config_editor			='jce';
	/** @var int */
	var $config_list_limit		= 30;
	/** @var string */
	var $config_favicon			= null;
	/** @var string */
	var $config_frontend_login	= 1;

// Debug
	/** @var int */
	var $config_debug=0;

// Database Settings
	/** @var string */
	var $config_host			= null;
	/** @var string */
	var $config_user			= null;
	/** @var string */
	var $config_password		= null;
	/** @var string */
	var $config_db				= null;
	/** @var string */
	var $config_dbprefix		= null;

// Server Settings
	/** @var string */
	var $config_absolute_path	= null;
	/** @var string */
	var $config_live_site		= null;
	/** @var string */
	var $config_secret			= null;
	/** @var int */
	var $config_gzip			= 0;
	/** @var int */
	var $config_lifetime			= 900;
	/** @var int */
	var $config_session_life_admin	= 1800;
	/** @var int */
	var $config_admin_expired		= '1';
	/** @var int */
	var $config_session_type	= 0;
	/** @var int */
	var $config_error_reporting	= 0;
	/** @var string */
	var $config_helpurl			= 'http://help.joom.ru';
	/** @var string */
	var $config_fileperms		= '0644';
	/** @var string */
	var $config_dirperms		= '0755';

// Locale Settings
	/** @var string */
	var $config_locale			= null;
	/** @var string */
	var $config_lang			= null;
	/** @var int */
	var $config_offset			= null;
	/** @var int */
	var $config_offset_user		= null;

// Mail Settings
	/** @var string */
	var $config_mailer			= null;
	/** @var string */
	var $config_mailfrom		= null;
	/** @var string */
	var $config_fromname		= null;
	/** @var string */
	var $config_sendmail		= '/usr/sbin/sendmail';
	/** @var string */
	var $config_smtpauth		= 0;
	/** @var string */
	var $config_smtpuser		= null;
	/** @var string */
	var $config_smtppass		= null;
	/** @var string */
	var $config_smtphost		= null;

// Cache Settings
	/** @var int */
	var $config_caching			= 0;
	/** @var string */
	var $config_cachepath		= null;
	/** @var string */
	var $config_cachetime		= null;

// User Settings
	/** @var int */
	var $config_allowUserRegistration	= 0;
	/** @var int */
	var $config_useractivation			= null;
	/** @var int */
	var $config_uniquemail				= null;
	/** @var int */
	var $config_shownoauth				= 0;
	/** @var int */
	var $config_frontend_userparams		= 1;

// Meta Settings
	/** @var string */
	var $config_MetaDesc		= null;
	/** @var string */
	var $config_MetaKeys		= null;
	/** @var int */
	var $config_MetaTitle		= null;
	/** @var int */
	var $config_MetaAuthor		= null;

// Statistics Settings
	/** @var int */
	var $config_enable_log_searches	= null;
	/** @var int */
	var $config_enable_stats		= null;
	/** @var int */
	var $config_enable_log_items	= null;

// SEO настройки
	/** @var int */
	var $config_sef=0;
	/** @var int */
	var $config_pagetitles=1;

// Content Settings
	/** @var int */
	var $config_link_titles		= 0;
	/** @var int */
	var $config_readmore		= 1;
	/** @var int */
	var $config_vote			= 0;
	/** @var int */
	var $config_hideAuthor		= 0;
	/** @var int */
	var $config_hideCreateDate	= 0;
	/** @var int */
	var $config_hideModifyDate	= 0;
	/** @var int */
	var $config_hits			= 1;
	/** @var int */
	var $config_hidePrint		= 0;
	/** @var int */
	var $config_hideEmail		= 0;
	/** @var int */
	var $config_icons			= 1;
	/** @var int */
	var $config_back_button		= 0;
	/** @var int */
	var $config_item_navigation	= 0;
	/** @var int */
	var $config_multilingual_support = 0;
	/** @var int */
	var $config_multipage_toc	= 0;
	/** Режим работы с itemid, 0 - прежний режим */
	var $config_itemid_compat	= 0;
// boston, дополнения
	/** @var int отключение ведения сессий на фронте */
	var $config_session_front = 0;
	/** @var int отключение syndicate */
	var $config_syndicate_off = 0;
	/**  @var int отключение тега Generator */
	var $config_generator_off = 0;
	/** @var int отключение мамботов группы system */
	var $config_mmb_system_off = 0;
	/** @var str использование одного шаблона на весь сайт */
	var $config_one_template = '...';
	/** @var int подсчет времени генерации страницы */
	var $config_time_gen = 0;
	/** @var int индексация страницы печати */		
	var $config_index_print = 0;
	/** @var int расширенные теги индексации */
	var $config_index_tag = 0;
	/** @var int отключение модулей на странице редактирования с фронта */
	var $config_module_on_edit_off = 0;
	/** @var int использование ежесуточной оптимизации таблиц базы данных */
	var $config_optimizetables = 1;
	/** @var int отключение мамботов группы content */
	var $config_mmb_content_off = 0;
	/** @var int кэширование меню панели управления */
	var $config_adm_menu_cache = 1;
	/** @var int расположение элементов title */
	var $config_pagetitles_first = 1;
	/** @var string разделитель "заголовок страницы - Название сайта " */
	var $config_tseparator = ' - ';
	/** @int отключение captcha */
	var $config_captcha = 1;
	/** @int очистка ссылки на com_frontpage */
	var $config_com_frontpage_clear = 1;
	/** @str корень для компонента управления медиа содержимым */
	var $config_media_dir = 'images/stories';
	/** @str корень файлового менеджера */
	var $config_joomlaxplorer_dir = null;
	/** @int автоматическая установка "Публиковать на главной" */
	var $config_auto_frontpage = 0;
	/** @int уникальные идентификаторы новостей */
	var $config_uid_news = 0;
	/** @int подсчет прочтений содержимого */
	var $config_content_hits = 1;
	/** @str формат даты */
	var $config_form_date = '%d.%m.%Y г.';
	/** @str полный формат даты и времени */
	var $config_form_date_full = '%d.%m.%Y г. %H:%M';
	/** @int поддержка работы на младших версиях MySQL */
	var $config_dbold = 1;
	/** @int не показывать "Главная" на первой странице */
	var $config_pathway_clean = 1;
	/** @int отключение удаления сессий в панели управления */
	var $config_adm_session_del = 0;
	/** @int отключение кнопки "Помощь" */
	var $config_disable_button_help = 0;
	/** @int отключение блокировок объектов */
	var $config_disable_checked_out = 0;
	/** @int отключение favicon */
	var $config_disable_favicon = 1;
	/** @str смещение для rss*/
	var $config_feed_timeoffset = null;
	/** @int использовать расширенную отладку на фронте */
	var $config_front_debug = 0;
	/** @var int отключение мамботов группы mainbody */
	var $config_mmb_mainbody_off = 0;
	/** @var int автоматическая авторизация после подтверждения регистрации */
	var $config_auto_activ_login = 0;
	/** @var int отключение SET sql_mode */
	var $config_sql_mode_off = 0;
	/** @var int отключение вкладки 'Изображения' */
	var $config_disable_image_tab = 0;
	/** @var int обрамлять заголовки тегом h1 */
	var $config_title_h1 = 0;
	/** @var int обрамлять заголовки тегом h1 только в режиме полного просмотра содержимого */
	var $config_title_h1_only_view = 1;
	/** @var int отключить проверки публикаций по датам */
	var $config_disable_date_state = 0;
	/** @var int отключить контроль доступа к содержимому */
	var $config_disable_access_control = 0;
	/** @var int включение оптимизации функции кэширования */
	var $config_cache_opt = 0;
	/** @var int включение сжатия css и js файлов */
	var $config_gz_js_css = 0;
	/** @var int captcha для регистрации */
	var $config_captcha_reg = 0;
	/** @var int captcha для формы контактов */
	var $config_captcha_cont = 0;



	/**
	 * @return array An array of the public vars in the class
	 */
	function getPublicVars() {
		$public = array();
		$vars = array_keys( get_class_vars( get_class( $this ) ) );
		sort( $vars );
		foreach ($vars as $v) {
			if ($v{0} != '_') {
				$public[] = $v;
			}
		}
		return $public;
	}

	/**
	 *	binds a named array/hash to this object
	 *	@param array $hash named array
	 *	@return null|string	null is operation was satisfactory, otherwise returns an error
	 */
	function bind( $array, $ignore='' ) {
		if (!is_array( $array )) {
			$this->_error = strtolower(get_class( $this )).'::ошибка сборки.';
			return false;
		} else {
			return mosBindArrayToObject( $array, $this, $ignore );
		}
	}

	/**
	 * Writes the configuration file line for a particular variable
	 * @return string
	 */
	function getVarText() {
		$txt = '';
		$vars = $this->getPublicVars();
		foreach ($vars as $v) {
			$k = str_replace( 'config_', 'mosConfig_', $v );
			$txt .= "\$$k = '" . addslashes( $this->$v ) . "';\n";
		}
		return $txt;
	}

	/**
	 * Binds the global configuration variables to the class properties
	 */
	function bindGlobals() {
		$vars = $this->getPublicVars();
		foreach ($vars as $v) {
			$k = str_replace( 'config_', 'mosConfig_', $v );
			if (isset( $GLOBALS[$k] ))
				$this->$v = $GLOBALS[$k];
		}

		/*
		*	Maintain the value of $mosConfig_live_site even if
		*	user signs in with https://
		*/
		require('../configuration.php');
		if( $mosConfig_live_site != $this->config_live_site )
			$this->config_live_site = $mosConfig_live_site;
	}
}
?>
