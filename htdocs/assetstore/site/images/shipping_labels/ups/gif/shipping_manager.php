<?
$username="root";
$password="s1a2mayfly";
$database="test_site2";



$fname=$_POST['fname'];
$lname=$_POST['lname'];
$last=$_POST['company'];
$phone=$_POST['phone'];
$mobile=$_POST['mobile'];
$fax=$_POST['fax'];
$email=$_POST['email'];
$web=$_POST['web'];


?>

<input type="hidden" name="fname" value="[loop-data transactions fname]" />
<input type="hidden" name="lname" value="[loop-data transactions lname]" />
<input type="hidden" name="company2" value="[loop-data transactions company]" />
<input type="hidden" name="address1" value="[loop-data transactions address1]" />
<input type="hidden" name="address2" value="[loop-data transactions address2]" />
<input type="hidden" name="city" value="[loop-data transactions city]" />
<input type="hidden" name="state" value="[loop-data transactions state]" />
<input type="hidden" name="zip" value="[loop-data transactions zip]" />
<input type="hidden" name="country" value="[loop-data transactions country]" />
<input type="hidden" name="order_number" value="[loop-data transactions order_number]" />
<input type="hidden" name="shipmode" value="[loop-data transactions shipmode]" />
<input type="hidden" name="email" value="[loop-data transactions email]" />
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <th scope="col">
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <th colspan="2" scope="col">Customer Shipping Information [loop-data transactions fname]  </th>
        <th width="49%" scope="col">Package Details </th>
      </tr>
      <tr>
        <td width="12%">Name:</td>
        <td width="39%">
          <label>
          <input name="name" type="text" id="name" value="<? echo $fname; ?> <? echo $lname; ?> " />
            </label>
        
        </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Company:</td>
        <td><input name="company" type="text" id="company" value="[loop-data transactions company]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Address:</td>
        <td><input name="textfield22" type="text" value="[loop-data transactions address1]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Address2:</td>
        <td><input name="textfield23" type="text" value="[loop-data transactions address2]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>City:</td>
        <td><input name="textfield24" type="text" value="[loop-data transactions city]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>State:</td>
        <td><input name="textfield25" type="text" value="[loop-data transactions state]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Zip:</td>
        <td><input name="textfield26" type="text" value="[loop-data transactions zip]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Phone:</td>
        <td><input name="textfield27" type="text" value="[loop-data transactions phone]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input name="textfield28" type="text" value="[loop-data transactions email]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Order#: </td>
        <td><input name="textfield29" type="text" value="[loop-data transactions order_number]" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Residential:</td>
        <td>
          <select name="select">
          </select>
      
        </td>
        <td>&nbsp;</td>
      </tr>
    </table></th>
  </tr>
</table>

<!-- ----- END REAL STUFF ----- -->

