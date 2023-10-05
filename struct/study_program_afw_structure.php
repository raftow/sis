<?php 
        class SisStudyProgramAfwStructure
        {
			public static function initInstance($obj)
			{
				if($obj instanceof StudyProgram)
        		{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 2;
					$obj->DISPLAY_FIELD = "study_program_name_ar";
					$obj->ORDER_BY_FIELDS = "courses_config_template_id,level_class_id,course_id,study_program_name_ar";

					$obj->editByStep = true;
					$obj->editNbSteps = 4;
					
					$obj->showRetrieveErrors = true;
					$obj->showQeditErrors = true;
					$obj->qedit_minibox = true;
					$obj->no_step_help = true;

					$obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration

					
					$obj->UNIQUE_KEY = array('courses_config_template_id','level_class_id','course_id','study_program_name_ar');
			
				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('FGROUP' => 'definition', 'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => false,  
				'TYPE' => 'PK', 'SEARCH' => true, 'QSEARCH' => true,  'TEXT-SEARCHABLE-SEPARATED'=>true,  'FGROUP' => 'personal',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			
			
			'courses_config_template_id' => array('FGROUP' => 'definition', 'SHORTNAME' => 'config',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'DEFAUT' => 0,  'MINIBOX' => true,  
				'ANSMODULE' => 'sis',  'STEP' => 1,  'READONLY' => true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25',),

			'level_class_id' => array('FGROUP' => 'definition', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'level_class',  'SIZE' => 40,  'DEFAUT' => 0,  'NO-COTE' => true,  
				'WHERE' => "id in (select lvl.id from c0sis.level_class lvl
                                                          inner join c0sis.school_level slv on lvl.school_level_id = slv.id 
                                                          inner join c0sis.levels_template lvt on slv.levels_template_id = lvt.id
                                                          inner join c0sis.courses_config_template cct on cct.levels_template_id = lvt.id and cct.id = §courses_config_template_id§)", 
				 'ANSMODULE' => 'sis',    'DISPLAY' => true,  'STEP' => 1, 'READONLY' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'course_id' => [
				'FGROUP' => 'definition',
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => false,
					'EDIT' => true,
					'QEDIT' => true,
					'UTF8' => false,
					'TYPE' => 'FK',
					'ANSWER' => 'course',
					'SHORTNAME' => 'course',
					'SIZE' => 40,
					'DEFAUT' => 0,
					'ANSMODULE' => 'sis',
					'DISPLAY' => true,
					'READONLY' => true,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'RELATION' => 'OneToMany',
					'CSS' => 'width_pct_50',
				],	

			'study_program_name_ar' => array('FGROUP' => 'definition', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 30,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'MINIBOX' => true,    'DISPLAY' => true,  'STEP' => 1, 'QSEARCH' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY' => true, 'EXCEL' => true,
				'CSS' => 'width_pct_50',),				

			'study_program_type' => [
					'FGROUP' => 'definition',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'DISPLAY' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],	
				

			'a_pct' => [
					'FGROUP' => 'definition',
					'TYPE' => 'INT',
					'UNIT' => 'مرة',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:100:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'DISPLAY' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],	
				
			'b_pct' => [
					'FGROUP' => 'definition',
					'TYPE' => 'INT',
					'UNIT' => 'حصة',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'DISPLAY' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],	

			'homework_round_type' => [
					'FGROUP' => 'definition',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'DISPLAY' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],					

			'c_pct' => [
					'FGROUP' => 'definition',
					'TYPE' => 'INT',
					'UNIT' => 'يوم',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'DISPLAY' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],	

			'homework2_round_type' => [
					'FGROUP' => 'definition',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'DISPLAY' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],				
				
			'mainwork_sens' => [
					'FGROUP' => 'mainwork',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],
		
			'mainwork_stop' => [
					'FGROUP' => 'mainwork',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
				
			'homework_sens' => [
					'FGROUP' => 'homework',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],
		
			'homework_stop' => [
					'FGROUP' => 'homework',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
			'homework2_sens' => [
					'FGROUP' => 'homework2',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],
		
			'homework2_stop' => [
					'FGROUP' => 'homework2',
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],				
			

			
			'studyProgramRuleMahfoodList' => array('TYPE' => 'FK', 'ANSWER' => 'study_program_rule', 'ANSMODULE' => 'sis', 
				'CATEGORY' => 'ITEMS', 'ITEM' => 'study_program_id', 'WHERE'=>'total_nb_pages > 0 and ((new_nb_pages = 0 or new_nb_pages is null) and (new_nb_lines = 0 or new_nb_lines is null))', 'HIDE_COLS' => array('homework2'),
				'ICONS'=>true, 'DELETE-ICON'=>true, 'BUTTONS'=>true, "NO-LABEL"=>false,	'STEP' => 2,			
				'SHOW' => true, 'FORMAT'=>'retrieve', 'EDIT' => false, 'READONLY' => true, ),	
		
			'studyProgramRuleJadidList' => array('TYPE' => 'FK', 'ANSWER' => 'study_program_rule', 'ANSMODULE' => 'sis', 
				'CATEGORY' => 'ITEMS', 'ITEM' => 'study_program_id', 'WHERE'=>'(total_nb_pages = 0 or total_nb_pages is null) and (new_nb_pages > 0 or new_nb_lines > 0)', 'HIDE_COLS' => array('homework'),
				'ICONS'=>true, 'DELETE-ICON'=>true, 'BUTTONS'=>true, "NO-LABEL"=>false,	'STEP' => 2,			
				'SHOW' => true, 'FORMAT'=>'retrieve', 'EDIT' => false, 'READONLY' => true, ),
				
			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

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
				'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),
		
		'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				
		
		

                        
                ); 
        } 
?>