<?php
class PHPBB_Login {

    function PHPBB_Login() {
    }

    function login( $phpbb_user_id ) {
        global $db, $board_config;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( '../../forum/config.php' );
    
        define('IN_PHPBB',true);

        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "../../forum/";
        
        require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        return session_begin( $phpbb_user_id, $user_ip, PAGE_INDEX, FALSE, TRUE );
    
    }

    function logout( $session_id, $phpbb_user_id ) {
        global $db, $lang, $board_config;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;
    
        // Setup the phpbb environment and then
        // run through the phpbb login process

        // You may need to change the following line to reflect
        // your phpBB installation.
        require_once( '../../forum/config.php' );
    
        define('IN_PHPBB',true);
        
        // You may need to change the following line to reflect
        // your phpBB installation.
        $phpbb_root_path = "../../forum/";

        require_once( $phpbb_root_path . "extension.inc" );
        require_once( $phpbb_root_path . "common.php" );

        session_end( $session_id, $phpbb_user_id );
    
        // session_end doesn't seem to get rid of these cookies,
        // so we'll do it here just in to make certain.
        setcookie( $board_config[ "cookie_name" ] . "_sid", "", time() - 3600, " " );
        setcookie( $board_config[ "cookie_name" ] . "_mysql", "", time() - 3600, " " );

    }

}

?>