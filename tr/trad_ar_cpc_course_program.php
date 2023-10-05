<?php
	$trad["cpc_course_program"]["cpccourseprogram.single"] = "برنامج دراسي";
	$trad["cpc_course_program"]["cpccourseprogram.new"] = "جديد";
	$trad["cpc_course_program"]["cpc_course_program"] = "البرامج الدراسية";
	$trad["cpc_course_program"]["course_program_name_ar"] = "مسمى البرنامج الدراسي";
	$trad["cpc_course_program"]["course_program_name_en"] = "مسمى البرنامج الدراسي بالانجليزي";
	$trad["cpc_course_program"]["levels_template_id"] = "نموذج المستويات الدراسية";
	$trad["cpc_course_program"]["program_type_id"] = "ترميز الوزارة";
	
	$trad["cpc_course_program"]["cpcCourseProgramSchoolList"] = "قائمة الوحدات الدراسية";
	$trad["cpc_course_program"]["cpcCourseProgramBookList"] = "قائمة الكتب";

	


	/*
	$trad["cpc_course_program"]["cpccourseprogram.single"] = "برنامج دراسي";
	$trad["cpc_course_program"]["cpccourseprogram.new"] = "جديد";
	$trad["cpc_course_program"]["cpc_course_program"] = "البرامج الدراسية";
	$trad["cpc_course_program"]["course_program_name_ar"] = "مسمى البرنامج الدراسي";
	$trad["cpc_course_program"]["course_program_name_en"] = "مسمى البرنامج الدراسي بالانجليزي";
	$trad["cpc_course_program"]["levels_template_id"] = "نموذج المستويات الدراسية";
	*/


	$trad["cpc_course_program"]["duration"] = "المدة باليوم";
	$trad["cpc_course_program"]["h_duration"] = "المدة بالساعة";
	$trad["cpc_course_program"]["accreditation_num"] = "رقم الاعتماد";
	$trad["cpc_course_program"]["duration_desc"] = "وصف المدة";
	$trad["cpc_course_program"]["school_level_id"] = "المستوى";

	 


	$trad["cpc_course_program"]["step1"] = "الخصائص العامة";
	$trad["cpc_course_program"]["step2"] = "الوحدات المنفذة";
	$trad["cpc_course_program"]["step3"] = "الكتب";


	if(!class_exists('SisCpcCourseProgramTranslator'))
	{
		class SisCpcCourseProgramTranslator {
			public static function init($obj)
			{
				if($obj instanceOf CpcCourseProgram)
				{

				}
			}
		}
	}

	
?>