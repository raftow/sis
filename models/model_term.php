<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table model_term : model_term - نماذج فصل دراسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ModelTerm extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "model_term"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("model_term","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "model_term_name";
                $this->ORDER_BY_FIELDS = "model_term_name";
	}
}
?>