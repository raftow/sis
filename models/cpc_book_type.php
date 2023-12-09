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


        // CPCBOOKTYPE-1 - كتاب  
        public static $CPC_BOOK_TYPE_BOOK = 1; 

        // CPCBOOKTYPE-2 - جزء من كتاب  
        public static $CPC_BOOK_TYPE_PART = 2; 

        // CPCBOOKTYPE-3 - فصل من كتاب  
        public static $CPC_BOOK_TYPE_CHAPTER = 3;
        
        public function __construct(){
		parent::__construct("cpc_book_type","id","sis");
                SisCpcBookTypeAfwStructure::initInstance($this);
                
                
                
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