<?php
class Assass2 extends SisObject
{

    public static function mapAssass2RowToCrmRow($assass2Row, $tableColsArr)
    {
        AfwAutoLoader::addModule("crm");
        $crmRow = [];
        $crmRow["mobile"] = AfwFormatHelper::formatMobile($assass2Row["MOBILENUMBER"]);
        // die("AfwFormatHelper::formatMobile(".$assass2Row["MOBILENUMBER"].") = " . $crmRow["mobile"]);
        /*
                1- هوية وطنية | يجب أن تكون الجنسية السعودية
                2- هوية خليجية | يجب أن تكون الجنسية خليجي (الكويت، البحرين، قطر، الإمارات، عمان)
                3- إقامة | يجب أن تكون الجنسية غير (السعودية، الكويت، البحرين، قطر، الإمارات، عمان، قبائل نازحة)
                4- جواز سفر | يجب أن تكون الجنسية غير (السعودية， الكويت， البحرين， قطر， الإمارات， عمان， قبائل نازحة)
                5 - رقم حدود | يجب أن تكون الجنسية غير (السعودية، الكويت، البحرين، قطر، الإمارات، عمان)


                $list_of_items[1] = "بطاقة أحوال";  //     code : AHWAL 
                $list_of_items[2] = "إقامة";  //     code : IQAMA 
                $list_of_items[99] = "أخرى";  //     code : OTHER 
        */
        $crmRow["idn_type_id"] = 99;

        if($assass2Row["IDENTITYTYPECODE"] == 1) $crmRow["idn_type_id"] = 1;
        if($assass2Row["IDENTITYTYPECODE"] == 3) $crmRow["idn_type_id"] = 2;

        $crmRow["idn"] = $assass2Row["IDENTITYNUMBER"];
        $crmRow["email"] = $assass2Row["EMAIL"];
        $crmRow["first_name_ar"] = $assass2Row["ARABICFIRSTNAME"];
        $crmRow["father_name_ar"] = $assass2Row["ARABICSECONDNAME"]. " " . $assass2Row["ARABICTHIRDNAME"];
        $crmRow["last_name_ar"] = $assass2Row["ARABICFOURTHNAME"];

        $crmRow["first_name_en"] = $assass2Row["ENGLISHFIRSTNAME"];
        $crmRow["father_name_en"] = $assass2Row["ENGLISHSECONDNAME"]. " " . $assass2Row["ENGLISHTHIRDNAME"];
        $crmRow["last_name_en"] = $assass2Row["ENGLISHFOURTHNAME"];
        // for now we set all imported students as new customers, but in future we can use some logic to determine if the student is already existing in crm or not, and set the type accordingly
        $crmRow["customer_type_id"] = 3; 
        if(AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"],"A"))
        {
            $crmRow["customer_type_id"] = 6;
        }
        elseif(AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"],"B"))
        {
            $crmRow["customer_type_id"] = 7;
        }
        elseif(AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"],"C"))
        {
            $crmRow["customer_type_id"] = 8;
        }

        $crmRow["ref_num"] = $assass2Row["STUDENTUNIQUEID"];
        $crmRow["status_id"] = Request::$REQUEST_STATUS_SENT;
        $crmRow["request_title"] = "ترحيل بياناتي إلى وزارة التعليم - أساس 2";
        $crmRow["request_text"] = "رجاء القيام بترحيل بياناتي إلى نظام وزارة التعليم لأتمكن من الاستفادة من الخدمات الإلكترونية المتاحة للطلاب";
        $crmRow["request_date"] = AfwDateHelper::currentHijriDate();
        $crmRow["request_time"] = date("H:i:s");
        $crmRow["assign_date"] = AfwDateHelper::currentHijriDate();
        $crmRow["assign_time"] = date("H:i:s");
        
        $crmRow["request_code"] = substr(md5($crmRow["idn"] . $crmRow["customer_type_id"]), 1, Request::$REQUEST_CODE_LENGTH);
        $crmRow["request_type_id"] = Request::$REQUEST_TYPE_REQUEST;
        $crmRow["region_id"] = 1;
        $crmRow["service_category_id"] = 2;
        $crmRow["service_id"] = 2;

        $crmRow["orgunit_id"] = 9323; // TVTC IT-DEPARTMENT
        $crmRow["employee_id"] = 1; // RAFIK
        

        $crmRowFinal = [];
        foreach($tableColsArr as $col)
        {
            $crmRowFinal[$col] = $crmRow[$col];
        }

        return $crmRowFinal;
    }

    public static function fromExcelToCrm($lang = "ar", $params = [])
    {
        global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
        $old_nb_queries_executed = $nb_queries_executed;
        $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
        $MODE_SQL_PROCESS_LOURD = true;

        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        $student_count = 0;
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
                if ($date_from) $file_identity = "-from-" . $date_from;
                if ($date_to) $file_identity .= "-to-" . $date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";
            $university_code = "";
            if ($fc == "A") $university_code = "pt";
            if ($fc == "B") $university_code = "coe";
            if (!$university_code) throw new AfwBusinessException("unknown university FC [$fc]");
            if (!$file_code) $file_code = "$university_code-students-to-crm" . $file_identity;
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


        $tablePK = [];
        $tablePK["crm_customer"] = array_flip(explode(",", "pk,mobile,email,idn_type_id,idn"));
        $tablePK["request"] = array_flip(explode(",", "pk,request_code,customer_id"));

        $tableColsArr = [];
        $tableColsArr["crm_customer"] = explode(",", "mobile,idn_type_id,idn,email,first_name_ar,last_name_ar,first_name_en,last_name_en,customer_type_id,ref_num");
        $tableColsArr["request"] = explode(",", "status_id,customer_id,request_title,request_text,request_date,request_time,request_code,request_type_id,region_id,service_category_id,service_id,orgunit_id,employee_id");


        // $toTrim = array_flip(explode(",", "TOTRIM,EMAIL"));
        /*

        
        $isScalar = array_flip(explode(",", "ISSCALAR,"));
        $isToSetNullWhenEmptyString = array_flip(explode(",", "SETNULLIFEMPTY,"));
        $isDate = array_flip(explode(",", "ISDATE,request_date"));
        $isDatetime = array_flip(explode(",", "ISDATETIME,"));

        $isMandatory = array_flip(['ISMANDATORY', 'STUDENTUNIQUEID', 'INSTITUTECODE', 'ARABICFIRSTNAME', 'ARABICSECONDNAME', 'ARABICFOURTHNAME', 'ENGLISHFIRSTNAME', 'ENGLISHSECONDNAME', 'ENGLISHFOURTHNAME', 'IDENTITYTYPECODE', 'IDENTITYNUMBER', 'BIRTHDATE', 'GENDERCODE', 'NATIONALITYCODE', 'ISSPECIALNEEDS', 'EMAIL', 'MOBILENUMBER', 'LASTUPDATEDATE', 'STUDENTUNIQUEID', 'HASSCHOLARSHIP', 'STUDENTACADEMICNUMBER', 'SCIENTIFICDEGREECODE', 'ACADEMICSTATUSCODE', 'STUDYLOCATIONCODE', 'INSTITUTECODE', 'CURRENTCOLLEGECODE', 'ACCEPTEDCOLLEGECODE', 'SECTIONCODE', 'MAJORCODE', 'SPECIALTYCLASSIFICATIONCODE', 'EDUCATIONALSUBLEVELCODE', 'INCLUDEDSPECIALIZATIONCODE', 'STUDYPROGRAMPERIODUNITCODE', 'STUDYPROGRAMPERIOD', 'REQUESTEDCREDITHOURSCOUNT', 'REGISTEREDCREDITHOURSCOUNT', 'PASSEDCREDITHOURSCOUNT', 'REMAININGCREDITHOURSCOUNT', 'REGISTRATIONSTATUSCODE', 'CURRENTACADEMICYEARDATE', 'CURRENTSEMESTERCODE', 'STUDYTYPECODE', 'ADMISSIONDATE', 'HASSTUDENTREWARD', 'GPATYPECODE', 'GPA', 'RATINGCODE', 'LASTACADEMICSTATUSUPDATEDATE', 'ISLASTACADEMICDATARECORD', 'EDUCATIONALSUBLEVELCODE', 'INCLUDEDSPECIALIZATIONCODE']);

        $isAssass1Only = array_flip(['ISASSASS1', 'TARGETSCIENTIFICDEGREEID', 'GRANTEDSCIENTIFICDEGREEID', 'COUNTRYID', 'SUMMERSEMESTERREGISTRATIONSTATUS', 'ISTRANSFERED', 'ISACCOMMODATIONINUNIVERSITY', 'GRADUATIONSEMESTERTYPEID']);
        */

        $php_generation_folder = AfwSession::config("sql_generation_folder", "/var/log/gen/sql/doing");
        $dir_sep = AfwSession::config("dir_sep", "/");
        $sql_examples = [];
        $pageEnd = $pageStart + $nbPages - 1;
        $info_arr[] = "<b>generation of pages from $pageStart to $pageEnd</b>";
        $requests_inserted=0;
        $requests_updated=0;  
        $customers_updated=0;
        $customers_inserted=0;
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $row_num_start = $pageRows * ($page - 1);
            $row_num_end = $pageRows * $page - 1;


            list($excel, $my_head, $my_data) = AfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "Assass2::fromExcelToCrm", true);
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
                $student_unique_id = $my_row['STUDENTUNIQUEID'];
                $my_row['BIRTHDATE'] = AfwDateHelper::parseGregDate($my_row['BIRTHDATE'], '/', 'm/d/Y');
                $my_row['MOBILENUMBER'] = AfwFormatHelper::formatMobile($my_row['MOBILENUMBER']);
                if (!AfwFormatHelper::isCorrectMobileNum($my_row['MOBILENUMBER'])) {
                    $my_row['MOBILENUMBER'] = '966500000001';
                } else {
                    $my_row['MOBILENUMBER'] = AfwFormatHelper::formatMobileInternational($my_row['MOBILENUMBER'], '966');
                }

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

                $my_row_mapped_customer = self::mapAssass2RowToCrmRow($my_row, $tableColsArr["crm_customer"]);
                $my_row_mapped_request = self::mapAssass2RowToCrmRow($my_row, $tableColsArr["request"]);
                
                // $sql_line = AfwSqlHelper::sqlInsertOrUpdate("crm_customer", $my_row_mapped, $tablePK["crm_customer"], $tableColsArr["crm_customer"]);

                // replace the placeholder @to_define_later in customer_id with the correct values
                $objCustomer = CrmCustomer::loadByMainIndex($my_row_mapped_customer["mobile"], $my_row_mapped_customer["idn_type_id"], $my_row_mapped_customer["idn"], true);
                $objCustomer->multipleSet($my_row_mapped_customer,true);
                if($objCustomer->is_new) $customers_inserted++;
                else $customers_updated++;


                $my_row_mapped_request["customer_id"] = $objCustomer->getId();

                $objRequest = Request::loadByMainIndex($my_row_mapped_request["request_code"], $objCustomer->getId(), true); 
                $objRequest->multipleSet($my_row_mapped_request,true);                
                if($objRequest->is_new) $requests_inserted++;
                else $requests_updated++;  

                // $sql_line2 = AfwSqlHelper::sqlInsertOrUpdate("request", $my_row_mapped, $tablePK["request"], $tableColsArr["request"]);
                
                

                $customers_updated++;
                $customers_inserted++;
                // $row_sql_prefix = "-- start academic details student Num $student_count ($student_unique_id)\n\n";
                // $row_sql_suffix = "-- end academic details student Num $student_count ($student_unique_id)\n\n";
                // $sql .= $row_sql_prefix . $sql_line . "\n\t commit;\n";
                // $sql .= $sql_line2 . "\n\t commit;\n" . $row_sql_suffix;
                
                $nb_rows++;
            }

            $info_arr[] = "$nb_rows row(s) processed for page $page";

            // $sql .= "\nselect 'after $file_code-at-$Ymd-p$page' as title, count(*) as record_count from STUDENTS.PERSONALINFO where STUDENTUNIQUEID like 'B%';\n";
            /*
            if (true) {
                $sql_prefix = "";
                $sql_suffix = "";

                if ($php_generation_folder != "no-gen") {
                    $relative_sql_fileName = "$file_code-at-$Ymd-p$page.sql";
                    $sql_fileName = $php_generation_folder . $dir_sep . $relative_sql_fileName;
                    try {
                        $nb_errors = count($error_arr);
                        if($nb_errors==0) $status = "successfully";
                        else {
                            $status = "and $nb_errors error(s)";
                            $errors_text = implode("\n", $error_arr);
                            $errors_fileName = $php_generation_folder . $dir_sep ."errors-in-$file_code-at-$Ymd-p$page.txt";
                            AfwFileSystem::write($errors_fileName, $errors_text);
                        }
                        AfwFileSystem::write($sql_fileName, $sql_prefix . $sql . $sql_suffix);
                        $info_arr[] = "file $sql_fileName generated with $nb_rows row(s) $status";
                        $warning_arr[] = "mysql -h 10.108.54.41 -u crm2 -p < $sql_fileName";
                    } catch (Exception $e) {
                        $error_arr[] = "failed to write sql file $sql_fileName : " . $e->getMessage();
                    } finally {
                    }
                } else {
                    $warning_arr[] = "file generation is disabled (see sql_generation_folder parameter in system config file)";
                }
            }*/
        }

        $info_arr[] = "$nb_rows row(s) processed for page $page";
        $info_arr[] = "$customers_inserted customer(s) inserted, $customers_updated customer(s) updated";
        $info_arr[] = "$requests_inserted request(s) inserted, $requests_updated request(s) updated";

        // $tech_arr[] = "sql examples : \n<br>" . implode("\n<br>", $sql_examples);
        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["my_head" => $my_head,  "my_data" => $my_data];


        $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
        $nb_queries_executed = $old_nb_queries_executed;

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr, $result_arr);
    }

    public static function fromExcel($lang = "ar", $params = [])
    {
        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        $student_count = 0;
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
                if ($date_from) $file_identity = "-from-" . $date_from;
                if ($date_to) $file_identity .= "-to-" . $date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";
            $university_code = "";
            if ($fc == "A") $university_code = "pt";
            if ($fc == "B") $university_code = "coe";
            if (!$university_code) throw new AfwBusinessException("unknown university FC [$fc]");
            if (!$file_code) $file_code = "$university_code-students-assass2" . $file_identity;
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
        $isScalar = array_flip(explode(",", "ISSCALAR,HASSCHOLARSHIP,HASSTUDENTREWARD,PASSEDCREDITHOURSCOUNT,WARNINGCOUNT,REMAININGCREDITHOURSCOUNT,REQUESTEDCREDITHOURSCOUNT,REGISTEREDCREDITHOURSCOUNT,CURRENTYEAR,ADMISSIONYEAR,STUDENTREWARDAMOUNT,GRADUTIONYEAR,GPA,STUDYPROGRAMPERIOD,HASTHESIS,ISLASTACADEMICDATARECORD,BORDERNUMBER,ISSPECIALNEEDS,MOBILENUMBER,STUDYPROGRAMPERIODUNITCODE,GENDERCODE"));
        $isToSetNullWhenEmptyString = array_flip(explode(",", "SETNULLIFEMPTY,SCHOLARSHIPTYPECODE,SCHOLARSHIPCLASSIFICATIONCODE,TARGETSCIENTIFICDEGREEID,GRANTEDSCIENTIFICDEGREEID,TARGETSCIENTIFICDEGREEID,GRANTEDSCIENTIFICDEGREEID,CURRENTACADEMICYEARID,CURRENTYEAR,ATTENDENCESEMESTERTYPEID,CURRENTSEMESTERASSESSMENTID,WARNINGCOUNT,STUDENTREWARDAMOUNT,COUNTRYID,SUMMERSEMESTERREGISTRATIONSTATUS,ISTRANSFERED,ISACCOMMODATIONINUNIVERSITY,THESISTITLE,ACCEPTENCEDATE,ISMAJOREDUCATIONAL,GRADUATIONSEMESTERTYPEID,EDUCATIONALSUBLEVELCODE,INCLUDEDSPECIALIZATIONCODE,STUDENTREWARDAMOUNT,BORDERNUMBER,GRADUATIONDATE,DISCLAIMERDATE,MINORCODE,SPECIALNEEDSTYPECODE,STUDENTREWARDAMOUNT,STUDYLOCATIONCODE"));
        $isDate = array_flip(explode(",", "ISDATE,BIRTHDATE,CURRENTACADEMICYEARDATE,GRADUATIONDATE,ADMISSIONDATE,ACCEPTENCEDATE,LASTACADEMICSTATUSUPDATEDATE,DISCLAIMERDATE"));
        $isDatetime = array_flip(explode(",", "ISDATETIME,LASTUPDATEDATE"));

        $isMandatory = array_flip(['ISMANDATORY', 'STUDENTUNIQUEID', 'INSTITUTECODE', 'ARABICFIRSTNAME', 'ARABICSECONDNAME', 'ARABICFOURTHNAME', 'ENGLISHFIRSTNAME', 'ENGLISHSECONDNAME', 'ENGLISHFOURTHNAME', 'IDENTITYTYPECODE', 'IDENTITYNUMBER', 'BIRTHDATE', 'GENDERCODE', 'NATIONALITYCODE', 'ISSPECIALNEEDS', 'EMAIL', 'MOBILENUMBER', 'LASTUPDATEDATE', 'STUDENTUNIQUEID', 'HASSCHOLARSHIP', 'STUDENTACADEMICNUMBER', 'SCIENTIFICDEGREECODE', 'ACADEMICSTATUSCODE', 'STUDYLOCATIONCODE', 'INSTITUTECODE', 'CURRENTCOLLEGECODE', 'ACCEPTEDCOLLEGECODE', 'SECTIONCODE', 'MAJORCODE', 'SPECIALTYCLASSIFICATIONCODE', 'EDUCATIONALSUBLEVELCODE', 'INCLUDEDSPECIALIZATIONCODE', 'STUDYPROGRAMPERIODUNITCODE', 'STUDYPROGRAMPERIOD', 'REQUESTEDCREDITHOURSCOUNT', 'REGISTEREDCREDITHOURSCOUNT', 'PASSEDCREDITHOURSCOUNT', 'REMAININGCREDITHOURSCOUNT', 'REGISTRATIONSTATUSCODE', 'CURRENTACADEMICYEARDATE', 'CURRENTSEMESTERCODE', 'STUDYTYPECODE', 'ADMISSIONDATE', 'HASSTUDENTREWARD', 'GPATYPECODE', 'GPA', 'RATINGCODE', 'LASTACADEMICSTATUSUPDATEDATE', 'ISLASTACADEMICDATARECORD', 'EDUCATIONALSUBLEVELCODE', 'INCLUDEDSPECIALIZATIONCODE']);

        $isAssass1Only = array_flip(['ISASSASS1', 'TARGETSCIENTIFICDEGREEID', 'GRANTEDSCIENTIFICDEGREEID', 'COUNTRYID', 'SUMMERSEMESTERREGISTRATIONSTATUS', 'ISTRANSFERED', 'ISACCOMMODATIONINUNIVERSITY', 'GRADUATIONSEMESTERTYPEID']);




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
                $student_unique_id = $my_row['STUDENTUNIQUEID'];
                $my_row['LASTACADEMICSTATUSUPDATEDATE'] = AfwDateHelper::parseGregDate($my_row['LASTACADEMICSTATUSUPDATEDATE'], '/', 'm/d/Y');
                // $beforeParse = $my_row['GREGORIANBIRTHDATE'];
                $my_row['BIRTHDATE'] = AfwDateHelper::parseGregDate($my_row['BIRTHDATE'], '/', 'm/d/Y');
                // $afterParse = $my_row['GREGORIANBIRTHDATE'];
                // die("beforeParse=$beforeParse afterParse=$afterParse");
                if ($my_row['GRADUATIONDATE'] and ($my_row['GRADUATIONDATE'] != "NULL")) $my_row['GRADUATIONDATE'] = AfwDateHelper::parseGregDate($my_row['GRADUATIONDATE'], '/', 'm/d/Y');
                if ($my_row['DISCLAIMERDATE'] and ($my_row['DISCLAIMERDATE'] != "NULL")) $my_row['DISCLAIMERDATE'] = AfwDateHelper::parseGregDate($my_row['DISCLAIMERDATE'], '/', 'm/d/Y');
                $my_row['ADMISSIONDATE'] = AfwDateHelper::parseGregDate($my_row['ADMISSIONDATE'], '/', 'm/d/Y');
                $my_row['CURRENTACADEMICYEARDATE'] = AfwDateHelper::parseGregDate($my_row['CURRENTACADEMICYEARDATE'], '/', 'm/d/Y');


                list($ADMY,) = explode("-", $my_row['ADMISSIONDATE']);
                $my_row['ADMISSIONYEAR'] = $ADMY;
                $my_row['CURRENTYEAR'] = $ADMY;
                $my_row['BORDERNUMBER'] = 'null';

                // because excel may remove 0 from left and length is always = 8
                $my_row['INCLUDEDSPECIALIZATIONCODE'] = AfwStringHelper::left_complete_len($my_row['INCLUDEDSPECIALIZATIONCODE'], 8, '0');
                $my_row['IDENTITYTYPECODE'] = AfwStringHelper::left_complete_len($my_row['IDENTITYTYPECODE'], 2, '0');
                $my_row['STUDYLOCATIONCODE'] = AfwStringHelper::left_complete_len($my_row['STUDYLOCATIONCODE'], 7, '0');
                

                // because HASTHESIS has sens only for majestir & doctorah
                $my_row['HASTHESIS'] = 'null';

                $GRADUTIONYEAR = "";
                if ($my_row['GRADUATIONDATE'] and ($my_row['GRADUATIONDATE'] != "NULL")) list($GRADUTIONYEAR,) = explode("-", $my_row['GRADUATIONDATE']);
                $my_row['GRADUTIONYEAR'] = $GRADUTIONYEAR;

                $my_row['GPA'] = round($my_row['GPA']*100)/100;

                if (!$my_row['GRADUTIONYEAR']) $my_row['GRADUTIONYEAR'] = 0;
                if (!$my_row['WARNINGCOUNT']) $my_row['WARNINGCOUNT'] = 0;

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

                
                $datetimeformat = 'MM/DD/YYYY HH24:MI';

                // if($university_code == "coe") $datetimeformat = 'DD/MM/YYYY HH24:MI:SS';

                list($errors, $sql_line) = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.ACADEMICDETAILS", $tableColsArr["STUDENTS.ACADEMICDETAILS"], $my_row, $isInTablePK["STUDENTS.ACADEMICDETAILS"], $isScalar, $isToSetNullWhenEmptyString, $isDate, $isDatetime, $isMandatory, $datetimeformat, 'YYYY-MM-DD', ['assass1' => $isAssass1Only]);
                list($errors2, $sql_line2) = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.PERSONALINFO", $tableColsArr["STUDENTS.PERSONALINFO"], $my_row, $isInTablePK["STUDENTS.PERSONALINFO"], $isScalar, $isToSetNullWhenEmptyString, $isDate, $isDatetime, $isMandatory, $datetimeformat, 'YYYY-MM-DD', ['assass1' => $isAssass1Only]);
                if ((count($errors) == 0) and (count($errors2) == 0)) {
                    $student_count++;
                    $row_sql_prefix = "-- start academic details student Num $student_count ($student_unique_id)\n\n";
                    $row_sql_suffix = "-- end academic details student Num $student_count ($student_unique_id)\n\n";
                    $sql .= $row_sql_prefix . $sql_line . "\n\t commit;\n";
                    $sql .= $sql_line2 . "\n\t commit;\n" . $row_sql_suffix;
                    if ($nb_rows < 2) {
                        $sql_examples[] = $sql_line2;
                        $sql_examples[] = $sql_line;
                    }
                    $nb_rows++;
                } else {
                    $sql .= "-- error for student ID ($student_unique_id) : \n-- " . implode("\n-- ", $errors2) . "\n-- " . implode("\n-- ", $errors) . "\n\n";
                    $errors2_nb = count($errors2);
                    $errors_nb = count($errors);
                    $error_student = "for student ID ($student_unique_id) :";
                    $error_student_personal_info = "";
                    $error_student_academic_details = "";
                    if ($errors2_nb > 0) $error_student_personal_info .= "\nThe personal info contain $errors2_nb errors : \n" . implode("\n", $errors2);
                    if ($errors_nb > 0)  $error_student_academic_details .= "\nThe academic details contain $errors_nb errors : \n" . implode("\n", $errors);
                    if($error_student_personal_info or $error_student_academic_details)
                    {
                        $error_arr[] = $error_student.$error_student_personal_info.$error_student_academic_details;
                    }                    
                }
            }

            $sql .= "\nselect 'after $file_code-at-$Ymd-p$page' as title, count(*) as record_count from STUDENTS.PERSONALINFO where STUDENTUNIQUEID like 'B%';\n";

            if (true) {
                $sql_prefix = "";
                $sql_suffix = "";

                if ($php_generation_folder != "no-gen") {
                    $relative_sql_fileName = "$file_code-at-$Ymd-p$page.sql";
                    $sql_fileName = $php_generation_folder . $dir_sep . $relative_sql_fileName;
                    try {
                        $nb_errors = count($error_arr);
                        if($nb_errors==0) $status = "successfully";
                        else {
                            $status = "and $nb_errors error(s)";
                            $errors_text = implode("\n", $error_arr);
                            $errors_fileName = $php_generation_folder . $dir_sep ."errors-in-$file_code-at-$Ymd-p$page.txt";
                            AfwFileSystem::write($errors_fileName, $errors_text);
                        }
                        AfwFileSystem::write($sql_fileName, $sql_prefix . $sql . $sql_suffix);
                        $info_arr[] = "file $sql_fileName generated with $nb_rows row(s) $status";
                        $warning_arr[] = "@E:\\work\\projects\\pt\\TETCO\\technical\\moeupdate\\doing\\$relative_sql_fileName";
                    } catch (Exception $e) {
                        $error_arr[] = "failed to write sql file $sql_fileName : " . $e->getMessage();
                    } finally {
                    }
                } else {
                    $warning_arr[] = "file generation is disabled (see sql_generation_folder parameter in system config file)";
                }
            }
        }
        $tech_arr[] = "sql examples : \n<br>" . implode("\n<br>", $sql_examples);
        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["my_head" => $my_head,  "my_data" => $my_data];

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr, $result_arr);
    }
}
