<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table alert_cat : alert_cat - إيجابي / سلبي / إعلامي / خطير  
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class AlertCat extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "alert_cat"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("alert_cat","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "alert_cat_name_ar";
                $this->ORDER_BY_FIELDS = "alert_cat_name_ar";
	}
}
?>