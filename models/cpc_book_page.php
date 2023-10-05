<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table cpc_book_page : cpc_book_page - صفحات الكتب الدراسية 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class CpcBookPage extends SisObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "sis"; 
        public static $TABLE			= "cpc_book_page"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("cpc_book_page","id","sis");
                SisCpcBookPageAfwStructure::initInstance($this);
                
                
                
	}
        
        public static function loadByMainIndex($book_id, $book_page_num,$create_obj_if_not_found=false)
        {
           $obj = new CpcBookPage();
           $obj->select("book_id",$book_id);
           $obj->select("book_page_num",$book_page_num);

           if($obj->load())
           {
                if($create_obj_if_not_found) $obj->activate();
                return $obj;
           }
           elseif($create_obj_if_not_found)
           {
                $obj->set("book_id",$book_id);
                $obj->set("book_page_num",$book_page_num);

                $obj->insert();
                return $obj;
           }
           else return null;
           
        }
        
        public function getDisplay($lang = 'ar')
        {
               if($this->getVal("book_page_name")) return $this->getVal("book_page_name");
               $data = array();
               $link = array();
               

               list($data[0],$link[0]) = $this->displayAttribute("book_id",false, $lang);
               list($data[1],$link[1]) = $this->displayAttribute("book_page_num",false, $lang);
               $data[1] = "ص ".$data[1]; 
               
               return implode(" - ",$data);
        }
        
        public function getFormuleResult($attribute, $what='value') 
        {
            global $me, $BOOKS_HTTP_PATH, $Main_Page;    
               
	       switch($attribute) 
               {
                    

                    case "page_desc" :
                        $id = $this->getId();
                        $html_desc = "";
                        $book_id = $this->getVal("book_id");
                        
                        
                        $wd = 105;
                        $hg = 150;
                        
                        $p = intval($this->getVal("book_page_num"));

                        if($p)
                        {
                               $pic_num = $book_id*10000+$p;
                               $pic_id = "zoom_${pic_num}";
                               
                               $pic_file = $pic_num.".jpg";
                                if($this->picture_style) $picture_style = "style='".$this->picture_style."'";
                                else $picture_style = "style='width: ${wd}px !important;   height: ${hg}px !important;'";
                                
                                $html_desc .= "<img id='$pic_id' src='$BOOKS_HTTP_PATH/pages/$pic_file' $picture_style  data-zoom-image='$BOOKS_HTTP_PATH/pages/$pic_file'>";  // alt='انقر هنا لتحميل الملف'
                                $html_desc .= "
        <script>
            $('#$pic_id').elevateZoom({
        cursor: \"crosshair\",
        zoomWindowWidth:788,
        zoomWindowHeight:960,
        zoomWindowFadeIn: 500,
        zoomWindowFadeOut: 1100,
        zoomWindowOffety:-200
        
           });
           
        </script>
                                ";
                        }    
                        
                        
			return $html_desc;
		    break;
                    
               }
        }
        
        protected function getOtherLinksArray($mode, $genereLog = false, $step="all")      
        {
             global $me, $objme, $lang;
             $otherLinksArray = $this->getOtherLinksArrayStandard($mode, false, $step);
             $my_id = $this->getId();
             $displ = $this->getDisplay($lang);
             
             
             
             return $otherLinksArray;
        }

        

        public static function displayParagraphTitles($bookObj, $right_page, $left_page, $templateObj, $paragraphNum, $paragraphNumTo)
        {
                //$page_numFardi = (($page_num % 2)==1) ? "pfardi" : "pzawji";
                $js = "";
                $cp = new CpcBookParagraph();
                $cp->select("book_id",$bookObj->id);
                $cp->where("page_num between $right_page and $left_page");
                $cpList = $cp->loadMany();

                foreach($cpList as $cpItem)
                {
                        $pnum = $cpItem->getVal("paragraph_num");
                        $aya_title = AfwStringHelper::truncateArabicJomla($cpItem->getVal("paragraph_text"), 52)." ($pnum)";
                        
                        $js .= "if(pnum==$pnum) return '$aya_title';\n";
                }

                $js .= "return 'الآية '+pnum;\n";
                return $js;
        }

        public static function displayPage($bookObj, $page_num, $templateObj, $paragraphNum, $paragraphNumTo, $attribute, $p12, $chapterId, $chapterIdFrom, $chapterIdTo, $pagination_sens=1)
        {
                $displayedPragraphsArr = [];
                $page_numFardi = (($page_num % 2)==1) ? "pfardi" : "pzawji";
                $html = "<div id='coranpage$page_num' class='coran-page page$page_num $page_numFardi'>";
                $cp = new CpcBookParagraph();
                $cp->select("book_id",$bookObj->id);
                $cp->select("page_num",$page_num);
                $cpList = $cp->loadMany();
                $first=0;
                $last=0;
                $first_paragraph_of_page = true;
                foreach($cpList as $cpItem)
                {
                        $pnum = $cpItem->getVal("paragraph_num");
                        $chpId = $cpItem->getVal("chapter_id");
                        $displayedPragraphsArr[] = "$pnum-$chpId";

                        if((!$first) and ($chpId == $chapterIdFrom)) $first=$pnum;
                        if(($last<$pnum) and ($chpId == $chapterIdTo)) $last = $pnum;
                        $aya = $cpItem->formatAya($cpItem->getVal("paragraph_text"),$pnum, $first_paragraph_of_page,$cpItem->getVal("len"));
                        $first_paragraph_of_page = false;
                        $pnumFardi = (($pnum%2)==1) ? "fardi" : "zawji";
                        if($pnum>=100) $xbig = "xbig";
                        else $xbig = "xsmall";
                        /*
                        if(($chpId<$chapterIdTo) and ($chpId>=$chapterIdFrom))
                        {
                                $aya_selected = "selected";
                        }*/
                        if(true)
                        {
                                $chapter_is_between = (($chpId>$chapterIdFrom) and ($chpId<$chapterIdTo));
                                if($chapterIdFrom!=$chapterIdTo)
                                {
                                        $paragraph_selected_in_first_chapter = (($pnum>=$paragraphNum) and ($chpId==$chapterIdFrom));
                                        $paragraph_selected_in_last_chapter = (($pnum<=$paragraphNumTo) and ($chpId==$chapterIdTo));
                                }
                                else
                                {
                                        $paragraph_selected_in_first_chapter = (($pnum>=$paragraphNum) and ($pnum<=$paragraphNumTo) and ($chpId==$chapterIdFrom));
                                        $paragraph_selected_in_last_chapter = $paragraph_selected_in_first_chapter;
                                }
                                
                                $aya_selected = "psifc-$paragraph_selected_in_first_chapter or-cib-$chapter_is_between or-psilc-$paragraph_selected_in_last_chapter aya-is-$pnum vs-from-aya-$paragraphNum-to-aya-$paragraphNumTo soura-is-$chpId-vs-soura-from-$chapterIdFrom-soura-to-$chapterIdTo";
                                if(($paragraph_selected_in_first_chapter or $paragraph_selected_in_last_chapter or $chapter_is_between))
                                {
                                        $aya_selected .= " selected";
                                }
                                else 
                                {
                                        $aya_selected .= " notsel ";
                                }
                        }
                        if(($chpId >= $chapterIdFrom) and ($chpId <= $chapterIdTo)) $aya_clickable_class = "bookpg";
                        else $aya_clickable_class = "disabled-bookpg c$chpId f$chapterIdFrom t$chapterIdTo";

                        $html .= "<a href='#a$attribute"."coranpage$page_num' id='a$attribute"."pg-$pnum-$chpId' class='$aya_clickable_class col$attribute'>";
                        $html .= "<span id='a$attribute"."p$pnum"."c$chpId' class='aya $aya_selected $pnumFardi'>$aya</span>
                                  <p id='a$attribute"."num$pnum"."c$chpId' class='aya-number $xbig $aya_selected'>&nbsp;$pnum&nbsp;</p>\n";
                        $html .= "</a>";
                }
                $html .= "<input type=\"hidden\" name=\"$attribute"."page".$p12."_start\" id=\"$attribute"."page".$p12."_start\" value=\"$first\">";
                $html .= "<input type=\"hidden\" name=\"$attribute"."page".$p12."_end\" id=\"$attribute"."page".$p12."_end\" value=\"$last\">";
                $html .= "</div>";
                $html .= "<div class='numpage $page_numFardi'> - $page_num - </div>";

                $displayedPragraphs = AfwHtmlHelper::phpArrayToJsArray($displayedPragraphsArr);

                return [$html, $displayedPragraphs];
        }

        public function stepsAreOrdered()
        {
            return false;
        }
             
}
?>