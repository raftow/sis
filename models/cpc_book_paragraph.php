<?php
// ------------------------------------------------------------------------------------
// ALTER TABLE `cpc_book_paragraph` ADD `len` FLOAT NULL AFTER `lines_count`;
// create table coran_aya_len as SELECT `chapter_id`,`paragraph_num`, `paragraph_text_uf`,
//       (length(REPLACE(paragraph_text_uf,'بسم الله الرحمن الرحيم',''))+15)/92 as len 
//   FROM `cpc_book_paragraph` WHERE `paragraph_num` = 1 union SELECT `chapter_id`,`paragraph_num`, `paragraph_text_uf`, (length(paragraph_text_uf)+15)/92 as len FROM `cpc_book_paragraph` WHERE `paragraph_num` != 1;
// update cpc_book_paragraph bp set len = (select len from coran_aya_len cal where cal.chapter_id=bp.chapter_id  and cal.paragraph_num=bp.paragraph_num)
// ------------------------------------------------------------------------------------

                

                
// old include of afw.php

class CpcBookParagraph extends SisObject
{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= ""; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("cpc_book_paragraph",null,"sis");
                SisCpcBookParagraphAfwStructure::initInstance($this);
                
	}

        public function getFormuleResult($attribute, $what='value')
        {
                //die("$this getFormuleResult($attribute, $what)");
                $return = $this->calcFormuleResult($attribute, $what);
                //die("$return = $this => calcFormuleResult($attribute, $what)");
                return $return;
        }

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
        }

        public static function repareBookTriplet($book_id, $part_id, $chapter_id)
        {
                if($book_id==1)
                {
                        if($part_id>31) $part_id = 10033 - $part_id;
                        if($chapter_id>1114) $chapter_id = 12115 - $chapter_id;
                }

                return [$book_id, $part_id, $chapter_id];
        }
        
        public static function loadByMainIndex($book_id, $part_id, $chapter_id, $paragraph_num, $create_obj_if_not_found=false)
        {
           $obj = new CpcBookParagraph();
           if($book_id) $obj->select("book_id",$book_id);
           if($part_id) $obj->select("part_id",$part_id);
           if($chapter_id) $obj->select("chapter_id",$chapter_id);
           $obj->select("paragraph_num",$paragraph_num);

           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("book_id",$book_id);
                $obj->set("part_id",$part_id);
                $obj->set("chapter_id",$chapter_id);
                $obj->set("paragraph_num",$paragraph_num);

                $obj->insertNew();
                return $obj;
           }
           else return null;
           
        }


        public static function loadFirstParagraph($book_id, $part_id, $chapter_id, $page_num)
        {
                $obj = new CpcBookParagraph();
                if($book_id) $obj->select("book_id",$book_id);
                if($part_id) $obj->select("part_id",$part_id);
                if($chapter_id) $obj->select("chapter_id",$chapter_id);
                $obj->select("page_num",$page_num);   
                if($obj->load('','',"paragraph_num asc"))
                {
                     return $obj;
                }

                return null;
        }


        public static function loadLastParagraph($book_id, $part_id, $chapter_id, $page_num)
        {
                $obj = new CpcBookParagraph();
                if($book_id) $obj->select("book_id",$book_id);
                if($part_id) $obj->select("part_id",$part_id);
                if($chapter_id) $obj->select("chapter_id",$chapter_id);
                if($page_num) $obj->select("page_num",$page_num);   
                if($obj->load('','',"paragraph_num desc"))
                {
                     return $obj;
                }

                return null;
        }

        public static function loadParagraphByNum($book_id, $part_id, $chapter_id, $paragraph_num)
        {
                if(!$paragraph_num) return null;
                if(!$chapter_id) return null;
                if(($book_id+$part_id)==0) return null;
                $obj = new CpcBookParagraph();
                if($book_id) $obj->select("book_id",$book_id);
                if($part_id) $obj->select("part_id",$part_id);
                if($chapter_id) $obj->select("chapter_id",$chapter_id);
                if($paragraph_num) $obj->select("paragraph_num",$paragraph_num);   
                if($obj->load())
                {
                     return $obj;
                }

                return null;
        }
        

        public static function getParagraphLinesArrayFor($book_id, $part_id_from, $part_id_to, $chapter_id_from=0, $chapter_id_to=0, $page_from=0, $page_to=0)
        {
                $obj = new CpcBookParagraph();
                if($book_id) $obj->select("book_id",$book_id);
                
                if($part_id_from and $part_id_to) $obj->where("part_id between $part_id_from and $part_id_to");
                if($chapter_id_from and $chapter_id_to) $obj->where("chapter_id between $chapter_id_from and $chapter_id_to");
                if($page_from and $page_to) $obj->where("page_num between $page_from and $page_to");

                $prgLinesArray = [];
                $souratLength = [];
                $ayatPageNumArray = [];
                $pageParagraphNumArray = [];
                $sql = $obj->getSQLMany();
                // die("getParagraphLinesArrayFor :: getSQLMany = $sql");
                $prgList = $obj->loadMany();
                if(count($prgList)==0)
                {
                        throw new RuntimeException("No ParagraphLines for : $sql");
                }

                
                foreach($prgList as $prgItem)
                {
                        /*
                        if($prgItem->getVal("page_num")==46)
                        {
                                die(" pageParagraphNumArray[44] = ".var_export($pageParagraphNumArray[44],true)." pageParagraphNumArray[45] = ".var_export($pageParagraphNumArray[45],true));
                        }
                        */
                        $prgLinesArray[$prgItem->getVal("chapter_id")][$prgItem->getVal("paragraph_num")] = $prgItem->getVal("len"); 
                        $ayatPageNumArray[$prgItem->getVal("chapter_id")][$prgItem->getVal("paragraph_num")] = $prgItem->getVal("page_num"); 
                        if(!$pageParagraphNumArray[$prgItem->getVal("page_num")])
                        {
                                $pageParagraphNumArray[$prgItem->getVal("page_num")] = [];
                                $pageParagraphNumArray[$prgItem->getVal("page_num")]["count"] = 0;
                        }
                        if(true)
                        {
                                $pageParagraphNumArray[$prgItem->getVal("page_num")]["count"]++;
                                if(!$pageParagraphNumArray[$prgItem->getVal("page_num")]["first"]) 
                                {
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["first"] = $prgItem->getVal("paragraph_num");
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["first_chapter_id"] = $prgItem->getVal("chapter_id");
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["first_part_id"] = $prgItem->getVal("part_id");
                                }

                                if($prgItem->getVal("part_id") != $pageParagraphNumArray[$prgItem->getVal("page_num")]["last_part_id"])
                                {
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["end_part"] = $pageParagraphNumArray[$prgItem->getVal("page_num")]["last"];
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["end_part_chapter_id"] = $pageParagraphNumArray[$prgItem->getVal("page_num")]["last_chapter_id"];
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["end_part_id"] = $pageParagraphNumArray[$prgItem->getVal("page_num")]["last_part_id"];
                                }
                                
                                if(true) 
                                {
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["last"] = $prgItem->getVal("paragraph_num");
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["last_chapter_id"] = $prgItem->getVal("chapter_id");
                                        $pageParagraphNumArray[$prgItem->getVal("page_num")]["last_part_id"] = $prgItem->getVal("part_id");
                                }

                        }
                        $souratLength[$prgItem->getVal("chapter_id")] = $prgItem->getVal("paragraph_num");
                }

                // may be error in page nums provided so no need to throw error
                // just return empty resuts
                // or see what @todo other than raise exception when it is data not program
                if($chapter_id_from and (!$prgLinesArray[$chapter_id_from]))
                {
                       // throw new RuntimeException("No Paragraph lines for chapter $chapter_id_from from sql $sql ".var_export(['lines'=>$prgLinesArray, 'length'=>$souratLength, 'pageNumArray'=>$ayatPageNumArray, 'pageParagraphNumArray'=>$pageParagraphNumArray],true));
                }

                return [$prgLinesArray, $souratLength, $ayatPageNumArray, $pageParagraphNumArray];
        }

        public static function moveOneParagraphToSens($prg_cursor_num, $chapter_id_cursor, $sens, $chapter_sens, $souratLength)
        {
                if(abs($sens)!=1) throw new RuntimeException("moveOneParagraphToSens need sens to be +1 or -1 current value = $sens");
                if(abs($chapter_sens)!=1) throw new RuntimeException("moveOneParagraphToSens need chapter_sens to be +1 or -1 current value = $chapter_sens");

                $chapter_sens = 1; // because with inversed moshaf no need to $chapter_sens = -1;

                $prg_cursor_num += $sens;
                if($prg_cursor_num<1)
                {
                        $chapter_id_cursor -= $chapter_sens;
                        $prg_cursor_num = $souratLength[$chapter_id_cursor];
                }
                elseif($prg_cursor_num>$souratLength[$chapter_id_cursor])
                {
                        $chapter_id_cursor += $chapter_sens;
                        $prg_cursor_num = 1;
                }

                return [$prg_cursor_num, $chapter_id_cursor];
        }

        public static function calcRemainLinesInChapter($chapter_id, $paragraph_num, $ayatLinesArray, $souratLength)
        {
                $last_paragraph_num = $souratLength[$chapter_id];
                $total_len = 0;
                for($prg_cursor_num=$paragraph_num+1;$prg_cursor_num<=$last_paragraph_num;$prg_cursor_num++)
                {
                        $total_len += $ayatLinesArray[$chapter_id][$prg_cursor_num];
                }
                
                return $total_len;
        }

        public function moveParagraphs($offset)
        {
                $book_id = $this->getVal("book_id");
                $part_id = $this->getVal("part_id");
                $chapter_id = $this->getVal("chapter_id");
                $page_num = $this->getVal("page_num");
                $paragraph_num = $this->getVal("paragraph_num");

                list($book_id, $part_id, $chapter_id_cursor, $page_num_final, $prg_cursor_num, $log_arr, $prgCursor)
                   = self::moveInParagraphs($book_id, $part_id, $chapter_id, $page_num, $paragraph_num, 1, $offset, 0, 0);

                return $prgCursor;
        }

        // اذا قال الطالب احفظ في اليوم 4 أسطر
        // واحتجنا لاكمال السورة أن نكلفه بحفظ 5 بدلا من 4 لا اشكال
        // : ال1 زيادة هذا هو ما ترجعه الدالة 
        // canAddSolfa

        public static function canAddSolfa($delta_lines_abs)
        {
                // if what remain to end of sourat is too less we can add it if it is few
                $delta_lines_few_to_add = $delta_lines_abs/4;
                $delta_lines_very_few_to_add = $delta_lines_abs/8;
                if($delta_lines_very_few_to_add>7) $delta_lines_very_few_to_add = 7; // 1/2 wajh (page) is max of very few
                $delta_lines_can_add = ($delta_lines_few_to_add<=3) ? $delta_lines_few_to_add : $delta_lines_very_few_to_add;
                return $delta_lines_can_add;
        }

        public static function moveInParagraphs($book_id, $part_id, $chapter_id, $page_num, $paragraph_num, 
                                                $chapter_sens, $delta_paragraph, $delta_lines, $delta_pages, 
                                                $lines_to_paragraph_method="nearest", $new_page_where="end", 
                                                $new_chapter_method="chapter-nearest",
                                                $estimated_delta_pages=0, $log=true)
        {
                if(!$chapter_id) throw new RuntimeException("moveInParagraphs require the param chapter_id");
                if(!$page_num) throw new RuntimeException("moveInParagraphs require the param page_num");
                if(!$paragraph_num) throw new RuntimeException("moveInParagraphs require the param paragraph_num");
                if(!$lines_to_paragraph_method) throw new RuntimeException("moveInParagraphs require the param lines_to_paragraph_method");
                if(!$new_page_where) throw new RuntimeException("moveInParagraphs require the param new_page_where");
                if(!$new_chapter_method) throw new RuntimeException("moveInParagraphs require the param new_chapter_method");

                $log_arr = [];
                $old_part_num = $part_id -1;
                if($old_part_num>10000) $old_part_num = $old_part_num - 10000;
                if($log) $log_arr[] = "<b>old position : part$old_part_num page$page_num chapter$chapter_id paragraph$paragraph_num</b>";
                $prg_cursor_num = $paragraph_num;
                $chapter_id_cursor = $chapter_id;
                $page_num_cursor = $page_num;
                

                $delta_lines_abs = abs($delta_lines);
                $delta_lines_sens = ($delta_lines>0) ? 1 : -1;

                // start from : $chapter_id-1, $chapter_id+1 ?
                $chapter_id_start = $chapter_id; // just to check that gives paragraphs from this chapter 
                                                 // otherwise the between SQL condition is on page nums below
                $chapter_id_end = 0; // $chapter_id+1;

                $part_id_from = 0;
                $part_id_to = 0;

                $nbpages_allowed_to_reach_part_end = 5;

                $page_from = $estimated_delta_pages < 0 ? $page_num + $estimated_delta_pages : $page_num;
                $page_to = $estimated_delta_pages > 0 ? $page_num + $estimated_delta_pages : $page_num;
                if(($new_page_where=="end-part") or ($new_page_where=="reach-end-part"))
                {
                        $page_to += $nbpages_allowed_to_reach_part_end;
                        // throw new RuntimeException("new_page_where=$new_page_where page_to=$page_to page_num=$page_num estimated_delta_pages=$estimated_delta_pages");
                }
                // else throw new RuntimeException("new_page_where=$new_page_where");
                // load ayat lines length for this soura
                list($ayatLinesArray, $souratLength, $ayatPageNumArray, $pageParagraphNumArray) = 
                   self::getParagraphLinesArrayFor($book_id, $part_id_from, $part_id_to, $chapter_id_start, $chapter_id_end, $page_from, $page_to);

                $loop_sequence = 0;
                //if($page_to==46) throw new RuntimeException("page_to=$page_to pageParagraphNumArray=".var_export($pageParagraphNumArray,true));

                if($log) $log_arr[] = "<h2>getParagraphLinesArrayFor</h2>:\n<br><span class='technical'>getParagraphLinesArrayFor($book_id, $part_id_from, $part_id_to, $chapter_id_start, $chapter_id_end, $page_from, $page_to) result::ayatLinesArray is ".var_export($ayatLinesArray,true)."</span>";                   

                $delta_paragraph_abs = abs($delta_paragraph);
                $delta_paragraph_sens = ($delta_paragraph>0) ? 1 : -1;
                $delta_paragraph_sign = ($delta_paragraph>0) ? "+1" : "-1";

                if($delta_paragraph_abs)
                {
                        if($log) $log_arr[] = "delta_paragraph : $delta_paragraph_abs, $delta_paragraph_sign";
                        $delta_paragraph_remain = $delta_paragraph_abs;                        
                        while(($delta_paragraph_remain>0) and ($loop_sequence<1000))
                        {
                                $loop_sequence++;
                                list($prg_cursor_num, $chapter_id_cursor) = self::moveOneParagraphToSens($prg_cursor_num, $chapter_id_cursor, $delta_paragraph_sens, $chapter_sens, $souratLength);
                                $delta_paragraph_remain--;
                                if($log) $log_arr[] = "paragraph-delta taken and one move to sens $delta_paragraph_sign => c$chapter_id_cursor, p$prg_cursor_num remain $delta_paragraph_remain";
                        }
                }

                $page_num_cursor = $ayatPageNumArray[$chapter_id_cursor][$prg_cursor_num];

                

                if($delta_lines_abs)
                {
                        // tranform $delta_lines to delta paragraph
                        $delta_lines_remain = $delta_lines_abs;

                        if($log) $log_arr[] = "delta_lines : $delta_lines_abs , $delta_lines_sens";
                        
                        while(($delta_lines_remain>0) and ($loop_sequence<1000))
                        {
                                if($log) $log_arr[] = "loop sequence=$loop_sequence remain : $delta_lines_remain";
                                $loop_sequence++;
                                $prg_len = $ayatLinesArray[$chapter_id_cursor][$prg_cursor_num];
                                if($log) $log_arr[] = "ch$chapter_id_cursor p$prg_cursor_num : aya length is $prg_len";


                                $simulated_delta_lines_remain = $delta_lines_remain;
                                $simulated_delta_lines_remain -= $prg_len;

                                if($log) $log_arr[] = "<b><i>ch$chapter_id_cursor p$prg_cursor_num : delta_lines - $prg_len = $simulated_delta_lines_remain</i></b>";

                                if($simulated_delta_lines_remain<0)
                                {
                                        if($lines_to_paragraph_method=="add") 
                                        {
                                                // move_accepted
                                                $delta_lines_remain = 0;
                                                if($log) $log_arr[] = "lines_to_paragraph_method=add : take paragraph even if more length";
                                        }

                                        if($lines_to_paragraph_method=="nearest")
                                        {
                                                $delta_lines_remain_abs = abs($delta_lines_remain);
                                                $delta_lines_remain_pct = round($delta_lines_remain_abs*100/$prg_len);
                                                if($delta_lines_remain_pct<50) 
                                                {
                                                        // move_accepted
                                                        $delta_lines_remain = 0;                                                
                                                        if($log) $log_arr[] = "lines_to_paragraph_method=nearest and few added $delta_lines_remain_pct % ($delta_lines_remain_abs/$prg_len) : take paragraph even if more length";
                                                }
                                                else
                                                {
                                                        $delta_lines_remain = $simulated_delta_lines_remain;
                                                        if($log) $log_arr[] = "lines_to_paragraph_method=nearest and toomuch added $delta_lines_remain_pct % ($delta_lines_remain_abs/$prg_len)";
                                                }
                                        }
                                }
                                else
                                {
                                        // move_accepted
                                        $delta_lines_remain = $simulated_delta_lines_remain;
                                }

                                $move_accepted = ($delta_lines_remain>=0);

                                if($move_accepted)
                                {
                                        $delta_lines_can_add = self::canAddSolfa($delta_lines_abs);
                                        
                                        if(true) // not clear : $delta_lines_remain<=$delta_lines_can_add
                                        {
                                                if($log) $log_arr[] = "###*** add needed as remain $delta_lines_remain ***#### i can add (solfa) = $delta_lines_can_add";
                                                $remain_to_end_of_chapter = self::calcRemainLinesInChapter($chapter_id_cursor, $prg_cursor_num, $ayatLinesArray, $souratLength);        
                                                $delta_lines_can_be_the_remain = $delta_lines_remain+$delta_lines_can_add;
                                                if($log) $log_arr[] = "end of remain almost reached calc remain_to_end_of_chapter = $remain_to_end_of_chapter vs delta_lines_can_be_the_remain = $delta_lines_remain+$delta_lines_can_add = $delta_lines_can_be_the_remain";
                                                
                                                if($remain_to_end_of_chapter <= $delta_lines_can_be_the_remain)
                                                {
                                                        $delta_lines_remain = $remain_to_end_of_chapter;  
                                                        if($log) $log_arr[] = "end of chapter almost reached add few lines to reach it : delta_lines_remain = $remain_to_end_of_chapter";
                                                        
                                                }
                                                else
                                                {
                                                        if($log) $log_arr[] = "sorry solfa canceled because delta_lines+solfa=$delta_lines_can_be_the_remain still not enough to reach end of chapter because remain $remain_to_end_of_chapter delta_lines_remain still = $delta_lines_remain";
                                                }
                                        }

                                        if(true)
                                        {
                                                $old_prg_cursor_num = $prg_cursor_num; 
                                                
                                                list($new_prg_cursor_num, $new_chapter_id_cursor) = self::moveOneParagraphToSens($prg_cursor_num, $chapter_id_cursor, $delta_lines_sens, $chapter_sens, $souratLength);
                                                if($log) $log_arr[] = "list(prg=$new_prg_cursor_num, chp=$new_chapter_id_cursor) = self::moveOneParagraphToSens(prg=$prg_cursor_num, chp=$chapter_id_cursor, delta_lines_sens=$delta_lines_sens, chapter-sens=$chapter_sens, souratLength=$souratLength)";
                                                // soura has changed apply method
                                                if($new_chapter_id_cursor != $chapter_id_cursor)
                                                {
                                                        $chapter_changed_accepted = false;
                                                        if(CpcBook::isValidChapterId($book_id, $new_chapter_id_cursor))
                                                        {
                                                                if($new_chapter_method=="goon") 
                                                                {
                                                                        $chapter_changed_accepted = true;
                                                                        if($log) $log_arr[] = "new_chapter_method=goon : go on to new chapter";
                                                                }

                                                                if($new_chapter_method=="chapter-nearest") 
                                                                {
                                                                        $delta_lines_remain_abs = abs($delta_lines_remain);
                                                                        $delta_lines_remain_pct = round(($delta_lines_remain_abs+$prg_len)*100/$delta_lines);
                                                                        if($delta_lines_remain_pct>70) 
                                                                        {
                                                                                $chapter_changed_accepted = true;
                                                                                if($log) $log_arr[] = "new_chapter_method=chapter-nearest and remain toomuch lines $delta_lines_remain_pct % ($delta_lines_remain_abs+$prg_len/$delta_lines) : go on to new chapter";
                                                                        }
                                                                        else
                                                                        {
                                                                                if($log) $log_arr[] = "new_chapter_method=chapter-nearest and remain few lines $delta_lines_remain_pct % ($delta_lines_remain_abs+$prg_len/$delta_lines) : ignore the move to new chapter";                                                                
                                                                        }
                                                                }

                                                                
                                                        }
                                                        else
                                                        {
                                                                if($log) $log_arr[] = "<span class='error'>CpcBook told that (bk=$book_id, chp=$new_chapter_id_cursor) is not Valid Chapter Id</span>";
                                                        }

                                                        if($chapter_changed_accepted)
                                                        {
                                                                $prg_cursor_num = $new_prg_cursor_num;
                                                                $chapter_id_cursor = $new_chapter_id_cursor;
                                                        }
                                                        else
                                                        {
                                                                if($log) $log_arr[] = "new_chapter_method=$new_chapter_method has not accpted move to new chapter !!!";
                                                        }
                                                }
                                                else
                                                {                                                
                                                        $prg_cursor_num = $new_prg_cursor_num;
                                                }

                                                if($old_prg_cursor_num != $prg_cursor_num)
                                                {
                                                        if($log) $log_arr[] = "paragraph taken ($old_prg_cursor_num != $prg_cursor_num) and one move to sens $delta_lines_sens done so => c$chapter_id_cursor, p$prg_cursor_num";
                                                }
                                        }
                                        else
                                        {
                                                if($log) $log_arr[] = "stop move end of remain lines";
                                        }
                                }
                                
                        }
                }

                

                if(abs($delta_pages)>0)
                {
                        if($log) $log_arr[] = "delta_pages : $delta_pages start from pg$page_num_cursor";
                        //$last_prg_num_in_page = $pageParagraphNumArray[$page_num_cursor]["last"];
                        //$prg_cursor_delta_to_end_of_page = $last_prg_num_in_page - $prg_cursor_num;
                        if($delta_pages>0)
                        {
                                $page_num_cursor += $delta_pages - 1; 
                                // -1 because we count the poles not the intervals
                                // for example if $delta_pages=1 we keep in same page we go to end of page only
                        }
                        else
                        {
                                $page_num_cursor += $delta_pages; 
                        }
                        
                        if($log) $log_arr[] = "goto pg$page_num_cursor and apply $new_page_where new-page-where-method";

                        if(($new_page_where=="end-part") or ($new_page_where=="reach-end-part"))
                        {
                                $new_page_where_old = $new_page_where;
                                $prg_cursor_num = null;
                                $chapter_id_cursor = null;
                                for($end_part_tentative=0; $end_part_tentative<= $nbpages_allowed_to_reach_part_end; $end_part_tentative++)
                                {
                                        if((!$prg_cursor_num) or (!$chapter_id_cursor))
                                        {
                                                $page_num_tentative = $page_num_cursor+$end_part_tentative;
                                                $prg_cursor_num = $pageParagraphNumArray[$page_num_tentative]["end_part"];
                                                $chapter_id_cursor = $pageParagraphNumArray[$page_num_tentative]["end_part_chapter_id"];
                                                $log_arr[] = "<span class='technical'>tentative-page:$page_num_tentative : pageParagraphNumArray[$page_num_tentative] is ".var_export($pageParagraphNumArray[$page_num_tentative],true)." => [chp$chapter_id_cursor, prg$prg_cursor_num]</span>";
                                        }
                                        else 
                                        {
                                                break;
                                        }
                                        
                                }
                                
                                if((!$prg_cursor_num) or (!$chapter_id_cursor))
                                {
                                        $new_page_where="end";
                                }
                                else
                                {
                                        $page_num_cursor = $page_num_tentative;
                                }
                                if($log) $log_arr[] = "after applying $new_page_where (old $new_page_where_old but not found) new-page-where-method goto pg$page_num_cursor";
                        }

                        if($new_page_where=="end")
                        {
                                $prg_cursor_num = $pageParagraphNumArray[$page_num_cursor]["last"];
                                $chapter_id_cursor = $pageParagraphNumArray[$page_num_cursor]["last_chapter_id"];                                
                        }
                        
                        
                        
                        if($new_page_where=="start")
                        {
                                $prg_cursor_num = $pageParagraphNumArray[$page_num_cursor]["first"];
                                $chapter_id_cursor = $pageParagraphNumArray[$page_num_cursor]["first_chapter_id"];
                        }
                        
                        if($log) $log_arr[] = "applyed new-page-where-method ^^$new_page_where^^ => c$chapter_id_cursor, p$prg_cursor_num pageParagraphNumArray[$page_num_cursor] = " . var_export($pageParagraphNumArray[$page_num_cursor],true);
                }

                $prgCursor = self::loadByMainIndex(0, 0, $chapter_id_cursor, $prg_cursor_num);
                if(!$prgCursor)
                {
                        if($log) $log_arr[] = "!!! CpcBookParagraph::loadByMainIndex(0, 0, $chapter_id_cursor, $prg_cursor_num) not found !!!";
                        $part_id = "not found";
                        $part_num = "not found";
                        $page_num_final = "not found";
                }
                else
                {
                        $part_id = $prgCursor->getVal("part_id");
                        $part_num = $part_id - 1;
                        if($part_num>10000) $part_num = $part_num - 10000;
                        $page_num_final = $prgCursor->getVal("page_num");
                }
                
                

                if($log) $log_arr[] = "<b>new position : part$part_num page$page_num_final (cur=$page_num_cursor should be same) chapter$chapter_id_cursor paragraph$prg_cursor_num</b>";


                return [$book_id, $part_id, $chapter_id_cursor, $page_num_final, $prg_cursor_num, $log_arr, $prgCursor];
        }
        
        public function getDisplay($lang = 'ar')
        {
                if($this->getVal("paragraph_text")) return AfwStringHelper::truncateArabicJomla($this->getVal("paragraph_text"),52);
               $data = array();
               $link = array();
               

               list($data[0],$link[0]) = $this->displayAttribute("chapter_id",false, $lang);
               $data[1] = "الآية ".$this->getVal("paragraph_num"); 
               
               return implode(" - ",$data);
        }


        public function calcPreview($what="value")
        {
                $params["book_id"] = $this->getVal("book_id");
                $params["paragraph_num"] = $this->getVal("paragraph_num");
                $params["paragraph_num_to"] = $this->getVal("paragraph_num");
                $params["chapter_id"] = $this->getVal("chapter_id");
                $params["chapter_id_from"] = $this->getVal("chapter_id");
                $params["chapter_id_to"] = $this->getVal("chapter_id");
                $params["start_hidden"] = false;
                $pg_num = $this->getVal("page_num");
                $pg_num_fardi = (($pg_num %2) == 1);
                $params["page_from"] = $pg_num_fardi ? $pg_num : $pg_num - 1;
                $params["page_to"] = $pg_num_fardi ? $pg_num+1 : $pg_num;
                $params["mode_input"] = "no-input";

                $file_dir_name = dirname(__FILE__); 

                ob_start();
                //die("$file_dir_name/tpl/select_ayat.php");
                include("$file_dir_name/../tpl/select_ayat.php");

                return ob_get_clean();
        }

        public function stepsAreOrdered()
        {
            return false;
        }        

        public static function resetToFirstParagraph($book_id, $work_sens)
        {
                $log1_arr = [];
                if($work_sens==1) 
                {
                        if($book_id==10001) $book_id = 1;
                        $new_part_id_from = 2;
                        $new_chapter_id_from = 1002;
                        $new_page_num_from = 2;
                        $new_paragraph_num_from = 1;
                        $log1_arr[] = "resetToFirstParagraph => forward from start on book_id=$book_id";
                }
                elseif($work_sens==-1)
                {
                        if($book_id==1) $book_id = 10001;
                        $new_part_id_from = 10002;
                        $new_chapter_id_from = 11001;
                        $new_page_num_from = 1;
                        $new_paragraph_num_from = 1;
                        $log1_arr[] = "resetToFirstParagraph => backward from end on book_id=$book_id";
                }


                return [$book_id, $new_part_id_from, $new_chapter_id_from, $new_page_num_from, $new_paragraph_num_from, $log1_arr];
        }

        public static function getMaxParagraphNumOf($chapter_id, $book_id=1)
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

                return AfwDatabase::db_recup_value("select max(paragraph_num) as pnum_max  from cpc_book_paragraph where book_id = $book_id and chapter_id = $chapter_id");


        }

        
        
             
}
?>
