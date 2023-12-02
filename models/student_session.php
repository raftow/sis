<?php
// ------------------------------------------------------------------------------------
// : student_session - سجلات الطلاب في الحصص الدراسية 
// alter table student_session change class_name class_name varchar(24) not null;
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php       

class StudentSession extends SisObject{      

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "student_session"; 
    public static $DB_STRUCTURE = null; 

    public static $attendance_status_on_time = 1;
    public static $attendance_status_on_late = 2;
    public static $attendance_status_absent  = 3;
    public static $attendance_status_leave   = 4;
    
    
    public function __construct(){
		parent::__construct("student_session","","sis");
        SisStudentSessionAfwStructure::initInstance($this);
	}
        
        
        
        public static function list_of_coming_status_id()
        {
            global $lang;
               return self::attendance_status(true)[$lang];
        }
        
        public static function list_of_exit_status_id()
        {
            global $lang;
               return self::attendance_status(false)[$lang];
        }
        
        public static function attendance_status($coming=true)
        {
                $arr_list_of_attendance_status = array();
                if($coming)
                {
                    $arr_list_of_attendance_status["en"][1] = "on_time";
                    $arr_list_of_attendance_status["ar"][1] = "في الموعد";
    
                    $arr_list_of_attendance_status["en"][2] = "on_late";
                    $arr_list_of_attendance_status["ar"][2] = "متأخر";
    
                    $arr_list_of_attendance_status["en"][3] = "absent";
                    $arr_list_of_attendance_status["ar"][3] = "غياب";
    
                    $arr_list_of_attendance_status["en"][4] = "leave";
                    $arr_list_of_attendance_status["ar"][4] = "إجازة";
                }
                else
                {
                    $arr_list_of_attendance_status["en"][1] = "on_time";
                    $arr_list_of_attendance_status["ar"][1] = "في الموعد";
    
                    $arr_list_of_attendance_status["en"][2] = "early_quitted";
                    $arr_list_of_attendance_status["ar"][2] = "خروج مبكر";
    
                    $arr_list_of_attendance_status["en"][3] = "absent";
                    $arr_list_of_attendance_status["ar"][3] = "غياب";
    
                    $arr_list_of_attendance_status["en"][4] = "leave";
                    $arr_list_of_attendance_status["ar"][4] = "إجازة";
                }
                
                
                return $arr_list_of_attendance_status;
        }

        public function at_of_exit_status_id()
        {
            global $lang;
            
            $this->get_ssid_from_db();

                
            
              $arr_list_of_coming_status_id = self::attendance_status();

               //     '2' => 'current_session',
              if($this->ssid==2)
              {
                  unset($arr_list_of_coming_status_id[$lang][4]);
                  unset($arr_list_of_coming_status_id[$lang][3]);
                  unset($arr_list_of_coming_status_id[$lang][2]);
                  unset($arr_list_of_coming_status_id[$lang][1]);
                  
                  
              } 
               
               //     '3' => 'closed_session',
              if($this->ssid==2)
              {
                  unset($arr_list_of_coming_status_id[$lang][4]);
                  unset($arr_list_of_coming_status_id[$lang][3]);
                  unset($arr_list_of_coming_status_id[$lang][2]);
                  unset($arr_list_of_coming_status_id[$lang][1]);
                  
                  
              }
            
                
                return $arr_list_of_coming_status_id[$lang];
        }
        
        public function at_of_coming_status_id()
        {
            global $lang;
            
            $this->get_ssid_from_db();
            
              $arr_list_of_coming_status_id = self::attendance_status();
               //     '1' => 'coming_session',
              if($this->ssid==1)
              {
                  unset($arr_list_of_coming_status_id[$lang][5]);
                  unset($arr_list_of_coming_status_id[$lang][6]);
                  unset($arr_list_of_coming_status_id[$lang][7]);
                  unset($arr_list_of_coming_status_id[$lang][8]);
                  
                  unset($arr_list_of_coming_status_id[$lang][4]);
                  
              } 
               //     '2' => 'current_session',
              if($this->ssid==2)
              {
                  unset($arr_list_of_coming_status_id[$lang][5]);
                  unset($arr_list_of_coming_status_id[$lang][6]);
                  unset($arr_list_of_coming_status_id[$lang][7]);
                  unset($arr_list_of_coming_status_id[$lang][8]);
                  
                  unset($arr_list_of_coming_status_id[$lang][3]);
                  
              } 
               
               //     '3' => 'closed_session',
              if($this->ssid==3)
              {
                  unset($arr_list_of_coming_status_id[$lang][5]);
                  unset($arr_list_of_coming_status_id[$lang][7]);
                  unset($arr_list_of_coming_status_id[$lang][8]);
                  
                  unset($arr_list_of_coming_status_id[$lang][3]);
                  unset($arr_list_of_coming_status_id[$lang][2]);
                  
              }
              
              return $arr_list_of_coming_status_id[$lang];
        }
        
        public function list_of_year()
	    {
		    $file_dir_name = dirname(__FILE__);
                
                include_once("$file_dir_name/../afw/common_date.php");
                list($hijri_year,$mm,$dd) =AfwDateHelper::currentHijriDate("hlist");
                $hijri_year = intval($hijri_year);
                
                $arr_list_of_year = array();
                
                $hijri_year_m_1 = $hijri_year-1;
                $hijri_year_p_1 = $hijri_year+1;
                $hijri_year_p_2 = $hijri_year+2;
                
                $arr_list_of_year[$hijri_year_m_1] = "$hijri_year_m_1-$hijri_year";
                $arr_list_of_year[$hijri_year] = "$hijri_year-$hijri_year_p_1";
                $arr_list_of_year[$hijri_year_p_1] = "$hijri_year_p_1-$hijri_year_p_2";
                
                return $arr_list_of_year;
	    }
        
        public function get_ssid_from_db()
        {
            if(!$this->ssid)              
            {
                //  bring it from database
                $cs = $this->getCourseSession();
                if($cs) $this->ssid = $cs->getVal("session_status_id");   
                else $this->ssid = "not-found";
            }
             
        }

        public function getCourseSession()
        {
            global $course_sess_arr;
            $school_id = $this->getVal('school_id');
            if(!$school_id) return null;
            $levels_template_id = $this->getVal('levels_template_id');
            if(!$levels_template_id) return null;
            $school_level_order = $this->getVal('school_level_order');
            if(!$school_level_order) return null;
            $level_class_order = $this->getVal('level_class_order');
            if(!$level_class_order) return null;
            $class_name = $this->getVal('class_name');
            $session_date = $this->getVal('session_date');
            $session_order = $this->getVal('session_order');

            if(!$course_sess_arr["$school_id-$levels_template_id-$school_level_order-$level_class_order-$class_name-$session_date-$session_order"])
            {
                $course_sess_arr["$school_id-$levels_template_id-$school_level_order-$level_class_order-$class_name-$session_date-$session_order"] = 
                   CourseSession::loadByMainIndex($school_id,
                        $levels_template_id,
                        $school_level_order,
                        $level_class_order,
                        $class_name,
                        $session_date,
                        $session_order);    
            }

            return $course_sess_arr["$school_id-$levels_template_id-$school_level_order-$level_class_order-$class_name-$session_date-$session_order"];

            
        }

        
        public function getComingTimeList()
        {
            $csObj = $this->getCourseSession();
            list($m,) = explode(":",$csObj->getVal("session_start_time"));
            return AfwDateHelper::getTimeInterval(intval($m));
        }

        public function getExitTimeList()
        {
            $csObj = $this->getCourseSession();
            list($m,) = explode(":",$csObj->getVal("session_end_time"));
            return AfwDateHelper::getTimeInterval(intval($m));
        }

        public function attributeIsApplicable($attribute)
        {
                /* rafik : very bad optimized
                $this->get_ssid_from_db();

                $session_started = true or (in_array($this->ssid,array(2,3)));

                if($attribute=="coming_status_id")
                {
                    return $session_started;
                }
                
                
                if($attribute=="exit_status_id")
                {
                    return $session_started; 
                }
                
                if($attribute=="coming_time")
                {
                    return $session_started;
                }
                
                if($attribute=="exit_time")
                {
                    return $session_started; 
                }

                if(($attribute=="moral_rank_id") or
                    ($attribute=="interest_rank_id") or
                    ($attribute=="level_rank_id"))  
                {
                    return $session_started; 
                }
                
                if(AfwStringHelper::stringStartsWith($attribute,"homework2_") or ($attribute=="homework2"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework2");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"homework_") or ($attribute=="homework"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"mainwork_") or ($attribute=="mainwork"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("mainwork");
                }*/
                
                
                return true;
         }
         
        public function calcCourse_session($what="value")
        {
            global $lang;
            $csObj = $this->getCourseSession();
            
            if($what=="value") $return = $csObj ? $csObj->id : 0;
            elseif($what=="decodeme")  $return = $csObj ? $csObj->getDisplay($lang) : "";
            else $return = $csObj;

            return $return;
        }
        

        public function calcSession_status_id($what="value")
        {
            global $lang;
            // if($what=="decodeme") $what="value";
            $csObj = $this->getCourseSession();
            // die("$csObj session_status_id = ".$csObj->getVal("session_status_id"));
            if($csObj)
            {
                $ssObj = $csObj->het("session_status_id");
            }
            else $ssObj = null;
            
            if($what=="value") $return = $ssObj ? $ssObj->id : 0;
            elseif($what=="decodeme")  $return = $ssObj ? $ssObj->getDisplay($lang) : "";
            else $return = $ssObj;

            // die("calcSession_status_id($what) = ".$return);

            return $return;
        }

        
        public function getDisplay($lang = 'ar')
        {
            $course = $this->showAttribute('course_id');
            $class_name = $this->showAttribute('class_name');
            $session_date = $this->showAttribute('session_date');
            $student = $this->showAttribute('student_id');

            return "$course ← $class_name ← $session_date ← $student";
        } 
        

        public function getAttributeLabel($attribute, $lang = 'ar', $short = false)
        {
            if(($attribute == "homework") or ($attribute == "homework2") or ($attribute == "mainwork"))
            {
                $courseObj = $this->hetCourse();
                if($courseObj) $return = $courseObj->getVal($attribute);
                else $return = "";
                if($return) return $return;
            }
            return $this->getAttributeTranslation($attribute, $lang, $short);
        }

        public function calcMainwork()
        {
            global $lang;
            $book = $this->het("mainwork_start_book_id");
            if(!$book) return "لم يتم تحديد الكتاب";
            $chapter_name = $book->getVal("chapter_name");
            if(!$chapter_name) $chapter_name = "فصل";
            $paragraph_name = $book->getVal("paragraph_name");            
            if(!$paragraph_name) $paragraph_name = "فقرة";

            $start_chapter = $this->het("mainwork_start_chapter_id");
            if(!$start_chapter) return "لم يتم تحديد $chapter_name البداية";
            $start_chapter_title = $start_chapter->getDisplay($lang);
            $start_paragraph_num = $this->getVal("mainwork_start_paragraph_num");
            if(!$start_paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            $end_chapter = $this->het("mainwork_end_chapter_id");
            if(!$end_chapter) return "لم يتم تحديد $chapter_name الانتهاء";
            $end_chapter_title = $end_chapter->getDisplay($lang);
            $end_paragraph_num = $this->getVal("mainwork_end_paragraph_num");
            $mainwork_start_page_num = $this->getVal("mainwork_start_page_num");
            $mainwork_end_page_num = $this->getVal("mainwork_end_page_num");
            if(!$end_paragraph_num) return "لم يتم تحديد $paragraph_name الانتهاء";
            
            if(AfwStringHelper::stringStartsWith($start_chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 
            if($end_chapter_title == $start_chapter_title) $end_chapter_title = "";
            
            return "من $chapter_name $start_chapter_title $paragraph_name $start_paragraph_num ص $mainwork_start_page_num 
                      إلى $chapter_name $end_chapter_title $paragraph_name $end_paragraph_num ص $mainwork_end_page_num ";
        }

        public function calcHomework()
        {
            global $lang;
            $book = $this->het("homework_start_book_id");
            if(!$book) return "لم يتم تحديد الكتاب";
            $chapter_name = $book->getVal("chapter_name");
            if(!$chapter_name) $chapter_name = "فصل";
            $paragraph_name = $book->getVal("paragraph_name");            
            if(!$paragraph_name) $paragraph_name = "فقرة";

            $start_chapter = $this->het("homework_start_chapter_id");
            if(!$start_chapter) return "لم يتم تحديد $chapter_name البداية";
            $start_chapter_title = $start_chapter->getDisplay($lang);
            $start_paragraph_num = $this->getVal("homework_start_paragraph_num");
            if(!$start_paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            $end_chapter = $this->het("homework_end_chapter_id");
            if(!$end_chapter) return "لم يتم تحديد $chapter_name الانتهاء";
            $end_chapter_title = $end_chapter->getDisplay($lang);
            $end_paragraph_num = $this->getVal("homework_end_paragraph_num");
            if(!$end_paragraph_num) return "لم يتم تحديد $paragraph_name الانتهاء";
            if(AfwStringHelper::stringStartsWith($start_chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 
            if($end_chapter_title == $start_chapter_title) $end_chapter_title = "";
            
            return "من $chapter_name $start_chapter_title $paragraph_name $start_paragraph_num 
                      إلى $chapter_name $end_chapter_title $paragraph_name $end_paragraph_num";
        }

        public function calcHomework2()
        {
            global $lang;
            $book = $this->het("homework2_start_book_id");
            if(!$book) return "لم يتم تحديد الكتاب";
            $chapter_name = $book->getVal("chapter_name");
            if(!$chapter_name) $chapter_name = "فصل";
            $paragraph_name = $book->getVal("paragraph_name");            
            if(!$paragraph_name) $paragraph_name = "فقرة";

            $start_chapter = $this->het("homework2_start_chapter_id");
            if(!$start_chapter) return "لم يتم تحديد $chapter_name البداية";
            $start_chapter_title = $start_chapter->getDisplay($lang);
            $start_paragraph_num = $this->getVal("homework2_start_paragraph_num");
            if(!$start_paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            $end_chapter = $this->het("homework2_end_chapter_id");
            if(!$end_chapter) return "لم يتم تحديد $chapter_name الانتهاء";
            $end_chapter_title = $end_chapter->getDisplay($lang);
            $end_paragraph_num = $this->getVal("homework2_end_paragraph_num");
            if(!$end_paragraph_num) return "لم يتم تحديد $paragraph_name الانتهاء";
            if(AfwStringHelper::stringStartsWith($start_chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 
            if($end_chapter_title == $start_chapter_title) $end_chapter_title = "";
            
            return "من $chapter_name $start_chapter_title $paragraph_name $start_paragraph_num 
                      إلى $chapter_name $end_chapter_title $paragraph_name $end_paragraph_num";
        }


        public function getFieldGroupInfos($fgroup)
        {
            
            

            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        public function stepsAreOrdered()
        {
            return false;
        }

        protected function debuggTableQueries($sql)
        {
            return false;
        } 

        public function paragraphShort($lang="ar", $attribute)
        {
            list($book_id, $paragraph_num, $chapter_id, $page_num, $prgh) = $this->getBookLocation($attribute);
            if(!$prgh) return "?!!!? [$chapter_id|$paragraph_num]";
            return AfwStringHelper::truncateArabicJomla($prgh->getVal("paragraph_text"), 52);
        }
/*
        public function getBookLocation($attribute)
        {
            $book_id = 0;
            $part_id = 0; // because sourat can start on part and finish on another //$this->getVal($attribute."_part_id");
            $chapter_id = $this->getVal($attribute."_chapter_id");
            $paragraph_num = $this->getVal($attribute."_paragraph_num");
            $prgh = CpcBookParagraph::loadByMainIndex($book_id, $part_id, $chapter_id, $paragraph_num);
            $page_num = $prgh ? $prgh->getVal("page_num") : 0;
            $book_id = $prgh ? $prgh->getVal("book_id") : 0;

            return array($book_id, $paragraph_num, $chapter_id, $page_num, $prgh);
        }

        public function getBookParams($attribute)
        {
            $attribute_start = str_replace("_end","_start",$attribute);
            $attribute_end = str_replace("_start","_end",$attribute);
            
            if($attribute==$attribute_start) $mode="interval-start";
            if($attribute==$attribute_end) $mode="interval-end";
            // echo("attribute_start=$attribute_start attribute_end=$attribute_end attribute=$attribute mode=$mode");
            list($book_id, $paragraph_num, $chapter_id, $page_num, ) = $this->getBookLocation($attribute_start);
            list($book_id, $paragraph_num_to, $chapter_id, $page_num_to, ) = $this->getBookLocation($attribute_end);
            return ['book_id'=>$book_id, 'paragraph_num'=>$paragraph_num, 'paragraph_num_to'=>$paragraph_num_to, 'chapter_id'=>$chapter_id, 'page'=>$page_num, 'mode_input'=>$mode];
        }*/

        public function paragraphShortFromTo($lang="ar", $attribute)
        {
            return CpcBook::paragraphShortFromTo($this, $attribute);
        }

        public function getBookLocation($attribute)
        {
            return CpcBook::getBookLocation($this, $attribute);
        }

        public function getBookParams($attribute)
        {
            return CpcBook::getBookParams($this, $attribute, true);
        }


        public function calcStudent_file($what="value")
        {
            $tempObj = StudentFile::loadByMainIndex(
                $this->getVal('student_id'),
                $this->getVal('school_id'),
                $this->getVal('year'),
                $this->getVal('levels_template_id'),
                $this->getVal('school_level_order'),
                $this->getVal('level_class_order')
            );

            if ($tempObj) {
                if($what=="object") return $tempObj;
                else return $tempObj->id;
            } else {
                return null;
            }
        }

        public function calcStudent_file_course($what="value")
        {
            $tempObj = StudentFileCourse::loadByMainIndex($this->getVal('student_id'),
                $this->getVal('school_id'),
                $this->getVal('year'),
                $this->getVal('levels_template_id'),
                $this->getVal('school_level_order'),
                $this->getVal('level_class_order'),
                $this->getVal('course_id')
            );

            if ($tempObj) {
                if($what=="object") return $tempObj;
                else return $tempObj->id;
            } else {
                return null;
            }
        }


        public function list_of_mainwork_end_page_num()
        {
            $part_id =    $this->getVal("mainwork_endt_part_id");
            $chapter_id = $this->getVal("mainwork_end_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_mainwork_start_page_num()
        {
            $part_id =    $this->getVal("mainwork_start_part_id");
            $chapter_id = $this->getVal("mainwork_start_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework_end_page_num()
        {
            $part_id =    $this->getVal("homework_end_part_id");
            $chapter_id = $this->getVal("homework_end_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework_start_page_num()
        {
            $part_id =    $this->getVal("homework_start_part_id");
            $chapter_id = $this->getVal("homework_start_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework2_end_page_num()
        {
            $part_id =    $this->getVal("homework2_end_part_id");
            $chapter_id = $this->getVal("homework2_end_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework2_start_page_num()
        {
            $part_id =    $this->getVal("homework2_start_part_id");
            $chapter_id = $this->getVal("homework2_start_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }

        public function calcMainwork_start_paragraph_id($what="value")
        {
            return CpcBook::calcAttribute_paragraph_id($this, "mainwork_start", $what, 0, "mainwork_start_book_id");
        }

        protected function getPublicMethods()
        {
 
            $pbms = array();

            $color = "blue";
            $title_ar = "غلق الحصة"; 
            $methodName = "closeEntireSession";
            $pbms[self::hzmEncode($methodName)] = array("METHOD"=>$methodName,
                                                        "COLOR"=>$color, 
                                                        "LABEL_AR"=>$title_ar, 
                                                        'STEP' => 6,
                                                        "ADMIN-ONLY"=>false, "BF-ID"=>"104687");
 
            $color = "green";
            $title_ar = "تحديث الواجبات"; 
            $methodName = "updateMyStudentWorkFromStudentFileCourse";
            $pbms[self::hzmEncode($methodName)] = array("METHOD"=>$methodName,
                                                        "COLOR"=>$color, 
                                                        "LABEL_AR"=>$title_ar, 
                                                        "ADMIN-ONLY"=>true, "BF-ID"=>"");
 
 

                                                      
 
            return $pbms;
        }


        public function updateMyStudentWorkFromStudentFileCourse($lang="ar")
        {
            $info_arr = [];
            $err_arr = [];
            $war_arr = [];

            $sfcObj = $this->calcStudent_file_course($what="object");
            if($sfcObj)
            {
                if($this->getVal('mainwork_rank_id')>1)
                {
                    $war_arr[] = "تم تقييم حفظ الجديد فلا يمكن تحديثه";
                }
                else
                {
                    $info_arr[] = "تم تحديث حفظ الجديد";
                    $this->set('mainwork_start_part_id', $sfcObj->getVal('mainwork_start_part_id'));
                    $this->set('mainwork_start_chapter_id' , $sfcObj->getVal('mainwork_start_chapter_id'));
                    $this->set('mainwork_start_page_num' , $sfcObj->getVal('mainwork_start_page_num'));
                    $this->set('mainwork_end_part_id' , $sfcObj->getVal('mainwork_end_part_id'));
                    $this->set('mainwork_end_chapter_id' , $sfcObj->getVal('mainwork_end_chapter_id'));
                    $this->set('mainwork_end_page_num' , $sfcObj->getVal('mainwork_end_page_num'));
                    $this->set('mainwork_start_paragraph_num' , $sfcObj->getVal('mainwork_start_paragraph_num'));
                    $this->set('mainwork_end_paragraph_num' , $sfcObj->getVal('mainwork_end_paragraph_num'));
                }
                
                if($this->getVal('homework_rank_id')>1)
                {
                    $war_arr[] = "تم تقييم المراجعة الكبرى فلا يمكن تحديثها";
                }
                else
                {
                    $info_arr[] = "تم تحديث المراجعة الكبرى";
                    $this->set('homework_start_part_id' , $sfcObj->getVal('homework_start_part_id'));
                    $this->set('homework_start_chapter_id' , $sfcObj->getVal('homework_start_chapter_id'));
                    $this->set('homework_start_page_num' , $sfcObj->getVal('homework_start_page_num'));
                    $this->set('homework_end_part_id' , $sfcObj->getVal('homework_end_part_id'));
                    $this->set('homework_end_chapter_id' , $sfcObj->getVal('homework_end_chapter_id'));
                    $this->set('homework_end_page_num' , $sfcObj->getVal('homework_end_page_num'));
                    $this->set('homework_start_paragraph_num' , $sfcObj->getVal('homework_start_paragraph_num'));
                    $this->set('homework_end_paragraph_num' , $sfcObj->getVal('homework_end_paragraph_num'));
                }
                
                if($this->getVal('homework2_rank_id')>1)
                {
                    $war_arr[] = "تم تقييم المراجعة الصغرى فلا يمكن تحديثها";
                }
                else
                {
                    $info_arr[] = "تم تحديث المراجعة الصغرى";
                    $this->set('homework2_start_part_id' , $sfcObj->getVal('homework2_start_part_id'));
                    $this->set('homework2_start_chapter_id' , $sfcObj->getVal('homework2_start_chapter_id'));
                    $this->set('homework2_start_page_num' , $sfcObj->getVal('homework2_start_page_num'));
                    $this->set('homework2_end_part_id' , $sfcObj->getVal('homework2_end_part_id'));
                    $this->set('homework2_end_chapter_id' , $sfcObj->getVal('homework2_end_chapter_id'));
                    $this->set('homework2_end_page_num' , $sfcObj->getVal('homework2_end_page_num'));
                    $this->set('homework2_start_paragraph_num' , $sfcObj->getVal('homework2_start_paragraph_num'));
                    $this->set('homework2_end_paragraph_num' , $sfcObj->getVal('homework2_end_paragraph_num'));                    
                }
                

                $this->commit();

                
            }
            else
            {
                $err_arr[] = "لا يوجد ملف متابعة انجاز هذا الطالب على هذه المادة";
            }

            return self::pbm_result($err_arr, $info_arr, $war_arr, " - ");
        }

        public function getDefaultStep()
        {
            return 4; // evaluation
        }

        public function isReady()
        {
            $coming_status_id = $this->getVal("coming_status_id");

            if($coming_status_id==self::$attendance_status_absent) return true;
            if($coming_status_id==self::$attendance_status_leave) return true;

            $courseObj = $this->hetCourse();
            if(!$courseObj) return false;
             
            $mainwork_rank_id  = ((!$courseObj->getVal("mainwork")) or $this->getVal("mainwork_rank_id"));
            $homework_rank_id  = ((!$courseObj->getVal("homework")) or $this->getVal("homework_rank_id"));
            $homework2_rank_id = ((!$courseObj->getVal("homework2")) or $this->getVal("homework2_rank_id"));
            return ($mainwork_rank_id and $homework_rank_id and $homework2_rank_id);
            
        }

        public function saveWorksAndGotoNext($lang)
        {
            // @todo
        }

        
        public function closeEntireSession($lang="ar")
        {
            $csObj = $this->getCourseSession();

            if($csObj) return $csObj->closeSession($lang);
            else return ["No CourseSession to close", "", ""];
        }

        public function closeSession($lang="ar")
        {
            $err_arr = [];
            $inf_arr = [];
            $war_arr = [];
            $tech_arr = [];

            $ready = $this->isReady();
            if($ready)
            {
                    list($err,$inf,$war,$tech) = $this->saveWorksAndGotoNext($lang);
                    if($err) $err_arr[] = $err;
                    if($inf) $inf_arr[] = $inf;
                    if($war) $war_arr[] = $war;
                    if($tech) $tech_arr[] = $tech;
            }
            else
            {
                $err_arr[] = "لا يمكن غلق حصة الطالب وهي غير مكتملة التحديث";
            }
            

            return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);        
        }

        public function getParentObject()
        {
            return $this->calcCourse_session("object");
        }

        public function qeditHeaderFooterEmbedded()
        {
            return true;
        }

        protected function considerEmpty()
        {
            return (!$this->getVal("coming_status_id") or !$this->getVal("mainwork_rank_id"));
        }
        
        
}
?>