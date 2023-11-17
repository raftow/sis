<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table course_sched_item : course_sched_item - عناصر الجدول الدراسي 
// ------------------------------------------------------------------------------------
 
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CourseSchedItem extends SisObject{

	public static $DATABASE		= ""; 
      public static $MODULE		= "sis"; 
      public static $TABLE			= "course_sched_item"; 
      public static $DB_STRUCTURE = null; 
       
       
      public function __construct(){
		parent::__construct("course_sched_item","id","sis");
              SisCourseSchedItemAfwStructure::initInstance($this);
              
	}

      public static function isEmptyList($courseSchedList)
      {
            foreach($courseSchedList as $courseSchedItem)
            {
                  if($courseSchedItem->getVal("prof_id") and $courseSchedItem->getVal("course_id"))
                  {
                        return false;
                  }
            }

            return true;
      }

      public static function loadByMainIndex($school_year_id, $level_class_id, $class_name, $wday_id, $session_order,$create_obj_if_not_found=false)
      {
            $obj = new CourseSchedItem();
            $obj->select("school_year_id",$school_year_id);
            $obj->select("level_class_id",$level_class_id);
            $obj->select("class_name",$class_name);
            $obj->select("wday_id",$wday_id);
            $obj->select("session_order",$session_order);

            if($obj->load())
            {
                  if($create_obj_if_not_found) $obj->activate();
                  return $obj;
            }
            elseif($create_obj_if_not_found)
            {
                  $obj->set("school_year_id",$school_year_id);
                  $obj->set("level_class_id",$level_class_id);
                  $obj->set("class_name",$class_name);
                  $obj->set("wday_id",$wday_id);
                  $obj->set("session_order",$session_order);

                  $obj->insertNew();
                  if(!$obj->id) return null; // means beforeInsert rejected insert operation
                  $obj->is_new = true;
                  return $obj;
            }
            else return null;
      
      }
      
      public function getLongDisplay($lang="ar")
      {
            list($data,$link) = $this->displayAttribute("level_class_id");
            list($data_class_name,$link_class_name) = $this->displayAttribute("class_name");
            list($data_wday_id,$linkwday_id) = $this->displayAttribute("wday_id");
            list($session_order,$link3) = $this->displayAttribute("session_order");
            return "حلقة ".$data." ".$data_class_name." ".$data_wday_id." حصة ".$session_order;
      }

      public function getDisplay($lang="ar")
      {
            list($data_class_name,$link_class_name) = $this->displayAttribute("class_name");
            list($data_wday_id,$linkwday_id) = $this->displayAttribute("wday_id");
            list($session_order,$link3) = $this->displayAttribute("session_order");
            return "حلقة ".$data_class_name."-".$data_wday_id."-حصة ".$session_order;
      }
      
      public function getSchoolClass() 
      {
            global $file_dir_name;
            // // require_once school_class.php");
            $sc_obj = new SchoolClass();
            
            $sc_obj->select("school_year_id",$this->getVal("school_year_id"));
            $sc_obj->select("level_class_id",$this->getVal("level_class_id"));
            $sc_obj->select("class_name",$this->getVal("class_name"));
            
            $sc_obj->load();
            if($sc_obj->getId()>0) return $sc_obj;
            else return null; 
      }
      
      
      public function getSchoolClassCourse() // 
      {
            global $file_dir_name;
            // // require_once school_class_course.php");
            $scc_obj = new SchoolClassCourse();
            
            $scc_obj->select("school_year_id",$this->getVal("school_year_id"));
            $scc_obj->select("level_class_id",$this->getVal("level_class_id"));
            $scc_obj->select("class_name",$this->getVal("class_name"));
            $scc_obj->select("course_id",$this->getVal("course_id"));
            
            $scc_obj->load();
            if($scc_obj->getId()>0) return $scc_obj;
            else return null; 
      }

      public function calcScc_prof_id($what="value")
      {
            global $lang;
            return self::decode_result($this->getProf(),$what,$lang);
      }

      public function calcSchool_class_id($what="value")
      {
            global $lang;
            return self::decode_result($this->getSchoolClass(),$what,$lang);
      }
      
      private function getProf() 
      {
            $scc_obj =& $this->getSchoolClassCourse();
            if($scc_obj and ($scc_obj->getVal("prof_id")>0)) return $scc_obj->get("prof_id");
            else return null;//$this->getEmptyObject("prof_id"); 
      }
      
      protected function getSpecificDataErrors($lang="ar",$show_val=true,$step="all")
      {
            $sp_errors = array();
            
            $school_year_id = $this->getVal("school_year_id");
            if(!$school_year_id) {
                  $sp_errors["school_year_id"] = "السنة الدراسية  غير معروفة";
                  return $sp_errors;
            }
            
            $course_id = $this->getVal("course_id");
            if(!$course_id) {
                  $sp_errors["course_id"] = "المادة  الدراسية  غير معروفة";
                  return $sp_errors;
            }
            
            $prof = $this->getProf();
            if($prof)
            {
            
            if(!$prof->getId()) $sp_errors["prof_id"] = "المدرس غير معروف";
            else
            {
                        $sched_id = $this->getId();
                        $wd_id = $this->getVal("wday_id");
                        $course_id = $this->getVal("course_id");
                        $wd_name = $this->get("wday_id")->getDisplay($lang);
                        $sess_ord = $this->getVal("session_order");    
                        $scc_list = $prof->calcSchoolClassCourseList("object", $school_year_id, $wd_id, $sess_ord);
                        $prof_wd_list = $prof->get("wday_mfk");
                        
                        if(!$prof_wd_list[$wd_id]) $sp_errors["wday_id"] = "المدرس غير متوفر في  يوم ". $wd_name;
                        
                        /* if(count($scc_list)==0) $sp_errors["prof_id"] = " خطأ في برمجة جدول المدرس  عنصر $sched_id";
                        else */
                        
                        if(count($scc_list)>1) {
                              $sp_errors["prof_id"] = "يوجد تزاحم في جدول المدرس  ليوم ". $wd_name;
                              $sp_errors["prof_id"] .= " حصة رقم $sess_ord : جدول المدرس لهذا اليوم: <br>\n ";
                              foreach($scc_list as $scc_item)
                              {
                                    $sp_errors["prof_id"] .= $scc_item->getDisplay($lang) . "<br>\n";
                              }
                              
                              $syObj =& $this->get("school_year_id");
                              $school =& $syObj->get("school_id");
                              
                              
                              /*
                              
                              to suggest prof we should be sure that he is free for all the course sched items (for this school class and this course) 
                              not only for the current (this)
                              
                              $cand_profs = "المدرسين المتفرغين المشرحين لهذه الحصة : ";
                              
                              include_once("$file_dir_name/../sis/school_employee.php");
                              $emplObj = new SchoolEmployee();
                              $emplObj->select("school_id",$school->getId());
                              $emplObj->select("active",'Y');
                              $emplObj->where("wday_mfk like '%,$wd_id,%'");
                              $emplObj->where("school_job_mfk like '%,3,%'");
                              $emplObj->where("course_mfk like '%,$course_id,%'");
                              $empl_list = $emplObj->loadMany();
                              foreach($empl_list as $empl_id => $empl_item)
                              {
                              $empl_scc_list = $empl_item->calcSchoolClassCourseList("object", $school_year_id, $wd_id, $sess_ord);
                              if(count($empl_scc_list)==0) {
                                          $cand_profs .= $empl_item->getDisplay(). "، ";
                              }
                                    
                              
                              }
                              $sp_errors["prof_id"] .= $cand_profs;*/
                        }  
                        else{
                              // only one but use boucle
                              foreach($scc_list as $scc_id => $scc_item)
                              {
                                    $scc_errors = $scc_item->getDataErrors();
                                    foreach($scc_errors as $scc_err) $sp_errors["course_id"]  = $scc_err;
                              }
                              
                              // suggest still requested courses
                              $sc_obj =& $this->getSchoolClass();
                              if((count($sp_errors)>0) and ($sc_obj))
                              {
                              $scc_still_list = $sc_obj->getStillRequestedCourses();
                              foreach($scc_still_list as $scc_still_id => $scc_still_item)
                              {
                                    $sp_errors["course_id"]  .=  " \n لا يزال مطلوب ". $scc_still_item->get("course_id")->getDisplay();
                              }
                              
                              }
                        }  
            }    
                  
            }
            else $sp_errors["prof_id"] = "المدرس غير محدد"; 
            
            return $sp_errors;
      }
      
      
      

      /*  
      public function at_of_course_id()
      {
            global $objme;
            $file_dir_name = dirname(__FILE__);
            $sy_id = $this->getVal("school_year_id");
            $sy = $this->hetSy();
            if(!$sy)
            {
                  $my_school = null;
            }
            else
            {
                  
                  $my_school = $sy->getSchool();
                  
            }
            if($my_school) $cct_id = $my_school->getVal("courses_config_template_id");
            else $cct_id = 0; 
            $level_class_id = $this->getVal("level_class_id");
            if(!$level_class_id) $level_class_id = 0;
            $db = $this->getDatabase();
            
            
            include_once("$file_dir_name/../sis/course.php");
            
            $crs = new Course();
            $crs->where("");
            $at = $crs->loadMany();
            // echo(count($at)."<br>\n"); 
            // die(var_export($at,true));
            
            return $at;
      }*/
      
      public static function list_of_course_id()
      {
            global $objme;
            $file_dir_name = dirname(__FILE__);
            if($objme->contextSchoolId)
            {
                  include_once("$file_dir_name/../sis/school.php");
                  $my_school = new School();
                  $my_school->load($objme->contextSchoolId);
            }
            else return array();

            $cct_id = $my_school->getVal("courses_config_template_id");

            if(!$cct_id) return array(); 
            
            include_once("$file_dir_name/../sis/course.php");
            
            $crs = new Course();
            $crs->where("id in (select cci.course_id from c0sis.courses_config_item cci where cci.courses_config_template_id = $cct_id and cci.session_nb > 0)");
            $at = $crs->loadMany();
            // echo(count($at)."<br>\n"); 
            // die(var_export($at,true));
            
            return $at;
      }

      public function getPrayerTimeList()
      {
            return AfwDateHelper::getPrayerTimeList();
      }

      public function getAfterPrayerTimeList()
      {
            return AfwDateHelper::getAfterPrayerTimeList();
      }
      

      public function updateProfAndBooks($commit=true)
      {
            // update The prof
            $profObj = $this->getProf();
            if($profObj) $this->set("prof_id",$profObj->id);            
            // update Books
            $courseObj = $this->het("course_id");
            if($courseObj)
            {
                  $this->set("mainwork_book_id", $courseObj->getVal("mainwork_book_id"));
                  $this->set("homework_book_id", $courseObj->getVal("homework_book_id"));
                  $this->set("homework2_book_id", $courseObj->getVal("homework2_book_id"));
            }
            
            if($commit) $this->commit();

            return $this->getVal("prof_id");
      }

      public function beforeMaj($id, $fields_updated) 
      {
            
            if($fields_updated["course_id"])
            {

                  $this->updateProfAndBooks($commit=false);

                  $courses_config_template_id = $this->calc("courses_config_template_id");
                  $course_id = $this->getVal("course_id");
                  $level_class_id = $this->getVal("level_class_id");

                  $obj = CoursesConfigItem::loadByCourseTemplate($courses_config_template_id,$course_id,$level_class_id);

                  if($obj)
                  {
                        // The books
                        $this->set("mainwork_book_id",$obj->getVal("mainwork_book_id"));
                        $this->set("homework_book_id",$obj->getVal("homework_book_id"));
                        $this->set("homework2_book_id",$obj->getVal("homework2_book_id"));
                  }
                  
                  
                  
            }
            
            return true;
      }
        
        
        
}
