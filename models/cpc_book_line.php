<?php
// ------------------------------------------------------------------------------------
// copy paste
// create table cpc_book_page_info
// SELECT book_id, `page_num`, sum(len) as nb_lines FROM `cpc_book_paragraph` WHERE book_id=1 group by book_id, page_num;
// alter table cpc_book_page_info add nbr_lines smallint;
// update `cpc_book_page_info` pi set pi.`nbr_lines` = 15 - ((SELECT count(*) as nb FROM `cpc_book` WHERE parent_book_id = 1 and `name_page_num` = pi.page_num)+(SELECT count(*) as nb FROM `cpc_book` WHERE parent_book_id = 1 and `besmella_page_num` = pi.page_num)) WHERE 1;
// alter table cpc_book_page_info change nbr_lines nbr_lines float;
// alter table cpc_book_page_info add corr_coef float;
// update `cpc_book_page_info` set nbr_lines = 5.5 where book_id=1 and page_num=1;
// update `cpc_book_page_info` set nbr_lines = 6 where book_id=1 and page_num=2;
// update `cpc_book_page_info` set corr_coef = nbr_lines / nb_lines;
// -- SELECT * FROM `cpc_book_page_info` WHERE abs(100 - 100*`corr_coef`) > 20;
// update `cpc_book_paragraph` pg set pg.`len_corr`= len*(select corr_coef from cpc_book_page_info pi where pi.book_id = pg.book_id and pi.page_num = pg.page_num);
//                 
// ------------------------------------------------------------------------------------


class CpcBookLine extends SisObject
{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("cpc_book_line",null,"sis");
                SisCpcBookLineAfwStructure::initInstance($this);
                
	}

        public function getFormuleResult($attribute, $what='value')
        {
                //die("$this getFormuleResult($attribute, $what)");
                $return = AfwFormulaHelper::calculateFormulaResult($this,$attribute, $what);
                //die("$return = $this => calcFormuleResult($attribute, $what)");
                return $return;
        }
        /*
        public function formatAya($aya, $num, $pagestart, $aya_len="")
        {
                $return = "";
                if($pagestart)
                {
                        $pagestart = "pagestart";
                        $part = $this->getVal("part_id")-1;
                        $partObj = $this->het("part_id");
                        $parttitle = $partObj->getVal("book_name");                        
                        if($num!=1)
                        {
                                $souraObj = $this->het("chapter_id");
                                $souratitle = $souraObj->getVal("book_name");
                                $parttitle = $souratitle." - ".$parttitle;
                        }
                        $return .= "<div class='parttitle aya$num'>$parttitle</div>";
                }
                else
                {
                        $pagestart = "";
                }
                if($num==1) 
                {                        
                        $souraObj = $this->het("chapter_id");
                        $souratitle = $souraObj->getVal("book_name");
                        $souralen = $souraObj->getVal("len");
                        $return .= "<div class='souratitle $pagestart'>$souratitle <span class='technical soura len'>$souralen</span></div>".str_replace("بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ","<div class='basmala'>بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>",$aya);
                }
                else $return .= $aya;
                if($aya_len) $return .= "<span class='technical aya len aya$num'>$aya_len</span>";
                return $return;
        }*/
        
        public static function loadByMainIndex($book_id, $line_num, $create_obj_if_not_found=false)
        {
           
                if(!$line_num) return null;
                if(!$book_id) return null;
                $obj = new CpcBookLine();
                $obj->select("book_id",$book_id);
                $obj->select("line_num",$line_num);   
                if($obj->load())
                {
                        if($create_obj_if_not_found) $obj->activate();
                        return $obj;
                }
                elseif($create_obj_if_not_found)
                {
                        $obj->set("book_id",$book_id);
                        $obj->set("line_num",$line_num);

                        $obj->insertNew();
                        return $obj;
                }
                else return null;
           
        }


        public static function loadFirstLine($book_id, $part_id, $chapter_id, $page_num)
        {
                $obj = new CpcBookLine();
                if($book_id) $obj->select("book_id",$book_id);
                if($part_id) $obj->select("part_id",$part_id);
                if($chapter_id) $obj->select("chapter_id",$chapter_id);
                $obj->select("page_num",$page_num);   
                if($obj->load('','',"line_num asc"))
                {
                     return $obj;
                }

                return null;
        }


        public static function loadLastLine($book_id, $part_id, $chapter_id, $page_num)
        {
                $obj = new CpcBookLine();
                if($book_id) $obj->select("book_id",$book_id);
                if($part_id) $obj->select("part_id",$part_id);
                if($chapter_id) $obj->select("chapter_id",$chapter_id);
                if($page_num) $obj->select("page_num",$page_num);   
                if($obj->load('','',"line_num desc"))
                {
                     return $obj;
                }

                return null;
        }

        public static function loadLineByNum($book_id, $line_num)
        {
                return self::loadByMainIndex($book_id, $line_num);
        }
        

        public static function calcRemainLinesInChapter($chapter_id, $line_num)
        {
                // @todo
        }

        public function moveLines($offset)
        {
                // @todo
        }



        public static function moveInLines($book_id, $from_line_num, $delta_lines)
        {
                if(!$book_id) throw new AfwRuntimeException("moveInLines require the param book_id");
                if(!$from_line_num) throw new AfwRuntimeException("moveInLines require the param line_num");
                
                CpcBookLine::loadLineByNum($book_id, $from_line_num)
                $from_part_id = 0; 
                $from_chapter_id = 0; 
                $from_page_num = 0; 
                // $from_line_num,
                $to_part_id = 0; 
                $to_chapter_id = 0;  
                $to_page_num = 0; 
                $to_line_num = $from_line_num + $delta_lines;
                $log_arr = [];

                return [$book_id, $from_part_id, $from_chapter_id, $from_page_num, $from_line_num,
                                  $to_part_id,   $to_chapter_id,   $to_page_num,   $to_line_num, 
                                  $log_arr];
        }
        
        public function getDisplay($lang = 'ar')
        {
               $data = array();
               $link = array();
               

               list($data[0],$link[0]) = $this->displayAttribute("chapter_id",false, $lang);
               $data[1] = "السطر ".$this->getVal("line_num"); 
               
               return implode(" - ",$data);
        }


        public function stepsAreOrdered()
        {
            return false;
        }        

        public static function resetToFirstLine($book_id, $work_sens)
        {
                $log1_arr = [];
                if($work_sens==1) 
                {
                        if($book_id==10001) $book_id = 1;
                        $new_part_id_from = 2;
                        $new_chapter_id_from = 1002;
                        $new_page_num_from = 2;
                        $new_line_num_from = 1;
                        $log1_arr[] = "resetToFirstLine => forward from start on book_id=$book_id";
                }
                elseif($work_sens==-1)
                {
                        if($book_id==1) $book_id = 10001;
                        $new_part_id_from = 10002;
                        $new_chapter_id_from = 11001;
                        $new_page_num_from = 1;
                        $new_line_num_from = 1;
                        $log1_arr[] = "resetToFirstLine => backward from end on book_id=$book_id";
                }


                return [$book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_line_num_from, $log1_arr];
        }

        /* to review if needed
        public static function getMaxLineNumOf($chapter_id, $book_id=1)
        {
                if($book_id==1)
                {
                        if($chapter_id==1001) return    7;
                        if($chapter_id==1002) return  286;
                        if($chapter_id==1003) return  200;
                        if($chapter_id==1004) return  176;
                        if($chapter_id==1005) return  120;
                        if($chapter_id==1006) return  165;
                        if($chapter_id==1007) return  206;
                        if($chapter_id==1008) return   75;
                        if($chapter_id==1009) return  129;
                        if($chapter_id==1010) return  109;
                        if($chapter_id==1011) return  123;
                        if($chapter_id==1012) return  111;
                        if($chapter_id==1013) return   43;
                        if($chapter_id==1014) return   52;
                        if($chapter_id==1015) return   99;
                        if($chapter_id==1016) return  128;
                        if($chapter_id==1017) return  111;
                        if($chapter_id==1018) return  110;
                        if($chapter_id==1019) return   98;
                        if($chapter_id==1020) return  135;
                        if($chapter_id==1021) return  112;
                        if($chapter_id==1022) return   78;
                        if($chapter_id==1023) return  118;
                        if($chapter_id==1024) return   64;
                        if($chapter_id==1025) return   77;
                        if($chapter_id==1026) return  227;
                        if($chapter_id==1027) return   93;
                        if($chapter_id==1028) return   88;
                        if($chapter_id==1029) return   69;
                        if($chapter_id==1030) return   60;
                        if($chapter_id==1031) return   34;
                        if($chapter_id==1032) return   30;
                        if($chapter_id==1033) return   73;
                        if($chapter_id==1034) return   54;
                        if($chapter_id==1035) return   45;
                        if($chapter_id==1036) return   83;
                        if($chapter_id==1037) return  182;
                        if($chapter_id==1038) return   88;
                        if($chapter_id==1039) return   75;
                        if($chapter_id==1040) return   85;
                        if($chapter_id==1041) return   54;
                        if($chapter_id==1042) return   53;
                        if($chapter_id==1043) return   89;
                        if($chapter_id==1044) return   59;
                        if($chapter_id==1045) return   37;
                        if($chapter_id==1046) return   35;
                        if($chapter_id==1047) return   38;
                        if($chapter_id==1048) return   29;
                        if($chapter_id==1049) return   18;
                        if($chapter_id==1050) return   45;
                        if($chapter_id==1051) return   60;
                        if($chapter_id==1052) return   49;
                        if($chapter_id==1053) return   62;
                        if($chapter_id==1054) return   55;
                        if($chapter_id==1055) return   78;
                        if($chapter_id==1056) return   96;
                        if($chapter_id==1057) return   29;
                        if($chapter_id==1058) return   22;
                        if($chapter_id==1059) return   24;
                        if($chapter_id==1060) return   13;
                        if($chapter_id==1061) return   14;
                        if($chapter_id==1062) return   11;
                        if($chapter_id==1063) return   11;
                        if($chapter_id==1064) return   18;
                        if($chapter_id==1065) return   12;
                        if($chapter_id==1066) return   12;
                        if($chapter_id==1067) return   30;
                        if($chapter_id==1068) return   52;
                        if($chapter_id==1069) return   52;
                        if($chapter_id==1070) return   44;
                        if($chapter_id==1071) return   28;
                        if($chapter_id==1072) return   28;
                        if($chapter_id==1073) return   20;
                        if($chapter_id==1074) return   56;
                        if($chapter_id==1075) return   40;
                        if($chapter_id==1076) return   31;
                        if($chapter_id==1077) return   50;
                        if($chapter_id==1078) return   40;
                        if($chapter_id==1079) return   46;
                        if($chapter_id==1080) return   42;
                        if($chapter_id==1081) return   29;
                        if($chapter_id==1082) return   19;
                        if($chapter_id==1083) return   36;
                        if($chapter_id==1084) return   25;
                        if($chapter_id==1085) return   22;
                        if($chapter_id==1086) return   17;
                        if($chapter_id==1087) return   19;
                        if($chapter_id==1088) return   26;
                        if($chapter_id==1089) return   30;
                        if($chapter_id==1090) return   20;
                        if($chapter_id==1091) return   15;
                        if($chapter_id==1092) return   21;
                        if($chapter_id==1093) return   11;
                        if($chapter_id==1094) return    8;
                        if($chapter_id==1095) return    8;
                        if($chapter_id==1096) return   19;
                        if($chapter_id==1097) return    5;
                        if($chapter_id==1098) return    8;
                        if($chapter_id==1099) return    8;
                        if($chapter_id==1100) return   11;
                        if($chapter_id==1101) return   11;
                        if($chapter_id==1102) return    8;
                        if($chapter_id==1103) return    3;
                        if($chapter_id==1104) return    9;
                        if($chapter_id==1105) return    5;
                        if($chapter_id==1106) return    4;
                        if($chapter_id==1107) return    7;
                        if($chapter_id==1108) return    3;
                        if($chapter_id==1109) return    6;
                        if($chapter_id==1110) return    3;
                        if($chapter_id==1111) return    5;
                        if($chapter_id==1112) return    4;
                        if($chapter_id==1113) return    5;
                        if($chapter_id==1114) return    6;

                        return -1;
                }

                return AfwDatabase::db_recup_value("select max(line_num) as pnum_max  from cpc_book_line where book_id = $book_id and chapter_id = $chapter_id");


        }*/

        
        
             
}
?>
