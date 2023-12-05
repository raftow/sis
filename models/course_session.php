<?php
// ------------------------------------------------------------------------------------
// ALTER TABLE `course_session` CHANGE `real_start_time` `real_start_time` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
// ALTER TABLE `course_session` CHANGE `real_end_time` `real_end_time` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
// ALTER TABLE `course_session` CHANGE `session_status_comment` `session_status_comment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
// alter table course_session change class_name class_name varchar(24) not null;



$file_dir_name = dirname(__FILE__);

// old include of afw.php

class CourseSession extends SisObject
{


    public static $executed_session_status = '(3,2)';

    public static $DATABASE = '';
    public static $MODULE = 'sis';
    public static $TABLE = 'course_session';
    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('course_session', null, 'sis');
        SisCourseSessionAfwStructure::initInstance($this);
    }

    public static function loadById($id)
    {
        $obj = new CourseSession();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else {
            return null;
        }
    }

    public static function loadByMainIndex(
        $school_id,
        $levels_template_id,
        $school_level_order,
        $level_class_order,
        $class_name,
        $session_date,
        $session_order,
        $create_obj_if_not_found = false
    ) {
        $obj = new CourseSession();
        $obj->select('school_id', $school_id);
        $obj->select('levels_template_id', $levels_template_id);
        $obj->select('school_level_order', $school_level_order);
        $obj->select('level_class_order', $level_class_order);
        $obj->select('class_name', $class_name);
        $obj->select('session_date', $session_date);
        $obj->select('session_order', $session_order);

        if ($obj->load()) {
            if ($create_obj_if_not_found) {
                $obj->activate();
            }
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set('school_id', $school_id);
            $obj->set('levels_template_id', $levels_template_id);
            $obj->set('school_level_order', $school_level_order);
            $obj->set('level_class_order', $level_class_order);
            $obj->set('class_name', $class_name);
            $obj->set('session_date', $session_date);
            $obj->set('session_order', $session_order);

            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else {
            return null;
        }
    }

    public function getShortDisplay($lang = 'ar')
    {
        $course = $this->showAttribute('course_id');
        $session_date = $this->showAttribute('session_date');
        $session_start_time = $this->showAttribute('session_start_time');
        $class_name = $this->showAttribute('class_name');

        return "$course ← $class_name ← $session_date ← $session_start_time";
    }

    public function getDisplay($lang = 'ar')
    {
        $course = $this->showAttribute('course_id');
        $lvl = $this->showAttribute('level_class_id');
        $class_name = $this->showAttribute('class_name');
        $session_date = $this->showAttribute('session_date');
        $session_start_time = $this->showAttribute('session_start_time');
        $session_status_id = $this->showAttribute('session_status_id');

        return "$course ← $lvl ← $class_name : [$session_date $session_start_time] ($session_status_id)";
    }

    public function list_of_year()
    {
        $file_dir_name = dirname(__FILE__);

        include_once "$file_dir_name/../afw/common_date.php";
        list($hijri_year, $mm, $dd) = AfwDateHelper::currentHijriDate('hlist');
        $hijri_year = intval($hijri_year);

        $arr_list_of_year = [];

        $hijri_year_m_1 = $hijri_year - 1;
        $hijri_year_p_1 = $hijri_year + 1;
        $hijri_year_p_2 = $hijri_year + 2;

        $arr_list_of_year[$hijri_year_m_1] = "$hijri_year_m_1-$hijri_year";
        $arr_list_of_year[$hijri_year] = "$hijri_year-$hijri_year_p_1";
        $arr_list_of_year[$hijri_year_p_1] = "$hijri_year_p_1-$hijri_year_p_2";

        return $arr_list_of_year;
    }

    public function getFormuleResult($attribute, $what='value')
    {
        // global $me, $URL_RACINE_SITE;

        switch ($attribute) {
            case 'attendanceList':
                return $this->getStudentSessions();
                break;

            case 'course_plan':
                return $this->getCoursePlan();
                break;
        }

        return $this->calcFormuleResult($attribute, $what);
    }

    public function getCoursePlan()
    {
        global $file_dir_name;

        // // require_once cpc_course_plan.php");
        $cplan_empty = new CpcCoursePlan();

        $school_id = $this->getVal('school_id');
        $course_num = $this->getVal('course_num');
        $cplan_empty->homework_desc = '';
        $cplan_empty->course_desc = '';

        if (!$school_id) {
            $cplan_empty->session_desc = 'no school';
        }
        if (!$school_id) {
            return $cplan_empty;
        }
        if (!$course_num) {
            $course_num = 1;
        } // for test only
        if (!$course_num) {
            $cplan_empty->session_desc = 'no course num';
        }
        if (!$course_num) {
            return $cplan_empty;
        }
        $school = $this->get('school_id');
        $courses_config_template_id = $school->getVal(
            'courses_config_template_id'
        );
        if (!$courses_config_template_id) {
            $cplan_empty->session_desc =
                'no course config template in school configuration';
        }
        if (!$courses_config_template_id) {
            return $cplan_empty;
        }

        // // require_once courses_config_item.php");

        $course_id = $this->getVal('course_id');
        $level_class_id = $this->calc('level_class_id');

        $confItem = new CoursesConfigItem();

        $confItem->select(
            'courses_config_template_id',
            $courses_config_template_id
        );
        $confItem->select('course_id', $course_id);
        $confItem->select('level_class_id', $level_class_id);
        if (!$level_class_id or !$course_id or !$confItem->load()) {
            $cplan_empty->session_desc = "for this course (id=$course_id) and level class (id=$level_class_id) : no course config item ";
            return $cplan_empty;
        }

        $course_program_id = $confItem->getVal('course_program_id');
        $book_id = $confItem->getVal('book_id');
        $homework_book_id = $confItem->getVal('homework_book_id');
        if (!$course_program_id) {
            $cplan_empty->session_desc =
                "for this course (id=$course_id) and this level class (id=$level_class_id) this config item (" .
                $confItem->displayMyLinkMode('edit') .
                ') no course program defined';
            return $cplan_empty;
        }

        $semester = $this->getVal('semester');
        //$sday_num = $this->getVal('sday_num');
        $class_name = $this->getVal('class_name');
        $session_order = $this->getVal('session_order');

        $cplan_new = new CpcCoursePlan();
        $cplan_new->select('course_program_id', $course_program_id);
        $cplan_new->select('level_class_id', $level_class_id);
        $cplan_new->select('course_id', $course_id);
        $cplan_new->select('course_num', $course_num);
        if (!$cplan_new->load()) {
            $cplan_new->homework_desc = '';
            $cplan_new->course_desc = '';
            $cplan_new->session_desc = "course plan not created click <a href='main.php?Main_Page=afw_mode_edit.php&cl=CpcCoursePlan&sel_course_program_id=$course_program_id&sel_level_class_id=$level_class_id&sel_course_id=$course_id&sel_course_num=$course_num'>here</a> to create";
        }

        return $cplan_new;
    }

    public function getStudentSessions()
    {
        
        $school_id = $this->getVal('school_id');
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        $level_class_order = $this->getVal('level_class_order');
        $class_name = $this->getVal('class_name');
        $session_date = $this->getVal('session_date');
        $session_order = $this->getVal('session_order');

        global $file_dir_name;
        // // require_once student_session.php");
        // include_once("$file_dir_name/../afw/common_date.php");
        // $cur_hdate =AfwDateHelper::currentHijriDate();
        $ssObj = new StudentSession();
        $ssObj->select('school_id', $school_id);
        $ssObj->select('levels_template_id', $levels_template_id);
        $ssObj->select('school_level_order', $school_level_order);
        $ssObj->select('level_class_order', $level_class_order);
        $ssObj->select('class_name', $class_name);
        $ssObj->select('session_date', $session_date);
        $ssObj->select('session_order', $session_order);

        return $ssObj->loadMany('', 'student_id');
    }

    protected function getOtherLinksArray(
        $mode,
        $genereLog = false,
        $step = 'all'
    ) {
        global $me, $objme, $lang;

        $my_id = $this->getId();
        $school_id = $this->getVal('school_id');
        // $syear = $this->getVal('syear');
        // $semester = $this->getVal('semester');
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        $level_class_order = $this->getVal('level_class_order');
        $class_name = $this->getVal('class_name');
        $session_date = $this->getVal('session_date');
        
        $session_order = $this->getVal('session_order');
        
        //$session_status_id = $this->getVal('session_status_id');

        $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
        
        if(($mode=="qedit") or ($mode=="QEDIT")) 
        {
            
            list($ready,$attendanceList, $attendanceToFix) = $this->isReadyToClose();
            if($ready)
            {
                unset($link);
                
                $link = [];
                $title = 'غلق الحصة';
                //$title_detailed = $title . ' ' . $this->getShortDisplay($lang);
                $link['URL'] = "index.php?id-cls=$my_id";              
                $link['TITLE'] = $title;
                $link['UGROUPS'] = [];
                $link['PUBLIC'] = true;
                $link['COLOR'] = 'green';
                $otherLinksArray[] = $link;
            }

            if(true)
            {
                unset($link);
                
                $link = [];
                $title = 'تحديث بدء الحفظ والمراجعة حسب المنهج';
                //$title_detailed = $title . ' ' . $this->getShortDisplay($lang);
                $link['URL'] = "index.php?id-rsw=$my_id";              
                $link['TITLE'] = $title;
                $link['UGROUPS'] = [];
                $link['PUBLIC'] = true;
                $link['COLOR'] = 'yellow';
                $otherLinksArray[] = $link;
            }
        
            $schoolClassItem = $this->calcSchool_class_id($what="object");        
            if($schoolClassItem) 
            {  
                $studentFileList = $schoolClassItem->get("stdn");

                $arr_books = [1=>"القرآن الكريم برواية حفص"];
                foreach($arr_books as $book_id => $book_name)
                {
                    unset($link);
                    $link = [];
                    $ids = "";
                
                    foreach($studentFileList as $studentFileItem)
                    {
                        $student_id = $studentFileItem->getVal("student_id");
                        if($student_id>0)
                        {
                            if($ids) $ids .= ",";
                            $ids .= $student_id."|".$book_id;
                        }
                    }

                    $title = $book_name." &larr; ".$schoolClassItem->translate("students-injaz",$lang);
                    $url = "m.php?mp=qe&cl=StudentBook&cm=sis&io=$my_id&co=SchoolClass&mo=sis&no=-1&ids=$ids";
                    $url .= "&xt=$title&xm=main_book_id=$book_id&xd=1&sel_main_book_id=$book_id";
                    $link["URL"] = $url;
                            
                    $link["TITLE"] = $title;
                    $link["BF-ID"] = SchoolClass::$BF_QEDIT_STUDENT_FILE;
                    $otherLinksArray[] = $link;
                }  
            }
        }

        if ($mode == 'mode_attendanceList') {
            unset($link);
            
            $link = [];
            $title = 'تحضير الطلاب';
            $title_detailed = $title . ' ' . $this->getShortDisplay($lang);
            $link['URL'] = 
              "main.php?Main_Page=afw_mode_qedit.php&cl=StudentSession&currmod=sis&id_origin=$my_id&class_origin=CourseSession&module_origin=sis&step_origin=4&newo=-1&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1";              
            $link['URL'] .= 
              "&fixm=school_id=$school_id,levels_template_id=$levels_template_id,school_level_order=$school_level_order,level_class_order=$level_class_order,class_name=$class_name,session_date=$session_date,session_order=$session_order";
            $link['URL'] .= "&sel_school_id=$school_id&sel_levels_template_id=$levels_template_id&sel_school_level_order=$school_level_order&sel_level_class_order=$level_class_order&sel_class_name=$class_name&sel_session_date=$session_date&sel_session_order=$session_order";

            $link['TITLE'] = $title;
            $link['UGROUPS'] = [];
            $link['PUBLIC'] = true;
            $otherLinksArray[] = $link;
        }

        return $otherLinksArray;
    }

    public function openSession($lang="ar")
    {
        $this->genereMyStudentSessions($lang);
        $this->set("session_status_id", SessionStatus::$opened_session);
        $this->commit();

        return ["","تم فتح الحصة"];
    }
    
    public function isOpened()
    {
        $session_status = $this->getVal("session_status_id");
        if($session_status == SessionStatus::$coming_session) return true;
        if($session_status == SessionStatus::$current_session) return true;
        if($session_status == SessionStatus::$opened_session) return true;
        return false;
    }
    
    public function isReadyToClose()
    {
        $ssList = $this->getStudentSessions();
        foreach($ssList as $ssItem)
        {
            if(!$ssItem->isReady()) return [false, $ssList, $ssItem];
        }

        return [true, $ssList, null];
    }

    public function getPreviousCourseSession($executed = false)
    {
        /*
        $prev = new CourseSession();
        $prev->select('school_id', $this->getVal('school_id'));
        $prev->select('syear', $this->getVal('syear'));
        to see this below because level_class_id is formula and not real field
        $prev->select('level_class_id', $this->getVal('level_class_id'));
        $prev->select('class_name', $this->getVal('class_name'));
        $prev->select('course_id', $this->getVal('course_id'));
        if ($executed) {
            $prev->where(
                'session_status_id in ' . self::$executed_session_status
            );
        }
        $sday_num = $this->getVal('sday_num');
        $session_order = $this->getVal('session_order');
        $prev->where(
            "((sday_num < $sday_num) or (sday_num=$sday_num and session_order<$session_order))"
        );
        $prev_list = $prev->loadMany(1, 'sday_num desc');

        if (count($prev_list) > 0) {
            foreach ($prev_list as $prev_id => $prev_item) {
                return $prev_item;
            }
        } else {
            return null;
        }*/
    }

    public function getPreviousExecutedCourseSession()
    {
        return $this->getPreviousCourseSession(true);
    }

    public function getSpecificActions($step)
    {
        global $lang;

        $actions_tpl_arr = [];

        $my_id = $this->getId();
        $school_id = $this->getVal('school_id');
        $syear = $this->getVal('syear');
        $semester = $this->getVal('semester');
        $sday_num = $this->getVal('sday_num');
        $level_class_id = $this->calc('level_class_id');
        $class_name = $this->getVal('class_name');
        $session_order = $this->getVal('session_order');
        $session_status_id = $this->getVal('session_status_id');

        $title = 'تحضير ';
        $title_detailed = $title . ' حصة ' . $this->getShortDisplay($lang);
        $param_arr['Main_Page'] = 'afw_mode_qedit.php';
        $param_arr['cl'] = 'StudentSession';
        $param_arr['currmod'] = 'sis';
        $param_arr['id_origin'] = $my_id;
        $param_arr['class_origin'] = 'CourseSession';
        $param_arr['module_origin'] = 'sis';
        $param_arr['newo'] = '-1';
        $param_arr['limit'] = '100';
        $param_arr['ids'] = 'all';
        $param_arr['fixmtit'] = $title_detailed;
        $param_arr['fixmdisable'] = '1';
        $param_arr['fixm'] = "school_id=$school_id,syear=$syear,semester=$semester,level_class_id=$level_class_id,class_name=$class_name,session_order=$session_order";
        $param_arr['sel_school_id'] = "$school_id";
        $param_arr['sel_year'] = "$syear";
        $param_arr['sel_semester'] = "$semester";
        $param_arr['sel_sday_num'] = "$sday_num";
        $param_arr['sel_level_class_id'] = $level_class_id;
        $param_arr['sel_class_name'] = $class_name;
        $param_arr['sel_session_order'] = $session_order;
        $param_arr['her_ssid'] = $session_status_id;

        $actions_tpl_arr['attend'] = [
            'page' => 'main.php',
            'params' => $param_arr,
            'img' => '../sis/pic/attend.png',
            'bf_system' => 1044,
            'bf_code' => 'BF_COURSE_SESSION_ATTENDANCE',
        ];

        return $actions_tpl_arr;
    }

    public function calcSchool_year_id($what="value")
    {
        $school_id = $this->getVal('school_id');
        $year = $this->getVal('year');
        $semester = 0;
        $syObj = SchoolYear::loadByMainIndex($school_id, $year, $semester, 1);
        global $lang;
        return self::decode_result($syObj,$what,$lang);
    }

    public function calcSemester_id($what="value")
    {
        $school_id = $this->getVal('school_id');
        $year = $this->getVal('year');
        $semester = $this->getVal('semester');
        $syObj = SchoolYear::loadByMainIndex($school_id, $year, $semester, 1);
        global $lang;
        return self::decode_result($syObj,$what,$lang);
    }

    public function calcSchool_level_id($what="value")
    {
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        if($levels_template_id and $school_level_order)
        {
            $slObj = SchoolLevel::loadByMainIndex(
                $levels_template_id,
                $school_level_order
            );
        }
        else $slObj = null;
        
        global $lang;
        return self::decode_result($slObj,$what,$lang);
    }

    public function calcLevel_class_id($what="value")
    {
        
        $school_level_obj = $this->calcSchool_level_id("object");
        $school_level_id = $school_level_obj ? $school_level_obj->id : 0;
        
        $level_class_order = $this->getVal('level_class_order');
        if($school_level_id and $level_class_order)
        {
            $lcObj = LevelClass::loadByMainIndex($school_level_id, $level_class_order);
        }
        else $lcObj = null;
        
        global $lang;
        return self::decode_result($lcObj,$what,$lang);
        
    }

    public function calcSchool_class_id($what="value")
    {
        $level_class_obj = $this->calcLevel_class_id("object");
        if($level_class_obj) $level_class_id = $level_class_obj->id;
        else $level_class_id = 0;
        if(!$level_class_id) return null;
        $class_name = $this->getVal('class_name');
        $school_year_obj = $this->calcSchool_year_id("object");
        $school_year_id = $school_year_obj ? $school_year_obj->id : 0; 
        $scObj = SchoolClass::loadByMainIndex(
            $school_year_id,
            $level_class_id,
            $class_name
        );
        global $lang;
        return self::decode_result($scObj,$what,$lang);
    }

    public function calcSchool_class_course_id($what="value")
    {
        $level_class_id = $this->calcLevel_class_id();
        if(!$level_class_id) throw new RuntimeException("No level_class_id for this course session");
        $school_year_obj = $this->calcSchool_year_id("object");
        $school_year_id = $school_year_obj ? $school_year_obj->id : 0;
        if(!$school_year_id) throw new RuntimeException("No school_year_id for this course session");
        $class_name = $this->getVal('class_name');
        $course_id = $this->getVal('course_id');
        
        
        $sclcObj = SchoolClassCourse::loadByMainIndex($school_year_id, $level_class_id,$class_name, $course_id);
        global $lang;
        return self::decode_result($sclcObj,$what,$lang);
        /*
        if ($sclcObj) {
            if($what=="object") return $sclcObj;
            else return $sclcObj->id;
        } else {
            throw new RuntimeException("SchoolClassCourse::loadByMainIndex($school_year_id, $level_class_id,$class_name, $course_id) return empty");
            return null;
        }*/
    }

    public function getPrayerTimeList()
    {
            return AfwDateHelper::getPrayerTimeList();
    }

    public function getAfterPrayerTimeList()
    {
            return AfwDateHelper::getAfterPrayerTimeList();
    }

    public function stepsAreOrdered()
    {
        return false;
    }

    public function updateMyStudentWork($lang = 'ar')
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        $attendanceList = $this->get("attendanceList");
        foreach($attendanceList as $attendanceItem)
        {
            $student = $attendanceItem->showAttribute('student_id');

            list($err,$inf,$war,$tech) = $attendanceItem->updateMyStudentWorkFromStudentFileCourse($lang);
            if($err) $err_arr[] = "$student : ".$err;
            if($inf) $inf_arr[] = "$student : ".$inf;
            if($war) $war_arr[] = "$student : ".$war;
            if($tech) $tech_arr[] = $tech;
        }

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function deleteMyStudentSessions($lang = 'ar', $all=false)
    {
        $db = $this->getDatabase();
        $school_id = $this->getVal('school_id');
        // $school = $this->het('school_id');
        // if(!$school) return ['no school defined for this course session', ''];
        $levels_template_id = $this->getVal("levels_template_id");
        $school_level_order = $this->getVal("school_level_order");
        $level_class_order = $this->getVal("level_class_order");
        $class_name = $this->getVal("class_name");
        $session_date_fixed = $this->getVal("session_date");
        $session_order = $this->getVal("session_order");

        if(!$all) $cond_not_all = "and (coming_status_id = 0 or coming_status_id is null)";
        else $cond_not_all = "";

        $sqlDelete = "delete from $db.student_session 
                where school_id = $school_id 
                and levels_template_id = $levels_template_id 
                and school_level_order = $school_level_order 
                and level_class_order = $level_class_order 
                and class_name = _utf8'$class_name'
                and session_date = '$session_date_fixed'
                and session_order = $session_order
                $cond_not_all ";

        
                

        list($result, $row_count, $deleted_row_count) = self::executeQuery($sqlDelete);

        return [$deleted_row_count, $sqlDelete];
    }

    public function genereMyStudentSessions($lang = 'ar')
    {
        


        $school_year_obj = $this->calcSchool_year_id("object");
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $sy_id = $this->getId();
        $school_id = $this->getVal('school_id');
        $year = $this->getVal('year');
        $school_id = $this->getVal('school_id');
        // $school = $this->het('school_id');
        // if(!$school) return ['no school defined for this course session', ''];
        $levels_template_id = $this->getVal("levels_template_id");
        $school_level_order = $this->getVal("school_level_order");
        $level_class_order = $this->getVal("level_class_order");
        $class_name = $this->getVal("class_name");
        $session_date_fixed = $this->getVal("session_date");
        $session_order = $this->getVal("session_order");

        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];


        list($deleted_row_count, $sqlDelete) = $this->deleteMyStudentSessions($lang = 'ar', $all=false);
        $tech_arr[] = $sqlDelete;        
        

        

        $war_arr[] = "تم مسح $deleted_row_count من الكشوفات";

        // update student file courses
        $sccObj = $this->calcSchool_class_course_id("object");
        if($sccObj)
        {
            list($err,$inf,$war, $tech) = $sccObj->genereStudentFileCourses($lang);
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
            if($war) $war_arr[] = $war;
            if($tech) $tech_arr[] = $tech;
        }
        else $err_arr[] = "لا يوجد مقرر علمي لهذه الحصة وهذا خلل فني يرجى مراجعة المشرف فيه";
        

        list($err,$inf,$war, $tech) = $school_year_obj->genereStudentSessionsBySchoolLevelAndClassLevel(
            $lang,
            $me,
            $school_id,
            $year,
            $levels_template_id,
            $school_level_order,
            $level_class_order,
            $class_name,
            0,
            $past_offset = 0,
            $future_offset = 0,
            $session_date_fixed,
            $session_order        
        );

        
        
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;
        if($war) $war_arr[] = $war;
        if($tech) $tech_arr[] = $tech;

        return self::pbm_result($err_arr,$inf_arr,$war_arr, $sep = "<br>\n", $tech_arr);
    }


    protected function getPublicMethods()
    {
        return [
            
        'yAa7d5' => [
            'CONDITION' => 'isOpened',
            'METHOD' => 'genereMyStudentSessions',
            'LABEL_AR' => 'تحديث كشوفات الحضور',
            'LABEL_EN' => 'update student sessions',
            'STEP' => 2,
            'COLOR' => "blue",
            'BF-ID' => '104680',
        ],

        'Yo98hU' => [
            'CONDITION' => 'isOpened',
            'METHOD' => 'updateMyStudentWork',
            'LABEL_AR' => 'تحديث الانجاز لجميع الطلاب',
            'LABEL_EN' => 'reset All Works For all students',
            'STEP' => 2,
            'COLOR' => "green",
            'ADMIN-ONLY' => 'true',
        ],

        


        'hUio98' => [
            'CONDITION' => 'isOpened',
            'METHOD' => 'updateAllWorksFromManhajAndInjaz',
            'LABEL_AR' => 'استيراد الانجاز لأول مرة لجميع الطلاب',
            'LABEL_EN' => 'update All Works For all students',
            'STEP' => 3,
            'COLOR' => "blue",
            'ADMIN-ONLY' => 'true',
        ],

        'hAbc33' => [
            'CONDITION' => 'isOpened',
            'METHOD' => 'resetAllWorksFromManhajAndInjaz',
            'LABEL_AR' => 'تصفير الانجاز واعادة استيراده لجميع الطلاب',
            'LABEL_EN' => 'reset All Works For all students',
            'STEP' => 3,
            'COLOR' => "red",
            'ADMIN-ONLY' => 'true',
        ],
    


        'cL1o4s' => [
            'CONDITION' => 'isOpened',
            'METHOD' => 'closeSession',
            'LABEL_AR' => 'غلق الحصة',
            'LABEL_EN' => 'close Session',
            'STEP' => 2,
            'COLOR' => "blue",
            'PUBLIC' => true,
            'QEDIT' => true,
            //'BF-ID' => '104687',
        ],

        'mi12SS' => [
            'METHOD' => 'missSession',
            'LABEL_AR' => 'غياب المعلم',
            'LABEL_EN' => 'close Session',
            'STEP' => 2,
            'COLOR' => "red",
            'BF-ID' => '104687', // 'PUBLIC' => true, // 
            'CONFIRMATION_NEEDED' => true,
            'CONFIRMATION_WARNING' => [
                'ar' => 'سيتم الغاء الحصة واعتبار المعلم غائبا',
                'en' => 'The session will be canceled and the teacher will be considered absent',
            ],
            'CONFIRMATION_QUESTION' => [
                'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                'en' => 'Are you sure you want to perform this procedure',
            ],
        ],

        'cA1ncl' => [
            'METHOD' => 'cancelSession',
            'LABEL_AR' => 'الغاء الحصة',
            'LABEL_EN' => 'close Session',
            'STEP' => 2,
            'COLOR' => "red",
            'BF-ID' => '104687', // 'PUBLIC' => true, // 
            'CONFIRMATION_NEEDED' => true,
            'CONFIRMATION_WARNING' => [
                'ar' => 'سيتم الغاء الحصة لأسباب غبر غياب المعلم',
                'en' => 'The session will be canceled (the teacher will not be considered absent)',
            ],
            'CONFIRMATION_QUESTION' => [
                'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                'en' => 'Are you sure you want to perform this procedure',
            ],
        ],

        

    
    
        ];
    }


    public function resetAllWorksFromManhajAndInjaz($lang="ar")
    {
        return $this->updateAllWorksFromManhajAndInjaz($lang, $reset=true);
    }

    public function updateAllWorksFromManhajAndInjaz($lang="ar", $reset=false)
    {
        $studentFileCourseList = $this->get("courses");
        list($err_arr,
                        $inf_arr,
                        $war_arr,
                        $tech_arr) = StudentFileCourse::updateAllWorkForStudentFileCourseList($studentFileCourseList, $lang, $reset);

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    
    public function missSession($lang="ar")
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        if($this->getVal("session_status_id") != SessionStatus::$missed_session)
        {
            $this->set("session_status_id", SessionStatus::$missed_session);
            $this->commit();

            $inf_arr[] = "تم تسجيل غياب المعلم";

            list($deleted_row_count, $sqlDelete) = $this->deleteMyStudentSessions($lang = 'ar', $all=true);
            $war_arr[] = "تم مسح $deleted_row_count من الكشوفات";
            $tech_arr[] = $sqlDelete;
        }
        else
        {
            $war_arr[] = "تم تسجيل غياب المعلم مسبقا";
        }
        

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function cancelSession($lang="ar")
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        if($this->getVal("session_status_id") != SessionStatus::$canceled_session)
        {
            $this->set("session_status_id", SessionStatus::$canceled_session);
            $this->commit();

            $inf_arr[] = "تم الغاء الحصة";

            list($deleted_row_count, $sqlDelete) = $this->deleteMyStudentSessions($lang = 'ar', $all=true);
            $war_arr[] = "تم مسح $deleted_row_count من الكشوفات";
            $tech_arr[] = $sqlDelete;
        }
        else
        {
            $war_arr[] = "الحصة ملغاة سابقا";
        }
        

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function closeSession($lang="ar")
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        if($this->getVal("session_status_id") != SessionStatus::$closed_session)
        {
            list($ready,$attendanceList, $attendanceToFix) = $this->isReadyToClose();
            if($ready)
            {
                foreach($attendanceList as $attendanceItem)
                {
                    $student = $attendanceItem->showAttribute('student_id');
        
                    list($err,$inf,$war,$tech) = $attendanceItem->closeSession($lang);
                    if($err) $err_arr[] = "$student : ".$err;
                    if($inf) $inf_arr[] = "$student : ".$inf;
                    if($war) $war_arr[] = "$student : ".$war;
                    if($tech) $tech_arr[] = $tech;
                }            

                $this->set("session_status_id", SessionStatus::$closed_session);
                $this->commit();

                $inf_arr[] = "تم غلق الحصة";
            }
            else
            {
                if($attendanceToFix) $studentToFix = " الطالب ".$attendanceToFix->showAttribute('student_id');
                else $studentToFix = "unknown-student-??!!";
                $err_arr[] = "لا يمكن غلق الحصة وفيها كشوفات غير مكتملة التحديث مثلا : ". $studentToFix;
            }
        }
        else
        {
            $war_arr[] = "الحصة مغلقة سابقا";
        }
        

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);        
    }

    
    public function getDefaultStep()
    {
        return 2; // evaluation
    }        


    public function calcPanel($what="value")
    {
        $structure2 = [];
        $structure2['MINIBOX-TEMPLATE'] = "tpl/school_class_minibox_tpl.php";
        $structure2['MINIBOX-TEMPLATE-PHP'] = true;
        $structure2['MINIBOX-OBJECT-KEY'] = "schoolClassItem";
        $schoolClassItem = $this->calcSchool_class_id($what="object");        
        if($schoolClassItem)
        {
            $schoolClassItem->mode_minibox = ["finished"=>true];
            return $schoolClassItem->showMinibox($structure2);        
        }
        else
        {
            return "<div class='error'>No school class for this course session !!!!</div>";
        }
        
    }
    
    
    
    
}
?>
