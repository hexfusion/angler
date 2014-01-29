<?php
/*
CaRP LE v4.0.11
Copyright (c) 2002-8 Antone Roundy

All rights reserved
This program may not be redistributed in whole or in part without written
permission from the copyright owner.

http://www.geckotribe.com/rss/carp/
Installation & Configuration Manual: http://carp.docs.geckotribe.com/
Also available as a remotely hosted service for sites that cannot run
scripts. See http://www.geckotribe.com/rss/jawfish/
*/

class RSSParser {
	var $insideitem=0;
	var $insidechannel=0;
	var $tag='';
	var $author='';
	var $dccreator='';
	var $title='';
	var $link='';
	var $pubdate='';
	var $dcdate='';
	var $ctitle='';
	var $cdescription='';
	var $clink='';
	var $clastbuilddate='';
	var $cpubdate='';
	var $cdcdate='';
	var $itemcount=0;
	var $itemindex=0;
	var $top='';
	var $bottom='';
	var $body='';
	var $showit;
	var $tagpairs;
	var $filterin;
	var $filterout;
	var $filterinfield;
	var $filteroutfield;
	var $linktargets=array('',' target="_blank"',' target="_top"');
	var $channelborder;
	var $channelaorder;
	var $itemorder;
	var $formatCheck;
	var $isatom;
	var $encodingout='';
	
	function SetItemOrder($iord) {
		$this->itemorder=explode(',',preg_replace('/[^a-z0-9,]/','',strtolower($iord)));
	}

	
	function CheckFilter($lookfor,$field) {
		if (!empty($field)) {
			if (strpos(strtolower($this->$field),$lookfor)!==false) return 1;
		} else {
			if (strpos(strtolower($this->title),$lookfor)!==false) return 1;
		}
		return 0;
	}

	function Truncate(&$text,$max,$after,$afterlen) {
		if (($max>0)&&(CarpStrLen(preg_replace("/<.*?>/",'',$text))>$max)) {
			$j=strlen($text);
			$truncmax=$max-$afterlen;
			$isUTF8=$this->encodingout=='UTF-8';
			$out='';
			for ($i=0,$len=0;($len<$truncmax)&&($i<$j);$i++) {
				switch($text{$i}) {
				case '<':
					for ($k=$i+1;($k<$j)&&($text{$k}!='>');$k++) {
						if (($text{$k}=='"')||($text{$k}=="'")) {
							if ($m=strpos($text,$text{$k},$k+1)) $k=$m;
							else $k=$j;
						}
					}
					if ($text{$k}=='>') $out.=substr($text,$i,$k+1-$i);
					$i=$k;
					break;
				case '&':
					if ($text{$i+1}=='#') {
						if ($text{$i+2}=='x') {
							$matchset='/[0-9]/';
							$start=$i+3;
						} else {
							$matchset='/[0-9a-fA-F]/';
							$start=$i+2;
						}
					} else {
						$matchset='/[a-zA-Z]/';
						$start=$i+1;
					}
					$valid=0;
					for ($k=$start;$k<$j;$k++) {
						$c=$text{$k};
						if (($c==';')||($c==' ')) {
							if ($k>$start) $valid=1;
							if ($c==' ') $k--;
							break;
						} else if (!preg_match($matchset,$c)) break;
					}
					if ($valid) {
						$out.=substr($text,$i,$k+1-$i);
						$i=$k;
					} else $out.='&amp;';
					$len++;
					break;
				default:
					if ($isUTF8) {
						$val=ord($text{$i});
						$bytes=($val<=0x7F)?1:(($val<=0xDF)?2:(($val<=0xEF)?3:4));
						$out.=substr($text,$i,$bytes);
						$i+=($bytes-1);
					} else $out.=$text{$i};
					$len++;
				}
			}
			$did=$i<$j;
			$text=$out.($did?$after:'');
		} else $did=0;
		return $did;
	}
	
	function CleanURL($val) {
		$doit=1;
		if ($this->insideitem) {
			if ($this->insideitem==2) $data=&$this->link;
		} else if ($this->insidechannel==2) $data=&$this->clink;
		else $doit=0;

		if ($doit) $data=preg_replace("/<[^>]*>/",'',str_replace('"','&quot;',str_replace('&','&amp;',trim($data))));
	}

	function FormatLink($title,$link,$class,$style,$maxtitle,$atrunc,$atrunclen,$btitle,$atitle,$deftitle,$titles,$attrs) {
		global $carpconf;

		$fulltitle=$title=trim(preg_replace("/<.*?>/",'',$title));
		$didTrunc=$this->Truncate($title,$maxtitle,$atrunc,$atrunclen);
		if (!isset($title{0})) $title=$deftitle;
		
		$rv=$btitle.
			(isset($link{0})?(
				"<a href=\"$link\"".$this->linktargets[$carpconf['linktarget']].
				((($titles&&$didTrunc)||($titles==2))?" title=\"".str_replace('"','&quot;',$fulltitle)."\"":'')
			):(strlen($class.$style)?'<span':'')).
			(isset($class{0})?(' class="'.$class.'"'):'').
			(isset($style{0})?(' style="'.$style.'"'):'').
			((isset($link{0})&&isset($attrs{0}))?" $attrs ":'').
			(strlen($link.$class.$style)?'>':'').
			$title.
			(isset($link{0})?'</a>':(strlen($class.$style)?'</span>':'')).
			$atitle.$carpconf['afield'];
		return $rv;
	}


	function FormatDate($val,$ci) {
		global $carpconf;
		$rv=($val>0)?($carpconf["b$ci".'date'].date($carpconf[$ci.'dateformat'],$val).$carpconf["a$ci".'date'].$carpconf['afield']):'';
		return $rv;
	}
	
	function FormatSimpleField($val,$ci,$name,$fixamp=0) {
		global $carpconf;
		if ($fixamp) $val=str_replace('&','&amp;',$val);
		$rv=isset($val{0})?($carpconf["b$ci$name"].$val.$carpconf["a$ci$name"].$carpconf['afield']):'';
		return $rv;
	}
	
	
	function XMLFormatError($show=0) {
		switch ($this->formatCheck) {
		case 3: $rv='CaRP LE cannot process Atom 0.3 feeds.'; break;
		case 10: $rv='CaRP LE cannot process Atom 1.0 feeds.'; break;
		case -1: $rv='Unknown RDF-based format or non-standard RSS element name prefix.'; break;
		case -11:
		case -12: $rv='Unknown feed format.'; break;
		case -20: $rv='This appears to be an HTML webpage, not a feed.'; break;
		case -100:
		case -101: $rv='Unknown document format.'; break;
		default: $rv='';
		}
		if (isset($rv{0})&&$show) CarpError($rv,'unknown-format');
		return " - $rv";
	}
	
	function CheckFormat($tagName,&$attrs) {
		if (strpos($tagName,':')) {
			list($prefix,$name)=explode(':',$tagName);
			switch ($name) {
			case 'RDF':
				foreach ($attrs as $k=>$v) {
					if ((strpos($k,'XMLNS')===0)&&(strpos($v,'http://purl.org/rss/')===0)) {
						$this->formatCheck=1;
						break;
					}
				}
				if (!$this->formatCheck) $this->formatCheck=-1;
				break;
			case 'feed':
				switch ($attrs["XMLNS:$prefix"]) {
				case 'http://www.w3.org/2005/Atom': $this->formatCheck=10; break;
				case 'http://purl.org/atom/ns#': $this->formatCheck=3; break;
				default: $this->formatCheck=-11;
				}
				break;
			default: $this->formatCheck=-100;
			}
		} else {
			switch($tagName) {
			case 'RSS': $this->formatCheck=2; break;
			case 'HTML': $this->formatCheck=-20; break;
			case 'FEED':
				switch ($attrs["XMLNS"]) {
				case 'http://www.w3.org/2005/Atom': $this->formatCheck=10; break;
				case 'http://purl.org/atom/ns#': $this->formatCheck=3; break;
				default: $this->formatCheck=-12;
				}
				break;					
			default: $this->formatCheck=-101;
			}
		}
		switch($this->formatCheck) {
		case 3: case 10: $this->isatom=1; break;
		default: $this->isatom=0;
		}
	}
	
	
	function startElement($parser,$tagName,$attrs) {
		global $carpconf;
		if (!$this->formatCheck) $this->CheckFormat($tagName,$attrs);

			$this->tag=$tagName;
			if ($this->insidechannel) $this->insidechannel++;
			if ($this->insideitem) $this->insideitem++;
			if ($tagName=="ITEM") {
				$this->insideitem=1;
				$this->title=$this->author=$this->dccreator=$this->link=$this->pubdate=$this->dcdate='';
			} else if ($tagName=="CHANNEL") {
				$this->insidechannel=1;
				$this->ctitle=$this->clink=$this->clastbuilddate=$this->cpubdate=$this->cdcdate='';
			}
	}

	function ProcessDataField($name,$is_channel=0) {
		global $carpconf;
		$rv='';
		if ($is_channel) {
			switch ($name) {
			case 'link':
			case 'title':
				$rv.=$this->FormatLink($this->ctitle,(($name=='link')?$this->clink:''),
				$carpconf['clinkclass'],$carpconf['clinkstyle'],$carpconf['maxctitle'],$carpconf['atruncctitle'],$carpconf['atruncctitlelen'],
				$carpconf['bctitle'],$carpconf['actitle'],'',$carpconf['clinktitles'],$carpconf['clink_attrs']); break;
			case 'url': $rv.=$this->FormatSimpleField($this->clink,'c','url',1); break;
			case 'date': $rv.=$this->FormatDate(CarpDecodeDate(
				isset($this->cpubdate{0})?$this->cpubdate:
					(isset($this->clastbuilddate{0})?$this->clastbuilddate:$this->cdcdate)),'c'); break;
			}
		} else {
			switch ($name) {
			case 'link':
			case 'title':
				$rv.=$this->FormatLink($this->title,(($name=='link')?$this->link:''),
				$carpconf['ilinkclass'],$carpconf['ilinkstyle'],$carpconf['maxititle'],$carpconf['atruncititle'],$carpconf['atruncititlelen'],
				$carpconf['bilink'],$carpconf['ailink'],$carpconf['defaultititle'],$carpconf['ilinktitles'],$carpconf['ilink_attrs']); break;
			case 'url': $rv.=$this->FormatSimpleField($this->link,'i','url',1); break;
			case 'author': $rv.=$this->FormatSimpleField(isset($this->dccreator{0})?$this->dccreator:$this->author,'i','author'); break;
			case 'date': $rv.=$this->FormatDate($this->pubdate,'i'); break;
			}
		}
		return $rv;
	}

	function endElement($parser,$tagName) {
		global $carpconf;
		
		if ($this->tag=='LINK') $this->CleanURL($tagName);
		
		array_pop($this->xmlbase);
		
			if ($tagName=="ITEM") {
				if ($this->itemcount<$carpconf['maxitems']) {
					$filterblock=0;

					if (count($this->filterin)) {
						$filterblock=1;
						for ($i=count($this->filterin)-1;$i>=0;$i--) {
							if ($this->CheckFilter($this->filterin[$i],$this->filterinfield[$i])) {
								$filterblock=0;
								break;
							}
						}
					}
					if (count($this->filterout)&&!$filterblock) {
						for ($i=count($this->filterout)-1;$i>=0;$i--) {
							if ($this->CheckFilter($this->filterout[$i],$this->filteroutfield[$i])) {
								$filterblock=1;
								break;
							}
						}
					}
					if (!$filterblock) {
							$thisitem=$carpconf['bi'];
							
							$this->pubdate=CarpDecodeDate(isset($this->pubdate{0})?$this->pubdate:$this->dcdate);

							for ($ioi=0;$ioi<count($this->itemorder);$ioi++) $thisitem.=$this->ProcessDataField($this->itemorder[$ioi]);
							$thisitem.=$carpconf['ai'];
							$this->itemcount++;
							if ($this->showit) $this->body.=$thisitem.$carpconf['aitem'];
							else $this->body.=(
									$this->pubdate?($this->pubdate+($carpconf['timeoffset']*60)):(($cdate=CarpDecodeDate($this->cdcdate))?
										($cdate+($carpconf['timeoffset']*60)-$this->itemcount):(($carpconf['lastmodified']>0)?($carpconf['lastmodified']-$this->itemcount):0)
									)
								).
								': :'.preg_replace("/[\r\n]/",' ',$thisitem).$carpconf['aitem'];
					}
				}
				$this->insideitem=0;
				$this->itemindex++;
			} else if ($tagName=="CHANNEL") {
				$this->insidechannel=0;
			}
			if ($this->insidechannel) $this->insidechannel--;
			if ($this->insideitem) $this->insideitem--;
	}

	function DoEndChannel(&$data,&$order,&$b,&$a) {
		global $carpconf;
		for ($coi=0;$coi<count($order);$coi++) $data.=$this->ProcessDataField($order[$coi],1);
		if (isset($data{0})) $data=$b.$data.$a;
		if (!$this->showit) $data=preg_replace("/\n/",' ',$data);
	}
		
	function characterData($parser,$data) {
		global $carpconf;
		if ($this->insideitem) {
			if ($this->itemcount==$carpconf['maxitems']) return;

			if ($this->insideitem==2) {
				switch ($this->tag) {
				case "TITLE": $this->title.=$data; break;
				case "LINK": $this->link.=$data; break;
				case "PUBDATE": $this->pubdate.=$data; break;
				case "DC:DATE": $this->dcdate.=$data; break;
				case "AUTHOR": $this->author.=$data; break;
				case "DC:CREATOR": $this->dccreator.=$data; break;
				}
			}
		} else if ($this->insidechannel==2) {
			switch ($this->tag) {
			case "TITLE": $this->ctitle.=$data; break;
			case "LINK": $this->clink.=$data; break;
			case "LASTBUILDDATE": $this->clastbuilddate.=$data; break;
			case "PUBDATE": $this->cpubdate.=$data; break;
			case "DC:DATE": $this->cdcdate.=$data; break;
			}
		}
	}
	
}

function CarpDecodeDate($val) {
	global $carpconf;
	if (isset($val{0})) {
		if (
			(($rv=strtotime($val))==-1)&&
			(($rv=strtotime(preg_replace("/([0-9]+\-[0-9]+\-[0-9]+)T([0-9:]*)(\\.[0-9]*)?(?:Z|([-+][0-9]{1,2}):([0-9]{2}))/","$1 $2 $4$5",$val)))==-1)
		) {
			$thisyear=date('Y');
			if (($rv=strtotime(preg_replace("/( [0-9]{1,2}:[0-9]{2})/",", $thisyear $1",$val)))==-1) $rv=0;
		}
	} else $rv=0;
	return $rv?($rv+($carpconf['timeoffset']*60)):0;
}

function OpenRSSFeed($url) {
	global $carpconf,$carpversion,$CarpRedirs;
	
	$carpconf['lastmodified']='';
	if (strtolower(substr($url,0,7))=='http://') {
		if (isset($carpconf['proxyserver']{0})) {
			$urlparts=parse_url($carpconf['proxyserver']);
			$therest=$url;
		} else {
			$urlparts=parse_url($url);
			$therest=$urlparts['path'].(isset($urlparts['query'])?('?'.$urlparts['query']):'');
		}
		$domain=$urlparts['host'];
		$port=isset($urlparts['port'])?$urlparts['port']:80;
		$fp=fsockopen($domain,$port,$errno,$errstr,$carpconf['timeout']);
		if ($fp) {
			fputs($fp,"GET $therest HTTP/1.0\r\n".
				($carpconf['sendhost']?"Host: $domain\r\n":'').
				(isset($carpconf['proxyauth']{0})?('Proxy-Authorization: Basic '.base64_encode($carpconf['proxyauth']) ."\r\n"):'').
				(isset($carpconf['basicauth']{0})?('Authorization: Basic '.base64_encode($carpconf['basicauth']) ."\r\n"):'').
				"User-Agent: CaRP/$carpversion\r\n\r\n");
			while ((!feof($fp))&&preg_match("/[^\r\n]/",$header=fgets($fp,1000))) {
				if (strtolower(substr($header,0,9))=='location:') {
					fclose($fp);
					if (count($CarpRedirs)<$carpconf['maxredir']) {
						$loc=trim(substr($header,9));
						if (strtolower(substr($loc,0,7))!='http://') {
							if (isset($carpconf['proxyserver']{0})) {
								$redirparts=parse_url($url);
								$loc=$redirparts['scheme'].'://'.$redirparts['host'].(isset($redirparts['port'])?(':'.$redirparts['port']):'').$loc;
							} else $loc="http://$domain".(($port==80)?'':":$port").$loc;
						}
						for ($i=count($CarpRedirs)-1;$i>=0;$i--) if (!strcmp($loc,$CarpRedirs[$i])) {
							CarpError('Redirection loop detected. Giving up.','redirection-loop');
							return 0;
						}
						$CarpRedirs[count($CarpRedirs)]=$loc;
						return OpenRSSFeed($loc);
					} else {
						CarpError('Too many redirects. Giving up.','redirection-too-many');
						return 0;
					}
				} else if (strtolower(substr($header,0,14))=='last-modified:')
					$carpconf['lastmodified']=CarpDecodeDate(substr($header,14));
			}
		} else CarpError("$errstr ($errno)",'connection-failed');
	} else if ($fp=fopen($url,'r')) {
		if ($stat=fstat($fp)) $carpconf['lastmodified']=$stat['mtime'];
	} else CarpError("Failed to open file: $url",'local-file-open-failed');
	return $fp;
}

function CarpCacheUpdatedMysql() {
	global $carpconf;
	$rv=-1;
	CarpCacheMysqlConnect();
	if (CarpParseMySQLPath($carpconf['cachefile'],$which,$key)) {
		if ($r=CarpMySQLQuery('select updated from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' where id="'.addslashes($key).'"')) {
			if (mysql_num_rows($r)) $rv=mysql_result($r,0);
			else $rv=0;
			mysql_free_result($r);
		} else CarpError('Database error attempting to check cache update time.','database-error');
	} else CarpError('Invalid cache identifier checking cache update time.','cache-not-found');
	return $rv;
}

function CarpCacheUpdatedFile($f) {
	global $carpconf;
	if ($s=fstat($f)) $rv=$s['mtime'];
	else {
		$rv=-1;
		CarpError('Can\'t stat cache file.','cache-file-access');
		fclose($f);
	}
	return $rv;
}

function CarpSaveCache($f,$data) {
	global $carpconf;
	switch($carpconf['cache-method']) {
	case 'mysql': if (CarpParseMySQLPath($carpconf['cachefile'],$which,$key)) {
			if (!CarpMySQLQuery('update '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' set updated='.time().',cache="'.addslashes($data).'" where id="'.addslashes($key).'"'))
				CarpError('Database error attempting to cache formatted data.','database-error');
			} else CarpError('Invalid cache indentifier saving cache.','cache-not-found');
		break;
	default: fwrite($f,$data); break;
	}
}

function CarpOpenCacheWriteMySQL() {
	global $carpconf;
	CarpCacheMysqlConnect();
	$rv=0;
	if (($a=CarpCacheUpdatedMysql())>=0) {
		if ($r=CarpMySQLQuery('select GET_LOCK("'.$carpconf['cachefile'].'",'.$carpconf['mysql-lock-timeout'].')')) {
			if (mysql_result($r,0)+0) {
				$b=CarpCacheUpdatedMysql();
				if ($a!=$b) CarpMySQLQuery('select RELEASE_LOCK("'.$carpconf['cachefile'].'")');
				else {
					CarpParseMySQLPath($carpconf['cachefile'],$rv,$key);
					$rv++;
					if (!$b) CarpTouchCache($carpconf['cachefile']);
				}
			}
		} else $rv=-1;
	} else $rv=-1;
	if ($rv==-1) CarpError('Failed to access database cache record.','cache-prepare-failed'); 
	return $rv;
}

function CarpOpenCacheWriteFile() {
	global $carpconf;
	$rv=0;
	if (!file_exists($carpconf['cachefile'])) touch($carpconf['cachefile']);
	if ($f=fopen($carpconf['cachefile'],'r+')) {
		if ($a=CarpCacheUpdatedFile($f)) {
			flock($f,LOCK_EX); // ignore result--doesn't work for all systems and situations
			clearstatcache();
			if ($b=CarpCacheUpdatedFile($f)) {
				if ($a!=$b) {
					flock($f,LOCK_UN);
					fclose($f);
				} else $rv=$f;
			} else $rv=-1;
		} else $rv=-1;
	} else $rv=-1;
	if ($rv==-1) {
		CarpError("Can't open cache file.",'cache-prepare-failed');
		if ($f) fclose($f);
	}
	return $rv;
}

function OpenCacheWrite() {
	switch($GLOBALS['carpconf']['cache-method']) {
	case 'mysql': $rv=CarpOpenCacheWriteMySQL(); break; 
	default: $rv=CarpOpenCacheWriteFile(); break; 
	}
	return $rv;
}

function CloseCacheWrite($f) {
	global $carpconf;
	switch($carpconf['cache-method']) {
	case 'mysql': CarpMySQLQuery('select RELEASE_LOCK("'.$carpconf['cachefile'].'")'); break; 
	default: ftruncate($f,ftell($f));
		fflush($f);
		flock($f,LOCK_UN);
		fclose($f);
	}
	$carpconf['mtime']=time();
}

function DoCacheRSSFeed($data,$cache='') {
	global $carpconf;
	if (isset($cache{0})) {
		$savecache=$carpconf['cachefile'];
		$carpconf['cachefile']=$cache;
	}
	if (($outf=OpenCacheWrite())>0) {
		switch($carpconf['cache-method']) {
		case 'mysql':
			CarpParseMySQLPath($carpconf['cachefile'],$which,$key);
			if (!CarpMySQLQuery('update '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' set updated='.time().',cache="'.addslashes($data).
				'" where id="'.$key.'"')
			) CarpError('Database error attempting to cache feed.','database-error');
			break;
		default: fwrite($outf,$data);
		}
		CloseCacheWrite($outf);
	}
	if (isset($cache{0})) $carpconf['cachefile']=$savecache;
}

function CacheRSSFeed($url) {
	$d='';
	if (substr($url,0,8)=='grouper:') $d=GrouperGetCache(substr($url,8))?$GLOBALS['grouperrawdata']:'';
	else if ($f=OpenRSSFeed($url)) {
		while ($l=fread($f,4000)) $d.=$l;
		fclose($f);
	}
	if (isset($d{0})) DoCacheRSSFeed($d);
}

function CarpReadData($fp) {
	global $carpconf;
	return $carpconf['fixentities']?
		preg_replace("/&(?!lt|gt|amp|apos|quot|#[0-9]+|#x[0-9a-f]+)(.*\b)/is","&amp;\\1\\2",preg_replace("/\\x00/",'',fread($fp,4096))):
		preg_replace("/\\x00/",'',fread($fp,4096));
}

function CarpStrLen($s) {
	if (strtoupper($GLOBALS['carpconf']['encodingout'])=='UTF-8') {
		for ($i=$len=0,$j=strlen($s);$i<$j;$len++) {
			$val=ord($s{$i});
			$i+=($val<=0x7F)?1:(($val<=0xDF)?2:(($val<=0xEF)?3:4));
		}
	} else $len=strlen($s);
	return $len;
}

function GetRSSFeed($url,$cache,$showit) {
	global $carpconf,$CarpRedirs;

	MyForceConf();
	
	
	$carpconf['atruncititlelen']=CarpStrLen($carpconf['atruncititle']);
	$carpconf['atruncctitlelen']=CarpStrLen($carpconf['atruncctitle']);
	
	$rss_parser=new RSSParser();
	$rss_parser->showit=$showit;
	$rss_parser->channelborder=explode(',',preg_replace('/[^a-z0-9,]/','',strtolower($carpconf['cborder'])));
	$rss_parser->channelaorder=explode(',',preg_replace('/[^a-z0-9,]/','',strtolower($carpconf['caorder'])));
	$rss_parser->SetItemOrder($carpconf['iorder']);
	$rss_parser->formatCheck=0;
	
	if (preg_match("/[^0-9]/",$carpconf['linktarget'])) $rss_parser->linktargets[$carpconf['linktarget']]=' target="'.$carpconf['linktarget'].'"';
	$rss_parser->filterinfield=array();
	if (isset($carpconf['filterin']{0})) {
		$rss_parser->filterin=explode('|',strtolower($carpconf['filterin']));
		for ($i=count($rss_parser->filterin)-1;$i>=0;$i--) {
			if (strpos($rss_parser->filterin[$i],':')!==false)
				list($rss_parser->filterinfield[$i],$rss_parser->filterin[$i])=explode(':',$rss_parser->filterin[$i],2);
			else $rss_parser->filterinfield[$i]='';
		}
	} else $rss_parser->filterin=array();
	$rss_parser->filteroutfield=array();
	if (isset($carpconf['filterout']{0})) {
		$rss_parser->filterout=explode('|',strtolower($carpconf['filterout']));
		for ($i=count($rss_parser->filterout)-1;$i>=0;$i--) {
			if (strpos($rss_parser->filterout[$i],':')!==false)
				list($rss_parser->filteroutfield[$i],$rss_parser->filterout[$i])=explode(':',$rss_parser->filterout[$i],2);
			else $rss_parser->filteroutfield[$i]='';
		}
	} else $rss_parser->filterout=array();

	$fromfp=0;
	if (substr($url,0,6)=='mysql:') $data=CarpGetCache($url);
	else if (substr($url,0,8)=='grouper:') $data=GrouperGetCache(substr($url,8))?$GLOBALS['grouperrawdata']:'';
	else $fromfp=1;
	if ((!$fromfp)||($fp=OpenRSSFeed($url))) {
		if ($fromfp) $data=CarpReadData($fp);
		$data=preg_replace('/^[^<]+/','',$data);
		$encodings_internal=array('ISO-8859-1','UTF-8','US-ASCII');
		if (isset($carpconf['encodingin']{0})) $encodingin=$carpconf['encodingin'];
		else $encodingin=preg_match("/^<\?xml\b.*?\bencoding=(\"|')(.*?)(\"|')/",$data,$matches)?
			strtoupper($matches[2]):'UTF-8';
		$encodingquestion=0;
		if (!in_array($encodingin,$encodings_internal)) {
				$actualencoding=$encodingin;
				$encodingin='UTF-8';
				$encodingquestion=1;
			}

		$xml_parser=xml_parser_create(strtoupper($encodingin));
		if (isset($carpconf['encodingout']{0})) {
			xml_parser_set_option($xml_parser,XML_OPTION_TARGET_ENCODING,$carpconf['encodingout']);
		}
		
		xml_set_object($xml_parser,$rss_parser);
		xml_set_element_handler($xml_parser,"startElement","endElement");
		xml_set_character_data_handler($xml_parser,"characterData");
		$CarpRedirs=array();

		$rss_parser->xmlbase=array(($url{strlen($url)-1}=='/')?$url:substr($url,0,strrpos($url,'/')+1));
		while (isset($data{0})||($fromfp&&($data=CarpReadData($fp)))) {
			if (!xml_parse($xml_parser,$data,$fromfp?feof($fp):1)) {
				CarpError("XML error: ".xml_error_string(xml_get_error_code($xml_parser))." at line ".xml_get_current_line_number($xml_parser).
					($encodingquestion?(". The free version of CaRP is unable to process this feed's encoding ($actualencoding). ".
						"Upgrading will likely solve this problem."):'').
						$rss_parser->XMLFormatError()
					,'xml-error');
				if ($fromfp) fclose($fp);
				xml_parser_free($xml_parser);
				unset($rss_parser);
				return;
			}
			$data='';
		}
		if ($fromfp) fclose($fp);

			if (isset($rss_parser->channelborder[0]{0})) $rss_parser->DoEndChannel($rss_parser->top,$rss_parser->channelborder,$carpconf['bcb'],$carpconf['acb']);
			if (isset($rss_parser->channelaorder[0]{0})) $rss_parser->DoEndChannel($rss_parser->bottom,$rss_parser->channelaorder,$carpconf['bca'],$carpconf['aca']);
		$data=($showit?($rss_parser->top.$carpconf['bitems']):('cb: :'.$rss_parser->top."\n".'ca: :'.$rss_parser->bottom."\n")).
			$rss_parser->body.
			($showit?($carpconf['aitems'].$rss_parser->bottom.$carpconf['poweredby']):'');

		if ($showit) {
			if ($carpconf['shownoitems']&&!$rss_parser->itemcount) CarpOutput($carpconf['noitems']);
			else CarpOutput($data);
			if (isset($rss_parser)&&!$rss_parser->itemcount) $rss_parser->XMLFormatError(1);
		}
		if ($cache) {
			if (($cfp=OpenCacheWrite())>0) {
				if ($carpconf['shownoitems']&&!$rss_parser->itemcount) CarpSaveCache($cfp,$carpconf['noitems']);
				else CarpSaveCache($cfp,$data);
				CloseCacheWrite($cfp);
			}
		}
		xml_parser_free($xml_parser);
	} else if ($showit&&isset($carpconf['cachefile']{0})) CarpOutput(CarpGetCache($carpconf['cachefile']));
	else if ($showit) CarpError('Can\'t open remote newsfeed.','feed-access-failed',0);
}

return;
?>
