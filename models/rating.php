<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table rating : rating - الدرجات التقديرية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Rating extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "rating"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("rating","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "rating_name_ar";
                $this->ORDER_BY_FIELDS = "rating_name_ar";
	}
}
?>