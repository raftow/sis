<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table student_exam : student_exam - نتائج اختبارات طالب(ة) 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class StudentExam extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null;  
        
        public function __construct(){
		parent::__construct("student_exam","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "student_exam_name";
                $this->ORDER_BY_FIELDS = "student_exam_name";
	}
}
?>