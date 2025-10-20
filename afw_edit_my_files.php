<?php
     $file_dir_name = dirname(__FILE__);
     include_once ("$file_dir_name/sis_start.php");
     $objme = AfwSession::getUserConnected();
     $me = "u".$objme->id;
     $codeme = substr(md5("code".$me),0,8);
     $_REQUEST["y"]=$codeme;
     include("$file_dir_name/../lib/afw/afw_edit_my_files.php");
?>