<?php
// rafik 21/1/2023 :
// ALTER TABLE `school_employee` CHANGE `sdepartment_id` `sdepartment_id` INT(11) NOT NULL DEFAULT '0', CHANGE `school_orgunit_id` `school_orgunit_id` INT(11) NOT NULL DEFAULT '0';

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolEmployee extends SisObject{

    // 328	شؤون الطلاب	goal-SIS-MAIN	10 - قائمة رئيسية	التعليمي	إدارة بيانات الدراسة من قبل القسم التعليمي		0	54	لم يتم تفعيل التثبت من الأخطاء		
    public static $sis_role_education = 328;

    // 376	شؤون الطلاب	goal-SCHOOL-DATA	10 - قائمة رئيسية	الوحدة التدريبية	إدارة البيانات الإدارية للوحدة التدريبية		0	48	لم يتم تفعيل التثبت من الأخطاء		
    public static $sis_role_administrative = 376;

    // 377	شؤون الطلاب	goal-PROGRAMS	10 - قائمة رئيسية	البرامج و المناهج	إدارة البرامج الدراسية والمناهج التعليمية		0	42	لم يتم تفعيل التثبت من الأخطاء		
    public static $sis_role_programs = 377;    

    // 378	شؤون الطلاب	goal-RIA-DATA	10 - قائمة رئيسية	إدارة التطبيق	إدارة البيانات العامة للنظام		0	40	لم يتم تفعيل التثبت من الأخطاء		
    public static $sis_role_lookups = 378;

    // 356	شؤون الطلاب	manual-admin	10 - قائمة رئيسية	إدارة التطبيق	إدارة البيانات العامة للنظام 
    public static $sis_role_admin = 356;

    // 381 goal-PROF	10 - قائمة رئيسية	المعلم تسهيل وظائف المعلم
    public static $sis_role_prof = 381;

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "school_employee"; 
    public static $DB_STRUCTURE = null;  
    
    
    public function __construct(){
		parent::__construct("school_employee","id","sis");
        SisSchoolEmployeeAfwStructure::initInstance($this);                
	}

    public static function loadByMainIndex($employee_id, $school_id,$create_obj_if_not_found=false)
    {


        $obj = new SchoolEmployee();
        $obj->select("employee_id",$employee_id);
        $obj->select("school_id",$school_id);

        if($obj->load())
        {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
        }
        elseif($create_obj_if_not_found)
        {
            $obj->set("employee_id",$employee_id);
            $obj->set("school_id",$school_id);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
        }
        else return null;
        
    }

    public static function getSchoolList($employee_id)
    {
        $esList = self::getEmployeeSchools($employee_id);

        $result = [];

        foreach($esList as $esItem)
        {
            $school_id = $esItem->getVal("school_id");
            if(!$result[$school_id])
            {
                $schoolObj = $esItem->hetSchool();
                if($schoolObj) $result[$school_id] = $schoolObj;
            }
            
        }
        return $result;
    }
    

    public static function getEmployeeSchools($employee_id)
    {
        $emp = new SchoolEmployee();
        if($employee_id != 1) $emp->select("employee_id",$employee_id);
        $emp->select("active","Y");

        return $emp->loadMany();
    }

    
        
        public function getDisplay($lang="ar")
        {
               if(!$this->getId()) return $this->translate("unknown",$lang);
               //list($data,$link) = $this->displayAttribute("school_id");
               //list($data2,$link2) = $this->displayAttribute("job_description");
               $fn = ""; // trim($this->valPrefixe());
               $fn = trim($fn." " . $this->valFirstname());
               $fn = trim($fn." " . $this->valF_firstname());
               $fn = trim($fn." " . $this->valLastname());
                        
               return $fn; //." - ".$data2;
        }

        public function getShortDisplay($lang="ar")
        {
               if(!$this->getId()) return $this->translate("new",$lang);
               //list($data,$link) = $this->displayAttribute("school_id");
               //list($data2,$link2) = $this->displayAttribute("job_description");
               $fn = ""; // trim($this->valPrefixe());
               $fn = trim($fn." " . $this->valFirstname());
               $fn = trim($fn." " . $this->valF_firstname());
               $fn = trim($fn." " . $this->valLastname());
                        
               return $fn; //." - ".$data2;
        }
        
        public function getContextDisplay($lang="ar", $module="")
        {
               return $this->getSchool()->getDisplay($lang);
        }


        public function calcHrm_ums($what="value")
        {
            $user = null;
            $mau = null;
            
            list($emp, $school) = $this->getHrmEmployeeAndSchool();
            if($emp)
            {
                $user = $emp->het("auser_id");
                if($user)
                {
                    $mau = $user->getMyModulesAndRoles(1044,false);
                }
            }

            $color = "nocolor";
            if(!$school) 
            {
                $ret = "NOS";
                $color = "red";
            }
            elseif(!$emp) 
            {
                $ret = "NOE";
                $color = "orange";
            }
            elseif(!$user) 
            {
                $ret = "NOU";
                $color = "orange";
            }
            elseif(!$mau) 
            {
                $ret = "NOA";
                $color = "yellow";
            }
            else
            {
                $ret = "ok";
                $color = "green";
            }

            return "<span class='ums hrm$color'>$ret</span>";
        }

        public function genereUserAndHrmEmployee($lang="ar", $return_employee=false)
        {            
            if($this->getVal("email") and AfwFormatHelper::isCorrectEmailAddress($this->getVal("email")))
            {
                global $the_last_update_sql;
                $emp = null;
                $school = $this->het("school_id");
                if(!$school) return $return_employee ? $emp : ["لم يتم التحديث",""];
                list($user_name,) = explode("@",$this->getVal("email"));
                
                $emp = Employee::loadByEmail($school->getVal("orgunit_id"), $this->getVal("email"), $create_obj_if_not_found=true);

                
                
                
                if(!$emp->getVal("gender_id")) $emp->set("gender_id",$this->getVal("gender_id"));
                $fields = "";
                if((!trim($emp->getVal("firstname"))) or (!trim($emp->getVal("lastname"))))
                {
                        $emp->set("firstname",$this->getVal("firstname"));
                        $emp->set("f_firstname",$this->getVal("f_firstname"));
                        $emp->set("g_f_firstname",$this->getVal("g_f_firstname"));
                        $emp->set("lastname",$this->getVal("lastname"));
                        $fields .= " firstname = ".$this->getVal("firstname");
                        $fields .= " f_firstname = ".$this->getVal("f_firstname");
                        $fields .= " g_f_firstname = ".$this->getVal("g_f_firstname");
                        $fields .= " lastname = ".$this->getVal("lastname");
                }

                if($this->getVal("birth_date")) $emp->set("birth_date",$this->getVal("birth_date"));
                if($this->getVal("country_id")) $emp->set("country_id",$this->getVal("country_id"));
                if($this->getVal("address")) $emp->set("address",$this->getVal("address"));
                if($this->getVal("city_id")) $emp->set("city_id",$this->getVal("city_id"));
                if($this->getVal("mobile")) $emp->set("mobile",$this->getVal("mobile"));
                if($this->getVal("phone")) $emp->set("phone",$this->getVal("phone"));
                if($emp->is_new) $emp->set("username",$user_name);
                if($this->getVal("job_description")) $emp->set("job",$this->getVal("job_description"));
                if($emp->is_new) $emp->set("jobrole_mfk",",165,");               
                $dept = $this->het("sdepartment_id");
                if($dept) $emp->set("id_sh_div",$dept->getVal("orgunit_id"));
                $emp->updateMyUserInformation();
                // list($query, $fields_updated, $report) = AfwSqlHelper::simulateUpdate($emp);
                // die("query=$query report=$report fields=$fields fields_updated=".var_export($fields_updated,true));
                $emp->commit();
                $this->set("employee_id",$emp->id);
                $this->commit();
                
                
                
                return $return_employee ? $emp : ["","تم تحديث بيانات الموارد البشرية والصلاحيات", "", $the_last_update_sql];
            }
            else return $return_employee ? null : ["can not genere user and employee with wrong email address",""];
               
        }

        public function resetUserPassword($lang="ar")
        {
            $school = $this->het("school_id");
            if(!$school) return ["ألا يوجد مدرسة لهذا الموظف !! ؟؟",""];
            $emp = Employee::loadByEmail($school->getVal("orgunit_id"), $this->getVal("email"));
            $user = $emp->het("auser_id");
            if(!$user) return ["ألا يوجد اسم مستخدم لهذا الموظف !! ؟؟",""];

            list($err, $info, $war, $pwd) = $user->resetPassword($lang);
            $war .= "كلمة المرور الجديدة : $pwd";
            return [$err, $info, $war];
        }

        protected function getPublicMethods()
        {
            $pbms = array();
            
            
            $pbms["gUaE34"] = array("METHOD"=>"genereUserAndHrmEmployee", 
                                        "LABEL_AR"=>"تحديث الحساب والصلاحيات", 
                                        "LABEL_EN"=>"update user account and employee properties",
                                        "BF-ID"=>"104503" 
                                        ); // 


            $pbms["gHx254"] = array("METHOD"=>"resetUserPassword", 
                                        "LABEL_AR"=>"تصفير كلمة المرور", 
                                        "LABEL_EN"=>"reset User Password",
                                        "COLOR"=>"red",
                                        'CONFIRMATION_NEEDED' => true,
                                        'CONFIRMATION_WARNING' => [
                                            'ar' => 'سيتم تصفير كلمة المرور وتوليدها من جديد',
                                            'en' => 'we will reset User Password and regenerate a new one',
                                        ],
                                        'CONFIRMATION_QUESTION' => [
                                            'ar' => 'هل أنت متأكد أنك ترغب في تنفيذ هذا الاجراء',
                                            'en' => 'Are you sure you want to perform this procedure',
                                        ],
                                        "BF-ID"=>"104503" 
                                        ); //                                         
                                        
            
            
            return $pbms;  
        }

        
        public function beforeMaj($id, $fields_updated) 
        {
               global $file_dir_name;
               
               //unset($this->tech_notes);
               $this->tech_notes[] = "start beforeMAJ";
               
               if($fields_updated["email"] or $fields_updated["school_job_mfk"])
               {
                    $emp = $this->genereUserAndHrmEmployee("ar", true); 

                    if($emp and (!$emp->is_new))
                    {
                            $this->set("job_description", $emp->getVal("job"));
                            $this->set("phone", $emp->getVal("phone"));
                            $this->set("mobile", $emp->getVal("mobile"));
                            $this->set("city_id", $emp->getVal("city_id"));
                            $this->set("address", $emp->getVal("address"));
                            $this->set("birth_date", $emp->getVal("birth_date"));
                            $this->set("country_id", $emp->getVal("country_id"));

                            $this->set("employee_id",$emp->id);
                            $this->set("auser_id",$emp->getVal("auser_id"));
                            if(!count($this->get("course_mfk")))
                            {
                                    $this->set("course_mfk",AfwSession::config("default_course_mfk",",1,"));
                            }
                            if(!count($this->get("wday_mfk")))
                            {
                                    $this->set("wday_mfk",AfwSession::config("default_wday_mfk",",1,2,3,4,5,6,7,"));
                            }
                    }
               }
               
               
               

               
               
               /*
               if($this->findInMfk("school_job_mfk",7,$mfk_empty_so_found=false))  // عنده وظيفة التدريس 
               {
                       // require_once sprof.php");
                       $prf = $this->getProfObj();
                            
                       if($prf) 
                       {
                            
                            $school_we = $school->valWE();
                            $we_arr = explode(",",trim($school_we,","));
                            $this->addRemoveInMfk("wday_mfk",array(), $we_arr);
                            $work_days_mfk = $this->getVal("wday_mfk");
                            // throw new AfwRuntimeException("school = $school, school_we = $school_we, we_arr=".var_export($we_arr,true)." --> work_days_mfk=".$work_days_mfk);
                            
                            // die("prof params 'sprof' found");
                            $prf->set("course_mfk",$this->getVal("course_mfk"));
                            $prf->set("wday_mfk",$this->getVal("wday_mfk"));
                            $prf->update();
                            // die("prof params 'sprof' updated");
                       }
                       else
                       {
                            $prf = new S prof();
                            $prf->set("course_mfk",$this->getVal("course_mfk"));
                            $prf->set("wday_mfk",$this->getVal("wday_mfk"));
                            $prf->insert($this->getId());
                       }
               }
               */
               return true;
        }
        /*
        public function getProfObj()
        {
               global $file_dir_name;
               
               $prf = new S prof();
               if(($this->getId()>0) and $prf->load($this->getId()))
               {
                   return $prf;
               }
               
               return null;
        }*/
        public function updateHrmEmployee($school=null, $force=false)
        {
            if(!$school) $school = $this->het("school_id");
            $emp = null;
            if($school and $school->getVal("orgunit_id") and $this->getVal("email"))  
            {
                $emp = Employee::loadByEmail($school->getVal("orgunit_id"), $this->getVal("email"), $create_obj_if_not_found=false);
            }

            if($emp and (!$this->getVal("employee_id") or $force))
            {
                $this->set("employee_id", $emp->id);
                $this->commit();
            }

            return $emp;
        }

        public function getHrmEmployeeAndSchool($update=false)
        {
            $school = $this->het("school_id");
            if($update) $emplEff = $this->updateHrmEmployee($school);
            if(!$emplEff) $emplEff = $this->het("employee_id");
            

            return [$emplEff, $school];
        }
        
        
        public function calcProfCalendarItemList($what="object",$school_year_id=0)
        {
              unset($this->tech_notes);
              //$this->tech_notes = array();
              if(!$school_year_id)
              {
                      if(!$this->getVal("school_id"))
                      {
                          // $this->tech_notes[] = "school not defined";
                          return [];
                      } 
                      $school = $this->het("school_id");
                      if(!$school)
                      {
                          // $this->tech_notes[] = "school not defined";
                          return [];
                      }
                      
                      $currSYear = $school->getCurrentSchoolYear();
                      if($currSYear) $school_year_id = $currSYear->getId();
              }
              
              if(!$school_year_id)
              {
                  // $this->tech_notes[] = "school year not defined";
                  return []; //"error"=>"no sdepartment defined for this employee");
              }
              
              $prof_id = $this->getId();
              $dep = null;
              if($this->getVal("sdepartment_id")>0)
              {
                   $dep = $this->get("sdepartment_id");
              }
              
              
              
              $wt = null;
              
              if($dep and $dep->getVal("week_template_id")>0)
              {
                   $wt = $dep->get("week_template_id");
              }
              else
              {
                $wt = $school->get("week_template_id");
              }
              
              if(!$wt) return array();//"error"=>"no week template defined for this sdepartment ".$dep->getDisplay());
              
              $dti1 = $wt->getVal("day1_template_id");
              $dti2 = $wt->getVal("day2_template_id");
              $dti3 = $wt->getVal("day3_template_id");
              $dti4 = $wt->getVal("day4_template_id");
              $dti5 = $wt->getVal("day5_template_id");
              $dti6 = $wt->getVal("day6_template_id");
              $dti7 = $wt->getVal("day7_template_id");
              
              if(($dti1==$dti2) and ($dti1==$dti3) and ($dti1==$dti4) and ($dti1==$dti5) and ($dti1==$dti6) and ($dti1==$dti7))
              {
                $dti=$dti1;
              }
              else return array();//"error"=>"the week template defined for this sdepartment ".$dep->getDisplay()." have different day templates, calendar retrieve for this case is not implemented");

              $db = $this->getDatabase();

              $sql = "select concat('$prof_id',dti.session_order) as id, dti.session_order, dti.session_start_time, dti.session_end_time, psi1.id as psi_1, psi2.id as psi_2, psi3.id as psi_3, psi4.id as psi_4, psi5.id as psi_5, psi6.id as psi_6, psi7.id as psi_7
from $db.day_template_item dti
     left outer join $db.prof_sched_item psi1 on psi1.school_year_id = $school_year_id
                                    and psi1.prof_id = $prof_id
                                    and psi1.wday_id = 1
                                    and psi1.session_order = dti.session_order 
     left outer join $db.prof_sched_item psi2 on psi2.school_year_id = $school_year_id
                                    and psi2.prof_id = $prof_id
                                    and psi2.wday_id = 2
                                    and psi2.session_order = dti.session_order
     left outer join $db.prof_sched_item psi3 on psi3.school_year_id = $school_year_id
                                    and psi3.prof_id = $prof_id
                                    and psi3.wday_id = 3
                                    and psi3.session_order = dti.session_order  
     left outer join $db.prof_sched_item psi4 on psi4.school_year_id = $school_year_id
                                    and psi4.prof_id = $prof_id
                                    and psi4.wday_id = 4
                                    and psi4.session_order = dti.session_order  
     left outer join $db.prof_sched_item psi5 on psi5.school_year_id = $school_year_id
                                    and psi5.prof_id = $prof_id
                                    and psi5.wday_id = 5
                                    and psi5.session_order = dti.session_order  
     left outer join $db.prof_sched_item psi6 on psi6.school_year_id = $school_year_id
                                    and psi6.prof_id = $prof_id
                                    and psi6.wday_id = 6
                                    and psi6.session_order = dti.session_order  
     left outer join $db.prof_sched_item psi7 on psi7.school_year_id = $school_year_id
                                    and psi7.prof_id = $prof_id
                                    and psi7.wday_id = 7
                                    and psi7.session_order = dti.session_order 
where dti.day_template_id = $dti";



            $pci_rows = $this::sqlRecupRows($sql);
            
                
              
            $pci_obj = new ProfCalendarItem();
            $pci_list = $pci_obj->loadMany("", "", true, $pci_rows); 
            
            return $pci_list;
        }
        
        
        public function calcSchoolClassCourseList($what="object", $school_year_id=0, $wday_id=0, $session_order=0)
        {
            $school_year_id_orig = $school_year_id;
              if(!$school_year_id)
              {
                      if(!$this->getVal("school_id")) return [];
                      $school = $this->get("school_id");
                      if(!$school) return [];
                      $currSYear = $school->getCurrentSchoolYear();
                      if(!$currSYear) return [];
                      $school_year_id = $currSYear->getId();                      
                      if(!$school_year_id) return [];
              }
              
              if(!is_numeric($school_year_id))
              {
                throw new AfwRuntimeException("::::calcSchoolClassCourseList($what, $school_year_id_orig, $wday_id, $session_order) => school_year_id=$school_year_id");
              }
              
              $prof_id = $this->getId();
              // global $file_dir_name;
              // require_once school_class_course.php");
              $obj = new SchoolClassCourse();
              $db = $this->getDatabase(); 
              $obj->where("id in (select distinct scc.id 
                                    from $db.school_class_course scc 
                                           inner join $db.course_sched_item csi 
                                                 on scc.school_year_id = csi.school_year_id 
                                                and scc.level_class_id = csi.level_class_id 
                                                and scc.class_name = csi.class_name 
                                                and scc.course_id = csi.course_id 
                                                and scc.prof_id = $prof_id 
                                                and scc.school_year_id = $school_year_id 
                                                and (csi.wday_id = $wday_id or $wday_id=0) 
                                                and (csi.session_order = $session_order or $session_order = 0))");
              
              $scc_list = $obj->loadMany();
              
              return $scc_list;
        }

        public function getListOfSchoolLevelClassOrder()
        {
            $result = [];
            $schoolClassCourseList = $this->get("schoolClassCourseList");
            foreach($schoolClassCourseList as $schoolClassCourseItem)
            {
                $school_level_order = $schoolClassCourseItem->calc("school_level_order");
                $level_class_order = $schoolClassCourseItem->calc("level_class_order");

                $result["$school_level_order-$level_class_order"] = [$school_level_order, $level_class_order];
            }

            return $result;
        }
        
        
        public function calcAttendanceList($what="object",$back_to_past=5, $nb_days=8)
        {
            $school_id = $this->getVal("school_id");
            $prof_id = $this->id;
            if(!$school_id) return array();
            if(!$prof_id) return array();

            $slco_arr = $this->getListOfSchoolLevelClassOrder();
            $slco_cond_arr = [];
            foreach($slco_arr as $slco_item)
            {
                list($school_level_order, $level_class_order)  = $slco_item;
                $slco_cond_arr[] = "(school_level_order=$school_level_order and level_class_order=$level_class_order)";
            }

            $slco_cond = implode(" and ", $slco_cond_arr);
            if(!$slco_cond) return array();
            
            
            
            $cur_date = date("Y-m-d");
            $min_date = AfwDateHelper::shiftGregDate($cur_date,-$back_to_past);
            $max_date = AfwDateHelper::shiftGregDate($min_date,$nb_days);
            

            $c_ss = new CourseSession();
            // $db = $this->getDatabase(); 
            $c_ss->where($slco_cond);
            $c_ss->where("school_id = $school_id and prof_id = $prof_id and session_date between '$min_date' and '$max_date'");
            
            $c_ssList = $c_ss->loadMany("","session_order");
            
            return $c_ssList;
        
        }
        
        
        
        
        public function showDayProgram($wd_id, $school_year_id=0)
        {
              $pci_list = $this->getProfCalendarItems($school_year_id);
              
              $program_txt = "";
              
              foreach($pci_list as $pci_id => $pci_item)
              {
                      $program_txt .= $pci_item->showDay($wd_id) . "\n";
              }
              // die($program_txt);
              return $program_txt;
              
              
        }
        
        public function attributeIsApplicable($attribute)
        {
            
                if(!$this->findInMfk("school_job_mfk",7,$mfk_empty_so_found=false))  // عنده وظيفة التدريس 
                {
                    if($attribute=="course_mfk") return false;
                    
                    /*
                    if($attribute=="wday_mfk") return false;
                    if($attribute=="schoolClassCourseList") return false;
                    if($attribute=="profCalendarItemList") return false;
                    if($attribute=="attendanceList") return false;
                    */
                }


                return true;
        }

        public function stepsAreOrdered()
        {
            return false;
        }
        
        public function beforeDelete($id, $id_replace) 
        {
            list($empl, $school) = $this->getHrmEmployeeAndSchool();
            if($empl) $empl->delete();
                
		    return true;
	    }

        protected function getReadOnlyFormFinishButtonLabel()
        {
            return 'FINISH';
        }

        protected function considerEmpty()
        {
            return (!$this->getVal("email") or !$this->getVal("employee_id"));
        }
        

        public function isFilled()
        {
            return !$this->isConsideredEmpty();
        }


        protected function myShortNameToAttributeName($attribute)
        {
            if($attribute=="school") return "school_id";
            return $attribute;
        }

        public function calcFull_name()
        {
                $fn = ""; 
                $fn = trim($fn." " . $this->valFirstname());
                $fn = trim($fn." " . $this->valF_firstname());
                $fn = trim($fn." " . $this->valLastname());
                
		        return $fn;
        }
             

}
