<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_employee : school_employee - الموظفون في المنشآت 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class StudentUpload extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("student_upload","id","sis");
                SisStudentUploadAfwStructure::initInstance($this);
	}
        
        

}
?>