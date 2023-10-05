<div class="cms_bg_pic">
<div class="content_big_title registration"><?php echo $title?>
<?php

if(count($schoolList )==0)
{
?>
<div class='hzm_data_prop hzm_message'>لا يوجد مدارس تشرف عليها حاليا</div>
<?
}

?>

</div>    
<div class="cms_bg">
<?
if(count($schoolList)>0)
{
        foreach($schoolList as $schoolObj)     
        {
                require("school_minibox_tpl.php");
        }
}
?>
</div>
</div>
