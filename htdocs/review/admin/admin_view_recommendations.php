<?php  
session_start();

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

    // get the pager input values 
    $page = @$_GET['page'];
if (isset($_POST['limit'])) {
    $limit = $_POST['limit'];  
} elseif (isset($_GET['limit'])) {
$limit = $_GET['limit']; 
} else {
$limit = 5;
}

$_SESSION['limit'] = "$limit";
    $result = mysql_query("select count(*) from review_recommend");  
    $total = mysql_result($result, 0, 0);  
	
	if ($total < "1") { 
	BodyHeader("No recommendations have been made","","");
	echo "Sorry, no recommendations have been made"; BodyFooter(); exit; } 

    // work out the pager values 

    $pager = getPagerData($total, $limit, $page);    
    $offset = $pager->offset;  
    $limit  = $pager->limit;  
    $page   = $pager->page;  

    // use pager values to fetch data 

    $query = "select * from review_recommend order by send_date ASC limit $offset, $limit"; 

    $result = mysql_query($query) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


//Display the header
BodyHeader("View Recommendations");
?>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
<div align="center">
  <p class="style1">There are a total of <?php echo "$total"; ?> recommendations.  How many would you like to show on each page? </p>

<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>">
<select name=limit>
      <option value="5" selected>5</option>
      <option value="10">10</option>
      <option value="15">15</option>
      <option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="100">100</option>
    </select>
    <input type="submit" name="Submit" value="Show Me">
  </form>
</div> 
<hr noshade size=1> 
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 
  <tr> 
   <td><strong>Delete</strong></td> 
    <td><strong>Recipient</strong></td> 
    <td><strong>Recipient Email</strong></td> 
    <td><strong>Sender</strong></td>
    <td><strong>Sender Email </strong></td>
    <td><strong>Date</strong></td>
    <td><strong>Message</strong></td>
  </tr> 
  <?php 
while ($row = mysql_fetch_array($result)) { 
	$message = stripslashes($row["message"]);
	$rec_id = $row["rec_id"];
	$recipient = stripslashes($row["recipient"]);
	$rec_email = stripslashes($row["rec_email"]);
	$username = stripslashes($row["username"]);
	$send_date = stripslashes($row["send_date"]);
	$email = stripslashes($row["email"]);
?> 


  <tr> 
  <td><form action="admin_del_recommend2.php" method="post">
    <div align="center">
      <input name="rec_id" type="hidden" value="<?php echo "$rec_id"; ?>" />
    <input name="submit" type="submit" value="Delete" id="submit" />
    </div>
  </form></td> 
    <td><?php echo "$recipient"; ?></td> 
    <td><a href=mailto:<?php echo "$rec_email"; ?>><?php echo "$rec_email"; ?></a></td> 
    <td><?php echo "$username"; ?></td>
    <td><a href=mailto:<?php echo "$email"; ?>><?php echo "$email"; ?></a></td>
    <td><?php echo "$send_date"; ?></td>
    <td><?php echo "$message"; ?></td>
  </tr> 
  <?php } ?> 
</table> 
<div align="center"><br />
<a href="admin_recommend_excel.php">Download</a> into Microsoft Excel</div>
<div align="center">
  <p>    <BR>
    <?php
 // output paging system (could also do it before we output the page content) 
    if ($page == 1) // this is the first page - there is no previous page 
        echo "";  
    else            // not the first page, link to the previous page 
        echo "<a href=\"admin_view_recommendations.php?page=" . ($page - 1) . "&limit=$limit\">Previous</a> | ";  

    for ($i = 1; $i <= $pager->numPages; $i++) {  
        if ($i > 1) echo " | ";   
        if ($i == $pager->page)  
            echo "Page $i";  
        else  
            echo "<a href=\"admin_view_recommendations.php?page=$i&limit=$limit\">Page $i</a>";  
    }  

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "";  
    else            // not the last page, link to the next page 
        echo " | <a href=\"admin_view_recommendations.php?page=" . ($page + 1) . "&limit=$limit\">Next</a><BR><BR>  <p>Back to <a href=\"admin_menu.php?<?php echo htmlspecialchars(SID); ?>\">Menu</a> </p>";  

BodyFooter();

?> 
  </p>
</div>
