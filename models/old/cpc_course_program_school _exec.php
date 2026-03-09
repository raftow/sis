<?php
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcCourseProgramSchoolExec extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= ""; 
    public static $DB_STRUCTURE = null; 
    
    
    public function __construct()
    {
		parent::__construct("cpc_course_program_school","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "school_id,course_program_id,exec_num";

                $this->editByStep = true;
                $this->editNbSteps = 1;
                
                $this->showRetrieveErrors = true;
                $this->showQeditErrors = true;
                $this->qedit_minibox = true;
                $this->no_step_help = true;

                $this->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration

                
                $this->UNIQUE_KEY = array('school_id', 'course_program_id','exec_num');
                
                
	}

    public static function loadByMainIndex($school_id, $course_program_id, $exec_num, $create_obj_if_not_found=false)
    {
        $obj = new CpcCourseProgramSchoolExec();
        $obj->select("school_id",$school_id);
        $obj->select("course_program_id",$course_program_id);
        $obj->select("exec_num",$exec_num);

        if($obj->load())
        {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
        }
        elseif($create_obj_if_not_found)
        {
            $obj->set("school_id",$school_id);
            $obj->set("course_program_id",$course_program_id);
            $obj->set("exec_num",$exec_num);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
        }
        else return null;
        
    }

    public static function loadFromRow($row)
    {
        return self::loadByMainIndex(trim($row["school_id"]),trim($row["course_program_id"]),trim($row["exec_num"]), $create_obj_if_not_found=true);
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