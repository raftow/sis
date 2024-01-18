<?php
               
$file_dir_name = dirname(__FILE__); 
                
/* 

if new deploms are added match with TV TC deploms for Individual Profile project database table training_major_LKP
by doing :
select *  from training_major_LKP where TRAINING_MAJOR_DESC_AR like '%XXXXXXXXX%';

update program_type.tv tc_major_code  and so :

drop table if exists major_matching;

create table major_matching as 
select distinct prg.lookup_code as deplom_id, prt.tv tc_major_code, prg.duration , prg.duration_desc , prg.h_duration , prg.accreditation_num 
    from cpc_course_program prg 
         inner join program_type prt on prt.id = prg.program_type_id 
where prg.school_level_id in (2,3);

alter table major_matching add primary key(deplom_id);


after dump it

mysqldump -h 127.0.0.1 -u root -p --databases c0sis --tables major_matching > major_matching.sql

add use tadreebi_deplom; line

use this file to erase the one in tadreebi_deplom PT DB Server
*/
class CpcCourseProgram extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "cpc_course_program"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct()
        {
		    parent::__construct("cpc_course_program","id","sis");
            SisCpcCourseProgramAfwStructure::initInstance($this);
                
            $tr = SisCpcCourseProgramTranslator::init($this);    
                
	    }

        public static function loadById($id)
        {
           $obj = new CpcCourseProgram();
           if(!$id) throw new AfwRuntimeException("loadById : id is mandatory parameter and is empty");
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }


        public static function loadByMainIndex($school_level_id, $lookup_code, $course_program_name_ar=null, $course_program_name_en=null, $levels_template_id=null, $create_obj_if_not_found=false)
        {
            $obj = new CpcCourseProgram();
            if(!$school_level_id) throw new AfwRuntimeException("loadByMainIndex : school_level_id is mandatory field");
            if(!$lookup_code) throw new AfwRuntimeException("loadByMainIndex : lookup_code is mandatory field");
            
            $obj->select("school_level_id",$school_level_id);
            $obj->select("lookup_code",$lookup_code);
    
            if($obj->load())
            {
                    if($create_obj_if_not_found)
                    {
                        if((!$course_program_name_ar) and (!$course_program_name_en)) throw new AfwRuntimeException("loadByMainIndex : course_program_name_ar and course_program_name_en one of them is mandatory");
                        if(!$levels_template_id) throw new AfwRuntimeException("loadByMainIndex : levels_template_id is mandatory field");
                        $obj->set("course_program_name_ar",$course_program_name_ar);
                        $obj->set("course_program_name_en",$course_program_name_en);
                        $obj->set("levels_template_id",$levels_template_id);
                        $obj->activate();
                
                    } 
                    return $obj;
            }
            elseif($create_obj_if_not_found)
            {
                    if((!$course_program_name_ar) and (!$course_program_name_en)) throw new AfwRuntimeException("loadByMainIndex : course_program_name_ar and course_program_name_en one of them is mandatory");
                    if(!$levels_template_id) throw new AfwRuntimeException("loadByMainIndex : levels_template_id is mandatory field");
                    $obj->set("school_level_id",$school_level_id);
                    $obj->set("lookup_code",$lookup_code);
                    $obj->set("course_program_name_ar",$course_program_name_ar);
                    $obj->set("course_program_name_en",$course_program_name_en);
                    $obj->set("levels_template_id",$levels_template_id);
    
                    $obj->insertNew();
                    $obj->is_new = true;
                    return $obj;
            }
            else return null;
 
        }

        

        public static function loadByNameAndTypeFromRow($row)
        {
            return self::loadByMainIndex(trim($row["school_level_id"]),trim($row["lookup_code"]),trim($row["course_program_name_ar"]),trim($row["course_program_name_en"]),trim($row["levels_template_id"]), $create_obj_if_not_found=true);
        }


        public static function loadFromRow($row)
        {
            $obj = self::loadById(trim($row["id"]));
            if(!$obj)
            {
                $obj = new CpcCourseProgram();
                $obj->set("id", trim($row["id"]));
                $obj->set("course_program_name_ar",trim($row["course_program_name_ar"]));
                $obj->set("course_program_name_en",trim($row["course_program_name_en"]));
                $obj->set("school_level_id",trim($row["school_level_id"]));
                $obj->insertNew();
                $obj->is_new = true;
            }
            else
            {
                $obj->activate(false);
            }
            return $obj;
        }
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             /*
             if($mode=="mode_cpcCoursePlanList")
             {
                   unset($link);
                   $my_id = $this->getId();
                   $link = array();
                   $title = "إدارة المحتوى الدراسي";
                   $title_detailed = $title;
                   $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcCoursePlan&currmod=sis&id_origin=$my_id&class_origin=CpcCourseProgram&module_origin=sis&newo=10&limit=30&ids=all&fixmtit=&fixmdisable=1&fixm=course_program_id=&sel_course_program_id=$my_id";
                   $link["TITLE"] = $title;
                   $link["UGROUPS"] = array();
                   $otherLinksArray[] = $link;
             }*/
             
             if($mode=="mode_cpcCourseProgramBookList" and false)
             {
                   unset($link);
                   $my_id = $this->getId();
                   $link = array();
                   $title = "إدارة الكتب الدراسية  ";
                   $title_detailed = $title." لبرنامج ".$this->getDisplay($lang);
                   $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcCourseProgramBook&currmod=sis&id_origin=$my_id&class_origin=CpcCourseProgram&module_origin=sis&newo=10&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=course_program_id=$my_id&sel_course_program_id=$my_id";
                   $link["TITLE"] = $title;
                   $link["UGROUPS"] = array();
                   $otherLinksArray[] = $link;
             }
             
             
             
             return $otherLinksArray;
        }

        
        protected function beforeSetAttribute($attribute, $newvalue)
        {
            if($this->id>0)
            {
                $oldvalue = $this->getVal($attribute);
                if(($attribute == "school_level_id") and (!is_numeric($newvalue)))
                {
                    throw new AfwRuntimeException("captured before set attribute $attribute from '$oldvalue' to '$newvalue'");
                }

                if(($attribute == "ccps_code"))
                {
                    throw new AfwRuntimeException("captured before set attribute $attribute from '$oldvalue' to '$newvalue'");
                }
            }
                
              
            return true;
        }


        public static function transformLevel($string) 
        { 
            $lvl = 0;
            $string = trim($string);
            if($string == "دبلوم") $lvl = 2;
            if($string == "برنامج تدريبي") $lvl = 3;
            // دورة تطويرية	development session	4
            if($string == "دورة تطويرية") $lvl = 4;
            // دورة تأهيلية	qualification course	5
            if($string == "دورة تأهيلية") $lvl = 5;
            // دبلوم مشارك
            if($string == "دبلوم مشارك") $lvl = 6;
            
            return array(($lvl!=0), $lvl);
        }

        public static function transformDevCourseLevel($string) 
        { 
            return array(true, 4);
        }

        public static function transformProgramLevel($string) 
        { 
            $lvl = 0;
            $string = trim($string);
            if($string == "برنامج تأهيلي") $lvl = 5; // هي نفسها دورة تأهيلية حيث الدورة التأهيلية تقع على برنامج تأهيلي  

            //if($string == "دبلوم") $lvl = 6;
            if($string == "برنامج تطويري") $lvl = 7;
            if($string == "تدريب إلكتروني") $lvl = 8;
            if($string == "برنامج تدريبي") $lvl = 9;
            

            return array(($lvl!=0), $lvl);
        }        


        public static function transformByExtractArabicName($string) 
        {
            list($arabic, $english, $transformed, $log) = AfwStringHelper::splitArabicFromLatinSentences($string);
            if((!$arabic) and $transformed) $arabic = $english;
            if(!$arabic) $arabic = $string;
            return array(true, $arabic);
        }


        public static function transformByExtractEnglishName($string) 
        {
            list($arabic, $english, $transformed, $log) = AfwStringHelper::splitArabicFromLatinSentences($string);
            return array($transformed, $english);
        }

        public static function decomposeSchoolLevelId($rowMapped, $row)
        {
            $school_level_id = $rowMapped["school_level_id"];
            if($school_level_id)
            {
                global $arr_school_levels;
                $slObj = $arr_school_levels[$school_level_id];
                if(!$slObj)
                {
                    $slObj = $arr_school_levels[$school_level_id] = SchoolLevel::loadById($school_level_id);
                }
            }
            
            $school_level_order = 1;
            $level_class_order = 1;
            $levels_template_id = 0;
            if($slObj)
            {
                $levels_template_id = $slObj->getVal("levels_template_id");
                $school_level_order = $slObj->getVal("school_level_order");
            }

            $rowMapped["levels_template_id"] = $levels_template_id;
            $rowMapped["school_level_order"] = $school_level_order;
            $rowMapped["level_class_order"] = $level_class_order;


            return $rowMapped;
        }


        public static function getFileCourseProgram($rowMapped, $row)
        {
            global $currentProgramObj;

            $lookup_code = $rowMapped["lookup_code"] = $row["lookup_code"];

            $school_level_id = $rowMapped["school_level_id"];

            

            if($currentProgramObj and $currentProgramObj->id and ($currentProgramObj->getVal("school_level_id") == $school_level_id) and ($currentProgramObj->getVal("lookup_code") == $lookup_code))
            {
                $cpObj = $currentProgramObj;
            }
            else
            {
                $cpObj = CpcCourseProgram::loadByMainIndex($school_level_id, $lookup_code);
                $currentProgramObj = $cpObj;
            }

            if($cpObj) $rowMapped["course_program_id"] = $cpObj->id;

            return $rowMapped;
        }


        public static function getDevCourseProgram($rowMapped, $row)
        {
            global $currentProgramObj;

            $lookup_code = $rowMapped["lookup_code"] = "TATWER-".$row["lookup_code"];

            $school_level_id = $rowMapped["school_level_id"];

            

            if($currentProgramObj and $currentProgramObj->id and ($currentProgramObj->getVal("school_level_id") == $school_level_id) and ($currentProgramObj->getVal("lookup_code") == $lookup_code))
            {
                $cpObj = $currentProgramObj;
            }
            else
            {
                $cpObj = CpcCourseProgram::loadByMainIndex($school_level_id, $lookup_code);
                $currentProgramObj = $cpObj;
            }

            if($cpObj) $rowMapped["course_program_id"] = $cpObj->id;

            return $rowMapped;
        }

        public static function getPROGRAM_CourseProgram($rowMapped, $row)
        {
            global $currentProgramObj;

            $lookup_code = $rowMapped["lookup_code"] = "PROGRAM-".$row["lookup_code"];

            $school_level_id = $rowMapped["school_level_id"];

            

            if($currentProgramObj and $currentProgramObj->id and ($currentProgramObj->getVal("school_level_id") == $school_level_id) and ($currentProgramObj->getVal("lookup_code") == $lookup_code))
            {
                $cpObj = $currentProgramObj;
            }
            else
            {
                $cpObj = CpcCourseProgram::loadByMainIndex($school_level_id, $lookup_code);
                $currentProgramObj = $cpObj;
            }

            if($cpObj) $rowMapped["course_program_id"] = $cpObj->id;

            return $rowMapped;
        }

        public static function concatDevCourse($rowMapped, $row)
        {
            $rowMapped["lookup_code"] = "TATWER-".$rowMapped["lookup_code"];
            return $rowMapped;
        }

        public static function concatProgramCourse($rowMapped, $row)
        {
            $rowMapped["lookup_code"] = "PROGRAM-".$rowMapped["lookup_code"];
            return $rowMapped;
        }


        public static function concatArId($rowMapped, $row)
        {
            if(!$rowMapped["course_program_name_ar"]) $rowMapped["course_program_name_ar"] = "برنامج";
            $rowMapped["course_program_name_ar"] = $rowMapped["course_program_name_ar"] . "-" . $rowMapped["lookup_code"];

            return $rowMapped;
        }

        public static function concatEnId($rowMapped, $row)
        {
            if(!$rowMapped["course_program_name_en"]) $rowMapped["course_program_name_en"] = "Program";
            $rowMapped["course_program_name_en"] = $rowMapped["course_program_name_en"] . "-" . $rowMapped["lookup_code"];

            return $rowMapped;
        }

        public function decodeSchool($row) 
        {
            $ref_num = $row["ref_num"];
            $ref_num = trim($ref_num);

            $ccps_code = trim($row["ccps_code"]);
            $program_sa_code = trim($row["program_sa_code"]);
            $level_sa_code = trim($row["level_sa_code"]);

            $duration = trim($row["duration"]);
            $duration_desc = trim($row["duration_desc"]);
            $h_duration = trim($row["h_duration"]);

            $objSchool = null;
            if($ref_num) $objSchool = School::loadByReference($ref_num);
            if(!$objSchool) return  "School '$ref_num' not found";

            if($this->id and $objSchool->id)
            {
                $objCCPS = CpcCourseProgramSchool::loadByMainIndex($this->id, $objSchool->id, $create_obj_if_not_found=true);
                if(!$objCCPS) 
                {
                    $log_supp = "\nwarning : CpcCourseProgramSchool($this->id, $objSchool->id) insert failed";
                }
                else
                {
                    $objCCPS->set("ccps_code", $ccps_code);
                    $objCCPS->set("duration", $duration);
                    $objCCPS->set("duration_desc", $duration_desc);
                    $objCCPS->set("h_duration", $h_duration);
                    $objCCPS->set("program_sa_code", $program_sa_code);
                    $objCCPS->set("level_sa_code", $level_sa_code);
                    $objCCPS->commit();
                    if($objCCPS->is_new) $log_supp = "and CpcCourseProgramSchool($this->id, $objSchool->id) created";
                    else $log_supp = "and CpcCourseProgramSchool($this->id, $objSchool->id) updated";
                }
            }
            else $log_supp = "";
            
            unset($objCCPS);
            unset($objSchool);
            

            return "School '$ref_num' has been found $log_supp";
        }
        
        public function decodeProgramDuration($string, $hPerDay=6)
        {
            return intval($string)*$hPerDay;
        }
        

        public function decodeDuration($string) 
        { 
            $dur = 0;

            $string = trim($string);

            if($string == "سنتان") $dur = 2*365;
            if($string == "سنة") $dur = 365;
            if($string == "ثلاث سنوات") $dur = 3*365;
            if($string == "سنتان ونصف") $dur = 2*365+182;
            if($string == "سنة ونصف") $dur = 365+182;
            if($string == "ستة عشر شهراً") $dur = 30*16;
            if($string == "سنتان وثلاثة أشهر") $dur = 30*25;
            if($string == "اربعة عشر شهراً") $dur = 30*14;
            if($string == "أحد عشر شهراً") $dur = 30*11;
            if($string == "عشرة اشهر") $dur = 30*10;
            if($string == "إحد عشر شهراً ونصف") $dur = 30*11+15;
            if($string == "خمسة عشر شهراً") $dur = 30*15;
            if($string == "سبعة أشهر ونصف") $dur = 30*7+15;
            if($string == "ثمانية أشهر") $dur = 30*8;
            if($string == "ثلاثة أشهر ونصف") $dur = 30*3+15;
            if($string == "ستة أشهر") $dur = 30*6;
            if($string == "تسعة أشهر") $dur = 30*9;
            if($string == "ثلاثة أشهر") $dur = 30*3;
            if($string == "5 أسابيع") $dur = 5*7;
            if($string == "أربعة أشهر") $dur = 30*4;
            

            
            $this->set("duration", $dur);
            return "program duration '$string' decoded to $dur";            
        }

        public function stepsAreOrdered()
        {
                return false;
        }

        public function maxRecordsUmsCheck()
        {
                return 0;
        }


        public function loadMeFromTadribInfo($lang="ar")
        {
            global $print_full_debugg;
            $print_full_debugg = true;
            $batch_root_path = "/var/www/hub_batch";
            include("$batch_root_path/pt2/pt2_config_program.php");

            unset($migration_config_arr["school"]);
            if($this->getVal("school_level_id")==4)
            {
                $keyConf = "dev_course";
                $condSupp = " and ID = 33549";
                $migration_config_arr["dev_course"]["sql_data_from"] .= $condSupp;
                $migration_config_arr["dev_course"]['startByReset'] .= " and id = $this->id";
            }
            else
            {
                $keyConf = "program_deplom";
            }

            $res = AfwDataMigrator::migrateData($migration_config_arr[$keyConf],0, "","ar",true);

            return array("",$res["log"]);
        }


        protected function getPublicMethods()
        {
            global $lang;

            $objme = AfwSession::getUserConnected();
            
            $pbms = array();
            if($objme) /* @todo */
            {    
                    
                    if($objme->isSuperAdmin() and false)
                    {
                        $color = "red";
                        $title_ar = "التحديث من تدريب انفو القديمة"; 
                        $pbms["xc013B"] = array("METHOD"=>"loadMeFromTadribInfo",
                            "COLOR"=>$color, "LABEL_AR"=>$title_ar, 
                            "PUBLIC"=>true, "BF-ID"=>"", "HZM-SIZE" =>12,
                            );
                    }


            }
        
            return $pbms;
            
            
        }

             
}
?>