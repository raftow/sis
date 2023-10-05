<?php

class SchoolController extends AfwController{
        
        public function getMyModule() {
                return "sis";
        }

        public function getMyEmployeeModule() {
                return AfwSession::config("main_module", "");
        }

        public function myViewSettings($methodName)
        {
                return array("", "sis/employee_front_header.php", "lib/hzm/web/hzm_simple_footer.php");
        }


        public function defaultMethod($request)
        {
                if($request["rid"]) return "view_school";
                return "myschools";
        }

        public function loadTheCustomer($customer_id)
        {
              if(!$customer_id) return null;
              return CrmCustomer::loadById($customer_id);  
        }

        public function checkEmployeeLoggedIn()
        {
                $theEmployeeUser = AfwSession::getUserConnected();
                $theEmployee = $theEmployeeUser->getEmployee();

                if(!$theEmployee)
                {
                    // die("No employee for ".$theEmployeeUser);
                    $login_module = AfwSession::config("login_module","sis"); 
                    $this->renderLogOutMessage("Session ended !", "../$login_module/login.php");
                    return;
                }

                return $theEmployee;
        }

        public function initiateStandardDisplay($request)
        {
                $theEmployee = self::checkEmployeeLoggedIn(); 
                

                return array($theEmployee, null);
        }

        /******************************** home action ********************************************** */

        public function prepareStandard($request)
        {
                $custom_scripts = array();
                $custom_scripts[] = array('type'=>'css' , 'path'=>"./css/content.css");
                $custom_scripts[] = array('type'=>'css' , 'path'=>"../lib/css/sweetalert2.min.css");
                $custom_scripts[] = array('type'=>'js',   'path'=>"../lib/js/sweetalert2.min.js");
                $custom_scripts[] = array('type'=>'js',   'path'=>"../lib/js/sweetalert.min.js");
                $custom_scripts[] = array('type'=>'js',   'path'=>"./js/jquery.nicefileinput.js");
                $custom_scripts[] = array('type'=>'css',  'path'=>"./css/jquery-nicefileinput-js.css");
                

                return $custom_scripts;
        }

        public function prepareHome($request)
        {
                $custom_scripts = $this->prepareStandard($request);

                return $custom_scripts;
        }

        public function home($request)
        {
                $data = $request;
                // echo("sis = ".var_export($request,true)."<br><br>");
                foreach($request as $key => $value) $$key = $value;

                $data["customerObj"] = AfwSession::getCustomerConnected(true,"CrmCustomer");
                // call the view 1
                $this->render("sis", "home", $data);
        }


        /******************************** view_school action ********************************************** */

        public function prepareView_school($request)
        {
                $custom_scripts = $this->prepareStandard($request);

                return $custom_scripts;
        }

        public function view_school($request)
        {
                foreach($request as $key => $value) $$key = $value;
                $data = $request;

                $theEmployee = self::checkEmployeeLoggedIn(); 
                $theCustomer = self::loadTheCustomer($customer_id);
                

                // if(!$rid) $rid = School::myLastSchoolId();
                
                if($rid) $data["schoolObj"] = School::loadById($rid);
                else $data["schoolObj"] = null;

                /*
                if($data["schoolObj"])
                {
                        $data["relatedschoolObj"] = $data["schoolObj"]->getRelatedschool();
                }*/
                

                // call the view 1
                if($data["schoolObj"] and (!$data["schoolObj"]->isEmpty()))
                {
                        if(($theEmployee->id != $data["schoolObj"]->getVal("customer_id")) and ($theEmployee->id != $data["schoolObj"]->getVal("deligated_id")))
                           $this->renderError("school view not allowed");
                        else
                        {
                                $data["main_module_home_page"] = AfwSession::config("main_module_home_page", "");
                                $data["customer_module_banner"] = AfwSession::config("customer_module_banner", "");
                                $this->render("sis", "view_school", $data);
                        }
                           
                }
                else
                {
                        $this->renderError("school not found : [rid=$rid]");
                }
                

        }

        /******************************** myschools action ********************************************** */

        public function prepareMyschools($request)
        {
                $custom_scripts = $this->prepareStandard($request);

                return $custom_scripts;
        }

        

        public function initiateMyschools($request)
        {
                $theEmployee = self::checkEmployeeLoggedIn();
                //die("theEmployee=".var_export($theEmployee,true));
                $data = $request;
                $data = array();
                if($theEmployee)
                {
                        // die("thecustomer = ".var_export($theCustomer,true));
                        $data["main_module_home_page"] = AfwSession::config("main_module_home_page", "");
                        $data["customer_module_banner"] = AfwSession::config("customer_module_banner", "");
                        
                        $data["schoolList"] = SchoolEmployee::getSchoolList($theEmployee->id);
                        // die("data=".var_export($data,true));
                        $data["title"] = "قائمة المدارس التي أنت تعمل فيها أو تشرف عليها";
                        
                        
                }
                else
                {
                        die("here rafik");
                        $login_module = AfwSession::config("login_module","sis"); 
                        $this->renderLogOutMessage("Session ended !", "../$login_module/login.php");
                        return [];
                }

                return $data;
        }

        public function myschools($request)
        {
                foreach($request as $key => $value) $$key = $value;
                $data = $request;
                
                // call the view to show
                if($data) $this->render("sis", "my_schools", $data);
                else return ;
                
        }
    }
?>