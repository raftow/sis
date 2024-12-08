<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table gremark : gremark - مجموعات ملاحظات على الطلاب 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Gremark extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "gremark"; 
        
        public static $DB_STRUCTURE = null; 
        
        
        
        
        public function __construct(){
		parent::__construct("gremark","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "gremark_name_ar";
                $this->ORDER_BY_FIELDS = "gremark_name_ar";
	}
}
?>