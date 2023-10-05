function region_onchange() {
  city_reload();
}

/*******************************  end of  region_id_onchange  *****************************/

function city_reload() {
  // alert(""+$("#region_id").val());
  // fld_deps_vals = ''  ;
  // alert("city_id_reload running deps = [] = ["+fld_deps_vals+"] ");
  $.getJSON(
    "../lib/api/anstab.php",
    {
      keepCurrent: 1,
      cl: "Invester",
      currmod: "license",
      objid: "1360",
      attribute: "city_id",
      attributeval: $("#city").val(),

      post_attr_region_id: $("#region").val(),
    },

    function (result) {
      var $select = $("#city");
      $select.find("option").remove();
      $select.append("<option value=0></option>");
      $.each(result, function (i, field) {
        $select.append("<option value=" + i + ">" + field + "</option>");
      });
    }
  );
}
/*******************************  end of  city_id_reload  *****************************/

function birthDateChanged()
{
    // alert("rafik="+$("#birth_date_en").val());
    $("#birth_date_en").val("");
}

function birthDateEnChanged()
{
    $("#birth_date").val("");
}

///////////////////////////////////////////////////

function set_mainwork_start_aya(from, from_title, to, to_title)
{
  console.log("set_mainwork_start_aya executed");
    $("#mainwork_start_paragraph_num").val(from);
    $("#ayat-btn-mainwork_start_paragraph_num").val(from_title);  
    $("#mainwork_end_paragraph_num").val(to);
    $("#ayat-btn-mainwork_end_paragraph_num").val(to_title);  
    
}

function set_mainwork_end_aya(from, from_title, to, to_title)
{
  console.log("set_mainwork_end_aya executed");
    $("#mainwork_start_paragraph_num").val(from);
    $("#ayat-btn-mainwork_start_paragraph_num").val(from_title);  
    $("#mainwork_end_paragraph_num").val(to);
    $("#ayat-btn-mainwork_end_paragraph_num").val(to_title);  
    
}



///////////////////////////////////////////////////



function mainwork_start_changed(hard)
{
  console.log("mainwork_start_changed executed");
   if(hard) $("#mainwork_start_paragraph_num").val(1);
   $("#ayat-btn-mainwork_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#ayat-btn-mainwork_start_paragraph_num").attr("disabled","disabled");
   $("#hzm-selector-div-mainwork_start_paragraph_num").addClass("hide");
}

function mainwork_start_chapter_id_onchange()
{
  mainwork_start_changed(true);
  $("#mainwork_start_page_num").val(0);
  $("#mainwork_start_page_num").attr("disabled","disabled");
}

function mainwork_start_page_num_onchange()
{
  mainwork_start_changed(false);
}

function homework_start_changed(hard)
{
  console.log("homework_start_changed executed");
   if(hard) $("#homework_start_paragraph_num").val(1);
   $("#ayat-btn-homework_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#ayat-btn-homework_start_paragraph_num").attr("disabled","disabled");
   $("#hzm-selector-div-homework_start_paragraph_num").addClass("hide");
   
}

function homework_start_chapter_id_onchange()
{
  homework_start_changed(true);
  $("#homework_start_page_num").val(0);
  $("#homework_start_page_num").attr("disabled","disabled");
}

function homework_start_page_num_onchange()
{
  
  homework_start_changed(false);
}



function homework2_start_changed(hard)
{
  console.log("homework2_start_changed executed");
  if(hard) $("#homework2_start_paragraph_num").val(1);
   $("#ayat-btn-homework2_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#ayat-btn-homework2_start_paragraph_num").attr("disabled","disabled");
   $("#hzm-selector-div-homework2_start_paragraph_num").addClass("hide");
   
}

function homework2_start_chapter_id_onchange()
{
  homework2_start_changed();
  $("#homework2_start_page_num").val(0);
  $("#homework2_start_page_num").attr("disabled","disabled");
}


function homework2_start_page_num_onchange()
{
  homework2_start_changed();
}



//************************************************** */


function homework_end_changed(hard)
{
  console.log("homework_end_changed executed");
  if(hard) $("#homework_end_paragraph_num").val(1);
   // !!! below the div is in start input not end
   $("#ayat-btn-homework_start_paragraph_num").attr("disabled","disabled");
   $("#ayat-btn-homework_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#hzm-selector-div-homework_start_paragraph_num").addClass("hide");
   // !!! !!! !!! !!! !!! !!! !!! 
   
}

function homework_end_chapter_id_onchange()
{
  homework_end_changed(true);
  $("#homework_end_page_num").val(0);
  $("#homework_end_page_num").attr("disabled","disabled");
}

function homework_end_page_num_onchange()
{
  homework_end_changed(false);
}




function mainwork_end_changed(hard)
{
   console.log("mainwork_end_changed executed");
   if(hard) $("#mainwork_end_paragraph_num").val(1);
   // !!! below the div is in start input not end
   $("#ayat-btn-mainwork_start_paragraph_num").attr("disabled","disabled");
   $("#ayat-btn-mainwork_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#hzm-selector-div-mainwork_start_paragraph_num").addClass("hide");
   // !!! !!! !!! !!! !!! !!! !!!
}

function mainwork_end_page_num_onchange()
{
  mainwork_end_changed(false);
}

function mainwork_end_chapter_id_onchange()
{
  mainwork_end_changed(true);
  $("#mainwork_end_page_num").val(0);
  $("#mainwork_end_page_num").attr("disabled","disabled");
}

function homework2_end_changed(hard)
{
  console.log("homework2_end_changed executed");
   if(hard) $("#homework2_end_paragraph_num").val(1);
   // !!! below the div is in start input not end
   $("#ayat-btn-homework2_start_paragraph_num").attr("disabled","disabled");
   $("#ayat-btn-homework2_start_paragraph_num").val(" اضغط على زر حفظ البيانات لتحميل السورة");
   $("#hzm-selector-div-homework2_start_paragraph_num").addClass("hide");
   // !!! !!! !!! !!! !!! !!! !!!
}

function homework2_end_chapter_id_onchange()
{
  homework2_end_changed();
  $("#homework2_end_page_num").val(0);
  $("#homework2_end_page_num").attr("disabled","disabled");
}

function homework2_end_page_num_onchange()
{
  homework2_end_changed();
}



///////////////////////////////////////////////////



function select_homework_start_aya(from, from_title, to, to_title)
{
  console.log("select_homework_start_aya executed");
  $("#homework_start_paragraph_num").val(from);
  $("#ayat-btn-homework_start_paragraph_num").val(from_title); 
  $("#homework_end_paragraph_num").val(to);
  $("#ayat-btn-homework_end_paragraph_num").val(to_title);  
    
}

function select_homework_end_aya(from, from_title, to, to_title)
{
  console.log("select_homework_end_aya executed");
    $("#homework_start_paragraph_num").val(from);
    $("#ayat-btn-homework_start_paragraph_num").val(from_title); 
    $("#homework_end_paragraph_num").val(to);
    $("#ayat-btn-homework_end_paragraph_num").val(to_title);  
    
}


function select_homework2_start_aya(from, from_title, to, to_title)
{
  console.log("select_homework2_start_aya executed");
    $("#homework2_start_paragraph_num").val(from);
    $("#ayat-btn-homework2_start_paragraph_num").val(from_title); 
    $("#homework2_end_paragraph_num").val(to);
    $("#ayat-btn-homework2_end_paragraph_num").val(to_title); 
    
}

function select_homework2_end_aya(from, from_title, to, to_title)
{
  console.log("select_homework2_end_aya executed");
    $("#homework2_start_paragraph_num").val(from);
    $("#ayat-btn-homework2_start_paragraph_num").val(from_title);
    $("#homework2_end_paragraph_num").val(to);
    $("#ayat-btn-homework2_end_paragraph_num").val(to_title);  
    
}



function select_mainwork_aya_interval(from, from_title, to, to_title)
{
  console.log("select_mainwork_aya_interval executed");
    $("#mainwork_start_paragraph_num").val(from);
    $("#mainwork_end_paragraph_num").val(to);
    $("#ayat-btn-mainwork_start_paragraph_num").val('من '+from_title+' إلى '+to_title);    
    //$("#ayat-btn-mainwork_end_paragraph_num").val(aslan hia hidden); 
    
}

function select_homework_aya_interval(from, from_title, to, to_title)
{
  console.log("select_homework_aya_interval executed");
    $("#homework_start_paragraph_num").val(from);
    $("#homework_end_paragraph_num").val(to);
    $("#ayat-btn-homework_start_paragraph_num").val('من '+from_title+' إلى '+to_title);    
    //$("#ayat-btn-homework_end_paragraph_num").val(aslan hia hidden); 
    
}

function select_homework2_aya_interval(from, from_title, to, to_title)
{
  console.log("select_homework2_aya_interval executed");
    $("#homework2_start_paragraph_num").val(from);
    $("#homework2_end_paragraph_num").val(to);
    $("#ayat-btn-homework2_start_paragraph_num").val('من '+from_title+' إلى '+to_title);    
    //$("#ayat-btn-homework2_end_paragraph_num").val(aslan hia hidden); 
    
}

function select_mainwork_start_aya(from, from_title, to, to_title)
{
  return select_mainwork_end_aya(from, from_title, to, to_title);     
}

function select_mainwork_end_aya(from, from_title, to, to_title)
{
  console.log("select_mainwork_end_aya executed");
    $("#mainwork_start_paragraph_num").val(from);
    $("#ayat-btn-mainwork_start_paragraph_num").val(from_title);
    $("#mainwork_end_paragraph_num").val(to);
    $("#ayat-btn-mainwork_end_paragraph_num").val(to_title);  
    
}

function select_book_aya(from, from_title, to, to_title)
{
    console.log("select_book_aya executed");
    $("#main_paragraph_num").val(from);
    $("#ayat-btn-main_paragraph_num").val(from_title);
}


function study_program_type_onchange()
{
    if($("#study_program_type").val() == 2)
    {
      $("#a_pct").val(0);
      $("#a_pct").attr("disabled","disabled");
      $("#b_pct").val('');
      $("#b_pct").removeAttr("disabled");
      $("#c_pct").val('');
      $("#c_pct").removeAttr("disabled");
    }
    else if($("#study_program_type").val() == 1)
    {
      $("#b_pct").val(0);
      $("#b_pct").attr("disabled","disabled");
      
      $("#a_pct").val('');
      $("#a_pct").removeAttr("disabled");
      $("#c_pct").val('');
      $("#c_pct").removeAttr("disabled");
    }
    else
    {
      $("#a_pct").val('');
      $("#a_pct").attr("disabled","disabled");
      $("#b_pct").val('');
      $("#b_pct").attr("disabled","disabled");
      $("#c_pct").val('');
      $("#c_pct").attr("disabled","disabled");
    }
}

///////////////////////////////////////////////////