<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_job : school_job -  المسميات الوظيفية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolJob extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "school_job"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("school_job","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->VIEW = true;
                $this->READONLY = true;
                $this->DISPLAY_FIELD = "school_job_name_ar";
                $this->ORDER_BY_FIELDS = "school_job_name_ar";
                $this->public_display = true;
	}
}
?>