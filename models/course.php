<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table course : course - المواد الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Course extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= ""; 
    public static $DB_STRUCTURE = null; 

    public static $course_is_repot = 6;
    
    public function __construct(){
		parent::__construct("course","id","sis");
        SisCourseAfwStructure::initInstance($this);
	}
 
        public static function loadById($id)
        {
           $obj = new Course();
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }
 
 
 
        public static function loadByMainIndex($lookup_code,$create_obj_if_not_found=false)
        {
           $obj = new Course();
           if(!$lookup_code) throw new AfwRuntimeException("loadByMainIndex : lookup_code is mandatory field");
           $obj->select("lookup_code",$lookup_code);
 
           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("lookup_code",$lookup_code);
 
                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
 
        }
 
 
        public function getDisplay($lang="ar")
        {
 
               $data = array();
               $link = array();
 
 
               list($data["ar"],$link["ar"]) = $this->displayAttribute("course_name_ar",false, $lang);
               list($data["en"],$link["en"]) = $this->displayAttribute("course_name_en",false, $lang);
 
 
               return $data[$lang];
        }
 
 
 
 
 
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
 
 
 
             return $otherLinksArray;
        }
 
        protected function getPublicMethods()
        {
 
            $pbms = array();
 
            $color = "green";
            $title_ar = "xxxxxxxxxxxxxxxxxxxx"; 
            $methodName = "mmmmmmmmmmmmmmmmmmmmmmm";
            //$pbms[self::hzmEncode($methodName)] = array("METHOD"=>$methodName,"COLOR"=>$color, "LABEL_AR"=>$title_ar, "ADMIN-ONLY"=>true, "BF-ID"=>"");
 
 
 
            return $pbms;
        }
 
        public function fld_CREATION_USER_ID()
        {
                return "created_by";
        }
 
        public function fld_CREATION_DATE()
        {
                return "created_at";
        }
 
        public function fld_UPDATE_USER_ID()
        {
        	return "updated_by";
        }
 
        public function fld_UPDATE_DATE()
        {
        	return "updated_at";
        }
 
        public function fld_VALIDATION_USER_ID()
        {
        	return "validated_by";
        }
 
        public function fld_VALIDATION_DATE()
        {
                return "validated_at";
        }
 
        public function fld_VERSION()
        {
        	return "version";
        }
 
        public function fld_ACTIVE()
        {
        	return  "active";
        }
 
        public function isTechField($attribute) {
            return (($attribute=="created_by") or ($attribute=="created_at") or ($attribute=="updated_by") or ($attribute=="updated_at") or ($attribute=="validated_by") or ($attribute=="validated_at") or ($attribute=="version"));  
        }
 
 
        protected function beforeDelete($id,$id_replace) 
        {
            
 
            if(!$id)
            {
                $id = $this->getId();
                $simul = true;
            }
            else
            {
                $simul = false;
            }
 
            if($id)
            {   
               if($id_replace==0)
               {
                   $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK part of me - not deletable 
 
 
                   $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK part of me - deletable 
 
 
                   // FK not part of me - replaceable 
                       // sis.sexam-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once sexam.php";
                            Sexam::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.sexam set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.course_session-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once course_session.php";
                            CourseSession::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.course_session set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.school_class_course-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once school_class_course.php";
                            SchoolClassCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_class_course set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.courses_config_item-المادة دراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once courses_config_item.php";
                            CoursesConfigItem::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_config_item set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.cpc_course_plan-المادة دراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once cpc_course_plan.php";
                            CpcCoursePlan::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.cpc_course_plan set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.cpc_course_program_book-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once cpc_course_program_book.php";
                            CpcCourseProgramBook::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.cpc_course_program_book set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.class_course-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once class_course.php";
                            ClassCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.class_course set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.prof sched item-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            ProfSchedItem::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                        }
                       // summer.summer_course-المقرر الدراسي	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // SummerCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_course set course_id='$id_replace' where course_id='$id' ");
                        }
 
 
 
                   // MFK
                       // sis.school_employee-المواد الدراسية التي يدرسها	course_mfk  
                        if(!$simul)
                        {
                            // require_once school_employee.php";
                            SchoolEmployee::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_employee set course_mfk=REPLACE(course_mfk, ',$id,', ',') where course_mfk like '%,$id,%' ");
                        }
 
                       // sis.courses_template-المواد الدراسية	course_mfk  
                        if(!$simul)
                        {
                            // require_once courses_template.php";
                            CoursesTemplate::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_template set course_mfk=REPLACE(course_mfk, ',$id,', ',') where course_mfk like '%,$id,%' ");
                        }
 
                       // sis.sprof-المواد الدراسية التي يدرسها	course_mfk  
                        if(!$simul)
                        {
                            // require_once sprof.php";
                            SchoolEmployee::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.sprof set course_mfk=REPLACE(course_mfk, ',$id,', ',') where course_mfk like '%,$id,%' ");
                        }
 
 
               }
               else
               {
                        $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK on me 
                       // sis.sexam-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once sexam.php";
                            Sexam::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.sexam set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.course_session-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once course_session.php";
                            CourseSession::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.course_session set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.school_class_course-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once school_class_course.php";
                            SchoolClassCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_class_course set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.courses_config_item-المادة دراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once courses_config_item.php";
                            CoursesConfigItem::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_config_item set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.cpc_course_plan-المادة دراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once cpc_course_plan.php";
                            CpcCoursePlan::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.cpc_course_plan set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.cpc_course_program_book-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once cpc_course_program_book.php";
                            CpcCourseProgramBook::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.cpc_course_program_book set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.class_course-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // require_once class_course.php";
                            ClassCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.class_course set course_id='$id_replace' where course_id='$id' ");
                        }
                       // sis.prof sched item-المادة الدراسية	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            ProfSchedItem::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                        }
                       // summer.summer_course-المقرر الدراسي	course_id  حقل يفلتر به-ManyToOne
                        if(!$simul)
                        {
                            // SummerCourse::updateWhere(array('course_id' =>$id_replace), "course_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_course set course_id='$id_replace' where course_id='$id' ");
                        }
 
 
                        // MFK
                       // sis.school_employee-المواد الدراسية التي يدرسها	course_mfk  
                        if(!$simul)
                        {
                            // require_once school_employee.php";
                            SchoolEmployee::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',$id_replace,')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_employee set course_mfk=REPLACE(course_mfk, ',$id,', ',$id_replace,') where course_mfk like '%,$id,%' ");
                        }
                       // sis.courses_template-المواد الدراسية	course_mfk  
                        if(!$simul)
                        {
                            // require_once courses_template.php";
                            CoursesTemplate::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',$id_replace,')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_template set course_mfk=REPLACE(course_mfk, ',$id,', ',$id_replace,') where course_mfk like '%,$id,%' ");
                        }
                       // sis.sprof-المواد الدراسية التي يدرسها	course_mfk  
                        if(!$simul)
                        {
                            // require_once sprof.php";
                            SchoolEmployee::updateWhere(array('course_mfk' =>"REPLACE(course_mfk, ',$id,', ',$id_replace,')"), "course_mfk like '%,$id,%'");
                            // $this->execQuery("update ${server_db_prefix}sis.sprof set course_mfk=REPLACE(course_mfk, ',$id,', ',$id_replace,') where course_mfk like '%,$id,%' ");
                        }
 
 
               } 
               return true;
            }    
	}

    public function getFieldGroupInfos($fgroup)
    {
        return ['name' => $fgroup, 'css' => 'pct_100'];
    }

    public function stepsAreOrdered()
    {
        return false;
    }
 
}
?>