<?php 
        class SisStudentUploadAfwStructure
        {
			public static function initInstance($obj)
			{
				if($obj instanceof StudentUpload)
        		{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "id";
					$obj->ORDER_BY_FIELDS = "id";
					$obj->UNIQUE_KEY = array("upload_name");
					
				}
			}
			
			
			
				public static $DB_STRUCTURE = array(
					'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
						'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'school_year_id' => [
							'IMPORTANT' => 'IN',
							'SEARCH' => true,
							'QSEARCH' => true,
							'SHOW' => true,
							'RETRIEVE' => true,
							'EDIT' => true,
							'QEDIT' => true,
							'UTF8' => false,
							'TYPE' => 'FK',
							'ANSWER' => 'school_year',
							'ANSMODULE' => 'sis',
							'SIZE' => 40,
							'DEFAUT' => 0,
							'SHORTNAME' => 'sy',
							'STEP' => 1,
							'DISPLAY' => true,
							'DISPLAY-UGROUPS' => '',
							'EDIT-UGROUPS' => '',
							'CSS' => 'width_pct_50',
							'READONLY' => true,
							'RELATION' => 'OneToMany',
						],						

					'csv' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
						'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
						'TYPE' => 'TEXT', 'DISPLAY' => true,  'STEP' => 1, 'SIZE' => 'AREA', 
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'MANDATORY' => true, 
						'CSS' => 'width_pct_100',),

					'upload_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
						'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
						'TYPE' => 'TEXT',  'SIZE' => '64', 'MAXLENGTH' => '64', 
						'DISPLAY' => true,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'MANDATORY' => true, 
						'CSS' => 'width_pct_100',),

					'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),

					'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
						'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
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
						'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),
				
				'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
						'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),					

								
						); 
				} 
?>