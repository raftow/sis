<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_course_program_book : cpc_course_program_book - الكتب الدراسية لبرنامج دراسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcCourseProgramBook extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "cpc_course_program_book"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("cpc_course_program_book","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "course_program_id, level_class_id, book_id";
                
                
	}
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             if($mode=="mode_cpcCoursePlanList")
             {
                   $course_program_id = $this->getVal("course_program_id");
                   $course_program = $this->showAttribute("course_program_id"); 
                   $course_id = $this->getVal("course_id");
                   $course = $this->showAttribute("course_id");
                   $level_class_id = $this->getVal("level_class_id");
                   $level_class = $this->showAttribute("level_class_id");
             
                   unset($link);
                   $my_id = $this->getId();
                   $link = array();
                   $title = "إدارة المحتوى الدراسي";
                   $title_detailed = $title ." : $course_program / صف $level_class / مادة $course";
                   $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcCoursePlan&currmod=sis&id_origin=$my_id&class_origin=CpcCourseProgramBook&module_origin=sis&newo=5&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=course_program_id=$course_program_id,level_class_id=$level_class_id,course_id=$course_id&sel_course_program_id=$course_program_id&sel_level_class_id=$level_class_id&sel_course_id=$course_id";
                   $link["TITLE"] = $title;
                   $link["UGROUPS"] = array();
                   $otherLinksArray[] = $link;
             }
             
             return $otherLinksArray;
        }
             
}
?>