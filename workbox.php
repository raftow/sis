<?php
$file_dir_name = dirname(__FILE__);

require_once("$file_dir_name/../external/db.php");
// old include of afw.php
require_once("$file_dir_name/../lib/afw/modes/afw_config.php");


$datatable_on=1;
$cl = "Request";
$currmod = "crm";
$currdb = $server_db_prefix."crm";
$limite = 0;
$genere_xls = 0;

$arr_sql_conds = array();
$arr_sql_conds[] = "me.active='Y'";
$objme = AfwSession::getUserConnected();
$myEmplId = $objme->getEmployeeId();

if(CrmEmployee::isAdmin($myEmplId)) 
{
        // $arr_sql_conds[] = "(me.supervisor_id='$myEmplId' or me.supervisor_id=0 or me.supervisor_id is null)";
        // $arr_sql_conds[] = "((me.status_id in (3, 301)) or (me.status_id in (2, 201, 4) and me.employee_id in (0,$myEmplId)))"; // 2=sent, 3=redirected

        $arr_sql_conds[] = "((".Request::inboxSqlCond("supervisor", $myEmplId).") or (".Request::inboxSqlCond("investigator", $myEmplId)."))";
        $employee_title = AfwLanguageHelper::tt('مشرف خدمة العملاء :')." ".$objme->getDisplay($lang);
}
else
{
        $arr_sql_conds[] = Request::inboxSqlCond("investigator", $myEmplId);
        $orgunit_name = CrmEmployee::orgOfEmployee($myEmplId, false, false);
        $employee_title = "<b>".$objme->getShortDisplay($lang)."</b>";
        
        if($orgunit_name) $employee_title .= " " . $orgunit_name;
        //else $employee_title .= " xx";
} 

$sql_order_by = "request_priority asc, request_date asc, request_time asc, customer_id asc";                          

// $my_class = new $cl();
$result_page_title = "صندوق الوارد";
$tit_qedit_ppp_fixm = "عرض التذكرة";
$actions_tpl_arr = array();

$actions_tpl_arr["edit"] = array("framework_action");
                          
if($datatable_on) {
	include "$file_dir_name/../lib/afw/modes/afw_handle_default_search.php";
        $collapse_in = "";
}
else $collapse_in = "in";


$wb_prefix = AfwLanguageHelper::tt("صندوق الوارد لـ");

$out_scr .= "<div class='crm-title hzm-info'>$wb_prefix$employee_title</div>";

if($datatable_on) 
{
	if($data_count>0) $out_scr .= $search_result_html; // die("search_result_html=".$search_result_html); // 
        else $out_scr .= "<div class='crm-information hzm-info'>
        <i class=\"hzm-container-center hzm-vertical-align-middle hzm-icon-fm hzm-icon-inbox\"></i>
        لا يوجد طلبات في صندوق الوارد
        </div>";
} 


                             
?>