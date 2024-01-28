<?php
// ------------------------------------------------------------------------------------
// 25/1/2023
// ALTER TABLE `school_scope` CHANGE `sdepartment_id` `sdepartment_id` INT(11) NOT NULL DEFAULT '0'; 
// ALTER TABLE `school_scope` CHANGE `class_nb` `class_nb` SMALLINT(6) NOT NULL DEFAULT '0'; 
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolScope extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "school_scope"; 
    public static $DB_STRUCTURE = null; 
    
    
    public function __construct(){
		parent::__construct("school_scope","id","sis");
        SisSchoolScopeAfwStructure::initInstance($this);
                
	}
        
    public static function loadByMainIndex($school_year_id, $school_level_id, $level_class_id,$create_obj_if_not_found=false)
    {


       $obj = new SchoolScope();
       $obj->select("school_year_id",$school_year_id);
       $obj->select("school_level_id",$school_level_id);
       $obj->select("level_class_id",$level_class_id);

       if($obj->load())
       {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
       }
       elseif($create_obj_if_not_found)
       {
            $obj->set("school_year_id",$school_year_id);
            $obj->set("school_level_id",$school_level_id);
            $obj->set("level_class_id",$level_class_id);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
       }
       else return null;
       
    }


        public function getDisplay($lang="ar")
        {
               list($data,$link) = $this->displayAttribute("level_class_id");
               list($data2,$link2) = $this->displayAttribute("school_year_id");
               $class_nb = $this->getVal("class_nb");
               return "مستوى ". $data." - ".$class_nb. " صفوف لــ : ".$data2;
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

    public function shouldBeCalculatedField($attribute){
        if($attribute=="school_id") return true;
        if($attribute=="levels_template_id") return true;
        return false;
    }
        
}
?>