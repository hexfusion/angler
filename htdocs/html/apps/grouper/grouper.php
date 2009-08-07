<?php
/*
Grouper GPL v1.4.2
Copyright (c) 2003 Antone Roundy

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
$GrouperVersion='1.4.2GPL';

function GrouperConfReset() {
	global $grouperconf,$grouperoptions;
	$grouperconf=array(

	'cachepath'=>'rsscache',
	'cacherelative'=>1,
	'cacheinterval'=>60,
	'cacheerrorwait'=>30,
	'sendhost'=>1,
	'maxredir'=>15,
	
	'contenttype'=>'application/rss+xml',
	
	'groupererrors'=>1,
	'phperrors'=>0,

	'source'=>'google',

	'maxcontent'=>0,
	'atrunccontent'=>'...',

	'maxitems'=>15,
	'skipdups'=>1,

	'contentfields'=>'description',

	'proxyserver'=>'',
	'proxyauth'=>'',
	'basicauth'=>'',
	'timeout'=>15,
	
	'namespaces'=>'',
	
	'http-headers'=>array()
	);
	
	$grouperoptions='|';
	while (list($k,$v)=each($grouperconf)) $grouperoptions.="$k|";
}

$grouperoldsources=array('google','yahoo','feedster','daypop');

function GrouperConf($n,$v) {
	global $grouperconf,$grouperoptions,$grouperoldsources;
	if (is_null($v)) $v='';
	$n=explode('|',strtolower(preg_replace("/ /",'',$n)));
	for ($i=count($n)-1;$i>=0;$i--) {
		if (($n[$i]=='source')&&!preg_match("/[^0-9]/",$v)) $v=$grouperoldsources[$v];
		if (strpos($grouperoptions,"|$n[$i]|")!==false) $grouperconf[$n[$i]]=$v;
		else GrouperError("Unknown option ($n[$i]). Please check the spelling of the option name and that the version of Grouper you are using supports this option.",0);
	}
}

$groupersources=array(
	'google'=>array(
		'channel'=>array(
			'title'=>'Google News',
			'description'=>'Google News search by <a href="http://www.geckotribe.com/rss/grouper/">Grouper</a>'
		),
		'searchdomain'=>'waterdata.usgs.gov',
		'tossbefore'=>'<!-- end table header -->',
		'tossafter'=>'Data Status codes:',
		'extractionpattern'=>'<td align=left><a [^>]*?href="([^>]+?)"[^>]*>(?!<img)(.*?)</a>',
		'extractionorder'=>'link|title|author|description',

		'querystart'=>'/ny/nwis/current?',
		'language'=>'',
		'edition'=>'',
		'encoding'=>'',
		'sortby'=>1,
		'sort1'=>'',
		'sort2'=>''
	),
	'yahoo'=>array(
		'channel'=>array(
			'title'=>'Yahoo! News',
			'description'=>'Yahoo! News search by <a href="http://www.geckotribe.com/rss/grouper/">Grouper</a>'
		),
		'searchdomain'=>'news.search.yahoo.com',
		'tossbefore'=>'Sort results by',
		'tossafter'=>'</ol>',
		'extractionpattern'=>'<li><a.*?href="(.+?)">(.+?)</a>.+?<em\b.*?>(.+?)&nbsp;-&nbsp;.+?</em>(.+?)</li>',
		'extractionorder'=>'link|title|author|description',

		'querystart'=>'/search/news/?c=&nytp=0',
		'encoding'=>'ISO-8859-1',
		'sortby'=>1,
		'sort1'=>'',
		'sort2'=>'&datesort=1'
	)
);

function GrouperSourceConf($n,$v,$a='') {
	global $groupersources,$grouperconf;

	// backwards compatibility code -- next 3 lines
	if (($n=='maxidesc')||($n=='atruncidesc')) { GrouperConf(($n=='maxidesc')?'maxcontent':'atrunccontent',$v); return; }
	if (($n=='dateformat')||($n=='mustparsedate')||($n=='datefields')) { GrouperConf($n,$v); return; }
	if (($n=='channeltitle')||($n=='channeldescription')) { $a='channel'; $n=substr($n,7); }

	if (is_null($v)) $v='';
	if (strlen($a)) {
		if (isset($groupersources[$grouperconf['source']][$a]["$n"])) $groupersources[$grouperconf['source']][$a]["$n"]=$v;
		else GrouperError("Unknown source option ($n) or source array ($a). Please check the spelling of the option name and that the version of Grouper you are using supports this option.",0);
	} else {
		if (isset($groupersources[$grouperconf['source']]["$n"])) $groupersources[$grouperconf['source']]["$n"]=$v;
		else GrouperError("Unknown source option ($n). Please check the spelling of the option name and that the version of Grouper you are using supports this option.",0);
	}
}


function GrouperError($s,$c=1) {
	global $grouperconf;
	if ($grouperconf['groupererrors']) echo "<br>\n[Grouper] $s<br>\n";
	if ($c&&$grouperconf['cacheerrorwait']&&strlen($grouperconf['cachefile']))
		touch($grouperconf['cachefile'],time()+60*($grouperconf['cacheerrorwait']-$grouperconf['cacheinterval']));
}

function GrouperSetCache($cachefile) {
	global $grouperconf;
	$cache=0;
	$cachefile=preg_replace("/\.+/",'.',$cachefile);
	$grouperconf['cachefile']=preg_replace("#/{2,}#",'/',($grouperconf['cacherelative']?(dirname(__FILE__).'/'):'').$grouperconf['cachepath']."/$cachefile");
	if (file_exists($grouperconf['cachefile'])) {
		$grouperconf['mtime']=filemtime($grouperconf['cachefile']);
		$nowtime=time();
		$cache=(($nowtime-$grouperconf['mtime'])<($grouperconf['cacheinterval']*60))?1:2;
	} else $cache=2;
	return $cache;
}

function GrouperShow($searchterms,$outputcache='',$showit=1,$fromcache='') {
	global $grouperconf;
	if ($grouperconf['phperrors']>=0) $grouperconf['savephperrors']=error_reporting($grouperconf['phperrors']);
	$cache=0;
	if (strlen($outputcache)) $cache=GrouperSetCache($outputcache);
	else if (!$showit) {
		GrouperError('No cache file indicated when calling GrouperShow with showit=0.',0);
		$grouperconf['cachefile']='';
		if ($grouperconf['phperrors']>=0) error_reporting($grouperconf['savephperrors']);
		return 0;
	}
	if ($showit && strlen($grouperconf['contenttype'])) Header('Content-Type: '.$grouperconf['contenttype']);
	if ($cache%2==0) {
		require_once dirname(__FILE__).'/grouperinc.php';
		GrouperGetFeed($searchterms,$cache,$showit,$fromcache);
	} else if ($showit) readfile($grouperconf['cachefile']);
	$grouperconf['cachefile']='';
	if ($grouperconf['phperrors']>=0) error_reporting($grouperconf['savephperrors']);
	return 1;
}

function GrouperCache($searchterms,$outputcache) {
	global $grouperconf;
	if ($grouperconf['phperrors']>=0) $grouperconf['savephperrors']=error_reporting($grouperconf['phperrors']);
	if (strlen($outputcache)) {
		$cache=GrouperSetCache($outputcache);
		if ($cache%2==0) {
			require_once dirname(__FILE__).'/grouperinc.php';
			GrouperCacheSearchResult($searchterms);
			$grouperconf['cachefile']='';
			if ($grouperconf['phperrors']>=0) error_reporting($grouperconf['savephperrors']);
			return 1;
		}
	} else GrouperError('No cache file indicated when calling GrouperCache.',0);
	$grouperconf['cachefile']='';
	if ($grouperconf['phperrors']>=0) error_reporting($grouperconf['savephperrors']);
	return 0;
}

if (file_exists(dirname(__file__)."/grouperconf.php")) require_once dirname(__file__)."/grouperconf.php";
else GrouperConfReset();
?>
