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
	switch($n3u_inputVars['x']){ // Switch between modes
		case "admin":
		case "contact":
		case "error":
		case "index":
		case "item":
		case "page":
		case "privacy":
		case "search":
		case "terms": // Blocks are loaded into sides
			echo "\t\t\t" . '<div id="leftnav">' . PHP_EOL;
			n3u_Block('left'); // Load left blocks by dir names ascending
			echo "\t\t\t" . '</div>' . PHP_EOL
			. "\t\t\t" . '<div id="rightnav">' . PHP_EOL;
			n3u_Block('right'); // Load right blocks by dir names ascending
			echo "\t\t\t" . '</div>' . PHP_EOL;
		break;
		case "login":
		case "logout":
		default: // Sides are empty but left as placeholders
			echo "\t\t\t" . '<div id="leftnav"></div>' . PHP_EOL 
			. "\t\t\t" . '<div id="rightnav"></div>' . PHP_EOL;
		break;
	}

?>