<?php
                
                $TECH_FIELDS = array();
                $MODULE = "sis";
                $THIS_MODULE_ID = 1044;
                $MODULE_FRAMEWORK[$THIS_MODULE_ID] = 1;
                
                $LANGS_MODULE = array("ar"=>true,"fr"=>false,"en"=>true);
                
                
                $MENU_ICONS[1] = "cogs";
                $MENU_ICONS[9] = "sitemap";
                $MENU_ICONS[3] = "building";
                $MENU_ICONS[5] = "pie-chart";
                
                
                $BOOKS_HTTP_PATH = "http://localhost/sis/books/";
                
                $custom_header = true;
                $date_pos_left = "20%";
                $welcome_pos_left = "23%";
                $date_pos_top = "5px";
                $welcome_pos_top = "33px";
                
                $date_color = "#000";
                $date_bgcolor = "transparent";
                $header_bg_color = "rgb(230, 242, 255)";
                //$date_font_weight = "bold";
                //$date_color = "#1e620b";
                $date_font_size = "14px";
                $date_font_family = "maghreb";
                
                
                //$check_depending_user_type = "check_sempl";
                //$my_account_page = "main.php?Main_Page=afw_mode_display.php&cl=Sempl&id=[SEMPL]&currmod=sdd&no_my_account_page_in_mod=[MODULE]";
                
                $login_page_options = array();
                
                // $body_css_class = "rea_body <-- contain problems when mode edit after submit PublicMethod the form disappear";  // by default hzm_body
                $file_box_css_class = "rea_filebox";  // by default filebox
                
                // $PUBLIC_MODULE_ROLES[$MODULE] = ",42,44,56,85,";
                
                
                $file_type_ids = "2,3,4,5,6,7,10,12,13,15";
                
                
                $module_config_token = array();
                
                $module_config_token["file_types"] = $file_type_ids;
                
                $front_header = true;
                $front_application = true;
                
                $header_style = "header_thin";
                $my_theme = "simple";
                //$my_font = "naskh";
                $banner = true;
                $bg_height = 300;
                $MODE_DEVELOPMENT = true;
                $student_title = "الطالب";                                                        
                
                $config["img-path"] = "../external/pic/";
                $config["img-company-path"] = "../external/pic/";
                
                $display_in_edit_mode["*"] = true;

                

                $TECH_FIELDS[$MODULE]["CREATION_USER_ID_FIELD"]="created_by";
                $TECH_FIELDS[$MODULE]["CREATION_DATE_FIELD"]="created_at";
                $TECH_FIELDS[$MODULE]["UPDATE_USER_ID_FIELD"]="updated_by";
                $TECH_FIELDS[$MODULE]["UPDATE_DATE_FIELD"]="updated_at";
                $TECH_FIELDS[$MODULE]["VALIDATION_USER_ID_FIELD"]="validated_by";
                $TECH_FIELDS[$MODULE]["VALIDATION_DATE_FIELD"]="validated_at";
                $TECH_FIELDS[$MODULE]["VERSION_FIELD"]="version";
                $TECH_FIELDS[$MODULE]["ACTIVE_FIELD"]="active";
?>