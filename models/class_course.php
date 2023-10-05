<?php

// obsolete

// ------------------------------------------------------------------------------------
// ----             auto generated php class of table class_course : class_course - قدارت المدرسين على تدريس المواد الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ClassCourse extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "class_course"; 
    public static $DB_STRUCTURE = null; 
    
    
    
    public function __construct(){
		parent::__construct("class_course","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "class_course_name";
                $this->ORDER_BY_FIELDS = "class_course_name";
	}
}
?>