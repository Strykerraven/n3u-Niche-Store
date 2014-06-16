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
	**/
	if(!defined('n3u')){die('Direct access is not permitted.');} // Is n3u defined?
	switch ($n3u_inputVars['x']){
		case "admin": // If admin, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . 'admin/admin.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "contact": // If contact, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/contact.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "error": // If error, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['include_dir'] . 'error.php');
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/error.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "index": // If index, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/index.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "item": // If item, Continue
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/item.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "login": // If login, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Login">' . PHP_EOL
			. "\t\t\t\t\t" . '<h3>'.$n3u_lang['Login'].'</h3>' . PHP_EOL
			. "\t\t\t\t\t" . '<hr />' . PHP_EOL
			. "\t\t\t\t\t" . '<form method="POST" action="' . $n3u_configVars['self'] . '?x=login">' . PHP_EOL
			. "\t\t\t\t\t\t" . '<h5>'.$n3u_lang['Username'].'</h5>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="username" id="username" value="" required>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<h5>'.$n3u_lang['Password'].'</h5>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<input autocomplete="on" type="password" name="password" id="password" value="" required>' . PHP_EOL;
			if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)) {
				echo "\t\t\t\t\t\t" . '<div id="reCaptcha">' . PHP_EOL
				. "\t\t\t\t\t\t\t" . @$reCaptcha_Error . PHP_EOL
				. "\t\t\t\t\t\t\t" . recaptcha_get_html($n3u_configVars['reCaptcha_pubKey']) . PHP_EOL
				. "\t\t\t\t\t\t" . '</div>' . PHP_EOL; // div reCaptcha
			}
			echo "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="login" value="Login">' . PHP_EOL
			. "\t\t\t\t\t" . '</form>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL // div Login
			. "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "logout": // If logout, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL
			. "\t\t\t\t" . $n3u_lang['Logged_Out'] . PHP_EOL
			. "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "page":
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			if (isset($n3u_inputVars['page']) && $n3u_inputVars['page'] != NULL){
				$url = n3u_HTTP_Host();
				if(!isset($url['host']) || $url['host'] == NULL){
					if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
						$url['host'] = $url['path'];
					}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE)NAME
						$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
					}else{ // Use SiteURL
						$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
					}
				}
				if (file_exists($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_inputVars['page'].'.php')){
					require_once $n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_inputVars['page'].'.php';
				}else{ // file doesn't exist
					n3u_Error(404,'Page <em>'. $n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_inputVars['page'] . '.php</em> doesn\'t exist.');
				}
				unset($url);
			}else{ // $n3u_inputVars['x'] was set to page but $n3u_inputVars['page'] is empty, Let's throw a 400 error (bad request)
				n3u_Error(400,'Page was not specified.');
			}
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "privacy": // If Privacy, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/privacy.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "search": // If Search, Continue
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/search_results.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		case "terms": // If Terms, do this
			echo "\t\t\t" . '<div id="content">' . PHP_EOL;
			require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/terms.php');
			echo "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
		default:
			echo "\t\t\t" . '<div id="content">' . PHP_EOL
			. "\t\t\t" . '</div>' . PHP_EOL; // div content
		break;
	}

?>