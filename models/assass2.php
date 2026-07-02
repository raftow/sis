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

        if ($assass2Row["IDENTITYTYPECODE"] == 1) $crmRow["idn_type_id"] = 1;
        if ($assass2Row["IDENTITYTYPECODE"] == 3) $crmRow["idn_type_id"] = 2;

        $crmRow["idn"] = $assass2Row["IDENTITYNUMBER"];
        $crmRow["email"] = $assass2Row["EMAIL"];
        $crmRow["first_name_ar"] = $assass2Row["ARABICFIRSTNAME"];
        $crmRow["father_name_ar"] = $assass2Row["ARABICSECONDNAME"] . " " . $assass2Row["ARABICTHIRDNAME"];
        $crmRow["last_name_ar"] = $assass2Row["ARABICFOURTHNAME"];

        $crmRow["first_name_en"] = $assass2Row["ENGLISHFIRSTNAME"];
        $crmRow["father_name_en"] = $assass2Row["ENGLISHSECONDNAME"] . " " . $assass2Row["ENGLISHTHIRDNAME"];
        $crmRow["last_name_en"] = $assass2Row["ENGLISHFOURTHNAME"];
        // for now we set all imported students as new customers, but in future we can use some logic to determine if the student is already existing in crm or not, and set the type accordingly
        $crmRow["customer_type_id"] = 3;
        if (AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"], "A")) {
            $crmRow["customer_type_id"] = 6;
        } elseif (AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"], "B")) {
            $crmRow["customer_type_id"] = 7;
        } elseif (AfwStringHelper::stringStartsWith($assass2Row["STUDENTUNIQUEID"], "C")) {
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
        foreach ($tableColsArr as $col) {
            $crmRowFinal[$col] = $crmRow[$col];
        }

        return $crmRowFinal;
    }

    public static function fromExcelToCrm($lang = "ar", $params = [])
    {
        UfwQueryAnalyzer::startProcessLourdMode();

        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        $student_count = 0;
        $university_code = "";
        $file_identity = "";

        $phpDateFormat = 'Y-m-d';
        $dateSeparator = "-";
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
                $file_identity .= "-block-$block";
            } else {
                if ($date_from) $file_identity .= "-from-" . $date_from;
                if ($date_to) $file_identity .= "-to-" . $date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";



            if ($fc == "A") {
                $university_code = "pt";
                $phpDateFormat = 'm/d/Y'; // kol marra haja wa rabbi yostor
                $dateSeparator = "/";
                $oracleDatetimeFormat = 'MM/DD/YYYY HH24:MI';
                $oracleDateFormat = 'MM/DD/YYYY';
            } elseif ($fc == "B") {
                $university_code = "coe";
                $phpDateFormat = 'd/m/Y';
                $dateSeparator = "/";
                $oracleDatetimeFormat = 'MM/DD/YYYY HH24:MI';
                $oracleDateFormat = 'DD/MM/YYYY';
            };
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
        $requests_inserted = 0;
        $requests_updated = 0;
        $customers_updated = 0;
        $customers_inserted = 0;
        $total_rows = 0;
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $row_num_start = $pageRows * ($page - 1);
            $row_num_end = $pageRows * $page - 1;


            list($excel, $my_head, $my_data) = UfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "Assass2::fromExcelToCrm", true);
            $sql = "";
            $nb_rows = 0;
            foreach ($my_data as $row => $my_row) {
                $my_row['STUDENTUNIQUEID'] = trim($my_row['STUDENTUNIQUEID']);
                if ($my_row['STUDENTUNIQUEID']) {
                    $fc0 = substr($my_row['STUDENTUNIQUEID'], 0, 1);
                    if (is_numeric($fc0) and ($fc == "A")) {
                        $my_row['STUDENTUNIQUEID'] = $fc . $my_row['STUDENTUNIQUEID'];
                        $fc0 = $fc;
                    }
                    if (strtoupper($fc0) != $fc) {
                        throw new AfwBusinessException("Are you sure this file is from a correct source, STUDENTUNIQUEID should starts with `$fc`");
                    }
                    $student_unique_id = $my_row['STUDENTUNIQUEID'];
                    list($my_row['BIRTHDATE'],) = explode(" ", $my_row['BIRTHDATE']);
                    $my_row['BIRTHDATE'] = AfwDateHelper::parseGregDate($my_row['BIRTHDATE'], $dateSeparator, $phpDateFormat);

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
                    $objCustomer->multipleSet($my_row_mapped_customer, true);
                    if ($objCustomer->is_new) $customers_inserted++;
                    else $customers_updated++;


                    $my_row_mapped_request["customer_id"] = $objCustomer->getId();

                    $objRequest = Request::loadByMainIndex($my_row_mapped_request["request_code"], $objCustomer->getId(), true);
                    $objRequest->multipleSet($my_row_mapped_request, true);
                    if ($objRequest->is_new) $requests_inserted++;
                    else $requests_updated++;

                    // $sql_line2 = AfwSqlHelper::sqlInsertOrUpdate("request", $my_row_mapped, $tablePK["request"], $tableColsArr["request"]);



                    $customers_updated++;
                    $customers_inserted++;
                } else {
                    // @todo
                }

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
                            UfwFileSystem::write($errors_fileName, $errors_text);
                        }
                        UfwFileSystem::write($sql_fileName, $sql_prefix . $sql . $sql_suffix);
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
            $total_rows += $nb_rows;
        }

        $info_arr[] = "$total_rows row(s) processed for page $page";
        $info_arr[] = "$customers_inserted customer(s) inserted, $customers_updated customer(s) updated";
        $info_arr[] = "$requests_inserted request(s) inserted, $requests_updated request(s) updated";

        // $tech_arr[] = "sql examples : \n<br>" . implode("\n<br>", $sql_examples);
        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["file" => $today_students_file,  "total_rows" => $total_rows];


        UfwQueryAnalyzer::stopProcessLourdMode();

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr, $result_arr);
    }

    public static function fromExcel($lang = "ar", $params = [])
    {
        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        $student_count = 0;
        $file_identity = "";
        $university_code = "";
        $oracleDatetimeFormat = 'YYYY-MM-DD HH24:MI:SS';
        $oracleDateFormat = 'YYYY-MM-DD';
        $phpDateFormat = 'Y-m-d';
        $phpDatetimeFormat = 'Y-m-d H:i:s';
        
        $dateSeparator = "-";
        if (count($params) > 0) {
            $suffix = $params["suffix"] ?? "";
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
            $showExamples = $params["example"];
            if (!$block and !$date_from) throw new AfwBusinessException("The identity of file you want to uplaod is to define by [date_from, date_to] or [block] attribute");
            if ($block) {
                $file_identity .= "-block-$block";
            } else {
                if ($date_from) $file_identity .= "-from-" . $date_from;
                if ($date_to) $file_identity .= "-to-" . $date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";

            if ($fc == "A") {
                $university_code = "pt";
                $phpDateFormat = 'm/d/Y'; // kol marra haja wa rabbi yostor
                $phpDatetimeFormat = 'm/d/Y H:i';
                $dateSeparator = "/";
                $oracleDatetimeFormat = 'MM/DD/YYYY HH24:MI';
                $oracleDateFormat = 'MM/DD/YYYY';
                
            } elseif ($fc == "B") {
                $university_code = "coe";
                $phpDateFormat = 'd/m/Y';
                $phpDatetimeFormat = 'd/m/Y H:i';
                $dateSeparator = "/";
                $oracleDatetimeFormat = 'MM/DD/YYYY HH24:MI';
                $oracleDateFormat = 'DD/MM/YYYY';
            };

            if (!$university_code) throw new AfwBusinessException("unknown university FC [$fc]");
            if (!$file_code) $file_code = "$university_code-students-assass2" . $file_identity;
        }

        $Ymd = date("Y-m-d");
        $today_students_file = "/var/log/$university_code-assass2/$file_code-at-$Ymd.xlsx";
        if (!file_exists($today_students_file)) {
            throw new AfwBusinessException("file $today_students_file does not exist");
        }

        $success_arr = [];
        $info_arr = [];
        $warning_arr = [];
        $error_arr = [];
        $error_category_arr = [];
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
        $total_rows = 0;
        $nb_rows = 0;
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $row_num_start = $pageRows * ($page - 1);
            $row_num_end = $pageRows * $page - 1;


            list($excel, $my_head, $my_data) = UfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "Assass2::fromExcel", true);
            $sql = "";
            $nb_rows = 0;
            foreach ($my_data as $row => $my_row) {
                $errors = [];
                $errors1 = [];
                $errors2 = [];
                $sql_line2 = "";
                $sql_line = "";
                $my_row['STUDENTUNIQUEID'] = trim($my_row['STUDENTUNIQUEID']);
                $my_row['IDENTITYNUMBER'] = trim($my_row['IDENTITYNUMBER']);
                $student_unique_id = $my_row['STUDENTUNIQUEID'];
                if (!$my_row['STUDENTUNIQUEID']) {
                    if ($my_row['IDENTITYNUMBER']) {
                        $student_unique_id = "No STUDENTUNIQUEID|| for IDENTITYNUMBER=[" . $my_row['IDENTITYNUMBER'] . "]";
                        $errors[] = $student_unique_id;
                        $sql_line = "-- $student_unique_id";
                    } else {
                        $student_unique_id = "No STUDENTUNIQUEID|| for excel row num = [$row]";
                        $errors[] = $student_unique_id;
                        $sql_line = "-- $student_unique_id";
                    }
                } else {
                    $fc0 = substr($my_row['STUDENTUNIQUEID'], 0, 1);
                    if (is_numeric($fc0) and ($fc == "A")) {
                        $my_row['STUDENTUNIQUEID'] = $fc . $my_row['STUDENTUNIQUEID'];
                        $fc0 = $fc;
                    }
                    if (strtoupper($fc0) != $fc) {
                        throw new AfwBusinessException("Are you sure this file is from a correct source, STUDENTUNIQUEID should starts with `$fc` found value [$student_unique_id]");
                    }

                    list($my_row['LASTACADEMICSTATUSUPDATEDATE'],) = explode(" ", $my_row['LASTACADEMICSTATUSUPDATEDATE']);
                    $my_row['LASTACADEMICSTATUSUPDATEDATE'] = AfwDateHelper::parseGregDate($my_row['LASTACADEMICSTATUSUPDATEDATE'], $dateSeparator, $phpDateFormat);
                    $my_row['LASTUPDATEDATE'] = AfwDateHelper::formatGDate("", $phpDatetimeFormat); // force now datetime to be taken by naQel process
                    $beforeParse = $my_row['BIRTHDATE'];
                    $afterParse = AfwDateHelper::parseGregDate($beforeParse, $dateSeparator, $phpDateFormat);
                    $my_row['BIRTHDATE'] = $afterParse;
                    // die("beforeParse=$beforeParse AfwDateHelper::parseGregDate($beforeParse, $phpDateFormat, $dateSeparator) = $afterParse");
                    if ($my_row['GRADUATIONDATE'] and ($my_row['GRADUATIONDATE'] != "NULL")) $my_row['GRADUATIONDATE'] = AfwDateHelper::parseGregDate($my_row['GRADUATIONDATE'], $dateSeparator, $phpDateFormat);
                    if ($my_row['DISCLAIMERDATE'] and ($my_row['DISCLAIMERDATE'] != "NULL")) $my_row['DISCLAIMERDATE'] = AfwDateHelper::parseGregDate($my_row['DISCLAIMERDATE'], $dateSeparator, $phpDateFormat);
                    // remove time from ADMISSIONDATE if exists
                    list($my_row['ADMISSIONDATE'],) = explode(" ", $my_row['ADMISSIONDATE']);
                    $my_row['ADMISSIONDATE'] = AfwDateHelper::parseGregDate($my_row['ADMISSIONDATE'], $dateSeparator, $phpDateFormat);
                    // remove time from CURRENTACADEMICYEARDATE if exists
                    list($my_row['CURRENTACADEMICYEARDATE'],) = explode(" ", $my_row['CURRENTACADEMICYEARDATE']);
                    $my_row['CURRENTACADEMICYEARDATE'] = AfwDateHelper::parseGregDate($my_row['CURRENTACADEMICYEARDATE'], $dateSeparator, $phpDateFormat);


                    list($ADMY,) = explode("-", $my_row['ADMISSIONDATE']);
                    $my_row['ADMISSIONYEAR'] = $ADMY;
                    $my_row['CURRENTYEAR'] = $ADMY;
                    $my_row['BORDERNUMBER'] = 'null';

                    $my_row['INCLUDEDSPECIALIZATIONCODE'] = trim($my_row['INCLUDEDSPECIALIZATIONCODE']);
                    $my_row['IDENTITYTYPECODE'] = trim($my_row['IDENTITYTYPECODE']);
                    $my_row['STUDYLOCATIONCODE'] = trim($my_row['STUDYLOCATIONCODE']);

                    // because excel may remove 0 from left and length is always = 8
                    if ($my_row['INCLUDEDSPECIALIZATIONCODE']) $my_row['INCLUDEDSPECIALIZATIONCODE'] = AfwStringHelper::left_complete_len($my_row['INCLUDEDSPECIALIZATIONCODE'], 8, '0');
                    if ($my_row['IDENTITYTYPECODE']) $my_row['IDENTITYTYPECODE'] = AfwStringHelper::left_complete_len($my_row['IDENTITYTYPECODE'], 2, '0');
                    if ($my_row['STUDYLOCATIONCODE']) $my_row['STUDYLOCATIONCODE'] = AfwStringHelper::left_complete_len($my_row['STUDYLOCATIONCODE'], 7, '0');



                    $GRADUTIONYEAR = "";
                    if ($my_row['GRADUATIONDATE'] and ($my_row['GRADUATIONDATE'] != "NULL")) list($GRADUTIONYEAR,) = explode("-", $my_row['GRADUATIONDATE']);
                    $my_row['GRADUTIONYEAR'] = $GRADUTIONYEAR;

                    $my_row['GPA'] = round($my_row['GPA'] * 100) / 100;

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

                    if (strlen($my_row['ARABICFIRSTNAME']) < 2) {
                        $errors[] = "Invalid ARABICFIRSTNAME value|| [" . $my_row['ARABICFIRSTNAME'] . "]";
                    }
                    if (strlen($my_row['ARABICSECONDNAME']) < 2) {
                        $errors[] = "Invalid ARABICSECONDNAME value|| [" . $my_row['ARABICSECONDNAME'] . "]";
                    }
                    if (strlen($my_row['ARABICTHIRDNAME']) < 2) {
                        // $wars[] = "Invalid ARABICTHIRDNAME value [" . $my_row['ARABICTHIRDNAME'] . "]";
                        $my_row['ARABICTHIRDNAME'] = "";
                    }
                    if (strlen($my_row['ARABICFOURTHNAME']) < 2) {
                        $errors[] = "Invalid ARABICFOURTHNAME value|| [" . $my_row['ARABICFOURTHNAME'] . "]";
                    }

                    $my_row['ENGLISHFIRSTNAME'] = trim($my_row['ENGLISHFIRSTNAME']);
                    $my_row['ENGLISHSECONDNAME'] = trim($my_row['ENGLISHSECONDNAME']);
                    $my_row['ENGLISHTHIRDNAME'] = trim($my_row['ENGLISHTHIRDNAME']);
                    $my_row['ENGLISHFOURTHNAME'] = trim($my_row['ENGLISHFOURTHNAME']);

                    if ((strlen($my_row['ENGLISHSECONDNAME']) > 30) or (strlen($my_row['ENGLISHTHIRDNAME']) > 30)) {
                        list($my_row['ENGLISHSECONDNAME'], $my_row['ENGLISHTHIRDNAME']) = AfwStringHelper::dividePhraseToNStrings($my_row['ENGLISHSECONDNAME'] . " " . $my_row['ENGLISHTHIRDNAME'], 30, 2);
                    }

                    if (strlen($my_row['ENGLISHFIRSTNAME']) > 30) $my_row['ENGLISHFIRSTNAME'] = substr($my_row['ENGLISHFIRSTNAME'], 0, 30);
                    if (strlen($my_row['ENGLISHSECONDNAME']) > 30) $my_row['ENGLISHSECONDNAME'] = substr($my_row['ENGLISHSECONDNAME'], 0, 30);
                    if (strlen($my_row['ENGLISHTHIRDNAME']) > 30) $my_row['ENGLISHTHIRDNAME'] = substr($my_row['ENGLISHTHIRDNAME'], 0, 30);
                    if (strlen($my_row['ENGLISHFOURTHNAME']) > 30) $my_row['ENGLISHFOURTHNAME'] = substr($my_row['ENGLISHFOURTHNAME'], 0, 30);

                    if (strlen($my_row['ENGLISHFIRSTNAME']) < 2) {
                        $errors[] = "Invalid ENGLISHFIRSTNAME value|| [" . $my_row['ENGLISHFIRSTNAME'] . "]";
                    }
                    if (strlen($my_row['ENGLISHSECONDNAME']) < 2) {
                        $errors[] = "Invalid ENGLISHSECONDNAME value|| [" . $my_row['ENGLISHSECONDNAME'] . "]";
                    }
                    if (strlen($my_row['ENGLISHTHIRDNAME']) < 2) {
                        // $wars[] = "Invalid ENGLISHTHIRDNAME value [" . $my_row['ENGLISHTHIRDNAME'] . "]";
                        $my_row['ENGLISHTHIRDNAME'] = "";
                    }
                    if (strlen($my_row['ENGLISHFOURTHNAME']) < 2) {
                        $errors[] = "Invalid ENGLISHFOURTHNAME value|| [" . $my_row['ENGLISHFOURTHNAME'] . "]";
                    }

                    $my_row['SCIENTIFICDEGREECODE'] = intval($my_row['SCIENTIFICDEGREECODE']);
                    $my_row['SPECIALTYCLASSIFICATIONCODE'] = intval($my_row['SPECIALTYCLASSIFICATIONCODE']);
                    $my_row['EDUCATIONALSUBLEVELCODE'] = intval($my_row['EDUCATIONALSUBLEVELCODE']);
                    $my_row['RATINGCODE'] = intval($my_row['RATINGCODE']);
                    $my_row['REGISTRATIONSTATUSCODE'] = intval($my_row['REGISTRATIONSTATUSCODE']);
                    $my_row['GPATYPECODE'] = intval($my_row['GPATYPECODE']);


                    // 1)	rule about fields ScientificDegreeCode and  HasThesis
                    // If ScientificDegreeCode = 4 or ScientificDegreeCode = 5  (doctorat or master)
                    if ($my_row['SCIENTIFICDEGREECODE'] == 4 or $my_row['SCIENTIFICDEGREECODE'] == 5) {
                        // Then HasThesis should be = 0 or = 1 (otherwise raise error)
                        if ($my_row['HASTHESIS'] != 0 && $my_row['HASTHESIS'] != 1) {
                            $errors[] = "Invalid value for HasThesis field||[" . $my_row['HASTHESIS'] . "]";
                        }
                    } else {
                        // Else HasThesis should be = null (in this case if user has sent HasThesis = 0, You in DB put HasThesis = null, 
                        if ((!$my_row['HASTHESIS']) or ($my_row['HASTHESIS'] == '0')) $my_row['HASTHESIS'] = 'null';
                        else $errors[] = "HasThesis should be null for scientific degree code different than 4 and 5 (doctorat and master)";
                    }

                    // 2)	rule about field ThesisTitle it should be null except if HasThesis = 1 then it should be filled with non-empty string , 
                    if ($my_row['HASTHESIS'] == 1) {
                        if (!$my_row['THESISTITLE'] or ($my_row['THESISTITLE'] == '')) {
                            $errors[] = "ThesisTitle should be filled with non-empty string when HasThesis is 1";
                        }
                    } else {
                        // if field ThesisTitle should be null and user has sent empty string accept it but you put in DB ThesisTitle = null
                        $my_row['THESISTITLE'] = 'null';
                    }







                    

                    // if($university_code == "coe") $oracleDatetimeFormat = 'DD/MM/YYYY HH24:MI:SS';

                    list($errors1, $sql_line) = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.ACADEMICDETAILS" . $suffix, $tableColsArr["STUDENTS.ACADEMICDETAILS"], $my_row, $isInTablePK["STUDENTS.ACADEMICDETAILS"], $isScalar, $isToSetNullWhenEmptyString, $isDate, $isDatetime, $isMandatory, $oracleDatetimeFormat,  ['assass1' => $isAssass1Only]);
                    list($errors2, $sql_line2) = AfwSqlHelper::oracleSqlInsertOrUpdate("STUDENTS.PERSONALINFO" . $suffix, $tableColsArr["STUDENTS.PERSONALINFO"], $my_row, $isInTablePK["STUDENTS.PERSONALINFO"], $isScalar, $isToSetNullWhenEmptyString, $isDate, $isDatetime, $isMandatory, $oracleDatetimeFormat, ['assass1' => $isAssass1Only]);
                }

                if ((count($errors) == 0) and (count($errors1) == 0) and (count($errors2) == 0)) {
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
                    $errors1_nb = count($errors1);
                    $errors_nb = count($errors);
                    foreach ($errors2 as $err_message) {
                        list($err_message_categ,) = explode("||", $err_message);
                        $err_message_categ = trim($err_message_categ);
                        $error_category_arr[$err_message_categ] = true;
                    }
                    foreach ($errors1 as $err_message) {
                        list($err_message_categ,) = explode("||", $err_message);
                        $err_message_categ = trim($err_message_categ);
                        $error_category_arr[$err_message_categ] = true;
                    }
                    foreach ($errors as $err_message) {
                        list($err_message_categ,) = explode("||", $err_message);
                        $err_message_categ = trim($err_message_categ);
                        $error_category_arr[$err_message_categ] = true;
                    }
                    $error_student = "for student ID ($student_unique_id) :";
                    $error_student_personal_info = "";
                    $error_student_academic_details = "";
                    $error_student_xls_row = "";
                    if ($errors2_nb > 0) $error_student_personal_info .= "\nThe personal info contain $errors2_nb errors : \n" . implode("\n", $errors2);
                    if ($errors1_nb > 0)  $error_student_academic_details .= "\nThe academic details contain $errors1_nb errors : \n" . implode("\n", $errors1);
                    if ($errors_nb > 0)  $error_student_xls_row .= "\nThe excel row-data contain $errors_nb errors : \n" . implode("\n", $errors);
                    if ($error_student_xls_row or $error_student_personal_info or $error_student_academic_details) {
                        $error_arr[] = $error_student . $error_student_personal_info . $error_student_academic_details . $error_student_xls_row;
                    }
                }
            }

            $sql .= "\nselect 'after $file_code-at-$Ymd-p$page' as title, count(*) as record_count from STUDENTS.PERSONALINFO where STUDENTUNIQUEID like '$fc%';\n";
            $sql .= "\nselect 'after $file_code-at-$Ymd-p$page' as title, count(*) as record_count from STUDENTS.ACADEMICDETAILS where STUDENTUNIQUEID like '$fc%';\n";

            if (true) {
                $sql_prefix = "";
                $sql_suffix = "";

                if ($php_generation_folder != "no-gen") {
                    $relative_sql_fileName = "$file_code-at-$Ymd-p$page.sql";
                    $sql_fileName = $php_generation_folder . $dir_sep . $relative_sql_fileName;
                    try {
                        $nb_errors = count($error_arr);
                        if ($nb_errors == 0) $status = "successfully";
                        else {
                            $status = "and $nb_errors error(s)";
                            $errors_text = implode("\n", $error_arr);
                            $errors_fileName = $php_generation_folder . $dir_sep . "errors-in-$file_code-at-$Ymd-p$page.txt";
                            UfwFileSystem::write($errors_fileName, $errors_text);
                        }
                        UfwFileSystem::write($sql_fileName, $sql_prefix . $sql . $sql_suffix);
                        $info_arr[] = "file $sql_fileName generated with $nb_rows row(s) $status";
                        $success_arr[] = "@E:\\work\\projects\\pt\\TETCO\\technical\\moeupdate\\doing\\$relative_sql_fileName";
                    } catch (Exception $e) {
                        $error_arr[] = "failed to write sql file $sql_fileName : " . $e->getMessage();
                    } finally {
                    }
                } else {
                    $warning_arr[] = "file generation is disabled (see sql_generation_folder parameter in system config file)";
                }

                $categErrIndex = 0;
                foreach ($error_category_arr as $err_message_categ => $bool00) {
                    $categErrIndex++;
                    $warning_arr[] = "Error category $categErrIndex => $err_message_categ";
                }
            }

            $total_rows += $nb_rows;
        }
        if ($showExamples) {
            $tech_arr[] = "sql examples : \n<br>" . implode("\n<br>", $sql_examples);
        }

        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["file" => $today_students_file,  "total_records" => $total_rows];

        return AfwFormatHelper::pbm_return($error_arr, $info_arr, $warning_arr, $success_arr, $result_arr, $tech_arr);
    }


    public static function fromExcelToApi($lang = "ar", $params = [])
    {
        $pageStart = 1;
        $pageRows = 1000;
        $nbPages = 1;
        $student_count = 0;
        $file_identity = "";
        $university_code = "";
        $phpDateFormat = 'Y-m-d';
        if (count($params) > 0) {
            $suffix = $params["suffix"] ?? "";
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
            $showExamples = $params["example"];
            if (!$block and !$date_from) throw new AfwBusinessException("The identity of file you want to uplaod is to define by [date_from, date_to] or [block] attribute");
            if ($block) {
                $file_identity .= "-block-$block";
            } else {
                if ($date_from) $file_identity .= "-from-" . $date_from;
                if ($date_to) $file_identity .= "-to-" . $date_to;
            }
            $fc = $params["fc"];
            if (!$fc) $fc = "A";

            if ($fc == "A") {
                $university_code = "pt";
                $phpDateFormat = 'm/d/Y'; // kol marra haja wa rabbi yostor
            } elseif ($fc == "B") {
                $university_code = "coe";
                $phpDateFormat = 'd/m/Y';
            };

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
        $api_examples = [];
        $tech_arr = [];
        $pageEnd = $pageStart + $nbPages - 1;
        $info_arr[] = "<b>generation of pages from $pageStart to $pageEnd</b>";
        $done_rows = 0;
        $nb_errors = 0;
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $row_num_start = $pageRows * ($page - 1);
            $row_num_end = $pageRows * $page - 1;


            list($excel, $my_head, $my_data) = UfwExcel::getExcelFileData($today_students_file, $row_num_start, $row_num_end, "Assass2::fromExcel", true);
            foreach ($my_data as $numr => $my_row) {
                list($sucess, $message, $response_api, $response_api_decoded, $attributes_values_json) =
                    self::sync_with_assass2_api($my_row);

                $warning_arr[] = "sync_with_assass2_api on ".var_export($my_row, true)." gived sucess=$sucess, message=$message";    

                if($sucess and !$attributes_values_json) {
                    $sucess = false;
                    $message = "No json constructed";
                }

                if($sucess and !$response_api) {
                    $sucess = false;
                    $message = "No response from api";
                }

                if($sucess and $response_api_decoded) {                    
                    if(!$response_api_decoded->status) {
                        $sucess = false;
                        $message = $response_api_decoded->message;
                    } 
                }    
                
                if(!$sucess) {
                    $error_arr[] = "row $numr : STUDENTUNIQUEID=".$my_row['STUDENTUNIQUEID']." : ".$message;   
                    $warning_arr[] = " executed with json : ";                       
                    $warning_arr[] = $attributes_values_json;
                    $warning_arr[] = "and got response : ";                       
                    $warning_arr[] = $response_api;
                    $nb_errors++;
                }
                else {
                    $done_rows++;
                    $info_arr[] = "row $numr : STUDENTUNIQUEID=".$my_row['STUDENTUNIQUEID']." done with json : ";                       
                    $info_arr[] = $attributes_values_json;
                    $info_arr[] = "and response : ";                       
                    $info_arr[] = $response_api;
                }
            }
        }

        $info_arr[] = "successfully done $done_rows row(s)";
        if($nb_errors>0) $warning_arr[] = "$nb_errors row(s) skipped with error(s)";

        if ($showExamples) {
            $tech_arr[] = "api examples : \n<br>" . implode("\n<br>", $api_examples);
        }

        // write the $sql in an sql file like generation of cline (same folder)


        $result_arr = ["file" => $today_students_file,  "done" => $done_rows,  "errors" => $nb_errors];

        return AfwFormatHelper::pbm_result($error_arr, $info_arr, $warning_arr, "<br>\n", $tech_arr, $result_arr);
    }


    /**
     * @param array $row
     * @return array
     */

    public static function sync_with_assass2_api($row)
    {
        $phpDatetimeFormat = 'm/d/Y H:i:s';
        $assass2_endpoint = AfwSession::config("assass2_endpoint", "");
        if (!$assass2_endpoint) {
            $error_0 = 'failed no assass2_endpoint defined in config file';
            return [false, $error_0, "Not called", null, null, null];
        } 
        $curl = curl_init();

        $attributes = [
            "StudentUniqueId",
            "InstituteCode",
            "ArabicFirstName",
            "ArabicSecondName",
            "ArabicThirdName",
            "ArabicFourthName",
            "EnglishFirstName",
            "EnglishSecondName",
            "EnglishThirdName",
            "EnglishFourthName",
            "IdentityTypeCode",
            "IdentityNumber",
            "BirthDate",
            "GenderCode",
            "NationalityCode",
            "IsSpecialNeeds",
            "SpecialNeedsTypeCode",
            "Email",
            "MobileNumber",
            "LastUpdateDate",
            "HasScholarship",
            "ScholarshipTypeCode",
            "ScholarshipClassificationCode",
            "StudentAcademicNumber",
            "ScientificDegreeCode",
            "AcademicStatusCode",
            "StudyLocationCode",
            "CurrentCollegeCode",
            "AcceptedCollegeCode",
            "SectionCode",
            "MajorCode",
            "MinorCode",
            "SpecialtyClassificationCode",
            "EducationalSubLevelCode",
            "IncludedSpecializationCode",
            "StudyProgramPeriodUnitCode",
            "StudyProgramPeriod",
            "RequestedCreditHoursCount",
            "RegisteredCreditHoursCount",
            "PassedCreditHoursCount",
            "RemainingCreditHoursCount",
            "RegistrationStatusCode",
            "CurrentAcademicYearDate",
            "CurrentSemesterCode",
            "GraduationDate",
            "StudyTypeCode",
            "AdmissionDate",
            "HasStudentReward",
            "StudentRewardAmount",
            "GPATypeCode",
            "GPA",
            "RatingCode",
            "HasThesis",
            "ThesisTitle",
            "LastAcademicStatusUpdateDate",
            "DisclaimerDate",
            "IsLastAcademicDataRecord"
        ];

        $attributes_values = [];
        foreach ($attributes as $attribute) {
            $attributeUC = strtoupper($attribute);
            $the_value = "".$row[$attributeUC]; // issam want it as string
            if(AfwStringHelper::stringContain($attributeUC, "DATE")) {
                list($the_date, $the_time) = explode(" ", $the_value);

                if(AfwStringHelper::stringContain($the_date, "-")) {
                    $the_date = AfwDateHelper::formatGDate($the_date, "m/d/Y");
                }

                $the_value = $the_date;
                if($the_time) $the_value .= " $the_time";
            }
            $attributes_values[$attribute] = $the_value;
        }

        // rule13) Ignore the value given by API caller for attribute LASTUPDATEDATE and force it to be NOW date-time value at the instant the API is called because otherwise in NAQEL system they will not migrate it to ASSASS2 systems if the date is old
        // @todo : issam should do it in assass2 api not me here (rule 13 above)
        $attributes_values['LastUpdateDate'] = AfwDateHelper::formatGDate("", $phpDatetimeFormat); // force now datetime to be taken by naQel process
        
        $attributes_values['StudyLocationCode'] = trim($attributes_values['StudyLocationCode']);
        if ($attributes_values['StudyLocationCode']) $attributes_values['StudyLocationCode'] = AfwStringHelper::left_complete_len($attributes_values['StudyLocationCode'], 7, '0');


        // die("attributes_values = ".var_export($attributes_values, true));

        $attributes_values_json = json_encode($attributes_values);

        // die("attributes_values_json = ".var_export($attributes_values_json, true));

        curl_setopt_array($curl, array(
            CURLOPT_URL => $assass2_endpoint . '/sync',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $attributes_values_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $message = sprintf('cURL error: "%s"', curl_error($curl));
            return [false, $message, $response, json_decode($response), $attributes_values_json];
        }
        curl_close($curl);
        return [true, '', $response, json_decode($response), $attributes_values_json];
    }
}
