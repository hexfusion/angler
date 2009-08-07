<?/* PHP **********************************************************/

////////////////////////////////////////////////////////////////////
// InverseFlow Help Desk v2.01
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

$_GET[file] = str_replace( "..", "", $_GET[file] );

$res = mysql_query( "SELECT id FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' && email = '{$_GET[email]}' )" );
$row = mysql_fetch_array( $res );
if( $row )
{
  Header( "Content-type: application/octet-stream" );
  Header( "Content-disposition: inline; filename={$_GET[file]}" ); 

  $fp = @fopen( "{$HD_TICKET_FILES}/{$row[id]}/{$_GET[file]}", "r" );
  if( $fp )
  {
    while( !feof( $fp ) )
      echo fread( $fp, 10240 );

    fclose( $fp );
  }
}

/********************************************************** PHP */?>