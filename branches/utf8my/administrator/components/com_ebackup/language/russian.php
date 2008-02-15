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

DEFINE('_BBKP_DATE_FORMAT_LC2',"%d.%m.%Y %H:%M");
DEFINE('_DATE_FORMAT_LC3',"%d. %B %Y um %H:%M:%S");

DEFINE('_BBKP_HEAD_1', "Бэкап создан Русской версией eBackup для Joomla.\n# Локализация команды Русского Дома Joomla. www.joom.ru");
DEFINE('_BBKP_HEAD_2', "Название базы данных   ");
DEFINE('_BBKP_HEAD_3', "Бэкап создан                 ");
DEFINE('_BBKP_HEAD_4', "eBackup - Copyright 2005 by Harald Baer");
DEFINE('_BBKP_HEAD_5', "Структура");
DEFINE('_BBKP_HEAD_6', "Данные");
DEFINE('_BBKP_HEAD_7', "Таблиц                       ");
DEFINE('_BBKP_ENVIRONMENT', "Environment");
DEFINE('_BBKP_SQL_SERVER', "MySQL сервер");
DEFINE('_BBKP_SQL_CLIENT', "MySQL клиент");
DEFINE('_BBKP_PHP_VERSION', "Версия PHP ");
DEFINE('_BBKP_OVERHEAD', "Заголовок");
DEFINE('_BBKP_LINES', "Записей");
DEFINE('_BBKP_TABLES', "Таблиц:");
DEFINE('_BBKP_SIZES', "Размер");
DEFINE('_BBKP_AUTO_INC', "Инскримент");
DEFINE('_BBKP_CREATE_TIME', "Создана");
DEFINE('_BBKP_CHECK_TIME', "Проверка");
DEFINE('_BBKP_FILESIZE', "Размер получившегося файла:");
DEFINE('_BBKP_FILENAME', "Имя файла:");
DEFINE('_BBKP_TIME', "Создано за:");
DEFINE('_BBKP_SECONDS', "Секунд");
DEFINE('_BBKP_DATE', "Дата");
DEFINE('_BBKP_SQL_INFO', "Информация о SQL");
DEFINE('_BBKP_DEL', "Удалить");
DEFINE('_BBKP_DOWNLOAD', "Скачать");
DEFINE('_BBKP_SETUP_TITLE', "Настройки");
DEFINE('_BBKP_FULL_INSERTS', "Дописывать 'INSERT' (данные)");
DEFINE('_BBKP_AUTOINCREMENT', "Вставлять значения AUTO_INCREMENT");
DEFINE('_BBKP_TABLE_FILTER', "Только доступные таблицы");
DEFINE('_BBKP_RUN_TIME', "Макс. время выполнения (сек)");
DEFINE('_BBKP_DROP',"Дописывать 'DROP TABLE'         ");
DEFINE('_BBKP_EXISTS',"Дописывать 'IF NOT EXISTS'        ");
DEFINE('_BBKP_DB_COMP', "Совместимость MySQL экспорта");
DEFINE('_BBKP_DB_AUTO_INC', "Auto Inc.                   ");
DEFINE('_BBKP_SETTINGS', "Опции");
DEFINE('_BBKP_GZIP', "Сжимать в gzip");
DEFINE('_BBKP_CHECK_OP', "OP");
DEFINE('_BBKP_CHECK_TYPE', "Тип");
DEFINE('_BBKP_CHECK_MESSAGE', "Статус");
DEFINE('_BBKP_BACKUP_WORKING', "Создаётся бэкап...");
DEFINE('_BBKP_BACKUP_TABLE', "Текущая таблица");
DEFINE('_BBKP_BACKUP_RECORD', "Запись");
DEFINE('_BBKP_DELAY_TIME', "Пауза до следующей сессии");
DEFINE('_BBKP_EMAIL', "Отправлять на eMail");

?>
