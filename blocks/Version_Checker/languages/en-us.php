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

	  NOTES:
	  All languages are based on country codes. This allows variety between similar languages
	  For example:
	  Canada and UK both use English but have minor differences compared to US English
	  In Canadian english one example is defence versus defense.
	  In British english another common example is colour versus color.

	  Languages are based on country code using the ISO 639 & ISO 3166 standards (with 2 character preference).
	  So for example:
	  ISO 639-1 states English can be en
	  ISO 3166-1 states the country code for United States is us
	  Therefor en-us = English (United States)
	  or en-ca = English (Canada)
	  or en-gb = English (United Kingdom)
	  or es-mx = Spanish (Mexico)

	  preprint_r($n3u_lang); will show all language options currently set.

	  IMPORTANT: If you decide to use a ' character, you will need to escape it with a \
	  Example: 'I can\'t do that!'
	 */
	if(!defined('n3u')){
		die('Direct access is not permitted.');
	} // Is n3u defined?
	$n3u_blocklang=array(
		// Useage:
		//	'Value'							=> 'Description',
		'Current_Version' => 'Current Version: ',
		#	'Outdated'						=> 'Your installation of n3u Niche Store is outdated. You may update <a href="https://github.com/Strykerraven/n3u-Niche-Store/" target="_blank" title="n3u Niche Store">HERE</a>.',
		'Version_Checker' => 'Version Checker',
		#	'Version_Invalid'				=> 'Version Checker returned an invalid version. Either there\'s a problem with the DNS lookup OR CloudFlare is expierencing issues <em>(not too likely on CloudFlares part)</em>. Your hosting provider may block DNS lookups. If you\'re hosting provider doesn\'t allow this, it\'s suggested that you follow the <a href="https://twitter.com/n3ucom">Twitter feed</a> for new versions. Moving this block into the #disabled folder will disable this message.',
		'Up_To_Date' => 'Your installation of n3u Niche Store is up to date.',
		#	'Version_Tampered'				=> 'Your installation of n3u Niche Store has an invalid version number stored in config.php, Please change this to: ',
		'Your_Version' => 'Your Version: ',
	);
	// preprint_r($n3u_lang);
?>