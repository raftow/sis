<?php
// ------------------------------------------------------------------------------------

use PhpOffice\PhpSpreadsheet\RichText\Run;

$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class StudyProgramRule extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "study_program_rule"; 
    public static $DB_STRUCTURE = null;  
    
    
    public function __construct(){
		parent::__construct("study_program_rule","id","sis");
        SisStudyProgramRuleAfwStructure::initInstance($this);
	}
        
    public static function loadById($id)
    {
       $obj = new StudyProgramRule();
       $obj->select_visibilite_horizontale();
       if($obj->load($id))
       {
            return $obj;
       }
       else return null;
    }
    
    
    
    public static function loadTheGoodRule($study_program_id, $new_nb_pages, $new_nb_lines, $total_nb_pages, $attribute)
    {
        if(!$new_nb_pages) $new_nb_pages = 0;
        if(!$new_nb_lines) $new_nb_lines = 0;
        if(!$total_nb_pages) $total_nb_pages = 0;

        $obj = new StudyProgramRule();
        $obj->select("study_program_id",$study_program_id);
        $work_nb_parts_attr = $attribute."_nb_parts";
        $work_nb_pages_attr = $attribute."_nb_pages";
        $work_nb_lines_attr = $attribute."_nb_lines";
        $obj->where("$work_nb_parts_attr > 0 or $work_nb_pages_attr > 0 or $work_nb_lines_attr > 0");
        if($new_nb_pages==0)
        {
            $obj->where("(new_nb_lines <= $new_nb_lines) or (total_nb_pages <= $total_nb_pages)");
        }
        else
        {
            $obj->where("(new_nb_pages <= $new_nb_pages or (new_nb_pages=$new_nb_pages and new_nb_lines <= $new_nb_lines)) 
                            or (total_nb_pages <= $total_nb_pages)");
        }
        


        if($total_nb_pages>0) $order_by = "total_nb_pages desc, new_nb_pages desc, new_nb_lines desc";                
        else $order_by = "new_nb_pages desc, new_nb_lines desc, total_nb_pages desc";                
        $sql = "SELECT * FROM `study_program_rule` me where 1 ".$obj->getSQL()." ORDER BY ".$order_by." LIMIT 1";
        // die("obj->getSQL() = $sql");
        $objList = $obj->loadMany(1,$order_by);
        if(count($objList)>0)
        {
            foreach($objList as $objItem) return [$objItem, $sql];
        }
        else return [null, $sql];
    }

    public static function loadByMainIndex($study_program_id, $program_order,$create_obj_if_not_found=false)
    {


       $obj = new StudyProgramRule();
       $obj->select("study_program_id",$study_program_id);
       $obj->select("program_order",$program_order);
      

       if($obj->load())
       {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
       }
       elseif($create_obj_if_not_found)
       {
            $obj->set("study_program_id",$study_program_id);
            $obj->set("program_order",$program_order);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
       }
       else return null;
       
    }


    public function getDisplay($lang="ar")
    {
        $condition = $this->calc("condition");
        $study_program = $this->showAttribute('study_program_id');
        $program_order = $this->showAttribute('program_order');
        
        return "$study_program ← قاعدة رقم $program_order ← $condition";
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

                
                if(AfwStringHelper::stringStartsWith($attribute,"homework2_") or ($attribute=="homework2"))
                {
                    $studyProgramObj = $this->het("study_program_id");
                    if(!$studyProgramObj) return false;
                    $courseObj = $studyProgramObj->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework2");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"homework_") or ($attribute=="homework"))
                {
                    $studyProgramObj = $this->het("study_program_id");
                    if(!$studyProgramObj) return false;
                    $courseObj = $studyProgramObj->hetCourse();
                    if(!$courseObj) return false;
                    return $courseObj->getVal("homework");
                }

                if(AfwStringHelper::stringStartsWith($attribute,"mainwork_") or ($attribute=="mainwork"))
                {
                    $studyProgramObj = $this->het("study_program_id");
                    if(!$studyProgramObj) return false;
                    $courseObj = $studyProgramObj->hetCourse();
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
        
        protected function getPublicMethods()
        {
            
            $pbms = array();
            
            $color = "green";
            $title_ar = "xxxxxxxxxxxxxxxxxxxx"; 
            $methodName = "mmmmmmmmmmmmmmmmmmmmmmm";
            //$pbms[self::hzmEncode($methodName)] = array("METHOD"=>$methodName,"COLOR"=>$color, "LABEL_AR"=>$title_ar, "ADMIN-ONLY"=>true, "BF-ID"=>"", 'STEP' =>$this->stepOfAttribute("xxyy"));
            
            
            
            return $pbms;
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

        public function list_of_study_program_type()
        {
            global $lang;
            return StudyProgram::studyProgramType()[$lang];
        }

        public function list_of_mainwork_stop()
        {
            global $lang;
            return StudyProgram::workStop()[$lang];
        }

        public static function intelligentCoranTitle($str)
        {
            $str = str_replace("10 أوجه","نصف جزء", $str);
            $str = str_replace("ونصف جزء","ونصف", $str);
            
            $str = str_replace("7 أسطر","نصف وجه", $str);
            $str = str_replace("ونصف وجه","ونصف", $str);

            $str = str_replace("واحد ونصف","ونصف", $str);
            

            return $str;
        }

        public function calcCondition()        
        {
            $new_nb_pages = $this->getVal("new_nb_pages");
            $new_nb_lines = $this->getVal("new_nb_lines");
            $total_nb_pages = $this->getVal("total_nb_pages");
            if(!$new_nb_pages) $new_nb_pages = 0;
            if(!$new_nb_lines) $new_nb_lines = 0;
            if(!$total_nb_pages) $total_nb_pages = 0;
            $result_arr = [];

            $total_nb_pages_net = $total_nb_pages % 20;
            $total_nb_parts = intval(($total_nb_pages -$total_nb_pages_net)/20);
            
            $total_arr = [];
            if($total_nb_parts>0) $total_arr[] = AfwStringHelper::intelligentArabicPlural("جزء", "أجزاء", $total_nb_parts);
            if($total_nb_pages_net>0) $total_arr[] = AfwStringHelper::intelligentArabicPlural("وجه", "أوجه", $total_nb_pages_net);
            if(count($total_arr)>0)
            {
                $result_arr[] =  "محفوظه ".implode(" و",$total_arr);
            }

           
            $new_arr = [];
            if($new_nb_pages>0) $new_arr[] = AfwStringHelper::intelligentArabicPlural("وجه", "أوجه", $new_nb_pages);
            if($new_nb_lines>0) $new_arr[] = AfwStringHelper::intelligentArabicPlural("سطر", "أسطر", $new_nb_lines);

            if(count($new_arr)>0)
            {
                $result_arr[] = "الحفظ الجديد : ".implode(" و",$new_arr);
            }
            
            return self::intelligentCoranTitle(implode(" و",$result_arr));
        }

        public function calcMainwork($what="value")
        {
            return $this->calcWork("mainwork");
        }

        public function calcHomework($what="value")
        {
            return $this->calcWork("homework");
        }

        public function calcHomework2($what="value")
        {
            return $this->calcWork("homework2");
        }

        public function calcWork($attribute)
        {
            
            $result_arr = [];
            $homework_nb_parts = $this->getVal($attribute."_nb_parts");
            $homework_nb_pages = $this->getVal($attribute."_nb_pages");
            $homework_nb_lines = $this->getVal($attribute."_nb_lines");

            // if($attribute=="homework") die("calcWork($attribute) => $homework_nb_parts p/ $homework_nb_pages pg / $homework_nb_lines ln");

            if($homework_nb_parts>0) $result_arr[] = AfwStringHelper::intelligentArabicPlural("جزء", "أجزاء", $homework_nb_parts);
            if($homework_nb_pages>0) $result_arr[] = AfwStringHelper::intelligentArabicPlural("وجه", "أوجه", $homework_nb_pages);
            if($homework_nb_lines>0) $result_arr[] = AfwStringHelper::intelligentArabicPlural("سطر", "أسطر", $homework_nb_lines);

            //if($attribute=="homework") throw new AfwRuntimeException("calcWork($attribute) => result_arr = ".var_export($result_arr,true));

            return self::intelligentCoranTitle(implode(" و", $result_arr));
        }

        public function stepsAreOrdered()
        {
                return false;
        }

        public function calcDcategory()        
        {
            // $new_nb_pages = $this->getVal("new_nb_pages");
            // $new_nb_lines = $this->getVal("new_nb_lines");
            $total_nb_pages = $this->getVal("total_nb_pages");            

            if($total_nb_pages>0) return 'mahfood';
            else return 'jadid';
        }

        public function rowCategoryAttribute()
        {
            return 'dcategory:formula';
        }

        

        
        
        
}
?>