<script language="JavaScript">
<!--
   // alert before session end
   var remainingseconds=@@@REMAININGSECONDS@@@;
   var url = "@@@PREFSURL@@@?action=timeoutwarning&sessionid=@@@SESSIONID@@@&session_noupdate=1";
   var tid;
   var hWnd;
   var warn=0;

   function timeoutcheck () {
      remainingseconds=remainingseconds-1;
      if (remainingseconds>0) {
         tid=setTimeout("timeoutcheck()", 1*1000);
         if (remainingseconds<65) {
            if (remainingseconds>6) {
               window.defaultStatus="sesjonstid : "+ (remainingseconds-5) +" sekunder igjen";
            } else if (remainingseconds>=5) {
               window.defaultStatus="sesjonstid : "+ (remainingseconds-5) +" sekunder igjen";
            }
            if (!warn) {
               warn=1;
               @@@JSCODE@@@
               hWnd = window.open(url,"_timeoutwarning","width=400,height=140,resizable=no,scrollbars=no");
               hWnd.focus();
            }
         }
      } else {
         window.defaultStatus="sesjonstiden er utl�pt";
         clearTimeout(tid);
      }
   }

   function sessioncheck () {
      if (remainingseconds>0) {
         return true;
      } else {
         alert("Sorry, your session is timeouted, please login again.");
         return false;
      }
   }

   timeoutcheck();
//-->
</script>
