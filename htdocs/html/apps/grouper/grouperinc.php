<?php
/*
Grouper GPL v1.4.2
Copyright (c) 2003-4 Antone Roundy

This program may be copied, modified and redistributed under the terms of the
GNU General Public License. This program is distributed with NO WARRANTY
WHATSOEVER, including the implied warranty of merchantability or fitness for
a particular purpose.
See http://www.gnu.org/copyleft/gpl.html for details.

A commercial version of the program with additional features is available
via our website.

http://www.geckotribe.com/rss/grouper/
Installation & Configuration Manual: http://www.geckotribe.com/rss/grouper/manual/
*/

function GrouperStripHTML(&$datain,&$dataout,$zapbad=1) {
	$dataout=trim(preg_replace("#<(.*?)>#s",($zapbad?'':"&lt;\\1&gt;"),$datain));
}


function GrouperTruncate(&$datain,&$dataout,$length,$postfix='...') {
	if ($length&&(strlen($datain)>$length)) 
		$dataout=substr($datain,0,$length-strlen($postfix)).$postfix;
	else $dataout=$datain;
}

function GrouperBuildQuery($searchterms) {
	global $grouperconf;
	global $groupersources;
	if (is_array($groupersources[$grouperconf['source']])) {
		switch(strtolower($grouperconf['source'])) {
		case 'google': $query=GrouperBuildQuery0($searchterms); break;
		case 'yahoo': $query=GrouperBuildQuery1($searchterms); break;
		default: $query=''; break;
		}
	}
	else $query='';
	if ($query=='') {
		GrouperError("ERROR: Invalid news source: ".$grouperconf['source'],0);
		$grouperconf['cachefile']='';
	}
	return $query;
}

function GrouperBuildQuery0($searchterms) {
	global $groupersources,$grouperconf;
	$srcConf=&$groupersources['google'];
	return ((substr($srcConf['querystart'],0,1)=='/')?'':'/').
		'multiple_site_no='.urlencode($searchterms);
}

function GrouperBuildQuery1($searchterms) {
	global $groupersources;
	$srcConf=&$groupersources['yahoo'];
	return ((substr($srcConf['querystart'],0,1)=='/')?'':'/').
		$srcConf['querystart'].'&ei='.$srcConf['encoding'].
		$srcConf['sort'.$srcConf['sortby']].
		'&p='.urlencode($searchterms);
}

function GrouperOpenSearchConnection($searchterms) {
	global $grouperconf,$GrouperVersion,$GrouperRedirs;
	global $groupersources;
	
	$srchDomain=$groupersources[$grouperconf['source']]['searchdomain'];
	$GrouperRedirs=array();
	if (strlen($grouperconf['query']=GrouperBuildQuery($searchterms)))
		$fp=GrouperOpenFile('http://'.$srchDomain.$grouperconf['query']);
	else $fp=0;
	return $fp;
}

function GrouperOpenFile($url) {
	global $grouperconf,$GrouperVersion,$GrouperRedirs;
	
	if (preg_match("#^http://#i",$url)) {
		if (strlen($grouperconf['proxyserver'])) {
			$urlparts=parse_url($grouperconf['proxyserver']);
			$therest=$url;
		} else {
			$urlparts=parse_url($url);
			$therest=$urlparts['path'].(isset($urlparts['query'])?('?'.$urlparts['query']):'');
		}
		$domain=$urlparts['host'];
		$port=isset($urlparts['port'])?$urlparts['port']:80;
		$fp=fsockopen($domain,$port,$errno,$errstr,$grouperconf['timeout']);
		if ($fp) {
			fputs($fp,"GET $therest HTTP/1.0\r\n".
				($grouperconf['sendhost']?"Host: $domain\r\n":'').
				(strlen($grouperconf['proxyauth'])?('Proxy-Authorization: Basic '.base64_encode($grouperconf['proxyauth']) ."\r\n"):'').
				(strlen($grouperconf['basicauth'])?('Authorization: Basic '.base64_encode($grouperconf['basicauth']) ."\r\n"):'').
				"User-Agent: Grouper/$GrouperVersion\r\n\r\n");
			$grouperconf['http-headers']=array();
			while ((!feof($fp))&&preg_match("/[^\r\n]/",$header=fgets($fp,2000))) {
				if (strpos($header,':')) {
					list($n,$v)=explode(':',$header,2);
					if (strlen($n=trim($n))) $grouperconf['http-headers'][strtolower($n)]=trim($v);
				}
				if (preg_match("/^Location:/i",$header)) {
					fclose($fp);
					if (count($GrouperRedirs)<$grouperconf['maxredir']) {
						$loc=trim(substr($header,9));
						if (!preg_match("#^http://#i",$loc)) {
							if (strlen($grouperconf['proxyserver'])) {
								$redirparts=parse_url($url);
								$loc=$redirparts['scheme'].'://'.$redirparts['host'].(isset($redirparts['port'])?(':'.$redirparts['port']):'').$loc;
							} else $loc="http://$domain".(($port==80)?'':":$port").$loc;
						}
						for ($i=count($GrouperRedirs)-1;$i>=0;$i--) if (!strcmp($loc,$GrouperRedirs[$i])) {
							GrouperError('Redirection loop detected. Giving up.');
							return 0;
						}
						$GrouperRedirs[]=$loc;
						return GrouperOpenFile($loc);
					} else {
						GrouperError('Too many redirects. Giving up.');
						return 0;
					}
				}
			}
		} else GrouperError("$errstr ($errno)");
	} else if (!($fp=fopen($url,'r'))) GrouperError("Failed to open file: $url");
	return $fp;
}

function GrouperOpenSearchCache($filename,$searchterms) {
	global $grouperconf;
	$fp=0;
	$filename=preg_replace("/\.+/",'.',$filename);
	$cachepath=preg_replace("#/{2,}#",'/',($grouperconf['cacherelative']?(dirname(__FILE__).'/'):'').$grouperconf['cachepath']."/$filename");
	$grouperconf['query']=GrouperBuildQuery($searchterms);
	if (file_exists($cachepath)) {
		if (!($fp=fopen($cachepath,'r'))) GrouperError("Unable to open input file.");
	} else GrouperError("Input file not found.");
	return $fp;
}

function GrouperOpenCacheWrite() {
	global $grouperconf;
	$j=0;
	if (!file_exists($grouperconf['cachefile'])) touch($grouperconf['cachefile']);
	if ($f=fopen($grouperconf['cachefile'],'r+')) {
		if ($a=fstat($f)) {
			flock($f,LOCK_EX); // ignore result--doesn't work for all systems and situations
			clearstatcache();
			if ($b=fstat($f)) {
				if ($a['mtime']!=$b['mtime']) {
					flock($f,LOCK_UN);
					fclose($f);
				} else $j=$f;
			} else {
				GrouperError("Can't stat cache file (2).");
				fclose($f);
			}
		} else {
			GrouperError("Can't stat cache file (1).");
			fclose($f);
		}
	} else GrouperError("Can't open cache file.");
	return $j;
}

function GrouperCloseCacheWrite($f) {
	global $grouperconf;
	ftruncate($f,ftell($f));
	fflush($f);
	flock($f,LOCK_UN);
	fclose($f);
	$grouperconf['mtime']=time();
}

function GrouperCacheSearchResult($searchterms) {
	$r=0;
	if ($f=GrouperOpenSearchConnection($searchterms)) {
		if ($outf=GrouperOpenCacheWrite()) {
			while ($l=fread($f,1000)) fwrite($outf,$l);
			GrouperCloseCacheWrite($outf);
			$r=1;
		} else GrouperError("Unable to create/open cache file.",0);
		fclose($f);
	}
	return $r;
}

function GrouperXMLEncodeText($t) {
	global $grouperconf;
	return preg_replace('/&amp;amp;/','&amp;',
		preg_replace('/&amp;gt;/','&gt;',
			preg_replace('/&amp;lt;/','&lt;',
				preg_replace("/&/","&amp;",
					htmlentities(preg_replace("/\\x00/",'',$t),ENT_NOQUOTES,$grouperconf['sourcesettings']['encoding'])
				)
			)
		)
	);
}

function GrouperXMLEncodeAttribute($a) {
	return ereg_replace('"','&quot;',ereg_replace('&','&amp;',$a));
}


function GrouperGetFeed($searchterms,$cache,$showit,$fromcache) {
	global $grouperconf,$GrouperVersion,$grouperrawdata;
	global $groupersources;
	
	$grouperconf['sourcesettings']=&$groupersources[$grouperconf['source']];
	$srcConf=&$grouperconf['sourcesettings'];

	$grouperrawdata=$items='';
	if ($fp=(strlen($fromcache)?GrouperOpenSearchCache($fromcache,$searchterms):GrouperOpenSearchConnection($searchterms))) {
		while ((!feof($fp))&&($grouperrawdata.=preg_replace("/\\x00/",'',fread($fp,4096)))) { }
		fclose($fp);
		
		if (strlen($srcConf['tossbefore'])) $grouperrawdata=stristr($grouperrawdata,$srcConf['tossbefore']);
		if (strlen($srcConf['tossafter'])) $grouperrawdata=substr($grouperrawdata,0,strpos($grouperrawdata,$srcConf['tossafter']));

		if (!preg_match_all('#'.$srcConf['extractionpattern'].'#si',$grouperrawdata,$matches)) GrouperError('Unable to find any news items.');
		else {
			$itemorder=explode('|',$srcConf['extractionorder']);
			$ordcount=count($itemorder);
			
			$contentlist=explode('|',$grouperconf['contentfields']);
			$contentfields=array();
			for ($i=count($contentlist)-1;$i>=0;$i--) $contentfields[$contentlist[$i]]=1;

			for ($i=$k=0,$j=count($matches[1]);($i<$j)&&($k<$grouperconf['maxitems']);$i++) {
				$items.="<item>\n";
				for ($m=0;$m<$ordcount;$m++) {
					if (strcmp($itemorder[$m],'skip')) {
						$temp1=preg_replace("/<.*?>/",'',$matches[$m+1][$i]);
						if (isset($contentfields[$itemorder[$m]])) {
							GrouperTruncate($temp1,$temp,$grouperconf['maxcontent'],$grouperconf['atrunccontent']);
							$temp1=$temp;
						}
						$items.='<'.$itemorder[$m].'>'.GrouperXMLEncodeText($temp1).'</'.$itemorder[$m].">\n";
					}
				}
				$items.="</item>\n";
				$k++;
			}
		}
		if (strlen($items)) {
				$rssstart='<?xml version="1.0" encoding="'.$srcConf['encoding'].'"?>'."\n".
					'<rss version="2.0" '.$grouperconf['namespaces'].'>'."\n<channel>\n<title>".GrouperXMLEncodeText($srcConf['channel']['title'])."</title>\n".
					'<link>'.(isset($srcConf['channel']['link'])?$srcConf['channel']['link']:
						('http://'.$srcConf['searchdomain'].GrouperXMLEncodeText($grouperconf['query'])))."</link>\n".
					'<description>'.GrouperXMLEncodeText($srcConf['channel']['description'])."</description>\n".
					'<pubDate>'.(isset($srcConf['channel']['pubDate'])?$srcConf['channel']['pubDate']:date('r',time()))."</pubDate>\n".
					"<generator>Grouper/$GrouperVersion</generator>\n";
				$rssend="</channel>\n</rss>\n";

			if ($cache) {
				if ($cfp=GrouperOpenCacheWrite()) {
					fwrite($cfp,$rssstart.$items.$rssend);
					GrouperCloseCacheWrite($cfp);
				} else GrouperError("Unable to create/open RSS cache file.",0);
			}
			if ($showit) echo $rssstart.$items.$rssend;
		}
	} else if ($showit && strlen($grouperconf['cachefile'])) readfile($grouperconf['cachefile']);
}
?>
