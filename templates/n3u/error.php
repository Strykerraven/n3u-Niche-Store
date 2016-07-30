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
	echo "\t\t\t\t" . '<div id="error">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Error'] . '</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="ErrorStatus">Error ' . $n3u_errorVars['Number'] . ' has occurred!</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="ErrorType">' . $n3u_errorVars['Type'] . '</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="ErrorDescription">' . $n3u_errorVars['Description'] . '</p>' . PHP_EOL
	. "\t\t\t\t" . '</div>' . PHP_EOL;

?>