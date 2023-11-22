<?
$importRequirement["student_idn"] = array(trad_ar=>"رقم الهوية", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["student_idn_type"] = array(trad_ar=>"نوع الهوية", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["student_genre"] = array(trad_ar=>"الجنس", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["student_nationality"] = array(trad_ar=>"الجنسية", mandatory=>false, type=>TEXT, );
$importRequirement["student_firstname"] = array(trad_ar=>"الاسم الأول", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["student_fatherfirstname"] = array(trad_ar=>"اسم الأب", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["student_lastname"] = array(trad_ar=>"اسم العائلة", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["student_birthdate"] = array(trad_ar=>"تاريخ الولادة - ميلادي", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["student_hijri_birthdate"] = array(trad_ar=>"تاريخ الولادة - هجري", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["student_level_class"] = array(trad_ar=>"الصف الدراسي لهذه السنة", mandatory=>true, required=>true, type=>TEXT);

$importRequirement["parent_idn"] = array(trad_ar=>"رقم الهوية للولي", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["parent_idn_type"] = array(trad_ar=>"نوع الهوية للولي", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["parent_genre"] = array(trad_ar=>"الجنس للولي", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["parent_nationality"] = array(trad_ar=>"جنسية الولي", mandatory=>false, required=>true, type=>TEXT);
$importRequirement["parent_mobile"] = array(trad_ar=>"جوال الولي", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["parent_firstname"] = array(trad_ar=>"اسم الولي", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["parent_lastname"] = array(trad_ar=>"اسم عائلة الولي", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["parent_relationship_type"] = array(trad_ar=>"صلة قرابة الولي", mandatory=>true, required=>true, type=>TEXT);
$importRequirement["parent_email"] = array(trad_ar=>"البريد الالكتروني للولي", mandatory=>false, required=>true, type=>TEXT);

?> 