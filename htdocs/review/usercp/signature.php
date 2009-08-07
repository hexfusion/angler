<?php
session_start();

/* if ($use_phpBB == "yes") {
include_once("../phpbb_include.php");   
} elseif ($use_vb == "yes") {
include_once("../vbulletin_include.php");
}//end use_vb */

if ($_SESSION['signedin'] != "y" && $use_vb == "no")
	{
	// User not logged in, redirect to login page
	Header("Location: ../login/signin.php");
	}

include ("../body.php");
include ("../config.php");
BodyHeader("Add/Update Signature");
?>
<form name="form1" method="post" action="signature2.php?<?php echo htmlspecialchars(SID); ?>">
<table width="767" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>Signature:</td>
    <td>
      <textarea name="sig" id="sig"></textarea>
    </td>
  </tr>
  <tr>
    <td>Url:</td>
    <td><input name="sig_url" type="text" id="sig_url"></td>
  </tr>
</table>
<p align="center">
  <input type="submit" name="Submit" value="Submit">
</p>
</form>
</body>
</html>
