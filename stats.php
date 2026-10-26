<?php
if(!class_exists("AfwSession")) die("page-not-found");
$file_dir_name = dirname(__FILE__);

// require_once("$file_dir_name/../external/db.php");
// old include of afw.php
// require_once("$file_dir_name/../lib/afw/modes/afw_config.php");
// $datatable_on=1;
// $cl = "Request";
// $currmod = "crm";
// $currdb = $server_db_prefix."crm";
// $limite = 0;
// $genere_xls = 0;
// $arr_sql_conds = array();
// $arr_sql_conds[] = "me.active='Y'";

$objme = AfwSession::getUserConnected();
$myEmplId = $objme->getEmployeeId();

$out_scr .= "[Page on construction]";

                                   
?>