<?php
         $user_infos["id"] = 5000000+$studentObj->getId();
         $user_infos["avail"] = "Y";
         $user_infos["firstname"] = $studentObj->getVal("firstname");
         
         //effacer les var d'une eventuelle session précédente
         AfwSession::resetSession();
         AfwSession::initUserSession($user_infos);
        
	  
         $customer_default_page = $login_page_options["student_default_page"];
         if(!$customer_default_page)  $customer_default_page = "index.php";
         
         header("Location: ".$customer_default_page);
?>