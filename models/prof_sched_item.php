<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table course_sched_item : course_sched_item - عناصر الجدول الدراسي 
// ------------------------------------------------------------------------------------
 
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ProfSchedItem extends SisObject
{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "prof_sched_item"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("prof_sched_item","id","sis");
                SisProfSchedItemAfwStructure::initInstance($this);
	}
        
        public function getDisplay($lang="ar")
        {
               $wday_id = $this->getVal("wday_id");
               $wday_curr = date("w")+1;
               
               if($wday_id==$wday_curr) $css_suffix = "_active";
               else $css_suffix = "";
               
               list($data0,$link0) = $this->displayAttribute("course_id");
               list($data,$link) = $this->displayAttribute("level_class_id");
               $symb_decoded = $this->decode("class_name");
               return "<div class='course_bloc$css_suffix'>$data0</div><div class='lclass_bloc$css_suffix'> $data $symb_decoded</div>";
        }


}
?>