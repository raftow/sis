<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table day_template_item : day_template_item - حصص نموذج يوم دراسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class DayTemplateItem extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("day_template_item","id","sis");
                SisDayTemplateItemAfwStructure::initInstance($this);
                
	}


        public function getPrayerTimeList()
        {
                return AfwDateHelper::getPrayerTimeList();
        }
    
        public function getAfterPrayerTimeList()
        {
                return AfwDateHelper::getAfterPrayerTimeList();
        }

        public function getDisplay($lang = "ar")
        {
                return "حصة رقم ".$this->getVal("session_order");
        }

        
        
}
?>