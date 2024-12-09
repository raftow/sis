<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table hday : hday - أيام التقويم الهجري 
// ------------------------------------------------------------------------------------
// ALTER TABLE `hday` CHANGE `wday_id` `wday_id` INT(11) NOT NULL DEFAULT '0'; 
// ALTER TABLE `hday` CHANGE `hday_num` `hday_num` SMALLINT(6) NOT NULL DEFAULT '0'; 
// ALTER TABLE `hday` CHANGE `active` `active` CHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y', CHANGE `hmonth` `hmonth` SMALLINT(6) NOT NULL DEFAULT '0', CHANGE `hday_date` `hday_date` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '13000101', CHANGE `holiday` `holiday` CHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N', CHANGE `hday_descr` `hday_descr` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '---', CHANGE `holiday_id` `holiday_id` INT(11) NOT NULL DEFAULT '0'; 

$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Hday extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "hday"; 
        public static $DB_STRUCTURE = null;  
	
	public function __construct(){
		parent::__construct("hday","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "school_year_id, hday_num";

                $this->UNIQUE_KEY = ["school_year_id","hday_gdat"];

	}

        public static function loadByMainIndex($school_year_id, $hday_num, $create_obj_if_not_found = false)
        {
                $obj = new Hday();
                $obj->select("school_year_id", $school_year_id);
                $obj->select("hday_num", $hday_num);
                
                if ($obj->load()) 
                {
                        if ($create_obj_if_not_found) $obj->activate();
                        return $obj;
                } 
                elseif ($create_obj_if_not_found) 
                {
                        $obj->set("school_year_id", $school_year_id);
                        $obj->set("hday_num", $hday_num);
                        
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                } 
                else return null;
        }

        public static function loadByGDate($school_year_id, $gdate, $create_obj_if_not_found = false)
        {
                $obj = new Hday();
                $obj->select("school_year_id", $school_year_id);
                $obj->select("hday_gdat", $gdate);
                
                if ($obj->load()) 
                {
                        if ($create_obj_if_not_found) $obj->activate();
                        return $obj;
                } 
                elseif ($create_obj_if_not_found) 
                {
                        $obj->set("school_year_id", $school_year_id);
                        $obj->set("hday_gdat", $gdate);
                        
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                } 
                else return null;
        }
        
        public static function getCurrHday($school_year_id)
        {
                global $file_dir_name;
                
                // include_once("$file_dir_name/../afw/com mon_date.php");
                $curr_hdate = AfwDateHelper::currentHijriDate();
                
                $hday = new Hday();
                $hday->select("school_year_id",$school_year_id);
                $hday->select("hday_date", $curr_hdate);
                $hday->select("active", "Y");
                
                if($hday->load())
                {
                   return $hday;
                }
                else return null;  
                
                 
        }


        public static function list_of_wday_id()
        {
            global $lang;
               return self::wdays()[$lang];
        }
        
        public static function wdays()
        {
                $arr_list_of_wdays = array();
                
                $arr_list_of_wdays["en"][1] = "sunday";
                $arr_list_of_wdays["ar"][1] = "الأحد";
                
                $arr_list_of_wdays["en"][2] = "monday";
                $arr_list_of_wdays["ar"][2] = "الاثنين";
                
                $arr_list_of_wdays["en"][3] = "tuesday";
                $arr_list_of_wdays["ar"][3] = "الثلاثاء";
                
                $arr_list_of_wdays["en"][4] = "wednesday";
                $arr_list_of_wdays["ar"][4] = "الأربعاء";
                
                $arr_list_of_wdays["en"][5] = "thursday";
                $arr_list_of_wdays["ar"][5] = "الخميس";
                
                $arr_list_of_wdays["en"][6] = "friday";
                $arr_list_of_wdays["ar"][6] = "الجمعة";
                
                $arr_list_of_wdays["en"][7] = "saturday";
                $arr_list_of_wdays["ar"][7] = "السبت";
                
                return $arr_list_of_wdays;
        }
}
?>