<?php
// 3/1/2023
// ALTER TABLE `scandidate` CHANGE `level_class_id` `level_class_id` INT(11) NOT NULL DEFAULT '0';
// ALTER TABLE `scandidate` CHANGE `student_id` `student_id` BIGINT(20) NOT NULL DEFAULT '0';
// ALTER TABLE `scandidate` ADD `class_name` VARCHAR(64) NULL AFTER `student_created`; 
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Scandidate extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "scandidate"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("scandidate","id","sis");
                SisScandidateAfwStructure::initInstance($this);                
	}
        
        public static function loadByMainIndex($school_id, $year, $student_id, $level, $eval, $capacity, $moral, $class_name, $create_update=false)
        {
           if(!$student_id) throw new RuntimeException("loadByMainIndex : student_id is mandatory field");
           if(!$year) throw new RuntimeException("loadByMainIndex : year is mandatory field");
           if(!$school_id) throw new RuntimeException("loadByMainIndex : school_id is mandatory field");


           $obj = new Scandidate();
           $obj->select("school_id",$school_id);
           $obj->select("year",$year);
           $obj->select("student_id",$student_id);

           if($obj->load())
           {
                if($create_update)
                {
                        $obj->set("eval",$eval);                
                        $obj->set("level",$level);
                        $obj->set("capacity",$capacity);
                        $obj->set("moral",$moral);
                        $obj->set("class_name",$class_name);
                        $obj->set("candidate_status_id",1);
                        
                        
                        $obj->activate();
                }
                return $obj;
           }
           elseif($create_update)
           {
                $obj->set("school_id",$school_id);
                $obj->set("year",$year);
                $obj->set("student_id",$student_id);
                $obj->set("eval",$eval);                
                $obj->set("level",$level);
                $obj->set("capacity",$capacity);
                $obj->set("moral",$moral);
                $obj->set("class_name",$class_name);
                $obj->set("candidate_status_id",1);
                
                $obj->insertNew();
                if(!$obj->id) return null; // means beforeInsert rejected insert operation
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }
        
        public function getDisplay($lang="ar")
        {
               //list($data,$link) = $this->displayAttribute("year");
               //list($data2,$link2) = $this->displayAttribute("school_id");
               //list($data3,$link3) = $this->displayAttribute("student_id");
               return "متقدم  : ".$this->getVal("full_name");
        }

        public function getShortDisplay($lang="ar")
        {
               // list($data3,$link3) = $this->displayAttribute("student_id");
               return "متقدم  : ".$this->getVal("full_name");
        }
        
        public function list_of_year()
	{
		$file_dir_name = dirname(__FILE__);
                
                include_once("$file_dir_name/../afw/common_date.php");
                list($hijri_year,$mm,$dd) = AfwDateHelper::currentHijriDate("hlist");
                $hijri_year = intval($hijri_year);
                
                $arr_list_of_year = array();
                
                $hijri_year_m_1 = $hijri_year-1;
                $hijri_year_p_1 = $hijri_year+1;
                $hijri_year_p_2 = $hijri_year+2;
                
                $arr_list_of_year[$hijri_year_m_1] = "$hijri_year_m_1-$hijri_year";
                $arr_list_of_year[$hijri_year] = "$hijri_year-$hijri_year_p_1";
                $arr_list_of_year[$hijri_year_p_1] = "$hijri_year_p_1-$hijri_year_p_2";
                
                return $arr_list_of_year;
	}

        protected function initObject()
        {
                $this->set("candidate_hdate",AfwDateHelper::currentHijriDate());
                return true;
        }

        protected function getPublicMethods()
        {
                $pbms = array();
                
                if(true)
                {
                        $pbms["xHff34"] = array("METHOD"=>"repareMe", 
                                                "LABEL_AR"=>"تصحيح البيانات", 
                                                "LABEL_EN"=>"fix My Data",
                                                "BF-ID"=>"" 
                                                ); // 


                        $pbms['b5yh1u'] = [
                                'METHOD' => 'applyCondition',
                                'LABEL_AR' => 'تطبيق شروط القبول',
                                'LABEL_EN' => 'apply admission rules',
                                'BF-ID' => 104618,
                                'COLOR' => 'blue',
                                'STEP' => 2,
                        ];
                
                        $pbms['a3H71e'] = [
                                'METHOD' => 'cancelApplyCondition',
                                'LABEL_AR' => 'الغاء تطبيق شروط القبول',
                                'LABEL_EN' => 'cancel apply admission rules',
                                'BF-ID' => 104618,
                                'COLOR' => 'red',
                                'STEP' => 7,
                        ];                                                

                        $pbms["Yasd15"] = array("METHOD"=>"assignMeToSuggestedSchoolClass", 
                                                "LABEL_AR"=>"تحديث بياناتي وتعييني في الحلقة المقترح", 
                                                "LABEL_EN"=>"assign Me To a Class",
                                                "COLOR"=>"green",
                                                "BF-ID"=>"" 
                                                ); //


                        $pbms["YT7034"] = array("METHOD"=>"assignMeToSchoolClass", 
                                                "LABEL_AR"=>"تعييني في أحد الصفوف أيا كان", 
                                                "LABEL_EN"=>"assign Me To any Class",
                                                "COLOR"=>"blue",
                                                "BF-ID"=>"" 
                                                ); // 

                                                 
                                                
                                                
                        $pbms["x5yi1u"] =  [
                                'METHOD' => 'calcGeneralEvaluation',
                                'LABEL_AR' => 'التقييم الشامل وتحديد المستوى ونقاط التوزيع',
                                'LABEL_EN' => 'calculate general evaluation',
                                'BF-ID' => 104618,
                                'COLOR' => 'orange',
                                'STEP' => 2,
                        ];
                                                
                                                
                }
                
                return $pbms;  
        }


        public function repareMe($lang="ar", $fields_updated=[], $commit=true)
        {
                $obj = $this->hetStudent();
                if($obj)
                {
                        if(!$this->getVal("level")) $this->set("level", $obj->getVal("level"));
                        if(!$this->getVal("eval")) $this->set("eval", $obj->getVal("eval"));
                        $this->set("birth_date_en", $obj->getVal("birth_date_en"));
                        
                        $full_name = $obj->getDisplay("ar");
                        // die("full_name of $student_id = ".var_export($full_name,true));
                        $this->set("full_name",$full_name);
                }

                $syObj = $this->getSchoolYearObject();
                if($syObj)
                {
                        $start_date_en = AfwDateHelper::hijriToGreg($syObj->getVal("school_year_start_hdate"));
                        $this->set("start_date_en",$start_date_en); 
                }

                if(!$this->getVal("candidate_status_id"))
                {
                        // بسم الله
                        $this->set("candidate_status_id", 1); // pending
                }                
                
                if(!$this->getVal("candidate_hdate"))
                {
                        $this->set("candidate_hdate",AfwDateHelper::currentHijriDate());
                }

                if($commit) $this->commit();
        }
        
        protected function beforeMaj($id, $fields_updated) 
        {
                
                $this->repareMe("ar", $fields_updated, false);
                
                return true;
        }


        public function calcSchool_year_id()
        {
                return $this->getVal("school_id").$this->getVal("year")."00";
        }

        public function getSchoolYearObject()
        {
                return SchoolYear::loadByMainIndex($this->getVal("school_id"),$this->getVal("year"));
        }

        public function list_of_level() {
                return Student::list_of_level();
        }

        public function list_of_eval() {
                return Student::list_of_eval();
        }

        public function list_of_moral() {
                $list_of_items = array();
                if(AfwSession::config("moral_poor",false))
                {
                        $list_of_items[1] = "ضعيف جدا";
                        $list_of_items[2] = "ضعيف";
                }
                $list_of_items[3] = "مقبول";
                $list_of_items[4] = "جيد";
                $list_of_items[5] = "ممتاز";

                return  $list_of_items;
        }

        public function list_of_capacity() {
                $list_of_items = array();
                $list_of_items[1] = "ضعيف جدا";
                $list_of_items[2] = "ضعيف";
                $list_of_items[3] = "مقبول";
                $list_of_items[4] = "جيد";
                $list_of_items[5] = "ممتاز";

                return  $list_of_items;
        }

        public function list_of_presence() {
                $list_of_items = array();
                $list_of_items[1] = "حضوري";
                $list_of_items[2] = "عن بعد";

                return  $list_of_items;
        }

        public function calcAge()
        {
                list($birth_date_en,) = explode(" ",$this->getVal("birth_date_en"));
                list($start_date_en,) = explode(" ",$this->getVal("start_date_en")); //date("Y-m-d");
                // die("start_date_en = $start_date_en , birth_date_en = $birth_date_en");
                if((strlen($birth_date_en)==10) and (strlen($start_date_en)==10))
                {                        
                        $diff = diff_date($start_date_en,$birth_date_en);
                        $age = round(($diff/354.0)*2)/2;
                }
                else $age = 0;
                
                return $age; // "$age = round of $diff = diff_date($today,$gdob)";
        }

        public function calcGeneralEvaluation($lang = 'ar', $objSchool=null)
        {
                
                if(!$objSchool) $objSchool = $this->hetSchool();
                if(!$objSchool) return ["No school defined for this candidate",""];

                $err_arr = [];
                $inf_arr = [];

                $age_coef = $objSchool->getVal("age_coef");
                $eval_coef = $objSchool->getVal("eval_coef");
                $moral_coef = $objSchool->getVal("moral_coef");
                $capacity_coef = $objSchool->getVal("capacity_coef");

                $general_max = $objSchool->getVal("general_max");
                if(!$general_max) $general_max = 100;

                $inf_arr[] = "General evaluation of ".$this->getDisplay($lang)." with general_max = $general_max";

                $ageV = $this->ageValue($general_max);
                $evalV = $this->evalValue($general_max);
                $moralV = $this->moralValue($general_max);
                $capacityV = $this->capacityValue($general_max);

                $general = round(($age_coef / 100)*$ageV + ($eval_coef / 100)*$evalV + ($moral_coef / 100)*$moralV + ($capacity_coef / 100)*$capacityV);
                $inf_arr[] = "general = round(($age_coef / 100)*$ageV + ($eval_coef / 100)*$evalV + ($moral_coef / 100)*$moralV + ($capacity_coef / 100)*$capacityV) = $general";
                $this->set("general", $general);


                $age_distrib = $objSchool->getVal("age_distrib");
                $eval_distrib = $objSchool->getVal("eval_distrib");
                $moral_distrib = $objSchool->getVal("moral_distrib");
                $capacity_distrib = $objSchool->getVal("capacity_distrib");

                $distrib_max = 100;

                $inf_arr[] = "Distrib evaluation of ".$this->getDisplay($lang)." with distrib_max = $distrib_max";

                $ageV = $this->ageValue($distrib_max);
                $evalV = $this->evalValue($distrib_max);
                $moralV = $this->moralValue($distrib_max);
                $capacityV = $this->capacityValue($distrib_max);

                $distrib = round(($age_distrib / 100)*$ageV + ($eval_distrib / 100)*$evalV + ($moral_distrib / 100)*$moralV + ($capacity_distrib / 100)*$capacityV);
                $inf_arr[] = "distrib = round(($age_distrib / 100)*$ageV + ($eval_distrib / 100)*$evalV + ($moral_distrib / 100)*$moralV + ($capacity_distrib / 100)*$capacityV) = $distrib";

                $this->set("distrib", $distrib);
                $inf_arr[] = "---------------------------------------------------------------------------------";
                
                $this->commit();

                return self::pbm_result($err_arr,$inf_arr);
        }


        public function capacityValue($max=100)
        {
                return round($this->getVal("capacity") * $max / 5);
        }

        public function moralValue($max=100)
        {
                return round($this->getVal("moral")  * $max / 5);
        }

        public function evalValue($max=100)
        {
                $max_eval_sis = AfwSession::config("max_eval_sis",30);
                return round($this->getVal("eval") * $max / $max_eval_sis);
        }


        public function ageValue($max=100)
        {
                $age =  $this->calcAge();
                if($age<4) $age = 4;
                if($age>=54) $age = 54;

                $x = $age - 3;

                return round(self::circularInversed($x)*$max/100);
        }


        /**
         * $x between 1 and 51
         * return $y between 50 and 100
         * 
         */
        public static function circularInversed($x)
        {
                $w = 51- $x;
                $y = 50 + sqrt(2500 - $w*$w);

                return $y;
        }


        public function cancelApplyCondition($lang="ar")
        {
                $this->set("level_class_id",0);
                $this->set("candidate_status_id", 1);
                $this->set("comments","");
                $this->commit();

                return ["",$this->tm("applying conditions canceled on")." : ".$this->getDisplay($lang)];
        }

        public function assignMeToSuggestedSchoolClass($lang="ar")
        {
                return $this->assignMeToSchoolClass($lang="ar", $updateData = true, $default_genre_id=1, $redistribute = true);  
        }

        public function assignMeToSchoolClass($lang="ar", $updateData = false, $default_genre_id=1, $redistribute = false)
        {
                $nb_assign = 0;
                $nb_update = 0;
                $arr_updates = [];
                $arr_assign = [];
                
                
                
                $sfObj = StudentFile::loadByMainIndex(
                        $this->getVal('student_id'),
                        $this->getVal('school_id'),
                        $this->getVal('year'),
                        $this->calc('levels_template_id'),
                        $this->calc('school_level_order'),
                        $this->calc('level_class_order'),
                        $create_obj_if_not_found = true
                    );
        
                if($sfObj->is_new or $updateData)
                {
                        $studentObj = $this->het('student_id');
        
                        if($this->getVal("genre_id")) $genre_id = $this->getVal("genre_id");
                        else $genre_id = $default_genre_id;
                        $sfObj->set("genre_id", $genre_id);
        
                        if($studentObj)
                        {
                                $sfObj->set("firstname", $studentObj->getVal("firstname"));
                                $sfObj->set("f_firstname", $studentObj->getVal("f_firstname"));
                                $sfObj->set("lastname", $studentObj->getVal("lastname"));
                        }
                        else
                        {
                                $sfObj->set("firstname", $this->getVal("firstname"));
                                $sfObj->set("f_firstname", $this->getVal("f_firstname"));
                                $sfObj->set("lastname", $this->getVal("lastname"));
                        }
                        $sfObj->set("mobile", $this->getVal("mobile"));
                        $sfObj->set("country_id", $this->getVal("country_id"));
                        $sfObj->set("birth_date", $this->getVal("birth_date"));
                        $sfObj->set("birth_date_en", $this->getVal("birth_date_en"));
                        $sfObj->set("parent_mobile", $this->getVal("parent_mobile"));
                        $sfObj->set("parent_idn_type_id", $this->getVal("parent_idn_type_id"));
                        $sfObj->set("parent_idn", $this->getVal("parent_idn"));
                        $sfObj->set("mother_mobile", $this->getVal("mother_mobile"));
                        $sfObj->set("mother_idn_type_id", $this->getVal("mother_idn_type_id"));
                        $sfObj->set("mother_idn", $this->getVal("mother_idn"));
                        $sfObj->set("address", $this->getVal("address"));
                        $sfObj->set("cp", $this->getVal("cp"));
                        $sfObj->set("quarter", $this->getVal("quarter"));
                        $sfObj->set("email", $this->getVal("email"));
        
                        $sfObj->set("eval", $this->getVal("eval"));
                        $sfObj->set("level", $this->getVal("level"));
                        //$sfObj->set("email", $this->getVal("email"));
                        $sfObj->commit();
                        $nb_update++;
                        $arr_updates[] = 'تم تحديث بيانات  ' . $sfObj->getDisplay($lang);
                }

                $sugg_class_name = trim($this->getVal('class_name'));
        
                if (!$sfObj->getVal('class_name') or $redistribute) 
                {
                        $syObj = $this->getSchoolYearObject();
                        list($scObj, $log) = $syObj->findAvailableSchoolClass($this->getVal('level_class_id'),$sugg_class_name,true);
                        if ($scObj) {
                                $sfObj->set('class_name', $scObj->getVal('class_name'));
                                $sfObj->commit();
                                $this->set("candidate_status_id",4); // approved
                                $nb_assign++;
                                $arr_assign[] =
                                        'تم تعيين '.$this->getShortDisplay($lang) .
                                        ' في ' .
                                        $scObj->getDisplay($lang);
                        }
                        else
                        {
                                $arr_assign[] =  "لا يوجد حلقة متاحة للمستوى : ".$this->showAttribute('level_class_id')." trim($sugg_class_name) $log";
                        }
                }
                else
                {
                        $this->set("candidate_status_id",4); // approved
                        $arr_assign[] =  " يوجد حلقة مسبقة : ".$sfObj->getVal('class_name')." sfObj=".var_export($sfObj,true);
                }

                $this->commit(); 

                $my_inf = ($nb_assign>0) ? "$nb_assign حالة تمت معالجتها واسنادها كالتالي : " . implode("<br>\n", $arr_assign) : "";
                $my_tech = ($nb_update>0) ? "$nb_update ملفات تم تحديثها كالتالي : " . implode("<br>\n", $arr_updates) : "";

                return ['', $my_inf, "", $my_tech];
        }


        public function applyCondition($lang="ar", $objSchool=null)
        {
                if(!$objSchool) $objSchool = $this->hetSchool();
                if(!$objSchool) return [$this->tm("no school defined for the candidate",$lang)." : ".$this->getDisplay($lang),""];
                $tempObj = $objSchool->hetTemplate();
                if(!$tempObj) return [$this->tm("no levels template defined for this school",$lang)." : ".$objSchool->getDisplay($lang),""];
                $conds_applied = 0;
                $accepted = 2;
                $reason = "";
                $applied_conds = "";
                
                // APPLY GENERAL CONDITIONS
                // apply age condition

                // apply genre condition

                // apply school level conditions
                if($accepted==2)
                {
                        // determine school level and level class                       
                        $general = $this->getVal("general");
                        $levelClassObj = $tempObj->getLevelClassOf($general);
                        if(!$levelClassObj) return [$this->tm("no level class defined for this general evaluation ",$lang)." : ".$general,""];
                        $this->set("level_class_id",$levelClassObj->id);

                        // apply level class conditions
                        $scond = SchoolCondition::loadByMainIndex($objSchool->id,$levelClassObj->id);

                        if($scond)
                        {
                                // age cond
                                $age_min = $scond->getVal("age_min");
                                $age_max = $scond->getVal("age_max");
                                $age = $this->calcAge();
                                if(($age > $age_max) or ($age < $age_min))
                                {
                                        $accepted = 3; // rejected
                                        $reason = $this->tm("age is not in the requested interval",$lang);
                                }
                                else
                                {
                                        $applied_conds .= "<br> >> $age_min <= $age <= $age_max";
                                }
                                $conds_applied++;

                                // 'level_mfk' cond
                                if($accepted==2)
                                {
                                        $levels_mfk_arr = explode(",",trim($scond->getVal("level_mfk"),","));
                                        $level = $this->getVal("level");
                                        if(!in_array($level,$levels_mfk_arr))
                                        {
                                                $accepted = 3; // rejected
                                                $reason = $this->tm("level is not in the requested list",$lang);
                                        }
                                        else
                                        {
                                                $applied_conds .= "<br> >> $level in (".$scond->getVal("level_mfk").")";
                                        }
                                        $conds_applied++;
                                }

                                // 'eval_mfk' cond
                                if($accepted==2)
                                {
                                        $eval_mfk_arr = explode(",",trim($scond->getVal("eval_mfk"),","));
                                        $eval = $this->getVal("eval");
                                        if(!in_array($eval,$eval_mfk_arr))
                                        {
                                                $accepted = 3; // rejected
                                                $reason = $this->tm("eval is not in the requested list",$lang);
                                        }
                                        else
                                        {
                                                $applied_conds .= "<br> >> $eval in (".$scond->getVal("eval_mfk").")";
                                        }

                                        $conds_applied++;
                                }
                        }
                }

                $this->set("candidate_status_id",$accepted);
                $this->set("comments",$reason);
                $this->commit();

                return ["",$conds_applied." ".$this->tm("applyied conditions on")." : ".$this->getDisplay($lang)."\n<br> => $applied_conds"];
        }

        public function getClassNames()
        {
                global $clNames;
                $sy_id = $this->calcSchool_year_id();
                if($clNames[$sy_id]) return $clNames[$sy_id];
                $scObj = new SchoolClass();
                $scObj->select("school_year_id", $sy_id);
                $scObj->select("active","Y");
                $scList = $scObj->loadMany();
                $return = [];
                foreach($scList as $scItem)
                {
                        $return[trim($scItem->getVal("class_name"))] = trim($scItem->getVal("class_name"));
                }

                $clNames[$sy_id] = $return;

                return $return;

        }

        
}
?>