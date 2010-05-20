<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

$mf = mosMainFrame::getInstance();
$mf->addJS( JPATH_SITE.'/media/js/plupload.full.min.js' );
$mf->addJS( JPATH_SITE.'/media/js/jquery.plugins/jquery.plupload.queue.min.js' );


?>
<form action="<?php echo sefRelToAbs('index.php?option=com_users&task=edit',true);  ?>" method="post" name="userForm" id="userForm">
	<input type="text" value="<?php echo $user->email ?>" name="email" />
	<input type="submit" value="Сохранить" />
	<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>

<form enctype="multipart/form-data" action="<?php echo sefRelToAbs('index.php?option=com_users&task=file',true);  ?>" method="post" name="userForm" id="userForm">
	<input type="file" name="file" />
	<input type="submit" value="Тыц!" />
</form>


	<div id="filelist">Выберите ка аватар</div>
	<input type="button" value="Выбрать" id="pickfiles" />
	<input type="button" value="Загрузить" id="uploadfiles" />



<script type="text/javascript">
	// Custom example logic
	$(function() {
		var uploader = new plupload.Uploader({
			runtimes : 'html5,gears,flash',
			browse_button : 'pickfiles',
			max_file_size : '3mb',
			url : _live_site + '/ajax.index.php?option=com_users&task=uploadavatar',
			flash_swf_url : '/media/swf/plupload.flash.swf',
			multi_selection: true,
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"},
			]
		});

		uploader.bind('FilesAdded', function(up, files) {
			$.each(files, function(i, file) {
				$('#filelist').append(
				'<div id="' + file.id + '">' + 'Файл: ' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>'
			);
			});
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		$('#uploadfiles').click(function(e) {
			uploader.start();
			e.preventDefault();
		});

		uploader.init();
	});
</script>

