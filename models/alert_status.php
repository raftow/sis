<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table alert_type : alert_type - أنواع الاشعارات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class AlertStatus extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "alert_status"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("alert_status","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "alert_status_name_ar";
                $this->ORDER_BY_FIELDS = "alert_status_name_ar";
	}
}
?>