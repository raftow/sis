<?php

$file_dir_name = dirname(__FILE__);
set_time_limit(8400);
ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);


 
if(!$student_idn_title) $student_idn_title = "رقم الهوية";

$logbl = substr(md5($_SERVER["HTTP_USER_AGENT"] . "-" . date("Y-m-d")),0,10);

$uri_items = explode("/",$_SERVER["REQUEST_URI"]);
if($uri_items[0]) $uri_module = $uri_items[0];
else $uri_module = $uri_items[1];

if(!$lang) $lang = "ar";
$module_dir_name = $file_dir_name;

$need_register = $_REQUEST["nrg"];
$sql_log_forced = true;


require_once ("$file_dir_name/../lib/afw/afw_autoloader.php");
require_once("$file_dir_name/../external/db.php");

AfwAutoLoader::addMainModule("sis");

if(!$uri_module) die("site code not defined !!!");
else
{ 
   require_once("$file_dir_name/../$uri_module/ini.php");
   require_once("$file_dir_name/../$uri_module/module_config.php");
   require_once("$file_dir_name/../$uri_module/application_config.php");
}
// die("DBG-begin of session start");
AfwSession::startSession();

AfwSession::initConfig($config_arr);
AfwSession::startSession();



// die("DBG-session started");
if(!$objme) $objme = AfwSession::getUserConnected();

$log_arr = [];

if(($_POST["start_pos"]) and ($_POST["order_delta"]) and ($_POST["order_prg_go"]))
{
    $start_pos = $_POST["start_pos"];
    $order_delta = $_POST["order_delta"];

    list($part_num, $chapter_num, $page_num, $paragraph_num, $chapter_sens) = explode("/",$start_pos);
    list($delta_paragraph, $delta_lines, $delta_pages, $lines_to_paragraph_method, $new_page_where, $new_chapter_method) = explode("/",$order_delta);
    if(!$new_chapter_method) $new_chapter_method="chapter-nearest";
    if(!$lines_to_paragraph_method) $lines_to_paragraph_method="nearest";
    if(!$new_page_where) $new_page_where="end";
    $book_id = 1;
    $part_id = $part_num + 1;
    $chapter_id = 1000+$chapter_num;
    if(!$estimated_delta_pages) $estimated_delta_pages=10;

    list($book_id, $new_part_id, $new_chapter_id, $new_page_num, $new_paragraph_num, $log_arr) = CpcBookParagraph::moveInParagraphs($book_id, $part_id, $chapter_id, $page_num, $paragraph_num, 
                $chapter_sens, $delta_paragraph, $delta_lines, $delta_pages, 
                $lines_to_paragraph_method, $new_page_where, $new_chapter_method,
                $estimated_delta_pages, true);
}
include("$file_dir_name/../lib/hzm/web/hzm_header.php");
?>

<div class="home_banner">
<div class="modal-dialog console">
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
                
                    <?
                       if($order_prg_message)
                       {
                    ?>
                        <div class="quote">
                            <div class="quoteinn">
                               <p class='login_error'><?=$order_prg_message?></p>
                            </div>
                        </div>
                    <? 
                       }         
                    ?>                    
                        <form id="formlogin1" name="formlogin1" method="post" dir="ltr" enctype="multipart/form-data">
                                <div class="form-group">
                                        <label>نقطة الانطلاق<br>part_num/chapter_num/page_num/paragraph_num/chapter_sens
                                        </label>
                                        <input class="form-control" type="text" name="start_pos" value="<?php echo $start_pos?>" required>
                                </div>
                                <div class="form-group">
                                        <label>الأمر بالتحرك<br>delta_paragraph/delta_lines/delta_pages/lines_to_paragraph_method/new_page_where/new_chapter_method
                                        </label>
                                        <input class="form-control" type="text" name="order_delta" value="<?php echo $order_delta?>" required>
                                </div>
                                <input type="submit" class="btnbtsp btn-primary btnregister" value="نفذ" name="order_prg_go">&nbsp;
                                
                                
                        </form>
<div style='direction:ltr;text-align:left;font-size:18px;line-height:28px'>
<?php
echo implode("<br>\n",$log_arr);
?>
</div>
</div>
</div>
