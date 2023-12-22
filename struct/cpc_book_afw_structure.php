<?php 

        class SisCpcBookAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if ($obj instanceof CpcBook) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					$obj->DISPLAY_FIELD = "book_name";
					$obj->IS_LOOKUP = true;
					$obj->ORDER_BY_FIELDS = "id";
					$obj->editByStep = true;
					$obj->editNbSteps = 5;
					$obj->public_display = true;
				}
			}


                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  'NO-COTE'=>true,
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'book_type_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  
				'QSEARCH' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book_type',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,   
				'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'parent_book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  
				'QSEARCH' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,   
				'WHERE' => 'book_type_id = 1', 'RELATION' => 'OneToMany',
				'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'course_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'MFK',  'ANSWER' => 'course',  'ANSMODULE' => 'sis',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'part_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'MFK',  'ANSWER' => 'cpc_book', 'WHERE' => 'book_type_id = 2', 
				'ANSMODULE' => 'sis',  'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'book_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 48,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'part_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  
				'SIZE' => 48,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'chapter_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  
				'SIZE' => 48,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),					

			'paragraph_name' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  
				'EDIT' => true,  'QEDIT' => true,  
				'SIZE' => 48,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	
				
				
				

			'first_page_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
				'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'last_page_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
				'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'book_nb_pages' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'len' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FLOAT',    'DISPLAY' => true,  'STEP' => 2, 'FORMAT' => '*.2',
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	

			'level_class_mfk' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => false,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  
				'TYPE' => 'MFK',  'ANSWER' => 'level_class',  'ANSMODULE' => 'sis',  
				'WHERE' => "school_level_id in (select slvl.id 
                                                                  from c0sis.school_level slvl 
                                                                      inner join c0sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", 
				   'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'cpcBookParagraphList' => array(
					'TYPE' => 'FK',  'ANSWER' => 'cpc_book_paragraph',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '',  
					'WHERE' => "book_id=§id§ or part_id=§id§ or chapter_id=§id§", 
					 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true, 
					 'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => true, 'DISPLAY' => true,  'STEP' => 2,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),
	

		'cpcSubBookList' => array(
					'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
					'CATEGORY' => 'ITEMS',  'ITEM' => '',  
					'WHERE' => 'parent_book_id=§id§ and book_type_id = 2',  
					
					 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true,  
					 'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => false,    'DISPLAY' => true,  'STEP' => 4,  
					'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
					'CSS' => 'width_pct_100',),

		'cpcChapterList' => array(
						'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
						'CATEGORY' => 'ITEMS',  'ITEM' => '',  
						'WHERE' => "parent_book_id=§parent_book_id§ and book_type_id = 3 and part_mfk like '%,§id§,%'",
						 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true,  
						 'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => false,    
						 'DISPLAY' => true,  'STEP' => 5,  
						'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
						'CSS' => 'width_pct_100',),			

		'cpcBookPageList' => array(
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book_page',  'ANSMODULE' => 'sis',  
				'CATEGORY' => 'ITEMS',  'ITEM' => 'book_id',  
				'WHERE' => "", 
				 'SHOW' => true,  'FORMAT' => 'retrieve',  'EDIT' => false,  'ICONS' => true,  
				 'DELETE-ICON' => false,  'BUTTONS' => true,  'NO-LABEL' => true,    'DISPLAY' => true,  'STEP' => 3,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'version'                  => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
				'QEDIT' => false, 'TYPE' => 'INT', 'FGROUP' => 'tech_fields'),

			// 'draft'                         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'EDIT' => false, 
			//                                        'QEDIT' => false, "DEFAULT" => 'Y', 'TYPE' => 'YN', 'FGROUP' => 'tech_fields'),

			'update_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
							'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

			'delete_groups_mfk'             => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
							'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

			'display_groups_mfk'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
							'QEDIT' => false, 'ANSWER' => 'ugroup', 'ANSMODULE' => 'ums', 'TYPE' => 'MFK', 'FGROUP' => 'tech_fields'),

			'sci_id'                        => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
							'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'pag', 'FGROUP' => 'tech_fields'),

			'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', 'QEDIT' => false, "SHOW-ADMIN" => true, 
							'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),

                        
                ); 
        } 
?>