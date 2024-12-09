<?php 
        class SisSchoolEmployeeAfwStructure
        {
            public static function initInstance(&$obj)
			{
				if ($obj instanceof SchoolEmployee) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->FORMULA_DISPLAY_FIELD  = self::$DB_STRUCTURE["full_name"]["FIELD-FORMULA"];
					$obj->editByStep = true;
					$obj->editNbSteps = 6;
					$obj->READONLY = true;
					$obj->UNIQUE_KEY = ['school_id', 'employee_id'];
					
					$obj->ORDER_BY_FIELDS = "school_id, sdepartment_id, school_job_mfk";
					$obj->after_save_edit = array("class"=>'School',"attribute"=>'school_id', "currmod"=>'sis',"currstep"=>6);
				}
			}    
			
			public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'gender_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'genre',  'ANSMODULE' => 'ums',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'REQUIRED' => true,
				'CSS' => 'width_pct_25',),

			'firstname' => array('IMPORTANT' => 'high',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1, 'REQUIRED' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'f_firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => false,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'g_f_firstname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => false,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'lastname' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1, 'REQUIRED' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

							'full_name' => array('STEP' => 999,  
								'TYPE' => 'TEXT',  
								'CATEGORY' => 'FORMULA',  'RETRIEVE' => true,  'SHOW' => true,  'QSEARCH' => true,  'SEARCH' => true,  'UTF8' => true,  
								'FIELD-FORMULA' => "concat(IF(ISNULL(firstname), '', firstname) , ' ' , IF(ISNULL(f_firstname), '', f_firstname) , ' ' , IF(ISNULL(lastname), '', lastname))", 
								'SEARCH-BY-ONE' => true,  'DISPLAY' => true,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
								),

			'birth_date' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 10,  'FORMAT' => 'CONVERT_NASRANI',  'UTF8' => false,  
				'TYPE' => 'DATE',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'country_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'country',  'ANSMODULE' => 'ums',  'DEFAUT' => 183,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'REQUIRED' => true,
				'CSS' => 'width_pct_25',),

			'address' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  /*'TITLE_AFTER' => ' المملكة العربية السعودية', */ 'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'city_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => true,  
				'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'city',  'ANSMODULE' => 'ums',  'DEFAULT' => 301,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'mobile' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  'FORMAT' => 'SA-MOBILE',  'UTF8' => false,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'REQUIRED' => true, 
				'CSS' => 'width_pct_25',),

			'phone' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 16,  'UTF8' => false,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'email' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => false,  'SIZE' => 64,  'FORMAT' => 'EMAIL',  'UTF8' => false,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  'REQUIRED' => true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'READONLY' => '::isFilled', 'EDIT_IF_EMPTY'=>true,
				'CSS' => 'width_pct_75',),

			'employee_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'QEDIT' => false,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'employee',  'ANSMODULE' => 'hrm',  'READONLY' => true,  
				'SIZE' => 40,  'DEFAUT' => 0,  'STEP' => 2,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'auser_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'QEDIT' => false,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'READONLY' => true,  
				'SIZE' => 40,  'DEFAUT' => 0,  'STEP' => 2,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true, 'QSEARCH' => true, 'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'SIZE' => 40,  'DEFAUT' => 0,  'NOT-SAVE' => true,  
				'SHORTNAME' => 'school',  'STEP' => 2,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  'RELATION' => 'OneToMany',
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'READONLY' => true, 
				'CSS' => 'width_pct_25',),

			'school_job_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true, 
				'UTF8' => false,   'DISPLAY' => true,  'STEP' => 2,  'DEFAULT' => ',7,',
				'TYPE' => 'MFK',  'ANSWER' => 'school_job',  'MFK-SHOW-SEPARATOR' => '، ',  'ANSMODULE' => 'sis',   
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'job_description' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
				'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 2,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'sdepartment_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'sdepartment',  
				'WHERE' => "school_id = §school_id§", 
				 'SIZE' => 40,  'DEFAUT' => 0,  'NOT-SAVE' => true,  'STEP' => 2,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_orgunit_id' => array(
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  'DEFAUT' => 0,  'STEP' => 2,    'DISPLAY' => '',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'sdepartment_orgunit_id' => array(
				'TYPE' => 'FK',  'ANSWER' => 'orgunit',  'ANSMODULE' => 'hrm',  'DEFAUT' => 0,  'STEP' => 2,    'DISPLAY' => '',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'wday_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  
				'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'MFK',  'ANSWER' => 'wday',  'MFK-SHOW-SEPARATOR' => '، ',  
				'STEP' => 2,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'hrm_ums' => array('CATEGORY' => 'FORMULA',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => false,  'SIZE' => 32,  'UTF8' => false,  
				'TYPE' => 'TEXT',  'MANDATORY' => true,  'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'READONLY' => true,
				'CSS' => 'width_pct_12',),

	

			'course_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
					'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  
					'UTF8' => false,  
					'TYPE' => 'MFK',  'ANSWER' => 'course',  'MFK-SHOW-SEPARATOR' => '، ',  
					'STEP' => 3,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),

		'schoolClassCourseList' => array('IMPORTANT' => 'IN',  'SHOW' => true,  
				'CATEGORY' => 'FORMULA',  'FORMAT' => 'retrieve',  'READONLY' => true,  
				'TYPE' => 'MFK',  'ANSWER' => 'school_class_course',  'ANSMODULE' => 'sis',  
				'NO-SAVE' => true,  'COLSPAN' => 3,  'NEW-TR' => true,  'VIEW-ICON' => true,  
				'STEP' => 4,  'EDIT' => true, 'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'profCalendarItemList' => array('IMPORTANT' => 'IN',  'SHOW' => true,  
				'CATEGORY' => 'FORMULA',  'FORMAT' => 'retrieve',  'READONLY' => true,  
				'TYPE' => 'MFK',  'ANSWER' => 'prof_calendar_item',  'ANSMODULE' => 'sis',  'NO-SAVE' => true,  
				'COLSPAN' => 3,  'NEW-TR' => true,  'NO-LABEL' => false,  
				'STEP' => 5, 'EDIT' => true, 'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'attendanceList' => array('IMPORTANT' => 'IN',  'SHOW' => true,  
				'CATEGORY' => 'FORMULA',  'FORMAT' => 'retrieve',  'READONLY' => true,  
				'TYPE' => 'MFK',  'ANSWER' => 'course_session',  'ANSMODULE' => 'sis',  
				'NO-SAVE' => true,  'COLSPAN' => 3,  'NEW-TR' => true,  'NO-LABEL' => false,  
				'STEP' => 6, 'EDIT' => true, 'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',  'NEW-TR' => true,    'DISPLAY' => '',  'STEP' => 99,  
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
				'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				



                        
                ); 
        } 
?>