<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table week_template : week_template - نماذج أسابيع التدريب 
// ------------------------------------------------------------------------------------

                
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