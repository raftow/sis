<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table wday : wday - أيام الأسبوع 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Wday extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		= "sis"; 
        public static $TABLE		= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("wday","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->IS_LOOKUP = true;
                $this->DISPLAY_FIELD = "wday_name_ar";
                $this->ORDER_BY_FIELDS = "id";
                $this->public_display = true;
	}
}
?>