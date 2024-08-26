<?php

if($moeaction != "genere-moe-sql") die("this moe action is not allowed");
if(!$student_id) $student_id = 0;

$file_dir_name = dirname(__FILE__);

require_once("$file_dir_name/../external/db.php");
// old include of afw.php
require_once("$file_dir_name/../lib/afw/modes/afw_config.php");

function assessmentFromGPA($gpa)
{
        if(($gpa>=60) and ($gpa<70)) return 7;
        if(($gpa>=70) and ($gpa<80)) return 6;
        if(($gpa>=80) and ($gpa<90)) return 5;
        if(($gpa>=90) and ($gpa<=100)) return 3;

        return 9;
}

               
if(!$dir_sep) $dir_sep = "/";
               
$file_dir_name = dirname(__FILE__); 


$gen_dir = "/var/log/gen/sql";

$old_validated_date = AfwDateHelper::shiftGregDate('',-3);

$sql = "select sf.student_id, sf.school_id, sf.idn, sf.rate_score, sf.status_date, sf.year, pt.lookup_code,c.school_level_id, sf.city_id, c.duration, c.id as prog_id,
               sf.genre_id,sf.firstname, sf.f_firstname, sf.lastname, sf.mobile, sf.country_id, sf.birth_date, sf.birth_date_en, 
                 sa.program_sa_code, sa.level_sa_code
                  from c0sis.student_file sf 
                      inner join c0sis.cpc_course_program c on sf.course_program_id = c.id 
                      inner join c0sis.program_type pt on c.program_type_id = pt.id 
                      inner join c0sis.cpc_course_program_school sa on sa.course_program_id = sf.course_program_id and sa.school_id = sf.school_id
        where (sf.student_id = $student_id or (($student_id=0) and (sf.validated_at <= '$old_validated_date' or sf.validated_at is null)))
          and sf.student_file_status_id in (4,5)
          and sf.active='Y'          
          and c.school_level_id in (2,6)
          and sa.program_sa_code !=''
          and sa.program_sa_code is not null
          and sa.level_sa_code !=''
          and sa.level_sa_code is not null
        limit 50000";

if($debugg)   $out_scr .= "<pre class='sql'>$sql</pre>";      

$data = Student::sqlRecupRows($sql);
$nb = 0;
$count = 0;
$lines_gen = 0;
$rowSTUDENT_CACHE = [];

$moeCityMapArr = Student::sqlRecupIndexedRows("select * from c0pag.city", "id");

$srcipt_version = date("mdHis");
$grad_sql_file = $gen_dir."/pt-grad-rafik-$srcipt_version";
$local_grad_sql_file = $gen_dir."/doing/pt-grad-rafik-$srcipt_version";
$moe_grad_sql_file = $gen_dir."/pt-grad-moe-$srcipt_version";
$local_moe_grad_sql_file = "@E:\\work\\projects\\pt\\TETCO\\technical\\moeupdate\\doing\\pt-grad-moe-$srcipt_version";

// MariaDB [c0sis]> alter table c0sis.RAFIK_ACADEMICDETAIL drop key ukad;
// MariaDB [c0sis]>  alter table c0sis.RAFIK_ACADEMICDETAIL add unique key ukad(STUDENTIDENTITYNUMBER,UNIVERSITYMAJORID,ADMISSIONYEAR);

/* 
For field : التخصص الدقيق (الفرعي) UniversityMinorId

value 'ZZ98' (from V07) will be replaced by null as Ahmed Salah said in email of 05 - sep - 2023 and as field is not mandatory

هذه الحقل بيرسل Null من جهتي
الحقول الجديدة
SAUDIMAJORCODE
SAUDIEDUCATIONLEVELCODE
*/


$part = 1;
$warnings_arr = [];
$MODE_BATCH_LOURD = true;
if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n BEGIN\n", 'append');
$new_inserted_arr = [];

$moe_exec_arr = [];
/*
@E:\work\projects\TETCO\sql\pt-grad-moe-0620162218-7.sql
@E:\work\projects\TETCO\sql\pt-grad-moe-0620162218-8.sql
@E:\work\projects\TETCO\sql\pt-grad-moe-0620162218-9.sql     
*/

$rafik_exec_arr = [];
/* mysql -h 127.0.0.1 -u admin -p < pt-grad-rafik-1224114419-1.sql */

foreach($data as $row)
{
        $major = $row["lookup_code"];
        $student_id = $row["student_id"];
        $school_id = $row["school_id"];
        $idn = $row["idn"];
        $year = $row["year"];
        $city_id = $row["city_id"];
        $school_level_id = $row["school_level_id"];
        $rate_score = $row["rate_score"];
        $duration = $row["duration"];
        $prog_id = $row["prog_id"];
        $program_sa_code = $row["program_sa_code"];
        $level_sa_code = $row["level_sa_code"];
        
        
        $gender = $row["genre_id"];
        $firstname = $row["firstname"];
        $f_firstname = $row["f_firstname"];
        $lastname = $row["lastname"];
        $mobile = $row["mobile"];
        $country_id = $row["country_id"];
        $birth_date = $row["birth_date"];
        $birth_date_en = $row["birth_date_en"];
        $status_date = $row["status_date"];
        list($gdate,) = explode(" ",$status_date);
        list($gyear,) = explode("-",$gdate);
        if($gdate == "0000-00-00") $gdate = "";
        if($gdate == "0000-00-00 00:00:00") $gdate = "";

        if($birth_date_en == "0000-00-00") $birth_date_en = "";
        if($birth_date_en == "0000-00-00 00:00:00") $birth_date_en = "";

        if((!$birth_date) and $birth_date_en)
        {
                $birth_date = AfwDateHelper::gregToHijri($birth_date_en);
        }

        if($birth_date)
        {
                $birth_date = AfwDateHelper::formatHijriDate($birth_date, ['separator'=>'-', 'show_year' => true, 'show_month' => true,]);
                // die($birth_date);
        } 
        
        $moe_city_code = $moeCityMapArr[$city_id]["moe_code"];

        if(($rate_score<60) or ($rate_score > 100))
        {
                $warnings_arr[] = "student $idn major $major year $year bad rate_score : $rate_score";
        }
        elseif(!$moe_city_code)
        {
                $warnings_arr[] = "student $idn major $major year $year city_id=$city_id has no moe code";
        }
        elseif(!$idn)
        {
                $warnings_arr[] = "student-id= $student_id has no IDN";
        }
        elseif((strlen($idn) != 10) or (!is_numeric($idn)))
        {
                $warnings_arr[] = "student-idn= $idn not valid IDN";
        }
        elseif(!$gdate)
        {
                $warnings_arr[] = "student-id= $idn has no Graduation date";
        }
        elseif(!$birth_date)
        {
                $warnings_arr[] = "student-id= $idn has no Birth date";
        }
        elseif(!$firstname)
        {
                $warnings_arr[] = "student-id= $idn has no first name";
        }
        else
        {
                
                $rowRAFIK = null;
                $rowRAFIKs = Student::sqlRecupRows("select IDENTITYNUMBER as idn, GPATYPEID, GPA as rate_score, STUDYPROGRAMPERIOD, GRADUTIONYEAR, 
                                                                GRADUATIONDATE_MYSQL as gdate, STUDYLOCATIONCITYID, SAUDIMAJORCODE, SAUDIEDUCATIONLEVELCODE 
                                                    from c0sis.RAFIK_ACADEMICDETAIL s 
                                                    where s.STUDENTIDENTITYNUMBER = '$idn'
                                                      and s.UNIVERSITYMAJORID='$major'
                                                      and s.ADMISSIONYEAR='$year'");
                $rowRAFIK = $rowRAFIKs[0];
                $rafik_city_code = $rowRAFIK["STUDYLOCATIONCITYID"];
                $rafik_rate_score = $rowRAFIK["rate_score"];
                $rafik_gyear = $rowRAFIK["GRADUTIONYEAR"];
                $rafik_gdate = $rowRAFIK["gdate"];
                $rafik_period = $rowRAFIK["STUDYPROGRAMPERIOD"];
                $rafik_program_sa_code = $row["SAUDIMAJORCODE"];
                $rafik_level_sa_code = $row["SAUDIEDUCATIONLEVELCODE"];

                $period = 0;
                $degree_id = 9;

                if(!$duration) $duration = 720;

                $period = round($duration/180);
                if(($period==2) or ($period==4) or ($period==6) or ($period==8) or ($period==10))
                {
                        $period = round($duration/360);
                        $period_unit = 1;    // year سنة
                }
                else
                {
                        $period_unit = 2;    // فصل
                }
        
                if($school_level_id == 2)
                {
                        if($duration>700)
                        {
                                $degree_id = 1;
                        }
                        else
                        {
                                $school_level_id = 6;
                                $degree_id = 9;
                                //throw new RuntimeException("for prog-id=$prog_id : school_level_id=$school_level_id has duration=[$duration]<=700 ");
                        }
                }
                elseif($school_level_id == 6)
                {
                        $degree_id = 9;
                }
                else
                {
                        // normally impossible to get here
                        throw new RuntimeException("school_level_id=$school_level_id not managed in MOE");
                }
        
                $assessment = assessmentFromGPA($rate_score);
        
                $sql_line_moe = "";
                $sql_line = "";

                $semester = (intval($gyear)-intval($year)) % 3 + 1;
                $now_datetime = date("Y-m-d H:i:s");
                $sf_validation_query = "update c0sis.student_file set validated_at = '$now_datetime' where student_id='$idn' and school_id='$school_id';";     
                if($rowRAFIK["idn"])
                {
                        
                        $diff="";
                        if($moe_city_code != $rafik_city_code) $diff .= " > moe_city_code != rafik_city_code ($moe_city_code != $rafik_city_code)";
                        if($rate_score != $rafik_rate_score ) $diff .= " > rate_score != rafik_rate_score ($rate_score != $rafik_rate_score)";
                        if($gyear != $rafik_gyear ) $diff .= " > gyear != rafik_gyear ($gyear != $rafik_gyear)";
                        if($gdate != $rafik_gdate ) $diff .= " > gdate != rafik_gdate ($gdate != $rafik_gdate)";
                        if($period != $rafik_period ) $diff .= " > period != rafik_period ($period != $rafik_period)";
                        if($program_sa_code != $rafik_program_sa_code ) $diff .= " > program_sa_code != rafik_program_sa_code ($program_sa_code != $rafik_program_sa_code)";
                        if($level_sa_code != $rafik_level_sa_code ) $diff .= " > level_sa_code != rafik_level_sa_code ($level_sa_code != $rafik_level_sa_code)";
                        
                        if($diff)
                        {
                                $sql_line_moe = "update MOE_ACADEMICDETAIL set IDENTITYNUMBER='$idn', 
                                        UNIVERSITYID='C01', TARGETSCIENTIFICDEGREEID='$degree_id', GRANTEDSCIENTIFICDEGREEID='$degree_id', 
                                        ADMISSIONCOLLEGEID = 'O33', CURRENTCOLLEGEID = 'O33', UNIVERSITYDEPARTMENTID = null, 
                                        UNIVERSITYMINORID=null, SAUDIMAJORCODE='$program_sa_code', SAUDIEDUCATIONLEVELCODE='$level_sa_code',
                                        STUDYPROGRAMPERIOD=$period, STUDYPROGRAMPERIODUNITID=$period_unit, 
                                        REQUESTEDCREDITHOURSCOUNT=998, REGISTEREDCREDITHOURSCOUNT=0, PASSEDCREDITHOURSCOUNT=0, REMAININGCREDITHOURSCOUNT=0, 
                                        ACADEMICSTATUSID='6', STUDYTYPEID='0', REGISTRATIONSTATUSID='0', CURRENTACADEMICYEARID = 99, 
                                        CURRENTYEAR='$gyear', ATTENDENCESEMESTERTYPEID=null, CURRENTSEMESTERTYPEID=$semester,
                                        GPA=$rate_score, GPATYPEID=3, CURRENTSEMESTERASSESSMENTID=$assessment, ACCUMULATEDASSESSMENTID=$assessment, WARNINGCOUNT=0, ISREWARDRECEIVED=2,
                                        STUDYLOCATIONCITYID='$moe_city_code', COUNTRYID='101',
                                        GRADUTIONYEAR='$gyear', GRADUATIONDATE=TO_DATE('$gdate', 'yyyy-mm-dd'), GRADUATIONSEMESTERTYPEID='9', SUMMERSEMREGSTATUS=2, ISTRANSFERED=2, 
                                        ISACCOMMODATIONINUNIVERSITY=2, ISMAJOREDUCATIONAL=0, MAJORTYPECODE=null, DISCLAIMERDECISION=null, 
                                        ACCEPTENCEDATE=null, DISCLAIMERDATE=null, LASTUPDATEONACADEMICSTATUS=TO_DATE('$gdate', 'yyyy-mm-dd')
                                where STUDENTIDENTITYNUMBER='$idn' and UNIVERSITYMAJORID='$major' and ADMISSIONYEAR = '$year';
                                -- $diff
                                ";
                
                                $sql_line = "
                                
                                update c0sis.RAFIK_ACADEMICDETAIL set IDENTITYNUMBER='$idn', 
                                        UNIVERSITYID='C01', TARGETSCIENTIFICDEGREEID='$degree_id', GRANTEDSCIENTIFICDEGREEID='$degree_id', 
                                        ADMISSIONCOLLEGEID = 'O33', CURRENTCOLLEGEID = 'O33', UNIVERSITYDEPARTMENTID = null, 
                                        UNIVERSITYMINORID=null, SAUDIMAJORCODE='$program_sa_code', SAUDIEDUCATIONLEVELCODE='$level_sa_code',
                                        STUDYPROGRAMPERIOD=$period, STUDYPROGRAMPERIODUNITID=$period_unit, 
                                        REQUESTEDCREDITHOURSCOUNT=998, REGISTEREDCREDITHOURSCOUNT=0, PASSEDCREDITHOURSCOUNT=0, REMAININGCREDITHOURSCOUNT=0, 
                                        ACADEMICSTATUSID='6', STUDYTYPEID='0', REGISTRATIONSTATUSID='0', CURRENTACADEMICYEARID = 99, 
                                        CURRENTYEAR='$gyear', ATTENDENCESEMESTERTYPEID=null, CURRENTSEMESTERTYPEID=$semester,
                                        GPA=$rate_score, GPATYPEID=3, CURRENTSEMESTERASSESSMENTID=$assessment, ACCUMULATEDASSESSMENTID=$assessment, WARNINGCOUNT=0, ISREWARDRECEIVED=2,
                                        STUDYLOCATIONCITYID='$moe_city_code', COUNTRYID='101',
                                        GRADUTIONYEAR='$gyear', GRADUATIONDATE_MYSQL='$gdate', GRADUATIONSEMESTERTYPEID='9', SUMMERSEMREGSTATUS=2, ISTRANSFERED=2, 
                                        ISACCOMMODATIONINUNIVERSITY=2, ISMAJOREDUCATIONAL=0, MAJORTYPECODE=null, DISCLAIMERDECISION=null, 
                                        ACCEPTENCEDATE_MYSQL=null, DISCLAIMERDATE_MYSQL=null, LASTUPDATEONACADEMICSTATUS='$gdate'
                                where STUDENTIDENTITYNUMBER='$idn' and UNIVERSITYMAJORID='$major' and ADMISSIONYEAR = '$year';

                                $sf_validation_query

                                -- $diff
                                ";

                                $sf_validation_query = "";
                        }
                }
                elseif(!$new_inserted_arr[$idn][$major][$year])
                {
                        
                        $new_inserted_arr[$idn][$major][$year] = true;
        
                        $sql_line_moe = "
                        begin
                                insert into MOE_ACADEMICDETAIL(STUDENTIDENTITYNUMBER,UNIVERSITYMAJORID,ADMISSIONCOLLEGEID,TARGETSCIENTIFICDEGREEID,UNIVERSITYID,
                                                                ISMAJOREDUCATIONAL,STUDYLOCATIONCITYID,GPA,CURRENTSEMESTERTYPEID,ATTENDENCESEMESTERTYPEID,
                                                                ADMISSIONYEAR,STUDYTYPEID,ACADEMICSTATUSID,CURRENTCOLLEGEID) values 
                                                        ('$idn', '$major','O33','$degree_id','C01',
                                                                0,'$moe_city_code',$rate_score,null,9,
                                                                '$year','0','6','O33');
                        exception when dup_val_on_index
                        then
                                null;
                        end;
        
                        update MOE_ACADEMICDETAIL set IDENTITYNUMBER='$idn', 
                                UNIVERSITYID='C01', TARGETSCIENTIFICDEGREEID='$degree_id', GRANTEDSCIENTIFICDEGREEID='$degree_id', 
                                ADMISSIONCOLLEGEID = 'O33', CURRENTCOLLEGEID = 'O33', UNIVERSITYDEPARTMENTID = null, 
                                UNIVERSITYMINORID=null, SAUDIMAJORCODE='$program_sa_code', SAUDIEDUCATIONLEVELCODE='$level_sa_code',
                                STUDYPROGRAMPERIOD=$period, STUDYPROGRAMPERIODUNITID=$period_unit, 
                                REQUESTEDCREDITHOURSCOUNT=998, REGISTEREDCREDITHOURSCOUNT=0, PASSEDCREDITHOURSCOUNT=0, REMAININGCREDITHOURSCOUNT=0, 
                                ACADEMICSTATUSID='6', STUDYTYPEID='0', REGISTRATIONSTATUSID='0', CURRENTACADEMICYEARID = 99, 
                                CURRENTYEAR='$gyear', ATTENDENCESEMESTERTYPEID=null, CURRENTSEMESTERTYPEID=$semester,
                                GPA=$rate_score, GPATYPEID=3, CURRENTSEMESTERASSESSMENTID=$assessment, ACCUMULATEDASSESSMENTID=$assessment, WARNINGCOUNT=0, ISREWARDRECEIVED=2,
                                STUDYLOCATIONCITYID='$moe_city_code', COUNTRYID='101',
                                GRADUTIONYEAR='$gyear', GRADUATIONDATE=TO_DATE('$gdate', 'yyyy-mm-dd'), GRADUATIONSEMESTERTYPEID='$semester', SUMMERSEMREGSTATUS=2, ISTRANSFERED=2, 
                                ISACCOMMODATIONINUNIVERSITY=2, ISMAJOREDUCATIONAL=0, MAJORTYPECODE=null, DISCLAIMERDECISION=null, 
                                ACCEPTENCEDATE=null, DISCLAIMERDATE=null, LASTUPDATEONACADEMICSTATUS=TO_DATE('$gdate', 'yyyy-mm-dd')
                        where STUDENTIDENTITYNUMBER='$idn' and UNIVERSITYMAJORID='$major' and ADMISSIONYEAR = '$year';
        
                        ";
        
                        $sql_line = "
                        
                                insert into c0sis.RAFIK_ACADEMICDETAIL set STUDENTIDENTITYNUMBER='$idn',IDENTITYNUMBER='$idn', 
                                                UNIVERSITYID='C01', TARGETSCIENTIFICDEGREEID='$degree_id', GRANTEDSCIENTIFICDEGREEID='$degree_id', 
                                                ADMISSIONCOLLEGEID = 'O33', CURRENTCOLLEGEID = 'O33', UNIVERSITYDEPARTMENTID = null, 
                                                UNIVERSITYMAJORID='$major', UNIVERSITYMINORID=null, SAUDIMAJORCODE='$program_sa_code', SAUDIEDUCATIONLEVELCODE='$level_sa_code',
                                                STUDYPROGRAMPERIOD=$period, STUDYPROGRAMPERIODUNITID=$period_unit, 
                                                REQUESTEDCREDITHOURSCOUNT=998, REGISTEREDCREDITHOURSCOUNT=0, PASSEDCREDITHOURSCOUNT=0, REMAININGCREDITHOURSCOUNT=0, 
                                                ACADEMICSTATUSID='6', STUDYTYPEID='0', REGISTRATIONSTATUSID='0', CURRENTACADEMICYEARID = 99, 
                                                ADMISSIONYEAR = '$year', CURRENTYEAR='$gyear', ATTENDENCESEMESTERTYPEID=null, CURRENTSEMESTERTYPEID=$semester,
                                                GPA=$rate_score, GPATYPEID=3, CURRENTSEMESTERASSESSMENTID=$assessment, ACCUMULATEDASSESSMENTID=$assessment, WARNINGCOUNT=0, ISREWARDRECEIVED=2,
                                                STUDYLOCATIONCITYID='$moe_city_code', COUNTRYID='101',
                                                GRADUTIONYEAR='$gyear', GRADUATIONDATE_MYSQL='$gdate', GRADUATIONSEMESTERTYPEID='9', SUMMERSEMREGSTATUS=2, ISTRANSFERED=2, 
                                                ISACCOMMODATIONINUNIVERSITY=2, ISMAJOREDUCATIONAL=0, MAJORTYPECODE=null, DISCLAIMERDECISION=null, 
                                                ACCEPTENCEDATE_MYSQL=null, DISCLAIMERDATE_MYSQL=null, LASTUPDATEONACADEMICSTATUS='$gdate';
                                                

                                $sf_validation_query
                                                ";

                                $sf_validation_query = "";
                }
                else
                {
                        $sql_line_moe = " \n-- ignored duplication [$idn][$major][$year] \n";
                        $sql_line = " \n-- ignored duplication [$idn][$major][$year] \n";
                }


                $rowSTUDENT_RAFIK = null;
                if(!$rowSTUDENT_CACHE[$idn])
                {
                        $rowSTUDENT_RAFIKs = Student::sqlRecupRows("select IDENTITYNUMBER as idn, ARABICFIRSTNAME,ARABICSECONDNAME,ARABICLASTNAME,
                                                                ENGLISHFIRSTNAME,ENGLISHSECONDNAME,ENGLISHLASTNAME, 
                                                                BIRTHPLACEID, HIJRIBIRTHDATE,
                                                                GENDER, NATIONALITYID
                                                from c0sis.RAFIK_STUDENT s 
                                                where s.IDENTITYNUMBER = '$idn';
                                                
                                                ");
                                        
                        //die("rowSTUDENT_RAFIKs=".var_export($rowSTUDENT_RAFIKs,true));
                        $rowSTUDENT_RAFIK = $rowSTUDENT_RAFIKs[0];
                        //die("rowSTUDENT_RAFIK=".var_export($rowSTUDENT_RAFIK,true));

                        $rafik_firstname = $rowSTUDENT_RAFIK["ARABICFIRSTNAME"];
                        $rafik_f_firstname = $rowSTUDENT_RAFIK["ARABICSECONDNAME"];
                        $rafik_lastname = $rowSTUDENT_RAFIK["ARABICLASTNAME"];
                        $rafik_gender = $rowSTUDENT_RAFIK["GENDER"];
                        $rafik_NATIONALITYID = $rowSTUDENT_RAFIK["NATIONALITYID"];
                        $rafik_birth_date_arr = explode("/",$rowSTUDENT_RAFIK["HIJRIBIRTHDATE"]);
                        $rafik_birth_date = $rafik_birth_date_arr[2]."-".$rafik_birth_date_arr[1]."-".$rafik_birth_date_arr[0];
                        
                        $birth_date_arr = explode("-",$birth_date);
                        $moe_birth_date = $birth_date_arr[2]."/".$birth_date_arr[1]."/".$birth_date_arr[0];

                        $sql_student_moe = "";
                        $sql_student = "";
                        
                        if(!$rowSTUDENT_RAFIK["idn"])
                        {
                                $sql_student_moe = "
                                
                                begin
                                        insert into GDPT.MOE_STUDENT(
                                                IDENTITYNUMBER,ARABICFIRSTNAME,ARABICSECONDNAME,ARABICLASTNAME,
                                                ENGLISHFIRSTNAME,ENGLISHSECONDNAME,ENGLISHLASTNAME,PASSPORTNUMBER,
                                                BORDERNUMBER, HOMEIDENTITYNUMBER, BIRTHPLACEID, HIJRIBIRTHDATE,
                                                GENDER, NATIONALITYID, MARITALSTATUSID, ORGINALLANGUAGEID,
                                                RELIGIONID, SPECIALNEEDSID, SPECIALNEEDID)
                                                values
                                                ('$idn','$firstname','$f_firstname', '$lastname',
                                                null,null,null,null,
                                                '', '','', '$moe_birth_date',
                                                $gender, '101',null, null,
                                                null, null, null
                                        );
                                exception when dup_val_on_index
                                then
                                        update GDPT.MOE_STUDENT
                                            set ARABICFIRSTNAME = '$firstname',
                                                ARABICSECONDNAME = '$f_firstname',
                                                ARABICLASTNAME = '$lastname',
                                                ENGLISHFIRSTNAME = null,
                                                ENGLISHSECONDNAME = null,
                                                ENGLISHLASTNAME = null,
                                                PASSPORTNUMBER = null,
                                                BORDERNUMBER = '', 
                                                HOMEIDENTITYNUMBER = '', 
                                                BIRTHPLACEID = '', 
                                                HIJRIBIRTHDATE = '$moe_birth_date',
                                                GENDER = $gender, 
                                                NATIONALITYID = '101', 
                                                MARITALSTATUSID = null, 
                                                ORGINALLANGUAGEID = null,
                                                RELIGIONID = null, 
                                                SPECIALNEEDSID = null, 
                                                SPECIALNEEDID = null
                                        where IDENTITYNUMBER = '$idn';
                                end;
                                
                                
                                ";

                                $sql_student = "insert into c0sis.RAFIK_STUDENT(
                                        IDENTITYNUMBER,ARABICFIRSTNAME,ARABICSECONDNAME,ARABICLASTNAME,
                                        ENGLISHFIRSTNAME,ENGLISHSECONDNAME,ENGLISHLASTNAME,PASSPORTNUMBER,
                                        BORDERNUMBER, HOMEIDENTITYNUMBER, BIRTHPLACEID, HIJRIBIRTHDATE,
                                        GENDER, NATIONALITYID, MARITALSTATUSID, ORGINALLANGUAGEID,
                                        RELIGIONID, SPECIALNEEDSID, SPECIALNEEDID)
                                        values
                                        ('$idn',_utf8'$firstname',_utf8'$f_firstname', _utf8'$lastname',
                                        null,null,null,null,
                                        '', '','', '$moe_birth_date',
                                        $gender, '101',null, null,
                                        null, null, null
                                );
                                
                                $sf_validation_query

                                ";                                 

                                $sf_validation_query = "";
                        }
                        else
                        {
                                $diff = "";
                                if($rafik_firstname != $firstname)     $diff .= " [$rafik_firstname] != [$firstname]";
                                if($rafik_f_firstname != $f_firstname) $diff .= " [$rafik_f_firstname] != [$f_firstname]";
                                if($rafik_lastname != $lastname)       $diff .= " [$rafik_lastname] != [$lastname]";
                                if($rafik_birth_date != $birth_date)   $diff .= " [$rafik_birth_date] != [$birth_date]";
                                if($rafik_gender != $gender)           $diff .= " [$rafik_gender] != [$gender]";
                                
                                if($diff)
                                {
                                        $sql_student_moe = "update GDPT.MOE_STUDENT set
                                                ARABICFIRSTNAME='$firstname',ARABICSECONDNAME='$f_firstname',ARABICLASTNAME='$lastname',
                                                HIJRIBIRTHDATE='$moe_birth_date',GENDER=$gender
                                                where IDENTITYNUMBER = '$idn';
                                                
                                                -- $diff
                                                ";

                                        $sql_student = "update c0sis.RAFIK_STUDENT set
                                                ARABICFIRSTNAME=_utf8'$firstname',ARABICSECONDNAME=_utf8'$f_firstname',ARABICLASTNAME=_utf8'$lastname',
                                                HIJRIBIRTHDATE='$moe_birth_date',GENDER=$gender
                                                where IDENTITYNUMBER = '$idn';

                                                $sf_validation_query
                                                
                                                ";                  
                                        $sf_validation_query = "";                      

                                }
                                

                        }

                        $rowSTUDENT_CACHE[$idn] = true;

                        if($sql_student_moe)
                        {
                                $lines_gen++;

                                if(!$simul) AfwFileSystem::write($grad_sql_file."-$part.sql", $sql_student, 'append');
                                if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", $sql_student_moe, 'append');
                                
                                $nb++;
                                
                        
                                if(($nb>8000) or ($count >= count($data)))
                                {
                                        if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n commit;\nEND;\n", 'append');
                                        $nb = 0;
                                        if(($count < count($data)))
                                        {
                                                $moe_exec_arr[$part] = $local_moe_grad_sql_file."-$part.sql";
                                                $rafik_exec_arr[$part] = "mysql -h 127.0.0.1 -u admin -p < $local_grad_sql_file-$part.sql"; 
                                                $part++;
                                                if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n BEGIN\n", 'append');
                                        }
                                        
                                }
                        } 
                }
        
                
                if($sql_line_moe)
                {
                        $lines_gen++;

                        if(!$simul) AfwFileSystem::write($grad_sql_file."-$part.sql", $sql_line, 'append');
                        if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", $sql_line_moe, 'append');
                        
                        $nb++;
                        
                
                        if(($nb>8000) or ($count >= count($data)))
                        {
                                if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n commit;\nEND;\n", 'append');
                                

                                $nb = 0;
                                if(($count < count($data)))
                                {
                                        $moe_exec_arr[$part] = $local_moe_grad_sql_file."-$part.sql";
                                        $rafik_exec_arr[$part] = "mysql -h 127.0.0.1 -u admin -p < $local_grad_sql_file-$part.sql"; 
                                        $part++;
                                        if(!$simul) AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n BEGIN\n", 'append');
                                }
                                
                        }
                }
        }

        $count++;
}

if(!$simul)
{
        AfwFileSystem::write($moe_grad_sql_file."-$part.sql", "\n commit;\nEND;\n", 'append');
        $moe_exec_arr[$part] = $local_moe_grad_sql_file."-$part.sql";
        $rafik_exec_arr[$part] = "mysql -h 127.0.0.1 -u admin -p < $local_grad_sql_file-$part.sql"; 
        
        $out_scr .= "<div style='direction:ltr'>";
        $out_scr .= "$count sql records fetched <br>\n
                     $lines_gen lines generated into $moe_grad_sql_file sql files <br>\n
                     devided in $part part(s) : <br>\n";
        
        $out_scr .= implode("<br>\n",$moe_exec_arr)."<br>\n";
        $out_scr .= implode("<br>\n",$rafik_exec_arr)."<br>\n";
} 



$nbw = count($warnings_arr);
if($nbw>0)
{
        $out_scr .= "$nbw warning(s)<br>";
        $out_scr .= "<div class='warning'>".implode("<br\n>",$warnings_arr)."</div>";
}

$out_scr .= "</div>";

?>