<?php
die("This file should be obsolete : rafik : 20/10/1438");

/*
$file_dir_name = dirname(__FILE__); 
include_once("$file_dir_name/../external/db.php");
// 
require_once("$file_dir_name/../lib/afw/afw_debugg.php");
require_once("$file_dir_name/../lib/afw/afw_ini.php");


require_once("$file_dir_name/hday.php");

require_once("$file_dir_name/holiday.php");


$only_admin = true;
$debug_name = "debugg_hijgen";


include("$file_dir_name/../lib/hzm/web/hzm_header.php");
        
$from = $_GET["from"];
$to = $_GET["to"];

$school_year_id = $_GET["sy"];


$holi = new Holiday();

$nb = 0;

$arr_hij_days = genereHijriPeriod($from,$to); 
$first_hdate = "";

$numjour_year = 0; 

foreach($arr_hij_days as $gdate => $hij_row)
{
        if(!$first_hdate) $first_hdate = $hij_row["hdate"];
         $hd = new Hday();
         $hd->set("wday_id",$hij_row["wday"]);
         $hd->set("hday_gdat",$gdate);
         $hd->set("hday_date",$hij_row["hdate"]);
         $semester = substr($hij_row["hdate"],4,2);
         //$active = "Y";
         //$hd->set("active",$active);
         if($hij_row["free"]=="N") 
         {
                $holObj = $holi->getHoliday($school_year_id,$hij_row["hdate"]);
                if ($holObj != null)
                {
                      $free = "Y";
                      $free_comment = $holObj->getDisplay();
                      $hol_id = $holObj->getId();
                      $numjour = 0;
                }
                else 
                {
                      $free = "N";
                      $free_comment = "";
                      $hol_id = 0;
                      $numjour_year++;
                      $numjour = $numjour_year;
                }
         }        
         else 
         {
                $free = "Y";
                $free_comment = $hij_row["descr"];
                $hol_id = 0;
                $numjour = 0;
         }
         
         $hd->set("holiday",$free);
         $hd->set("holiday_id",$hol_id);
         $hd->set("school_year_id",$school_year_id);
         $hd->set("hday_descr",$free_comment);
         $hd->set("sday_num",$numjour);
         $hd->set("semester",$semester);
         
         if($hd->insert()) $nb++;
}

$last_hdate = $hij_row["hdate"];

echo "$nb hdays including $numjour_year open days generated from $first_hdate to $last_hdate";




include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
*/
?>

