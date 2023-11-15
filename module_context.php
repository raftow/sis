<?php
        $contextLabel = array("ar"=>"إختيار  المنشأة","fr"=>"choix de l'ecole","en"=>"school choice");
        
        $contextExplainLabel = array("ar"=>"لأجل إستخدام نظام شؤون المنشأة لا بد من إختيار المنشأة","fr"=>"choix de l'ecole","en"=>"school choice");
        
        $contextShortLabel = array("ar"=>"", "fr"=>"","en"=>"");
        
        $contextChangeLabel = array("ar"=>"الانتقال إلى وحدة دراسية أخرى", "fr"=>"basculer a une autre ecole","en"=>"switch to another school");
        
        $contextCreationMenu = array(
            name   =>   array('ar' =>  "أنت مدير وحدة دراسية  ؟  يمكنك تجربة النظام مجانا لمدة 3 شهور", fr=>"xxxxggg",'en' => "yyyyyygggg"),
            button =>   array('ar' => "إنشاء حساب مجاني لمدرستك", fr=>"xxxxggg",'en' => "yyyyyygggg"),
            href   =>   "registerSchool.php"
        ); 
        
             
        $contextList = array();
        if($objme)
        {
                require_once("school_employee.php");
             
                $em = new SchoolEmployee();
                $em->select("rea_user_id",$objme->getId());
                $em->select("active","Y");
             
                $em_list = $em->loadMany();
                
                foreach($em_list as $em_item)
                {
                      if($em_item->getId()>0)
                      {
                           $contextList[$em_item->getId()] = $em_item;
                      }
                }
        }
        
        $SUB_CONTEXT_MANDATORY = true;
        
        return $contextList;
        
        
?>