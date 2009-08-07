<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

BodyHeader("Add a New Category","","");

?>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>

<p align="center"><strong><font size="4">Add a Category</font> </strong></p>
<form name="form1" method="post" action="admin_add_cat2.php?<?php echo htmlspecialchars(SID); ?>">
  <p>&nbsp;</p>
  <table width="464" border="0" cellspacing="5" cellpadding="5" align="center">
    <tr> 
      <td width="234" valign=top><div align="right"><strong>Category:</strong></div></td>
      <td width="230" valign=top> <input name="category" type="text" id="category" size="30"></td>
    </tr>
    <tr>
      <td align="right" valign="top"></td>
      <td>



<tr>
      <td align="right" valign="top"><strong>Parent:</strong></td>
      <td>
	  
 <select name="parent">
  <option value=""> -- Make it a Sub-Category of... -- </option>

<?
	//
function displayCat($catId,$dashes=0){
$st="";
if($catId==""){
	$query='Select * from review_category_list where parent=""';
	$dashes=0;
}else{
	//die('Select * from review_category_list where parent IN(' .$catId .')');
	$query='Select * from review_category_list where parent IN(\'' .$catId .'\')';
		for($i=0;$i<$dashes;$i++){
		$st.="--";
	}
}
$dashes+=2;

$result=mysql_query($query);

while($row=mysql_fetch_array($result)){
	echo "<option value='" .$row["category"]."'>" .$st . $row["category"] . "</option>";
	displayCat($row["category"],$dashes);
}
}
displayCat("");
?>
</select>	  
  </td>
    </tr>



      </td>
    </tr>
    <tr> 
      <td align=center colspan=2> <p>&nbsp;
        </p>
        <p>
          <INPUT type="submit" class=submit onclick="MM_validateForm('category','','R');return document.MM_returnValue" value="Insert Category"> 
        </p></td>
    </tr>
  </table>
  <div align="center"><br />
  </div>
</form>
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
exit;
?>