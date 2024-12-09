<?php
// ------------------------------------------------------------------------------------
// 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Holiday extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "holiday"; 
        public static $DB_STRUCTURE = null; 
	
	public function __construct()
        {
            global $objme;
            
		parent::__construct("holiday","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "holiday_name";
                $this->ORDER_BY_FIELDS = "country_id, school_id, year, holiday_start_hdate";
                
                $context_cols = "country_id, school_id";
                $this->setContextAndPartitionCols("", $context_cols);
                
                if($objme)
                {
                   $this->set("school_id",$objme->getContextValue("sis", "school_id"));
                   $this->set("country_id",$objme->getContextValue("sis", "country_id"));
                }
	}
        
        public static function getHoliday($school_year_id,$hdate)
        {
                return null;
                if(!$school_year_id) return null;
                $file_dir_name = dirname(__FILE__); 
                require_once("$file_dir_name/school_year.php");
                $sy = new SchoolYear();
                $sy->load($school_year_id);
                $year = $sy->getVal("year");
                $sc = $sy->get("school_id");
                
                $hol_sc_id = $sc->getVal("holidays_school_id");
                if(!$hol_sc_id) $hol_sc_id = -1;
                
                $sc_id = $sc->getId(); 
                
                
                $holi = new Holiday();
                $holi->select("active","Y");
                $holi->where("school_id in ($hol_sc_id,$sc_id)");
                $holi->select("year", $year);
                $holi->where("'$hdate' between holiday_start_hdate and holiday_end_hdate");
                if($holi->load())
                {
                       return $holi;
                }
                else 
                {
                       return null; 
                }
        }
        
        public function list_of_year()
	{
		$file_dir_name = dirname(__FILE__);
                
                
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
?>