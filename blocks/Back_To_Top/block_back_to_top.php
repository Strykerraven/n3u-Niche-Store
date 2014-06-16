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
	$n3u_block_allowed = 'true'; // Block is allowed in all positions.
	switch($n3u_inputVars['x']){ 
		default: // default mode (Shows Back To Top)
			echo "\t\t\t\t" . '<div id="BackToTop"><a href="#Top" title="Back to the Top">'.$n3u_lang['Back_To_Top'].'</a></div>' . PHP_EOL;
		break;
	}

?>