<?php
    $schoolClassItemId = $schoolClassItem->id;
    $schoolClassItem_disp = $schoolClassItem->getVal("class_name");
        if($schoolClassItem->showErrorsAsSessionWarnings("edit"))
        {
            $err = "";
            $war = "";
            $inf = "";
            list($is_obj_ok,$dataErr) = $schoolClassItem->isOk(true,true);
            if(!$is_obj_ok)
            {
                $error_message = implode("<br>",$dataErr);
                $error_count = count($dataErr);
                $error_class = "error";
            }
            else
            {
                $error_message = "";
                $error_count = 0;
                $error_class = "";
            }

            if($error_count>0) $schoolClassItem_disp .= " ($error_count أخطاء)";
        }

    $cssObj = null;
    $css_status_arr = [];
    if($schoolClassItem->mode_minibox["current"]) 
    {
        $cssObj = $schoolClassItem->getCurrentCourseSession();
        $css_status_arr[] = "حالية";
    }

    if((!$cssObj) and $schoolClassItem->mode_minibox["stdby"]) 
    {
        $cssObj = $schoolClassItem->getStdByCourseSession();
        $css_status_arr[] = "معلقة";
    }
    if((!$cssObj) and $schoolClassItem->mode_minibox["finished"]) 
    {
        $cssObj = $schoolClassItem->getLastFinishedCourseSession();
        $css_status_arr[] = "منتهية";
    }
   
    // no hissa complete
    if(!$cssObj)
    {
        $css_status = implode(" أو ", $css_status_arr);

        $cssObj_fix_url = "<a target='_myschoolclass' class='schoolclass title icon error' href='main.php?Main_Page=afw_mode_edit.php&cl=SchoolClass&id=$schoolClassItemId&currmod=sis&currstep=13'>
                            لا يوجد حصة $css_status      
                        </a>";

        $prof_status = "absent";
        
    }
    else
    {
        $session_status_id = $cssObj->getVal("session_status_id");
        $prof_status = ($session_status_id == SessionStatus::$missed_session) ? "absent" : "present";
        list($is_obj_ok,$dataErr) = $schoolClassItem->isOk(true,true);
        
            if(!$is_obj_ok)
            {
                $css_error_message = implode("<br>",$dataErr);
                $css_error_count = count($dataErr);
                $css_error_class = "error";
            }
            elseif($session_status_id == SessionStatus::$missed_session)
            {
                $css_error_message = "";
                $css_error_count = 0;
                $css_error_class = "error";
            }
            else
            {
                $css_error_message = "";
                $css_error_count = 0;
                $css_error_class = "ok";
            }
        $cssObj_title = $cssObj->getShortDisplay($lang);
        $cssId = $cssObj->id;
        $cssObj_fix_url = "<a target='_myschoolclass' class='schoolclass title icon $css_error_class' href='main.php?Main_Page=afw_mode_edit.php&cl=CourseSession&id=$cssId&currmod=sis&currstep=2'>
            $cssObj_title
        </a>";
    }
    // 
    // hissa incomplete
    // main.php?Main_Page=afw_mode_edit.php&cl=CourseSession&id=2|10|1|1|احسن%20الحديث|2023-08-01|3&currmod=sis&currstep=2



    $arrObjectsRelated = [];
    $arrObjectsRelated["courseSession"] = $cssObj;

    if($schoolClassItem->currentProf) $prof_name = $schoolClassItem->currentProf->getShortDisplay($lang);
    else $prof_name = $cssObj ? $cssObj->showAttribute("prof_id") : $schoolClassItem->showAttribute("prof_id");
    
    if((!$prof_name) or ($prof_name=="0"))
    {
        if(!$cssObj)
        {
                $no_prof_message = " --- ";
        }
        else
        {
                $no_prof_message = "لا يوجد معلم";
        }
        $prof_name = "<a target='_myschoolclass' class='schoolclass prof title icon error' href='main.php?Main_Page=afw_mode_edit.php&cl=SchoolClass&id=$schoolClassItemId&currmod=sis&currstep=4'>
                        $no_prof_message
                      </a>";
    }
    else
    {
        if($session_status_id == SessionStatus::$missed_session)
        {
            $prof_name .= " غائب";
            $prof_css = "error";
        }
        else
        {
            $prof_css = "ok";
        }

        $prof_name = "<a target='_myschoolclass' class='schoolclass prof title icon $prof_css' href='#'>
            $prof_name
        </a>";
    }

    $session_name = $cssObj_fix_url;
    // list($needed_stdn, $room_comment, $room_capacity, $stdn_count) = $schoolClassItem->getPlacesInfo(false);

    $arrSCIndicators = [];

    $arrSCIndicators["Capacity"]  = ['class'=>"gray", 'sens'=>'asc'];
    $arrSCIndicators["Mainwork"]  = ['class'=>"vert", 'sens'=>'asc'];
    $arrSCIndicators["Homework"]  = ['class'=>"blue", 'sens'=>'asc'];
    $arrSCIndicators["Homework2"] = ['class'=>"cade", 'sens'=>'asc'];
    $arrSCIndicators["Absence"]   = ['class'=>"vert", 'sens'=>'desc', 'orange_pct'=>1, 'red_pct'=>2];
    $arrSCIndicators["MainworkIncomplete"]  = ['class'=>"vert", 'sens'=>'desc', 'orange_pct'=>1, 'red_pct'=>2];
    $arrSCIndicators["HomeworkIncomplete"]  = ['class'=>"blue", 'sens'=>'desc', 'orange_pct'=>1, 'red_pct'=>2];
    $arrSCIndicators["Homework2Incomplete"]  = ['class'=>"cade", 'sens'=>'desc', 'orange_pct'=>1, 'red_pct'=>2];
    

    $arrIndicatorsResults = [];
        
        foreach($arrSCIndicators as $indicator => $rowIndicator)
        {
            foreach($rowIndicator as $ikey => $ikey_value) ${"normal_".$ikey} = $ikey_value;

                list($arrIndicatorsResults[$indicator]["objective"], 
                     $arrIndicatorsResults[$indicator]["value"], 
                     $arrIndicatorsResults[$indicator]["value_class"],
                     $arrIndicatorsResults[$indicator]["objective_red"],
                     $arrIndicatorsResults[$indicator]["objective_orange"],
                     ) 
                     = SisObject::executeIndicator($schoolClassItem, $indicator, $normal_class, $arrObjectsRelated, $normal_sens, $normal_red_pct, $normal_orange_pct);

                if(!$arrIndicatorsResults[$indicator]["objective"]) $arrIndicatorsResults[$indicator]["objective"] = $normal_objective;
                if(!$arrIndicatorsResults[$indicator]["objective"]) 
                {
                    $arrIndicatorsResults[$indicator]["value_class"] = "disabled";
                    ${"theSC".$indicator."Pct"} = "&#9959;";
                    $arrIndicatorsResults[$indicator]["objective"] = "&#9959;";
                    $arrIndicatorsResults[$indicator]["value"] = "&#9959;";
                    ${"theSC".$indicator."Missed"} = "&#9959;";

                }
                else
                {
                    ${"theSC".$indicator."Pct"} = round(100.0*$arrIndicatorsResults[$indicator]["value"]/$arrIndicatorsResults[$indicator]["objective"]);
                    ${"theSC".$indicator."Missed"} = $arrIndicatorsResults[$indicator]["objective"] - $arrIndicatorsResults[$indicator]["value"];
                    if(${"theSC".$indicator."Missed"}<0) ${"theSC".$indicator."Missed"} = 0;
                }

                ${"theSC".$indicator."Objective"} = $arrIndicatorsResults[$indicator]["objective"];
                ${"theSC".$indicator."Value"} = $arrIndicatorsResults[$indicator]["value"];
                
                
                ${"theSC".$indicator."ValueClass"} = $arrIndicatorsResults[$indicator]["value_class"];
                ${"theSC".$indicator."Sens"} = $arrIndicators[$indicator]["sens"];
                ${"theSC".$indicator."ObjectiveRed"} = $arrIndicatorsResults[$indicator]["objective_red"];
                ${"theSC".$indicator."ObjectiveOrange"} = $arrIndicatorsResults[$indicator]["objective_orange"];
        }

?>

<div class='my_school_ticket sclass width_pct_50'>
            <div class='my_school_ticket transp timbre2 width_pct_100 big_label top'>
                    <div class='icon transp fix_pct_12'><a href='#'><img src="./pic/prof_<?php echo $prof_status ?>.png"></a>
                    </div>
                    <div class='icon transp fix_pct_75'>
                        <a target='_myschoolclass' class='sis title' href='./m.php?mp=ed&cl=SchoolClass&id=<?php echo $schoolClassItem->id ?>&currmod=sis&currstep=1'>حلقة <?php echo $schoolClassItem_disp?></a> 
                    </div>
                    <div class='icon transp fix_pct_12'>
                        <a target='_myschoolclass' class='sis title icon <?php echo $error_class ?>' href='./m.php?mp=ed&cl=SchoolClass&id=<?php echo $schoolClassItem->id ?>&currmod=sis&currstep=13'>
                            <img src="../lib/images/settingicon.png">
                        </a>                    
                    </div>
            </div>
            <div class='my_school_ticket transp timbre4 width_pct_100 top'>
                    <div class='icon transp width_pct_100'>
                         <?php echo $prof_name;?>   <?php echo $session_name;?>  
                    </div>
            </div>
            <div class='my_school_ticket <?php echo $theSCCapacityValueClass; ?> <?php echo "g".$theSCCapacityObjective." r".$theSCCapacityObjectiveRed." o".$theSCCapacityObjectiveOrange; ?> timbre2 student-capacity width_pct_75 nopad'>
                    <div class='my_school_ticket transp top width_pct_100 big_label'>
                    <a target='_myschoolclass' class='sis title' href='./m.php?mp=ed&cl=SchoolClass&id=<?php echo $schoolClassItem->id ?>&currmod=sis&currstep=3'> عدد الطلاب <?php echo $theSCCapacityValue ?></a> 
                    </div>        
                    <div class='my_school_ticket transp top width_pct_100 medium_label'>
                            استيعاب القاعة <?php echo $theSCCapacityObjective ?>
                    </div>        
            </div>
            <div class='my_school_ticket <?php echo $theSCAbsenceValueClass; ?> <?php echo "g".$theSCAbsenceObjective." r".$theSCAbsenceObjectiveRed." o".$theSCAbsenceObjectiveOrange; ?> timbre2 width_pct_25 nopad'>
                    <div class='my_school_ticket transp top width_pct_100 big_medium_label'>
                            عدد الغياب 
                    </div>        
                    <div class='my_school_ticket transp top width_pct_100 medium_label'>
                            <?php 
                            
                                echo $theSCAbsenceValue ;
                                echo (($theSCAbsenceValue>=3) and ($theSCAbsenceValue<=10)) ? "طلاب" : "طالب";
                            
                            ?> 
                    </div>        
            </div>
            <div class='Mainwork my_school_ticket <?php echo $theSCMainworkValueClass; ?> <?php echo "g".$theSCMainworkObjective." r".$theSCMainworkObjectiveRed." o".$theSCMainworkObjectiveOrange; ?> timbre width_pct_25 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                            <?php echo $theSCMainworkValue ?>
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز الحفظ
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                            <?php echo $theSCMainworkMissed ?> لم ينجزوا
                            </div>
            </div>
            <div class='Homework my_school_ticket <?php echo $theSCHomeworkValueClass; ?> <?php echo "g".$theSCHomeworkObjective." r".$theSCHomeworkObjectiveRed." o".$theSCHomeworkObjectiveOrange; ?> timbre width_pct_25 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                            <?php echo $theSCHomeworkValue ?>
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز م. كبرى
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                            <?php echo $theSCHomeworkMissed ?> لم ينجزوا
                            </div>
            </div>
            <div class='Homework2 my_school_ticket <?php echo $theSCHomework2ValueClass; ?> <?php echo "g".$theSCHomework2Objective." r".$theSCHomework2ObjectiveRed." o".$theSCHomework2ObjectiveOrange; ?> timbre width_pct_25 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                <?php echo $theSCHomework2Value ?>
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز م. صغرى
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                            <?php echo $theSCHomework2Missed ?> لم ينجزوا
                            </div>
            </div>
            <div class='my_school_ticket transp timbre width_pct_25 nopad'>
                
                    <div class='my_school_ticket <?php echo $theSCMainworkIncompleteValueClass; ?> <?php echo "g".$theSCMainworkIncompleteObjective." r".$theSCMainworkIncompleteObjectiveRed." o".$theSCMainworkIncompleteObjectiveOrange; ?> timbre3 top width_pct_100'>
                            <div class='my_school_ticket spad transp top width_pct_100 corner-left'>
                                    نقص الحفظ <?php echo $theSCMainworkIncompleteValue ?>
                            </div> 
                    </div>
                    <div class='my_school_ticket <?php echo $theSCHomeworkIncompleteValueClass; ?> <?php echo "g".$theSCHomeworkIncompleteObjective." r".$theSCHomeworkIncompleteObjectiveRed." o".$theSCHomeworkIncompleteObjectiveOrange; ?> timbre3 width_pct_100'>
                            <div class='my_school_ticket spad transp top width_pct_100 corner-left'>
                                    نقص م. كبرى <?php echo $theSCHomeworkIncompleteValue ?>
                            </div> 
                    </div>

                    <div class='my_school_ticket <?php echo $theSCHomework2IncompleteValueClass; ?> <?php echo "g".$theSCHomework2IncompleteObjective." r".$theSCHomework2IncompleteObjectiveRed." o".$theSCHomework2IncompleteObjectiveOrange; ?> timbre3 width_pct_100'>
                            <div class='my_school_ticket spad transp top width_pct_100 corner-left'>
                                    نقص م. صغرى <?php echo $theSCHomework2IncompleteValue ?>
                            </div> 
                    </div>
            </div>
    <!--
            <div class='my_school_ticket transp timbre width_pct_25 nopad'>
                    <div class='my_school_ticket gray timbre2 top width_pct_100 nopad'>
                            <div class='my_school_ticket transp top width_pct_100 big_label'>
                                    عدد الطلاب 102
                            </div>        
                            <div class='my_school_ticket transp top width_pct_100 small_label'>
                                    هدف المنشأة 257
                            </div>        
                    </div>
                    <div class='my_school_ticket vert timbre4 width_pct_100'>
                            <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                    2%
                            </div> 
                    </div>
                    <div class='my_school_ticket rouge timbre4 width_pct_100'>
                            <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                    85%
                            </div> 
                    </div>
            </div>                
            <div class='my_school_ticket vert timbre width_pct_12 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                    10
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز الحفظ
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                                    3 لم ينجزوا
                            </div>
            </div>
            <div class='my_school_ticket rouge timbre width_pct_12 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                    70
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز م. كبرى
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                                    10 لم ينجزوا
                            </div>
            </div>
            <div class='my_school_ticket rouge timbre width_pct_12 padtop padleft'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                    20
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 label leftpad'>
                                    انجاز م. صغرى
                            </div>
                            <div class='my_school_ticket timbre3 gray top width_pct_100 medium_label leftpad topspace'>
                                    50 لم ينجزوا
                            </div>
            </div>
            <div class='my_school_ticket gray timbre width_pct_12'>
                            <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                    1
                            </div>
                            <div class='my_school_ticket timbre3 transp top width_pct_100 medium_label leftpad'>
                                نسبة الاتقان للمراجعة 
                            </div>
            </div>
            <div class='my_school_ticket transp timbre width_pct_12 nopad'>
                
                    <div class='my_school_ticket vert timbre2 top width_pct_100'>
                            <div class='my_school_ticket spad transp top width_pct_100 corner-left'>
                                    نفص الحفظ 140
                            </div> 
                    </div>
                    <div class='my_school_ticket rouge timbre2 width_pct_100'>
                            <div class='my_school_ticket spad transp top width_pct_100 corner-left'>
                                    نفص م. كبرى 5
                            </div> 
                    </div>
                    

            </div>
            <div class='my_school_ticket gray timbre width_pct_12'>
                    <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                            0
                    </div>
                    <div class='my_school_ticket timbre3 transp top width_pct_100 medium_label leftpad'>
                            رعاية الطالب معالجة
                    </div>
            </div> -->
</div>