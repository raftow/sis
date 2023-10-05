<?php

$file_dir_name = dirname(__FILE__);


// require_once ini.php");
// require_once module_config.php");

require_once("$file_dir_name/../external/db.php");
// here old require of common.php
        


set_time_limit(8400);
ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

AfwSession::startSession();

$uri_items = explode("/",$_SERVER["REQUEST_URI"]);
if($uri_items[0]) $uri_module = $uri_items[0];
else $uri_module = $uri_items[1];

if(!$lang) $lang = "ar";
$module_dir_name = $file_dir_name;

$only_members = true;
include("$file_dir_name/../pag/check_member.php");

if(!$objme) $objme = AfwSession::getUserConnected();

// require_once school.php");
// require_once school_config.php");
require_once("$file_dir_name/../lib/afw/afw_manage_motor.php");
                         

                         




foreach($_POST as $col => $val) ${$col} = $val;


if(!$next_step)
{
       $school = new School;
       include("$file_dir_name/../lib/hzm/web/hzm_header.php");
       include("$file_dir_name/../sis/registerSchool_step0.php");
       include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
}
elseif($next_step==1)
{
   
   
   $school = null;
   
   $school_error = "";   
   // 1. check if the infos are correct
   
   // 2. check if this school already exists
   $school_exists = "";
   
   //2.0 delete aready disabled account with same idn or same 
   $old_disabled_school_exists_obj = new School();
   $old_disabled_school_exists_obj->select("active","N");
   $old_disabled_school_exists_obj->select("maps_location_url",$maps_location_url);
   if($old_disabled_school_exists_obj->load()) $old_disabled_school_exists_obj->delete();
   
   unset($old_disabled_school_exists_obj);
   
   $old_disabled_school_exists_obj = new School();
   $old_disabled_school_exists_obj->select("active","N");
   $old_disabled_school_exists_obj->select("school_name_ar",$school_name_ar);
   if($old_disabled_school_exists_obj->load()) $old_disabled_school_exists_obj->delete();
   
   // 2.1 check if the IDN was already used
   $school_exists_obj = new School();
   $school_exists_obj->select("maps_location_url",$maps_location_url);
   $school_exists_obj->select("active","Y");
   if($school_exists_obj->load())
   {
       if($school_exists_obj->getVal("school_name_ar")==$school_name_ar)
       {
            $school_exists = School::traduireMessage("SCHOOL_EXISTS_SAME_LOCATION_SAME_NAME","sis");;
       }
       else
       {
            $school_exists_name_ar = $school_exists_obj->getVal("school_name_ar");
            $school_exists = School::traduireMessage("SCHOOL_EXISTS_SAME_LOCATION_WITH_ANOTHER_NAME","sis") . $school_exists_name_ar;
       }
       
   }
   else
   {
       // 2.1 check if the mobile was already used
       unset($school_exists_obj);
       $school_exists_obj = new School();
       $school_exists_obj->select("school_name_ar",$school_name_ar);
       $school_exists_obj->select("active","Y");
       if($school_exists_obj->load())
       {
            $school_error = School::traduireMessage("SCHOOL_NAME_ALREADY_EXISTS","sis");
       }
       else
       {
   
            if(is_array($period_mfk))
        		$period_mfk_val = ','.implode(',', $period_mfk).',';
            else
        		$period_mfk_val = $period_mfk;
        
        
            $school = new School;
            $school->select("school_name_ar",$school_name_ar);
            $school->select("active","N");
            $school->select("created_by",$me);
            $school->load();
            // create school not activated 
            $school->set("active","N");
            $school->set("school_type_id",        $school_type_id);
            $school->set("school_name_ar",        $school_name_ar);
            $school->set("school_name_en",        $school_name_en);
            $school->set("genre_id",              $genre_id);
            $school->set("lang_id",               $lang_id);
            $school->set("scapacity",             $scapacity);
            $school->set("period_mfk",            $period_mfk_val);
            $school->set("city_id",               $city_id);
            $school->set("address",               $address);
            $school->set("quarter",               $quarter);
            $school->set("maps_location_url",     $maps_location_url);
        
            
            if($school->commit())
            {
                 $school_id = $school->getId();
                 $orgunit_id = $school->getVal("orgunit_id");
                 $school_creation_success = (($school_id>0) and ($orgunit_id>0));
            }
            else  $school_creation_success = false;
            
            if(!$school_creation_success)
            {
                 $school_error = School::traduireMessage("SCHOOL_CREATION_FAIL","sis");
                 
            }     
        }
        
        
        $iam_admin = $objme->isAdmin();
            
        if($school_creation_success)
        {
                   $_SESSION["success"] = School::traduireMessage("SCHOOL_CREATION_SUCCESS","sis");
        }
        else
        {
                   $_SESSION["error"] = trim($school_error . " " . $school_exists);
        }
            
        if($iam_admin) 
        {
                   $school->activate();
                   $_SESSION["information"] = School::traduireMessage("SCHOOL_ACTIVATION_SUCCESS","sis");
        }
        else
        {
                   $_SESSION["information"] = School::traduireMessage("SCHOOL_ACTIVATION_ASSIGNED_TO_REAYA_WORKER","sis");
        }
    }
    
    $employee_creation_success = true;
    
    if($school_creation_success and $objme and $me)
    {
          // create an employee account for me as director of this school
          // require_once("/employee.php");  
     
          $empl = Employee::loadByMainIndex($orgunit_id, $objme->getVal("idn_type_id"), $objme->getVal("idn"), $create_obj_if_not_found=true);
     
          $empl->set("auser_id", $me);
          $empl->set("gender_id", $objme->getVal("genre_id"));
          $empl->set("firstname", $objme->getVal("firstname"));
          $empl->set("f_firstname", $objme->getVal("f_firstname"));
          $empl->set("lastname", $objme->getVal("lastname"));
          $empl->set("country_id", $objme->getVal("country_id"));
          $empl->set("address", $objme->getVal("address"));
          $empl->set("city_id", $objme->getVal("city_id"));
          $empl->set("mobile", $objme->getVal("mobile"));
          
          $empl->set("job", School::traduireMessage("SCHOOL_DIRECTOR","sis"));
          $empl->set("jobrole_mfk", ",1,");

          if($empl->commit())
          {
               $empl_id = $empl->getId();
               $employee_creation_success = ($empl_id>0);
          }
          else  $employee_creation_success = false;
          
          if(!$employee_creation_success)
          {
               $_SESSION["warning"] = School::traduireMessage("EMPLOYEE_CREATION_FAIL","sis");
          }
       

    }
    
   
    if($school_creation_success and $employee_creation_success)
    {
        $schoolConf = new SchoolConfig();
        $schoolConf->set("school_id",$school_id);
        include("$file_dir_name/../lib/hzm/web/hzm_header.php");
        include("$file_dir_name/../sis/registerSchool_step1.php");
        include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
    }
   
}
elseif($next_step==2)
{
        // 2.1 check if the mobile was already used
        unset($school_conf);
        $school_conf = new SchoolConfig;
        $school_conf->select("school_id",$school_id);;
        $school_conf->select("active","Y");
        $school_conf->load();
            
        $school_conf->set("school_id",       $school_id);
        $school_conf->set("nb_rooms",        $nb_rooms);
        $school_conf->set("holidays",        $holidays);
        $school_conf->set("levels",          $levels);
        $school_conf->set("courses",         $courses);
        
        if($school_conf->commit())
        {
                $school_conf_id = $school_conf->getId();
                $school_conf_creation_success = ($school_conf_id>0);
        }
        else  $school_conf_creation_success = false;
        
        if(!$school_conf_creation_success)
        {
                $school_conf_error = SchoolConf::traduireMessage("CREATION_FAIL","sis");
         
        }     
        
        
        
        $iam_admin = $objme->isAdmin();
            
        if($school_creation_success)
        {
                   $_SESSION["success"] = SchoolConf::traduireMessage("CREATION_SUCCESS","sis");
        }
        else
        {
                   $_SESSION["error"] = trim($school_conf_error);
        }
   
        header("Location: index.php");
}

?>

<?
  
?>
