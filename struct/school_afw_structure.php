<?php 
        class SisSchoolAfwStructure
        {
            public static function initInstance(&$obj)
			{
				if ($obj instanceof School) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					// $obj->DISPLAY_FIELD = "school_name_ar";
					$obj->ORDER_BY_FIELDS = "school_name_ar";
					$obj->editByStep = true;
					$obj->editNbSteps = 10;

					$obj->showRetrieveErrors = true;
					$obj->showQeditErrors = true;
					$obj->qedit_minibox = true;
					$obj->no_step_help = true;

					$obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration


					$obj->UNIQUE_KEY = array('ref_num');
				}
			}       
			
			public static $DB_STRUCTURE = array(

                        
			'id' => array('STEP' => 1,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'group_num' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => false,  'AUDIT' => false,  
				'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true, 'DEFAULT' => 1,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_type_id' => array('STEP' => 1,  'SHORTNAME' => 'type',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_type',  'ANSMODULE' => 'sis',  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'period_mfk' => array('STEP' => 1,  'SHORTNAME' => 'periods',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'MFK',  'ANSWER' => 'period',  'ANSMODULE' => 'sis',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'presence_mfk' => array('STEP' => 1,  'SHORTNAME' => 'presences',  
				'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  'MFK-SHOW-SEPARATOR' => '، ',  
				'TYPE' => 'MENUM',  'ANSWER' => 'FUNCTION',  
				'READONLY' => false,  'SEARCH-BY-ONE' => false,  'REQUIRED' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'school_name_ar' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'MAXLENGTH' => 48,  'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => 'ARABIC-CHARS,SPACE',  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => true, 
				'CSS' => 'width_pct_50',),

			'school_name_en' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'MAXLENGTH' => 48,  'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => 'ALPHABETIC,SPACE',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => false, 
				'CSS' => 'width_pct_50',),

			'group_school_id' => array('STEP' => 1,  'SHORTNAME' => 'group',  
				'WHERE' => "school_type_id = 2", 
				 'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',   
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'orgunit_id' => array('STEP' => 1,  'SHORTNAME' => 'orgunit',  
				'WHERE' => "(§school_type_id§ = 1 and id_sh_type = 9) or (§school_type_id§ = 2 and id_sh_type = 10)", 
				 'SEARCH' => false,  'QSEARCH' => false,  'SHOW' => false,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  'AUTOCOMPLETE' => true,  
				'RELATION' => 'ManyToOne',  'READONLY' => true,  'SEARCH-BY-ONE' => false,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'genre_id' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'ENUM', 'ANSWER' => "FUNCTION",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'age_min' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => "سنة",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'age_max' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => "سنة",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			
				
			'status_id' => array('STEP' => 1, 'SHORTNAME' => 'status',  'SEARCH' => true,  'QSEARCH' => true,  
				'SHOW' => true,  'RETRIEVE' => true,  'EXCEL' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 60,  
				'MANDATORY' => true,  'UTF8' => false,  
				'CSS' => 'width_pct_25',  'QSIZE' => 3,  
				'TYPE' => 'FK',  'ANSWER' => 'school_status',  'ANSMODULE' => 'sis',  'DEFAULT' => 1,  
				'RELATION' => 'ManyToOne',  'READONLY' => true,  'AUDIT' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'ERROR-CHECK' => true, ),				
				


				
			'age_coef' => array('FGROUP' => 'coef', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'eval_coef' => array('FGROUP' => 'coef', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 100,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'moral_coef' => array('FGROUP' => 'coef', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'capacity_coef' => array('FGROUP' => 'coef', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'general_max' => array('FGROUP' => 'coef','STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 
				'READONLY' => false,  'QSEARCH' => true,  'DEFAULT' => 100,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				
				
				
			'age_distrib' => array('FGROUP' => 'distrib', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'eval_distrib' => array('FGROUP' => 'distrib', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 100,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'moral_distrib' => array('FGROUP' => 'distrib', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'capacity_distrib' => array('FGROUP' => 'distrib', 'STEP' => 2,  'SHOW' => true,  'AUDIT' => false, 'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG', 'UNIT' => "%", 'DEFAULT' => 0,  
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),						


									

							


			'scapacity' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'nbrooms' => array('SHOW' => true,  'EDIT' => true,  'SIZE' => 32,  'CAN-BE-SETTED' => true,  'DIRECT_ACCESS' => true,  
				'TYPE' => 'INT',  
				'CATEGORY' => 'FORMULA',  'STEP' => 3,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'building_size' => array('SHOW' => true,  'EDIT' => true,  'SIZE' => 32,  'DIRECT_ACCESS' => true,  
				'TYPE' => 'INT',  
				'STEP' => 3,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			'lang_id' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'lang',  'ANSMODULE' => 'pag',  'DEFAULT' => 1,
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'holidays_school_id' => array('STEP' => 3,  'SHORTNAME' => 'holidays',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'levels_template_id' => array('STEP' => 3,  'SHORTNAME' => 'template',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'levels_template',  'ANSMODULE' => 'sis',  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'courses_template_id' => array('STEP' => 3,  'SHORTNAME' => 'ctemplate',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'courses_template',  'ANSMODULE' => 'sis',  
				'RELATION' => 'OneToMany',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


						'course_mfk' => array('IMPORTANT' => 'IN',  
							'CATEGORY' => 'SHORTCUT',
            				'SHORTCUT' => 'courses_template_id.course_mfk',
							'TYPE' => 'MFK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis', 'STEP' => 3,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_100',),	

			

			'main_course_id' => array('STEP' => 3,  'SHORTNAME' => 'ctemplate',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis', 
				'WHERE' => "§course_mfk§ like CONCAT('%',id,'%')",
				'RELATION' => 'OneToMany',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'date_system_id' => array('STEP' => 3,  'SHORTNAME' => 'dsystem',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'sis_date_system',  'ANSMODULE' => 'sis',  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'age_date_system_id' => array('STEP' => 3,  'SHORTNAME' => 'adsystem',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'sis_date_system',  'ANSMODULE' => 'sis',  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'ref_num' => array('STEP' => 3, 'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  
				'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  
				'MAXLENGTH' => 32,  'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => '',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			'license_start_date' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 10,  'MAXLENGTH' => 10,  'FORMAT' => 'CONVERT_NASRANI_SIMPLE',  'UTF8' => false,  
				'TYPE' => 'DATE',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),
				
			'license_end_date' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 10,  'MAXLENGTH' => 10,  'FORMAT' => 'CONVERT_NASRANI_SIMPLE',  'UTF8' => false,  
				'TYPE' => 'DATE',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'expiring_hdate' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 10,  'MAXLENGTH' => 10,  'FORMAT' => 'CONVERT_NASRANI_SIMPLE',  'UTF8' => false,  
				'TYPE' => 'DATE',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			'trade_num' => array('STEP' => 3,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'MAXLENGTH' => 32,  'MIN-SIZE' => 5,  'CHAR_TEMPLATE' => '',  'UTF8' => false,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'courses_config_template_id' => array('STEP' => 4,  'SHORTNAME' => 'config_template',  
				'WHERE' => "levels_template_id=§levels_template_id§ and courses_template_id=§courses_template_id§ and school_id in (0§id§,§group_school_id§,0)", 
				 'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'ANSMODULE' => 'sis',  
				'RELATION' => 'OneToMany',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'school_level_mfk' => array('STEP' => 4,  'SHORTNAME' => 'levels',  
				'WHERE' => "levels_template_id=§levels_template_id§", 
				 'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  'MFK-SHOW-SEPARATOR' => '، ',  
				'TYPE' => 'MFK',  'ANSWER' => 'school_level',  'ANSMODULE' => 'sis',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'sp1' => array('STEP' => 4,  'UNIT' => 'دق',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'sp2' => array('STEP' => 4,  'UNIT' => '%',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'PCTG',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'we_mfk' => array('STEP' => 4,  'SHORTNAME' => 'we',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'MENUM',  'ANSWER' => 'FUNCTION', 'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'study_program_id' => array('SHORTNAME' => 'config',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'study_program',  'DEFAUT' => 0,  'MINIBOX' => true,  
				'WHERE' => "courses_config_template_id = §courses_config_template_id§ and course_id = §main_course_id§",
				'ANSMODULE' => 'sis',  'STEP' => 4,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25',),	

			'start_from' => array('STEP' => 4,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'ENUM', 'ANSWER' => "FUNCTION",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'min_rank_id' => [
					'SHOW' => true,
					'EDIT' => true,
					'DEFAULT' => 3,
					'TYPE' => 'ENUM',
					'ANSWER' =>
						'1,لم يقم به|2,ناقص|3,يوجد أخطاء|4,جيد|5,جيد جدا|6,ممتاز',
					/*
					'QSEARCH' => true,
					'FORMAT-INPUT' => 'hzmtoggle',
					'HZM-CSS' =>
						'6,excellent|5,verygood|4,good|3,accepted|2,poor|1,verypoor',
					'DEFAULT-CSS' => 'notevaluated',
					'ANSMODULE' => 'sis',*/
					'DISPLAY' => true,
					'STEP' => 4,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],				


		'conditionList' => array('STEP' => 5,  'SEARCH' => false,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'SIZE' => 32,  'MAXLENGTH' => 32,  'FORMAT' => 'retrieve',  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_condition',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_id',  
				'WHERE' => "", 
				 
				'MANDATORY' => false,  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),				



		'empl' => array('STEP' => 6,  'SEARCH' => false,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
				'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 32,  'MAXLENGTH' => 32,  
				'FORMAT' => 'retrieve',  'UTF8' => false,  'FGROUP' => 'empl',  
				'TYPE' => 'FK',  'ANSWER' => 'school_employee',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_id',  
				'WHERE' => "", 
				'RELATION' => 'unkn',  'MANDATORY' => false,  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'roomList' => array('STEP' => 7,  'SEARCH' => false,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'FGROUP' => 'roomList',  'SIZE' => 32,  'MAXLENGTH' => 32,  'FORMAT' => 'retrieve',  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'room',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_id',  
				'WHERE' => "", 
				 
				'RELATION' => 'unkn',  'MANDATORY' => false,  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'syear' => array('STEP' => 8,  'SEARCH' => false,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'FGROUP' => 'syear',  'SIZE' => 32,  'MAXLENGTH' => 32,  'FORMAT' => 'retrieve',  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_year',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_id',  
				'WHERE' => "", 
				 
				'RELATION' => 'unkn',  'MANDATORY' => false,  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'sdep' => array('STEP' => 9,  
				'TYPE' => 'FK',  'ANSWER' => 'sdepartment',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_id',  
				'WHERE' => "", 
				'HELP' => "SDEP_DEFINITION", 
				'FGROUP' => 'sdep',  'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  
				'FORMAT' => 'retrieve',  'EDIT' => false,  'NO-LABEL' => false, 'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'address' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 128,  'MAXLENGTH' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'city_id' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'city',  'ANSMODULE' => 'pag',  'AUTOCOMPLETE' => true,  
				'RELATION' => 'ManyToOne',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'quarter' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'pc' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 16,  'MAXLENGTH' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'mail_box' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'MAXLENGTH' => 96,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),


			'maps_location_url' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 255,  'MAXLENGTH' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_75',),

			'building_desc' => array('STEP' => 10,  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  
				'SIZE' => 'AREA', 'UTF8' => true,  'ROWS' => 4,  
				'TYPE' => 'TEXT',  'READONLY' => false,  'SEARCH-BY-ONE' => false,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),					



			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => true,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',  'SHOW' => true,    'DISPLAY' => true,  'STEP' => 99,  
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
				'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),

'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				




                ); 
        } 
?>