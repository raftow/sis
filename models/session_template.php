<?php
// ------------------------------------------------------------------------------------
// session_template - أوقات الحصص 
// ------------------------------------------------------------------------------------


$file_dir_name = dirname(__FILE__); 

// old include of afw.php

class SessionTemplate extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        
        
        public function __construct(){
		parent::__construct("session_template","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "";
                $this->public_display = true;
	}
}
?>