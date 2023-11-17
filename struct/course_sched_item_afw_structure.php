<?php 
        class SisCourseSchedItemAfwStructure
        {
            public static function initInstance(&$obj)
			{
				if ($obj instanceof CourseSchedItem) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					//$obj->VIEW = true;
					$obj->READONLY = true;
					$obj->DISPLAY_FIELD = "class_name";
					$obj->ORDER_BY_FIELDS = "school_year_id,level_class_id,class_name,wday_id,session_order";
            		$obj->UNIQUE_KEY = explode(',', $obj->ORDER_BY_FIELDS);
					$obj->commonFields=true;
					$after_save_currstep = $obj->getVal("wday_id")+5;
					$obj->after_save_edit = array("class"=>'SchoolClass',"formulaAttribute"=>'school_class_id', "currmod"=>'sis',"currstep"=>$after_save_currstep);
					
				}
			}    
			
			public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_year_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_year',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'READONLY' => true,  'SHORTNAME' => 'sy',  
				'WHERE' => "school_id='§SUB_CONTEXT_ID§'", 
				 'QSEARCH' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

				'courses_config_template_id' => array('SHORTNAME' => 'config_template',  
					'CATEGORY' => "SHORTCUT", 'SHORTCUT' => "school_year_id.school.courses_config_template_id",
					'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'ANSMODULE' => 'sis',  
					'RELATION' => 'ManyToOne',  'READONLY' => true,
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),

			'level_class_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true, 
			    'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false, 'NO-COTE' => true,   
				'TYPE' => 'FK',  'ANSWER' => 'level_class',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'READONLY' => true,  
				'WHERE-SEARCH' => "school_level_id in (select slvl.id 
                                                                  from c0sis.school_level slvl 
                                                                      inner join c0sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", 
				 'QSEARCH' => true,  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

				'class_name' => [
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'SIZE' => 1,
					'QEDIT' => true,
					'EDIT' => true,
					'TYPE' => 'TEXT',
					'SIZE' => 32,
					'UTF8' => true,
					'READONLY' => true,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],

				'school_class_id' => [
                    'IMPORTANT' => 'IN',
                    'SEARCH' => true,
                    'SHOW' => true,
                    'RETRIEVE' => false,
                    'EDIT' => true,
                    'QEDIT' => false,
                    'SIZE' => 40,
                    'UTF8' => false,
                    'TYPE' => 'FK',
                    'SHORTNAME' => 'sclass',
                    'ANSWER' => 'school_class',
                    'ANSMODULE' => 'sis',
                    'CATEGORY' => 'FORMULA',
                    'RELATION' => 'OneToMany',
                    'DEFAUT' => 0,
                    'DISPLAY' => true,
                    'STEP' => 1,
                    'DISPLAY-UGROUPS' => '',
                    'EDIT-UGROUPS' => '',
                    'READONLY' => true,
                    'CSS' => 'width_pct_25',
                ],

			'wday_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'wday',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'READONLY' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'session_order' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 3,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  
				'TYPE' => 'INT',  'READONLY' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'session_start_time' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 5,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'READONLY' => true,  
				'TYPE' => 'TIME', 'ANSWER_METHOD'=>'getPrayerTimeList', 'FORMAT' => 'OBJECT',   
				'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'session_end_time' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 5,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'READONLY' => true,  
				'TYPE' => 'TIME', 'ANSWER_METHOD'=>'getAfterPrayerTimeList', 'FORMAT' => 'OBJECT',    
				'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'course_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'NO-ERROR-CHECK' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'WHERE'  => 'id=6 or id in (select cci.course_id from c0sis.courses_config_item cci 
				                       where cci.courses_config_template_id = §courses_config_template_id§ 
									     and (cci.level_class_id = 0 or cci.level_class_id = §level_class_id§) 
										 and cci.session_nb > 0)',
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'NO-COTE' => true,
				'CSS' => 'width_pct_25',),

			'prof_id' => array('IMPORTANT' => 'IN',  'SHOW' => true,  'EDIT' => true,  'RETRIEVE' => true,  'QEDIT' =>true,  'SIZE' => 40,  
					'TYPE' => 'FK',  'ANSWER' => 'school_employee',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  
					'STEP' => 1, 'CATEGORY' => 'FORMULA',
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),
			
			'mainwork_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
					'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
					'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
					 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),
	
			'homework_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
					'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
					'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
					 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),
	
			'homework2_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
					'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
					'WHERE' => "book_type_id=1 and course_mfk like '%,§course_id§,%' and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')", 
					 'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),				

			'is_ok' => array(
						'TYPE' => 'TEXT',  
						'CATEGORY' => 'FORMULA',  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'READONLY' => true,  'NO-ERROR-CHECK' => true,    'DISPLAY' => true,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
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