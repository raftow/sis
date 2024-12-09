<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table sexam : sexam - الإختبارات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Sexam extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "sexam"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("sexam","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "exam_session_name";
                $this->ORDER_BY_FIELDS = "exam_session_name";
                
                
	}
        
        public function showMe($style="",$lang="ar")
        {
                $exam_session_name = $this->getVal("exam_session_name");
                $cpc_book = $this->showAttribute("cpc_book_id");
                $from_page = $this->getVal("from_page");
                $to_page = $this->getVal("to_page");
                $exam_session_desc = $this->getVal("exam_session_desc");
                
                return "<div class='exam_name'>$exam_session_name</div>
                        <div class='exam_desc'>من ص $from_page إلى ص $to_page : $exam_session_desc</div>
                        
                
                ";
        } 
        
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = array();
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             
             
             return $otherLinksArray;
        }
             
}
?>