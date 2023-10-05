<?php
$file_dir_name = dirname(__FILE__);
// set_time_limit(8400);
// ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

//// here old require of common.php
//require_once("$file_dir_name/../cms/menu_functions.php");
require_once("$file_dir_name/../external/db.php");  
// require_once student.php");




AfwSession::startSession();
$me = $_SESSION["user_id"];
$email = $_SESSION["user_email"];



$home_path_browser = $menu_title." &larr; ".$path_title;
$force_allow_access_to_customers = true;

$check_depending_user_type="NO-CHECK";


$objToShow = new Student();
$objToShow->load($me-5000000);
$ro = true;
if($objToShow and $objToShow->getId() and (!$ro))
{
        
        include_once ("$file_dir_name/ini.php");
        include_once ("$file_dir_name/module_config.php");
        $Main_Page = "afw_mode_edit.php";
        $My_Module = "pag";
        $cl = "Student";
        $currmod="sis";
        $id = $objToShow->getId();
        include("$file_dir_name/../lib/afw/afw_main_page.php");
        
        /*
        $Main_Page = "my_account.php";
        $My_Module = "pag";
        include_once ("$file_dir_name/ini.php");
        include_once ("$file_dir_name/module_config.php");
        
        include("$file_dir_name/../lib/afw/afw_main_page.php");*/
}
else
{
        include_once ("$file_dir_name/ini.php");
        include_once ("$file_dir_name/module_config.php");

        $direct_dir_name = "$file_dir_name/../pag"; 
        $Direct_Page = "show_object.php";
        include("$file_dir_name/../lib/afw/afw_direct_page.php");
}
?>