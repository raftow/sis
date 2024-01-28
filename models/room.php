<?php
                
$file_dir_name = dirname(__FILE__); 
                
// rafik 22/01/2023
// ALTER TABLE `room` DROP `room_name`; 
// ALTER TABLE `room` CHANGE `room_name_en` `room_name_en` VARCHAR(48) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL; 


class Room extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "room"; 
        public static $DB_STRUCTURE = null;  
        
        public function __construct(){
		parent::__construct("room","id","sis");
                SisRoomAfwStructure::initInstance($this);
	}


        public static function loadByMainIndex($school_id,$room_name_ar,$create_obj_if_not_found=false)
        {
           if(!$school_id) throw new AfwRuntimeException("loadByMainIndex : school_id is mandatory field");
           if(!$room_name_ar) throw new AfwRuntimeException("loadByMainIndex : room_name_ar is mandatory field");


           $obj = new Room();
           $obj->select("school_id",$school_id);
           $obj->select("room_name_ar",$room_name_ar);

           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("school_id",$school_id);
                $obj->set("room_name_ar",$room_name_ar);

                $obj->insertNew();
                $obj->is_new = true;
                return $obj;
           }
           else return null;
           
        }
        
        public function getDisplay($lang="ar")
        {
                $fn = trim($this->valroom_name_ar()); // . " (".$this->valcapacity()." مقعد)";
                if(!$fn) $fn = trim($this->valtitre());
                
                return $fn;
        }
        
        public function beforeMAJ($id, $fields_updated) 
        {
               if(!$this->getVal("room_name_en"))
               {
                   $this->set("room_name_en","round ".$this->getVal("room_num"));
               }
               
               if(!$this->getVal("room_name_ar"))
               {
                   $this->set("room_name_ar","الحلقة ".$this->getVal("room_num"));
               }
               
               return true;
        }

        public function calcSchool_class_id($what="value",$currSYear=null)
        {
                $school_id = $this->getVal("school_id"); 
                $schoolObj = $this->het("school_id"); 
                if(!$currSYear) $currSYear = $schoolObj->getCurrentSchoolYear();
                if ($currSYear) 
                {
                        $year = $currSYear->getVal("year");;
                        $school_year_id = $currSYear->id; 
                        //$class_name = $this->getVal("room_name_ar");
                
                        $scObj = SchoolClass::loadByRoomId($school_year_id, $this->id);
                

                        global $lang;
                        return AfwFormatHelper::decode_result($scObj,$what,$lang); 
                }

                return null;
        }
        
}
?>