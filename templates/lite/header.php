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
	switch($n3u_inputVars['x']){
		case "download":
		case "feed":
		case "go":
			// do nothing
			break;
		case "admin": // If Admin, do this
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="This page controls all the main settings for n3u Niche Store.">' . PHP_EOL;
			if(isset($n3u_PostVars) && $n3u_PostVars != NULL){
				echo "\t\t" . '<meta http-equiv="refresh" content="0">' . PHP_EOL;
			}
			echo "\t\t" . '<meta name="robots" content="noindex,nofollow">' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['Admin_Panel'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "contact": // If contact, do this
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="Need to contact ' . $n3u_configVars['SiteName'] . '? This page will allow you to do just that!">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL
			. "\t\t" . '<script type="text/javascript">var RecaptchaOptions = {theme : \'blackglass\'}; </script>' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['Contact'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . str_replace('index.php', '', $n3u_configVars['self']) . $n3u_inputVars['x'] . '.htm', FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}else{
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'], FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "error": // If error, do this
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="Sorry, An error has occurred.">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="noindex,nofollow">' . PHP_EOL;
			if($n3u_inputVars['q']){
				echo "\t\t" . '<title>' . $n3u_inputVars['q'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}else{
				echo "\t\t" . '<title>' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL // div Top
			. "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "index": // If index, do this
			n3u_FetchSearch($n3u_configVars['Prosperent_Endpoint']);
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				header("HTTP/1.1 404 Not Found");
			}
			n3u_CacheBegin(); // Start Caching
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="Thanks for visiting ' . $n3u_configVars['SiteName'] . '! Here you may find much relating to ' . $n3u_configVars['defaultKeyword'] . '. Please enjoy the website!">' . PHP_EOL;
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				echo "\t\t" . '<meta name="robots" content="noindex">' . PHP_EOL;
			}else{
				echo "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL;
			}
			if($n3u_inputVars['q']){
				echo "\t\t" . '<title>' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}else{
				echo "\t\t" . '<title>' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}
			n3u_CacheCSS();
			echo "\t\t" . '<script type="text/javascript">' . PHP_EOL
			. "\t\t\t" . '<!--' . PHP_EOL
			. "\t\t\t\t" . 'prosperent_pa_uid = ' . $n3u_configVars['Prosperent_UserID'] . ';' . PHP_EOL
			. "\t\t\t\t" . 'prosperent_pa_fallback_query = \'' . $n3u_configVars['defaultKeyword'] . '\';' . PHP_EOL
			//	. "\t\t\t\t" . 'prosperent_pa_fallback_query_is_default = 1;' . PHP_EOL
			. "\t\t\t" . '//-->' . PHP_EOL
			. "\t\t" . '</script>' . PHP_EOL;
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "item": // If item, do this
			n3u_FetchItem($n3u_configVars['Prosperent_Endpoint']);
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				header("HTTP/1.1 404 Not Found");
			}
			foreach($n3u_results as $n3u_result){
				// loop through, This is needed
			}
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="' . substr(n3u_TitleCleaner(str_replace(array("\t\t\t\t\t\t", PHP_EOL), '', n3u_extract($n3u_result['description'], '2', FALSE))), 0, 160) . '">' . PHP_EOL;
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				echo "\t\t" . '<meta name="robots" content="noindex">' . PHP_EOL;
			}else{
				echo "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL;
			}
			if(isset($n3u_result['keyword'])){
				echo "\t\t" . '<title>' . substr($n3u_result['keyword'], 0, 55) . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}else{
				echo "\t\t" . '<title>' . substr($n3u_inputVars['q'], 0, 55) . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . str_replace('index.php', '', $n3u_configVars['self']) . $n3u_inputVars['x'] . '_' . $n3u_inputVars['item'] . '.htm', FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}else{
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=item&amp;item=' . $n3u_inputVars['item'], FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}
			n3u_CacheCSS();
			echo "\t\t" . '<script type="text/javascript">' . PHP_EOL
			. "\t\t\t" . '<!--' . PHP_EOL
			. "\t\t\t\t" . 'prosperent_pa_uid = ' . $n3u_configVars['Prosperent_UserID'] . ';' . PHP_EOL
			. "\t\t\t\t" . 'prosperent_pa_fallback_query = \'' . $n3u_configVars['defaultKeyword'] . '\';' . PHP_EOL
			//	. "\t\t\t\t" . 'prosperent_pa_fallback_query_is_default = 1;' . PHP_EOL
			. "\t\t\t" . '//-->' . PHP_EOL
			. "\t\t" . '</script>' . PHP_EOL;
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "login": // If login, do this
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="You must first login. You may do so on this page.">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="noindex,nofollow">' . PHP_EOL
			. "\t\t" . '<script type="text/javascript">var RecaptchaOptions = {theme : \'clean\'}; </script>' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['Login'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "logout": // If logout, do this
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="You have been logged out.">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="noindex,nofollow">' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['LogOut'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "privacy": // If Privacy, do this
			n3u_CacheBegin(); // Start Caching
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="This is the Privacy Policy for ' . $n3u_configVars['SiteName'] . '. Please read over it carefully.">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['PrivacyPolicy'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . str_replace('index.php', '', $n3u_configVars['self']) . $n3u_inputVars['x'] . '.htm', FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}else{
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'], FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "search": // If Search, do this
			n3u_FetchSearch($n3u_configVars['Prosperent_Endpoint']);
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				header("HTTP/1.1 404 Not Found");
			}
			n3u_CacheBegin(); // Start Caching
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="Search Results for ' . @$n3u_inputVars['q'] . '. Displaying page ' . $n3u_inputVars['p'] . '">' . PHP_EOL;
			if(!isset($n3u_results) || @$prosperentApi->gettotalRecordsFound() == NULL){
				echo "\t\t" . '<meta name="robots" content="noindex">' . PHP_EOL;
			}elseif($n3u_inputVars['sort'] != 'REL'){
				echo "\t\t" . '<meta name="robots" content="noindex,follow">' . PHP_EOL;
			}else{
				echo "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL;
			}
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . str_replace('index.php', '', $n3u_configVars['self']) . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-REL-' . $n3u_inputVars['p'] . '.htm', FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}else{
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p=' . $n3u_inputVars['p'], FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}
			echo "\t\t" . '<link rel="dns-prefetch" href="//prosperent.com">' . PHP_EOL;
			n3u_CacheCSS();
			if($n3u_configVars['CleanUrls'] == TRUE){
				if(isset($n3u_inputVars['q']) && $n3u_inputVars['q'] != $n3u_configVars['defaultKeyword']){
					echo "\t\t" . '<link href="feed_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-REL-' . $n3u_inputVars['p'] . '.xml" rel="alternate" title="' . str_replace($n3u_configVars['defaultKeyword'] . ' ', '', $n3u_inputVars['q']) . ' - ' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}elseif($n3u_inputVars['q'] == $n3u_configVars['defaultKeyword']){
					echo "\t\t" . '<link href="feed_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-REL-' . $n3u_inputVars['p'] . '.xml" rel="alternate" title="' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}else{
					echo "\t\t" . '<link href="feed_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-REL-' . $n3u_inputVars['p'] . '.xml" rel="alternate" title="' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}
			}else{
				if(isset($n3u_inputVars['q']) && $n3u_inputVars['q'] != $n3u_configVars['defaultKeyword']){
					echo "\t\t" . '<link href="?x=feed' . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p=' . $n3u_inputVars['p'] . '" rel="alternate" title="' . str_replace($n3u_configVars['defaultKeyword'] . ' ', '', $n3u_inputVars['q']) . ' - ' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}elseif($n3u_inputVars['q'] == $n3u_configVars['defaultKeyword']){
					echo "\t\t" . '<link href="?x=feed' . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p=' . $n3u_inputVars['p'] . '" rel="alternate" title="' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}else{
					echo "\t\t" . '<link href="?x=feed' . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p=' . $n3u_inputVars['p'] . '" rel="alternate" title="' . $n3u_configVars['SiteName'] . '" type="application/atom+xml">' . PHP_EOL;
				}
			}
			if(isset($n3u_inputVars['q']) && $n3u_inputVars['q'] != $n3u_configVars['defaultKeyword']){
				echo "\t\t" . '<title>' . str_replace($n3u_configVars['defaultKeyword'] . ' ', '', substr($n3u_inputVars['q'], 0, 55)) . ' - ' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}elseif($n3u_inputVars['q'] == $n3u_configVars['defaultKeyword']){
				echo "\t\t" . '<title>' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}else{
				echo "\t\t" . '<title>' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "terms": // If Terms, do this
			n3u_CacheBegin(); // Start Caching
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="description" content="This is the Terms &amp; Conditions for ' . $n3u_configVars['SiteName'] . '. Please read over them carefully.">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_lang['TermsConditions'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . str_replace('index.php', '', $n3u_configVars['self']) . $n3u_inputVars['x'] . '.htm', FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}else{
				echo "\t\t" . '<link rel="canonical" href="' . filter_var(preg_replace('((?<!:)(//+))', '/', $n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'], FILTER_SANITIZE_URL)) . '">' . PHP_EOL;
			}
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Top
			n3u_Block('header');
			echo "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
		case "page":
		default:
			echo '<!DOCTYPE html>' . PHP_EOL
			. '<html>' . PHP_EOL
			. "\t" . '<head>' . PHP_EOL
			. "\t\t" . '<meta charset="UTF-8">' . PHP_EOL
			. "\t\t" . '<meta name="author" content="Strykerraven">' . PHP_EOL
			. "\t\t" . '<meta name="application-name" content="n3u Niche Store ' . $n3u_configVars['Version'] . '">' . PHP_EOL
			. "\t\t" . '<meta name="robots" content="index,follow">' . PHP_EOL
			. "\t\t" . '<title>' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			n3u_CacheCSS();
			n3u_CacheJS();
			echo "\t" . '</head>' . PHP_EOL
			. "\t" . '<body>' . PHP_EOL
			. "\t\t" . '<div id="wrapper">' . PHP_EOL
			. "\t\t\t" . '<div id="header">' . PHP_EOL
			. "\t\t\t\t" . '<div id="Top">' . PHP_EOL
			. "\t\t\t\t\t" . '<a class="link" href="./" target="_self" title="' . n3u_TitleCleaner($n3u_configVars['SiteName']) . '">' . $n3u_configVars['SiteName'] . '</a>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL // div Top
			. "\t\t\t" . '</div>' . PHP_EOL; //div header
			break;
	}
?>