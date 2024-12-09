<?php
$file_dir_name = dirname(__FILE__);
set_time_limit(8400);
ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

AfwSession::startSession();
 

$logbl = substr(md5($_SERVER["HTTP_USER_AGENT"] . "-" . date("Y-m-d")),0,10);

$uri_items = explode("/",$_SERVER["REQUEST_URI"]);
if($uri_items[0]) $uri_module = $uri_items[0];
else $uri_module = $uri_items[1];

if(!$lang) $lang = "ar";
$module_dir_name = $file_dir_name;


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
        
require_once("$file_dir_name/../external/db.php");
// 

if(($_SESSION["user_avail"] == "Y") and ($_SESSION["user_firstname"])) 
{
	//die("rafik 2019-007 mySessions = ".var_export($_SESSION,true));
        header("Location: index.php");
} 
elseif($_POST["student_verify_go"])
{
      $student_verify_ec = $_POST["student_verify_ec"];
      $student_verify_code_check = md5($_POST["student_verify_code"]);
      $student_idn_ignore_check = trim($_POST["student_idn_ignore_check"]);
      // die("student_verify_go => student_verify_ec=$student_verify_ec vs student_verify_code_check=$student_verify_code_check ");

      if($student_verify_code_check==$student_verify_ec)
      {
              // require_once student.php");
              
              
              $new_student = $_POST["new_student"];
              $student_mobile = $_POST["student_mobile"];
              $student_email = $_POST["student_email"];
              $student_idn = $_POST["student_idn"];
              $gender_id = $_POST["gender_id"];
              $student_school_id = $_POST["student_school_id"];
              $student_first_name = $_POST["student_first_name"];
              $student_last_name = $_POST["student_last_name"];
              $student_father_name = $_POST["student_father_name"];
              $student_num = $_POST["student_num"];
              //die("marrouma : new=$new_student, fn=$student_first_name, mob=$student_mobile, gender_id=$gender_id");
              $student_register_errors = array();
              
              list($student_idn_correct, $student_idn_type_id) = Student::getIdnTypeId($student_idn);
              
              if(((!$student_idn_correct) or (!$student_idn_type_id)) and (!$student_idn_ignore_check))
              {
                   $student_register_errors["student_idn"] = "رقم الهوية غير صحيح";
              }
             
              $student_mobile = Student::formatMobile($student_mobile);
              if(!Student::isCorrectMobileNum($student_mobile))
              {
                    $student_register_errors["student_mobile"] = "رقم الجوال غير صحيح";
              }
              $student_email = trim($student_email);
              
              if($new_student and $student_email)
              {
                      if(!Student::isCorrectEmailAddress($student_email))
                      {
                            $student_register_errors["student_email"] = "عنوان البريد الالكتروني غير صحيح";
                            //$student_email = "";
                      }
              }
              
              if(count($student_register_errors)==0) 
              {
                     // die("marrouma : new=$new_student, fn=$student_first_name, mob=$student_mobile, gender_id=$gender_id");
                     $studentObj = Student::loadByMinInfos($student_mobile, $student_idn_type_id, $student_idn, $gender_id, $student_email, $student_school_id, $student_num, $student_first_name, $student_father_name, $student_last_name, $create_obj_if_not_found=$new_student);
                     if($new_student)
                     {
                             if($studentObj)
                             {
                                    include("$file_dir_name/../sis/student_ready.php");
                                    exit;
                             }
                             else
                             {
                                    $student_verify_msg = "أثناء الحفظ. حصل خطأ أرجوا التواصل مع المشرف أو المحاولة لاحقا";
                             }
                     }
                     else
                     {
                             if($studentObj)
                             {
                                    include("$file_dir_name/../sis/student_ready.php");
                                    exit;
                             }
                             else
                             {
                                    $student_verify_msg = "هذا الحساب  غير موجود. نأسف";
                             }
                     }
              }
              else
              {
                    $student_verify_msg = "تعذر الحفظ. يوجد أخطاء في البيانات : ".implode(", ", $student_register_errors);
              }
        }
        else
        {
                    $student_verify_msg = "الرمز المدخل خطأ ";// . $_POST["student_cpt"] . " تختلف عن" . $_SESSION["cpt"];
        }       
}
else
{
       // random code
       $student_verify_the_code = round(rand(1001,9998));
       $student_verify_the_message = "عزيزي العميل - رمز التحقق " . $student_verify_the_code;
       
       // send SMS to student
       include("$file_dir_name/../lib/hzm/sms/hzm.sms.php");
       $username = "rboubaker";
       $sms_application_id=40;
       
       $sms_encoding="utf-8";
       $sms_method = 'SendSMS';
       $SMS_PROCESS_ID = 1;
       $student_mobile_sms = $student_mobile;
       // if($MODE_DEVELOPMENT) $student_mobile_sms = "05989 88330";
       $sms_info = hzmSMS($student_mobile_sms, $student_verify_the_message, $username, $sms_application_id, $SMS_PROCESS_ID, $sms_encoding, $sms_method);
       
       $sms_info_input = "hzmSMS($student_mobile, $student_verify_the_message, $username, $sms_application_id, $SMS_PROCESS_ID, $sms_encoding, $sms_method)";
       $sms_info_export = var_export($sms_info,true);
       
       // encrypt code
       $student_verify_ec = md5($student_verify_the_code);
}

if(!$student_type_id) $student_type_id = 1;

include("$file_dir_name/../lib/hzm/web/hzm_header.php");

?>
<div class="home_banner">
<div class="modal-dialog popup-register popup-sms-verify">
        <div class="modal-content">
                <div class="modal-header">
                        <div>
                                <a href="#">
                                        <img src="../crm/pic/register.png" alt="" title="">
                                </a>
                                        
                                <h2 class='title_register'>التثبت من صحة رقم الجوال</h2>        
                        </div>
                </div>
                    <?
                       if($student_verify_msg)
                       {
                    ?>
                        <div class="quote">
                            <div class="quoteinn">
                               <p class='login_error'><?=$student_verify_msg?></p>
                            </div>
                        </div>
                    <? 
                       }         
                    ?>                    
                <div class="modal-body">
                        <form id="form_register" name="form_register" method="post" action="student_verify.php"  onSubmit="return register_checkForm();" dir="rtl" enctype="multipart/form-data">
                                <div class="form-group form-sms">
                                        <label>أدخل الرمز المرسل على جوالك <?php echo $student_mobile  .  " <!-- $sms_info_input => [sms_i:$sms_info_export] -->"; ?>
                                        </label>                                        
                                        <input type="text" class="form-control" name="student_verify_code" value="" tabindex=0 autofocus autocomplete="off" required>
                                        <input type="hidden" name="new_student" value="<?php echo $new_student?>" >
                                        <input type="hidden" name="student_mobile" value="<?php echo $student_mobile?>" >
                                        <input type="hidden" name="student_email" value="<?php echo $student_email?>" >
                                        <input type="hidden" name="student_idn" value="<?php echo $student_idn?>"  >
                                        <input type="hidden" name="gender_id" value="<?php echo $gender_id?>" />
                                        <input type="hidden" name="student_school_id" value="<?php echo $student_school_id?>" />
                                        <input type="hidden" name="student_first_name" value="<?php echo $student_first_name?>"  >
                                        <input type="hidden" name="student_father_name" value="<?php echo $student_father_name?>"  >
                                        <input type="hidden" name="student_last_name" value="<?php echo $student_last_name?>"  >
                                        <input type="hidden" name="student_num" value="<?php echo $student_num?>"  >
                                        <input type="hidden" name="student_verify_ec" value="<?php echo $student_verify_ec ?>">
                                        <input type="hidden" name="student_idn_ignore_check" value="<?php echo $student_idn_ignore_check?>">
                                        
                                </div>
                                <input type="submit" class="btnbtsp btn-primary btnregister" value="دخول" name="student_verify_go">&nbsp;
                                
                                
                        </form>
                </div>
        </div>
       
</div>
</div>
<?
  include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
?>