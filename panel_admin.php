<?php
$file_dir_name = dirname(__FILE__); 
require_once ("$file_dir_name/ini.php");
require_once ("$file_dir_name/../external/db.php");
// here old require of common.php
$only_members = false;
include("$file_dir_name/../pag/check_member.php");


include("$file_dir_name/../lib/hzm/web/hzm_header.php");


include("admin_menu.php");

include("$file_dir_name/../lib/hzm/web/hzm_footer.php");
?>