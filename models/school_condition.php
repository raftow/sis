<?php
// ------------------------------------------------------------------------------------
// 27/1/2023
// ALTER TABLE `school_class` CHANGE `room_id` `room_id` INT(11) NOT NULL DEFAULT '0'; 

$file_dir_name = dirname(__FILE__);

// old include of afw.php       

class SchoolCondition extends SisObject
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
    public static $TABLE            = "";
    public static $DB_STRUCTURE = null; 
    public function __construct()
    {
        parent::__construct("school_condition", "id", "sis");
        SisSchoolConditionAfwStructure::initInstance($this);
    }

    public static function loadById($id)
    {
        $obj = new SchoolCondition();
        if($obj->load($id))
        {
            return $obj;
        }
        else return null;
    }

    public static function loadByMainIndex($school_id, $level_class_id, $min_eval=0, $max_eval=0, $create_obj_if_not_found = false)
    {
        $obj = new SchoolCondition();
        $obj->select("school_id", $school_id);
        $obj->select("level_class_id", $level_class_id);
        $eval_mfk = ",";
        for($i=$min_eval; $i<=$max_eval; $i++)
        {
            $eval_mfk .= $i . ",";
        }
        if ($obj->load()) {
            if ($create_obj_if_not_found)
            {
                $obj->set("level_mfk",self::all_level_mfk());
                $obj->set("age_min",4);
                $obj->set("age_max",75);
                $obj->set("eval_mfk",$eval_mfk);
                $obj->activate();
            } 
            return $obj;
        } elseif ($create_obj_if_not_found) {
            $obj->set("school_id", $school_id);
            $obj->set("level_class_id", $level_class_id);
            $obj->set("level_mfk",self::all_level_mfk());
            $obj->set("age_min",4);
            $obj->set("age_max",75);
            $obj->set("eval_mfk",$eval_mfk);
            $obj->insertNew();
            $obj->is_new = true;
            return $obj;
        } else return null;
    }
/*
    public function getPlac esInfo()
    {
        if ($this->getVal("room_id") > 0) 
        {
            $stdn_count = intval($this->calc("stdn_nb"));
            //die("stdn_count = $stdn_count");
            $room_capacity = intval($this->get("room_id")->getVal("capacity"));
            $needed_stdn = $room_capacity - $stdn_count;
            $room_comment = "$stdn_count طالب";
            if ($needed_stdn <= 0)
                $needed_stdn = 0;
            else
                $room_comment .= " $needed_stdn مقاعد متوفرة";
        } else {
            $needed_stdn = 0;
            $room_comment = " الرجاء تحديد القاعة ";
        }

        return array($needed_stdn, $room_comment);
    }*/




    protected function getPublicMethods()
    {

        $return = array();


        return $return;
    }



    


    protected function getSpecificDataErrors($lang = "ar", $show_val = true, $step = "all")
    {
        $sp_errors = array();

        
        return $sp_errors;
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


        for($d=1;$d<=7;$d++)
        {
            if($attribute == "sched_$d")
            {
                return $this->findInMfk("wdays_mfk",$d,$mfk_empty_so_found=false);
            }
        }


        */

        

        return true;
    }

    public function whyAttributeIsNotApplicable($attribute, $lang = "ar")
    {
        $icon = "na20.png";
        $textReason = $this->translateMessage("ACTIVATE-STATS-COMPUTE-OPTION", $lang);
        return array($icon, $textReason);
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

    public function calcStart_near_date()
    {
        return AfwDateHelper::shiftGregDate('',-5);
    }

    public function calcEnd_near_date()
    {
        return AfwDateHelper::shiftGregDate('',1);
    }

    public static function list_of_wdays_mfk()
    {
            return Hday::list_of_wday_id();
    }


    public static function list_of_level_mfk() {
        return Student::list_of_level();
    }

    public static function all_level_mfk() {
        $return = array_keys(Student::list_of_level());

        $return_mfk = ",".implode(",",$return).",";

        return $return_mfk;
    }

    public function list_of_eval_mfk() {
        return Student::list_of_eval();
    }


    protected function beforeDelete($id,$id_replace) 
        {
            $server_db_prefix = AfwSession::config("db_prefix","c0");
            
            if($id)
            {   
               if($id_replace==0)
               {
                   // FK part of me - not deletable 

                        
                   // FK part of me - deletable 

                   
                   // FK not part of me - replaceable 
                       
                        
                   
                   // MFK

               }
               else
               {
                         // FK on me 
                       
                        
                        // MFK

                   
               } 
               return true;
            }    
        }
}
