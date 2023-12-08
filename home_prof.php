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
if($myEmplId)
{
        $schoolList = SchoolEmployee::getSchoolList($myEmplId); 
        $schoolNameArr = [];
        if(count($schoolList)>1)
        {
                foreach($schoolList as $schoolObj)
                {
                        $schoolNameArr[] = $schoolObj->getDisplay($lang);
                }
                $out_scr .= "<div class='sis warning'>\n";
                $out_scr .= "أنت موظف في أكثر من مدرسة (انطر القائمة أدناه) لا تنصح الادارة باستخدام نفس الحساب (الجوال والبريد الالكتروني) في أكثر من مدرسة لاجتناب تداخل الحسابات وكثرة الأخطاء من قبل الموظف<br>\n";
                $out_scr .= "أنت موظف في :<br>\n";
                $out_scr .= implode("<br>\n", $schoolNameArr);
                $out_scr .= "</div>\n";
        }
        // die("home prof SchoolEmployee::getSchoolList($myEmplId) = ".var_export($schoolList,true)." school count = ".count($schoolList));
        $cSessObj = null;
        $schoolObjSelected = null;
        $schoolObjYearSelected = null;
        // first try to find current course session for prof
        foreach($schoolList as $schoolObj)
        {
                if($schoolObj)
                {
                        $schoolObjSelected = $schoolObj;                                        
                        $schoolEmployeeObj = SchoolEmployee::loadByMainIndex($myEmplId, $schoolObj->id);
                        $currSYear = $schoolObj->getCurrentSchoolYear();
                        if($currSYear and $schoolEmployeeObj)
                        {
                                $schoolObjYearSelected = $currSYear;
                                $cSessObj = $currSYear->findCurrentSession($schoolEmployeeObj->id);
                                // if found qedit it for attendance
                                if($cSessObj)
                                {
                                       
                                        break;         
                                }

                                $cSessObj = $currSYear->findStdBySession($schoolEmployeeObj->id);
                                // if found qedit it for attendance
                                if($cSessObj)
                                {
                                        break;           
                                }
                        }
                }
        }

        

        // if course session found qedit it for attendance
        if($cSessObj)
        {
                $link['URL'] = 
                "main.php?Main_Page=afw_mode_qedit.php&";              
                $link['URL'] .= 
                "&";
                $link['URL'] .= "&";
                $cl="StudentSession";
                $currmod="sis";
                $id_origin=$cSessObj->id;
                $class_origin="CourseSession";
                $module_origin="sis";
                $step_origin="4";
                $newo=-1;
                $limit=70;
                $ids="all";
                $school_id = $cSessObj->getVal('school_id');
                $levels_template_id = $cSessObj->getVal('levels_template_id');
                $school_level_order = $cSessObj->getVal('school_level_order');
                $level_class_order = $cSessObj->getVal('level_class_order');
                $class_name = $cSessObj->getVal('class_name');
                $session_date = $cSessObj->getVal('session_date');
                $session_order = $cSessObj->getVal('session_order');
                $_REQUEST = [];
                $_REQUEST["sel_school_id"]=$school_id;
                $_REQUEST["sel_levels_template_id"]=$levels_template_id;
                $_REQUEST["sel_school_level_order"]=$school_level_order;
                $_REQUEST["sel_level_class_order"]=$level_class_order;
                $_REQUEST["sel_class_name"]=$class_name;
                $_REQUEST["sel_session_date"]=$session_date;
                $_REQUEST["sel_session_order"]=$session_order;

                $fixm="school_id=$school_id,levels_template_id=$levels_template_id,school_level_order=$school_level_order,level_class_order=$level_class_order,class_name=$class_name,session_date=$session_date,session_order=$session_order";
                $fixmtit='تحضير الطلاب '.$cSessObj->getShortDisplay($lang);
                $fixmdisable=1;

                include("$file_dir_name/../lib/afw/modes/afw_mode_qedit.php");
                return;
        }
        


        
        
        if($schoolObjSelected)
        {
                $structure = [];
                $structure['MINIBOX-TEMPLATE'] = "tpl/prof_minibox_tpl.php";
                $structure['MINIBOX-TEMPLATE-PHP'] = true;
                $structure['MINIBOX-OBJECT-KEY'] = "schoolObj";
                $out_scr .= $schoolObjSelected->showMinibox($structure);
        }
        else
        {
                $out_scr .= "<div class='sis warning'>\n";
                $out_scr .= "عزيزي المستخدم حسابك غير مرتبط بمدرسة نرجوا منك مراجعة مشرف المدرسة التي تنتمي اليها. مع الشكر<br>\n";
                $out_scr .= "</div>\n";
        }        
}
else
{
        $out_scr .= "<div class='sis warning'>\n";
        $out_scr .= "عزيزي المستخدم حسابك غير مرتبط بشكل صحيح بموظف في نظام الموارد البشرية نرجوا منك مراجعة مشرف المدرسة التي تنتمي اليها. مع الشكر<br>\n";
        $out_scr .= "</div>\n";
}
                                   
?>