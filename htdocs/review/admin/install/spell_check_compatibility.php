<?php
$i = 0;

function check_prereqs()
{
  if(version_compare(phpversion(), '4.3.0') < 0)
  {
  $i++;
    echo "PHP 4.3.0 or greater is required.  You can not use the spell check feature unless you upgrade to a newer version of php.<br />
<br />
";
    exit;
  }
  if(!extension_loaded('pcre'))
  {
  $i++;
    echo "pcre extension is required.  You can not use the spell check feature unless you have the pcre extension enabled.  Contact your webhost.<br />
<br />";
    exit;
  }
  if(!extension_loaded('pspell'))
  {
  $i++;
    echo "pspell extension is required.  You can not use the spell check feature unless you have access to the pspell extension.  Contact your webhost.<br />
<br />";
 exit;
  }
}

check_prereqs();

if ($i == "0") { echo "Your server meets the requirements for using the spell checker!";
exit;
}
?>