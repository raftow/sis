<?php
$direct_dir_name = $file_dir_name = dirname(__FILE__);
include("$file_dir_name/sis_start.php");
$objme = AfwSession::getUserConnected();
//if(!$objme) $studentMe = AfwSession::getStudentConnected();
$studentMe = null;
if (!$lang) $lang = "ar";

$id_cs = "";
$cs_action = "";


if ($_GET["id-cls"]) {
        $id_cs = $_GET["id-cls"];
        $cs_action = "closeSession";
}

if ($_GET["id-rsw"]) {
        $id_cs = $_GET["id-rsw"];
        $cs_action = "resetAllWorksFromManhajAndInjaz";
}

if ($_GET["id-uss"]) {
        $id_cs = $_GET["id-uss"];
        $cs_action = "updateMyStudentWork"; // updateStudentSessionsWithMe
}







//die("rafik index 1 : user_id=".AfwSession::getSessionVar("user_id")." objme=".var_export($objme,true));

if ($objme) {
        if ($id_cs and $cs_action) {
                $csObj = CourseSession::loadById($id_cs);
                if ($csObj and $csObj->canAccessMe($objme)) {
                        list($err, $inf, $war) = $csObj->$cs_action($lang);
                        if ($err) AfwSession::pushError($err);
                        if ($inf) AfwSession::pushSuccess($inf);
                        if ($war) AfwSession::pushWarning($war);
                } else {
                        AfwSession::pushWarning($objme->tm("Not authorized action", $lang));
                }
        }
        // depending on role of user we show different home page
        $objme_is_school_admin = false;
        $objme_is_school_admin =
                (
                        $objme->isSuperAdmin() or
                        $objme->hasRole('sis', SchoolEmployee::$sis_role_education) or
                        $objme->hasRole('sis', SchoolEmployee::$sis_role_administrative) or
                        $objme->hasRole('sis', SchoolEmployee::$sis_role_programs)
                );

        $objme_is_prof = false;
        $objme_is_prof =
                (
                        $objme->isSuperAdmin() or
                        $objme->hasRole('sis', SchoolEmployee::$sis_role_prof)
                );




        $moeaction = $_GET["moea"];
        if ($moeaction) {
                $Main_Page = "pt.php";
                $My_Module = "sis";
                unset($_POST);
                unset($_GET);
                require("$file_dir_name/../lib/afw/afw_main_page.php");
                $options = AfwMainPage::getDefaultOptions($Main_Page);
                // die("main-options for $Main_Page : ".var_export($options,true));
                AfwMainPage::echoMainPage($MODULE, $Main_Page, $file_dir_name, $options);
        }
        if (false and $objme->isSuperAdmin()) {
                $Main_Page = "monitoring.php";
                $My_Module = "sis";
                unset($_POST);
                unset($_GET);
                $studentMe = null;

                require("$file_dir_name/../lib/afw/afw_main_page.php");
                $options = AfwMainPage::getDefaultOptions($Main_Page);
                // die("main-options for $Main_Page : ".var_export($options,true));
                AfwMainPage::echoMainPage($MODULE, $Main_Page, $file_dir_name, $options);
        } elseif (false) {
                $controllerName = "School";
                $methodName = "myschools";

                $file_dir_name = dirname(__FILE__);
                require("$file_dir_name/i.php");
        } elseif ($objme_is_school_admin) {
                $Main_Page = "home.php";
                $My_Module = "sis";
                /*
                $cl = "Request";
                $currmod="crm";
                */
                $studentMe = null;
                unset($_POST);
                unset($_GET);
                $page_css_file = "content";

                // AfwRunHelper::simpleError("System under maintenance. contactez RB");
                require("$file_dir_name/../lib/afw/afw_main_page.php");
                $options = AfwMainPage::getDefaultOptions($Main_Page);
                // die("main-options for $Main_Page : ".var_export($options,true));
                AfwMainPage::echoMainPage($MODULE, $Main_Page, $file_dir_name, $options);
        } elseif ($objme_is_prof) {
                $Main_Page = "home_prof.php";
                $My_Module = "sis";
                /*
                $cl = "Request";
                $currmod="crm";
                */
                $studentMe = null;
                unset($_POST);
                unset($_GET);
                $page_css_file = "content";

                // AfwRunHelper::simpleError("System under maintenance. contactez RB");
                require("$file_dir_name/../lib/afw/afw_main_page.php");
                $options = AfwMainPage::getDefaultOptions($Main_Page);
                // die("main-options for $Main_Page : ".var_export($options,true));
                AfwMainPage::echoMainPage($MODULE, $Main_Page, $file_dir_name, $options);
        } else {
                $mess = "Your are registered now, you can contact your administrator to give you privileges";
                if ($objme) $mess = $objme->tm($mess, $lang);
                die($mess);
        }


        /*
        $force_allow_access_to_customers = true; 
        $Direct_Page = "main_menu.php";
        
        include("$file_dir_name/../lib/afw/afw_direct_page.php");*/
} elseif ($studentMe)  // يدخل كطالب
{
        $controllerName = "Sis";
        $methodName = "myhomework";

        $file_dir_name = dirname(__FILE__);
        include("$file_dir_name/../sis/i.php");
} else {
        include("$file_dir_name/../sis/login.php");
}
