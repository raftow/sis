<?php
// ------------------------------------------------------------------------------------
// alter table c0sis.student add parent_customer_id bigint after email;
// alter table c0sis.student add mother_customer_id bigint after parent_customer_id;
// alter table c0sis.student drop column father_rea_user_id;
// alter table c0sis.student drop column mother_rea_user_id;
// alter table c0sis.student drop column resp1_rea_user_id;
// alter table c0sis.student drop column resp2_rea_user_id;
// alter table c0sis.student add parent_mobile varchar(20) after parent_customer_id;
// alter table c0sis.student add parent_idn_type_id smallint after parent_mobile;
// alter table c0sis.student add parent_idn varchar(20) after parent_idn_type_id;

// alter table c0sis.student add mother_mobile varchar(20) after mother_customer_id;
// alter table c0sis.student add mother_idn_type_id smallint after mother_mobile;
// alter table c0sis.student add mother_idn varchar(20) after mother_idn_type_id;

/* ALTER TABLE `student` CHANGE `genre_id` `genre_id` INT(11) NOT NULL DEFAULT '0', 
                       CHANGE `country_id` `country_id` INT(11) NOT NULL DEFAULT '0', 
                       CHANGE `current_level_class_id` `current_level_class_id` INT(11) NOT NULL DEFAULT '0', 
                       CHANGE `city_id` `city_id` INT(11) NOT NULL DEFAULT '0'; */
// ALTER TABLE `student` CHANGE `birth_date` `birth_date` VARCHAR(8) NULL;

// 06/05/2024:
// alter table c0sis.student add student_status_id smallint default 0;
// alter table c0sis.student add course_program_id int(11) default 0;
// alter table c0sis.student add levels_template_id smallint default 0, add school_level_order smallint default 0, add level_class_order smallint default 0;

// use PhpOffice\PhpSpreadsheet\RichText\Run;

$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Student extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "student"; 
    public static $DB_STRUCTURE = null;  
    
    
    public function __construct(){
		parent::__construct("student","id","sis");
        SisStudentAfwStructure::initInstance($this);
	}
        
        public static function loadById($id)
        {
           $obj = new Student();
           
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }
        
        public static function hashNumeric($idn, $len=12)
        {
            $idn_str = hash('sha256', $idn);
            $idn_new = "";
            $idn_cnt = 0;
            for($c=0;$c<strlen($idn_str);$c++)
            {
                $ch = substr($idn_str,$c,1);
                if(is_numeric($ch))
                {
                    $idn_new .= $ch;
                    $idn_cnt++;
                    if(strlen($idn_new)>=$len) return $idn_new;
                }
            }

            return $idn_new;
        }
        
        public static function loadByMainIndex($idn_type_id, $idn,$create_obj_if_not_found=false)
        {
           $obj = new Student();
           if(!$idn_type_id) list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($idn); 
           if(!$idn_type_id)  throw new AfwRuntimeException("Student :: loadByMainIndex : idn_type_id is mandatory field");
           
           if(is_numeric($idn)) $the_id = $idn;
           else $the_id = self::hashNumeric($idn);

           $obj->select("id",$the_id);
           //$obj->select("idn",$idn);
           //$obj->select("idn_type_id",$idn_type_id);

           if($obj->load())
           {

            if($obj->getVal("idn") == $idn)
            {
                if($create_obj_if_not_found)
                {
                    $obj->set("idn_type_id",$idn_type_id);
                    $obj->activate();
                } 
                return $obj;
            }
            else return null;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("idn_type_id",$idn_type_id);
                $obj->set("idn",$idn);
                $obj->set("id",$the_id);

                $obj->insertNew();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }
        
        
        public static function loadByLoginInfos($student_mobile, $idn_type_id, $student_idn, $create_obj_if_not_found=false)
        {
           $obj = new Student();
           
           $obj->select("idn",$student_idn);
           $obj->select("mobile",$student_mobile);
           if($obj->load())
           {
                if($create_obj_if_not_found) 
                {
                        $obj->set("idn_type_id",$idn_type_id);
                        $obj->activate();
                }
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("idn_type_id",$idn_type_id);
                $obj->set("idn",$student_idn);

                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }
        
        public static function loadByMinInfos($student_mobile, $idn_type_id, $student_idn, $gender_id, $student_email, $student_school_id, $student_num, $student_first_name, $student_father_name, $student_last_name, $create_obj_if_not_found=false)
        {
           $obj = new Student();
           $obj->select("idn",$student_idn);
           $obj->select("mobile",$student_mobile);
           

           if($obj->load())
           {
                if($create_obj_if_not_found) 
                {
                        $obj->set("idn_type_id",$idn_type_id);
                        $obj->set("genre_id",$gender_id);
                        $obj->set("email",$student_email);
                        $obj->set("school_id",$student_school_id);
                        $obj->set("student_num",$student_num);
                        $obj->set("firstname",$student_first_name);
                        $obj->set("f_firstname",$student_father_name);
                        $obj->set("lastname",$student_last_name);
                        $obj->activate();
                }
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("idn_type_id",$idn_type_id);
                $obj->set("idn",$student_idn);
                $obj->set("mobile",$student_mobile);
                $obj->set("genre_id",$gender_id);
                $obj->set("email",$student_email);
                $obj->set("school_id",$student_school_id);
                $obj->set("student_num",$student_num);
                $obj->set("firstname",$student_first_name);
                $obj->set("f_firstname",$student_father_name);
                $obj->set("lastname",$student_last_name);

                $obj->insert();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }

        
        public function getDisplay($lang="ar")
        {
                return $this->myFullName($lang);   
        }


        public function myFullName($lang="ar")
        {
                $fn = ""; // trim($this->valPrefixe());
                $fn = trim($fn." " . $this->valFirstname());
                $fn = trim($fn." " . $this->valF_firstname());
                $fn = trim($fn." " . $this->valLastname());
                //$fn = trim($fn." (" . $this->valAge()." سنوات)");
                return $fn;
        }
        
        public function getCurrentFile() 
        {
            $reg_date = $this->getVal("reg_date");
            list($reg_year, $reg_mm, $reg_dd) = explode("-",$reg_date);

            // below 2, 2 means only for deplomed programs because for others (ex trainings) we can have many current files for the same student
            return StudentFile::loadByMainIndex($this->id, $this->getVal("school_id"), $reg_year, 2, 2);
        }
        
        
        public function getFormuleResult($attribute, $what='value') 
        {
               global $lang, $file_dir_name;    
               
               include_once("$file_dir_name/../afw/common_date.php");
               
	            switch($attribute) 
                {
                    case "nomcomplet" :
                        return $this->myFullName($lang);
                    break;

                    case "current_file" :
                        return $this->getCurrentFile();
                    break;

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
                            $age = 0;
                        }
                        
                        return $age; 
                    break;
                }
        }
        
        protected function importRecord($dataRecord,$orgunit_id,$overwrite_data,$options,$lang, $dont_check_error)                                     
        {
            $errors = [];
            
            foreach($dataRecord as $key => $val) $$key = $val;
            if(!$student_idn)
            {
                $errors[] = $this->translateMessage("missed idn value",$lang);
                return array(null,$errors,[],[]);
            }
            // idn and idn type identification
            $student_idn_type_id = 0;
            $student_idn_type_ok = false;
            if($student_idn_type) list($student_idn_type_ok, $student_idn_type_id) = AfwStringHelper::parseAttribute($this,"idn_type_id",$student_idn_type,$lang,false);
            if(!$student_idn_type_ok)
            {
                    // find it from idn format
                    list($idn_correct, $student_idn_type_id) = AfwFormatHelper::getIdnTypeId($student_idn);
            }
            
            if($idn_correct)
            {
                    //lookup for the student may be it exists
                    $student = self::loadByMainIndex($student_idn_type_id, $student_idn, $create_obj_if_not_found=true);

                    // mise a jour de $student si new or $overwrite_data
                    if($overwrite_data or $student->is_new)
                    {
                            
                            if($student_genre) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"genre_id",$student_genre,$lang); else $val_ok = true;
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_nationality) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"country_id",$student_nationality,$lang); else $val_ok = true;
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_firstname) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"firstname",$student_firstname,$lang); else $val_ok = true;
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_fatherfirstname) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"f_firstname",$student_fatherfirstname,$lang); else $val_ok = true;
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_lastname) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"lastname",$student_lastname,$lang); else $val_ok = true; 
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_birthdate) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"nasrani_birth_date",$student_birthdate,$lang); else $val_ok = true; 
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            if($student_hijri_birthdate) list($val_ok, $val_parsed_or_error) = AfwStringHelper::parseAttribute($student,"birth_date",$student_hijri_birthdate,$lang); else $val_ok = true; 
                            if(!$val_ok) $errors[] = $val_parsed_or_error;
                            
                            if(count($errors)==0)
                            {
                                $errors = $student->getDataErrors($lang);
                                //throw new AfwRuntimeException("student->getDataErrors = ".var_export($errors,true));
                            }                             
                            if(count($errors)==0)
                            {
                                $student->commit();
                            } 
                            
                    }
                    else
                    {
                            $errors[] = $this->translateMessage("This student already exists and overwrite is not allowed",$lang);
                    }
                    return array($student,$errors,[],[]);
            }
            else
            {
                    $errors[] = $this->translateMessage("incorrect idn format",$lang) . " : " . $student_idn;
                    return array(null,$errors,[],[]);
            } 
            
            
            
        }
        
        protected function namingImportRecord($dataRecord,$lang)
        {
            return $dataRecord["student_firstname"]. " " . $dataRecord["student_fatherfirstname"]. " " . $dataRecord["student_lastname"];
        }
      
      protected function getRelatedClassesForImport($options=null)
      {
          $file_dir_name = dirname(__FILE__);     
          
          include("$file_dir_name/module_tables_info.php");
          include("$file_dir_name/../rea/module_tables_info.php");
          include("$file_dir_name/../sis/module_tables_info.php");
               
          $importClassesList = [];
          
          $importClassesList["ParentUser"] = ['table_id'=>$TABLES_INFO["rea"]["parent_user"],'file'=>"$file_dir_name/../rea/parent_user.php"];
          $importClassesList["FamilyRelation"] = ['table_id'=>$TABLES_INFO["rea"]["family_relation"],'file'=>"$file_dir_name/../rea/family_relation.php"];
          if($options[1])
          {
             $importClassesList["StudentFile"] = ['table_id'=>$TABLES_INFO["sis"]["student_file"],'file'=>"$file_dir_name/../sis/student_file.php"];
          }
          return $importClassesList;
      }
      
      public function searchDefaultValue($attribute)
      {
          global $objme;
      
              if($attribute=="genre_id") 
              {
                  if($objme) return $objme->getVal("genre_id");
                  else return 1;
              } 
              return null;
      }
      
      public function userCanDoOperationOnMe($auser, $operation, $operation_sql)
      {
              $user_student_id = (5000000 + $this->getId());
              if(($operation=="display") and (($auser->getId() == $user_student_id) or (!$auser->getId()))) return true;
              return $this->userCanDoOperationOnMeStandard($auser, $operation, $operation_sql);
      }
      
      public function canBeSpeciallyDisplayedBy($auser)
      {
                if($auser and ($auser->getId()==$this->getOwnerId()))
                {
                     return true;
                }
                
               return false;
      }
      
      
        protected function userCanEditMeWithoutRole($auser)
        {
                $reason = "";
                
                if($this->getId()==0) 
                {
                    list($conv, $not_conv_reason) = $this->connectedUserConvenientForAction("edit", $auser);
                    if($conv) return array(true, "");
                    else $reason .= "$not_conv_reason ,"; 
                }
                
                
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
                    $pt2_batch_path = AfwSession::config("pt2_batch_path","");
                    if($pt2_batch_path)
                    {
                        $pbms["xHaa58"] = array("METHOD"=>"getDataFromTadreebInfo", 
                            "LABEL_AR"=>"اعادة استيراد البيانات من المنصة القديمة", 
                            "LABEL_EN"=>"get Data From Tadreeb-Info",
                            "ADMIN"=>true,
                            ); //
                    }

                    $pbms["A57BN0"] = array("METHOD"=>"generateStudentBooks", 
                        "LABEL_AR"=>"تحديث سجلات الانجازات", 
                        "LABEL_EN"=>"generate My Books",
                        "BF-ID"=>"" 
                    ); // 
                                                                 
            }
            
            return $pbms;  
        }

        public function generateStudentBooks($lang="ar", $mainBookList=null)
        {
            $nbSB_new = 0;
            $nbSB_old = 0;

            

            $my_id = $this->getId();
            $studentBookList = $this->get("studentBookList");
            if(!$mainBookList) $mainBookList = AfwSession::config("mainBookList", [1=>"مصحف القرآن الكريم برواية حفص"]);
            foreach($mainBookList as $mainBookId => $mainBookTitle)
            {
                if(!$studentBookList["$my_id-$mainBookId"])
                {
                    // die("studentBookList[$my_id-$mainBookId] not found in studentBookList = ".var_export($studentBookList,true));
                    // die("trying StudentBook::loadByMainIndex($my_id,$mainBookId,true) ... ");
                    $objSB = StudentBook::loadByMainIndex($my_id,$mainBookId,true);                    
                    if($objSB->is_new) $nbSB_new++;
                    else $nbSB_old++;
                    $studentBookList["$my_id-$mainBookId"] = $objSB;
                    // die("tryed StudentBook::loadByMainIndex($my_id,$mainBookId,true) => ".var_export($objSB, true));
                }                    
            }

            return ["", "تم توليد $nbSB_new سجل انجاز جديد", "", $studentBookList];
        }


        public function getDataFromTadreebInfo($lang="ar", $commit=true)
        {
            $idn = $this->getVal("idn");            
            if(!$idn) return ["student without IDN defined",""];
            $partition = substr($idn, 9, 1);
            $pt2_batch_path = AfwSession::config("pt2_batch_path","");
            if(!$pt2_batch_path) return ["old system import btach file path not defined",""];
            $delta_cond = "s.iqama = '$idn'";
            include("$pt2_batch_path/pt2_config_student.php");
            if(!$migration_config_arr) return ["$pt2_batch_path/pt2_config_student.php should contain migration_config_arr var",""];
            if(!AfwStringHelper::stringContain($migration_config_arr["dip_student"]["sql_data_from"],$idn)) return ["$pt2_batch_path/pt2_config_student.php migration_config_arr[dip_student][sql_data_from] should contain \$delta_cond = $delta_cond here",""];
            //$migration_config_arr["dip_student"]["sql_data_from"] .= " and s.iqama = '$idn'";
            $recap_data = array();
            $log = "";
            foreach($migration_config_arr as $table_key => $migration_config)
            {
                $res = AfwDataMigrator::migrateData($migration_config, $partition, "", $lang, true);
                $res["jobname"] = "student $idn";
                if($res["log"]) $log .= "\n". $res["log"];
                $recap_data[] = $res;
            }
            $recap_header = array('jobname'=>55, 'all_count'=>15, 'created_count'=>15, 'updated_count'=>15, 'skipped_count'=>15, );
            $html_info = AfwBatch::html_data($recap_header,$recap_data, []);

            // $this->updateLastStudentFile();

            return ["", $html_info."log <br> $log"];
        }

        public function fixMyData($lang="ar", $commit=true)
        {
            $err = "";
            $info = "";
            $warn = "";
            $tech = "";
            /*
            if(!$this->getVal("firstname") and $this->getVal("lastname"))
            {
                $this->decodeName($this->getVal("lastname"));
                $info .= " تم تصحيح الاسم،";
            }

            if(AfwStringHelper::isNameOfAllah($this->getVal("f_firstname")))
            {
                // استغفر الله                
                $nameAllah = $this->getVal("f_firstname");
                $this->setForce("f_firstname","");
            }
            else
            {
                $nameAllah = "الله";
            }
            
            $cnt = 0;
               
            if($this->getVal("firstname"))
            {
                $fnm0 = trim($this->getVal("firstname"));
                $fnm = $fnm0;
                while(AfwStringHelper::stringEndsWith($fnm,"الله") and ($cnt<20))
                {
                    $warn .= "قبل $cnt : $fnm ";
                    $fnm = trim(ltrim($fnm,"الله"));                    
                    $warn .= "بعد $cnt : $fnm ";
                    $cnt++;
                    
                }
                if($fnm != $fnm0)
                {
                    $this->set("firstname", $fnm." "."الله"); 
                }

                $fnm0 = $fnm;
                while(AfwStringHelper::stringEndsWith($fnm,"الرحمن") and ($cnt<20))
                {
                    $warn .= "قبل $cnt : $fnm ";
                    $fnm = trim(ltrim($fnm,"الرحمن"));
                    $warn .= "بعد $cnt : $fnm ";
                    $cnt++;
                }
                if($fnm != $fnm0)
                {
                    $this->set("firstname", $fnm." "."الرحمن"); 
                }
                
                $fnm0 = $fnm;
                while(AfwStringHelper::stringEndsWith($fnm,"الرحيم") and ($cnt<20))
                {
                    $warn .= "قبل $cnt : $fnm ";
                    $fnm = trim(ltrim($fnm,"الرحيم"));
                    $warn .= "بعد $cnt : $fnm ";
                    $cnt++;
                }
                if($fnm != $fnm0)
                {
                    $this->set("firstname", $fnm." "."الرحيم"); 
                }

                $fnm0 = $fnm;
                while(AfwStringHelper::stringEndsWith($fnm,$nameAllah) and ($cnt<20))
                {
                    $warn .= "قبل $cnt : $fnm ";
                    $fnm = trim(ltrim($fnm,$nameAllah));
                    $warn .= "بعد $cnt : $fnm ";
                    $cnt++;
                }
                if($fnm != $fnm0)
                {
                    $this->set("firstname", $fnm." ".$nameAllah); 
                }
                
                
            }*/
            
            


            if(!$this->getVal("idn_type_id") and $this->getVal("idn"))
            {
                list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($this->getVal("idn")); 
                if($idn_correct and $idn_type_id) 
                {
                    $info .= " تم تحديد نوع الهوية،";
                    $this->set("idn_type_id",$idn_type_id);
                }
            }

            if(!$this->getVal("parent_idn_type_id") and $this->getVal("parent_idn"))
            {
                list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($this->getVal("parent_idn")); 
                if($idn_correct and $idn_type_id) 
                {
                    $info .= " تم تحديد نوع هوية الولي، ";
                    $this->set("parent_idn_type_id",$idn_type_id);
                }
            }

            if(!$this->getVal("mother_idn_type_id") and $this->getVal("mother_idn"))
            {
                list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($this->getVal("mother_idn")); 
                if($idn_correct and $idn_type_id) 
                {
                    $info .= " تم تحديد نوع هوية الأم ";
                    $this->set("mother_idn_type_id",$idn_type_id);
                }
            }

            if(!$this->getVal("parent_customer_id"))
            {
                $mobile = $this->getVal("parent_mobile");
                $idn = $this->getVal("parent_idn");
                //$idn_type_id = $this->getVal("parent_idn_type_id");
                
                if($mobile and $idn)
                {
                    $city_id = $this->getVal("city_id");
                    $first_name = $this->getVal("f_firstname");
                    $last_name = $this->getVal("lastname");
                    if(!$first_name)
                    {
                        $first_name = "والد المتدرب";
                        $last_name = $this->getShortDisplay("ar");
                    }
                    try
                    {
                        $objParent = CrmCustomer::createOrUpdateCustomer($mobile, $idn, $first_name, $last_name, $customer_gender_id=1, $city_id, $customer_type_id=6);
                        if($objParent) $this->set("parent_customer_id",$objParent->id);
                    }
                    catch(Exception $e)
                    {
                        $tech = $e->getMessage();
                        $err = $this->getShortDisplay("ar")." : الرجاء التثبت من البيانات المدخلة لولي الأمر";
                        AfwSession::pushError($err);
                    }
                    
                }
                    
            }                
            
            if(!$this->getVal("mother_customer_id"))
            {
                $mobile = $this->getVal("mother_mobile");
                $idn = $this->getVal("mother_idn");
                //$idn_type_id = $this->getVal("parent_idn_type_id");
                
                if($mobile and $idn)
                {
                    $city_id = $this->getVal("city_id");
                    $first_name = "والدة المتدرب";
                    $last_name = $this->getShortDisplay("ar");                        
                    try
                    {
                        $objParent = CrmCustomer::createOrUpdateCustomer($mobile, $idn, $first_name, $last_name, $customer_gender_id=2, $city_id, $customer_type_id=6);
                        if($objParent) $this->set("mother_customer_id",$objParent->id);
                    }
                    catch(Exception $e)
                    {
                        $tech = $e->getMessage();
                        $warn = $this->getShortDisplay("ar")." : الرجاء التثبت من البيانات المدخلة للأم";
                        AfwSession::pushError($warn);
                    }
                }                
            }


            $hdob = $this->getVal("birth_date");
            $gdob = $this->getVal("birth_date_en");
            if($hdob)
            {
                $gdob = AfwDateHelper::hijriToGreg($hdob);
                $this->set("birth_date_en", $gdob);
                $info .= " تم تحديد تاريخ الولادة بالميلادي،";
            }
            elseif($gdob and ($gdob != "0000-00-00"))
            {
                $hdob = AfwDateHelper::gregToHijri($gdob);
                $this->set("birth_date", $hdob);
                $info .= " تم تحديد تاريخ الولادة بالهجري،";
            }


                                

            if($commit) $this->commit();
            
            if((!$info) and (!$err) and (!$warn)) 
            {
                $info = "لا يوجد معلومات تحتاج لتصحيح. اذا لم يكن الأمر كذلك راجع مدير المنصة";
            }

            return array($err, $info, $warn, $tech); //
        }

        public function beforeMaj($id, $fields_updated) 
        {
            // if This is an insert 
            if(!$this->getVal("id"))
            {
                // check that the idn is not empty
                $idn = $this->getVal("idn");
                if(!$idn)
                {
                    AfwSession::pushError("رقم الهوية الزامي");
                    return false;
                }
                // check that the idn is not already used
                $tmp = new Student();
                if($tmp->load($idn))
                {
                    AfwSession::pushError("هذا الطالب موجود مسبقا : $tmp");
                    return false;
                }
                
                $this->set("id", $idn);
            }
            
            $this->fixMyData("ar",$commit=false);

               
            if($fields_updated["school_id"] or $fields_updated["levels_template_id"] or $fields_updated["school_level_order"] or $fields_updated["level_class_order"] or $fields_updated["reg_date"])
            {
                $this->updateLastStudentFile();
            }

            return true;
        }
      
        public function beforeDelete($id,$id_replace) 
        {
                
    
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
                    $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK part of me - not deletable 
    
    
                    $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK part of me - deletable 
                        // sis.student_file-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once student_file.php";
                                StudentFile::removeWhere("student_id='$id'");
                                // $this->execQuery("delete from ${server_db_prefix}sis.student_file where student_id = '$id' ");
    
                            } 
    
    
                        // sis.family_relation-التابع	student_id  أنا تفاصيل لها-OneToMany
                            /*
                            if(!$simul)
                            {
                                // require_once family_relation.php";
                                FamilyRelation::removeWhere("student_id='$id'");
                                // $this->execQuery("delete from ${server_db_prefix}sis.family_relation where student_id = '$id' ");
    
                            } 
                        
    
                        // sis.alert_user-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once alert_user.php";
                                AlertUser::removeWhere("student_id='$id'");
                                // $this->execQuery("delete from ${server_db_prefix}sis.alert_user where student_id = '$id' ");
    
                            } 
    
    
                        // sis.rservice_student-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once rservice_student.php";
                                RserviceStudent::removeWhere("student_id='$id'");
                                // $this->execQuery("delete from ${server_db_prefix}sis.rservice_student where student_id = '$id' ");
    
                            } 
                        */
    
    
    
                    // FK not part of me - replaceable 
                        // sis.alert-الطالب	student_id  حقل يفلتر به-ManyToOne
                        /*
                            if(!$simul)
                            {
                                // require_once alert.php";
                                Alert::updateWhere(array('student_id'=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.alert set student_id='$id_replace' where student_id='$id' ");
                            }
                        // sis.student_session-student_id	student_id  حقل يفلتر به-ManyToOne
                            if(!$simul)
                            {
                                // require_once student_session.php";
                                StudentSession::removeWhere("student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.student_session set student_id='$id_replace' where student_id='$id' ");
                            }
                        // talent.practice-المتدرب	student_id  حقل يفلتر به-ManyToOne
                            if(!$simul)
                            {
                                require_once "../talent/practice.php";
                                Practice::removeWhere("student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}talent.practice set student_id='$id_replace' where student_id='$id' ");
                            }
                        */  
    
    
                    // MFK
    
                }
                else
                {
                            $server_db_prefix = AfwSession::config("db_prefix","c0"); // FK on me 
                        // sis.student_file-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once student_file.php";
                                StudentFile::updateWhere(array('student_id'=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.student_file set student_id='$id_replace' where student_id='$id' ");
    
                            }
    
                        // sis.family_relation-التابع	student_id  أنا تفاصيل لها-OneToMany
                        /*
                            if(!$simul)
                            {
                                // require_once family_relation.php";
                                FamilyRelation::updateWhere(array(student_id=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.family_relation set student_id='$id_replace' where student_id='$id' ");
    
                            }
    
                        // sis.alert_user-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once alert_user.php";
                                AlertUser::updateWhere(array(student_id=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.alert_user set student_id='$id_replace' where student_id='$id' ");
    
                            }
    
                        // sis.rservice_student-الطالب	student_id  أنا تفاصيل لها-OneToMany
                            if(!$simul)
                            {
                                // require_once rservice_student.php";
                                RserviceStudent::updateWhere(array(student_id=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.rservice_student set student_id='$id_replace' where student_id='$id' ");
    
                            }
                            
                        // sis.alert-الطالب	student_id  حقل يفلتر به-ManyToOne
                            if(!$simul)
                            {
                                // require_once alert.php";
                                Alert::updateWhere(array('student_id'=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.alert set student_id='$id_replace' where student_id='$id' ");
                            }
                        // sis.student_session-student_id	student_id  حقل يفلتر به-ManyToOne
                            if(!$simul)
                            {
                                // require_once student_session.php";
                                StudentSession::updateWhere(array('student_id'=>$id_replace), "student_id='$id'");
                                // $this->execQuery("update ${server_db_prefix}sis.student_session set student_id='$id_replace' where student_id='$id' ");
                            }
                        */
    
    
                            // MFK
    
    
                } 
                return true;
                }    
        }

        public static function loadFromRow($row)
        {
            if(!$row["idn_type_id"])
            {
                list($idn_correct, $row["idn_type_id"]) = AfwFormatHelper::getIdnTypeId($row["idn"]);
                if(!$row["idn_type_id"]) return null;//$row["idn"]." is not a correct IDN";
            }            
            return self::loadByMainIndex($row["idn_type_id"], $row["idn"], $create_obj_if_not_found=true);
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

        public function decodeIDNType($string) 
        { 
            list($idn_correct, $idn_type_id) = AfwFormatHelper::getIdnTypeId($string);
            if($idn_correct)
            {
                $this->set("idn_type_id",$idn_type_id);
                return "idn '$string' has type = $idn_type_id";
            } 

            return "idn '$string' has unknown type";
        }

        

        public function decodeName($string) 
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
            $this->setForce("firstname",$first_name);
            $this->setForce("f_firstname",$father_name);
            $this->setForce("lastname",$last_name);

            return "name '$string' has been splitted ($first_name / $father_name / $last_name)";

        }
        
        public function decodeCountry($string) 
        {
            global $NAT_ARR;
            if(!$NAT_ARR) $NAT_ARR = array();
            $nat = trim($string);
            if(!$nat)  return  "Country/Nationality '$string' not valid";
            if($NAT_ARR[$nat])
            {
                $obj_id = $NAT_ARR[$nat];
                $string_arr = [];
                $string_arr[] = ' -- from cache -- ';
            }
            else
            {
                list($obj_id, $string_arr) = Country::getCountryIdFromName($nat);
                if(!$obj_id) return  "Country/Nationality '$string' not found, words checked : ".implode(",",$string_arr);
                if($obj_id) $NAT_ARR[$nat] = $obj_id;
            }
            
            $this->set("country_id",$obj_id);

            return "Country/Nationality '$string' has been found : ".$obj_id." , words checked : ".implode(",",$string_arr);
        }


        public function decodeQualification($string) 
        {
            // @todo
            return "todo";
        }

        public function decodeStatus($student_status) 
        {
            // |  1 | مستمر                       |
            if($student_status=="mostamer") return 1;
            // |  2 | منقطع                       |
            if($student_status=="monqt3") return 2;
            // |  3 | مفصول                       |
            // if($student_status=="mostamer") return 1;
            // |  4 | متخرج                       |
            if($student_status=="diplomated") return 4;
            // |  5 | متخرج معتمد                 |
            if($student_status=="dgraduated") return 5;
            if($student_status=="graduated") return 5;
            // |  7 | منسحب                       |
            if($student_status=="withdrawed") return 7;
            // |  8 | مقبول                       |
            if($student_status=="accepted") return 8;
            // |  9 | مطوي قيده                   |
            // if($student_status=="mostamer") return 1;

            


            
        }
        

        
        public function decodeSchool($row) 
        {
            global $schools_arr;
            $ref_num = trim($row["ref_num"]);
            $reg_date = trim($row["reg_date"]);
            if($reg_date>"15000101")
            {
                $reg_date = AfwDateHelper::gregToHijri($reg_date);
            }
            $student_num = trim($row["student_num"]);
            $student_status = trim($row["status"]);
            $student_status_id = $this->decodeStatus($student_status);
            $objSchool = null;

            if($ref_num) 
            {
                if($schools_arr[$ref_num]) $objSchool = $schools_arr[$ref_num];
                else
                {
                    $objSchool = School::loadByReference($ref_num);
                    $schools_arr[$ref_num] = $objSchool;
                }
            }
            if(!$objSchool) return  "School '$ref_num' not found";
            $school_gender = $objSchool->getVal("genre_id");
            $objSchool_id = $objSchool->id;            
            unset($objSchool);

            $old_reg_date = $this->getVal("reg_date"); 
            if($old_reg_date>"15000101") $old_reg_date = "13900101";
            $oldSchool = $this->het("school_id");
            $old_ref_num = $oldSchool->getVal("ref_num"); 


            $lookup_code = $row["lookup_code"];
            if(!$lookup_code)
            {
                return "Error mapping : program lookup code is null , row = ".var_export($row,true);
            }
            $school_level_id = $row["school_level_id"];

            if(!$school_level_id)
            {
                return "Error mapping : school level is null , row = ".var_export($row,true);
            }

            global $arr_school_levels, $currentProgramObj;

            if($currentProgramObj and $currentProgramObj->id and ($currentProgramObj->getVal("school_level_id") == $school_level_id) and ($currentProgramObj->getVal("lookup_code") == $lookup_code))
            {
                $cpObj = $currentProgramObj;
            }
            else
            {
                $cpObj = CpcCourseProgram::loadByMainIndex($school_level_id, $lookup_code);
                $currentProgramObj = $cpObj;
            }

            
            
            $school_level_order = 1;
            $level_class_order = 1;
            $levels_template_id = 0;
            if($cpObj)
            {
                $course_program_id = $cpObj->id;
                $levels_template_id = $cpObj->getVal("levels_template_id");
                $slObj = $arr_school_levels[$cpObj->getVal("school_level_id")];
                if(!$slObj) $slObj = $cpObj->het("school_level_id");
                if($slObj)
                {
                    $arr_school_levels[$cpObj->getVal("school_level_id")] = $slObj;
                    $school_level_order = $slObj->getVal("school_level_order");
                }
                $log_supp_arr[] = "warning : [$school_level_id, $lookup_code] => DECODED AS COURSE-PROGRAM-ID = $course_program_id =";
            }
            else
            {
                return "Error mapping : course_program_id is null  where school_level_id=$school_level_id, lookup_code=$lookup_code, all row = ".var_export($row,true);
                $course_program_id = 0;
            }

            if(($old_ref_num==$ref_num) and ($reg_date == $old_reg_date))
            {
                return "School-registarion ($ref_num, $reg_date) found and it is the same";
            }
            elseif($this->isEmpty() or ($reg_date > $old_reg_date) or (!$this->getVal("school_id")))
            {
                
                if($school_gender>0)
                {
                    $this->set("genre_id",$school_gender);    
                }
                $this->set("school_id",$objSchool_id);
                $this->set("reg_date",$reg_date);
                $this->set("student_num",$student_num);
                $this->set("student_status_id",$student_status_id);
                $this->set("levels_template_id",$levels_template_id);
                $this->set("school_level_order",$school_level_order);
                $this->set("level_class_order",$level_class_order);
                $this->set("course_program_id",$course_program_id);
                
                return "Current-School-registarion updated to ($ref_num, $reg_date)  with number $student_num and student_status_id=$student_status_id ($student_status) from row=".var_export($row,true);
            }
            else
            {
                return "School-registarion new($ref_num, $reg_date) ignored vs old($old_ref_num, $old_reg_date) current-school=[".$oldSchool->getWideDisplay("ar")."]";
            }

            
            

            
        }

        public function saveStudentFileIfNew($row, $advanced_log=false) 
        {
            return $this->saveStudentFile($row, $advanced_log, $do_not_update_existing=true);
        }

        public function saveStudentFile($row, $advanced_log=false, $do_not_update_existing=false) 
        {
            global $arr_school_levels, $currentProgramObj;
            $ref_num = $row["ref_num"];
            $ref_num = trim($ref_num);
            $objSchool = null;
            if($ref_num) $objSchool = School::loadByReference($ref_num);
            if((!$objSchool) or (!$objSchool->id)) return  "School '$ref_num' not found";
            if(!$this->id) return  "Student not defined";

            $reg_date_hijri = $row["reg_date"];
            if(!$reg_date_hijri) $reg_date_hijri = "13010101";
            $reg_date = AfwDateHelper::hijriToGreg($reg_date_hijri);
            // $reg_date = AfwDateHelper::repareGorbojGregDate($reg_date);
            // 
            $log_supp_arr = [];

            $best_exam_date = $row["best_exam_date"];
            $best_exam_score = $row["best_exam_score"];
            
            if(!$arr_school_levels) $arr_school_levels = [];
            if($reg_date)
            {
                list($reg_year,$reg_mm, $reg_dd) = explode("-",$reg_date);
                $lookup_code = $row["lookup_code"];
                $school_level_id = $row["school_level_id"];

                if(!$school_level_id)
                {
                    throw new AfwRuntimeException("Error mapping : school level is null , row = ".var_export($row,true));
                }

                if($currentProgramObj and $currentProgramObj->id and ($currentProgramObj->getVal("school_level_id") == $school_level_id) and ($currentProgramObj->getVal("lookup_code") == $lookup_code))
                {
                    $cpObj = $currentProgramObj;
                }
                else
                {
                    $cpObj = CpcCourseProgram::loadByMainIndex($school_level_id, $lookup_code);
                    $currentProgramObj = $cpObj;
                }

                
                
                $school_level_order = 1;
                $level_class_order = 1;
                $levels_template_id = 0;
                if($cpObj)
                {
                    $course_program_id = $cpObj->id;
                    $levels_template_id = $cpObj->getVal("levels_template_id");
                    $slObj = $arr_school_levels[$cpObj->getVal("school_level_id")];
                    if(!$slObj) $slObj = $cpObj->het("school_level_id");
                    if($slObj)
                    {
                        $arr_school_levels[$cpObj->getVal("school_level_id")] = $slObj;
                        $school_level_order = $slObj->getVal("school_level_order");
                    }
                    $log_supp_arr[] = "warning : [$school_level_id, $lookup_code] => DECODED AS COURSE-PROGRAM-ID = $course_program_id =";
                }
                else
                {
                    throw new AfwRuntimeException("Error mapping : course_program_id is null  where school_level_id=$school_level_id, lookup_code=$lookup_code, all row = ".var_export($row,true));
                    $course_program_id = 0;
                }
                $objSF = StudentFile::loadByMainIndex($this->id, $objSchool->id, $reg_year, $levels_template_id, $school_level_order, $level_class_order, $create_obj_if_not_found=true);
                if(!$objSF) 
                {
                    $log_supp_arr[] = "warning : StudentFile($this->id, $objSchool->id, $reg_year) insert failed";
                }
                elseif((!$objSF->is_new) and ($do_not_update_existing))
                {
                    $log_supp_arr[] = "warning : StudentFile($this->id, $objSchool->id, $reg_year) skipped because exists";
                }
                else
                {
                    
                    $student_num = $row["student_num"];
                    if($course_program_id)
                    {
                        $objSF->set("course_program_id", $course_program_id);
                        
                        
                        $duration = 730;
                        if($cpObj)
                        {
                            $duration = $cpObj->getVal("duration");
                        }
                        if($best_exam_score and ($best_exam_score>=60))
                        {
                            $student_file_status_id = 4;
                            $status_date = $best_exam_date;
                            $rate_score = $best_exam_score;
                        }
                        else
                        {
                            $oldest_regdate = AfwDateHelper::addXDaysToGregDate(-$duration-90);
                            
                            if($oldest_regdate<=$reg_date) $student_file_status_id = 1;
                            else $student_file_status_id = 2;
                            $status_date = $reg_date;
                            $rate_score = 0;
                        }

                        $objSF->set("student_file_status_id", $student_file_status_id);
                        $objSF->set("status_date", $status_date);                        
                        $objSF->set("rate_score", $rate_score);
                    }
                    $objSF->set("reg_date", $reg_date_hijri);
                    
                    $objSF->set("idn_type_id", $this->getVal("idn_type_id"));
                    $objSF->set("idn", $this->getVal("idn"));
                    $objSF->set("city_id", $objSchool->getVal("city_id"));
                    $objSF->set("genre_id", $this->getVal("genre_id"));
                    $objSF->set("firstname", $this->getVal("firstname"));
                    $objSF->set("f_firstname", $this->getVal("f_firstname"));
                    $objSF->set("lastname", $this->getVal("lastname"));
                    $objSF->set("mobile", $this->getVal("mobile"));
                    $objSF->set("country_id", $this->getVal("country_id"));
                    $objSF->set("birth_date", $this->getVal("birth_date"));
                    $objSF->set("birth_date_en", $this->getVal("birth_date_en"));
                    $objSF->set("parent_mobile", $this->getVal("parent_mobile"));
                    $objSF->set("parent_idn_type_id", $this->getVal("parent_idn_type_id"));
                    $objSF->set("parent_idn", $this->getVal("parent_idn"));
                    $objSF->set("mother_mobile", $this->getVal("mother_mobile"));
                    $objSF->set("mother_idn_type_id", $this->getVal("mother_idn_type_id"));
                    $objSF->set("mother_idn", $this->getVal("mother_idn"));
                    $objSF->set("address", $this->getVal("address"));
                    $objSF->set("cp", $this->getVal("cp"));
                    $objSF->set("quarter", $this->getVal("quarter"));
                    $objSF->set("email", $this->getVal("email"));
                    $objSF->set("student_num", $student_num);
                    
                    
                    list($query, $fields_updated) = AfwSqlHelper::getSQLUpdate($objSF, 1, 2, $objSF->id);
                    $log_sql = "query=$query, fields_updated=".var_export($fields_updated,true);
                    $objSF->commit();
                    if($advanced_log)
                    {
                        if($objSF->is_new) $log_supp_arr[] = "StudentFile($this->id, $objSchool->id, $reg_year) created with (status_id=$student_file_status_id,score=$best_exam_score,date=$status_date | program=$course_program_id,student_num=$student_num) => $log_sql";
                        else $log_supp_arr[] = "StudentFile($this->id, $objSchool->id, $reg_year) updated with (status_id=$student_file_status_id,score=$rate_score,date=$status_date | program=$course_program_id,student_num=$student_num) => $log_sql";
                    }
                }
            }
            else $log_supp_arr[] = "warning : for student [".$this->id."] and school [".$objSchool->id."] no registration date is defined";
            
            unset($objSF);
            unset($objSchool);
            $log_supp = implode("\n", $log_supp_arr);
            if($log_supp) return "School '$ref_num' has been found and treated WITH LOG : $log_supp";
            else return "School '$ref_num' has been found and treated successfully";
        }


        public function updateLastStudentFile() 
        {
            $reg_date_hijri = $this->getVal("reg_date");
            if(!$reg_date_hijri) return array(false, "no hijri registration date");
            $reg_date = AfwDateHelper::hijriToGreg($reg_date_hijri);
            list($reg_year,$reg_mm, $reg_dd) = explode("-",$reg_date);

            $objSF = StudentFile::loadByMainIndex($this->id, $this->getVal("school_id"), $reg_year, $this->getVal("levels_template_id"), $this->getVal("school_level_order"), $this->getVal("level_class_order"), $create_obj_if_not_found=true);
            $objSF->set("student_file_status_id", $this->getVal("student_status_id"));
            $objSF->set("student_num", $this->getVal("student_num"));
            $objSF->set("course_program_id", $this->getVal("student_num"));
            //$objSF->set("", $xxx);
            $objSF->commit();            
        }
        
        public function stepsAreOrdered()
        {
                return true;
        }      

        protected function considerEmpty()
        {
            if(!trim($this->getVal("firstname"))) return true;
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

            if(AfwSession::config("level_2_grouped",false))
            {
                $list_of_items[21] = "ثانوي";
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


        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")
        {
            global $me, $objme, $lang;
    
            $displ = $this->getDisplay();
            $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
            $my_id = $this->getId();
    
            if ($mode == "mode_cands") {
                unset($link);
                
                $link = array();
                $title = "إضافة ملف ترشح";
                $schoo_id = 2;
                $link["URL"] = "main.php?Main_Page=afw_mode_edit.php&cl=Scandidate&currmod=sis&id_origin=$my_id&class_origin=Student&module_origin=sis&sel_school_id=$schoo_id&sel_year=2023&sel_student_id=$my_id";
                $link["TITLE"] = $title;
                $link["UGROUPS"] = array();
                $otherLinksArray[] = $link;
            }

            
    
    
            
    
    
    
            return $otherLinksArray;
        }
        
        public function getFieldGroupInfos($fgroup)
        {
            if ($fgroup == 'files') {
                return ['name' => $fgroup, 'css' => 'pct_100'];
            }

            if ($fgroup == 'cands') {
                return ['name' => $fgroup, 'css' => 'pct_100'];
            }
            
            return ['name' => $fgroup, 'css' => 'pct_100'];
        }

        public function tryToLoadWithUniqueKeyForEditMode()
        {
            return false;
        }

        public function shouldBeCalculatedField($attribute) {
            if($attribute=="address") return true;
            if($attribute=="city_id") return true;
            if($attribute=="quarter") return true;
            if($attribute=="course_program_name_ar") return true;
            if($attribute=="program_type_id") return true;
            if($attribute=="duration") return true;
            if($attribute=="ref_num") return true;
            if($attribute=="school_name_ar") return true;
            if($attribute=="region_id") return true;
            return false;
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
                return AfwSession::config("student_birth_date_hijri",true);
            }

            // but keep that by default we should use standard HZM-UMS model
            return [true, ''];
        }
        */
        
        
}
?>
