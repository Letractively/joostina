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

// запрет прямого доступа
defined( '_VALID_MOS' ) or die( 'Прямой вызов файла запрещен' );

// NOTE TO USERS!
// Set the initial browse (or base) path here. Default is 'images/stories'.
// Set to '' to browse your full Joomla! website. Enjoy!
global $mosConfig_media_dir;
$jwmmxtd_browsepath = $mosConfig_media_dir;



// ----------------------------------------------------------------------------------------------- //
// JoomlaWorks MEDIA MANAGER XTD administrator component v1.1
// You don't need to change anything below this line.
// ----------------------------------------------------------------------------------------------- //
define('JWMMXTD_STARTABSPATH',$mosConfig_absolute_path.DIRECTORY_SEPARATOR.$jwmmxtd_browsepath);
define('JWMMXTD_STARTURLPATH',$mosConfig_live_site.'/'.$jwmmxtd_browsepath);
define('JWMMXTD_COMP','com_jwmmxtd');
require_once( $mainframe->getPath( 'admin_html' ) );
function makeSafe( $file ) {
	return str_replace( '..', '', urldecode( $file ) );
}

$subtask 	    = mosGetParam( $_REQUEST, 'subtask', '' );
$curdirectory 	= makeSafe( mosGetParam( $_REQUEST, 'curdirectory', '' ) );
$img 	        = mosGetParam( $_REQUEST, 'img', '' );
$selectedfile   = mosGetParam( $_REQUEST, 'selectedfile', '' );
$curfile        = mosGetParam( $_REQUEST, 'curfile', '' );
$newfile        = mosGetParam( $_REQUEST, 'newfilename', '' );
$folder_name    = mosGetParam( $_POST, 'createfolder', '' );
$delFile        = makeSafe( mosGetParam( $_REQUEST, 'delFile', '' ) );
$delFolder      = mosGetParam( $_REQUEST, 'delFolder', '' );
$dirtocopy 	    = makeSafe( mosGetParam( $_REQUEST, 'dirtocopy', '/' ) );
$dirtomove 	    = makeSafe( mosGetParam( $_REQUEST, 'dirtomove', '/' ) );

if (is_int(strpos ($curdirectory, ".."))) {
	mosRedirect( "index2.php", "Попытка взлома..." );
}

// Language File
if (file_exists($mosConfig_absolute_path.'/administrator/components/'.JWMMXTD_COMP.'/language/'.$mosConfig_lang.'.php')) {
	include_once ($mosConfig_absolute_path.'/administrator/components/'.JWMMXTD_COMP.'/language/'.$mosConfig_lang.'.php');
} else {
	include_once ($mosConfig_absolute_path.'/administrator/components/'.JWMMXTD_COMP.'/language/russian.php');
}

// PCLZIP Library
include_once ($mosConfig_absolute_path.'/administrator/components/'.JWMMXTD_COMP.'/lib/pclzip.class.php');

$tmpimage 	    = mosGetParam( $_REQUEST, 'tmpimage', '' );
if($tmpimage!="") {
   @unlink("./components/".JWMMXTD_COMP."/tmp/".$tmpimage);
}

// Component HEAD
$jw_mmxtd_head = '<style type="text/css">@import "components/'.JWMMXTD_COMP.'/css/jw_mmxtd.php";</style>';

if ($task=='edit') {
   $jw_mmxtd_head .= '<script type="text/javascript" src="components/'.JWMMXTD_COMP.'/js/jw_mmxtd_edit.php"></script>';
} else {
	$jw_mmxtd_head .= '
	<script type="text/javascript" src="components/'.JWMMXTD_COMP.'/js/jw_mmxtd_browse.php"></script>
	<script type="text/javascript">
	<!--
	if (navigator.appName.indexOf("Microsoft") == 0) {
		function loadEvent(obj, evType, fn) { 
			if (obj.addEventListener) { 
				obj.addEventListener(evType, fn, false); 
				return (true); 
			} else if (obj.attachEvent) { 
				var r = obj.attachEvent("on"+evType, fn); 
				return (r); 
			} else return (false);
		}
		loadEvent(window, "load", Slider.init);
		loadEvent(window, "load", Reflection.addFromClass);	
		loadEvent(window, "load", Lightbox.init.bind(Lightbox));	
		loadEvent(window, "load", Videobox.init.bind(Videobox));	
	} else {
		window.addEvent("domready", Slider.init);
		window.addEvent("domready", Reflection.addFromClass);
		window.addEvent("domready", Lightbox.init.bind(Lightbox));
		window.addEvent("domready", Videobox.init.bind(Videobox));				
	}
	-->		
	</script>
	<script language="javascript" type="text/javascript">
	<!--
		function updateDir(){
			var allPaths = window.top.document.forms[0].dirPath.options;
			for(i=0; i<allPaths.length; i++) {
				allPaths.item(i).selected = false;
				if((allPaths.item(i).value)== "';
					if (strlen($curdirectory)>0) {				
					$jw_mmxtd_head .= $curdirectory;
					} else {
					$jw_mmxtd_head .=  '/';
					}
					$jw_mmxtd_head .= '") {
					allPaths.item(i).selected = true;
				}
			}
		}
		function deleteFolder(folder, numFiles) {
			if(numFiles > 0) {
				alert("'._JWMEDIAMAN_NOEMPTY_FOLDER.'");
				return false;
			}
			if(confirm("'._JWMEDIAMAN_ALERT_DEL_FOLDER.'\""+folder+"\"?")) return true; return false;
		}
	-->
	</script>
';
}

$mainframe->addCustomHeadTag($jw_mmxtd_head);

// ---- TASKS ---- //
switch ($task) {
	case 'edit':
		editImage( $img, $curdirectory );
		break;

	case 'unzipfile':
		$mosmsg = unzipzipfile( $curdirectory, $curfile, $dirtocopy );
		viewMediaManager( $dirtocopy, $mosmsg );
		break;

	case 'createfolder':
		if (ini_get('safe_mode')=='On') {
			mosRedirect( "index2.php?option=".JWMMXTD_COMP."&curdirectory=".$curdirectory, "При активированном параметре SAFE MODE возможны проблемы с созданием каталогов." );
		} else {
			if(create_folder( $curdirectory, $folder_name )) $mosmsg=_JWMEDIAMAN_CREATE_FOLDER_DONE; else $mosmsg=_JWMEDIAMAN_CREATE_FOLDER_ERROR;
		}
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'delete':
		if(delete_file( $curdirectory, $delFile )) $mosmsg=_JWMEDIAMAN_DEL_FILE_DONE; else $mosmsg=_JWMEDIAMAN_DEL_FILE_ERROR;
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'deletefolder':
		if(delete_folder( $curdirectory, $delFolder )) $mosmsg=_JWMEDIAMAN_DEL_FOLDER_DONE; else $mosmsg=_JWMEDIAMAN_DEL_FOLDER_ERROR;
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'uploadimages':
		$mosmsg = uploadImages( $curdirectory );
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'alterfilename':
		if(newFileName( $curdirectory, $curfile, $newfile )) $mosmsg=_JWMEDIAMAN_REN_FILE_DONE; else $mosmsg=_JWMEDIAMAN_REN_FILE_ERROR;
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'copyfile':
		if(copyFile( $curdirectory, $curfile, $dirtocopy )) $mosmsg=_JWMEDIAMAN_COPY_DONE; else $mosmsg=_JWMEDIAMAN_COPY_ERROR;
		viewMediaManager( $dirtocopy, $mosmsg );
		break;
		
	case 'movefile':
		if(moveFile( $curdirectory, $curfile, $dirtomove )) $mosmsg=_JWMEDIAMAN_MOVE_DONE; else $mosmsg=_JWMEDIAMAN_MOVE_ERROR;
		viewMediaManager( $dirtomove, $mosmsg );
		break;
		
	case 'emptytmp':
		if(emptyTmp()) $mosmsg=_JWMEDIAMAN_TMP_DONE; else $mosmsg=_JWMEDIAMAN_TMP_ERROR;
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'saveimage':
		$mosmsg = saveImage( $curdirectory );
		viewMediaManager( $curdirectory, $mosmsg );
		break;
		
	case 'returnfromedit':
		returnFromEdit( $curdirectory );
		viewMediaManager( $curdirectory );
		break;

	default:
		viewMediaManager($curdirectory, "", $selectedfile);
		break;
}

function unzipzipfile ($curdirpath, $curfile, $destindir) {
	$path = JWMMXTD_STARTABSPATH . $curdirpath . DIRECTORY_SEPARATOR . $curfile;
	$path2 = JWMMXTD_STARTABSPATH . $destindir . DIRECTORY_SEPARATOR ;
	
	//for zip extraction
	if(is_file($path))
 	{
 		$path_parts = pathinfo($path);
 		
 		if(eregi(".zip",$path))
  		{
  			$zip=new PclZip($path);
		 	$list = $zip->extract($path2);
		    if($list>0) 
			{
			   $msg= count($list). _JWMEDIAMAN_ZIP_FILES_EXTRACTED . $refresh;
			   $reply=1;
			   return $msg;
			}
			else
    			$msg= _JWMEDIAMAN_ZIP_FILES_UNEXPECTED_ERROR . $curfile;
  		}
 		else 
		{
			$msg=_JWMEDIAMAN_ZIP_FILE_STRING . $curfile . _JWMEDIAMAN_ZIP_NOT_A_ZIP_FILE;
			return $msg;
		} 
 	}
	else $msg=_JWMEDIAMAN_ZIP_FILE_STRING . $curfile . _JWMEDIAMAN_ZIP_NOT_EXISTS;
	return $msg;
}

function saveImage($cur) {
	
	require_once ("class.upload.php");
	global $mosConfig_absolute_path;
	
	$cur = JWMMXTD_STARTABSPATH . $cur . DIRECTORY_SEPARATOR;
	
	$primage = mosGetParam( $_REQUEST, 'primage', '' );
	$orimage = mosGetParam( $_REQUEST, 'originalimage', '' );
	//echo $primage."-".$orimage."-".$cur;
	
	$tmp = explode("/",$orimage);
	$ornamewithext = end($tmp);
	$orname = str_replace(substr($ornamewithext, -4), "", $ornamewithext ) ;
	
	if($orname) {
	
	  $pic = new upload($mosConfig_absolute_path."/administrator/components/".JWMMXTD_COMP."/tmp/".$primage);
	
	  if ($pic->uploaded) {

	  	 $pic->file_src_name_body = $orname."_edit".rand(100,999);
		 $pic->Process( $cur);	
		 @unlink($mosConfig_absolute_path."/administrator/components/".JWMMXTD_COMP."/tmp/".$primage);
		 $ok=true;
		        	         
	  }	else $ok=false;
	  
	} else $ok=false;	
	
	if($ok) $msg = _JWMEDIAMAN_SAVEEDIT_DONE.$pic->file_dst_name; else $msg = _JWMEDIAMAN_SAVEEDIT_ERROR;
	
	return $msg;
	
}

function returnFromEdit($cur) {
	require_once ("class.upload.php");
	global $mosConfig_absolute_path;	
	$primage = mosGetParam( $_REQUEST, 'primage', '' );
	$orimage = mosGetParam( $_REQUEST, 'originalimage', '' );
	@unlink("components/".JWMMXTD_COMP."/tmp/".$primage);
}

function emptyTmp() {
	global $mosConfig_absolute_path;
		$dir = $mosConfig_absolute_path."/administrator/components/".JWMMXTD_COMP."/tmp";
		if(is_dir($dir)) {
			$d = dir($dir);
			while ( false !== ($entry = $d->read()) ) {
				if ( substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,-4) == ".png" ) {
					@unlink($dir."/".$entry); 
				} 
			}
			$d->close();
		}
		$total_file = 0;
		if(is_dir($dir)) {
			$d = dir($dir);
			while ( false !== ($entry = $d->read()) ) {
				if ( substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,-4) == ".png" ) {
					$total_file++;
				}
			}
			$d->close();
		}
		if($total_file==0) $ok=true; else $ok=false;
        return $ok;
}

function newFileName( $curdirectory, $curfile, $newfile ) {
	if($curfile == "" || $newfile == "") return false;
	$path = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR . $curfile;
	$path2 = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR . $newfile;
	if(file_exists($path2)) return false;
    if(rename($path, $path2)) $ok=true; else $ok=false;
    return $ok;
}

function copyFile( $curdirectory, $curfile, $dirtocopy ) {
	if($curfile == "") return false;
	$path = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR . $curfile;
	$path2 = JWMMXTD_STARTABSPATH . $dirtocopy . DIRECTORY_SEPARATOR . $curfile;
	if(file_exists($path2)) return false;
	if (!copy($path, $path2)) {
         $ok=false;
    } else $ok=true;
    return $ok;
}

function moveFile( $curdirectory, $curfile, $dirtomove ) {
	if($curfile == "") return false;
	$path = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR . $curfile;
	$path2 = JWMMXTD_STARTABSPATH . $dirtomove . DIRECTORY_SEPARATOR . $curfile;
	if(file_exists($path2)) return false;
    if (!rename($path, $path2)) {
         $ok=false;
    } else $ok=true;
    return $ok;
}

function uploadImages ( $curdirectory ) {
    // ---------- MULTIPLE UPLOADS ----------
    include('class.upload.php');
    // as it is multiple uploads, we will parse the $_FILES array to reorganize it into $files
    $files = array();
    foreach ($_FILES['upimage'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files)) 
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
    $mosmsg = _JWMEDIAMAN_UPL_SERVER_ERROR;
    // now we can loop through $files, and feed each element to the class
    foreach ($files as $file) {
    
        // we instanciate the class for each element of $file
        $handle = new Upload($file);
        
        // then we check if the file has been uploaded properly
        // in its *temporary* location in the server (often, it is /tmp)
        if ($handle->uploaded) {

            // now, we start the upload 'process'. That is, to copy the uploaded file
            // from its temporary location to the wanted location
            // It could be something like $handle->Process('/home/www/my_uploads/');
            $updirectory = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR;
            $handle->Process($updirectory);

            // we check if everything went OK
            if ($handle->processed) {
                // everything was fine !
				$mosmsg = _JWMEDIAMAN_UPL_SERVER_DONE;
            } else {
                // one error occured
                ///$mosmsg = "<h3>File not uploaded to the wanted location</h3>";
            }
            
        } else {
            // if we're here, the upload file failed for some reasons
            // i.e. the server didn't receive the file
            //$mosmsg = "<h3>File not uploaded on the server</h3>";
        }
    }
    return $mosmsg;
}

function delete_folder($listdir, $delFolder) {
	$del_html 	= JWMMXTD_STARTABSPATH . $listdir . DIRECTORY_SEPARATOR . $delFolder . DIRECTORY_SEPARATOR . 'index.html';
	$del_folder = JWMMXTD_STARTABSPATH . $listdir . DIRECTORY_SEPARATOR . $delFolder;
	$entry_count = 0;
	$dir = opendir( $del_folder );
	while ($entry = readdir( $dir )) {
		if( $entry != "." & $entry != ".." & strtolower($entry) != "index.html" )
		$entry_count++;
	}
	closedir( $dir );
	if ($entry_count < 1) {
		@unlink( $del_html );
		if(rmdir( $del_folder )) $ok=true; else $ok=false;
	} else {
		$ok=false;
	}
	return $ok;
}

function delete_file( $listdir, $delFile ) {
	$fullPath = JWMMXTD_STARTABSPATH . $listdir . DIRECTORY_SEPARATOR . stripslashes( $delFile );
	if (file_exists( $fullPath )) {
	  if(unlink( $fullPath )) return true;
	}
	return  false;
}

function listofImages($listdir) {
	global $mosConfig_live_site;

	// get list of images
	$listdir = JWMMXTD_STARTABSPATH . $listdir;
	$d = @dir( $listdir); 

	if($d) {
		//var_dump($d);
		$images 	= array();
		$folders 	= array();
		$docs 		= array();
		$allowable 	= 'xcf|odg|gif|jpg|png|bmp';

		while (false !== ($entry = $d->read())) { 
			$img_file = $entry; 
			if(is_file( $listdir.'/'.$img_file) && substr($entry,0,1) != '.' && strtolower($entry) !== 'index.html' ) { 
				if (eregi( $allowable, $img_file )) {
					$image_info 				= @getimagesize( $listdir.'/'.$img_file);
					$file_details['file'] 		= $listdir."/".$img_file;
					$file_details['img_info'] 	= $image_info;
					$file_details['size'] 		= filesize( $listdir."/".$img_file);
					$images[$entry] 			= $file_details;
				} else {
					// file is document
					$file_details['size'] 	= filesize( $listdir."/".$img_file);
					$file_details['file'] 	= $listdir."/".$img_file;
					$docs[$entry] 			= $file_details;
				}
			} else if(is_dir( $listdir.'/'.$img_file) && substr($entry,0,1) != '.' && strtolower($entry) !== 'cvs') {
				$folders[$entry] = $img_file;
			}
		}
		$d->close();

		

		if(count($images) > 0 || count($folders) > 0 || count($docs) > 0) {
			//now sort the folders and images by name.
			ksort($images);
			ksort($folders);
			ksort($docs);
            
			// FOLDERS
            if(count($folders)>0) {
			$j=0;
			echo "<fieldset><legend>"._JWMEDIAMAN_FOLDERS."</legend>";

			for($i=0; $i<count($folders); $i++) {

				$folder_name = key($folders); 
				HTML_mmxtd::show_dir($folders[$folder_name], $folder_name,str_replace(JWMMXTD_STARTABSPATH, "", $listdir));
				next($folders);
			}
			echo "</fieldset>";
            }

            // IMAGES
			if(count($images)>0) {
			$j=0;
			echo "<fieldset><legend>"._JWMEDIAMAN_IMAGES."</legend>";

			for($i=0; $i<count($images); $i++) {

				$image_name = key($images);
				HTML_mmxtd::show_image($images[$image_name]['file'], $image_name, $images[$image_name]['img_info'], $images[$image_name]['size'],str_replace(JWMMXTD_STARTABSPATH, "", $listdir));
				next($images);
			}

			echo "</fieldset>";
			}           
            
			// VARIUS
            if(count($docs)>0) {
			$j=0;
			echo "<fieldset><legend>"._JWMEDIAMAN_FILES."</legend>";

			for($i=0; $i<count($docs); $i++) {

				$doc_name = key($docs);
				$iconfile= $GLOBALS['mosConfig_absolute_path'].'/administrator/components/'.JWMMXTD_COMP.'/icons/'.substr($doc_name,-3).'.png';
				if (file_exists($iconfile))	{
					$icon = 'components/'.JWMMXTD_COMP.'/icons/'.(substr($doc_name,-3)).'.png'	;
				} else {
					$icon = 'components/'.JWMMXTD_COMP.'/icons/document.png';
				}
				HTML_mmxtd::show_doc($doc_name, $docs[$doc_name]['size'],str_replace(JWMMXTD_STARTABSPATH, "", $listdir), $icon);
				next($docs);

			}
			echo "</fieldset>";
            }
	
		} else {
			
		}
	} else {
		
	}
}

function listImagesBak($dirname='.') {
      return glob($dirname .'*.{jpg,png,jpeg,gif}', GLOB_BRACE);
}

function create_folder($curdirectory, $folder_name) {
	
    $folder_name = str_replace(" ","_", $folder_name);
	
	if(strlen($folder_name) >0) {
		if (eregi("[^0-9a-zA-Z_]", $folder_name)) {
			mosRedirect( "index2.php?option=".JWMMXTD_COMP."curdirectory=".$curdirectory, _JWMEDIAMAN_ALPHANUMERIC_FOLDER );
		}
		$folder = JWMMXTD_STARTABSPATH . $curdirectory . DIRECTORY_SEPARATOR . $folder_name;
		if(!is_dir( $folder ) && !is_file( $folder )) {
			$suc = mosMakePath( $folder );
			$fp = fopen( $folder . "/index.html", "w" );
			fwrite( $fp, "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>" );
			fclose( $fp );
			mosChmod( $folder."/index.html" );
			$refresh_dirs = true;
			return $suc;
		}
	}
}

function listofdirectories( $base ) {
	static $filelist = array();
	static $dirlist = array();

	if(is_dir($base)) {
		$dh = opendir($base);
		while (false !== ($dir = readdir($dh))) {
			if (is_dir($base .'/'. $dir) && $dir !== '.' && $dir !== '..' && strtolower($dir) !== 'cvs' && strtolower($dir) !== '.svn') {
				$subbase = $base .'/'. $dir;
				$dirlist[] = $subbase;
				$subdirlist = listofdirectories($subbase);
			}
		}
		closedir($dh);
	}
	return $dirlist;
 }


function viewMediaManager($curdirectory = "", $mosmsg = "", $selectedfile = "") {
	global $database, $mainframe, $my, $acl, $mosConfig_absolute_path, $subtask, $task;
	
	$imgFiles 	= listofdirectories( JWMMXTD_STARTABSPATH );
	$images 	= array();
	$folders 	= array();
	$folders[] 	= mosHTML::makeOption( "", "/" );

	$len = strlen( JWMMXTD_STARTABSPATH );
	foreach ($imgFiles as $file) {
		$folders[] = mosHTML::makeOption( substr( $file, $len ) );
	}
	if (is_array( $folders )) {
		sort( $folders );
	}
	
	$dirPath = mosHTML::selectList( $folders, 'curdirectory', "class=\"inputbox\" size=\"1\" onchange=\"document.adminForm.task.value='';document.adminForm.submit( );\" ", 'value', 'text', $curdirectory );
	
	if($curdirectory == "") $upcategory = "";
	else {
		$tmp = explode("/",$curdirectory);
		end($tmp);
		unset($tmp[key($tmp)]);
		$upcategory = implode("/",$tmp);
		if($upcategory=="") $upcategory="";
	}
	
// Error/warning message here
if($mosmsg) { echo '<div class="message">'.$mosmsg.'</div>'; }
?>

<!-- JoomlaWorks MEDIA MANAGER XTD starts here [browse page] -->
<div id="jwmmxtd">
  <form action="index2.php" name="adminForm" method="POST" enctype="multipart/form-data">
    <!-- controls -->
    <table cellpadding="0" cellspacing="0" style="width:100%;" id="upper">
      <tr>
        <td><h1 class="title"><?php echo _JWMEDIAMAN_TITLE; ?></h1></td>
        <td id="browse"><table cellpadding="0" cellspacing="4" align="right">
            <tr>
              <td><?php echo _JWMEDIAMAN_LEG_CRE_FOLDER; ?></td>
              <td style="width:220px;"><input class="inputbox" type="text" name="createfolder" id="createfolder"></td>
              <td><a href="#" class="button" onclick="javascript:document.adminForm.task.value='createfolder';document.adminForm.submit( );"><?php echo _JWMEDIAMAN_CLICKTOCREATE; ?></a> </td>
            </tr>
            <tr>
              <td><?php echo _JWMEDIAMAN_LEG_UPL_IMAGES; ?> <a id="toggle" name="toggle" href="#"><?php echo _JWMEDIAMAN_LEG_UPL_MIMAGES; ?></a></td>
              <td><input type="file" class="inputbox" name="upimage[]">
                <div class="wrap">
                  <div id="upload_more">
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                    <input type="file" class="inputbox" name="upimage[]">
                    <br />
                  </div>
                </div></td>
              <td><a href="#" class="button" onclick="javascript:document.adminForm.task.value='uploadimages';document.adminForm.submit( );"><?php echo _JWMEDIAMAN_CLICKTOUPLOAD; ?></a></td>
            </tr>
            <tr>
              <td><?php echo _JWMEDIAMAN_LEG_SEL_DIR; ?></td>
              <td><?php echo $dirPath; ?></td>
              <td><a href="index2.php?option=<?php echo JWMMXTD_COMP; ?>&curdirectory=<?php echo $upcategory; ?>"><img src="images/uparrow.png" alt="Up" /></a> </td>
            </tr>
          </table></td>
      </tr>
    </table>
    <div id="actions">
      <!-- display action blocks -->
      
      <?php if($selectedfile != "" && $subtask=="renamefile") { ?>
      <fieldset class="block">
      <legend><?php echo _JWMEDIAMAN_LEG_REN_FILE." <span>".$selectedfile."</span>"; ?></legend>
      <input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
      <?php echo _JWMEDIAMAN_TEXT_NAME_REN_FILE; ?>:
      <input type="text" name="newfilename" id="newfilename">
      <?php echo $ext; ?><a href="#" onclick="javascript:document.adminForm.task.value='alterfilename';document.adminForm.submit( );" class="button"><?php echo _JWMEDIAMAN_CLICKTORENAME; ?></a>
      </fieldset>
      <?php } ?>
      
      <?php if($selectedfile != "" && $subtask=="copyfile") { ?>
      <fieldset class="block">
      <legend><?php echo _JWMEDIAMAN_LEG_COPY_FILE." <span>".$selectedfile."</span>"; ?></legend>
      <input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
      <?php echo _JWMEDIAMAN_TEXT_NAME_COPY_FILE; ?>: <?php echo mosHTML::selectList( $folders, 'dirtocopy', "class=\"inputbox\" size=\"1\" ", 'value', 'text', $curdirectory ); ?><a href="#" onclick="javascript:document.adminForm.task.value='copyfile';document.adminForm.submit( );" class="button"><?php echo _JWMEDIAMAN_CLICKTOCOPY; ?></a>
      </fieldset>
      <?php } ?>
      
      <?php if($selectedfile != "" && $subtask=="movefile") { ?>
      <fieldset class="block">
      <legend><?php echo _JWMEDIAMAN_LEG_MOVE_FILE." <span>".$selectedfile."</span>"; ?></legend>
      <input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
      <?php echo _JWMEDIAMAN_TEXT_NAME_MOVE_FILE; ?>: <?php echo mosHTML::selectList( $folders, 'dirtomove', "class=\"inputbox\" size=\"1\" ", 'value', 'text', $curdirectory ); ?><a href="#" onclick="javascript:document.adminForm.task.value='movefile';document.adminForm.submit( );" class="button"><?php echo _JWMEDIAMAN_CLICKTOMOVE; ?></a>
      </fieldset>
      <?php } ?>
      
      <?php if($selectedfile != "" && $subtask=="unzipfile") { ?>
      <fieldset class="block">
      <legend><?php echo _JWMEDIAMAN_LEG_ZIP_FILE." <span>".$selectedfile."</span>"; ?></legend>
      <input type="hidden" name="curfile" value="<?php echo $selectedfile; ?>">
      <?php echo _JWMEDIAMAN_TEXT_NAME_ZIP_FILE; ?>: <?php echo mosHTML::selectList( $folders, 'dirtocopy', "class=\"inputbox\" size=\"1\" ", 'value', 'text', $curdirectory ); ?><a href="#" onclick="javascript:document.adminForm.task.value='unzipfile';document.adminForm.submit( );" class="button"><?php echo _JWMEDIAMAN_CLICKTOUNZIP; ?></a>
      </fieldset>
      <?php } ?>
      
    </div>
    <input type="hidden" name="selectedfile" value="">
    <input type="hidden" name="subtask" value="">
    <input type="hidden" name="task" value="">
    <input type="hidden" name="option" value="<?php echo JWMMXTD_COMP; ?>">
  </form>
  <div class="jwmmxtd_clr"></div>
  <!-- display files -->
  <?php echo listofImages( $curdirectory ); ?>
  <div class="jwmmxtd_clr"></div>
  <!-- temp folder -->
  <div id="jwmmxtd_tmp">
    <?php if($my->gid==25 || $my->gid==24) { ?>
    <?php echo _JWMEDIAMAN_TEXT_TMP; ?>:
    <?php
		$dir = $mosConfig_absolute_path."/administrator/components/".JWMMXTD_COMP."/tmp";
		$total_file 	= 0;
		if(is_dir($dir)) {
			$d = dir($dir);
			while ( false !== ($entry = $d->read()) ) {
				if ( substr($entry,-4) == ".jpg" || substr($entry,-4) == ".gif" || substr($entry,-4) == ".png" ) {
					$total_file++;
				}
			}
			$d->close();
		}
		echo $total_file;
     ?>
    <a href="#" class="button" onclick="javascript:document.adminForm.task.value='emptytmp';document.adminForm.submit( );"><?php echo _JWMEDIAMAN_CLICKTOTMP; ?></a>
    <?php } ?>
  </div>
</div>
<?php
}

function OriginalImage($aFormValues) { 
	require_once ("class.upload.php");
	global $mosConfig_absolute_path, $img;	
	
	$primage = $aFormValues['primage'];
	$orimage = $aFormValues['originalimage'];
	$curdirectory = $aFormValues['curdirectory'];
	
	@unlink("components/".JWMMXTD_COMP."/tmp/".$primage);
	
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("mmxtd","innerHTML","<img name=\"mainimage\" id=\"mainimage\" src='".JWMMXTD_STARTURLPATH.$curdirectory."/".$orimage."'>");
	$objResponse->addAssign("imagepath", "value", JWMMXTD_STARTURLPATH.$curdirectory."/".$orimage);
	return $objResponse;	
}

function UpdateImage($aFormValues) {
	require_once ("class.upload.php");
	global $mosConfig_absolute_path, $img;
	
	$imagepath = $aFormValues['imagepath'];
	$tmpimage = $aFormValues['imagepath'];
	$imagepath = str_replace(JWMMXTD_STARTURLPATH, JWMMXTD_STARTABSPATH, $imagepath);
// joostina patch
	if(isset($aFormValues['primage'])) $primage = $aFormValues['primage']; else $primage = 0 ;

	$width = intval($aFormValues['width']);
	$height = intval($aFormValues['height']);
	$convert = trim($aFormValues['convert']);
	
	$crop = trim($aFormValues['crop']);
// joostina patch
	$cropv = trim($aFormValues['cropv']);
	$cropo = trim($aFormValues['cropo']);
	
	$cropt = trim($aFormValues['cropt']);
	$cropr = trim($aFormValues['cropr']);
	$cropb = trim($aFormValues['cropb']);
	$cropl = trim($aFormValues['cropl']);
	
	$rotation = intval($aFormValues['rotation']);
    $flip = trim($aFormValues['flip']);
    
    $bevelpx = intval($aFormValues['bevelpx']);
    $beveltl = trim($aFormValues['beveltl']);
    $bevelrb = trim($aFormValues['bevelrb']);
    
	$borderw = trim($aFormValues['borderw']);
	$borderc = trim($aFormValues['borderc']);
	
	$bordert = trim($aFormValues['bordert']);
	$borderr = trim($aFormValues['borderr']);
	$borderb = trim($aFormValues['borderb']);
	$borderl = trim($aFormValues['borderl']);
	$borderc2 = trim($aFormValues['borderc2']);
	
	$tint = trim($aFormValues['tint']);
	
	$overlayp = trim($aFormValues['overlayp']);
	$overlayc = trim($aFormValues['overlayc']);
	
	$brightness = intval($aFormValues['brightness']);
	$contrast = intval($aFormValues['contrast']);
	$threshold = intval($aFormValues['threshold']);
// joostina patch
	if(isset($aFormValues['greyscale'])) $greyscale = $aFormValues['greyscale']; else $greyscale = 0 ;
	if(isset($aFormValues['negative'])) $negative = $aFormValues['negative']; else $negative = 0 ;

	
	$text = trim($aFormValues['text']);
	$textcolor = trim($aFormValues['textcolor']);
	$textfont = trim($aFormValues['textfont']);
	$textpercent = intval($aFormValues['textpercent']);
	$textdirection = trim($aFormValues['textdirection']);
	$textposition = trim($aFormValues['textposition']);
	$bgcolor = trim($aFormValues['bgcolor']);
	$bgpercent = intval($aFormValues['bgpercent']);
	$textpaddingx = intval($aFormValues['textpaddingx']);
	$textpaddingy = intval($aFormValues['textpaddingy']);
	$textabsolutex = intval($aFormValues['textabsolutex']);
	$textabsolutey = intval($aFormValues['textabsolutey']);
	
	$pic = new upload($imagepath);
	
	if ($pic->uploaded) {
		
		         $pic->file_new_name_body = md5(uniqid("mmxtd"));
		         
		         if($width > 0 || $height > 0) {
		         	$pic->image_resize = true;
		         }	
		         if($width > 0 && $height > 0) {
		         	$pic->image_x = $width;
		         	$pic->image_y = $height;
		         }		                  
		         if($width > 0 && $height == 0) {
		         	$pic->image_x = $width;
		         	$pic->image_ratio_y = true;
		         }
		         if($height > 0 && $width == 0) {
		         	$pic->image_y = $height;
		         	$pic->image_ratio_x = true;
		         }
		       	 if($crop != "") {
		         	$pic->image_crop = $crop;
		         }
		       	 elseif($cropv != "" && $cropo != "") {
		         	$pic->image_crop = $cropv." ".$cropo;
		         }		         		         
		       	 elseif($cropt != "" && $cropr != "" && $cropb != "" && $cropl != "") {
		         	$pic->image_crop = $cropt." ".$cropr." ".$cropb." ".$cropl;
		         }
		         if($rotation > 0) {
		         	$pic->image_rotate = $rotation;
		         }
		         if($flip != "none") {
		         	$pic->image_flip = $flip;
		         }
		         if($convert != "none") {
		         	$pic->image_convert = $convert;
		         }
		         if($bevelpx > 0 && $beveltl != "" && $bevelrb != "" ) {
		         	$pic->image_bevel = $bevelpx;
		         	$pic->image_bevel_color1 = $beveltl;
		         	$pic->image_bevel_color2 = $bevelrb;
		         }
		       	 if($borderw != "" && $borderc != "") {
		         	$pic->image_border = $borderw;
		         	$pic->image_border_color = $borderc;
		         }
		       	 elseif($bordert != "" && $borderr != "" && $borderb != "" && $borderl != "" && $borderc2 != "") {
		         	$pic->image_border = $bordert." ".$borderr." ".$borderb." ".$borderl;
		         	$pic->image_border_color = $borderc2;
		         }
		         if($tint != "") {
		         	$pic->image_tint_color = $tint;
		         }		
		         if($overlayp != "" && $overlayc != "") {
		         	$pic->image_overlay_percent = $overlayp;
		         	$pic->image_overlay_color = $overlayc;
		         }	
		         if($brightness != 0) {
		         	$pic->image_brightness = $brightness;
		         }
		         if($contrast != 0) {
		         	$pic->image_contrast = $contrast;
		         }
		         if($threshold != 0) {
		         	$pic->image_threshold = $threshold;
		         }	
		         if($greyscale) {
		         	$pic->image_greyscale = true;
		         }
		         if($negative) {
		         	$pic->image_negative = true;
		         }	
		                  
		         if($text != "") {
		           $pic->image_text = $text;    	
		           if($textcolor != "") {
		         	 $pic->image_text_color = "$textcolor";
		           }	
		           if($textfont != "") {
		         	 $pic->image_text_font = $textfont;
		           }    
		           if($textpercent != 0) {
		         	 $pic->image_text_percent = $textpercent;
		           }  
		           if($textdirection != "") {
		         	 $pic->image_text_direction = $textdirection;
		           }  
		           if($textposition != "") {
		         	 $pic->image_text_position = $textposition;
		           }		
		           if($bgcolor != "") {
		         	 $pic->image_text_background = $bgcolor;
		           }
		           if($bgpercent != 0) {
		         	 $pic->image_text_background_percent = $bgpercent;
		           }		         
		           if($textpaddingx != 0) {
		         	 $pic->image_text_padding_x = $textpaddingx;
		           }		         
		           if($textpaddingy != 0) {
		         	 $pic->image_text_padding_y = $textpaddingy;
		           }		         
		           if($textabsolutex != 0) {
		         	 $pic->image_text_x = $textabsolutex;
		           }	
		           if($textabsolutey != 0) {
		         	 $pic->image_text_y = $textabsolutey;
		           }	     
		         }              

                 $pic->Process( $mosConfig_absolute_path.'/administrator/components/'.JWMMXTD_COMP.'/tmp/');
            
                 if ($pic->processed) {                	
                 	$img2out = "<img name=\"mainimage\" id=\"mainimage\" src=\"./components/".JWMMXTD_COMP."/tmp/".$pic->file_dst_name."\" />";
                 	@unlink("components/".JWMMXTD_COMP."/tmp/".$primage);
                 	$primage = $pic->file_dst_name;
                 }
                 
	} else $img2out = "Ошибка при обработке файла ".$imagepath;

	
	$objResponse = new xajaxResponse();
	//$objResponse->addAssign("mymsg","innerHTML",$imagepath."--".$primage);
	$objResponse->addClear("mainimage","src");
	$objResponse->addAssign("loading_placeholder","innerHTML",'');
	$objResponse->addAssign("mmxtd","innerHTML",$img2out);
	$objResponse->addAssign("primage","innerHTML","<input type=\"hidden\" name=\"primage\" id=\"primage\" value=\"".$primage."\">");
	$objResponse->addAssign("imagepath", "value", "components/".JWMMXTD_COMP."/tmp/".$primage);
	$objResponse->addAssign("width", "value", "");
	$objResponse->addAssign("height", "value", "");
	$objResponse->addAssign("rotation", "value", "0");
	$objResponse->addAssign("flip", "value", "none");
	$objResponse->addAssign("convert", "value", "none");
	$objResponse->addAssign("bevelpx", "value", "");
	$objResponse->addAssign("beveltl", "value", "");
	$objResponse->addAssign("bevelrb", "value", "");
	$objResponse->addAssign("borderw", "value", "");
	$objResponse->addAssign("borderc", "value", "");
	$objResponse->addAssign("bordert", "value", "");
	$objResponse->addAssign("borderr", "value", "");
	$objResponse->addAssign("borderb", "value", "");
	$objResponse->addAssign("borderl", "value", "");
	$objResponse->addAssign("borderc2", "value", "");
	$objResponse->addAssign("tint", "value", "");
	$objResponse->addAssign("overlayp", "value", "");
	$objResponse->addAssign("overlayc", "value", "");
	$objResponse->addAssign("brightness", "value", "");
	$objResponse->addAssign("contrast", "value", "");
	$objResponse->addAssign("threshold", "value", "");
	$objResponse->addAssign("greyscale","checked",false);
	$objResponse->addAssign("negative","checked",false);
	
	$objResponse->addAssign("text", "value", "");
	$objResponse->addAssign("textcolor", "value", "");
	$objResponse->addAssign("textfont", "value", "");
	$objResponse->addAssign("textpercent", "value", "");
	$objResponse->addAssign("textdirection", "value", "none");
	$objResponse->addAssign("textposition", "value", "none");
	$objResponse->addAssign("bgcolor", "value", "");
	$objResponse->addAssign("bgpercent", "value", "");
	$objResponse->addAssign("textpaddingx", "value", "");
	$objResponse->addAssign("textpaddingy", "value", "");
	$objResponse->addAssign("textabsolutex", "value", "");
	$objResponse->addAssign("textabsolutey", "value", "");
	
	return $objResponse;
}

function editImage( $img, $cur ) {
	global $mosConfig_live_site, $option;
	global $mosConfig_absolute_path;
	require_once ($mosConfig_absolute_path."/includes/xajax/xajax.inc.php");
	$path = JWMMXTD_STARTURLPATH . $cur . "/" . $img;
	$xajax = new xajax();
	//$xajax->debugOn();
	$xajax->registerFunction("UpdateImage");
	$xajax->registerFunction("OriginalImage");
	$xajax->registerFunction("MoveImage");
	$xajax->processRequests();
	$xajax->printJavascript($mosConfig_live_site.'/includes/xajax');
?>
<!-- JoomlaWorks MEDIA MANAGER XTD starts here [edit page] -->
<script type="text/javascript">
	function UpdateImg(value){
		document.getElementById("loading_placeholder").innerHTML='<div id="loadingbar"><img src="<?php echo $mosConfig_live_site?>/administrator/components/<?php echo JWMMXTD_COMP?>/images/loading_bar.gif" border="0" /></div>';
		xajax_UpdateImage(value);
	}
</script>
<div id="loading_placeholder"></div>
<div id="jwmmxtd">
  <h1 class="title"><?php echo _JWMEDIAMAN_TITLE; ?></h1>
  <div id="mymsg"></div>
  <div id="action_buttons">
    <div class="action_button" onclick="UpdateImg(xajax.getFormValues('adminForm'))"><?php echo _JWMEDIAMAN_CLICKCONVERT; ?></div>
    <div class="action_button" onclick="xajax_OriginalImage(xajax.getFormValues('adminForm'));"><?php echo _JWMEDIAMAN_CLICKORIGINAL; ?></div>
    <div class="action_button" onclick="submitform('saveimage');"><?php echo _JWMEDIAMAN_CLICKSAVEIMAGE; ?></div>
    <div class="action_button" onclick="submitform('returnfromedit');"><?php echo _JWMEDIAMAN_CLICKJWMEDIAMAN; ?></div>
  </div>
  <div id="show_image_path"><?php echo _JWMEDIAMAN_IMAGE_PATH.'<b>'.$path.'</b>'; ?></div>
  <div id="jwmmxtd_editpage">
    <div id="jwmmxtd_image">
      <div id="mmxtd"><?php echo "<img name=\"mainimage\" id=\"mainimage\" src='".$path."'>"; ?></div>
    </div>
    <div id="jwmmxtd_panel">
      <form method="POST" id="adminForm" name="adminForm" enctype="multipart/form-data" onSubmit="return false;">
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_WIDTHHEIGHT; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_WIDTH; ?>
        <input id="width" name="width" type="text" size="4" />
        x
        <input id="height" name="height" type="text" size="4" />
        <?php echo _JWMEDIAMAN_TEXT_HEIGHT; ?>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_EXT; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_EXT; ?>
        <select id="convert" name="convert">
          <option value="none"><?php echo _JWMEDIAMAN_SELECT; ?></option>
          <option value="jpg">jpg</option>
          <option value="gif">gif</option>
          <option value="png">png</option>
        </select>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_GROP; ?></legend>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_TEXT_GROP_PER; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_GROP_PER; ?>
        <input id="crop" name="crop" type="text" size="4" />
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_TEXT_GROP_DIMEN; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_V; ?>
        <input id="cropv" name="cropv" type="text" size="4" />
        <?php echo _JWMEDIAMAN_TEXT_H; ?>
        <input id="cropo" name="cropo" type="text" size="4" />
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BORD_SIDES; ?></legend>
        <table cellpadding="0" cellspacing="0" style="text-align:center;">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BORD_TOP; ?><br />
              <input id="cropt" name="cropt" type="text" size="4" /></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BORD_LEFT; ?>
              <input id="cropl" name="cropl" type="text" size="4" />
              &nbsp;
              <input id="cropr" name="cropr" type="text" size="4" />
              <?php echo _JWMEDIAMAN_TEXT_BORD_RIGHT; ?></td>
          </tr>
          <tr>
            <td><input id="cropb" name="cropb" type="text" size="4" />
              <br />
              <?php echo _JWMEDIAMAN_TEXT_BORD_BOTTOM; ?></td>
          </tr>
        </table>
        </fieldset>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_ROT; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_DEGREES; ?>
        <select id="rotation" name="rotation">
          <option value="0"><?php echo _JWMEDIAMAN_SELECT; ?></option>
          <option value="90">90</option>
          <option value="180">180</option>
          <option value="270">270</option>
        </select>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_FLIP; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_DIRECTION; ?>
        <select id="flip" name="flip">
          <option value="none"><?php echo _JWMEDIAMAN_SELECT; ?></option>
          <option value="H"><?php echo _JWMEDIAMAN_TEXT_V; ?></option>
          <option value="V"><?php echo _JWMEDIAMAN_TEXT_H; ?></option>
        </select>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BEVEL; ?></legend>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BEVEL_PX; ?></td>
            <td><input id="bevelpx" name="bevelpx" type="text" /></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BEVEL_TL ?></td>
            <td><input id="beveltl" name="beveltl" type="text" />
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].beveltl)"><img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"></a></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BEVEL_RB ?></td>
            <td><input id="bevelrb" name="bevelrb" type="text" />
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].bevelrb)"><img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"></a></td>
          </tr>
        </table>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BORD; ?></legend>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BORD; ?></legend>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_WIDTH; ?></td>
            <td><input id="borderw" name="borderw" type="text" /></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_COLOR; ?></td>
            <td><input id="borderc" name="borderc" type="text" />
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].borderc)"> <img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"> </a></td>
          </tr>
        </table>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BORD_ALL; ?></legend>
        <table cellpadding="0" cellspacing="0" style="text-align:center;">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BORD_TOP; ?><br />
              <input id="bordert" name="bordert" type="text" size="4" /></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_BORD_LEFT; ?>
              <input id="borderl" name="borderl" type="text" size="4" />
              &nbsp;
              <input id="borderr" name="borderr" type="text" size="4" />
              <?php echo _JWMEDIAMAN_TEXT_BORD_RIGHT; ?></td>
          </tr>
          <tr>
            <td><input id="borderb" name="borderb" type="text" size="4" />
              <br />
              <?php echo _JWMEDIAMAN_TEXT_BORD_BOTTOM; ?><br />
              <?php echo _JWMEDIAMAN_TEXT_COLOR; ?>
              <input id="borderc2" name="borderc2" type="text" />
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].borderc2)"><img width="16" height="16" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"></a> </td>
          </tr>
        </table>
        </fieldset>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_TINT; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_COLOR; ?>
        <input id="tint" name="tint" type="text" />
        <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].tint)"> <img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"> </a>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_OVERLAY; ?></legend>
        <table cellpadding="0" cellspacing="0">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_OVERLAY; ?></td>
            <td><input id="overlayp" name="overlayp" type="text" size="4" /></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_COLOR; ?></td>
            <td><input id="overlayc" name="overlayc" type="text" />
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].overlayc)"> <img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"> </a></td>
          </tr>
        </table>
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_BRIGHTNESS; ?></legend>
        <input id="brightness" name="brightness" type="text" />
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_CONTRAST; ?></legend>
        <input id="contrast" name="contrast" type="text" />
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_THRESHOLD; ?></legend>
        <input id="threshold" name="threshold" type="text" />
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_SPECIAL; ?></legend>
        <?php echo _JWMEDIAMAN_TEXT_GREYSCALE; ?>
        <input type="checkbox" name="greyscale" id="greyscale">
        <?php echo _JWMEDIAMAN_TEXT_NEGATIVE; ?>
        <input type="checkbox" name="negative" id="negative">
        </fieldset>
        <fieldset>
        <legend><?php echo _JWMEDIAMAN_LEG_TEXT; ?></legend>
        <table cellpadding="0" cellspacing="2">
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT; ?></td>
            <td><input type="text" name="text" id="text">
            </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_COLOR; ?></td>
            <td><input type="text" name="textcolor" id="textcolor">
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].textcolor)"> <img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"> </a> </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_FONT; ?></td>
            <td><input type="text" name="textfont" id="textfont">
            </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_PER; ?></td>
            <td><input type="text" name="textpercent" id="textpercent"></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_DIRECTION; ?></td>
            <td><select name="textdirection" id="textdirection">
                <option value="none"><?php echo _JWMEDIAMAN_SELECT; ?></option>
                <option value="h"><?php echo _JWMEDIAMAN_TEXT_H; ?></option>
                <option value="v"><?php echo _JWMEDIAMAN_TEXT_V; ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_POS; ?></td>
            <td><select name="textposition" id="textposition">
                <option value="none"><?php echo _JWMEDIAMAN_SELECT; ?></option>
                <option value="TL">Top - Left</option>
                <option value="T">Top</option>
                <option value="TR">Top - Right</option>
                <option value="L">Left</option>
                <option value="R">Right</option>
                <option value="BL">Bottom - Left</option>
                <option value="B">Bottom</option>
                <option value="BR">Bottom - Right</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_BG_PER; ?></td>
            <td><input type="text" name="bgpercent" id="bgpercent">
            </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_BG_COLOR; ?></td>
            <td><input type="text" name="bgcolor" id="bgcolor">
              <a style="cursor:pointer;" onClick="showColorPicker(this,document.forms[0].bgcolor)"> <img width="16" height="16" border="0" alt="Click Here to Pick up the color" src="<?php echo $mosConfig_live_site.'/administrator/components/'.JWMMXTD_COMP.'/images/color_wheel.png'; ?>"> </a> </td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_PADDING; ?></td>
            <td> X:
              <input type="text" name="textpaddingx" id="textpaddingx" size="4">
              Y:
              <input type="text" name="textpaddingy" id="textpaddingy" size="4"></td>
          </tr>
          <tr>
            <td><?php echo _JWMEDIAMAN_TEXT_TEXT_ABS_POS; ?></td>
            <td> X:
              <input type="text" name="textabsolutex" id="textabsolutex" size="4">
              Y:
              <input type="text" name="textabsolutey" id="textabsolutey" size="4"></td>
          </tr>
        </table>
        </fieldset>
        <input type="hidden" name="imagepath" id="imagepath" value="<?php echo $path; ?>">
        <input type="hidden" name="originalimage" id="originalimage" value="<?php echo $img; ?>">
        <input type="hidden" name="curdirectory" id="curdirectory" value="<?php echo $cur; ?>">
        <input type="hidden" name="option" id="option" value="<?php echo $option; ?>">
        <input type="hidden" name="task" id="task" value="">
        <div id="primage"></div>
      </form>
    </div>
    <div class="jwmmxtd_clr"></div>
  </div>
</div>
<div id="jwmmxtd_credits"><?php echo _JWMEDIAMAN_CREDITS; ?></div>
<!-- JoomlaWorks MEDIA MANAGER XTD ends here [edit page] -->
<?php } ?>
