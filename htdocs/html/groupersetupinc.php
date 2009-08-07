<?php
function HiddenField($n,$v='') {
	global $groupersetup;
	if ((!strlen($v))&&isset($groupersetup[$n])) $v=$groupersetup[$n];
	if (strlen($v)) echo '<input type="hidden" name="'.$n.'" value="'.preg_replace('/"/','&quot;',$v)."\" />\n";
}

function HiddenFields($step,$asking=0) {
	global $groupersetup;
	HiddenField('step',$step);
	if ($asking!=1) {
		if (isset($groupersetup['incdir'])) HiddenField('incdir');
		if (isset($groupersetup['chrincdir'])) HiddenField('chrincdir');
	}
	if ($asking!=2) {
		if (isset($groupersetup['proxyserver'])) HiddenField('proxyserver');
		if (isset($groupersetup['proxyport'])) HiddenField('proxyport');
		if (isset($groupersetup['proxyuser'])) HiddenField('proxyuser');
		if (isset($groupersetup['proxypass'])) HiddenField('proxypass');
	}
}

function AddToFailed($failedname) {
	global $failed,$havefailed;
	$failed.=($havefailed?', ':'').$failedname;
	$havefailed=1;
}

function GrouperSetup0() {
	global $failed,$havefailed;
	
	echo '<b>Checking your server\'s PHP version...</b>';
	$vers=explode('.',PHP_VERSION);
	$needvers=array(4,1);
	$j=count($vers);
	$k=count($needvers);
	if ($k<$j) $j=$k;
	for ($i=0;$i<$j;$i++) {
		if (($vers[$i]+0)>$needvers[$i]) break;
		if (($vers[$i]+0)<$needvers[$i]) {
			echo '<span class="fail">Failed</span>. Your server is running PHP version '.PHP_VERSION.
				'. Grouper requires PHP version ';
			for ($i=0,$j=count($needvers);$i<$j;$i++) echo ($i?'.':'').$needvers[$i];
			echo ' or higher.';
			return;
		}
	}
	
	echo 'Pass<br /><br /><b>Checking your server\'s PHP function support...</b>';
	$failed='';
	$havefailed=0;
	if (trim(' a ')!='a') AddToFailed('trim');
	if (str_replace('ab','x','1ab2ab3')!='1x2x3') AddToFailed('str_replace');
	if (!file_exists(__file__)) AddToFailed('file_exists');
	if (count(explode('.','1.2.3.4'))!=4) AddToFailed('explode');
	if (strlen('12345')!=5) AddToFailed('strlen');
	if (strpos('12345','4')!=3) AddToFailed('strpos');
	if (strtolower('Grouper')!='grouper') AddToFailed('strtolower');
	if (strcmp('grouper','grouper')||!strcmp('carp','grouper')) AddToFailed('strcmp');
	if (preg_replace('/a/','b','asdf')!='bsdf') AddToFailed('preg_replace');
	if (!preg_match('/a/','ace')) AddToFailed('preg_match');

	if (strlen($_SERVER['SERVER_ADDR'])) $ip=$_SERVER['SERVER_ADDR'];
	else if (strlen($_SERVER['SERVER_NAME'])||strlen($_SERVER['HOST_NAME'])) {
		$server=$_SERVER[strlen($_SERVER['SERVER_NAME'])?'SERVER_NAME':'HOST_NAME'];
		if (preg_match('/[^0-9.]/',$server)) {
			$ip=gethostbyname($server);
			if ($ip==$server) $ip='127.0.0.1';
		} else $ip=$server;
	} else $ip='127.0.0.1';
	if ($fp=fsockopen($ip,$_SERVER['SERVER_PORT'])) fclose($fp);
	else AddToFailed('fsockopen');

	if ($fp=fopen(__file__,'r')) fclose($fp);
	else AddToFailed('fopen');

	/*
	ereg_replace, date, strtotime, stristr, htmlentities (w/charset-PHP 4.1)
	*/
	
	if (strlen($failed)) {
		include_once dirname(__file__).'/grouper.php';
		?>
		<span class="fail">Failed</span><br />The following functions are either disabled on your server,
			or are not working correctly.
		Grouper will not work on this server unless this situation is resolved:
		<?php echo $failed; ?><br /><br />

		<form action="http://www.geckotribe.com/rss/grouper/installer_feedback.php" method="post" style="display:inline;">
		We would be appreciate your sending us the follow information to help us with future Grouper development.
		All items are optional.<br /><br />
		
		<table border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td>Host name:</td>
			<td><input name="host" size="60" maxlength="255" value="<?php echo $_SERVER['SERVER_NAME']; ?>" /></td>
		</tr><tr>
			<td>Grouper Version:</td>
			<td><input name="version" size="60" maxlength="255" value="<?php echo $GrouperVersion; ?>" /></td>
		</tr><tr>
			<td>Error message:</td>
			<td><input name="errormsg" size="60" value="Unsupported functions: <?php echo $failed; ?>" /></td>
		</tr><tr>
			<td valign="top">Comments:</td>
			<td>
				<textarea name="comments" rows="5" cols="60" wrap="virtual"></textarea><br /><br />
				If you would like a response to your comments, please indicate your email address.
			</td>
		</tr><tr>
			<td></td>
			<td><input type="submit" value="Send"></td>
		</tr>
		</table>
		</form>		
		<?php
		return;
	}
	echo "Pass<br /><br />\n";
	GrouperSetup1();
}

function GrouperSetup1() {
	global $groupersetup;
	?>
	<b>Create cache folder:</b><br/>
	The easiest method is to enter your login name and password, and let me try to do it automatically.
	<b style="color:#c00;">If the automatic method fails for any reason, you will need to use the manual method.</b>
	Please choose your preferred method:<br /><br />
	
	<table border="1" cellpadding="5" cellspacing="1"><tr>
	<td valign="top">
		<form action="groupersetup.php" method="post">
		<?php HiddenFields(2); ?>
		<b>Automatic:</b><br />
		<table border="0" cellspacing="0" cellpadding="2">
		<tr><td>Login name:</td><td><input name="u" size="12"></td></tr>
		<tr><td>Password:</td><td><input type="password" name="p" size="12"></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value="Continue..." /></td></tr>
		</table><br />
		NOTES:
		<ul>
		<li>This process make take a few seconds to complete.
			If it is successful, I'll also take a few seconds attempting to generate a newsfeed.
			Please be patient.</li>
		<li>Your username and password will be transmitted as plain text between you and your webserver.
			If you can connect to your webserver via SSH, you may wish to use the manual method of setting access permissions.</li>
		</ul>
		</form>
	</td><td valign="top">
		<form action="groupersetup.php" method="post">
		<?php HiddenFields(3); ?>
		<b>Manual:</b><br />
		On a UNIX-based server (Linux, BSD, MacOS X, etc.) enter the command shown below on your server's command line (log in using SSH or Telnet).
		Note that you may need to change the first part of the path if your server login is in a "chroot" environment.
			In any case, enter the path to the grouper directory.<br /><br />
		
		On systems that don't support the "chmod" command, set the access permissions to whatever is necessary to allow anyone to write to the directory.
		(We will change the permissions back to something more secure later).
		Once you have changed the access permissions, click "Continue...".<br /><br />
		
		<code>chmod 777 <?php echo $groupersetup['chrincdir']; ?></code><br /><br />
		
		<input type="submit" value="Continue..." />
		</form>
	</td>
	</tr></table>
	<?php
}

function GrouperSetupCheckAccess($desired,$mask) {
	global $groupersetup;
	$rv=0;
	clearstatcache();
	if ($dstat=stat($groupersetup['incdir'])) {
		if (($dstat['mode']&$mask)==($desired&$mask)) $rv=1;
	}
	return $rv;
}

function GrouperSetup2LastTry() {
	global $groupersetup,$telnet;
	$telnet->DoCommand('chmod 777 '.$groupersetup['chrincdir'],$result);

	if (GrouperSetupCheckAccess(0777,0777)) GrouperSetup4();
	else {
		echo '<span class="fail">Unable to set access permissions automatically</span><br />';
		echo 'Please set the access permissions manually.';
		GrouperSetup1();
	}
}

function GrouperSetup2() {
	global $groupersetup,$telnet;
	
	include_once dirname(__file__).'/PHPTelnet.php';
	$telnet=new PHPTelnet;
	if (!($r=$telnet->Connect('',$groupersetup['u'],$groupersetup['p']))) {
		$telnet->DoCommand('chmod 777 '.$groupersetup['incdir'],$junk);
		if (GrouperSetupCheckAccess(0777,0777)) GrouperSetup4();
		else {
			$telnet->DoCommand('pwd',$path);
			if (($start=strpos($groupersetup['incdir'],$path))!==false) {
				$groupersetup['chrincdir']=substr($groupersetup['incdir'],$start);
				GrouperSetup2LastTry($groupersetup['chrincdir']);
			} else if (($start=strpos($groupersetup['incdir'],'public_html'))!==false) {
				$groupersetup['chrincdir']='/www'.substr($groupersetup['incdir'],$start+strlen('public_html'));
				GrouperSetup2LastTry($groupersetup['chrincdir']);
			}
		}
	} else {
		echo '<span class="fail">Unable to set access permissions</span><br />';
		switch($r) {
		case 1:
			echo 'Unable to create network connection. Please set access permissions manually.<br /><br />';
			break;
		case 3:
			echo 'Login failed. Please be sure to enter your login name and password accurately, or set access permissions manually.<br /><br />';
			break;
		case 4:
			echo 'The version of PHP running on your server does not support functions needed to set access permissions automatically. Please do that manually instead.<br /><br />';
			break;
		}
		GrouperSetup1();
	}
}

function GrouperSetupCreateDirectories() {
	global $groupersetup;
	echo 'Attempting to create cache directory...';
	$rv=(file_exists($groupersetup['incdir']."/rsscache")||mkdir($groupersetup['incdir']."/rsscache",0700));
	if ($rv) echo "Success<br />\n";
	else {
		echo '<span class="fail">Unexpected error</span><br />';
		echo 'Although the access permissions on your grouper directory are correct, I am unable to create a subdirectory inside it. '.
			'Unable to proceed with installation.';
	}
	return $rv;
}

function GrouperSetupAccessDirectories() {
	global $groupersetup;
	$rv=1;
	echo 'Attempting to create a file in the cache directory...';
	if ($f=fopen($groupersetup['incdir']."/rsscache/test",'w')) {
		fclose($f);
		unlink($groupersetup['incdir']."/rsscache/test");
	} else $rv=0;
	if ($rv) echo "Success<br />\n";
	else echo '<span class="fail">Failed.</span> Unable to create a file inside your cache directory. '.
		'If you created the directory manually (for example, with the command "mkdir rsscache"), please delete it and run the installation script again. '.
		'If the installation script created it, then some unexected error is causing the problem.';
	return $rv;
}

function GrouperSetup3() {
	if (GrouperSetupCheckAccess(0777,0777)) {
		if (GrouperSetupCreateDirectories()&&GrouperSetupAccessDirectories()) GrouperSetup6();
	} else {
		echo '<span class="fail">Access permissions incorrect</span><br />';
		GrouperSetup1();
	}
}

function GrouperSetup4() {
	if (GrouperSetupCreateDirectories()&&GrouperSetupAccessDirectories()) GrouperSetup5();
}

function GrouperSetup5() {
	global $groupersetup,$telnet;
	$telnet->DoCommand('chmod 711 '.$groupersetup['chrincdir'],$junk);
	if (GrouperSetupCheckAccess(0711,0777)) GrouperSetup7();
	else {
		echo '<span class="fail">I was unable to reset the access permissions on the grouper directory.</span><br />';
		GrouperSetup6();
	}
}

function GrouperSetup6() {
	global $groupersetup;
	?>
	<form action="groupersetup.php" method="post">
	<?php HiddenFields(7); ?>
	For security purposes, please change the access permissions on your grouper directory so that it is not writable by everyone.
	On a UNIX-based system, use the command shown below.
	Change the path if necessary, as you did when setting the access permissions before.
	Once you have changed the access permissions, click "Continue...".<br /><br />
		
	<code>chmod 711 <?php echo $groupersetup['chrincdir']; ?></code><br /><br />
	
	<input type="submit" value="Continue..." />
	</form>
	<?php
}

function GrouperSetupAskProxy() {
	global $groupersetup;
	?>
	<form action="groupersetup.php" method="post">
	<?php HiddenFields(2,2); ?>
	<table border="0" cellpadding="3" cellspacing="0" width="610">
	<tr><td colspan="3" style="color:white;background:#003399;">
		If the server where you are installing Grouper connects to the internet through a web proxy server, please enter the following.
		Otherwise, leave them blank.
	</td></tr>
	<tr>
		<td>Proxy server name:</td>
		<td>http://<input name="proxyserver" size="20"></td>
		<td>eg. www.myproxyserver.com</td>
	</tr>
	<tr>
		<td>Proxy server port:</td>
		<td><input name="proxyport" size="4" value="80"></td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan="3" style="color:white;background:#003399;">
		If the proxy server requires a username and password, enter them here.
		Otherwise, leave them blank.
	</td></tr>
	<tr>
		<td>Username:</td>
		<td><input name="proxyuser" size="20"></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input name="proxypass" size="20"></td>
		<td>&nbsp;</td>
	</tr>
	</table><p>

	<input type="submit" value="Continue...">
	</form>
	<?php
}

function GrouperSetup7() {
	global $groupersetup;
	if (isset($groupersetup['proxyserver'])) {
		$proxy=preg_replace("#/$#",'',$groupersetup['proxyserver']).(isset($groupersetup['proxyport'])&&($groupersetup['proxyport']!=80))?(':'.$groupersetup['proxyport']):'';
	} else $proxy=$groupersetup['proxyuser']=$groupersetup['proxypass']='';
	
	if (strlen($proxy)) {
		if (preg_match('/[^0-9.]/',$groupersetup['proxyserver'])) $ip=gethostbyname($groupersetup['proxyserver']);
		$rq='http://www.geckotribe.com/help/installtest.txt';
		$port=($groupersetup['proxyport']+0)?($groupersetup['proxyport']+0):80;
		$server=$groupersetup['proxyserver'];
	} else {
		$ip=gethostbyname('www.geckotribe.com');
		$rq='/help/installtest.txt';
		$port=80;
		$server='www.geckotribe.com';
	}
	if (preg_match('/[^0-9.]/',$ip)) {
		echo "<span class=\"fail\">DNS lookup of $server failed.</span><br />Grouper may be installed properly, but I am unable to confirm at this time.";
	} else {
		if ($tfp=fsockopen($ip,$port)) {
			fputs($tfp,"GET $rq HTTP/1.0\r\nHost: $server\r\nUser-Agent: GrouperInstaller/1.0\r\n");
			if (strlen($groupersetup['proxyuser']))
				fputs($tfp,'Proxy-Authorization: Basic '.base64_encode($groupersetup['proxyuser'].':'.md5($groupersetup['proxypass']))."\r\n");
			fputs($tfp,"\r\n");
			do { $l=fgets($tfp); } while (strlen(preg_replace("/[\r\n]/",'',$l))&&!feof($tfp));
			if (feof($tfp)) GrouperSetupAskProxy();
			else {
				$l=fgets($tfp);
				if (preg_match('/Installation Success/',$l)) GrouperSetup8();
				else GrouperSetupAskProxy();
			}
		} else GrouperSetupAskProxy();
	}
}

function GrouperSetup8() {
	global $groupersetup,$grouperconf,$groupersources,$grouperoldsources,$GrouperVersion;
	include_once $groupersetup['incdir'].'/grouper.php';
	if (isset($grouperconf)) {
		echo "I will now attempt to generate a newsfeed. The raw newsfeed data will appear below. It will not be converted to HTML, so it should appear as garbled text.<br />";
		echo '<div style="margin:15px;padding:6px;background:#ccc;border:1px solid:#333;">';
		GrouperConf('maxitems',2);
		GrouperConf('phperrors',E_ALL);
		GrouperConf('groupererrors',1);
		if (isset($groupersetup['proxyserver'])) {
			$proxy=preg_replace("#/$#",'',$groupersetup['proxyserver']).(isset($groupersetup['proxyport'])&&($groupersetup['proxyport']!=80))?(':'.$groupersetup['proxyport']):'';
		} else $proxy=$groupersetup['proxyuser']=$groupersetup['proxypass']='';
		if (strlen($proxy)) GrouperConf('proxyserver',$proxy);
		if (isset($groupersetup['proxyuser'])&&strlen($groupersetup['proxyuser'])) GrouperConf('proxyauth',$groupersetup['proxyuser'].':'.md5($groupersetup['proxypass']));
		GrouperConf('contenttype','');
		GrouperShow('rss feed');
		?>
		</div><br />
		If the box above is full of garbled text, then installation and setup were successful.
		If error messages were displayed, you will need to resolve them.<br /><br />
		
		To generate newsfeeds, create PHP pages containing code like the following.
		Add any desired configuration settings between the two lines shown.
		Change "cache_file.rss" to something different for each combination of search terms and configuration.<br /><br />
		
		For news searches, change "search terms" to the words you wish to search for.
		See the <a href="http://www.geckotribe.com/rss/grouper/manual/">Grouper documentation</a> for information about how to use different data sources,
			including those supported by <a href="http://www.geckotribe.com/rss/grouper/plugins/">Grouper Evolution Plugins</a>.<br /><br />

		For information on how to use CaRP to display the newsfeeds generated by Grouper, refer to the <a href="http://www.geckotribe.com/rss/grouper/manual/">Grouper documentation</a>.<br /><br />

		&lt;?php<br>
		require_once '<?php echo $groupersetup['incdir']; ?>/grouper.php';<br>
		<?php
		if (isset($groupersetup['proxyuser'])&&strlen($groupersetup['proxyuser'])) echo "GrouperConf('proxyauth',".$groupersetup['proxyuser'].':'.md5($groupersetup['proxypass']).");<br>\n";
		if (isset($proxy)&&strlen($proxy)) echo "GrouperConf('proxyserver',$proxy);<br>\n";
		echo "GrouperShow('search terms','cache_file.rss');<br>\n";
		echo "?&gt;\n";
		
		if ((isset($proxy)&&strlen($proxy))||(isset($groupersetup['proxyuser'])&&strlen($groupersetup['proxyuser']))||(isset($groupersetup['proxypass'])&&strlen($groupersetup['proxypass']))) {
			echo "<br>You may wish to specify the proxyserver and proxyauth settings in grouperconf.php rather than in every PHP file where you use Grouper.\n";
		}
	} else echo "An unexpected error occurred while attempting to load grouper.php. Please resolve this issue and then load the Grouper setup assistant again.";
}
?>
