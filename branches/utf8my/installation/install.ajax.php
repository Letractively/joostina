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

// устанавливаем родительский флаг
define( '_VALID_MOS', 1 );
// проверка файла конфигурации
if (!file_exists( '../configuration.php' )) {
	die('NON config file');
}

require_once( '../configuration.php' );
// попытка удаления каталогу установки
if(!deldir($mosConfig_absolute_path.'/installation/')) echo 'Error!'; else echo 'www.joostina.ru';


function deldir( $dir ) {
	$current_dir = opendir( $dir );
	$old_umask = umask(0);
	while ($entryname = readdir( $current_dir )) {
		if ($entryname != '.' and $entryname != '..') {
			if (is_dir( $dir . $entryname )) {
				@deldir( $dir . $entryname.'/' ) ;
			} else {
				@chmod($dir . $entryname, 0777);
				@unlink( $dir . $entryname );
			}
		}
	}
	@umask($old_umask);
	@closedir( $current_dir );
	return @rmdir( $dir );
}

?>
