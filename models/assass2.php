<?php
class Assass2 extends SisObject
{
    public static function fromExcel($lang = "ar", $params = [])
    {
        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        if (count($params) > 0) {
            $pageStart = $params["ps"];
            if (!$pageStart) $pageStart = 1;
            $pageRows = $params["rowspp"];
            if (!$pageRows) $pageRows = 1000;
            $nbPages = $params["pages"];
            if (!$nbPages) $nbPages = 1;
            $file_code = $params["file"];
            $date_from = $params["from"];
            $date_to = $params["to"];
            $block = $params["block"];
            if (!$block and !$date_from) throw new AfwBusinessException("The identity of file you want to uplaod is to define by [date_from, date_to] or [block] attribute");
            if ($block) {
                $file_identity = "-block-$block";
            } else {
                if($date_from) $file_identity ="-from-".$date_from;
                if($date_to) $file_identity.="-to-".$date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";
            $university_code = "";
            if ($fc == "A") $university_code = "pt";
            if ($fc == "B") $university_code = "coe";
            if (!$university_code) throw new AfwBusinessException("unknown university FC [$fc]");
            if (!$file_code) $file_code = "$university_code-students-assass2".$file_identity;
        }

        $Ymd = date("Y-m-d");
        $today_students_file = "/var/log/$university_code-assass2/$file_code-at-$Ymd.xlsx";
        if (!file_exists($today_students_file)) {
            throw new AfwBusinessException("file $today_students_file does not exist");
        }

        $info_arr = [];
        $warning_arr = [];
        $error_arr = [];
        $tech_arr = [];
        // $server_db_prefix = "c"."0";
        $tableColsArr = [];
        $tableColsArr["STUDENTS.PERSONALINFO"] = explode(",", "STUDENTUNIQUEID,ARABICFIRSTNAME,ARABICSECONDNAME,ARABICTHIRDNAME,ARABICFOURTHNAME,ENGLISHFIRSTNAME,ENGLISHSECONDNAME,ENGLISHTHIRDNAME,ENGLISHFOURTHNAME,PASSPORTNUMBER,BORDERNUMBER,HOMEIDENTITYNUMBER,BIRTHPLACEID,IDENTITYTYPECODE,IDENTITYNUMBER,BIRTHDATE,GENDERCODE,NATIONALITYCODE,MARITALSTATUSID,ORGINALLANGUAGEID,ISSPECIALNEEDS,SPECIALNEEDSTYPECODE,RELIGIONID,EMAIL,MOBILENUMBER,SPECIALNEEDID,LASTUPDATEDATE,INSTITUTECODE");
        $tableColsArr["STUDENTS.ACADEMICDETAILS"] = explode(",", "STUDENTUNIQUEID,HASSCHOLARSHIP,SCHOLARSHIPTYPECODE,SCHOLARSHIPCLASSIFICATIONCODE,TARGETSCIENTIFICDEGREEID,GRANTEDSCIENTIFICDEGREEID,SCIENTIFICDEGREECODE,STUDENTACADEMICNUMBER,ACADEMICSTATUSCODE,STUDYLOCATIONCODE,CURRENTCOLLEGECODE,ACCEPTEDCOLLEGECODE,SECTIONCODE,MAJORCODE,MINORCODE,SPECIALTYCLASSIFICATIONCODE,EDUCATIONALSUBLEVELCODE,INCLUDEDSPECIALIZATIONCODE,STUDYPROGRAMPERIODUNITCODE,STUDYPROGRAMPERIOD,CURRENTACADEMICYEARID,ADMISSIONYEAR,CURRENTYEAR,ATTENDENCESEMESTERTYPEID,PASSEDCREDITHOURSCOUNT,REMAININGCREDITHOURSCOUNT,REQUESTEDCREDITHOURSCOUNT,REGISTEREDCREDITHOURSCOUNT,REGISTRATIONSTATUSCODE,CURRENTACADEMICYEARDATE,CURRENTSEMESTERASSESSMENTID,CURRENTSEMESTERCODE,WARNINGCOUNT,GRADUATIONDATE,STUDYTYPECODE,ADMISSIONDATE,HASSTUDENTREWARD,STUDENTREWARDAMOUNT,COUNTRYID,GPATYPECODE,GPA,SUMMERSEMESTERREGISTRATIONSTATUS,ISTRANSFERED,RATINGCODE,ISACCOMMODATIONINUNIVERSITY,HASTHESIS,THESISTITLE,ISMAJOREDUCATIONAL,ACCEPTENCEDATE,LASTACADEMICSTATUSUPDATEDATE,DISCLAIMERDATE,DISCLAIMERDECISIONORBARGINNUNBER,ISLASTACADEMICDATARECORD,GRADUTIONYEAR,GRADUATIONSEMESTERTYPEID,INSTITUTECODE");


        $isInTablePK = [];
        $isInTablePK["STUDENTS.ACADEMICDETAILS"] = array_flip(explode(",", "ISPK,STUDENTUNIQUEID,CURRENTACADEMICYEARDATE,CURRENTSEMESTERCODE"));
        $isInTablePK["STUDENTS.PERSONALINFO"] = array_flip(explode(",", "ISPK,STUDENTUNIQUEID"));

        // $toTrim = array_flip(explode(",", "TOTRIM,EMAIL"));
        $isScalar = array_flip(explode(",", "ISSCALAR,HASSCHOLARSHIP,HASSTUDENTREWARD,PASSEDCREDITHOURSCOUNT,WARNINGCOUNT,REMAININGCREDITHOURSCOUNT,REQUESTEDCREDITHOURSCOUNT,REGISTEREDCREDITHOURSCOUNT,CURRENTYEAR,ADMISSIONYEAR,STUDENTREWARDAMOUNT,GRADUTIONYEAR,GPA,STUDYPROGRAMPERIOD,HASTHESIS,ISLASTACADEMICDATARECORD,BORDERNUMBER,ISSPECIALNEEDS,MOBILENUMBER"));
        $isNoEmptyString = array_flip(explode(",", "ISNOEMPTY,SCHOLARSHIPTYPECODE,SCHOLARSHIPCLASSIFICATIONCODE,TARGETSCIENTIFICDEGREEID,GRANTEDSCIENTIFICDEGREEID,TARGETSCIENTIFICDEGREEID,GRANTEDSCIENTIFICDEGREEID,CURRENTACADEMICYEARID,CURRENTYEAR,ATTENDENCESEMESTERTYPEID,CURRENTSEMESTERASSESSMENTID,WARNINGCOUNT,STUDENTREWARDAMOUNT,COUNTRYID,SUMMERSEMESTERREGISTRATIONSTATUS,ISTRANSFERED,ISACCOMMODATIONINUNIVERSITY,THESISTITLE,ACCEPTENCEDATE,ISMAJOREDUCATIONAL,GRADUATIONSEMESTERTYPEID,EDUCATIONALSUBLEVELCODE,INCLUDEDSPECIALIZATIONCODE,STUDENTREWARDAMOUNT,BORDERNUMBER"));
        $isDate = array_flip(explode(",", "ISDATE,BIRTHDATE,CURRENTACADEMICYEARDATE,GRADUATIONDATE,ADMISSIONDATE,ACCEPTENCEDATE,LASTACADEMICSTATUSUPDATEDATE,DISCLAIMERDATE"));
        $isDatetime = array_flip(explode(",", "ISDATETIME,LASTUPDATEDATE"));

        $isMandatory = array_flip(['ISMANDATORY', 'STUDENTUNIQUEID','INSTITUTECODE','ARABICFIRSTNAME','ARABICSECONDNAME','ARABICFOURTHNAME','ENGLISHFIRSTNAME','ENGLISHSECONDNAME','ENGLISHFOURTHNAME','IDENTITYTYPECODE','IDENTITYNUMBER','BIRTHDATE','GENDERCODE','NATIONALITYCODE','ISSPECIALNEEDS','EMAIL','MOBILENUMBER','LASTUPDATEDATE','STUDENTUNIQUEID','HASSCHOLARSHIP','STUDENTACADEMICNUMBER','SCIENTIFICDEGREECODE','ACADEMICSTATUSCODE','STUDYLOCATIONCODE','INSTITUTECODE','CURRENTCOLLEGECODE','ACCEPTEDCOLLEGECODE','SECTIONCODE','MAJORCODE','SPECIALTYCLASSIFICATIONCODE','EDUCATIONALSUBLEVELCODE','INCLUDEDSPECIALIZATIONCODE','STUDYPROGRAMPERIODUNITCODE','STUDYPROGRAMPERIOD','REQUESTEDCREDITHOURSCOUNT','REGISTEREDCREDITHOURSCOUNT','PASSEDCREDITHOURSCOUNT','REMAININGCREDITHOURSCOUNT','REGISTRATIONSTATUSCODE','CURRENTACADEMICYEARDATE','CURRENTSEMESTERCODE','STUDYTYPECODE','ADMISSIONDATE','HASSTUDENTREWARD','GPATYPECODE','GPA','RATINGCODE','LASTACADEMICSTATUSUPDATEDATE','ISLASTACADEMICDATARECORD']);





        $php_generation_folder = AfwSession::config("sql_generation_folder", "/var/log/gen/sql/doing");
        $dir_sep = AfwSession::config("dir_sep", "/");
        $sql_examples = [];
        $pageEnd = $pageStart + $nbPages - 1;
        $info_arr[] = "<b>generation of pages from $pageStart to $pageEnd</b>";
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $row_num_start = $pageRows * ($page - 1);
            $row_num_end = $pageRows * $page - 1;


            list($excel, $my_head, $my_data) = AfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "Assass2::fromExcel", true);
            $sql = "";
            $nb_rows = 0;
            foreach ($my_data as $row => $my_row) {
                $fc0 = substr($my_row['STUDENTUNIQUEID'], 0, 1);
                if (is_numeric($fc0) and ($fc == "A")) {
                    $my_row['STUDENTUNIQUEID'] = $fc . $my_row['STUDENTUNIQUEID'];
                    $fc0 = $fc;
                }
                if (strtoupper($fc0) != $fc) {
                    throw new AfwBusinessException("Are you sure this file is from a correct source, STUDENTUNIQUEID should starts with `$fc`");
                }
                $my_row['LASTACADEMICSTATUSUPDATEDATE'] = AfwDateHelper::parseGregDate($my_row['LASTACADEMICSTATUSUPDATEDATE'], '/', 'm/d/Y');
                // $beforeParse = $my_row['GREGORIANBIRTHDATE'];
                $my_row['BIRTHDATE'] = AfwDateHelper::parseGregDate($my_row['BIRTHDATE'], '/', 'm/d/Y');
                // $afterParse = $my_row['GREGORIANBIRTHDATE'];
                // die("beforeParse=$beforeParse afterParse=$afterParse");
                $my_row['GRADUATIONDATE'] = AfwDateHelper::parseGregDate($my_row['GRADUATIONDATE'], '/', 'm/d/Y');
                $my_row['DISCLAIMERDATE'] = AfwDateHelper::parseGregDate($my_row['DISCLAIMERDATE'], '/', 'm/d/Y');
                $my_row['ADMISSIONDATE'] = AfwDateHelper::parseGregDate($my_row['ADMISSIONDATE'], '/', 'm/d/Y');
                $my_row['CURRENTACADEMICYEARDATE'] = AfwDateHelper::parseGregDate($my_row['CURRENTACADEMICYEARDATE'], '/', 'm/d/Y');


                list($ADMY,) = explode("-", $my_row['ADMISSIONDATE']);
                $my_row['ADMISSIONYEAR'] = $ADMY;
                $my_row['CURRENTYEAR'] = $ADMY;

                list($GDMY,) = explode("-", $my_row['GRADUATIONDATE']);
                $my_row['GRADUTIONYEAR'] = $GDMY;

                $my_row['MOBILENUMBER'] = AfwFormatHelper::formatMobile($my_row['MOBILENUMBER']);
                if (!AfwFormatHelper::isCorrectMobileNum($my_row['MOBILENUMBER'])) {
                    $my_row['MOBILENUMBER'] = '966500000001';
                } else {
                    $my_row['MOBILENUMBER'] = AfwFormatHelper::formatMobileInternational($my_row['MOBILENUMBER'], '966');
                }

                $my_row['STUDENTACADEMICNUMBER'] = "Y" . $ADMY . "S" . $my_row['STUDENTACADEMICNUMBER'] . $my_row['MAJORCODE'];

                $my_row['EMAIL'] = trim($my_row['EMAIL']);
                $my_row['ARABICFIRSTNAME'] = trim($my_row['ARABICFIRSTNAME']);
                $my_row['ARABICSECONDNAME'] = trim($my_row['ARABICSECONDNAME']);
                $my_row['ARABICTHIRDNAME'] = trim($my_row['ARABICTHIRDNAME']);
                $my_row['ARABICFOURTHNAME'] = trim($my_row['ARABICFOURTHNAME']);

                if ((strlen($my_row['ARABICSECONDNAME']) > 30) or (strlen($my_row['ARABICTHIRDNAME']) > 30)) {
                    list($my_row['ARABICSECONDNAME'], $my_row['ARABICTHIRDNAME']) = AfwStringHelper::dividePhraseToNStrings($my_row['ARABICSECONDNAME'] . " " . $my_row['ARABICTHIRDNAME'], 30, 2);
                }

                if (strlen($my_row['ARABICFIRSTNAME']) > 30) $my_row['ARABICFIRSTNAME'] = substr($my_row['ARABICFIRSTNAME'], 0, 30);
                if (strlen($my_row['ARABICSECONDNAME']) > 30) $my_row['ARABICSECONDNAME'] = substr($my_row['ARABICSECONDNAME'], 0, 30);
                if (strlen($my_row['ARABICTHIRDNAME']) > 30) $my_row['ARABICTHIRDNAME'] = substr($my_row['ARABICTHIRDNAME'], 0, 30);
                if (strlen($my_row['ARABICFOURTHNAME']) > 30) $my_row['ARABICFOURTHNAME'] = substr($my_row['ARABICFOURTHNAME'], 0, 30);

                $my_row['ENGLISHFIRSTNAME'] = trim($my_row['ENGLISHFIRSTNAME']);
                $my_row['ENGLISHSECONDNAME'] = trim($my_row['ENGLISHSECONDNAME']);
                $my_row['ENGLISHTHIRDNAME'] = trim($my_row['ENGLISHTHIRDNAME']);
                $my_row['ENGLISHFOURTHNAME'] = trim($my_row['ENGLISHFOURTHNAME']);

                if ((strlen($my_row['ENGLISHSECONDNAME']) > 30) or (strlen($my_row['ENGLISHTHIRDNAME']) > 30)) {
                    list($my_row['ENGLISHSECONDNAME'], $my_row['ENGLISHTHIRDNAME']) = AfwStringHelper::dividePhraseToNStrings($my_row['ENGLISHSECONDNAME'] . " " . $my_row['ENGLISHTHIRDNAME'], 30, 2);
                }

                $my_row['ENGLISHFIRSTNAME'] = substr($my_row['ENGLISHFIRSTNAME'], 0, 30);
                $my_row['ENGLISHSECONDNAME'] = substr($my_row['ENGLISHSECONDNAME'], 0, 30);
                $my_row['ENGLISHTHIRDNAME'] = substr($my_row['ENGLISHTHIRDNAME'], 0, 30);
                $my_row['ENGLISHFOURTHNAME'] = substr($my_row['ENGLISHFOURTHNAME'], 0, 30);

                $sql_line = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.ACADEMICDETAILS", $tableColsArr["STUDENTS.ACADEMICDETAILS"], $my_row, $isInTablePK["STUDENTS.ACADEMICDETAILS"], $isScalar, $isNoEmptyString, $isDate, $isDatetime);
                if ($nb_rows < 2) $sql_examples[] = $sql_line;
                $sql .= $sql_line . "\n\t commit;\n";
                $sql_line = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.PERSONALINFO", $tableColsArr["STUDENTS.PERSONALINFO"], $my_row, $isInTablePK["STUDENTS.PERSONALINFO"], $isScalar, $isNoEmptyString, $isDate, $isDatetime);
                if ($nb_rows < 2) $sql_examples[] = $sql_line;
                $sql .= $sql_line . "\n\t commit;\n";

                $nb_rows++;
            }

            $sql_prefix = "";

            $sql_suffix = "";



            if ($php_generation_folder != "no-gen") {
                $relative_sql_fileName = "$file_code-at-$Ymd-p$page.sql";
                $sql_fileName = $php_generation_folder . $dir_sep . $relative_sql_fileName;
                try {
                    AfwFileSystem::write($sql_fileName, $sql_prefix . $sql . $sql_suffix);
                    $info_arr[] = "file $sql_fileName generated successfully with $nb_rows row(s)";
                    $info_arr[] = "run : \n @E:\\work\\projects\\pt\\TETCO\\technical\\moeupdate\\doing\\$relative_sql_fileName";
                } catch (Exception $e) {
                    $error_arr[] = "failed to write sql file $sql_fileName : " . $e->getMessage();
                } finally {
                }
            } else {
                $warning_arr[] = "file generation is disabled (see sql_generation_folder parameter in system config file)";
            }
        }
        $tech_arr[] = "sql examples : \n<br>" . implode("\n<br>", $sql_examples);
        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["my_head" => $my_head,  "my_data" => $my_data];

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr, $result_arr);
    }
}
