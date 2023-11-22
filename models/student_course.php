<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table student_course : student_course - الملفات السنوية لطالب(ة) بوحدة دراسية
// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class StudentCourse extends SisObject
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
    public static $TABLE = 'student_course';
    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('student_course', '', 'sis');
        SisStudentCourseAfwStructure::initInstance($this);
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
        $obj = new StudentCourse();
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
        if (!$row["course_program_id"]) return null;

        return self::loadByMainIndex($row["student_id"],
        $row["school_id"],
        $row["year"],
        $row["course_program_id"],
        $row["levels_template_id"],
        $row["school_level_order"],
        $row["level_class_order"],
        $create_obj_if_not_found = true);
    }

    public static function loadByMainIndex(
        $student_id,
        $school_id,
        $year,
        $course_program_id,
        $levels_template_id = 0,
        $school_level_order = 0,
        $level_class_order = 0,
        $create_obj_if_not_found = false
    ) {
        $obj = new StudentCourse();
        $obj->select('student_id', $student_id);
        $obj->select('school_id', $school_id);
        $obj->select('year', $year);
        $obj->select('course_program_id', $course_program_id);
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
            $obj->set('course_program_id', $course_program_id);
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
        return $this->getVal('student_file_title');
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
        list($data[4], $link) = $this->displayAttribute('course_program_id',false,$lang);
        return implode(',', $data);
    }

    public function beforeMAJ($id, $fields_updated)
    {
        global $file_dir_name, $lang;

        if ($this->getVal('student_file_title') == '--') {
            $this->set('student_file_title', '');
        }
        if (!$this->getVal('student_file_title')) {
            $this->set('student_file_title', $this->getTitle($lang));
        }

        $objSchool = $this->hetSchool();
        $objStudent = $this->hetStudent();

        if($objSchool) $this->set("city_id", $objSchool->getVal("city_id"));
        if($objStudent)
        {
            $this->set("genre_id", $objStudent->getVal("genre_id"));
            $this->set("firstname", $objStudent->getVal("firstname"));
            $this->set("f_firstname", $objStudent->getVal("f_firstname"));
            $this->set("lastname", $objStudent->getVal("lastname"));
            $this->set("mobile", $objStudent->getVal("mobile"));
            $this->set("country_id", $objStudent->getVal("country_id"));
            $this->set("birth_date", $objStudent->getVal("birth_date"));
            $this->set("birth_date_en", $objStudent->getVal("birth_date_en"));
            $this->set("parent_mobile", $objStudent->getVal("parent_mobile"));
            $this->set("parent_idn_type_id", $objStudent->getVal("parent_idn_type_id"));
            $this->set("parent_idn", $objStudent->getVal("parent_idn"));
            $this->set("mother_mobile", $objStudent->getVal("mother_mobile"));
            $this->set("mother_idn_type_id", $objStudent->getVal("mother_idn_type_id"));
            $this->set("mother_idn", $objStudent->getVal("mother_idn"));
            $this->set("address", $objStudent->getVal("address"));
            $this->set("cp", $objStudent->getVal("cp"));
            $this->set("quarter", $objStudent->getVal("quarter"));
            $this->set("email", $objStudent->getVal("email"));
        }
        

        // @todo : rafik check utility of this below
        // seems to look for level_class_id and school_c lass_id because in old version (ria) was mandatory
        /*
               $options = [];
               $options[4] = true;
               // die("student_file_title = ".$this->getVal("student_file_title"));
               if(!$this->getVal("school_ class_id")) return $this->completeStudentCourse($options);
               else 
               {
                     $scl = $this->get("school _class_id");
                     $this->set("level_class_id",$scl->getVal("level_class_id"));
                     $this->set("class_name",$scl->getVal("class_name"));
                     return true;
               }
               return false;
               */

        return true;
    }
/*
    protected function afterUpdate($id, $fields_updated)
    {
        global $lang, $_SESSION;

        if ($fields_updated['paid'] and $this->_isPaid()) {
            list($error, $info) = $this->genere StudentSessions($lang);
            if ($info) {
                $_SESSION['information'] .= ' ' . $info;
            }
            if ($error) {
                $_SESSION['error'] .= ' ' . $error;
            }
        }
    }*/

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

    public function getFormuleResult($attribute, $what = 'value')
    {
        // global $me, $URL_RACINE_SITE;

        switch ($attribute) {
            case 'group_num':
                $school = $this->het('school_id');
                if ($school) {
                    $group_num = $school->getVal('group_num');
                } else {
                    $group_num = -1;
                }
                return $group_num;
                break;

            case 'curr_hmonth':
                if (!$this->hday_curr) {
                    if (!$this->sy) {
                        if (!$this->sclass) {
                            $this->sclass = $this->getSclass();
                        }
                        if (!$this->sclass) {
                            return 0;
                        }
                        $this->sy = $this->sclass->getSy();
                    }
                    if (!$this->sy) {
                        return 0;
                    }
                    $this->hday_curr = $this->sy->getCurrHday();
                }
                if (!$this->hday_curr) {
                    return 0;
                }
                return $this->hday_curr->getVal('semester');
                break;

            case 'curr_hday_num':
                if (!$this->hday_curr) {
                    if (!$this->sy) {
                        if (!$this->sclass) {
                            $this->sclass = $this->getSclass();
                        }
                        if (!$this->sclass) {
                            return 0;
                        }
                        $this->sy = $this->sclass->getSy();
                    }
                    if (!$this->sy) {
                        return 0;
                    }
                    $this->hday_curr = $this->sy->getCurrHday();
                }
                if (!$this->hday_curr) {
                    return 0;
                }
                return $this->hday_curr->getVal('sday_num');
                break;
        }

        return $this->calcFormuleResult($attribute, $what);
    }

    public function calcIs_diploma()    
    {
        $chool_level_id = $this->calcSchool_level_id($getObj=false);
        return (($chool_level_id == 2) ? 1 : 0);
    }

    public function calcIs_qualif_diploma()    
    {
        $chool_level_id = $this->calcSchool_level_id($getObj=false);
        return (($chool_level_id == 2) ? 1 : 0);
    }

    public function calcSchool_level_id($getObj=true)
    {
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        $slObj = SchoolLevel::loadByMainIndex(
            $levels_template_id,
            $school_level_order
        );
        if ($slObj) {
            if($getObj) return $slObj;
            else return $slObj->id;
        } else {
            return null;
        }
    }

    public function calcLevel_class_id($getObj=false)
    {
        $school_level_obj = $this->calcSchool_level_id();
        $school_level_id = $school_level_obj ? $school_level_obj->id : 0;
        if(!$school_level_id) return null;
        $level_class_order = $this->getVal('level_class_order');
        $lc = LevelClass::loadByMainIndex($school_level_id, $level_class_order);
        if ($lc) {
            if($getObj) return $lc;
            else return $lc->id;
        } else {
            return null;
        }
    }
/*
    public function completeStudentCourse($options)
    {
        global $file_dir_name, $lang;

        $level_class_id = $this->getVal('level_class_id');
        if (!$level_class_id) {
            // on doit avoir specifie soit school_ class_id et on deduit level_class_id et le reste
            // soit l'inverse
            if (!$this->getVal('school_c lass_id')) {
                return false;
            }
            $sc_obj = $this->get('school _class_id');
            if (!$sc_obj) {
                return false;
            }
            $class_name = $sc_obj->getVal('class_name');
            $level_class_id = $sc_obj->getVal('level_class_id');
            $sy_obj = $sc_obj->get('school_year_id');
            $year = $sy_obj->get('year');
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

        if (!$this->getVal('school_cl ass_id') and $sc_obj) {
            $this->set('school_cla ss_id', $sc_obj->getId());
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
    }*/

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

                $studentFile->completeStudentCourse($options);

                if (count($errors) == 0) {
                    $errors = $studentFile->getDataErrors($lang);
                    //$this->throwError("parent->getDataErrors = ".var_export($errors,true));
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
        $step = 'all'
    ) {
        global $objme;
        $sp_errors = [];

        

        return $sp_errors;
    }

    
/*
    public function fixModeSubAttributes($attribute, $value)
    {
        // should be overriden for virtual fields or category fields
        if($attribute == "school_ class_id")
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
    }*/

    public function stepsAreOrdered()
    {
            return false;
    } 

    

    public static function list_of_period() { 
        $list_of_items = array();
        
            $list_of_items[1] = "فترة صباحية";
            $list_of_items[2] = "فترة مسائية";
            $list_of_items[3] = "تدريب الكتروني";
        

        return  $list_of_items;
    }

    public static function list_of_eval() { 
        $list_of_items = array(); 
        $max_eval_sis = AfwSession::config("max_eval_sis",30);
        $max_eval_sis_unit = AfwSession::config("max_eval_sis_unit","جزء");
        for($k=1;$k<=$max_eval_sis;$k++)
        {
            $list_of_items[$k] = $k. " " . $max_eval_sis_unit;
        }

        return  $list_of_items;
    }
    

    public function list_of_genre_id() { 
        $list_of_items = array(); 
        $list_of_items[1] = "ذكر";
        $list_of_items[2] = "أنثى";
         
       return  $list_of_items;
    }

    protected function beforeDelete($id,$id_replace) 
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


    

    public function decodePeriod($string)
    {
        if(AfwStringHelper::stringContain($string, "صباح")) $period = 1;
        elseif(AfwStringHelper::stringContain($string, "مسا")) $period = 2;
        elseif(AfwStringHelper::stringContain($string, "لكتر")) $period = 3;
        else $period = 0;
        $this->set("period",$period);

        return "decodePeriod from $string returned period=$period";
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
        list($first_name, $father_name, $last_name) = AfwStringHelper::intelligentDecodeName($string);

        $this->set("firstname",$first_name);
        $this->set("f_firstname",$father_name);
        $this->set("lastname",$last_name);

        return "name '$string' has been splitted ($first_name / $father_name / $last_name)";

    }

    
}
?>
