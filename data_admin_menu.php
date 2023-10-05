<?php
      $file_dir_name = dirname(__FILE__); 
      
      
      //require_once("$file_dir_name/../rfw/rfw.php");
      
      $module_id = 44;
      $module_obj = new Module();
      $module_obj->load($module_id);
      $currmod = $module_obj->getVal("module_code");
      
      $sub_modules = $module_obj->get("smd");
      // die(var_export($sub_modules,true));
      
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      $numfrontclass = 0;
      $theme[$numtheme] = "وحدات نظام ".$module_obj->getDisplay();
      foreach($sub_modules as $sub_module_id => $submodule_obj) 
      {
                $at = new Atable();
                $at->select("id_module",$module_id);
                $at->select("id_sub_module",$sub_module_id);
                $at->select("avail",'Y');
                $at_list = $at->loadMany($limit = "", $order_by = "id_sub_module asc");
                //die(var_export($at_list,true));
                if(is_array($at_list) and count($at_list)) 
                {
                        $subtheme[$numtheme][$numsubtheme] = $submodule_obj->getDisplay();                
                        foreach($at_list as $atb_id => $atb_obj)
                        {
                                     $atb_obj_class = $atb_obj->getTableClass();
                                     $atb_obj_desc =  $atb_obj->getVal("titre_short");
                                     $atb_obj_name =  $atb_obj->getVal("titre_u");  
                                     
                                     if($atb_obj->isOriginal()) {
                                             if(($atb_obj->getRowCount()<= 15) and ($atb_obj->_isLookup()))
                                             {
                                                     $fixmtit = "إدارة ".$atb_obj_desc;
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_qedit.php&cl=$atb_obj_class&currmod=$currmod&ids=all&newo=3&fixmtit=$fixmtit", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                                     $numfrontclass = ($numfrontclass + 1) % 15; 
                                                     
                                             }
                                             else
                                             {
                                                     $tit = "البحث في ".$atb_obj_desc;
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=$atb_obj_class&currmod=$currmod", "png"=>"../images/profile.png", "titre"=>"$tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                                     $numfrontclass = ($numfrontclass + 1) % 15; 
                                             }
                                     }
                                     else
                                     {
                                             $rc = $atb_obj->getRowCount();
                                             if($rc<= 15)
                                             {
                                                     if(!$rc) $rc = 0;
                                                     $tit = "البحث في ".$atb_obj_desc;
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=$atb_id", "png"=>"../images/profile.png", "titre"=>"$tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                                     $numfrontclass = ($numfrontclass + 1) % 15; 

                                                     
                                                     $fixmtit = "إدارة ".$atb_obj_desc;
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_qedit.php&tblid=$atb_id&ids=all&newo=3&fixmtit=$fixmtit&limit=15", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                                     $numfrontclass = ($numfrontclass + 1) % 15; 
                                                     
                                             }
                                             else
                                             {
                                                     $tit = "البحث في ".$atb_obj_desc;
                                                     $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=$atb_id", "png"=>"../images/profile.png", "titre"=>"$tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                                                     $numfrontclass = ($numfrontclass + 1) % 15; 
                                             }
                                     
                                     }
                                     //$atb_obj->getVal("atable_name")
                                                
                        }
                        $numsubtheme++;
                        
                 }       
      }
      $numtheme++;
      //die(var_export($menu,true));
      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

