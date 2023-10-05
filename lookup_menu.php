<?php
      $file_dir_name = dirname(__FILE__); 
      
      
      require_once("$file_dir_name/../rfw/rfw.php");
      
      
      $module_id = 44;
      $module_obj = new Module();
      $module_obj->load($module_id);
      
      $sub_modules = $module_obj->get("smd");
      
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      $theme[$numtheme] = $module_obj->getMainsh()->getDisplay(). " - " . $module_obj->getDisplay();
      foreach($sub_modules as $sub_module_id => $submodule_obj) {
                
                $at = new Atable();
                $at->select("id_module",$module_id);
                $at->select("id_sub_module",$sub_module_id);
                $at->select("avail",'Y');
                $at->select("is_lookup",'Y');
                $at_list = $at->loadMany($limit = "", $order_by = "id_sub_module asc");
                $subtheme[$numtheme][$numsubtheme] = $submodule_obj->getDisplay();
                if(is_array($at_list) and count($at_list)) 
                {
                        foreach($at_list as $atb_id => $atb_obj)
                        {
                                     $atb_obj_class = $atb_obj->getTableClass();
                                     $atb_obj_desc =  $atb_obj->getVal("titre_short");
                                     $atb_obj_name =  $atb_obj->getVal("titre_u");  
                                     
                                     if($atb_obj->isOriginal()) {
                                             $fixmtit = "إدارة ".$atb_obj_desc;
                                             $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_qedit.php&cl=$atb_obj_class&ids=all&newo=3&fixmtit=$fixmtit", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"opened", "subtheme"=>0);
                                     }
                                     else
                                     {
                                             $fixmtit = "إدارة ".$atb_obj_desc;
                                             $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_qedit.php&tblid=$atb_id&ids=all&newo=3&fixmtit=$fixmtit&limit=15", "png"=>"../images/profile.png", "titre"=>"$fixmtit", "id"=>"", "class"=>"opened", "subtheme"=>0);
                                     }
                        }
                 }
                 $numsubtheme++;       
      }

      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

