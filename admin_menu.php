<?php
      $file_dir_name = dirname(__FILE__); 
      
      
      require_once("$file_dir_name/../rfw/rfw_factory.php");      
      require_once("$file_dir_name/../rfw/rfw.php");
      
      $rfwFactoryObj = new RFWFactory();
      
      $module_id = 44;
      $module_obj = new Module();
      $module_obj->load($module_id);
      
      $sub_modules = $module_obj->get("smd");
      
      $numfrontclass = 0;
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      $theme[$numtheme] = $module_obj->getMainsh()->getDisplay(). " - " . $module_obj->getDisplay();
      foreach($sub_modules as $sub_module_id => $submodule_obj) {
                
                $at = new Atable();
                $at->select("id_module",$module_id);
                $at->select("id_sub_module",$sub_module_id);
                $at->select("avail",'Y');
                $at_list = $at->loadMany($limit = "", $order_by = "id_sub_module asc");
                
                if(is_array($at_list) and count($at_list)) 
                {
                        $subtheme[$numtheme][$numsubtheme] = $submodule_obj->getDisplay();
                        foreach($at_list as $atb_id => $atb_obj)
                        {
                                     $atb_obj_class = $atb_obj->getTableClass();
                                     $atb_obj_desc =  $atb_obj->getVal("titre_short");
                                     $atb_obj_name =  $atb_obj->getVal("titre_u");
                                     $atb_obj_qedit_fixmcols =  $atb_obj->get("qfim_fields_mfk");
                                     
                                     $nb_fim_cols = 0;
                                     unset($fixmcol_obj1);
                                     unset($fixmcol_obj2);
                                     foreach($atb_obj_qedit_fixmcols as $fixmcol_obj) {
                                          if(($fixmcol_obj->getId()) and ($nb_fim_cols<2)) 
                                          {
                                                  $nb_fim_cols++;      
                                                  ${"fixmcol_obj$nb_fim_cols"} = $fixmcol_obj;
                                          }
                                     }
                                     
                                     $fixm_cases = array();
                                     
                                     if($fixmcol_obj1) 
                                     {
                                        $fixmcol_obj1_tit = $fixmcol_obj1->getVal("titre_short");
                                        $myAnsRfw1 =& $rfwFactoryObj->getObject($fixmcol_obj1->getVal("answer_table_id"));
                                        $ans_items1 = $myAnsRfw1->loadMany();
                                        foreach($ans_items1 as $ans_item1) {
                                                  $fixmtit = "إدارة ".$atb_obj_desc." ".$ans_item1->getDisplay(); // $fixmcol_obj1_tit
                                                  $fixm_cases[$fixmtit] = $fixmcol_obj1->getVal("field_name")."=".$ans_item1->getId();
                                        }                                                  
                                     }
                                     else
                                     {
                                        $fixmtit = "إدارة ".$atb_obj_desc;
                                        $fixm_cases[$fixmtit] = "";
                                        
                                     }
                                     
                                     $srchtit = "البحث في ".$atb_obj_desc;
                                     
                                     if($atb_obj->isOriginal()) 
                                     {
                                             $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=$atb_obj_class", "png"=>"../images/profile.png", "titre"=>"$srchtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                     }
                                     else
                                     {
                                             $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=$atb_id", "png"=>"../images/profile.png", "titre"=>"$srchtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                     }
                                     
                                     
                                        
                                     foreach($fixm_cases as $fixmtit => $fixm) 
                                     {
                                             if($atb_obj->isOriginal()) {
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_qedit.php&cl=$atb_obj_class&ids=all&newo=3&fixmtit=$fixmtit&fixm=$fixm&sel_$fixm&limit=99", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                             }
                                             else
                                             {
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_qedit.php&tblid=$atb_id&ids=all&newo=3&fixmtit=$fixmtit&fixm=$fixm&sel_$fixm&limit=99", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                             }
                                     }
                                     $numfrontclass = ($numfrontclass + 1) % 15;
                        }
                        $numsubtheme++;
                 }
                        
      }

      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

