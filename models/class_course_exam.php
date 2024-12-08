<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table class_course_exam : class_course_exam - الإختبارات على مادة دراسية لصف نموذجي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ClassCourseExam extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("class_course_exam","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "class_course_exam_name";
                $this->ORDER_BY_FIELDS = "class_course_exam_name";
	}
}
?>