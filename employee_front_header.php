<?php
  if(!$my_theme)  $my_theme = "simple";
  $file_dir_name = dirname(__FILE__); 
  if(!$lang) $lang = "ar";
?>
<!DOCTYPE html>
<?php
$lang = strtolower($lang);
//die("after include front_header.php : _SESSION = ".var_export($_SESSION,true)); 
if($imposed_charset) $page_charset = $imposed_charset;
else 
{
    $page_charset = "UTF-8";
}
if($lang=="ar") $dir = "rtl";
else $dir = "ltr";

$objme = AfwSession::getUserConnected();

if(!$objme)
{
     $login_out_css = "sign-in";
     $login_out_classe = "login";
     $login_out_cl = "login";
     if(!$login_page) $login_page = $pages_arr["login"][$MODULE];
     $login_out_page = $login_page;
     if(!$login_out_page) $login_out_page = "login.php";
     if(!$login_button_title)
         $login_out_title = AfwLanguageHelper::tarjemOperator("LOGIN", $lang);
     else
         $login_out_title = $login_button_title; 
}
else
{
     $login_out_css = "sign-out";
     $login_out_classe = "login";
     $login_out_cl = "logout";
     $login_out_page = $pages_arr["logout"][$MODULE];
     if(!$login_out_page) $login_out_page = "logout.php";
     
     $login_out_title = AfwLanguageHelper::tarjemOperator("LOGOUT", $lang);    
}

$welcome_div = "";
if($objme)
{
        $welcome = $objme->translate("welcome",$lang);
        $firstname = $objme->getVal("firstname");
        $lastname = $objme->getVal("lastname");
        $welcome_user = "<span> $welcome </span><br>$firstname $lastname<i class=\"fa fa-user\"></i>";
        $welcome_div = "<div class=\"title_company_user\">$welcome_user</div>";
}
  
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$page_charset?>">

<link rel="stylesheet" href="../lib/css/jquery-ui-1.11.4.css">
<link rel="stylesheet" href="../lib/css/font-awesome.min-4.3.css">
<link rel="stylesheet" href="../lib/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tajawal%3A400%2C700&ver=5.5.1">

<link rel="stylesheet" href="../lib/css/front-application.css">
<link rel="stylesheet" href="../lib/css/front_app.css">
<link rel="stylesheet" href="../lib/css/material-design-iconic-font.min.css">
<link rel="stylesheet" href="../lib/bootstrap/bootstrap-v3.min.css">
<link href="../lib/attention/attention.css" rel="stylesheet">




<script src="../lib/js/jquery-1.12.0.min.js"></script>
<script src="../lib/bootstrap/bootstrap-v3.min.js"></script>

<script src="../lib/js/jquery.validate.js"></script>
<?php
  if(!$my_font) $my_font = "front";
  $app_name = AfwSession::config("application_name", ['ar'=>"license"]);
  $header_style = AfwSession::config("header_style", "header_thin");
  $main_module = AfwSession::config("main_module", "");

  $cmodule = AfwUrlManager::currentURIModule();
  $xmodule = AfwSession::getCurrentlyExecutedModule();
  $ymodule = "";
  if($xmodule)
  {
        $ymodule = "-".$xmodule;
        if($main_module and ($main_module != $xmodule)) $ymodule .= "-".$main_module;
  }

?>

<script src="../lib/js/jquery-ui-1.11.4.js"></script>

<script src="../lib/attention/attention.js"></script>
<script src="../lib/attention/attention_functions.js"></script>

<link rel="stylesheet" href="../lib/hijra/jquery.calendars.picker.css"/>
<script src="../lib/hijra/jquery.calendars.js"></script>
<script src="../lib/hijra/jquery.calendars.plus.js"></script>
<script src="../lib/hijra/jquery.calendars.picker.js"></script>
<script src="../lib/hijra/jquery.calendars.ummalqura.js"></script>

<!-- <msdropdown> -->
<link rel="stylesheet" type="text/css" href="../lib/msdropdown/css/msdropdown/dd.css" />
<script src="../lib/msdropdown/js/msdropdown/jquery.dd.js"></script>
<!-- </msdropdown> -->

<script src="./js/schedule-viewmodel.js"></script>
<script src="./js/module.js"></script>
        
<link href="../lib/css/autocomplete.css" rel="stylesheet" type="text/css">
<link href="../lib/css/responsive.css" rel="stylesheet" type="text/css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=$config["website-description"]?>">
<meta name="keywords" content="<?=$config["website-keywords"]?>">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=EDGE">
<meta http-equiv="X-Frame-Options" content="sameorigin">
<link href="pic/logo.png" rel="shortcut icon">

<title><?php echo $app_name["ar"]; ?></title>
<link href="../lib/css/def_<?=$lang?>_<?=$my_font?>.css" rel="stylesheet" type="text/css">
<link href="../lib/css/<?=$my_theme?>/style_common.css" rel="stylesheet" type="text/css">
<link href="../lib/css/<?=$my_theme?>/style_<?=$lang?>.css" rel="stylesheet" type="text/css">

<link href="../lib/css/<?=$header_style?>.css" rel="stylesheet">
<?php
        if($main_module and ($xmodule != $main_module))
        {
?>
            <link href="../<?=$main_module?>/css/module.css" rel="stylesheet" type="text/css" type="text/css">
<?php
        }

        if(!$no_common_css)
        {
?>
            <link href="../external/css/common.css" rel="stylesheet" type="text/css" type="text/css">
<?php
        }
?>
<link href="./css/module.css" rel="stylesheet" type="text/css" type="text/css">

<?php
        if($main_module and $cmodule and ($cmodule != $main_module))
        {
?>
<link href="../<?=$main_module?>/css/module_<?=$cmodule?>.css" rel="stylesheet" type="text/css" type="text/css">
<?php
        }        
        if($xmodule)
        {
?>
<link href="./css/xmodule<?=$xmodule?>.css" rel="stylesheet" type="text/css">
<?
        }
?>
<link href="../lib/skins/square/green.css" rel="stylesheet" type="text/css">
<link href="../lib/skins/square/red.css" rel="stylesheet" type="text/css">
<script src="../lib/js/icheck.js"></script>
<?php
        
        foreach($custom_scripts as $custom_script)
        {
                if($custom_script["type"]=="css")
                {
                        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$custom_script["path"]."\" />";
                }
                elseif($custom_script["type"]=="js")
                {
                        echo "<script  type=\"text/javascript\" src=\"".$custom_script["path"]."\" ></script>";
                }
                else die($custom_script["path"]." has unknown type");
        }
        //die(var_export($custom_scripts,true));

?>        
        
             
<script>
$(document).ready(function(){
  $('input.echeckbox').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '20%' // optional
  });

  $('input.rcheckbox').iCheck({
    checkboxClass: 'icheckbox_square-red',
    radioClass: 'iradio_square-red',
    increaseArea: '20%' // optional
  });
  
});
</script>
</head>


<body dir="<?=$dir?>" >
<div class="container">   
<?php
   

if(($system_date_format != "greg") and ($lang=="ar"))
{
        $hijri_date =AfwDateHelper::currentHijriDate("hdate_long",$DateSeparator="/");
        // die("hijri_date=$hijri_date");
        $hijri_date_arr = explode($DateSeparator,$hijri_date);
        $display_date_year = $hijri_date_arr[3];
        $display_date_day = $hijri_date_arr[1];
        $display_date_month = $hijri_date_arr[2];
}
else
{
        list($wday, $display_date_day, $display_date_month, $display_date_year) = current_greg_date_arr();
}

$mode_run = AfwSession::config("mode_unified_number", "dev");
if($mode_run) $mode_run = "-".$mode_run;
if(!$config["img-path"]) $config["img-path"] = "pic/";
if(!$config["img-company-path"]) $config["img-company-path"] = $config["img-path"];

?> 
<div class="hzm_front_header cms_container">
                        
        <div class="logo_company">  
                <img src="<?php echo $config["img-company-path"] ?>logo-company<?php echo $ymodule ?>.png" alt="" class="hzm_front_header_img"/> 
        </div>  
        <div class="title_company">  
                <img src="<?php echo $config["img-company-path"] ?>title-company<?php echo $ymodule ?>.png" alt="" class="hzm_front_header_img"/> 
        </div>
        <?php echo $welcome_div ?>
        <div class="logo_application">
                <img src="<?php echo $config["img-path"] ?>logo-application.png" alt="" class="hzm_front_header_img fleft"/>
        </div>
        <div class="title_application">
                <img src="<?php echo $config["img-path"] ?>title-application<?php echo $mode_run ?>.png" alt="" class="hzm_front_header_img fleft"/>
        </div>     
        <div class="calendar_bloc_g">
                <div id="year" class="calendar_year"><?php echo $display_date_year ?></div>
                <div class="calendar_day">
                <span class="dday"><?php echo $display_date_day ?></span>
                <br>
                <?php echo $display_date_month ?>
                </div>
        </div>
                                                        
</div>

<div class="hideScreen">
        <span class="menuBar openScreen">القائمة
        </span>
</div>
<nav id="front_main_menu" class="front_main_menu cms_container navbar navbar-inverse">
        <div class="container-fluid">
                <ul class="hzm_front_menu_bar hzm_front_menu_bar nav navbar-nav">
                        <!--<li class="navbar-header <?php echo ($methodName === "home") ? "active" : ""; ?>">
                                <a class="navbar-brand" href="home.php"><i class="fa fa-home"></i>الرئيسية</a>
                        </li>-->
                        <li class="hzm_<?php echo $login_out_cl ?>">
                                <a href="<?php echo $login_out_page ?>" class="a<?php echo $login_out_cl ?>"><i class="fas fa-<?php echo $login_out_css ?>-alt"></i><?php echo $login_out_title ?></a>                                
                        </li>
                        <?php
                                $arrMenu = include("$file_dir_name/../$MODULE/front_main_menu_arr.php");

                                foreach($arrMenu as $rowMenu)
                                {
                                        if($objme or $rowMenu["guest"])
                                        {
                        ?>
                        <li class="<?php echo ($methodName === $rowMenu["methodName"]) ? "active" : ""; ?>">
                                <a href="i.php?cn=<?php echo $rowMenu["controller"] ?>&mt=<?php echo $rowMenu["methodName"] ?>"><i class="fa fa-<?php echo $rowMenu["icon"] ?>"></i><?php echo $rowMenu["methodTitle"] ?></a>
                        </li>
                        <?php
                                        } 
                                }

                                $menus_special_arr = AfwSession::config("menus_special_1073", null);
                                if($menus_special_arr)
                                {
                                        foreach($menus_special_arr as $menu_item)
                                        {
                                                if(true)
                                                {
                                                        if(!$menu_item["hide_children"])
                                                        {
                                                                $li_class="class=\"dropdown\"";
                                                                $a_props="class=\"dropdown-toggle\" data-toggle=\"dropdown\"";
                                                                $a_href="#";
                                                                $caret_html = "<span class=\"caret\" style=\"padding: 0px 0px 0px 0px !important;\"></span>";
                                                        }
                                                        else
                                                        {
                                                                $li_class="";           
                                                                $a_props="";
                                                                $a_href=$menu_item["page"];
                                                                $caret_html = "";
                                                        }
                        ?>
                                <li <?php echo $li_class?>>
                                                <a <?php echo $a_props?> href="<?php echo $a_href?>"><?=$menu_item["menu_name"]?>
                                                <?php echo $caret_html?> 
                                                </a>
                                                <?php
                                                if(!$menu_item["hide_children"])
                                                {
                                                ?>
                                                <ul class='dropdown-menu'>
                                                <?   
                                                foreach($menu_item["folders"] as $menu_item_folder_id => $folder_arr)
                                                {
                                                        $menu_item_folder_title = $folder_arr["title"];
                                                        $menu_item_folder_page = $folder_arr["page"];
                                                        $menu_item_folder_css = $folder_arr["css"];
                                                        echo "<li><a href='$menu_item_folder_page' class='$menu_item_folder_css'>$menu_item_folder_title</a></li>\n"; 
                                                }
                                                
                                                foreach($menu_item["items"] as $menu_item_item_id => $item_arr)
                                                {
                                                     if(is_array($item_arr))   
                                                     {
                                                        $menu_item_title = $item_arr["title"];
                                                        $menu_item_page = $item_arr["page"];
                                                        $menu_item_css = $item_arr["css"];
                                                        echo "<li><a href='$menu_item_page' class='$menu_item_css'>$menu_item_title</a></li>\n"; 
                                                     }   
                                                }    
                                                        
                                                ?>
                                                </ul>
                                                <?php
                                                }
                                                ?>
                                </li>
                        
                        <?php
                                                }
                                                

                                        }
                                }
                        ?>
                
                </ul>
        </div>
</nav>
<script> 
        $(document).ready(function() {       
                $(".menuBar").click(function(){
                        $("#front_main_menu").toggleClass("active");
                });
        });
</script>

<div class="notification_message_container">  
<?php
   if(AfwSession::getSessionVar("error"))
   {
?>
                <div class="alert messages messages--error alert-dismissable" role="alert" ><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <div class="swal2-hzm swal2-icon swal2-error swal2-icon-show" style="display: flex;">
                        <span class="swal2-x-mark">
                                <span class="swal2-x-mark-line-left"></span>
                                <span class="swal2-x-mark-line-right"></span>
                        </span>
                </div>

                <?php 
                  $cnt = count(explode("<br>",AfwSession::getSessionVar("error")));
                  if ($cnt>1)
                  {
                ?>
                يوجد أخطاء : <br>
                <?php 
                  }
                  echo AfwSession::pullSessionVar("error","header"); 
                ?>
                </div><br>

<?php
   }

   if(AfwSession::getSessionVar("warning"))
   {
?>
                <div class="alert messages messages--warning alert-dismissable" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <div class="swal2-hzm swal2-icon swal2-warning swal2-icon-show" style="display: flex; color: orange; border-color: orange;">
                        <div class="swal2-icon-content">!</div>
                </div>

                <?php 
                  $cnt = count(explode("<br>",AfwSession::getSessionVar("warning")));
                  if ($cnt>1)
                  {
                ?>
                يوجد تنبيهات : <br>
                <?php 
                  }
                  echo AfwSession::pullSessionVar("warning","header"); 
                ?>
                </div><br>
<?php
   }

   if(AfwSession::getSessionVar("information"))
   {
?>
                <div class="alert messages messages--status  alert-dismissable <?=AfwSession::getSessionVar("information-class")?>" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <div class="swal2-hzm swal2-icon swal2-info swal2-icon-show" style="display: flex;">
                        <div class="swal2-icon-content">i</div>
                </div>

                <?php echo AfwSession::pullSessionVar("information","header");?>
                </div><br>
<?php
   }
   
   if(AfwSession::getSessionVar("success"))
   {
?>
                <div class="alert messages messages--success alert-dismissable  <?=AfwSession::getSessionVar("information-class")?>" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <div class="swal2-hzm swal2-icon swal2-success swal2-icon-show" style="display: flex;">
                        <!--<div class="swal2-success-circular-line-left" style="background-color: rgb(227 244 253);"></div>-->
                        <span class="swal2-success-line-tip"></span> 
                        <span class="swal2-success-line-long"></span>
                        <div class="swal2-success-ring"></div> 
                        <!--<div class="swal2-success-fix" style="background-color: rgb(227 244 253);"></div>
                        <div class="swal2-success-circular-line-right" style="background-color: rgb(227 244 253);"></div>-->
                </div>

                        <?php echo AfwSession::pullSessionVar("success","header");?>
                </div><br>
<?php
   }
   
?> 
            </div>
<!-- #END OF Header -->