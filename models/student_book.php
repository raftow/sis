<?php
// ------------------------------------------------------------------------------------
/*

DROP TABLE IF EXISTS c0sis.`student_book`;
 
CREATE TABLE IF NOT EXISTS c0sis.`student_book` (
  `created_by` int(11) NOT NULL,
  `created_at`   datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `validated_at` datetime DEFAULT NULL,
  `active` char(1) NOT NULL,
  `draft` char(1) NOT NULL default 'Y',
  `version` int(4) DEFAULT NULL,
  `update_groups_mfk` varchar(255) DEFAULT NULL,
  `delete_groups_mfk` varchar(255) DEFAULT NULL,
  `display_groups_mfk` varchar(255) DEFAULT NULL,
  `sci_id` int(11) DEFAULT NULL,
  
    
   student_id BIGINT(20) NOT NULL , 
   main_book_id int(11) NOT NULL ,
   
   main_sens smallint DEFAULT NULL , 
   main_part_id int(11) DEFAULT NULL , 
   main_chapter_id int(11) DEFAULT NULL , 
   main_page_num smallint DEFAULT NULL , 
   main_paragraph_num smallint DEFAULT NULL , 

   mainwork_nb_pages smallint DEFAULT NULL ,
   mainwork_nb_lines smallint DEFAULT NULL ,

  
  PRIMARY KEY (`student_id`,`main_book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
PARTITION BY HASH (`student_id`)
PARTITIONS 100;

alter table c0sis.`student_book` add main_sens smallint after main_book_id;
alter table c0sis.`student_book` add mainwork_nb_pages smallint after main_chapter_id;
alter table c0sis.`student_book` add mainwork_nb_lines smallint after main_chapter_id;
*/
// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__);

// old include of afw.php

class StudentBook extends SisObject
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
    public static $TABLE = 'student_book';
    public static $DB_STRUCTURE = null;

    public function __construct()
    {
        parent::__construct('student_book', '', 'sis');
        SisStudentBookAfwStructure::initInstance($this);
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
        $obj = new StudentBook();
        $obj->select_visibilite_horizontale();
        if ($obj->load($id)) {
            return $obj;
        } else {
            return null;
        }
    }
/*
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
    }*/

    
    public function updateToDefaultInjaz($defaultInjaz, $only_if_empty=true)
    {
        if($this->getVal("main_chapter_id") and $this->getVal("main_paragraph_num") and $only_if_empty)
        {
            return ["", "", "يوجد بيانات انجاز سابقة", ""];
        }

        if((!$defaultInjaz) or (!$defaultInjaz->getVal("main_chapter_id")) or (!$defaultInjaz->getVal("main_paragraph_num")))
        {
            return ["", "", "لا يوجد بيانات انجاز افتراضية", ""];
        }

        $this->set("main_sens",$defaultInjaz->getVal("main_sens"));
        $this->set("main_part_id",$defaultInjaz->getVal("main_part_id"));
        $this->set("main_chapter_id",$defaultInjaz->getVal("main_chapter_id"));
        $this->set("main_page_num",$defaultInjaz->getVal("main_page_num"));
        $this->set("main_paragraph_num",$defaultInjaz->getVal("main_paragraph_num"));
        $this->set("mainwork_nb_pages",$defaultInjaz->getVal("mainwork_nb_pages"));
        $this->set("mainwork_nb_lines",$defaultInjaz->getVal("mainwork_nb_lines"));

        $this->commit();
        
    }
    
    public static function loadByMainIndex($student_id, $book_id,$create_obj_if_not_found=false)
    {


        $obj = new StudentBook();
        $obj->select("student_id",$student_id);
        $obj->select("main_book_id",$book_id);

        if($obj->load())
        {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
        }
        elseif($create_obj_if_not_found)
        {
            $obj->set("student_id",$student_id);
            $obj->set("main_book_id",$book_id);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
        }
        else return null;
        
    }

    public function getDisplay($lang = 'ar')
    {
        return "انجاز ".$this->id." &larr; ".$this->getTitle($lang);
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
            'main_book_id',
            false,
            $lang
        );
        
        return implode(',', $data);
    }

    public function beforeMaj($id, $fields_updated)
    {
        global $file_dir_name, $lang;

        if($fields_updated["main_chapter_id"])
        {
            
            if((!$fields_updated["main_page_num"]) and (!$fields_updated["main_paragraph_num"]))
            {
                $chp = $this->het("main_chapter_id");
                if($chp) $this->set("main_page_num",$chp->getVal("first_page_num"));
                $this->set("main_paragraph_num",1);
            }
            
        }

        if($fields_updated["main_paragraph_num"])
        {
            if(!$fields_updated["main_page_num"])
            {
                $paragraphObj = $this->calcMain_paragraph_id("object");
                if($paragraphObj) $this->set("main_page_num",$paragraphObj->getVal("page_num"));
            }
        }

        
        

        return true;
    }

    protected function afterUpdate($id, $fields_updated)
    {
        global $lang, $_SESSION;

        
    }

    

    public function getFormuleResult($attribute, $what='value')
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

            
        }

        return $this->calcFormuleResult($attribute,$what);
    }

    

    

    

    protected function getSpecificDataErrors(
        $lang = 'ar',
        $show_val = true,
        $step = 'all'
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

    

    public function stepsAreOrdered()
    {
            return false;
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


    

        

        public function paragraphShort($lang="ar", $attribute)
        {            
            list($book_id, $paragraph_num, $chapter_id, $page_num, $prgh) = $this->getBookLocation($attribute);
            if(!$prgh) return "?!!!? [$chapter_id|$paragraph_num]";
            return AfwStringHelper::truncateArabicJomla($prgh->getVal("paragraph_text"), 32)."($paragraph_num)";
        }
        
        public function calcMain_paragraph_id($what="value")
        {
            return CpcBook::calcAttribute_paragraph_id($this, "main", $what);
        }

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
            $params = CpcBook::getBookParams($this, $attribute);
            $main_sens = $this->getVal("main_sens");
            if(($params["book_id"]==1) and ($main_sens==2))
            {
                $params["book_id"]=10001;
                //$params["chapter_id_to"]=11114;
            
            }
            if(($params["book_id"]<=1) and ($params["chapter_id_from"]>11000))
            {
                $params["book_id"]=10001;
            }
            //elseif($params["book_id"]==10001) $params["chapter_id_to"]=11114;
            //elseif($params["book_id"]==1) $params["chapter_id_to"]=1114;
            // die("... CpcBook::getBookParams($this, $attribute) = ".var_export($params,true));
            return $params;
        }

        


        


        protected function getPublicMethods()
        {
            global $lang;
            $pbms = array();

            // $currSYObj = $this->getCurrentSchoolYear();
            // $disp = $this->getDisplay($lang);
            /*
            $pbms["xWa5YO"] = array(
                "METHOD" => "approveMa inworkAndNext",
                "LABEL_AR" => "تم انجاز الحفظ انتقل الى ما يليه",
                "LABEL_EN" => "approve Main work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "green",
                "STEP" => 1
            );

            $pbms["xYa5Zu"] = array(
                "METHOD" => "approveHomeworkAndNext",
                "LABEL_AR" => "تم انجاز المراجعة الكبرى انتقل الى ما يليه",
                "LABEL_EN" => "approve Home work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "blue",
                "STEP" => 2
            );

            $pbms["hja5Op"] = array(
                "METHOD" => "approveHomework2AndNext",
                "LABEL_AR" => "تم انجاز المراجعة الصغرى انتقل الى ما يليه",
                "LABEL_EN" => "approve Home work Goto Next",
                "PUBLIC" => true,
                "COLOR" => "orange",
                "STEP" => 3
            );
            */
            

            return $pbms;
        }
        

        
        public function calcReal_book_id($what="value")
        {
            $book_id = $this->getVal("main_book_id");    
            if(!$book_id) $book_id = 1;
            $main_sens = $this->getVal("main_sens");

            if(($main_sens==2) and ($book_id==1))
            {
                return 10001;
            }
            else return $book_id;
        }
          
            
        

        public function list_of_main_page_num()
        {
            $part_id =    $this->getVal("main_part_id");
            $chapter_id = $this->getVal("main_chapter_id");

            return StudentFileCourse::list_of_page_nums($chapter_id, $part_id);
        }


        public function getTotalNbPages()
        {
            
            $main_page_num = $this->getVal("main_page_num");
            /*
            $main_sens = $this->getVal("main_sens");
            //$bookObj_id = $this->getVal("main_book_id");
            $real_bookObj_id = $this->calc("main_book_id",false,"value");
            if($main_sens==1)
            {
                if($real_bookObj_id != 10001)
                {
                    $bookObj = $this->het("main_book_id");
                    $book_nb_pages = $bookObj->getVal("book_nb_pages");
                    $return = $book_nb_pages-$main_page_num;
                    $case = "case1 : main_sens=$main_sens bookObj_id == 10001 return = $return = $book_nb_pages-$main_page_num";
                }
                else 
                {
                    $return = $main_page_num;
                    $case = "case2 : main_sens=$main_sens bookObj_id == $bookObj_id return = main_page_num = $return";
                }
            }
            elseif($main_sens==2)
            {
                if($bookObj_id != 10001)
                {
                    // $bookObj = $this->het("main_book_id");               
                    // $book_nb_pages = $bookObj->getVal("book_nb_pages");
                    // $return = $book_nb_pages-$main_page_num;

                    rafik disabled above because main_page_num is related to 
                    $return = $main_page_num;
                    $case = "case3 : main_sens=$main_sens bookObj_id == $bookObj_id return = $book_nb_pages-$main_page_num";
                }
                else 
                {
                    $return = $main_page_num;
                    $case = "case4 : main_sens=$main_sens bookObj_id == 10001  return = main_page_num = $return";
                }
                
            }
            else 
            {
                $return = -1;
                $case = "case5 : main_sens=$main_sens";
            }

            die($this->getDisplay("ar")." ; case= $case");*/

            return $main_page_num;
        }

        


        public function list_of_main_sens()
        {
            global $lang;
            return StudyProgram::workSens()[$lang];
        }

        public function list_of_approval_status_id()
        {
            global $lang;
                $arr_approvalStatus = array();
                
                $arr_approvalStatus["en"][1] = "waiting";
                $arr_approvalStatus["ar"][1] = "في الانتظار";
                
                $arr_approvalStatus["en"][2] = "approved";
                $arr_approvalStatus["ar"][2] = "تم الاعتماد";

                $arr_approvalStatus["en"][3] = "rejected";
                $arr_approvalStatus["ar"][3] = "تم الرفض";

                return $arr_approvalStatus[$lang];
        }

        
        
}
?>
