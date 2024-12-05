<?php
class SisStudentBookAfwStructure
{
    public static function initInstance(&$obj)
    {
        if($obj instanceof StudentBook)
        {

            $multiple_key_cols = "student_id,main_book_id";
            $part_cols = "student_id";
            $context_cols = "";

            $obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
            // $obj->HIDE_DISPLAY_MODE = true;
            // $obj->hideQeditCommonFields = true;
            $obj->DISPLAY_FIELD = "";
            $obj->ORDER_BY_FIELDS = $multiple_key_cols;
            $obj->PK_MULTIPLE = "|";
            $obj->PK_MULTIPLE_ARR = explode(",",$multiple_key_cols);
            $obj->UNIQUE_KEY = $obj->PK_MULTIPLE_ARR;
            
            //$obj->editByStep = true;
            $obj->editNbSteps = 1;

            $obj->showQeditErrors = true;

            $obj->is_detail_for["student"] = true;
            $obj->after_save_edit = [
                'class' => 'Student',
                'attribute' => 'student_id',
                'currmod' => 'sis',
                'currstep' => 7,
            ];
            
            $obj->setContextAndPartitionCols($part_cols, $context_cols);
            $obj->setMultiplePK($multiple_key_cols,$obj->PK_MULTIPLE);

        }        
    }

    public static $DB_STRUCTURE = [

        'student_id' => ['STEP' => 1, 
            'IMPORTANT' => 'high',
            'SHORTNAME' => 'student',
            'SEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'QEDIT-BEFORE-COLS' => 1,
            'SIZE' => 40,
            'SEARCH-ADMIN' => true,
            'SHOW-ADMIN' => true,
            'EDIT-ADMIN' => true,
            'TYPE' => 'FK',
            'ANSWER' => 'student',
            'ANSMODULE' => 'sis',
            'MANDATORY' => true,
            'RELATION' => 'OneToMany',
            // 'PILLAR' => false,
            'DEFAUT' => 0,
            'AUTOCOMPLETE' => false,
            'WHERE' =>
                'id in (select student_id from §DBPREFIX§sis.scandidate where school_id=§school_id§ and year=§year§ and level_class_id=§level_class_id§)', 
            'DISPLAY' => true,
            'READONLY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'ERROR-CHECK' => true,
            'CSS' => 'width_pct_50',
        ],

        

        'main_book_id' => [
            'IMPORTANT' => 'high',
            'SEARCH' => true,
            "NO-COTE"=>true,
            'SHOW' => true,
            'RETRIEVE' => true,
            'QEDIT' => false,
            'EDIT' => true,
            'READONLY' => true,
            'TYPE' => 'FK',
            'ANSWER' => 'cpc_book',
            'DEPENDENT_OFME' => array("main_part_id", "main_chapter_id",),
            'SIZE' => 40,
            'DEFAUT' => 0,
            'QSEARCH' => true,
            'ANSMODULE' => 'sis',
            'DISPLAY' => true,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_50',
        ],
        
        'main_sens' => [
            'IMPORTANT' => 'high',
            'TYPE' => 'ENUM',
            'ANSWER' => "FUNCTION",
            'RETRIEVE' => true,
            'SHOW' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'QEDIT-BEFORE-COLS' => 2,
            'READONLY' => false,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_50',
            'MANDATORY' => true,
        ],

        'real_book_id' => [
            'CATEGORY' => 'FORMULA',
            'TYPE' => 'FK',
            'ANSWER' => 'cpc_book',
            'ANSMODULE' => 'sis',
            'STEP' => 99,
        ],

        'main_part_id' => [
            'IMPORTANT' => 'high',
            "NO-COTE"=>true,
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
            'IMPORTANT' => 'high',
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
            'WHERE' => "book_type_id = 3 and ('§main_part_id§'='' or '§main_part_id§'='0' or part_mfk like '%,§main_part_id§,%')",
            'DEPENDENCIES' => ['main_book_id','main_part_id'],
            'ANSMODULE' => 'sis',
            'DISPLAY' => true,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
            'READONLY' => false,
            'MANDATORY' => true,
        ],

        'main_page_num' => [            
            'TYPE' => 'ENUM',
            'ANSWER' => "FUNCTION",
            'RETRIEVE' => true,
            'SHOW' => true,
            'EDIT' => true,
            'QEDIT' => false,
            'READONLY' => false,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'main_paragraph_num' => [
            'IMPORTANT' => 'high',
            'SEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => true,
            'QEDIT' => true,
            'MANDATORY' => true,
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

        'mainwork_nb_pages' => [
            'IMPORTANT' => 'high',
            'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:30:1',
            'RETRIEVE' => false,
            'SHOW' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'QEDIT-AFTER-COLS' => 1,
            'READONLY' => false,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'mainwork_nb_lines' => [
            'IMPORTANT' => 'high',
            'EDITOR-STYLE' => 'INCREMENT', 'FORMAT'=>'STEP:0:600:1',
            'RETRIEVE' => false,
            'SHOW' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'QEDIT-AFTER-COLS' => 1,
            'READONLY' => false,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

                    'main_paragraph_id' => [
                        'CATEGORY' => 'FORMULA',
                        'SEARCH' => false,
                        'SHOW' => false,
                        'RETRIEVE' => true,
                        'QEDIT' => false,
                        'EDIT' => false,
                        'READONLY' => true,
                        'TYPE' => 'FK',
                        'ANSWER' => 'cpc_book_paragraph',
                        'ANSMODULE' => 'sis',
                        'SIZE' => 40,
                        'DEFAUT' => 0,
                        'QSEARCH' => true,
                        'STEP' => 1,
                        'DISPLAY-UGROUPS' => '',
                        'EDIT-UGROUPS' => '',
                        'CSS' => 'width_pct_50',
                        'RELATION-SUPER' => 'IMPORTANT'
                    ],

        'study_program_id' => array('SHORTNAME' => 'config',  'SEARCH' => true,  
                'IMPORTANT' => 'high',
                'QSEARCH' => false,  'SHOW' => true, 
			    'RETRIEVE' => true,  'EDIT' => true,  
                'QEDIT' => true, 'MANDATORY' => true, 
                'QEDIT-AFTER-COLS' => 1,
                'SIZE' => 40,  'UTF8' => false,  
				'TYPE' => 'FK',  'ANSWER' => 'study_program', 
                // to see how to show only study programs that are global (for all schools or for current school) 
                // for the moment the global courses_config_template_id = 3 (for assaf) is available for all schools
                // and course_id = 5 حفظ ومراجعة القرآن
                // for current school we need to calculate formula to get courses_config_template_id
                // for school of current authenticated user
                'WHERE' => "courses_config_template_id = 3 and course_id = 5",
                'DEFAUT' => 0,  'MINIBOX' => true, 
				'ANSMODULE' => 'sis',  'STEP' => 1,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '',  'RELATION' => 'ManyToOne',
				'CSS' => 'width_pct_25',),

        'approval_school_id' => [
            'IMPORTANT' => 'IN',
            'SEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => false,
            'QEDIT' => false,
            'EDIT' => true,
            'READONLY' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'school',
            'SIZE' => 40,
            'DEFAUT' => 0,
            'QSEARCH' => true,
            'WHERE' => "1",
            'DEPENDENT_OFME' => array("approval_employee_id", ),
            'ANSMODULE' => 'sis',
            'DISPLAY' => true,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
            'READONLY' => false,
        ],

        'approval_status_id' => [
            'TYPE' => 'ENUM',
            'ANSWER' => "FUNCTION",
            'RETRIEVE' => true,
            'SHOW' => true,
            'EDIT' => true,
            'QEDIT' => false,
            'READONLY' => true,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'approval_employee_id' => [
            'IMPORTANT' => 'IN',
            'SEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => false,
            'QEDIT' => false,
            'EDIT' => true,
            'READONLY' => true,
            'TYPE' => 'FK',
            'ANSWER' => 'employee',
            'ANSMODULE' => 'hrm',
            'SIZE' => 40,
            'DEFAUT' => 0,
            'QSEARCH' => true,
            'WHERE' => "id in (select employee_id from §DBPREFIX§sis.school_employee  where school_id = §approval_school_id§)",
            'DEPENDENCIES' => ['approval_school_id'],
            'DISPLAY' => true,
            'STEP' => 1,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        
        'created_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'created_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'updated_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'updated_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'validated_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'validated_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'active' => [
            'IMPORTANT' => 'high',
            'SHOW' => true,
            'RETRIEVE' => false,
            'EDIT' => true,
            'QEDIT' => false,
            //'QEDIT-AFTER-COLS' => 1,

            'DEFAUT' => 'Y',
            'TYPE' => 'YN',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

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
    ];
}
