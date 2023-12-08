<?php
// ------------------------------------------------------------------------------------
// mysql> alter table week_template change level_class_id level_class_id int null;
// mysql> alter table week_template change school_id school_id int null;
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class WeekTemplate extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "week_template"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("week_template","id","sis");
                SisWeekTemplateAfwStructure::initInstance($this);
                
	}
}
?>