<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
<?php
 $search_term = (isset($_GET['words']) ? htmlspecialchars(stripslashes($_REQUEST['words'])) : '');
  $normal = (($_GET['mode'] == 'normal') ? ' selected="selected"' : '' );
  $boolean = (($_GET['mode'] == 'boolean') ? ' selected="selected"' : '' );
  
  echo '<form method="get" action="index.php">';
  echo '<input type="hidden" name="cmd" value="search" />';
  echo 'Search for: <input type="text" name="words" value="'.$search_term.'" />&nbsp;';
  echo 'Mode: ';
  echo '<select name="mode">';
  echo '<option value="normal"'.$normal.'>Normal</option>';
  echo '<option value="boolean"'.$boolean.'>Boolean</option>';
  echo '</select>&nbsp;';
  echo '<input type="submit" value="Search" />';
  echo '</form>';

?>
</body>
</html>
