<?php
// ------------------------------------------------------------------------------------

$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class StudyProgram extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= ""; 
    public static $DB_STRUCTURE = null;  
    
    
    public function __construct(){
		parent::__construct("study_program","id","sis");
        SisStudyProgramAfwStructure::initInstance($this);
	}
        
    public static function loadById($id)
    {
       $obj = new StudyProgram();
       $obj->select_visibilite_horizontale();
       if($obj->load($id))
       {
            return $obj;
       }
       else return null;
    }
    
    
    
    public static function loadByMainIndex($course_id, $study_program_name_ar, $school_id,$create_obj_if_not_found=false)
    {


       $obj = new StudyProgram();
       $obj->select("course_id",$course_id);
       $obj->select("study_program_name_ar",$study_program_name_ar);
       $obj->select("school_id",$school_id);

       if($obj->load())
       {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
       }
       elseif($create_obj_if_not_found)
       {
            $obj->set("course_id",$course_id);
            $obj->set("study_program_name_ar",$study_program_name_ar);
            $obj->set("school_id",$school_id);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
       }
       else return null;
       
    }


    public function getDisplay($lang="ar")
    {
           if($this->getVal("study_program_name_ar")) return $this->getVal("study_program_name_ar");
           $data = array();
           $link = array();
           


           
           return implode(" - ",$data);
    }
        
        public function getFieldGroupInfos($fgroup)
        {
            
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        public function tryToLoadWithUniqueKeyForEditMode()
        {
            return false;
        }

        public function attributeIsApplicable($attribute)
        {
            if(AfwStringHelper::stringContain($attribute,"_nb_"))
            {
                if($this->getVal("study_program_type") != 3) return false;
            }

            if(AfwStringHelper::stringEndsWith($attribute,"_sens"))
            {
                if($this->getVal("study_program_type") != 3) return false;
            }

            if(AfwStringHelper::stringEndsWith($attribute,"_stop"))
            {
                if($this->getVal("study_program_type") != 3) return false;
            }

                
                if(AfwStringHelper::stringStartsWith($attribute,"homework2_") or ($attribute=="homework2"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework2");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"homework_") or ($attribute=="homework"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"mainwork_") or ($attribute=="mainwork"))
                {
                    $courseObj = $this->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("mainwork");
                }
                
                
                return true;
        }


        protected function getOtherLinksArray($mode,$genereLog=false,$step="all")      
        {
             global $lang;
             // $objme = AfwSession::getUserConnected();
             // $me = ($objme) ? $objme->id : 0;

             $otherLinksArray = $this->getOtherLinksArrayStandard($mode,$genereLog,$step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             
             
             // check errors on all steps (by default no for optimization)
             // rafik don't know why this : \//  = false;
             
             return $otherLinksArray;
        }
        
                
        public function fld_CREATION_USER_ID()
        {
                return "created_by";
        }

        public function fld_CREATION_DATE()
        {
                return "created_at";
        }

        public function fld_UPDATE_USER_ID()
        {
        	return "updated_by";
        }

        public function fld_UPDATE_DATE()
        {
        	return "updated_at";
        }
        
        public function fld_VALIDATION_USER_ID()
        {
        	return "validated_by";
        }

        public function fld_VALIDATION_DATE()
        {
                return "validated_at";
        }
        
        public function fld_VERSION()
        {
        	return "version";
        }

        public function fld_ACTIVE()
        {
        	return  "active";
        }
        
        public function isTechField($attribute) {
            return (($attribute=="created_by") or ($attribute=="created_at") or ($attribute=="updated_by") or ($attribute=="updated_at") or ($attribute=="validated_by") or ($attribute=="validated_at") or ($attribute=="version"));  
        }
        
        
        public function beforeDelete($id,$id_replace) 
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

        public function list_of_homework2_sens()
        {
            global $lang;
            return self::workStartAndSens()[$lang];
        }

        public function list_of_homework2_stop()
        {
            global $lang;
            return self::workStop()[$lang];
        }

        public function list_of_homework_sens()
        {
            global $lang;
            return self::workSensRelative()[$lang];
        }

        public function list_of_homework_stop()
        {
            global $lang;
            return self::workStop()[$lang];
        }

        public function list_of_mainwork_sens()
        {
            global $lang;
            return self::workSens()[$lang];
        }

        public function list_of_study_program_type()
        {
            global $lang;
            return self::studyProgramType()[$lang];
        }

        public function list_of_mainwork_stop()
        {
            global $lang;
            return self::workStop()[$lang];
        }

        public static function workSensRelative()
        {
                $arr_workSens = array();
                
                $arr_workSens["en"][1] = "same as save work";
                $arr_workSens["ar"][1] = "نفس اتجاه الحفظ";
                
                $arr_workSens["en"][2] = "inverse of save work";
                $arr_workSens["ar"][2] = "عكس اتجاه الحفظ";

                $arr_workSens["en"][3] = "same as save work but after end go backward";
                $arr_workSens["ar"][3] = "نفس اتجاه الحفظ لكن عند الانتهاء يعكس الاتجاه";

                return $arr_workSens;
        }

        public static function workStartAndSens()
        {
                $arr_workSens = array();
                
                $arr_workSens["en"][1] = "always from end of saved and inverse of save work";
                $arr_workSens["ar"][1] = "تراكميا من نهاية الحفظ وعكس اتجاه الحفظ";
                
                return $arr_workSens;
        }

        public static function workSens()
        {
                $arr_workSens = array();
                
                $arr_workSens["en"][1] = "from start to end";
                $arr_workSens["ar"][1] = "من البقرة الى الناس";
                
                $arr_workSens["en"][2] = "from end to start";
                $arr_workSens["ar"][2] = "من الناس الى البقرة";

                // $arr_workSens["en"][3] = "fixed on end of saved";
                // $arr_workSens["ar"][3] = "ثابتة من آخر ما وصل اليه الحفظ";

                return $arr_workSens;
        }

        public static function studyProgramType()
        {
                $arr_study_program_type = array();
                
                $arr_study_program_type["en"][1] = "By quantity";
                $arr_study_program_type["ar"][1] = "منهج كمي - حسب الحفظ الجديد";
                
                $arr_study_program_type["en"][2] = "By Saved";
                $arr_study_program_type["ar"][2] = "منهج المحفوظ - حسب مجموع الحفظ كاملا";

                $arr_study_program_type["en"][3] = "Customized";
                $arr_study_program_type["ar"][3] = "منهج مخصص";

                return $arr_study_program_type;
        }

        public static function workStop()
        {
            $arr_workStop = array();
                
            $arr_workStop["en"][1] = "end of the AYA with adding words";
            $arr_workStop["ar"][1] = "نهاية الآية مع الزيادة";
            
            $arr_workStop["en"][2] = "head of the AYA with removing words";
            $arr_workStop["ar"][2] = "رأس الآية مع النقص";

            $arr_workStop["en"][3] = "head of the AYA with nearest add/rem words";
            $arr_workStop["ar"][3] = "رأس الآية مع اختيار الأقرب";

            $arr_workStop["en"][4] = "end of the part if add/rem few words";
            $arr_workStop["ar"][4] = "نهاية الجزء اذا لم يبق عليه الا قليل";

            $arr_workStop["en"][5] = "end of the page";
            $arr_workStop["ar"][5] = "نهاية الوجه";

            $arr_workStop["en"][6] = "end of the part";
            $arr_workStop["ar"][6] = "نهاية الجزء";

            $arr_workStop["en"][7] = "start of the page";
            $arr_workStop["ar"][7] = "بداية الوجه";

            return $arr_workStop;
        }

        public function list_of_homework_round_type()
        {
            global $lang;
            return self::roundType()[$lang];
        }

        public function list_of_homework2_round_type()
        {
            global $lang;
            return self::roundType()[$lang];
        }

        public static function roundType()
        {
            $arr_roundType = array();
            
            $arr_roundType["en"][1] = "no round";
            $arr_roundType["ar"][1] = "بدون تقريب";            

            $arr_roundType["en"][2] = "to half of a part";
            $arr_roundType["ar"][2] = "الى نصف الجزء";
            
            $arr_roundType["en"][3] = "to a part";
            $arr_roundType["ar"][3] = "الى الجزء";

            $arr_roundType["en"][4] = "to half part for few otherwise to a part";
            $arr_roundType["ar"][4] = "الى نصف الجزء في القليل والا فالى الجزء";


            $arr_roundType["en"][5] = "to half of a page";
            $arr_roundType["ar"][5] = "الى نصف الوجه";
            
            $arr_roundType["en"][6] = "to a page";
            $arr_roundType["ar"][6] = "الى الوجه";

            $arr_roundType["en"][7] = "to half page for few otherwise to a page";
            $arr_roundType["ar"][7] = "الى نصف الوجه في القليل والا فالى الوجه";


            return $arr_roundType;
        }

        public static function roundRule($parts, $pages, $lines, $round_type, $many_parts=5)
        {
            $old_parts = $parts;
            $old_pages = $pages;
            $old_lines = $lines;
            
            
            // 4] = "الى نصف الجزء في القليل والا فالى الجزء";
            if($round_type == 4)
            {
                if($parts>=$many_parts)
                {
                    $round_type = 3;
                }
                else
                {
                    $round_type = 2;
                }
            }

            if($round_type == 7)
            {
                if($parts>=$many_parts)
                {
                    $round_type = 6;
                }
                else
                {
                    $round_type = 5;
                }
            }

            // 2] = "الى نصف الجزء";
            if($round_type == 2)
            {
                $pages = round($pages/10)*10;
                if($pages==20) 
                {
                    $pages = 0;
                    $parts++;
                }
            }

            // 3] = "الى الجزء";
            if($round_type == 3)
            {
                $pages = round($pages/20)*20;
                if($pages==20) 
                {
                    $pages = 0;
                    $parts++;
                }
            }

            // 5] = "الى نصف الوجه";
            if($round_type == 5)
            {
                $lines = round($lines/7)*7;
                if($lines>=14) 
                {
                    $lines = 0;
                    $pages++;
                }
            }

            // 6] = "الى الوجه";
            if($round_type == 6)
            {
                $lines = round($lines/14)*14;
                if($lines>=14) 
                {
                    $lines = 0;
                    $pages++;
                }
            }

            if(!$parts and !$pages and !$lines)
            {
                $parts = $old_parts;
                $pages = $old_pages;
                $lines = $old_lines;
            }

            return [$parts, $pages, $lines, $round_type];
        }

        protected function getPublicMethods()
        {
            
            $pbms = array();
            
            $color = "green";
            $title_ar = "توليد قواعد المنهج"; 
            $methodName = "generateRules";
            $pbms[AfwStringHelper::hzmEncode($methodName)] = array("METHOD"=>$methodName,"COLOR"=>$color, 
                                        "LABEL_AR"=>$title_ar, "ADMIN-ONLY"=>true, 
                                        "BF-ID"=>"", 'STEP' =>$this->stepOfAttribute("studyProgramRuleList"));
            
            
            
            return $pbms;
        }

        

        public function generateRules($lang="ar")
        {
            global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;

            $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
            $MODE_SQL_PROCESS_LOURD = true;
            $study_program_id = $this->getId();
            list($res,$rcn, $cnt) = StudyProgramRule::deleteWhere("study_program_id=$study_program_id");
            $ord = 0;
            $nb_new_save_rule = 0;
            $nb_new_total_rule = 0;
            // a = a_pct, b = b_pct, c = c_pct
            $a = $this->getVal("a_pct");
            $b = 1.0/$this->getVal("b_pct");
            $c = $this->getVal("c_pct");

            $studyProgramType = $this->getVal("study_program_type");
            $homework_round_type = $this->getVal("homework_round_type");
            $homework2_round_type = $this->getVal("homework2_round_type");

            if($studyProgramType <= 2)
            {
                $max_new_pages = AfwSession::config("max_new_pages",5);
                for($new_pages = 0; $new_pages<=$max_new_pages; $new_pages++)
                {
                    $new_lines_arr = [];
                    if($new_pages == 0) $new_lines_arr = [1,2,3,4,5,6,7,8,9,10]; // ,11,12,13,14
                    elseif($new_pages == $max_new_pages) $new_lines_arr = [0];
                    else $new_lines_arr = [0,7]; 
    
                    foreach($new_lines_arr as $new_lines)
                    {
                        $nb_new_save_rule++;
                        $ord += 10;
                        $newObj = StudyProgramRule::loadByMainIndex($study_program_id, $ord, true);
                        $newObj->set("new_nb_pages", $new_pages);
                        $newObj->set("new_nb_lines", $new_lines);
                        $newObj->set("total_nb_pages", 0);
    
                        $newObj->set("mainwork_nb_parts", 0);
                        $newObj->set("mainwork_nb_pages", $new_pages);
                        $newObj->set("mainwork_nb_lines", $new_lines);
    
                        // x = mainwork
                        $x_pages = $new_pages;
                        $x_lines = $new_lines;
                        
                        if($studyProgramType != 2)
                        {
                            // y = homework
                            // y = ax + bt
                            // here t = 0
                            $y_pages_lines = round($a*$x_pages*15);
                            $y_pages_rest_lines = $y_pages_lines % 15;
                            $y_pages_net = intval(round(($y_pages_lines - $y_pages_rest_lines)/15));
        
                            $y_lines = round($a*$x_lines) + $y_pages_rest_lines;
                            $y_lines_final = $y_lines % 15;
                            $y_lines_become_pages = intval(round(($y_lines - $y_lines_final)/15));
        
                            $y_pages = $y_pages_net + $y_lines_become_pages;
        
                            $y_pages_final = $y_pages % 20;
                            $y_parts = intval(round(($y_pages - $y_pages_final)/20)); 


                            list($y_parts, $y_pages_final, $y_lines_final) = self::roundRule($y_parts, $y_pages_final, $y_lines_final, $homework_round_type);
        
                            $newObj->set("homework_nb_parts", $y_parts);
                            $newObj->set("homework_nb_pages", $y_pages_final);
                            $newObj->set("homework_nb_lines", $y_lines_final);
                        }
                        else
                        {
                            $newObj->set("homework_nb_parts", 0);
                            $newObj->set("homework_nb_pages", 0);
                            $newObj->set("homework_nb_lines", 0);
                        }
    
                        // z = homework2
                        // z = cx + dt
                        // here t = 0
                        // here d = 0
                        $z_pages_lines = round($c*$x_pages*15);
                        $z_pages_rest_lines = $z_pages_lines % 15;
                        $z_pages_net = intval(round(($z_pages_lines - $z_pages_rest_lines)/15));
    
                        $z_lines = round($c*$x_lines) + $z_pages_rest_lines;
                        $z_lines_final = $z_lines % 15;
                        $z_lines_become_pages = intval(round(($z_lines - $z_lines_final)/15));
    
                        $z_pages = $z_pages_net + $z_lines_become_pages;
    
                        $z_pages_final = $z_pages % 20;
                        $z_parts = intval(round(($z_pages - $z_pages_final)/20)); 

                        list($z_parts, $z_pages_final, $z_lines_final) = self::roundRule($z_parts, $z_pages_final, $z_lines_final, $homework2_round_type);
    
                        $newObj->set("homework2_nb_parts", $z_parts);
                        $newObj->set("homework2_nb_pages", $z_pages_final);
                        $newObj->set("homework2_nb_lines", $z_lines_final);
    
                        $newObj->commit();
                    }
                }
            }

            if($studyProgramType == 2)
            {
                $max_total_parts = 30;
                $many_parts = 4;
                for($total_parts = 0; $total_parts<=$max_total_parts; $total_parts++)
                {
                    $total_pages_arr = [];
                    if($total_parts == 0) $total_pages_arr = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]; // 
                    elseif($total_parts >= $many_parts) $total_pages_arr = [0];
                    else $total_pages_arr = [0,10]; 
    
                    foreach($total_pages_arr as $total_pages)
                    {
                        $nb_new_total_rule++;
                        $ord += 10;
                        $newObj = StudyProgramRule::loadByMainIndex($study_program_id, $ord, true);
                        $newObj->set("new_nb_pages", 0);
                        $newObj->set("new_nb_lines", 0);
                        $total_nb_pages = $total_pages+20*$total_parts;
                        $newObj->set("total_nb_pages", $total_nb_pages);
    
                        $newObj->set("mainwork_nb_parts", 0);
                        $newObj->set("mainwork_nb_pages", 0);
                        $newObj->set("mainwork_nb_lines", 0);
    
                        // t = total_nb_pages
                        $t_pages = $total_nb_pages;
                        
                        
                        // y = homework
                        // y = ax + bt
                        // here x = 0 => y = bt
                        $y_pages = round($b*$t_pages);
                        $y_pages_final = $y_pages % 20;
                        $y_parts = intval(round(($y_pages - $y_pages_final)/20)); 

                        list($y_parts, $y_pages_final, ) = self::roundRule($y_parts, $y_pages_final, 0, $homework_round_type);
    
                        if(($y_parts==0) and ($y_pages_final==0)) $y_pages_final = 1; // أقل شيء وجه للمراجعة الكبرى
                        $newObj->set("homework_nb_parts", $y_parts);
                        $newObj->set("homework_nb_pages", $y_pages_final);
                        $newObj->set("homework_nb_lines", 0);
    
                        // z = homework2
                        // z = cx + dt
                        // here x = 0
                        // here d = 0
                        $newObj->set("homework2_nb_parts", 0);
                        $newObj->set("homework2_nb_pages", 0);
                        $newObj->set("homework2_nb_lines", 0);
    
                        $newObj->commit();
                    }
                }
            }

            $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
            $nb_queries_executed = 0;

            return ["","new rules : s$nb_new_save_rule t$nb_new_total_rule", "deleted rules : $cnt"];

        }

        public function stepsAreOrdered()
        {
                return false;
        }

                                     
        public function getWorkCoefs($new_nb_pages, $new_nb_lines, $total_nb_pages, $attribute)
        {
            $work_nb_parts = -1;
            $work_nb_pages = -1;
            $work_nb_lines = -1;
            list($objRule, $sql) = StudyProgramRule::loadTheGoodRule($this->id, $new_nb_pages, $new_nb_lines, $total_nb_pages, $attribute);


            $work_nb_parts_attr = $attribute."_nb_parts";
            $work_nb_pages_attr = $attribute."_nb_pages";
            $work_nb_lines_attr = $attribute."_nb_lines";

            if($objRule)
            {
                $work_nb_parts = $objRule->getVal($work_nb_parts_attr);
                $work_nb_pages = $objRule->getVal($work_nb_pages_attr);
                $work_nb_lines = $objRule->getVal($work_nb_lines_attr);
            }

            return [$work_nb_parts,$work_nb_pages,$work_nb_lines, $objRule, $work_nb_parts_attr, $work_nb_lines_attr, $work_nb_pages_attr, $sql];
        }

        
        /*
        protected function attributeCanBeEditedBy($attribute, $user, $desc)
        {
            // this method can be orverriden in sub-classes
            // write here your cases
            // ...
            // return type is : array($can, $reason)
            
            if($attribute == "birth_date")
            {
                return AfwSession::config("study_program_birth_date_hijri",true);
            }

            // but keep that by default we should use standard HZM-UMS model
            return [true, ''];
        }
        */
        
        
}
?>
