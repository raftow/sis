<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table session_template_item : session_template_item - أوقات الحصص 
// ------------------------------------------------------------------------------------


$file_dir_name = dirname(__FILE__); 

// old include of afw.php

class SessionTemplateItem extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "session_template_item"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("session_template_item","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "";
                $this->public_display = true;
	}
}
?>