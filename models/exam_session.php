<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table exam_session : exam_session - حصص اختبار على صف مدرسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ExamSession extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "exam_session"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("exam_session","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "exam_session_name";
                $this->ORDER_BY_FIELDS = "exam_session_name";
	}
}
?>