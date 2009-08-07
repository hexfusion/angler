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

include_once "settings.php";
include_once "include.php";
include_once "popwrapper.php";
include_once "email-parse.php";

$res = mysql_query( "SELECT * FROM {$pre}pop" );
$pop3 = new POP3( );

$error = array( );

while( $row = mysql_fetch_array( $res ) )
{
  if( $pop3->connect( $row[server], $row[port] ) )
  {
    $count = $pop3->login( $row[username], $row[password] );
    if( $count === false )
      array_push( $error, "<b>[ERROR]</b> Could not login to {$row[server]} on port {$row[port]}" );
    else
    {
      for( $i = 1; $i <= $count; $i++ )
      {
        $email = $pop3->get( $i );
        if( $email && count( $email ) )
        {
          $content = "";
          for( $j = 0; $j < count( $email ); $j++ )
            $content .= $email[$j];          

          parse_email_to_ticket( $content );

          if( $row[del] )
            $pop3->delete( $i );
        }
      }
      
      array_push( $error, "<b>[SUCCESS]</b> Retrieved $count messages from {$row[server]}" );
    }
  }
  else
    array_push( $error, "<b>[ERROR]</b> Could not connect to {$row[server]} on port {$row[port]}" );

  $pop3->quit( );
}

/********************************************************** PHP */?>
