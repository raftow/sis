<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table program_type : program_type - أنواع الجوالات ايفون/اندرويد ... 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ProgramType extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "program_type"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("program_type","id","sis");
                SisProgramTypeAfwStructure::initInstance($this);
                
	}

        public function getDisplay($lang="ar")
        {
               if(!$this->getId()) return $this->translate("unknown",$lang);
               $fn = ""; 
               $fn = trim($fn." " . $this->valProgram_type_name_ar());
               $fn = trim($fn." " . $this->valLookup_code());
                        
               return $fn;
        }
}
?>