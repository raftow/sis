<?php
                
$file_dir_name = dirname(__FILE__); 
                
// ALTER TABLE cpc_course_program_school ADD program_sa_code VARCHAR(64);
// ALTER TABLE cpc_course_program_school ADD level_sa_code VARCHAR(64);
	

class CpcCourseProgramSchool extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= ""; 
    public static $DB_STRUCTURE = null; 
    
    
    public function __construct()
    {
		parent::__construct("cpc_course_program_school","id","sis");
        SisCpcCourseProgramSchoolAfwStructure::initInstance($this);
	}

    public static function loadByMainIndex($course_program_id, $school_id,$create_obj_if_not_found=false)
    {
        $obj = new CpcCourseProgramSchool();
        $obj->select("course_program_id",$course_program_id);
        $obj->select("school_id",$school_id);

        if($obj->load())
        {
            if($create_obj_if_not_found) $obj->activate();
            return $obj;
        }
        elseif($create_obj_if_not_found)
        {
            $obj->set("course_program_id",$course_program_id);
            $obj->set("school_id",$school_id);

            $obj->insertNew();
            if(!$obj->id) return null; // means beforeInsert rejected insert operation
            $obj->is_new = true;
            return $obj;
        }
        else return null;
        
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