<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_course_plan : cpc_course_plan - فكرة جدولة التدريس أو ما سنسميه بالمحتوى العلمي للحصص الدراسية تعتمد على مبدأ رائع جدا ومريح للوحدة دراسية وللمدرسين وهو أن برنامج كل حصة دراسية يكون مجدول مسبقا بل ربما منذ بداية السنة الدراسية وهو نفسه لا يتغير من سنة دراسية إلى أخرى بحيث يعرف المدرس مسبقا ماذا عليه أن يدرس في حصة الغد (مثلا جدول الضرب مع التمارين من رقم 1 إلى رقم 10 / أو من صفحة كذا إلى ص كذا من كتاب النشاط) وهكذا بحيث يلتزم المدرسون بالسير على نفس البرنامج لكل مادة دراسية معينة مادام الحلقة نفسه (صف ثالث مثلا) بغض النظر عن الرمز (أ/ب/الخ) لأنهم كلهم سيسيرون على جدول تدريسي واحد.
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcCoursePlan extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "cpc_course_plan"; 
    public static $DB_STRUCTURE = null; 
    
    
    
    public function __construct(){
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