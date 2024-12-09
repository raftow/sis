<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table tservice : tservice - تعليم، تدريب رياضي، تعليم قرآن، روضة أطفال 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Tservice extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "tservice"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("tservice","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "tservice_name";
                $this->ORDER_BY_FIELDS = "tservice_name";
	}
}
?>