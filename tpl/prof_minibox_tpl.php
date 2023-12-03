<?php
        $objme = AfwSession::getUserConnected();
        $myEmplId = $objme->getEmployeeId();

        $my_status = $schoolObj->getVal("status_id");
        $my_status_decoded = $schoolObj->decode("status_id");
        $genre_id_decoded = $schoolObj->decode("genre_id");

        $currSYear = $schoolObj->getCurrentSchoolYear();

        $arrObjectsRelated = [];
        $arrObjectsRelated["currentSchoolYear"] = $currSYear;

        $arrIndicators = [];

        $arrIndicators["Capacity"] = ['class'=>"gray", 'sens'=>'asc'];
        $arrIndicators["Accepted"] = ['class'=>"vert", 'sens'=>'asc'];
        $arrIndicators["Pending"]  = ['class'=>"blue", 'sens'=>'desc'];




        $arrIndicatorsResults = [];
        
        foreach($arrIndicators as $indicator => $rowIndicator)
        {
                foreach($rowIndicator as $ikey => $ikey_value) ${"normal_".$ikey} = $ikey_value;

                list($arrIndicatorsResults[$indicator]["objective"], 
                     $arrIndicatorsResults[$indicator]["value"], 
                     $arrIndicatorsResults[$indicator]["value_class"],
                     $arrIndicatorsResults[$indicator]["objective_red"],
                     $arrIndicatorsResults[$indicator]["objective_orange"],
                     ) 
                     = SisObject::executeIndicator($schoolObj, $indicator, $normal_class, $arrObjectsRelated, $normal_sens, $normal_red_pct, $normal_orange_pct);

                if(!$arrIndicatorsResults[$indicator]["objective"]) $arrIndicatorsResults[$indicator]["objective"] = $normal_objective;
                if(!$arrIndicatorsResults[$indicator]["objective"]) 
                {
                    $arrIndicatorsResults[$indicator]["value_class"] = "disabled";
                    ${"the".$indicator."Pct"} = "&#9959;";
                    $arrIndicatorsResults[$indicator]["objective"] = "&#9959;";
                    $arrIndicatorsResults[$indicator]["value"] = "&#9959;";
                    ${"the".$indicator."Missed"} = "&#9959;";
                }
                else
                {
                    ${"the".$indicator."Pct"} = round(100.0*$arrIndicatorsResults[$indicator]["value"]/$arrIndicatorsResults[$indicator]["objective"]);
                    ${"the".$indicator."Missed"} = $arrIndicatorsResults[$indicator]["objective"] - $arrIndicatorsResults[$indicator]["value"];
                    if(${"the".$indicator."Missed"}<0) ${"theSC".$indicator."Missed"} = 0;
                }

                ${"the".$indicator."Objective"} = $arrIndicatorsResults[$indicator]["objective"];
                ${"the".$indicator."Value"} = $arrIndicatorsResults[$indicator]["value"];
                
                ${"the".$indicator."ValueClass"} = $arrIndicatorsResults[$indicator]["value_class"];
                ${"the".$indicator."Sens"} = $arrIndicators[$indicator]["sens"];
                ${"the".$indicator."ObjectiveRed"} = $arrIndicatorsResults[$indicator]["objective_red"];
                ${"the".$indicator."ObjectiveOrange"} = $arrIndicatorsResults[$indicator]["objective_orange"];
        }
        

        

        $schoolId = $schoolObj->id;
        if($currSYear)
        {
                
                $currSYearId = $currSYear->id;
                $mySchoolEmpl = SchoolEmployee::loadByMainIndex($myEmplId,$schoolId);
                if($mySchoolEmpl)
                {
                        list($ids, $schoolClassList) = SchoolClassCourse::loadMySchoolClasses($currSYearId,$mySchoolEmpl->id);
                }
                else
                {
                        $schoolClassList = [];
                }
                
                // $ids_txt = implode(",", $ids);
                
                
                $currSYearTitle = $currSYear->getShortDisplay("ar");
                $schoolClassListCount = count($schoolClassList);
                $schoolYearHref = "main.php?Main_Page=afw_mode_edit.php&cl=SchoolYear&currmod=sis&id=$currSYearId";
        }
        else
        {
                $schoolClassList = [];
                $currSYearId = 0;
                $currSYearTitle = "منشأة جديدة";
                $schoolClassListCount = "";
                $schoolYearHref = "#";
        }
?>

<div class="school_container">
<div class='hzm_attribute hzm_wd4 hzm_minibox_header0'>                
        
        <div class='front_bloc hzm_school_bloc ticket status_<?php echo $my_status; ?>'>
                <div class='mb_long_title my_school'> 
                        <div class='my_school_ticket title'>
                                <h3>
                                        <div class='my_school_title fright'>
                                                <a target='_myschool' class='sis title' href='./m.php?mp=ed&cl=School&id=<?php echo $schoolId ?>&currmod=sis&currstep=6'>مهامي في <?php echo $schoolObj->getShortDisplay("ar"); ?></a> 
                                        </div>
                                        <div class='my_school_ticket id sy fleft'>
                                                <div class='school_ticket_num'>
                                                        <a target='_myschoolyear' class='sy' href='<?php echo $schoolYearHref; ?>'>
                                                                <?php echo $currSYearTitle; ?>
                                                        </a>
                                                </div>
                                        </div>
                                        <div class='my_school_ticket id sc fleft'>
                                                <div class='school_ticket_num'>
                                                        <?php echo $schoolClassListCount; ?> 
                                                        <?php echo ($schoolClassListCount>=3 and $schoolClassListCount<=10) ? "حلقات" : "حلقة"; ?> 
                                                        
                                        
                                                </div>
                                        </div>
                                </h3>                                         
                        </div>

                        <div class='my_school_ticket students width_pct_100'>
                                <div class='my_school_ticket transp timbre width_pct_25 nopad'>
                                        <div class='my_school_ticket <?php echo $theCapacityValueClass; ?> <?php echo "g".$theCapacityObjective." r".$theCapacityObjectiveRed." o".$theCapacityObjectiveOrange; ?> timbre2 top width_pct_100 nopad'>
                                                <div class='my_school_ticket transp top width_pct_100 big_label'>
                                                        عدد الطلاب <?php echo $theCapacityValue; ?>
                                                </div>        
                                                <div class='my_school_ticket transp top width_pct_100 small_label'>
                                                        هدف المنشأة <?php echo $theCapacityObjective; ?>
                                                </div>        
                                        </div>
                                        <div class='my_school_ticket <?php echo $theAcceptedValueClass; ?> <?php echo "g".$theAcceptedObjective." r".$theAcceptedObjectiveRed." o".$theAcceptedObjectiveOrange; ?> timbre4 width_pct_100'>
                                                <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                                        عدد الطلاب المقبولين    <?php echo $theAcceptedValue; ?> _________ <?php echo $theAcceptedPct."%"; ?>      
                                                </div> 
                                        </div>
                                        <div class='my_school_ticket <?php echo $thePendingValueClass; ?> <?php echo "g".$thePendingObjective." r".$thePendingObjectiveRed." o".$thePendingObjectiveOrange." s".$thePendingSens; ?> timbre4 width_pct_100'>
                                                <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                                        عدد الطلاب انتظار <?php echo $thePendingValue; ?> _________ <?php echo $thePendingPct."%"; ?>  
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
                                        <!-- <div class='my_school_ticket transp timbre4 width_pct_100'>
                                                <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                                        
                                                </div> 
                                        </div>-->
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
                                        <!-- <div class='my_school_ticket transp timbre4 width_pct_100'>
                                                <div class='my_school_ticket timbre8 spad transp top width_pct_100 corner-left'>
                                                        
                                                </div> 
                                        </div>-->

                                </div>
                                <div class='my_school_ticket gray timbre width_pct_12'>
                                        <div class='my_school_ticket timbre8 transp top width_pct_100 corner-left'>
                                                0
                                        </div>
                                        <div class='my_school_ticket timbre3 transp top width_pct_100 medium_label leftpad'>
                                                رعاية الطالب معالجة
                                        </div>
                                </div>
                        </div>
                        <?php
                                
                                
                        ?>
                        
                        
                        
                        <?php
                                $structure2 = [];
                                $structure2['MINIBOX-TEMPLATE'] = "tpl/school_class_minibox_tpl.php";
                                $structure2['MINIBOX-TEMPLATE-PHP'] = true;
                                $structure2['MINIBOX-OBJECT-KEY'] = "schoolClassItem";
                                foreach($schoolClassList as $schoolClassItem)
                                {
                                        $schoolClassItem->currentProf = $mySchoolEmpl;
                                        $schoolClassItem->mode_minibox = ["current"=>true, "stdby"=>true, "finished"=>true];
                                        echo $schoolClassItem->showMinibox($structure2);
                                }
                                

                        ?>

                </div>

                <?php
                        if($show_school_details)
                        {

                ?>
                <div class='front_bloc hzm_data_props my_cms'>
                                <div class='my_school_ticket_data'>   
                                        <div class="row school_data title_school">
                                                <label>بيانات المنشأة</label>                                                
                                        </div>
                                        <div class="row school_data school_activity_id label_data_mixed">
                                            <b>    النشاط الاقتصادي : </b><?php echo $schoolObj->showAttribute("activity_id"); ?>
                                        </div>                             
                                        <div class="row school_data school_mobile label_data_mixed">
                                            <b>    رقم الجوال للتواصل : </b><?php echo $schoolObj->showAttribute("mobile"); ?>
                                        </div> 
                                        
                                        <div class="row school_data school_phone label_data_mixed">
                                                <b>رقم الهاتف للتواصل : </b><?php echo $schoolObj->showAttribute("phone"); ?>
                                        </div>

                                        <div class="row school_data school_email label_data_mixed">
                                                <b>البريد الالكتروني : </b><?php echo $schoolObj->showAttribute("email"); ?>
                                        </div>

                                        <div class="row school_data school_city_id label_data_mixed">
                                                <b>المدينة : </b><?php echo $schoolObj->showAttribute("city_id"); ?>
                                        </div>

                                        <div class="row school_data school_quarter label_data_mixed">
                                                <b>الحي : </b><?php echo $schoolObj->showAttribute("quarter"); ?>
                                        </div>

                                        <div class="row school_data school_pc label_data_mixed">
                                                <b>الرمز البريدي : </b><?php echo $schoolObj->showAttribute("pc"); ?>
                                        </div>

                                        <div class="row school_data school_mail_box label_data_mixed">
                                                <b> الصندوق البريدي : </b><?php echo $schoolObj->showAttribute("mail_box"); ?>
                                        </div>

                                        
                                        <div class="row school_data title_school">
                                                <label>المرفقات</label>                                                
                                        </div>
                                <?php 
                                        $fileListHtml = $schoolObj->showAttribute("ul_cl_files");
                                        
                                ?>
                                        <div class="row school_data school_files hzm_data_ul_cl_files">
                                        <?php 
                                             echo $fileListHtml;
                                        ?>  
                                        </div>
                                        
                                        <?php 
                                        if($schoolObj->is("survey_sent"))
                                        {
                                        ?> 
                                        
                                        <div class="row school_data title_school">
                                                <label>استبيان رضا المستثمر</label>                                                
                                        </div>
                                        <div class="row school_data survey">
                                                <label>تواصل سهل وسريع</label>                                                
                                                <div class='hzm_data_prop survey easy_fast<?php echo $schoolObj->getVal("easy_fast"); ?>'>
                                                        <?php echo $schoolObj->showAttribute("easy_fast"); ?> 
                                                </div>
                                        </div>
                                        <div class="row school_data survey">
                                                <label>رضا المستثمر</label>                                                
                                                <div class='hzm_data_prop survey service_satisfied<?php echo $schoolObj->getVal("service_satisfied"); ?> '>
                                                        <?php echo $schoolObj->showAttribute("service_satisfied"); ?> 
                                                </div>
                                        </div>
                                        <div class="row school_data survey">
                                                <label>تم حل المشكل</label>                                                
                                                <div class='hzm_data_prop survey pb_resolved<?php echo $schoolObj->getVal("pb_resolved"); ?>'>
                                                        <?php echo $schoolObj->showAttribute("pb_resolved"); ?> 
                                                </div>
                                        </div>
                                        <?php 
                                                if((!$schoolObj->is("service_satisfied")) or (!$schoolObj->is("pb_resolved")))
                                                {
                                        ?>
                                        <div class="btn_container taqib">
                                                <div class='content_body contact'>   
                                                                <a class='school question' href='/school/i.php?cn=school&mt=school&rt=<?php echo $schoolObj->getVal("school_type_id"); ?>&pt=<?php echo $schoolObj->id; ?>'>تحرير طلب تعقيب</a>                                                                
                                                </div>
                                        </div>
                                        <?php 
                                                }
                                        ?>
                                        <?php 
                                        }
                                        ?>


                                
                                
                                
                                
                                </div>                                
                        </div>
                <?php
                        }
                ?>
        </div>

        
        
</div>
</div>