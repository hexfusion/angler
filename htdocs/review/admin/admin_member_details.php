<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page	
                    header("Location: index.php");}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$id= makeStringSafe($_GET['id']);

$sqlaccess = "SELECT *
		FROM  review_users
		WHERE id='$id'
		";
//echo "$sqlaccess";  exit;
	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
$email = stripslashes($row['email']);
$username = stripslashes($row['username']);
$active= stripslashes($row['active']);
$name= stripslashes($row['name']);
$id= stripslashes($row['id']);
$created= stripslashes($row['created']);
} //while

//prepare date in a friendly format
//$created = date('l, F j, Y \a\t h:i A');



	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!","","");
?>
</p>
<link href="../style_mem.css" rel="stylesheet" type="text/css" />
<p align="center"></p>
<p align="center"><span class="box1">The user was not found. Please push the back button in your browser to proceed.</span></p>
<p align="center"></p>
<p align="center"><br />
  <?php BodyFooter();  
exit;  
}


BodyHeader("$username's Profile","","");

//$_SESSION['name'] = "$name";
?>
</p>
<div align="center">
  <fieldset>
  <p>
    <legend><span class="style3"><?php echo "$sitename"; ?></span><br />
    <span class="index2-summary"><?php echo ucfirst($username); ?>'s Information</span><br />
    </legend>
  </p>
          <p align="left" class="medium"><b class="style3">Username:</b> <?php echo ucfirst($username); ?> <br />
          <b class="style3"> Name:</b> <?php echo ucfirst($name); ?> <br />
<b class="style3">E-mail:</b> <a 
href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
                          <b class="style3">Join Date:</b> <?php echo "$created"; ?> <br />
                          <br />
  </p>
          <form id="form1" name="form1" method="post" action="admin_edit_user2.php?<?php echo htmlspecialchars(SID); ?>">
            <input name="sel_record" type="hidden" id="sel_record" value="<?php echo "$id"; ?>" /><br />
<br />

                              <input type="submit" name="Submit" value="Edit <?php echo "$username"; ?>" />
                            
  </form>
          <p align="left" class="medium"><br />
            <br />
                  </p>
  <div align="center" class="medium">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Admin Menu</a> or <a href="admin_member_report.php?<?php echo htmlspecialchars(SID); ?>">Member Report</a></div>
  </fieldset>
</div>
<?php
BodyFooter();
exit;
?>
