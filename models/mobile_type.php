<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table mobile_type : mobile_type - أنواع الجوالات ايفون/اندرويد ... 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class MobileType extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "mobile_type"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("mobile_type","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "mobile_type_name_ar";
                $this->ORDER_BY_FIELDS = "mobile_type_name_ar";
	}
}
?>