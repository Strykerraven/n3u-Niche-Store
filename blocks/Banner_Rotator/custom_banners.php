<?php

	/**
	  n3u Niche Store - Custom Niche PHP Script
	  Copyright (C) 2012-2016 Strykerraven <https://github.com/Strykerraven/>

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
	 */
	if(!defined('n3u')){
		die('Direct access is not permitted.');
	} // Is n3u defined?
	$n3u_custom_banners=array(
		// Use the following format:
		// 'UNIQUE_NUMBER' => array('SITE_URL', 'BANNER_URL', 'ALT_TEXT'),
		// If using a ' character as part of ALT_TEXT, remember to escape it with \ so can't would become can\'t
		'01' => array('http://validator.w3.org/check?uri=referer', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/Valid-HTML5.png', $n3u_lang['Valid_html5']), // Auto-determines host
		'02' => array('http://jigsaw.w3.org/css-validator/check/referer', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/Valid-CSS3.png', $n3u_lang['Valid_css3']), // Auto-determines host
		'03' => array('http://php.net', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/php5-power-micro.png', $n3u_lang['PHP_Powered']),
		'04' => array('http://ipv6-test.com/validate.php?url=referer', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/button-ipv6-80x15.png', $n3u_lang['IPv6_Ready']), // Auto-determines host
		'05' => array('https://www.cloudflare.com/', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/cf-web-badges-f-4.png', $n3u_lang['CloudFlare_Enabled']),
		'06' => array('http://prosperent.com/ref/' . $n3u_configVars['Prosperent_UserID'], $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/logoBlack-88x16.png', $n3u_lang['Prosperent_Powered']), // Auto populates based on Prosperent_UserID
		'07' => array('https://www.fsf.org/', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/fsf.png', $n3u_lang['FSF']), // Auto populates based on Prosperent_UserID
		'08' => array('http://validator.w3.org/feed/check.cgi?uri=referer', $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/Valid-Atom.png', $n3u_lang['Valid_Atom']), // Auto populates based on Prosperent_UserID
	);
?>