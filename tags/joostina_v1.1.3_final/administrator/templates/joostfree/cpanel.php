<?php
/** joostfree
* @version 1.0
* @локализация и корректировка (C) 2007 Joom.Ru
* @translator Nikoay P. Kirsh aka boston (boston56@mail.ru)
*/

defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен.' );

$tabs = new mosTabs(0,1);
	$tabs->startPane("ico");
	$tabs->startTab("Кнопки","ico-page");
		mosLoadAdminModules( 'icon', 0 );
	$tabs->endTab();
	$tabs->startTab("Модули","panel-page");
?>
	<div class="polovina">
<?php
	mosLoadAdminModules( 'advert1', 1 );
?></div>
	<div class="polovina">
<?php
	mosLoadAdminModules( 'advert2', 1 );
?>
	</div>
<?php
	mosLoadAdminModules( 'cpanel', 1 );
	$tabs->endTab();
	$tabs->endPane();
?>
