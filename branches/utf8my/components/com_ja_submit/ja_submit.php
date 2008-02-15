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

//include('configuration.php');
require ($mosConfig_absolute_path.'/administrator/components/com_ja_submit/settings.php');
require ($mosConfig_absolute_path.'/components/com_ja_submit/ja_submit.html.php');
# Get the language
if ( file_exists($mosConfig_absolute_path.'/components/com_ja_submit/language/'.$H_language) ) {
	require($mosConfig_absolute_path.'/components/com_ja_submit/language/'.$H_language);
	} else {
	require($mosConfig_absolute_path.'/components/com_ja_submit/language/russian.php');
}

if (!$H_enabled) {
	echo "<div style=\"margin: 20px 0;\"><strong>". _H_SUBMIT_DISABLE ."</strong></div>";
} else {

	# Set the title of the page;
	$canSetTitle = array($mainframe, 'SetPageTitle');
	if (is_callable($canSetTitle)) $mainframe->SetPageTitle(_H_SUBMIT_CONTENT);

	$task = mosGetParam($_REQUEST, 'task', '');
	
	switch ($task) {
		case "cancel":
			mosRedirect( "index.php");
			break;
		case "save":
			submit_save_form();
			break;
		case "great":
			HTML_submit::great();
			break;
		default:
			submit_print_form();
			break;
	}
}

function submit_save_form() {
	global $database, $Itemid,$mainframe, $my,$mosConfig_live_site, $mosConfig_absolute_path,$Itemid,$H_notify_email, $H_catid, $H_sectionid,$mosConfig_mailfrom,$mosConfig_sitename;
	require ($mosConfig_absolute_path.'/administrator/components/com_ja_submit/settings.php');
	if(!$my->id) if(!$H_guest) mosRedirect( "index.php",_NOT_AUTH );
	if($H_captcha){
		session_start();
		$captcha=$_POST['captcha'];
		if(!isset($_SESSION['captcha_keystring'])||$_SESSION['captcha_keystring']!==$captcha){
			unset($_SESSION['captcha_keystring']);
			echo "<script> alert('Введён не правильный код проверки'); window.history.go(-1); </script>\n";
			exit();
		}
		session_unset();
		session_write_close();
	}

	//start uploading introtext image and maintext image
	if(($_FILES['image1']['name']!='') OR ($_FILES['image2']['name']!='')){
		// добавим к имени файла изображения мд5 хеш заголовка публикации
		if($_FILES['image1']['size']>0) $_FILES['image1']['name'] = md5($_POST['introtext']).'-'.$_FILES['image1']['name'];
		if($_FILES['image2']['size']>0) $_FILES['image2']['name'] = md5($_POST['introtext']).'-'.$_FILES['image2']['name'];

		if (!image_upload()){
			mosRedirect( "index.php?option=com_ja_submit&Itemid=$Itemid",_H_UNABLE_IMAGE );
		}
	}

	//finish uploading images, start uploading content to the database
	$nullDate = $database->getNullDate();
	$row = new mosContent( $database );
	if ( !$row->bind( $_POST ) ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$query="SELECT section
			FROM   #__categories 
			WHERE  id=".$row->catid;
	$database->setQuery($query);
	$row->sectionid = $database->loadResult();
	#build sql string
	//introtext image
	
	$Nameimage1 = $_FILES['image1']['name'];
	$Nameimage2 = $_FILES['image2']['name'];

	$row->images = '';
	$Nameimage1 ? $row->images .= "".$H_image_upload."/".$Nameimage1."|".$H_image_position."||1||" : null ;
	$Nameimage2 ? $row->images .= "\n".$H_image_upload."/".$Nameimage2."|left||1||" : null;

	$addmos = "{mosimage}";
	if ($Nameimage1!=""){
		$row->introtext=$addmos.$row->introtext;
	}

	if ($Nameimage2!=""){
		$row->fulltext=$addmos.$row->fulltext;
	}

		// new record
	$row->created = date( 'Y-m-d H:i:s' );
	if (!$my->id)  {$row->created_by = $H_id_users;} else {$row->created_by = $my->id;}
	
	if ( trim( $row->publish_down ) == 'Never' ) {
		$row->publish_down = $nullDate;
	}
	
	// code cleaner for xhtml transitional compliance
	if ($H_tag) {
		$row->introtext = nl2br($row->introtext);
		$row->fulltext = nl2br($row->fulltext);
	}else{
		$row->introtext = nl2br($row->introtext);
		$row->introtext = str_replace( '<br>', '<br />', $row->introtext );
		$row->introtext = "<p>" . $row->introtext . "</p>";
		$row->introtext = str_replace ('<br />' , '</p><p>', $row->introtext);
		$row->fulltext = nl2br($row->fulltext);
		$row->fulltext = str_replace( '<br>', '<br />', $row->fulltext );
		$row->fulltext = "<p>" . $row->fulltext . "</p>";
		$row->fulltext = str_replace ('<br />' , '</p><p>', $row->fulltext);
	}

	// remove <br /> take being automatically added to empty fulltext
	$length	= strlen( $row->fulltext ) < 9;
	$search = strstr( $row->fulltext, '<br />');
	if ( $length && $search ) {
		$row->fulltext = NULL;
	}
	
	$row->title = ampReplace( $row->title );
	
	//Check whether this content will be automatically publish or not
	if (isPublishCategory($row->catid)||isPublishGroup()){
		$row->state=1;
	}
	

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->version++;
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// manage frontpage items
	require_once( $mainframe->getPath( 'class', 'com_frontpage' ) );
	$fp = new mosFrontPage( $database );

	if ( mosGetParam( $_REQUEST, 'frontpage', 0 ) ) {

		// toggles go to first place
		if (!$fp->load( $row->id )) {
			// new entry
			$query = "INSERT INTO #__content_frontpage"
			. "\n VALUES ( $row->id, 1 )"
			;
			$database->setQuery( $query );
			if (!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
			$fp->ordering = 1;
		}
	} else {
	// no frontpage mask
		if ( !$fp->delete( $row->id ) ) {
			$msg .= $fp->stderr();
		}
		$fp->ordering = 0;
	}
	$fp->updateOrder();

	$row->checkin();
	$row->updateOrder( "catid = $row->catid" );
	// gets section name of item
	$query = "SELECT s.title"
	. "\n FROM #__sections AS s"
	. "\n WHERE s.scope = 'content'"
	. "\n AND s.id = $row->sectionid"
	;
	$database->setQuery( $query );
	// gets category name of item
	$section = $database->loadResult();

	$query = "SELECT c.title"
	. "\n FROM #__categories AS c"
	. "\n WHERE c.id = $row->catid"
	;
	$database->setQuery( $query	);
	$category = $database->loadResult();
	
	//sending emails
	
	$message=_H_EMAIL_NOTICE;
	$message.=".$mosConfig_sitename.";
	$message.=_H_AS_FOLLOW."\n"."\n";
	$message.="======================================================================="."\n"."\n";
	$message.=_H_NAME.":  ".$_POST['created_by_alias']."\n";
	$message.=_H_EMAIL.": ".$_POST['email']."\n";
	$message.=_H_TITLE.":  ".$_POST['title']."\n";
	$message.=_H_SECTION.": ".$section."\n";
	$message.=_H_CAT.": ".$category."\n"."\n";
	$message.=_H_INTROTEXT.": ".$_POST['introtext']."\n"."\n";
	$message.=_H_MAINTEXT.": ".$_POST['fulltext']."\n"."\n";
	$message.=_H_PLS_LOG_PR."\n"."\n";
	$message.=_H_AUTOSYSTEM."\n"."\n";

	$headers = "From: ".$mosConfig_mailfrom."\r\n" .
	'Reply-To: '.$mosConfig_mailfrom."\r\n";
	
	$subject=_H_EMAIL_SUBJECT;
	
	if (trim($H_notify_email)!=""){
		$listemails=explode(";",$H_notify_email);
		sendmail($listemails,$subject,$message,$headers);
	}
	if ($row->state==1){
		$phrase="1";//_H_THANKS_PUBLISHED
		$query="SELECT id FROM #__content ORDER BY id DESC LIMIT 0,1";
		$database->setQuery($query);
		$row=$database->loadRow();
		$phrase.="&id=".$row[0];
	}else{
		$phrase="2";//_H_THANKS
	}
	mosRedirect( "index.php?option=com_ja_submit&task=great&phrase=$phrase&Itemid=$Itemid","" );
}


/****************************************************/
function image_upload(){
	global $mosConfig_live_site, $mosConfig_absolute_path,$Itemid;
	global $database, $mainframe;
	global $H_width, $H_height, $H_maxsize,$H_enable_resize;
	#include image resizer
	require ('components/com_ja_submit/resize.php');
	require ($mosConfig_absolute_path.'/administrator/components/com_ja_submit/settings.php');

	#set upload dirs
	$uploadphotodir = $mosConfig_absolute_path."/images/stories/".$H_image_upload."/";
	$tempdir = $mosConfig_absolute_path."/cache/";

	$H_allowable_images=str_replace(".","",$H_allowable_images);
	$allowtype=explode(",",$H_allowable_images);

	#File size in Bytes.Changed its type to variable
	$maxSize = "$H_maxsize";

	//-------------Image 1---------------//

	#check move image 1


	$Nameimage1 = $_FILES['image1']['name'];
	$Nameimage2 = $_FILES['image2']['name'];
	$name1Ext=substr($Nameimage1,strlen($Nameimage1)-3,3);
	$name2Ext=substr($Nameimage2,strlen($Nameimage2)-3,3);

	if ($Nameimage1!=""){
		//check the allow extension
		if (!in_array($name1Ext,$allowtype)) mosRedirect("index.php?option=com_ja_submit&Itemid=$Itemid",_H_UNSUPPORTED_IMAGE);

		$image1Temp = $tempdir.$Nameimage1;
		if (move_uploaded_file($_FILES['image1']['tmp_name'], $image1Temp)) {
			#check size. Fixed Display error
			if($_FILES['image1']['size'] > $maxSize) {
				mosRedirect('index.php?option=com_ja_submit',_H_IMG."  ".$Nameimage1."  "._H_FAILED_UP."  "._H_FILE_LARGER." ".$H_maxsize." ". _H_BYTES);
			}else{
				#get original size
				list($oldwidth, $oldheight) = getimagesize($image1Temp);

				#resize
				if ($H_enable_resize==0){
					//NOT RESIZE
					$smwidth=$oldwidth;
					$smheight=$oldheight;
				} else {
					if($oldwidth > $oldheight) {
					$smwidth = "$H_width";
					$smheight = ($oldheight*($smwidth/$oldwidth));
					} else {
						$smheight = "$H_height";
						$smwidth = ($oldwidth*($smheight/$oldheight));
					}
				}

				if (isset($smheight) && isset($smwidth)) {
					makeimage($image1Temp,$Nameimage1,'',$uploadphotodir,$smwidth,$smheight);
					//echo "...Resized Image $Nameimage1 created<br />";

				}
			}
			#delete temp file
			unlink($image1Temp);
			//echo ":: Backup File for $Nameimage1 Deleted ::<br />";
		}
	}

	//------------Image 2------------//
	//check move image 2
	if ($Nameimage2!=""){
		$image2Temp = $tempdir.$Nameimage2;
		if (!in_array($name2Ext,$allowtype)) mosRedirect("index.php?option=com_ja_submit&Itemid=$Itemid",_H_UNSUPPORTED_IMAGE);
		if (move_uploaded_file($_FILES['image2']['tmp_name'], $image2Temp)) {
			#check size. Fixed Display error
			if($_FILES['image2']['size'] > $maxSize){
				mosRedirect('index.php?option=com_ja_submit',_H_IMG.$Nameimage2._H_FAILED_UP."  "._H_FILE_LARGER." ".$H_maxsize." ". _H_BYTES);
			}else{
				#get original size
				list($oldwidth, $oldheight) = getimagesize($image2Temp);
				#resize
				if ($H_enable_resize==0){
					//NOT RESIZE
					$smwidth=$oldwidth;
					$smheight=$oldheight;
				} else {
					if ($oldwidth > $oldheight) {
					$smwidth = "$H_width";
					$smheight = ($oldheight*($smwidth/$oldwidth));
					}else{
						$smheight = "$H_height";
						$smwidth = ($oldwidth*($smheight/$oldheight));
					}
				}

				if (isset($smheight) && isset($smwidth)) {
					makeimage($image2Temp,$Nameimage2,'',$uploadphotodir,$smwidth,$smheight);
					//echo "...Resized Image $Nameimage2 created<br>";
				}
			}
			#delete temp file
			unlink($image2Temp);
			}
		}
		return true;
}

function submit_print_form() {
	global $mosConfig_module_on_edit_off,$mosConfig_absolute_path,$my;
	require ($mosConfig_absolute_path.'/administrator/components/com_ja_submit/settings.php');
	if(!$my->id) if(!$H_guest) mosRedirect( "index.php",_NOT_AUTH );
	// при редактировании материала с фронта отключаем показ всех модулей - пользователю будет не повадно переходить по ссылкам без сохранения, и место освободим
	if($mosConfig_module_on_edit_off ==1) $GLOBALS['_MOS_MODULES']='' ;
	HTML_submit::editContent( );
}

function sendmail($mails,$subject,$message,$header=NULL){
	if (count($mails)>0){
		foreach ($mails As $email){
			@mail($email, $subject, $message,$header);
		}
	}
}

function isPublishGroup(){
	global $H_auto_approve_groups,$my;
	if ($H_auto_approve_groups=="") return false;
	$groups=explode(",",$H_auto_approve_groups);
	
	for ($i=0;$i<count($groups);$i++){
		if (strcasecmp($my->usertype,$groups[$i])==0){
			return true;
		}
	}
	return false;
}

function isPublishCategory($catid){
	global $H_auto_approve_categories;
	if ($H_auto_approve_categories=="") return false;
	$pcats=explode(",",$H_auto_approve_categories); //allow publish cats
	for ($i=0;$i<count($pcats);$i++){
		if ($catid==$pcats[$i]) return true;
	}
	return false;
}
?>
