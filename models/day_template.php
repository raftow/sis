<?php
// ------------------------------------------------------------------------------------
// day_template - أيام دراسية نموذجية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class DayTemplate extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "day_template"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("day_template","id","sis");
                SisDayTemplateAfwStructure::initInstance($this);
                
                
	}
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             if($mode=="mode_dayTemplateItemList")
             {
                   unset($link);
                   $my_id = $this->getId();
                   if($my_id)
                   {
                        $link = array();
                        $title = "إدارة حصص نموذج يوم دراسي ";
                        $title_detailed = $title;
                        $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=DayTemplateItem&currmod=sis&id_origin=$my_id&class_origin=DayTemplate&module_origin=sis&newo=10&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=day_template_id=$my_id&sel_day_template_id=$my_id";
                        $link["TITLE"] = $title;
                        $link["UGROUPS"] = array();
                        $otherLinksArray[] = $link;
                   }
             }
             
             
             
             return $otherLinksArray;
        }
}
?>