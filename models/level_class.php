<?php
// ------------------------------------------------------------------------------------
// 27/1/2023
// ALTER TABLE `level_class` CHANGE `level_class_order` `level_class_order` SMALLINT(6) NOT NULL DEFAULT '0'; 

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class LevelClass extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        

        public function __construct(){
		parent::__construct("level_class","id","sis");
                SisLevelClassAfwStructure::initInstance($this);
	}
        
        public function getDisplay($lang="ar")
        {
               if($lang=="fr") $lang = "en";
               list($data,$link) = $this->displayAttribute("school_level_id");
               $data2 = $this->getVal("level_class_name_$lang");
               //$data3 = $this->getVal("level_class_order");
               return $data." ← ".$data2;
        }

        public function getDropdownDisplay($lang="ar")
        {
               if($lang=="fr") $lang = "en";
               list($data,$link) = $this->displayAttribute("school_level_id");
               $data2 = $this->getVal("level_class_name_$lang");
               // $data3 = $this->getVal("level_class_order");
               return $data." ← ".$data2;
        }

        public static function loadByMainIndex($school_level_id,$level_class_order,$create_obj_if_not_found=false)
        {
           $obj = new LevelClass();
           if(!$school_level_id) $obj->throwError("loadByMainIndex : school_level_id is mandatory field");
           if(!$level_class_order) $obj->throwError("loadByMainIndex : level_class_order is mandatory field");
           $obj->select("school_level_id",$school_level_id);
           $obj->select("level_class_order",$level_class_order); 
           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("school_level_id",$school_level_id);
                $obj->set("level_class_order",$level_class_order); 
                $obj->insertNew();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
 
        }
}
?>      