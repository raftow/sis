<?php 
        class SisSchoolYearAfwStructure
        {
			public static function initInstance(&$obj)
			{
				if ($obj instanceof SchoolYear) 
				{
					$obj->IS_LOOKUP = true;
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					// $obj->DISPLAY_FIELD = "year";
					$obj->ORDER_BY_FIELDS = 'school_id,year';
					$obj->UNIQUE_KEY = ['school_id', 'year'];
					$obj->editByStep = true;
					$obj->editNbSteps = 9;
				}
			}

            public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => false,  
				'TYPE' => 'PK',  'STEP' => 1,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'school_id' => array('FGROUP' => 'dates',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'school',  'SIZE' => 40,  'DEFAUT' => 0,  'READONLY' => true,  'SHORTNAME' => 'school',  'STEP' => 1,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 'RELATION' => 'OneToMany', 
				'CSS' => 'width_pct_50',),

			'year' => array('IMPORTANT' => 'high',  'FGROUP' => 'dates', 'SHOW' => false, 'EDIT' => false, 
				'TYPE' => 'INT',  
				// 'ANSWER' => 'FUNCTION',  
				'READONLY' => true,  'STEP' => 1,  'ANSMODULE' => 'sis',    
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

						'school_year_name' => array('IMPORTANT' => 'high', 'FGROUP' => 'dates', 'CATEGORY' => 'FORMULA',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE-AR' => true,  
							'EDIT' => true,  'READONLY' => true,  'SIZE' => 32,  'UTF8' => true,  
							'TYPE' => 'TEXT',  'STEP' => 1,    'DISPLAY' => true,  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_50',),				

			'semester' => array('IMPORTANT' => 'high', 'FGROUP' => 'dates', 'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',  'READONLY' => true,  'STEP' => 1,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'DEFAUT' => '0', 
				'CSS' => 'width_pct_50',),

			'school_year_type' => array('FGROUP' => 'dates', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'ENUM',  'ANSWER' => 'FUNCTION',  'READONLY' => true,  'STEP' => 1,  'ANSMODULE' => 'sis',    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),									

			'school_year_start_hdate' => array('FGROUP' => 'dates', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'DATE',  'STEP' => 1,    'DISPLAY' => true, 'MANDATORY' => true, 'FORMAT' => 'CONVERT_NASRANI_2LINES',
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'school_year_end_hdate' => array('FGROUP' => 'dates', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'UTF8' => false,  
				'TYPE' => 'DATE',  'STEP' => 1,    'DISPLAY' => true, 'MANDATORY' => true, 'FORMAT' => 'CONVERT_NASRANI_2LINES', 
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'admission_start_hdate' => array('FGROUP' => 'dates', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 10,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'DATE',  'FORMAT' => '',  'STEP' => 1,    'DISPLAY' => true, 'FORMAT' => 'CONVERT_NASRANI_2LINES',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'admission_end_hdate' => array('FGROUP' => 'dates', 'IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 10,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'DATE',  'FORMAT' => '',  'STEP' => 1,    'DISPLAY' => true, 'FORMAT' => 'CONVERT_NASRANI_2LINES',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'classes_names' => array('FGROUP' => 'cln', 'STEP' => 1, 'SEARCH' => true,  
				'SHOW' => true,  'EDIT' => true,  'QEDIT' => true, 'UTF8' => true,  
				'TYPE' => 'TEXT',  'SIZE' => 'AREA', 'ROWS' => 18, 'COLS' => 24,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),				

		'pendingCandidateList' => array(
					'TYPE' => 'FK',  'ANSWER' => 'scandidate',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '',  'ITEM_OPER' => '!=',  
					'WHERE' => "school_id=§school_id§ and year=§year§ and candidate_status_id = 1", 
					'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'NO-LABEL' => true,  'BUTTONS' => true,  'FGROUP' => 'cand',  'STEP' => 4,    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'STEP' => 2, 
					'CSS' => 'width_pct_100',),		
					
					'decision_stats' => array('CATEGORY' => 'FORMULA',  'FGROUP' => 'decision_stats',  'SEARCH' => true,  'SHOW' => true,  'FORMAT' => 'HTML',  
								'EDIT' => true,  'READONLY' => true,  'SIZE' => 'AREA',  'UTF8' => true,  
								'TYPE' => 'TEXT',  'STEP' => 3,    'READONLY' => true,  
								'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
								'CSS' => 'width_pct_50',),	

		'school_year_name_ar' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE-AR' => true,  'EDIT' => false,  'QEDIT' => true,  'SIZE' => 32,  'UTF8' => true,  
				'TYPE' => 'TEXT',  'STEP' => 4,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

		'school_year_name_en' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE-EN' => true,  'EDIT' => false,  'QEDIT' => true,  'SIZE' => 32,  
				'TYPE' => 'TEXT',  'STEP' => 4,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),
		'scop' => array(
				'TYPE' => 'FK',  'ANSWER' => 'school_scope',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_year_id',  
				'WHERE' => "", 
				'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve', 
				 'EDIT' => false,  'NO-LABEL' => true,  'BUTTONS' => true,  
				'STEP' => 4,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'schoolClassList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'school_class',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_year_id',  
				'WHERE' => "", 
				'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  
				/*'FORMAT' => 'minibox',  'MINIBOX-HEADER' => 'all_close',*/
				'FORMAT' => 'retrieve', 
				'EDIT' => false,  'NO-LABEL' => true,  'BUTTONS' => true,  'FGROUP' => 'schoolClassList',  
				'DO-NOT-RETRIEVE-COLS' => array (
									0 => 'school_year_id',
									1 => 'year',
									2 => 'school_id',
									3 => 'is_ok',
									),  
				'STEP' => 5,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

		'hdayList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'hday',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'school_year_id',  
				'WHERE' => "hday_gdat between substring(now(),1,10) and §date_window_max§", 
				'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  'FORMAT' => 'retrieve',  
				'EDIT' => false,  'NO-LABEL' => true, 'FGROUP' => 'hdayList',  
				'STEP' => 6,    'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

					'date_window_max' => array(
							'TYPE' => 'GDAT',  
							'CATEGORY' => 'FORMULA',  'STEP' => 6,    'DISPLAY' => '',  
							'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
							'CSS' => 'width_pct_25',),

		'acceptedCandidateList' => array(
						'TYPE' => 'FK',  'ANSWER' => 'scandidate',  'ANSMODULE' => 'sis',  
						'CATEGORY' => 'ITEMS',  'ITEM' => '',  'ITEM_OPER' => '!=',  
						'WHERE' => "school_id=§school_id§ and year=§year§ and candidate_status_id = 2", 
						'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  'FORMAT' => 'retrieve',  
						'EDIT' => false,  'NO-LABEL' => true,  'BUTTONS' => true,  'FGROUP' => 'cand',  'STEP' => 4,    'DISPLAY' => true,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'STEP' => 7, 
						'CSS' => 'width_pct_100',),					
		
				
		'rejectedCandidateList' => array(
					'TYPE' => 'FK',  'ANSWER' => 'scandidate',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '',  'ITEM_OPER' => '!=',  
					'WHERE' => "school_id=§school_id§ and year=§year§ and candidate_status_id = 3", 
					'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => false,  'FORMAT' => 'retrieve',  'EDIT' => false,  
					'NO-LABEL' => true,  'BUTTONS' => true,  'FGROUP' => 'cand',  'STEP' => 4,    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'STEP' => 8, 
					'CSS' => 'width_pct_100',),	
					
		
		/*			'studentCourseProgramList' => array('STEP' => 9,  
					'TYPE' => 'FK',  'ANSWER' => 'student_course_program',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '',  
					'WHERE' => "school_id=§school_id§ and year=§year§", 
					'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve',  
					'DO-NOT-RETRIEVE-COLS' => [],
					'EDIT' => false,  'BUTTONS' => true,  'FGROUP' => 'files',    'DISPLAY' => true,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),*/
		
	
		'studentFileList' => array('STEP' => 9,  
						'TYPE' => 'FK',  'ANSWER' => 'student_file',  'ANSMODULE' => 'sis',  
						'CATEGORY' => 'ITEMS',  'ITEM' => '',  
						'WHERE' => "school_id=§school_id§ and year=§year§", 
						'SHOW' => true,  'ICONS' => true,  'DELETE-ICON' => true,  'FORMAT' => 'retrieve',  
						'DO-NOT-RETRIEVE-COLS' => [],
						'EDIT' => false,  'BUTTONS' => true,  'DISPLAY' => true,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),					

						'levels_template_id' => [
							'IMPORTANT' => 'IN',
							'TYPE' => 'FK',
							'ANSWER' => 'levels_template',
							'CATEGORY' => 'SHORTCUT',
							'SHORTCUT' => 'school_id.levels_template_id',
							'SIZE' => 40,
							'DEFAUT' => 0,
							'ANSMODULE' => 'sis',
							'DISPLAY' => '',
							'STEP' => 99,
							'DISPLAY-UGROUPS' => '',
							'EDIT-UGROUPS' => '',
							'READONLY' => true,
							'CSS' => 'width_pct_25',
						],


		'courseSessionList' => [
							'STEP' => 10,
							'TYPE' => 'FK',
							'ANSWER' => 'course_session',
							'ANSMODULE' => 'sis',
							'CATEGORY' => 'ITEMS',
							'ITEM' => '',
							'WHERE' =>
								'school_id=§school_id§ and 
								 levels_template_id=§levels_template_id§ and 
								 session_date between §start_near_date§ and §end_near_date§',
							'SHOW' => true,
							'ICONS' => true,
							'DELETE-ICON' => false,
							'FORMAT' => 'retrieve',
							'EDIT' => false,
							'NO-LABEL' => false,
							'BUTTONS' => true,
							'DO-NOT-RETRIEVE-COLS' => [
								0 => 'school_year_id',
								1 => 'level_class_id',
								2 => 'class_name',
								3 => 'school_class_id',
								4 => 'mainwork_book_id',
								5 => 'homework_book_id',
								6 => 'homework2_book_id',
							],
							'DISPLAY' => true,
							'DISPLAY-UGROUPS' => '',
							'EDIT-UGROUPS' => '',
							'CSS' => 'width_pct_100',
						],						

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

                        'created_by'         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'created_at'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'DATETIME', 'FGROUP' => 'tech_fields'),

                        'updated_by'           => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'updated_at'              => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'DATETIME', 'FGROUP' => 'tech_fields'),

                        'validated_by'       => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'validated_at'          => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
                                                                'TYPE' => 'DATETIME', 'FGROUP' => 'tech_fields'),

                        /* 'active'                   => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'EDIT' => true, 
                                                                'QEDIT' => true, "DEFAULT" => 'Y', 'TYPE' => 'YN', 'FGROUP' => 'tech_fields'),*/

                        'version'                  => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'INT', 'FGROUP' => 'tech_fields'),

                        // 'draft'                         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'EDIT' => true, 
                        //                                        'QEDIT' => true, "DEFAULT" => 'Y', 'TYPE' => 'YN', 'FGROUP' => 'tech_fields'),

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