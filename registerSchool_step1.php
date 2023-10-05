<div class="modal-dialog">
        <div class="modal-content">
                        <?
                          //
                         $register_title = Auser::traduireMessage("REGISTER_SCHOOL_PARAMS_TITLE",$uri_module); 
                         $register_sentence = Auser::traduireMessage("REGISTER_SCHOOL_PARAMS_SENTENCE",$uri_module);
                         // $register_data_type = Auser::traduireMessage("REGISTER_DATA_TYPE",$uri_module);
                         $register_conditions = Auser::traduireMessage("REGISTER_SCHOOL_PARAMS_CONDITIONS",$uri_module);
                         
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
                                genereInputForAttribute("school_id", $schoolConf, $lang, "school_id");
                                genereInputForAttribute("nb_rooms", $schoolConf, $lang, "nb_rooms");
                                genereInputForAttribute("holidays", $schoolConf, $lang, "holidays");
                                genereInputForAttribute("levels", $schoolConf, $lang, "levels");
                                genereInputForAttribute("courses", $schoolConf, $lang, "courses");
                            ?>
                            
                            <input type="hidden" name="next_step" value="2">
                            <h3><?=$register_conditions?></h3>
                            <input type="submit" name="registerGo" value="التسجيل" class="btnbtsp btn-primary" />
                                
                        </form>
                </div>
        </div>
</div>

<script type="text/javascript">
function checkRegisterForm() 
{
	if($("#school_id").val() == 0 || $("#nb_rooms").val() <= 0 || $("#holidays").val() == "" || $("#levels").val() == "" || $("#courses").val() == "" ) 
        {
		alert("الرجاء إدخال بيانات التسجيل  كاملة، كل الحقول اجبارية");
		return false;
	}
        else {
		return true;
	}
}
</script>
