<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table payment_period : payment_period - فترات الاستهلاك الخدمة 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class PaymentPeriod extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "payment_period"; 
        public static $DB_STRUCTURE = null; 
        
        
        public function __construct(){
		parent::__construct("payment_period","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "payment_period_name";
                $this->ORDER_BY_FIELDS = "payment_period_name";
	}
}
?>