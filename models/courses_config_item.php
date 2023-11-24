<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table courses_config_item : courses_config_item - تفاصيلالمناهج والكتب للمواد الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CoursesConfigItem extends SisObject{

	public static $DATABASE		= ""; public static $MODULE		    = "sis"; public static $TABLE			= ""; public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("courses_config_item","id","sis");
                SisCoursesConfigItemAfwStructure::initInstance($this);


	}

        public static function loadByCourseTemplate($courses_config_template_id,$course_id,$level_class_id)
        {
                if(!$courses_config_template_id) throw new RuntimeException("loadByMainIndex : courses_config_template_id is mandatory field");
                if(!$course_id) throw new RuntimeException("loadByMainIndex : course_id is mandatory field");
                if(!$level_class_id) throw new RuntimeException("loadByMainIndex : level_class_id is mandatory field");
                $obj = new CoursesConfigItem();
                $obj->select("courses_config_template_id",$courses_config_template_id);
                $obj->select("course_id",$course_id); 
                $obj->select("level_class_id",$level_class_id); 
                if($obj->load())
                {
                     return $obj;
                }
                else
                {
                        $obj->select("courses_config_template_id",$courses_config_template_id);
                        $obj->select("course_id",$course_id); 
                        $obj->select("level_class_id",0);  // all level classes
                        if($obj->load())
                        {
                             return $obj;
                        }
                }
                
                return null;  
        }
        
        public function getDisplay($lang="ar")
        {
               $data = array();
               $link = array();
               
               list($data[0],$link[0]) = $this->displayAttribute("courses_config_template_id");
               list($data[1],$link[1]) = $this->displayAttribute("course_id");
               list($data[2],$link[2]) = $this->displayAttribute("level_class_id");
               
               return implode(" - ",$data);
        }

        protected function getOtherLinksArray(
                $mode,
                $genereLog = false,
                $step = 'all'
            ) 
        {
                global $me, $objme, $lang;
        
                $otherLinksArray = $this->getOtherLinksArrayStandard($mode,$genereLog,$step);
                $my_id = $this->getId();
                $displ = $this->getDisplay($lang);
             
                $courses_config_template_id = $this->getVal('courses_config_template_id');
                $level_class_id = $this->getVal('level_class_id');
                $course_id = $this->getVal('course_id');
        
                if($mode=="mode_studyProgramList")
                {
                        unset($link);
                        $link = array();
                        $title = "إضافة منهج تعليمي جديد";
                        $title_detailed = $title ."لـ : ". $displ;
                        $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=StudyProgram&currmod=sis&sel_courses_config_template_id=$courses_config_template_id&sel_course_id=$course_id&sel_level_class_id=$level_class_id";
                        $link["TITLE"] = $title;
                        $link["UGROUPS"] = array();
                        $otherLinksArray[] = $link;
                }
                return $otherLinksArray;
        }


        public function stepsAreOrdered()
        {
            return false;
        }
                
        
}
?>