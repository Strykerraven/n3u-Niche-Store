<?php 
	/**
		n3u Niche Store - Custom Niche PHP Script
		Copyright (C) 2012-2014 n3u.com

		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program. If not, see <http://www.gnu.org/licenses/>

		Notes:
			Do not inject any code directly into this file unless you are sure 
			what you are doing as that code will be replaced in future updates.
			
	**/
	// $mtime = explode(" ",microtime());$mtime = $mtime[1] + $mtime[0];$starttime = $mtime;
	define('n3u', TRUE); // Lets define n3u here as this file calls all other files.
	require_once('n3u.php'); // The brains
	switch($n3u_inputVars['x']){ // Which mode is defined?
		case "admin": // If admin, do this
			if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
				header('Location: index.php?x=login');
			}
			// At this point we are logged in
			$clearCache = filter_input(INPUT_GET,'clearcache');
			if(isset($clearCache) && $clearCache != NULL){n3u_ClearCache();}
			header('Content-type: text/html; charset=utf-8');
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/header.php'); // Request the Header
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/content.php'); // Request the Content
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/sides.php'); // Request the Sides
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/footer.php'); // Request the Footer
		break;
		case "contact":
			if(!isset($n3u_configVars['SiteEmail']) || $n3u_configVars['SiteEmail'] == NULL){ // Email Address is not set
				header('Location: index.php');
				exit; // Vistor was redirected back to homepage.
			}
			// Get the reCaptcha Library
			if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)){
				require_once($n3u_configVars['include_dir'] . 'recaptchalib.php');
			}
			header('Content-type: text/html; charset=utf-8');
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/header.php'); // Request the Header
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/content.php'); // Request the Content
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/sides.php'); // Request the Sides
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/footer.php'); // Request the Footer
		break;
		case "download": // If download, do this
			if(defined('admin')){
				if(isset($n3u_inputVars['url']) && $n3u_inputVars['url'] != NULL){
					$tempurl = urldecode(base64_decode($n3u_inputVars['url']));
					header('Content-type: text/html; charset=utf-8');
					header('Content-Disposition: attachment; filename="'.$tempurl.'"');
					readfile($tempurl);
					exit;
				}
			}
			break;
		case "feed":
			header('Content-type: application/atom+xml; charset=utf-8');
			n3u_FetchSearch();
			if(isset($n3u_results) && $n3u_results != NULL){n3u_Feed($n3u_results);}
		break;
		case "go": // If go, do this
			if(isset($n3u_inputVars['url']) && $n3u_inputVars['url'] != NULL){
				$tempurl = urldecode(base64_decode($n3u_inputVars['url']));
				// Track Click
				$n3u_configVars['visitor_ip'];
				header('Location: '.$tempurl);
				exit;
			}
		break;
		case "login":
			// Get the reCaptcha Library if keys are set
			if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)){
				require_once($n3u_configVars['include_dir'] . 'recaptchalib.php');
			}
			if($n3u_ServerVars['REQUEST_METHOD'] == "POST"){
				if(isset($n3u_PostVars['emailconfirm']) && $n3u_PostVars['emailconfirm'] != NULL){
					header('Location: index.php?x=login'); // Redirect to login without reason since this shouldnt be filled.
				}
				if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)){
					$resp = recaptcha_check_answer ($n3u_configVars['reCaptcha_privKey'],$n3u_ServerVars["REMOTE_ADDR"],$n3u_PostVars["recaptcha_challenge_field"],$n3u_PostVars["recaptcha_response_field"]);
					if(!$resp->is_valid){ // What happens when the CAPTCHA was entered incorrectly
						$reCaptcha_Error = ('<span class="fail">'.$n3u_Lang['reCaptcha_fail'].'</span>');
					}else{
						if($n3u_PostVars['username'] == $n3u_configVars['username'] && $n3u_PostVars['password'] == $n3u_configVars['password']){
							$_SESSION['username'] = md5($n3u_configVars['username']);
							$_SESSION['password'] = md5($n3u_configVars['password']);
							header('Location: index.php?x=admin');
						}
					}
				}else{
					if($n3u_PostVars['username'] == $n3u_configVars['username'] && $n3u_PostVars['password'] == $n3u_configVars['password']){
						$_SESSION['username'] = md5($n3u_configVars['username']);
						$_SESSION['password'] = md5($n3u_configVars['password']);
						header('Location: index.php?x=admin');
					}
				}
			}
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/header.php'); // Request the Header
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/content.php'); // Request the Content
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/sides.php'); // Request the Sides
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/footer.php'); // Request the Footer
		break;
		case "logout":
			session_start(); // Starts new session
			$_SESSION = array(); // Unset all of the session variables.
			session_destroy(); // Destroys session
			header('Location: index.php'); // Header Re-direct
			exit; // Process no further.
		break;
		case "error":
		case "index":
		case "item":
		case "page":
		case "privacy":
		case "search":
		case "terms":
			header('Content-type: text/html; charset=utf-8');
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/header.php'); // Request the Header
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/content.php'); // Request the Content
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/sides.php'); // Request the Sides
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/footer.php'); // Request the Footer
		break;
		default:
			echo 'You should not see this.';
		break;
	}
	unset($n3u_configVars['api_key']); // unset api_key
	// $mtime = explode(" ",microtime());$mtime = $mtime[1] + $mtime[0];$endtime = $mtime;$totaltime = ($endtime - $starttime);echo "This page was created in ".$totaltime." seconds";

?>