<?/* PHP **********************************************************/

////////////////////////////////////////////////////////////////////
// InverseFlow Help Desk v2.2
// -----------------------------------------------------------------
// 
// LICENSE INFO:
// -----------------------------------------------------------------
// This file can be modified and used only within
// the domain name(s) for which this help desk was purchased.
// You may not distribute or sell this help desk in its
// present form or after making your own
// modifications.  Please contact InverseFlow
// with any questions.
// -----------------------------------------------------------------
// Copyright © 2002-2003 InverseFlow
////////////////////////////////////////////////////////////////////

include "settings.php";
include "include.php";
include "email-parse.php";

$stdin = fopen( "php://stdin", "r" );

while( !feof( $stdin ) )
  $email .= fread( $stdin, 10240 );

fclose( $stdin );

return parse_email_to_ticket( $email );

/********************************************************** PHP */?>
