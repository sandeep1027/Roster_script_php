<?php 
class Roster{
	
	//connection and query 
	private $connection;

	function __construct(){
		 $this->open_connection();
	}
    public function open_connection(){
		 
		 $this->connection=mysql_connect("localhost","root","toor");
		 if(!$this->connection){
		   die("sql server error".mysql_error());
		 }else
		  {
			$db_select=mysql_select_db("ttmer",$this->connection);
			if(!$db_select)
			   die("sql error".mysql_error());
		  }
	  }
	public function close_connection(){
		if(isset($this->connection)){
		  mysql_close($this->connection);
		  unset($this->connection);
		}
	  }
   public function query($sql){
	  
		//mysql_query("SET NAMES utf8");
		$query=mysql_query($sql,$this->connection) or die(mysql_error());
		return $query;
	}
	public function num_row($res){
	   return mysql_num_rows($res);
	}
	public function fetch_array($res){
		return mysql_fetch_array($res);
	}
	
   public function clear(){
	   if($this->query("truncate ttm_roster"))
		   return "y";
	   else
		   return "n";
   }	
	
	//array store accent character conversion
	private $unwanted_array = array('Š'=>'S','š'=>'s','Ž'=>'Z','ž'=>'z','À'=>'A','Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E','É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y',);

	
	private $rm=array("Quarterbacks","Running backs","Wide receivers","Tight ends","Defensive linemen","Offensive linemen","Linebackers","Defensive backs","Special teams","Reserve lists","Pitchers","Catchers","Outfielders","Infielders","Closer","Bullpen","Starting rotation","PitchersStarting rotation","PitchersZtarting rotation");
	private $rm1=array("FB","DT","C","T","G","DE","OLB","ILB","SS","CB","FS","K","LS","KOS","P","GT","MLB","PR","KR","LB","S");
    
	private function remove_accents( $string ) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if ($this->seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
		chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
		chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
		chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
		chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
		chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
		chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
		chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
		chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
		chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
		chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
		chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
		chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
		chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
		chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
		chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Decompositions for Latin Extended-B
		chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
		chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '',
		// Vowels with diacritic (Vietnamese)
		// unmarked
		chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
		chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
		// grave accent
		chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
		chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
		chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
		chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
		chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
		chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
		chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
		// hook
		chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
		chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
		chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
		chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
		chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
		chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
		chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
		chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
		chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
		chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
		chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
		chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
		// tilde
		chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
		chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
		chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
		chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
		chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
		chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
		chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
		chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
		// acute accent
		chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
		chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
		chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
		chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
		chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
		chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
		// dot below
		chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
		chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
		chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
		chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
		chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
		chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
		chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
		chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
		chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
		chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
		chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
		chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
		// Vowels with diacritic (Chinese, Hanyu Pinyin)
		chr(201).chr(145) => 'a',
		// macron
		chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
		// acute accent
		chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
		// caron
		chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
		chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
		chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
		chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
		chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
		// grave accent
		chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
		);

		

		$string = strtr($string, $chars);
	} else {
		$chars = array();
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars = array();
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

	private function seems_utf8($Str) { # by bmorel at ssi dot fr
		 $length = strlen($Str);
		 for ($i = 0; $i < $length; $i++) {
		  if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
		  elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n = 1; # 110bbbbb
		  elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n = 2; # 1110bbbb
		  elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n = 3; # 11110bbb
		  elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n = 4; # 111110bb
		  elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n = 5; # 1111110b
		  else return false; # Does not match any model
		  for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
		   if ((++$i == $length) || ((ord($Str[$i]) & 0xC0) != 0x80))
		   return false;
		  }
		 }
		 return true;
		}
	
	
	private $MLB=array(0=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Mets','path' =>'//*[@id="mw-content-text"]/table[6]/tr[3]/td[1]','name' =>'New York Mets'),1=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Yankees','path' =>'//*[@id="mw-content-text"]/table[8]/tr[3]/td[1]','name' =>'New York Yankees'),2=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Angels_of_Anaheim','path' =>'//*[@id="mw-content-text"]/table[8]/tr[3]/td[1]','name' =>'Los Angeles Angels of Anaheim'),3=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Dodgers','path' =>'//*[@id="mw-content-text"]/table[6]/tr[3]/td[1]','name' =>'Los Angeles Dodgers'),4=>array('url'=>'https://en.wikipedia.org/wiki/Chicago_Cubs','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]','name' =>'Chicago Cubs'),5=>array('url'=>'https://en.wikipedia.org/wiki/Chicago_White_Sox','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]','name' =>'Chicago White Sox'),6=>array('url'=>'https://en.wikipedia.org/wiki/Baltimore_Orioles','path' =>'//*[@id="mw-content-text"]/table[6]/tr[3]/td[1]','name' =>'Baltimore Orioles'),7=>array('url'=>'https://en.wikipedia.org/wiki/Washington_Nationals','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]','name' =>'Washington Nationals'),8=>array('url'=>'https://en.wikipedia.org/wiki/Oakland_Athletics','path' =>'//*[@id="mw-content-text"]/table[8]/tr[3]/td[1]','name' =>'Oakland Athletics'),9=>array('url'=>'https://en.wikipedia.org/wiki/San_Francisco_Giants','path' =>'//*[@id="mw-content-text"]/table[9]/tr[3]/td[1]','name' =>'San Francisco Giants'),10=>array('url'=>'https://en.wikipedia.org/wiki/Boston_Red_Sox','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]','name' =>'Boston Red Sox'),11=>array('url'=>'https://en.wikipedia.org/wiki/Texas_Rangers_(baseball)','path' =>'//*[@id="mw-content-text"]/table[9]/tr[3]/td[1]','name' =>'Texas Rangers (baseball)'),12=>array('url'=>'https://en.wikipedia.org/wiki/Philadelphia_Phillies','path' =>'//*[@id="mw-content-text"]/table[8]/tr[3]/td[1]','name' =>'Philadelphia Phillies'),13=>array('url'=>'https://en.wikipedia.org/wiki/Houston_Astros','path' =>'//*[@id="mw-content-text"]/table[7]/tr[3]/td[1]','name' =>'Houston Astros'),14=>array('url'=>'https://en.wikipedia.org/wiki/Miami_Marlins','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]','name' =>'Miami Marlins'),15=>array('url'=>'https://en.wikipedia.org/wiki/Atlanta_Braves','path' =>'//*[@id="mw-content-text"]/table[2]/tr/td/table[6]/tr[3]/td[1]','name' =>'Atlanta Braves'),16=>array('url'=>'https://en.wikipedia.org/wiki/Detroit_Tigers','path' =>'//*[@id="mw-content-text"]/table[12]/tr[3]/td[1]','name' =>'Detroit Tigers'),17=>array('url'=>'https://en.wikipedia.org/wiki/Seattle_Mariners','path' =>'//*[@id="mw-content-text"]/table[9]/tr[3]/td[1]','name' =>'Seattle Mariners'),18=>array('url'=>'https://en.wikipedia.org/wiki/Arizona_Diamondbacks','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]','name' =>'Arizona Diamondbacks'),19=>array('url'=>'https://en.wikipedia.org/wiki/Minnesota_Twins','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]','name' =>'Minnesota Twins'),20=>array('url'=>'https://en.wikipedia.org/wiki/Cleveland_Indians','path' =>'//*[@id="mw-content-text"]/table[5]/tr[3]/td[1]','name' =>'Cleveland Indians'),21=>array('url'=>'https://en.wikipedia.org/wiki/Colorado_Rockies','path' =>'//*[@id="mw-content-text"]/table[5]/tr[3]/td[1]','name' =>'Colorado Rockies'),22=>array('url'=>'https://en.wikipedia.org/wiki/San_Diego_Padres','path' =>'//*[@id="mw-content-text"]/table[9]/tr[3]/td[1]','name' =>'San Diego Padres'),23=>array('url'=>'https://en.wikipedia.org/wiki/Tampa_Bay_Rays','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]','name' =>'Tampa Bay Rays'),24=>array('url'=>'https://en.wikipedia.org/wiki/St._Louis_Cardinals','path' =>'//*[@id="mw-content-text"]/table[6]/tr[3]/td[1]','name' =>'St. Louis Cardinals'),25=>array('url'=>'https://en.wikipedia.org/wiki/Pittsburgh_Pirates','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]','name' =>'Pittsburgh Pirates'),26=>array('url'=>'https://en.wikipedia.org/wiki/Kansas_City_Royals','path' =>'//*[@id="mw-content-text"]/table[7]/tr[3]/td[1]','name' =>'Kansas City Royals'),27=>array('url'=>'https://en.wikipedia.org/wiki/Cincinnati_Reds','path' =>'//*[@id="mw-content-text"]/table[6]/tr[3]/td[1]','name' =>'Cincinnati Reds'),28=>array('url'=>'https://en.wikipedia.org/wiki/Milwaukee_Brewers','path' =>'//*[@id="mw-content-text"]/table[8]/tr[3]/td[1]','name' =>'Milwaukee Brewers'));
	
	#private $MLB=array(array('url'=>'https://en.wikipedia.org/wiki/Minnesota_Twins','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]','name' =>'Minnesota Twins'));
	
	private $NBA=array(0=>array('url'=>'https://en.wikipedia.org/wiki/Boston_Celtics','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Boston Celtics'),1=>array('url'=>'https://en.wikipedia.org/wiki/Brooklyn_Nets','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]/table','name' =>'Brooklyn Nets'),2=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Knicks','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'New York Knicks'),3=>array('url'=>'https://en.wikipedia.org/wiki/Philadelphia_76ers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Philadelphia 76ers'),4=>array('url'=>'https://en.wikipedia.org/wiki/Toronto_Raptors','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]/table','name' =>'Toronto Raptors'),5=>array('url'=>'https://en.wikipedia.org/wiki/Chicago_Bulls','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Chicago Bulls'),6=>array('url'=>'https://en.wikipedia.org/wiki/Cleveland_Cavaliers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Cleveland Cavaliers'),7=>array('url'=>'https://en.wikipedia.org/wiki/Detroit_Pistons','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Detroit Pistons'),8=>array('url'=>'https://en.wikipedia.org/wiki/Indiana_Pacers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Indiana Pacers'),9=>array('url'=>'https://en.wikipedia.org/wiki/Milwaukee_Bucks','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Milwaukee Bucks'),10=>array('url'=>'https://en.wikipedia.org/wiki/Atlanta_Hawks','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Atlanta Hawks'),11=>array('url'=>'https://en.wikipedia.org/wiki/Charlotte_Hornets','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Charlotte Hornets'),12=>array('url'=>'https://en.wikipedia.org/wiki/Miami_Heat','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Miami Heat'),13=>array('url'=>'https://en.wikipedia.org/wiki/Orlando_Magic','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Orlando Magic'),14=>array('url'=>'https://en.wikipedia.org/wiki/Washington_Wizards','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Washington Wizards'),15=>array('url'=>'https://en.wikipedia.org/wiki/Denver_Nuggets','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Denver Nuggets'),16=>array('url'=>'https://en.wikipedia.org/wiki/Minnesota_Timberwolves','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]/table','name' =>'Minnesota Timberwolves'),17=>array('url'=>'https://en.wikipedia.org/wiki/Oklahoma_City_Thunder','path' =>'//*[@id="mw-content-text"]/table[5]/tr[3]/td[1]/table','name' =>'Oklahoma City Thunder'),18=>array('url'=>'https://en.wikipedia.org/wiki/Portland_Trail_Blazers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Portland Trail Blazers'),19=>array('url'=>'https://en.wikipedia.org/wiki/Utah_Jazz','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Utah Jazz'),20=>array('url'=>'https://en.wikipedia.org/wiki/Golden_State_Warriors','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Golden State Warriors'),21=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Clippers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Los Angeles Clippers'),22=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Lakers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Los Angeles Lakers'),23=>array('url'=>'https://en.wikipedia.org/wiki/Phoenix_Suns','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'Phoenix Suns'),24=>array('url'=>'https://en.wikipedia.org/wiki/Sacramento_Kings','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Sacramento Kings'),25=>array('url'=>'https://en.wikipedia.org/wiki/Dallas_Mavericks','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]/table','name' =>'Dallas Mavericks'),26=>array('url'=>'https://en.wikipedia.org/wiki/Houston_Rockets','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Houston Rockets'),27=>array('url'=>'https://en.wikipedia.org/wiki/Memphis_Grizzlies','path' =>'//*[@id="mw-content-text"]/table[4]/tr[3]/td[1]/table','name' =>'Memphis Grizzlies'),28=>array('url'=>'https://en.wikipedia.org/wiki/New_Orleans_Pelicans','path' =>'//*[@id="mw-content-text"]/table[2]/tr[3]/td[1]/table','name' =>'New Orleans Pelicans'),29=>array('url'=>'https://en.wikipedia.org/wiki/San_Antonio_Spurs','path' =>'//*[@id="mw-content-text"]/table[3]/tr[3]/td[1]/table','name' =>'San Antonio Spurs'));
	
	private $NHL=array(0=>array('url'=>'https://en.wikipedia.org/wiki/Boston_Bruins','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Boston Bruins'),1=>array('url'=>'https://en.wikipedia.org/wiki/Buffalo_Sabres','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Buffalo Sabres'),2=>array('url'=>'https://en.wikipedia.org/wiki/Detroit_Red_Wings','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Detroit Red Wings'),3=>array('url'=>'https://en.wikipedia.org/wiki/Florida_Panthers','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Florida Panthers'),4=>array('url'=>'https://en.wikipedia.org/wiki/Montreal_Canadiens','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Montreal Canadiens'),5=>array('url'=>'https://en.wikipedia.org/wiki/Ottawa_Senators','path' =>'//*[@id="mw-content-text"]/table[2]','name' =>'Ottawa Senators'),6=>array('url'=>'https://en.wikipedia.org/wiki/Tampa_Bay_Lightning','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Tampa Bay Lightning'),7=>array('url'=>'https://en.wikipedia.org/wiki/Toronto_Maple_Leafs','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Toronto Maple Leafs'),8=>array('url'=>'https://en.wikipedia.org/wiki/Carolina_Hurricanes','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Carolina Hurricanes'),9=>array('url'=>'https://en.wikipedia.org/wiki/Columbus_Blue_Jackets','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Columbus Blue Jackets'),10=>array('url'=>'https://en.wikipedia.org/wiki/New_Jersey_Devils','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'New Jersey Devils'),11=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Islanders','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'New York Islanders'),12=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Rangers','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'New York Rangers'),13=>array('url'=>'https://en.wikipedia.org/wiki/Philadelphia_Flyers','path' =>'//*[@id="mw-content-text"]/table[2]','name' =>'Philadelphia Flyers'),14=>array('url'=>'https://en.wikipedia.org/wiki/Pittsburgh_Penguins','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Pittsburgh Penguins'),15=>array('url'=>'https://en.wikipedia.org/wiki/Washington_Capitals','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Washington Capitals'),16=>array('url'=>'https://en.wikipedia.org/wiki/Anaheim_Ducks','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Anaheim Ducks'),17=>array('url'=>'https://en.wikipedia.org/wiki/Arizona_Coyotes','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Arizona Coyotes'),18=>array('url'=>'https://en.wikipedia.org/wiki/Calgary_Flames','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Calgary Flames'),19=>array('url'=>'https://en.wikipedia.org/wiki/Edmonton_Oilers','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Edmonton Oilers'),20=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Kings','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Los Angeles Kings'),21=>array('url'=>'https://en.wikipedia.org/wiki/San_Jose_Sharks','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'San Jose Sharks'),22=>array('url'=>'https://en.wikipedia.org/wiki/Vancouver_Canucks','path' =>'//*[@id="mw-content-text"]/table[5]','name' =>'Vancouver Canucks'),23=>array('url'=>'https://en.wikipedia.org/wiki/Chicago_Blackhawks','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Chicago Blackhawks'),24=>array('url'=>'https://en.wikipedia.org/wiki/Colorado_Avalanche','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'Colorado Avalanche'),25=>array('url'=>'https://en.wikipedia.org/wiki/Dallas_Stars','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Dallas Stars'),26=>array('url'=>'https://en.wikipedia.org/wiki/Minnesota_Wild','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Minnesota Wild'),27=>array('url'=>'https://en.wikipedia.org/wiki/Nashville_Predators','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Nashville Predators'),28=>array('url'=>'https://en.wikipedia.org/wiki/St._Louis_Blues','path' =>'//*[@id="mw-content-text"]/table[4]','name' =>'St. Louis Blues'),29=>array('url'=>'https://en.wikipedia.org/wiki/Winnipeg_Jets','path' =>'//*[@id="mw-content-text"]/table[3]','name' =>'Winnipeg Jets'));
	
	private $NFL=array(0=>array('url'=>'https://en.wikipedia.org/wiki/Buffalo_Bills','path' =>'//*[@id="mw-content-text"]/table[6]/tr[2]/td[1]','name' =>'Buffalo Bills'),1=>array('url'=>'https://en.wikipedia.org/wiki/Miami_Dolphins','path' =>'//*[@id="mw-content-text"]/table[6]/tr[2]/td[1]','name' =>'Miami Dolphins'),2=>array('url'=>'https://en.wikipedia.org/wiki/New_England_Patriots','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'New England Patriots'),3=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Jets','path' =>'//*[@id="mw-content-text"]/table[4]/tr[2]/td[1]','name' =>'New York Jets'),4=>array('url'=>'https://en.wikipedia.org/wiki/Baltimore_Ravens','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Baltimore Ravens'),5=>array('url'=>'https://en.wikipedia.org/wiki/Cincinnati_Bengals','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Cincinnati Bengals'),6=>array('url'=>'https://en.wikipedia.org/wiki/Cleveland_Browns','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Cleveland Browns'),7=>array('url'=>'https://en.wikipedia.org/wiki/Pittsburgh_Steelers','path' =>'//*[@id="mw-content-text"]/table[5]/tr[2]/td[1]','name' =>'Pittsburgh Steelers'),8=>array('url'=>'https://en.wikipedia.org/wiki/Houston_Texans','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Houston Texans'),9=>array('url'=>'https://en.wikipedia.org/wiki/Indianapolis_Colts','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Indianapolis Colts'),10=>array('url'=>'https://en.wikipedia.org/wiki/Jacksonville_Jaguars','path' =>'//*[@id="mw-content-text"]/table[4]/tr[2]/td[1]','name' =>'Jacksonville Jaguars'),11=>array('url'=>'https://en.wikipedia.org/wiki/Tennessee_Titans','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Tennessee Titans'),12=>array('url'=>'https://en.wikipedia.org/wiki/Denver_Broncos','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Denver Broncos'),13=>array('url'=>'https://en.wikipedia.org/wiki/Kansas_City_Chiefs','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Kansas City Chiefs'),14=>array('url'=>'https://en.wikipedia.org/wiki/Oakland_Raiders','path' =>'//*[@id="mw-content-text"]/table[6]/tr[2]/td[1]','name' =>'Oakland Raiders'),15=>array('url'=>'https://en.wikipedia.org/wiki/San_Diego_Chargers','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'San Diego Chargers'),16=>array('url'=>'https://en.wikipedia.org/wiki/Dallas_Cowboys','path' =>'//*[@id="mw-content-text"]/table[4]/tr[2]/td[1]','name' =>'Dallas Cowboys'),17=>array('url'=>'https://en.wikipedia.org/wiki/New_York_Giants','path' =>'//*[@id="mw-content-text"]/table[5]/tr[2]/td[1]','name' =>'New York Giants'),18=>array('url'=>'https://en.wikipedia.org/wiki/Philadelphia_Eagles','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Philadelphia Eagles'),19=>array('url'=>'https://en.wikipedia.org/wiki/Washington_Redskins','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Washington Redskins'),20=>array('url'=>'https://en.wikipedia.org/wiki/Chicago_Bears','path' =>'//*[@id="mw-content-text"]/table[6]/tr[2]/td[1]','name' =>'Chicago Bears'),21=>array('url'=>'https://en.wikipedia.org/wiki/Detroit_Lions','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Detroit Lions'),22=>array('url'=>'https://en.wikipedia.org/wiki/Green_Bay_Packers','path' =>'//*[@id="mw-content-text"]/table[9]/tr[2]/td[1]','name' =>'Green Bay Packers'),23=>array('url'=>'https://en.wikipedia.org/wiki/Minnesota_Vikings','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Minnesota Vikings'),24=>array('url'=>'https://en.wikipedia.org/wiki/Atlanta_Falcons','path' =>'//*[@id="mw-content-text"]/table[4]/tr[2]/td[1]','name' =>'Atlanta Falcons'),25=>array('url'=>'https://en.wikipedia.org/wiki/Carolina_Panthers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Carolina Panthers'),26=>array('url'=>'https://en.wikipedia.org/wiki/New_Orleans_Saints','path' =>'//*[@id="mw-content-text"]/table[11]/tr[2]/td[1]','name' =>'New Orleans Saints'),27=>array('url'=>'https://en.wikipedia.org/wiki/Tampa_Bay_Buccaneers','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Tampa Bay Buccaneers'),28=>array('url'=>'https://en.wikipedia.org/wiki/Arizona_Cardinals','path' =>'//*[@id="mw-content-text"]/table[3]/tr[2]/td[1]','name' =>'Arizona Cardinals'),29=>array('url'=>'https://en.wikipedia.org/wiki/Los_Angeles_Rams','path' =>'//*[@id="mw-content-text"]/table[2]/tr[2]/td[1]','name' =>'Los Angeles Rams'),30=>array('url'=>'https://en.wikipedia.org/wiki/San_Francisco_49ers','path' =>'//*[@id="mw-content-text"]/table[7]/tr[2]/td[1]','name' =>'San Francisco 49ers'),31=>array('url'=>'https://en.wikipedia.org/wiki/Seattle_Seahawks','path' =>'//*[@id="mw-content-text"]/table[4]/tr[2]/td[1]','name' =>'Seattle Seahawks'));
   
    private function replace_spec_char($subject) {
     $_convertTable = array(
    '&amp;' => 'and',   '@' => 'at',    '©' => 'c', '®' => 'r', 'À' => 'a',
    'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'Å' => 'a', 'Æ' => 'ae','Ç' => 'c',
    'È' => 'e', 'É' => 'e', 'Ë' => 'e', 'Ì' => 'i', 'Í' => 'i', 'Î' => 'i',
    'Ï' => 'i', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
    'Ø' => 'o', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'Ý' => 'y',
    'ß' => 'ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', 'å' => 'a',
    'æ' => 'ae','ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
    'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o',
    'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
    'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'p', 'ÿ' => 'y', 'Ā' => 'a',
    'ā' => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ć' => 'c',
    'ć' => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Č' => 'c',
    'č' => 'c', 'Ď' => 'd', 'ď' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ē' => 'e',
    'ē' => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e',
    'ę' => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ğ' => 'g',
    'ğ' => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ĥ' => 'h',
    'ĥ' => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ī' => 'i',
    'ī' => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Į' => 'i', 'į' => 'i', 'İ' => 'i',
    'ı' => 'i', 'Ĳ' => 'ij','ĳ' => 'ij','Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'k',
    'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'l', 'ĺ' => 'l', 'Ļ' => 'l', 'ļ' => 'l',
    'Ľ' => 'l', 'ľ' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ł' => 'l', 'ł' => 'l',
    'Ń' => 'n', 'ń' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ň' => 'n', 'ň' => 'n',
    'ŉ' => 'n', 'Ŋ' => 'n', 'ŋ' => 'n', 'Ō' => 'o', 'ō' => 'o', 'Ŏ' => 'o',
    'ŏ' => 'o', 'Ő' => 'o', 'ő' => 'o', 'Œ' => 'oe','œ' => 'oe','Ŕ' => 'r',
    'ŕ' => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ś' => 's',
    'ś' => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Ş' => 's', 'ş' => 's', 'Š' => 's',
    'š' => 's', 'Ţ' => 't', 'ţ' => 't', 'Ť' => 't', 'ť' => 't', 'Ŧ' => 't',
    'ŧ' => 't', 'Ũ' => 'u', 'ũ' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ŭ' => 'u',
    'ŭ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ų' => 'u',
    'ų' => 'u', 'Ŵ' => 'w', 'ŵ' => 'w', 'Ŷ' => 'y', 'ŷ' => 'y', 'Ÿ' => 'y',
    'Ź' => 'z', 'ź' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ž' => 'z', 'ž' => 'z',
    'ſ' => 'z', 'Ə' => 'e', 'ƒ' => 'f', 'Ơ' => 'o', 'ơ' => 'o', 'Ư' => 'u',
    'ư' => 'u', 'Ǎ' => 'a', 'ǎ' => 'a', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ǒ' => 'o',
    'ǒ' => 'o', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ǘ' => 'u',
    'ǘ' => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǻ' => 'a',
    'ǻ' => 'a', 'Ǽ' => 'ae','ǽ' => 'ae','Ǿ' => 'o', 'ǿ' => 'o', 'ə' => 'e',
    'Ё' => 'jo','Є' => 'e', 'І' => 'i', 'Ї' => 'i', 'А' => 'a', 'Б' => 'b',
    'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ж' => 'zh','З' => 'z',
    'И' => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
    'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
    'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch','Ш' => 'sh','Щ' => 'sch',
    'Ъ' => '-', 'Ы' => 'y', 'Ь' => '-', 'Э' => 'je','Ю' => 'ju','Я' => 'ja',
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
    'ж' => 'zh','з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l',
    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
    'ш' => 'sh','щ' => 'sch','ъ' => '-','ы' => 'y', 'ь' => '-', 'э' => 'je',
    'ю' => 'ju','я' => 'ja','ё' => 'jo','є' => 'e', 'і' => 'i', 'ї' => 'i',
    'Ґ' => 'g', 'ґ' => 'g', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd',
    'ה' => 'h', 'ו' => 'v', 'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i',
    'ך' => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n',
    'נ' => 'n', 'ס' => 's', 'ע' => 'e', 'ף' => 'p', 'פ' => 'p', 'ץ' => 'C',
    'צ' => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't', '™' => 'tm',
);
    return strtr($subject, $_convertTable);
}
	
	
	public function htmlurl($url){
	 /*	$ch = curl_init();
		if($ch === false)
		{
			die('Failed to create curl object');
		}
		$timeout = 2;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)",
                "Accept-Language: en-us,en;q=0.5"
            ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$html = curl_exec($ch);
		curl_close($ch);
		*/
		$html = file_get_contents($url);
		libxml_use_internal_errors(true);
		$doc = new DOMDocument; 
		$doc->loadHTML($html);
		$xpath=new DOMXpath($doc);
		return $xpath;
	}
	public function xpath($xp,$path){
		$elements1 = $xp->query($path);
		return $elements1;
	}
	
	public function getteamid($name){
		$val=$this->fetch_array($this->query("SELECT address_book_group_id FROM address_book WHERE name like '%$name%'"));
		return $val['address_book_group_id']; 
	}
	
	
	public function save($Id,$res){
		foreach($res as $a){
			$this->query("INSERT INTO ttm_roster(team_id,  player_name) VALUES ('$Id','".mysql_real_escape_string($a)."')");
		}
	}
	
	private function rm_($str){
		$temp_str=explode(" ",$str);
		$tot=count($temp_str);
		$last_char=$temp_str[$tot-1];
		if(in_array($last_char,$this->rm1)){
			$temp_2=array_slice($temp_str,0,count($temp_str)-1);
			$new_str="";
			foreach($temp_2 as $a){
				$new_str.=$a." ";
			}
			return trim($new_str);
		}else{
			return $str;
		}
	}
	
	//NFL
	public function doNFL(){
	 global $result;
     for($i=0;$i<count($this->NFL);$i++){
		    $result=array();
		    echo $this->NFL[$i]['name'];
			echo "\n";
			$temp_xpath_element=$this->htmlurl($this->NFL[$i]['url']);
			for($j=1;$j<7;){
			$new_path_temp=substr($this->NFL[$i]['path'], 0, -2);
            $new_path=$new_path_temp."$j]";		
            $da=$this->xpath($temp_xpath_element,$new_path);
			foreach($da as $f){
				$r=explode("\n",$f->textContent);
			}
			$xx=count($result);
			foreach($r as $a){
				/* old code not working well 
 				echo utf8_decode($a);
				$words = preg_replace('/\d+/u', '', $a);
				$x=utf8_decode($words);
				$str = strtr( $x,$this->unwanted_array);
				*/
                $str=$this->remove_accents($a);
				$str=$this->rm_($str);
				$result[$xx]= trim(preg_replace("/[^a-zA-Z ]+/", "", $str));
				$xx++;
			}
			$j+=2;
			}
			$index=0;
			$result=array_values(array_filter($result));
			foreach($result as $a){
				if(in_array($a,$this->rm))
					unset($result[$index]);
			  $index++;
			}
			print_r(array_filter($result));
			
		$teamID=$this->getteamid($this->NFL[$i]['name']);
		if($teamID!="")
			$this->save($teamID,$result);
		else{
			$txt=$this->NFL[$i]['name'];
			$myfile = file_put_contents('logxxx.txt', $txt.PHP_EOL , FILE_APPEND);
			}
			
     }
	}
//MLB
public function doMLB(){
	 global $result;
     for($i=0;$i<count($this->MLB);$i++){
		    $result=array();
		    echo $this->MLB[$i]['name'];
			echo "\n";
			$temp_xpath_element=$this->htmlurl($this->MLB[$i]['url']);
			for($j=1;$j<=3;){
					$new_path_temp=substr($this->MLB[$i]['path'], 0, -2);
					echo $new_path=$new_path_temp."$j]";		
					$da=$this->xpath($temp_xpath_element,$new_path);
					foreach($da as $f){
						$r=explode("\n",$f->textContent);
					}
					$xx=count($result);
					foreach($r as $a){
						#echo utf8_decode($a);
						#$words = preg_replace('/\d+/u', '', $a);
						#$x=utf8_decode($words);
						#$str = strtr( $x,$this->unwanted_array);
						$str=utf8_decode($a);
						$str=$this->replace_spec_char($str);
						$str=$this->rm_($str);
						$result[$xx]= trim(preg_replace("/[^a-zA-Z ]+/", "", $str));
						$xx++;
					}
					$j+=2;
				}
				$index=0;
				$result=array_values(array_filter($result));
				foreach($result as $a){
					if(in_array($a,$this->rm))
						unset($result[$index]);
				  $index++;
				}
				print_r(array_filter($result));
			$teamID=$this->getteamid($this->MLB[$i]['name']);	
			echo $teamID."\n";
			if($teamID!="")
				$this->save($teamID,$result);
			else{
				$txt=$this->MLB[$i]['name'];
				$myfile = file_put_contents('logxxx.txt', $txt.PHP_EOL , FILE_APPEND);
				}
				
			}
	 }
	 
	//NHL
	public function doNHL(){
		global $result;
		for($i=0;$i<count($this->NHL);$i++){
		    $result=array();
		    echo $this->NHL[$i]['name'];
			echo "\n";
			$temp_xpath_element=$this->htmlurl($this->NHL[$i]['url']);
			for($j=2;$j<=24;){
					$new_path=$this->NHL[$i]['path'].'/tr['.$j.']/td[2]/span[2]/span/a';		
					#echo $new_path;
					$da=$this->xpath($temp_xpath_element,$new_path);
					foreach($da as $f){
						$r=explode("\n",$f->textContent);
					}
					$xx=count($result);
					foreach($r as $a){
						#echo utf8_decode($a);
						#$words = preg_replace('/\d+/u', '', $a);
						#$x=utf8_decode($words);
						#$str = strtr( $x,$this->unwanted_array);
						$str=$this->remove_accents($a);
						$str=$this->rm_($str); 
						$result[$xx]= trim(preg_replace("/[^a-zA-Z ]+/", "", $str));
						$xx++;
					}
					$j+=1;
				}
				$index=0;
				$result=array_unique(array_values(array_filter($result)));
				foreach($result as $a){
					if(in_array($a,$this->rm))
						unset($result[$index]);
				  $index++;
				}
				print_r(array_unique(array_filter($result)));
			$teamID=$this->getteamid($this->NHL[$i]['name']);	
			if($teamID!="")
				$this->save($teamID,$result);
			else{
				$txt=$this->NHL[$i]['name'];
				$myfile = file_put_contents('logxxx.txt', $txt.PHP_EOL , FILE_APPEND);
				}
				
	    } 
    }
	
	//NBA
	public function doNBA(){
		global $result;
		for($i=0;$i<count($this->NBA);$i++){
		    $result=array();
		    echo $this->NBA[$i]['name'];
			echo "\n";
			$temp_xpath_element=$this->htmlurl($this->NBA[$i]['url']);
			for($j=2;$j<=20;){
					$new_path=$this->NBA[$i]['path'].'/tr['.$j.']/td[3]/a';		
					#echo $new_path;
					$da=$this->xpath($temp_xpath_element,$new_path);
					foreach($da as $f){
						$r=explode("\n",$f->textContent);
					}
					$xx=count($result);
					foreach($r as $a){
						#echo utf8_decode($a);
						#$words = preg_replace('/\d+/u', '', $a);
						#$x=utf8_decode($words);
						#$str = strtr( $x,$this->unwanted_array);
						$str=$this->remove_accents($a);
						$str=$this->rm_($str); 
						$result[$xx]=trim(preg_replace("/[^a-zA-Z ]+/", "", $str));
						$xx++;
					}
					$j+=1;
				}
				$index=0;
				$result=array_unique(array_values(array_filter($result)));
				foreach($result as $a){
					if(in_array($a,$this->rm))
						unset($result[$index]);
				  $index++;
				}
				print_r(array_unique(array_filter($result)));
			$teamID=$this->getteamid($this->NBA[$i]['name']);	
			if($teamID!="")
				$this->save($teamID,$result);
			else{
				$txt=$this->NBA[$i]['name'];
				$myfile = file_put_contents('logxxx.txt', $txt.PHP_EOL , FILE_APPEND);
				}
	    } 
    }
	
}
$r=new Roster();
if($r->clear()=="y"){
	$r->doNFL();
	#$r->doMLB();
	#$r->doNHL();
	#$r->doNBA();
}else{
	echo "Cannt delete files please look at the code again";
}
?>

