<?php
// ------------------------------------------------------------------------------------
// alter table c0sis.school add status_id smallint after school_type_id;
// 19/1/2023
// ALTER TABLE `school` CHANGE `group_school_id` `group_school_id` INT(11) NOT NULL DEFAULT '0'; 
/*
ALTER TABLE `school` CHANGE `allowed_open_before` `allowed_open_before` SMALLINT(6) NULL, 
                     CHANGE `genre_id` `genre_id` INT(11) NOT NULL DEFAULT '1', 
                     CHANGE `course_program_mfk` `course_program_mfk` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `address` `address` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `city_id` `city_id` INT(11) NOT NULL DEFAULT '0', 
                     CHANGE `quarter` `quarter` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `pc` `pc` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `maps_location_url` `maps_location_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `expiring_hdate` `expiring_hdate` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
                     CHANGE `levels_template_id` `levels_template_id` INT(11) NOT NULL DEFAULT '0', 
                     CHANGE `courses_template_id` `courses_template_id` INT(11) NOT NULL DEFAULT '0', 
                     CHANGE `courses_config_template_id` `courses_config_template_id` INT(11) NOT NULL DEFAULT '0', 
                     CHANGE `school_level_mfk` `school_level_mfk` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ',', 
                     CHANGE `holidays_school_id` `holidays_school_id` INT(11) NOT NULL DEFAULT '0', 
                     CHANGE `lang_id` `lang_id` INT(11) NOT NULL DEFAULT '1', CHANGE `sp1` `sp1` SMALLINT(6) NULL, 
                     CHANGE `sp2` `sp2` DECIMAL(5,2) NULL, CHANGE `we_mfk` `we_mfk` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ',6,7,';
*/
// 11/7/2023
// alter table c0sis.school add start_from smallint after we_mfk;
// alter table c0sis.school add `main_course_id` INT NULL AFTER `courses_template_id`;
// alter table c0sis.school add `min_rank_id` SMALLINT NULL AFTER `start_from`;
// alter table c0sis.school add study_program_id INT NULL AFTER we_mfk;



$file_dir_name = dirname(__FILE__);

// old include of afw.php

class School extends SisObject
{


    public static $MY_ATABLE_ID = 13330;
    // إجراء إحصائيات حول الوحدات 
    public static $BF_STATS_SCHOOL = 104290;
    // إدارة الوحدات 
    public static $BF_QEDIT_SCHOOL = 104285;
    // إنشاء وحدة 
    public static $BF_EDIT_SCHOOL = 104284;
    // الاستعلام عن وحدة 
    public static $BF_QSEARCH_SCHOOL = 104289;
    // البحث في الوحدات 
    public static $BF_SEARCH_SCHOOL = 104288;
    // عرض تفاصيل وحدة 
    public static $BF_DISPLAY_SCHOOL = 104287;
    // مسح وحدة 
    public static $BF_DELETE_SCHOOL = 104286;

    public static $DATABASE        = "";
    public static $MODULE            = "sis";
    public static $TABLE            = "";
    public static $DB_STRUCTURE = null; 
    public function __construct()
    {
        parent::__construct("school", "id", "sis");
        SisSchoolAfwStructure::initInstance($this);
        
    }

    public static function loadById($id)
    {
        $obj = new School();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else return null;
    }

    public static function loadByReference($ref_num, $create_obj_if_not_found = false, $commit_activate = true)
    {

        $obj = new School();
        $obj->select("ref_num", $ref_num);

        if ($obj->load()) {
            if ($create_obj_if_not_found) $obj->activate($commit_activate);
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("ref_num", $ref_num);

            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public static function loadByMainIndex($orgunit_id, $create_obj_if_not_found = false)
    {
        $obj = new School();
        $obj->select("orgunit_id", $orgunit_id);

        if ($obj->load()) {
            if ($create_obj_if_not_found) $obj->activate();
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("orgunit_id", $orgunit_id);

            $obj->insert();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }

    public static function loadFromRow($row)
    {
        if (!$row["ref_num"]) return null;
        return self::loadByReference($row["ref_num"], $create_obj_if_not_found = true, $commit_activate = false);
    }

    public function get_curr_year()
    {
        $currSYear = $this->getCurrentSchoolYear();
        if ($currSYear and (is_object($currSYear))) return $currSYear->getVal("year");
        else {
            $file_dir_name = dirname(__FILE__);
            include_once("$file_dir_name/../afw/common_date.php");
            list($hijri_year, $mm, $dd) = AfwDateHelper::currentHijriDate("hlist");
            $hijri_year = intval($hijri_year);
            return $hijri_year;
        }
    }


    public function get_next_year()
    {
        return $this->get_curr_year() + 1;
    }

    public function get_prev_year()
    {
        return $this->get_curr_year() - 1;
    }

    public function getApplicationSYear()
    {
        global $file_dir_name;

        // // require_once school_year.php");

        include_once("$file_dir_name/../afw/common_date.php");

        $hijri_curr_date = AfwDateHelper::currentHijriDate();


        $curr_SY = new SchoolYear();

        $curr_SY->select("school_id", $this->getId());
        $curr_SY->where("'$hijri_curr_date' between admission_start_hdate and admission_end_hdate");

        if ($curr_SY->load()) return $curr_SY;
        else return $this->getCurrentSchoolYear();
    }

    public static function formatSYDate($school_year_date, $currY)
    {
        $prevY = $currY-1;
        $nextY = $currY+1;
        $school_year_date2 = str_replace("CY", "$currY", $school_year_date);
        $school_year_date3 = str_replace("PY", "$prevY", $school_year_date2);
        $school_year_date4 = str_replace("NY", "$nextY", $school_year_date3);


        return $school_year_date4;
    }

    public function getCurrentSchoolYear() 
    {
        /*
        list($err, $inf, $war, $curr_SY_struct, $prev_SY_struct, $next_SY_struct) 
          = $this->genereSchoolYears($lang="ar", $createPY=false, $createCY=false, $createNY=false);
        if($curr_SY_struct)
        {
            return $curr_SY_struct["obj"];
        }
        else return null;
        */
        $sy = new SchoolYear();
        $sy->select('school_id', $this->id);
        $sy->select('school_year_type', 1);
        $currHDate = AfwDateHelper::currentHijriDate();
        $sy->where ("'$currHDate' between school_year_start_hdate and school_year_end_hdate");
        
        if ($sy->load()) return $sy;
        
        return null;

    }

    public function getAvailableRoom($syObj=null)
    {
        if(!$syObj) $syObj = $this->getCurrentSchoolYear();
        $roomList = $this->get("roomList");
        foreach($roomList as $roomItem)
        {
            $scObj = $roomItem->calcSchool_class_id($what="object",$syObj);
            if(!$scObj) return $roomItem;
        }

        return null;
    }

    public function getCurrentSchoolYearPassedPercentage()
    {
        $curYObj = $this->getCurrentSchoolYear();
        if(!$curYObj) return [null, -1];

        if(is_object($curYObj)) $school_year_start_hdate = $curYObj->getVal('school_year_start_hdate'); 
        if(is_object($curYObj)) $school_year_end_hdate = $curYObj->getVal('school_year_end_hdate');
        if((!$school_year_end_hdate) or (!$school_year_start_hdate)) return -2;

        $curr_date = AfwDateHelper::currentHijriDate();
        if($curr_date >= $school_year_end_hdate) return 100;
        if($curr_date <= $school_year_start_hdate) return 0;
        
        $totSYDays = AfwDateHelper::hijriDateDiff($school_year_end_hdate, $school_year_start_hdate);
        
        if($totSYDays==0) return -3;

        $passedDays = AfwDateHelper::hijriDateDiff($curr_date, $school_year_start_hdate);

        return [$curYObj, round($passedDays*100/$totSYDays)];
    }


    public function getStandardCurrentSchoolYear()
    {
        $date_system_config = ($this->getVal("date_system_id") == 1) ? "HIJRI" : "GREG";
        $school_year_start = AfwSession::config("school_year_start","CY-01-01");
        $school_year_end = AfwSession::config("school_year_end","CY-10-15");

        if($date_system_config == "HIJRI")
        {
            // example
            // 'school_year_name_template' => 'CY',
            // school_year_start => 'CY-01-01',
            // 'school_year_end' => 'CY-10-15',
            // school_year_date_current_year => CY-05-15
            $curr_date = AfwDateHelper::currentHijriDate();
            $CurrY = substr($curr_date,0,4);
        }
        else
        {
            // example
            // 'school_year_name_template' => 'PY - CY',
            // school_year_start => 'PY-09-01',
            // 'school_year_end' => 'CY-06-30',
            // school_year_date_current_year => CY-02-01
            $curr_date = date("Y-m-d");
            $CurrY = date("Y");
        }

        $currentSchoolYear = "";
        $currentSchoolYearStartDate = "";
        $currentSchoolYearEndDate = "";

        $pctg = -1;

        for($yearCursor=$CurrY-1; $yearCursor<=$CurrY+1;$yearCursor++)
        {
            $school_year_start_date = self::formatSYDate($school_year_start,$yearCursor);
            $school_year_end_date = self::formatSYDate($school_year_end,$yearCursor);

            if($curr_date < $school_year_end_date) 
            {
                $currentSchoolYear = $yearCursor;
                $currentSchoolYearStartDate = $school_year_start_date;
                $currentSchoolYearEndDate = $school_year_end_date;
            }
        }

        if((!$currentSchoolYearStartDate) or (!$currentSchoolYearEndDate)) $pctg = -2;

        if($curr_date >= $currentSchoolYearEndDate) return 100;
        if($curr_date <= $currentSchoolYearStartDate) return 0;
        
        if($date_system_config == "HIJRI")
        {
            $totSYDays = AfwDateHelper::hijriDateDiff($currentSchoolYearEndDate, $currentSchoolYearStartDate);
            $passedDays = AfwDateHelper::hijriDateDiff($curr_date, $currentSchoolYearStartDate);
        }
        else
        {
            $totSYDays = AfwDateHelper::gregDateDiff($currentSchoolYearEndDate, $currentSchoolYearStartDate);
            $passedDays = AfwDateHelper::gregDateDiff($curr_date, $currentSchoolYearStartDate);
        }

        if($totSYDays==0) $pctg = -3;
        else $pctg = round($passedDays*100/$totSYDays);

        return [$currentSchoolYear, $currentSchoolYearStartDate, $currentSchoolYearEndDate, $pctg, $school_year_start, $school_year_end, $date_system_config];
        
    }

    // need to be reviewed and well tested
    public function genereSchoolYears($lang="ar", $createPY=false, $createCY=true, $createNY=null, $copySettingsFromPrevious=true)
    {
        $errors_arr = [];
        $wars_arr = [];
        $infos_arr = [];

        $curr_SY = null;
        $prev_SY = null;
        $next_SY = null;

        $school_year_start_date = "??";
        $school_year_end_date = "??";
        $school_year_start_dateNY = "??";
        $school_year_end_dateNY = "??";
        $school_year_start_datePY = "??";
        $school_year_end_datePY = "??";

        $school_year_start_date_hijri = "??";
        $school_year_end_date_hijri = "??";
        $school_year_start_dateNY_hijri = "??";
        $school_year_end_dateNY_hijri = "??";
        $school_year_start_datePY_hijri = "??";
        $school_year_end_datePY_hijri = "??";

        try
        {
            
            list($currentSchoolYear, $currentSchoolYearStartDate, $currentSchoolYearEndDate, $pctg, $school_year_start, $school_year_end, $date_system_config) = $this->getStandardCurrentSchoolYear();
            //die("list($currentSchoolYear, $currentSchoolYearStartDate, $currentSchoolYearEndDate, $pctg, $school_year_start, $school_year_end, $date_system_config) = this->getStandardCurrentSchoolYear()");
            if($currentSchoolYear)
            {
                $createNY_to_decide = ($createNY===null);
                if($createNY_to_decide)
                {
                    $pctg_limit = AfwSession::config("pctg_limit_to_create_next_school_year",80);
                    $createNY = ($pctg >= $pctg_limit);
                    $infos_arr[] = "السنة الدراسية الحالية هي $currentSchoolYear";
                    $infos_arr[] = "مضى $pctg % من السنة الدراسية الحالية";
                    if($createNY) $infos_arr[] = "سيتم انشاء أو تحديث السنة الدراسية القادمة لأجل الاستعداد";
                    else $infos_arr[] = "لا يزال الوقت مبكرا لتحديث بيانات السنة الدراسية القادمة";
                }
                //die("createNY_to_decide=$createNY_to_decide genereSchoolYears infos_arr=".var_export($infos_arr,true));
                $objme = AfwSession::getUserConnected();
                // $date_system_config = AfwSession::config("date_system","HIJRI");
                
                $infos_arr[] = "تم حساب السنة الدراسية الحالية = $currentSchoolYear";
                $infos_arr[] = "البداية = $currentSchoolYearStartDate";
                $infos_arr[] = "النهاية = $currentSchoolYearEndDate";
                


                // die("genereSchoolYears infos_arr=".var_export($infos_arr,true)." prev_SY=".var_export($prev_SY,true));
                

                $school_year_start_date = self::formatSYDate($school_year_start,$currentSchoolYear);
                $school_year_end_date = self::formatSYDate($school_year_end,$currentSchoolYear);
                $school_year_start_dateNY = self::formatSYDate($school_year_start,$currentSchoolYear+1);
                $school_year_end_dateNY = self::formatSYDate($school_year_end,$currentSchoolYear+1);
                $school_year_start_datePY = self::formatSYDate($school_year_start,$currentSchoolYear-1);
                $school_year_end_datePY = self::formatSYDate($school_year_end,$currentSchoolYear-1);

                if($date_system_config == "HIJRI")
                {
                    $school_year_start_date_hijri = $school_year_start_date;
                    $school_year_end_date_hijri = $school_year_end_date;
                    $school_year_start_dateNY_hijri = $school_year_start_dateNY;
                    $school_year_end_dateNY_hijri = $school_year_end_dateNY;
                    $school_year_start_datePY_hijri = $school_year_start_datePY;
                    $school_year_end_datePY_hijri = $school_year_end_datePY;
                }
                else
                {
                    $school_year_start_date_hijri = AfwDateHelper::gregToHijri($school_year_start_date);
                    $school_year_end_date_hijri = AfwDateHelper::gregToHijri($school_year_end_date);
                    $school_year_start_dateNY_hijri = AfwDateHelper::gregToHijri($school_year_start_dateNY);
                    $school_year_end_dateNY_hijri = AfwDateHelper::gregToHijri($school_year_end_dateNY);
                    $school_year_start_datePY_hijri = AfwDateHelper::gregToHijri($school_year_start_datePY);
                    $school_year_end_datePY_hijri = AfwDateHelper::gregToHijri($school_year_end_datePY);
                }

                // die("before prev_SY = SchoolYear::loadByMainIndex($this->id, $currentSchoolYear-1, 0, 1, $school_year_start_datePY_hijri, $school_year_end_datePY_hijri, $createPY)");
                
                $prev_SY = SchoolYear::loadByMainIndex($this->id, $currentSchoolYear-1, 0, 1, $school_year_start_datePY_hijri, $school_year_end_datePY_hijri, $createPY);
                
                die("xx prev_SY=".var_export($prev_SY,true));

                if($prev_SY and $prev_SY->is_new)
                {
                    $infos_arr[] = "تم انشاء السنة الدراسية الماضية ".$prev_SY->getShortDisplay($lang)." من $school_year_start_datePY إلى $school_year_end_datePY";
                }
                elseif($prev_SY and $createPY)
                {
                    $infos_arr[] = "تم تحديث السنة الدراسية الماضية ".$prev_SY->getShortDisplay($lang)." من $school_year_start_datePY إلى $school_year_end_datePY";
                }

                // die("genereSchoolYears infos_arr=".var_export($infos_arr,true)." prev_SY=".var_export($prev_SY,true));
                
                $curr_SY = SchoolYear::loadByMainIndex($this->id, $currentSchoolYear, 0, 1, $school_year_start_date_hijri, $school_year_end_date_hijri, $createCY);
                
                if($curr_SY and $curr_SY->is_new)
                {
                    $infos_arr[] = "تم انشاء السنة الدراسية الحالية " .$curr_SY->getShortDisplay($lang)." من $school_year_start_date إلى $school_year_end_date";
                }
                elseif($curr_SY and $createCY)
                {
                    $infos_arr[] = "تم تحديث السنة الدراسية الحالية ".$curr_SY->getShortDisplay($lang)." من $school_year_start_date إلى $school_year_end_date";
                }

                if($copySettingsFromPrevious and $curr_SY and $prev_SY)
                {
                    $curr_SY->copySettingsFrom($lang, $prev_SY);
                    $infos_arr[] = "تم نسخ اعدادات السنة الدراسية $curr_SY من السنة الدراسية $prev_SY";
                }

                $next_SY = SchoolYear::loadByMainIndex($this->id, $currentSchoolYear+1, 0, 1, $school_year_start_dateNY_hijri, $school_year_end_dateNY_hijri, $createNY);
                if($next_SY and $next_SY->is_new)
                {
                    $infos_arr[] = "تم انشاء السنة الدراسية القادمة ".$next_SY->getShortDisplay($lang)." من $school_year_start_dateNY إلى $school_year_end_dateNY";
                }
                elseif($next_SY and $createNY)
                {
                    $infos_arr[] = "تم تحديث السنة الدراسية القادمة ".$next_SY->getShortDisplay($lang)." من $school_year_start_dateNY إلى $school_year_end_dateNY";
                }

                if($copySettingsFromPrevious and $next_SY and $curr_SY)
                {
                    list($err, $inf, $war) = $next_SY->copySettingsFrom($lang, $curr_SY);
                    if($err) $errors_arr[] = $err;
                    if($inf) $infos_arr[] = $inf;
                    if($war) $wars_arr[] = $war;
                    $infos_arr[] = "تم نسخ اعدادات السنة الدراسية $next_SY من السنة الدراسية $curr_SY";
                }
            }
            else
            {
                $errors_arr[] = " : لا يمكن التعرف على السنة الدراسية الحالية حسب الاعدادات التالية \n start=$school_year_start end=$school_year_end";
            }
        }
        catch(Exception $e)
        {
            $errors_arr[] = "حصل خطأ أثناء تحديث السنوات الدراسية رقم المرجع : " . $this->id;
            $objme = AfwSession::getUserConnected();
            if($objme->isSuperAdmin()) $errors_arr[] = " ex : ".var_export($e,true);
        }

        $curr_SY_struct = ["obj"=>$curr_SY, "start_date"=>$school_year_start_date, "end_date"=> $school_year_end_date];
        $prev_SY_struct = ["obj"=>$prev_SY, "start_date"=>$school_year_start_datePY, "end_date"=> $school_year_end_datePY];
        $next_SY_struct = ["obj"=>$next_SY, "start_date"=>$school_year_start_dateNY, "end_date"=> $school_year_end_dateNY];

        return array(implode("<br>\n",$errors_arr), implode("<br>\n",$infos_arr), implode("<br>\n",$wars_arr), $curr_SY_struct, $prev_SY_struct, $next_SY_struct);
        
        
    }

    


    public function updateOrgunit($lang = "ar", $commit = false)
    {
        $sg_id = $this->getVal("group_school_id");
        if ($sg_id > 0) $parent_orgunit_id = $this->get("group_school_id")->getVal("orgunit_id");
        else $parent_orgunit_id = 0;

        $school_type_id = $this->getVal("school_type_id");
        $id_sh_org = 1;
        $id_sh_type = 0;
        if ($school_type_id == 1) $id_sh_type = 9;
        if ($school_type_id == 2) $id_sh_type = 10;
        $id_domain = 2;
        $hrm_code = $this->getVal("ref_num");
        if (!$hrm_code) {
            $hrm_code = "school-" . $this->id;
        }

        $titre_short = $titre = $this->getVal("school_name_ar");
        $orgunitObj = Orgunit::findOrgunit($id_sh_type, $id_sh_org, $hrm_code, $titre_short, $titre, $id_domain, $create_obj_if_not_found = true);
        $orgunitObj->set("gender_id", $this->getVal("genre_id"));
        $orgunitObj->set("id_sh_parent", $parent_orgunit_id);
        $orgunitObj->set("addresse", $this->getVal("address"));
        $city_id = $this->getVal("city_id");
        if ($city_id > 0) $city_name = $this->get("city_id")->getDisplay($lang);
        else $city_name = "";
        $orgunitObj->set("city_name", $city_name);
        $orgunitObj->set("cp", $this->getVal("pc"));
        $orgunitObj->set("quarter", $this->getVal("quarter"));
        $orgunitObj->commit();
        $this->set("ref_num", $hrm_code);
        $this->set("orgunit_id", $orgunitObj->getId());
        if ($commit) $this->commit();

        return $orgunitObj;
    }

    public function beforeMAJ($id, $fields_updated)
    {
        global $lang;
        if (
            $fields_updated["school_name_ar"] or
            $fields_updated["address"] or
            $fields_updated["city_id"] or
            $fields_updated["cp"] or
            $fields_updated["school_type_id"] or
            $fields_updated["group_school_id"] or
            $fields_updated["address"]
        ) {
            $this->updateOrgunit();
        }


        return true;
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
                       // sis.school_year-المنشأة	school_id  نوع علاقة بين كيانين ← 1 (required field)
                        // require_once "../sis/school_year.php";
                        $obj = new SchoolYear();
                        $obj->where("school_id = '$id' and active='Y' ");
                        $nbRecords = $obj->count();
                        // check if there's no record that block the delete operation
                        if($nbRecords>0)
                        {
                            $this->deleteNotAllowedReason = "Used in some School years(s) as School";
                            return false;
                        }
                        // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                        if(!$simul) $obj->deleteWhere("school_id = '$id' and active='N'");


                        
                   // FK part of me - deletable 
                       // sis.school_term-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_term.php";
                            SchoolTerm::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.school_term where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.model_term-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/model_term.php";
                            ModelTerm::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.model_term where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.student_file-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/student_file.php";
                            StudentFile::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.student_file where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.school_period-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_period.php";
                            SchoolPeriod::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.school_period where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.school_employee-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_employee.php";
                            SchoolEmployee::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.school_employee where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.holiday-النظام المدرسي	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/holiday.php";
                            Holiday::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.holiday where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.room-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/room.php";
                            Room::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.room where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.sdepartment-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/sdepartment.php";
                            Sdepartment::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.sdepartment where school_id = '$id' ");
                            
                        } 
                        
                        
                       // sis.class_course-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/class_course.php";
                            ClassCourse::removeWhere("school_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.class_course where school_id = '$id' ");
                            
                        } 
                        
                        

                   
                   // FK not part of me - replaceable 
                       // sis.school-المنشأة الأم / المجموعة	group_school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school.php";
                            School::updateWhere(array('group_school_id'=>$id_replace), "group_school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set group_school_id='$id_replace' where group_school_id='$id' ");
                        }
                       // sis.school-النظام التدريبي للعطل	holidays_school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school.php";
                            School::updateWhere(array('holidays_school_id'=>$id_replace), "holidays_school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set holidays_school_id='$id_replace' where holidays_school_id='$id' ");
                        }
                       // sis.alert-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/alert.php";
                            //Alert::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.alert set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.rating-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/rating.php";
                            Rating::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.rating set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.student-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/student.php";
                            Student::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.student set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.school_member_day-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school_member_day.php";
                            SchoolMemberDay::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_member_day set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.student_session-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/student_session.php";
                            StudentSession::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.student_session set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.course_session-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/course_session.php";
                            CourseSession::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.course_session set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.day_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/day_template.php";
                            DayTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.day_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.week_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/week_template.php";
                            WeekTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.week_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.courses_config_template-خاص بمجموعة المنشآت	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/courses_config_template.php";
                            CoursesConfigTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_config_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.alert_user-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/alert_user.php";
                            AlertUser::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.alert_user set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.scandidate-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/scandidate.php";
                            Scandidate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.scandidate set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.session_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/session_template.php";
                            SessionTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.session_template set school_id='$id_replace' where school_id='$id' ");
                        }

                        // summer.summer_upload-school_id	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../summer/summer_upload.php";
                            //SummerUpload::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_upload set school_id='$id_replace' where school_id='$id' ");
                        }
                       // summer.summer_course-الكلية	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../summer/summer_course.php";
                            //SummerCourse::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_course set school_id='$id_replace' where school_id='$id' ");
                        }

                        
                   
                   // MFK

               }
               else
               {
                        // FK on me 
 

                        // sis.school_year-المنشأة	school_id  نوع علاقة بين كيانين ← 1 (required field)
                        if(!$simul)
                        {
                            // require_once "../sis/school_year.php";
                            SchoolYear::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_year set school_id='$id_replace' where school_id='$id' ");
                            
                        } 
                        

                       // sis.school_term-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_term.php";
                            SchoolTerm::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_term set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.model_term-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/model_term.php";
                            ModelTerm::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.model_term set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.student_file-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/student_file.php";
                            StudentFile::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.student_file set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.school_period-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_period.php";
                            SchoolPeriod::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_period set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.school_employee-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/school_employee.php";
                            SchoolEmployee::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_employee set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.holiday-النظام المدرسي	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/holiday.php";
                            Holiday::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.holiday set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.room-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/room.php";
                            Room::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.room set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.sdepartment-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/sdepartment.php";
                            Sdepartment::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.sdepartment set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.class_course-المنشأة	school_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            // require_once "../sis/class_course.php";
                            ClassCourse::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.class_course set school_id='$id_replace' where school_id='$id' ");
                            
                        }
                        
                       // sis.school-المنشأة الأم / المجموعة	group_school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school.php";
                            School::updateWhere(array('group_school_id'=>$id_replace), "group_school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set group_school_id='$id_replace' where group_school_id='$id' ");
                        }
                       // sis.school-النظام التدريبي للعطل	holidays_school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school.php";
                            School::updateWhere(array('holidays_school_id'=>$id_replace), "holidays_school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set holidays_school_id='$id_replace' where holidays_school_id='$id' ");
                        }
                       // sis.alert-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/alert.php";
                            ////Alert::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.alert set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.rating-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/rating.php";
                            Rating::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.rating set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.student-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/student.php";
                            Student::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.student set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.school_member_day-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/school_member_day.php";
                            SchoolMemberDay::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school_member_day set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.student_session-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/student_session.php";
                            StudentSession::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.student_session set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.course_session-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/course_session.php";
                            CourseSession::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.course_session set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.day_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/day_template.php";
                            DayTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.day_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.week_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/week_template.php";
                            WeekTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.week_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.courses_config_template-خاص بمجموعة المنشآت	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/courses_config_template.php";
                            CoursesConfigTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_config_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.alert_user-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/alert_user.php";
                            AlertUser::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.alert_user set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.scandidate-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/scandidate.php";
                            Scandidate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.scandidate set school_id='$id_replace' where school_id='$id' ");
                        }
                       // sis.session_template-المنشأة	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../sis/session_template.php";
                            SessionTemplate::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.session_template set school_id='$id_replace' where school_id='$id' ");
                        }
                       // summer.summer_upload-school_id	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../summer/summer_upload.php";
                            //SummerUpload::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_upload set school_id='$id_replace' where school_id='$id' ");
                        }
                       // summer.summer_course-الكلية	school_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            // require_once "../summer/summer_course.php";
                            //SummerCourse::updateWhere(array('school_id'=>$id_replace), "school_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}summer.summer_course set school_id='$id_replace' where school_id='$id' ");
                        }

                        
                        // MFK

                   
               } 
               return true;
            }    
	}

    protected function getOtherLinksArray($mode, $genereLog = false, $step="all")
    {
        
        $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
        $my_id = $this->getId();
        $displ = $this->getDisplay();


        if ($mode == "mode_empl") {
            $genre_id = $this->getVal("genre_id");
            unset($link);
            $link = array();
            $title = "إنشاء موظف";
            $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=SchoolEmployee&currmod=sis&id_origin=$my_id&class_origin=School&module_origin=sis&sel_school_id=$my_id&sel_gender_id=$genre_id";
            $link["TITLE"] = $title;
            $link["UGROUPS"] = array();
            $otherLinksArray[] = $link;
        }

        /*
        if ($mode == "mode_syear") 
        {
            // 
            $c_syear = $this->getCurrentSchoolYear();
            $p_syear = $this->getPrevSYear();
            $n_syear = $this->getNextSYear();
            // die("je suis dans mode == mode_syear pY=".$p_syear."/CY=".$c_syear.")");
            if ($this->getVal("date_system_id") == 1) // هجري
            {
                $curr_year = $this->get_curr_year();
                $prev_year = $this->get_prev_year();
                $next_year = $this->get_next_year();
                $next_2_year = $next_year + 1;
            } 
            else 
            {
                $curr_year = date("Y");
                $prev_year = $curr_year -1;
                $next_year = $curr_year +1;
                $next_2_year = $next_year + 1;
            }

            if (!$p_syear) 
            {
                unset($link);
                $link = array();
                $title = "انشاء السنة الدراسية الماضية $prev_year - $curr_year";
                // $title .= "(pY=".$p_syear."/CY=".$c_syear.")";
                $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=SchoolYear&currmod=sis&&id_origin=$my_id&class_origin=School&module_origin=sis&sel_school_id=$my_id&sel_year=$prev_year&sel_school_year_type=1";
                $link["TITLE"] = $title;
                $link["STEP"] = 7;
                $link["PUBLIC"] = true;
                $otherLinksArray[] = $link;
            }

            if (!$c_syear) {
                unset($link);
                $link = array();
                $title = "انشاء السنة الدراسية الحالية $curr_year - $next_year";
                $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=SchoolYear&currmod=sis&&id_origin=$my_id&class_origin=School&module_origin=sis&sel_school_id=$my_id&sel_year=$curr_year&sel_school_year_type=1";
                $link["TITLE"] = $title;
                $link["STEP"] = 7;
                $link["PUBLIC"] = true;
                $otherLinksArray[] = $link;
            }

            if (!$n_syear) {
                unset($link);
                $link = array();
                $title = "انشاء السنة الدراسية القادمة $next_year - $next_2_year";
                $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=SchoolYear&currmod=sis&&id_origin=$my_id&class_origin=School&module_origin=sis&sel_school_id=$my_id&sel_year=$next_year&sel_school_year_type=1";
                $link["TITLE"] = $title;
                $link["STEP"] = 7;
                $link["PUBLIC"] = true;
                $otherLinksArray[] = $link;
            }
        }*/

        if ($mode == "mode_roomList") {
            unset($link);
            $my_id = $this->getId();
            $link = array();
            $title = "إدارة القاعات ";
            $title_detailed = $title;
            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=Room&currmod=sis&id_origin=$my_id&class_origin=School&module_origin=sis&step_origin=6&newo=10&limit=30&ids=all&fixmtit=&fixmdisable=1&fixm=school_id=$my_id&sel_school_id=$my_id";
            $link["TITLE"] = $title;
            $link["BF-ID"] = 101733;
            $otherLinksArray[] = $link;
        }

        return $otherLinksArray;
    }

    public function getNewStudentNum()
    {
        $file_dir_name = dirname(__FILE__);
        $db = $this->getDatabase();

        // require_once("$file_dir_name/student_file.php");

        $sf = new StudentFile;

        $sf->select("school_id", $this->id);
        $student_num = $sf->func("max(student_num)");
        if ($student_num) return $student_num + 1;
        else return 501;
    }

    public function synchStudentFiles($lang="ar")
    {
        global $MODE_SQL_PROCESS_LOURD;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;

        $sf = new StudentFile;
        $sf->select("school_id", $this->id);
        $sf->select('active', 'Y');
        $sfList = $sf->loadMany();
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

        return ["", "تم تحديث $nb ملف"];
    }

    public function getStudentNumForStudent($student_id)
    {
        // $file_dir_name = dirname(__FILE__);
        // $db = $this->getDatabase();
        // require_once("$file_dir_name/student_file.php");

        $sf = new StudentFile;

        $sf->select("student_id", $student_id);
        $sf->select("school_id", $this->id);
        $student_num = $sf->func("max(student_num)");
        if ($student_num) return $student_num;
        else return $this->getNewStudentNum();
    }

    public function keepclass_nameForStudent($student_id)
    {
        $file_dir_name = dirname(__FILE__);
        $db = $this->getDatabase();

        // chercher le dernier student file et en prendre le class_name 
        // require_once("$file_dir_name/student_file.php");
        $sf = new StudentFile;
        $sf->select("student_id", $student_id);
        $sf->select("school_id", $this->id);
        $sf_list = $sf->loadMany($limit = "1", $order_by = "year desc");

        foreach ($sf_list as $sf_item) {
            return $sf_item->getVal("class_name");
        }
    }

    public function calcNbrooms($what='value')    
    {
        $this->getRelation("roomList")->count();
    }

    

    

    protected function afterSetAttribute($attribute)
    {
        $file_dir_name = dirname(__FILE__);
        $this_id = $this->getId();
        if ($attribute == "nbrooms") {
            $nbrooms = $this->getVal("nbrooms");
            if (($nbrooms > 0) and  (count($this->get("roomList")) == 0)) {
                // require_once("$file_dir_name/room.php");
                // -- generate the rooms
                $school_capacity = $this->getVal("scapacity");
                $room_capacity = round($school_capacity / $nbrooms);
                $room_arr = array();

                for ($rnum = 1; $rnum <= $nbrooms; $rnum++) {
                    $room_arr[$rnum] = new Room();
                    $room_arr[$rnum]->set("school_id", $this_id);
                    $room_arr[$rnum]->set("room_num", $rnum);
                    $room_arr[$rnum]->set("capacity", $room_capacity);
                    $room_arr[$rnum]->set("room_name_ar", "قاعة رقم $rnum");
                    $room_arr[$rnum]->insert();
                }
            }
        }
    }

    public function genereSchoolAsOneDepartment($lang = "ar")
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) return array("no user connected", "");

        $db = $this->getDatabase();

        // @todo : genre one sdep by period depending on periods configured for school
        // صباحية مسائية الخ 
        // require_once("$file_dir_name/sdepartment.php");

        $sdepartment = Sdepartment::loadByMainIndex($this->getVal("orgunit_id"),true);

        $sdepartment->set("sdepartment_name_ar", $this->getVal("school_name_ar"));
        $sdepartment->set("sdepartment_name_en", $this->getVal("school_name_en"));
        $sdepartment->set("school_id", $this->getId());
        $week_template_id = 0;
        $sdepartment->set("week_template_id", $week_template_id);

        $sdepartment->commit();

        $currSYObj = $this->getCurrentSchoolYear();
        if ($currSYObj and is_object($currSYObj) and $sdepartment and $sdepartment->getId() and $currSYObj->getId()) 
        {
            $scope = new SchoolScope;

            $scope->select("school_year_id", $currSYObj->getId());
            $scope->set("sdepartment_id", $sdepartment->getId());
            $scope->update(false);
            return array("genereSchoolAsOneDepartment_succeeded", "");
        } else {
            return array("genereSchoolAsOneDepartment_failed", "");
        }
    }

    public function genereOneDepartmentByLevel($lang = "ar")
    {
        $me = AfwSession::getUserIdActing();
        if (!$me) return array("no user connected", "");


        $db = $this->getDatabase();
        // @todo
        /*
             $sdepartment = new Sdepartment;
             
             $week_template_id = 0;
             
             $sdepartment->set("sdepartment_name_ar", $this->getVal("school_name_ar"));
             $sdepartment->set("sdepartment_name_en", $this->getVal("school_name_en"));
             $sdepartment->set("school_id", $this->getId());
             $sdepartment->set("week_template_id", $week_template_id);
             
             $sdepartment->insert();
             $currSYObj = $this->getCurrentSchoolYear();
             if($sdepartment->getId() and $currSYObj->getId())
             {
                     // require_once("$file_dir_name/school_scope.php");
                     
                     $scope = new SchoolScope;
                     
                     $scope->select("school_year_id",$currSYObj->getId());
                     $scope->set("sdepartment_id",$sdepartment->getId());
                     $scope->update(false);
                     return array("genereSchoolAsOneDepartment_succeeded", "");
             }
             else
             {
                     return array("genereSchoolAsOneDepartment_failed", "");
             }*/
    }



    protected function getPublicMethods()
    {
        global $lang;
        $pbms = array();

        // $currSYObj = $this->getCurrentSchoolYear();
        // $disp = $this->getDisplay($lang);

        $pbms["xB11YO"] = array(
            "METHOD" => "genereConditions",
            "LABEL_AR" => "إنشاء شروط الالتحاق",
            "LABEL_EN" => "genere school conditions",
            "PUBLIC" => true,
            "STEP" => 5
        );

        $pbms["xDsH34"] = array(
            "METHOD" => "genereSchoolYears",
            "LABEL_AR" => "إنشاء السنوات الدراسية",
            "LABEL_EN" => "genere school years",
            "COLOR" => "default",
            "PUBLIC" => true,
            "STEP" => 8
        );

        $pbms['b3y2de'] = [
            'METHOD' => 'synchStudentFiles',
            'LABEL_AR' => 'تحديث  ملفات  الطلاب  من الأرشيف',
            'LABEL_EN' => 'complete Student Files from archive',
            'ADMIN-ONLY' => 'true',
            'STEP' => 5,
        ];

        if ((count($this->getSDep()) == 0)) 
        {
            
            $pbms["xBff34"] = array(
                "METHOD" => "genereSchoolAsOneDepartment",
                "LABEL_AR" => "جعل المنشأة كقسم واحد",
                "LABEL_EN" => "genere school as one department",
                "PUBLIC" => true,
                "STEP" => 9
            ); 


            $pbms["xH1sz4"] = array(
                "METHOD" => "genereOneDepartmentByLevel",
                "LABEL_AR" => "انشاء قسم خاص بكل مستوى",
                "LABEL_EN" => "genere one department by level",
                "PUBLIC" => true,
                "STEP" => 9
            );                                              
        }

        return $pbms;
    }


    public function list_of_genre_id()
    {
        $list_of_items = array();
        if(AfwSession::config("enfisal_authorized",true))
        {
            $list_of_items[1] = "ذكور";
            $list_of_items[2] = "إناث";
        }
        if(AfwSession::config("ekhtilat",true))
        {
            $list_of_items[3] = "ذكور وإناث";
        }

        return  $list_of_items;
    }

    public function list_of_presence_mfk()
    {
        $list_of_items = array();
        $list_of_items[1] = "حضوري";
        $list_of_items[2] = "عن بعد";
        return  $list_of_items;
    }

    public static function list_of_start_from()
    {
        return self::at_of_start_from();
    }

    public static function at_of_start_from()
    {
        $list_of_items = array();
        $list_of_items[1] = "من البداية بحسب الانجاه";
        $list_of_items[2] = "من حيث وصل الطالب بعد اختباره";
        $list_of_items[3] = "من حيث يريد الطالب";
        return  $list_of_items;
    }

    public function decodeSchoolType($string)
    {
        $lookup_code = substr(md5($string), 1, 8);
        $objST = SchoolType::loadByMainIndex($lookup_code, $string, $create_obj_if_not_found = true);
        $this->set("school_type_id", $objST->id);
        $objST_new = $objST->is_new;
        unset($objST);
        if ($objST_new) return "school type '$string' has been created";
        else return "school type '$string' has been found and updated";
    }


    public static function decodeCityId($string)
    {
        $string = trim($string);
        if (!$string)  return  0;
        list($obj_id,$string_arr) = City::getCityIdFromName($string);

        return $obj_id;
    }

    public function decodeCity($string)
    {
        global $CITY_ARR;
        if (!$CITY_ARR) $CITY_ARR = array();
        $string = trim($string);
        if (!$string)  return  "city '$string' empty or not valid";
        if ($CITY_ARR[$string]) {
            $obj_id = $CITY_ARR[$string];
            $obj_new = false;
            $where_found = "in cache";
        } else {
            $obj_id = self::decodeCityId($string);
            $where_found = "decoded by City::getCityIdFromName";
        }
        
        if($obj_id) 
        {
            $CITY_ARR[$string] = $obj_id;
            $this->set("city_id", $obj_id);
            return "city '$string' has been found $where_found value id = $obj_id";
        }
        else return  "city '$string' not found";
    }

    public function decodeStatus($string)
    {
        global $STATUS_ARR;
        if (!$STATUS_ARR) $STATUS_ARR = array();
        $string = trim($string);
        if (!$string)  return  "school status '$string' empty or not valid";
        if ($STATUS_ARR[$string]) {
            $obj_id = $STATUS_ARR[$string];
            $objST_new = false;
        } else {
            $lookup_code = substr(md5($string), 1, 8);
            $objST = SchoolStatus::loadByMainIndex($lookup_code, $string, $create_obj_if_not_found = true);
            $objST_new = $objST->is_new;
            $obj_id = $objST->id;
            unset($objST);
        }

        $this->set("status_id", $obj_id);


        if ($objST_new) return "school status '$string' has been created as $obj_id";
        else return "school status '$string' has been found $obj_id and updated";
    }

    public function decodeBuildingDesc($string)
    {
        // $desc_body = ""; // $this->getVal("building_desc");
        $string = trim($string);
        $string = trim($string, ".:{}(),;/\\");
        $desc_body = $string;
        if (is_numeric($string)) {
            $im2 = intval($string);
            if ($im2 > 15) $desc_body = "مساحة العقار $im2 م2 (متر مربع)";
            else $desc_body = "";
        }

        $this->set("building_desc", $desc_body);

        return $desc_body;
    }

    public function decodeBuildingNbRooms($string)
    {
        $desc_body = trim($this->getVal("building_desc"));
        $desc_body = trim($desc_body, ".:{}(),;/\\");

        $string = trim($string);
        $string = trim($string, ".:{}(),;/\\");
        if (is_numeric($string)) {
            $inbr = intval($string);
            if ($inbr > 0) $desc_body .= "\nالعقار يحتوي على $inbr قاعة مخصصة للتدريس";
        } elseif ($string) {
            $desc_body .= "\n" . $string;
        }

        $desc_body = trim($desc_body);

        $this->set("building_desc", $desc_body);

        return $desc_body;
    }


    public function decodeGender($string)
    {
        $genre_id = 1;
        if ($string == "نسائي") $genre_id = 2;
        if ($string == "أنثى") $genre_id = 2;
        if ($string == "إناث") $genre_id = 2;

        $this->set("genre_id", $genre_id);
        return "school gender '$string' decoded to $genre_id";
    }

    public function saveTelephone($string)
    {
        $orgunitObj = $this->hetOrgunit();
        if ($orgunitObj) {
            $orgunitObj->set("phone", $string);
            return "phone $string saved into orgunit object";
        }
        unset($orgunitObj);

        return "phone $string not saved because no orgunit object";
    }

    public function saveEmail($string)
    {
        $orgunitObj = $this->hetOrgunit();
        if ($orgunitObj) {
            $orgunitObj->set("email", $string);
            return "email $string saved into orgunit object";
        }
        unset($orgunitObj);

        return "email $string not saved because no orgunit object";
    }


    public function getTimeStampFromRow($row, $context = "update", $timestamp_field = "")
    {
        if ($row["synch_timestamp"] < $row["e_synch_timestamp"]) {
            if ($context == "create")
                return $row["synch_timestamp"];
            else
                return $row["e_synch_timestamp"];
        } else {
            if ($context == "update")
                return $row["synch_timestamp"];
            else
                return $row["e_synch_timestamp"];
        }
    }

    public function stepsAreOrdered()
    {
        return true;
    }


    public function getDisplay($lang = "ar")
    {
        return $this->getVal("school_name_$lang");
    }


    public function getRetrieveDisplay($lang = "ar")
    {
        list($city_name,) = $this->showAttribute("city_id",null,false,$lang);
        return $this->getVal("school_name_$lang")."<br><b>$city_name</b>";
    }


    protected function attributeCanBeUpdatedBy($attribute, $user, $desc)
    {
        if ($attribute == "ref_num") {
            if (($this->getVal("orgunit_id") > 0) and AfwSession::config("ref_num_authorization_automatic",false))
            {
                return array(false, "After creation of orgunit (HRM Module) and link with it with hrm code this field become readonly");
            }    
            else return array(true, "");
        }

        return $this->attributeCanBeModifiedBy($attribute, $user, $desc);
    }


    public static function list_of_we_mfk()
    {
            return Hday::list_of_wday_id();
    }

    public function getFieldGroupInfos($fgroup)
    {
        if ($fgroup == 'hdayList') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }
        if ($fgroup == 'cand') {
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }
        if ($fgroup == 'cand') {
            return ['name' => $fgroup, 'css' => 'sdep'];
        }

        return ['name' => $fgroup, 'css' => 'pct_100'];
    }

    public function attributeIsApplicable($attribute)
    {
        if($attribute == "ref_num")
        {
            return ($this->getVal("status_id") >= 2);
        }

        return true;
    }

    public function genereConditions($lang = 'ar',$regen = false) 
    {
        $file_dir_name = dirname(__FILE__);

        $school_id = $this->id;
        $levelList = $this->getLevels();
        $obj_inserted = 0;
        $obj_count = 0;
        $levelListCount = 0;
        $levelClassListCount = 0;
        SchoolCondition::logicDeleteWhere("school_id = $school_id");
        foreach ($levelList as $levelId => $school_level_obj) 
        {
            $levelListCount++;
            $levelClassList = $school_level_obj->get("levelClassList");
                foreach ($levelClassList as $level_class_id => $level_class_obj) 
                {
                    $levelClassListCount++;
                    $level_class_order = $level_class_obj->getVal('level_class_order');
                    $min_eval = $level_class_obj->getVal('min_eval');
                    $max_eval = $level_class_obj->getVal('max_eval');
                    
                    
                    $school_level_order = $school_level_obj->getVal('school_level_order');

                        $objSC = SchoolCondition::loadByMainIndex($school_id, $level_class_id, $min_eval, $max_eval, true);
                        

                        if ($objSC->is_new) {
                            $obj_inserted++;
                        }
                        $obj_count++;
                }
        }
        

        $info =
            "اعتمادا على $levelListCount مستوى دراسي و $levelClassListCount فرع فقد تم انشاء عدد $obj_inserted من شروط الالتحاق بالمنشأة  " .
            $this->getDisplay() .
            " ففي الجملة يوجد الآن $obj_count شرط";
        $error = '';

        return [$error, $info];
    }

    public static function decodeSchool($rowMapped, $row)
    {
        $objSchool = self::loadByReference($row["license"]);
        $rowMapped["school_id"] = $objSchool->id;
        return $rowMapped;
    }

    public function getCapacityIndicator($arrObjectsRelated)    
    {
        global $arr_schoolClassList;
        $total_capacity=0;
        $total_students=0;
        if($arrObjectsRelated["currentSchoolYear"])
        {
            if($arr_schoolClassList[$arrObjectsRelated["currentSchoolYear"]->id])
            {
                $schoolClassList = $arr_schoolClassList[$arrObjectsRelated["currentSchoolYear"]->id];
            }
            else
            {
                $schoolClassList = $arrObjectsRelated["currentSchoolYear"]->get("schoolClassList");
                $arr_schoolClassList[$arrObjectsRelated["currentSchoolYear"]->id] = $schoolClassList;
            }
            //die("schoolClassList=".var_export($schoolClassList));
            foreach($schoolClassList as $schoolClassItem)
            {
                list($needed_stdn, $room_comment, $room_capacity, $stdn_count) = $schoolClassItem->getPlacesInfo(false);
                $total_capacity += $room_capacity;
                $total_students += $stdn_count;
            }
        }
        

        return [$total_capacity, $total_students];        
    }

    

    public function getAcceptedIndicator($arrObjectsRelated)    
    {
        $total_objective=0;
        $total_done=0;
        if($arrObjectsRelated["currentSchoolYear"])
        {
            $pendingCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("pendingCandidateList")->count();
            $total_objective += $pendingCandidateCount;
            $acceptedCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("acceptedCandidateList")->count();
            $total_objective += $acceptedCandidateCount;
            $total_done += $acceptedCandidateCount;
            $rejectedCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("rejectedCandidateList")->count();
            $total_objective += $rejectedCandidateCount;
            
            
        }
        

        return [$total_objective, $total_done];        
    }

    public function getPendingIndicator($arrObjectsRelated)    
    {
        $total_objective=$this->getVal("pending_objective");
        if(!$total_objective) $total_objective=1;
        $total_done=0;
        if($arrObjectsRelated["currentSchoolYear"])
        {
            $pendingCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("pendingCandidateList")->count();
            //$total_objective += $pendingCandidateCount;
            $total_done += $pendingCandidateCount;
            //$acceptedCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("acceptedCandidateList")->count();
            //$total_objective += $acceptedCandidateCount;
            //$rejectedCandidateCount = $arrObjectsRelated["currentSchoolYear"]->getRelation("rejectedCandidateList")->count();
            //$total_objective += $rejectedCandidateCount;
            
            
        }
        

        return [$total_objective, $total_done];        
    }

    public static function bootstrapAllPaidSchoolsWork($lang="ar")
    {
        $errors_arr = [];
        $wars_arr = [];
        $infos_arr = [];
        $tech_arr = [];

        $obj = new School();
        $obj->select("active", "Y");
        $obj->select("status_id", SchoolStatus::$status_ongoing);
        $schoolList = $obj->loadMany();
        foreach($schoolList as $schoolItem)
        {
            $schoolItemDisp = $schoolItem->getShortDisplay($lang);
            list($err, $inf, $war, $tech) = $schoolItem->bootstrapSchoolWork($lang);
            if($err) $errors_arr[] = $schoolItemDisp." : ".$err;
            if($inf) $infos_arr[] = $schoolItemDisp." : ".$inf;
            if($war) $wars_arr[] = $schoolItemDisp." : ".$war;
            if($tech) $tech_arr[] = $tech;
            
        }

        return self::pbm_result($errors_arr,$infos_arr,$wars_arr,"<br>\n",$tech_arr);
    }


    public function bootstrapSchoolWork($lang="ar")
    {
        $currSYear = $this->getCurrentSchoolYear();
        if ($currSYear and is_object($currSYear)) return $currSYear->bootstrapWork($lang);

        return ["no-current-school-year-for-school".$this->id,""];

    }

    public function shouldBeCalculatedField($attribute){
        if($attribute=="course_mfk") return true;
        return false;
    }
    

    
    

    
    
    
}
