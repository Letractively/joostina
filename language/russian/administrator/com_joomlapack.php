<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();

DEFINE('_JP_BACKUPPING','Резервирование');
DEFINE('_JP_PHPINFO','- Информация о PHP -');
DEFINE('_JP_FREEMEMORY','Свободно памяти');
DEFINE('_JP_GZIP_ENABLED','GZIP сжатие   : доступно (это хорошо)');
DEFINE('_JP_GZIP_NOT_ENABLED','GZIP сжатие   : недоступно (это плохо)');
DEFINE('_JP_START_BACKUP_DB','Начало резервирования базы данных');
DEFINE('_JP_START_BACKUP_FILES','Начало резервирования всех данных сайта');
DEFINE('_JP_CUBE_ON_STEP','CUBE :: Работа на шаге');
DEFINE('_JP_CUBE_STEP_FINISHED','CUBE :: Шаг завершён ');
DEFINE('_JP_CUBE_FINISHED','CUBE :: Завершено!');
DEFINE('_JP_ERROR_ON_STEP','CUBE :: Ошибка на шаге ');
DEFINE('_JP_CLEANUP','Очистка');
DEFINE('_JP_RECURSING_DELETION','Рекурсивное удаление ');
DEFINE('_JP_NOT_FILE','Удаление <b>ЭТО ФАЙЛ, НЕ КАТАЛОГ!</b>');
DEFINE('_JP_ERROR_DEL_DIRECTORY','Ошибка удаления каталога. Проверьте права доступа');
DEFINE('_JP_QUICK_MODE','Режим однопроходности');
DEFINE('_JP_QUICK_MODE_ON_STEP','Используется быстрый алгоритм на шаге');
DEFINE('_JP_CANNOT_USE_QUICK_MODE','Невозможно использовать быстрый алгоритм на шаге');
DEFINE('_JP_MULTISTEP_MODE','Режим многопроходности');
DEFINE('_JP_MULTISTEP_MODE_ON_STEP','Используется медленный алгоритм на шаге');
DEFINE('_JP_MULTISTEP_MODE_ERROR','Ошибка выполнения медленного алгоритма на шаге');
DEFINE('_JP_SMART_MODE','Ускоренный режим');
DEFINE('_JP_SMART_MODE_ON_STEP','Выполнение ускоренного режима на шаге');
DEFINE('_JP_SMART_MODE_ERROR','Ошибка выполнения ускоренного режима на шаге');
DEFINE('_JP_CHOOSED_ALGO','Выбран');
DEFINE('_JP_ALGORITHM_FOR','алгоритм для');
DEFINE('_JP_NEXT_STEP_BACKUP_DB','Следующий шаг --> Резервирование базы');
DEFINE('_JP_NEXT_STEP_FILE_LIST','Следующий шаг --> Создание списка файлов');
DEFINE('_JP_NEXT_STEP_FINISHING','Следующий шаг --> Завершение');
DEFINE('_JP_NEXT_STEP_GZIP','Следующий шаг --> Упаковка');
DEFINE('_JP_NEXT_STEP_FINISHED','Следующий шаг --> Завершено');
DEFINE('_JP_NO_NEXT_STEP','Следующий шаг не требуется; всё уже завершено');
DEFINE('_JP_NO_CUBE','Нет существующего CUBE; создание нового');
DEFINE('_JP_CURRENT_STEP','Текущий шаг');
DEFINE('_JP_UNPACKING_CUBE','Распаковка существующего CUBE');
DEFINE('_JP_TIMEOUT','Время на выполнение операции вышло, но операция не завершена');
DEFINE('_JP_FETCHING_TABLE_LIST','CDBBackupEngine :: Получение списка таблиц');
DEFINE('_JP_BACKUP_ONLY_DB','CDBBackupEngine :: Резервирование только базы данных');
DEFINE('_JP_ONE_FILE_STORE','CDBBackupEngine :: Задано объединение файлом');
DEFINE('_JP_FILE_STRUCTURE','CDBBackupEngine :: Файл структуры');
DEFINE('_JP_DATAFILE','CDBBackupEngine :: Файл данных');
DEFINE('_JP_FILE_DELETION','CDBBackupEngine :: Удаление файлов');
DEFINE('_JP_FIRST_STEP','CDBBackupEngine :: Первый проход');
DEFINE('_JP_ALL_COMPLETED','CDBBackupEngine :: Всё завершено');
DEFINE('_JP_START_TICK','CDBBackupEngine :: Начало обработки');
DEFINE('_JP_READY_FOR_TABLE','Выполнено для таблицы');
DEFINE('_JP_DB_BACKUP_COMPLETED','Резервирование базы данных завершено');
DEFINE('_JP_NEW_FRAGMENT_ADDED','Добавлен новый фрагмент');
DEFINE('_JP_KERNEL_TABLES','Таблицы ядра');
DEFINE('_JP_FIRST_STEP_2','Первый проход');
DEFINE('_JP_NEXT_VALUE','Выходное значение');
DEFINE('_JP_SKIP_TABLE','Пропуск таблицы');
DEFINE('_JP_GETTING','Получение');
DEFINE('_JP_COLUMN_FROM','столбца из');
DEFINE('_JP_ERROR_WRITING_FILE','Ошибка записи в файл');
DEFINE('_JP_CANNOT_SAVE_DUMP','Невозможно сохранить в дамп');
DEFINE('_JP_CHECK_RESULTS','Результаты проверки');
DEFINE('_JP_ANALYZE_RESULTS','Результаты анализа');
DEFINE('_JP_OPTIMIZE_RESULTS','Результаты оптимизации');
DEFINE('_JP_REPAIR_RESULTS','Результаты исправления');
DEFINE('_JP_GETTING_DIRS_LIST','Получение списка каталогов для исключения из резервной копии');
DEFINE('_JP_GZIP_FIRST_STEP','Упаковка: первый шаг');
DEFINE('_JP_GZIP_FINISHED','Упаковка :: Завершено');
DEFINE('_JP_PACK_FINISHED','Завершение архивирования');
DEFINE('_JP_GZIP_OF_FRAGMENT','Архивирование фрагмента #');
DEFINE('_JP_CURRENT_FRAGMENT',' Текущий фрагмент');
DEFINE('_JP_DELETE_PATH',' путь для удаления :');
DEFINE('_JP_PATH_TO_DELETE',' путь для добавления ');
DEFINE('_JP_SAVING_ARCHIVE_INFO','Сохранение информации о архивных объектах');
DEFINE('_JP_LOADING_ARCHIVE_INFO','Загрузка информации о архивных объектах');
DEFINE('_JP_ADDING_FILE_TO_ARCHIVE','Добавлений файлов в архив');
DEFINE('_JP_ARCHIVING','Архивирование');
DEFINE('_JP_ARCHIVE_COMPLETED','Архивирование завершено');
DEFINE('_JP_BACKUP_CONFIG','Конфигурация резервного копирования данных');
DEFINE('_JP_CONFIG_SAVING','Сохранение настроек');
DEFINE('_JP_MAIN_CONFIG','Основные настройки');
DEFINE('_JP_CONFIG_DIRECTORY','Каталог сохранения архивов');
DEFINE('_JP_ARCHIVE_NAME','Название архива');
DEFINE('_JP_LOG_LEVEL','Уровень ведения лога');
DEFINE('_JP_ADDITIONAL_CONFIG','Дополнительные настройки');
DEFINE('_JP_DELETE_PREFIX','Удалять преффикс таблиц');
DEFINE('_JP_EXPORT_TYPE','Тип экспорта базы данных');
DEFINE('_JP_FILELIST_ALGORITHM','Обработка файлов');
DEFINE('_JP_CONFIG_DB_BACKUP','Обработка базы');
DEFINE('_JP_CONFIG_GZIP','Сжатие файлов');
DEFINE('_JP_CONFIG_DUMP_GZIP','Сжатие дампа базы данных');
DEFINE('_JP_AVAILABLE','<font color="green"><b>доступно</b></font>');
DEFINE('_JP_NOT_AVAILABLE','<font color="red"><b>недоступно</b></font>');
DEFINE('_JP_MYSQL4_COMPAT','В режиме совместимости с MySQL 4');
DEFINE('_JP_NO_GZIP','Не архивировать (.sql)');
DEFINE('_JP_GZIP_TAR_GZ','Архивировать в TAR.GZ (.tar.gz)');
DEFINE('_JP_GZIP_ZIP','Архивировать в ZIP (.zip)');
DEFINE('_JP_QUICK_METHOD','Быстро - один проход');
DEFINE('_JP_STANDARD_METHOD','Рекомендовано - Стандартно');
DEFINE('_JP_SLOW_METHOD','Медленно - мультипроходность');
DEFINE('_JP_LOG_ERRORS_OLY','Только ошибки');
DEFINE('_JP_LOG_ERROR_WARNINGS','Ошибки и предупреждения');
DEFINE('_JP_LOG_ALL','Вся информация');
DEFINE('_JP_LOG_ALL_DEBUG','Вся информация и отладка');
DEFINE('_JP_DONT_SAVE_DIRECTORIES_IN_BACKUP','Не сохранять каталоги в резервной копии');
DEFINE('_FILE_NAME','Имя файла');
DEFINE('_JP_DOWNLOAD_FILE','Скачать');
DEFINE('_JP_REALLY_DELETE_FILE','Действительно удалить файл?');
DEFINE('_JP_FILE_CREATION_DATE','Создан');
DEFINE('_JP_NO_BACKUPS','Файлы резервных копий отсутствуют');
DEFINE('_JP_ACTIONS_LOG','Лог выполнения действий');
DEFINE('_JP_BACKUP_MANAGEMENT','Резервное копирование');
DEFINE('_JP_CREATE_BACKUP','Создать архив данных');
DEFINE('_DB_MANAGEMENT','Управление базой данных');
DEFINE('_JP_DONT_SAVE_DIRECTORIES','Не сохранять каталоги');
DEFINE('_JP_CONFIG','Настройки сохранения');
DEFINE('_JP_ERRORS_TMP_DIR','Обнаружены ошибки, проверьте возможность записи в каталог хранения резервных копий');
DEFINE('_JP_BACKUP_CREATION','Создание резервной копии данных');
DEFINE('_JP_DONT_CLOSE_BROWSER_WINDOW','Пожалуйста, не закрывайте окно браузера и не переходите с этой страницы до окончания резервирования и отображения соответствующего сообщения.');
DEFINE('_JP_ERRORS_VIEW_LOG','В ходе работы обнаружены ошибки, пожалуйста, <a href="index2.php?option=com_joomlapack&act=log">посмотрите лог</a> работы и выясните причину.');
DEFINE('_JP_BACKUP_SUCCESS','Резервирование данных сайта выполнено успешно. Скачать');
DEFINE('_JP_CREATION_FILELIST','1. Создание списка файлов для архивирования.');
DEFINE('_JP_BACKUPPING_DB','2. Архивирование базы данных.');
DEFINE('_JP_CREATION_OF_ARCHIVE','3. Создание итогового архива.');
DEFINE('_JP_ALL_COMPLETED_2','4. Всё выполнено');
DEFINE('_JP_PROGRESS','Обработка');
DEFINE('_SQL_TABLES','Таблицы');
DEFINE('_DB_NUM_RECORDS','Записей');
DEFINE('_JP_SIZE','Размер');
DEFINE('_JP_INCREMENT','Инскремент');
DEFINE('_JP_CREATION_DATE','Создана');
DEFINE('_JP_CHECKING','Проверка');
DEFINE('_JP_FULL_BACKUP','Полный резерв');
DEFINE('_JP_BACKUP_BASE','Резервировать базу');
DEFINE('_JP_BACKUP_PANEL','Панель резервирования');
DEFINE('_JP_REPAIR','Исправить');
DEFINE('_JP_OPTIMIZE','Оптимизировать');
DEFINE('_JP_ANALYSE','Анализировать');
DEFINE('_JP_CHECK','Проверить');
DEFINE('_SQL_TABLE','Таблица');
DEFINE('_JP_GET_FILE_LISTING','Получение списка файлов');
