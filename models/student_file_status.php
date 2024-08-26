<?php
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
class StudentFileStatus extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        
        
        
        public function __construct(){
		parent::__construct("student_file_status","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "student_file_status_name_ar";
                $this->ORDER_BY_FIELDS = "student_file_status_name_ar";
                $this->IS_LOOKUP = true;
	}
}
?>