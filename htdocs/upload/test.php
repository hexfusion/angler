

 
       
  
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

      <head>
  
       
  
      <script language="javascript" type="text/javascript">
   
      function showLoading()
  
      {
  
           document.getElementById(�imageUpload�).style.display = "none";
  
           document.getElementById(�imageLoading�).style.display = "block";
 
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

           if ($_FILES[�imagefile�][�type�] == "image/jpg" || $_FILES[�imagefile�][�type�] == "image/jpeg")
 
           {
  
                copy ($_FILES[�imagefile�][�tmp_name�], "images/upload/".$_FILES[�imagefile�][�name�]) or die ("Could not copy");
 
                     $imageDir = getcwd()."/image/upload/";
 
                     $imageThumbDir = $imageDir."thumbs/";

                     $imageNormalDir = $imageDir."images/";

                     $image = $_FILES[�imagefile�][�name�];
 
                     //Thumbs
 
                     system("convert ".$imageDir.$image." -resize 100�100 ".$imageThumbDir.$image."  2>&1");
 
                     //Normal size images

                     system("convert ".$imageDir.$image." -resize 300�300 ".$imageNormalDir.$image."  2>&1");
 
                          

                echo "New Item Added.
 
      ";

                echo "Thumbnail

      <img src=\"upload/thumbs/".$image."\">
 
      <a href=\"upload/thumbs/$image\">upload/thumbs/".$image."</a>";
  
                echo "Normal Size
 
      <img src=\"upload/images/".$image."\">
 
      <a href=\"upload/images/$image\">upload/images/".$image."</a>";
 
                echo "Original
 
      <img src=\"upload/".$image."\">
 
      <a href=\"upload/$image\">upload/".$image."</a>";
  
           } else {
 
                echo "

       
 
      ";
  
                echo "Could Not Copy, Wrong Filetype (".$_FILES[�imagefile�][�name�].")
 
      ";
  
           }
  
      }
  
      ?>
 
      </form>
 
      </div>
  
      <div id="imageLoading" style="display:none;">
  
      <p>Please Wait�</p>
 
      <img src=�http://www.qsopht.com/wordpress/wp-content/upload/2007/04/loading.gif� alt=�loading� />
  
      </div>
 
       
  
      </body>
 
      </html>
