<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table currency : currency - العملات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Currency extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "currency"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("currency","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "currency_name_ar";
                $this->ORDER_BY_FIELDS = "currency_name_ar";
	}
}
?>