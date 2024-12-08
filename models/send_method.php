<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table send_method : send_method - اشعار التطبيق / اشعار ويب / بريد الكتروني / رسالة قصيرة 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class SendMethod extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("send_method","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "send_method_name_ar";
                $this->ORDER_BY_FIELDS = "send_method_name_ar";
                $this->public_display = true;
	}
}
?>