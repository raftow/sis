<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_member_day : school_member_day - تسجيل الحضور والغياب لطالب/موظف في وحدة دراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolMemberDay extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "school_member_day"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("school_member_day","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "";
                $this->public_display = true;
	}
}
?>