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
	// Figure out if block is allowed
	if($n3u_position == 'header' || $n3u_position == 'footer'){
		$n3u_block_allowed = TRUE;
	}else{
		$n3u_block_allowed = FALSE;
	}
	switch($n3u_inputVars['x']){ // Which mode is defined?
		case "admin": // If admin, do this
			if($n3u_block_allowed == TRUE){
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="LinkToThisPage">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['LinkToThisPage'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_No_Settings'].'</span>' . PHP_EOL	
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div LinkToThisPage
			}else{
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="LinkToThisPage">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['LinkToThisPage'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_Not_Allowed'].'</span>' . PHP_EOL	
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div LinkToThisPage
			}
		break;
		case "index":
		case "item":
		case "page":
		case "search":
			if($n3u_block_allowed == TRUE){
				$bbcode_link = htmlspecialchars('[url='.$n3u_configVars['location'].']'.$n3u_configVars['location'].'[/url]');
				$html_link = htmlspecialchars('<a href="'.$n3u_configVars['location'].'" title="'.n3u_TitleCleaner($n3u_configVars['SiteName']).'">'.$n3u_configVars['location'].'</a>');
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="LinkToThisPage">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['LinkToThisPage'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<label>HTML Link:</label><span class="Linker_Desc">'.$n3u_Lang['CopyHTML'].' Select text and press <strong>CTRL+C</strong> to copy this to your keyboard <small><em>(CMD+C on Mac)</em></small>.</span><textarea onfocus="this.select()" placeholder="'.$html_link.'" readonly rows="4">'.$html_link.'</textarea>' . PHP_EOL
				. "\t\t\t\t\t" . '<label>BBCode Link:</label><span class="Linker_Desc">'.$n3u_Lang['CopyBBCode'].' Select text and press <strong>CTRL+C</strong> to copy this to your keyboard <small><em>(CMD+C on Mac)</em></small>.</span><textarea onfocus="this.select()" placeholder="'.$bbcode_link.'" readonly rows="4">'.$bbcode_link.'</textarea>' . PHP_EOL
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div LinkToThisPage
			}else{
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="LinkToThisPage">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['LinkToThisPage'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_Not_Allowed'].'</span>' . PHP_EOL	
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div LinkToThisPage
			}
		break;
		default:
			// Do nothing
		break;
	}

?>