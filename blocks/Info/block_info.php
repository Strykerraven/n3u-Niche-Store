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
	if($n3u_inputVars['x'] == 'admin'){$n3u_block_allowed = 'true';}else{$n3u_block_allowed = 'false';}	
	switch($n3u_block_allowed){
		case "true": // If true, Block is allowed so we return data	
			echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Info">' . PHP_EOL
			. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Info'] . '</h3>' . PHP_EOL
			. "\t\t\t\t\t" . '<hr />' . PHP_EOL
			. "\t\t\t\t\t" . '<div>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['Niche_Store_Version'].'</label><span class="explain">'.$n3u_configVars['Version'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['SERVER_NAME'].'</label><span class="explain">'.$n3u_ServerVars['SERVER_NAME'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['SERVER_ADDR'].'</label><span class="explain">'.$n3u_ServerVars['SERVER_ADDR'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['SERVER_SOFTWARE'].'</label><span class="explain">'.$n3u_ServerVars['SERVER_SOFTWARE'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['SERVER_PROTOCOL'].'</label><span class="explain">'.$n3u_ServerVars['SERVER_PROTOCOL'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['DOCUMENT_ROOT'].'</label><span class="explain">'.$n3u_ServerVars['DOCUMENT_ROOT'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['HTTP_HOST'].'</label><span class="explain">'.$n3u_ServerVars['HTTP_HOST'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['HTTP_USER_AGENT'].'</label><span class="explain">'.$n3u_ServerVars['HTTP_USER_AGENT'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['REMOTE_ADDR'].'</label><span class="explain">'.$n3u_ServerVars['REMOTE_ADDR'].'</span>' . PHP_EOL
			. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['PHP_Version'].'</label><span class="explain">'. @phpversion() .'</span>' . PHP_EOL
			. "\t\t\t\t\t" . '</div>' . PHP_EOL // div 
			. "\t\t\t\t" . '</div>' . PHP_EOL; // div Info
		break;
		case "false": // If false, Block is not allowed so we return empty
		default:
		break;
	}

?>