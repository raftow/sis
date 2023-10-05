<?php

class SisObject extends AFWObject{

    /*
        // إدارة المنتج	إدارة البيانات العامة للنظام
        public static $AROLE_OF_DATA_SITE = 322;
        
        // التحقيق	التحقيق والرد على طلبات العملاء 
        public static $AROLE_OF_INVESTIGATOR = 323;

        // الإشراف على تشغيل نظام خدمة العملاء
        public static $AROLE_OF_SUPERVISOR = 324;

        // إدخال الطلبات الالكترونية التي تصل عبر الهاتف
        public static $AROLE_OF_REQUEST_ENTER = 327;

        // إدارة البيانات المرجعية للنظام
        public static $AROLE_OF_LOOKUPS = 347;


        public static function userConnectedIsSupervisor($objme=null)
        {
            if(!$objme) $objme = AfwSession::getUserConnected();
            if(!$objme) return 0;

            $employee_id = $objme->getEmployeeId();
            if(!$employee_id) return 0;

            return CrmEmployee::isAdmin($employee_id);
        }

        public static function userConnectedIsGeneralSupervisor($objme=null)
        {
            if(!$objme) $objme = AfwSession::getUserConnected();
            if(!$objme) return 0;

            $employee_id = $objme->getEmployeeId();
            if(!$employee_id) return 0;

            return CrmEmployee::isGeneralAdmin($employee_id);
        }

        public static function userConnectedIsSuperAdmin($objme=null)
        {
                if(!$objme) $objme = AfwSession::getUserConnected();
                if(!$objme) return false;
                return $objme->isSuperAdmin();
        }*/

        public function fld_CREATION_USER_ID()
        {
                return "created_by";
        }
 
        public function fld_CREATION_DATE()
        {
                return "created_at";
        }
 
        public function fld_UPDATE_USER_ID()
        {
        	return "updated_by";
        }
 
        public function fld_UPDATE_DATE()
        {
        	return "updated_at";
        }
 
        public function fld_VALIDATION_USER_ID()
        {
        	return "validated_by";
        }
 
        public function fld_VALIDATION_DATE()
        {
                return "validated_at";
        }
 
        public function fld_VERSION()
        {
        	return "version";
        }
 
        public function fld_ACTIVE()
        {
        	return  "active";
        }
 
        public function isTechField($attribute) {
            return (($attribute=="created_by") or ($attribute=="created_at") or ($attribute=="updated_by") or ($attribute=="updated_at") or ($attribute=="validated_by") or ($attribute=="validated_at") or ($attribute=="version"));  
        }
	

        public function getTimeStampFromRow($row,$context="update", $timestamp_field="")
        {
                if(!$timestamp_field) return $row["synch_timestamp"];
                else return $row[$timestamp_field];
        }

        public static function list_of_sis_eval() {
                $list_of_items = array(); 
                $max_eval_sis = AfwSession::config("max_eval_sis",30);
                $max_eval_sis_unit0 = AfwSession::config("max_eval_sis_unit0","أجزاء");
                $max_eval_sis_unit = AfwSession::config("max_eval_sis_unit","جزء");
                for($k=1;$k<=$max_eval_sis;$k++)
                {
                $max_eval_sis_unit_s = (($k>=3) and ($k<=10)) ? $max_eval_sis_unit0 : $max_eval_sis_unit;
                $list_of_items[$k] = $k. " " . $max_eval_sis_unit_s;
                }

                return  $list_of_items;
        }

        public static function executeIndicator($object, $indicator, $normal_class, $arrObjectsRelated, $sens="asc", $default_red_pct=0, $default_orange_pct=0)
        {
                global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
                $old_nb_queries_executed = $nb_queries_executed;
                $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
                $MODE_SQL_PROCESS_LOURD = true;

                if(!$normal_class) $normal_class="vert";
                $methodIndicator = "get".$indicator."Indicator";
                list($objective, $value) = $object->$methodIndicator($arrObjectsRelated);

                $objective_red_pct = $object->getVal(strtolower($indicator)."_red_pct");
                if(!$objective_red_pct) $objective_red_pct = $default_red_pct;
                if(!$objective_red_pct) $objective_red_pct = ($sens=="asc") ? 80.0 : 120.0;
                
                $objective_red = $objective_red_pct * $objective / 100.0;
                
                $orange_pct = $object->getVal("orange_pct");
                
                if(!$orange_pct) $orange_pct = $default_orange_pct;
                if(!$orange_pct) $orange_pct = ($sens=="asc") ? 90.0 : 110.0; // %
                $objective_orange_pct = round($objective_red_pct * 100.0 / $orange_pct);
                $objective_orange = $objective_orange_pct * $objective / 100.0;

                if(($sens=="asc"))
                {
                        if($value<$objective_red) $value_class = "rouge";
                        elseif($value<$objective_orange) $value_class = "orange";
                        else $value_class = $normal_class;
                }
                else
                {
                        if($value>$objective_red) $value_class = "rouge";
                        elseif($value>$objective_orange) $value_class = "orange";
                        else $value_class = $normal_class;
                }

                $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
                $nb_queries_executed = $old_nb_queries_executed;
                

                // die("$objective, $value, $value_class, $objective_red, $objective_orange");
                return [$objective, $value, $value_class, $objective_red, $objective_orange];

        }

}