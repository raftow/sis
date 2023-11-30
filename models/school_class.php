<?php
// ------------------------------------------------------------------------------------
// 27/1/2023
// ALTER TABLE `school_class` CHANGE `room_id` `room_id` INT(11) NOT NULL DEFAULT '0'; 
// alter table c0sis.school_class add study_program_id INT NULL;

$file_dir_name = dirname(__FILE__);

// old include of afw.php       

class SchoolClass extends SisObject
{

    public static $MY_ATABLE_ID = 13333;
    // إحصائيات حول الحلقات 
    public static $BF_STATS_SCHOOL_CLASS = 101640;
    // إدارة الحلقات 
    public static $BF_QEDIT_SCHOOL_CLASS = 101635;
    // إنشاء صف مدرسي 
    public static $BF_EDIT_SCHOOL_CLASS = 101634;
    // الاستعلام عن صف مدرسي 
    public static $BF_QSEARCH_SCHOOL_CLASS = 101639;
    // البحث في الحلقات 
    public static $BF_SEARCH_SCHOOL_CLASS = 101638;
    // عرض تفاصيل صف مدرسي 
    public static $BF_DISPLAY_SCHOOL_CLASS = 101637;
    // مسح صف مدرسي 
    public static $BF_DELETE_SCHOOL_CLASS = 101636;


    // إدارة الملفات السنوية لطالب(ة) بوحدة دراسية 
    public static $BF_QEDIT_STUDENT_FILE = 101999;

    public static $DATABASE        = "";
    public static $MODULE            = "sis";
    public static $TABLE            = "school_class";
    public static $DB_STRUCTURE = null; 


    public $currentProf = null;
    public $mode_minibox = 'Last';

    public function __construct()
    {
        parent::__construct("school_class", "id", "sis");
        SisSchoolClassAfwStructure::initInstance($this);
    }

    public static function loadById($id)
    {
        $obj = new SchoolClass();
        if($obj->load($id))
        {
            return $obj;
        }
        else return null;
    }

    public static function loadByRoomId($school_year_id, $room_id)
    {
        $obj = new SchoolClass();
        $obj->select("school_year_id", $school_year_id);
        $obj->select("room_id", $room_id);
        if ($obj->load()) return $obj;
        else return null;
        
    }

    public static function loadByMainIndex($school_year_id, $level_class_id, $class_name, $create_obj_if_not_found = false)
    {
        $obj = new SchoolClass();
        $obj->select("school_year_id", $school_year_id);
        $obj->select("level_class_id", $level_class_id);
        $obj->select("class_name", $class_name);
        if ($obj->load()) {
            if ($create_obj_if_not_found) $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("school_year_id", $school_year_id);
            $obj->set("level_class_id", $level_class_id);
            $obj->set("class_name", $class_name);
            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public function getPlacesInfo($optimize=true)
    {
        global $stdn_sim_count;
        if(!$stdn_sim_count) $stdn_sim_count = [];
        if(!$stdn_sim_count[$this->id]) $stdn_sim_count[$this->id] = 0;
        if ($this->getVal("room_id") > 0) 
        {
            $room_capacity = intval($this->get("room_id")->getVal("capacity"));
        }
        else
        {
            // take default room_capacity of school
            // @todo 
            $room_capacity = AfwSession::config("room_default_capacity",12);;
        }    

        if($room_capacity > 0)
        {
            if($optimize and $stdn_sim_count[$this->id]) $stdn_count = $stdn_sim_count[$this->id];
            else $stdn_count = intval($this->calc("stdn_nb"));
            //die("stdn_count = $stdn_count");
            
            $needed_stdn = $room_capacity - $stdn_count;
            $room_comment = "$room_capacity مقعد ناقص $stdn_count طالب مسجل = ";
            if ($needed_stdn <= 0)
            {
                $needed_stdn = 0;
            }
            else
            {
                if(!$stdn_sim_count[$this->id]) $stdn_sim_count[$this->id] = 0;
                $stdn_sim_count[$this->id]++;
                $room_comment .= " $needed_stdn مقاعد متوفرة";
            }
                
        } 
        else 
        {
            $needed_stdn = 0;
            $room_comment = " الرجاء تحديد الطاقة الاستعابية للقاعة ";
        }

        return array($needed_stdn, $room_comment, $room_capacity, $stdn_count);
    }

    public function getDisplay($lang = 'ar')
    {
        //return "aaa";
        // list($data, $link) = $this->displayAttribute("level_class_id");
        list($data2, $link2) = $this->displayAttribute("class_name");
        // die("data2 = $data2 link2 = $link2");
        // list($data3, $link3) = $this->displayAttribute("school_year_id");

        // list($needed_stdn, $room_comment) = $this->getPlacesInfo();

        return $this->translate("schoolclass.single")." ".$data2; // . " - " . $room_comment;
    }

    public function getDropDownDisplay($lang = 'ar')
    {
        list($data0, $link) = $this->displayAttribute("level_class_id.school_level_id");
        list($data1, $link) = $this->displayAttribute("level_class_id");
        list($data2, $link2) = $this->displayAttribute("class_name");

        return $data0 . " ← " . $data1 . " ← " . $data2; 
    }

    public function getSchoolScope()
    {
        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");

        global $file_dir_name;
        // // require_once school_scope.php");
        $ss = new SchoolScope();

        $ss->select("school_year_id", $school_year_id);
        $ss->select("level_class_id", $level_class_id);

        if ($ss->load()) {
            return $ss;
        } else {
            if ($level_class_id > 0) {
                $lvlClass = $this->get("level_class_id");
                $ss->set("school_year_id", $school_year_id);
                $ss->set("school_level_id", $lvlClass->getVal("school_level_id"));
                $ss->set("level_class_id", $level_class_id);
                $ss->insert();
            }
            return $ss;
        }
    }


    public function getLastFinishedCourseSession($session_date=null, $session_date_max=null, $session_time_max=null)    
    {
        $status_arr = [SessionStatus::$closed_session,SessionStatus::$missed_session];
        return $this->getLastCourseSessionWithStatusIn($status_arr, $session_date, $session_date_max, $session_time_max);
    }

    public function getCurrentCourseSession($session_date=null, $session_date_max=null, $session_time_max=null)    
    {
        $status_arr = [SessionStatus::$current_session];
        return $this->getLastCourseSessionWithStatusIn($status_arr, $session_date, $session_date_max, $session_time_max);
    }

    public function getStdByCourseSession($session_date=null, $session_date_max=null, $session_time_max=null)    
    {
        $status_arr = [SessionStatus::$standby_session];
        return $this->getLastCourseSessionWithStatusIn($status_arr, $session_date, $session_date_max, $session_time_max);
    }

    public function getLastCourseSessionWithStatusIn($status_arr, $session_date=null, $session_date_max=null, $session_time_max=null)
    {
        if(!$session_date_max) $session_date_max = date("Y-m-d");
        if(!$session_time_max) $session_time_max = date("H:i");
        $school_id = $this->calc("school_id",false,"value");
        $levels_template_id = $this->calc("levels_template_id",false,"value");
        $school_level_order = $this->calc("school_level_order",false,"value");
        $level_class_order = $this->calc("level_class_order",false,"value");

        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        $year = $this->calc("year",false,"value");

        $cssObj = new CourseSession();
        $cssObj->select("school_id",$school_id);
        $cssObj->select("levels_template_id",$levels_template_id);
        $cssObj->select("school_level_order",$school_level_order);
        $cssObj->select("level_class_order",$level_class_order);
        $cssObj->select("class_name",$class_name);
        if($session_date) $cssObj->select("session_date",$session_date);
        if($this->currentProf)
        {
            $cssObj->select("prof_id",$this->currentProf->id);
        }
        if($session_date_max and $session_time_max)
        {
            $cssObj->where("session_date < '$session_date_max' or (session_date = '$session_date_max' and session_start_time < '$session_time_max')");
        }
        $cssObj->selectIn("session_status_id",$status_arr);

        if($cssObj->load('','','session_date desc, session_order desc'))
        {
            return $cssObj;
        }


        return null;
        
    }

    /*
    public function getCourseSessions($school_id = 0, $offsetdays = 0)
    {

        if (!$school_id) $school_id = $this->getVal("school_id");

        if (!$school_id) return null;
        $school = $this->get("school_id");

        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        $group_num = $school->getVal("group_num");
        if (!$school) return null;

        $currSYear = $this->get("school_year_id");
        if ((!$currSYear) or (!$currSYear->getId())) return null;
        $school_year_id = $currSYear->getId();
        $year = $currSYear->getVal("year");



        global $file_dir_name;
        // // require_once course_session.php");
        include_once("$file_dir_name/../afw/common_date.php");

        $cur_hdate = AfwDateHelper::currentHijriDate();

        if ($offsetdays) $cur_hdate = AfwDateHelper::shiftHijriDate($cur_hdate, $offsetdays);


        $c_ss = new CourseSession();
        //$db = $this->getDatabase(); 
        $c_ss->where("group_num = $group_num and school_id = $school_id and year = '$year' and level_class_id = $level_class_id and class_name = '$class_name' and session_hdate = '$cur_hdate'");

        $scc_list = $c_ss->loadMany("", "session_order");

        return $scc_list;
    }
    */

    public function getWeekScheduledSessionsNb()
    {
        $crsp_list = &$this->get("schoolClassCourseList");

        $nb = 0;

        foreach ($crsp_list as $crsp_id => $crsp_item) {
            $nb += $crsp_item->calc("scheds_nb");
        }

        return $nb;
    }

    public function getWeekRequestedSessionsNb()
    {
        $crsp_list = &$this->get("schoolClassCourseList");

        $nb = 0;

        foreach ($crsp_list as $crsp_id => $crsp_item) {
            $nb += $crsp_item->calc("week_sess_nb");
        }

        return $nb;
    }

    public function getScheduleErrorsNb()
    {
        $crsp_list = &$this->get("schoolClassCourseList");

        $nb = 0;

        foreach ($crsp_list as $crsp_id => $crsp_item) {
            if (!$crsp_item->isOk()) $nb += 1;
        }

        return $nb;
    }


    public function getFormuleResult($attribute, $what='value')
    {
        // global $me, $URL_RACINE_SITE;    

        switch ($attribute) {
            case "stdn_nb":                
                return $this->getRelation("stdn")->count();
                break;

            case "free_place_nb":
                list($needed_stdn, $room_comment) = $this->getPlacesInfo();
                return $needed_stdn;
                break;

            case "crsp_nb":
                return $this->getRelation("schoolClassCourseList")->count();
                break;

            case "errors_sched_nb":
                return $this->getScheduleErrorsNb();
                break;

            case "ws_sched_nb":
                return $this->getWeekScheduledSessionsNb();
                break;

            case "ws_req_nb":
                return $this->getWeekRequestedSessionsNb();
                break;

            case "attendanceList":
                return  $this->getCourseSessions();
                break;


            case "sss2_mfk":
                return  $this->getCourseSessions(0, 1);
                break;

            case "scope_id":
                return  $this->getSchoolScope();
                break;
        }

        return $this->calcFormuleResult($attribute, $what);
    }

    public function getMyCourseSchedItems($onlyCount=false)
    {
        $obj = new CourseSchedItem();
        $obj->select("school_year_id",$this->getVal("school_year_id"));
        $obj->select("level_class_id",$this->getVal("level_class_id"));
        $obj->select("class_name",$this->getVal("class_name"));
        if($onlyCount) return $obj->count();
        else return $obj->loadMany();
        
    }

    public function updateMyProfsAndBooks($lang = "ar")
    {
        $csiList = $this->getMyCourseSchedItems($onlyCount=false);
        $nb=0;
        foreach($csiList as $csiItem)
        {
            if($csiItem->updateProfAndBooks()>0) $nb++;
        }

        return ["", "$nb course-prof(s) has been updated"];
    }

    public function resetWeekProgram($lang = "ar")
    {
        return self::genereWeekProgram($lang = "ar", $reset=true);
    }

    public function genereWeekProgramEmpty($lang = "ar")
    {
        if($this->getMyCourseSchedItems($onlyCount=true)>0)
        {
            return array("لا يمكن توليد خطة دراسية أسبوعية فارغة لوجود خطة سابقة", "");
        }
        return self::genereWeekProgram($lang = "ar", $reset=false);
    }

    public function genereWeekProgram($lang = "ar", $reset=false)
    {
        $me = AfwSession::getUserIdActing();

        $ss = &$this->getSchoolScope();

        if (!$ss)  return array("مخطط مجال عمل المنشأة غير محدد لهذا الصف", "");

        if (!$ss->getVal("sdepartment_id")) return array("الإدارة/القسم لهذه الحلقة  غير محددة. رجاء مراجعة مخطط مجال عمل المنشأة وتحديد القسم لكل صف", "");

        $week_template_id = $ss->get("sdepartment_id")->getVal("week_template_id");

        if (!$week_template_id) return array("النموذج الاسبوعي  لهذا القسم/هذه الإدارة غير محدد", "");

        $sc_id = $this->getId();

        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        $db = $this->getDatabase();

        $work_days_list = trim(trim($this->getVal("wdays_mfk")),",");
        if (!$work_days_list) return array("الرجاء تحديد أيام العمل في الاسبوع", "");
        $start_time = $this->getVal("start_time");
        if(!$start_time) $start_time = "00:00";
        $end_time = $this->getVal("end_time");
        if(!$end_time) $end_time = "23:59";
        if($end_time == "00:00") $end_time = "23:59";

        if($reset)
        {
            $sqlDelete = "delete from $db.course_sched_item 
                          where school_year_id = $school_year_id 
                            and level_class_id = $level_class_id 
                            and class_name = _utf8'$class_name'";


            list($result, $row_count, $deleted_row_count) = self::executeQuery($sqlDelete);                            
        }
        else
        {
            $sqlDelete = "delete from $db.course_sched_item 
                          where school_year_id = $school_year_id 
                            and level_class_id = $level_class_id 
                            and class_name = _utf8'$class_name'
                            and course_id = 0
                            and prof_id = 0";


            list($result, $row_count, $deleted_row_count) = self::executeQuery($sqlDelete);
        }

        $sql = "insert into $db.course_sched_item
(`school_year_id`,`level_class_id`, `class_name`,`wday_id`,`session_order`,            
 `session_start_time`,`session_end_time`,`course_id`, 
 active, version, created_by, created_at, updated_by, updated_at) 
select $school_year_id, 
       $level_class_id,     
       _utf8'$class_name', 
       wd.id,
dti.session_order,dti.session_start_time,dti.session_end_time, 
0, 'Y', 0, $me, now(), $me, now() 
from $db.day_template_item dti
    inner join $db.wday wd
    inner join $db.week_template wt 
    left outer join $db.course_sched_item csi 
            on csi.school_year_id = $school_year_id 
            and csi.level_class_id = $level_class_id 
            and csi.class_name = _utf8'$class_name' 
            and csi.wday_id = wd.id 
            and csi.session_order = dti.session_order  
where wt.id = $week_template_id
  and wd.id in ($work_days_list)
  and dti.session_start_time between '$start_time' and '$end_time'
  and dti.session_end_time between '$start_time' and '$end_time'
  and ((day_template_id = wt.day1_template_id and wd.id = 1) or
       (day_template_id = wt.day2_template_id and wd.id = 2) or
       (day_template_id = wt.day3_template_id and wd.id = 3) or
       (day_template_id = wt.day4_template_id and wd.id = 4) or
       (day_template_id = wt.day5_template_id and wd.id = 5) or
       (day_template_id = wt.day6_template_id and wd.id = 6) or
       (day_template_id = wt.day7_template_id and wd.id = 7)
       )
  and csi.id is null";


        list($result, $row_count, $affected_row_count) = self::executeQuery($sql);
        $infos = "تم انشاء $affected_row_count سجل من عناصر الجدول الدراسي";
        $infos .= " وتم مسح $deleted_row_count عنصر";
        if(!$affected_row_count)
        {
            $infos .= "\n إذا كنت لا تشاهد توليد عناصر الجدول الدراسي فذلك لأحد الأسباب التالية\n";
            $infos .= "1. أنه تم مسبقا انشاؤها ولا حاجة للاعادة\n";
            $infos .= "2. لا يوجد نموذج اسبوع دراسي مكتمل مرتبط بالقسم المحدد لهذه الحلقة  في مجال عمل المنشأة\n";
            $infos .= "3. أنه تم توليدها فعلا لكن ليست لك الصلاحية في رؤيتها\n";
        }
        return array("", $infos,"",$sql);
    }

    public function genereMyStudentSessions($lang = "ar", $student_id = 0)
    {
        /*
        $me = AfwSession::getUserIdActing();
        if (!$me) return array("no user connected", "");*/

        $school_year = $this->het("school_year_id");
        if (!$school_year) return array("no school year defined for this school class", "");

        return $school_year->genereStudentSessions($lang, $this->getVal("level_class_id"), $this->getVal("class_name"), $student_id);    
    }


    public function genereSchoolClassCourses($lang = "ar")
    {
        $school_year = $this->het("school_year_id");
        if (!$school_year) return array("no school year defined for this school class", "");

        return $school_year->genereSchoolClassCourses($lang, 0, $this->id);    
    }


    public function resetAndGenereCourseSessions($lang = "ar", $max_days=5)
    {
        return self::genereCourseSessions($lang, $max_days, $reset=true);
    }

    public function continueGenereCourseSessionsFromLast($lang = "ar", $max_days=5)
    {
        return self::genereCourseSessions($lang, $max_days, $reset=false, $continue_from_last=true);
    }

    public function genereCourseSessionsInPast($lang = "ar")    
    {
        $max_days_past=AfwSession::config("genere-course-sessions-in-past-days", 7);

        return self::genereCourseSessions($lang, $max_days_past, $reset=false, $continue_from_last=false, $back_to_past=$max_days_past);
    }

    

    public function genereCourseSessions($lang = "ar", $max_days=5, $reset=false, $continue_from_last=false, $back_to_past=false)
    {
        
        // die("genereCourseSessions($lang, $max_days, $reset, $continue_from_last, $back_to_past)");
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        $me = AfwSession::getUserIdActing();
        if (!$me) return array("no user connected", "");

        if ($this->getScheduleErrorsNb() > 0)  return array("يجب معالجة الأخطاء أولا حتى تنتفي", "");

        for($wwwdddd=1;$wwwdddd<=7;$wwwdddd++)
        {
                if($this->findInMfk("wdays_mfk",$wwwdddd,$mfk_empty_so_found=false))
                {
                    if($this->dayIsNotScheduled($wwwdddd))
                    {
                        return array("لم يتم تعيين المواد الدراسية والمعلمين ل :" . $this->translate('sched_'.$wwwdddd,$lang), "");
                    }
                }
        }
        
        list($err,$inf) = $this->updateMyProfsAndBooks($lang);
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;

        $sc_id = $this->getId();
        //$school = $this->get("school_id");
        $school_id = $this->calc("school_id",false,"value");
        $levels_template_id = $this->calc("levels_template_id",false,"value");
        $school_level_order = $this->calc("school_level_order",false,"value");
        $level_class_order = $this->calc("level_class_order",false,"value");

        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        $year = $this->calc("year",false,"value");

        $db = $this->getDatabase();

        // reset if possible and requested
        if($reset)
        {
            $sqlDelete = "delete from $db.course_session 
                        where school_id = $school_id 
                            and levels_template_id = $levels_template_id 
                            and school_level_order = $school_level_order 
                            and level_class_order = $level_class_order 
                            and class_name = _utf8'$class_name'
                            and session_status_id in (1)";  // next (قادمة)
            list($resultDelete, $row_countDelete, $affected_row_countDelete) = self::executeQuery($sqlDelete);
            $tech_arr[]=$sqlDelete;
            $war_arr[] = "تم مسح $row_countDelete من الحصص الفارغة لاجل اعادة توليدها";
        }
        else
        {
            $affected_row_countDelete = 0;
        }

        if(!$back_to_past)
        {
            if($continue_from_last)
            {
                // last generated date
                $sql = "select max(session_date) as max_session_date from $db.course_session 
                            where school_id = $school_id 
                                and levels_template_id = $levels_template_id 
                                and school_level_order = $school_level_order 
                                and level_class_order = $level_class_order 
                                and class_name = _utf8'$class_name' ";
    
                $max_session_date = $this->dbdb_recup_value($sql);
                if(!$max_session_date) $cur_date = date("Y-m-d");
                else $cur_date = AfwDateHelper::shiftGregDate($max_session_date,1);
            }
            else
            {
                $cur_date = date("Y-m-d");
            }
        }
        else
        {
            $old_cur_date = $cur_date = date("Y-m-d");
            $cur_date = AfwDateHelper::shiftGregDate($cur_date,-$back_to_past);
            // die("cur_date=$cur_date = AfwDateHelper::shiftGregDate($old_cur_date,-$back_to_past)");
        }

        


        $fin_date = AfwDateHelper::hijriToGreg($this->getSy()->getVal("school_year_end_hdate"));
        $max_date = AfwDateHelper::shiftGregDate($cur_date,$max_days);

        // we will not generate more than SY days
        if($fin_date>$max_date) $fin_date = $max_date;

        if ($cur_date > $fin_date)  return array("لا يوجد فترة دراسية متبقية في السنة الدراسية لتوليد الحصص عليها", "");
        
        // 1. check no past session (حصة سابقة) during this period
        $sql = "select count(*) as nb from $db.course_session 
                      where school_id = $school_id 
                        and levels_template_id = $levels_template_id 
                        and school_level_order = $school_level_order 
                        and level_class_order = $level_class_order 
                        and class_name = _utf8'$class_name' 
                        and session_date between '$cur_date' and '$max_date'";

        $nb_past_session = $this->dbdb_recup_value($sql);
        if ($nb_past_session > 0) return array("لا يمكن إعادة إنشاء الحصص لهذه الفترة لوجود حصص سابقة فيها", "");

        // 2. check course sched items existance
        $sql = "select count(*) as nb from $db.course_sched_item csi 
                      where csi.school_year_id = $school_year_id 
                          and csi.level_class_id = $level_class_id 
                          and csi.class_name = _utf8'$class_name'";

        $nb_prof_sched_items = $this->dbdb_recup_value($sql);
        if (!$nb_prof_sched_items) return array("رجاء التثبت من وجود خطة أسبوعية لهذا الصف، وأنه تم تحديد المدرس لكل مادة", "");

        // 2. check hday existance for this school_year
        $sql = "select count(*) as nb from $db.hday hd 
                      where hd.school_year_id = $school_year_id 
                          and hd.hday_gdat between '$cur_date' and '$fin_date' 
                          and hd.holiday = 'N'";

        $nb_hdays = $this->dbdb_recup_value($sql);
        if (!$nb_hdays) return array("رجاء التثبت من وجود  أيام التقويم لهذه السنة الدراسية بين تاريخ   $cur_date و $fin_date", "");

        
        
        
        $sqlInsert = "insert into $db.course_session (created_by, created_at, updated_by, updated_at, active, version, 
                                    school_id, levels_template_id, school_level_order, level_class_order, 
                                    class_name, session_date, session_order, year, semester,
                                    course_id, course_num, prof_id, 
                                    mainwork_book_id, homework_book_id, homework2_book_id,
                                    session_start_time, session_end_time, session_status_id
                                    ) 
                      select $me, now(), $me, now(), 'Y', 0, 
                             $school_id, $levels_template_id, $school_level_order, $level_class_order, 
                             csi.class_name, hd.hday_gdat, csi.session_order, $year, hd.semester,
                             csi.course_id, 0 as course_num, csi.prof_id, 
                             csi.mainwork_book_id, csi.homework_book_id, csi.homework2_book_id,
                             csi.session_start_time, csi.session_end_time, 1
                        from $db.course_sched_item csi 
                           inner join $db.hday hd 
                                           on hd.school_year_id = csi.school_year_id 
                                               and hd.wday_id = csi.wday_id 
                                               and hd.hday_gdat between '$cur_date' and '$fin_date' 
                                               and hd.holiday = 'N'
                        where csi.school_year_id = $school_year_id 
                          and csi.level_class_id = $level_class_id 
                          and csi.class_name = _utf8'$class_name'
                          and csi.course_id > 0 and csi.prof_id > 0";

        list($resultInsert, $row_count, $affected_row_count) = self::executeQuery($sqlInsert);
        $tech_arr[]=$sqlInsert;
        $inf_arr[]="للفترة من $cur_date إلى $fin_date تم توليد $affected_row_count حصة دراسية جديدة وتم مسح $affected_row_countDelete قديمة";

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }


    protected function afwCall($name, $arguments) {
        if(substr($name, 0, 12)=="copyDayPlan_") 
        {
            $lang = $arguments[0];
            list($wd, $wd2) = explode("_",substr($name, 12));
            return $this->copyDayPlan($wd, $wd2, $lang);
        }

        return false;
        // the above return should be keeped if not treated
    }

    public function dayIsNotScheduled($wd)
    {
        $courseSchedList = $this->get("sched_$wd");        
        return CourseSchedItem::isEmptyList($courseSchedList);
    }

    public function copyDayPlan($wd, $wd2, $lang)
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        $ss = &$this->getSchoolScope();
        if (!$ss)  return array("مخطط مجال عمل المنشأة غير محدد لهذا الصف", "");
        $ssDep = $ss->het("sdepartment_id");
        if (!$ssDep) return array("الإدارة/القسم لهذه الحلقة  غير محددة. رجاء مراجعة مخطط مجال عمل المنشأة وتحديد القسم لكل صف : " . $ss->getDisplay($lang), "");
        $weekTemplateObj = $ssDep->het("week_template_id");
        if (!$weekTemplateObj) return array("النموذج الاسبوعي  لهذا القسم/هذه الإدارة غير محدد : " . $ssDep->getDisplay($lang), "");

        $wdTemplateObj = $weekTemplateObj->het("day".$wd."_template_id");
        $session_count = $wdTemplateObj->getRelation("dayTemplateItemList")->count();
        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        for($session_order=1; $session_order<=$session_count; $session_order++) 
        {
            $wd2CourseSchedItem = CourseSchedItem::loadByMainIndex($school_year_id, $level_class_id, $class_name,$wd2, $session_order);
            if($wd2CourseSchedItem)
            {
                $wdCourseSchedItem = CourseSchedItem::loadByMainIndex($school_year_id, $level_class_id, $class_name,$wd, $session_order,true);
                if($wdCourseSchedItem)
                {
                        $wdCourseSchedItem->copyDataFrom($wd2CourseSchedItem);
                        $wdCourseSchedItem->commit();
                        $inf_arr[] = "تم انشاء الحصة $session_order من المخطط الدراسي لليوم ".$wd;
                }            
                else $err_arr[] = "لم نتمكن من انشاء الحصة $session_order من المخطط الدراسي لليوم ".$wd;
            }
            //else $war_arr[] = "لم يوجد الحصة $session_order من المخطط الدراسي لليوم ".$wd2;
        }
        
        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }


    protected function getPublicMethods()
    {

        $return = array(
            
            

            "x1aH54" => array(
                "METHOD" => "genereCourseSessions",
                "LABEL_EN" => "genere course sessions",
                'STEP' => 13,
                'ADMIN-ONLY' => true,
                // "BF-ID" => "104680" // @todo change 104680
            ),

            "c1onT4" => array(
                "METHOD" => "continueGenereCourseSessionsFromLast",
                "LABEL_EN" => "continue Genere Course Sessions From Last",
                'STEP' => 13,
                'ADMIN-ONLY' => true,
                //"BF-ID" => "104680" // @todo change 104680
            ),


            "p4asT8" => array(
                "METHOD" => "genereCourseSessionsInPast",
                "LABEL_EN" => "genere Course Sessions In Past",
                'STEP' => 13,
                'ADMIN-ONLY' => true,
                //"BF-ID" => "104680" // @todo change 104680
            ),
            

            "y1aG22" => array(
                "METHOD" => "resetAndGenereCourseSessions",
                "LABEL_EN" => "regenere  course sessions",
                "COLOR" => "red",
                'ADMIN-ONLY' => true,
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => [
                    'ar' => 'سيتم تصفير الحصص الدراسية وتوليدها من جديد',
                    'en' =>
                        'Course sessions will be resetted and regenerated newly',
                ],
                'CONFIRMATION_QUESTION' => [
                    'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                    'en' => 'Are you sure you want to perform this procedure',
                ],
                'STEP' => 13,
            ),

            'xRa45b' => [
                'METHOD' => 'genereSchoolClassCourses',
                'ADMIN-ONLY' => true,
                'LABEL_AR' =>
                    'تحديث المقررات',
                'LABEL_EN' => 'genere school class courses',
                'STEP' => 4,
                'COLOR' => 'green',
                "BF-ID" => "104708", // School Class Course edit
            ],

            'xHa12b' => [
                'METHOD' => 'genereStudentFileCourses',
                'ADMIN-ONLY' => true,
                'LABEL_AR' =>
                    'تحديث سجلات متابعة الطلاب',
                'LABEL_EN' => 'genere school class courses',
                'STEP' => 4,
                'COLOR' => 'green',
                
                "BF-ID" => "104694",// Student File Course edit
            ],

            'xA482b' => [
                'METHOD' => 'loadAcceptedCandidates',
                'ADMIN-ONLY' => true,
                'LABEL_AR' =>
                    'استيراد المتقدمين المقبولين',
                'LABEL_EN' => 'load Accepted Candidates',
                'STEP' => 3,
                'COLOR' => 'blue',
                "BF-ID" => "104631", // SCandidate edit
            ],

            


            "as2354" => array(
                "METHOD" => "genereMyStudentSessions",
                'ADMIN-ONLY' => true,
                "LABEL_AR" => "توليد سجلات الحضور والانصراف للطلاب",
                "LABEL_EN" => "genere student sessions records",
                "BF-ID" => "104680",
                'STEP' => 13,
            ),


            "b3aG22" => array(
                    "METHOD" => "resetAndGenereStudentFileCourses",
                    "LABEL_EN" => "regenere student courses",
                    'LABEL_AR' => 'إعادة توليد سجلات متابعة الطلاب',
                    "COLOR" => "red",
                    'ADMIN-ONLY' => true,
                    'CONFIRMATION_NEEDED' => true,
                    'CONFIRMATION_WARNING' => [
                    'ar' => 'سيتم تصفير سجلات متابعة انجاز الطالب وتوليدها من جديد',
                    'en' =>
                            'student courses will be resetted and regenerated newly',
                    ],
                    'CONFIRMATION_QUESTION' => [
                    'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                    'en' => 'Are you sure you want to perform this procedure',
                    ],
                    'STEP' => 4,
            ),

        );

        for ($wd = 1; $wd <= 7; $wd++) {
            if($wd>1)
            {
                for($wd2 = $wd-1; ($wd2 <= 7 and $wd2 >= $wd-3 and $wd2 >= 1); $wd2--) 
                {
                    if($this->findInMfk("wdays_mfk",$wd2,$mfk_empty_so_found=false))
                    {
                        $title_ar = "نسخ خطة " . $this->translate("sched_$wd2", $lang = "ar")." إلى " . $this->translate("sched_$wd", $lang = "ar");
                        
                        $color = ($wd2 % 2 == 0) ? "blue" : "green";
                        
                        $methodName = "copyDayPlan_".$wd."_".$wd2;
                        $return[self::hzmEncode($methodName)] = array(
                                        "METHOD"=>$methodName,
                                        "STEP"=>$wd+5, 
                                        "COLOR"=>$color, 
                                        "LABEL_AR"=>$title_ar, 
                                        'ADMIN-ONLY' => true, // temp because "BF-ID"=>"104701" doesn't work
                                        "BF-ID"=>"104701"); // edit Course Sched Item
                    }
 
                }
            }
        }

        $return["xZaf54"] = array(
            "METHOD" => "genereWeekProgram",
            "LABEL_EN" => "genere week program",
            "COLOR" => "blue",
            "STEP" => 4,
            "STEPS" => [6,7,8,9,10,11,12],
            'ADMIN-ONLY' => true, // temp because "BF-ID"=>"104701" doesn't work
            "BF-ID" => "104701" // ???
        );

        $return["xHgU54"] = array(
            "METHOD" => "updateMyProfsAndBooks",
            "LABEL_EN" => "update My Profs",
            "COLOR" => "green",
            'ADMIN-ONLY' => true, // temp because "BF-ID"=>"104708" doesn't work
            "STEP" => 4,
            "STEPS" => [6,7,8,9,10,11,12],
            "BF-ID" => "104708" 
        );

        

        if($this->getMyCourseSchedItems($onlyCount=true)>0)
        {

            $return["gZay38"] = array(
                "METHOD" => "resetWeekProgram",
                "LABEL_AR" => "تصفير كل عناصر الخطة الدراسية الأسبوعية",
                "LABEL_EN" => "reset week program",
                "COLOR" => "red",
                "STEP" => 4,
                "STEPS" => [6,7,8,9,10,11,12],
                'ADMIN-ONLY' => true,
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => [
                    'ar' => 'سيتم تصفير الخطة الدراسية وتوليدها من جديد',
                    'en' =>
                        'Week program will be resetted and regenerated newly',
                ],
                'CONFIRMATION_QUESTION' => [
                    'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                    'en' => 'Are you sure you want to perform this procedure',
                ],
            );
        }
        else
        {
            $return["gZay38"] = array(
                "METHOD" => "genereWeekProgramEmpty",
                "LABEL_EN" => "genere empty week program",
                "STEP" => 7,
                "STEPS" => [6,7,8,9,10,11,12],
                "BF-ID" => "104701", // ???
                'ADMIN-ONLY' => true, // temp because "BF-ID"=>"104708" doesn't work
            );
        }

        

        return $return;
    }

    public function resetAndGenereStudentFileCourses($lang = "ar")
    {
            return $this->genereStudentFileCourses($lang, $reset=true);
    }

    protected function getOtherLinksArray($mode, $genereLog = false, $step="all")
    {
        global $me, $objme, $lang;

        $displ = $this->getDisplay();
        $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
        if ($mode == "mode_stdn") {
            unset($link);
            $my_id = $this->getId();
            $link = array();
            $title = $this->translate("students-management",$lang)." $displ";


            // $school_id = $this->getVal("school_id");
            // $year = $this->getVal("year");
            // $level_class_id = $this->getVal("level_class_id");
            // $class_name = $this->getVal("class_name");


            list($needed_stdn, $room_comment) = $this->getPlacesInfo();

            if ($needed_stdn > 10) $needed_stdn = 10;
            if(true)
            {                
                $url = "main.php?Main_Page=afw_mode_qedit.php&cl=StudentFile&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis";
                $url .= "&newo=-1&ids=all";
                $url .= "&fixmtit=$title&fixmdisable=1&fixm=school_class_id=$my_id&sel_school_class_id=$my_id";
                $link["URL"] = $url;
                        
                $link["TITLE"] = $title;
                $link["BF-ID"] = self::$BF_QEDIT_STUDENT_FILE;
                $otherLinksArray[] = $link;
            }

            if($needed_stdn>0)
            {
                $school_id = $this->calc("school_id",false,"value");
                $levels_template_id = $this->calc("levels_template_id",false,"value");
                $school_level_order = $this->calc("school_level_order",false,"value");
                $level_class_order = $this->calc("level_class_order",false,"value");
                $class_name = $this->getVal("class_name");
                $year = $this->calc("year",false,"value");
        

                $url = "main.php?Main_Page=afw_mode_edit.php&cl=StudentFile&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis";
                $url .= "&sel_school_id=$school_id&sel_year=$year&sel_levels_template_id=$levels_template_id&sel_school_level_order=$school_level_order&sel_level_class_order=$level_class_order&sel_class_name=$class_name&comm=$room_comment";

                $link["URL"] = $url;
                        
                $link["TITLE"] = "القبول المباشر";
                $link["BF-ID"] = self::$BF_QEDIT_STUDENT_FILE;
                $otherLinksArray[] = $link;
            }
        }


        if ($mode == "mode_cand") {
            unset($link);
            $my_id = $this->getId();
            $sc_id = $this->get("school_year_id")->getVal("school_id");
            $yr = $this->get("school_year_id")->getVal("year");
            $lc_id = $this->getVal("level_class_id");
            $link = array();
            $title = "إدارة  ملفات التقديم";
            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=Scandidate&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis&newo=3&limit=30&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_id=$sc_id,year=$yr,level_class_id=$lc_id,candidate_status_id=1&sel_school_id=$sc_id&sel_year=$yr&sel_level_class_id=$lc_id&sel_candidate_status_id=1";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }


        for ($wd = 1; $wd <= 7; $wd++) {
            if ($mode == "mode_sched_$wd") {
                unset($link);
                $my_id = $this->getId();
                $scy_id = $this->getVal("school_year_id");
                $lc_id = $this->getVal("level_class_id");
                $class_name = $this->getVal("class_name");
                $next_step = 6+$wd;
                if($next_step>12) $next_step = 12;
                $link = array();
                $title = "إدارة " . $this->translate("sched_$wd", $lang = "ar");
                $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CourseSchedItem&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis&step_origin=$next_step&newo=-1&limit=30";
                $link["URL"] .= "&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_year_id=$scy_id,level_class_id=$lc_id,class_name=$class_name,wday_id=$wd";
                $link["URL"] .= "&sel_school_year_id=$scy_id&sel_level_class_id=$lc_id&sel_class_name=$class_name&sel_wday_id=$wd";

                $link["TITLE"] = $title;
                $link["UGROUPS"] = array();
                $otherLinksArray[] = $link;

            }
        }

        if ($mode == "mode_schoolClassCourseList") {
            $my_id = $this->getId();
            $my_name = $this->getId();
            $scy_id = $this->getVal("school_year_id");
            $lc_id = $this->getVal("level_class_id");
            $class_name = $this->getVal("class_name");

            $courses_config_template_id = $this->getSchool()->getVal("courses_config_template_id");

            unset($link);
            $link = array();
            $title = "تحديد المدرس لكل مادة دراسية";
            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=SchoolClassCourse&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis&newo=-1&limit=30";
            $link["URL"] .= "&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_year_id=$scy_id,level_class_id=$lc_id,class_name=$class_name";
            $link["URL"] .= "&sel_school_year_id=$scy_id&sel_level_class_id=$lc_id&sel_class_name=$class_name";

            $link["TITLE"] = $title;
            $link["BF-ID"] = 101726;
            $otherLinksArray[] = $link;


            unset($link);
            $link = array();
            $title = "إعدادات المواد الدراسية";
            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CoursesConfigItem&currmod=sis&id_origin=$my_id&class_origin=SchoolClass&module_origin=sis&newo=-1&limit=50";
            $link["URL"] .= "&ids=all&fixmtit=إدارةالمناهج والكتب للمواد الدراسية صف $my_name&fixmdisable=1&fixm=courses_config_template_id=$courses_config_template_id,level_class_id=$lc_id";
            $link["URL"] .= "&sel_courses_config_template_id=$courses_config_template_id&sel_level_class_id=$lc_id";

            $link["TITLE"] = $title;
            $link["BF-ID"] = 101768;
            $otherLinksArray[] = $link;
        }



        return $otherLinksArray;
    }


    protected function getSpecificDataErrors($lang = "ar", $show_val = true, $step = "all")
    {
        $sp_errors = array();
        
        
        
        $crsp_nb = $this->calc("crsp_nb");
        $errors_sched_nb = $this->calc("errors_sched_nb");
         
        if (!$crsp_nb and $this->stepContainAttribute($step,"crsp_nb", null))
        {
            $sp_errors["crsp_nb"] = "لا يوجد مقررات دراسية";
        }

        if ($errors_sched_nb>0  and $this->stepContainAttribute($step,"errors_sched_nb", null))
        {
            $sp_errors["errors_sched_nb"] = "يوجد أخطاء في الجدول الدراسي";
        }

        if($this->stepContainAttribute($step,"courseSessionList", null))
        {
            $ws_sched_nb = $this->calc("ws_sched_nb");
            
            if (!$ws_sched_nb)
            {
                $sp_errors["courseSessionList"] = "جدولة الحصص الدراسية غير موجودة";
            }
            else
            {
                $ws_req_nb = $this->calc("ws_req_nb");
                if ($ws_sched_nb < $ws_req_nb) {
                    $sp_errors["courseSessionList"] = "جدولة الحصص الدراسية غير مكتملة بشكل صحيح عدد الحصص المجدولة $ws_sched_nb أقل من عدد الحصص المطلوبة $ws_req_nb";
                }
            }
            
        }

        return $sp_errors;
    }

    public function getStillRequestedCourses()
    {
        $crsp_list = $this->get("schoolClassCourseList");
        $crsp_still_req_list = array();
        foreach ($crsp_list as $crsp_id => $crsp_item) {
            if ($crsp_item->getVal("week_sess_nb") > $crsp_item->getVal("scheds_nb")) {
                $crsp_still_req_list[$crsp_id] = $crsp_item;
            }
        }

        return $crsp_still_req_list;
    }


    public function attributeIsApplicable($attribute)
    {
        /*
        global $objme;
        
        if (($attribute == "crsp_nb") or
            ($attribute == "ws_req_nb") or
            ($attribute == "ws_sched_nb") or
            ($attribute == "errors_sched_nb")
        ) return AfwSession::hasOption("STATS-COMPUTE");
        */

        for($d=1;$d<=7;$d++)
        {
            if($attribute == "sched_$d")
            {
                return $this->findInMfk("wdays_mfk",$d,$mfk_empty_so_found=false);
            }
        }

        return true;
    }

    public function whyAttributeIsNotApplicable($attribute, $lang = "ar")
    {
        $icon = "na20.png";
        $textReason = $this->translateMessage("ACTIVATE-STATS-COMPUTE-OPTION", $lang);
        return array($icon, $textReason);
    }


        protected function beforeDelete($id,$id_replace) 
        {
            $server_db_prefix = AfwSession::config("db_prefix","c0");
            
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
                   // FK part of me - not deletable 

                        
                   // FK part of me - deletable 
                       // sis.student_file-الصف	school_class_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once student_file.php";
                            $school_yearObj = $this->het("school_year_id");
                            $level_classObj = $this->het("level_class_id");
                            if($school_yearObj and $level_classObj)
                            {
                                $schoolObj = $school_yearObj->het("school_id");
                                $school_levelObj = $level_classObj->het("school_level_id");
                                if($schoolObj and $school_levelObj)
                                {
                                    $school_id = $schoolObj->id;
                                    $year = $school_yearObj->getVal("year");
                                    $levels_template_id = $school_levelObj->getVal("levels_template_id");
                                    $school_level_order = $school_levelObj->getVal("school_level_order");
                                    $level_class_order = $level_classObj->getVal("level_class_order");
                                    $class_name = $this->getVal("class_name");
                                    StudentFile::removeWhere("school_id=$school_id and year=$year and levels_template_id = $levels_template_id and school_level_order = $school_level_order and level_class_order = $level_class_order and class_name=_utf8'$class_name'");
                                }
                            }
                            // $this->execQuery("delete from ${server_db_prefix}sis.student_file where school_class_id = '$id' ");
                            
                        } 
                        
                        

                   
                   // FK not part of me - replaceable 

                        
                   
                   // MFK

               }
               else
               {
                        
                        

                        
                        // MFK

                   
               } 
               return true;
            }    
	}

    public function getFieldGroupInfos($fgroup)
    {
        if ($fgroup == 'stdn') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        
        
        

        return ['name' => $fgroup, 'css' => 'pct_100'];
    }

    public function stepsAreOrdered()
    {
        return false;
    }

    public function calcProf_id($what="value")
    {
        global $lang;
        return self::decode_result($this->getProf(),$what,$lang);
    }

    public function getProf()
    {
        $school_year_id = $this->getVal("school_year_id");
        $level_class_id = $this->getVal("level_class_id");
        $class_name = $this->getVal("class_name");
        
        
        $schoolYearObj = $this->het("school_year_id");
        if(!$schoolYearObj) return null;
        $schoolObj = $schoolYearObj->het("school_id");
        if(!$schoolObj) return null;
        $main_course_id =  $schoolObj->getVal("main_course_id");
        $schoolClassMainCourseObj = SchoolClassCourse::loadByMainIndex($school_year_id, $level_class_id, $class_name, $main_course_id);
        if(!$schoolClassMainCourseObj) return null;
        $profObj = $schoolClassMainCourseObj->het("prof_id");
        return $profObj;
    }

    public function calcStart_near_date()
    {
        return date("Y-m-d");
    }

    public function calcEnd_near_date()
    {
        return AfwDateHelper::shiftGregDate('',7);
    }

    public function calcStart_prev_date()
    {
        return AfwDateHelper::shiftGregDate('',-6);
    }

    public function calcEnd_prev_date()
    {
        return AfwDateHelper::shiftGregDate('',-1);
    }

    public static function list_of_wdays_mfk()
    {
            return Hday::list_of_wday_id();
    }


    public function getPrayerTimeList()
    {
            return AfwDateHelper::getPrayerTimeList();
    }

    public function getAfterPrayerTimeList()
    {
        return AfwDateHelper::getAfterPrayerTimeList();
    }

    public function genereStudentFileCourses($lang = "ar", $reset=false)
    {
        $errors_arr = [];
        $infos_arr = [];
        $warns_arr = [];

        $schoolClassCourseList = $this->het("schoolClassCourseList");
        foreach($schoolClassCourseList as $schoolClassCourseItem)
        {
            list($error, $info, $warn) = $schoolClassCourseItem->genereStudentFileCourses($lang, $reset);
            if($error) $errors_arr[] = $error;
            if($info) $infos_arr[] = $info;
            if($warn) $warns_arr[] = $warn;
            
        }

        return self::pbm_result($errors_arr, $infos_arr, $warns_arr);
    }

    public function getCapacityIndicator($arrObjectsRelated)    
    {
        $total_capacity=0;
        $total_students=0;
        list($needed_stdn, $room_comment, $room_capacity, $stdn_count) = $this->getPlacesInfo(false);
        $total_capacity += $room_capacity;
        $total_students += $stdn_count;


        return [$total_capacity, $total_students];        
    }

    public function getMainworkIndicator($arrObjectsRelated)    
    {
        return $this->getWorkIndicator("mainwork", $arrObjectsRelated);
    }

    public function getHomeworkIndicator($arrObjectsRelated)    
    {
        return $this->getWorkIndicator("homework", $arrObjectsRelated);
    }

    public function getHomework2Indicator($arrObjectsRelated)    
    {
        return $this->getWorkIndicator("homework2", $arrObjectsRelated);
    }

    public function getAbsenceIndicator($arrObjectsRelated)    
    {
        $total_objective=0;
        $total_done=0;
        if($arrObjectsRelated["courseSession"])
        {
            $courseSession = $arrObjectsRelated["courseSession"];
            $attendanceList = $courseSession->get("attendanceList");
            //die("attendanceList=".var_export($attendanceList));
            foreach($attendanceList as $attendanceItem)
            {
                
                $coming_status_id = $attendanceItem->getVal("coming_status_id");
                if(($coming_status_id>0) and ($coming_status_id != StudentSession::$attendance_status_leave))
                {
                    $total_objective ++;                    
                    if($coming_status_id == StudentSession::$attendance_status_absent) $total_done ++;
                }
            }
        }
        

        return [$total_objective, $total_done];
    }

    public function getWorkIndicator($attribute, $arrObjectsRelated)    
    {        
        $total_objective=0;
        $total_done=0;
        if($arrObjectsRelated["courseSession"])
        {
            $courseSession = $arrObjectsRelated["courseSession"];
            $attendanceList = $courseSession->get("attendanceList");
            //die("attendanceList=".var_export($attendanceList));
            foreach($attendanceList as $attendanceItem)
            {
                $coming_status_id = $attendanceItem->getVal("coming_status_id");
                if(($coming_status_id>=1) and ($coming_status_id<=2))
                {
                    $rank = $attendanceItem->getVal($attribute."_rank_id");
                    $total_objective ++;
                    if($rank>=2) $total_done ++;
                }
            }
        }
        

        return [$total_objective, $total_done];        
    }

    public function getMainworkIncompleteIndicator($arrObjectsRelated)    
    {
        return $this->getWorkIncompleteIndicator("mainwork", $arrObjectsRelated);
    }

    public function getHomeworkIncompleteIndicator($arrObjectsRelated)    
    {
        return $this->getWorkIncompleteIndicator("homework", $arrObjectsRelated);
    }

    public function getHomework2IncompleteIndicator($arrObjectsRelated)    
    {
        return $this->getWorkIncompleteIndicator("homework2", $arrObjectsRelated);
    }

    public function getWorkIncompleteIndicator($attribute, $arrObjectsRelated)    
    {        
        $total_objective=0;
        $total_done=0;
        if($arrObjectsRelated["courseSession"])
        {
            $courseSession = $arrObjectsRelated["courseSession"];
            $attendanceList = $courseSession->get("attendanceList");
            //die("attendanceList=".var_export($attendanceList));
            foreach($attendanceList as $attendanceItem)
            {
                $coming_status_id = $attendanceItem->getVal("coming_status_id");
                if(($coming_status_id>=1) and ($coming_status_id<=2))
                {
                    $rank = $attendanceItem->getVal($attribute."_rank_id");
                    $total_objective ++;
                    if($rank==2) $total_done ++;
                }
            }
        }
        

        return [$total_objective, $total_done];        
    }

    public function notCompleted()
    {
        list($needed_stdn, $room_comment) = $this->getPlacesInfo();
        if($needed_stdn>0) return true;
        return false;
    }

    public function loadAcceptedCandidates(
        $lang = 'ar',
        $redistribute = true,
        $updateData = false
    )
    {
        $school_year = $this->het("school_year_id");
        if (!$school_year) return array("how no school year defined for this school class, it is strange", "");
        $levels_template_id = $this->calc("levels_template_id",false,"value");
        $school_level_order = $this->calc("school_level_order",false,"value");
        $level_class_order = $this->calc("level_class_order",false,"value");
        $class_name = $this->getVal("class_name");

        return $school_year->distributeAcceptedCandidates($lang, $redistribute, $updateData, 
                        // make specific to this school class
                        $levels_template_id,
                        $school_level_order,
                        $level_class_order,
                        $class_name);
    }

    
}
