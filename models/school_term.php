<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_term : school_term - الفصول الدراسية بوحدة دراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolTerm extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "school_term"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("school_term","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "school_term_name";
                $this->ORDER_BY_FIELDS = "school_term_name";
                $this->public_display = true;
	}
}
?>