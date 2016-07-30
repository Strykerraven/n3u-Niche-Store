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
	function n3u_RandomBanner($total = 3){ // Randomally choose a banner.
		global $n3u_configVars;
		global $n3u_lang;
		require_once dirname(__FILE__) . '/custom_banners.php';
		$result = '';
		$i = 0;
		shuffle($n3u_custom_banners);
		while($i != $total){
			$img_size = getimagesize($n3u_custom_banners[$i][1]);
			$result .= "\t\t\t\t\t\t" . '<a href="'. $n3u_custom_banners[$i][0] .'" id="'. n3u_IdCleaner($n3u_custom_banners[$i][2]) .'" target="_blank" title="'. n3u_TitleCleaner($n3u_custom_banners[$i][2]) .'"><img alt="'. $n3u_custom_banners[$i][2] .'" class="image" height="'.$img_size[1].'" src="'. $n3u_custom_banners[$i][1] .'" width="'.$img_size[0].'" /></a>' . PHP_EOL;
			$i++;
		}
		unset($img_size,$i);
		return $result;
	}
	// Figure out if block is allowed
	if(in_array($n3u_inputVars['x'],array('search','index','item'))){$n3u_block_allowed = 'true';}else{$n3u_block_allowed = 'false';}	
	switch($n3u_block_allowed){
		case "true": // If true, Block is allowed so we return data			
			echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Banner_Rotator">' . PHP_EOL
			. "\t\t\t\t\t" . '<div>' . PHP_EOL
			. n3u_RandomBanner(3) // 3 is however many you want
			. "\t\t\t\t\t" . '</div>' . PHP_EOL
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Ad
		break;
		case "false": // If false, Block is not allowed so we return empty
		default:
		break;
	}

?>