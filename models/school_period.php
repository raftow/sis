<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_period : school_period - الفترات الدراسية بوحدة دراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolPeriod extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "school_period"; 
        public static $DB_STRUCTURE = null; 
        
        
        
        public function __construct(){
		parent::__construct("school_period","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "period_name";
                $this->ORDER_BY_FIELDS = "period_name";
                $this->public_display = true;
	}
}
?>