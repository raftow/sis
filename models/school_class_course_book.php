<?php
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolClassCourseBook extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("school_class_course_book","id","sis");
                SisSchoolClassCourseBookAfwStructure::initInstance($this);
                
	}
        
        public function getDisplay($lang="ar")
        {
               list($data0,$link0) = $this->displayAttribute("school_year_id");
               list($data1,$link1) = $this->displayAttribute("level_class_id");
               list($data2,$link2) = $this->displayAttribute("class_name");
               list($data3,$link3) = $this->displayAttribute("course_id");
               list($data4,$link4) = $this->displayAttribute("book_id");
               
               return $data0." &larr; ".$data1." &larr; ".$data2." &larr; ".$data3." &larr; ".$data4;
        }
        
        

        public static function loadByMainIndex($school_year_id, $level_class_id, $class_name, $course_id, $book_id, $create_obj_if_not_found = false)
        {
                $obj = new SchoolClassCourseBook();
                $obj->select("school_year_id", $school_year_id);
                $obj->select("level_class_id", $level_class_id);
                $obj->select("class_name", $class_name);
                $obj->select("course_id", $course_id);
                $obj->select("book_id", $book_id);
                
                if ($obj->load()) {
                        if ($create_obj_if_not_found) $obj->activate();
                        return $obj;
                } 
                elseif ($create_obj_if_not_found) 
                {
                        $obj->set("school_year_id", $school_year_id);
                        $obj->set("level_class_id", $level_class_id);
                        $obj->set("class_name", $class_name);
                        $obj->set("course_id", $course_id);
                        $obj->set("book_id", $book_id);
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                } else return null;
        }
        
        public function calcSclass_course($what='value')
        {
                $school_year_id = $this->getVal("school_year_id"); 
                $level_class_id = $this->getVal("level_class_id");
                $class_name = $this->getVal("class_name");
                $course_id = $this->getVal("course_id");
                $return = SchoolClassCourse::loadByMainIndex($school_year_id, $level_class_id, $class_name,$course_id);        

                if($return)
                {
                        return ($what=='object') ? $return : $return->id;
                }
                else
                {
                        return ($what=='object') ? null : 0;
                }
        
        }


        
        
        protected function getSpecificDataErrors($lang="ar",$show_val=true,$step="all",$erroned_attribute=null,$stop_on_first_error=false, $start_step=null, $end_step=null)
        {
              $sp_errors = array();
              return $sp_errors;
        }

        public function stepsAreOrdered()
        {
            return false;
        }

        public function getFieldGroupInfos($fgroup)
        {
                

                return ['name' => $fgroup, 'css' => 'pct_100'];
        }


        public function list_of_main_sens()
        {
            global $lang;
            return StudyProgram::workSens()[$lang];
        }

        public function list_of_main_page_num()
        {
            $part_id =    $this->getVal("main_part_id");
            $chapter_id = $this->getVal("main_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function paragraphShort($lang="ar", $attribute)
        {            
            list($book_id, $paragraph_num, $chapter_id, $page_num, $prgh) = $this->getMyBookLocation($attribute);
            if(!$prgh) return "?!!!? [$chapter_id|$paragraph_num]";
            return AfwStringHelper::truncateArabicJomla($prgh->getVal("paragraph_text"), 32)."($paragraph_num)";
        }
        
        public function calcMain_paragraph_id($what="value")
        {
            return CpcBook::calcAttribute_paragraph_id($this, "main", $what);
        }

        public function paragraphShortFromTo($lang="ar", $attribute)
        {
            return CpcBook::paragraphShortFromTo($this, $attribute);
        }

        public function getMyBookLocation($attribute)
        {
            return CpcBook::getBookLocation($this, $attribute);
        }

        public function getBookParams($attribute)
        {
            $params = CpcBook::getBookParams($this, $attribute);
            $main_sens = $this->getVal("main_sens");
            if(($params["book_id"]==1) and ($main_sens==2))
            {
                $params["book_id"]=10001;
                //$params["chapter_id_to"]=11114;
            
            }
            if(($params["book_id"]<=1) and ($params["chapter_id_from"]>11000))
            {
                $params["book_id"]=10001;
            }
            //elseif($params["book_id"]==10001) $params["chapter_id_to"]=11114;
            //elseif($params["book_id"]==1) $params["chapter_id_to"]=1114;
            // die("... CpcBook::getBookParams($this, $attribute) = ".var_export($params,true));
            return $params;
        }

        public function calcReal_book_id($what="value")
        {
            $book_id = $this->getVal("main_book_id");    
            if(!$book_id) $book_id = 1;
            $main_sens = $this->getVal("main_sens");

            if(($main_sens==2) and ($book_id==1))
            {
                return 10001;
            }
            else return $book_id;
        }


        

        
}
?>