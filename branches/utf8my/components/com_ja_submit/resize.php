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
/*
	Version 1.0 Created by: Ryan Stemkoski
	Questions or comments: ryan@ipowerplant.com
	Visit us on the web at: http://www.ipowerplant.com
	Purpose:  This script can be used to resize one or more images.  It will save the file to a directory and output the path to that directory which you
			  can display or write to a databse.

	TO USE, SET:
		$filename = image to be resized
		$newfilename = added to filename to for each use to keep from overwriting images created example thumbnail_$filename is how it will be saved.
		$path = where the image should be stored and accessed.
		$newwidth = resized width could be larger or smaller
		$newheight = resized height could be larger or smaller

	SAMPLE OF FUNCTION: makeimage('image.jpg','fullimage_','imgs/',250,250)

	Include the file containing the function in your document and simply call the function with the correct parameters and your image will be resized.

*/

//IMAGE RESIZE FUNCTION FOLLOW ABOVE DIRECTIONS
function makeimage($filelocation,$filename,$newfilename,$path,$newwidth,$newheight) {

	//SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER . )
	$image_type = strstr($filename, '.');

	//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
		switch($image_type) {
			case '.jpg':
				$source = imagecreatefromjpeg($filelocation);
				break;
			case '.jpeg':
				$source = imagecreatefromjpeg($filelocation);
				break;
			case '.JPG':
				$source = imagecreatefromjpeg($filelocation);
				break;
			case '.JPEG':
				$source = imagecreatefromjpeg($filelocation);
				break;
			case '.png':
				$source = imagecreatefrompng($filelocation);
				break;
			case '.PNG':
				$source = imagecreatefrompng($filelocation);
				break;
			case '.gif':
				$source = imagecreatefromgif($filelocation);
				break;
			case '.GIF':
				$source = imagecreatefromgif($filelocation);
				break;
			default:
				echo("Ошибка: Изображение неподдерживаемого типа");
				die;
				break;
			}

	//CREATES THE NAME OF THE SAVED FILE
	$file = $newfilename . strtolower($filename);

	//CREATES THE PATH TO THE SAVED FILE
	$fullpath = $path . $file;

	//FINDS SIZE OF THE OLD FILE
	list($width, $height) = getimagesize($filelocation);

	//CREATES IMAGE WITH NEW SIZES
	$thumb = imagecreatetruecolor($newwidth, $newheight);

	//RESIZES OLD IMAGE TO NEW SIZES
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	//SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100
	imagejpeg($thumb, $fullpath, 85);

	//CREATING FILENAME TO WRITE TO DATABSE
	$filepath = $fullpath;

	//RETURNS FULL FILEPATH OF IMAGE ENDS FUNCTION
	return $filepath;

}

?>
