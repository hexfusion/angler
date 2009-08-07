<?php
include ("../functions.php");
include ("../f_secure.php");
$query = "SELECT * FROM review_recommend";
$result = mysql_query($query); 
    $count = mysql_num_fields($result); 

    for ($i = 0; $i < $count; $i++) 
        { 
        if (isset($header)) 
            $header .= mysql_field_name($result, $i)."\t"; 
            else 
                $header = mysql_field_name($result, $i)."\t"; 
        } 

    while ($row = mysql_fetch_row($result)) 
        { 
        $line = ''; 

        foreach ($row as $value) 
            { 
            if (!isset($value) || $value == '') 
                $value = "\t"; 
                else 
                    { 
                    $value = str_replace('"', '""', $value); 
                    $value = '"'.$value.'"'."\t"; 
                    } 

            $line .= $value; 
            } 

        if (isset($data)) 
            $data .= trim($line)."\n"; 
            else 
                $data = trim($line)."\n"; 
        } 

    $data = str_replace("\r", "", $data); 

    if ($data == '') 
        $data = "\nno matching records\n"; 

    header("Content-Type: application/vnd.ms-excel; name='excel'"); 
    header("Content-type: application/octet-stream"); 
    header("Content-Disposition: attachment; filename=review_recommend.xls"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 

    echo $header."\n".$data; 
	exit;
?>
