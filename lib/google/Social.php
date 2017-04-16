<?php
/* -----------------------------------------------------------------------------------------
   IdiotMinds - http://idiotminds.com
   -----------------------------------------------------------------------------------------
*/
//For Facebook
require_once 'config.php';
//For Google
require_once 'lib/Google_Client.php';
require_once 'lib/Google_Oauth2Service.php';


class Social{
	
	public $_REDIRECT_URI;
	public $_GETURL;

	public function __construct($redirectUrl,$get = 0){
		$this->_REDIRECT_URI = $redirectUrl;
		$this->_GETURL = $get;
	}
 
    function google(){
	  
			$client = new Google_Client();
			$client->setApplicationName("Idiot Minds Google Login Functionallity");
			$client->setClientId(CLIENT_ID);
			$client->setClientSecret(CLIENT_SECRET);
			$client->setRedirectUri($this->_REDIRECT_URI);
			$client->setApprovalPrompt(APPROVAL_PROMPT);
			$client->setAccessType(ACCESS_TYPE);
			$oauth2 = new Google_Oauth2Service($client);
			if (isset($_GET['code'])) {
			  $client->authenticate($_GET['code']);
			  $_SESSION['token'] = $client->getAccessToken();
			}
			if (isset($_SESSION['token'])) {
			 $client->setAccessToken($_SESSION['token']);
			}
			if (isset($_REQUEST['error'])) {
			 echo '<script type="text/javascript">window.close();</script>'; exit;
			}
			if(!$this->_GETURL){
				if ($client->getAccessToken()) {
				  $user = $oauth2->userinfo->get();
				  if($user['email']){
					//var_dump($user['email']);exit;
					return $user;
				  }
				  $_SESSION['User']=$user;
				  $_SESSION['token'] = $client->getAccessToken();
				  
		
				} else {
				  $authUrl = $client->createAuthUrl();
				  //header('Location: '.$authUrl);
					return $authUrl;
				}
			# 鑾峰彇 login url
			}else{
				$authUrl = $client->createAuthUrl();
				  //header('Location: '.$authUrl);
				return $authUrl;
			}
      }
  
  
  

}

//$dd = new Social();exit;


?>