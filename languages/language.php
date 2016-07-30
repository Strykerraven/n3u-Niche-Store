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

		NOTE:
			All languages are based on country codes. This allows variety between similar languages
			For example: Canadian and United Kingdom both use English but have minor differences
			In canadian english we have defence vs defense, In british english we have colour vs color
			
			By Default, the "Primary" language used will be en-us, However should a user select Canada or United Kingdom,
			We would add those unique changes after requiring the US english version of the file.
			
			Since Spanish is not close to english, we would not include the US english version.
			However with that said, Spain Spanish and Mexican Spanish would follow similar rules outlined above.
			
			Languages are based on country code using the ISO 639 & ISO 3166 standards (with 2 character preference).
			So for example:
			ISO 639-1 states English can be en
			ISO 3166-1 states the country code for US is us
			Therefor en-us = English (United States)
			or en-gb = English (United Kingdom)
			
	*/
	if(!defined('n3u')){die('Direct access is not permitted.');} // Is n3u defined?
	require_once($n3u_configVars['language_dir'] . $n3u_inputVars['lang'] . '/' . $n3u_inputVars['lang'] . '.php');
	$n3u_langtemp = array(); // Build a temp array to store data during the following loop:
	foreach(glob($n3u_configVars['blocks_dir'] . "*/languages/" . $n3u_inputVars['lang'] . '.php') as $filename){
		if(preg_match('/disabled/i', $filename) != TRUE){
			require_once($filename);
			$n3u_langtemp = array_merge($n3u_langtemp, $n3u_blocklang); // merges with itself
		}
	} // done in this way to avoid looping $n3u_Lang by number of blocks loaded
	$n3u_lang = array_merge($n3u_lang, $n3u_langtemp); // merges with the $n3u_lang array
	unset($n3u_langtemp);
	$n3u_lang_customfile = $n3u_configVars['language_dir'] . $n3u_inputVars['lang'] . '/' . $n3u_inputVars['lang'] . '_custom.php';
	if(file_exists($n3u_lang_customfile)){ // Gets custom
		require_once($n3u_lang_customfile);
	}else{
		$n3u_lang_custom = array();
	}
	if(isset($n3u_PostVars['lang'])){
		if(isset($n3u_PostVars['defaultLanguage'])){
			$n3u_configVars['defaultLanguage'] = $n3u_PostVars['defaultLanguage'];
		//	$n3u_configVars['defaultLanguage'] = 'en-gb';
			n3u_WriteConfig();
			unset($n3u_PostVars['defaultLanguage']);
		}
		$n3u_lang_changes = $n3u_lang_custom;
		$n3u_lang_changes = array_diff($n3u_PostVars,$n3u_lang);
		n3u_WriteLanguage($n3u_lang_changes);
	//	preprint_r($n3u_lang_changes);
	}elseif(isset($n3u_PostVars['resetlang'])){
		$n3u_lang_changes = array();
		n3u_WriteLanguage(NULL,TRUE);
	}
	if(isset($n3u_lang_changes) && $n3u_lang_changes != NULL){
		$n3u_lang = array_merge($n3u_lang,$n3u_lang_custom,$n3u_lang_changes);
	}else{
		$n3u_lang = array_merge($n3u_lang,$n3u_lang_custom);
	}

?>