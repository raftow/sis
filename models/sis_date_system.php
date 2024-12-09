<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table sis_date_system : sis_date_system - الأنظمة التأريخية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SisDateSystem extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "sis_date_system"; 
        public static $DB_STRUCTURE = null; 
        
        
        
        public function __construct(){
		parent::__construct("sis_date_system","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "date_system_name";
                $this->ORDER_BY_FIELDS = "date_system_name";
                $this->public_display = true;
	}
}
?>