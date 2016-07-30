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
	if(!defined('n3u')){die('Direct access is not permitted.');} // Is n3u defined?
	$n3u_blocklang = array(
	// Useage:
	//	'Value'							=> 'Description',
		'Feed'							=> 'Feed',
		'Feed_credits'					=> 'Provided by ',
		'Feed_Settings'					=> 'Feed Settings:',
		'Feed_feedinfo'					=> 'Feed_feedinfo',
		'Feed_url'						=> 'Feed URL:',
		'Feed_url_Explain'				=> 'Add an rss feed url below.',
		'Feed_no_feed'					=> 'You must first add a feed to use this block.',
	);
	// preprint_r($n3u_lang);
?>