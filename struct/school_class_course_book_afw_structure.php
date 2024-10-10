<?php 
        class SisSchoolClassCourseBookAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if ($obj instanceof SchoolClassCourseBook) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "";
					$obj->ORDER_BY_FIELDS = "school_year_id,level_class_id,class_name,course_id, book_id";
					$obj->UNIQUE_KEY = array("school_year_id", "level_class_id", "class_name","course_id","book_id");
					$obj->editByStep = true;
            		$obj->editNbSteps = 2;
					$obj->is_detail_for["school_class_course"] = true;
					$obj->after_save_edit = [
						'class' => 'SchoolClassCourse',
						'formulaAttribute' => 'sclass_course',
						'currmod' => 'sis',
						'currstep' => 3,
					];

				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'school_year_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school_year',  'ANSMODULE' => 'sis',  
				'WHERE' => "school_id='§SUB_CONTEXT_ID§'", 'READONLY' => true,
				 'QSEARCH' => true,  'SIZE' => 40,  'DEFAUT' => 0,  'SHORTNAME' => 'sy',  'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

				'school_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.school_id',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 2,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),

				'levels_template_id' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'levels_template',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.school_id.levels_template_id',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 2,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),
				
				'year' => array('IMPORTANT' => 'IN',  'SEARCH' => false,  'SHOW' => false,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'SIZE' => 40,  
						'TYPE' => 'FK',  'ANSWER' => 'school',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,  'MINIBOX' => false,  'READONLY' => true,  
						'CATEGORY' => 'SHORTCUT',  'SHORTCUT' => 'school_year_id.year',  'CAN-BE-SETTED' => false,    'DISPLAY' => false,  'STEP' => 2,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_25',),

		'level_class_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => false,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'level_class',  'ANSMODULE' => 'sis',  'QSEARCH' => true,  
				'WHERE' => "school_level_id in (select slvl.id 
                                                                  from c0sis.school_level slvl 
                                                                      inner join c0sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", 
				 'SIZE' => 40,  'DEFAUT' => 0,  'DISPLAY' => true,  'STEP' => 2,  'READONLY' => true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

					'school_level_order' => [
								'IMPORTANT' => 'IN',
								'TYPE' => 'INT',
								'STEP' => 2,
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
								'STEP' => 2,
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
				'QSEARCH' => true,  'ANSMODULE' => 'sis',  'DISPLAY' => true,  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'READONLY' => true, 
				'CSS' => 'width_pct_25',),

						'sclass_course' => array(
								'TYPE' => 'FK',  'ANSWER' => 'school_class_course',  
								'CATEGORY' => 'FORMULA',  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'READONLY' => true,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  'STEP' => 2,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'RELATION' => 'OneToMany',
								'CSS' => 'width_pct_25',),

			'course_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  
				'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis',  'SIZE' => 40,  
				'DEFAUT' => 0,    'READONLY' => true,  'STEP' => 2, 'RELATION' => 'OneToMany', 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

					'courses_config_template_id' => array('STEP' => 99,  'SHORTNAME' => 'ctemplate',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
								'TYPE' => 'FK',  'ANSWER' => 'courses_config_template',  'ANSMODULE' => 'sis', 
								'CATEGORY' => "SHORTCUT", "SHORTCUT" => "school_year_id.school_id.courses_config_template_id",
								'RELATION' => 'OneToMany',  'READONLY' => true, 'DISPLAY' => true,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
								'CSS' => 'width_pct_25',),

			'book_id' => array('SHORTNAME' => 'config',  'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => false,  'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book', 
                'WHERE' => "",
                'DEFAUT' => 0,  'MINIBOX' => true,  
				'ANSMODULE' => 'sis',  'STEP' => 2,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'OneToMany',
				'CSS' => 'width_pct_25',),	

			'real_book_id' => [
					'CATEGORY' => 'FORMULA',
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => true,
					'TYPE' => 'FK',
					'ANSWER' => 'cpc_book',
					'ANSMODULE' => 'sis',
					'STEP' => 2,
				],

			
				'main_sens' => [
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => true,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
				],
		
				
		
				'main_part_id' => [
					'IMPORTANT' => 'IN',
					"NO-COTE"=>true,
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'QEDIT' => false,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'FK',
					'ANSWER' => 'cpc_book',
					'SIZE' => 40,
					'DEFAUT' => 0,
					'QSEARCH' => true,
					'WHERE' => 'book_type_id = 2 and parent_book_id = §real_book_id§',
					'DEPENDENCY' => 'main_book_id',
					'DEPENDENT_OFME' => array("main_chapter_id", ),
					'ANSMODULE' => 'sis',
					'DISPLAY' => true,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_50',
					'READONLY' => false,
				],
		
				
		
				'main_chapter_id' => [
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'QEDIT' => true,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'FK',
					'ANSWER' => 'cpc_book',
					'SIZE' => 40,
					'DEFAUT' => 0,
					'QSEARCH' => true,
					'WHERE' => "book_type_id = 3 and part_mfk like '%,§main_part_id§,%'",
					'DEPENDENCIES' => ['main_book_id','main_part_id'],
					'ANSMODULE' => 'sis',
					'DISPLAY' => true,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
					'READONLY' => false,
				],
		
				'main_page_num' => [
					'TYPE' => 'ENUM',
					'ANSWER' => "FUNCTION",
					'RETRIEVE' => true,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
				'mainwork_nb_pages' => [
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
		
				'mainwork_nb_lines' => [
					'TYPE' => 'INT',
					'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
					'RETRIEVE' => false,
					'SHOW' => true,
					'EDIT' => true,
					'READONLY' => false,
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_25',
				],
		
				'main_paragraph_num' => [
					'IMPORTANT' => 'IN',
					'SEARCH' => true,
					'SHOW' => true,
					'RETRIEVE' => true,
					'QEDIT' => true,
					'EDIT' => true,
					'READONLY' => false,
					'TYPE' => 'INT',
					'EDITOR' => ['src'=>'sis/tpl/select_ayat.php', 
								 'buttonTitleMethod'=>'paragraphShort',
								 'buttonTitleObjectAttribute'=>'main',
								 'paramsMethod'=>'getBookParams',
								 'full' => true,
								 'jsFunction'=>'select_book_aya',                   
								 
								],
					'STEP' => 1,
					'DISPLAY-UGROUPS' => '',
					'EDIT-UGROUPS' => '',
					'CSS' => 'width_pct_100',
					
				],

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

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'QEDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 99,  
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

'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true,  'QEDIT' => false, 
				'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),				





                        
                ); 
        } 
?>