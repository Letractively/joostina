-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Фев 17 2010 г., 19:49
-- Версия сервера: 5.1.37
-- Версия PHP: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `games`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jos_articles`
--

CREATE TABLE IF NOT EXISTS `jos_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(5) unsigned NOT NULL DEFAULT '0',
  `game_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `anons` text NOT NULL,
  `image` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `source` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `game_score` text NOT NULL,
  `recommend` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `jos_articles`
--

INSERT INTO `jos_articles` (`id`, `cat_id`, `game_id`, `title`, `slug`, `anons`, `image`, `text`, `source`, `created`, `user_id`, `state`, `game_score`, `recommend`, `meta_description`, `meta_keywords`) VALUES
(1, 2, 1, 'Первая игруха!', 'тыц-тыц', '', '', '', 'Рупампам!', '0000-00-00 00:00:00', 0, 10, '', '', 'Ой дааа!', 'Нет нееет!'),
(2, 7, 2, ' 443534534', '5 3454354', '', '', '', '3ertretret re', '0000-00-00 00:00:00', 0, 0, '', '', 'tertretr', 'etertertertert'),
(3, 2, 2, ' 234werewre', ' werew ewr w', '', '', '', 'wrewrewrewr', '0000-00-00 00:00:00', 0, 0, '', '', 'werew ', ' werewr ');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_components`
--

CREATE TABLE IF NOT EXISTS `jos_components` (
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
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `jos_components`
--

INSERT INTO `jos_components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`) VALUES
(1, 'Содержимое', 'option=com_articles', 0, 0, 'option=com_articles', '', 'com_articles', 0, '', 0, ''),
(15, 'Авторизация', 'option=com_login', 0, 0, '', '', 'com_login', 0, '', 1, ''),
(16, 'Поиск', 'option=com_search', 0, 0, '', '', 'com_search', 0, '', 1, ''),
(26, 'Игры', 'option=com_games', 0, 0, 'option=com_games', '', 'com_games', 0, '', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_config`
--

CREATE TABLE IF NOT EXISTS `jos_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL,
  `subgroup` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `jos_config`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro` (
  `aro_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aro_id`),
  UNIQUE KEY `value` (`value`),
  UNIQUE KEY `jos_gacl_section_value_value_aro` (`section_value`(100),`value`(100)),
  KEY `jos_gacl_hidden_aro` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_core_acl_aro`
--

INSERT INTO `jos_core_acl_aro` (`aro_id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', '62', 0, 'Administrator', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro_groups`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `jos_gacl_parent_id_aro_groups` (`parent_id`),
  KEY `jos_gacl_lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Дамп данных таблицы `jos_core_acl_aro_groups`
--

INSERT INTO `jos_core_acl_aro_groups` (`group_id`, `parent_id`, `name`, `lft`, `rgt`) VALUES
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
-- Структура таблицы `jos_core_acl_aro_sections`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(230) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`),
  UNIQUE KEY `value_aro_sections` (`value`),
  KEY `hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_core_acl_aro_sections`
--

INSERT INTO `jos_core_acl_aro_sections` (`section_id`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', 1, 'Users', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_groups_aro_map`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_groups_aro_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '',
  `aro_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`),
  KEY `aro_id` (`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_core_acl_groups_aro_map`
--

INSERT INTO `jos_core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`) VALUES
(25, '', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_log_items`
--

CREATE TABLE IF NOT EXISTS `jos_core_log_items` (
  `time_stamp` date NOT NULL DEFAULT '0000-00-00',
  `item_table` varchar(50) NOT NULL DEFAULT '',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_core_log_items`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_log_searches`
--

CREATE TABLE IF NOT EXISTS `jos_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  KEY `hits` (`hits`),
  KEY `search_term` (`search_term`(16))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_core_log_searches`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_games`
--

CREATE TABLE IF NOT EXISTS `jos_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `name_rus` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `desc` text NOT NULL,
  `date` datetime NOT NULL,
  `date_rus` datetime NOT NULL,
  `developer` varchar(200) NOT NULL,
  `publisher` varchar(200) NOT NULL,
  `publisher_rus` varchar(200) NOT NULL,
  `localizer` varchar(200) NOT NULL,
  `site` varchar(200) NOT NULL,
  `site_rus` varchar(200) NOT NULL,
  `min_req` text NOT NULL,
  `recom_req` text NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `our_choise` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `jos_games`
--

INSERT INTO `jos_games` (`id`, `name`, `name_rus`, `slug`, `desc`, `date`, `date_rus`, `developer`, `publisher`, `publisher_rus`, `localizer`, `site`, `site_rus`, `min_req`, `recom_req`, `state`, `our_choise`, `meta_description`, `meta_keywords`) VALUES
(1, 'Игра номер раз! 1111', 'Игрушка номер два', 'Тыц-тыц', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'Метыдеск', 'Метакэй'),
(2, 'Онотоле атаке!', 'Онотоле атаке!Онотоле атаке!', 'Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!', 'Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!Онотоле атаке!'),
(3, 'ц укуцкуц кцукуц куцкцук', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, '', ''),
(4, ' цук ццукуц куцкуц куцку', ' цук уц', 'куцкуц к', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'уцкуцкуц к', 'цукцукуцк'),
(5, 'у цку уцк', 'цк цуку 3242', 'цук-уцк-уц-куц-куцк', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'цу кцук', 'ц укуц куцк уцк'),
(6, '3423432к4цуу', 'цу кцк цу', ' 234кцу', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'кцукуц', 'куцкуцкцукуц'),
(7, ' 23432цукуц куц', 'куцкуцкуц', ' цукцукуцк уц', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 0, 0, 'цукуцкуцку', ' цукц кц');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_groups`
--

CREATE TABLE IF NOT EXISTS `jos_groups` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_groups`
--

INSERT INTO `jos_groups` (`id`, `name`) VALUES
(0, 'Общий'),
(1, 'Участники'),
(2, 'Специальный');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_menu`
--

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(25) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
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
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `componentid` (`componentid`,`menutype`,`published`,`access`),
  KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_menu`
--

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) VALUES
(1, 'mainmenu', 'Главная', 'index.php?option=com_main', 'components', 1, 0, 10, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules`
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(10) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `numnews` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES
(26, 'Полное меню', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_fullmenu', 0, 99, 1, '', 1, 1),
(28, 'Панель инструментов', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 99, 1, '', 1, 1),
(30, 'Кнопки быстрого доступа', '', 2, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicons', 0, 99, 1, '', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules_menu`
--

CREATE TABLE IF NOT EXISTS `jos_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_modules_menu`
--

INSERT INTO `jos_modules_menu` (`moduleid`, `menuid`) VALUES
(4, 0),
(5, 0),
(6, 1),
(17, 0),
(18, 0),
(30, 0),
(37, 1),
(38, 1),
(39, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_quickicons`
--

CREATE TABLE IF NOT EXISTS `jos_quickicons` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `jos_quickicons`
--

INSERT INTO `jos_quickicons` (`id`, `text`, `target`, `icon`, `ordering`, `new_window`, `published`, `title`, `display`, `access`, `gid`) VALUES
(9, 'Редактор меню', 'index2.php?option=com_menumanager', '/administrator/templates/joostfree/images/cpanel_ico/menu.png', 9, 0, 1, 'Управление объектами меню', 0, 0, 24),
(11, 'Пользователи', 'index2.php?option=com_users', '/administrator/templates/joostfree/images/cpanel_ico/user.png', 10, 0, 1, 'Управление пользователями', 0, 0, 24),
(12, 'Глобальная конфигурация', 'index2.php?option=com_config&hidemainmenu=1', '/administrator/templates/joostfree/images/cpanel_ico/config.png', 13, 0, 1, 'Глобальная конфигурация сайта', 0, 0, 25),
(14, 'Очистить весь кэш', 'index2.php?option=com_admin&task=clean_all_cache', '/administrator/templates/joostfree/images/cpanel_ico/clear.png', 14, 0, 1, 'Очистить весь кэш сайта', 0, 0, 24);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_session`
--

CREATE TABLE IF NOT EXISTS `jos_session` (
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

--
-- Дамп данных таблицы `jos_session`
--

INSERT INTO `jos_session` (`username`, `time`, `session_id`, `guest`, `userid`, `usertype`, `gid`) VALUES
('', '1266392661', 'bd69250733f806ba8cf0ecdd2c72dd4a', 1, 0, '', 0),
('boston', '1266407757', '5954ba04d067374c052dec83bc8d470b', 1, 62, 'Super Administrator', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_template_positions`
--

CREATE TABLE IF NOT EXISTS `jos_template_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `jos_template_positions`
--

INSERT INTO `jos_template_positions` (`id`, `position`, `description`) VALUES
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
-- Структура таблицы `jos_users`
--

CREATE TABLE IF NOT EXISTS `jos_users` (
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
  `params` text NOT NULL,
  `bad_auth_count` int(2) DEFAULT '0',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `idxemail` (`email`),
  KEY `block_id` (`block`,`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `gid`, `registerDate`, `lastvisitDate`, `activation`, `params`, `bad_auth_count`, `avatar`) VALUES
(62, 'Administrator', 'boston', 'bost56@gmail.com', 'f9d35b6e17a439eb966c9f2f2310856c:EAuzS0aYUPkYmv5y', 'Super Administrator', 0, 1, 25, '2010-02-12 16:20:02', '2010-02-16 20:58:04', '', 'expired=\nexpired_time=', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users_extra`
--

CREATE TABLE IF NOT EXISTS `jos_users_extra` (
  `user_id` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `about` tinytext NOT NULL,
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

--
-- Дамп данных таблицы `jos_users_extra`
--


-- --------------------------------------------------------

--
-- Структура таблицы `jos_usertypes`
--

CREATE TABLE IF NOT EXISTS `jos_usertypes` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `mask` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_usertypes`
--

INSERT INTO `jos_usertypes` (`id`, `name`, `mask`) VALUES
(0, 'superadministrator', ''),
(1, 'administrator', ''),
(2, 'editor', ''),
(3, 'user', ''),
(4, 'author', ''),
(5, 'publisher', ''),
(6, 'manager', '');

