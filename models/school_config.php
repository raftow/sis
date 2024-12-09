<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table school_config : school_config - إعدادات حسابات المنشآت 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SchoolConfig extends SisObject{

        public static $MY_ATABLE_ID=13678; 

        
	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "school_config"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("school_config","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "id";
                $this->ORDER_BY_FIELDS = "id";
                 
                
                
                
	}
        
        public static function loadById($id)
        {
           $obj = new SchoolConfig();
           $obj->select_visibilite_horizontale();
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }
        
        
        
        
        
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             
             
             return $otherLinksArray;
        }
             
}
?>