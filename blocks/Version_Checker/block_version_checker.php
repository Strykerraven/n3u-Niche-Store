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
	// Figure out if block is allowed
	if(defined('admin')){ // Admins can only see this block
		$n3u_block_allowed='true';
	}else{
		$n3u_block_allowed='false';
	}
	switch($n3u_block_allowed){
		case "true": // If true, Block is allowed so we return data
			$current_version='16.07.29'; // n3u_VersionChecker(); Overridden since n3u Niche Store was discontinued.
			echo "\t\t\t\t" . '<div class="block_' . $n3u_position . '" id="Version_Checker">' . PHP_EOL
			. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Version_Checker'] . '</h3>' . PHP_EOL
			. "\t\t\t\t\t" . '<hr />' . PHP_EOL
			. "\t\t\t\t\t" . '<label>' . $n3u_lang['Your_Version'] . '</label>' . '<span class="Your_Version">' . $n3u_configVars['Version'] . '</span>' . PHP_EOL
			. "\t\t\t\t\t" . '<label>' . $n3u_lang['Current_Version'] . '</label>' . '<span class="Current_Version">' . $current_version . '</span>' . PHP_EOL;
			if($current_version == '16.07.29'){
				echo "\t\t\t\t\t" . '<span class="Up_To_Date">' . $n3u_lang['Up_To_Date'] . '</span>' . PHP_EOL;
			}
			//	var_dump($current_version);
			echo "\t\t\t\t" . '</div>' . PHP_EOL; // div Version_Checker
			break;
		case "false": // If false, Block is not allowed so we return empty
		default:
			break;
	}
?>