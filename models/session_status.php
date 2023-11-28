<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table session_status : session_status - حالات الحصص الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SessionStatus extends SisObject{

        public static $coming_session = '1';
        public static $current_session = '2';
        public static $opened_session = '3';
        public static $closed_session = '4';
        public static $canceled_session = '5';
        public static $missed_session = '6';        
        public static $standby_session = '7';        

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "session_status"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("session_status","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "session_status_name_ar";
                $this->ORDER_BY_FIELDS = "session_status_name_ar";
                $this->public_display = true;
	}
}
?>