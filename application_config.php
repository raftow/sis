<?php
$config_arr = array(
        'application_id' => 1044,

        'application_code' => 'sis',

        // roClassName => Booking,

        'x_module_means_company'=>false,


        'application_name' => ['ar' => "إدارة معلومات الطالب", 'en' => "Student Information System",],
                                  
        'no_menu_for_login' => true,

        'enable_language_switch' => false,

        'student_title' => "الطالب",

        'cust_type_list' => array(1 => "فرد من المجتمع",
                                  5 => "متعاون من خارج المؤسسة",
                                  3 => "متدرب", ),


        //  classes params
        /*TravelTemplate_showId =>true, */
        
        'default_controller_name' => "content",                                  

        

        'notify_customer' => array("new_request" => array("sms"=>true, "email" => false, "web" => false, "whatsup" => false),
        
                                ),

        'notify_manager' => array("new_request" => array("sms"=>true, "email" => false, "web" => true, "whatsup" => false),
        
                        ),

        'notify_employee' => array("new_request" => array("sms"=>true, "email" => false, "web" => true, "whatsup" => false),
        
                ),


        'general_company_id' => 1,

        'tasksClassName' => "Request",

        'consider_user_as_customer' =>true,

        'default_customer_type' =>2,

        'HEADER_LOGO_HEIGHT' => 86,

        'DISABLE_PROJECT_ITEMS_MENU' => true,

        'register_file' => "customer_register",

        
        // smtp email config can be found in external folder
        
        // APPLICATION SETTINGS
        'MODE_DEVELOPMENT' => true,

        // SIS settings
        'default_course_mfk' => ',1,',

        'date_system' => 'GREG',
        'school_year_name_template' => 'PY - CY',
        'school_year_start' => 'PY-09-01',
        'school_year_end' => 'CY-06-30',
        'school_year_date_current_year' => 'CY-02-01',


        'level_2' => false, // for motqen is true
        'level_1' => false, // for motqen is true
        'level_0' => false, // for motqen is true
        'level_t' => false, // for motqen is true
        'level_2_grouped' => true, // for motqen is false
        'student_file_course_program_id_retrieve' => true, // for motqen is false

        );

//$sql_capture_and_backtrace = "or (session_date =";
