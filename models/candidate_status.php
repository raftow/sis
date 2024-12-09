<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table candidate_status : candidate_status - حالات القبول 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CandidateStatus extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "candidate_status"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("candidate_status","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "candidate_status_name_ar";
                $this->ORDER_BY_FIELDS = "id";
                $this->public_display = true;
	}
}
?>