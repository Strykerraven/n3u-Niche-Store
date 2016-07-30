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
	switch($n3u_inputVars['x']){ // Is mode defined?
		case "download":
		case "feed":
		case "go":
			// do nothing
			break;
		case "index": // If index, do below
		case "privacy": // If privacy, do below
		case "search": // If search, do below
		case "terms": // If terms, do below
			echo "\t\t\t" . '<div id="footer">' . PHP_EOL
			. "\t\t\t\t" . '<noscript>' . $n3u_lang['No_JS'] . '</noscript>' . PHP_EOL; // JS Warning
			n3u_Debug('', TRUE);
			n3u_Block('footer');
			echo "\t\t\t\t" . '<div id="Bottom">' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="./admin.htm" id="Admin_Link" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Admin_Panel']) . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./go/' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '.htm" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./privacy.htm" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./terms.htm" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./contact.htm" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}else{
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=admin" id="Admin_Link" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Admin_Panel']) . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=privacy" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=terms" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=contact" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . '&copy;&nbsp;' . date("Y") . '&nbsp;<a href="' . $n3u_configVars['SiteURL'] . '" type="text/html">' . $n3u_configVars['SiteName'] . '</a> - All rights reserved.';
			if(isset($n3u_configVars['Supporter']) && $n3u_configVars['Supporter'] == '0'){
				echo '&nbsp;Powered by <a href="https://github.com/Strykerraven/n3u-Niche-Store/" id="n3uPoweredBy" target="_blank" title="Powered by n3u Niche Store">n3u Niche Store</a><br />' . PHP_EOL;
			}else{
				echo '<br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . $n3u_lang['Trademark_Disclaimer'] . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL //div bottom
			. "\t\t\t" . '</div>' . PHP_EOL //div footer
			. "\t\t" . '</div>' . PHP_EOL //div wrapper
			. "\t" . '</body>' . PHP_EOL
			. '</html>';
			n3u_CacheEnd(); // End Caching
			break;
		case "admin": // If admin, do below
		case "contact": // If contact, do below
		case "page":
		case "item": // If item, do below
			echo "\t\t\t" . '<div id="footer">' . PHP_EOL
			. "\t\t\t\t" . '<noscript>' . $n3u_lang['No_JS'] . '</noscript>' . PHP_EOL; // JS Warning
			n3u_Debug('', TRUE);
			n3u_Block('footer');
			echo "\t\t\t\t" . '<div id="Bottom">' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="index.php?x=admin" id="Admin_Link" target="_self" title="' . $n3u_lang['Admin_Panel'] . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./go/' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '.htm" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./privacy.htm" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./terms.htm" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./contact.htm" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}else{
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="index.php?x=admin" id="Admin_Link" target="_self" title="' . $n3u_lang['Admin_Panel'] . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=privacy" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=terms" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="' . $n3u_configVars['self'] . '?x=contact" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . '&copy;&nbsp;' . date("Y") . '&nbsp;<a href="' . $n3u_configVars['SiteURL'] . '" type="text/html">' . $n3u_configVars['SiteName'] . '</a> - All rights reserved.';
			if(isset($n3u_configVars['Supporter']) && $n3u_configVars['Supporter'] == '0'){
				echo '&nbsp;Powered by <a href="https://github.com/Strykerraven/n3u-Niche-Store" id="n3uPoweredBy" target="_blank" title="Powered by n3u Niche Store">n3u Niche Store</a><br />' . PHP_EOL;
			}else{
				echo '<br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . $n3u_lang['Trademark_Disclaimer'] . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL //div bottom
			. "\t\t\t" . '</div>' . PHP_EOL //div footer
			. "\t\t" . '</div>' . PHP_EOL //div wrapper
			. "\t" . '</body>' . PHP_EOL
			. '</html>';
			break;
		case "error": // If error, do below
		case "login": // If login, do below
		case "logout": // If logout, do below
		default:
			echo "\t\t\t" . '<div id="footer">' . PHP_EOL;
			n3u_Debug('', TRUE);
			echo "\t\t\t\t" . '<div id="Bottom">' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="index.php?x=admin" id="Admin_Link" target="_self" title="' . $n3u_lang['Admin_Panel'] . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./go/' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '.htm" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./privacy.htm" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./terms.htm" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><a href="./contact.htm" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}else{
				echo "\t\t\t\t\t" . '<ul>' . PHP_EOL;
				if(defined('admin')){
					echo "\t\t\t\t\t\t" . '<li><a href="index.php?x=admin" id="Admin_Link" target="_self" title="' . $n3u_lang['Admin_Panel'] . '" type="text/html">' . $n3u_lang['Admin_Panel'] . '</a></li>' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . '<li><span class="bullet">&bull;</span><a href="./" id="ViewHome" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Home']) . '" type="text/html">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><span class="bullet">&bull;</span><a href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode('http://prosperent.com/policies/ftc')) . '" id="Disclosure" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Disclosure']) . '" type="text/html">' . $n3u_lang['Disclosure'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><span class="bullet">&bull;</span><a href="' . $n3u_configVars['self'] . '?x=privacy" id="PrivacyPolicy" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><span class="bullet">&bull;</span><a href="' . $n3u_configVars['self'] . '?x=terms" id="TermsConditions" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<li><span class="bullet">&bull;</span><a href="' . $n3u_configVars['self'] . '?x=contact" id="Contact' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL
				. "\t\t\t\t\t" . '</ul><br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . '&copy;&nbsp;' . date("Y") . '&nbsp;<a href="' . $n3u_configVars['SiteURL'] . '" type="text/html">' . $n3u_configVars['SiteName'] . '</a> - All rights reserved.';
			if(isset($n3u_configVars['Supporter']) && $n3u_configVars['Supporter'] == '0'){
				echo '&nbsp;Powered by <a href="https://github.com/Strykerraven/n3u-Niche-Store" id="n3uPoweredBy" target="_blank" title="Powered by n3u Niche Store">n3u Niche Store</a><br />' . PHP_EOL;
			}else{
				echo '<br />' . PHP_EOL;
			}
			echo "\t\t\t\t\t" . $n3u_lang['Trademark_Disclaimer'] . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL //div bottom
			. "\t\t\t" . '</div>' . PHP_EOL //div footer
			. "\t\t" . '</div>' . PHP_EOL //div wrapper
			. "\t" . '</body>' . PHP_EOL
			. '</html>';
			break;
	}
?>