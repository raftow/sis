<?php 
        class SisCpcCourseProgramSchoolAfwStructure
        {
			public static function initInstance(&$obj)
			{
				if ($obj instanceof CpcCourseProgramSchool) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "";
					$obj->ORDER_BY_FIELDS = "course_program_id,school_id";

					$obj->editByStep = true;
					$obj->editNbSteps = 1;
					
					$obj->showRetrieveErrors = true;
					$obj->showQeditErrors = true;
					$obj->qedit_minibox = true;
					$obj->no_step_help = true;

					$obj->CAN_FORCE_UPDATE_DATE = true; // temporaire pour la migration

					
					$obj->UNIQUE_KEY = array('course_program_id','school_id');
				}
			}

			

                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'course_program_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_course_program',  'ANSMODULE' => 'sis',  'RELATION' => 'OneToMany',  
                'DEFAUT' => 0,  'READONLY' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true, 
                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  
                'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  'RELATION' => 'OneToMany',  				
				 'QSEARCH' => true,  'NO-COTE' => true,  				
				 'DEFAUT' => 0,  'READONLY' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),
            
            'ccps_code' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
                'SIZE' => 64,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
                'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
                'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
                'CSS' => 'width_pct_25',),

            'duration' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => 'يوم', 
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'duration_desc' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'h_duration' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => 'ساعة', 
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',), 
				
			'program_sa_code' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
                'SIZE' => 64,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,    
                'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
                'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
                'CSS' => 'width_pct_25',),				
						
			'level_sa_code' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  
                'SIZE' => 64,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,    
                'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
                'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
                'CSS' => 'width_pct_25',),				
				 
			
			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

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