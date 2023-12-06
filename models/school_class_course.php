<?php
// ------------------------------------------------------------------------------------
// rafik 02-mars-2023
// ALTER TABLE `school_class_course` CHANGE `new_prof_id` `new_prof_id` INT(11) NOT NULL DEFAULT '0'; 
// ALTER TABLE `school_class_course` CHANGE `new_prof_start_hdate` `new_prof_start_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL; 

// 24/7/2023
// alter table c0sis.school_class_course add study_program_id INT NULL after prof_id;

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class SchoolClassCourse extends SisObject
{

        public static $DATABASE                = "";
        public static $MODULE                    = "sis";
        public static $TABLE                        = "school_class_course";
        public static $DB_STRUCTURE = null;

        public function __construct()
        {
                parent::__construct("school_class_course", "id", "sis");
                SisSchoolClassCourseAfwStructure::initInstance($this);
        }

        public function getDisplay($lang = "ar")
        {
                list($data0, $link0) = $this->displayAttribute("school_year_id");
                list($data1, $link1) = $this->displayAttribute("level_class_id");
                list($data2, $link2) = $this->displayAttribute("class_name");
                list($data3, $link3) = $this->displayAttribute("course_id");

                return $data0 . " &larr; " . $data1 . " &larr; " . $data2 . " &larr; " . $data3;
        }

        public function getFormuleResult($attribute, $what = 'value')
        {
                // global $me, $URL_RACINE_SITE;    

                switch ($attribute) {

                        case "sclass":
                                return $this->getSchoolClass();
                                break;
                }

                return $this->calcFormuleResult($attribute, $what);
        }

        public static function loadMySchoolClasses($school_year_id, $prof_id)
        {
                $result = [];
                $ids = [];
                // level_class_id, class_name
                $obj = new SchoolClassCourse();
                $obj->select("school_year_id", $school_year_id);
                $obj->select("prof_id", $prof_id);
                $sccList = $obj->loadMany();
                foreach ($sccList as $sccItem) {
                        $scItem = $sccItem->getSchoolClass();
                        if ($scItem) {
                                $result[$scItem->id] = $scItem;
                                $ids[] = $scItem->id;
                        }
                }

                return [$ids, $result];
        }

        public static function loadByMainIndex($school_year_id, $level_class_id, $class_name, $course_id, $create_obj_if_not_found = false)
        {
                $obj = new SchoolClassCourse();
                $obj->select("school_year_id", $school_year_id);
                $obj->select("level_class_id", $level_class_id);
                $obj->select("class_name", $class_name);
                $obj->select("course_id", $course_id);

                if ($obj->load()) {
                        if ($create_obj_if_not_found) $obj->activate();
                        return $obj;
                } elseif ($create_obj_if_not_found) {
                        $obj->set("school_year_id", $school_year_id);
                        $obj->set("level_class_id", $level_class_id);
                        $obj->set("class_name", $class_name);
                        $obj->set("course_id", $course_id);
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                } else return null;
        }

        public function getSchoolClass()
        {


                $school_year_id = $this->getVal("school_year_id");
                $level_class_id = $this->getVal("level_class_id");
                $class_name = $this->getVal("class_name");

                return SchoolClass::loadByMainIndex($school_year_id, $level_class_id, $class_name);
        }


        public function getDefaultInjaz($book_id)
        {
                $school_year_id = $this->getVal("school_year_id");
                $level_class_id = $this->getVal("level_class_id");
                $class_name = $this->getVal("class_name");
                $course_id = $this->getVal("course_id");
                return SchoolClassCourseBook::loadByMainIndex($school_year_id, $level_class_id, $class_name, $course_id, $book_id);
        }


        public function calcScheds_nb()
        {
                // المجدولة في البرنامج الاسبوعي
                return $this->getRelation("scheds")->count();
        }


        public function calcFollowups_nb()
        {
                // 
                return $this->getRelation("courses")->count();
        }

        public function calcWeek_sess_nb()
        {
                $sy = &$this->getSy();
                $cct_id = $sy->getSchool()->getVal("courses_config_template_id");

                $course_id = $this->getVal("course_id");
                $level_class_id = $this->getVal("level_class_id");

                $file_dir_name = dirname(__FILE__);

                include_once("$file_dir_name/../sis/courses_config_item.php");

                $cci = new CoursesConfigItem();

                $cci->select("courses_config_template_id", $cct_id);
                $cci->select("course_id", $course_id);
                $cci->select("level_class_id", $level_class_id);
                if ($cci->load()) {
                        $session_nb = $cci->getVal("session_nb");
                } else $session_nb = -1;

                return $session_nb;
        }

        protected function getSpecificDataErrors($lang = "ar", $show_val = true, $step = "all")
        {
                $sp_errors = array();

                if ($this->getVal("week_sess_nb") < $this->getVal("scheds_nb")) {
                        $sp_errors["scheds_nb"] = "عدد الحصص المجدولة  لهذه المادة تجاوز المبرمج في الاعدادات";
                }

                $school_year_id = $this->getVal("school_year_id");
                if ($this->getVal("prof_id") > 0) {
                        $scheds_list = $this->get("scheds");
                        $prof = $this->get("prof_id");
                        if (!$prof->getId()) $sp_errors["prof_id"] = "المدرس غير معروف";
                        $prof_wd_list = $prof->get("wday_mfk");
                        $prof_no_work_list = array();
                        foreach ($scheds_list as $sched_id => $sched_item) {
                                $wd_id = $sched_item->getVal("wday_id");
                                $wd_name = $sched_item->get("wday_id")->getDisplay();
                                $sess_ord = $sched_item->getVal("session_order");
                                if (!$prof_wd_list[$wd_id]) $prof_no_work_list[] = $wd_name;
                                if (AfwSession::config('check-prof-conflicts', false)) {
                                        // not clear what is this below
                                        // and the error message is non-understandable
                                        // if(count($scc_list)==0) $sp_errors["scheds"] = " خطأ في برمجة جدول المدرس  عنصر $sched_id";
                                        $scc_list = $prof->calcSchoolClassCourseList("object", $school_year_id, $wd_id, $sess_ord);
                                        if (count($scc_list) > 1) {
                                                $sp_errors["scheds"] = "يوجد تزاحم في جدول المدرس  ليوم " . $wd_name . " حصة رقم $sess_ord <br>\n";
                                                foreach ($scc_list as $scc_item) {
                                                        $sp_errors["scheds"] .= $scc_item->getDisplay($lang) . "<br>\n";
                                                }
                                        }
                                }

                                
                        }

                        if (count($prof_no_work_list) > 0) {
                                $sp_errors["prof_id"] = "المدرس غير متوفر في الايام التالية : " . implode(" ", $prof_no_work_list);
                        }
                }
                elseif($this->getVal("course_id") != Course::$course_is_repot)  
                {
                       $sp_errors["prof_id"] = "المدرس غير محدد";
                }



                return $sp_errors;
        }

        public function stepsAreOrdered()
        {
                return false;
        }


        public function genereSchoolClassCourseBookList($lang = "ar")
        {
                $courseObj = $this->hetCourse();
                if (!$courseObj) return [];

                $obj_inserted = 0;
                $obj_count = 0;

                $attribute_arr = ["mainwork", "homework", "homework2"];
                $dones = [];
                foreach ($attribute_arr as $attribute) {
                        $obj_book_id = $courseObj->getVal($attribute . "_book_id");
                        if ($obj_book_id and (!$dones[$obj_book_id])) {
                                $school_year_id = $this->getVal("school_year_id");
                                $level_class_id = $this->getVal("level_class_id");
                                $class_name = $this->getVal("class_name");
                                $course_id = $this->getVal("course_id");
                                $sccbObj = SchoolClassCourseBook::loadByMainIndex($school_year_id, $level_class_id, $class_name, $course_id, $obj_book_id, true);
                                if ($sccbObj->is_new) {
                                        $obj_inserted++;
                                }
                                $obj_count++;
                        }


                        $dones[$obj_book_id] = true;
                }

                return ["", "genereSchoolClassCourseBookList : inserted $obj_inserted, all $obj_count"];
        }



        protected function getPublicMethods()
        {

                $return = array(

                        "aXsd84" => array(
                                "METHOD" => "genereSchoolClassCourseBookList",
                                "LABEL_EN" => "genere School Class Course Book List",
                                'STEP' => 3,
                                "ONLY-ADMIN" => true
                        ),

                        "a2aH54" => array(
                                "METHOD" => "genereStudentFileCourses",
                                "LABEL_EN" => "genere student courses",
                                'STEP' => 4,
                                "BF-ID" => ""
                        ),

                        "b3aG22" => array(
                                "METHOD" => "resetAndGenereStudentFileCourses",
                                "LABEL_EN" => "regenere student courses",
                                "COLOR" => "red",
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

                        'hAbc33' => [
                                'METHOD' => 'resetAllWorksFromManhajAndInjaz',
                                'LABEL_AR' => 'تصفير الانجاز لجميع الطلاب',
                                'LABEL_EN' => 'reset All Works For all students',
                                'STEP' => 4,
                                'COLOR' => "red",
                                'CONFIRMATION_NEEDED' => true,
                                'CONFIRMATION_WARNING' => [
                                        'ar' => 'سيتم تصفير انجازات الطلبة وفقا لسياسة الحلقة',
                                        'en' =>
                                        'student work will be resetted according to class',
                                ],
                                'CONFIRMATION_QUESTION' => [
                                        'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                                        'en' => 'Are you sure you want to perform this procedure',
                                ],
                                'ADMIN-ONLY' => 'true',
                        ],

                        'hUio98' => [
                                'METHOD' => 'updateAllWorksFromManhajAndInjaz',
                                'LABEL_AR' => 'استيراد الانجاز لأول مرة لجميع الطلاب',
                                'LABEL_EN' => 'update All Works For all students',
                                'STEP' => 3,
                                'COLOR' => "blue",
                                'ADMIN-ONLY' => 'true',
                        ],





                );





                return $return;
        }


        public function resetAndGenereStudentFileCourses($lang = "ar")
        {
                return $this->genereStudentFileCourses($lang, $reset = true);
        }

        public function genereStudentFileCourses($lang = "ar", $reset = false)
        {
                $db = $this->getDatabase();


                if ($reset) {
                        $course_id = $this->getVal("course_id");
                        $syObj = $this->het("school_year_id");
                        $lcObj = $this->het("level_class_id");

                        $level_class_order = $lcObj->getVal("level_class_order");
                        $school_level_id = $lcObj->getVal("school_level_id");
                        $slObj = $lcObj->het("school_level_id");

                        $school_level_order = $slObj->getVal("school_level_order");
                        $school_id = $syObj->getVal("school_id");
                        $schoolObj = $syObj->het("school_id");
                        $levels_template_id = $schoolObj->getVal("levels_template_id");
                        $year = $syObj->getVal("year");
                        $sql_delete = "delete from $db.student_file_course 
                        where school_id=$school_id 
                          and year=$year
                          and levels_template_id = $levels_template_id
                          and school_level_order = $school_level_order 
                          and level_class_order = $level_class_order
                          and course_id = $course_id
                        ";

                        list($result, $row_count, $affected_row_count) = self::executeQuery($sql_delete);
                        $info_mess_arr[] = $this->tm('عدد سجلات متابعة الطلاب التي تم مسحها : ', $lang) . $affected_row_count;
                }

                $me = AfwSession::getUserIdActing();
                $this_id = $this->id;
                //$scObj = $this->getSchoolClass();
                //$class_name = $scObj->getVal("class_name");


                $sql_insert = "insert into $db.student_file_course(created_by,  created_at, updated_by,updated_at, active, version, 
                                student_id,school_id,year,semester,class_name,levels_template_id,
                                school_level_order,level_class_order,course_id, 
                                mainwork_start_book_id, mainwork_start_part_id, mainwork_start_chapter_id, mainwork_start_paragraph_num,
                                mainwork_end_book_id, mainwork_end_part_id, mainwork_end_chapter_id, mainwork_end_paragraph_num,
                                homework_start_book_id, homework_start_part_id, homework_start_chapter_id, homework_start_paragraph_num,
                                homework_end_book_id, homework_end_part_id, homework_end_chapter_id, homework_end_paragraph_num,
                                homework2_start_book_id, homework2_start_part_id, homework2_start_chapter_id, homework2_start_paragraph_num,
                                homework2_end_book_id, homework2_end_part_id, homework2_end_chapter_id, homework2_end_paragraph_num)
                                select $me, now(), $me, now(), sf.active, 0 as version, 
                                        sf.student_id, sy.school_id, sy.year, sy.semester, scc.class_name, s.levels_template_id, 
                                        sl.school_level_order, lc.level_class_order, scc.course_id,
                                        cs.mainwork_book_id,  cs.mainwork_start_part_id, cs.mainwork_start_chapter_id, cs.mainwork_start_paragraph_num,
                                        cs.mainwork_book_id,  cs.mainwork_start_part_id, cs.mainwork_start_chapter_id, cs.mainwork_start_paragraph_num+2,
                                        cs.homework_book_id,  cs.homework_start_part_id, cs.homework_start_chapter_id, cs.homework_start_paragraph_num,
                                        cs.homework_book_id,  cs.homework_start_part_id, cs.homework_start_chapter_id, cs.homework_start_paragraph_num+200,
                                        cs.homework2_book_id, cs.homework2_start_part_id, cs.homework2_start_chapter_id, cs.homework2_start_paragraph_num,
                                        cs.homework2_book_id, cs.homework2_start_part_id, cs.homework2_start_chapter_id, cs.homework2_start_paragraph_num+30
                                
                                from $db.school_class_course scc
                                        inner join $db.course cs on scc.course_id = cs.id
                                        inner join $db.school_year sy on scc.school_year_id = sy.id
                                        inner join $db.school s on sy.school_id = s.id
                                        inner join $db.level_class lc on lc.id = scc.level_class_id
                                        inner join $db.school_level sl on sl.id = lc.school_level_id
                                        inner join $db.student_file sf 
                                                on sy.school_id = sf.school_id 
                                                and sy.year = sf.year 
                                                and s.levels_template_id = sf.levels_template_id 
                                                and sl.school_level_order = sf.school_level_order 
                                                and lc.level_class_order = sf.level_class_order
                                                and scc.class_name = sf.class_name
                                                and sf.active = 'Y'
                                        left join $db.student_file_course ss on
                                                ss.school_id = sf.school_id and
                                                ss.levels_template_id = s.levels_template_id and
                                                ss.school_level_order = sl.school_level_order and
                                                ss.level_class_order = lc.level_class_order and
                                                ss.year = sy.year and
                                                ss.student_id = sf.student_id and
                                                ss.course_id = scc.course_id   
                                                          
                                where scc.id = $this_id
                                  and ss.student_id is null";


                list($result, $row_count, $affected_row_count) = self::executeQuery($sql_insert);


                $studentFileCourseList = $this->get("courses");

                list(
                        $err_arr,
                        $inf_arr,
                        $war_arr,
                        $tech_arr
                ) = StudentFileCourse::updateAllWorkForStudentFileCourseList($studentFileCourseList, $lang, $reset, true);

                $inf_arr[] = $this->getDisplay($lang) . " : " . $this->tm('عدد سجلات متابعة الطلاب التي تم توليدها : ', $lang) . $affected_row_count;
                return self::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
        }

        public function getFieldGroupInfos($fgroup)
        {


                return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        public function updateAllWorksFromManhajAndInjaz($lang = "ar", $reset = false)
        {
                $studentFileCourseList = $this->get("courses");
                list(
                        $err_arr,
                        $inf_arr,
                        $war_arr,
                        $tech_arr
                ) = StudentFileCourse::updateAllWorkForStudentFileCourseList($studentFileCourseList, $lang, $reset, true);

                return self::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
        }

        public function resetAllWorksFromManhajAndInjaz($lang = "ar")
        {
                return $this->updateAllWorksFromManhajAndInjaz($lang, $reset = true);
        }

        protected function beforeDelete($id, $id_replace)
        {
                $server_db_prefix = AfwSession::config("db_prefix", "c0");

                if (!$id) {
                        $id = $this->getId();
                        $simul = true;
                } else {
                        $simul = false;
                }

                if ($id) {
                        if ($id_replace == 0) {
                                // FK part of me - not deletable 


                                // FK part of me - deletable 


                                // FK not part of me - replaceable 



                                // MFK

                        } else {
                                // FK on me 


                                // MFK


                        }
                        return true;
                }
        }
}
