<?php
      $file_dir_name = dirname(__FILE__); 
      // require_once("/orgunit.php");
      
      
      require_once("$file_dir_name/../rfw/rfw_factory.php");      
      require_once("$file_dir_name/../rfw/rfw.php");
      
      $rfwFactoryObj = new RFWFactory();

      $module_id = 16;
      $module_obj = new Module();
      $module_obj->load($module_id);


      $sh_obj = new Orgunit();
      $sh_obj->where("id in (select distinct id_main_sh from module where id_module_parent = $module_id)");
      $sh_list = $sh_obj->loadMany();
      
      $numfrontclass = 0;
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      
      $theme[$numtheme] = "";// $module_obj->getMainsh()->getDisplay();
      $subtheme[$numtheme][$numsubtheme] = "الإدارات المتاحة";//$module_obj->getDisplay();
      foreach($sh_list as $sh_id => $sh_item) 
      {
                $menu_tit = $sh_item->getDisplay();
                $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"panel_admin.php?sh=$sh_id", "png"=>"../images/profile.png", "titre"=>"$menu_tit", "id"=>"", "class"=>"front_$numfrontclass", "subtheme"=>0);
                $numfrontclass = ($numfrontclass + 1) % 15;
      }
      
      include "../pag/menu_constructor.php";
      
       
      
      


?>

