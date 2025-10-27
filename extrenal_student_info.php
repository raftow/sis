<?php
$direct_dir_name = $file_dir_name = dirname(__FILE__);
include("$file_dir_name/sis_start.php");
$objme = AfwSession::getUserConnected();
//if(!$objme) $studentMe = AfwSession::getStudentConnected();
if(!$sid) $sid = $_GET["sid"];
$studentId = $sid;
if(!$lang) $lang = "ar";

$id_cs = "";
$cs_action = "";

/*
if($_GET["id-cls"])
{
        $id_cs = $_GET["id-cls"];
        $cs_action = "closeSession";
}

if($_GET["id-rsw"])
{
     $id_cs = $_GET["id-rsw"];
     $cs_action = "resetAllWorksFromManhajAndInjaz";
}

if($_GET["id-uss"])
{
     $id_cs = $_GET["id-uss"];
     $cs_action = "updateMyStudentWork"; // updateStudentSessionsWithMe
}
*/






//die("rafik index 1 : user_id=".AfwSession::getSessionVar("user_id")." objme=".var_export($objme,true));

include("$file_dir_name/../lib/hzm/oldweb/hzm_header.php");
echo "<style>
span.cell.error>a {
    color: #f1ed72 !important;
    font-size: 18px;
    font-weight: bold;
}
</style>
";
if($objme and $studentId)
{
    try{
    
        $khottaStatusLookup = [
            10 => 'جاري العمل عليها عند المعهد',
            20 => 'جاري مراجعتها عند التدريب الأهلي',
            30 => 'جاري مراجعتها عند المناهج',
            32 => 'جاري تدقيقها الفني قبل الارسال للوزارة',
            35 => 'تم الارسال للوزارة في مجلد 1',
            36 => 'تم الارسال للوزارة في مجلد 2',
            37 => 'تم الارسال للوزارة في مجلد 3',
            40 => 'يوجد ملاحظات من الوزارة',
            41 => 'استبعاد آلي بسبب أخطاء في البيانات',
            50 => 'تم الاعتماد من الوزارة',

        ];

        $rowStudent = AfwDatabase::db_recup_row("select stu_name, user, ID_deplom, diploma_date, admission_date,
                                                   reg_date, status, best_exam_date, best_exam_score
                                                from tadreebi_deplom.student where iqama=$studentId",
                                                true,true,"npt");

        $data = [];                                                
        $data[] = $rowStudent;                                                

        $data_header = ['stu_name'=>'الاسم', 'user'=>'الرخصة', 'ID_deplom'=>'مسلسل برنامج الدبلوم', 'diploma_date'=>'تاريخ التخرج', 
                            'admission_date'=>'تاريخ التسجيل', 'best_exam_date'=>'تاريخ الاختبار', 'best_exam_score'=>'النتيجة','status'=>'الحالة الأكاديمية', 
                        ];

        if(!$rowStudent or !$rowStudent["ID_deplom"] or !$rowStudent["user"]) 
        {
            die("<span class='warning'>هذا الرقم للهوية غير موجود في المسجلين في برامج الدبلوم الرجاء التأكد</span>");                
        }

        $license = $rowStudent["user"];
        $ID_deplom = $rowStudent["ID_deplom"];

        
                
        echo AfwHtmlHelper::tableToHtml($data, $data_header);

        $sql = "select * from tadreebi_org.license  where license='$license'";
        $rowLicense = AfwDatabase::db_recup_row($sql, true,true,"npt");

        $sql = "select * from tadreebi_deplom.khotta where ID = $ID_deplom and license = $license";

        $dataKhotta = AfwDatabase::db_recup_rows($sql, true,true,"npt");

        $rowKhotta = $dataKhotta[0];

        echo AfwHtmlHelper::tableToHtml($dataKhotta);

        $kh_status = $rowKhotta["status"];
        $status_date = $rowKhotta["status_date"];
        $status_kh_disp = $khottaStatusLookup[$kh_status];
        $status_comments = $rowKhotta["comments"];

        if($kh_status!=50)
        {
            $license_status = $rowLicense["type"];

            echo "<span class='cell warning'>لم يتم بعد اعتماد التصنيف السعودي لهذا التخصص في هذا المعهد وحالة الطلب $status_kh_disp = $kh_status, <br><br></span><br>";
            if(($kh_status==30) or ($kh_status==32))
            {
                
                echo "<span class='cell success'> يرجى التأكد من حالة طلب الترميز من هنا : <br><br>
                    <a href='https://tadreeb.tvtc.gov.sa/diploma/search_khotta.php'>الاستعلام عن حالة تصنيف التخصصات من قبل أحد المعاهد</a><br><br>
                    فاذا كان هنالك أخطاء في البيانات يجب على المتدرب مراجعة معهده للتصنيف بشكل صحيح<br><br>
                    الملاحظات : $status_comments<br><br>
                    إذا كان لا يوجد أخطاء : <br><br>
                    جاري العمل على تدقيق البيانات من قبل المناهج ثم الوزارة واعتمادهم (قد يأخذ بضعة أيام)  تاريخ ارسال بيانات التصنيف السعودي من قبل المعهد : $status_date
                </span><br><br>";

                
            }

            if(($kh_status>=35) and ($kh_status<=37))
            {
                echo "<span class='cell success'>مع العلم أنه أرسل لتدقيق المناهج والوزارة بتاريخ $status_date<br><br></span>";
            }

            if(($kh_status==40) or ($kh_status==41))
            {
                echo "<span class='cell error'>يوجد ملاحظات من قبل المناهج أو وزارة التعليم $status_comments<br><br></span>";
            }

            echo "<span class='cell warning'>ملاحظة : حالة الرخصة للمعهد = $license_status</span><br>";
            
        }
        else
        {
            echo "<span class='cell success'>تم اعتماد التصنيف السعودي لهذا التخصص في هذا المعهد</span><br>";

            $old_validated_date = AfwDateHelper::shiftGregDate('',-3);
            $sql = "select sf.active,sf.firstname, sf.f_firstname, sf.lastname, sf.school_id, sf.course_program_id, 
                            sf.levels_template_id, sf.school_level_order, sf.level_class_order,
                            sf.rate_score, sf.status_date, sf.year, sf.reg_date, student_file_status_id, '$old_validated_date' as max_valid_date, sf.validated_at, 
                            sa.program_sa_code, sa.level_sa_code, -- if one of both is null neeed saudi classif  
                            c.school_level_id, pt.lookup_code as program_type_code -- if this field is null the cpc_course_program neeed moe classif
                            from c0sis.student_file sf 
                                left join c0sis.cpc_course_program c on sf.course_program_id = c.id 
                                left join c0sis.program_type pt on c.program_type_id = pt.id 
                                left join c0sis.cpc_course_program_school sa on sa.course_program_id = sf.course_program_id and sa.school_id = sf.school_id
                    where sf.student_id = $studentId and sf.active='Y'";

            $dataSInfo = AfwDatabase::db_recup_rows($sql);
            if(count($dataSInfo)==0) echo "<span class='cell error'>لا يوجد بيانات في نظام SIS SQB يفضل اعادة تشغيل المهمة الآلية على هذا الطالب<br><br></span>";

            echo "<!-- ".$sql."=> <br>".var_export($dataSInfo, true)."<br>-----------------------------------------------------------------<br> -->";

            

            foreach($dataSInfo as $i => $rowSInfo)
            {
                $school_id = $rowSInfo["school_id"];
                $year = $rowSInfo["year"];
                $levels_template_id = $rowSInfo["levels_template_id"];
                $school_level_order = $rowSInfo["school_level_order"];
                $level_class_order = $rowSInfo["level_class_order"];

                $student_info_ready_to_moe = true;
                // in (4,5)                    
                $student_file_status_id = $rowSInfo["student_file_status_id"];
                if (AfwStringHelper::stringContain($rowSInfo["firstname"], "???"))
                {
                    $sf_url = "m.php?mp=ed&cl=StudentFile&cm=sis&id=$studentId|$school_id|$year|$levels_template_id|$school_level_order|$level_class_order&cs=1&clp=Student";
                    $dataSInfo[$i]["firstname"] = "<span class='cell error'><a target='sfile' href='$sf_url'>".$rowSInfo["firstname"]."</a></span>";
                    $student_info_ready_to_moe = false;
                }
                else
                {
                    $dataSInfo[$i]["firstname"] = "<span class='cell success'>".$rowSInfo["firstname"]."</span>";
                }

                if (AfwStringHelper::stringContain($rowSInfo["f_firstname"], "???"))
                {
                    $dataSInfo[$i]["f_firstname"] = "<span class='cell error'>".$rowSInfo["f_firstname"]."</span>";
                    $student_info_ready_to_moe = false;
                }
                else
                {
                    $dataSInfo[$i]["f_firstname"] = "<span class='cell success'>".$rowSInfo["f_firstname"]."</span>";
                }

                if (AfwStringHelper::stringContain($rowSInfo["lastname"], "???"))
                {
                    $student_url = "main.php?Main_Page=afw_mode_edit.php&cl=Student&id=$studentId&currmod=sis&currstep=1";
                    $dataSInfo[$i]["lastname"] = "<span class='cell error'><a target='sfile' href='$student_url'>".$rowSInfo["lastname"]."</a></span>";
                    $student_info_ready_to_moe = false;
                }
                else
                {
                    $dataSInfo[$i]["lastname"] = "<span class='cell success'>".$rowSInfo["lastname"]."</span>";
                }


                if (($student_file_status_id!=4) and ($student_file_status_id!=5))
                {
                    $dataSInfo[$i]["student_file_status_id"] = "<span class='cell error'>".$rowSInfo["student_file_status_id"]."</span>";
                    $student_info_ready_to_moe = false;
                }
                else
                {
                    $dataSInfo[$i]["student_file_status_id"] = "<span class='cell success'>".$rowSInfo["student_file_status_id"]."</span>";
                }
                                        
                // in (2,6)
                $school_level_id = $rowSInfo["school_level_id"];
                if ((($school_level_id!=2) and ($school_level_id!=6)) or (!$rowSInfo["program_sa_code"]) or (!$rowSInfo["level_sa_code"]))
                {
                    $dataSInfo[$i]["school_level_id"] = "<span class='cell error'>".$rowSInfo["school_level_id"]."</span>";
                    $student_info_ready_to_moe = false;
                }
                else
                {
                    $dataSInfo[$i]["school_level_id"] = "<span class='cell success'>".$rowSInfo["school_level_id"]."</span>";
                }

                $program_type_code = $rowSInfo["program_type_code"]; 
                $course_program_id = $rowSInfo["course_program_id"]; 
                if(!$program_type_code)
                {
                    $dataSInfo[$i]["program_type_code"] = "<span class='cell error'>[the cpc_course_program `$course_program_id` neeed moe classif]</span>";
                }
                else
                {
                    $dataSInfo[$i]["program_type_code"] = "<span class='cell success'>$program_type_code</span>";
                }

                if($student_info_ready_to_moe)
                {
                    $dataSInfo[$i]["program_sa_code"] = "<span class='cell success'>".$rowSInfo["program_sa_code"]."</span>";
                    $dataSInfo[$i]["level_sa_code"] = "<span class='cell success'>".$rowSInfo["level_sa_code"]."</span>";
                    echo "<span class='cell success'>تأكد اذا لم تكن البيانات منذ بضع أيام في الوسيطة (الوعاء) بانتظار سحبها من قبل الوزارة</span><br>";
                }
                

                
            }
            

            echo AfwHtmlHelper::tableToHtml($dataSInfo);

            


        }
    }
    catch(Exception $e)
    {
        die("Exception happened  : The message is ".$e->getMessage()."\n The stack trace is : ".$e->getTraceAsString());
    }
    catch(Error $e)
    {
        die("Error happened  : The message is ".$e->__toString());
    }     

}
else
{
    echo "please enter student ID as sid param";
}