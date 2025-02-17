<?php
// ------------------------------------------------------------------------------------
// mysql> alter table sdepartment drop sdepartment_name;
// mysql> alter table sdepartment change period_id period_id int null;
// mysql> alter table sdepartment change school_id school_id int null;
// mysql> alter table sdepartment change week_template_id week_template_id int null;
                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class Sdepartment extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "sdepartment"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("sdepartment","id","sis");
                SisSdepartmentAfwStructure::initInstance($this);
                
	}

        public static function loadByMainIndex($orgunit_id,$create_obj_if_not_found = false) 
        {
                $obj = new Sdepartment();
                $obj->select('orgunit_id', $orgunit_id);
                if ($obj->load()) 
                {
                        if ($create_obj_if_not_found) 
                        {
                                $obj->activate();
                        }
                        return $obj;
                }
                elseif ($create_obj_if_not_found) 
                {
                        $obj->set('orgunit_id', $orgunit_id);
                        $obj->insertNew();
                        $obj->is_new = true;
                        return $obj;
                } 
                else 
                {
                    return null;
                }
        }

        

        public function beforeMAJ($id, $fields_updated) 
        {
               $file_dir_name = dirname(__FILE__); 
               
               if(!$this->getVal("orgunit_id")) 
               {
                // the orgunit ID is mandaory because main unique index
                return false;
                /*
                    $sg_id = $this->getVal("school_id");
                    $schoolObj = $this->het("school_id");
                    if($schoolObj) $orgunit_id = $schoolObj->getVal("orgunit_id");
                    else $parent_orgunit_id = 0;

                    $id_sh_type = 3;
                    $id_domain = 2;
                    $hrm_code = $schoolObj->getVal("ref_num");
                    

                    $titre_short = $this->getVal("sdepartment_name_ar");
                    $titre_short_en = $this->getVal("sdepartment_name_en");

                    $orgunitObj = Orgunit::findOrgunit($id_sh_type, $parent_orgunit_id, $hrm_code, $titre_short, $titre_short, $titre_short_en, $titre_short_en, $id_domain, $create_obj_if_not_found = true);
		
                    if($orgunitObj) $this->set("orgunit_id",$orgunitObj->getId());*/
               }
               
               
               return true;
        }



}
?>