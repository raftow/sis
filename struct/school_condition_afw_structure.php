<?php
class SisSchoolConditionAfwStructure
{
    public static function initInstance(&$obj)
    {
        if ($obj instanceof SchoolCondition) 
        {

            $obj->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
            $obj->DISPLAY_FIELD = "";
            $obj->ORDER_BY_FIELDS = "school_id, level_class_id";
            $obj->UNIQUE_KEY = array("school_id", "level_class_id");
            $obj->editByStep = true;
            $obj->after_save_edit = array("class"=>'School',"attribute"=>'school_id', "currmod"=>'sis',"currstep"=>5);
            $obj->editNbSteps = 13;
        }
	}

    public static $DB_STRUCTURE = [
        'id' => [
            'SHOW' => false,
            'RETRIEVE' => true,
            'EDIT' => false,
            'TYPE' => 'PK',
            'STEP' => 1,
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'school_id' => [
            'IMPORTANT' => 'IN',
            'SEARCH' => true,
            'QSEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'UTF8' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'school',
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

        'levels_template_id' => [
            'IMPORTANT' => 'IN',
            'SHOW' => true,
            'RETRIEVE' => false,
            'EDIT' => true,
            'QEDIT' => false,
            'SIZE' => 40,
            'READONLY' => true,
            'UTF8' => false,
            'TYPE' => 'FK',
            'DEFAUT' => 0,
            'STEP' => 1,
            'ANSWER' => 'levels_template',
            'ANSMODULE' => 'sis',
            'CATEGORY' => 'SHORTCUT',
            'SHORTCUT' => 'school_id.levels_template_id',
            'DISPLAY' => false,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],
        
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

        'level_class_id' => [
            'IMPORTANT' => 'IN',
            'SEARCH' => true,
            'SHOW' => true,
            'RETRIEVE' => true,
            'EDIT' => true,
            'QEDIT' => true,
            'UTF8' => false,
            'READONLY' => true,
            'TYPE' => 'FK',
            'ANSWER' => 'level_class',
            'ANSMODULE' => 'sis',
            'SIZE' => 40,
            'DEFAUT' => 0,
            'WHERE' =>
                'school_level_id in (select slvl.id from §DBPREFIX§sis.school_level slvl where slvl.levels_template_id = §levels_template_id§)',
            'QSEARCH' => true,
            /*'WHERE-SEARCH' => "school_level_id in (select slvl.id 
                                                                  from §DBPREFIX§sis.school_level slvl 
                                                                      inner join §DBPREFIX§sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')", */
            'STEP' => 1,
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'age_min' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  
                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => "سنة",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

		'age_max' => array('STEP' => 1,  'SEARCH' => true,  'QSEARCH' => true,  'SHOW' => true,  'AUDIT' => false,  
                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 40,  'MAXLENGTH' => 32,  'UTF8' => false,  
				'TYPE' => 'INT', 'UNIT' => "سنة",
				'READONLY' => false,  'QSEARCH' => true,  'DISPLAY' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',),

        'level_mfk' => array('STEP' => 1,  
				'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  
                'RETRIEVE' => true,  'EDIT' => true,  'QEDIT' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  'UTF8' => false,  
                'MFK-SHOW-SEPARATOR' => '، ',  
				'TYPE' => 'MENUM',  'ANSWER' => 'FUNCTION',  
				'READONLY' => false,  'SEARCH-BY-ONE' => false,  'REQUIRED' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',), 
                
        'eval_mfk' => array('STEP' => 1,  
				'SEARCH' => true,  'QSEARCH' => false,  'SHOW' => true,  'AUDIT' => false,  'RETRIEVE' => true,  
                'EDIT' => true,  '  ' => true,  'SIZE' => 32,  'MAXLENGTH' => 32,  
                'UTF8' => false,  'MFK-SHOW-SEPARATOR' => '، ',  
				'TYPE' => 'MENUM',  'ANSWER' => 'FUNCTION',  
				'READONLY' => false,  'SEARCH-BY-ONE' => false,  'REQUIRED' => true,  
				'DISPLAY-UGROUPS' => '',  'EDIT-UGROUPS' => '', 
				'CSS' => 'width_pct_25',), 
        /*        
        'stdn_nb' => [
            'TYPE' => 'INT',
            'CATEGORY' => 'FORMULA',
            'SHOW' => true,
            'RETRIEVE' => true,
            'FGROUP' => 'stdn',
            'STEP' => 3,
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'free_place_nb' => [
            'TYPE' => 'INT',
            'CATEGORY' => 'FORMULA',
            'SHOW' => true,
            'RETRIEVE' => true,
            'FGROUP' => 'stdn',
            'STEP' => 3,
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'crsp_nb' => [
            'TYPE' => 'INT',
            'CATEGORY' => 'FORMULA',
            'SHOW' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => true,
            'READONLY' => true,
            'CONSTRAINTS' => [0 => 'i-between;1,30'],
            'STEP' => 4,
            'XOPTION_KEY' => 'STATS-COMPUTE',
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],

        'ws_req_nb' => [
            'TYPE' => 'INT',
            'CATEGORY' => 'FORMULA',
            'SHOW' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'QEDIT' => true,
            'READONLY' => true,
            'CONSTRAINTS' => [0 => 'i-between;1,70'],
            'STEP' => 4,
            'XOPTION_KEY' => 'STATS-COMPUTE',
            'DISPLAY' => true,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_25',
        ],
*/
        

        'created_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'created_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'updated_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'updated_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'validated_by' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'FK',
            'ANSWER' => 'auser',
            'ANSMODULE' => 'ums',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'validated_at' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'TYPE' => 'GDAT',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

        'active' => [
            'SHOW-ADMIN' => true,
            'RETRIEVE' => false,
            'EDIT' => false,
            'DEFAUT' => 'Y',
            'TYPE' => 'YN',
            'DISPLAY' => '',
            'STEP' => 99,
            'DISPLAY-UGROUPS' => '',
            'EDIT-UGROUPS' => '',
            'CSS' => 'width_pct_100',
        ],

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



    ];
}
?>
