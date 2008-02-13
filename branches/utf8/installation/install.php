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

define( "_VALID_MOS", 1 );

if (file_exists( '../configuration.php' ) && filesize( '../configuration.php' ) > 10) {
        header( 'Location: ../index.php' );
        exit();
}
/** Include common.php */
include_once( 'common.php' );
function writableCell( $folder ) {
        echo "<tr>";
        echo "<td class=\"item\">" . $folder . "/</td>";
        echo "<td align=\"left\">";
        echo is_writable( "../$folder" ) ? '<b><font color="green">Доступен для записи </font></b>' : '<b><font color="red">Недоступен для записи</font></b>' . "</td>";
        echo "</tr>";
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Joostina - Web-установка. Лицензия ...</title>
 <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link rel="shortcut icon" href="../images/favicon.ico" />
 <link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
 <div id="wrapper">
  <div id="header">
   <div id="joomla"><img src="header_install.png" alt="Установка Joomla" /></div>
  </div>
 </div>
 <div id="ctr" align="center">
  <form action="install1.php" method="post" name="adminForm" id="adminForm">
   <div class="install">
    <div id="stepbar">
     <div class="step-off">Проверка системы</div>
     <div class="step-on">Лицензия</div>
     <div class="step-off">Шаг 1</div>
     <div class="step-off">Шаг 2</div>
     <div class="step-off">Шаг 3</div>
     <div class="step-off">Шаг 4</div>
    </div>
    <div id="right">
     <div id="step">Лицензия</div>
     <div class="far-right">
      <input class="button" type="submit" name="next" value="Далее &gt;&gt;"/>
     </div>
     <div class="clr"></div>
     <h1>Лицензия GNU/GPL:</h1>
     <div class="licensetext">
      Joostina- свободное программное обеспечение, распространяемое по лицензии GNU/GPL, для использования системы Вы должны полностью согласиться с предоставленной лицензией.
     </div>
     <div class="clr"></div>
     <div class="license-form">
      <div class="form-block" style="padding: 0px;">
       <iframe src="gpl.html" class="license" frameborder="0" scrolling="auto"></iframe>
      </div>
     </div>
     <div class="clr"></div>
     <div class="clr"></div>
    </div>
    <div id="break"></div>
    <div class="clr"></div>
    <div class="clr"></div>
   </div>
  </form>
 </div>
 <div class="ctr"><a href="http://www.Joostina.ru" target="_blank">Joostina</a> - свободное программное обеспечение, распространяемое по лицензии GNU/GPL.</div>
</body>
</html>
