<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table courses_template : courses_template - نماذج المواد الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CoursesTemplate extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "courses_template"; 
        
        public static $DB_STRUCTURE = null; 
        
        
        
        public function __construct(){
		parent::__construct("courses_template","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "courses_template_name_ar";
                $this->ORDER_BY_FIELDS = "courses_template_name_ar";
                $this->public_display = true;
	}
}
?>