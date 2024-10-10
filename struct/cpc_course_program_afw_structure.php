<?php 
        class SisCpcCourseProgramAfwStructure
        {
			public static function initInstance(&$obj)
			{
				if ($obj instanceof CpcCourseProgram) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "course_program_name_ar";
					$obj->ORDER_BY_FIELDS = "school_level_id,course_program_name_ar";

					$obj->editByStep = true;
					$obj->editNbSteps = 3;
					
					$obj->showRetrieveErrors = true;
					$obj->showQeditErrors = true;
					$obj->qedit_minibox = true;
					$obj->no_step_help = true;

					$obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration

					
					$obj->UNIQUE_KEY = array('school_level_id','lookup_code');
				}
			}
			
                public static $DB_STRUCTURE = array(

										

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'lookup_code' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
			    'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 64,  
				'UTF8' => true,  'QSEARCH' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	
				
				
			"program_type_id" => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true, 
					'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
					'TYPE' => 'FK',  'ANSWER' => 'program_type',  'ANSMODULE' => 'sis',  'SIZE' => 40,  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'AUTOCOMPLETE' => true, 'AUTOCOMPLETE-SEARCH' => true,
					'CSS' => 'width_pct_50',  'READONLY' => false,),				

			'course_program_name_ar' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'QSEARCH' => true,  
				'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),


			'course_program_name_en' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'SEARCH-ADMIN' => true,  'QSEARCH' => true,  
				'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			"school_level_id" => array("IMPORTANT" => "IN", "SEARCH" => true,  'QSEARCH' => true, "SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "QEDIT" => true, "UTF8" => false, "TYPE" => "FK",
				"ANSWER" => "school_level", "ANSMODULE" => "sis", "SIZE" => 40, 
				'CSS' => 'width_pct_25', "DEFAULT" => 0),				


			'duration' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => 'يوم',  'QSEARCH' => true, 
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'duration_desc' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'h_duration' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => 'ساعة', 
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					
				
			'levels_template_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'levels_template',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			/*'accreditation_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 64, 
			    'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),*/


		'cpcCourseProgramSchoolList' => array(
					'TYPE' => 'FK',  'ANSWER' => 'cpc_course_program_school',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => 'course_program_id',  
					'WHERE' => "", 
					 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  
					 'ICONS' => true,  'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => true,    
					 'DISPLAY' => true,  'STEP' => 2,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),
	

		'cpcCourseProgramBookList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'cpc_course_program_book',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'course_program_id',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true,  'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => true,    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

'version'                  => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'TYPE' => 'INT', 'FGROUP' => 'tech_fields'),

'update_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

'delete_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

'display_groups_mfk'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

'sci_id'                        => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
				'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 				
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),                        
                ); 
        } 
?>