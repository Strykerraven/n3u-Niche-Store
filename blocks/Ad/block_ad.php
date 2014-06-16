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
	if(in_array($n3u_inputVars['x'],array('admin','terms','search','index','item'))){$n3u_block_allowed = 'true';}else{$n3u_block_allowed = 'false';}	
	switch($n3u_block_allowed){
		case "true": // If true, Block is allowed so we return data
			$n3u_ad = @file_get_contents(dirname(__FILE__) . '/ad.txt');
			if(defined('admin') && $n3u_inputVars['x'] == 'admin'){
				//	$n3u_configVars['AddThis_pubid'] = '';
				if(isset($n3u_PostVars['submit'])){
					if(isset($n3u_PostVars['Ad_Text']) && $n3u_PostVars['Ad_Text'] != ''){
						$cleaned= str_replace(array('<!--','//-->'),'',$n3u_PostVars['Ad_Text']); // Remove <!-- and //-->
						file_put_contents(dirname(__FILE__) . '/ad.txt',$cleaned); // Write the ad_text to txt file
					}
				}
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Ad">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Ad'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<form id="ad_form" method="POST" action="' . $n3u_configVars['self'] . '?x=admin">' . PHP_EOL
				. "\t\t\t\t\t\t" . '<fieldset>' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<legend>' . $n3u_lang['Ad_Settings'] . '</legend>' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<label for="Ad_Text">' . $n3u_lang['Ad_Custom'] . '</label>' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Ad_Custom_Explain'] . '</span>' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<textarea cols="30" id="Ad_Text" name="Ad_Text" placeholder="'.$n3u_lang['Ad_Text_Explain'].'" rows="5" wrap="hard">'.$n3u_ad.'</textarea>' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update">' . PHP_EOL		
				. "\t\t\t\t\t\t" . '</fieldset>' . PHP_EOL
				. "\t\t\t\t\t" . '</form>' . PHP_EOL
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div Ad
			}else{
				echo "\t\t\t\t" . '<div id="Ad">' . PHP_EOL
				. "\t\t\t\t\t" . '<div>'.$n3u_ad.'</div>' . PHP_EOL
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div Ad
			}
			unset($n3u_ad);
		break;
		case "false": // If false, Block is not allowed so we return empty
		default:
		break;
	}

?>