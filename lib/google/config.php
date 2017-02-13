<?php
/*
------------------------------------------------------
  www.idiotminds.com
--------------------------------------------------------
*/  
session_start();
//define('BASE_URL', filter_var('http://localhost/social/', FILTER_SANITIZE_URL));
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
# index.php 设置的
Global $googleapiinfo;
//var_dump($googleapiinfo);exit;
define('CLIENT_ID',$googleapiinfo['GOOGLE_CLIENT_ID']);
define('CLIENT_SECRET',$googleapiinfo['GOOGLE_CLIENT_SECRET']);
//define('REDIRECT_URI','/social/login.php?google');//example:http://ecommerce.onfancy.com/social/login.php?google,http://example/login.php?google
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');

?>