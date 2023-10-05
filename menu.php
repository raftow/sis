<?php
      
      // // here was old const php
      
      $nummenu = 1;
      $numtheme = 0;
      $numsubtheme = 0;
      
      $theme[$numtheme] = "إدارة المدخلات والمخرجات";
      $subtheme_class[$numtheme][$numsubtheme] = "front";
      $subtheme_title_class[$numtheme][$numsubtheme] = "database"; 
      $subtheme[$numtheme][$numsubtheme] = "المدخلات والمخرجات"; 
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=1330", "png"=>"../images/profile.png", "titre"=>"تجربة", "id"=>"", "class"=>"opened", "subtheme"=>0);
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=1356", "png"=>"../images/profile.png", "titre"=>"الفترات", "id"=>"", "class"=>"opened", "subtheme"=>0);
      $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=rfw_mode_search.php&tblid=1355", "png"=>"../images/profile.png", "titre"=>"", "id"=>"", "class"=>"opened", "subtheme"=>0);
      
      if(($objme) and ($objme->isAdmin()))
      {
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=GfieldType", "png"=>"../images/settingicon.png", "titre"=>"أنواع المعلومات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=GfieldCat", "png"=>"../images/settingicon.png", "titre"=>"أصناف المعلومات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Aprio", "png"=>"../images/settingicon.png", "titre"=>"أولوية المعلومات", "id"=>"", "class"=>"opened", "subtheme"=>0);
      }
      $numsubtheme++;
      $numtheme++;
      
      if(($objme) and ($objme->isAdmin()))
      {
              $theme[$numtheme] = "إدارة البيانات";
              $subtheme[$numtheme][$numsubtheme] = "قواعد البيانات";
              $subtheme_class[$numtheme][$numsubtheme] = "front2";
              $subtheme_title_class[$numtheme][$numsubtheme] = "database"; 
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Dbsystem", "png"=>"../images/settingicon.png", "titre"=>"أنظمة قواعد البيانات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Dbengine", "png"=>"../images/settingicon.png", "titre"=>"محركات قواعد البيانات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Atable", "png"=>"../images/settingicon.png", "titre"=>"الجداول", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Afield", "png"=>"../images/settingicon.png", "titre"=>"حقول البيانات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=AfieldType", "png"=>"../images/settingicon.png", "titre"=>"أنواع حقول البيانات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=EntryType", "png"=>"../images/profile.png", "titre"=>"طرق الإدخال", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $numsubtheme++;
              $numtheme++;
              
              $theme[$numtheme] = "إدارة المشاريع و الأنظمة";
              $subtheme[$numtheme][$numsubtheme] = "";
              $subtheme_class[$numtheme][$numsubtheme] = "front3";
              $subtheme_title_class[$numtheme][$numsubtheme] = "database";     
              $menu[$numtheme][$numsubtheme][$nummenu++]  =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Module", "png"=>"../images/settingicon.png", "titre"=>"المشاريع و الوحدات", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $menu[$numtheme][$numsubtheme][$nummenu++]  =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=ModuleType", "png"=>"../images/settingicon.png", "titre"=>"أنواع الوحدات", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $menu[$numtheme][$numsubtheme][$nummenu++] =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=ModuleStatus", "png"=>"../images/settingicon.png", "titre"=>"حالات المشاريع", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $menu[$numtheme][$numsubtheme][$nummenu++]  =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=ModuleSh", "png"=>"../images/settingicon.png", "titre"=>"علاقة الجهات بالوحدات أو المشاريع", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $menu[$numtheme][$numsubtheme][$nummenu++] =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Bfunction", "png"=>"../images/settingicon.png", "titre"=>"الخدمات العملية", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $menu[$numtheme][$numsubtheme][$nummenu++] =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=BfunctionType", "png"=>"../images/settingicon.png", "titre"=>"أنواع الخدمات العملية", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $numsubtheme++;
        
        
              $subtheme[$numtheme][$numsubtheme] = "الصلاحيات";    
              $menu[$numtheme][$numsubtheme][$nummenu++]  =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Arole", "png"=>"../images/settingicon.png", "titre"=>"الصلاحيات", "id"=>"", "class"=>"opened", "subtheme"=>$numsubtheme);
              $numsubtheme++;
              
              //
              
              $subtheme[$numtheme][$numsubtheme] = "إدارة الموظفين";
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Auser", "png"=>"../images/profile.png", "titre"=>"الموظفون", "id"=>"", "class"=>"opened", "subtheme"=>2);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Jobrole", "png"=>"../images/profile.png", "titre"=>"الأدوار الوظيفية", "id"=>"", "class"=>"opened", "subtheme"=>2);
              $menu[$numtheme][$numsubtheme][$nummenu++]  =  array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=ModuleAuser", "png"=>"../images/settingicon.png", "titre"=>"الموظفون المعنيون بمشروع أو وحدة", "id"=>"", "class"=>"opened", "subtheme"=>1);
              $numsubtheme++;
        
              $subtheme[$numtheme][$numsubtheme] = "إدارة الجهات"; 
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=ShType", "png"=>"../images/schoolinfo.png", "titre"=>"أنواع الجهات المعنية", "id"=>"", "class"=>"opened", "subtheme"=>3);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Orgunit", "png"=>"../images/schoolinfo.png", "titre"=>"الجهات المعنية", "id"=>"", "class"=>"opened", "subtheme"=>3);
              $numsubtheme++;
        
              $subtheme[$numtheme][$numsubtheme] = "تحرير النصوص";   
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=PtextType", "png"=>"../images/schoolinfo.png", "titre"=>"أنواع النصوص ", "id"=>"", "class"=>"opened", "subtheme"=>4);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Ptext", "png"=>"../images/schoolinfo.png", "titre"=>"النصوص", "id"=>"", "class"=>"opened", "subtheme"=>4);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=PtextCat", "png"=>"../images/schoolinfo.png", "titre"=>"أصناف النصوص", "id"=>"", "class"=>"opened", "subtheme"=>4);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=PtextStatus", "png"=>"../images/schoolinfo.png", "titre"=>"حالات النصوص", "id"=>"", "class"=>"opened", "subtheme"=>4);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Comm", "png"=>"../images/profile.png", "titre"=>"المراسلات", "id"=>"", "class"=>"opened", "subtheme"=>0);
              $menu[$numtheme][$numsubtheme][$nummenu++] = array("page"=>"main.php?Main_Page=afw_mode_search.php&cl=Theme", "png"=>"../images/profile.png", "titre"=>"المواضيع", "id"=>"", "class"=>"opened", "subtheme"=>0);
      }
      include "$file_dir_name/../pag/menu_constructor.php";
?>

