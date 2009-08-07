<html>
<head>
	<title>Grouper Setup Assistant</title>
	<style type="text/css">
	.fail {
		font-weight:bold;
		color:#c00;
	}
	body, td {
		font-family:Verdana,Arial,Helvetica,sans-serif;
		font-size:10pt;
	}
	ul {
		margin:0;
	}
	</style>
</head>
<body bgcolor="white">
<b>Grouper Setup Assistant</b><br /><br />

<?php
foreach ($_POST as $k => $v) $groupersetup[$k]=$v;
if (isset($groupersetup['step'])) $groupersetup['step']+=0;
else $groupersetup['step']=0;

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set(display_errors,1);

if (!isset($groupersetup['incdir'])) {
	for ($path=dirname(__file__);strlen($path)&&!isset($groupersetup['incdir']);$path=substr($path,0,strrpos($path,'/'))) {
		if (file_exists("$path/grouper/groupersetupinc.php")) $groupersetup['incdir']="$path/grouper";
	}
}
if (!isset($groupersetup['incdir'])) {
	if (file_exists('/www/grouper/groupersetupinc.php')) $groupersetup['incdir']='/www/grouper';
}
if (isset($groupersetup['incdir'])&&file_exists($groupersetup['incdir'].'/groupersetupinc.php')) {
	if (!isset($groupersetup['chrincdir'])) $groupersetup['chrincdir']=$groupersetup['incdir'];
	include $groupersetup['incdir'].'/groupersetupinc.php';
	call_user_func('grouperSetup'.$groupersetup['step']);
} else {
	?>
	<form action="groupersetup.php" method="post">
	I was unable to find groupersetupinc.php<?php if (isset($groupersetup['incdir'])) echo ' in the directory '.$groupersetup['incdir']; ?>.
	Please enter the path to the directory containing groupersetupinc.php below.<br /><br />

	<input name="incdir" size="40" value="<?php echo isset($groupersetup['incdir'])?$groupersetup['incdir']:dirname(__file__); ?>" /><br />
	<input type="submit" value="Continue..." /><br /><br />
	
	NOTES:
	<ul>
	<li>Depending on your server's configuration, the directory path may look different to me that it does when you log in to your server.
		The path to this file (groupersetup.php) as I see it is <?php echo __file__; ?>.
		Please take that into account when entering the path above.</li>
	<li>If you see an error message about a safe mode violation, you may need to move the grouper directory into your web directory.</li>
	</form>
	<?php
}
?>
</body>
</html>
