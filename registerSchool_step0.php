<div class="modal-dialog">
        <div class="modal-content">
                        <?
                          //
                         $register_title = AfwLanguageHelper::tarjemMessage("REGISTER_SCHOOL_TITLE",$uri_module); 
                         $register_sentence = AfwLanguageHelper::tarjemMessage("REGISTER_SCHOOL_SENTENCE",$uri_module);
                         // $register_data_type = AfwLanguageHelper::tarjemMessage("REGISTER_DATA_TYPE",$uri_module);
                         $register_conditions = AfwLanguageHelper::tarjemMessage("REGISTER_SCHOOL_CONDITIONS",$uri_module);
                         
                        ?>
       
                
                <div class="modal-header">
                        <span style="float: right;">
                                <a href="index.php" title="الرئيسسة" style="float: right;">
                                        <img src="../<?=$MODULE?>/pic/logo.png" alt="<?=$register_title?>" title="<?=$register_title?>"></a>
                                </a>
                                <h1 style="float: right;margin-right: 30px;padding-top: 0px;vertical-align: middle;"><?=$register_title?></h1>
                        </span>
  
                </div>
                <div class="modal-body">
                        <h3><?=$register_sentence?></h3>
                        <form id="formRegister" name="formRegister" method="post" action="registerSchool.php"  onSubmit="return checkRegisterForm();" dir="rtl" enctype="multipart/form-data">
                            <?
                                genereInputForAttribute("school_type_id", $school, $lang, "school_type_id");
                                genereInputForAttribute("school_name_ar", $school, $lang, "school_name_ar");
                                genereInputForAttribute("school_name_en", $school, $lang, "school_name_en");
                                genereInputForAttribute("genre_id", $school, $lang, "genre_id");
                                genereInputForAttribute("lang_id", $school, $lang, "lang_id");
                                genereInputForAttribute("scapacity", $school, $lang, "scapacity");
                                genereInputForAttribute("period_mfk", $school, $lang, "period_mfk");

                                genereInputForAttribute("city_id", $school, $lang, "city_id");
                                genereInputForAttribute("address", $school, $lang, "address");
                                genereInputForAttribute("quarter", $school, $lang, "quarter");
                                genereInputForAttribute("maps_location_url", $school, $lang, "maps_location_url");
                            ?>
                            
                            <input type="hidden" name="next_step" value="1">
                            <h3><?=$register_conditions?></h3>
                            <input type="submit" name="registerGo" value="التسجيل" class="btnbtsp btn-primary" />
                                
                        </form>
                </div>
        </div>
</div>

<script type="text/javascript">
function checkRegisterForm() 
{
	if($("#genre_id").val() == 0 || $("#school_name_ar").val() == "" || $("#school_name_en").val() == "" || $("#school_type_id").val() == "" || $("#lang_id").val() == "" || ($("#city_id").val() == "") || ($("#maps_location_url").val() == "")) {
		alert("الرجاء إدخال بيانات التسجيل  كاملة، كل الحقول اجبارية");
		return false;
	}
        else {
		return true;
	}
}
</script>
