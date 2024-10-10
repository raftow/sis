<?php 
        class SisCoursesConfigItemAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if ($obj instanceof CoursesConfigItem) {
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "";
					$obj->ORDER_BY_FIELDS = "courses_config_template_id,level_class_id,course_id";
					$obj->UNIQUE_KEY = explode(',', $obj->ORDER_BY_FIELDS);
					// $title = ScandidateTranslator::translateAttribute("scandidate.single","ar");
					$obj->after_save_edit = [
						'class' => 'CoursesConfigTemplate',
						'attribute' => 'courses_config_template_id',
						'currmod' => 'sis',
						'currstep' => 2,
					];

					$obj->editByStep = true;
					$obj->editNbSteps = 2;
				}
			}

                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'courses_config_template_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'SIZE' => 40,  'DEFAUT' => 0,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'RELATION' => 'OneToMany', 'READONLY' => true,
				'CSS' => 'width_pct_25',),

			'course_id' => array('STEP' => 1, 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'course',  'SIZE' => 40,  'DEFAUT' => 0,  'NO-COTE' => true,  
				'WHERE' => "id in (select crs.id from c0sis.course crs 
                                                          inner join c0sis.courses_template crt on crt.course_mfk like concat('%,',crs.id,',%')
                                                          inner join c0sis.courses_config_template cct on cct.courses_template_id = crt.id and cct.id = §courses_config_template_id§)", 
				 'ANSMODULE' => 'sis',    'DISPLAY' => true,  'READONLY' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'level_class_id' => array('IMPORTANT' => 'high',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'level_class',  'SIZE' => 40,  'DEFAUT' => 0,  'NO-COTE' => true,  
				'WHERE' => "id in (select lvl.id from c0sis.level_class lvl
                                                          inner join c0sis.school_level slv on lvl.school_level_id = slv.id 
                                                          inner join c0sis.levels_template lvt on slv.levels_template_id = lvt.id
                                                          inner join c0sis.courses_config_template cct on cct.levels_template_id = lvt.id and cct.id = §courses_config_template_id§)", 
				 'ANSMODULE' => 'sis',    'DISPLAY' => true,  'STEP' => 1, 'READONLY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'session_nb' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,    'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'coef' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,    'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'course_program_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  
				'QEDIT' => false,  'SIZE' => 40,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_course_program',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'mainwork_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
				 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'mainwork_book_expiring_hdate' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 10,    'UTF8' => false,  
				'TYPE' => 'DATE',  'FORMAT' => 'CONVERT_NASRANI',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'homework_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
				 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'homework_book_expiring_hdate' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  
				'QEDIT' => false,  'SIZE' => 10,    'UTF8' => false,  
				'TYPE' => 'DATE',  'FORMAT' => 'CONVERT_NASRANI',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'homework2_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  
				'QEDIT' => true,  'SIZE' => 40,    'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
				 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),
				
			'homework2_book_expiring_hdate' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  
				'QEDIT' => false,  'SIZE' => 10,    'UTF8' => false,  
				'TYPE' => 'DATE',  'FORMAT' => 'CONVERT_NASRANI',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			
			'studyProgramList' => array('TYPE' => 'FK', 'ANSWER' => 'study_program', 'ANSMODULE' => 'sis', 
				'CATEGORY' => 'ITEMS', 'ITEM' => '', 
				'WHERE'=>'courses_config_template_id = §courses_config_template_id§ 
				      and course_id = §course_id§ 
					  and level_class_id = §level_class_id§', 
				'HIDE_COLS' => array(), 'STEP' => 2,
				'SHOW' => true, 'FORMAT'=>'retrieve', 'EDIT' => false, 'QEDIT' => false, 'READONLY' => true, 
				'ICONS'=>true, 'DELETE-ICON'=>true, 'BUTTONS'=>true, "NO-LABEL"=>false),				

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false, 
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false, 
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false, 
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false, 'QEDIT' => false, 
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 99,  
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
		
		'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', 
				"SHOW-ADMIN" => true, 'QEDIT' => false,
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),		

                        
                ); 
        } 
?>