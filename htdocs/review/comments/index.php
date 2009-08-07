<?php
session_start();

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$review_id = clean($_GET['review_id']);

BodyHeader("Comments","","");
?>

<body onLoad="prepare_entries();">


<script LANGUAGE="JavaScript" src="comments.js"></script>
<script LANGUAGE="JavaScript" src="XHConn.js"></script>
<link href="comments.css" rel="stylesheet" type="text/css">
  
<div id="borderbox">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#FFFFFF">
	<div style="padding:10px;"> 
        <table width="600" height="20" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid #C1C1C1;">
          <tr valign="top"> 
            <td width="35" height="25">
            </td>
            <td> 
             <div class="comments_header"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                  <tr>
    <td width="22%"> 
                <div align="left"><span class="comments_header_text"> <a href="#" onClick="showPost(this, 1);" id="postLink">Post 
                  Comment</a>  </span> 
              </div></td>
    <td width="78%"><span class="comments_header_text"> <span id="pageInfo"></span></span></td>
  </tr>
</table>
</div>
			</td>
          </tr>
        </table>
       
<div id="postcomment">
        <form action="" method="get" enctype="multipart/form-data" style="margin:0px;">
        <input name="review_id" type="hidden" id="review_id" value="<?php echo "$review_id"; ?>" />
            <table width="600" border="0" cellspacing="5" cellpadding="0" class="forms_table">
              <tr> 
        <td width="103" valign="top"><div align="right">Comment:</div></td>
        <td width="503"><textarea name="comment" cols="50" rows="4" id="comment" class="forms_textfield"></textarea></td>
      </tr>
      <tr> 
        <td valign="top"><div align="right">Name:</div></td>
        <td><input name="author" type="text" id="author" class="forms_textfield">
        </td>
      </tr>
      <tr> 
        <td valign="top"><div align="right"></div></td>
        <td><input type="button" name="Button" value="Post" onClick="verify_new();" class="forms_button">
          <input name="page" type="hidden" id="page" value="<?php if ($_GET['page'] == '') echo 1; else echo $_GET['page']; ?>"></td>
      </tr>
    </table>

    </form>
</div>
<div id="currentEntries"></div>
</div>
	</td>
  </tr>
</table>

<p>&nbsp;</p>
<div align="center">Back to <a href=<?php echo "../$back"; ?>><?php echo "$item_name"; ?></a></div></div>
<?php
BodyFooter();
exit;
?>

