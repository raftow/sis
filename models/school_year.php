<?php
// ------------------------------------------------------------------------------------
// 25/1/2023
// ALTER TABLE `school_year` CHANGE `semester` `semester` SMALLINT(6) NOT NULL DEFAULT '0', CHANGE `school_year_start_hdate` `school_year_start_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `school_year_end_hdate` `school_year_end_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `admission_start_hdate` `admission_start_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `admission_end_hdate` `admission_end_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL; 
// alter table school_year change id id bigint(20);    
$file_dir_name = dirname(__FILE__);

// old include of afw.php

class SchoolYear extends SisObject
{
    public static $BF_QEDIT_SCHOOL_CLASS = 101635;
    public static $BF_EDIT_SCANDIDATE = 104631;
    public static $BF_QEDIT_SCANDIDATE = 104631;

    public static $DATABASE = '';
    public static $MODULE = 'sis';
    public static $TABLE = 'school_year';
    public static $DB_STRUCTURE = null;

    // 1] = "سنة دراسية";
    public static $SY_TYPE_SYEAR = 1;
    // 2] = "فصل دراسي";
    public static $SY_TYPE_SEMESTER = 2;
    // 3] = "دورة";
    public static $SY_TYPE_SESSION = 3;


    public $pbmethod_main_param = "";

    public function __construct()
    {
        parent::__construct('school_year', 'id', 'sis');
        SisSchoolYearAfwStructure::initInstance($this);
        
    }

    public static function loadByMainIndex(
        $school_id,
        $year,        
        $semester=0,
        $school_year_type=1,
        $sdate = null,
        $edate = null,
        $create_obj_if_not_found = false
    ) {
        $obj = new SchoolYear();
        $obj->select('school_id', $school_id);
        $obj->select('year', $year);
        $obj->select('semester', $semester);
        $obj->select('school_year_type', $school_year_type);
        if ($obj->load()) 
        {
            if ($create_obj_if_not_found) 
            {
                if($sdate) $obj->set('school_year_start_hdate', $sdate); 
                if($edate) $obj->set('school_year_end_hdate', $edate); 
                $obj->activate();
            }
            return $obj;
        } 
        elseif ($create_obj_if_not_found) 
        {
            $obj->set('school_id', $school_id);
            $obj->set('year', $year);
            $obj->set('semester', $semester);
            $obj->set('school_year_type', $school_year_type);
            if($sdate) $obj->set('school_year_start_hdate', $sdate); 
            if($edate) $obj->set('school_year_end_hdate', $edate); 
            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else {
            return null;
        }
    }


    public function getFormuleResult($attribute, $what='value')
    {
        // global $me, $URL_RACINE_SITE;

        switch ($attribute) {
            case 'date_window_max':
                return add_x_days_to_mysqldate(21);
                break;
        }

        return $this->calcFormuleResult($attribute, $what);
    }

    protected function getPublicMethods()
    {
        $today_hday_num = $this->getCurrHdayNum() + 1;

        return [
            'xY01ab' => [
                'METHOD' => 'genereHdays',
                'LABEL_AR' => 'تحديث جميع أيام الدراسة والعطل',
                'LABEL_EN' => 'genere school days with omalqura hijri system',
                'BF-ID' => 104618,
                'COLOR' => 'red',
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => [
                    'ar' => 'سيتم حساب العطل من جديد وفق الاعدادات للمنشأة',
                    'en' =>
                        'Holidays will be calculated again according to the school settings',
                ],
                'CONFIRMATION_QUESTION' => [
                    'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                    'en' => 'Are you sure you want to perform this procedure',
                ],
                'STEP' => 6,
            ],

            'xZ011e' => [
                'METHOD' => 'regenereOnlyComingHdays',
                'LABEL_AR' => 'تحديث أيام الدراسة والعطل القادمة فقط',
                'LABEL_EN' =>
                    're-genere coming school days with omalqura hijri system',
                'STEP' => 6,
            ],

            'MoC36b' => [
                'METHOD' => 'genereSchoolScopeAccordingToStats',
                'LABEL_AR' => 'انشاء مجال عمل المنشأة وفقا للاحصائيات',
                'LABEL_EN' => 'genere school classes according to defined scope',
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => array('ar' => 'سيتم مسح المجال المنشأ سابقا', 'en' => 'The scope created before will be deleted'),
                'CONFIRMATION_QUESTION' =>array('ar' => 'هل أنت متأكد؟', 'en' => 'are you sure ?'),                
                'BF-ID' => 104618,
                'COLOR' => 'blue',
                'STEP' => 3,
                'STEP2' => 4,
            ],

            'yHC36b' => [
                'CONDITION' => 'scopeDefined',
                'METHOD' => 'updateSchoolClassesAccordingToScopeAndAvailableRooms',
                'LABEL_AR' => 'انشاء الحلقات وفقا لمجال عمل المنشأة وتوفر القاعات',
                'LABEL_EN' => 'genere school classes according to defined scope',
                /*'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => array('ar' => 'سيتم مسح الحلقات المنشأة سابقا', 'en' => 'The classes created before will be deleted'),
                'CONFIRMATION_QUESTION' =>array('ar' => 'هل أنت متأكد؟', 'en' => 'are you sure ?'), */
                'BF-ID' => 104618,
                'STEP' => 5,
            ],


            'aHB36b' => [
                'CONDITION' => 'noStudentsRegistered',
                'METHOD' => 'deleteGeneratedSchoolClasses',
                'LABEL_AR' => 'تصفير الحلقات',
                'LABEL_EN' => 'delete generated school classes',
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => array('ar' => 'سيتم مسح الحلقات المنشأة', 'en' => 'The classes created will be deleted'),
                'CONFIRMATION_QUESTION' =>array('ar' => 'هل أنت متأكد؟', 'en' => 'are you sure ?'),                
                'BF-ID' => 104618,
                'COLOR' => 'red',
                'STEP' => 5,
            ],

            'xHa12b' => [
                'METHOD' => 'genereSchoolClassCourses',
                'LABEL_AR' =>
                    'انشاء المواد المقررة وإسنادها للمدرسين ',
                'LABEL_EN' => 'genere school class courses',
                'STEP' => 99,
                'COLOR' => 'red',
                'ADMIN-ONLY' => 'true',
            ],

            'yAa7d5' => [
                'METHOD' => 'genereAllStudentSessions',
                'LABEL_AR' => 'انشاء كشوفات الحضور',
                'LABEL_EN' => 'genere student sessions',
                'STEP' => 99,
                'ADMIN-ONLY' => 'true',
            ],

            'a12xe5' => [
                'METHOD' => 'genereCopyOfSY',
                'LABEL_AR' => 'نسخ السنة الدراسية الجديدة من القديمة',
                'LABEL_EN' => 'copy new school year',
                'STEP' => 99,
                'ADMIN-ONLY' => 'true',
            ],

            'a2d13y' => [
                'METHOD' => 'genereAllCourseSessions',
                'LABEL_AR' => 'إنشاء الحصص لجميع الحلقات',
                'LABEL_EN' => 'genereAllCourseSessions',
                'STEP' => 5,
                'ADMIN-ONLY' => 'true',
            ],

            'u2acss' => [
                'METHOD' => 'updateAllCourseSessionsStatus',
                'LABEL_AR' => 'تحديث حالة الحصص الدراسية',
                'LABEL_EN' => 'update All Course Sessions Status',
                'STEP' => 10,
                'ADMIN-ONLY' => 'true',
            ],

            

            'b3y2de' => [
                'METHOD' => 'synchStudentFiles',
                'LABEL_AR' => 'تحديث  ملفات  الطلاب  من الأرشيف',
                'LABEL_EN' => 'complete Student Files from archive',
                'ADMIN-ONLY' => 'true',
                'COLOR' => 'orange',
                'STEP' => 9,
            ],

            /*
            'b378de' => [
                'METHOD' => 'copyStudentFilesFromPreviousYear',
                'LABEL_AR' => 'نسخ  ملفات  الطلاب  من السنة السابقة',
                'LABEL_EN' => 'complete school year',
                'ADMIN-ONLY' => 'true',
                'STEP' => 99,
            ],*/
            
            'a3x21e' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'distributeAcceptedCandidates',
                'LABEL_AR' => 'توزيع المتقدمين المقبولين على الحلقات',
                'LABEL_EN' => 'distribute accepted candidates',
                'BF-ID' => 104618,
                'COLOR' => 'orange',
                'STEP' => 7,
            ],

            'a3x44e' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'updateStudentWithAcceptedCandidatesData',
                'LABEL_AR' => 'تحديث بيانات ملفات الطلاب الدراسية من خلال بيانات المتقدمين المقبولين',
                'LABEL_EN' => 'update students with accepted candidates data',
                'BF-ID' => 104618,
                'COLOR' => 'red',
                'CONFIRMATION_NEEDED' => true,
                'CONFIRMATION_WARNING' => [
                    'ar' => 'سيتم اعتماد بيانات الطلاب من من خلال بيانات المتقدمين المقبولين',
                    'en' =>
                        'It will update students with accepted candidates data',
                ],
                'CONFIRMATION_QUESTION' => [
                    'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                    'en' => 'Are you sure you want to perform this procedure',
                ],
                'STEP' => 7,
            ],


            'b5yh1u' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'applyConditions',
                'LABEL_AR' => 'تطبيق شروط القبول',
                'LABEL_EN' => 'apply admission rules',
                'BF-ID' => 104618,
                'COLOR' => 'blue',
                'STEP' => 2,
            ],

            'a3H71e' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'cancelApplyConditions',
                'LABEL_AR' => 'الغاء تطبيق شروط القبول',
                'LABEL_EN' => 'cancel apply admission rules',
                'BF-ID' => 104618,
                'COLOR' => 'red',
                'STEP' => 7,
            ],
/*
            'ajhi1e' => [
                'CONDITION' => 'notCompleted',
                'METHOD' => 'simulateDistributeAcceptedCandidates',
                'LABEL_AR' => 'محاكاة توزيع المتقدمين المقبولين على الحلقات',
                'LABEL_EN' => 'simulate distribute accepted candidates',
                'BF-ID' => 104618,
                'COLOR' => 'green',
                'STEP' => 7,
            ],
            
            */


            'a3x45H' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'uploadCandidates',
                'LABEL_AR' => 'استيراد متقدمين ',  // عبر رقم الهوية
                'LABEL_EN' => 'upload candidates with identity',
                'BF-ID' => 104618,
                'COLOR' => 'orange',
                'STEP' => 2,
                'MAIN_PARAM' => [
                                    'structure' => array('IMPORTANT' => 'IN',  'SHOW' => true,  'EDIT' => true, 
                                                        'SIZE' => 'AREA', 'MAXLENGTH' => '64', 'COLS' => 12, // 
                                                        'TYPE' => 'TEXT',  'ROWS' => 12, 'DEFAULT' => '...',
                                                        'PLACE-HOLDER' => 'ادخل كل رقم هوية في سطر منفصل',
                                                        'CSS' => 'width_pct_100 idns',),
                                ]
            ],


            


            'x5yi1u' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'calcGeneralEvaluation',
                'LABEL_AR' => 'التقييم الشامل وتحديد المستوى ونقاط التوزيع',
                'LABEL_EN' => 'calculate general evaluation',
                'BF-ID' => 104618,
                'COLOR' => 'green',
                'STEP' => 7,
            ],

            'x5T01u' => [
                'CONDITION' => 'notFinished',
                'METHOD' => 'calcGeneralEvaluationForAll',
                'LABEL_AR' => 'التقييم الشامل وتحديد المستوى ونقاط التوزيع',
                'LABEL_EN' => 'calculate general evaluation',
                'BF-ID' => 104618,
                'COLOR' => 'green',
                'STEP' => 2,
            ],
            





            
        ];
    }


    public function uploadCandidates($pMethodParams, $lang="ar", $updateStudent=true)
    {
        // die("pMethodParams = ".var_export($pMethodParams,true));
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_nb_queries_executed = $nb_queries_executed;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;
        $idn_text = $pMethodParams['main_param'];
        $idnList = explode("\n",$idn_text);
        $error_arr = [];
        $info_arr = [];
        $war_arr = [];
        $supObj = null;
        $acceptBadIdns = AfwSession::config("accept_bad_idns",true);
        $info_arr[] = count($idnList)." متقدم في كلف الاكسيل";
        $cnt = 0;
        $success_cnt = 0;
        foreach($idnList as $krow => $idn_row)
        {
            //	الاسم الكامل
            //  الهوية 
            //	رقم هوية ولي الامر
            //	الجنسية
            //	رقم جوال الطالب
            //	رقم جوال ولي الأمر
            //	تاريخ الميلاد
            //	المرحلة	
            //  الحفظ
            // 	القدرات
            //	الأخلاق
            //	اسم الحلقة
            $success_upload = false;
            list($full_name, $idn, $parent_idn, $nationailty, $student_mobile, $parent_mobile, $birth_date, $level, $eval, $capacity, $moral, $class_name,) = explode(",",$idn_row);
            $idn = trim($idn);
            $student_mobile = AfwFormatHelper::formatMobile($student_mobile);
            $parent_mobile = AfwFormatHelper::formatMobile($parent_mobile);
            if(!$student_mobile) $student_mobile = $parent_mobile;
            if($idn and AfwFormatHelper::isCorrectMobileNum($student_mobile))
            {
                // even those who use passport or other should convert their orginal IDN to SA IDN virtual, @todo create page for this
                $authorize_other_idns = AfwSession::config('ACCEPT-ANY-OTHER-IDN',false);
                list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($idn, $authorize_other_idns);
                if($idn_correct or $acceptBadIdns)
                {
                    if(!$idn_type_id) $idn_type_id = 99;
                    $stdnObj = Student::loadByMainIndex($idn_type_id,$idn,$updateStudent);
                    if($stdnObj)
                    {
                        $force_update_student_name_when_upload = AfwSession::config("force_update_student_name_when_upload",true);
                        if(($force_update_student_name_when_upload or (!$stdnObj->getVal("firstname") and !$stdnObj->getVal("lastname"))) and $full_name and $updateStudent)
                        {
                            $stdnObj->decodeName($full_name);
                        }
                        $birth_date_en_empty = (!$stdnObj->getVal("birth_date_en") or ($stdnObj->getVal("birth_date_en") == "0000-00-00") or ($stdnObj->getVal("birth_date_en") == "0000-00-00 00:00:00"));  
                        if(!$stdnObj->getVal("birth_date") and $birth_date_en_empty and $birth_date and $updateStudent)
                        {
                            list($success, $birth_date_ar, $birth_date_en) = AfwDataMigrator::fixHijriOrMiladi($birth_date);
                            if($success)
                            {
                                if($birth_date_ar) $stdnObj->set("birth_date",$birth_date_ar);
                                if($birth_date_en) $stdnObj->set("birth_date_en",$birth_date_en);
                            }
                        }

                        if((!$stdnObj->getVal("mobile")) and $student_mobile and $updateStudent)
                        {
                            $stdnObj->set("mobile",$student_mobile);
                        }

                        if((!$stdnObj->getVal("parent_mobile")) and $parent_mobile and $updateStudent)
                        {
                            $stdnObj->set("parent_mobile",$parent_mobile);
                        }

                        
                        /* attribute capacity doesn't exist in strcuture of this class : Student
                        if((!$stdnObj->getVal("capacity")) and $capacity and $updateStudent)
                        {
                            $stdnObj->set("capacity",$capacity);
                        }*/

                        list($parent_idn_correct, $parent_idn_type_id) = AfwFormatHelper::getIdnTypeId($parent_idn);
                        if($parent_idn_correct or $acceptBadIdns)
                        {
                            if((!$stdnObj->getVal("parent_idn")) and $parent_idn and $updateStudent)
                            {
                                $stdnObj->set("parent_idn",$parent_idn);
                                $stdnObj->set("parent_idn_type_id",$parent_idn_type_id);
                            }
                        }

                        if(!$stdnObj->getVal("country_id") and $nationailty and $updateStudent)
                        {
                            list($country_id,) = Country::getCountryIdFromName($nationailty);
                            $stdnObj->set("country_id",$country_id);
                        }

                        if(!$level) $level = $stdnObj->getVal("level");
                        elseif((!$stdnObj->getVal("level")) and $updateStudent)
                        {
                            $stdnObj->set("level",$level);
                        }

                        if(!$eval) $eval = $stdnObj->getVal("eval");
                        elseif((!$stdnObj->getVal("eval")) and $updateStudent)
                        {
                            $stdnObj->set("eval",$eval);
                        }

                        if($stdnObj->reallyUpdated())
                        {
                            $stdnObj->commit();
                        }

                        if(!$capacity) $capacity = 0;
                        if(!$moral) $moral = 0;

                        $scanObj = Scandidate::loadByMainIndex($this->getVal("school_id"), $this->getVal("year"), $stdnObj->id, $level, $eval, $capacity, $moral, $class_name, $create_obj_if_not_found=true);
                        if($scanObj)
                        {
                            $success_upload = true;
                            $success_cnt++;
                            if($scanObj->is_new) $info_arr[] = $scanObj->tm("created candidate")." : ".$stdnObj->getDisplay($lang);
                            else $info_arr[] = $scanObj->tm("updated candidate")." : ".$stdnObj->getDisplay($lang);
                            unset($idnList[$krow]);
                        }
                        else
                        {
                            $error_arr[] = $this->tm("failed to created candidate")." : ".$stdnObj->getDisplay($lang);
                        }
                        
                    }
                    else
                    {
                        $war_arr[] = $idn. " : " . $this->tm("no student found with this identity number");
                    }
                    
                }
                else
                {
                    $error_arr[] = $idn. " : " . $this->tm("is incorrect identity number");
                }
            }
            else
            {
                $error_arr[] =  "($idn/$student_mobile) : " . $this->tm("is empty identity number or bad mobile number");
            }
            if($success_upload) $info_arr[] = "مترشح [$cnt] : $idn تم استيراده بنجاح";
            else $error_arr[] = "مترشح [$cnt] : $idn/$student_mobile فشل استيراده";

            
        }

        $info_arr[] = "$success_cnt مترشح تم استيرادهم بنجاح";

        $this->pbmethod_main_param = implode("\n",$idnList);

        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
        $nb_queries_executed = $old_nb_queries_executed;

        return self::pbm_result($error_arr,$info_arr,$war_arr);
    }

    public function getShortDisplay($lang = 'ar')
    {
        $data = explode("-",$this->getVal("school_year_name_$lang"));
        unset($data[0]);
        return implode("-",$data);
    }

    public function getDisplay($lang = 'ar')
    {
        $data = $this->getShortDisplay($lang);
        list($data2, $link2) = $this->displayAttribute('school_id');
        return $data . " ($data2)";
    }

    public function getDetailedDisplay($lang = 'ar')
    {
        return $this->getDisplay($lang);
    }


    public function list_of_school_year_type()
    {
        $arr_list = [];
        $arr_list[1] = "سنة دراسية";
        $arr_list[2] = "فصل دراسي";
        $arr_list[3] = "دورة";

        return $arr_list;
    }

    public function list_of_year()
    {
        
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

    public function genereCopyOfSY($lang = 'ar', $testMode = true)
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', 'no user connected'];
        }

        $file_dir_name = dirname(__FILE__);
        $db = $this->getDatabase();

        $old_school_year_id = $this->getId();

        $school = $this->get('school_id');
        $school_id = $school->getId();
        //$group_num = $school->getVal('group_num');
        $hijri_year = $this->getVal('year');
        $hijri_year_p_1 = $hijri_year + 1;
        // check if next year does not exist create it

        $next_school_year = new SchoolYear();

        $next_school_year->select('school_id', $school_id);
        $next_school_year->select('year', $hijri_year_p_1);
        if (!$next_school_year->load()) {
            $next_school_year->set('school_id', $school_id);
            $next_school_year->set('year', $hijri_year_p_1);

            $old_school_year_start_hdate = $this->getVal(
                'school_year_start_hdate'
            );
            $old_school_year_end_hdate = $this->getVal('school_year_end_hdate');
            $old_admission_start_hdate = $this->getVal('admission_start_hdate');
            $old_admission_end_hdate = $this->getVal('admission_end_hdate');

            $new_school_year_start_hdate =
                intval($old_school_year_start_hdate) + 10000 . '';
            $new_school_year_end_hdate =
                intval($old_school_year_end_hdate) + 10000 . '';
            $new_admission_start_hdate =
                intval($old_admission_start_hdate) + 10000 . '';
            $new_admission_end_hdate =
                intval($old_admission_end_hdate) + 10000 . '';

            $next_school_year->set(
                'school_year_start_hdate',
                $new_school_year_start_hdate
            );
            $next_school_year->set(
                'school_year_end_hdate',
                $new_school_year_end_hdate
            );
            $next_school_year->set(
                'admission_start_hdate',
                $new_admission_start_hdate
            );
            $next_school_year->set(
                'admission_end_hdate',
                $new_admission_end_hdate
            );

            $next_school_year->insert($school_id * 10000 + $hijri_year_p_1);
        }

    }
    
    public function copySettingsFrom($lang = 'ar', $oldSY, $testMode=true)
    {
        return $oldSY->copySettingsTo($lang, $this, $testMode);
    }
    
    public function copySettingsTo($lang = 'ar', $newSY, $testMode=true)
    {
        /*
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', 'no user connected'];
        }

        $war_arr = [];
        $inf_arr = [];
        $err_arr = [];
        $tech_arr = [];

        $new_school_year_id = $newSY->getId();
        $new_school_year_year = $newSY->valYear();

        $newSY->set("classes_names", $this->getVal("classes_names"));
        
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        // $newSY->set("classes_names", $this->getVal("classes_names"));
        $newSY->commit();

        $old_school_year_id = $this->getId();

        $db = $this->getDatabase();

        // // require_once("$file_dir_name/school_scope.php");
        if (!$testMode) {
            $ss = new SchoolScope();
            $ss->select('school_year_id', $new_school_year_id);
            $ss->select('active', 'Y');
            if ($ss->count() > 0) {
                $war_arr[] = "يجب  حذف مجال عمل المنشأة الذي تم انشاؤه لهذه السنة الدراسية $new_school_year_id  قبل نسخ القديم إليها";                
            }
        } else {
            $sql_del-ete = "del ete from $db.school_scope where school_year_id = $new_school_year_id";
            list($resultDel, $row_count, $ss_deleted_rows_count) = self::executeQuery($sql_delete);            
            $inf_arr[] = "تم مسح $ss_deleted_rows_count من سجلات مجالات العمل القديمة";
        }

        $sql_insert = "insert into $db.school_scope(school_year_id,school_level_id,level_class_id,class_nb,sdepartment_id,created_by,created_at,active,version,update_groups_mfk,delete_groups_mfk,display_groups_mfk)
                                select $new_school_year_id,school_level_id,level_class_id,class_nb,sdepartment_id,$me,now(),active,0,update_groups_mfk,delete_groups_mfk,display_groups_mfk
                                from $db.school_scope
                                where school_year_id = $old_school_year_id
                                  and active = 'Y'";

        list($resultInsert, $row_count, $ss_inserted_rows_count) = self::executeQuery($sql_insert);
        $inf_arr[] = "تم نسخ $ss_inserted_rows_count من سجلات مجالات العمل";

        // require_once("$file_dir_name/school_class.php");

        if (!$testMode) {
            $sc = new SchoolClass();
            $sc->select('school_year_id', $new_school_year_id);
            $sc->select('active', 'Y');
            if ($sc->count() > 0) {
                $war_arr[] = 'يجب  حذف الحلقات  التي  تم انشاؤها  لهذه السنة الدراسية الجديدة  قبل نسخ القديم إليها';
            }
        } else {
            $sql_delete = "delete from $db.school_class where school_year_id = $new_school_year_id";
            list($resultDel, $row_count, $sc_deleted_rows_count) = self::executeQuery($sql_delete);                        
            $inf_arr[] = "تم مسح $sc_deleted_rows_count من الحلقات القديمة";
        }

        $sql_insert = "insert into $db.school_class(school_year_id,level_class_id,class_name,room_id,created_by,created_at,active,version,update_groups_mfk,delete_groups_mfk,display_groups_mfk)
                                select $new_school_year_id,level_class_id,class_name,room_id,$me,now(),active,0,update_groups_mfk,delete_groups_mfk,display_groups_mfk
                                from $db.school_class
                                where school_year_id = $old_school_year_id
                                  and active = 'Y'";

        list($result, $row_count, $sc_inserted_rows_count) = self::executeQuery($sql_insert);                                              
        $inf_arr[] = "تم نسخ $sc_inserted_rows_count من سجلات الحلقات";
        

        // require_once("$file_dir_name/course_sched_item.php");

        if (!$testMode) {
            $csi = new CourseSchedItem();
            $csi->select('school_year_id', $new_school_year_id);
            $csi->select('active', 'Y');
            if ($csi->count() > 0) {
                $war_arr[] = 'يجب  حذف الجداول الأسبوعية  التي  تم انشاؤها  لهذه السنة الدراسية الجديدة  قبل نسخ القديم إليها';
            }
        } else {
            $sql_delete = "delete from $db.course_sched_item where school_year_id = $new_school_year_id";
            list($result, $row_count, $sci_deleted_rows_count) = self::executeQuery($sql_delete);                                              
            $inf_arr[] = "تم مسح $sci_deleted_rows_count من الجداول الأسبوعية القديمة";
        }

        $sql_insert = "insert into $db.course_sched_item
                                (`school_year_id`,`level_class_id`, `class_name`,`wday_id`,`session_order`,            
                                      `session_start_time`,`session_end_time`,`course_id`, 
                                      active, version, created_by, created_at, updated_by, updated_at,
                                      update_groups_mfk,delete_groups_mfk,display_groups_mfk) 
                            select $new_school_year_id,level_class_id,class_name,wday_id,session_order,
                                      session_start_time,session_end_time,course_id,
                                      active, 0, $me,now(), $me,now(),
                                      update_groups_mfk,delete_groups_mfk,display_groups_mfk
                            from $db.course_sched_item
                                 where school_year_id = $old_school_year_id
                                   and active = 'Y'";

        list($result, $row_count, $csi_inserted_rows_count) = self::executeQuery($sql_insert);                                              
        $inf_arr[] = "تم نسخ $csi_inserted_rows_count من سجلات الجداول الأسبوعية";

        list($err, $inf, $war) = $newSY->genereSchoolClassCourses($lang, $old_school_year_id);
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;
        if($war) $war_arr[] = $war;

        $inf_arr[] = "تم انشاء $ss_inserted_rows_count سجلات مجال عمل المنشأة للسنة الجديدة" ;
        $inf_arr[] = "تم انشاء $sc_inserted_rows_count من الحلقات الدراسية  للسنة الجديدة";
        $inf_arr[] = "تم انشاء $csi_inserted_rows_count من عتاصر الجداول  الدراسية  للسنة الجديدة";

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);*/
    }

    public function bootstrapWork($lang = 'ar')    
    {
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];
        // $this_disp = $this->getDisplay($lang);
        list($err,$inf,$war,$tech) = $this->genereAllCourseSessions($lang);
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;
        if($war) $war_arr[] = $war;
        if($tech) $tech_arr[] = $tech;

        list($err,$inf,$war,$tech) = $this->updateAllCourseSessionsStatus($lang);
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;
        if($war) $war_arr[] = $war;
        if($tech) $tech_arr[] = $tech;

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    

    public function genereAllCourseSessions($lang = 'ar', $testMode = true)
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', 'no user connected'];
        }

        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];
        

        $file_dir_name = dirname(__FILE__);
        $db = $this->getDatabase();

        $this_id = $this->getId();
        $this_year = $this->valYear();

        $school = $this->get('school_id');
        $school_id = $school->getId();
        $group_num = $school->getVal('group_num');
        $hijri_year = $this->getVal('year');

        // require_once("$file_dir_name/school_class.php");

        $sc = new SchoolClass();
        $sc->select('school_year_id', $this_id);
        $sc->select('active', 'Y');

        $sc_list = $sc->loadMany();
        $err_sc_gen = '';
        foreach ($sc_list as $sc_id => $sc_item) {
            $sc_item_disp = $sc_item->getDisplay($lang);

            list($err,$inf,$war,$tech) = $sc_item->genereCourseSessions($lang);
            if($err) $err_arr[] = "$sc_item_disp : ".$err;
            if($inf) $inf_arr[] = "$sc_item_disp : ".$inf;
            if($war) $war_arr[] = "$sc_item_disp : ".$war;
            if($tech) $tech_arr[] = $tech;
        }

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function updateAllCourseSessionsStatus($lang = 'ar')
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }
        $school = $this->het('school_id');
        if (!$school) {
            return ['no school for this SY !!!! very strange', ''];
        }
        $options = [];

        $options["min_before_open_course_session"] = $school->getVal("sp1");
        $school_id = $school->getId();
        $levelsTemplateObj = $school->het("levels_template_id");
        if(!$levelsTemplateObj)
        {
            return ['no level template for this school', ''];
        }

        $levels_template_id = $levelsTemplateObj->id;

        $schoolLevelList = $levelsTemplateObj->get("schoolLevels");
        foreach($schoolLevelList as $schoolLevelItem)
        {
            $levelClassList = $schoolLevelItem->get("levelClassList");

            foreach($levelClassList as $levelClassItem)
            {
                $disp = $schoolLevelItem->getDisplay($lang).">".$levelClassItem->getDisplay($lang);
                list($err,$inf,$war,$tech) = $this->updateAllCourseSessionsStatusBySchoolLevelAndClassLevel($lang,
                        $me,
                        $school_id,
                        $levels_template_id,
                        $schoolLevelItem->getVal("school_level_order"),
                        $levelClassItem->getVal("level_class_order"),
                        $options
                );

                if($err) $err_arr[] = "$disp : ".$err;
                if($inf) $inf_arr[] = "$disp : ".$inf;
                if($war) $war_arr[] = "$disp : ".$war;
                if($tech) $tech_arr[] = $tech;
            }
        }

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function updateAllCourseSessionsStatusBySchoolLevelAndClassLevel($lang,
                        $me,
                        $school_id,
                        $levels_template_id,
                        $school_level_order,
                        $level_class_order,
                        $options=[]
                )
    {
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_nb_queries_executed = $nb_queries_executed;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;

        
        $today = date("Y-m-d");
        $day_before_yesterday = AfwDateHelper::shiftGregDate('',-2);
        $min_before_open_course_session = $options["min_before_open_course_session"];
        if(!$min_before_open_course_session) $min_before_open_course_session = 30;
        $date_time_cursor_to_open_course_session = AfwDateHelper::addDatetimeToGregDatetime('',0,0,0,0,$min_before_open_course_session,0);
        $date_time_cursor_to_current_course_session = AfwDateHelper::addDatetimeToGregDatetime('',0,0,0,0,30+$min_before_open_course_session,0);
        
        list($date_cursor_to_open_course_session, $time_cursor_to_open_course_session) = explode(" ", $date_time_cursor_to_open_course_session);
        if($date_cursor_to_open_course_session>$today) $time_cursor_to_open_course_session = "23:59:59";
        list($date_cursor_to_current_course_session, $time_cursor_to_current_course_session) = explode(" ", $date_time_cursor_to_current_course_session);
        if($date_cursor_to_current_course_session>$today) $time_cursor_to_current_course_session = "23:59:59";

        $cssObj = new CourseSession();
        $cssObj->select("school_id",$school_id);
        $cssObj->select("levels_template_id",$levels_template_id);
        $cssObj->select("school_level_order",$school_level_order);
        $cssObj->select("level_class_order",$level_class_order);
        $cssObj->select("session_date",$today);
        $cssObj->selectIn("session_status_id", [0, SessionStatus::$coming_session]);
        
        $cssObj->where("session_start_time < '$time_cursor_to_current_course_session'");

        $cssObj->set("session_status_id", SessionStatus::$current_session);
        $nb_cur = $cssObj->update(false);

        unset($cssObj);

        $cssObj = new CourseSession();
        $cssObj->select("school_id",$school_id);
        $cssObj->select("levels_template_id",$levels_template_id);
        $cssObj->select("school_level_order",$school_level_order);
        $cssObj->select("level_class_order",$level_class_order);
        $cssObj->where("session_date < '$today'",);
        $cssObj->selectIn("session_status_id", [0, SessionStatus::$coming_session, SessionStatus::$current_session]);

        $cssObj->set("session_status_id", SessionStatus::$standby_session);
        $nb_sby = $cssObj->update(false);

        unset($cssObj);

        $cssObj = new CourseSession();
        $cssObj->select("school_id",$school_id);
        $cssObj->select("levels_template_id",$levels_template_id);
        $cssObj->select("school_level_order",$school_level_order);
        $cssObj->select("level_class_order",$level_class_order);
        $cssObj->where("session_date < '$day_before_yesterday'",);
        $cssObj->selectIn("session_status_id", [0, SessionStatus::$coming_session, SessionStatus::$current_session, SessionStatus::$standby_session]);

        $cssObj->set("session_status_id", SessionStatus::$missed_session);
        $nb_mss = $cssObj->update(false);

        // open sessions to be opened
        $nb_opened=0;
        $cssObj = new CourseSession();
        $cssObj->select("school_id",$school_id);
        $cssObj->select("levels_template_id",$levels_template_id);
        $cssObj->select("school_level_order",$school_level_order);
        $cssObj->select("level_class_order",$level_class_order);
        $cssObj->select("session_date",$today);
        $cssObj->select("session_status_id", SessionStatus::$current_session);        
        $cssObj->where("and session_start_time < '$time_cursor_to_open_course_session'");
        $sql_toopen = $cssObj->getSQLMany();
        $cssList = $cssObj->loadMany();
        $nb_toopen = count($cssList);
        foreach($cssList as $cssItem)
        {
            list($err,$inf) = $cssItem->openSession($lang);
            if(!$err) $nb_opened++;
        }

        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
        $nb_queries_executed = $old_nb_queries_executed;

        return ["", 
                "o$nb_opened/to$nb_toopen ".self::tt("sessions become opened and",$lang,"sis")."<br>\n [$min_before_open_course_session/$date_time_cursor_to_open_course_session] <br>\n $nb_sby ".self::tt("sessions become stand by and",$lang,"sis")."<br>\n $nb_mss ".self::tt("sessions become missed",$lang,"sis"),
                "",
                $sql_toopen
            ];

    }

    public function applyConditions($lang = 'ar', $recalcEvals=true)
    {
        global $MODE_SQL_PROCESS_LOURD;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;
        if($recalcEvals) $this->calcGeneralEvaluation($lang,'pendingCandidateList');
        $schoolObj = $this->hetSchool();
        $target = 'pendingCandidateList';
        $candidateList = $this->get($target);
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];
        $nb = count($candidateList);
        foreach ($candidateList as $acandidateItem) 
        {
            list($err,$inf,$war,$tech) = $acandidateItem->applyCondition($lang, $schoolObj);
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
            if($war) $war_arr[] = $war;
            if($tech) $tech_arr[] = $tech;
        }
        if(count($err_arr)==0) $inf_arr[] = "تم تطبيق الشروط بنجاح على $nb مترشح"; // $this->tm

        list($err,$inf,$war,$tech) = $this->distributeAcceptedCandidates($lang,false);
        if($err) $err_arr[] = $err;
        if($inf) $inf_arr[] = $inf;
        if($war) $war_arr[] = $war;
        if($tech) $tech_arr[] = $tech;

        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;


        return self::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);

        
    }


    public function calcGeneralEvaluationForAll($lang = 'ar', $accepted=true, $pending=true)
    {
        $err_arr = [];
        $inf_arr = [];
        $accepted_err = "";
        $accepted_inf = "";
        $pending_err = "";
        $pending_inf = "";


        if($accepted) list($accepted_err, $accepted_inf) = $this->calcGeneralEvaluation($lang);
        if($pending) list($pending_err, $pending_inf) = $this->calcGeneralEvaluation($lang,'pendingCandidateList');

        if($accepted_err) $err_arr[] = $accepted_err;
        if($pending_err)  $err_arr[] = $pending_err;
        if($accepted_inf) $inf_arr[] = $accepted_inf;
        if($pending_inf)  $inf_arr[] = $pending_inf;

        return self::pbm_result($err_arr,$inf_arr);
        
    }
    
    public function calcGeneralEvaluation($lang = 'ar', $target='acceptedCandidateList')
    {
        $schoolObj = $this->hetSchool();
        $candidateList = $this->get($target);
        $err_arr = [];
        $inf_arr = [];
        if(count($candidateList)==0) $err_arr[] = "no candidates for target $target";
        foreach ($candidateList as $acandidateItem) 
        {
            list($err,$inf) = $acandidateItem->calcGeneralEvaluation($lang, $schoolObj);
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
        }

        return self::pbm_result($err_arr,$inf_arr);
    }


    public function cancelApplyConditions($lang = 'ar') 
    {
        // why ?
        // self::deleteGeneratedSchoolClasses();
        $target = 'acceptedCandidateList';
        $candidateList = $this->get($target);

        foreach ($candidateList as $acandidateItem) 
        {
            list($err,$inf) = $acandidateItem->cancelApplyCondition($lang);
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
        }

        return self::pbm_result($err_arr,$inf_arr);
    }

    /*
    public function goAndDistributeAcceptedCandidates($lang = 'ar', $keep_class_name_if_decided=true) 
    {
        return $this->simulateDistributeAcceptedCandidates($lang); // , $commit=true, $keep_class_name_if_decided
    }

    public function simulateDistributeAcceptedCandidates($lang = 'ar')  // , $commit=false, $keep_class_name_if_decided=true
    {
        $acceptedCandidateList = $this->get('acceptedCandidateList');
        $nb_assign = 0;
        $arr_assign = [];
        $arr_warnings = [];
        
        foreach ($acceptedCandidateList as $acceptedCandidateItem) {
            $acceptedCandidateItem->assignMeToSchoolClass($lang);
             this code below make big problem he changed me class_name of all previousley accepted
               and manually assigned to school classes
            if($keep_class_name_if_decided)
            {
                $sugg_class_name = trim($acceptedCandidateItem->getVal('class_name'));
            }
            else
            {
                $sugg_class_name = "";
            }
            list($scObj, $log) = $this->findAvailableSchoolClass($acceptedCandidateItem->getVal('level_class_id'),$sugg_class_name);
            if ($scObj) {
                if($commit)
                {
                    $acceptedCandidateItem->set('class_name', $scObj->getVal('class_name'));
                    $acceptedCandidateItem->commit();
                }
                
                $nb_assign++;
                $disEval = $this->getVal("distrib");
                $arr_assign[] = $acceptedCandidateItem->getShortDisplay($lang) .  " ($disEval) إلى " . $scObj->getDisplay($lang);
            }
            else
            {
                $arr_warnings[] = "لم نجد صف متاح للمستوى : ".$acceptedCandidateItem->showAttribute('level_class_id')." [$sugg_class_name] $log";
            }
        } 

        return [
            '',
            "$nb_assign حالة تمت معالجتها واسنادها كالتالي : <br>\n<br>\n" .
            implode("<br>\n", $arr_assign),
            implode("<br>\n", $arr_warnings)
        ];
    }
*/

    public function updateStudentWithAcceptedCandidatesData($lang = 'ar')
    {
        return $this->distributeAcceptedCandidates($lang, $redistribute = false, $updateData = true); 
    }

    public function distributeAcceptedCandidates(
        $lang = 'ar',
        $redistribute = false,
        $updateData = false,
        // if for specific school class
        $levels_template_id=null,
        $school_level_order=null,
        $level_class_order=null,
        $class_name=null
    ) 
    {
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $old_nb_queries_executed = $nb_queries_executed;
        $MODE_SQL_PROCESS_LOURD = true;
        $acceptedCandidateList = $this->get('acceptedCandidateList');
        $errors_arr = array();
        $infos_arr = array();
        $warns_arr = array();
        $default_genre_id = 1;
        foreach ($acceptedCandidateList as $acceptedCandidateItem) 
        {
            if((!$class_name) or (
                ($levels_template_id == $acceptedCandidateItem->calc('levels_template_id')) and
                ($school_level_order == $acceptedCandidateItem->calc('school_level_order')) and
                ($level_class_order == $acceptedCandidateItem->calc('level_class_order')) and
                ($class_name == $acceptedCandidateItem->getVal('class_name'))

                )
            )
            {
                list($error,$info, $warn) = $acceptedCandidateItem->assignMeToSchoolClass($lang, $updateData, $default_genre_id, $redistribute);
                if($error) $errors_arr[] = $error;
                if($info) $infos_arr[] = $info;
                if($warn) $warns_arr[] = $warn;
            }
        }

        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
        $nb_queries_executed = $old_nb_queries_executed;

        return self::pbm_result($errors_arr, $infos_arr, $warns_arr);
    }

    public function findAvailableSchoolClass($level_class_id, $class_name="", $create_obj_if_not_found=false)
    {
        if(!$class_name)
        {
            $obj = new SchoolClass();
            $obj->select('school_year_id', $this->id);
            $obj->select('level_class_id', $level_class_id);
            $obj->select('active', 'Y');
            $objList = $obj->loadMany();

            foreach ($objList as $objItem) {
                list($needed_stdn, $room_comment) = $objItem->getPlacesInfo();
                if ($needed_stdn > 0) {
                    return array($objItem, "done");
                }
            }

            return array(null, $obj->getSQLMany());
        }
        else
        {
            
            $objSC = SchoolClass::loadByMainIndex($this->id,$level_class_id,$class_name,$create_obj_if_not_found);
            if($create_obj_if_not_found and $objSC)
            {
                $schoolObj = $this->het('school_id');
                if($schoolObj)
                {
                    $objSC->set('study_program_id', $schoolObj->getVal('study_program_id'));
                    $objSC->commit();
                }
            }
            
            if(!$objSC) $objSCLog = "school_year_id=".$this->id." and level_class_id=$level_class_id and class_name='$class_name' and active='Y'";
            else $objSCLog = "";
            return array($objSC, $objSCLog);
        }


        
        
    }

    public function synchStudentFiles($lang="ar")
    {
        global $MODE_SQL_PROCESS_LOURD;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;

        $sf = new StudentFile;
        $sf->select("school_id", $this->getVal("school_id"));
        $this_year = $this->valYear();
        $sf->select('year', $this_year);
        $sf->select('active', 'Y');
        // die($sf->getSQLMany());
        $sfList = $sf->loadMany();
        $sfListCount = count($sfList);
        $nb = 0;
        foreach($sfList as $sfItem)
        {
            $objStudent = $sfItem->het("student_id");            
            if($objStudent) {
                $objStudent->fixMyData($lang);
                $sfItem->syncSameFieldsWith($objStudent,true, true);
                $nb++;
            }
        }

        $$MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;

        return ["", "تم تحديث $nb/$sfListCount ملف"];
    }

    public function copyStudentFilesFromPreviousYear(
        $lang = 'ar',
        $testMode = true
    ) {
        /* to see
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $file_dir_name = dirname(__FILE__);
        $db = $this->getDatabase();

        $this_id = $this->getId();
        $this_year = $this->valYear();

        $school = $this->het('school_id');
        if (!$school) {
            return ['no school for this SY !!!! very strange', ''];
        }
        $school_id = $school->getId();
        $group_num = $school->getVal('group_num');
        $old_hijri_year = $this_year - 1;

        // require_once("$file_dir_name/student_file.php");

        if ($testMode) {
            $sf = new StudentFile();
            $sf->select('school_id', $school_id);
            $sf->select('year', $this_year);
            $sf->select('active', 'Y');
            if ($sf->count() > 0) {
                return [
                    'يوجد حاليا ملفات طلاب للسنة الجديدة لا يمكن نقل الملفات من السنة الماضية',
                    '',
                ];
            }

            // for test copy also StudentFile of last year
            $sql_insert = "insert into $db.student_file(student_num, student_id,school_class_id,school_id,year,level_class_id,class_name,student_file_status_id,created_by,created_at,active,version) 
                                   select sf_old.student_num,  sf_old.student_id, sc_new.id, school_id, $this_year, sc_new.level_class_id, sc_new.class_name,sf_old.student_file_status_id,$me,now(),'Y',0 
                                   from $db.student_file sf_old
                                     inner join $db.school_class sc_old on sc_old.id = sf_old.school_class_id
                                     inner join $db.school_class sc_new on sc_new.school_year_id = $this_id
                                                                    and sc_new.level_class_id = sc_old.level_class_id
                                                                    and sc_new.class_name = sc_old.class_name
                                   where sf_old.school_id = $school_id
                                     and sf_old.year = '$old_hijri_year'
                                     and sf_old.active = 'Y'";
            list($result, $row_count, $sf_inserted_rows_count) = self::executeQuery($sql_insert);                                              
            
        } else {
            $sf_inserted_rows_count = 0;
        }

        $sf = new StudentFile();
        $sf->select('school_id', $school_id);
        $sf->select('year', $this_year);
        $sf->select('active', 'Y');
        if ($sf->count() > 0) {
            list($err_gen_ss, $info_gen_ss) = $this->genereAllStudentSessions(
                $lang
            );
        } else {
            $info_gen_ss = '';
        }

        $log_info =
            "تم انشاء $sf_inserted_rows_count من ملفات الطلاب للسنة الجديدة للتجربة" .
            "\n<br>";
        $log_info .= $info_gen_ss;

        return [$err_gen_ss, $log_info];
        */
    }

    public function getCurrHdayNum()
    {
        $today_hday = $this->getCurrHday();
        if (!$today_hday) {
            $today_hday_num = 1; // @todo this to review dangereous only for test
            // return array("no current Hday", "no current Hday");
        } else {
            $today_hday_num = $today_hday->getVal('hday_num');
        }

        return $today_hday_num;
    }

    // @todo need to copy this function below and adapt to genere only today studentSession if no student_session is filled at yet (morning early)

    public function genereAllStudentSessions($lang = 'ar', $genere_course_sessions=true, $update_course_sessions=true)
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $sy_id = $this->getId();
        $school = $this->het('school_id');
        if (!$school) {
            return ['no school for this SY !!!! very strange', ''];
        }
        $school_id = $school->getId();
        $levelsTemplateObj = $school->het("levels_template_id");
        if(!$levelsTemplateObj)
        {
            return ['no level template for this school', ''];
        }

        
        $levels_template_id = $levelsTemplateObj->id;
        $year = $this->getVal('year');
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];
        if($genere_course_sessions)
        {
            list($err,$inf,$war,$tech) = $this->genereAllCourseSessions($lang = 'ar');
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
            if($war) $war_arr[] = $war;
            if($tech) $tech_arr[] = $tech;
        }
        
        $schoolLevelList = $levelsTemplateObj->get("schoolLevels");
        foreach($schoolLevelList as $schoolLevelItem)
        {
            $levelClassList = $schoolLevelItem->get("levelClassList");

            foreach($levelClassList as $levelClassItem)
            {
                $disp = $schoolLevelItem->getDisplay($lang).">".$levelClassItem->getDisplay($lang);
                list($err,$inf,$war,$tech) = $this->genereStudentSessionsBySchoolLevelAndClassLevel($lang,
                        $me,
                        $school_id,
                        $year,
                        $levels_template_id,
                        $schoolLevelItem->getVal("school_level_order"),
                        $levelClassItem->getVal("level_class_order")
                );

                if($err) $err_arr[] = "$disp : ".$err;
                if($inf) $inf_arr[] = "$disp : ".$inf;
                if($war) $war_arr[] = "$disp : ".$war;
                if($tech) $tech_arr[] = $tech;
            }
        }

        if($update_course_sessions)
        {
            list($err,$inf,$war,$tech) = $this->updateAllCourseSessionsStatus($lang = 'ar');
            if($err) $err_arr[] = $err;
            if($inf) $inf_arr[] = $inf;
            if($war) $war_arr[] = $war;
            if($tech) $tech_arr[] = $tech;
        }

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);


    }


    public function genereStudentSessions(
        $lang,
        $level_class_id,
        $class_name,
        $student_id
    )
    {
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_nb_queries_executed = $nb_queries_executed;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;
                
        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $school = $this->het('school_id');
        if (!$school) {
            return ['no school for this SY !!!! very strange', ''];
        }
        $school_id = $school->getId();
        $levelsTemplateObj = $school->het("levels_template_id");
        if(!$levelsTemplateObj)
        {
            return ['no level template for this school', ''];
        }

        
        $levels_template_id = $levelsTemplateObj->id;
        $year = $this->getVal('year');

        $scopList = $this->get('scop');
        foreach ($scopList as $scopId => $scopObj) {
            if($level_class_id == $scopObj->getVal('level_class_id'))
            {
                $level_class_obj = $scopObj->het('level_class_id');
                $school_level_obj = $scopObj->het('school_level_id');
                $level_class_order = $level_class_obj->getVal('level_class_order');
                $school_level_order = $school_level_obj->getVal('school_level_order');
                list($err,$war,$inf, $tech) = $this->genereStudentSessionsBySchoolLevelAndClassLevel($lang,$me,$school_id,$year,$levels_template_id,
                            $school_level_order,$level_class_order,$class_name,$student_id);
                if($err) $err_arr[] = $err;
                if($inf) $inf_arr[] = $inf;
                if($war) $war_arr[] = $war;
                if($tech) $tech_arr[] = $tech;
            }

            
        }

        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
        $nb_queries_executed = $old_nb_queries_executed;

        return self::pbm_result($err_arr,$inf_arr,$war_arr, $sep = "<br>\n", $tech_arr);
    }

    public function genereStudentSessionsBySchoolLevelAndClassLevel(
        $lang,
        $me,
        $school_id,
        $year,
        $levels_template_id,
        $school_level_order,
        $level_class_order,
        $class_name = '',
        $student_id = 0,
        $past_offset = -3,
        $future_offset = 2,
        $session_date_fixed = null,
        $session_order = 0        
    ) 
    {
        if($session_date_fixed)
        {
            $session_date_start = $session_date_fixed;
            $session_date_end = $session_date_fixed;
        }
        else
        {
            $session_date_start = AfwDateHelper::shiftGregDate('',$past_offset);
            $session_date_end = AfwDateHelper::shiftGregDate('',$future_offset);
        }

        $db = $this->getDatabase();
        
        $sql_insert = "insert into $db.student_session(created_by,  created_at, updated_by, updated_at, active, version, 
                                                        school_id, year, semester, levels_template_id, school_level_order, level_class_order, class_name, 
                                                        session_date, session_order, course_id, student_id,
                                                        mainwork_start_book_id,homework_start_book_id,homework2_start_book_id,
                                                        mainwork_end_book_id,homework_end_book_id,homework2_end_book_id,
                                                        mainwork_start_part_id, mainwork_start_chapter_id, mainwork_start_paragraph_num, mainwork_start_page_num,
                                                        mainwork_end_part_id, mainwork_end_chapter_id, mainwork_end_paragraph_num, mainwork_end_page_num,
                                                        homework_start_part_id, homework_start_chapter_id, homework_start_paragraph_num, homework_start_page_num,
                                                        homework_end_part_id, homework_end_chapter_id, homework_end_paragraph_num, homework_end_page_num, 
                                                        homework2_start_part_id, homework2_start_chapter_id, homework2_start_paragraph_num, homework2_start_page_num,
                                                        homework2_end_part_id, homework2_end_chapter_id, homework2_end_paragraph_num, homework2_end_page_num,
                                                        moral_rank_id, interest_rank_id )
                        select $me, now(), $me, now(), sf.active, 0 as version, 
                                cs.school_id, cs.year, cs.semester, cs.levels_template_id, cs.school_level_order, cs.level_class_order, cs.class_name, 
                                cs.session_date, cs.session_order, cs.course_id, sf.student_id, 
                                sfc.mainwork_start_book_id, sfc.homework_start_book_id, sfc.homework2_start_book_id,                                
                                sfc.mainwork_end_book_id, sfc.homework_end_book_id, sfc.homework2_end_book_id,                                
                                sfc.mainwork_start_part_id, sfc.mainwork_start_chapter_id, sfc.mainwork_start_paragraph_num,  sfc.mainwork_start_page_num,  
                                sfc.mainwork_end_part_id,   sfc.mainwork_end_chapter_id,   sfc.mainwork_end_paragraph_num,  sfc.mainwork_end_page_num, 
                                sfc.homework_start_part_id, sfc.homework_start_chapter_id, sfc.homework_start_paragraph_num,  sfc.homework_start_page_num,  
                                sfc.homework_end_part_id, sfc.homework_end_chapter_id, sfc.homework_end_paragraph_num,  sfc.homework_end_page_num, 
                                sfc.homework2_start_part_id, sfc.homework2_start_chapter_id, sfc.homework2_start_paragraph_num,  sfc.homework2_start_page_num,  
                                sfc.homework2_end_part_id, sfc.homework2_end_chapter_id, sfc.homework2_end_paragraph_num,  sfc.homework2_end_page_num,
                                4, 4
                        from $db.course_session cs 
                               inner join $db.student_file sf 
                                            on cs.school_id = sf.school_id 
                                            and cs.year = sf.year 
                                            and cs.levels_template_id = sf.levels_template_id 
                                            and cs.school_level_order = sf.school_level_order 
                                            and cs.level_class_order = sf.level_class_order
                                            and cs.class_name = sf.class_name
                                            and sf.active = 'Y'
                                inner join $db.student_file_course sfc
                                            on cs.school_id = sfc.school_id 
                                            and cs.year = sfc.year 
                                            and cs.levels_template_id = sfc.levels_template_id 
                                            and cs.school_level_order = sfc.school_level_order 
                                            and cs.level_class_order = sfc.level_class_order
                                            and sf.student_id = sfc.student_id
                                            and cs.course_id = sfc.course_id
                                            and sfc.active = 'Y'
                                left join $db.student_session ss on
                                        ss.school_id = cs.school_id and
                                        ss.levels_template_id = cs.levels_template_id and
                                        ss.school_level_order = cs.school_level_order and
                                        ss.level_class_order = cs.level_class_order and
                                        ss.class_name = cs.class_name and
                                        ss.session_date = cs.session_date and
                                        ss.session_order = cs.session_order and
                                        ss.student_id = sf.student_id            
                        where cs.school_id = $school_id
                          and cs.levels_template_id = $levels_template_id
                          and cs.school_level_order = $school_level_order
                          and cs.level_class_order = $level_class_order
                          and cs.year = '$year' 
                          and ('$class_name' = '' or cs.class_name = _utf8'$class_name') 
                          and cs.session_date between '$session_date_start' and '$session_date_end'
                          and ('$session_order' = '0' or cs.session_order = '$session_order')
                          and sf.student_file_status_id = 1
                          and ($student_id = 0 or sf.student_id = $student_id)
                          and cs.course_id > 0
                          and ss.school_id is null";


        list($result, $row_count, $affected_row_count) = self::executeQuery($sql_insert);
        $war_mess = '';
        $info_mess = $this->tm('عدد كشوفات الحضور التي تم توليدها : ',$lang) . $affected_row_count;
        if((!$affected_row_count) and (!$student_id)) $war_mess = 'تعذر توليد كشوفات الحضور لهذا الطالب تأكد من وجود ملفات الطلاب في هذه الحلقة وكذلك وجود سجلات الانجاز'; 

        return ['', $info_mess, $war_mess, $sql_insert];
    }

    public function genereSchoolClassCourses(
        $lang = 'ar',
        $prof_distribution_from_sy = 0,
        $school_class_id = 0
    ) 
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $err_arr = [];
        $inf_arr = [];
        $war_arr = [];
        $tech_arr = [];

        $file_dir_name = dirname(__FILE__);
        $school = $this->het('school_id');
        if(!$school){
            return ['very strange error, no school for this school year', ''];
        }
        $cct_id = $school->getVal('courses_config_template_id');
        $min_rank_id = $school->getVal('min_rank_id');
        if(!$min_rank_id) $min_rank_id = 2;
        $sy_id = $this->getId();
        if (!$prof_distribution_from_sy) {
            
            $school_id = $school->getId();
            $group_num = $school->getVal('group_num');
            $hijri_year = $this->getVal('year');
            $hijri_year_m_1 = $hijri_year - 1;

            $prev_school_year = new SchoolYear();

            $prev_school_year->select('school_id', $school_id);
            $prev_school_year->select('year', $hijri_year_m_1);
            $prev_school_year->load();

            $prof_distribution_from_sy = $prev_school_year->getId();
        }

        $db = $this->getDatabase();
        $now = date("Y-m-d H:i:s");
        // @todo : rafik 20/10/2022
        // here below the from $db.school_scope ss has no sens 
        // not needed and no relation between school_scope and generated school_class_course
        // can and to vbe removed 
        $sql = "insert into $db.school_class_course(school_year_id, level_class_id,class_name,course_id,prof_id,min_rank_id,active,version,created_by,created_at,updated_by,updated_at) 
                select sc.school_year_id, sc.level_class_id, sc.class_name, cci.course_id, 0, $min_rank_id, 'Y', 0, $me, '$now', $me, '$now'
                from $db.school_scope ss 
                   inner join $db.school_class sc on ss.school_year_id = sc.school_year_id and ss.school_year_id = $sy_id and ss.level_class_id = sc.level_class_id 
                   inner join $db.courses_config_item cci on sc.level_class_id = ss.level_class_id 
                          and cci.level_class_id = sc.level_class_id 
                          and cci.session_nb > 0 
                   left join $db.school_class_course scc on scc.school_year_id = sc.school_year_id 
                          and scc.level_class_id = sc.level_class_id 
                          and scc.class_name = sc.class_name 
                          and scc.course_id = cci.course_id 
                where cci.courses_config_template_id = $cct_id 
                  and ($school_class_id = 0 or sc.id = $school_class_id)
                  and scc.id is null";

        list($resultInsert, $row_count, $last_insert_row_count) = self::executeQuery($sql);

        $tech_arr[] = $sql;
         
        
        

        if ($prof_distribution_from_sy) {
            // require_once("$file_dir_name/school_class_course.php");

            $scc = new SchoolClassCourse();
            $scc->select('school_year_id', $sy_id);
            $scc->select('active', 'Y');
            $scc_list = $scc->loadMany();
            $last_prof_update_row_count = 0;
            foreach ($scc_list as $scc_id => $scc_item) {
                $scc_level_class_id = $scc_item->getVal('level_class_id');
                $scc_class_name = $scc_item->getVal('class_name');
                $scc_course_id = $scc_item->getVal('course_id');

                $scc2 = new SchoolClassCourse();
                $scc2->select('school_year_id', $prof_distribution_from_sy);
                $scc2->select('active', 'Y');
                $scc2->select('level_class_id', $scc_level_class_id);
                $scc2->select('class_name', $scc_class_name);
                $scc2->select('course_id', $scc_course_id);

                $scc2_prof_id = $scc2->func('max(prof_id) ');

                if ($scc2_prof_id > 0) {
                    $sql = "update $db.school_class_course scc 
                                            set scc.prof_id = $scc2_prof_id
                                          where scc.id = $scc_id";

                    list($resultUpd, $row_count, $last_upd_row_count) = self::executeQuery($sql);
                    $last_prof_update_row_count += $last_upd_row_count;
                }
            }
        }

        $inf_arr[] = "تم انشاء $last_insert_row_count من المقررات وتم تحديث المدرس لـ : $last_prof_update_row_count مواد دراسية على صفوف مدرسية";

        return self::pbm_result($err_arr,$inf_arr,$war_arr,"<br>\n",$tech_arr);
    }

    public function regenereOnlyComingHdays($lang = 'ar')
    {
        return $this->genereHdays($lang = 'ar', true);
    }

    public function getCurrHday()
    {
        return Hday::getCurrHday($this->getId());
    }

    public function genereHdays(
        $lang = 'ar',
        $coming_only = false,
        $shift_to_tomorrow = 1
    ) {
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_mode = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = 'genereHdays';
        $hd_0 = new Hday();
        $where_school_year_id = 'school_year_id = ' . $this->getId();

        $gdate_tomorrow = AfwDateHelper::shiftGregDate(
            date('Y-m-d'),
            $shift_to_tomorrow
        ); //tomorrow

        if ($coming_only) {
            $hd_0->where($where_school_year_id);
            $last_generated_gdate = $hd_0->func('max(hday_gdat)');
            $next_hday_num = $hd_0->func('max(hday_num)') + 1;
            $from = AfwDateHelper::shiftGregDate($last_generated_gdate, 1);
            if ($gdate_tomorrow > $from) {
                $from = $gdate_tomorrow;
            }
            // no need to do logic delete as we start from where we stopped
            // so all are new records
        } else {
            $next_hday_num = 1;
            $from = AfwDateHelper::hijriToGreg(
                $this->getVal('school_year_start_hdate')
            );
            // verifier que les hdays ne sont pas deja generes
            $hd_0->where($where_school_year_id);
            $hd_0->logicDelete(true, false);
        }

        $to = AfwDateHelper::hijriToGreg(
            $this->getVal('school_year_end_hdate')
        );

        $nb = 0;
        $nb_upd = 0;
        $we_mfk = $this->getSchool()->getVal('we_mfk');
        $we_arr = explode(',', trim($we_mfk, ','));
        $arr_hij_days = AfwDateHelper::genereHijriPeriod($from, $to, $we_arr);
        $first_hdate = '';

        foreach ($arr_hij_days as $gdate => $hij_row) {
            if (!$first_hdate) {
                $first_hdate = $hij_row['hdate'];
            }
            $hd = Hday::loadByGDate($this->getId(), $gdate, true);
            if ($hd->is_new) {
                $nb++;
            }
            $hd->set('wday_id', $hij_row['wday']);
            $hd->set('hday_date', $hij_row['hdate']);
            $hmonth = substr($hij_row['hdate'], 4, 2);
            $hd->set('hmonth', $hmonth);
            $hd->set('hday_num', $next_hday_num);

            // WE DON'T CHANGE Holiday INFOS FOR THE PAST
            if ($gdate >= $gdate_tomorrow) {
                if ($hij_row['free'] == 'N') {
                    $holObj = Holiday::getHoliday($this->id, $hij_row['hdate']);
                    if ($holObj != null) {
                        $free = 'Y';
                        $free_comment = $holObj->getDisplay();
                        $hol_id = $holObj->getId();
                    } else {
                        $free = 'N';
                        $free_comment = '';
                        $hol_id = 0;
                    }
                } else {
                    $free = 'Y';
                    $free_comment = $hij_row['descr'];
                    $hol_id = 0;
                }

                $hd->set('holiday', $free);
                $hd->set('holiday_id', $hol_id);
                $hd->set('hday_descr', $free_comment);
            }

            if ($hd->update()) {
                $nb_upd++;
            }
            $next_hday_num++;
        }

        $last_hdate = $hij_row['hdate'];
        $first_hdate_disp = AfwDateHelper::add_slashes($first_hdate);
        $last_hdate_disp = AfwDateHelper::add_slashes($last_hdate);
        $info =
            "تم انشاء $nb يوما من أيام " .
            $this->getDisplay() .
            "[ من  $first_hdate_disp   إلى $last_hdate_disp] وتم تعديل $nb_upd يوم";
        $error = '';
        $MODE_SQL_PROCESS_LOURD = $old_mode;
        $nb_queries_executed = 0; // to not count old queries and avoid next small and light processes halt because of.
        return [$error, $info, "", "we_mfk=$we_mfk we_arr=".var_export($we_arr,true)." arr_hij_days=".var_export($arr_hij_days,true)];
    }

    protected function getOtherLinksArray(
        $mode,
        $genereLog = false,
        $step = 'all'
    ) {
        global $me, $objme, $lang;
        $otherLinksArray = $this->getOtherLinksArrayStandard(
            $mode,
            false,
            $step
        );
        $my_id = $this->getId();
        $school = $this->het('school_id');
        if ($school) {
            $myschool_id = $school->id;
        } else {
            $myschool_id = 0;
        }

        if ($mode == 'mode_scop') 
        {
            if ($myschool_id > 0) 
            {
                $school_levels = $school->get('school_level_mfk');
                foreach ($school_levels as $school_level_id => $school_level_obj) 
                {
                    unset($link);
                    $link = [];
                    $title =
                        'إدارة مجال عمل المنشأة  للمستوى :' . $school_level_obj;
                    $title_detailed =
                        $title . ' ' . $this->getDetailedDisplay($lang);
                    $link[
                        'URL'
                    ] = "main.php?Main_Page=afw_mode_qedit.php&cl=SchoolScope&currmod=sis&id_origin=$my_id&class_origin=SchoolYear&module_origin=sis&step_origin=2&newo=3&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=school_year_id=$my_id,school_level_id=$school_level_id&sel_school_year_id=$my_id&sel_school_level_id=$school_level_id";
                    $link['TITLE'] = $title;
                    $link['UGROUPS'] = [];
                    $otherLinksArray[] = $link;
                }
            }
        }

        if ($mode == 'mode_scls') {
            unset($link);

            $link = [];
            $title = $this->translateMessage('إدارة الحلقات', $lang);
            $title_detailed = $title . ' ' . $this->getDetailedDisplay($lang);
            $link[
                'URL'
            ] = "main.php?Main_Page=afw_mode_qedit.php&cl=SchoolClass&currmod=sis&id_origin=$my_id&class_origin=SchoolYear&module_origin=sis&newo=3&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=school_year_id=$my_id&sel_school_year_id=$my_id";
            $link['TITLE'] = $title;
            $link['BF-ID'] = self::$BF_QEDIT_SCHOOL_CLASS;
            $otherLinksArray[] = $link;
        }

        if ($mode == 'mode_pendingCandidateList') {
            if ($myschool_id > 0) {
                $year = $this->getVal('year');
                unset($link);
                $link = [];
                $title = 'إنشاء متقدم';
                $link[
                    'URL'
                ] = "main.php?Main_Page=afw_mode_edit.php&cl=Scandidate&currmod=sis&id_origin=$my_id&class_origin=SchoolYear&module_origin=sis&sel_school_id=$myschool_id&sel_year=$year";
                $link['TITLE'] = $title;
                $link['BF-ID'] = self::$BF_EDIT_SCANDIDATE;
                $otherLinksArray[] = $link;
            }
        }

        if ($mode == 'mode_studentFileList') {
            if ($myschool_id > 0) {
                $year = $this->getVal('year');
                unset($link);
                $link = [];
                $title = 'توزيع يدوي للطلاب على الحلقات';
                $link[
                    'URL'
                ] = "main.php?Main_Page=afw_mode_qedit.php&cl=StudentFile&currmod=sis&id_origin=$my_id&class_origin=SchoolYear&module_origin=sis&newo=-1&limit=9999&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_id=$myschool_id,year=$year&sel_school_id=$myschool_id&sel_year=$year";
                $link['TITLE'] = $title;
                $link['BF-ID'] = self::$BF_QEDIT_SCANDIDATE;
                $otherLinksArray[] = $link;
            }
        }

        if ($mode == 'mode_acceptedCandidateList') {
            if ($myschool_id > 0) {
                $year = $this->getVal('year');
                unset($link);
                $link = [];
                $title = 'توزيع يدوي للمتقدمين على الحلقات';
                $link[
                    'URL'
                ] = "main.php?Main_Page=afw_mode_qedit.php&cl=Scandidate&currmod=sis&id_origin=$my_id&class_origin=SchoolYear&module_origin=sis&newo=-1&limit=30&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_id=$myschool_id,year=$year&sel_school_id=$myschool_id&sel_year=$year";
                $link['TITLE'] = $title;
                $link['BF-ID'] = self::$BF_QEDIT_SCANDIDATE;
                $otherLinksArray[] = $link;
            }
        }

        return $otherLinksArray;
    }

    public function calcSchool_year_name()
    {
        $school_year_name_template = AfwSession::config("school_year_name_template","CY");
        $syname = School::formatSYDate($school_year_name_template, $this->getVal('year'));
        return $syname;
    }


    public function beforeMAJ($id, $fields_updated)
    {
        $semPadded = str_pad($this->getVal('semester'),  2, "0", STR_PAD_LEFT);
        if(!$this->id) $this->setId($this->getVal('school_id') . $this->getVal('year').$semPadded);
        $school_year_name = $this->calcSchool_year_name();
        $school_year_name_ar = 'السنة الدراسية - ' . $school_year_name;
        $school_year_name_en = 'school year - ' . $school_year_name;
        $this->set('school_year_name_ar', $school_year_name_ar);
        $this->set('school_year_name_en', $school_year_name_en);
        //$this->throwError(var_export($this,true));
        return true;
    }


    public function genereSchoolScopeAccordingToStats($lang = 'ar')
    {
        $arr_stats = $this->statsDecisionArray();
        $nb = 0;
        foreach($arr_stats as $level_class_id => $row_stats)
        {
            $slid = $row_stats['slid'];
            if($this->id and $level_class_id and $slid)
            {
                $scopObj = SchoolScope::loadByMainIndex($this->id,$slid,$level_class_id,true);
                $scopObj->set("class_nb", $row_stats['nb-cls']);
                $scopObj->commit();
                $nb++;
            }            
        }

        if(!$nb) return ["", "", "لا يوجد احصائيات لسنوات سابقة أو حصيلة متقدمين على المنشأة ليتم اعتبارها في توليد آلي لمجال العمل ولذلك للمرة الأولى على المدير القيام بذلك يدويا",var_export($arr_stats,true)];
        else return ["", "تم توليد $nb من مجالات العمل وفقا للاحصائيات", ""];

    }


    public function deleteGeneratedSchoolClasses($lang = 'ar')
    {
        $noStudentsRegistered = $this->noStudentsRegistered();
        if(!$noStudentsRegistered)
        {
            $nbStudentsRegistered = $this->nbStudentsRegistered();
            return ["لا يمكن مسح صفوف من هذه السنة الدراسية بسبب وجود $nbStudentsRegistered طلاب مسجلين", ""];
        }
        else
        {
            $school_year_id = $this->getId();
            list($res,$rcn, $cnt) = SchoolClass::deleteWhere("school_year_id=$school_year_id");        
            return ["", "تم مسح $cnt صفوف من هذه السنة الدراسية ".$this->getDisplay($lang)." noStudentsRegistered=$noStudentsRegistered"];
        }    
    }

    public function scopeDefined()
    {
        $scopCount = $this->getRelation('scop')->count();

        return ($scopCount>0);
    }

    public function noStudentsRegistered()
    {
        $studentCount = $this->getRelation('studentFileList')->count();

        return ($studentCount==0);
    }

    public function nbStudentsRegistered()
    {
        $studentCount = $this->getRelation('studentFileList')->count();

        return $studentCount;
    }

    public function noSchoolClassesGenerated()
    {
        $scCount = $this->getRelation('schoolClassList')->count();

        return ($scCount==0);
    }

    public function scopeDefinedAndNoSchoolClassesGenerated()
    {
        return $this->scopeDefined() and $this->noSchoolClassesGenerated();
    }

    public function genereSchoolClassesAccordingToScope($lang = 'ar')
    {
        return $this->updateSchoolClassesAccordingToScope($lang, true);
    }

    public function getSchoolClassListByLevelClassId($level_class_id)
    {
        return $this->getRelation("schoolClassList")->resetWhere("level_class_id = $level_class_id")->getList();
    }

    public function updateSchoolClassesAccordingToScopeAndAvailableRooms(
        $lang = 'ar',
        $regen = false
    ) 
    {
        $school_year_id = $this->getId();
        if($regen)
        {
            SchoolClass::deleteWhere("school_year_id=$school_year_id");
        }
        $file_dir_name = dirname(__FILE__);

        $school_id = $this->getVal('school_id');
        $schoolObj = $this->het('school_id');
        
        $scopList = $this->get('scop');
        $objSC_inserted = 0;
        $objSC_count = 0;

        
        foreach ($scopList as $scopId => $scopObj) {
            $level_class_id = $scopObj->getVal('level_class_id');
            $level_class_obj = $scopObj->het('level_class_id');
            $school_level_obj = $scopObj->het('school_level_id');
            $level_class_order = $level_class_obj->getVal('level_class_order');
            $school_level_order = $school_level_obj->getVal('school_level_order');

            
            $schoolClassList = $this->getSchoolClassListByLevelClassId($level_class_id);

            $missed_sc = $scopObj->getVal('class_nb') - count($schoolClassList);

            for ($k = 0; $k < $missed_sc; $k++) 
            {
                $roomObj = $schoolObj->getAvailableRoom($this);
                if($roomObj)
                {
                    $class_name = $roomObj->getVal("room_name_ar");
                    $objSC = SchoolClass::loadByMainIndex(
                        $school_year_id,
                        $level_class_id,
                        $class_name,
                        $create_obj_if_not_found = true
                    );
                    $objSC->set('room_id', $roomObj->id);
                    
                    if($schoolObj)
                    {
                        $objSC->set('study_program_id', $schoolObj->getVal('study_program_id'));
                    }
                    

                    $objSC->commit();

                    if ($objSC->is_new) {
                        $objSC_inserted++;
                    }
                    $objSC_count++;
                }
            }
        }

        $info =
            "بحسب مجال العمل في اعدادات هذه السنة الدراسية وبحسب توفر القاعات تم توليد $objSC_inserted من الحلقات للسنة الدراسية  " .
            $this->getDisplay();
        $error = '';

        return [$error, $info];
    }

    public function updateSchoolClassesAccordingToScope(
        $lang = 'ar',
        $regen = false
    ) 
    {
        $school_year_id = $this->getId();
        if($regen)
        {
            SchoolClass::deleteWhere("school_year_id=$school_year_id");
        }
        $file_dir_name = dirname(__FILE__);

        $school_id = $this->getVal('school_id');
        $schoolObj = $this->het('school_id');
        
        $classes_names = $this->getVal('classes_names');

        if($classes_names)
        {
            $class_name_arr = [];
            $class_name_arr[0] = explode("\n",$classes_names);
        }
        else
        {
            include "$file_dir_name/../../external/school_all_config.php";
            include "$file_dir_name/../../external/school_$school_id"."_config.php";
        }        

        $scopList = $this->get('scop');
        $objSC_inserted = 0;
        $objSC_count = 0;
        foreach ($scopList as $scopId => $scopObj) {
            $level_class_id = $scopObj->getVal('level_class_id');
            $level_class_obj = $scopObj->het('level_class_id');
            $school_level_obj = $scopObj->het('school_level_id');
            $level_class_order = $level_class_obj->getVal('level_class_order');
            $school_level_order = $school_level_obj->getVal('school_level_order');

            $naming_ord = $school_level_order * 100 + $level_class_order;

            for ($k = 0; $k < $scopObj->getVal('class_nb'); $k++) {
                if (isset($class_name_arr[$naming_ord])) {
                    $ord = $objSC_count % count($class_name_arr[$naming_ord]);
                    $rank = floor(
                        $objSC_count / count($class_name_arr[$naming_ord])
                    );
                    $class_name = $class_name_arr[$naming_ord][$ord];
                } else {
                    $class_name = '';
                }

                if (!$class_name) {
                    $ord = $objSC_count % count($class_name_arr[0]);
                    $rank = floor($objSC_count / count($class_name_arr[0]));
                    $class_name = $class_name_arr[0][$ord];
                }

                if ($rank) {
                    $class_name .= '-' . $rank;
                }
                $objSC = SchoolClass::loadByMainIndex(
                    $school_year_id,
                    $level_class_id,
                    $class_name,
                    $create_obj_if_not_found = true
                );
                if (!$objSC->getVal('room_id')) {
                    // by default name of room is same of name of school class
                    $roomObj = Room::loadByMainIndex($school_id, $class_name);
                    $room_id = 0;
                    if ($roomObj) {
                        $room_id = $roomObj->id;
                    }
                    if ($room_id) {
                        $objSC->set('room_id', $room_id);
                        
                    }
                }
                if($schoolObj)
                {
                    $objSC->set('study_program_id', $schoolObj->getVal('study_program_id'));
                }
                

                $objSC->commit();

                if ($objSC->is_new) {
                    $objSC_inserted++;
                }
                $objSC_count++;
            }
        }

        $info =
            "تم توليد $objSC_inserted من الحلقات للسنة الدراسية  " .
            $this->getDisplay() .
            " ففي الجملة يوجد الآن $objSC_count صف لهذه السنة";
        $error = '';

        return [$error, $info];
    }

    public function firstSchoolClassAvailable($level_class_id)
    {
        $school_year_id = $this->getId();

        // require_once("$file_dir_name/school_class.php");

        $db = $this->getDatabase();

        $query_avail_sc = "SELECT tplaces.school_class_id
		FROM
		( SELECT sc.id AS school_class_id,
            sc.class_name,
            r.capacity,
            COUNT(sf.id) AS reserved
		   FROM $db.school_class AS sc
		   LEFT JOIN $db.room AS r ON r.id = sc.room_id AND sc.active='Y' AND r.active='Y'
		   LEFT JOIN $db.student_file AS sf ON sf.school_class_id = sc.id AND sf.active='Y'
		   WHERE sc.level_class_id = $level_class_id
		   AND sc.school_year_id = $school_year_id
		   GROUP BY sc.id
		) tplaces
		WHERE tplaces.capacity > tplaces.reserved
		ORDER BY tplaces.reserved ASC, tplaces.class_name ASC
		LIMIT 1";

        $avail_school_class_id = $this->dbdb_recup_value($query_avail_sc);

        if ($avail_school_class_id) {
            $sc = new SchoolClass();
            if ($sc->load($avail_school_class_id)) {
                return $sc;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    protected function beforeDelete($id, $id_replace)
    {
        $server_db_prefix = AfwSession::config('db_prefix', 'c0');

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
                // sis.school_class-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_class.php';
                    SchoolClass::removeWhere("school_year_id='$id'");
                    // $this->execQuery("delete from ${server_db_prefix}sis.school_class where school_year_id = '$id' ");
                }

                // sis.school_class_course-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_class_course.php';
                    SchoolClassCourse::removeWhere("school_year_id='$id'");
                    // $this->execQuery("delete from ${server_db_prefix}sis.school_class_course where school_year_id = '$id' ");
                }

                // sis.school_scope-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_scope.php';
                    SchoolScope::removeWhere("school_year_id='$id'");
                    // $this->execQuery("del-ete from ${server_db_prefix}sis.school_scope where school_year_id = '$id' ");
                }

                // sis.hday-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once hday.php';
                    Hday::removeWhere("school_year_id='$id'");
                    // $this->execQuery("delete from ${server_db_prefix}sis.hday where school_year_id = '$id' ");
                }

                // sis.prof sched item-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    ProfSchedItem::removeWhere("school_year_id='$id'");
                }

                // FK not part of me - replaceable
                // sis.course_sched_item-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 2
                if (!$simul) {
                    // require_once course_sched_item.php';
                    CourseSchedItem::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.course_sched_item set school_year_id='$id_replace' where school_year_id='$id' ");
                }
                // sis.rservice_student-السنة الدراسية والمنشأة	school_year_id  نوع علاقة بين كيانين ← 2
                if (!$simul) {
                    // require_once rservice_student.php';
                    RserviceStudent::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.rservice_student set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // MFK
            } else {
                // FK on me
                // sis.school_class-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_class.php';
                    SchoolClass::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.school_class set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // sis.school_class_course-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_class_course.php';
                    SchoolClassCourse::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.school_class_course set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // sis.school_scope-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once school_scope.php';
                    SchoolScope::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.school_scope set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // sis.hday-السنة الدراسية بالمنشأة	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    // require_once hday.php';
                    Hday::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.hday set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // sis.prof sched item-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 1
                if (!$simul) {
                    ProfSchedItem::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                }

                // sis.course_sched_item-السنة الدراسية	school_year_id  نوع علاقة بين كيانين ← 2
                if (!$simul) {
                    // require_once course_sched_item.php';
                    CourseSchedItem::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.course_sched_item set school_year_id='$id_replace' where school_year_id='$id' ");
                }
                // sis.rservice_student-السنة الدراسية والمنشأة	school_year_id  نوع علاقة بين كيانين ← 2
                if (!$simul) {
                    // require_once rservice_student.php';
                    RserviceStudent::updateWhere(
                        ['school_year_id' => $id_replace],
                        "school_year_id='$id'"
                    );
                    // $this->execQuery("update ${server_db_prefix}sis.rservice_student set school_year_id='$id_replace' where school_year_id='$id' ");
                }

                // MFK
            }
            return true;
        }
    }

    public function getFieldGroupInfos($fgroup)
    {
        if ($fgroup == 'dates') {
            return ['name' => $fgroup, 'css' => 'pct_50'];
        }

        if ($fgroup == 'cln') {
            return ['name' => $fgroup, 'css' => 'pct_50'];
        }

        if ($fgroup == 'decision_stats') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        
        
        return ['name' => $fgroup, 'css' => 'pct_100'];
    }

    public function stepsAreOrdered()
    {
        return (!$this->id);
    }


    public function attributeIsApplicable($attribute)
    {
        if($attribute == "school_year_type") return false;
        if(($attribute == "semester"))
        {
            return ($this->getVal("school_year_type") == self::$SY_TYPE_SEMESTER);
        }

                

        return true;
    }

    public function statsDecisionArray()
    {
        $acceptedCandidateList = $this->get('acceptedCandidateList');
        $arr_stats = [];
        foreach ($acceptedCandidateList as $acceptedCandidateItem) 
        {        
            $level_class_id = $acceptedCandidateItem->getVal("level_class_id");
            $school_level_id = $acceptedCandidateItem->getVal("level_class_id.school_level_id");
            $level_class_name = $acceptedCandidateItem->showAttribute("level_class_id");
            if(!$arr_stats[$level_class_id]) $arr_stats[$level_class_id] = ['name'=>$level_class_name, 'slid'=>$school_level_id, 'count'=>0, 'min-age'=>99, 'max-age'=>0, 'nb-cls'=>0];
            $arr_stats[$level_class_id]['count']++;
            $age = $acceptedCandidateItem->calcAge();
            if($arr_stats[$level_class_id]['min-age']>$age) $arr_stats[$level_class_id]['min-age'] = $age;
            if($arr_stats[$level_class_id]['max-age']<$age) $arr_stats[$level_class_id]['max-age'] = $age;
        }

        foreach($arr_stats as $level_class_id => $row_stats)
        {
            $max_by_sclass = 12;
            $arr_stats[$level_class_id]['nb-cls'] = round($arr_stats[$level_class_id]['count']/$max_by_sclass);
        }

        return $arr_stats;
    }

    public function calcDecision_stats()
    {
        $arr_stats = $this->statsDecisionArray();
        $arr_stats_header = ['name'=>'الفرع', 'count'=>'عدد المقبولين', 'min-age'=>'أصغر عمر', 'max-age'=>'أكبر عمر', 'nb-cls'=>'عدد الحلقات المقترح'];

        list($html,) = AfwShowHelper::tableToHtml($arr_stats, $arr_stats_header);
        return $html;
    }

    public function notCompleted()
    {
        if($this->notStarted()) return true;
        $schoolClassList = $this->getRelation("schoolClassList");
        foreach($schoolClassList as $schoolClassItem)
        {
            if($schoolClassItem->notCompleted()) return true;
        }

        return false;

    }

    public function notStarted()
    {
        $school_year_start_hdate = $this->getVal("school_year_start_hdate");
        $h_to_day = AfwDateHelper::currentHijriDate();

        return ($school_year_start_hdate>$h_to_day);
    }

    public function notFinished()
    {
        $school_year_end_hdate = $this->getVal("school_year_end_hdate");
        $h_to_day = AfwDateHelper::currentHijriDate();

        return ($school_year_end_hdate>=$h_to_day);
    }

    

    public function findCurrentSession($mySchoolEmployeeId)
    {
        return $this->findSessionByStatus($mySchoolEmployeeId, SessionStatus::$opened_session);
    }

    public function findStdBySession($mySchoolEmployeeId)
    {
        return $this->findSessionByStatus($mySchoolEmployeeId, SessionStatus::$standby_session);
    }

    public function findSessionByStatus($mySchoolEmployeeId, $sess_status)
    {        
        $school = $this->het('school_id');
        if (!$school) {
            return null;
        }
        $school_id = $school->getId();
        $levelsTemplateObj = $school->het("levels_template_id");
        if(!$levelsTemplateObj)
        {
            return null;
        }

        
        $levels_template_id = $levelsTemplateObj->id;
        $year = $this->getVal('year');
        
        $schoolLevelList = $levelsTemplateObj->get("schoolLevels");
        unset($levelsTemplateObj);
        $cssObj = new CourseSession();
        foreach($schoolLevelList as $schoolLevelItem)
        {
            $levelClassList = $schoolLevelItem->get("levelClassList");
            foreach($levelClassList as $levelClassItem)
            {
                $year = $this->getVal('year');
                $school_level_order = $schoolLevelItem->getVal("school_level_order");
                $level_class_order = $levelClassItem->getVal("level_class_order");                
                $cssObj->select("school_id",$school_id);
                $cssObj->select("levels_template_id",$levels_template_id);
                $cssObj->select("school_level_order",$school_level_order);
                $cssObj->select("level_class_order",$level_class_order);
                $cssObj->select("prof_id",$mySchoolEmployeeId);
                $cssObj->select("session_status_id", $sess_status);        
                // die("findSessionByStatus cssObj->getSQLMany => ".$cssObj->getSQLMany());
                if($cssObj->load())
                {
                    unset($levelClassList);
                    unset($schoolLevelList);            
                    return  $cssObj;
                }
            }
            unset($levelClassList);
        }
        unset($cssObj);
        unset($schoolLevelList);
        

        // nothing found
        return null;
    }

    public function calcStart_near_date()
    {
        return date("Y-m-d");
    }

    public function calcEnd_near_date()
    {
        return date("Y-m-d");
    }

    public function calcStart_prev_date()
    {
        return AfwDateHelper::shiftGregDate('',-2);
    }

    public function calcEnd_prev_date()
    {
        return AfwDateHelper::shiftGregDate('',-1);
    }


}

