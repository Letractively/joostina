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


class JFiler{

  var $zipped   = false;
  var $filename = '';
  var $isopen   = false;
  var $fp       = 0;
  var $reopen   = false;


  function JFiler($zipped = false){
    $this->zipped = $zipped;
  }

  function compressFile($source, $level=false){
     $dest=$source.'.gz';
     $mode='wb'.$level;
     $error=false;
     if($fp_out=gzopen($dest,$mode)){
         if($fp_in=fopen($source,'rb')){
             while(!feof($fp_in))
                 gzwrite($fp_out,fread($fp_in,1024*512));
             fclose($fp_in);
             }
           else $error=true;
         gzclose($fp_out);
         }
       else $error=true;
     if($error) return false;
       else return $dest;
  }

  function createFile($filename){
    $this->filename = $filename;
    if ($this->fp = fopen($this->filename, "wb")){
       @chmod ($configfile, 0777);
       $this->isopen = true;
       return $this->filename;
    }else{
       $this->isopen = false;
       return FALSE;
    }
  }

  function openFile($filename){
    $this->filename = $filename;
    if ($this->fp = fopen($this->filename, "ab")){
       $this->isopen = true;
       return $this->filename;
    }else{
       $this->isopen = false;
       return false;
    }
  }

  function writeFile($data){
    fwrite($this->fp, $data);
  }

  function closeFile(){
    if ($this->zipped){
       $this->compressFile($this->filename, 9);
       fclose($this->fp);
       unlink($this->filename);
       $this->isopen = false;
    }else{
       fclose($this->fp);
       $this->isopen = false;
    }
  }

  function getFileSize(){
    $size = 0;
    if (!$this->isopen){
       if ($this->zipped){
          $size = filesize($this->filename.".gz");
       }else{
          $size = filesize($this->filename);
       }
    }
    return $size;
  }

  function getFileInfo($filename){
    define ("MAX_LINE_LENGTH",65536);
    $path_parts = pathinfo($filename);
    if (strtolower($path_parts["extension"]) == "gz"){
       $file = gzopen($filename, "r");
       while (!gzeof($file)){
             $buffer = trim(gzgets($file, MAX_LINE_LENGTH));
             if (strlen($buffer)== 0){
                break;
             }else{
                $info.= str_replace(' ', "&nbsp;", $buffer)."<br />";
             }
       }
       gzclose($file);
       return $info;
    }else{
       $file = fopen($filename, "r");
       while (!feof($file)){
             $buffer = trim(fgets($file, MAX_LINE_LENGTH));
             if (strlen($buffer)== 0){
                break;
             }else{
                $info.= str_replace(' ', "&nbsp;", $buffer)."<br />";
             }
       }
       fclose($file);
       return $info;
    }

  }
}

?>
