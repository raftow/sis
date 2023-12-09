<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_book_type : cpc_book_type - أنواع الكتب الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcBookType extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        
        
        public function __construct(){
		parent::__construct("cpc_book_type","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "book_type_name_ar";
                $this->ORDER_BY_FIELDS = "book_type_name_ar";
                $this->public_display = true;
                
                
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