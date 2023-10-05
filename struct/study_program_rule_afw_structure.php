<?php 
        class SisStudyProgramRuleAfwStructure
        {
			public static function initInstance($obj)
			{
				if($obj instanceof StudyProgramRule)
        		{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 2;
					$obj->DISPLAY_FIELD = "";
					$obj->ORDER_BY_FIELDS = "study_program_id, program_order";

					$obj->editByStep = true;
					$obj->editNbSteps = 2;
					
					$obj->showRetrieveErrors = true;
					$obj->showQeditErrors = true;
					$obj->qedit_minibox = true;
					$obj->no_step_help = true;

					$obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration

					
					$obj->UNIQUE_KEY = array('study_program_id','program_order');
			
				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK', 'SEARCH' => true, 'READONLY' => true,  'TEXT-SEARCHABLE-SEPARATED'=>true,  
				'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			
			
			'study_program_id' => array('SHORTNAME' => 'config',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'study_program',  'DEFAUT' => 0,  'MINIBOX' => true,  
				'ANSMODULE' => 'sis',  'STEP' => 2,  'READONLY' => true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25',),

			'program_order' => [
				'TYPE' => 'INT',
				'RETRIEVE' => true,
				'SHOW' => true,
				'EDIT' => true,
				'QEDIT' => true,
				'READONLY' => true,
				'STEP' => 2,
				'DISPLAY-UGROUPS' => '',
				'EDIT-UGROUPS' => '',
				'CSS' => 'width_pct_25',
			],	

			'condition' => [
				'IMPORTANT' => 'IN',
				'SEARCH' => true,
				'SHOW' => true,
				'RETRIEVE' => true,
				'QEDIT' => false,
				'EDIT' => true,
				'STEP' => 2,
				'READONLY' => true,
				'TYPE' => 'TEXT',
				'SIZE' => 48,
				'CATEGORY' => 'FORMULA',
				'CSS' => 'width_pct_25',
			],


			'dcategory' => [
				'IMPORTANT' => 'IN',
				'SEARCH' => true,
				'SHOW' => true,
				'RETRIEVE' => false,
				'QEDIT' => false,
				'EDIT' => true,
				'STEP' => 99,
				'READONLY' => true,
				'TYPE' => 'TEXT',
				'SIZE' => 48,
				'CATEGORY' => 'FORMULA',
				'CSS' => 'width_pct_25',
			],

			
			'new_nb_pages' => [
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,					
					'SHOW' => true,
					'EDIT' => true,
					'QEDIT' => true,
					'READONLY' => true,
					'STEP' => 2,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_33',
				],
		
			'new_nb_lines' => [
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'QEDIT' => true,
					'READONLY' => true,
					'STEP' => 2,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_33',
				],	
							
			'total_nb_pages' => [
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => true,
					'QEDIT' => true,
					'STEP' => 2,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_33',
				],

				
		
			'mainwork_nb_parts' => [
					'FGROUP' => 'mainwork',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
			'mainwork_nb_pages' => [
					'FGROUP' => 'mainwork',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],
		
			'mainwork_nb_lines' => [
					'FGROUP' => 'mainwork',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],

			'mainwork' => [
					'FGROUP' => 'mainwork',
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => false,
					'QEDIT' => false,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'TEXT',
					'SIZE' => 48,
					'CATEGORY' => 'FORMULA',
					'STEP' => 99,
					'CSS' => 'width_pct_75',
				],
	
				
			'homework_nb_parts' => [
					'FGROUP' => 'homework',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
			'homework_nb_pages' => [
					'FGROUP' => 'homework',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],
		
			'homework_nb_lines' => [
					'FGROUP' => 'homework',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],
		
			'homework' => [
					'FGROUP' => 'homework',
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'QEDIT' => false,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'TEXT',
					'SIZE' => 48,
					'CATEGORY' => 'FORMULA',
					'STEP' => 99,
					'CSS' => 'width_pct_75',
				],
	
			'homework2_nb_parts' => [
					'FGROUP' => 'homework2',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
			'homework2_nb_pages' => [
					'FGROUP' => 'homework2',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],
		
			'homework2_nb_lines' => [
					'FGROUP' => 'homework2',
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_12',
				],

				
			'homework2' => [
					'FGROUP' => 'homework2',
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'QEDIT' => false,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'TEXT',
					'SIZE' => 48,
					'CATEGORY' => 'FORMULA',
					'STEP' => 99,
					'CSS' => 'width_pct_75',
				],
				


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


		'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
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
				'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),
		
		'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				
		
		

                        
                ); 
        } 
?>