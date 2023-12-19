<?php 
        class SisCpcBookLineAfwStructure
        {

			public static function initInstance(&$obj)
			{
				if ($obj instanceof CpcBookLine) 
				{
					$obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
					//$obj->DISPLAY_FIELD = "book_page_name";
					$obj->ORDER_BY_FIELDS = "book_id, line_num";
					
					$obj->UNIQUE_KEY = array('book_id','line_num');


					$obj->PK_MULTIPLE = "-";
					$obj->PK_MULTIPLE_ARR = array('book_id','line_num');
					/*
					$obj->editByStep = true;
					$obj->editNbSteps = 2;*/
					$obj->public_display = true;
				}
			}

            public static $DB_STRUCTURE = array(

			'book_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  
			    'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' =>'book_type_id = 1',
				'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'part_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  
			    'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' =>'book_type_id = 2',
				'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),	
				
			'chapter_id' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'cpc_book',  'ANSMODULE' => 'sis',  
				'WHERE' =>'book_type_id = 3',
				'DEFAUT' => 0,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),					

			'line_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'page_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'aya_complete_num' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'INT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),				

			'aya_incomplete_pct' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'SEARCH-ADMIN' => true,  'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => false,  
				'TYPE' => 'FLOAT',    'DISPLAY' => true,  'STEP' => 1, 'FORMAT' => '*.2',  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),	
			

			'created_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'created_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'updated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_by' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'auser',  'ANSMODULE' => 'ums',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'validated_at' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  
				'TYPE' => 'DATETIME',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 99,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_100',),

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