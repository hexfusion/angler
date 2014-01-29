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

$carpversion='4.0.11LE';

function CarpConfReset() {
	global $carpconf,$carpurltags;
	$poweredby=array(
		'RSS feed imported by CaRP',
		'Content by CaRP PHP RSS parser',
		'RSS to HTML conversion by CaRP'
	);
	$carpconf=array(

	'cache-method'=>'file',
	
	'cachepath'=>'',
	'cacherelative'=>1,
	
	'mysql-database'=>'carpcache',
	'mysql-tables'=>array('carpaggregatecache','carpmanualcache','carpautocache'),
	'mysql-connect'=>0,
	'mysql-select-db'=>0,
	'mysql-connection'=>FALSE,
	'mysql-host'=>'localhost',
	'mysql-username'=>'',
	'mysql-password'=>'',
	'mysql-lock-timeout'=>10,
	
	'cacheinterval'=>60,
	'cacheerrorwait'=>30,
	'cachetime'=>'',
	
	'carperrors'=>2,
	'phperrors'=>0,
	'fixentities'=>0,

	'basicauth'=>'',
	'proxyauth'=>'',
	'proxyserver'=>'',

	'filterin'=>'',
	'filterout'=>'',
	'skipdups'=>1,

	'descriptiontags'=>'b|/b|i|/i|br|p|/p|hr|span|/span|font|/font',
	'strip-xhtml-prefixes'=>1,
	
	'linktarget'=>0,

	'cborder'=>'image,link,desc',
	'bcb'=>'',
	'acb'=>'<br />',
	'caorder'=>'',
	'bca'=>'',
	'aca'=>'<br />',
	'bcurl'=>'',
	'acurl'=>'<br />',
	'bctitle'=>'',
	'actitle'=>'<br />',
	'clinkclass'=>'',
	'clinkstyle'=>'',
	'clinktitles'=>1,
	'clink_attrs'=>'',
	'maxctitle'=>80,
	'atruncctitle'=>'...',
	'bcdate'=>'<i>',
	'acdate'=>'</i><br />',
	'cdateformat'=>'j M Y \a\t g:ia',
	'bcdesc'=>'',
	'acdesc'=>'<br />',
	'maxcdesc'=>0,
	'atrunccdesc'=>'...',
	'bcimage'=>'',
	'acimage'=>'<br />',
	'maxcimagew'=>144,
	'maxcimageh'=>400,
	'defcimagew'=>0,
	'defcimageh'=>0,
	'setcimagew'=>0,
	'setcimageh'=>0,

	'afield'=>"\n",
	'aitem'=>"\n",

	'maxitems'=>15,
	'noitems'=>'No news items found',
	'shownoitems'=>0,
	'iorder'=>'image,link,author,date,desc,podcast',
	'bitems'=>'',
	'aitems'=>'',
	'bi'=>'',
	'ai'=>'',
	'biurl'=>'',
	'aiurl'=>'<br />',
	'bilink'=>'',
	'ailink'=>'<br />',
	'ilinkclass'=>'',
	'ilinkstyle'=>'',
	'ilinktitles'=>1,
	'ilink_attrs'=>'',
	'defaultititle'=>'(no title)',
	'maxititle'=>80,
	'atruncititle'=>'...',
	'biauthor'=>'<i>by ',
	'aiauthor'=>'</i><br />',
	'bidate'=>'<i>',
	'aidate'=>'</i><br />',
	'idateformat'=>'j M Y \a\t g:ia',
	'timeoffset'=>0,
	'bidesc'=>'',
	'aidesc'=>'<br />',
	'maxidesc'=>0,
	'atruncidesc'=>'...',
	'biimage'=>'',
	'aiimage'=>'<br />',
	'maxiimagew'=>144,
	'maxiimageh'=>400,
	'defiimagew'=>0,
	'defiimageh'=>0,
	'setiimagew'=>0,
	'setiimageh'=>0,
	'bipodcast'=>'<a href="',
	'aipodcast'=>'">Listen</a><br />',

	'maxgroupfilter'=>3,
	'encodingin'=>'',
	'encodingout'=>'ISO-8859-1',
	'maxredir'=>10,
	'timeout'=>15,
	'sendhost'=>1,
	'removebadtags'=>1,
	'outputformat'=>0,
	
	'cachefunctions'=>array('CarpAggregatePath','CarpCachePath','CarpAutoCachePath'),
	
	'prefix-map'=>array(),
	'html-urls'=>1,

	/* If you change poweredby, we'd appreciate a link from elsewhere on your
	site. If you incorporate CaRP into another product and change it, please
	ensure that a link is shown by default somewhere. Thanks! */

	'poweredby'=>'<br /><i><a href="http://www.geckotribe.com/rss/carp/" target="_blank">'.$poweredby[rand(0,count($poweredby)-1)].'</a></i>'
	);
	
}

function CarpConf($n,$v) {
	global $carpconf;
	if (is_null($v)) $v='';
	$n=explode('|',strtolower(str_replace(' ','',$n)));
	for ($i=count($n)-1;$i>=0;$i--) {
		if (isset($carpconf[$n[$i]])) $carpconf[$n[$i]]=$v;
		else CarpError("Unknown option ($n[$i]). Please check the spelling of the option name and that the version of CaRP you are using supports this option.",'unknown-option',0);
	}
}

function CarpConfAdd($n,$v,$ba=1) {
	global $carpconf;
	$n=explode('|',strtolower(str_replace(' ','',$n)));
	for ($i=count($n)-1;$i>=0;$i--) {
		if (isset($carpconf[$n[$i]])) {
			if ($ba) $carpconf[$n[$i]].=$v;
			else $carpconf[$n[$i]]=$v.$carpconf[$n[$i]];
		} else CarpError("Unknown option ($n[$i]). Please check the spelling of the option name and that the version of CaRP you are using supports this option.",'unknown-option',0);
	}
}

function CarpConfRemove($n,$v) {
	global $carpconf;
	$n=explode('|',strtolower(str_replace(' ','',$n)));
	for ($i=count($n)-1;$i>=0;$i--) {
		if (isset($carpconf[$n[$i]])) $carpconf[$n[$i]]=str_replace($v,'',$carpconf[$n[$i]]);
		else CarpError("Unknown option ($n[$i]). Please check the spelling of the option name and that the version of CaRP you are using supports this option.",'unknown-option',0);
	}
}


function CarpDirName() { return str_replace("\\","/",dirname(__file__)); }

function CarpLoadTheme($name) {
	if (file_exists($fn=dirname(__file__)."/themes/$name")) require $fn;
	else CarpError("Theme \"$name\" not found in CaRP themes directory",'theme-not-found',0);
}

function CarpLoadPlugin($name) {
	CarpError('This version of CaRP does not support plugins. To use plugins, please upgrade to CaRP Evolution.','no-plugin-support',0);
}


function CarpOutput($t,$force=0) {
	global $carpconf,$carpoutput;

	if (is_array($t)) { for ($i=0,$j=count($t);$i<$j;$i++) $t[$i]=str_replace("&apos;","'",$t[$i]); }
	else $t=str_replace("&apos;","'",$t);


	switch ($carpconf['outputformat']) {
	case 1:
		if (!is_array($t)) $t=explode("\n",preg_replace("/[\r\n]+/","\n",$t));
		for ($i=0,$j=count($t);$i<$j;$i++) echo 'document.writeln("'.str_replace('"','\"',trim(str_replace("\r",' ',$t[$i])))."\");\n";
  		break;
	case 2: $carpoutput.=(is_array($t)?implode('',$t):$t);
		break;
	default: echo is_array($t)?implode('',$t):$t;
	}
}

function CarpError($s,$link='',$c=1) {
	global $carpconf;
	if ($carpconf['carperrors']) {
		$link=preg_replace('#[^-a-zA-Z0-9/]#','',$link);
		if (($carpconf['carperrors']==1)||!isset($link{0})) CarpOutput("<br />\n[CaRP] $s<br />\n",1);
		else CarpOutput("<br />\n[CaRP] <a target=\"_blank\" href=\"http://carp.docs.geckotribe.com/errors/$link.php\">$s</a><br />\n",1);
	}
	if ($c&&$carpconf['cacheerrorwait']&&isset($carpconf['cachefile'])&&isset($carpconf['cachefile']{0}))
		CarpTouchCache($carpconf['cachefile'],time()+60*($carpconf['cacheerrorwait']-$carpconf['cacheinterval']),0);
}

function CarpMySQLQuery($q) {
	global $carpconf;
	return $carpconf['mysql-connection']?mysql_query($q,$carpconf['mysql-connection']):mysql_query($q);
}

function CarpCacheMysqlConnect() {
	global $carpconf;
	if (!isset($carpconf['mysql-database-name'])) $carpconf['mysql-database-name']='';
	if ($carpconf['mysql-connection']===FALSE) {
		$carpconf['mysql-connection']=$carpconf['mysql-connect']?
			mysql_connect($carpconf['mysql-host'],$carpconf['mysql-username'],$carpconf['mysql-password']):0;
		if ($carpconf['mysql-connection']===FALSE) CarpError('Database not connected or unable to open connection','database-not-connected');
		else if ($carpconf['mysql-select-db']) {
			$carpconf['mysql-database-name']='';
			if ($carpconf['mysql-connection']) mysql_select_db($carpconf['mysql-database'],$carpconf['mysql-connection']);
			else mysql_select_db($carpconf['mysql-database']);
		} else if (!empty($carpconf['mysql-database'])) $carpconf['mysql-database-name']=$carpconf['mysql-database'].'.';
	}
}

function CarpParseMySQLPath($path,&$which,&$key) {
	$rv=0;
	if (preg_match('#^mysql:([^/]+)/(.*)$#',$path,$m)) {
		switch($m[1]) {
		case 'aggregatecache': $which=0; break;
		case 'manualcache': $which=1; break;
		case 'autocache': $which=2; break;
		default: CarpError('Invalid mySQL cache database specified.','database-invalid-table'); return;
		}
		$key=$m[2];
		$rv=1;
	}
	return $rv;
}

function CarpDataPath() {
	global $carpconf;
	$rv='';
	switch($carpconf['cache-method']) {
	case 'mysql': $rv='mysql:'; break;
	default: $rv=preg_replace("#/{2,}#",'/',($carpconf['cacherelative']?(CarpDirName().'/'):'').$carpconf['cachepath'].(isset($carpconf['cachepath']{0})?'/':''));
	}
	return $rv;
}

function CarpAggregatePath() { return CarpDataPath().'aggregatecache/'; }
function CarpCachePath() { return CarpDataPath().'manualcache/'; }
function CarpAutoCachePath() { return CarpDataPath().'autocache/'; }

function CarpClearCache($which,$stale=0) {
	global $carpconf;
	if ($stale) $stale=ceil(time()-($stale*86400));
	switch($carpconf['cache-method']) {
	case 'mysql': CarpCacheMysqlConnect();
		CarpMySQLQuery('delete from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].($stale?(" where updated<$stale"):''));
		break; 
	default:
		if ($d=opendir($dir=call_user_func($carpconf['cachefunctions'][$which]))) {
			while (false!==($fn=readdir($d))) if (($fn!='.')&&($fn!='..')) {
				if ($stale) {
					$s=stat($dir.$fn);
					if ($s['mtime']<$stale) unlink($dir.$fn);
				} else unlink($dir.$fn);
			}
		} else CarpError('Unable to access cache folder.','cache-folder-access',0);
	}
}

function CarpClearCacheFile($which,$filename) {
	global $carpconf;
	switch($carpconf['cache-method']) {
	case 'mysql': CarpCacheMysqlConnect();
		CarpMySQLQuery('delete from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' where id="'.(($which==2)?md5($filename):addslashes($filename)).'"');
		break; 
	default: unlink(call_user_func($carpconf['cachefunctions'][$which]).(($which==2)?md5($filename):$filename)); break; 
	}
}

function CarpGetCacheUpdated($fn) {
	global $carpconf;
	$rv=0;
	if (CarpParseMySQLPath($fn,$which,$key)) {
		CarpCacheMysqlConnect();
		if ($r=CarpMySQLQuery('select updated from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' where id="'.$key.'"')) {
			if (mysql_num_rows($r)) $rv=mysql_result($r,0)+0;
			mysql_free_result($r);
		} else CarpError('Database error while checking when the cache was last updated.','database-error'); 
	} else if (file_exists($fn)) $rv=filemtime($fn);
	return $rv;
}

function CarpCheckCache($cachefile) {
	global $carpconf;
	$cache=0;
	if ($carpconf['mtime']=CarpGetCacheUpdated($cachefile)) {
		$nowtime=time();
		if (isset($carpconf['cachetime']{0})) {
			list($hour,$min)=explode(':',$carpconf['cachetime']);
			$limtime=mktime($hour,$min,0);
			$cache=($carpconf['mtime']>$limtime-(($nowtime<$limtime)?86400:0))?1:2;
		} else $cache=(($nowtime-$carpconf['mtime'])<($carpconf['cacheinterval']*60))?1:2;
	} else $cache=2;
	return $cache;
}

function CarpSetCache($cachefile,$cachefunction=1) {
	global $carpconf;
	$cachefile=preg_replace("/\.+/",'.',$cachefile);
	$carpconf['cachefile']=call_user_func($carpconf['cachefunctions'][$cachefunction]).$cachefile;
	return CarpCheckCache($carpconf['cachefile']);
}

function CarpGetCache($fn) {
	global $carpconf;
	$rv='';
	if (CarpParseMySQLPath($fn,$which,$key)) {
		CarpCacheMysqlConnect();
		if ($r=CarpMySQLQuery('select cache from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' where id="'.addslashes($key).'"')) {
			if (mysql_num_rows($r)) $rv=mysql_result($r,0);
			else CarpError('Cache record not found.','cache-not-found');
			mysql_free_result($r);
		} else CarpError('Database error attempting to retrieve cache record.','database-error');
	} else if (file_exists($fn)) {
		if ($f=fopen($fn,'r')) {
			$rv=fread($f,filesize($fn));
			fclose($f);
		} else CarpError('Failed to open cache file.','cache-not-found');
	} else CarpError('Cache file not found.','cache-not-found');
	return $rv;
}

function CarpTouchCache($fn,$time=0,$errors=1) {
	global $carpconf;
	if (!$time) $time=time();
	else $time+=0;
	if (CarpParseMySQLPath($fn,$which,$key)) {
		CarpCacheMysqlConnect();
		if ($r=CarpMySQLQuery('select cache from '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which].' where id="'.addslashes($key).'"')) {
			if (mysql_num_rows($r)) CarpMySQLQuery('update '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which]." set updated=\"$time\" where id=\"".addslashes($key).'"');
			else CarpMySQLQuery('insert into '.$carpconf['mysql-database-name'].$carpconf['mysql-tables'][$which]." set updated=\"$time\",id=\"".addslashes($key).'"');
		} else if ($errors) CarpError('Database error attempting to set update time on cache record.','database-error');
	} else touch($fn,$time); 
}

function CarpCacheFilter($url,$cachefile) { CarpCacheShow($url,$cachefile,0); }
function CarpFilter($url,$cachefile) { CarpShow($url,$cachefile,0); }
function CarpGroupFilter($feeds,$group='') {
	global $carpconf;
	$cachelist='';
	if (!is_array($feeds)) CarpError('The first argument to CarpGroupFilter is in the wrong format','group-filter-arg');
	else {
		$r=0;
		$aggpath=call_user_func($carpconf['cachefunctions'][0]);
		$autopath=call_user_func($carpconf['cachefunctions'][2]);
		$oldencodingin=$carpconf['encodingin'];
		$resetencoding=0;
		$haveGroup=isset($group{0});
		foreach ($feeds as $k=>$v) {
			if ($haveGroup) {
				$k=$v;
				$v="$group-".md5($k);
			} else if (is_array($v)) {
				$resetencoding=1;
				list($v,$encodingin)=$v;
				CarpConf('encodingin',$encodingin);
			}
			$cachelist.="|$v";
			if (($r<$carpconf['maxgroupfilter'])||(!$carpconf['maxgroupfilter'])||(!CarpGetCacheUpdated("$aggpath$v"))) {
				$r+=CarpCache($k,$autocache=md5($k),2);
				CarpShow($autopath.$autocache,$v,0);
			}
			if ($resetencoding) {
				$resetencoding=0;
				CarpConf('encodingin',$oldencodingin);
			}
		}
	}
	return substr($cachelist,1);
}

function CarpCacheShow($url,$cachefile='',$showit=1) {
	global $carpconf;
	$rv=CarpCache($url,$autocache=md5($url),2);
	CarpShow(call_user_func($carpconf['cachefunctions'][2]).$autocache,$cachefile,$showit);
	return $rv;
}

function CarpPHPErrors($set) {
	global $carpconf;
	if ($carpconf['phperrors']>=0) {
		if ($set) {
			$carpconf['savephperrors']=error_reporting($carpconf['phperrors']);
			$carpconf['savedisplayerrors']=ini_set('display_errors',1);
		} else {
			ini_set('display_errors',$carpconf['savedisplayerrors']);
			error_reporting($carpconf['savephperrors']);
		}
	}
}

function CarpShow($url,$cachefile='',$showit=1) {
	global $carpconf,$carpoutput;

	CarpPHPErrors(1);
	$carpoutput='';
	$cache=0;
	$carpconf['cachefile']='';
	if (isset($cachefile{0})) $cache=CarpSetCache($cachefile,$showit);
	else if (!$showit) {
		CarpError('No cache file indicated when calling CarpFilter or CarpShow with showit=0.','cache-required',0);
		CarpPHPErrors(0);
		return 0;
	}
		if ($cache%2==0) {
			require_once CarpDirName().'/carpinc.php';
			GetRSSFeed($url,$cache,$showit);
		} else if ($showit) CarpOutput(CarpGetCache($carpconf['cachefile']));
	$carpconf['cachefile']='';
	CarpPHPErrors(0);
}

function CarpAggregateSort($a,$b) {
	$a=floor($a);
	$b=floor($b);
	return ($a==$b)?0:(($a>$b)?-1:1);
}

function CarpInterleave($feeds) { CarpAggregate($feeds,1); }

function CarpAggregate($feeds, $interleave=0) {
	global $carpconf,$carpoutput;
	CarpPHPErrors(1);
		$carpoutput='';
		$fl=explode('|',$feeds);
		$id=$il=$cb=$ca=$ci=array();
		$j=0;
		for ($i=$fc=count($fl)-1;$i>=0;$i--) {
			if ($f=CarpGetCache(call_user_func($carpconf['cachefunctions'][0]).$fl[$i])) {
				if (!is_array($f)) {
					$f=explode("\n",preg_replace("/[\r\n]+/","\n",$f));
					for ($an=count($f)-1;$an>=0;$an--) $f[$an].="\n";
				}
				$k=1000000;
				foreach ($f as $l) if ($l{0}!="\n") {
					list($datetime,$reserved,$data)=explode(':',$l,3);
					switch ($datetime) {
					case 'cb': $cb[$i]=$data; break;
					case 'ca': $ca[$i]=$data; break;
					default:
						$il[$j]=$data;
						if ($interleave) {
							$k-=1000;
							$ii=$k+$fc-$i;
							$id["$ii"]=$j;
						} else $id["$datetime.$j"]=$j;
						$ci[$j]=$i;
						$j++;
					}
				}
			}
		}
		if ($carpconf['shownoitems']&&!count($id)) CarpOutput($carpconf['noitems']);
		else {
			uksort($id,'CarpAggregateSort');
			CarpOutput($carpconf['bitems']);
			for ($i=0;($i<$carpconf['maxitems'])&&(list($k,$v)=each($id));$i++)
					CarpOutput((isset($cb[$ci[$v]])?$cb[$ci[$v]]:'').$il[$v].(isset($ca[$ci[$v]])?$ca[$ci[$v]]:''));
			CarpOutput($carpconf['aitems'].$carpconf['poweredby']);
		}
	CarpPHPErrors(0);
}

function CarpCache($url,$cachefile,$cachefunction=1) {
	global $carpconf;
	CarpPHPErrors(1);
	if (isset($cachefile{0})) {
		$cache=CarpSetCache($cachefile,$cachefunction);
		if ($cache%2==0) {
			require_once CarpDirName().'/carpinc.php';
			CacheRSSFeed($url);
			$carpconf['cachefile']='';
			CarpPHPErrors(0);
			return 1;
		}
	} else CarpError('No cache file indicated when calling CarpCache.','cache-required',0);
	$carpconf['cachefile']='';
	CarpPHPErrors(0);
	return 0;
}

if (file_exists(CarpDirName()."/carpconf.php")) require_once CarpDirName()."/carpconf.php";
else CarpConfReset();

return;
?>