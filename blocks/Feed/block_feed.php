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
	if(!defined('n3u')){die('Direct access is not permitted.');} // Is n3u defined?
	$n3u_BlockFeedInfo['Feed_info'] = explode(' = ', filter_var(file_get_contents(dirname(__FILE__) . '/feedinfo.txt'),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH));
	if(!isset($n3u_BlockFeedInfo['Feed_info']) || $n3u_BlockFeedInfo['Feed_info'] == NULL){
		 $n3u_BlockFeedInfo['Feed_info'][0] = 'http://news.google.com/news?pz=1&amp;cf=all&amp;ned=us&amp;hl=en&amp;q='.urlencode($n3u_inputVars['q']).'&amp;output=rss';
		 $n3u_BlockFeedInfo['Feed_info'][1] = '3';
		 $n3u_BlockFeedInfo['Feed_info'][2] = 'Google News';
	}
	$n3u_BlockFeedInfo['Feed_url'] = $n3u_BlockFeedInfo['Feed_info'][0];
	$n3u_BlockFeedInfo['Feed_limit'] = $n3u_BlockFeedInfo['Feed_info'][1];
	$n3u_BlockFeedInfo['Feed_credits'] = $n3u_BlockFeedInfo['Feed_info'][2];
	switch($n3u_inputVars['x']){ // Which mode is defined?
		case "admin": // If admin, do this
			if(isset($n3u_PostVars['submit'])){
				if(isset($n3u_PostVars['Feed_url']) && $n3u_PostVars['Feed_url'] != NULL){
					$n3u_BlockFeedInfo['Feed_url'] = $n3u_PostVars['Feed_url'];
					$n3u_BlockFeedInfo['Feed_limit'] = $n3u_PostVars['Feed_limit'];
					$n3u_BlockFeedInfo['Feed_credits'] = $n3u_PostVars['Feed_credits'];
					file_put_contents(dirname(__FILE__) . '/feedinfo.txt', @$n3u_BlockFeedInfo['Feed_url'] . ' = ' . @$n3u_BlockFeedInfo['Feed_limit'] . ' = ' . @$n3u_BlockFeedInfo['Feed_credits']); // Write the feed info to txt file
				}
			}
			echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="FeedInfo">' . PHP_EOL
			. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Feed'] . '</h3>' . PHP_EOL
			. "\t\t\t\t\t" . '<hr />' . PHP_EOL
			. "\t\t\t\t\t" . '<form id="feed_form" method="POST" action="' . $n3u_configVars['self'] . '?x=admin">' . PHP_EOL
			. "\t\t\t\t\t\t" . '<fieldset>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<legend>' . $n3u_lang['Feed_Settings'] . '</legend>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<label for="Feed_url">' . $n3u_lang['Feed_url'] . '</label>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Feed_url_Explain'] . '</span>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="Feed_url" id="Feed_url" value="' . $n3u_BlockFeedInfo['Feed_url'] . '" required>' . PHP_EOL
		//	. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="Feed_limit" id="Feed_limit" value="' . $n3u_BlockInfo['Feed_limit'] . '" required>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<label for="Feed_limit">' . $n3u_lang['Limit'] . '</label>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<select id="Feed_limit" name="Feed_limit" onchange="this.form.submit()">' . PHP_EOL;
			$i = 1; // First option
			while($i<=30){
				if($n3u_BlockFeedInfo['Feed_limit'] == $i){echo "\t\t\t\t\t\t\t\t" . '<option selected value="'.$i.'">'.$i.'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="'.$i.'">'.$i.'</option>' . PHP_EOL;}
				$i++;
			}
			echo "\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<label for="Feed_credits">' . $n3u_lang['Feed_credits'] . '</label>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="Feed_credits" id="Feed_credits" value="' . $n3u_BlockFeedInfo['Feed_credits'] . '" required>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update">' . PHP_EOL		
			. "\t\t\t\t\t\t" . '</fieldset>' . PHP_EOL
			. "\t\t\t\t\t" . '</form>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div FeedInfo
		break;
		default:
			echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="FeedInfo">' . PHP_EOL;
		//	echo "\t\t\t\t\t" . '<h3>' . $n3u_lang['Feed'] . '</h3>' . PHP_EOL
		//	. "\t\t\t\t\t" . '<hr />' . PHP_EOL;
			if(isset($n3u_BlockFeedInfo['Feed_url']) && $n3u_BlockFeedInfo['Feed_url'] != NULL){
				if(isset($n3u_BlockFeedInfo['Feed_limit']) && $n3u_BlockFeedInfo['Feed_limit'] != NULL){
					echo "\t\t\t\t\t";n3u_FetchFeed($n3u_BlockFeedInfo['Feed_url'],$n3u_BlockFeedInfo['Feed_limit']);echo PHP_EOL;
				}else{
					echo "\t\t\t\t\t";n3u_FetchFeed($n3u_BlockFeedInfo['Feed_url']);echo PHP_EOL;
				}
			}
			echo "\t\t\t\t" . '<small><em>' . $n3u_lang['Feed_credits'] . '<a class="link" href="' . str_replace('&','&amp;', $n3u_BlockFeedInfo['Feed_url']) . '" target="_blank" title="' . n3u_TitleCleaner($n3u_BlockFeedInfo['Feed_credits']) . '">' . $n3u_BlockFeedInfo['Feed_credits'] . '</a></em></small>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div AddThis
		break;
	}
	unset($n3u_BlockFeedInfo); // No Longer needed

?>