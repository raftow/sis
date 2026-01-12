<?php
// ------------------------------------------------------------------------------------
// 29/7/2023
// alter table student_file_course add mainwork_update char(1);
// alter table student_file_course add homework_update char(1);
// alter table student_file_course add homework2_update char(1);
// alter table student_file_course change class_name class_name varchar(24) not null;
// alter table student_file_course add study_program_id int; 
// alter table ".$server_db_prefix."sis.student_file_course add   mainwork_start_line_num smallint DEFAULT NULL  after mainwork_end_page_num;
// alter table ".$server_db_prefix."sis.student_file_course add   mainwork_end_line_num smallint DEFAULT NULL  after mainwork_start_paragraph_num;
// alter table ".$server_db_prefix."sis.student_file_course add   homework_start_line_num smallint DEFAULT NULL  after homework_end_page_num;
// alter table ".$server_db_prefix."sis.student_file_course add   homework_end_line_num smallint DEFAULT NULL  after homework_start_paragraph_num;
// alter table ".$server_db_prefix."sis.student_file_course add   homework2_start_line_num smallint DEFAULT NULL  after homework2_end_page_num;
// alter table ".$server_db_prefix."sis.student_file_course add   homework2_end_line_num smallint DEFAULT NULL  after homework2_start_line_num;
// SELECT `page_num`, sum(len) FROM `cpc_book_paragraph` WHERE book_id=1 group by page_num having sum(len) not between 14.5 and 15.5;


// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class StudentFileCourse extends SisObject
{
    public static $DATABASE = '';
    public static $MODULE = 'sis';
    public static $TABLE = 'student_file_course';
    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('student_file_course', '', 'sis');
        SisStudentFileCourseAfwStructure::initInstance($this);
    }
    /*
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
    );*/

    public static function loadById($id)
    {
        $obj = new StudentFileCourse();
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
        $levels_template_id,
        $school_level_order,
        $level_class_order,
        $course_id,
        $create_obj_if_not_found = false
    ) {
        $obj = new StudentFileCourse();
        $obj->select('student_id', $student_id);
        $obj->select('school_id', $school_id);
        $obj->select('year', $year);
        $obj->select('levels_template_id', $levels_template_id);
        $obj->select('school_level_order', $school_level_order);
        $obj->select('level_class_order', $level_class_order);
        $obj->select('course_id', $course_id);

        
        

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
            $obj->set('course_id', $course_id);
 
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
        return $this->getTitle($lang,false);
    }

    public function getWideDisplay($lang = 'ar')
    {
        return $this->getTitle($lang);
    }

    public function getTitle($lang, $long=true)
    {
        $data = [];
        $data[0] = $this->decode('student_id');
        if($long) $data[1] = $this->decode('school_id');
        if($long) $data[2] = $this->getVal("class_name");
        if($long) $data[3] = $this->getVal('year');
        if($long) $data[4] = $this->decode('course_id');
        
        return implode('-', $data);
    }

    public function beforeMaj($id, $fields_updated)
    {
        global $file_dir_name, $lang;

        if($fields_updated["mainwork_start_chapter_id"])
        {
            
            if((!$fields_updated["mainwork_start_page_num"]) and (!$fields_updated["mainwork_start_paragraph_num"]))
            {
                $chp = $this->het("mainwork_start_chapter_id");
                if($chp) $this->set("mainwork_start_page_num",$chp->getVal("first_page_num"));
                $this->set("mainwork_start_paragraph_num",1);
            }
            
        }

        if($fields_updated["homework_start_chapter_id"])
        {
            
            if((!$fields_updated["homework_start_page_num"]) and (!$fields_updated["homework_start_paragraph_num"]))
            {
                $chp = $this->het("homework_start_chapter_id");
                if($chp) $this->set("homework_start_page_num",$chp->getVal("first_page_num"));
                $this->set("homework_start_paragraph_num",1);
                $chp_disp = $chp ? $chp->getDisplay($lang) : "";
                //AfwSession::pushWarning("تم الرجوع الى الآية الأولى من سورة المراجعة ".$chp_disp);
            }
        }

        if($fields_updated["homework2_start_chapter_id"])
        {
            
            if((!$fields_updated["homework2_start_page_num"]) and (!$fields_updated["homework2_start_paragraph_num"]))
            {
                $chp = $this->het("homework2_start_chapter_id");
                if($chp) $this->set("homework2_start_page_num",$chp->getVal("first_page_num"));
                $this->set("homework2_start_paragraph_num",1);
            }
            
        }

        if($fields_updated["mainwork_start_part_id"])
        {
            
            if((!$fields_updated["mainwork_start_page_num"]) and (!$fields_updated["mainwork_start_paragraph_num"]))
            {
                $part = $this->het("mainwork_start_part_id");
                if($part) $this->set("mainwork_start_page_num",$part->getVal("first_page_num"));
                $this->setForce("mainwork_start_paragraph_num",0);
            }
            
        }

        if($fields_updated["homework_start_part_id"])
        {
            
            if((!$fields_updated["homework_start_page_num"]) and (!$fields_updated["homework_start_paragraph_num"]))
            {
                $part = $this->het("homework_start_part_id");
                if($part) $this->set("homework_start_page_num",$part->getVal("first_page_num"));
                $this->setForce("homework_start_paragraph_num",0);
                $chp_disp = $chp ? $chp->getDisplay($lang) : "";
                AfwSession::pushWarning("تم الرجوع الى الصفر في سورة المراجعة ".$chp_disp);
            }
        }

        if($fields_updated["homework2_start_part_id"])
        {
            
            if((!$fields_updated["homework2_start_page_num"]) and (!$fields_updated["homework2_start_paragraph_num"]))
            {
                $part = $this->het("homework2_start_part_id");
                if($part) $this->set("homework2_start_page_num",$part->getVal("first_page_num"));
                $this->set("homework2_start_paragraph_num",0);
                
            }
            
        }


        if($fields_updated["mainwork_end_chapter_id"])
        {
            if((!$fields_updated["mainwork_end_page_num"]) and (!$fields_updated["mainwork_end_paragraph_num"]))
            {
                $chp = $this->het("mainwork_end_chapter_id");
                if($chp) $this->set("mainwork_end_page_num",$chp->getVal("last_page_num"));
                if($chp) $this->set("mainwork_end_paragraph_num",$chp->getVal("last_paragraph_num"));
            }
        }

        if($fields_updated["homework_end_chapter_id"])
        {
            if((!$fields_updated["homework_end_page_num"]) and (!$fields_updated["homework_end_paragraph_num"]))
            {
                $chp = $this->het("homework_end_chapter_id");
                if($chp) $this->set("homework_end_page_num",$chp->getVal("last_page_num"));
                if($chp) $this->set("homework_end_paragraph_num",$chp->getVal("last_paragraph_num"));
            }
        }

        if($fields_updated["homework2_end_chapter_id"])
        {
            if((!$fields_updated["homework2_end_page_num"]) and (!$fields_updated["homework2_end_paragraph_num"]))
            {
                $chp = $this->het("homework2_end_chapter_id");
                if($chp) $this->set("homework2_end_page_num",$chp->getVal("last_page_num"));
                if($chp) $this->set("homework2_end_paragraph_num",$chp->getVal("last_paragraph_num"));
            }
            
        }

        if($fields_updated["mainwork_end_part_id"])
        {
            if((!$fields_updated["mainwork_end_page_num"]) and (!$fields_updated["mainwork_end_paragraph_num"]))
            {
                $part = $this->het("mainwork_end_part_id");
                if($part) $this->set("mainwork_end_page_num",$part->getVal("last_page_num"));
                if($part) $this->set("mainwork_end_paragraph_num",$part->getVal("last_paragraph_num"));
                
            }
        }

        if($fields_updated["homework_end_part_id"])
        {
            if((!$fields_updated["homework_end_page_num"]) and (!$fields_updated["homework_end_paragraph_num"]))
            {
                $part = $this->het("homework_end_part_id");
                if($part) $this->set("homework_end_page_num",$part->getVal("last_page_num"));
                if($part) $this->set("homework_end_paragraph_num",$part->getVal("last_paragraph_num"));
                
            }
        }
                
        if($fields_updated["homework2_end_part_id"])
        {
            if((!$fields_updated["homework2_end_page_num"]) and (!$fields_updated["homework2_end_paragraph_num"]))
            {
                $part = $this->het("homework2_end_part_id");
                if($part) $this->set("homework2_end_page_num",$part->getVal("last_page_num"));
                if($part) $this->set("homework2_end_paragraph_num",$part->getVal("last_paragraph_num"));
                
            }
            
        }

        if($this->getVal("mainwork_end_page_num")<$this->getVal("mainwork_start_page_num"))
        {
            $this->set("mainwork_end_page_num", $this->getVal("mainwork_start_page_num"));
        }
        if($this->getVal("homework_end_page_num")<$this->getVal("homework_start_page_num"))
        {
            $this->set("homework_end_page_num", $this->getVal("homework_start_page_num"));
        }
        if($this->getVal("homework2_end_page_num")<$this->getVal("homework2_start_page_num"))
        {
            $this->set("homework2_end_page_num", $this->getVal("homework2_start_page_num"));
        }

        if(!$this->getVal("mainwork_start_paragraph_num")) $this->setForce("mainwork_start_paragraph_num",0);
        if(!$this->getVal("homework_start_paragraph_num")) $this->setForce("homework_start_paragraph_num",0);
        if(!$this->getVal("homework2_start_paragraph_num")) $this->setForce("homework2_start_paragraph_num",0);

        if(!$this->getVal("mainwork_end_paragraph_num")) $this->setForce("mainwork_end_paragraph_num",0);
        if(!$this->getVal("homework_end_paragraph_num")) $this->setForce("homework_end_paragraph_num",0);
        if(!$this->getVal("homework2_end_paragraph_num")) $this->setForce("homework2_end_paragraph_num",0);

        if($fields_updated["mainwork_sens"])
        {
            $this->set("mainwork_start_book_id", $this->calcMainwork_real_book_id());
        }

        if($fields_updated["homework_sens"])
        {
            $this->set("homework_start_book_id", $this->calcHomework_real_book_id());
        }

        if($fields_updated["homework2_sens"])
        {
            $this->set("homework2_start_book_id", $this->calcHomework2_real_book_id());
        }

        $attribute_arr = ["mainwork","homework","homework2"];
        $pos_arr = ["start","end"];
        foreach($attribute_arr as $attribute_case)
        {
            foreach($pos_arr as $pos_case)
            {
                if($fields_updated[$attribute_case."_".$pos_case."_paragraph_num"])
                {
                    $prg = CpcBookParagraph::loadParagraphByNum(0,$this->getVal($attribute_case."_".$pos_case."_part_id"),$this->getVal($attribute_case."_".$pos_case."_chapter_id"),$this->getVal($attribute_case."_".$pos_case."_paragraph_num"));
                    if($prg) $this->set($attribute_case."_".$pos_case."_page_num", $prg->getVal("page_num"));
                }
            }
        }

        if($fields_updated["study_program_id"])
        {
            $studyProgramObj = $this->het("study_program_id");
            $sccObj = $this->calcSchool_class_course_id("object");
            $scObj = $this->calcSchool_class_id("object");
            if($studyProgramObj and $scObj and $sccObj)
            {
                foreach($attribute_arr as $attribute_case)
                {
                    $this->updateWorkManhaj($attribute_case, $lang, $studyProgramObj, $scObj, $sccObj,false);
                }
            }
            
        }

        foreach($attribute_arr as $attribute_case)
        {
            if(
                $fields_updated[$attribute_case."_nb_parts"] or
                $fields_updated[$attribute_case."_nb_pages"] or
                $fields_updated[$attribute_case."_nb_lines"] or
                $fields_updated[$attribute_case."_start_part_id"] or
                $fields_updated[$attribute_case."_start_chapter_id"] or
                $fields_updated[$attribute_case."_start_paragraph_num"]
                )
            {
                $chapter_id = $this->getVal($attribute_case."_start_chapter_id");
                $page_num = $this->getVal($attribute_case."_start_page_num");
                $paragraph_num = $this->getVal($attribute_case."_start_paragraph_num");
                $line_num = $this->getVal($attribute_case."_start_line_num");
                
                if($chapter_id and $page_num and $paragraph_num)
                {
                    $delta_parts = $this->getVal($attribute_case."_nb_parts");
                    if(!$delta_parts) $delta_parts = 0;
                    $delta_nb_pages = $this->getVal($attribute_case."_nb_pages");
                    if(!$delta_nb_pages) $delta_nb_pages = 0;
                    $delta_nb_pages = $delta_parts*20+$delta_nb_pages;
                    $delta_paragraph = 0;
                    $delta_lines = $this->getVal($attribute_case."_nb_lines");
                    
                    $book_id = $this->getVal($attribute_case."_start_book_id");
                    $part_id = $this->getVal($attribute_case."_start_part_id");
                    $chapter_sens = $this->getManhajSens($attribute_case);

                    list($book_id, $part_id, $chapter_id) = CpcBookParagraph::repareBookTriplet($book_id, $part_id, $chapter_id);
                    /*
                    list($book_id, $new_part_id, $new_chapter_id, $new_page_num, $new_paragraph_num,$log_arr) 
                        = CpcBookParagraph::moveInParagraphs($book_id, $part_id, $chapter_id, $page_num, $paragraph_num, 
                                    $chapter_sens, $delta_paragraph, $delta_lines, $delta_pages);*/

                    list($book_id, 
                        $old_part_id, $old_chapter_id, $old_page_num, $old_paragraph_num, 
                        $new_part_id, $new_chapter_id, $new_page_num, $new_paragraph_num, $new_line_num, 
                        $log_arr) 
                                    = CpcBookLine::moveInLines($book_id, $line_num, $chapter_sens, $delta_paragraph, $delta_lines, $delta_nb_pages);

                    $log_txt = implode("<br>\n",$log_arr);                        

                    if($new_paragraph_num and ($new_paragraph_num!="not found"))
                    {
                        $this->set($attribute_case."_end_part_id",$new_part_id);
                        $this->set($attribute_case."_end_chapter_id",$new_chapter_id);
                        $this->set($attribute_case."_end_page_num",$new_page_num);
                        $this->set($attribute_case."_end_paragraph_num",$new_paragraph_num);        
                        $this->set($attribute_case."_end_line_num",$new_line_num);        
                        AfwSession::pushSuccess("moveInParagraphs(bk=$book_id, part=$part_id, sourat=$chapter_id, pg=$page_num, aya=$paragraph_num, sens=$chapter_sens, delta_pgph=$delta_paragraph, delta-ln=$delta_lines, deltapg=$delta_nb_pages) => new_paragraph_num=$new_paragraph_num <br>\n $log_txt");
                    }
                    else
                    {
                        AfwSession::pushWarning("moveInParagraphs(bk=$book_id, part=$part_id, sourat=$chapter_id, pg=$page_num, aya=$paragraph_num, sens=$chapter_sens, delta_pgph=$delta_paragraph, delta-ln=$delta_lines, deltapg=$delta_nb_pages) => new_paragraph_num=$new_paragraph_num <br>\n $log_txt");
                    }
                }
            }

        }

        return true;
    }

    public function getOpenedCourseAndStudentSessions($getAlsoStudentSession=false, $courseSessObj=null)
    {
        $scObj = $this->calcSchool_class_id("object");
        if(!$courseSessObj) $courseSessObj = $scObj->getOpenedCourseSession();
        $studentSessObj = null;
        if($courseSessObj and $courseSessObj->isStrictlyOpened() and $getAlsoStudentSession)
        {
            $studentSessObj = $courseSessObj->getStudentSessionOf($this->getVal("student_id"));
        }


        return [$courseSessObj, $studentSessObj];
    }

    public function updateStudentSessionWithMe($lang="ar", $fields_updated="all", $studentSessObj = null, $courseSessObj=null)
    {
        if(!$studentSessObj)
        {
            list($courseSessObj, $studentSessObj) = $this->getOpenedCourseAndStudentSessions(true, $courseSessObj);
        }

        $arr_updated_fields = [];
        $nb_updated_fields = 0;
        $nb_unchanged_fields = 0;
        $nb_ignored_fields = 0;

        $error = "";
        $information = "";

        $log_arr = [];

        if($studentSessObj)
        {
            $student_location_fields = ['mainwork_start_book_id','homework_start_book_id','homework2_start_book_id',
                        'mainwork_end_book_id','homework_end_book_id','homework2_end_book_id','mainwork_start_part_id',
                        'mainwork_start_chapter_id','mainwork_start_paragraph_num','mainwork_start_page_num',
                        'mainwork_end_part_id','mainwork_end_chapter_id','mainwork_end_paragraph_num','mainwork_end_page_num',
                        'homework_start_part_id','homework_start_chapter_id','homework_start_paragraph_num','homework_start_page_num',
                        'homework_end_part_id','homework_end_chapter_id','homework_end_paragraph_num','homework_end_page_num',
                        'homework2_start_part_id','homework2_start_chapter_id','homework2_start_paragraph_num','homework2_start_page_num',
                        'homework2_end_part_id','homework2_end_chapter_id','homework2_end_paragraph_num','homework2_end_page_num'];


            foreach($student_location_fields as $location_field)            
            {
                if(($fields_updated=="all") or ($fields_updated[$location_field]))
                {
                    $old = $studentSessObj->getVal($location_field);
                    $new = $this->getVal($location_field);
                    if($old !== $new)
                    {
                        $studentSessObj->setForce($location_field,$this->getVal($location_field));
                        $nb_updated_fields++;
                        $arr_updated_fields[] = $location_field;
                        $log_arr[] = "********** ".$location_field." is updated ************";
                    }
                    else
                    {
                        $log_arr[] = $location_field." old=$old is same than new=$new";
                        $nb_unchanged_fields++;
                    }
                }
                else
                {
                    $log_arr[] = $location_field." not updated";
                    $nb_ignored_fields++;
                }
            }

            $nb_ignored_fields += $nb_unchanged_fields;
            $information = "تم تحديث $nb_updated_fields حقول وتجاهل $nb_ignored_fields حقول في كشف ".$studentSessObj->getShortDisplay($lang);

            if(!$studentSessObj->commit())
            {
                $error = "المفروض أنه $information ولكن فشلت عملية تحديث الكشف : ".$studentSessObj->debugg_reason_non_update." fields_updated=".var_export($fields_updated,true)." <br>\n Log <br>\n ".implode("<br>\n",$log_arr);
            }
            else
            {
                
            }

            

        }
        else
        {
            if($courseSessObj) return ["", "", $this->getShortDisplay($lang)." : لا يوجد حصة مفتوحة حاليا"];
            else return [$this->getShortDisplay($lang)." : لا يوجد حصة حاليا", "", ""];
        }


        

        return [$error, $information, "", $studentSessObj->id . " updated_fields=".var_export($arr_updated_fields,true)];
    }

    public function afterUpdate($id, $fields_updated, $disableAfterCommitDBEvent=false)
    {
        global $lang;

        list($error, $info, $war) = $this->updateStudentSessionWithMe($lang, $fields_updated);

        if ($info) {
            AfwSession::pushInformation($info);
        }

        if ($war) {
            AfwSession::pushWarning($war);
        }

        if ($error) {
            AfwSession::pushError($error);
        }

        

        if ($fields_updated['class_name']) {
            
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
        // global $me, $URL_RACINE_SITE;

        switch ($attribute) {
            
        }

        return AfwFormulaHelper::calculateFormulaResult($this,$attribute,$what);
    }

    

    public function calcSchool_level_id($what="value")
    {
        $levels_template_id = $this->getVal('levels_template_id');
        $school_level_order = $this->getVal('school_level_order');
        $slObj = SchoolLevel::loadByMainIndex(
            $levels_template_id,
            $school_level_order
        );
        if ($slObj) {
            if($what=="object") return $slObj;
            else return $slObj->id;
        } else {
            return null;
        }
    }

    public function calcLevel_class_id($what="value")
    {
        $school_level_id = $this->calcSchool_level_id();
        if(!$school_level_id) return null;
        $level_class_order = $this->getVal('level_class_order');
        $lc = LevelClass::loadByMainIndex($school_level_id, $level_class_order);
        if ($lc) {
            if($what=="object") return $lc;
            else return $lc->id;
        } else {
            return null;
        }
    }

    public function calcSchool_class_id($what="value")
    {
        $level_class_id = $this->calcLevel_class_id();
        $school_id = $this->getVal('school_id');
        $year = $this->getVal('year');
        $school_year_id = $school_id.$year."00";
        $class_name = $this->getVal('class_name');
        $sclObj = SchoolClass::loadByMainIndex($school_year_id, $level_class_id,$class_name);
        if ($sclObj) {
            if($what=="object") return $sclObj;
            else return $sclObj->id;
        } else {
            return null;
        }
    }

    public function calcSchool_class_course_id($what="value")
    {
        $level_class_id = $this->calcLevel_class_id();
        $school_id = $this->getVal('school_id');
        $year = $this->getVal('year');
        $school_year_id = $school_id.$year."00";
        $class_name = $this->getVal('class_name');
        $course_id = $this->getVal('course_id');
        $sclcObj = SchoolClassCourse::loadByMainIndex($school_year_id, $level_class_id,$class_name, $course_id);
        if ($sclcObj) {
            if($what=="object") return $sclcObj;
            else return $sclcObj->id;
        } else {
            return null;
        }
    }

    

    public function completeStudentFileCourse($options)
    {
        global $file_dir_name, $lang;

        $level_class_id = $this->getVal('level_class_id');
        if (!$level_class_id) {
            // on doit avoir specifie soit school_class_id et on deduit level_class_id et le reste
            // soit l'inverse
            if (!$this->getVal('school_class_id')) {
                return false;
            }
            $sc_obj = $this->get('school_class_id');
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

        /*if(!$class_name)
               {
                   $class_name = "a";
               }*/

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

                $studentFile->completeStudentFileCourse($options);

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
        /*
        $level_class_id = $this->getVal('level_class_id');
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
                    $arrStudentIds = $schoolClassObj->getRelation("stdn")->getArray("student_id");
                    $subAttr = [];

                    $subAttr["school_id"] = $school_id;
                    $subAttr["year"] = $year;
                    $subAttr["levels_template_id"] = $levels_template_id;
                    $subAttr["school_level_order"] = $school_level_order;
                    $subAttr["level_class_order"] = $level_class_order;
                    $subAttr["class_name"] = $class_name;
                    $subAttr["student_id"] = $arrStudentIds;
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

    

    public static function list_of_level() { 
        $list_of_items = array();
        if(AfwSession::config("level_t",true))
        {
            $list_of_items[1] = "تمهيدي";
        }

        if(AfwSession::config("level_0",true))
        {
            $list_of_items[2] = "أولى ابتدائي";
            $list_of_items[3] = "ثاني ابتدائي";
            $list_of_items[4] = "ثالث ابتدائي";
            $list_of_items[5] = "رابع ابتدائي";
            $list_of_items[6] = "خامس ابتدائي";
            $list_of_items[7] = "سادس ابتدائي";
        }

        if(AfwSession::config("level_1",true))    
        {
            $list_of_items[11] = "أولى متوسط";
            $list_of_items[12] = "ثاني متوسط";
            $list_of_items[13] = "ثالث متوسط";
        }

        if(AfwSession::config("level_2",true))
        {
            $list_of_items[21] = "أولى ثانوي";
            $list_of_items[22] = "ثاني ثانوي";
            $list_of_items[23] = "ثالث ثانوي";
            if(AfwSession::config("level_2_4",false)) $list_of_items[24] = "رابع ثانوي";
        }

        if(AfwSession::config("level_3_detailed",false))
        {
            $list_of_items[31] = "أولى جامعي";
            $list_of_items[32] = "ثاني جامعي";
            $list_of_items[33] = "ثالث جامعي";
            $list_of_items[34] = "رابع جامعي";
            $list_of_items[35] = "خامس جامعي";
        }
        if(AfwSession::config("level_3_grouped",true))
        {
            $list_of_items[31] = "جامعي";
        }

        return  $list_of_items;
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

    public function getAttributeLabel($attribute, $lang = 'ar', $short = false)
    {
        if(($attribute == "homework") or ($attribute == "homework2") or ($attribute == "mainwork"))
        {
            $courseObj = $this->hetCourse();
            if($courseObj) $return = $courseObj->getVal($attribute);
            else $return = "";
            if($return) return $return;
        }
        return AfwLanguageHelper::getAttributeTranslation($this, $attribute, $lang, $short);
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

            $chapter = $this->het("mainwork_start_chapter_id");
            if(!$chapter) return "لم يتم تحديد $chapter_name البداية";
            $chapter_title = $chapter->getDisplay($lang);
            $paragraph_num = $this->getVal("mainwork_start_paragraph_num");
            if(!$paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            
            
            if(AfwStringHelper::stringStartsWith($chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 
            
            
            $end_paragraph_num = "$paragraph_name ".$this->getVal("mainwork_end_paragraph_num");
            if(!$end_paragraph_num) $end_paragraph_num = "نهاية غير محددة";
            
            
            return "$chapter_name $chapter_title $paragraph_name $paragraph_num إلى $end_paragraph_num";

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

            $chapter = $this->het("homework_start_chapter_id");
            if(!$chapter) return "لم يتم تحديد $chapter_name البداية";
            $chapter_title = $chapter->getDisplay($lang);
            $paragraph_num = $this->getVal("homework_start_paragraph_num");
            if(!$paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            
            if(AfwStringHelper::stringStartsWith($chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 
            
            $end_paragraph_num = "$paragraph_name ".$this->getVal("homework_end_paragraph_num");
            if(!$end_paragraph_num) $end_paragraph_num = "نهاية غير محددة";
            
            
            return "$chapter_name $chapter_title $paragraph_name $paragraph_num إلى $end_paragraph_num";
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

            $chapter = $this->het("homework2_start_chapter_id");
            if(!$chapter) return "لم يتم تحديد $chapter_name البداية";
            $chapter_title = $chapter->getDisplay($lang);
            $paragraph_num = $this->getVal("homework2_start_paragraph_num");
            if(!$paragraph_num) return "لم يتم تحديد $paragraph_name البداية";

            
            if(AfwStringHelper::stringStartsWith($chapter_title, $chapter_name))
            {
                $chapter_name="";
            } 

            $end_paragraph_num = "$paragraph_name ".$this->getVal("homework2_end_paragraph_num");
            if(!$end_paragraph_num) $end_paragraph_num = "نهاية غير محددة";
            
            
            return "$chapter_name $chapter_title $paragraph_name $paragraph_num إلى $end_paragraph_num";
        }

        

        public function paragraphShort($lang="ar", $attribute)
        {            
            list($book_id, $paragraph_num, $chapter_id, $page_num, $prgh) = $this->getMyBookLocation($attribute);
            if(!$prgh) return "?!!!? [$chapter_id|$paragraph_num]";
            return AfwStringHelper::truncateArabicJomla($prgh->getVal("paragraph_text"), 32)."($paragraph_num)";
        }
        


        public function calcMainwork_start_paragraph_id($what="value")
        {
            return CpcBook::calcAttribute_paragraph_id($this, "mainwork_start", $what);
        }

        

        public function paragraphShortFromTo($lang="ar", $attribute)
        {
            return CpcBook::paragraphShortFromTo($this, $attribute);
        }

        public function getMyBookLocation($attribute)
        {
            return CpcBook::getBookLocation($this, $attribute, 1);
        }

        public function calcStudent_book($what="value", $book_id=0, $attribute=null)
        {
            $student_id = $this->getVal("student_id");
            if(!$book_id)
            {
                $courseObj = $this->hetCourse();
                if(!$courseObj)
                {
                    return [$book_id, $paragraph_num_to="", $chapter_id_to="", $page_num_to="", $toParagraph="", $part_id_to="", "No-course"];
                }
                else
                {
                    if($attribute) $book_id = $courseObj->getVal($attribute."_book_id");
                    if(!$book_id) $book_id = $courseObj->getVal("mainwork_book_id");
                    if(!$book_id) $book_id = $courseObj->getVal("homework_book_id");
                    if(!$book_id) $book_id = $courseObj->getVal("homework2_book_id");
                }
            }
            

            $SBObj = null;
            // انجاز الطالب واختياره المنهجي                
            if($book_id and $student_id) $SBObj = StudentBook::loadByMainIndex($student_id, $book_id);

            $SBObj_id = $SBObj ? $SBObj->id : 0;
            return ($what=="object") ? $SBObj : $SBObj_id;
        }

        public function getStudentBookLocation($attribute, $book_id=0)
        {
            $SBObj = $this->calcStudent_book("object", $book_id, $attribute);
            if($SBObj) return CpcBook::getBookLocation($SBObj, "main");
            else return [$book_id, $paragraph_num_to="", $chapter_id_to="", $page_num_to="", $toParagraph="", $part_id_to="", "No-StudentBook"];
        }
        

        public function getBookParams($attribute)
        {
            $return = CpcBook::getBookParams($this, $attribute);
            $return["strict_from"] = "true";
            return $return;
        }

        public function getManhajSens($attribute)
        {
            $mainwork_sens = $this->getVal("mainwork_sens") ? intval($this->getVal("mainwork_sens")) : 1;
            $mw_chapter_sens = 3 - 2*$mainwork_sens;
            $attribute_sens = $this->getVal($attribute."_sens");
            
            if($attribute_sens==3)
            {
                // نفس اتجاه الحفظ لكن عند الانتهاء يعكس الاتجاه
                // @todo
                $attribute_sens=1;
            }
            if($attribute=="homework") $chapter_sens = (3 - 2*$attribute_sens)*$mw_chapter_sens;
            elseif($attribute=="homework2") $chapter_sens = $mw_chapter_sens;
            else $chapter_sens = $mw_chapter_sens;
            
            return $chapter_sens;
        }

        public function getManhaj($attribute)
        {
            $mainwork_sens = $this->getVal("mainwork_sens") ? intval($this->getVal("mainwork_sens")) : 1;
            $mw_chapter_sens = 3 - 2*$mainwork_sens;
            $attribute_sens = $this->getVal($attribute."_sens");
            
            if($attribute_sens==3)
            {
                // نفس اتجاه الحفظ لكن عند الانتهاء يعكس الاتجاه
                // @todo
                $attribute_sens=1;
            }
            if($attribute=="homework") $chapter_sens = (3 - 2*$attribute_sens)*$mw_chapter_sens;
            elseif($attribute=="homework2") $chapter_sens = $mw_chapter_sens;
            else $chapter_sens = $mw_chapter_sens;
            $work_stop = $this->getVal($attribute."_stop");

            // default methods
            $lines_to_paragraph_method = "nearset";
            if($chapter_sens>0) $new_page_where = "end";
            else $new_page_where = "start";

            // value="1">نهاية الآية مع الزيادة
            if($work_stop==1) $lines_to_paragraph_method = "add";
            // value="2">رأس الآية مع النقص
            elseif($work_stop==2) $lines_to_paragraph_method = "remove";
            // value="3" >رأس الآية مع اختيار الأقرب
            elseif($work_stop==3) $lines_to_paragraph_method = "nearset";
            // value="4">نهاية الجزء اذا لم يبق عليه الا قليل
            elseif($work_stop==4) $new_page_where = "reach-end-part";
            // value="5">نهاية الوجه
            elseif($work_stop==5) $new_page_where = "end";
            // value="6">نهاية الجزء
            elseif($work_stop==6) $new_page_where = "end-part";
            // value="7">بداية الوجه
            elseif($work_stop==7) $new_page_where = "start";
            else $chapter_sens = 99;

            $delta_lines = intval($this->getVal($attribute."_nb_lines")) + 0;
            $delta_parts = intval($this->getVal($attribute."_nb_parts")) + 0;
            $delta_pages = intval($this->getVal($attribute."_nb_pages")) + 20*$delta_parts;
            $estimated_delta_pages = $delta_pages + 2 + round($delta_lines / 10);

            if((abs($delta_lines)+abs($delta_pages))==0) 
            {
                list($err, $inf, $war, $tech) = $this->updateWorkManhaj($attribute, "ar");
            }

            $delta_lines = intval($this->getVal($attribute."_nb_lines")) + 0;
            $delta_parts = intval($this->getVal($attribute."_nb_parts")) + 0;
            $delta_pages = intval($this->getVal($attribute."_nb_pages")) + 20*$delta_parts;
            $estimated_delta_pages = $delta_pages + 2 + round($delta_lines / 10);

            if((abs($delta_lines)+abs($delta_pages))==0) $chapter_sens = 88;

            return [$chapter_sens, $delta_lines, $delta_pages, $estimated_delta_pages, $lines_to_paragraph_method, $new_page_where, $err, $inf, $war, $tech];
        }

        public function isCorrectManhaj($attribute)
        {
            $chapter_sens = $this->getManhajSens($attribute);

            return (abs($chapter_sens)==1);
        }


        public function approveMainworkAndNext($lang="ar")
        {
            return $this->approveWorkAndNext("mainwork", $lang);
        }

        public function approveHomeworkAndNext($lang="ar")
        {
            return $this->approveWorkAndNext("homework", $lang);
        }

        public function approveHomework2AndNext($lang="ar")
        {
            //Obsoleted by rafik : $this->approveWorkAndNext("homework2", $lang);
            // السبب :
            // التراكمي ليس كالمراجعة يتحرك بل هو متعلق ولاصق في الحفظ الجديد
            // لا يتغير ولا يتحرك عنه الا اذا تغير الانجاز أو المنهج
            return $this->resetHomework2FromManhajAndInjaz($lang);
            
        }

        public function updateMainworkFromManhajAndInjaz($lang="ar", $reset=false)
        {
            return $this->updateWorkFromManhajAndInjaz("mainwork", $lang, $reset);
        }

        public function updateHomeworkFromManhajAndInjaz($lang="ar", $reset=false)
        {
            return $this->updateWorkFromManhajAndInjaz("homework", $lang, $reset);
        }

        public function updateHomework2FromManhajAndInjaz($lang="ar", $reset=false)
        {
            return $this->updateWorkFromManhajAndInjaz("homework2", $lang, $reset);
        }

        public function resetMainworkFromManhajAndInjaz($lang="ar")
        {
            return $this->updateMainworkFromManhajAndInjaz($lang, true);
        }

        public function resetHomeworkFromManhajAndInjaz($lang="ar")
        {
            return $this->updateHomeworkFromManhajAndInjaz($lang, true);
        }

        public function resetHomework2FromManhajAndInjaz($lang="ar")
        {
            return $this->updateHomework2FromManhajAndInjaz($lang, true);
        }

        public function calcMainwork_real_book_id($what="value")
        {
            return $this->calcWork_real_book_id("mainwork", $what);
        }
        


        public function calcHomework_real_book_id($what="value")
        {
            return $this->calcWork_real_book_id("homework", $what);
        }

        public function calcHomework2_real_book_id($what="value")
        {
            return $this->calcWork_real_book_id("homework2", $what);
        }
        

        public function calcWork_real_book_id($attribute, $what="value")
        {
            $book_id = $this->getVal($attribute."_start_book_id");    
            $chapter_sens = $this->getManhajSens($attribute);

            if(($chapter_sens==-1) and ($book_id==1))
            {
                return 10001;
            }
            else return $book_id;
        }

        public function calcMainwork_reel_end_page_num($what="value")
        {
            $attribute_case = "mainwork";
            $pos_case = "end";
            $part_id = $this->getVal($attribute_case."_".$pos_case."_part_id");
            $chapter_id = $this->getVal($attribute_case."_".$pos_case."_chapter_id");
            $paragraph_num = $this->getVal($attribute_case."_".$pos_case."_paragraph_num");

            if($part_id>31) $part_id = 10033 - $part_id;
            if($chapter_id>1114) $chapter_id = 12115 - $chapter_id;

            $pgh = CpcBookParagraph::loadByMainIndex(1,$part_id, $chapter_id, $paragraph_num);
            return $pgh ? $pgh->getVal("page_num") : 0;
        }
        
        public static function translateAttrib($attribute)
        {
            if($attribute=="mainwork") return "حفظ الجديد";
            if($attribute=="homework") return "المراجعة";
            if($attribute=="homework2") return "التثبيت (م.ص)";            
        }

        public function updateAllWorkFromManhajAndInjaz($lang="ar", $reset=false)
        {
            $attribute_arr = ["mainwork","homework","homework2"];
            return $this->updateWorkFromManhajAndInjaz($attribute_arr, $lang, $reset);
        }

        public function updateWorkFromManhajAndInjaz($attrib, $lang="ar", $reset=false)
        {
            
            $err_arr = [];
            $inf_arr = [];
            $war_arr = [];
            $tech_arr = [];

            if(is_array($attrib))
            {
                $attribute_arr = $attrib;
            }
            else
            {
                $attribute_arr = [];
                $attribute_arr[] = $attrib;
            }

            foreach($attribute_arr as $attribute)
            {
                $attribute_trans = self::translateAttrib($attribute);
                list($chapter_sens, $delta_lines, $delta_pages, $estimated_delta_pages, $lines_to_paragraph_method, $new_page_where, $err, $inf, $war, $tech) = $this->getManhaj($attribute);
                if(abs($chapter_sens)!=1)
                {
                    $err.=" $attribute_trans : المنهج غير محدد أو غير مكتمل CHAPTER-SENS=$chapter_sens";
                    return [$err, $inf, $war, $tech];
                }

                $book_id = $this->calc($attribute."_real_book_id");
                $tech_arr[] = "book_id=calc($attribute._real_book_id)=[$book_id]";
                // if we have inversed the book from 1 to 10001 no need to keep $chapter_sens=-1
                if(($chapter_sens==-1) and ($book_id==10001)) $chapter_sens = 1;
                $tech_arr[] = "this->getManhaj($attribute) => (chapter_sens=$chapter_sens, delta_lines=$delta_lines, delta_pages=$delta_pages, estimated_delta_pages=$estimated_delta_pages, lines_to_paragraph_method=$lines_to_paragraph_method, new_page_where=$new_page_where)";

                $workExists = (($this->getVal($attribute."_start_paragraph_num")>0) and ($this->getVal($attribute."_end_paragraph_num")>0));
                
                if($workExists and (!$reset))
                {
                    //throw new AfwRuntimeException("why get here ?");
                    $war_arr[] = "$attribute_trans : يوجد في هذا الملف أعمال جارية سيتم الابقاء عليها. اذا اردت تصفيرها فاستخدم زر التصفير";

                    return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
                }

                $new_chapter_method="goon";

                if($attribute=="mainwork")
                {
                    list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $toParagraph, $part_id_to, $log) = $this->getStudentBookLocation($attribute);
                    $tech_arr[] = "this->getStudentBookLocation() => (paragraph_num_to=$paragraph_num_to, chapter_id_to=$chapter_id_to, page_num_to=$page_num_to, estimated_delta_pages=$estimated_delta_pages)";

                

                    if(!$part_id_to and !$chapter_id_to)
                    {
                        $tech_arr[] = "list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $toParagraph, $part_id_to, $log) = $this --> getStudentBookLocation() : <br>\n";
                        $war_arr[] = "$attribute_trans : لم يتم تحديد انجاز الطالب يمكنك مراجعة المشرف لمزيد من التفاصيل الفنية";
                        return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
                    }
                    

                    
                    if($paragraph_num_to and $page_num_to and $chapter_id_to)
                    {
                        list($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $log1_arr) 
                        = CpcBookParagraph::moveInParagraphs($book_id, $part_id_to, $chapter_id_to, $page_num_to, $paragraph_num_to, 
                                                $chapter_sens, 1, 0, 0, $lines_to_paragraph_method, $new_page_where, $new_chapter_method, 1, true);
                    }
                    else
                    {
                        $war_arr[] = "$attribute : moveInParagraphs skipped [from paragraph_num=$paragraph_num_to page_num=$page_num_to chapter_id=$chapter_id_to] one of them is missed";
                    }
                }
                elseif($attribute=="homework")
                {
                    $log0_arr = [];

                    $mainwork_sens = 3 - 2*$this->getVal("mainwork_sens");
                    $homework_sens = $this->getVal("homework_sens");

                    // 3] = "نفس اتجاه الحفظ لكن عند الانتهاء يعكس الاتجاه";
                    if($homework_sens==3) 
                    {
                        $homework_sens=1;
                    }
                    // 1] = "نفس اتجاه الحفظ";
                    if($homework_sens==1) $work_sens = $mainwork_sens;
                    // 2] = "عكس اتجاه الحفظ";
                    if($homework_sens==2) $work_sens = -$mainwork_sens;

                    $log0_arr[] = "mainwork_sens=$mainwork_sens homework_sens=$homework_sens work_sens=$work_sens";

                    // @todo-asked-abd-samad bring from setted config

                    list($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $log1_arr) 
                        = CpcBookParagraph::resetToFirstParagraph($book_id, $work_sens);

                    $log0_arr[] = "list(book_id=$book_id, new_part_id_from=$new_part_id_from, new_chapter_id_from=$new_chapter_id_from, 
                                        new_page_num_from=$new_page_num_from, new_paragraph_num_from=$new_paragraph_num_from, ) = CpcBookParagraph::resetToFirstParagraph($book_id, $work_sens)";    

                    $log1_arr = array_merge($log0_arr, $log1_arr);    

                    if(!$new_chapter_id_from or !$new_page_num_from or !$new_paragraph_num_from)
                    {
                        throw new AfwRuntimeException("list($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, ) = CpcBookParagraph::resetToFirstParagraph(book=$book_id, sens=$work_sens)");
                    }                    

                }
                elseif($attribute=="homework2")
                {
                    list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $toParagraph, $part_id_to, $log) = $this->getStudentBookLocation($attribute);
                    $tech_arr[] = "this->getStudentBookLocation() => (paragraph_num_to=$paragraph_num_to, chapter_id_to=$chapter_id_to, page_num_to=$page_num_to, estimated_delta_pages=$estimated_delta_pages)";

                    // die("here rafik 1973");

                    if(!$part_id_to and !$chapter_id_to)
                    {
                        //$tech_arr[] = "list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $toParagraph, $part_id_to, $log) = $this --> getStudentBookLocation() : <br>\n";
                        $war_arr[] = "$attribute_trans : لم يتم تحديد انجاز الطالب يمكنك مراجعة المشرف لمزيد من التفاصيل الفنية";
                        return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
                    }
                    

                    

                    $estimated_delta_pages = -(abs($delta_pages) + 2 + round(abs($delta_lines) / 10));
                    if($paragraph_num_to and $page_num_to and $chapter_id_to)
                    {
                        list($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $log1_arr) 
                            = CpcBookParagraph::moveInParagraphs($book_id, $part_id_to, $chapter_id_to, $page_num_to, $paragraph_num_to, 
                                                    $chapter_sens, 0, -$delta_lines, - $delta_pages, $lines_to_paragraph_method, "start", $new_chapter_method, $estimated_delta_pages, true);

                        $new_part_id_to       = $part_id_to;
                        $new_chapter_id_to    = $chapter_id_to;
                        $new_page_num_to      = $page_num_to;
                        $new_paragraph_num_to = $paragraph_num_to;  

                        if(($new_part_id_to and ($new_part_id_to!="not found")) and
                           ($new_chapter_id_to and ($new_chapter_id_to!="not found")) and
                           ($new_page_num_to and ($new_page_num_to!="not found")))
                        {

                            list($err,$inf,$war,$tech) = $this->setNextWork($attribute, $book_id, 
                                                    $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                                                    $new_part_id_to,   $new_chapter_id_to,   $new_page_num_to,   $new_paragraph_num_to, 
                                                    $tech_arr, $log1_arr, $lang); 

                            if($err) $err_arr[] = "$attribute_trans : ".$err;
                            if($inf) $inf_arr[] = "$attribute_trans : ".$inf;
                            if($war) $war_arr[] = "$attribute_trans : ".$war;
                            if($tech) $tech_arr[] = $tech;                                                
                        }
                        else
                        {
                            $err_arr[] = "$attribute_trans : CpcBookParagraph::moveInParagraphs from $book_id, $part_id_to, $chapter_id_to, $page_num_to, $paragraph_num_to, $chapter_sens, 0, -$delta_lines, - $delta_pages, $lines_to_paragraph_method => not found";
                        }
                    }
                    else
                    {
                        $war_arr[] = "$attribute : moveInParagraphs+setNextWork skipped [from paragraph_num=$paragraph_num_to page_num=$page_num_to chapter_id=$chapter_id_to] one of them is missed";
                    }                                           

                }

                
    
                
                
                
                if($new_part_id_from == "not found")
                {
                    $war_arr[] = "$attribute_trans : حصل خطأ أثناء تحديث الواجب";
                    $tech_arr[] = "log of move to new start position : <br>\n".implode("<br>\n", $log1_arr);
                    
                    return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
                }
                
                if($new_chapter_id_from and $new_page_num_from and $new_paragraph_num_from)                
                {
                    list($err,$inf,$war,$tech) = $this->moveToNextWork($attribute, $book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                    $chapter_sens, $delta_lines, $delta_pages, 
                    $new_chapter_method, $estimated_delta_pages, $log1_arr, $lang, $new_page_where, $lines_to_paragraph_method);

                    if($err) $err_arr[] = "$attribute_trans : ".$err;
                    if($inf) $inf_arr[] = "$attribute_trans : ".$inf;
                    if($war) $war_arr[] = "$attribute_trans : ".$war;
                    if($tech) $tech_arr[] = $tech;                            
                }
                else
                {
                    $war_arr[] = "moveToNextWork skipped [from paragraph_num=$new_paragraph_num_from page_num=$new_page_num_from chapter_id=$new_chapter_id_from] one of them is missed";
                }
                
                
            }

            return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
        }
        

        public function approveWorkAndNext($attribute, $lang="ar")
        {
            
            list($chapter_sens, $delta_lines, $delta_pages, $estimated_delta_pages, $lines_to_paragraph_method, $new_page_where) = $this->getManhaj($attribute);

            if(abs($chapter_sens)!=1)
            {
                return ["المنهج غير محدد أو غير مكتمل CS=$chapter_sens",""];
            }

            $log0_arr = [];

            $log0_arr[] = "getManhaj($attribute) => (CS=$chapter_sens, DL=$delta_lines, DP=$delta_pages, ESP=$estimated_delta_pages)";


            //list($book_id, $paragraph_num_from, $chapter_id_from, $page_num_from, $fromParagraph, $part_id_from) = $this->getMyBookLocation($attribute."_start");
            list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $toParagraph, $part_id_to) = $this->getMyBookLocation($attribute."_end");

            $log0_arr[] = "getMyBookLocation($attribute _end) => (BK=$book_id, PGPH=$paragraph_num_to, CHP=$chapter_id_to, PAGE=$page_num_to, .., PART=$part_id_to)";
            
            $new_chapter_method="goon";

            list($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $log1_arr) 
              = CpcBookParagraph::moveInParagraphs($book_id, $part_id_to, $chapter_id_to, $page_num_to, $paragraph_num_to, 
                                        $chapter_sens, 1, 0, 0, $lines_to_paragraph_method, $new_page_where, $new_chapter_method, 1, true);

            $log1_arr = array_merge($log0_arr, $log1_arr);
        
            if($new_part_id_from == "not found")
            {
                $war = "There are more technical details with administrator<br>\n<div class='technical'>log of move to new start position : <br>\n".implode("<br>\n", $log1_arr)."</div>";
                
                return ["حصل خطأ أثناء تحديد الواجب الجديد","",$war];
            }
            return $this->moveToNextWork($attribute, $book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                                        $chapter_sens, $delta_lines, $delta_pages, 
                                        $new_chapter_method, $estimated_delta_pages, $log1_arr, $lang, $new_page_where, $lines_to_paragraph_method);
        }

        
        public function setNextWork($attribute, $book_id, 
                        $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                        $new_part_id_to,   $new_chapter_id_to,   $new_page_num_to,   $new_paragraph_num_to, 
                        $log1_arr, $log2_arr, $lang)
        {
            $studentObj = $this->het('student_id');
            if($studentObj) $student_disp = $studentObj->getShortDisplay($lang);
            else $student_disp = "no-student !!!";

            $this->set($attribute."_start_part_id", $new_part_id_from);
            $this->set($attribute."_start_chapter_id", $new_chapter_id_from);
            $this->set($attribute."_start_page_num", $new_page_num_from);
            $this->set($attribute."_start_paragraph_num", $new_paragraph_num_from);

            $this->set($attribute."_end_part_id", $new_part_id_to);
            $this->set($attribute."_end_chapter_id", $new_chapter_id_to);
            $this->set($attribute."_end_page_num", $new_page_num_to);
            $this->set($attribute."_end_paragraph_num", $new_paragraph_num_to);

            $this->commit();

            if($log1_arr and is_array($log1_arr)) $tech_info = "log of move to new start position : <br>\n".implode("<br>\n", $log1_arr);
            if($log2_arr and is_array($log2_arr)) $tech_info .= "<br>\nlog of move to new end position : <br>\n".implode("<br>\n", $log2_arr);
            $tech_info .= "<br>\n<h1>";
            $tech_info .= "$attribute $student_disp From [part$new_part_id_from, chapter$new_chapter_id_from, page$new_page_num_from, aya$new_paragraph_num_from]";            
            $tech_info .= "$attribute $student_disp To   [part$new_part_id_to,   chapter$new_chapter_id_to,   page$new_page_num_to,   aya$new_paragraph_num_to]";

            $tech_info .= "this->set($attribute._start_part_id, $new_part_id_from)<br>\n";
            $tech_info .= "this->set($attribute._start_chapter_id, $new_chapter_id_from)<br>\n";
            $tech_info .= "this->set($attribute._start_page_num, $new_page_num_from)<br>\n";
            $tech_info .= "this->set($attribute._start_paragraph_num, $new_paragraph_num_from)<br>\n";

            $tech_info .= "this->set($attribute._end_part_id, $new_part_id_to)<br>\n";
            $tech_info .= "this->set($attribute._end_chapter_id, $new_chapter_id_to)<br>\n";
            $tech_info .= "this->set($attribute._end_page_num, $new_page_num_to)<br>\n";
            $tech_info .= "this->set($attribute._end_paragraph_num, $new_paragraph_num_to)<br>\n";

            $tech_info .= "<br>\n</h1>";


            return ["","تم تحديد الواجب الجديد","",$tech_info];
        }


        public function moveToNextWork($attribute, $book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $chapter_sens, $delta_lines, $delta_pages, $new_chapter_method, $estimated_delta_pages, $log1_arr, $lang="ar", $new_page_where = "end", $lines_to_paragraph_method = "nearset")
        {

            list($book_id, $new_part_id_to, $new_chapter_id_to, $new_page_num_to, $new_paragraph_num_to, $log2_arr) 
              = CpcBookParagraph::moveInParagraphs($book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                                        $chapter_sens, 0, $delta_lines, $delta_pages, 
                                        $lines_to_paragraph_method, $new_page_where, $new_chapter_method,
                                        $estimated_delta_pages, true);

            if(($new_part_id_to and ($new_part_id_to!="not found")) and
            ($new_chapter_id_to and ($new_chapter_id_to!="not found")) and
            ($new_page_num_to and ($new_page_num_to!="not found")))
            {

                return $this->setNextWork($attribute, $book_id, 
                    $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, 
                    $new_part_id_to,   $new_chapter_id_to,   $new_page_num_to,   $new_paragraph_num_to, 
                    $log1_arr, $log2_arr, $lang);                                                
            }
            else
            {
                return ["$attribute : CpcBookParagraph::moveInParagraphs from $book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $chapter_sens, 0, $delta_lines, $delta_pages, $lines_to_paragraph_method, $new_page_where, $new_chapter_method, $estimated_delta_pages => not found",""];
            }                                        

            
        }


        protected function getPublicMethods()
        {
            global $lang;
            $pbms = array();

            // $currSYObj = $this->getCurrentSchoolYear();
            // $disp = $this->getDisplay($lang);

            $pbms["xWa5YO"] = array(
                "METHOD" => "approveMainworkAndNext",
                "LABEL_AR" => "تم انجاز الحفظ انتقل الى ما يليه",
                "LABEL_EN" => "approve Main work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "green",
                "STEP" => 1
            );

            $pbms["xYa5Zu"] = array(
                "METHOD" => "approveHomeworkAndNext",
                "LABEL_AR" => "تم انجاز المراجعة انتقل الى ما يليه",
                "LABEL_EN" => "approve Home work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "blue",
                "STEP" => 2
            );

            $pbms["hja5Op"] = array(
                "METHOD" => "approveHomework2AndNext",
                "LABEL_AR" => "تم انجاز التثبيت (م.ص) انتقل الى ما يليه",
                "LABEL_EN" => "approve Home work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "orange",
                "STEP" => 3
            );


            

            $methodName =  "updateStudentSessionWithMe";
            $color = "blue";
            $title_ar = "تحديث الحصة الحالية من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>1);
            

            $methodName =  "updateMainworkFromManhajAndInjaz";
            $color = "green";
            $title_ar = "تحديث الحفظ الجديد من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>1);
            $methodName =  "resetMainworkFromManhajAndInjaz";
            $color = "red";
            $title_ar = "تصفير الحفظ الجديد من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>1);
                                                                
        
            $methodName =  "updateHomeworkFromManhajAndInjaz";
            $color = "blue";
            $title_ar = "تحديث المراجعة من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>2);

            $methodName =  "resetHomeworkFromManhajAndInjaz";
            $color = "red";
            $title_ar = "تصفير المراجعة من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>2);
                                                                                                                                        
        
        
            $methodName =  "updateHomework2FromManhajAndInjaz";
            $color = "orange";
            $title_ar = "تحديث التثبيت (م.ص) من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>3);

            $methodName =  "resetHomework2FromManhajAndInjaz";
            $color = "red";
            $title_ar = "تصفير التثبيت (م.ص) من خلال الاعدادات"; 
            $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                    "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                    "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>3);
                                                        
            if(!$this->isCorrectManhaj("mainwork") or true)
            {
                $methodName =  "updateMainworkManhaj";
                $color = "green";
                $title_ar = "تحديث منهج الحفظ"; 
                $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                      "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                      "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>4);
            
            }

            if(!$this->isCorrectManhaj("homework") or true)
            {
                $methodName =  "updateHomeworkManhaj";
                $color = "blue";
                $title_ar = "تحديث منهج المراجعة"; 
                $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                      "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                      "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>4);
            
            }

            if(!$this->isCorrectManhaj("homework2") or true)
            {
                $methodName =  "updateHomework2Manhaj";
                $color = "orange";
                $title_ar = "تحديث منهج التثبيت (م.ص)"; 
                $pbms[substr(md5($methodName.$title_ar),1,5)] = array("METHOD"=>$methodName,
                                                                      "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                                                                      "ADMIN-ONLY"=>true, "BF-ID"=>"", "STEP"=>4);
            
            }

            

            return $pbms;
        }
        

        

          
            
        public static function list_of_page_nums($chapter_id, $part_id)     
        {
            $server_db_prefix = AfwSession::currentDBPrefix();
            if(!$chapter_id) $chapter_id = 0;
            if(!$part_id) $part_id = 0;
            if($chapter_id>0)
            {
                $row = AfwDatabase::db_recup_row("select min(page_num) as min_pnum, max(page_num) as max_pnum 
                            from ".$server_db_prefix."sis.cpc_book_paragraph 
                            where chapter_id = $chapter_id
                            and ($part_id=0 or part_id = $part_id)");
            }
            $result = array();
            $title = "الوجه";

            for($pnum = $row["min_pnum"]; $pnum <= $row["max_pnum"]; $pnum++)
            {
                $result[$pnum] = $title." ".$pnum;
            }

            return $result;
        }

        public function list_of_mainwork_end_page_num()
        {
            $part_id =    $this->getVal("mainwork_endt_part_id");
            $chapter_id = $this->getVal("mainwork_end_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_mainwork_start_page_num()
        {
            $part_id =    $this->getVal("mainwork_start_part_id");
            $chapter_id = $this->getVal("mainwork_start_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework_end_page_num()
        {
            $part_id =    $this->getVal("homework_end_part_id");
            $chapter_id = $this->getVal("homework_end_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework_start_page_num()
        {
            $part_id =    $this->getVal("homework_start_part_id");
            $chapter_id = $this->getVal("homework_start_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework2_end_page_num()
        {
            $part_id =    $this->getVal("homework2_end_part_id");
            $chapter_id = $this->getVal("homework2_end_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }

        public function list_of_homework2_start_page_num()
        {
            $part_id =    $this->getVal("homework2_start_part_id");
            $chapter_id = $this->getVal("homework2_start_chapter_id");

            return self::list_of_page_nums($chapter_id, $part_id);
        }


        public function list_of_homework2_sens()
        {
            global $lang;
            return StudyProgram::workStartAndSens()[$lang];
        }

        public function list_of_homework2_stop()
        {
            global $lang;
            return StudyProgram::workStop()[$lang];
        }

        public function list_of_homework_sens()
        {
            global $lang;
            return StudyProgram::workSensRelative()[$lang];
        }

        public function list_of_homework_stop()
        {
            global $lang;
            return StudyProgram::workStop()[$lang];
        }

        public function list_of_mainwork_sens()
        {
            global $lang;
            return StudyProgram::workSens()[$lang];
        }

        public function list_of_mainwork_stop()
        {
            global $lang;
            return StudyProgram::workStop()[$lang];
        }

        

        public function updateMainworkManhaj($lang="ar")
        {
            return $this->updateWorkManhaj("mainwork", $lang);
        }

        public function updateHomeworkManhaj($lang="ar")
        {
            return $this->updateWorkManhaj("homework", $lang);
        }

        public function updateHomework2Manhaj($lang="ar")
        {
            return $this->updateWorkManhaj("homework2", $lang);
        }

        protected function beforeSetAttribute($attribute, $newvalue)
        {
            $oldvalue = $this->getVal($attribute);
            /*
            if(($attribute=="homework_start_paragraph_num") and ($newvalue==1))
            {
                throw new AfwRuntimeException("before set attribute $attribute from '$oldvalue' to '$newvalue'");
            }*/
            
            return true;
        }

        public function getMainBookList($lang)
        {
            $mainBookList = [];

            $courseObj = $this->hetCourse();
            if(!$courseObj) return [];

            $attribute_arr = ["mainwork"]; // ,"homework","homework2"];            

            foreach($attribute_arr as $attribute)
            {
                $obj_book_id = $courseObj->getVal($attribute."_book_id");
                if($obj_book_id and (!$mainBookList[$obj_book_id]))
                {
                    $bookObj = $courseObj->het($attribute."_book_id");
                    $mainBookList[$obj_book_id] = $bookObj->getDisplay($lang);
                }
            }

            return $mainBookList;
        }

        public static function updateAllWorkForStudentFileCourseList($studentFileCourseList, $lang = "ar", $reset=false, $same_school_class=false)
        {
                $err_arr = [];
                $inf_arr = [];
                $war_arr = [];
                $tech_arr = [];

                global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
                $old_nb_queries_executed = $nb_queries_executed;
                $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
                $MODE_SQL_PROCESS_LOURD = true;

                if(count($studentFileCourseList)==0)
                {
                    $err_arr[] = "يجب أولا انشاء سجلات متابعة الاتجاز قبل تحديثها";
                }

                $schoolClassCourseItem = null;

                foreach($studentFileCourseList as $studentFileCourseItem)
                {
                    if(!$same_school_class)
                    {
                        unset($schoolClassCourseItem);
                        $schoolClassCourseItem = null;
                    }
                    $studentObj = $studentFileCourseItem->het('student_id');
                    if(!$schoolClassCourseItem) $schoolClassCourseItem = $studentFileCourseItem->calcSchool_class_course_id("object");
                    
                    if($studentObj)
                    {
                        $student_disp = $studentObj->getShortDisplay($lang);
                        
                        if($schoolClassCourseItem)                        
                        {
                            // when the school class for this course has choosed to force same work location for all students
                            if($schoolClassCourseItem->sureIs("force_same_work"))
                            {
                                $mainBookList = $studentFileCourseItem->getMainBookList($lang);
                                list($err, $inf, $war, $studentBookList) = $studentObj->generateStudentBooks($lang, $mainBookList);
                                if($err) $err_arr[] = "$student_disp (ت.انجاز): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (ت.انجاز): ".$inf;
                                if($war) $war_arr[] = "$student_disp (ت.انجاز): ".$war;

                                foreach($studentBookList as $studentBookItem)
                                {
                                    $book_id = $studentBookItem->getVal("main_book_id");
                                    $defaultInjaz = $schoolClassCourseItem->getDefaultInjaz($book_id);
                                    if($defaultInjaz)
                                    {
                                        list($err,$inf,$war,$tech) = $studentBookItem->updateToDefaultInjaz($defaultInjaz, $only_if_empty=(!$reset));
                                        if($err) $err_arr[] = "$student_disp كناب-$book_id (نفس.انجاز): ".$err;
                                        if($inf) $inf_arr[] = "$student_disp كناب-$book_id (نفس.انجاز): ".$inf;
                                        if($war) $war_arr[] = "$student_disp كناب-$book_id (نفس.انجاز): ".$war;
                                        if($tech) $tech_arr[] = $tech;
                                    }
                                    else
                                    {
                                        $war_arr[] = "كناب-$book_id نفس الانجاز الافتراضي غير متوفر للحلقة ".$schoolClassCourseItem->getDisplay($lang);
                                    }
                                    
                                }
                            }
                            else
                            {
                                $inf_arr[] = " (ح) = الحفظ";
                                $inf_arr[] = " (م-ح) = منهج الحفظ";
                                
                                $inf_arr[] = " (م.ك) = مراجعة .ك";
                                $inf_arr[] = " (م-م.ك) = منهج م.ك";
        
                                $inf_arr[] = " (م.ص) = مراجعة .ص";
                                $inf_arr[] = " (م-م.ص) = منهج م.ص";
                                
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateMainworkManhaj($lang);
                                if($err) $err_arr[] = "$student_disp (م-ح): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (م-ح): ".$inf;
                                if($war) $war_arr[] = "$student_disp (م-ح): ".$war;
                                if($tech) $tech_arr[] = $tech;
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateMainworkFromManhajAndInjaz($lang, $reset);
                                if($err) $err_arr[] = "$student_disp (ح): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (ح): ".$inf;
                                if($war) $war_arr[] = "$student_disp (ح): ".$war;
                                if($tech) $tech_arr[] = $tech;
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateHomeworkManhaj($lang);
                                if($err) $err_arr[] = "$student_disp (م-م.ك): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (م-م.ك): ".$inf;
                                if($war) $war_arr[] = "$student_disp (م-م.ك): ".$war;
                                if($tech) $tech_arr[] = $tech;
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateHomeworkFromManhajAndInjaz($lang, $reset);
                                if($err) $err_arr[] = "$student_disp (م.ك): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (م.ك): ".$inf;
                                if($war) $war_arr[] = "$student_disp (م.ك): ".$war;
                                if($tech) $tech_arr[] = $tech;
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateHomework2Manhaj($lang);
                                if($err) $err_arr[] = "$student_disp (م-م.ص): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (م-م.ص): ".$inf;
                                if($war) $war_arr[] = "$student_disp (م-م.ص): ".$war;
                                if($tech) $tech_arr[] = $tech;
        
                                list($err,$inf,$war,$tech) = $studentFileCourseItem->updateHomework2FromManhajAndInjaz($lang, $reset);
                                if($err) $err_arr[] = "$student_disp (م.ص): ".$err;
                                if($inf) $inf_arr[] = "$student_disp (م.ص): ".$inf;
                                if($war) $war_arr[] = "$student_disp (م.ص): ".$war;
                                if($tech) $tech_arr[] = $tech;
                            }
                            
                        }
                        else
                        {
                            $err_arr[] = "$studentFileCourseItem : School_class_course not found";
                        }
                        
                        
                    }
                    else
                    {
                        $err_arr[] = "$studentFileCourseItem : student not found";
                    }    
                }

                $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
                $nb_queries_executed = $old_nb_queries_executed;

                return [$err_arr, $inf_arr, $war_arr, $tech_arr];

        }

         

        

        public function updateWorkManhaj($attribute, $lang="ar", $studyProgramObj=null, $scObj=null, $sccObj=null, $commit=true)
        {
            global $arr_SBObj;

            $err_arr = [];
            $inf_arr = [];
            $war_arr = [];
            $tech_arr = [];


            // $student_disp = $this->showAttribute("student_id");

            $student_id = $this->getVal("student_id");
            $courseObj = $this->hetCourse();
            if(!$studyProgramObj) $studyProgramObj = $this->het("study_program_id");
            if(!$courseObj)
            {
                $err_arr[] = "لم يتم العثور على المادة الدراسية في سجل المتابعة الحالي : ".$this->getDisplay($lang);; 
            }
            else
            {
                $book_id = $courseObj->getVal($attribute."_book_id");
                // انجاز الطالب واختياره المنهجي
                if($arr_SBObj["$student_id-$book_id"]) 
                {
                    $SBObj = $arr_SBObj["$student_id-$book_id"];
                }
                else
                {
                    $arr_SBObj["$student_id-$book_id"] = $SBObj = StudentBook::loadByMainIndex($student_id, $book_id);
                }
                
                if($SBObj)
                {
                    $inf_arr[] = "تم العثور على انجاز الطالب واختياره المنهجي";
                    $work_sens = $SBObj->getVal("main_sens");
                    $work_nb_parts = 0;
                    $new_nb_pages = $work_nb_pages = $SBObj->getVal("mainwork_nb_pages");
                    $new_nb_lines = $work_nb_lines = $SBObj->getVal("mainwork_nb_lines");

                    if(!$new_nb_pages and !$new_nb_lines)
                    {
                        $war_arr[] = "لم يتم تحديد مقدار الحفظ الجديد في سجل انجاز الطالب واختياره المنهجي";    
                    }
                    
                    $total_nb_pages = $SBObj->getTotalNbPages();
                    if(!$total_nb_pages)
                    {
                        $war_arr[] = "لم يتم تحديد مقدار كامل المحفوظ في سجل انجاز الطالب واختياره المنهجي";    
                    }
                    
                    if(!$studyProgramObj)
                    {
                        // get study program (منهج تعليمي) from student book
                        $studyProgramObj = $SBObj->het("study_program_id");
                    }
                }
                else
                {
                    $war_arr[] = "لم يتم العثور على انجاز الطالب واختياره المنهجي : برمز STD$student_id-BK$book_id";
                }
            }
            
            if(($attribute == "homework") or ($attribute == "homework2"))
            {
                // general manhaj for school class course
                if(!$studyProgramObj)
                {
                    if(!$sccObj) $sccObj = $this->calcSchool_class_course_id("object");
                    if($sccObj)
                    {
                        $sccObj_display = $sccObj->getDisplay($lang);
                        $studyProgramObj = $sccObj->het("study_program_id");
                        if($studyProgramObj) $inf_arr[] = "تم العثور على منهج المقرر العلمي وهو : ".$studyProgramObj->getDisplay($lang);
                    }
                    else $war_arr[] = $this->getDisplay($lang)." : لم يتم العثور على المقرر العلمي";
                    
                }

                // general manhaj for school class
                if(!$studyProgramObj)
                {
                    $mess_war = "$sccObj_display : لم يتم العثور على منهج المقرر العلمي";
                    if(AfwStringHelper::stringContain($sccObj_display, "قرآن تفسير")) throw new AfwRuntimeException($mess_war);
                    $war_arr[] = $mess_war;
                    if(!$scObj) $scObj = $this->calcSchool_class_id("object");
                    if($scObj)
                    {
                        $scObj_display = $scObj->getDisplay($lang);
                        $studyProgramObj = $scObj->het("study_program_id");
                        if($studyProgramObj) $inf_arr[] = "تم العثور على منهج الحلقة وهو : ".$studyProgramObj->getDisplay($lang);
                    }
                    else $war_arr[] = $this->getDisplay($lang)." : لم يتم العثور على الحلقة";
                }

                // general manhaj for school
                if(!$studyProgramObj)
                {
                    $war_arr[] = "$scObj_display : لم يتم العثور على منهج الحلقة";                
                    $schoolObj = $this->het("school_id");
                    if($schoolObj)
                    {
                        $schoolObj_display = $schoolObj->getDisplay($lang);
                        $studyProgramObj = $schoolObj->het("study_program_id");
                        if($studyProgramObj) $inf_arr[] = "تم العثور على منهج المنشأة وهو : ".$studyProgramObj->getDisplay($lang);
                        else $war_arr[] = "$schoolObj_display : لم يتم العثور على منهج المنشأة"; 
                    }
                    else $war_arr[] = $this->getDisplay($lang)." : لم يتم العثور على المنشأة";
                }

                if($studyProgramObj)
                {
                    list($work_nb_parts,$work_nb_pages,$work_nb_lines, $ruleObj, $work_nb_parts_attr, $work_nb_lines_attr, $work_nb_pages_attr, $sql)
                      = $studyProgramObj->getWorkCoefs($new_nb_pages, $new_nb_lines, $total_nb_pages, $attribute);

                    if($ruleObj)  
                    {
                        $inf_arr[] = " قاعدة المنهج المستخدمة هي ".$ruleObj->getDisplay($lang);
                        $inf_arr[] = " وفيها عدد الأجزاء = ".$work_nb_parts;
                        $inf_arr[] = " وفيها عدد الأوجه = ".$work_nb_pages;
                        $inf_arr[] = " وفيها عدد الأسطر = ".$work_nb_lines;
                    }
                    else
                    {
                        $war_arr[] = " قاعدة المنهج مفقودة";
                        $tech_arr[] = "sql=$sql";
                        $work_nb_parts = 0;
                        $work_nb_pages = 0;
                        $work_nb_lines = 0;
                    }
                    
                                           

                    /*  
                    $war_arr[] = " $studyProgramObj --> getWorkCoefs($new_nb_pages, $new_nb_lines, $total_nb_pages, $attribute) => <br> 
                                work_nb_parts=$work_nb_parts <br> 
                                work_nb_pages=$work_nb_pages <br> 
                                work_nb_lines=$work_nb_lines <br> 
                                ruleObj=$ruleObj <br> 
                                parts-attr=$work_nb_parts_attr <br> 
                                lines-attr=$work_nb_lines_attr <br> 
                                pages-attr=$work_nb_pages_attr <br>
                                sql=$sql <br>";   */
                }
                else
                {
                    $war_arr[] = "المنهج مفقود من جميع العناصر المقرر والحلقة والمنشأة"; 
                    $work_nb_parts = 0;
                    $work_nb_pages = 0;
                    $work_nb_lines = 0;
                }
            }

            if($attribute == "homework") $work_sens = 1;
            if($attribute == "homework2") $work_sens = 1;

            if($studyProgramObj) $this->set('study_program_id', $studyProgramObj->id);


            if(!$this->getVal($attribute.'_sens') and $work_sens)
            {
                $this->set($attribute.'_sens', $work_sens);
            }

            if(!$work_nb_parts) $work_nb_parts = 0;
            if(!$work_nb_pages) $work_nb_pages = 0;
            if(!$work_nb_lines) $work_nb_lines = 0;

            if((300*$work_nb_parts+15*$work_nb_pages+$work_nb_lines) > 0)
            {
                $this->setForce($attribute.'_nb_parts', $work_nb_parts, true);
                $this->setForce($attribute.'_nb_pages', $work_nb_pages, true);
                $this->setForce($attribute.'_nb_lines', $work_nb_lines, true);
            }

            if(!$this->getVal($attribute.'_stop'))
            {
                if(!$studyProgramObj)
                {
                    if($attribute=="mainwork")
                    {
                        // رأس الآية مع اختيار الأقرب
                        $this->set($attribute.'_stop', 3);
                    }
        
                    if($attribute=="homework")
                    {
                        // نهاية الجزء اذا لم يبق عليه الا قليل
                        // أي ونهاية الوجه في الحالة الأخرى @todo
                        $this->set($attribute.'_stop', 4);
                    }
        
                    if($attribute=="homework2")
                    {
                        // بداية الوجه
                        $this->set($attribute.'_stop', 7);
                    }
                }
                else
                {
                    $this->set($attribute.'_stop', $studyProgramObj->getVal($attribute.'_stop'));
                }
            }

            if(!$this->getVal($attribute.'_update'))
            {
                $this->set($attribute.'_update', 'Y');                
            }

            if($commit) $this->commit();

            // $inf_arr[] = "end of updateWorkManhaj($attribute, $lang)";

            return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr,"<br>\n",$tech_arr);
            
        }


        public function optimizeQEditLookups($submode = "", $fgroup = "")
        {
            // load lookup of students as optimization
            if($this->class_origin == "SchoolClass")
            {
                $school_class_id = $this->id_origin;
                $objSchoolClass = SchoolClass::loadById($school_class_id);
                $arrStudentIds = $objSchoolClass->getRelation("stdn")->getArray("student_id");
                AfwLoadHelper::lookupDecodeValues("sis", "student", implode(",",$arrStudentIds), ",", "", "id");
                //die("rafik arrStudentIds=".implode(",",$arrStudentIds));
            }
            

            return true; // suceeded to load and optimize
        }

            

        public function qeditHeaderFooterEmbedded($submode="",$fgroup="")
        {
            $step = 0;
            if($submode=="FGROUP" and $fgroup="mainwork") $step = 2;
            if($submode=="FGROUP" and $fgroup="homework") $step = 3;
            if($submode=="FGROUP" and $fgroup="homework2") $step = 4;
            return [$this->showMyLink($step, '', "short", null, "edit"), "", 1];
        }


        public function repareExistingObjectForEdit()
        {
            $attribute_arr = ["mainwork","homework","homework2"];
            $pos_arr = ["start","end"];
            $book_id = 0;
            foreach($attribute_arr as $attribute_case)
            {
                if($attribute_case=="mainwork") $book_id = $this->calcMainwork_real_book_id();
                if($attribute_case=="homework") $book_id = $this->calcHomework_real_book_id();
                if($attribute_case=="homework2") $book_id = $this->calcHomework2_real_book_id();

                foreach($pos_arr as $pos_case)
                {
                    $part_id = $this->getVal($attribute_case."_".$pos_case."_part_id");
                    $chapter_id = $this->getVal($attribute_case."_".$pos_case."_chapter_id");

                    // من الناس الى البقرة
                    if($book_id==10001)
                    {
                            if($part_id<=31) $part_id = 10033 - $part_id;
                            if($chapter_id<=1114) $chapter_id = 12115 - $chapter_id;
                            
                    }

                    // من البقرة الى الناس
                    if($book_id==1)
                    {
                           if($part_id>10000) $part_id = 10033 - $part_id;
                           if($chapter_id>=10000) $chapter_id = 12115 - $chapter_id;
                    }

                    $this->setSilent($attribute_case."_".$pos_case."_part_id", $part_id);
                    $this->setSilent($attribute_case."_".$pos_case."_chapter_id", $chapter_id);
                }
            }
            return true;
        }

        public function shouldBeCalculatedField($attribute){
            if($attribute=="courses") return true;
            if($attribute=="course_name_ar") return true;
            return false;
        }

        public function myShortNameToAttributeName($attribute){
            if($attribute=="student") return "student_id";
            if($attribute=="config") return "study_program_id";
            if($attribute=="school") return "school_id";
            if($attribute=="sclass") return "school_class_course_id";
            if($attribute=="course") return "course_id";
            if($attribute=="sbook") return "student_book";
            return $attribute;
        }
        
}
?>
