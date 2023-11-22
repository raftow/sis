<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_course_plan : cpc_course_plan - فكرة جدولة التدريس أو ما سنسميه بالمحتوى العلمي للحصص الدراسية تعتمد على مبدأ رائع جدا ومريح للوحدة دراسية وللمدرسين وهو أن برنامج كل حصة دراسية يكون مجدول مسبقا بل ربما منذ بداية السنة الدراسية وهو نفسه لا يتغير من سنة دراسية إلى أخرى بحيث يعرف المدرس مسبقا ماذا عليه أن يدرس في حصة الغد (مثلا جدول الضرب مع التمارين من رقم 1 إلى رقم 10 / أو من صفحة كذا إلى ص كذا من كتاب النشاط) وهكذا بحيث يلتزم المدرسون بالسير على نفس البرنامج لكل مادة دراسية معينة مادام الحلقة نفسه (صف ثالث مثلا) بغض النظر عن الرمز (أ/ب/الخ) لأنهم كلهم سيسيرون على جدول تدريسي واحد.
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcCoursePlan extends SisObject{

	public static $DATABASE		= ""; public static $MODULE		    = "sis"; public static $TABLE			= ""; public static $DB_STRUCTURE = null; /* array(
                "id" => array("SHOW" => true, "RETRIEVE" => true, "EDIT" => true, "TYPE" => "PK"),
  
		"course_program_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", 
                                          "ANSWER" => cpc_course_program, "ANSMODULE" => sis, "DEFAULT" => 0, READONLY=>true),
		"level_class_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => level_class, "ANSMODULE" => sis, 
                                          WHERE => "school_level_id in (select slvl.id from c0sis.school_level slvl where slvl.levels_template_id = §levels_template_id§)", 
                                          "SEARCH-BY-ONE"=>true, "NO-COTE"=>true,
                                          "WHERE-SEARCH"=>"school_level_id in (select slvl.id 
                                                                  from c0sis.school_level slvl 
                                                                      inner join c0sis.school scl on scl.levels_template_id = slvl.levels_template_id
                                                                  where scl.id = '§SUB_CONTEXT_ID§')","DEFAULT" => 0, READONLY=>true),
		"course_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", "ANSWER" => course, "ANSMODULE" => sis, "DEFAULT" => 0, READONLY=>true),
		"course_num" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT", READONLY=>true),
		"course_content" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT"),
		"course_book_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", 
                                          "ANSWER" => cpc_book, "ANSMODULE" => sis, "DEFAULT" => 0, WHERE=>"book_type_id=1 and course_id=§course_id§ and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')"),
		"course_book_from_page" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		"course_book_to_page" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		"course_comment" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT"),
		"next_homework_book_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK", 
                                          "ANSWER" => cpc_book, "ANSMODULE" => sis, "DEFAULT" => 0, WHERE=>"book_type_id=2 and course_id=§course_id§ and (level_class_mfk is null or level_class_mfk ='' or level_class_mfk like '%,§level_class_id§,%')"),
		"next_homework_book_from_page" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		"next_homework_book_to_page" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "INT"),
		"next_homework" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => true, "SIZE" => 32, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => true, "TYPE" => "TEXT"),
		"sexam_id" => array("IMPORTANT" => "IN", "SEARCH" => true, "SHOW" => true, "RETRIEVE" => false, "EDIT" => true, "QEDIT" => false, "SIZE" => 40, "SEARCH-ADMIN" => true, "SHOW-ADMIN" => true, "EDIT-ADMIN" => true, "UTF8" => false, "TYPE" => "FK",
                                    "ANSWER" => sexam, "ANSMODULE" => sis, SHORTNAME => exam, "DEFAULT" => 0),
                session_desc => array("TYPE" => "TEXT", "CATEGORY" => "FORMULA", "SHOW"=>true, "RETRIEVE"=>true, "EDIT" => false, "QEDIT" => false, "READONLY"=>true, ),
                course_desc  => array("TYPE" => "TEXT", "CATEGORY" => "FORMULA", "SHOW"=>true, "RETRIEVE"=>true, "EDIT" => false, "QEDIT" => false, "READONLY"=>true, ),
                homework_desc  => array("TYPE" => "TEXT", "CATEGORY" => "FORMULA", "SHOW"=>true, "RETRIEVE"=>true, "EDIT" => false, "QEDIT" => false, "READONLY"=>true, ),
                
                "created_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "created_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "updated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "updated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "validated_by" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "auser", "ANSMODULE" => "ums"),
                "validated_at" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "DATETIME"),
                "active" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "DEFAULT" => "Y", "TYPE" => "YN"),
                "version" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "INT"),
                "update_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "delete_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "display_groups_mfk" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "ANSWER" => "ugroup", "ANSMODULE" => "ums", "TYPE" => "MFK"),
                "sci_id" => array("SHOW-ADMIN" => true, "RETRIEVE" => false, "EDIT" => false, "TYPE" => "FK", "ANSWER" => "scenario_item", "ANSMODULE" => "pag"),
	);
	
	*/ public function __construct(){
		parent::__construct("cpc_course_plan","id","sis");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "";
                $this->ORDER_BY_FIELDS = "course_program_id,level_class_id,course_id,course_num";
                
                
	}
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             
             
             return $otherLinksArray;
        }
        
        public function getFormuleResult($attribute, $what='value') 
        {
            global $lang, $BOOKS_HTTP_PATH, $Main_Page;    
            $upld_path = AfwSession::config("uploads_http_path","");
               
	       switch($attribute) 
               {
                    
                    case "download" :
                        $new_name = $this->getNewName();
                        $url = "<a target='_download' href='$upld_path/$new_name'><img src='../images/Download.png'  width='16' heigth='16'></a>";  // alt='انقر هنا لتحميل الملف'
			return $url;
		    break;
                    
                    case "session_desc" :
                        if($this->session_desc) return $this->session_desc;
                        $course_content = $this->getVal("course_content");
                        $course_comment = $this->getVal("course_comment");
                        $next_homework = $this->getVal("next_homework");
                        $course_num = $this->getVal("course_num");
                        
                        $html_desc = "";
                        
                        if($course_content)
                        {
                                $html_desc .= "<span class='counter_desc'>حصة رقم $course_num : </span> ";
                                $html_desc .= "<span class='course_title'>$course_content</span><br>";
                        }
                           

                        $book_id = $this->getVal("course_book_id");
                        $book = $this->showAttribute("course_book_id");
                        $level_class_id = $this->getVal("level_class_id");
                        $course_book_from_page = $this->getVal("course_book_from_page");
                        $course_book_to_page = $this->getVal("course_book_to_page");
                        
                        if($book) $html_desc .= "<span class='course_desc'>الدرس من ص $course_book_from_page إلى ص $course_book_to_page : الكتاب $book</span> ";
                        if($course_comment) $html_desc .= "<span class='course_comment'>$course_comment</span><br>";
                        
                        $book_id = $this->getVal("next_homework_book_id");
                        $book = $this->showAttribute("next_homework_book_id");
                        $level_class_id = $this->getVal("level_class_id");
                        $next_homework_book_from_page = $this->getVal("next_homework_book_from_page");
                        $next_homework_book_to_page = $this->getVal("next_homework_book_to_page");
                        if($book) $html_desc .= "<div class='homework_desc'>الواجبات من ص $next_homework_book_from_page إلى ص $next_homework_book_to_page : الكتاب $book</div> ";
                        if($next_homework) $html_desc .= "<div class='homework_comment'>$next_homework</div><br>";
                        
                        $exam_id = $this->getVal("sexam_id");
                        if($exam_id>0) $exam = $this->hetExam();
                        else $exam = null;
                        
                        if($exam)
                        {
                               $html_desc .= "<div class='exam_warning'>تنبيه : في هذا الدرس سيقوم الطلاب باجراء إختبار تقييمي !</div>";
                               $html_desc .= $exam->showMe("html",$lang);
                        }
                        
                        
                        return $html_desc;
		        break;
                    case "course_desc" :
                        if($this->course_desc) return $this->course_desc; 
                        $id = $this->getId();
                        
                        $html_desc = "";//$html_desc = "<h5 class='bluetitle'><i></i>صفحات الدرس</h5>";
                        
                        $book_id = $this->getVal("course_book_id");
                        $level_class_id = $this->getVal("level_class_id");
                        $course_book_from_page = $this->getVal("course_book_from_page");
                        $course_book_to_page = $this->getVal("course_book_to_page");
                        
                        if(($Main_Page=="afw_mode_qedit.php") or ($Main_Page=="afw_handle_default_qedit.php"))
                        {
                             $wd = 35;
                             $hg = 50;
                        }
                        else
                        {
                             $wd = 105;
                             $hg = 150;
                        }
                        
                        for($p=$course_book_from_page;$p<=$course_book_to_page;$p++)
                        {
                           if($p)
                           {
                               $pic_num = $book_id*10000+$p;
                               $pic_id = "zoom_${pic_num}";
                               
                               $pic_file = $pic_num.".png";
                                if($this->picture_style) $picture_style = "style='".$this->picture_style."'";
                                else $picture_style = "style='width: ${wd}px !important;   height: ${hg}px !important;'";
                                
                                $html_desc .= "<img id='$pic_id' src='$BOOKS_HTTP_PATH/pages/$pic_file' $picture_style  data-zoom-image='$BOOKS_HTTP_PATH/l$level_class_id/$pic_file'>";  // alt='انقر هنا لتحميل الملف'
                                $html_desc .= "
        <script>
            $('#$pic_id').elevateZoom({
        cursor: \"crosshair\",
        zoomWindowWidth:788,
        zoomWindowHeight:960,
        zoomWindowFadeIn: 500,
        zoomWindowFadeOut: 1100,
        zoomWindowOffety:-200
        
           });
           
        </script>
                                ";
                            }    
                        }
                        
			return $html_desc;
		    break;
                    
                    case "homework_desc" :
                        if($this->homework_desc) return $this->homework_desc;
                        $id = $this->getId();
                        
                        $html_desc = "";//$html_desc = "<h5 class='bluetitle'><i></i>صفحات الدرس</h5>";
                        
                        $book_id = $this->getVal("next_homework_book_id");
                        $level_class_id = $this->getVal("level_class_id");
                        $next_homework_book_from_page = $this->getVal("next_homework_book_from_page");
                        $next_homework_book_to_page = $this->getVal("next_homework_book_to_page");
                        
                        if(($Main_Page=="afw_mode_qedit.php") or ($Main_Page=="afw_handle_default_qedit.php"))
                        {
                             $wd = 35;
                             $hg = 50;
                        }
                        else
                        {
                             $wd = 105;
                             $hg = 150;
                        }
                        
                        for($p=$next_homework_book_from_page;$p<=$next_homework_book_to_page;$p++)
                        {
                           if($p)
                           {
                               $pic_num = $book_id*10000+$p;
                               $pic_id = "zoom_${pic_num}";
                               
                               $pic_file = $pic_num.".jpg";
                                if($this->picture_style) $picture_style = "style='".$this->picture_style."'";
                                else $picture_style = "style='width: ${wd}px !important;   height: ${hg}px !important;'";
                                
                                $html_desc .= "<img id='$pic_id' src='$BOOKS_HTTP_PATH/pages/$pic_file' $picture_style  data-zoom-image='$BOOKS_HTTP_PATH/l$level_class_id/$pic_file'>";  // alt='انقر هنا لتحميل الملف'
                                $html_desc .= "
        <script>
            $('#$pic_id').elevateZoom({
        cursor: \"crosshair\",
        zoomWindowWidth:788,
        zoomWindowHeight:960,
        zoomWindowFadeIn: 500,
        zoomWindowFadeOut: 1100,
        zoomWindowOffety:-200
        
           });
           
        </script>
                                ";
                            }    
                        }
                        
			return $html_desc;
		    break; 
               }
        }
        
             
}
?>