<?php
// ------------------------------------------------------------------------------------
// 25/11/2023
// 
// repair table student_file;
// update student_file set validated_at=null where validated_at<'1900-01-01 00:00:00';
// alter table student_file change student_file_title student_file_title varchar(255) null;
//  update ".$server_db_prefix."sis.student set school_level_order = 2 where levels_template_id=2 and school_level_order=0;
// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class StudentFile extends SisObject
{
    public static $MY_ATABLE_ID = 13338;
    // إحصائيات حول الملفات السنوية لطالب(ة) بم
    public static $BF_STATS_STUDENT_FILE = 102004;
    // إدارة الملفات السنوية لطالب(ة) بوحدة دراسية
    public static $BF_QEDIT_STUDENT_FILE = 101999;
    // إنشاء ملف سنوي لطالب(ة) بوحدة دراسية
    public static $BF_EDIT_STUDENT_FILE = 101998;
    // الاستعلام عن ملف سنوي لطالب(ة) بوحدة دراسية
    public static $BF_QSEARCH_STUDENT_FILE = 102003;
    // البحث في الملفات السنوية لطالب(ة) بوحدة دراسية
    public static $BF_SEARCH_STUDENT_FILE = 102002;
    // عرض تفاصيل ملف سنوي لطالب(ة) بوحدة دراسية
    public static $BF_DISPLAY_STUDENT_FILE = 102001;
    // مسح ملف سنوي لطالب(ة) بوحدة دراسية
    public static $BF_DELETE_STUDENT_FILE = 102000;

    public static $DATABASE = '';
    public static $MODULE = 'sis';
    public static $TABLE = 'student_file';
    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('student_file', '', 'sis');
        SisStudentFileAfwStructure::initInstance($this);
    }

    public static $STATS_CONFIG = array(

        "gs001"=> array("STATS_WHERE"=>"active = 'Y' ", // السعوديين فقط and country_id = 183
                             "DISABLE-VH" => true,
                             "FOOTER_TITLES" => true,
                             "FOOTER_SUM" => true,
                             "GROUP_SEP"=>".",
                             "GROUP_COLS"=>array(
                                                  0=> array("COLUMN"=>"country_id", "DISPLAY-FORMAT"=>"decode","FOOTER_SUM_TITLE"=>"الإجمــالـي"),
                                                ),
                             "DISPLAY_COLS"=>array(
                                                  1=> array("COLUMN"=>"is_diploma", "COLUMN_IS_FORMULA"=>true, "GROUP-FUNCTION"=>"sum", "SHOW-NAME"=>"is_diploma","FOOTER_SUM"=>true),
                                                  2=> array("COLUMN"=>"is_qualif_diploma", "COLUMN_IS_FORMULA"=>true, "GROUP-FUNCTION"=>"sum", "SHOW-NAME"=>"is_qualif_diploma","FOOTER_SUM"=>true),
                                                ),

                             "FORMULA_COLS"=>array(
                                             //0 => array("SHOW-NAME"=>"perf", "METHOD"=>"getPerf"),
                             ),
                            )
    );

    public static function loadById($id)
    {
        $obj = new StudentFile();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else {
            return null;
        }
    }

    public function select_visibilite_horizontale($dropdown = false)
    {
        $objme = AfwSession::getUserConnected();
        if(!$objme->isAdmin())
        {
            $my_schools_arr = [0];
            $myEmplId = 0;
            if($objme) $myEmplId = $objme->getEmployeeId();
            if($myEmplId) 
            {
                $schoolList = SchoolEmployee::getSchoolList($myEmplId);
            }
            foreach($schoolList as $schoolItemId => $schoolItem)
            {
                $my_schools_arr[] = $schoolItemId;
            }
            
            $this->select_visibilite_horizontale_default();
            $this->where('school_id in ('.implode(', ',$my_schools_arr).')');
        }
    }

    public static function loadFromRow($row)
    {
        // die("stopped by rafik before loadFromRow row=".var_export($row,true));
        if (!$row["student_id"]) return null;
        if (!$row["school_id"]) return null;
        if (!$row["year"]) return null;

        return self::loadByMainIndex($row["student_id"],
        $row["school_id"],
        $row["year"],
        $row["levels_template_id"],
        $row["school_level_order"],
        $row["level_class_order"],
        $create_obj_if_not_found = true);
    }

    public static function loadByMainIndex(
        $student_id,
        $school_id,
        $year,
        $levels_template_id = 0,
        $school_level_order = 0,
        $level_class_order = 0,
        $create_obj_if_not_found = false
    ) {
        $obj = new StudentFile();
        $obj->select('student_id', $student_id);
        $obj->select('school_id', $school_id);
        $obj->select('year', $year);
        if($levels_template_id) $obj->select('levels_template_id', $levels_template_id);
        if($school_level_order) $obj->select('school_level_order', $school_level_order);
        if($level_class_order) $obj->select('level_class_order', $level_class_order);

        if ($obj->load()) {
            if ($create_obj_if_not_found) {
                $obj->activate();
            }
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set('student_id', $student_id);
            $obj->set('school_id', $school_id);
            $obj->set('year', $year);
            $obj->set('levels_template_id', $levels_template_id);
            $obj->set('school_level_order', $school_level_order);
            $obj->set('level_class_order', $level_class_order);

            $obj->insertNew();
            if (!$obj->id) {
                return null;
            } // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
        } else {
            return null;
        }
    }

    public function getDisplay($lang = 'ar')
    {
        return $this->getTitle($lang);
    }

    public function getTitle($lang)
    {
        $data = [];
        list($data[0], $link) = $this->displayAttribute(
            'student_id',
            false,
            $lang
        );
        list($data[1], $link) = $this->displayAttribute(
            'school_id',
            false,
            $lang
        );
        //list($data[2],$link) = $this->displayAttribute("class_name",false,$lang);
        list($data[3], $link) = $this->displayAttribute('year', false, $lang);
        
        list($data[4], $link) = $this->displayAttribute(
            'level_class_id',
            false,
            $lang
        );
        return implode('-', $data);
    }

    public function deleteMyFutureStudentSessions($lang = 'ar')
    {
        $school_id = $this->getVal('school_id');
        $levels_template_id = $this->getVal("levels_template_id");
        $school_level_order = $this->getVal("school_level_order");
        $level_class_order = $this->getVal("level_class_order");
        $class_name = $this->getVal("class_name");
        $student_id = $this->getVal('student_id');

        if($school_id and $levels_template_id and $school_level_order and $level_class_order and $class_name and $student_id)
        {
            $today = date("Y-m-d");
            $db = $this->getDatabase();

            $sqlDelete = "delete from $db.student_session 
                    where school_id = $school_id 
                    and levels_template_id = $levels_template_id 
                    and school_level_order = $school_level_order 
                    and level_class_order = $level_class_order 
                    and class_name = _utf8'$class_name'
                    and session_date > '$today'
                    and student_id = $student_id";

            list($result, $row_count, $deleted_row_count) = self::executeQuery($sqlDelete);
        }
        else
        {
            $deleted_row_count = -1;
            $sqlDelete = "nothing";
        } 

        return [$deleted_row_count, $sqlDelete];
    }

    public function  fixMyData($lang="ar", $commit=false)
    {
        $err = "";
        $info = "";
        $warn = "";

        if (!$this->getVal('city_id')) {
            $objSchool = $this->het("school_id");
            if($objSchool)
            {
                $this->set("city_id", $objSchool->getVal("city_id"));
                $info = "تم تصحيح مدينة سكن الطالب";
            } 
        }
        
        $objStudent = $this->het("student_id");
        if(!$objStudent)
        {
            $idn = $this->getVal('idn');
            $idn_type_id = $this->getVal('idn_type_id');
            if($idn)
            {
                $objStudent = Student::loadById($idn);
                if(!$objStudent and ($this->getVal('firstname') and $this->getVal('lastname') and $this->getVal('mobile')))
                {
                    $objStudent = Student::loadByMainIndex($idn, $idn_type_id,true);
                } 
                //die("std=".var_export($objStudent,true));
            }
            else
            {
                //die("this=".var_export($this,true));
            }
        }
        if($objStudent) 
        {
            $this->set("student_id", $objStudent->id);
            
            // avoid infinite loop because fixMyData and syncSameFieldsWith will commit $objStudent
            if(!$objStudent->IS_COMMITING) 
            {
                list($err, $info, $warn) = $objStudent->fixMyData($lang,false);

                // below this is the master
                //  I (this student file) take from him (objStudent) only what I need
                // but after
                // He (objStudent) take from me (this student file) all my fields except primary key and unique index columns
                list($fields1, $fields0) = $this->syncSameFieldsWith($objStudent, true, $commit, $avoid_if_filled_fields = ["firstname"=>true,"f_firstname"=>true,"lastname"=>true,"birth_date_en"=>true]);
                $nb_fields = count($fields1)+count($fields0);
                if($nb_fields>0)
                {
                    $info .= " -> تم تصحيح $nb_fields من الحقول fields0 = ".var_export($fields0,true)." fields1 = ".var_export($fields1,true);
                }

                $fname = trim(trim($this->getVal("firstname")),"-");
                if(!$fname)
                {
                    $this->set("firstname", $objStudent->getVal("firstname"));
                    $this->set("f_firstname", $objStudent->getVal("f_firstname"));
                    $this->set("lastname", $objStudent->getVal("lastname"));
                }
                
            }
            
            
        }

        if ($this->getVal('student_file_title') == '--') {
            $this->set('student_file_title', '');
        }
        if (!$this->getVal('student_file_title')) {
            $this->set('student_file_title', $this->getTitle($lang));
            $info = "تم تصحيح عنوان الملف";
        }

        if($commit) $this->commit();
            
        if((!$info) and (!$err) and (!$warn)) 
        {
            $info = "لا يوجد معلومات تحتاج لتصحيح. اذا لم يكن الأمر كذلك راجع مدير المنصة";
        }

        return array($err, $info, $warn); //
    }

    public function beforeMaj($id, $fields_updated)
    {
        global $file_dir_name, $lang;
        $this->IS_COMMITING = false;
        $this->fixMyData($lang);

        if($fields_updated["class_name"])
        {
            // delete future student sessions
            $this->deleteMyFutureStudentSessions($lang);
        }

        // @todo : rafik check utility of this below
        // seems to look for level_class_id and school_class_id because in old version (ria) was mandatory
        /*
               $options = [];
               $options[4] = true;
               // die("student_file_title = ".$this->getVal("student_file_title"));
               if(!$this->getVal("school_class_id")) return $this->completeStudentFile($options);
               else 
               {
                     $scl = $this->get("school_class_id");
                     $this->set("level_class_id",$scl->getVal("level_class_id"));
                     $this->set("class_name",$scl->getVal("class_name"));
                     return true;
               }
               return false;
               */

        return true;
    }

    public function afterUpdate($id, $fields_updated, $disableAfterCommitDBEvent=false)
    {
        global $lang;

        // if($fields_updated['paid'] and $this->_isPaid())
        if ($fields_updated['class_name']) {
            list($error, $info) = $this->genereStudentSessions($lang);
            if ($info) {
                AfwSession::pushInformation($info);
            }
            if ($error) {
                AfwSession::pushError($error);
            }
        }
    }

    public function list_of_year()
    {
        $file_dir_name = dirname(__FILE__);

        
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
               global $lang, $file_dir_name;    
               
               
               
	            switch($attribute) 
                {
                    case "age" :
                        $hdob = $this->getVal("birth_date");
                        list($gdob,) = explode(" ",$this->getVal("birth_date_en"));
                        if(($gdob=="--") or ($gdob=="0000-00-00")) $gdob = "";
                        $age = "";
                        if((!$gdob) and $hdob)
                        {
                                $gdob = AfwDateHelper::hijriToGreg($hdob);
                                if(($gdob=="--") or ($gdob=="0000-00-00")) die("$gdob = AfwDateHelper::hijriToGreg($hdob)");
                        }
                        if(($gdob=="--") or ($gdob=="0000-00-00")) 
                        {
                            $gdob = "";
                        }

                        if($gdob)
                        {
                            $today = date("Y-m-d");
                            $diff = diff_date($today,$gdob);
                            $age = round(($diff/354.0)*1000)/1000;
                        }
                        else
                        {
                            $age = -1;
                        }
                        
                        return $age; 
                    break;
                }

                return AfwFormulaHelper::calculateFormulaResult($this,$attribute, $what);
        }

    public function calcIs_diploma($what='value')    
    {
        $chool_level_id = $this->calcSchool_level_id();
        return (($chool_level_id == 2) ? 1 : 0);
    }

    public function calcIs_qualif_diploma($what='value')    
    {
        $chool_level_id = $this->calcSchool_level_id();
        return (($chool_level_id == 2) ? 1 : 0);
    }

    public function calcSchool_level_id($what='value')
    {
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        $slObj = null;
        if($levels_template_id and $school_level_order)
        {
            $slObj = SchoolLevel::loadByMainIndex(
                $levels_template_id,
                $school_level_order
            );
        }
        global $lang;
        return AfwFormatHelper::decode_result($slObj,$what,$lang);
    }


    public function calcCourse_program_school_id($what='value')
    {
        $course_program_id = $this->getVal('course_program_id');
        $school_id = $this->getVal('school_id');
        $cpsObj = null;
        if($course_program_id and $school_id)
        {
            $cpsObj = CpcCourseProgramSchool::loadByMainIndex($course_program_id,$school_id);
        }
        global $lang;
        return AfwFormatHelper::decode_result($cpsObj,$what,$lang);
    }

    public function calcLevel_class_id($what='value')
    {
        $school_level_obj = $this->calcSchool_level_id('object');
        $school_level_id = $school_level_obj ? $school_level_obj->id : 0;
        if(!$school_level_id) return null;
        $level_class_order = $this->getVal('level_class_order');
        $lcObj = LevelClass::loadByMainIndex($school_level_id, $level_class_order);
        global $lang;
        return AfwFormatHelper::decode_result($lcObj,$what,$lang);
    }


    public function calcSchool_class_id($what="value")
    {
        $school_id = $this->getVal("school_id"); 
        $year = $this->getVal("year");
        $sy_obj = SchoolYear::loadByMainIndex($school_id,$year,0,SchoolYear::$SY_TYPE_SYEAR,$create_obj_if_not_found = false);
        $school_year_id = $sy_obj->id; 
        $level_class_id = $this->calc("level_class_id");
        $class_name = $this->getVal("class_name");
        
        $scObj = SchoolClass::loadByMainIndex($school_year_id, $level_class_id, $class_name);
        if(!$scObj) die("scObj is null for $school_year_id, $level_class_id, $class_name");

        global $lang;
        return AfwFormatHelper::decode_result($scObj,$what,$lang); 
    }
    public function completeStudentFile($options)
    {
        global $file_dir_name, $lang;

        $level_class_id = $this->calc('level_class_id');
        if (!$level_class_id) {
            // on doit avoir specifie soit school_class_id et on deduit level_class_id et le reste
            // soit l'inverse
            if (!$this->calc('school_class_id')) {
                return false;
            }
            $sc_obj = $this->calcSchool_class_id('object');
            if (!$sc_obj) {
                return false;
            }
            $class_name = $sc_obj->getVal('class_name');
            $level_class_id = $sc_obj->calc('level_class_id');
            $sy_obj = $sc_obj->het('school_year_id');
        }

        if (!$level_class_id) {
            return false;
        }

        // student
        $student_id = $this->getVal('student_id');
        if (!$student_id) {
            return false;
        }
        $student = $this->get('student_id');
        // school
        $school_id = $this->getVal('school_id');
        if (!$school_id) {
            return false;
        }
        $school = $this->get('school_id');

        // year
        $year = $this->getVal('year');
        if (!$year) {
            $sy_obj = $school->getApplicationSYear();
            $year = $sy_obj->getVal('year');
        } else {
            // // require_once school_year.php");
            $sy_obj = SchoolYear::loadByMainIndex($school_id,$year,0,SchoolYear::$SY_TYPE_SYEAR,$create_obj_if_not_found = false);
        }

        if (!$sy_obj) {
            return false;
        }

        if (!$class_name) {
            $class_name = $this->getVal('class_name');
        }

        // 	إبقاء الطلاب مع نفس زملاءهم للسنة الماضية ما أمكن
        //    Keep students with the same colleagues when it is possible
        if (!$class_name and $options[3]) {
            $class_name = $school->keepclass_nameForStudent($student_id);
        }

        if (!$class_name and $options[4]) {
            $sc_obj = $sy_obj->firstSchoolClassAvailable($level_class_id);
            if ($sc_obj) {
                $class_name = $sc_obj->getVal('class_name');
            }
        }



        if (!$sc_obj) {
            // // require_once school_class.php");
            $sc_obj = SchoolClass::loadByMainIndex(
                $sy_obj->getId(),
                $level_class_id,
                $class_name
            );
        }

        if (!$this->getVal('school_class_id') and $sc_obj) {
            $this->set('school_class_id', $sc_obj->getId());
        }
        if (!$this->getVal('school_id')) {
            $this->set('school_id', $school->getId());
        }
        if (!$this->getVal('student_num')) {
            $this->set(
                'student_num',
                $school->getStudentNumForStudent($this->getVal('student_id'))
            );
        }
        if (!$this->getVal('year')) {
            $this->set('year', $sy_obj->getVal('year'));
        }
        if (!$this->getVal('level_class_id')) {
            $this->set('level_class_id', $level_class_id);
        }
        if (!$this->getVal('class_name') and $class_name) {
            $this->set('class_name', $class_name);
        }

        // افتراضيا الطالب مقبول إلا إذا وجدت موانع
        $student_accepted = true;

        // تطبيق الشروط على الطالب
        // 1. شرط الجنس

        $school->options['genre_id_condition'] = true; // en tunisie = false

        if (
            $school->options['genre_id_condition'] and
            $student->getVal('genre_id') != $school->getVal('genre_id')
        ) {
            $student_accepted = false;
            $student_rejected_reason = $school->translateMessage(
                'genre_id_condition',
                $lang
            );
        }
        // 2. شرط الجنسية
        // @todo

        // 3. شرط السن
        // @todo

        // 4. شرط قرب السكن
        // @todo

        // 5. شرط حسن السيرة والسلوك
        // @todo

        if ($student_accepted) {
            $this->set('student_file_status_id', 3);
        } else {
            $this->set('student_file_status_id', 4);
            // insert into application table the application and the $student_rejected_reason
            // @todo
        }

        return true;
    }

    protected function importRecord(
        $dataRecord,
        $orgunit_id,
        $overwrite_data,
        $options,
        $lang,
        $dont_check_error
    ) 
    
    {
        $errors = [];

        foreach ($dataRecord as $key => $val) {
            $$key = $val;
        }

        // // require_once student.php");
        $student_empty = new Student();
        if (!$student_idn) {
            $errors[] = $this->translateMessage(
                'missed idn value for student',
                $lang
            );
            return [null, $errors, [], []];
        }
        // idn and idn type identification
        $student_idn_type_id = 0;
        $student_idn_type_ok = false;
        if ($student_idn_type) {
            list(
                $student_idn_type_ok,
                $student_idn_type_id,
            ) = AfwStringHelper::parseAttribute($student_empty,
                'idn_type_id',
                $student_idn_type,
                $lang,
                false
            );
        }
        if (!$student_idn_type_ok) {
            // find it from idn format
            list($idn_correct, $student_idn_type_id) = AfwFormatHelper::getIdnTypeId(
                $student_idn
            );
        }

        if ($idn_correct) {
            //lookup for the student it should exists
            $student = Student::loadByMainIndex(
                $student_idn_type_id,
                $student_idn,
                $create_obj_if_not_found = false
            );
            if (!$student) {
                $errors[] =
                    $this->translateMessage(
                        'student not found for this idn ',
                        $lang
                    ) .
                    ' : ' .
                    $student_idn;
                return [null, $errors, [], []];
            }
        } else {
            $errors[] =
                $this->translateMessage('incorrect student idn format', $lang) .
                ' : ' .
                $student_idn;
            return [null, $errors, [], []];
        }

        if (!$orgunit_id) {
            $errors[] = $this->translateMessage(
                'missed orgunit id value for school',
                $lang
            );
            return [null, $errors, [], []];
        }

        //lookup for the school it should exists
        $school = School::loadByMainIndex(
            $orgunit_id,
            $create_obj_if_not_found = false
        );
        if (!$school) {
            $errors[] =
                $this->translateMessage(
                    'school not found for this org unit ',
                    $lang
                ) .
                ' : ' .
                $orgunit_id;
            return [null, $errors, [], []];
        }

        $school_id = $school->getId();
        $student_id = $student->getId();

        $appSchoolYear = $school->getApplicationSYear();
        if (!$appSchoolYear) {
            $errors[] =
                $this->translateMessage(
                    'school current application year not found for school ',
                    $lang
                ) .
                ' : ' .
                $school;
            return [null, $errors, [], []];
        }
        //
        $year = $appSchoolYear->getVal('year');

        // check that we are sure that all needed objects are correct and filled
        if ($school_id and $student_id and $year and $levels_template_id and $school_level_order and $level_class_order) {
            //lookup for the auser may be it exists
            $studentFile = self::loadByMainIndex(
                $student_id,
                $school_id,
                $year,
                $levels_template_id,
                $school_level_order,
                $level_class_order,
                $create_obj_if_not_found = true
            );

            // mise a jour de $famRel si new or $overwrite_data
            if ($overwrite_data or $studentFile->is_new) {
                if ($student_level_class) {
                    list(
                        $val_ok,
                        $val_parsed_or_error,
                    ) = AfwStringHelper::parseAttribute($studentFile,
                        'level_class_id',
                        $student_level_class,
                        $lang
                    );
                    if (!$val_ok) {
                        $errors[] = $val_parsed_or_error;
                    }
                }

                $studentFile->completeStudentFile($options);

                if (count($errors) == 0) {
                    $errors = $studentFile->getDataErrors($lang);
                    //throw new AfwRuntimeException("parent->getDataErrors = ".var_export($errors,true));
                }
                if (count($errors) == 0) {
                    $studentFile->commit();
                }
            } else {
                //$errors[] = $this->translateMessage("This parent already exists and overwrite is not allowed",$lang);
            }
            return [$studentFile, $errors, [], []];
        } else {
            $errors[] =
                $this->translateMessage(
                    'the infos (school_id,student,year) are not complete',
                    $lang
                ) . " : ($school_id,$student_id,$year)";
            return [null, $errors, [], []];
        }
    }

    protected function namingImportRecord($dataRecord, $dont_check_error)
    {
        return $dataRecord['parent_firstname'] .
            ' ' .
            $dataRecord['parent_lastname'] .
            ' ' .
            $dataRecord['parent_relationship_type'] .
            ' ' .
            $dataRecord['student_firstname'] .
            ' ' .
            $dataRecord['student_lastname'];
    }

    protected function getRelatedClassesForImport($options = null)
    {
        return [];
    }

    protected function getSpecificDataErrors(
        $lang = 'ar',
        $show_val = true,
        $step = 'all', 
        $erroned_attribute = null,
        $stop_on_first_error = false, $start_step=null, $end_step=null
    ) {
        global $objme;
        $sp_errors = [];

        /* $level_class_id = $this->getVal('level_class_id');
        $school_class_id = $this->getVal('school_class_id');

        if (!$level_class_id and !$school_class_id) {
            $sp_errors['level_class_id'] = $this->translateMessage(
                'level_class_id or school_class_id should be defined'
            );
        }*/

        return $sp_errors;
    }

    public function genereStudentSessions($lang = 'ar')
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) {
            return ['no user connected', ''];
        }

        $school_class = $this->het('school_class_id');
        if (!$school_class) {
            return ['no school class defined for this student file', ''];
        }

        $school_year = $school_class->het('school_year_id');
        if (!$school_year) {
            return ['no school year defined for this school class', ''];
        }

        return $school_year->genereStudentSessions(
            $lang,
            $school_class->getVal('level_class_id'),
            $school_class->getVal('class_name'),
            $this->getVal('student_id')
        );
    }

    public function fixModeSubAttributes($attribute, $value)
    {
        // should be overriden for virtual fields or category fields
        if($attribute == "school_class_id")
        {
            $schoolClassObj = SchoolClass::loadById($value);
            $school_yearObj = $schoolClassObj->het("school_year_id");
            $level_classObj = $schoolClassObj->het("level_class_id");
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
                    $class_name = $schoolClassObj->getVal("class_name");

                    $subAttr = [];

                    $subAttr["school_id"] = $school_id;
                    $subAttr["year"] = $year;
                    $subAttr["levels_template_id"] = $levels_template_id;
                    $subAttr["school_level_order"] = $school_level_order;
                    $subAttr["level_class_order"] = $level_class_order;
                    $subAttr["class_name"] = $class_name;
                    //die("fixModeSubAttributes($attribute, $value) is ".var_export($subAttr,true));
                    return $subAttr;
                }
            }    
        }
        // by default no sub attributes :
        return [];
    }

    public function stepsAreOrdered()
    {
            return false;
    } 

    

    

    public static function list_of_eval() { 
        return self::list_of_sis_eval();
    }
    

    public function list_of_genre_id() { 
        $list_of_items = array(); 
        $list_of_items[1] = "ذكر";
        $list_of_items[2] = "أنثى";
         
       return  $list_of_items;
    }

    public function beforeDelete($id,$id_replace) 
    {
        return true;
    }


    public function getFieldGroupInfos($fgroup)
    {
        if ($fgroup == 'payment') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        if ($fgroup == 'result') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }
        
        return ['name' => $fgroup, 'css' => 'pct_100'];
    }

    public function decodeBirthDate($string)
    {
        list($success, $birth_date_ar, $birth_date_en) = AfwDataMigrator::fixHijriOrMiladi($string);
        if($success)
        {
            if($birth_date_ar) $this->set("birth_date",$birth_date_ar);
            if($birth_date_en) $this->set("birth_date_en",$birth_date_en);
            return "birth_date decode succeeded ar=$birth_date_ar en=$birth_date_en from $string";
        }

        return "birth_date decode failed from $string";
    }

    public function decodeName($string) 
    {
        if((!$this->getVal("firstname")) or (!$this->getVal("lastname")))
        {
            list($first_name, $father_name, $last_name) = AfwStringHelper::intelligentDecodeName($string);
            if(AfwStringHelper::isNameOfAllah($first_name))
            {
                $first_name = "";
            }
            if(AfwStringHelper::isNameOfAllah($father_name))
            {
                $father_name = "";
            }
            if(AfwStringHelper::isNameOfAllah($last_name))
            {
                $last_name = "";
            }
            $this->set("firstname",$first_name);
            $this->set("f_firstname",$father_name);
            $this->set("lastname",$last_name);
            return "name '$string' has been splitted ($first_name / $father_name / $last_name)";
        } 
        else return "decodeName ignored as both first_name and last_name are filled";
        

        

    }

    public function getClassNames()
    {
        $level_class_id = $this->calc("level_class_id");
        $school_id = $this->getVal("school_id"); 
        $year = $this->getVal("year");
        $syObj = SchoolYear::loadByMainIndex($school_id,$year,0,SchoolYear::$SY_TYPE_SYEAR,$create_obj_if_not_found = false);


        $result = [];
        if($syObj)
        {
            $schoolClassList = $syObj->getSchoolClassListByLevelClassId($level_class_id);
            foreach($schoolClassList as $schoolClassItem)
            {
                $clName = $schoolClassItem->getVal("class_name");
                $result[$clName]=$clName;
            }
        }

        return $result;
    }

    protected function getPublicMethods()
    {
        $pbms = array();
        
        if(true)
        {
                $pbms["xHff34"] = array("METHOD"=>"fixMyData", 
                                            "LABEL_AR"=>"تصحيح البيانات", 
                                            "LABEL_EN"=>"fix My Data",
                                            "BF-ID"=>"" 
                                            ); //   
                                            
                                            
                                                                
        }
        
        return $pbms;  
    }

    public function shouldBeCalculatedField($attribute){
        if($attribute=="address") return true;
        if($attribute=="quarter") return true;
        if($attribute=="studentList") return true;
        if($attribute=="program_type_id") return true;
        if($attribute=="course_program_name_ar") return true;
        if($attribute=="duration") return true;
        if($attribute=="ref_num") return true;
        if($attribute=="school_name_ar") return true;
        return false;
    }    


    protected function beforeSetAttribute($attribute, $newvalue)
    {
        $oldvalue = $this->getVal($attribute);
        /*
        if($attribute=="course_program_id")
        {
           throw new AfwRuntimeException("before set attribute $attribute from '$oldvalue' to '$newvalue'");
        }*/
        
        return true;
    }

    protected function afterSetAttribute($attribute)
    {
        /*
        if($attribute=="course_program_id")
        {
           throw new AfwRuntimeException("after set of attribute $attribute : newval=".$this->getVal($attribute));
        }*/
    }

    public static function list_of_level() { 
        return self::list_of_sis_level();
    }

    public static function loadFromExcel($lang = "ar", $params=[])     
    { 
        $pageStart=1;
        $pageRows=1000;
        $nbPages=1;
        if(count($params)>0)
        {
            $pageStart = $params["ps"];
            if(!$pageStart) $pageStart=1;
            $pageRows = $params["rowspp"];
            if(!$pageRows) $pageRows=1000;
            $nbPages = $params["pages"];
            if(!$nbPages) $nbPages=1;
            $file_code = $params["file"];
            $date_from = $params["from"];
            $date_to = $params["to"];
            if(!$file_code) $file_code="students-delta";
            if($date_from) $file_code.="-from-".$date_from;
            if($date_to) $file_code.="-to-".$date_to;
        }

        $Ymd = date("Y-m-d");  
        $today_students_file = "/var/log/$file_code-at-$Ymd.xlsx";
        if(!file_exists($today_students_file))
        {
            throw new AfwBusinessException("file $today_students_file does not exist");
        }
        
        $info_arr = [];
        $warning_arr = [];
        $error_arr = [];
        $tech_arr = [];
        $server_db_prefix = "c"."0";
        
        $php_generation_folder = AfwSession::config("sql_generation_folder", "/var/log/gen/sql/doing");
        $dir_sep = AfwSession::config("dir_sep", "/");     
        $sql_examples = [];
        $pageEnd = $pageStart+$nbPages-1;
        $info_arr[]="<b>generation of pages from $pageStart to $pageEnd</b>";
        for($page=$pageStart; $page<=$pageEnd;$page++) 
        {
            $row_num_start = $pageRows*($page-1);
            $row_num_end = $pageRows*$page-1;
            

            list($excel, $my_head, $my_data) = AfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "StudentFile::loadFromExcel", true);
            $sql = "";
            $nb_rows = 0;            
            foreach($my_data as $row => $my_row)
            {
                $my_row['LASTUPDATEONACADEMICSTATUS'] = AfwDateHelper::parseGregDate($my_row['LASTUPDATEONACADEMICSTATUS'], '/', 'd/m/Y');
                // $beforeParse = $my_row['GREGORIANBIRTHDATE'];
                $my_row['GREGORIANBIRTHDATE'] = AfwDateHelper::parseGregDate($my_row['GREGORIANBIRTHDATE'], '/', 'd/m/Y');
                // $afterParse = $my_row['GREGORIANBIRTHDATE'];
                // die("beforeParse=$beforeParse afterParse=$afterParse");
                $my_row['GRADUATIONDATE'] = AfwDateHelper::parseGregDate($my_row['GRADUATIONDATE'], '/', 'd/m/Y');
                $sql_line = AfwSqlHelper::sqlInsertOrUpdate($server_db_prefix."sis.TAMKEEN_ACADEMICDETAIL", $my_row);
                if($nb_rows<2) $sql_examples[] = $sql_line;
                $sql .= $sql_line."\n";
                $nb_rows++;
            }
            
            $sql_prefix = "";
            if($page==1)
            {
                $sql_prefix = "create table ".$server_db_prefix."sis.TAMKEEN_ACADEMICDETAIL_old_".date("Ymd_His")." as select * from ".$server_db_prefix."sis.TAMKEEN_ACADEMICDETAIL;\n".
                                                        "truncate ".$server_db_prefix."sis.TAMKEEN_ACADEMICDETAIL;\n";
            }
            
            
            if($php_generation_folder!="no-gen")
            {
                $sql_fileName = $php_generation_folder . $dir_sep . "$file_code-at-$Ymd-p$page.sql";
                try
                {
                    AfwFileSystem::write($sql_fileName, $sql_prefix.$sql);
                    $info_arr[] = "file $sql_fileName generated successfully with $nb_rows row(s)";                    
                    $info_arr[] = "run : mysql -h 127.0.0.1 -u admin -p < $sql_fileName [P@996220Admin@1234!996220@P]";
                }
                catch(Exception $e)
                {
                    $error_arr[] = "failed to write sql file $sql_fileName : ".$e->getMessage();
                }
                finally
                {
                    
                }
                
            }
            else
            {
                $warning_arr[] = "file generation is disabled (see sql_generation_folder parameter in system config file)";
            }
        }    
        $tech_arr[] = "sql examples : \n<br>".implode("\n<br>", $sql_examples);
        // write the $sql in an sql file like generation of cline (same folder)
        
        // $warning = "my_head=".var_export($my_head, true);
        // $info = "<br>\n my_data=".var_export($my_data, true);
        

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr);
    
    }
    

    
}
?>
