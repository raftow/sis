<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table courses_config_template : courses_config_template - نماذجالمناهج والكتب للمواد الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CoursesConfigTemplate extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "courses_config_template"; 
    public static $DB_STRUCTURE = null; 
    
    public function __construct(){
		parent::__construct("courses_config_template","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "courses_config_template_name_ar";
                $this->ORDER_BY_FIELDS = "courses_config_template_name_ar";
                $this->editByStep = true;
                $this->editNbSteps = 2;                
                $this->public_display = true;
	}
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
           global $me, $objme, $lang;
           
             $my_disp = $this->getDisplay($lang);
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();

             
             if($mode=="mode_coursesConfigItemList")
             {
                      $levelsTemplateObj = $this->hetLevt();
                      
                        if($levelsTemplateObj)
                        {
                            $title = $my_disp. " : إدارة المناهج والكتب للمواد الدراسية العامة";
                            $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CoursesConfigItem&currmod=sis&id_origin=$my_id&class_origin=CoursesConfigTemplate&module_origin=sis&newo=15&limit=30";
                            $link["URL"] .= "&ids=all&fixmtit=$title&fixmdisable=1&fixm=courses_config_template_id=$my_id,level_class_id=0";
                            $link["URL"] .= "&sel_courses_config_template_id=$my_id&sel_level_class_id=0";
                            $link["TITLE"] = $title;
                            $link["UGROUPS"] = array();
                            $otherLinksArray[] = $link;

                            $schoolLevelObjs = $levelsTemplateObj->het("schoolLevels");
                            // die("schoolLevelObjs = ".var_export($schoolLevelObjs,true));
                            foreach($schoolLevelObjs as $schoolLevelObjId => $schoolLevelObj)
                            {
                               if($schoolLevelObj) $levelClassList = $schoolLevelObj->het("levelClassList");
                               else $levelClassList = array();
                               // die("levelClassList = ".var_export($levelClassList,true));
                               foreach($levelClassList as $lev => $levelClassObj)
                               {
                                           unset($link);
                                           $link = array();
                                           $mylevelClassDisp = $levelClassObj->getDisplay($lang);
                                           $title = $my_disp. " : إدارة المناهج والكتب لـ :  ". $mylevelClassDisp;
                                           
                                           $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CoursesConfigItem&currmod=sis&id_origin=$my_id&class_origin=CoursesConfigTemplate&module_origin=sis&newo=15&limit=30";
                                           $link["URL"] .= "&ids=all&fixmtit=$title&fixmdisable=1&fixm=courses_config_template_id=$my_id,level_class_id=$lev";
                                           $link["URL"] .= "&sel_courses_config_template_id=$my_id&sel_level_class_id=$lev";
                         
                                           $link["TITLE"] = $title;
                                           $link["UGROUPS"] = array();
                                           $otherLinksArray[] = $link;
                               }
                          }
                      }
                      
                      
                       /*
                       unset($link);
                       $link = array();
                       $title = "إدارة تفاصيلالمناهج والكتب للمواد الدراسية لـ :  ". $my_disp;
                       $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CoursesConfigItem&currmod=sis&&id_origin=$my_id&class_origin=CoursesConfigTemplate&module_origin=sis";
                       $link["URL"] .= "&newo=3&limit=30&ids=all&fixmtit=$title&fixmdisable=1&fixm=courses_config_template_id=$my_id&sel_courses_config_template_id=$my_id";
                       $link["TITLE"] = $title;
                       $link["UGROUPS"] = array();
                       $otherLinksArray[] = $link; */     

             }
             
             return $otherLinksArray;          
        }

        public function stepsAreOrdered()
        {
                return false;
        }

        public function beforeDelete($id,$id_replace) 
        {
            $server_db_prefix = AfwSession::config("db_prefix","default_db_");
            
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
                       // sis.courses_config_item-النموذجالمناهج والكتب للمواد الدراسية	courses_config_template_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            CoursesConfigItem::removeWhere("courses_config_template_id='$id'");
                            // $this->execQuery("delete from ${server_db_prefix}sis.courses_config_item where courses_config_template_id = '$id' ");
                            
                        } 
                        
                        

                   
                   // FK not part of me - replaceable 
                       // sis.school-نموذجالمناهج والكتب للمواد الدراسية	courses_config_template_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            School::updateWhere(array('courses_config_template_id'=>$id_replace), "courses_config_template_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set courses_config_template_id='$id_replace' where courses_config_template_id='$id' ");
                        }

                        
                   
                   // MFK

               }
               else
               {
                        // FK on me 
                       // sis.courses_config_item-النموذجالمناهج والكتب للمواد الدراسية	courses_config_template_id  نوع علاقة بين كيانين ← 1
                        if(!$simul)
                        {
                            CoursesConfigItem::updateWhere(array('courses_config_template_id'=>$id_replace), "courses_config_template_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.courses_config_item set courses_config_template_id='$id_replace' where courses_config_template_id='$id' ");
                            
                        }
                        
                       // sis.school-نموذجالمناهج والكتب للمواد الدراسية	courses_config_template_id  نوع علاقة بين كيانين ← 2
                        if(!$simul)
                        {
                            School::updateWhere(array('courses_config_template_id'=>$id_replace), "courses_config_template_id='$id'");
                            // $this->execQuery("update ${server_db_prefix}sis.school set courses_config_template_id='$id_replace' where courses_config_template_id='$id' ");
                        }

                        
                        // MFK

                   
               } 
               return true;
            }    
	}
        

}
?>