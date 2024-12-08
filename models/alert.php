<?php
// obsolted to rethink about : rafik 18/9/2022

/*                 
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Alert extends SisObject{

	public static $DATABASE		= ""; public static $MODULE		    = "sis"; public static $TABLE			= ""; public static $DB_STRUCTURE = null; 
        
        array(
		"group_num" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT", "MANDATORY"=>true),
		"school_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => school, "ANSMODULE" => sis, "DEFAULT" => 0, "MANDATORY"=>true),
		"owner_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => auser, "ANSMODULE" => ums, "DEFAULT" => 0, "MANDATORY"=>true),
		"year" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => true, "SIZE" => 4, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "TYPE" => "ENUM", "ANSWER" =>"FUNCTION"),
		"sday_num" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT", "MANDATORY"=>true),
		"alert_time" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT", "MANDATORY"=>true),
                "alert_num" => array("SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "READONLY" => true, "QEDIT" => false, "TYPE" => "INT"),

		"level_class_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, 
                                          "TYPE" => "FK", "ANSWER" => level_class, "ANSMODULE" => sis, 
                                          WHERE => "school_level_id in (select slvl.id from ".$server_db_prefix."sis.school_level slvl where slvl.levels_template_id = §levels_template_id§)", 
                                          "SEARCH-BY-ONE"=>true,
                                          "WHERE-SEARCH"=>"school_level_id in (select slvl.id 
                                                                  from ".$server_db_prefix."sis.school_level slvl 
                                                                      inner join ".$server_db_prefix."sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", "DEFAULT" => 0),
		"class_name" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, 
                               "EDIT" => true, "QEDIT" => true, "SIZE" => 1, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, 
                               "TYPE" => "TEXT","ENUM_ALPHA"=>true),
		"session_order" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		"student_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => student, "ANSMODULE" => sis, "DEFAULT" => 0),
                
		"alert_type_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => alert_type, "ANSMODULE" => sis, "DEFAULT" => 0, "MANDATORY"=>true),
		"alert_name_ar" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT", "MANDATORY"=>true),
		"alert_name_en" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "TYPE" => "TEXT", "MANDATORY"=>true),
		"alert_text_ar" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => "AREA", "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT", "MANDATORY"=>true),
                "alert_text_en" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => "AREA", "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "TYPE" => "TEXT", "MANDATORY"=>true),

		"alert_read" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => false, "READONLY" => true, "QEDIT" => false, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "YN"),
		"alert_read_hdate" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => false, "READONLY" => true, "QEDIT" => false, "SIZE" => 10, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "DATE", "FORMAT" => "CONVERT_NASRANI"),
		"alert_read_time" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => false, "READONLY" => true, "QEDIT" => false, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "TIME"),

                
                "created_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "created_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "updated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "updated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "validated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "validated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "active" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "DEFAULT" => "Y", "TYPE" => "YN"),
                "version" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "INT"),
                "update_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "pag", "TYPE" => "MFK"),
                "delete_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "pag", "TYPE" => "MFK"),
                "display_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "pag", "TYPE" => "MFK"),
                "sci_id" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "scenario_item", "ANSMODULE" => "pag"),
	);
	
	 public function __construct(){
		global $objme, $file_dir_name ;
                parent::__construct("alert","","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "alert_name_ar";
                $part_cols = "group_num, school_id, owner_id, year, sday_num, alert_time, alert_num"; 
                $context_cols = "group_num, school_id, year";
                $this->ORDER_BY_FIELDS = $part_cols;
                
                $this->setContextAndPartitionCols($part_cols, $context_cols);
                
                $this->UNIQUE_KEY = array('group_num','school_id','owner_id','year','sday_num','alert_time','alert_num');
                
                if($objme) {
                        $hijri_year = $objme->getContextValue("sis", "year");
                        $group_num = $objme->getContextValue("sis", "group_num");                
                        $school_id = $objme->getContextValue("sis", "school_id");
                        $school_year_id = $school_id.$hijri_year;
                        include_once("$file_dir_name/../sis/h day.php");
                        $objCurHday = Hday::getCurrHday($school_year_id);
                        if($objCurHday) $sday_num = $objCurHday->getVal("sday_num");
                        else $sday_num =  -1;
                                        
                        $this->set("owner_id",$objme->getId());
                        $this->set("group_num",$group_num);
                        $this->set("school_id",$school_id);
                        $this->set("year",$hijri_year);
                        $this->set("sday_num",$sday_num);
                        $this->set("alert_time",date("His"));
                        $this->set("alert_num",1);
                }
	}
        
        public static function loadByMainIndex($group_num, $school_id, $owner_id, $year, $sday_num, $alert_time, $alert_num,$create_obj_if_not_found=false)
        {
           $obj = new Alert();
           $obj->select("group_num",$group_num);
           $obj->select("school_id",$school_id);
           $obj->select("owner_id",$owner_id);
           $obj->select("year",$year);
           $obj->select("sday_num",$sday_num);
           $obj->select("alert_time",$alert_time);
           $obj->select("alert_num",$alert_num);

           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("group_num",$group_num);
                $obj->set("school_id",$school_id);
                $obj->set("owner_id",$owner_id);
                $obj->set("year",$year);
                $obj->set("sday_num",$sday_num);
                $obj->set("alert_time",$alert_time);
                $obj->set("alert_num",$alert_num);

                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }


        public function getDisplay($lang="ar")
        {
               if($this->getVal("alert_name_$lang")) return $this->getVal("alert_name_$lang");
               $data = array();
               $link = array();
               

               list($data[0],$link[0]) = $this->displayAttribute("group_num",false, $lang);
               list($data[1],$link[1]) = $this->displayAttribute("school_id",false, $lang);
               list($data[2],$link[2]) = $this->displayAttribute("owner_id",false, $lang);
               list($data[3],$link[3]) = $this->displayAttribute("year",false, $lang);
               list($data[4],$link[4]) = $this->displayAttribute("sday_num",false, $lang);
               list($data[5],$link[5]) = $this->displayAttribute("alert_time",false, $lang);
               list($data[6],$link[6]) = $this->displayAttribute("alert_num",false, $lang);

               
               return implode(" - ",$data);
        }
        
        public function getPKField()
	{
		return "";
	}
        
        public function list_of_year()
	{
		$file_dir_name = dirname(__FILE__);
                
                include_once("$file_dir_name/../afw/common_date.php");
                list($hijri_year,$mm,$dd) =AfwDateHelper::currentHijriDate("hlist");
                $hijri_year = intval($hijri_year);
                
                $arr_list_of_year = array();
                
                $hijri_year_m_1 = $hijri_year-1;
                $hijri_year_p_1 = $hijri_year+1;
                $hijri_year_p_2 = $hijri_year+2;
                
                $arr_list_of_year[$hijri_year_m_1] = "$hijri_year_m_1-$hijri_year";
                $arr_list_of_year[$hijri_year] = "$hijri_year-$hijri_year_p_1";
                $arr_list_of_year[$hijri_year_p_1] = "$hijri_year_p_1-$hijri_year_p_2";
                
                return $arr_list_of_year;
	}
        
        
}
*/
?>