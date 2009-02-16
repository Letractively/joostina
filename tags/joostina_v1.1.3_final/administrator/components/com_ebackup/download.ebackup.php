<?php

  $filename = "../../backups/".$_REQUEST['file'];
  $filesize = filesize($filename);
  header("Pragma: public");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Expires: 0");
  header("Content-Type: application/octet-stream");
  header("Content-Transfer-Encoding: binary");
  header("Content-Disposition: attachment; filename=".$_REQUEST['file']."; size=".$filesize);
  readfile_chunked($filename);

  function readfile_chunked($filename,$retbytes=true){
    $chunksize = 1*(1024*1024); // how many bytes per chunk
    $buffer = '';
    $cnt =0;
    $handle = fopen($filename, 'rb');
    if ($handle === false){
        return false;
    }
    while (!feof($handle)){
          $buffer = fread($handle, $chunksize);
          echo $buffer;
          if ($retbytes){
             $cnt += strlen($buffer);
          }
    }
    $status = fclose($handle);
    if ($retbytes && $status) {
       return $cnt; // return num. bytes delivered like readfile() does.
    }
    return $status;
  }


?>