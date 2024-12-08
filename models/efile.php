<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table efile : efile - الملفات الإلكترونية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Efile extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "efile"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("efile","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "efile_name";
                $this->ORDER_BY_FIELDS = "efile_name";
	}
}
?>