<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table day_template_item : day_template_item - حصص نموذج يوم دراسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class ProfCalendarItem extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 

        
        public function __construct(){
		parent::__construct("prof_calendar_item","id","sis");
                SisProfCalendarItemAfwStructure::initInstance($this);                
	}
       
        
        public function showDay($wd)
        {
                $res = "<br>\nبرنامج المعلم لليوم ".$wd;
                $res .= "<br>\nالحصة : ".$this->getVal("session_order") . " > ";
                
                if(!$this->getVal("psi_$wd")) $res .= " متوفر";
                else 
                {
                        $prof_sched_item_Obj = $this->het("psi_$wd");
                        if($prof_sched_item_Obj) $res .= $prof_sched_item_Obj->getDisplay("ar");
                        else $res .= " غير مبرمج أصلا";
                }
                
                return $res; 
        
        }
}

?>