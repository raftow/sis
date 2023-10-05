<?php
$file_dir_name = dirname(__FILE__);
set_time_limit(8400);
ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

AfwSession::startSession();
 
if(!$student_idn_title) $student_idn_title = "رقم الهوية";

$logbl = substr(md5($_SERVER["HTTP_USER_AGENT"] . "-" . date("Y-m-d")),0,10);

$uri_items = explode("/",$_SERVER["REQUEST_URI"]);
if($uri_items[0]) $uri_module = $uri_items[0];
else $uri_module = $uri_items[1];

if(!$lang) $lang = "ar";
$module_dir_name = $file_dir_name;

$need_register = $_REQUEST["nrg"];
$sql_log_forced = true;

if(!$uri_module) die("site code not defined !!!");
else
{ 
   require_once("$file_dir_name/../$uri_module/ini.php");
   require_once("$file_dir_name/../$uri_module/module_config.php");
}
if(!$NOM_SITE) die("site not configured or not initialized !!!");

$nom_site = $NOM_SITE[$lang];
$desc_site = $DESC_SITE[$lang];
$welcome_site = $WELCOME_SITE[$lang];

$debugg_login = false;
$debugg_after_login = true;
$debugg_after_golden_or_db = true;
$debugg_after_session_created = true;

$student_msg = "";
if(!$student_school_id) $student_school_id = $default_student_school_id;
$gender_id = 0;
$gender_id_selected_2 = "";
$gender_id_selected_1 = "";


require_once("$file_dir_name/../external/db.php");
// here old require of common.php

if(($_SESSION["user_avail"] == "Y") and ($_SESSION["user_firstname"]) and (!$need_register)) 
{
	// die("rafik 2020-07-10 mySessions = ".var_export($_SESSION,true));
        header("Location: index.php");
} 
elseif(($_POST["student_mobile"]) and ($_POST["student_idn"]) and ($_POST["crm_go"]))
{
      if((!$config["sms-captcha"]) or (strtoupper($_POST["student_cpt"])==strtoupper($_SESSION["cpt"])))
      {
              // require_once student.php");
              
              $gender_id = intval($_POST["gender_id"]);
              
              if($gender_id==2)  
              {
                $gender_id_selected_2 = "selected";
                $gender_id_selected_1 = "";
              }
              else
              {
                $gender_id_selected_2 = "";
                $gender_id_selected_1 = "selected";
              }
              
              $student_mobile = AfwSession::hardSecureCleanString(strtolower(trim($_POST["student_mobile"])));
              $student_idn = AfwSession::hardSecureCleanString(trim($_POST["student_idn"]));
              $student_idn_ignore_check = trim($_POST["student_idn_ignore_check"]);
              $student_login_errors = array();
              
              list($student_idn_correct, $student_idn_type_id) = Student::getIdnTypeId($student_idn);
              if((!$student_idn_correct) and (!$student_idn_ignore_check))
              {
                   $student_login_errors["student_idn"] = "رقم الهوية غير صحيح";
              }
              
              $student_email = "";
              $student_mobile = Student::formatMobile($student_mobile);
              if(!Student::isCorrectMobileNum($student_mobile))
              {
                   $student_login_errors["student_mobile"] = "رقم الجوال غير صحيح";
                   //$student_mobile = "";
              }
              
              
                   
              
              if(count($student_login_errors)==0) 
              {
                 
                 if($student_mobile and $student_idn)
                 {
                     //die("je suis avant check student exists : student_idn_type_id=$student_idn_type_id and student_mobile=$student_mobile and student_idn=$student_idn ");
                     $studentObj = Student::loadByLoginInfos($student_mobile, $student_idn_type_id, $student_idn);
                     if($studentObj)
                     {
                          // die("student found ".var_export($studentObj,true));
                          $new_student = 0;
                          include("$file_dir_name/../sis/student_verify.php");
                          exit;
                          //include("$file_dir_name/../crm/student_ready.php");
                     }
                     else
                     {
                          if(!$register_new) 
                          {
                                $student_msg = "لم يتم التعرف على حساب $student_title أو يوجد خطأ في البيانات المدخلة";
                          }      
                          else
                          {
                                $student_msg = "دخول لأول مرة الرجاء استكمال البيانات الناقصة";
                                $need_register = true;
                          }
                          // die("je suis avant start register : need_register=$need_register and student_mobile=$student_mobile and student_idn=$student_idn , ".var_export($student_login_errors,true));
                          if($need_register and $student_mobile and $student_idn)
                          {
                                //die("preparing register new : need_register=$need_register and student_mobile=$student_mobile and student_idn=$student_idn , _POST=".var_export($_POST,true));
                                $gender_id = intval($_POST["gender_id"]);
                                $student_num = trim($_POST["student_num"]);
                                $student_email = trim($_POST["student_email"]);
                                $student_first_name = trim($_POST["student_first_name"]);
                                $student_father_name = trim($_POST["student_father_name"]);
                                $student_last_name = trim($_POST["student_last_name"]);
                                $student_school_id = trim($_POST["student_school_id"]);
                                
                                if(!$student_num)
                                {
                                   $student_login_errors["student_num"] = "الرقم الأكاديمي إلزامي";
                                }
                                          
                                if(!$student_first_name)
                                {
                                   $student_login_errors["student_first_name"] = "الإسم الأول إلزامي";
                                }
                                
                                if(!$student_father_name)
                                {
                                   $student_login_errors["student_father_name"] = "إسم الأب إلزامي";
                                }
                                
                                if(!$student_last_name)
                                {
                                   $student_login_errors["student_last_name"] = "الإسم الأخير إلزامي";
                                }
                                
                                if(!$student_school_id)
                                {
                                   $student_login_errors["student_school_id"] = "لا بد من تحديد المدرسة";
                                }
                                // die("je suis before student_verify for new _POST = ".var_export($_POST,true)." student_login_errors=".var_export($student_login_errors,true));
                                if(count($student_login_errors)==0) 
                                {
                                     // die("je suis avant student_verify for new ".var_export($_POST,true)." ");
                                     $new_student = 1;
                                     include("$file_dir_name/../sis/student_verify.php");
                                     exit;
                                     /*
                                     $studentObj = Student::loadByLoginInfos($student_mobile, $student_idn_type_id, $student_idn, true);
                                     if($studentObj)
                                     {
                                         $studentObj->set("genre_id",$genre_id);          
                                         $studentObj->set("firstname",$student_first_name);
                                         $studentObj->set("f_firstname",$student_father_name);
                                         $studentObj->set("lastname",$student_last_name);
                                         $studentObj->set("student_num",$student_num);
                                         $studentObj->set("school_id",$student_school_id);
                                         $studentObj->set("email",$student_email);
                                         
                                         $studentObj->commit();
                                     }
                                     */
                                }   
                                
                          }
                          
                     }
                 }
                 else
                 {
                      $student_login_message = "عزيزي $student_title الرجاء التثبت من البيانات المدخلة";
                 }
              }
              else
              {
                      $student_login_message = implode("<br>\n",$student_login_errors);
              }
              /*
              
              student_idn
              student*/
        }
        else
        {
        
              if($config["sms-captcha"])
              {
                    $student_login_message = "الرمز المدخل خطأ ";// . $_POST["student_cpt"] . " تختلف عن" . $_SESSION["cpt"];
              }
        }
        
}
else
{
        $student_mobile = AfwSession::hardSecureCleanString(trim($_GET["mb"]),true);
        $student_idn = AfwSession::hardSecureCleanString(trim($_GET["idn"]),true);
        $student_msg = "";
}


include("$file_dir_name/../lib/hzm/web/hzm_header.php");
if($desc_site)
{	
   echo "<div class='hzm_intro modal-dialog'>
              <div class='modal-header'>
                        <div>
                                <h2 class='title_intro'>$welcome_site</h2>        
                        </div>
              </div>
              <div class='modal-body'>
                   $desc_site
              </div>
         </div>";
}

if(!$student_mobile) $need_register = false;

if($need_register)
{
        // require_once school.php");
        $schoolObj = new School();
        $schoolObj->select("group_school_id", 111);
        $schoolObj->select("genre_id", $gender_id);
        $schoolObj->select_visibilite_horizontale();
        $schoolList = $schoolObj->loadMany();
        /*
        global $the_last_sql;
        echo "analysis_log : ".$_SESSION["analysis_log"];
        echo "last SQL : ".$the_last_sql;
        */
        $data_title = "بيانات ".$student_general_title;
}
else
{
        $data_title = "بيانات ".$student_general_title;
}
?>
<div class="home_banner">
<div class="modal-dialog popup-login">
        <div class="modal-header">                        
                    <?
                       if($student_msg)
                       {
                    ?>
                        <div class="quote">
                            <div class="quoteinn">
                               <p class='login_warning'><?=$student_msg?></p>
                            </div>
                        </div>
                    <? 
                       }         
                    ?>
        </div>
        <div class="modal-content">
                <div class="modal-header">
                        <div>
                                <a href="index.php" title="الرئيسسة">
                                        <img src="../sis/pic/logo.png" alt="<?=$student_login_by_sentence?>" title="<?=$student_login_by_sentence?>"></a>
                                        
                                <h2 class='title_login'>تسجيل دخول <?php echo $student_title?></h2>        
                        </div>
                </div>
                    <?
                       if($student_login_message)
                       {
                    ?>
                        <div class="quote">
                            <div class="quoteinn">
                               <p class='login_error'><?=$student_login_message?></p>
                            </div>
                        </div>
                    <? 
                       }         
                    ?>                    
                <div class="modal-body"><h1><?php echo $data_title?></h1><br>
                        <form id="formlogin1" name="formlogin1" method="post" action="<?php echo $login_submit_file ?>"  onSubmit="return student_checkForm();" dir="rtl" enctype="multipart/form-data">
                                <div class="form-group">
                                        <label>رقم الجوال
                                        </label>
                                        <input class="form-control" type="text" name="student_mobile" value="<?php echo $student_mobile?>" required>
                                </div>
                                <div class="form-group">
                                        <label><?php echo $student_idn_title ?>
                                        </label>
                                        <input type="text" class="form-control" name="student_idn" value="<?php echo $student_idn?>"  autocomplete="off" required>                                        
                                </div>
                                <input type="hidden" name="student_idn_ignore_check" value="<?php echo $student_idn_ignore_check?>">
<?php                                 
                                if((!$student_no_gender) or $need_register)
                                {
?>                                
                                <div class="form-group">
                                        <label>الجنس
                                        </label>
                                        <select class="form-control valid" name="gender_id" id="gender_id" size="1" required>
                                			<option value="0"></option>
                                                        <option value="1" <?php echo $gender_id_selected_1?>>ذكر</option>
                                			<option value="2" <?php echo $gender_id_selected_2?>>انثى</option>
                                	        
                                	</select>                                        
                                </div>                                
<?php                                
                                }
                                
                                if($need_register)
                                {       
?>
                                <input type="hidden" name="nrg" value="1">
                                <div class="form-group">
                                        <label>الاسم الأول
                                        </label>     
                                        <input class="form-control" type="text" name="student_first_name" value="<?php echo $student_first_name?>" required>
                                </div>
                                <div class="form-group">
                                        <label>اسم الأب
                                        </label>
                                        <input class="form-control" type="text" name="student_father_name" value="<?php echo $student_father_name?>" required>
                                </div>
                                <!-- div class="form-group">
                                        <label>اسم الجد
                                        </label>
                                        <input class="form-control" type="text" name="student_gfather_name" value="<?php echo $student_gfather_name?>" required>
                                </div>-->
                                
                                <div class="form-group">
                                        <label>الإسم الأخير
                                        </label>
                                        <input class="form-control" type="text" name="student_last_name" value="<?php echo $student_last_name?>" required>
                                </div>
                                
                                <div class="form-group">
                                        <label class="hzm_label hzm_label_student_num">الرقم الأكاديمي 
                                        </label>
                                        <input class="form-control" type="text" name="student_num" value="<?php echo $student_num?>">
                                        <?php if($student_register_errors["student_num"]) echo "<label id='student_num-error' class='error' for='student_num'>".$student_register_errors["student_num"]."</label>"; ?>
                                </div>
                                <div class="form-group">
                                        <label class="hzm_label hzm_label_student_email">البريد الالكتروني
                                        </label>
                                        <input class="form-control" type="text" name="student_email" value="<?php echo $student_email?>">
                                        <?php if($student_register_errors["student_email"]) echo "<label id='student_email-error' class='error' for='student_email'>".$student_register_errors["student_email"]."</label>"; ?>
                                </div>
                                <div class="form-group">
                                        <label class="hzm_label hzm_label_student_school_id">المدرسة
                                        </label>
                                        <select class="form-control valid" name="student_school_id" id="student_school_id" tabindex="0" onchange="" size="1" required="" aria-invalid="false">
                                               <?php
                                                   if(!$student_school_id)
                                                   {
                                               ?>
                                                        <option value="0" selected > </option>
                                               <?php
                                                   }
                                                   
                                                   foreach($schoolList as $school_id => $schoolObj)
                                                   {
                                                       $school_id_selected = ($student_school_id == $school_id) ? "selected" : "";
                                               ?>
                                			<option value="<?php echo $school_id?>" <?php echo $school_id_selected?> > <?php echo $schoolObj->getDisplay($lang)?></option>
                                               <?php
                                                   }
                                               ?>         
                                	</select>                                        
                                </div>
<?php                                
                                }
                                if($config["sms-captcha"])
                                {
?>
                                <div class="form-group">
                                        <label>أدخل الرمز
                                        </label>                                        
                                        <input type="text" class="form-control" name="student_cpt" value=""  autocomplete="off" required>
                                        <div class='hzm_captcha'>
                                                <img src="../lib/afw/afw_captcha.php" />
                                                <div class="hzm_help"> 
                                                        upper or lower case doesn't matter <br>
                                                        لا يهم حجم الحرف صغيرا كان أو كبيرا<br>
                                                        if the code is not clear please refresh this page<br>                                   
                                                            قم بتحديث الصفحة إذا لم يكن الرمز واضحا
                                                        
                                                </div>
                                        </div>                                        
                                </div>
<?php                                
                                }
?>
                                
                                <!-- logbl:<?php echo $logbl?> -->
                                <input type="submit" class="btnbtsp btn-primary btnregister" value="دخول" name="crm_go">&nbsp;
                                
                                
                        </form>
                </div>
        </div>
       
</div>
</div>
<?
  
  include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
?>