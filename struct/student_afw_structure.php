<?php 
        class SisStudentAfwStructure
        {
			public static function initInstance($obj)
			{
				if($obj instanceof Student)
        		{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->ORDER_BY_FIELDS = "firstname,f_firstname,lastname";
					$obj->DISPLAY_FIELD = "concat(IF(ISNULL(firstname), '', firstname) , ' ' , IF(ISNULL(lastname), '', lastname))";
					$obj->AUTOCOMPLETE_FIELD = "idn";//
					$obj->AUTOCOMPLETE_EXACT_SEARCH = true;
					$obj->UNIQUE_KEY = array("idn");
					$obj->copypast = true;


					$obj->editByStep = true;
					$obj->editNbSteps = 7;
					
					$obj->showQeditErrors = true;
					$obj->showRetrieveErrors = true;
					$part_cols = "id";
            		$context_cols = "";

					// $obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration
					$obj->setContextAndPartitionCols($part_cols, $context_cols);
				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => false,  
				'TYPE' => 'PK', 'SEARCH' => true, 'QSEARCH' => true,  'TEXT-SEARCHABLE-SEPARATED'=>true,  'FGROUP' => 'personal',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'nomcomplet' => array(
				'IMPORTANT' => 'high', 'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => false,  'SEARCH' => true,  'RETRIEVE' => true,  'UTF8' => true,  'EDIT' => false,  
				'FIELD-FORMULA' => "concat(IF(ISNULL(firstname), '', firstname) , ' ' , IF(ISNULL(f_firstname), '', f_firstname) , ' ' , IF(ISNULL(lastname), '', lastname))", 
				   'DISPLAY' => false,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'genre_id' => array(
				'CATEGORY' => '',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  'RETRIEVE' => false, 
				'SEARCH' => true,  'QSEARCH' => false,  'SEARCH-MANDATORY' => true,  'SEARCH-DEFAULT' => true, 
				'QSEARCH' => true,  'DEFAUT' => 1,  'SHOW-ADMIN' => true,  'ROLES' => 6,  
				'MINIBOX' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',
				'MANDATORY' => true,  'UTF8' => false,  
				'TYPE' => 'ENUM',  'ANSWER' => "FUNCTION",  
				'RELATION' => 'ManyToOne',  'READONLY' => false, ),

							
				
			'firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 30,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1, 'QSEARCH' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => true, 'EXCEL' => true,
				'CSS' => 'width_pct_25',),

			'f_firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 30,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1, 'QSEARCH' => true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'EXCEL' => true,
				'CSS' => 'width_pct_25',),

			'lastname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 30,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1,'QSEARCH' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => true, 'EXCEL' => true,
				'CSS' => 'width_pct_25',),

			'mobile' => array('IMPORTANT' => 'high',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 20,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1,'QSEARCH' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => ">>config::student_mobile_mandatory,",  
				'CSS' => 'width_pct_25',),

			'country_id' => array('IMPORTANT' => 'high',  'SEARCH' => true, 'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
			    'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'country',  'DEFAUT' => 0,  'ANSMODULE' => 'ums',  'MINIBOX' => true,    
				'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'idn_type_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true, 
				'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false, 'MANDATORY' => true,  
				'TYPE' => 'FK',  'ANSWER' => 'idn_type',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'EDIT_IF_EMPTY'=>true,
				/* was readonly I dont know why ? 
				'READONLY' => true,
				*/
				'CSS' => 'width_pct_25',),

			'idn' => array('IMPORTANT' => 'high',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,
			    'SIZE' => 20,  'UTF8' => true, 'MANDATORY' => true, /* 'QSEARCH' => true,  'TEXT-SEARCHABLE-SEPARATED'=>true,*/
				'TYPE' => 'TEXT',  'FORMAT' => 'SA-IDN',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'EXCEL' => true, 'READONLY' => true, 'EDIT_IF_EMPTY'=>true,
				/* is readonly because Id = Idn*/
				'CSS' => 'width_pct_25',),

			'birth_date' => array('IMPORTANT' => 'medium',
				'TYPE' => 'DATE',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  
				'SEARCH' => true,   'MINIBOX' => true,  // 'FORMAT' => 'CONVERT_NASRANI', 
				'MANDATORY' => false,    'RETRIEVE' => true, 'STEP' => 1,  
				'ON-CHANGE' => "birthDateChanged();",
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, 
				'CSS' => 'width_pct_25',),

			'birth_date_en' => array(
					'TYPE' => 'GDAT',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  'SEARCH' => true, 
					'FORMAT' => '',  'MINIBOX' => true,  'RETRIEVE' => false, 
					'ON-CHANGE' => "birthDateEnChanged();",
					'MANDATORY' => false,    'DISPLAY' => true, 'STEP' => 1,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, 
					'CSS' => 'width_pct_25',),				

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			/*	
			'nasrani_birth_date' => array(
				'TYPE' => 'GDAT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'READONLY' => true,  'DATE_CONVERT' => 'NASRANI',  'CAN-BE-SETTED' => true,  'NO-SAVE' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),*/

						


			'parent_mobile' => array('IMPORTANT' => 'medium',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 20,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' =>  ">>config::student_parent_mobile_mandatory,",
				'CSS' => 'width_pct_25',),

			'parent_idn_type_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'idn_type',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,  'MINIBOX' => true,    'DISPLAY' => false,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' =>  ">>config::student_parent_idn_mandatory,",
				'CSS' => 'width_pct_25',),

			'parent_idn' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 20,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'FORMAT' => 'SA-IDN',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' =>  ">>config::student_parent_idn_mandatory,",
				'CSS' => 'width_pct_25',),
			

			'parent_customer_id' => array('STEP' => 2, 'SHORTNAME' => 'parent',   'SEARCH' => true,  'QSEARCH' => false,  
							'SHOW' => true,  'RETRIEVE' => false,  'EXCEL' => false,  'EDIT' => true,  'QEDIT' => false,  
							'CSS' => 'width_pct_25',  'SIZE' => 32,  'MANDATORY' => false,  'UTF8' => false,  'AUTOCOMPLETE' => true,  
							'TYPE' => 'FK',  'ANSWER' => 'crm_customer',  'ANSMODULE' => 'crm',  
							'WHERE' => 'mobile = §parent_mobile§ and first_name_ar = §f_firstname§',
							'RELATION' => 'OneToMany', "NO-RETURNTO" => true, "OTM-NO-LABEL" => false, "OTM-FILE" => true, 
							'READONLY' => true,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),							


			'mother_mobile' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 20,  'UTF8' => true,  
							'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 2,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_25',),
			
			'mother_idn_type_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
							'TYPE' => 'FK',  'ANSWER' => 'idn_type',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,  'MINIBOX' => true,    'DISPLAY' => false,  'STEP' => 2,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_25',),
			
			'mother_idn' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 20,  'UTF8' => true,  
							'TYPE' => 'TEXT',  'FORMAT' => 'SA-IDN',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 2,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_25',),							


			'mother_customer_id' => array('STEP' => 2, 'SHORTNAME' => 'parent',   'SEARCH' => true,  'QSEARCH' => false,  
							'SHOW' => true,  'RETRIEVE' => false,  'EXCEL' => false,  'EDIT' => true,  'QEDIT' => false,  
							'CSS' => 'width_pct_25',  'SIZE' => 32,  'MANDATORY' => false,  'UTF8' => false,  'AUTOCOMPLETE' => true,  
							'TYPE' => 'FK',  'ANSWER' => 'crm_customer',  'ANSMODULE' => 'crm',  
							'WHERE' => 'mobile = §mother_mobile§',
							'RELATION' => 'OneToMany', "NO-RETURNTO" => true, "OTM-NO-LABEL" => false, "OTM-FILE" => true, 
							'READONLY' => true,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),							



			'address' => array('FGROUP' => 'address',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 128,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'cp' => array('FGROUP' => 'address',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 20,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'city_id' => array('FGROUP' => 'address',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false, 
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'city',  'DEFAUT' => 0,  'ANSMODULE' => 'ums', 
				'MINIBOX' => true,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true, 'AUTOCOMPLETE' => true,
				'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'quarter' => array('FGROUP' => 'address',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true, 
				'QEDIT' => false,  'SIZE' => 40,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'email' => array('FGROUP' => 'address',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  
				'QEDIT' => false,  'SIZE' => 128,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_75',),

			'school_id' => array('SHORTNAME' => 'school',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'DEFAUT' => 0,  'MINIBOX' => true,  
				'ANSMODULE' => 'sis',  'AUTOCOMPLETE' => true,  'STEP' => 4,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'READONLY' => true, 'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25', ),


			'levels_template_id' => ['STEP' => 4, 
						'SHOW' => false,
						'EDIT' => false,
						'QEDIT' => false,
						'UTF8' => false,
						'TYPE' => 'FK',
						'ANSWER' => 'levels_template',
						'SIZE' => 40,
						'DEFAUT' => 0,
						'QSEARCH' => true,
						'ANSMODULE' => 'sis',
						'DISPLAY-UGROUPS' => '',
						'EDIT-UGROUPS' => '',
						'READONLY' => true,
						'CSS' => 'width_pct_25',
					],

			'school_level_order' => ['STEP' => 4, 
					'IMPORTANT' => 'high',
					'SHOW' => false,
					'EDIT' => false,
					'QEDIT' => false,
					'UTF8' => false,
					'TYPE' => 'INT',
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'READONLY' => true,
					'CSS' => 'width_pct_25',
				],

			'level_class_order' => ['STEP' => 4, 
					'IMPORTANT' => 'high',
					'SHOW' => false,
					'EDIT' => false,
					'QEDIT' => false,
					'UTF8' => false,
					'TYPE' => 'INT',
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'READONLY' => true,
					'CSS' => 'width_pct_25',
				],

			'course_program_id' => ['STEP' => 4, 
				'IMPORTANT' => 'IN',
				'SEARCH' => true,
				'SHOW' => true,
				'RETRIEVE' => false,
				'EDIT' => true,
				'QEDIT' => false,
				'SIZE' => 40,
				'QSEARCH' => true,
				'UTF8' => false,
				'TYPE' => 'FK',
				'ANSWER' => 'cpc_course_program',
				'ANSWERMODULE' => 'sis',
				'AUTOCOMPLETE-SEARCH' => true,
				'AUTOCOMPLETE' => true,
				'CSS' => 'width_pct_50',
				'DEFAULT' => 0,
			],

			'student_status_id' => [
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'QSEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => false,
					'EDIT' => true,
					'QEDIT' => false,
					'SIZE' => 40,
					'UTF8' => false,
					'TYPE' => 'FK',
					'ANSWER' => 'student_file_status',
					'ANSMODULE' => 'sis',
					'DEFAUT' => 1,
					'STEP' => 4,
					'DISPLAY' => true,
					'READONLY' => true,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],

			'reg_date' => array(
					'TYPE' => 'DATE',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  'SEARCH' => true,  'FORMAT' => '',  'MINIBOX' => true,  
					'MANDATORY' => false,  'READONLY' => true,    'DISPLAY' => true,  'STEP' => 4, 'EXCEL' => true, 
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, 
					'CSS' => 'width_pct_25',),				

			'student_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 4, 'QSEARCH' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'READONLY' => true, 
				'CSS' => 'width_pct_25',),

			'age' => array('STEP' => 4,  
					'TYPE' => 'INT',  'EDIT' => true,  
					'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false, 
					'UNIT' => 'سنة', "NO-HZM-UNIT" => true, 'READONLY' => true,  
					'CONSTRAINTS' => ['f-between;2,65'],  
					'MANDATORY' => false,    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true,  
					'CSS' => 'width_pct_25',),				

			'level' => array('STEP' => 4,  
						'CATEGORY' => '',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  'RETRIEVE' => false, 
						'SEARCH' => true,  'QSEARCH' => false,  'SEARCH-MANDATORY' => true,  'SEARCH-DEFAULT' => true, 
						'DEFAUT' => 0,  'SHOW-ADMIN' => true,  'ROLES' => 6,  
						'MINIBOX' => true,  'DISPLAY' => true,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',
						'MANDATORY' => true,  'UTF8' => false,  
						'TYPE' => 'ENUM',  'ANSWER' => "FUNCTION",  
						'RELATION' => 'ManyToOne',  'READONLY' => false, ),	
						
			'eval' => array('STEP' => 4,  
						'CATEGORY' => '',  'EDIT' => true,  'QEDIT' => false,  'SHOW' => true,  'RETRIEVE' => false, 
						'SEARCH' => true,  'QSEARCH' => false,  'SEARCH-MANDATORY' => true,  'SEARCH-DEFAULT' => true, 
						'DEFAUT' => 1,  'SHOW-ADMIN' => true,  'ROLES' => 6,  
						'MINIBOX' => true,  'DISPLAY' => true,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',
						'MANDATORY' => true,  'UTF8' => false,  
						'TYPE' => 'ENUM',  'ANSWER' => "FUNCTION",  
						'RELATION' => 'ManyToOne',  'READONLY' => false, ),							


			'files' => array('STEP' => 5,  
					'TYPE' => 'FK',  'ANSWER' => 'student_file',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => 'student_id',  
					'WHERE' => "", 
					'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve',  
					'DO-NOT-RETRIEVE-COLS' => ['firstname', 'f_firstname', 'lastname', 'idn', 'mobile', 'parent_mobile'],
					'EDIT' => false,  'BUTTONS' => true,  'FGROUP' => 'files',    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),

			'current_file' => array('STEP' => 5,  
					'TYPE' => 'FK',  'ANSWER' => 'student_file',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'FORMULA', 
					'WHERE' => "", 
					'SHOW' => true,  
					'EDIT' => false,  'READONLY' => true,  'FGROUP' => 'files',    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),



			'courses' => array('STEP' => 5,  
				'TYPE' => 'FK',  'ANSWER' => 'student_course',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'student_id',  
				'WHERE' => "", 
				'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve',  
				'DO-NOT-RETRIEVE-COLS' => ['firstname', 'f_firstname', 'lastname', 'idn'],
				'EDIT' => false,  'BUTTONS' => true,  'FGROUP' => 'files',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),
	


			'course_program_name_ar' => array('STEP' => 99,  'SEARCH' => true,  'SHOW' => true,  'EXCEL' => true,  
					'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'QSEARCH' => true,  
					'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.course_program_id.course_program_name_ar',
					'TYPE' => 'TEXT',    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_50',),					

			"program_type_id" => array( 'STEP' => 99,   'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true, 
					'EXCEL' => true, 'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.course_program_id.program_type_id',
					'TYPE' => 'FK',  'ANSWER' => 'program_type',  'ANSMODULE' => 'sis',  'SIZE' => 40,  'DEFAUT' => 0,    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'AUTOCOMPLETE' => true,
					'CSS' => 'width_pct_50',  'READONLY' => false,),		
					
			'duration' => array('STEP' => 99,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.course_program_id.duration',
					'TYPE' => 'INT', 'UNIT' => 'يوم',  'QSEARCH' => true, 
					'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),		
					
					
			'cands' => array('STEP' => 6, 
					'TYPE' => 'FK',  'ANSWER' => 'scandidate',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => 'student_id',  'ITEM_OPER' => '',  
					'WHERE' => "candidate_status_id != 2", 
					'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  'FORMAT' => 'retrieve',  
					'EDIT' => true,  'BUTTONS' => true,  'FGROUP' => 'files',  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  
					'CSS' => 'width_pct_100',),	

			'studentBookList' => array('STEP' => 7, 
				    'SHORTNAME' => 'studentBooks',  'SHOW' => true,  'FORMAT' => 'retrieve',  'ICONS' => true,  
					'DELETE-ICON' => true,  'BUTTONS' => true,  'SEARCH' => false,  'QSEARCH' => false, 
					'AUDIT' => false,  'RETRIEVE' => false,  
					'EDIT' => false,  'QEDIT' => false,  
					'SIZE' => 32,  'MAXLENGTH' => 32,  'MIN-SIZE' => 1,  'CHAR_TEMPLATE' => "ALPHABETIC,SPACE",  'MANDATORY' => false,  'UTF8' => false,  
					'TYPE' => 'FK',  
					'CATEGORY' => 'ITEMS',  'ANSWER' => 'student_book',  'ANSMODULE' => 'sis',  'ITEM' => 'student_id',  
					'READONLY' => true,  
					'CSS' => 'width_pct_100', ),					



			'ref_num' => array('STEP' => 10, 'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  
					'MAXLENGTH' => 32,  'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => '',  'UTF8' => false,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.ref_num',
					'TYPE' => 'TEXT',  'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),		
					
			'school_name_ar' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false, 
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'MAXLENGTH' => 48,  
					'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => 'ARABIC-CHARS,SPACE',  'UTF8' => true,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.school_name_ar',
					'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  					
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  
					'CSS' => 'width_pct_25',),
					
			'address' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 128,  'MAXLENGTH' => 32,  'UTF8' => true,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.address',
					'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),
	
			'city_id' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.city_id',
					'TYPE' => 'FK',  'ANSWER' => 'city',  'ANSMODULE' => 'ums',  'AUTOCOMPLETE' => true,  
					'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),

			'region_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true, 'QSEARCH' => true, 'SHOW' => true,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
					'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.city_id.region_id',
					'TYPE' => 'FK',  'ANSWER' => 'region',  'ANSMODULE' => 'ums',  'SIZE' => 40,  'DEFAUT' => 0,    'DISPLAY' => true,  
					'STEP' => 10,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					),					
	
			'quarter' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
					'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => true,  
					'CATEGORY' => 'SHORTCUT', 'SHORTCUT' => 'current_file.school_id.quarter',
					'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),					

/*  to be reviewed
			'father_auser_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'FGROUP' => 'family',  'DEFAUT' => 0,  'MINIBOX' => false,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'mother_auser_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'resp1_auser_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'resp2_auser_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),*/


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


		'version'                  => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'TYPE' => 'INT', 'FGROUP' => 'tech_fields'),
		
		'update_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),
		
		'delete_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),
		
		'display_groups_mfk'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),
		
		'sci_id'                        => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
				'TYPE' => 'INT', /*stepnum-not-the-object*/ 'FGROUP' => 'tech_fields'),
		
		'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				
		
		

                        
                ); 
        } 
?>