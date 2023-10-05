<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_course_program_book : cpc_course_program_book - الكتب الدراسية لبرنامج دراسي 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcCourseProgramBook extends SisObject{

	public static $DATABASE		= ""; public static $MODULE		    = "sis"; public static $TABLE			= ""; public static $DB_STRUCTURE = null; /* array(
                "id" => array("SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "TYPE" => "PK"),

		"course_program_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => cpc_course_program, "ANSMODULE" => sis, "DEFAULT" => 0, "READONLY"=>true),
		"level_class_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => level_class, "ANSMODULE" => sis, 
                                          "SEARCH-BY-ONE"=>true, "NO-COTE"=>true,
                                          "WHERE"=>"school_level_id in (select slvl.id 
                                                                  from c0sis.school_level slvl 
                                                                      inner join c0sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", "DEFAULT" => 0),

		"book_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", 
                                      ANSWER => cpc_book, "ANSMODULE" => sis, "DEFAULT" => 0, 
                                      WHERE=>"(level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')"),

		"course_id" => array(CATEGORY=>SHORTCUT, "IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "RETRIEVE-ADMIN" => true, "EDIT-ADMIN" => true, "QEDIT-ADMIN" => true, "UTF8" => false, 
                                      "TYPE" => "FK", "ANSWER" => course, "ANSMODULE" => sis, "DEFAULT" => 0, SHORTCUT=>"book_id.course_id", "NO-SAVE"=>true, "READONLY"=>true),
		"cpc_book_type_id" => array(CATEGORY=>SHORTCUT, "IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, 
                                      "TYPE" => "FK", "ANSWER" => cpc_book_type, "ANSMODULE" => sis, "DEFAULT" => 0, SHORTCUT=>"book_id.book_type_id", "NO-SAVE"=>true, "READONLY"=>true),

		"course_nb" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		//"comments" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "SIZE" => 255, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT"),
                
                cpcCoursePlanList => array(TYPE => FK, ANSWER => cpc_course_plan, ANSMODULE => sis, CATEGORY => ITEMS, ITEM => '', WHERE=>"course_program_id=§course_program_id§ and course_id=§course_id§ and level_class_id='§level_class_id§'", SHOW => true, FORMAT=>retrieve, EDIT => false, ICONS=>true, 'DELETE-ICON'=>false, BUTTONS=>true, "NO-LABEL"=>true),
                
                "created_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "created_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "updated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "updated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "validated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "validated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "active" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "DEFAULT" => "Y", "TYPE" => "YN"),
                "version" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "INT"),
                "update_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "delete_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "display_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "sci_id" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "scenario_item", "ANSMODULE" => "pag"),
	);
	
	*/ public function __construct(){
		parent::__construct("cpc_course_program_book","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "course_program_id, level_class_id, book_id";
                
                
	}
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             if($mode=="mode_cpcCoursePlanList")
             {
                   $course_program_id = $this->getVal("course_program_id");
                   $course_program = $this->showAttribute("course_program_id"); 
                   $course_id = $this->getVal("course_id");
                   $course = $this->showAttribute("course_id");
                   $level_class_id = $this->getVal("level_class_id");
                   $level_class = $this->showAttribute("level_class_id");
             
                   unset($link);
                   $my_id = $this->getId();
                   $link = array();
                   $title = "إدارة المحتوى الدراسي";
                   $title_detailed = $title ." : $course_program / صف $level_class / مادة $course";
                   $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcCoursePlan&currmod=sis&id_origin=$my_id&class_origin=CpcCourseProgramBook&module_origin=sis&newo=5&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=course_program_id=$course_program_id,level_class_id=$level_class_id,course_id=$course_id&sel_course_program_id=$course_program_id&sel_level_class_id=$level_class_id&sel_course_id=$course_id";
                   $link["TITLE"] = $title;
                   $link["UGROUPS"] = array();
                   $otherLinksArray[] = $link;
             }
             
             return $otherLinksArray;
        }
             
}
?>