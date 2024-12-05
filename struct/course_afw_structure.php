<?php 
        class SisCourseAfwStructure
        {
                public static function initInstance(&$obj)
                {
                        if ($obj instanceof Course) 
                        {
                                $obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                                $obj->DISPLAY_FIELD = "course_name_ar";
                                // $obj->ENABLE_DISPLAY_MODE_IN_QEDIT=true;
                                $obj->ORDER_BY_FIELDS = "lookup_code";
                                $obj->horizontalTabs = true;
                                $obj->IS_LOOKUP = true;                                 
                                $obj->ignore_insert_doublon = true;
                                $obj->UNIQUE_KEY = array('lookup_code');
                 
                                $obj->showQeditErrors = true;
                                $obj->showRetrieveErrors = true;
                                $obj->general_check_errors = true;
                                $obj->public_display = true;
                                $obj->editByStep = true;
                                $obj->editNbSteps = 2;
                                // $obj->after_save_edit = array("class"=>'Road',"attribute"=>'road_id', "currmod"=>'btb',"currstep"=>9);
                        }
                }  
                public static $DB_STRUCTURE = array(

                        
			'id' => array('SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  
				'TYPE' => 'PK',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'course_name_ar' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
                                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
                                'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

			'course_name_en' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
                                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
                                'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),

                        'mainwork' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
                                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
                                'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DEFAULT' => 'الحفظ',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),                                

                        'homework' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
                                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
                                'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DEFAULT' => 'المراجعة',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),        

                        'homework2' => array('IMPORTANT' => 'IN',  'SEARCH' => true,  'SHOW' => true,  
                                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SEARCH-ADMIN' => true,  
                                'SHOW-ADMIN' => true,  'EDIT-ADMIN' => true,  'UTF8' => true,  
				'TYPE' => 'TEXT',    'DEFAULT' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_50',),     
                                
                                

                                
                            
                                    'mainwork_book_id' => [
                                        'FGROUP' => 'mainwork',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                    ],
                            
                                    
                            
                                    
                            
                                    'mainwork_start_part_id' => [
                                        'FGROUP' => 'mainwork',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 2',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'mainwork_start_chapter_id' => [
                                        'FGROUP' => 'mainwork',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => true,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 3',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'mainwork_start_paragraph_num' => [
                                        'FGROUP' => 'mainwork',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => true,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'INT',
                                        'RETRIEVE' => false,
                                        'READONLY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                    ],
                            
                                    
                            
                                    'homework_book_id' => [
                                        'FGROUP' => 'homework',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    
                            
                                    
                            
                                    'homework_start_part_id' => [
                                        'FGROUP' => 'homework',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 2',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'homework_start_chapter_id' => [
                                        'FGROUP' => 'homework',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => true,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 3',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'homework_start_paragraph_num' => [
                                        'FGROUP' => 'homework',
                                        'IMPORTANT' => 'IN',
                                        'TYPE' => 'INT',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                    ],
                            
                                    
                            
                                    'homework2_book_id' => [
                                        'FGROUP' => 'homework2',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    
                            
                                    'homework2_start_part_id' => [
                                        'FGROUP' => 'homework2',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => false,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 2',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'homework2_start_chapter_id' => [
                                        'FGROUP' => 'homework2',
                                        'IMPORTANT' => 'IN',
                                        'SEARCH' => true,
                                        'SHOW' => true,
                                        'RETRIEVE' => false,
                                        'QEDIT' => true,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'TYPE' => 'FK',
                                        'ANSWER' => 'cpc_book',
                                        'SIZE' => 40,
                                        'DEFAUT' => 0,
                                        'QSEARCH' => true,
                                        'WHERE' => 'book_type_id = 3',
                                        'ANSMODULE' => 'sis',
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                        'READONLY' => true,
                                    ],
                            
                                    'homework2_start_paragraph_num' => [
                                        'FGROUP' => 'homework2',
                                        'IMPORTANT' => 'IN',
                                        'TYPE' => 'INT',
                                        'RETRIEVE' => false,
                                        'SHOW' => true,
                                        'EDIT' => true,
                                        'READONLY' => true,
                                        'DISPLAY' => true,
                                        'STEP' => 2,
                                        'DISPLAY-UGROUPS' => '',
                                        'EDIT-UGROUPS' => '',
                                        'CSS' => 'width_pct_25',
                                    ],
                            
                                    

			'lookup_code' => array(
				'TYPE' => 'TEXT',  'SHOW' => true,  'RETRIEVE' => true,  'EDIT' => true,  'SIZE' => 64,  'QEDIT' => true,  'UTF8' => true,    'DISPLAY' => true,  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

			'active' => array('SHOW-ADMIN' => true,  'RETRIEVE' => false,  'EDIT' => false,  'DEFAUT' => 'Y',  
				'TYPE' => 'YN',    'DISPLAY' => '',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

                        'created_by'         => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'created_at'            => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

                        'updated_by'           => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'updated_at'              => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'TECH_FIELDS-RETRIEVE' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

                        'validated_by'       => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 
                                                                'QEDIT' => false, 'TYPE' => 'FK', 'ANSWER' => 'auser', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'validated_at'          => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'SHOW' => true, 'RETRIEVE' => false, 'QEDIT' => false, 
                                                                'TYPE' => 'GDAT', 'FGROUP' => 'tech_fields'),

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
                                                                'TYPE' => 'FK', 'ANSWER' => 'scenario_item', 'ANSMODULE' => 'ums', 'FGROUP' => 'tech_fields'),

                        'tech_notes' 	                => array('STEP' => 99, 'HIDE_IF_NEW' => true, 'TYPE' => 'TEXT', 'CATEGORY' => 'FORMULA', "SHOW-ADMIN" => true, 
                                                                'TOKEN_SEP'=>"§", 'READONLY'=>true, "NO-ERROR-CHECK"=>true, 'FGROUP' => 'tech_fields'),
                ); 
        }
