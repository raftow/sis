<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table sdepartment : sdepartment - أقسام المدرسة 
// ------------------------------------------------------------------------------------

                
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


        public function beforeMAJ($id, $fields_updated) 
        {
               $file_dir_name = dirname(__FILE__); 
               
               if(!$this->getVal("orgunit_id")) 
               {
                    // require_once("/orgunit.php");
                    $sh = new Orgunit();
                    $sh->set("titre_short",$this->getVal("sdepartment_name_ar"));
                    $sh->set("titre",$this->getVal("sdepartment_name_en"));
                    $sg_id = $this->getVal("school_id");
                    if($sg_id>0) $parent_orgunit_id = $this->get("school_id")->getVal("orgunit_id");
                    else $parent_orgunit_id = 0;
                    $sh->set("id_sh_type",3);
                    $sh->set("id_sh_org",$parent_orgunit_id);
                    $sh->set("id_sh_parent",$parent_orgunit_id);

                        /* @todo
        		"phone" => array("SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => false, "TYPE" => "TEXT"),
        		"web" => array("SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => false, "TYPE" => "TEXT", "FORMAT" => "WEB"),
        		"email" => array("SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => false, "TYPE" => "TEXT"),*/
                    
                    $sh->insert();
		
                    $this->set("orgunit_id",$sh->getId());
               }
               
               
               return true;
        }



}
?>