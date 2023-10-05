<?php
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

$schoolList = SchoolEmployee::getSchoolList($myEmplId);    
$structure = [];
$structure['MINIBOX-TEMPLATE'] = "tpl/school_minibox_tpl.php";
$structure['MINIBOX-TEMPLATE-PHP'] = true;
$structure['MINIBOX-OBJECT-KEY'] = "schoolObj";

foreach($schoolList as $schoolObj)
{
        $out_scr .= $schoolObj->showMinibox($structure);
}

                                   
?>