<?php
// ------------------------------------------------------------------------------------
// ALTER TABLE `cpc_book` ADD `part_mfk` VARCHAR(255) NULL AFTER `course_mfk`;
// ALTER TABLE `cpc_book` ADD `len` FLOAT NULL AFTER `book_nb_pages`;
// alter table c0sis.cpc_book add   name_page_num smallint DEFAULT NULL  after last_page_num;
// alter table c0sis.cpc_book add   besmella_page_num smallint DEFAULT NULL  after name_page_num;
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcBook extends SisObject{

	public static $DATABASE		= ""; 
    public static $MODULE		    = "sis"; 
    public static $TABLE			= "cpc_book"; 
    public static $DB_STRUCTURE = null; 
    
    
    
    public function __construct(){
		parent::__construct("cpc_book","id","sis");
        SisCpcBookAfwStructure::initInstance($this);
                
                
                
	}

        public static function loadById($id)
        {
           $obj = new CpcBook();
           if($obj->load($id))
           {
                return $obj;
           }
           else return null;
        }
        
        public function getDisplay($lang="ar")
        {
            return $this->getVal("book_name");
        }

        public function getDropdownDisplay($lang="ar")
        {
            return str_replace("سورة ","",$this->getVal("book_name"));
        }
                
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
            global $me, $objme, $lang;
            $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
            $my_id = $this->getId();
            $displ = $this->getDisplay($lang);
            
            if($mode=="mode_cpcBookPageList")
            {
                unset($link);
                $my_id = $this->getId();
                $link = array();
                $title = "إدارة صفحات الكتب الدراسية ";
                $title_detailed = $title;
                $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcBookPage&currmod=sis&id_origin=$my_id&class_origin=CpcBook&module_origin=sis&newo=10&limit=30&ids=all&fixmtit=$title_detailed&fixmdisable=1&fixm=book_id=$my_id&sel_book_id=$my_id";
                $link["TITLE"] = $title;
                $link["UGROUPS"] = array();
                $otherLinksArray[] = $link;
            }

            if($mode=="mode_cpcChapterList")
            {
                unset($link);
                $my_id = $this->getId();
                $link = array();
                $title = "إدارة السور ";
                $title_detailed = $title;
                $link["URL"] = "main.php?Main_Page=afw_mode_qedit.php&cl=CpcBook&currmod=sis&id_origin=$my_id&class_origin=CpcBook&module_origin=sis&newo=-1&limit=150&ids=all&fixmtit=$title_detailed&fixmdisable=1".
                               "&fixm=parent_book_id=$my_id,book_type_id=3&sel_parent_book_id=$my_id&sel_book_type_id=3";
                $link["TITLE"] = $title;
                $link["UGROUPS"] = array();
                $otherLinksArray[] = $link;
            }

             
             
             
             
             return $otherLinksArray;
        }
        
        public function beforeMAJ($id, $fields_updated) 
        {
               global $file_dir_name;
              
               if(!$this->getVal("book_name")) $this->set("book_name",$this->getDisplay());
               $book_id = $this->getId();
               $book_nb_pages = intval($this->getVal("book_nb_pages"));
               
               if(isset($fields_updated["book_nb_pages"]) and ($book_nb_pages>0))
               {
                   // // require_once cpc_book_page.php");
                   $obj = new CpcBookPage();
                   $obj->select("book_id",$book_id);
                   $obj->where("book_page_num > $book_nb_pages");
                   $obj->set("active",'N');
                   $obj->update(false);
                   
                   for($page=1;$page<=$book_nb_pages;$page++)
                   {
                         $bookPageObjArr[$page] = CpcBookPage::loadByMainIndex($book_id, $page,true);
                   }
               
               }
               
               return true;
        }

        public function stepsAreOrdered()
        {
            return false;
        }  
        
        
        protected function getPublicMethods()
        {

            $return = array(

                

                "x1cp54" => array(
                    "METHOD" => "calculateParts",
                    "LABEL_EN" => "calculate Parts",
                    "LABEL_AR" => "توليد الأجزاء أو تحديثها",
                    'STEP' => 1,
                    "BF-ID" => ""
                ),

                "r4cp85" => array(
                    "METHOD" => "calculateAllParts",
                    "LABEL_EN" => "calculate all Parts",
                    "LABEL_AR" => "توليد كل الأجزاء أو تحديثها",
                    'STEP' => 5,
                    "BF-ID" => ""
                ),

                "r2cp99" => array(
                    "METHOD" => "updateGeneratedPages",
                    "LABEL_EN" => "update Generated Pages",
                    "LABEL_AR" => "توليد الأوجه أو تحديثها",
                    'STEP' => 3,
                    "BF-ID" => ""
                ),

                "r10001" => array(
                    "METHOD" => "resetBook10001",
                    "LABEL_EN" => "reset book 10001",
                    "LABEL_AR" => "توليد الأوجه الصحيحة ل10001",
                    'STEP' => 1,
                    "BF-ID" => ""
                ),


                

                

            );

            

            

            return $return;
        }

        public function calculateParts($lang="ar")
        {
            $cpcBookParagraphList = $this->get("cpcBookParagraphList");
            foreach($cpcBookParagraphList as $cpcBookParagraphItem)
            {
                $this->addRemoveInMfk("part_mfk",[$cpcBookParagraphItem->getVal("part_id")],[]);
            }
            $this->commit();
        }

        public function calculateAllParts($lang="ar")
        {
            $cpcChapterList = $this->get("cpcChapterList");
            foreach($cpcChapterList as $cpcChapterItem)
            {
                $cpcChapterItem->calculateParts();
            }
        }

        public function resetBook10001($lang="ar")
        {
            global $MODE_SQL_PROCESS_LOURD, $MODE_BATCH_LOURD, $nb_queries_executed;
            $old_mode = $MODE_BATCH_LOURD;
            //$MODE_SQL_PROCESS_LOURD = 'resetBook10001';
            $MODE_BATCH_LOURD = 'resetBook10001';

            $err_arr = [];
            $inf_arr = [];
            $war_arr = [];
            $tech_arr = [];

            $page_curr = 1;
            $page_size_lines = 16.2;
            $lines_remain_curr = $page_size_lines; 

            $chp_start = 11001;
            $chp_end = 11114;

            for($chp=$chp_start; $chp<=$chp_end; $chp++)
            {
                $tech_arr[] = "page_curr=$page_curr chapter_cur=$chp lines_remain=$lines_remain_curr";
                $lines_chapter_total = 0;
                $height_chapter_title = 2.0;

                $chpBookObj = self::loadById($chp);

                $chp_len = $chpBookObj->getVal("len");
                // case - 1 no enough space for whole of small chapter
                if(($lines_remain_curr<$chp_len) and ($chp_len<=5.0))
                {
                    $tech_arr[] = "lines_remain_curr=$lines_remain_curr < chpLen=$chp_len no enough space for whole of small chapter => goto next page";
                    $lines_remain_curr = $page_size_lines;
                    $page_curr++;
                    $tech_arr[] = "page_curr=$page_curr";
                }

                // case - 2 no enough space for title of chapter
                if($lines_remain_curr<$height_chapter_title)
                {
                    $tech_arr[] = "lines_remain_curr = $lines_remain_curr is few => goto next page";
                    $lines_remain_curr = $page_size_lines;
                    $page_curr++;
                    $tech_arr[] = "page_curr=$page_curr";
                }
                $lines_remain_curr -= $height_chapter_title;
                $tech_arr[] = "lines_remain_curr -= $height_chapter_title (title) => $lines_remain_curr";

                $lines_chapter_total += $height_chapter_title;
                $first_page_num = $page_curr;
                
                $cpcBookParagraphList = $chpBookObj->get("cpcBookParagraphList");
                foreach($cpcBookParagraphList as $cpcBookParagraphItem)
                {
                    $aya_num = $cpcBookParagraphItem->getVal("paragraph_num");
                    $len = floatval($cpcBookParagraphItem->getVal("len"));
                    // 0.4 متسامح فيها لوضع الآية في نغس الوجه
                    if($lines_remain_curr<=($len-0.4))
                    {
                        $tech_arr[] = "lines_remain_curr = $lines_remain_curr is few for (aya$aya_num len=$len) => goto next page";
                        $lines_remain_curr = $page_size_lines;
                        $page_curr++;
                        $tech_arr[] = "page_curr=$page_curr";
                    }
                    $lines_remain_curr -= $len;
                    $tech_arr[] = "lines_remain_curr -= $len (aya$aya_num) => $lines_remain_curr";

                    $lines_chapter_total += $len;
                    $cpcBookParagraphItem->set("page_num", $page_curr);
                    $cpcBookParagraphItem->commit();
                    
                }
                $lines_remain_curr = ceil($lines_remain_curr+0.125)-1;
                $last_page_num = $page_curr;
                $chpBookObj->set("first_page_num",$first_page_num);
                $chpBookObj->set("last_page_num",$last_page_num);
                $chpBookObj->set("len",$lines_chapter_total);
                $chpBookObj->commit();
            }

            $this->set("book_nb_pages",$last_page_num);
            $this->commit();

            $MODE_BATCH_LOURD = $old_mode;
            $nb_queries_executed = 0; // to not count old queries and avoid next small and light processes halt because of.

            $inf_arr[] = "last page created $page_curr";

            return AfwFormatHelper::pbm_result($err_arr, $inf_arr, $war_arr, "<br>\n", $tech_arr);
        }


        public function updateGeneratedPages($lang="ar")
        {
            global $MODE_SQL_PROCESS_LOURD, $nb_queries_executed;
            $old_nb_queries_executed = $nb_queries_executed;
            $old_MODE_SQL_PROCESS_LOURD = $MODE_SQL_PROCESS_LOURD;
            $MODE_SQL_PROCESS_LOURD = true;

            $nbPages = $this->getVal("book_nb_pages");
            for($pg = 1; $pg<=$nbPages; $pg++)
            {
                $objPage = CpcBookPage::loadByMainIndex($this->id,$pg,true);
                $objPage->set('book_page_name' ,'ص'.$pg);
                // @todo later fill 'book_page_content'
                // $objPage->set('book_page_content' ,$xxxxxxx);
                $objPage->commit();

			
            }

            $MODE_SQL_PROCESS_LOURD = $old_MODE_SQL_PROCESS_LOURD;
            $nb_queries_executed = $old_nb_queries_executed;
        }


         


        public function getDefaultTemplate()
        {
            return null;
        }

        public static function getBookLocation($obj, $attribute, $offset=0)
        {
            global $lang;
            $book_id = 0;
            $part_id = 0; // because sourat can start on part and finish on another //$this->getVal($attribute."_part_id");
            $chapter_id = $obj->getVal($attribute."_chapter_id");
            $chapter_name = $obj->showAttribute($attribute."_chapter_id");
            $paragraph_num = $obj->getVal($attribute."_paragraph_num");

            if(!$chapter_id)
            {
                $chapter_id = 1002;
                $paragraph_num = 1;
                $chapter_name = "البقرة";
            }
            elseif(!$paragraph_num)
            {
                // $chapter_num = $chapter_id - 1000;
                $paragraph_num = 1;
            }

            $page_num = $obj->getVal($attribute."_page_num");
            //if($attribute=="homework2_end") die("trying CpcBookParagraph::loadByMainIndex($book_id, $part_id, $chapter_id, $paragraph_num) ...");
            if($chapter_id and $paragraph_num) 
            {
                $prgh = CpcBookParagraph::loadByMainIndex($book_id, $part_id, $chapter_id, $paragraph_num);
                if($offset!=0) 
                {
                    $prgh = $prgh->moveParagraphs($offset);
                }
                if($prgh)
                {
                    $page_num = $prgh->getVal("page_num");
                    $chapter_id = $prgh->getVal("chapter_id");
                } 
            }
            elseif($page_num) 
            {
                $prgh = CpcBookParagraph::loadFirstParagraph($book_id, $part_id, $chapter_id, $page_num);            
                if($prgh) $paragraph_num = $prgh->getVal("paragraph_num");
            }
            else $prgh = null;
            if(!$page_num) $page_num = $prgh ? $prgh->getVal("page_num") : 0;
            $book_id = $prgh ? $prgh->getVal("book_id") : 0;
            $part_id = $prgh ? $prgh->getVal("part_id") : 0;

            return array($book_id, $paragraph_num, $chapter_id, $page_num, $prgh, $part_id, $chapter_name);
        }

        public static function getBookParams($obj, $attribute, $lock=false, $unlockable=true, $offset_start=0)
        {
            $attribute_start = str_replace("_end","_start",$attribute);
            $attribute_end = str_replace("_start","_end",$attribute);
            if(!$lock)
            {
                if($attribute_end==$attribute_start) $mode="unique";
                elseif($attribute==$attribute_start) $mode="interval-start";
                elseif($attribute==$attribute_end) $mode="interval-end";
                else $mode="unique";
            }
            else
            {
                
                if($unlockable) $mode="lock-unlockable";
                else $mode="lock";
            }
            
            //die("getBookParams($obj, $attribute, $lock) => attribute_start=$attribute_start attribute_end=$attribute_end attribute=$attribute mode=$mode");
            list($book_id, $paragraph_num, $chapter_id_from, $page_num_from, $prgFromObj, $part_from, $chapter_id_from_name,) = self::getBookLocation($obj, $attribute_start, $offset_start);
            list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $prgToObj, $part_to, $chapter_id_to_name,) = self::getBookLocation($obj, $attribute_end);
            //die("list(book_id=$book_id, paragraph_num_to=$paragraph_num_to, chapter_id_to=$chapter_id_to, page_num_to=$page_num_to, .., part_to=$part_to, chapter_id_to_name=$chapter_id_to_name,) = self::getBookLocation(obj, $attribute_end)");
            if($page_num_to==$page_num_from) $page_num_to++;
            if($attribute==$attribute_start) $page_num = $page_num_from;
            if($attribute==$attribute_end) $page_num = $page_num_to - 1;

            if($attribute==$attribute_start) $chapter_id = $chapter_id_from;
            if($attribute==$attribute_end) $chapter_id = $chapter_id_to;

            if($page_num<1) $page_num = 1;
            

            return ['book_id'=>$book_id, 
                        'paragraph_num'=>$paragraph_num, 'paragraph_num_to'=>$paragraph_num_to, 
                        'chapter_id'=>$chapter_id, 'chapter_id_from'=>$chapter_id_from, 'chapter_id_to'=>$chapter_id_to,
                        'chapter_id_from_name'=>$chapter_id_from_name, 'chapter_id_to_name'=>$chapter_id_to_name,
                        'page_from'=>$page_num_from,'page_to'=>$page_num_to,'page'=>$page_num, 
                        'mode_input'=>$mode, "strict_from" => "false"];
        }

        public static function paragraphShortFromTo($obj, $attribute)
        {
            $attribute_start = str_replace("_end","_start",$attribute);
            $attribute_end = str_replace("_start","_end",$attribute);

            list($book_id, $paragraph_num_from, $chapter_id_from, $page_num_from, $prgh_from) = $obj->getMyBookLocation($attribute_start);
            if(!$prgh_from) return "?!!!? [$attribute_start|$paragraph_num_from, $chapter_id_from, $page_num_from]";
            list($book_id, $paragraph_num_to, $chapter_id_to, $page_num_to, $prgh_to) = $obj->getMyBookLocation($attribute_end);
            if(!$prgh_to) return "?!!!? [$attribute_end|$paragraph_num_to, $chapter_id_to, $page_num_to]";
            //$return = "<span class='oper'>من</span> ";
            $return = "من ";
            $return .=  AfwStringHelper::truncateArabicJomla($prgh_from->getVal("paragraph_text"), 32)."($paragraph_num_from)";
            $return .= " إلى ";
            $return .=  AfwStringHelper::truncateArabicJomla($prgh_to->getVal("paragraph_text"), 32)."($paragraph_num_to)";

            return $return;
        }

        public static function calcAttribute_paragraph_id($obj, $attribute, $what="value", $book_id = 0, $book_attribute="")
        {
            global $cache_paragraphs;
            if($what=="decodeme") $what="value";
            if($book_attribute) $book_id = $obj->getVal($book_attribute);
            $part_id = 0; // because sourat can start on part and finish on another //$this->getVal($attribute."_part_id");
            $chapter_id = $obj->getVal($attribute."_chapter_id");
            $paragraph_num = $obj->getVal($attribute."_paragraph_num");
            if((!$chapter_id) or (!$paragraph_num))
            {
                $chapter_id = 1002;
                $paragraph_num = 1;
            } 
            //die("calcAttribute_paragraph_id($obj, $attribute, $what) => chapter_id=$chapter_id , paragraph_num=$paragraph_num");
            
            if($chapter_id and $paragraph_num) 
            {
                if($cache_paragraphs[$book_id][$part_id][$chapter_id][$paragraph_num])
                {
                    $returnObj = $cache_paragraphs[$book_id][$part_id][$chapter_id][$paragraph_num];
                }
                else
                {
                    $returnObj = CpcBookParagraph::loadByMainIndex($book_id, $part_id, $chapter_id, $paragraph_num);
                    $cache_paragraphs[$book_id][$part_id][$chapter_id][$paragraph_num] = $returnObj;
                }
                
                //die("calcAttribute_paragraph_id ( $obj, $attribute, $what ) => ch$chapter_id and pg$paragraph_num => $returnObj");
                if(!$returnObj)
                {
                    return ($what=="value") ? 0 : null;    
                }
                return ($what=="value") ? $returnObj->id : $returnObj;
            }    
            else
            {
                return ($what=="value") ? 0 : null;
            }
        }

        public static function isValidChapterId($book_id, $chapter_id)
        {
            if($book_id==1) return (($chapter_id>=1001) and ($chapter_id<=1114));
            if($book_id==10001) return (($chapter_id>=11001) and ($chapter_id<=11114));

            return true;
        }


        public static function getParagraphAttributeFromChapterAndParagraphNum($chapter_id, $paragraph_num, $attribute)
        {
            $paragObj = CpcBookParagraph::loadByMainIndex(0, 0, $chapter_id, $paragraph_num);

            if($paragObj) return $paragObj->getVal($attribute);
            return null;
        }


        public function genereMyLines($lang="ar")
        {
            // 1	book_id Primary	int(11)			No	None			 		
            // 2	part_id	int(11)			Yes	NULL			 		
            // 3	chapter_id	int(11)			Yes	NULL			 		
            // 4	line_num Primary	smallint(6)			No	None			 		
            // 5	page_num	smallint(6)			Yes	NULL			 		
            // 6	aya_complete_num	smallint(6)			Yes	NULL			 		
            // 7	aya_incomplete_pct	float			Yes	NULL			 		

            // 1	book_id PrimaryIndex	int(11)								
            // 2	part_id Primary	int(11)								
            // 3	chapter_id PrimaryIndex	int(11)								
            // 4	paragraph_num PrimaryIndex	smallint(6)								
            // 7	page_num	smallint(6)								
            // 8	lines_count	smallint(6)								
            // 9	len	        float			Yes	NULL					
            // 10	len_corr	float								
            // 11	paragraph_text	text	utf8_unicode_ci							
            // 12	paragraph_text_uf_2	text	utf8_unicode_ci							
            // 13	paragraph_text_uf	text	utf8_unicode_ci							

            $attributes_arr = ["part_id","chapter_id","paragraph_num","page_num","lines_count","len_corr"];

            $cpcBookParagraphArr = $this->getRelation("cpcBookParagraphList")->getData($attributes_arr);
            foreach($cpcBookParagraphArr as $cpcBookParagraphRow)
            {
                
            }
        


        }
             
}
?>