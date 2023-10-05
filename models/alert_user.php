<?php
// ------------------------------------------------------------------------------------
// AlertUser - الاشعارات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class AlertUser extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "alert_user"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("alert_user","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $part_cols = "group_num, school_id, year, semester, sday_num, level_class_id, class_name, session_order, student_id, id"; 
                $context_cols = "group_num, school_id, year, employee_id";
                $this->ORDER_BY_FIELDS = $part_cols;
                
                $this->setContextAndPartitionCols($part_cols, $context_cols);
	}
        
        
}
?>