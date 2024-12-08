<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table period : period - الفترات الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Period extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "period"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("period","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "period_name_ar";
                $this->ORDER_BY_FIELDS = "id";
                $this->public_display = true;
	}
}
?>