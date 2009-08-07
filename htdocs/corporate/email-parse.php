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
// Copyright � 2002-2003 InverseFlow
////////////////////////////////////////////////////////////////////

// Richard Heyes' MIME decoding PHP script.  This would have been
// very difficult to do without... thanks!
include "mimedecode.php";

function get_message_parts( $structure )
{
  global $email_parts;

  echo $HD_TICKET_FILES;

  if( isset( $structure->body ) )
  {
    $part = array( "body"=>$structure->body, "type_primary"=>$structure->ctype_primary, "type_secondary"=>$structure->ctype_secondary, "parameters"=>$structure->ctype_parameters, "dparameters"=>$structure->d_parameters, "headers"=>$structure->headers );

    array_push( $email_parts, $part );
  }
  
  if( isset( $structure->parts ) )
  {
    for( $i = 0; $i < count( $structure->parts ); $i++ )
      get_message_parts( $structure->parts[$i] );
  }

}

function parse_email_to_ticket( $email )
{
  global $pre;
  global $email_parts;
  global $HD_TICKET_FILES;
 
  $options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter", "email_ticket_created_subject", "email_ticket_created", "email_notify_create_subject", "email_notify_create", "email_notify_reply_subject", "email_notify_reply", "floodcontrol", "email_notifysms_create_subject", "email_notifysms_create", "email_notifysms_reply_subject", "email_notifysms_reply" );
  $data = get_options( $options );

  if( strpos( $email, "\r" ) === false )
    $crlf = "\n";
  else
    $crlf = "\r\n";

  $params = array( 'input'          => $email,
           			   'crlf'           => $crlf,
                   'include_bodies' => TRUE,
                   'decode_headers' => TRUE,
                   'decode_bodies'  => TRUE );

  $structure = Mail_mimeDecode::decode( $params, $crlf );
  if( !$structure )
  {
    $res = mysql_query( "SELECT email FROM {$pre}user WHERE ( admin = '1' )" );
    $row = mysql_fetch_array( $res );
    if( $row )
    {
      mail( $row[0], "Help Desk - Failed email", "The following email could not be received by the help desk due to an error.  It has been forwarded to you to make sure it won't be lost:\n\n{$email}", "From: {$data[email]}" );
     
      return false;
    }
  }
    
  $email_parts = array( );
  get_message_parts( $structure );

  $i = count( $structure->headers[received] ) - 1;
  if( $i >= 0 )
  {
    if( preg_match( "/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $structure->headers[received][$i], $match ) )
      $ip = $match[0];
    else
      $ip = "";
  }
  else
    $ip = "";

  $subject = $structure->headers[subject];
  if( preg_match( "/^([^<]+)<([^>]+)>/i", $structure->headers[from], $match ) )
  {
    $name = str_replace( "\"", "", $match[1] ); // Get rid of any quotes
    $email = $match[2];
  }
  else if( preg_match( "/^<?([^>]+)>?/i", $structure->headers[from], $match ) )
    $name = $email = $match[1];

  if( preg_match( "/^([^<]+)<([^>]+)>/i", $structure->headers[to], $match ) )
    $to = $match[2];
  else if( preg_match( "/^<?([^>]+)>?/i", $structure->headers[to], $match ) )
    $to = $match[1];

  $message = "";
  for( $i = 0; $i < count( $email_parts ); $i++ )
  {
    if( ($email_parts[$i][type_primary] == "text" && $email_parts[$i][type_secondary] == "plain") ||
        ($email_parts[$i][type_primary] == "plain" && $email_parts[$i][type_secondary] == "text") )
      $message = addslashes( $email_parts[$i][body] );

    // Replaces all CID's in HTML files, etc., with the file name that will exist in the directory.  That way
    // HTML files will show images and everything.  Pretty cool stuff...
    else if( isset( $email_parts[$i]["headers"]["content-id"] ) )
    {
      $search = "cid:" . trim( $email_parts[$i]["headers"]["content-id"] );
      $search = str_replace( "<", "", $search );
      $search = str_replace( ">", "", $search );

      for( $j = 0; $j < count( $email_parts ); $j++ )
      {
        if( $j != $i )
        {
          if( isset( $email_parts[$i][parameters][name] ) )
            $email_parts[$j][body] = str_replace( $search, $email_parts[$i][parameters][name], $email_parts[$j][body] );
          else if( isset( $email_parts[$i][dparameters][filename] ) )
            $email_parts[$j][body] = str_replace( $search, $email_parts[$i][dparameters][filename], $email_parts[$j][body] );
        }
      }
    }
  }

  // Make sure slashes are added so this stuff can be put in a SQL query
  $name = addslashes( trim( $name ) );
  $email = addslashes( trim( $email ) );
  $subject = addslashes( trim( $subject ) );
  $to = addslashes( trim( $to ) );

  if( trim( $name ) == "" )
    $name = "No Name";

  $priority = $GLOBALS[PRIORITY_LOW];

  $res = mysql_query( "SELECT dept.id, dept.name FROM {$pre}dept AS dept, {$pre}pop AS pop WHERE ( pop.dept_id = dept.id && pop.email = '$to' ) LIMIT 1" );
  if( $row = mysql_fetch_array( $res ) )
  {
    $dept_id = $row[0];
    $department = $row[1];
  }
  else
  {
    $dept_id = 0;
    $department = "Global";
  }

  // Check to see if this is a reply to a ticket, not a new ticket
  $subject_words = split( " ", $subject );
  $exists = 0;
  for( $i = 0; $i < count( $subject_words ); $i++ )
  {
    if( trim( $subject_words[$i] ) != "" )
    {
      $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}ticket WHERE ( ticket_id = '{$subject_words[$i]}' && email = '$email' )" );
      if( $exists )
      {
        $ticket = $subject_words[$i];
        break;
      }
    }
  }

  if( !$exists )
  {
    // Checks for a duplicate ticket if flood control is enabled
    if( $data[floodcontrol] )
    {
      $res_check = mysql_query( "SELECT id, ticket_id FROM {$pre}ticket WHERE ( name = '$name' && email = '$email' && subject = '$subject' )" );
      while( $row_check = mysql_fetch_array( $res_check ) )
      {
        $res_check_post = mysql_query( "SELECT message FROM {$pre}post WHERE ( ticket_id = '{$row_check[id]}' && user_id = '-1' ) ORDER BY date LIMIT 1" );
        $row_check_post = mysql_fetch_array( $res_check_post );

        if( trim( $row_check_post[message] ) == trim( stripslashes( $message ) ) )
          return false;
      }
    }

    $ticket = strtoupper( base_convert( time( ), 10, 16 ) );
    if( get_row_count( "SELECT COUNT(*) FROM {$pre}ticket WHERE ( ticket_id = '$ticket' )" ) )
    {
      $res = mysql_query( "SELECT ticket_id FROM {$pre}ticket ORDER BY ticket_id DESC LIMIT 1" );
      $row = mysql_fetch_array( $res );
      $ticket = strtoupper( base_convert( base_convert( $row[0], 16, 10 ) + 1, 10, 16 ) );
    }

    mysql_query( "INSERT INTO {$pre}ticket ( ticket_id, dept_id, email, name, subject, date, status, notify, priority, lastactivity ) VALUES ( '$ticket', '$dept_id', '$email', '$name', '$subject', '" . time( ) . "', '$HD_STATUS_OPEN', '1', '$priority', '" . time( ) . "' )" );

    $id = mysql_insert_id( );

    mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message, ip ) VALUES ( '$id', '-1', '" . time( ) . "', '$subject', '$message', '$ip' )" );

    $post_id = mysql_insert_id( );

    $autoreply = "";
    $res = mysql_query( "SELECT reply, phrase FROM {$pre}reply WHERE ( dept_id = '0' || dept_id = '$dept_id' )" );
    while( $row = mysql_fetch_array( $res ) )
    {
      if( $row[phrase] == "" )
      {
        $autoreply = "{$row[reply]}\n\n";
        break;
      }
      else if( strstr( strtoupper( $subject, strtoupper( $row[phrase] ) ) ) )
      {
        $autoreply = "{$row[reply]}\n\n";
        break;
      }
    }
     
    eval( "\$email_subject = \"{$data[email_ticket_created_subject]}\";" );
    eval( "\$email_message = \"{$data[email_ticket_created]}\";" );
    mail( $email, $email_subject, $email_message, "From: {$data[email]}" );

    // Notification messages
    $res_user = mysql_query( "SELECT DISTINCT user.email, user.sms FROM {$pre}user AS user, {$pre}privilege AS priv WHERE ( user.id = priv.user_id && (priv.dept_id = '0' || priv.dept_id = '$dept_id') && user.notify & {$GLOBALS[HD_NOTIFY_CREATION]} > '0' )" );
    
    while( $row_user = mysql_fetch_array( $res_user ) )
    {
      eval( "\$email_subject = \"{$data[email_notify_create_subject]}\";" );
      eval( "\$email_message = \"{$data[email_notify_create]}\";" );
      mail( $row_user[email], $email_subject, $email_message, "From: {$data[email]}" );

      if( trim( $row_user[sms] ) != "" )
      {
        eval( "\$email_subject = \"{$data[email_notifysms_create_subject]}\";" );
        eval( "\$email_message = \"{$data[email_notifysms_create]}\";" );
        mail( $row_user[sms], $email_subject, $email_message, "From: {$data[email]}" );
      }
    }
  }
  else
  {
    $res = mysql_query( "SELECT id FROM {$pre}ticket WHERE ( ticket_id = '$ticket' )" );
    $row = mysql_fetch_array( $res );
    $id = $row[0];

    // Checks for a duplicate posting if flood protection is enabled
    $res_check = mysql_query( "SELECT subject, message FROM {$pre}post WHERE ( ticket_id = '$id' ) ORDER BY date DESC LIMIT 1" );
    $row_check = mysql_fetch_array( $res_check );
    if( $data[floodcontrol] && (trim( $row_check[message] ) == trim( stripslashes( $message ) )) )
      return false;

    mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message ) VALUES ( '$id', '-1', '" . time( ) . "', '$subject', '$message' )" );

    mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "' WHERE ( id = '$id' )" );

    // Notification messages
    $res_user = mysql_query( "SELECT DISTINCT user.email, user.sms FROM {$pre}user AS user, {$pre}privilege AS priv, {$pre}post AS post WHERE ( user.id = priv.user_id && (priv.dept_id = '0' || priv.dept_id = '$dept_id') && user.notify & {$GLOBALS[HD_NOTIFY_REPLY]} > '0' && post.user_id = user.id && post.ticket_id = '$id' )" );

    while( $row_user = mysql_fetch_array( $res_user ) )
    {
      eval( "\$email_subject = \"{$data[email_notify_reply_subject]}\";" );
      eval( "\$email_message = \"{$data[email_notify_reply]}\";" );
      mail( $row_user[email], $email_subject, $email_message, "From: {$data[email]}" );

      if( trim( $row_user[sms] ) != "" )
      {
        eval( "\$email_subject = \"{$data[email_notifysms_create_subject]}\";" );
        eval( "\$email_message = \"{$data[email_notifysms_create]}\";" );
        mail( $row_user[sms], $email_subject, $email_message, "From: {$data[email]}" );
      }
    }
  }

  $res = mysql_query( "SELECT text FROM {$pre}options WHERE ( name = 'uploads' )" );
  $row = mysql_fetch_array( $res );
  if( !$row )
    $data[uploads] = 0;
  else
    $data[uploads] = $row[0];

  if( $data[uploads] )
  {
    if( !is_dir( "{$HD_TICKET_FILES}/{$id}" ) )
    {
      $oldumask = umask( 0 ); 
      mkdir( "{$HD_TICKET_FILES}/{$id}", 0777 );
      umask( $oldumask );
    }
    
    // Creates files for all the attachments
    for( $i = 0; $i < count( $email_parts ); $i++ )
    {
      if( !(($email_parts[$i][type_primary] == "text" && $email_parts[$i][type_secondary] == "plain") ||
           ($email_parts[$i][type_primary] == "plain" && $email_parts[$i][type_secondary] == "text")) )
      {
        if( isset( $email_parts[$i][parameters][name] ) )
          $filename = $email_parts[$i][parameters][name];
        else if( isset( $email_parts[$i][dparameters][filename] ) )
          $filename = $email_parts[$i][dparameters][filename];
        else
          $filename = sprintf( "%s-%s.%03d", $email_parts[$i][type_primary], $email_parts[$i][type_secondary], $i );

        $fp = fopen( "{$HD_TICKET_FILES}/{$id}/{$filename}", "w" );
        if( $fp )
        {
          fwrite( $fp, $email_parts[$i][body] );
          fclose( $fp );
        }
      }      
    }
  }

  return true;
}

/********************************************************** PHP */?>