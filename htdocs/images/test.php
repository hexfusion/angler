

 
       
  
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

      <head>
  
       
  
      <script language="javascript" type="text/javascript">
   
      function showLoading()
  
      {
  
           document.getElementById('imageUpload').style.display = "none";
  
           document.getElementById('imageLoading').style.display = "block";
 
      }
  
      </script>   
 
       
 
      </head>
  
      <body>       
 
   <div id="imageUpload">
 
      <form name="form1" method="post" action="" enctype="multipart/form-data" onsubmit="return showLoading();">
 
      <table>

           <tr><td style="text-align: left; vertical-align:top;">Image:</td> <td><input type="file" id="imagefile" name="imagefile" /></td></tr>

           <tr><td style="text-align:center;" colspan="2"><input type="submit" name="Submit" value="Submit" /></td></tr>
 
      </table>

      <?php

      if(isset( $Submit )) //If the Submit button was pressed
 
      {

           if ($_FILES['imagefile']['type'] == "image/jpg" || $_FILES['imagefile']['type']['error'] == "image/jpeg")
 
           {
  
                copy ($_FILES['imagefile']['tmp_name'], "/upload/".$_FILES['imagefile']['name'] ['error']) or die ("Could not copy");
 
                     $imageDir = getcwd()."/upload/";
 
                     $imageThumbDir = $imageDir."/upload/thumbs/";

                     $imageNormalDir = $imageDir."/upload/images/";

                     $image = $_FILES['imagefile']['name']['error'];
 
                     //Thumbs
 
                     system("convert ".$imageDir.$image." -resize 100�100 ".$imageThumbDir.$image."  2>&1");
 
                     //Normal size images

                     system("convert ".$imageDir.$image." -resize 300�300 ".$imageNormalDir.$image."  2>&1");
 
                          

                echo "

      New Item Added.
 
      ";

                echo "Thumbnail

      <img src=\"uploads/thumbs/".$image."\">
 
      <a href=\"uploads/thumbs/$image\">uploads/thumbs/".$image."</a>";
  
                echo "
  
       
 
      Normal Size
 
      <img src=\"uploads/images/".$image."\">
 
      <a href=\"uploads/images/$image\">uploads/images/".$image."</a>";
 
                echo "
  
       
  
      Original
 
      <img src=\"uploads/".$image."\">
 
      <a href=\"uploads/$image\">uploads/".$image."</a>";
  
           } else {
 
                echo "

       
 
      ";
  
                echo "Could Not Copy, Wrong Filetype (".$_FILES['imagefile']['name'].")
 
      ";
  
           }
  
      }
  
      ?>
 
      </form>
 
      </div>
  
      <div id="imageLoading" style="display:none;">
  
      <p>Please Wait�</p>
 
      <img src='http://www.qsopht.com/wordpress/wp-content/uploads/2007/04/loading.gif' alt='loading' />
  
      </div>
 
       
  
      </body>
 
      </html>
