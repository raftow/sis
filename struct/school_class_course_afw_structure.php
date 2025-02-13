<?php 
        class SisSchoolClassCourseAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if ($obj instanceof SchoolClassCourse) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "";
					$obj->ORDER_BY_FIELDS = "school_year_id,level_class_id,class_name,course_id";
					$obj->UNIQUE_KEY = array("school_year_id", "level_class_id", "class_name","course_id");
					$obj->editByStep = true;
            		$obj->editNbSteps = 4;
					$obj->is_detail_for["school_class"] = true;
					$obj->after_save_edit = [
						'class' => 'SchoolClass',
						'formulaAttribute' => 'sclass',
						'currmod' => 'sis',
						'currstep' => 4,
					];

				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'school_year_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_year',  'ANSMODULE' => 'sis',  
				'WHERE' => "school_id='§SUB_CONTEXT_ID§'", 'READONLY' => true,
				 'QSEARCH' => true,  'SIZE' => 40,  'DEFAUT' => 0,  'SHORTNAME' => 'sy',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

				'school_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.school_id',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),

				'levels_template_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'levels_template',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.school_id.levels_template_id',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),
				
				'year' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.year',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 1,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),

		'level_class_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'level_class',  'ANSMODULE' => 'sis',  'QSEARCH' => true,  
				'WHERE' => "school_level_id in (select slvl.id 
                                                                  from §DBPREFIX§sis.school_level slvl 
                                                                      inner join §DBPREFIX§sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", 
				 'SIZE' => 40,  'DEFAUT' => 0,  'DISPLAY' => true,  'STEP' => 1,  'READONLY' => true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

					'school_level_order' => [
								'IMPORTANT' => 'IN',
								'TYPE' => 'INT',
								'STEP' => 1,
								'DISPLAY-UGROUPS' => '',
								'EDIT-UGROUPS' => '',
								'READONLY' => true,
								'CSS' => 'width_pct_25',
								'CATEGORY' => 'SHORTCUT',
								'SHORTCUT' => 'level_class_id.school_level_id.school_level_order',
							],
		
					'level_class_order' => [
								'IMPORTANT' => 'IN',
								'TYPE' => 'INT',
								'STEP' => 1,
								'DISPLAY-UGROUPS' => '',
								'EDIT-UGROUPS' => '',
								'READONLY' => true,
								'CSS' => 'width_pct_25',
								'CATEGORY' => 'SHORTCUT',
								'SHORTCUT' => 'level_class_id.level_class_order',
							],

			'class_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 1,  'UTF8' => true,  
				'TYPE' => 'TEXT',  
				// 'ENUM_ALPHA' => true,  'ANSWER' => 'a,أ|b,ب|c,ت|d,ث|e,ج|f,ح|g,خ',  
				'QSEARCH' => true,  'ANSMODULE' => 'sis',  'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'READONLY' => true, 
				'CSS' => 'width_pct_25',),

						'sclass' => array(
								'TYPE' => 'FK',  'ANSWER' => 'school_class',  
								'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => false,  'QEDIT' => false,  'READONLY' => true,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  'STEP' => 2,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'RELATION' => 'OneToMany',
								'CSS' => 'width_pct_25',),

			'course_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  
				'UTF8' => false, 'SHORTNAME' => 'course', 
				'TYPE' => 'FK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis',  'SIZE' => 40,  
				'DEFAUT' => 0,    'READONLY' => true,  'STEP' => 1, 'RELATION' => 'OneToMany', 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'MANDATORY'=>true,
				'CSS' => 'width_pct_25',),

					'courses_config_template_id' => array('STEP' => 99,  'SHORTNAME' => 'ctemplate',  
								'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
								'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
								'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
								'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'ANSMODULE' => 'sis', 
								'CATEGORY' => "SHORTCUT", "SHORTCUT" => "school_year_id.school_id.courses_config_template_id",
								'RELATION' => 'OneToMany',  'READONLY' => true, 'DISPLAY' => true,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
								'CSS' => 'width_pct_25',),

			'study_program_id' => array('SHORTNAME' => 'config',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'study_program', 
                'WHERE' => "courses_config_template_id = §courses_config_template_id§ and course_id = §course_id§",
                'DEFAUT' => 0,  'MINIBOX' => true, 'MANDATORY'=>true,
				'ANSMODULE' => 'sis',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25',),	

			'prof_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_employee',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  
				'WHERE' => "school_id = §school_id§ and school_job_mfk like '%,7,%' and course_mfk like concat('%,',§course_id§,',%')", 
				   'DISPLAY' => true,  'STEP' => 1, 'MANDATORY'=>true, 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			
			'min_rank_id' => [
					'SHOW' => true,
					'EDIT' => true,
					'QEDIT' => true,
					'RETRIEVE' => true,
					'DEFAULT' => 3,
					'TYPE' => 'ENUM',
					'ANSWER' =>
					'1,لم ينجر|2,&#9734;|3,&#9734;&#9734;|4,&#9734;&#9734;&#9734;|5,&#9734;&#9734;&#9734;&#9734;|6,&#9734;&#9734;&#9734;&#9734;&#9734;',
					/*
					'QSEARCH' => true,
					'FORMAT-INPUT' => 'hzmtoggle',
					'HZM-CSS' =>
						'6,excellent|5,verygood|4,good|3,accepted|2,poor|1,verypoor',
					'DEFAULT-CSS' => 'notevaluated',
					'ANSMODULE' => 'sis',*/
					'DISPLAY' => true,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],	

			'active' => array('SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),
		

		'scheds' => array(
				'TYPE' => 'FK',  'ANSWER' => 'course_sched_item',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => '',  
				'WHERE' => "school_year_id=§school_year_id§ 
				             and level_class_id=§level_class_id§ 
							 and class_name=_utf8§class_name§ 
							 and course_id = §course_id§", 
				'DO-NOT-RETRIEVE-COLS' => [
						0 => 'school_year_id',
						1 => 'level_class_id',
						2 => 'class_name',
						3 => 'course_id',
						4 => 'homework_book_id',
						5 => 'homework2_book_id',
					],							 
				 'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  'FORMAT' => 'retrieve',  'EDIT' => false,
				   'NO-LABEL' => false,  'BUTTONS' => false,    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'week_sess_nb' => array(
				'TYPE' => 'INT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'QEDIT' => true,  'READONLY' => true,    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'scheds_nb' => array(
				'TYPE' => 'INT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  
				'READONLY' => true,    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'followups_nb' => array(
					'TYPE' => 'INT',  
					'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  
					'READONLY' => true,    'DISPLAY' => true,  'STEP' => 2,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_25',),
	
				
					
		'courses' => array(
					'TYPE' => 'FK',  'ANSWER' => 'student_file_course',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '', 	
					'WHERE' => "school_id=§school_id§ 
					        and year=§year§ 
							and levels_template_id=§levels_template_id§ 
							and school_level_order=§school_level_order§ 
							and level_class_order=§level_class_order§ 
							and class_name=_utf8§class_name§ 
							and course_id = §course_id§", 
					 'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false, 'EDIT-ICON' => false, 'FORMAT' => 'retrieve',  'EDIT' => false,
					   'NO-LABEL' => true,  'BUTTONS' => false,    'DISPLAY' => true,  'STEP' => 4,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),		

		'schoolClassCourseBookList' => [
						'TYPE' => 'FK',
						'ANSWER' => 'school_class_course_book',
						'ANSMODULE' => 'sis',
						
						'CATEGORY' => 'ITEMS',
						'ITEM' => '',
						'WHERE' =>
							'school_year_id=§school_year_id§ 
							     and level_class_id=§level_class_id§ 
								 and class_name=_utf8§class_name§
								 and course_id = §course_id§',
						'SHOW' => true,
						'ICONS' => true,
						'DELETE-ICON' => false,
						'FORMAT' => 'retrieve',
						'EDIT' => true,
						'QEDIT' => false,
						'DO-NOT-RETRIEVE-COLS' => [
							0 => 'school_year_id',
							1 => 'level_class_id',
							2 => 'class_name',
							3 => 'course_id',
						],
						'NO-LABEL' => true,
						'BUTTONS' => true,
						'FGROUP' => 'schoolClassCourseBookList',
						'STEP' => 3,
						'OPTIM' => false,
						'READONLY' => true,
						'DISPLAY-UGROUPS' => '',
						'EDIT-UGROUPS' => '',
						'CSS' => 'width_pct_100',
					],					

		'is_ok' => array(
				'TYPE' => 'TEXT',  
				'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => false,  
				'READONLY' => true,  'NO-ERROR-CHECK' => true,    'DISPLAY' => true,  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  
				'TYPE' => 'GDAT',    'DISPLAY' => '',  'STEP' => 99,  
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
				'TYPE' => 'INT', /*stepnum-not-the-object*/ 'FGROUP' => 'tech_fields'),

'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true,  'QEDIT' => false, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				





                        
                ); 
        } 
?>