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
require(dirname(__FILE__).'/die.php');

require_once( 'includes/joomla.php' );
@include_once ('language/'.$mosConfig_lang.'.php');

global $option, $database;
global $mosConfig_live_site;

// получение шаблона страницы
$cur_template = @$mainframe->getTemplate();
if ( !$cur_template ) {
	$cur_template = 'rhuk_solarflare_ii';
}

// Вывод HTML

// требуется для разделения номера ISO из константы языкового файла _ISO
$iso = split( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
table.moswarning {
	font-size: 200%;
	background-color: #c00;
	color: #fff;
	border-bottom: 2px solid #600
}

table.moswarning h2 {
	padding: 0;
	margin: 0;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
}

</style>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<title><?php echo $mosConfig_sitename; ?> - Сайт выключен</title>
<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/templates/<?php echo $cur_template;?>/css/template_css.css" type="text/css" />
</head>
<body style="margin: 0px; padding: 0px;">

<table width="100%" align="center" class="moswarning">
<?php 
if ( $mosConfig_offline == 1 ) { 
	?>
	<tr>
		<td>
			<h2>
			<?php
			echo $mosConfig_sitename;
			echo ' - ';
			echo $mosConfig_offline_message;
			?>
			</h2>
		</td>
	</tr>
	<?php
} else if ( @$mosSystemError ){

	?>
	<tr>
		<td>
			<h2>
			<?php echo $mosConfig_error_message; ?>
			</h2>
			<?php echo $mosSystemError; ?>
		</td>
	</tr>
	<?php
} else {
	?>
	<tr>
		<td>
			<h2>
			<?php echo 'INSTALL_WARN'; ?>
			</h2>
		</td>
	</tr>
	<?php
}
?>
</table>

</body>
</html>