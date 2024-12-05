<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_level : school_level - المستويات الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolLevel extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null;

        public function __construct(){
		parent::__construct("school_level","id","sis");
                SisSchoolLevelAfwStructure::initInstance($this);
                
	}

        public static function loadById($id)
        {
           $obj = new SchoolLevel();
           
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }

        public static function loadByMainIndex($levels_template_id,$school_level_order,$create_obj_if_not_found=false)
        {
                $obj = new SchoolLevel();
                if(!$levels_template_id) throw new AfwRuntimeException("loadByMainIndex : levels_template_id is mandatory field");
                if(!$school_level_order) throw new AfwRuntimeException("loadByMainIndex : school_level_order is mandatory field");
                $obj->select("levels_template_id",$levels_template_id);
                $obj->select("school_level_order",$school_level_order); 
                if($obj->load())
                {
                        if($create_obj_if_not_found) $obj->activate();
                        return $obj;
                }
                elseif($create_obj_if_not_found)
                {
                        $obj->set("levels_template_id",$levels_template_id);
                        $obj->set("school_level_order",$school_level_order); 
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                }
                else return null;
 
        }
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
           global $me, $objme, $lang;
           
             $displ = $this->getDisplay($lang);
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();

             if($my_id and ($mode=="mode_levelClassList"))
             {
                  
                       unset($link);
                       $link = array();
                       $title = "إدارة الفروع للـمستوى :  ". $displ;
                       $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=LevelClass&currmod=sis&&id_origin=$my_id&class_origin=SchoolLevel&module_origin=sis";
                       $link["URL"] .= "&newo=10&limit=30&ids=all&fixmtit=$title&fixmdisable=1&fixm=school_level_id=$my_id&sel_school_level_id=$my_id";
                       $link["TITLE"] = $title;
                       $link["UGROUPS"] = array();
                       $otherLinksArray[] = $link;      

             }
             
             return $otherLinksArray;          
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
?>