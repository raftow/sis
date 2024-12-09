<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table remark : remark - الملاحظات على الطلاب 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Remark extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "remark"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("remark","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "remark_name";
                $this->ORDER_BY_FIELDS = "remark_name";
	}
}
?>