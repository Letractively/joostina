# $Id: joostina.sql Joostina 1.3.1 boston $

--
-- Структура таблицы `#__categories`
--

CREATE TABLE IF NOT EXISTS `#__categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '',
  `section` varchar(50) NOT NULL DEFAULT '',
  `image_position` varchar(10) NOT NULL DEFAULT '',
  `description` text,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `params` text,
  `templates` text,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Структура таблицы `#__components`
--

CREATE TABLE IF NOT EXISTS `#__components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `menuid` int(11) unsigned NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_menu_link` varchar(255) NOT NULL DEFAULT '',
  `admin_menu_alt` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(50) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `admin_menu_img` varchar(255) NOT NULL DEFAULT '',
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__components`
--

INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`) VALUES
(null, 'Страницо', 'option=com_pages', 0, 0, 'Страницо', 'Страницо', 'com_pages', 0, 'js/ThemeOffice/checkin.png', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `#__config`
--

CREATE TABLE IF NOT EXISTS `#__config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__core_acl_aro`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aro` (
  `aro_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aro_id`),
  UNIQUE KEY `value` (`value`),
  UNIQUE KEY `gacl_section_value_value_aro` (`section_value`(100),`value`(100)),
  KEY `gacl_hidden_aro` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Структура таблицы `#__core_acl_aro_groups`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aro_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `#__gacl_parent_id_aro_groups` (`parent_id`),
  KEY `#__gacl_lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__core_acl_aro_groups`
--

INSERT INTO `#__core_acl_aro_groups` (`group_id`, `parent_id`, `name`, `lft`, `rgt`) VALUES
(17, 0, 'ROOT', 1, 22),
(28, 17, 'USERS', 2, 21),
(29, 28, 'Public Frontend', 3, 12),
(18, 29, 'Registered', 4, 11),
(19, 18, 'Author', 5, 10),
(20, 19, 'Editor', 6, 9),
(21, 20, 'Publisher', 7, 8),
(30, 28, 'Public Backend', 13, 20),
(23, 30, 'Manager', 14, 19),
(24, 23, 'Administrator', 15, 18),
(25, 24, 'Super Administrator', 16, 17);

-- --------------------------------------------------------

--
-- Структура таблицы `#__core_acl_aro_sections`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_aro_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(230) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`),
  UNIQUE KEY `value_aro_sections` (`value`),
  KEY `hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__core_acl_aro_sections`
--

INSERT INTO `#__core_acl_aro_sections` (`section_id`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', 1, 'Users', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `#__core_acl_groups_aro_map`
--

CREATE TABLE IF NOT EXISTS `#__core_acl_groups_aro_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '',
  `aro_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`),
  KEY `aro_id` (`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__core_acl_groups_aro_map`
--

INSERT INTO `#__core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`) VALUES
(25, '', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `#__groups`
--

CREATE TABLE IF NOT EXISTS `#__groups` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__groups`
--

INSERT INTO `#__groups` (`id`, `name`) VALUES
(0, 'Общий'),
(1, 'Участники'),
(2, 'Специальный');

-- --------------------------------------------------------

--
-- Структура таблицы `#__mambots`
--

CREATE TABLE IF NOT EXISTS `#__mambots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `element` varchar(100) NOT NULL DEFAULT '',
  `folder` varchar(100) NOT NULL DEFAULT '',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `iscore` tinyint(3) NOT NULL DEFAULT '0',
  `client_id` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `idx_folder` (`published`,`client_id`,`access`,`folder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__mambots`
--


-- --------------------------------------------------------

--
-- Структура таблицы `#__menu`
--

CREATE TABLE IF NOT EXISTS `#__menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(25) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `link_title` varchar(200) NOT NULL,
  `link` text,
  `type` varchar(50) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `componentid` int(11) unsigned NOT NULL DEFAULT '0',
  `sublevel` int(11) DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pollid` int(11) NOT NULL DEFAULT '0',
  `browserNav` tinyint(4) DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `utaccess` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `componentid` (`componentid`,`menutype`,`published`,`access`),
  KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__menu`
--

INSERT INTO `#__menu` (`id`, `menutype`, `name`, `page_title`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) VALUES
(null, 'mainmenu', 'Главная', 'werwerwer werwer werewew ', 'index.php?option=com_pages', 'components', 1, 0, 15, 0, 1, 62, '2010-04-29 16:40:28', 0, 0, 0, 0, '{ "page_id": "1" }');

-- --------------------------------------------------------

--
-- Структура таблицы `#__modules`
--

CREATE TABLE IF NOT EXISTS `#__modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(10) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `numnews` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text,
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `cache_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__modules`
--

INSERT INTO `#__modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `cache_time`) VALUES
(3, 'Главное меню', '', 2, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_mljoostinamenu', 0, 0, 0, '', 0, 0, 32767),
(4, 'Авторизация', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_login', 0, 0, 0, '', 0, 0, 0),
(17, 'Верхнее меню', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 0, '', 0, 0, 0),
(19, 'Компоненты', '', 2, 'cpanel', 0, '0000-00-00 00:00:00', 0, 'mod_components', 0, 99, 1, '', 0, 1, 0),
(23, 'Последние зарегистрированные пользователи', '', 4, 'advert2', 0, '0000-00-00 00:00:00', 0, 'mod_latest_users', 0, 99, 1, '', 0, 1, 0),
(26, 'Полное меню', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_fullmenu', 0, 99, 1, '', 0, 1, 0),
(27, 'Путь', '', 1, 'pathway', 0, '0000-00-00 00:00:00', 1, 'mod_pathway', 0, 99, 1, '', 0, 1, 0),
(28, 'Панель инструментов', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 99, 1, '', 0, 1, 0),
(29, 'Системные сообщения', '', 1, 'inset', 0, '0000-00-00 00:00:00', 1, 'mod_mosmsg', 0, 99, 1, '', 0, 1, 0),
(30, 'Кнопки быстрого доступа', '', 2, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicons', 0, 99, 1, '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `#__modules_menu`
--

CREATE TABLE IF NOT EXISTS `#__modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__modules_menu`
--

INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES
(1, 0),
(3, 0),
(4, 0),
(10, 1),
(17, 0),
(18, 0),
(30, 0),
(37, 1),
(38, 1),
(39, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `#__pages`
--

CREATE TABLE IF NOT EXISTS `#__pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `title_page` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__quickicons`
--

CREATE TABLE IF NOT EXISTS `#__quickicons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(64) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  `new_window` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `gid` int(3) DEFAULT '25',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `#__quickicons`
--

INSERT INTO `#__quickicons` (`id`, `text`, `target`, `icon`, `ordering`, `new_window`, `published`, `title`, `display`, `access`, `gid`) VALUES
(null, 'Категории', 'index2.php?option=com_categories&section=content', '/administrator/templates/joostfree/images/cpanel_ico/categories.png', 5, 0, 1, 'Управление категориями', 0, 0, 0),
(null, 'Редактор меню', 'index2.php?option=com_menumanager', '/administrator/templates/joostfree/images/cpanel_ico/menu.png', 9, 0, 1, 'Управление объектами меню', 0, 0, 24),
(null, 'Пользователи', 'index2.php?option=com_users', '/administrator/templates/joostfree/images/cpanel_ico/user.png', 10, 0, 1, 'Управление пользователями', 0, 0, 24),
(null, 'Глобальная конфигурация', 'index2.php?option=com_config&hidemainmenu=1', '/administrator/templates/joostfree/images/cpanel_ico/config.png', 13, 0, 1, 'Глобальная конфигурация сайта', 0, 0, 25),
(null, 'Очистить весь кэш', 'index2.php?option=com_admin&task=clean_all_cache', '/administrator/templates/joostfree/images/cpanel_ico/clear.png', 14, 0, 1, 'Очистить весь кэш сайта', 0, 0, 24),
(null, 'Странички', 'index2.php?option=com_pages', '/administrator/templates/joostfree/images/cpanel_ico/all_typed.png', 0, 0, 1, '', 0, 0, 25);

-- --------------------------------------------------------

--
-- Структура таблицы `#__session`
--

CREATE TABLE IF NOT EXISTS `#__session` (
  `username` varchar(50) DEFAULT '',
  `time` varchar(14) DEFAULT '',
  `session_id` varchar(200) NOT NULL DEFAULT '0',
  `guest` tinyint(4) DEFAULT '1',
  `userid` int(11) DEFAULT '0',
  `usertype` varchar(50) DEFAULT '',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`(64)),
  KEY `whosonline` (`guest`,`usertype`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__templates_menu`
--

CREATE TABLE IF NOT EXISTS `#__templates_menu` (
  `template` varchar(50) NOT NULL DEFAULT '',
  `menuid` int(11) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__templates_menu`
--

INSERT INTO `#__templates_menu` (`template`, `menuid`, `client_id`) VALUES
('newline2', 0, 0),
('joostfree', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `#__template_positions`
--

CREATE TABLE IF NOT EXISTS `#__template_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__template_positions`
--

INSERT INTO `#__template_positions` (`id`, `position`, `description`) VALUES
(1, 'left', ''),
(2, 'right', ''),
(3, 'top', ''),
(4, 'bottom', ''),
(5, 'inset', ''),
(6, 'banner', ''),
(7, 'header', ''),
(8, 'footer', ''),
(9, 'newsflash', ''),
(10, 'legals', ''),
(11, 'pathway', ''),
(12, 'toolbar', ''),
(13, 'cpanel', ''),
(14, 'user1', ''),
(15, 'user2', ''),
(16, 'user3', ''),
(17, 'user4', ''),
(18, 'user5', ''),
(19, 'user6', ''),
(20, 'user7', ''),
(21, 'user8', ''),
(22, 'user9', ''),
(23, 'advert1', ''),
(24, 'advert2', ''),
(25, 'advert3', ''),
(26, 'icon', ''),
(27, 'debug', '');

-- --------------------------------------------------------

--
-- Структура таблицы `#__users`
--

CREATE TABLE IF NOT EXISTS `#__users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text,
  `bad_auth_count` int(2) DEFAULT '0',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`block`,`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__users_extra`
--

CREATE TABLE IF NOT EXISTS `#__users_extra` (
  `user_id` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `about` tinytext,
  `location` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icq` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `jabber` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `yahoo` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `mobil` varchar(255) NOT NULL,
  `birthdate` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__usertypes`
--

CREATE TABLE IF NOT EXISTS `#__usertypes` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `mask` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `#__usertypes`
--

INSERT INTO `#__usertypes` (`id`, `name`, `mask`) VALUES
(0, 'superadministrator', ''),
(1, 'administrator', ''),
(2, 'editor', ''),
(3, 'user', ''),
(4, 'author', ''),
(5, 'publisher', ''),
(6, 'manager', '');