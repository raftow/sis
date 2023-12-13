<?php
include_once("coran_js.php");
//die("select aya params = ".var_export($params,true));
$bookId = $params["book_id"];
if(!$bookId)
{
    echo "bookId not defined params=".var_export($params,true);
    return;
}
if(!$pagination_sens) $pagination_sens = 1;
if($pagination_sens == -1)
{
    if($bookId==1) $bookId=10001;
    $chapterIdOffset = 11000;
}
else
{
    $chapterIdOffset = 1000;
}
$strict_from = $params["strict_from"];
$strict_from_php = ($strict_from=="true");
$paragraphNum = $params["paragraph_num"];
$paragraphNumTo = $params["paragraph_num_to"];
if(!$paragraphNumTo) $paragraphNumTo = $paragraphNum;
$chapterId = $params["chapter_id"];
$chapterIdFrom = $params["chapter_id_from"];
$chapterIdTo = $params["chapter_id_to"];
$chapterIdFromName = $params["chapter_id_from_name"];
if(!$chapterIdFromName) $chapterIdFromName = "السورة ".($chapterIdFrom-$chapterIdOffset);
$chapterIdToName = $params["chapter_id_to_name"];
if(!$chapterIdToName) $chapterIdToName = "السورة ".($chapterIdTo-$chapterIdOffset);
$right_page = $params["page_from"];
$start_hidden = $params["start_hidden"];
$left_page = $params["page_to"];
$pagination_sens = $params["pagination_sens"];

if(!$right_page) $right_page = $left_page - 1;  
if($right_page<=0) throw new RuntimeException("how can right_page/left_page be empty params=".var_export($params,true));
    
if(!$left_page) $left_page = $right_page+1;

if($left_page < $right_page+1) $left_page = $right_page+1;
//throw new RuntimeException("how can left_page=$left_page < right_page+1=$right_page+1 params=".var_export($params,true));
if($left_page > $right_page+1)
{
    $pages_separated = "separated";
}
else
{
    $pages_separated = "";
}

$modeInput = $params["mode_input"];
//$right_page = 191;

$bookObj = CpcBook::loadById($bookId);
$templateObj = $bookObj->getDefaultTemplate();
if($modeInput=="unique") $to_is_readonly = "readonly";
else $to_is_readonly = "";

$hide_help_start = ($modeInput=="interval-start") ? "" : "hide";
$hide_help_end = ($modeInput=="interval-end") ? "" : "hide";
$hide_help_lock = (($modeInput=="lock") or ($modeInput=="lock-unlockable")) ? "" : "hide";

$selecotr_start_help = "أنت بصدد تغيير آية البداية بشكل أولوي فاذا ضغطت وسط الآيات المظللة فسوف تتغير آية البداية  واذااردت تغيير النهاية بتقليص الآيات المظللة من جهة النهاية انقر هنا ";
$selecotr_end_help = "أنت بصدد تغيير آية النهاية بشكل أولوي فاذا ضغطت وسط الآيات المظللة فسوف تتغير آية النهاية واذااردت تغيير البداية بتقليص الآيات المظللة من جهة البداية انقر هنا ";
$selecotr_lock_help = "انقر للتعديل";
?>
<input type="hidden" name="<?php echo $col_name ?>_mode" id="<?php echo $col_name ?>_mode" value="<?php echo $modeInput ?>">
<input type="hidden" name="<?php echo $col_name ?>" id="<?php echo $col_name ?>" value="<?php echo $val ?>">
<input type="button" class="ayat-btn" name="ayat-btn-<?php echo $col_name ?>" id="ayat-btn-<?php echo $col_name ?>" value="<?=$buttonTitle?>" >
<?php
    $right_page_numFardi = (($right_page % 2)==1) ? "pfardi" : "pzawji";
    $left_page_numFardi = (($left_page % 2)==1) ? "pfardi" : "pzawji";
?>
<div class="hzm-selector-div <?php echo $start_hidden ?>" id="hzm-selector-div-<?php echo $col_name ?>">
    <div class="hzm-selecting-div" id="hzm-selecting-div-<?php echo $col_name ?>">
        <div class="book-page page<?=$right_page." $right_page_numFardi $pages_separated ".$objectAttribute." BK".$bookId?> pright fright" id="pright">
        <?php 
            list($page1Html, $displayedPragraphsPage1) = CpcBookPage::displayPage($bookObj, $right_page, $templateObj, $paragraphNum, $paragraphNumTo, $col_name, 1, $chapterId, $chapterIdFrom, $chapterIdTo, $pagination_sens, $strict_from_php);  
            echo $page1Html;
        ?>        
        </div> 
        <div class="book-page page<?=$left_page." $left_page_numFardi $pages_separated ".$objectAttribute." BK".$bookId?> pleft fright" id="pleft">
        <?php 
            list($page2Html, $displayedPragraphsPage2) = CpcBookPage::displayPage($bookObj, $left_page,  $templateObj, $paragraphNum, $paragraphNumTo, $col_name, 2, $chapterId, $chapterIdFrom, $chapterIdTo, $pagination_sens, $strict_from_php);  
            echo $page2Html;
        ?>           
        </div>              
    </div>
    <div class='selector-from-to'>    
    <div class='selector-num-title selector from'>من <?php echo $chapterIdFromName ?> الآية</div>
    <input placeholder="من الآية" type="number" tabindex="1" class="form-control hzm_numeric aya-num selector from" name="<?php echo $col_name ?>from" id="<?php echo $col_name ?>from" value="<?php echo $paragraphNum ?>">
    <div class='selector-num-title selector to'>إلى <?php echo $chapterIdToName ?> الآية</div>
    <input placeholder="إلى الآية" type="number" tabindex="2" class="form-control hzm_numeric aya-num selector to  " name="<?php echo $col_name ?>to" id="<?php echo $col_name ?>to" value="<?php echo $paragraphNumTo ?>" <?php echo $to_is_readonly ?>>
    </div>

    <?php if($start_hidden)  {  ?>
    <div class='selector-btns'>    
    <input type="button" class="close-btn" name="closebtn-<?php echo $col_name ?>" id="closebtn-<?php echo $col_name ?>" value="غلق">
    <input type="button" class="select-btn" name="select-btn-<?php echo $col_name ?>" id="select-btn-<?php echo $col_name ?>" value="اختيار">
    </div>
<?php }  ?>
    <div id="help-start-<?php echo $col_name ?>" class='selecotr-help start <?php echo $hide_help_start." o".$modeInput?>'><?=$selecotr_start_help ?></div>
    <div id="help-end-<?php   echo $col_name ?>" class='selecotr-help end <?php   echo $hide_help_end. " o".$modeInput?>'><?=$selecotr_end_help ?></div>
    <div id="help-lock-<?php   echo $col_name ?>" class='selecotr-help lock <?php   echo $hide_help_lock. " o".$modeInput?>'><?=$selecotr_lock_help ?></div>
</div>

<script>
    var chapter_from = <?php echo $chapterIdFrom ?>;
    var chapter_to = <?php echo $chapterIdTo ?>;
    var displayed_pragraphs_page1 = <?php echo $displayedPragraphsPage1 ?>;
    var displayed_pragraphs_page2 = <?php echo $displayedPragraphsPage2 ?>;
    function <?php echo $col_name ?>_get_paragraph_title(pnum)
    {
        <?php echo CpcBookPage::displayParagraphTitles($bookObj, $right_page, $left_page, $templateObj, $paragraphNum, $paragraphNumTo);  ?>
    }


    function <?php echo $col_name ?>_refresh_selector_old()
    {
        console.log('refresh_selector_old entered');
        from_start = parseInt($("#<?php echo $col_name ?>page1_start").val());
        to_end = parseInt($("#<?php echo $col_name ?>page2_end").val());

        from = parseInt($("#<?php echo $col_name ?>from").val());
        to = parseInt($("#<?php echo $col_name ?>to").val());
        console.log('from_start='+from_start);
        console.log('to_end='+to_end);
        
        for(i=from_start;i<=to_end;i++)
        {
            //console.log('i='+i);
            if((i>=from)&&(i<=to))
            {
                console.log('<?php echo $col_name ?> i='+i+' in '+from+','+to);
                $("#a<?php echo $col_name ?>p"+i).addClass("selected");
                $("#a<?php echo $col_name ?>num"+i).addClass("selected");
            }
            else
            {
                console.log('<?php echo $col_name ?> i='+i+' out '+from+','+to);
                $("#a<?php echo $col_name ?>p"+i).removeClass("selected");
                $("#a<?php echo $col_name ?>num"+i).removeClass("selected");
            }
        }
    }

    function strict_greater_than(a,b,strict)
    {
        return ((a>b) || (!strict) && (a==b));
    }

    function <?php echo $col_name ?>_refresh_selector_in_page(displayed_pragraphs_page, strict_from)
    {
        from = parseInt($("#<?php echo $col_name ?>from").val());
        to = parseInt($("#<?php echo $col_name ?>to").val());

        for(j=0;j<displayed_pragraphs_page.length;j++)
        {
            tmp_arr = displayed_pragraphs_page[j].split('-');
            paragraph_index = tmp_arr[0];
            chapter_index = tmp_arr[1];

            chapter_is_first = (chapter_index == chapter_from);
            chapter_is_last = (chapter_index == chapter_to);
            chapter_is_between = ((chapter_index > chapter_from) && (chapter_index < chapter_to));

            if(chapter_from < chapter_to)
            {
                paragraph_selected_in_first_chapter = (chapter_is_first && (strict_greater_than(paragraph_index,from,strict_from)));
                paragraph_selected_in_last_chapter = (chapter_is_last  && (paragraph_index<=to));
            }
            else
            {
                paragraph_selected_in_first_chapter = (chapter_is_first && (strict_greater_than(paragraph_index,from,strict_from)) && (paragraph_index<=to));
                paragraph_selected_in_last_chapter =  (chapter_is_last  && (strict_greater_than(paragraph_index,from,strict_from)) && (paragraph_index<=to));
            }
            idcp = paragraph_index+"c"+chapter_index;
            
            if(paragraph_selected_in_first_chapter || 
               paragraph_selected_in_last_chapter ||
               chapter_is_between)
            {
                console.log('<?php echo $col_name ?>: idcp='+idcp+' in psifc='+paragraph_selected_in_first_chapter+' psilc='+paragraph_selected_in_last_chapter+' cib='+chapter_is_between);                
                $("#a<?php echo $col_name ?>p"+idcp).addClass("selected");
                $("#a<?php echo $col_name ?>num"+idcp).addClass("selected");
            }
            else
            {
                console.log('<?php echo $col_name ?>: idcp='+idcp+' out cif='+chapter_is_first+' cil='+chapter_is_last+' from='+from+' to='+to);
                $("#a<?php echo $col_name ?>p"+idcp).removeClass("selected");
                $("#a<?php echo $col_name ?>num"+idcp).removeClass("selected");
            }
        }
    }

    function <?php echo $col_name ?>_refresh_selector(strict_from)
    {
        console.log('refresh_selector entered');

        <?php echo $col_name ?>_refresh_selector_in_page(displayed_pragraphs_page1,strict_from);
        <?php echo $col_name ?>_refresh_selector_in_page(displayed_pragraphs_page2,strict_from);
        
        
    }

    function <?php echo $col_name ?>_choose_selection()
    {
        from = parseInt($("#<?php echo $col_name ?>from").val());
        from_title = <?php echo $col_name ?>_get_paragraph_title(from);
        to = parseInt($("#<?php echo $col_name ?>to").val()); 
        to_title = <?php echo $col_name ?>_get_paragraph_title(to);
        <?=$jsFunction?>(from, from_title, to, to_title); 
        
    }  
    
    function <?php echo $col_name ?>_switch_to_start_mode()
    {
        if(($("#<?php echo $col_name ?>_mode").val()=="interval-end") ||
           ($("#<?php echo $col_name ?>_mode").val()=="lock-unlockable"))
        {   
            $("#<?php echo $col_name ?>_mode").val("interval-start");
            $("#help-start-<?php echo $col_name ?>").removeClass("hide");  
            $("#help-end-<?php echo $col_name ?>").addClass("hide");
            $("#help-lock-<?php echo $col_name ?>").addClass("hide");
        }

    }
    function <?php echo $col_name ?>_switch_to_end_mode()
    {
        if($("#<?php echo $col_name ?>_mode").val()=="interval-start")
        {
            $("#<?php echo $col_name ?>_mode").val("interval-end");
            $("#help-end-<?php echo $col_name ?>").removeClass("hide");  
            $("#help-start-<?php echo $col_name ?>").addClass("hide");     
            $("#help-lock-<?php echo $col_name ?>").addClass("hide");
        }
        
    }



    function <?php echo $col_name ?>_switch_mode()
    {
        if($("#<?php echo $col_name ?>_mode").val() == "interval-end")
        {
            <?php echo $col_name ?>_switch_to_start_mode();
        }
        else
        {
            <?php echo $col_name ?>_switch_to_end_mode();
        }
    }

    $(document).ready(function(){

        $(".ayat-btn").click(function()
                            { 
                                    id_arr = $(this).attr('id').split('-');
                                    colname = id_arr[2];  
                                    $("#hzm-selector-div-"+colname).removeClass("hide");            
                                    $(".alert-dismissable").fadeOut().remove();            
                            }
        );


        $(".close-btn").click(function()
                            { 
                                    id_arr = $(this).attr('id').split('-');
                                    colname = id_arr[2];            
                                    
                                    $("#hzm-selector-div-"+colname).addClass("hide");          
                            }
        );

        $("#<?php echo $col_name ?>from").blur(function()
                            { 
                                <?php echo $col_name ?>_refresh_selector(<?php echo $strict_from ?>); 
                                <?php echo $col_name ?>_choose_selection();       
                            }
        );

        $("#<?php echo $col_name ?>to").blur(function()
                            { 
                                <?php echo $col_name ?>_refresh_selector(<?php echo $strict_from ?>);        
                                <?php echo $col_name ?>_choose_selection();
                            }
        );

        $("#select-btn-<?php echo $col_name ?>").click(function()
                            {
                                <?php echo $col_name ?>_choose_selection(); 
                                $(".hzm-selector-div").addClass("hide");   
                            }
        );

        $("#help-start-<?php echo $col_name ?>").click(function()
                            {
                                <?php echo $col_name ?>_switch_to_end_mode();          
                            }
        );

        $("#help-end-<?php echo $col_name ?>").click(function()
                            {
                                <?php echo $col_name ?>_switch_to_start_mode();           
                            }
        );

        $("#help-lock-<?php echo $col_name ?>").click(function()
                            {
                                <?php echo $col_name ?>_switch_to_start_mode();           
                            }
        );

        $(".bookpg.col<?php echo $col_name ?>").click(function()
                            { 
                                id_arr = $(this).attr('id').split('-');   
                                pnum = id_arr[1];  
                                chapter = id_arr[2];  
                                mode = $("#<?php echo $col_name ?>_mode").val();
                                if((mode!="lock") && (mode!="lock-unlockable"))
                                { 
                                    if(mode=="unique")
                                    {
                                        $("#<?php echo $col_name ?>to").val(pnum);
                                        $("#<?php echo $col_name ?>from").val(pnum);
                                    }
                                    else
                                    {
                                        from = parseInt($("#<?php echo $col_name ?>from").val());
                                        to = parseInt($("#<?php echo $col_name ?>to").val());
                                        if(chapter_to==chapter_from)
                                        {
                                            if(mode=="interval-end")
                                            {
                                                if(pnum>=from)
                                                {
                                                    console.log('mode changing interval-end clicked '+pnum+' after start '+from+' we change to');
                                                    $("#<?php echo $col_name ?>to").val(pnum);
                                                    // to = parseInt($("#<?php echo $col_name ?>to").val()); 
                                                }
                                                else
                                                {
                                                    console.log('mode changing interval-end clicked '+pnum+' before start '+from+' we change from');
                                                    $("#<?php echo $col_name ?>from").val(pnum);
                                                    // from = parseInt($("#<?php echo $col_name ?>from").val());
                                                }
                                            }
                                            else
                                            {
                                                if(pnum<=to) 
                                                {
                                                    console.log('mode changing interval-start clicked '+pnum+' before end '+to+' we change from');
                                                    $("#<?php echo $col_name ?>from").val(pnum);
                                                    // from = parseInt($("#<?php echo $col_name ?>from").val());
                                                }
                                                else
                                                {
                                                    console.log('mode changing interval-start clicked '+pnum+' after end '+to+' we change to');
                                                    $("#<?php echo $col_name ?>to").val(pnum);
                                                    // to = parseInt($("#<?php echo $col_name ?>to").val()); 
                                                }
                                                
                                                
                                            }
                                        }
                                        else if(chapter==chapter_from)
                                        {
                                            if(mode=="interval-end")
                                            {
                                                console.log('mode multiple chapters : changing interval-end clicked on chapter from so switch to interval-start');
                                                <?php echo $col_name ?>_switch_to_start_mode();
                                                mode=="interval-start";
                                            }

                                            if(mode=="interval-start")
                                            {
                                                console.log('mode multiple chapters : changing interval-start clicked on chapter from so accepted');
                                                $("#<?php echo $col_name ?>from").val(pnum);
                                            }
                                        }
                                        else if(chapter==chapter_to)
                                        {
                                            if(mode=="interval-start")
                                            {
                                                console.log('mode multiple chapters : changing interval-start clicked on chapter to so switch to interval-end');
                                                <?php echo $col_name ?>_switch_to_end_mode();
                                                mode=="interval-end";
                                            }
                                            if(mode=="interval-end")
                                            {
                                                console.log('mode multiple chapters : changing interval-end clicked on chapter to so accepted');
                                                $("#<?php echo $col_name ?>to").val(pnum);
                                            }
                                        }
                                        
                                    }
                                    
                                    <?php echo $col_name ?>_refresh_selector(<?php echo $strict_from ?>);
                                    
                                    <?php echo $col_name ?>_choose_selection();
                                }
                            }
        );

        
    
    });
</script>