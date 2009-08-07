<?php 
require("Sajax2.php"); 

// the PHP function we will be exporting to JavaScript: 

function server_php() { 
echo "Hello World...!!!!"; 
} 

sajax_init(); 
sajax_export("server_php"); // list of functions to export 
sajax_handle_client_request(); // serve client instances 

?> 

<html><title>SAJAX Example - Hello World</title> 
<head> 
<script type="text/javascript"> 
   // <![CDATA[ 
   <?php
   sajax_show_javascript(); 
   ?> 
        function set_result(result) { 
document.getElementById('_show_up_here_').innerHTML = result; 
       } 
    function jsData() { 
var theVariable; 
y = document.getElementById('_show_up_here_').innerHTML; 
                x_server_php(set_result); 
    } 

    
   // ]]> 
   </script> 
</head> 
<body> 
<!-- The Javascript that sends its http request to SAJAX will be displayed below //--> 
<div id="_show_up_here_">Basic SAJAX Example</div> 
<hr /> 
 <a  href="#" onclick="jsData();">Click Me to test SAJAX</a> 
</body> 
</html>