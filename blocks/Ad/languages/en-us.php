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
	  For example: Canadian and United Kingdom both use English but have minor differences
	  In canadian english we have defence vs defense, In british english we have colour vs color

	  By Default, the "Primary" language used will be en-us, This is done to prevent inconsistancies. This can be set in the administration panel.
	  Should a user select Canada or United Kingdom instead of the US dialect,
	  We would add those unique changes after requiring the US english version of the file.

	  These are the plans atleast. They are only partially implemented.
	  For now users must append the following to url's to prevent english defaulting during install:    &lang=en-gb    or    &lang=es-mx
	  However after installation if you choose another auto-detected language, That language will be used as the default.


	  Languages are based on country code using the ISO 639 & ISO 3166 standards (with 2 character preference).
	  So for example:
	  ISO 639-1 states English can be en
	  ISO 3166-1 states the country code for United States is us
	  Therefor en-us = English (United States)
	  or en-gb = English (United Kingdom)
	  or es-mx = Spanish (Mexico)

	  preprint_r($n3u_lang); will show all language currently set.

	  IMPORTANT: If you decide to use a ' character, you will need to escape it with a \
	  Example: 'I can\'t do that!'
	  This is due to using ' instead of ", However if we used " things would be worse.

	  Future plans include to implement administration panel support for changing language values for easier editing.

	 */
	if(!defined('n3u')){
		die('Direct access is not permitted.');
	} // Is n3u defined?

	$n3u_blocklang=array(
		// Useage:
		//	'Value'	=> 'Description',
		'Ad' => 'Ad',
		'Ad_Settings' => 'Ad Settings:',
		'Ad_Custom' => 'Custom Ad:',
		'Ad_Custom_Explain' => 'Enter code for custom ads below.',
		'Ad_Text_Explain' => 'Your Ad Here. HTML, Javascript, Inline-CSS etc allowed.',
	);
	// preprint_r($n3u_lang);
?>