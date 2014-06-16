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
	// Get required files and execute required functions in order
	require_once('Prosperent_Api.php'); // The Prosperent Api library

	$n3u_ServerVars = filter_input_array(INPUT_SERVER);
	n3u_Input(); // Gets and filters input data
	n3u_Config(); // Gets and filters config data
	$n3u_PostVars = filter_input_array(INPUT_POST);
	n3u_Defaults(); // Checks that defaults are set, if not sets them.
//	
	session_start(); // Check Sessions
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		if($_SESSION['username'] == md5($n3u_configVars['username']) && $_SESSION['password'] == md5($n3u_configVars['password'])){
			define('admin',TRUE);	
		}else{
			session_start();
			$_SESSION = array();
			session_destroy();
			header('Location: index.php?x=login');
			exit;
		}
	}
	require_once($n3u_configVars['language_dir'] . 'language.php'); // Get the Language
	n3u_ErrorChecker(); // Check for errors
	n3u_CheckCache(); // Check for cache cleanup routines
	n3u_BlockConfig();
	/**
		n3u_AdditionalStores() looks for additional stores pointed to the same 
		installation of n3u Niche Store. This is done by looking for domain 
		configuration files.
	**/
	function n3u_AdditionalStores($page_limit = NULL){ // Returns array of Custom pages
		$n3u_AdditionalStores = array();
		$i = 1;
		foreach(glob("*_config.php") as $n3u_additional_store){
			if(preg_match('/_block/i', $n3u_additional_store) != TRUE){
				$n3u_additional_storename = str_replace('_config.php','',$n3u_additional_store);
				$n3u_AdditionalStores[$i]['name'] = $n3u_additional_storename;
				$n3u_AdditionalStores[$i]['path'] = $n3u_additional_store;
				$n3u_AdditionalStores[$i]['url'] = 'http://'. $n3u_additional_storename;
				if($page_limit == NULL){ // limits pages returned if specified
					$i++;
				}elseif($page_limit > $i){
					$i++;
				}
			}
		} // finds all custom pages in templates custom directory, builds array
		unset($page_limit,$n3u_additional_store,$n3u_additional_storename,$i);
		return $n3u_AdditionalStores;
	}
	function n3u_AutoLinker($text=NULL){
		global $n3u_configVars;
		$matches=NULL;
		preg_match_all("/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/",$text,$matches);
		if(isset($matches) && $matches != NULL){
			foreach($matches[0] as $match){
				// preprint_r($match);
				// $text=str_replace($match,'<a href="' . $match . '" target="_blank" title="External Link"> ' . ($match) . '</a>',$text);
				
				if($n3u_configVars['CleanUrls'] == TRUE){
					$text=str_replace($match,'<a class="ExternalLink" href="go/'.base64_encode(urlencode($match)) . '.htm" rel="nofollow" target="_blank" title="External Link"> ' . ($match) . '</a>',$text);
				}else{
					$text=str_replace($match,'<a class="ExternalLink" href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode($match)) . '" rel="nofollow" target="_blank" title="External Link"> ' . ($match) . '</a>',$text);
				}
			}
			return $text;
		}
	}
	/**
		n3u_Block() is used to restrive individual blocks. If $block_area is 
		specified, n3u_Block() will only look in that position. If TRUE is
		passed as a 2nd option, A list will be returned instead.
	**/
	function n3u_BlockSort($a,$b){
		return $a['SortOrder'] - $b['SortOrder'];
	}
	function n3u_Block($block_area='*',$list=FALSE){
		global $n3u_blockData;
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $n3u_results;
		global $n3u_PostVars;
		global $n3u_ServerVars;
		global $prosperentApi;
		// preprint_r($n3u_blockData);
		if($list == FALSE){
			$Blocks = array();
			foreach($n3u_blockData as $BlockNameKey => $BlockArrayValue){
		//		preprint_r($BlockArrayValue);
				$n3u_position = $BlockArrayValue['Position'];
				if($n3u_position == $block_area){ // If correct Position
					$Blocks[$BlockNameKey] = array('Path' => $BlockArrayValue['Path'],'Position' => $BlockArrayValue['Position'], 'SortOrder' => $BlockArrayValue['SortOrder']);
				}
				unset($n3u_position);
			}
			$reverse_blockData = $Blocks;
			krsort($reverse_blockData); // Reverse Order by keyname so that usort sorts by correct order
			usort($reverse_blockData, 'n3u_BlockSort'); // Call n3u_BlockSort Function to sort by SortOrder
			foreach($reverse_blockData as $Block){
				$n3u_position = $Block['Position'];
				require_once $Block['Path'];
				unset($n3u_position);
			}
	//		preprint_r($reverse_blockData);
		}elseif($list == TRUE){
			$n3u_BlockList = array();
			$block_name = '*';
			$block_filename = 'block_*.php';
			foreach($n3u_blockData as $BlockNameKey => $BlockArrayValue){
				list($block_dir,$BlockArrayValue['Position'],$BlockNameKey,$block_filename) = explode('/', $filepath);
				$n3u_BlockList[$BlockNameKey]['filename'] = $BlockNameKey;
				$n3u_BlockList[$BlockNameKey]['Path'] = $BlockArrayValue['Path'];
				$n3u_BlockList[$BlockNameKey]['Position'] = $BlockArrayValue['Position'];
				if(is_numeric($block_name[0])){
					$n3u_BlockList[$BlockNameKey]['SortOrder'] = $block_name[0];
				}else{
					$n3u_BlockList[$BlockNameKey]['SortOrder'] = NULL;
				}
			}
			unset($block_area,$block_dir,$block_filename,$block_name);
	//		preprint_r($blocklist);
			return $n3u_BlockList;
		}
	}
	/**
		n3u_Block() is used to restrive individual blocks. If $block_area is 
		specified, n3u_Block() will only look in that position. If TRUE is
		passed as a 2nd option, A list will be returned instead.
	**/
	function n3u_BlockConfig(){
		global $n3u_blockData;
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $n3u_results;
		global $n3u_PostVars;
		global $n3u_ServerVars;
		global $prosperentApi;
		$url = n3u_HTTP_Host();
	//	if(file_exists($url['host'].'_config.php')){require_once($url['host'].'_config.php');}
		if(file_exists($n3u_configVars['include_dir'] . 'configs/' . $url['host'] . '_block_config.php')){ // Use existing but check for new
			require_once($n3u_configVars['include_dir'] . 'configs/' . $url['host'] . '_block_config.php');
			foreach($n3u_blockData as $BlockName => $BlockArray){
				if(!file_exists($BlockArray['Path'])){ // Check to see if path is no longer valid. If so unset data.
					unset($n3u_blockData[$BlockName]);
				//	preprint_r($BlockArray);
					n3u_WriteBlockConfig();
				}
			}
			$folderlist = glob($n3u_configVars['blocks_dir'] . "*",GLOB_ONLYDIR);
			foreach($folderlist as $folderpath){
				$name = str_replace($n3u_configVars['blocks_dir'],'',$folderpath);
				if(!isset($n3u_blockData[$name]) || $n3u_blockData[$name] == NULL){
					$n3u_blockData[$name] = array(
						'Path' => $n3u_configVars['blocks_dir'] . $name . '/block_'.$name.'.php',
						'Position' => '#disabled',
						'SortOrder' => '3',
					);
					n3u_WriteBlockConfig();
				}
			}
			ksort($n3u_blockData);
	//		preprint_r($n3u_blockData);
		}else{ // Get new Block Data
			$n3u_blockData = array();
			$folderlist = glob($n3u_configVars['blocks_dir'] . "*",GLOB_ONLYDIR);
			foreach($folderlist as $folderpath){
				$name = str_replace($n3u_configVars['blocks_dir'],'',$folderpath);
				$n3u_blockData[$name] = array(
					'Path' => $n3u_configVars['blocks_dir'] . $name . '/block_'.$name.'.php',
					'Position' => '#disabled',
					'SortOrder' => '3',
				);
			}
			ksort($n3u_blockData);
		}
	//	preprint_r($n3u_blockData);

	//	n3u_WriteBlockConfig();
	//	preprint_r($n3u_BlockListArray);
		return $n3u_blockData;
	}
	function n3u_WriteBlockConfig($domainConfig=NULL){
		global $n3u_blockData;
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $n3u_results;
		global $n3u_PostVars;
		global $n3u_ServerVars;
		global $prosperentApi;
		$url = n3u_HTTP_Host();
		if(!isset($domainConfig) || $domainConfig == NULL){
			$domainConfig = $n3u_configVars['include_dir'] . 'configs/' . $url['host'] . '_block_config.php';
		}
		$configFile = '<?php ' . PHP_EOL
		. "\t" . '/**' . PHP_EOL
		. n3u_GPL_Credits()
		. "\t\t" . 'n3u Niche Store - '.$url['host'].'_block_config.php' . PHP_EOL
		. "\t\t\t" . 'This configuration file maintains the data used for blocks.' . PHP_EOL
		. "\t\t\t" . 'It\'s best to configure Blocks directly from the Admin Panel' . PHP_EOL
		. "\t\t\t" . 'Don\'t use a \' character in any option unless you know how to escape properly.' . PHP_EOL
		. "\t\t\t" . 'Again it\'s best to use Admin Panel which is safer.' . PHP_EOL
		. "\t" . '**/' . PHP_EOL
		. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL
		. "\t" . '$n3u_blockData = array( // Visit the Blocks admin section to change settings.' . PHP_EOL;
	//	$BlockData = n3u_BlockConfig();
	//	preprint_r($BlockData);
		foreach($n3u_blockData as $Blockkey => $Blockvalue){
			$configFile .= "\t\t" . var_export($Blockkey,TRUE) . ' => ' . preg_replace(array('\'\s+\'','/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/','(,\),)'),array('','','),'),var_export($Blockvalue,TRUE) . ',') . PHP_EOL;
		}
		$configFile .= "\t" . '); // n3u Niche Store is brought to you by n3u.com' . PHP_EOL
		. '?>';
		$fp = fopen($domainConfig, "w");
		fwrite($fp, $configFile);
		fclose($fp);
		unset($configFile,$url);
	}
	/**
		n3u_CacheBegin() is used to start the caching process. If the admin is 
	    logged in, or if caching is disabled, This process does not start. If 
	    caching is enabled appropiate headers are sent to notify the browser to 
	    cache as well.
	**/
	function n3u_CacheBegin(){
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_cacheFile;
		global $n3u_ServerVars;
		$url = parse_url($n3u_ServerVars['HTTP_HOST']);
		if(!isset($url['host']) || $url['host'] == NULL){
			if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
				$url['host'] = $url['path'];
			}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE)NAME
				$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
			}else{ // Use SiteURL
				$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
			}
		}
		$n3u_cacheFile = $n3u_configVars['cache_dir'] .$url['host']. '/' . $n3u_inputVars['x'] . '_' . (md5(@$n3u_inputVars['m'] . '_' . @$n3u_inputVars['b'] . '_' . @urlencode($n3u_inputVars['q']) . '-' . @$n3u_inputVars['sort'] . '-' . @$n3u_inputVars['p'])) . '.htm';
		date_default_timezone_set('GMT'); // Sets the default timezone to use, browser headers require GMT.
		// Serve from the cache if it is younger than the current $cachetime
		if(defined('admin') || $n3u_configVars['caching'] == FALSE){
			// Cache is not generated for admin or if caches are disabled.
		}elseif(file_exists($n3u_cacheFile) && time() - $n3u_configVars['lifetime'] < filemtime($n3u_cacheFile)){
			// if a fresh file exist and admin is not logged in at this point...
			header('Cache-Control: max-age='.$n3u_configVars['lifetime'].', must-revalidate');
			header('Expires: ' . date('D, d M Y H:i:s e', filemtime($n3u_cacheFile)+$n3u_configVars['lifetime'])); // Date in the future
			header('Last-Modified: ' . date('D, d M Y H:i:s e', filemtime($n3u_cacheFile))); // Cache header only sent if not serving cached copy
			header('Pragma: public');
			header('X-Powered-By: n3u Niche Store ' . $n3u_configVars['Version']);
			include($n3u_cacheFile);
			// echo "\t\t" . '<!-- This file was generated on ' . date('F jS,Y \@ g:i:s e', filemtime($n3u_cacheFile)) . ' -->' . PHP_EOL;
			exit;
		}else{
			header('Cache-Control: max-age='.$n3u_configVars['lifetime'].', must-revalidate');
			header('Expires: ' . date('D, d M Y H:i:s e', time()+$n3u_configVars['lifetime'])); // Date in the future
			header('Last-Modified: ' . date('D, d M Y H:i:s e', time())); // Cache header only sent if not serving cached copy
			header('Pragma: public');
			header('X-Powered-By: n3u Niche Store ' . $n3u_configVars['Version']);
		}
		unset($url);
		ob_start(); // Start the output buffer. The cache don't exist at this point.
	}
	/**
		n3u_CacheCSS() is used to cache CSS if canching is enabled. If disabled, 
	    or Admin is logged in, each individal style link is returned.
	**/
	function n3u_CacheCSS(){
		global $n3u_configVars;
		global $n3u_blockData;
		global $n3u_ServerVars;
		// Cache the contents to a file
		if(defined('admin') || $n3u_configVars['caching'] == FALSE){ // if admin or if caching is disabled
			foreach(glob($n3u_configVars['blocks_dir'] . '*/*.css') as $filename){ // get block css files
				echo "\t\t" . '<link rel="stylesheet" type="text/css" href="' . $filename . '">' . PHP_EOL;
			}
			foreach(glob($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/*.css') as $filename){ // get template css files
				echo "\t\t" . '<link rel="stylesheet" type="text/css" href="' . $filename . '">' . PHP_EOL;
			}
			if((defined('admin') == TRUE) && file_exists($n3u_configVars['Template_Dir'] . 'admin/admin.css')){
				echo "\t\t" . '<link rel="stylesheet" type="text/css" href="'.$n3u_configVars['Template_Dir'] . 'admin/admin.css">' . PHP_EOL;
			}
			if((defined('admin') == TRUE) && file_exists($n3u_configVars['Template_Dir'] . 'admin/style.php')){
				echo "\t\t" . '<link rel="stylesheet" type="text/css" href="'.$n3u_configVars['Template_Dir'] . 'admin/style.php">' . PHP_EOL;
			}
		}else{ // if not admin and caching is enabled and cache is old
			$url = parse_url($n3u_ServerVars['HTTP_HOST']);
			if(!isset($url['host']) || $url['host'] == NULL){
				if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
					$url['host'] = $url['path'];
				}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE_NAME
					$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
				}else{ // Use SiteURL
					$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
				}
			}
			$n3u_cacheFilePath = $n3u_configVars['cache_dir'] .$url['host']. '/' . 'css.css'; // Set cache file name
			if(file_exists($n3u_cacheFilePath) && time() - $n3u_configVars['lifetime'] < filemtime($n3u_cacheFilePath)){ // else if a recent cache file exist
				// Already exist and is recent so do nothing.
			}else{
				$css = array();
				foreach(glob($n3u_configVars['blocks_dir'] . '*/*.css') as $filename){ // get block css files
			//		if(preg_match('/disabled/i', $filename) != TRUE){
						$css[] = $filename; // add each filename into css array
			//		}
				}
				foreach(glob($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/*.css') as $filename){ // get template css files
					if(preg_match('/admin.css/i', $filename) != TRUE){ // Not admin so strip out admin css
						$css[] = $filename; // add each filename into css array
					}
				}
				if(file_exists($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/style.php')){ // get style.php if exist
					$css[] = $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/style.php';
				}
				if((defined('admin') == TRUE) && file_exists($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/style.php')){ // get style.php if exist
					$css[] = $n3u_configVars['Template_Dir'] . 'admin/style.php';
				}
				if((defined('admin') == TRUE) && file_exists($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/style.php')){ // get style.php if exist
					$css[] = $n3u_configVars['Template_Dir'] . 'admin/admin.css';
				}
				$pattern = array('\'\s+\'','/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/','/images\/' . '/'); // what to find
				$replacement = array(' ','','../../' . $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/'); // what to replace
				ob_start();
				foreach($css as $file){require_once($file);} // require every file from the css array
				$n3u_cacheFileDump = str_replace(array(' { ',' } '),array('{','} '),preg_replace($pattern,$replacement,ob_get_contents())); // Dump contents as var & replace from patterns
				ob_end_clean(); // Stop & flush buffer
				$n3u_cacheFileName = fopen($n3u_cacheFilePath, 'w'); // open as writeable
				fwrite($n3u_cacheFileName, $n3u_cacheFileDump); // Write the file from var
				fclose($n3u_cacheFileName); // Close file
				unset($css,$file,$n3u_cacheFileName,$n3u_cacheFileDump,$pattern,$replacement);
			}
			echo "\t\t" . '<link rel="stylesheet" type="text/css" href="' . $n3u_cacheFilePath . '">' . PHP_EOL;	
			unset($n3u_cacheFilePath,$url);
		}
	}
	/**
		n3u_CacheEnd() is used to end the caching process and write the results 
	    to a single cache file. Cache has already been minified and combined.
	**/
	function n3u_CacheEnd(){
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_cacheFile;
		// Cache the contents to a file
		if(defined('admin') || $n3u_configVars['caching'] == FALSE){
			ob_end_flush(); // Send the output to browser & flush buffer
		}else{
			$n3u_cacheFileName = fopen($n3u_cacheFile, 'w'); // Open file as writeable
			$n3u_cacheFileDump = str_replace('> <','><',preg_replace("'\s+'",' ',ob_get_contents())); // Remove extra spaces to minify
			fwrite($n3u_cacheFileName, $n3u_cacheFileDump); // Write the file
			fclose($n3u_cacheFileName); // Close file
			ob_end_flush(); // Send the output to browser & flush buffer
		}
	}
	/**
		n3u_CacheImage() is used to cache the images returned by Prosperent if 
		Image Caching is enabled in the Cache Settings of n3u Niche Store.
		Images appear to be from your site instead of from another domain. The 
	    browser also deals with less http request and gains performance from 
		having the file ready to serve. Images are sorted based on their size.
	**/
	function n3u_CacheImage($src_img,$val){
		global $n3u_configVars;
	//	global $n3u_inputVars;
		if($n3u_configVars['cacheImgs'] == FALSE){return $src_img;} // nothing further to do.
		$dst_img = $n3u_configVars['img_dir'] . $n3u_configVars['img_size'] . '/' . $val . '_'.$n3u_configVars['img_size'].'.jpg';
		if(!is_dir($n3u_configVars['img_dir'])){
			mkdir($n3u_configVars['img_dir']);
			fclose(fopen($n3u_configVars['img_dir'] . 'index.php', 'w'));
		}elseif(!is_dir($n3u_configVars['img_dir'] . $n3u_configVars['img_size'] . '/')){
			mkdir($n3u_configVars['img_dir'] . $n3u_configVars['img_size'] . '/');
			fclose(fopen($n3u_configVars['img_dir'] . $n3u_configVars['img_size'] . '/' . 'index.php', 'w'));
		}
		if(!is_file($dst_img)){ // Check if file exist
			@copy($src_img,$dst_img);
			usleep(50000); // small delay to minimize timeouts from remote images, increase if needed (microseconds)
		}
		$dst_size = @filesize($dst_img);
		if($dst_size < 225){
			@unlink($dst_img); // file is empty or likely invalid, delete file
			return $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/NoImg'.$n3u_configVars['img_size'].'.png';
		}
		return $dst_img;
	}
	/**
		n3u_CacheJS() is used to cache JS in a similar manner to n3u_CacheCSS().
	**/
	function n3u_CacheJS(){
		global $n3u_configVars;
		global $n3u_ServerVars;
		if(defined('admin') || $n3u_configVars['caching'] == FALSE){ // if admin or if caching is disabled
			echo "\t\t" . '<script type="text/javascript">' . PHP_EOL
			. "\t\t" . '  var _prosperent = {' . PHP_EOL
			. "\t\t" . '    \'campaign_id\': \'4fc3f330cc53edb6b1b672001dd0a607\',' . PHP_EOL
			. "\t\t" . '    \'platform\': \'other\'' . PHP_EOL
			. "\t\t" . '  };' . PHP_EOL
			. "\t\t" . '</script>' . PHP_EOL
			. "\t\t" . '<script defer type="text/javascript" src="http://prosperent.com/js/prosperent.js"></script>' . PHP_EOL
			. "\t\t" . '<script defer src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>' . PHP_EOL;
			foreach(glob($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/*.js') as $filename){echo "\t\t" . '<script defer src="' . $filename . '"></script>' . PHP_EOL;} // get template js files
			foreach(glob($n3u_configVars['blocks_dir'] . '*/*.js') as $filename){if(preg_match('/disabled/i', $filename) != TRUE){echo "\t\t" . '<script defer src="' . $filename . '"></script>' . PHP_EOL;}} // get block js files
			if($n3u_configVars['jScroll'] == TRUE){echo "\t\t" . '<script defer src="'.$n3u_configVars['include_dir'].'jquery.jscroll.min.js"></script>' . PHP_EOL;}
		}else{ // if not admin and caching is enabled and cache is old
			$url = parse_url($n3u_ServerVars['HTTP_HOST']);
			if(!isset($url['host']) || $url['host'] == NULL){
				if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
					$url['host'] = $url['path'];
				}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE_NAME
					$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
				}else{ // Use SiteURL
					$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
				}
			}
			$n3u_cacheFilePath = $n3u_configVars['cache_dir'] .$url['host']. '/' . 'js.js'; // Set cache file name
			if(file_exists($n3u_cacheFilePath) && time() - $n3u_configVars['lifetime'] < filemtime($n3u_cacheFilePath)){ // else if a recent cache file exist
				// Already exist and is recent so do nothing.
			}else{
				$js = array();
				foreach(glob($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/*.js') as $filename){ // get template js files
					$js[] = $filename; // add each filename into js array
				}
				foreach(glob($n3u_configVars['blocks_dir'] . '*/*.js') as $filename){ // get block js files
					if(preg_match('/disabled/i', $filename) != TRUE){$js[] = $filename;} // add each filename into js array but ignore disabled blocks
				}
				ob_start();
				foreach($js as $file){require_once($file);} // require every file from the js array
				$n3u_cacheFileDump = preg_replace(array('\'\s+\'','/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/'),array(' ',''),ob_get_contents()); // Dump contents as var & replace from patterns
				ob_end_clean(); // Stop & flush buffer
				$n3u_cacheFileName = fopen($n3u_cacheFilePath, 'w'); // open as writeable
				fwrite($n3u_cacheFileName, $n3u_cacheFileDump); // Write the file from var
				fclose($n3u_cacheFileName); // Close file
				unset($js,$file,$n3u_cacheFileName,$n3u_cacheFileDump);
			}
	//		echo "\t\t" . '<script type="text/javascript">' . PHP_EOL
		//	. "\t\t\t" . '<!--' . PHP_EOL
	//		. "\t\t\t" . 'prosperent_pa_uid = '.$n3u_configVars['Prosperent_UserID'].';' . PHP_EOL
	//		. "\t\t\t" . 'prosperent_pa_fallback_query = \''.$n3u_configVars['defaultKeyword'].'\';' . PHP_EOL
		//	. "\t\t\t" . '//-->' . PHP_EOL
	//		. "\t\t" . '</script>' . PHP_EOL
			echo "\t\t" . '<script type="text/javascript">' . PHP_EOL
			. "\t\t" . '  var _prosperent = {' . PHP_EOL
			. "\t\t" . '    \'campaign_id\': \'4fc3f330cc53edb6b1b672001dd0a607\',' . PHP_EOL
			. "\t\t" . '    \'platform\': \'other\'' . PHP_EOL
			. "\t\t" . '  };' . PHP_EOL
			. "\t\t" . '</script>' . PHP_EOL
			. "\t\t" . '<script defer src="http://prosperent.com/js/prosperent.js" type="text/javascript"></script>' . PHP_EOL
			. "\t\t" . '<script defer src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>' . PHP_EOL
			. "\t\t" . '<script defer src="' . $n3u_cacheFilePath . '" type="text/javascript"></script>' . PHP_EOL;
			if($n3u_configVars['jScroll'] == TRUE){echo "\t\t" . '<script defer src="'.$n3u_configVars['include_dir'].'jquery.jscroll.min.js" type="text/javascript"></script>' . PHP_EOL;}
			unset($n3u_cacheFilePath,$url);
		}
	}
	/**
		n3u_CheckApiKey() is used for the Supporter Feature which is fully
		configurable in the Administration Panel. Supporters support the project
		by allocating n3u.com's api key instead of theirs for that specific item
		For example, if a val of 10 is passed (which is default) that translates
		into n3u.com having a 1 out of a 10 chance to have it's API Key applied 
		instead of the default sites API Key. That site would still then average
		it's api key being applied 9 out of 10 times. n3u Niche Store allows you
		full control of this in the Admin Panel so rather than disable if you 
		find 1 out of 10 too many, Please try another allocation. It's up to you 
		though and n3u.com forces nothing.
	**/
	function n3u_CheckApiKey($api_key,$val=10){ // Check API Key
		global $n3u_configVars;
		if($val == 0){
			return $api_key; // 0 means disabled, so we just return the users api key and never set n3u Niche Stores :(
		}elseif($val < 3){ // val was set for 1 or 2 meaning user attempted to allocate over 50% of page views to n3u Niche Store
			$val = 3; // This is overridden to 3 meaning 1 out 3 visits (or 33%), The extra view(s) are given back since 3 is minimum for if statement below. Otherwise lower numbers would never return true.
		} // $val was set 3 or higher, Process check as normal.
		if(rand(1,$val) == 3){ // 3 is symbolic so if 3 is picked at random, replace with n3u's API Key
			$api_key = base64_decode('ODcwM2I1OWJhMWE2NDQ5ZmE3ZjM2MWZhMTM5ODI1MjQ=');
		}
		return $api_key; // returns either the sites api key, or n3u.com's if conditions are met
	}
	/**
		n3u_CheckCache() is used for cache cleanup. You configure the Frequency 
		in the Administration Panel. This affects both file and image caches.
		Files are emptied at random based on the Frequency. Images are only 
		emptied if they are found to be older than 45 days. This function also 
		detects when the administrator empties the cache.
	**/
	function n3u_CheckCache(){ // Check Cache
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_PostVars;
		if(rand(1,$n3u_configVars['ClearCacheFreq']) == 3){ // if 3, empty cache (avg 1 out of ClearCacheFreq visits)
			n3u_ClearCache(); // Clear File Caches, Auto cleans cache folder 1 out of ClearCacheFreq visits for older files.
		}
		if(rand(1,$n3u_configVars['ClearImgCacheFreq']) == 3){ // if 3, empty cache (avg 1 out of ClearImgCacheFreq visits)
			n3u_ClearImages(60); // Clear Image Caches, Auto cleans images folders 1 out of ClearImgCacheFreq visits for older images.
		}
		if(isset($n3u_inputVars['clearcache']) && defined('admin')){ // Check if sent via server via GET (Also make sure is admin)
			n3u_ClearCache(); // Delete all file caches (but not images)
		}
		if(isset($n3u_inputVars['clearimgs']) && defined('admin')){ // Clear Image Caches
			if($n3u_inputVars['clearimgs'] == 'accessed'){
				n3u_ClearImages(60); // Clear images that were last-accessed 45 days or more ago (cleans stale, keeps recently accessed)
			}elseif($n3u_inputVars['clearimgs'] == 'modified'){
				n3u_ClearImages(60,TRUE); // Clear images that were last-modified 45 days or more ago (cleans any older than 45 days)
			}elseif($n3u_inputVars['clearimgs'] == 'all'){
				n3u_ClearImages(); // Clear all images (not recommended, Spiders)
			}
		}
		if(isset($n3u_PostVars['ClearCache']) && $n3u_PostVars['ClearCache'] == 'ClearCacheFiles'){ // Check if sent to server via $n3u_PostVars
			n3u_ClearCache(); // Clear cache files (does not include images)
		}elseif(isset($n3u_PostVars['ClearCache']) && $n3u_PostVars['ClearCache'] == 'ClearAllImages'){
			n3u_ClearImages();  // Clear all images (not recommended, Spiders)
		}elseif(isset($n3u_PostVars['ClearCache']) && $n3u_PostVars['ClearCache'] == 'ClearLastAccessedImages'){
			n3u_ClearImages(60); // Clear images that were last-accessed 45 days or more ago (cleans stale, keep recently accessed)
		}elseif(isset($n3u_PostVars['ClearCache']) && $n3u_PostVars['ClearCache'] == 'ClearLastModifiedImages'){
			n3u_ClearImages(60,TRUE); // Clear images that were last-modified 45 days or more ago (cleans any older than 30 days ago)
		}
	}
	/**
		n3u_Cleaner() may be redundant and needs to be checked.
	
	function n3u_Cleaner($string){
		$id_entities = array('%21','%2A','%27','%28','%29','%3B','%3A','%40','%26','&','%3D','%2B','%24','%2C','%2F','%3F','%25','%23','%5B','%5D');
		$id_replacements = array('!','*',"'",'(', ')',';',':','@','&amp;','&amp;','=','+','$',',','/','?','%','#','[',']');
		return str_replace($id_entities,$id_replacements,$string);
	}**/
	/**
		n3u_Clean() may be redundant and needs to be checked.
	
	function n3u_Clean($string){
		$id_entities = array('\'','.',',','/','&','  ');
		$id_replacements = array('',' ','','','and',' ');
		return strtolower(urlencode(str_replace($id_entities,$id_replacements,$string)));
	}**/
	/**
		n3u_ClearCache() does the actual job of cleaning file caches. If called
		directly, expect that file caches are emptied without condition.
	**/
	function n3u_ClearCache(){
		global $n3u_configVars;
		$files = array_filter(glob('{' . $n3u_configVars['cache_dir'] . '*,'. $n3u_configVars['cache_dir'] . '*/*}',GLOB_BRACE), 'is_file');
		$folders = glob($n3u_configVars['cache_dir'] . '*',GLOB_ONLYDIR);
		array_map("unlink", $files);
		array_map("rmdir", $folders);
		unset($folders,$files);
	}
	/**
		n3u_ClearImages() does the actual job of cleaning image caches. If
		called directly, expect that image caches are emptied without condition.
	**/
	function n3u_ClearImages($val=null,$val2=FALSE){
		global $n3u_configVars;
		$files = array_filter(glob('{' . $n3u_configVars['img_dir'] . '*,'. $n3u_configVars['img_dir'] . '*/*,'. $n3u_configVars['img_dir'] . '*/*/*}',GLOB_BRACE), 'is_file');
		$folders = array_filter($files, 'is_dir');
		if($val == null){ // empty, clear all
			array_map("unlink", $files);
			array_map("rmdir", $folders);
		}elseif(is_int($val)){ // number of days
			if($val2 == TRUE){ // last modified
				foreach ($files as $file){
					if(filemtime($file) < strtotime('-' . $val . ' days')){@unlink($file);} // delete file
				}
			}elseif($val2 == FALSE){ // last accessed
				foreach ($files as $file){
					if(fileatime($file) < strtotime('-' . $val . ' days')){@unlink($file);} // delete file
				}
			}
		}
		unset($folders,$files);
	}
	/**
		n3u_Config() is used to retrieve and filter the ocnfiguration for 
		n3u_ConfigVars().
	**/
	function n3u_Config(){ // Check installation, get data
		global $n3u_configArgs; // Make $n3u_configArgs Global
		global $n3u_configVars;
		global $n3u_lang;
		global $n3u_ServerVars;
		if(file_exists('config.php')){require_once('config.php');} // Backwards compatibility
		$url = parse_url($n3u_ServerVars['HTTP_HOST']);
		if(!isset($url['host']) || $url['host'] == NULL){
			if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
				$url['host'] = $url['path'];
			}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE_NAME
				$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
			}else{ // Use SiteURL
				$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
			}
		}
		if(file_exists($url['host'].'_config.php')){require_once($url['host'].'_config.php');} // This should get config for sub domains and overtide main configs array in the process.
		$n3u_configArgs = array(
			'accessKey' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'AdminCategories' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'AdminCatList' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'api_key' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'blocks_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'cache_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'cacheBackend' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'cacheImgs' => FILTER_VALIDATE_BOOLEAN,
			'caching' => FILTER_VALIDATE_BOOLEAN,
			'ClearCacheFreq' => FILTER_VALIDATE_INT,
			'ClearImgCacheFreq' => FILTER_VALIDATE_INT,
			'Categories' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'CategoryFilters' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
		//	'channel_id' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'CleanUrls' => FILTER_VALIDATE_BOOLEAN,
			'commissionDateMonths' => FILTER_VALIDATE_INT,
			'commissionDateRange' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'debug' => FILTER_VALIDATE_BOOLEAN,
			'enableCoupons' => FILTER_VALIDATE_BOOLEAN,
			'enableFacets' => FILTER_VALIDATE_BOOLEAN,
			'enableJsonCompression' => FILTER_VALIDATE_BOOLEAN,
			'enableQuerySuggestion' => FILTER_VALIDATE_BOOLEAN,
			'defaultKeyword' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'defaultLanguage' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'img_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'img_size' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_HIGH),
			'include_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'language_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'lifetime' =>  FILTER_SANITIZE_NUMBER_INT,
			'limit' => FILTER_SANITIZE_NUMBER_INT,
			'logging' => FILTER_VALIDATE_BOOLEAN,
			'msg_dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'password' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'Prosperent_Endpoint' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'Prosperent_UserID' => FILTER_SANITIZE_NUMBER_INT,
			'querySuggestion' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'reCaptcha_privKey' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'reCaptcha_pubKey' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_ENCODE_HIGH),
			'jScroll' => FILTER_VALIDATE_BOOLEAN,
			'self' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'sid' => FILTER_SANITIZE_URL,
			'SiteEmail' => FILTER_VALIDATE_EMAIL,
			'SiteIndex' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'SiteName' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'SiteURL' => FILTER_SANITIZE_URL,
			'Supporter' => FILTER_SANITIZE_NUMBER_INT,
			'Template_Dir' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'Template_Name' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'username' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'Version' => array('filter' => FILTER_SANITIZE_NUMBER_FLOAT,'flags' => FILTER_FLAG_ALLOW_FRACTION),
		);
		$n3u_configVars = @filter_var_array($n3u_configData,$n3u_configArgs);
		unset($url);
	}
	/**
		n3u_CustomPages() restrieves custom pages and builds and array
	**/
	function n3u_CustomPages($page_limit = NULL){ // Returns array of Custom pages
		global $n3u_configVars;
		$n3u_customPages = array();
		$i = 1;
		$url = n3u_HTTP_Host();
		foreach(glob($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . "*.php") as $n3u_custom_page){
			$n3u_custom_pagename = str_replace('.php','',str_replace($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_','',$n3u_custom_page));
			$n3u_customPages[$i]['name'] = $n3u_custom_pagename;
			$n3u_customPages[$i]['path'] = $n3u_custom_page;
			if($n3u_configVars['CleanUrls'] == TRUE){
				$n3u_customPages[$i]['url'] = './page_'. $n3u_custom_pagename .'.htm';
			}else{
				$n3u_customPages[$i]['url'] = $n3u_configVars['self'] . '?x=page&amp;page=' . $n3u_custom_pagename;
			}
			if($page_limit == NULL){ // limits pages returned if specified
				$i++;
			}elseif($page_limit > $i){
				$i++;
			}
		} // finds all custom pages in templates custom directory, builds array
		unset($page_limit,$n3u_custom_page,$n3u_custom_pagename,$i,$url);
		return $n3u_customPages;
	}
	function Boolean2String($boolean = NULL){ // returns string
	  return($boolean?'True':'False');
	}
	/**
		n3u_date() returns the date in the format requested.
	**/
	function n3u_date($date, $format){
		$n3u_date = date($format, strtotime($date));
		return $n3u_date;
	}
	/**
		n3u_Debug() returns debugging information if Debug is enabled.
	**/
	function n3u_Debug($n3u_val,$info=FALSE){
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_lang;
		global $prosperentApi;
		global $n3u_results; //$n3u_results
		if($info == TRUE){
			if($n3u_configVars['debug'] == TRUE){ // Is debug mode enabled?
				if(defined('admin')){
					// Error & Debug Info
					echo "\t\t\t\t" . '<div class="block_footer" id="Debug">' . PHP_EOL
					. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Debug'] . '</h3>' . PHP_EOL
					. "\t\t\t\t\t" . '<hr />' . PHP_EOL
					. "\t\t\t\t\t" . '<form action="' . $n3u_configVars['self'] . '" id="debug_form" method="post">' . PHP_EOL
					. "\t\t\t\t\t\t" . '<fieldset>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<legend>' . $n3u_lang['Debug_Mode'] . '</legend>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<ul>' . PHP_EOL
					. "\t\t\t\t\t\t\t\t" . '<li>' . $n3u_lang['n3u_Niche_Store'] . ': <span>' . $n3u_lang['Debug_Enabled'] . '</span></li>' . PHP_EOL
					. "\t\t\t\t\t\t\t\t" . '<li>' . $n3u_lang['n3u_Niche_Store'] . ': <span>' . $n3u_lang['Debug_Explain1'] . '</span></li>' . PHP_EOL;
					if(in_array($n3u_inputVars['x'],array('index','item','search'))){
						n3u_FetchSearch();
						if($prosperentApi->hasWarnings()){
							foreach($prosperentApi->getWarnings() as $n3u_Warnings){
								echo "\t\t\t\t\t\t\t\t" . '<li>' . $n3u_lang['Prosperent'] . ': <span class="explain">(' . $n3u_Warnings['code'] . ') ' . $n3u_Warnings['msg'] . '</span></li>' . PHP_EOL;
							}
						}
						if($prosperentApi->hasErrors()){
							foreach($prosperentApi->getErrors() as $n3u_Errors){
								echo "\t\t\t\t\t\t\t\t" . '<li>' . $n3u_lang['Prosperent'] . ': <span class="explain">(' . $n3u_Errors['code'] . ') ' . $n3u_Errors['msg'] . '</span></li>' . PHP_EOL;
							}
						}
						echo "\t\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
						if(isset($n3u_results) && $n3u_results != NULL){
							echo "\t\t\t\t\t\t\t\t" . '<label>Dump of $n3u_results: </label>' . PHP_EOL;
							foreach($n3u_results as $n3u_resultsString){
								foreach($n3u_resultsString as $n3u_resultKey => $n3u_resultString){
									echo "\t\t\t\t\t\t\t\t" . '<div class="debugvalues"><div class="keyvalue">$n3u_results[\''.$n3u_resultKey.'\']&nbsp;=&nbsp;</div>' . '<div class="stringvalue">'.str_replace('&','&amp;',var_export($n3u_resultString,TRUE)).';</div></div>' . PHP_EOL;
								}
								echo "\t\t\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL;
							}	
						}
					}else{
						echo "\t\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
					}
					echo "\t\t\t\t\t\t\t" . '<label>Dump of $n3u_inputVars: </label>' . PHP_EOL;
					foreach($n3u_inputVars as $n3u_inputVarKey => $n3u_inputVarString){
						echo "\t\t\t\t\t\t\t" . '<div class="debugvalues"><div class="keyvalue">$n3u_inputVars[\''.$n3u_inputVarKey.'\']&nbsp;=&nbsp;</div>'
						. '<div class="stringvalue">'.var_export($n3u_inputVarString,TRUE).';</div></div>' . PHP_EOL;
					}
					echo "\t\t\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
					. "\t\t\t\t\t\t\t\t" . '<label>Dump of $n3u_configVars: </label>' . PHP_EOL;
					foreach($n3u_configVars as $n3u_configVarKey => $n3u_configVarString){
						if($n3u_configVarKey == 'password'){
							$n3u_configVarString = str_replace($n3u_configVarString,'*******',$n3u_configVarString);
						}
						echo "\t\t\t\t\t\t\t" . '<div class="debugvalues"><div class="keyvalue">$n3u_configVars[\''.$n3u_configVarKey.'\']&nbsp;=&nbsp;</div>'
						. '<div class="stringvalue">'.var_export($n3u_configVarString,TRUE).';</div></div>' . PHP_EOL;
					}
					echo "\t\t\t\t\t\t" . '</fieldset>' . PHP_EOL
					. "\t\t\t\t\t" . '</form>' . PHP_EOL
					. "\t\t\t\t" . '</div>' . PHP_EOL; //div debug
				}
			}
		}else{ // Not generating info, instead debug an array
			if(isset($n3u_val) && $n3u_val != NULL){
				echo "\t\t\t\t\t" . '<pre>' . PHP_EOL;
				foreach($n3u_val as $n3u_row){print_r(str_replace('&','&amp;',array_combine(array_keys($n3u_row), array_values($n3u_row))));}
				echo "\t\t\t\t\t" . '</pre>' . PHP_EOL;
			}
		}
	}
	/**
		n3u_Defaults() sets the minimums or defaults for n3u Niche Store.
	**/
	function n3u_Defaults(){
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_lang;
		global $n3u_extendedSort;
		global $n3u_ServerVars;
		if($n3u_configVars['debug'] == TRUE){
			error_reporting(E_ALL ^ E_NOTICE); // Report PHP errors but not notices
		}else{
			error_reporting(0); // Report no PHP errors
		}
		// First let's set defaults for $n3u_inputVars
		if(!isset($n3u_inputVars['b']) || $n3u_inputVars['b'] == array('Any'||'Unknown')){$n3u_inputVars['b'] = '';}
	//	if(!isset($n3u_inputVars['compare'])){$n3u_inputVars['compare'] = NULL;}
		if(!isset($n3u_inputVars['m']) || $n3u_inputVars['m'] == array('Any'||'Unknown')){$n3u_inputVars['m'] = '';}
		if(!isset($n3u_inputVars['url'])){unset($n3u_inputVars['url']);}
	//	if(!isset($n3u_inputVars['price_max']) || ($n3u_inputVars['price_max'] == NULL)){$n3u_inputVars['price_max'] = '0';}
	//	if(!isset($n3u_inputVars['price_min']) || ($n3u_inputVars['price_min'] == NULL)){$n3u_inputVars['price_min'] = '0';}
		if(!isset($n3u_inputVars['p']) || ($n3u_inputVars['p'] == NULL)){$n3u_inputVars['p'] = '1';}
		if(!isset($n3u_inputVars['sort']) || !in_array($n3u_inputVars['sort'],array('ASC','DESC','REL'))){$n3u_inputVars['sort'] = 'REL';}
		if(!isset($n3u_inputVars['item'])){$n3u_inputVars['item'] = '';} // implement an error here
		if(isset($n3u_inputVars['sort']) && $n3u_inputVars['sort'] == 'ASC'){
			$n3u_extendedSort= 'price ASC';
		}elseif(isset($n3u_inputVars['sort']) && $n3u_inputVars['sort'] == 'DESC'){
			$n3u_extendedSort= 'price DESC';
		}elseif(isset($n3u_inputVars['sort']) && $n3u_inputVars['sort'] == 'REL'){
			$n3u_extendedSort= '@relevance DESC';
		}
		// defaults
		if(!isset($n3u_ServerVars['HTTP_CF_CONNECTING_IP'])){$n3u_configVars['visitor_ip'] = @$n3u_ServerVars['REMOTE_ADDR'];}else{$n3u_configVars['visitor_ip'] = $n3u_ServerVars['HTTP_CF_CONNECTING_IP'];}		// CloudFlare is a reverse proxy, so all ips look like they originate from cloudflare, CloudFlare passes REMOTE_ADDR as HTTP_CF_CONNECTING_IP
		if(!isset($n3u_ServerVars['HTTP_REFERER'])){$n3u_configVars['referrer'] = '';$n3u_ServerVars['HTTP_REFERER'] = '';}else{$n3u_configVars['referrer'] = $n3u_ServerVars['HTTP_REFERER'];} // Auto determines Visitors Referrer
		if(preg_match("/" . @$n3u_ServerVars['HTTP_HOST'] . "/i",$n3u_ServerVars['HTTP_REFERER'])){$n3u_configVars['referrer'] = '';$n3u_ServerVars['HTTP_REFERER'] = '';} // empties referrer if from site.
		if(!isset($n3u_configVars['api_key']) || $n3u_configVars['api_key'] == NULL){$n3u_configVars['api_key'] = '8703b59ba1a6449fa7f361fa13982524'; } // Use n3u's if no other is set (to ensure script functions)
		if(!isset($n3u_configVars['blocks_dir']) || $n3u_configVars['blocks_dir'] == NULL){$n3u_configVars['blocks_dir'] = 'blocks/';}
		if(!isset($n3u_configVars['cacheBackend']) || $n3u_configVars['cacheBackend'] == NULL){$n3u_configVars['cacheBackend'] = 'File';}
		if(!isset($n3u_configVars['cacheImgs']) || $n3u_configVars['cacheImgs'] == NULL){$n3u_configVars['cacheImgs'] = FALSE;}
		if(!isset($n3u_configVars['cache_dir']) || $n3u_configVars['cache_dir'] == NULL){$n3u_configVars['cache_dir'] = 'cache/';}
		if(!isset($n3u_configVars['caching']) || $n3u_configVars['caching'] == NULL){$n3u_configVars['caching'] = FALSE;}
		if(!isset($n3u_configVars['ClearCacheFreq']) || $n3u_configVars['ClearCacheFreq'] == NULL){$n3u_configVars['ClearCacheFreq'] = 100;}
		if(!isset($n3u_configVars['ClearImgCacheFreq']) || $n3u_configVars['ClearImgCacheFreq'] == NULL){$n3u_configVars['ClearImgCacheFreq'] = 100;}
		if(!is_dir($n3u_configVars['cache_dir'])){mkdir($n3u_configVars['cache_dir']);fclose(fopen($n3u_configVars['cache_dir'] . 'index.php', 'w'));} // Auto creates cache folder & index.php
		$url = parse_url($n3u_ServerVars['HTTP_HOST']);
		if(!isset($url['host']) || $url['host'] == NULL){
			if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
				$url['host'] = $url['path'];
			}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE_NAME
				$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
			}else{ // Use SiteURL
				$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
			}
		}
		if(!is_dir($n3u_configVars['cache_dir'] . $url['host'] . '/')){mkdir($n3u_configVars['cache_dir'] . $url['host'] . '/');fclose(fopen($n3u_configVars['cache_dir'] . $url['host'] . '/' . 'index.php', 'w'));} // Auto creates cache folder & index.php
		unset($url);
		if(!isset($n3u_configVars['Categories']) || $n3u_configVars['Categories'] == NULL){$n3u_configVars['Categories'] = '7.2v,9.6v,12v,14.4v,18v,20v';}
		if(!isset($n3u_configVars['CategoryFilters']) || $n3u_configVars['CategoryFilters'] == NULL){$n3u_configVars['CategoryFilters'] = 'refurb|refurbished|recondition|reconditioned';}
		$n3u_configVars['CategoryFilters'] = str_replace(array(' , ',', ',',',' | ','| ',' '),array('|','|','|','|','|','%20'),$n3u_configVars['CategoryFilters']);
		if(!isset($n3u_configVars['CleanUrls']) || $n3u_configVars['CleanUrls'] == NULL){$n3u_configVars['CleanUrls'] = FALSE;}
		if(!isset($n3u_configVars['commissionDateMonths']) || $n3u_configVars['commissionDateMonths'] == NULL){$n3u_configVars['commissionDateMonths'] = 3;}
		if(!isset($n3u_configVars['commissionDateRange']) || $n3u_configVars['commissionDateRange'] == NULL){$n3u_configVars['commissionDateRange'] = date('Ymd', strtotime('-'.$n3u_configVars['commissionDateMonths'].' months -1 day')).','.date('Ymd', strtotime('yesterday'));}
		if(!isset($n3u_configVars['debug']) || $n3u_configVars['debug'] == NULL){$n3u_configVars['debug'] = FALSE;}
		if(!isset($n3u_configVars['defaultKeyword']) || $n3u_configVars['defaultKeyword'] == NULL){$n3u_configVars['defaultKeyword'] ='Cordless Drill';}
		if(!isset($n3u_inputVars['q']) || $n3u_inputVars['q'] == NULL){$n3u_inputVars['q'] = $n3u_configVars['defaultKeyword'];}
		if($n3u_inputVars['q'] != $n3u_configVars['defaultKeyword']){$n3u_inputVars['q'] = $n3u_configVars['defaultKeyword'].' '.str_replace($n3u_configVars['defaultKeyword'] .' ','',$n3u_inputVars['q']);}else{$n3u_inputVars['q'] = $n3u_configVars['defaultKeyword'];}
		if($n3u_inputVars['q'] == $n3u_configVars['defaultKeyword'].' '.$n3u_configVars['defaultKeyword']){$n3u_inputVars['q'] = $n3u_configVars['defaultKeyword']; }
		if(!isset($n3u_configVars['defaultLanguage']) || $n3u_configVars['defaultLanguage'] == NULL){$n3u_configVars['defaultLanguage'] = 'en-us';}
		if(!isset($n3u_configVars['enableCoupons']) || $n3u_configVars['enableCoupons'] == NULL){$n3u_configVars['enableCoupons'] = FALSE;}
		if(!isset($n3u_configVars['enableFacets']) || $n3u_configVars['enableFacets'] == NULL){$n3u_configVars['enableFacets'] = TRUE;}
		if(!isset($n3u_configVars['enableJsonCompression']) || $n3u_configVars['enableJsonCompression'] == NULL){$n3u_configVars['enableJsonCompression'] = TRUE;}
		if(!isset($n3u_configVars['enableQuerySuggestion']) || $n3u_configVars['enableQuerySuggestion'] == NULL){$n3u_configVars['enableQuerySuggestion'] = TRUE;}
		if(!isset($n3u_inputVars['lang']) || $n3u_inputVars['lang'] == NULL){$n3u_inputVars['lang'] = $n3u_configVars['defaultLanguage'];} // set default language
		if(!isset($n3u_configVars['include_dir']) || $n3u_configVars['include_dir'] == NULL){$n3u_configVars['include_dir'] = 'inc/';}
		if(!is_dir($n3u_configVars['include_dir'])){mkdir($n3u_configVars['include_dir']);fclose(fopen($n3u_configVars['include_dir'] . 'index.php', 'w'));} // Auto creates inc folder & index.php
		if(!is_dir($n3u_configVars['include_dir'] . 'configs/')){mkdir($n3u_configVars['include_dir'] . 'configs/');fclose(fopen($n3u_configVars['include_dir'] . 'configs/index.php', 'w'));} // Auto creates custom folder & index.php
		if(!is_dir($n3u_configVars['include_dir'] . 'custom/')){mkdir($n3u_configVars['include_dir'] . 'custom/');fclose(fopen($n3u_configVars['include_dir'] . 'custom/index.php', 'w'));} // Auto creates custom folder & index.php
		if(!is_dir($n3u_configVars['include_dir'] . 'messages/')){mkdir($n3u_configVars['include_dir'] . 'messages/');fclose(fopen($n3u_configVars['include_dir'] . 'messages/index.php', 'w'));} // Auto creates custom folder & index.php
		if(!isset($n3u_configVars['img_dir']) || $n3u_configVars['img_dir'] == NULL){$n3u_configVars['img_dir'] = 'images/';}
		if(!isset($n3u_configVars['img_size']) || $n3u_configVars['img_size'] == NULL){$n3u_configVars['img_size'] = '125x125';}
		if(!isset($n3u_configVars['language_dir']) || $n3u_configVars['language_dir'] == NULL){$n3u_configVars['language_dir'] = 'languages/';}
		if(!isset($n3u_configVars['lifetime']) || $n3u_configVars['lifetime'] == NULL){$n3u_configVars['lifetime'] = '86400';}
		if(!isset($n3u_configVars['limit']) || $n3u_configVars['limit'] == NULL){$n3u_configVars['limit'] = '10';}
		if(!isset($n3u_configVars['username']) || $n3u_configVars['username'] == NULL){$n3u_configVars['username'] = 'n3uadmin';}
		if(!isset($n3u_configVars['password']) || $n3u_configVars['password'] == NULL){$n3u_configVars['password'] = 'n3upass';}
		if(!isset($n3u_configVars['location']) || $n3u_configVars['location'] == NULL){$n3u_configVars['location'] = 'http://' . $n3u_ServerVars['HTTP_HOST'] . $n3u_ServerVars['REQUEST_URI'];} // Auto determines page location, change to https only if your server supports
		if(!isset($n3u_configVars['Prosperent_Endpoint']) || $n3u_configVars['Prosperent_Endpoint'] == NULL){$n3u_configVars['Prosperent_Endpoint'] = 'USA';} // Defaults to USA
		if(!isset($n3u_configVars['Prosperent_UserID']) || $n3u_configVars['Prosperent_UserID'] == NULL){$n3u_configVars['Prosperent_UserID'] = '400414';}
		if(!isset($n3u_configVars['self'])){$n3u_configVars['self'] = str_replace(' ','%20',($n3u_ServerVars['PHP_SELF']));} // Auto determines current file path, ex: /index.php
		if(!isset($n3u_configVars['sid']) || $n3u_configVars['sid'] == NULL){$n3u_configVars['sid'] = @$n3u_ServerVars['HTTP_HOST'];} // Auto determines Domain Name to track by domain, Shouldn't need to change
		if(!isset($n3u_configVars['SiteIndex']) || $n3u_configVars['SiteIndex'] == NULL){$n3u_configVars['SiteIndex'] = 'index';} // Defaults to script name
		if(!isset($n3u_inputVars['x']) || $n3u_inputVars['x'] == NULL){$n3u_inputVars['x'] = $n3u_configVars['SiteIndex'];}
		$n3u_inputVars['x'] = strtolower($n3u_inputVars['x']); // Force lowercase
		if($n3u_inputVars['x'] == 'admin' && (!isset($n3u_inputVars['page']) || ($n3u_inputVars['page'] == NULL))){$n3u_inputVars['page'] = 'dashboard';} // Sets default admin page
		if(!isset($n3u_configVars['SiteName']) || $n3u_configVars['SiteName'] == NULL){$n3u_configVars['SiteName'] = 'n3u Niche Store';} // Defaults to script name
		if(!isset($n3u_configVars['SiteURL']) || $n3u_configVars['SiteURL'] == NULL){$n3u_configVars['SiteURL'] = 'http://' . $n3u_ServerVars['HTTP_HOST'] . '/';} // Defaults to script name
		if(!isset($n3u_configVars['Supporter'])){$n3u_configVars['Supporter'] = 10;}elseif(isset($n3u_configVars['Supporter']) && !is_numeric($n3u_configVars['Supporter'])){$n3u_configVars['Supporter'] = '0';} // Defaults on and to 1 out of 10
		if(!isset($n3u_configVars['Template_Dir']) || $n3u_configVars['Template_Dir'] == NULL){$n3u_configVars['Template_Dir'] = 'templates/';}
		if(!isset($n3u_configVars['Template_Name']) || $n3u_configVars['Template_Name'] == NULL){$n3u_configVars['Template_Name'] = 'n3u';}
		if(!isset($n3u_configVars['userAgent']) || $n3u_configVars['userAgent'] == NULL){$n3u_configVars['userAgent'] = @$n3u_ServerVars['HTTP_USER_AGENT'];} // Auto determines Visitors User Agent, Shouldn't need to change
		if(!isset($n3u_configVars['Version']) || $n3u_configVars['Version'] == NULL){$n3u_configVars['Version'] = '14.02.20';}
		if(!defined('admin') && $n3u_inputVars['x'] == 'item'){$n3u_configVars['api_key'] = n3u_CheckApiKey($n3u_configVars['api_key'],$n3u_configVars['Supporter']);$n3u_configVars['img_size'] = '250x250';}
	}
	/**
		n3u_DirSize() retreives the sizes and file counts of directories.
	**/
	function n3u_DirSize($path=NULL,$count=FALSE){
		$i=0; // used for bytes
		$files_count=0; // used to count number of files
		$files = array_filter(glob('{'.$path.'*,'.$path.'*/*,'.$path.'*/*/*,'.$path.'*/*/*/*,'.$path.'*/*/*/*}',GLOB_BRACE),'is_file');  // Returns up to 5 levels deep.
		foreach($files as $file){
			$i=$i+filesize($file); // returned in bytes
			if($count == TRUE){$files_count++;} // Auto increase
		}
		if($count==TRUE){
			return round($i/1024/1024,2) . ' MB <em>('.$files_count.' Files)</em>';
		}else{
			return round($i/1024/1024,2) . ' MB';
		}
	}
	/**
		n3u_Error() produces an error. This function is used when you want to 
		throw a specific error. For example a page not found, or forbidden.
	**/
	function n3u_Error($ErrorNum,$ErrorDesc=NULL){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_errorVars;
		global $n3u_lang;
		$n3u_errorVars['Number'] = $ErrorNum;
		$n3u_errorVars['Description'] = $ErrorDesc;
		require_once($n3u_configVars['include_dir'] . 'error.php');
		require_once($n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/error.php');
	}
	/**
		n3u_ErrorChecker() checks to see if any errors have occured and throws 
		a relevant error if detected.
	**/
	function n3u_ErrorChecker(){
		global $n3u_inputVars;
		global $n3u_errorVars;
		global $n3u_lang;
		global $n3u_results;
		// Handle 404 errors properly to avoid search engine penalties
		$error_check = array('admin','contact','download','feed','go','index','item','login','logout','page','privacy','search','terms');
		if(!in_array($n3u_inputVars['x'],$error_check)){
			$n3u_inputVars['x'] = 'error'; // Set error mode
			$n3u_errorVars['Number'] = 404; // Set error type (number)
		}
		unset($error_check);
		if(($n3u_inputVars['x'] == 'download') && !defined('admin')){
			$n3u_inputVars['x'] = 'error';
			$n3u_errorVars['Number'] = 403; // Set error type (number)
			$n3u_errorVars['Description'] = $n3u_lang['Download_Not_Authorized']; // Set error type (number)
		}elseif($n3u_inputVars['x'] == 'download' && (!isset($n3u_inputVars['url']) || $n3u_inputVars['url'] == NULL)){
			$n3u_inputVars['x'] = 'error';
			$n3u_errorVars['Number'] = 403; // Set error type (number)
			$n3u_errorVars['Description'] = $n3u_lang['No_URL']; // Set error type (number)
		}elseif($n3u_inputVars['x'] == 'go' && (!isset($n3u_inputVars['url']) || $n3u_inputVars['url'] == NULL)){
			$n3u_inputVars['x'] = 'error';
			$n3u_errorVars['Number'] = 403; // Set error type (number)
			$n3u_errorVars['Description'] = $n3u_lang['No_URL']; // Set error type (number)
		}
	}
	/**
		n3u_extract() takes a block of text and strips it into sentences.
		This is mostly used to limit sentences in Search Results but is also used
		in meta descriptions with FALSE passed to prevent links.
	**/
	function n3u_extract($text,$num=5,$autolink=TRUE){
		global $n3u_configVars;
		global $n3u_inputVars;
		// Create array of sentences using period as the divider
		$sentence = explode('. ',$text); // destroys period & space
		$extract = ''; // set to empty
		$sentences = count($sentence); // Get total number of sentences
		$i=0;
		// This function is used to replace words actively in item and search result descriptions.
		$WordsToBeReplaced = array(
			$n3u_inputVars['q'], // search query
			$n3u_inputVars['m'], // search merchant
			$n3u_inputVars['b'], // search brand
		);
		// Links
		if($n3u_configVars['CleanUrls'] == TRUE){
			$LinksToReplaceWith = array(
				'<a href="search___'.urlencode($n3u_inputVars['q']).'--1.htm" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['q'])) . '">'.$n3u_inputVars['q'].'</a>', // replace query with link
				'<a href="search_'.urlencode($n3u_inputVars['m']).'__--1.htm" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['m'])) . '">'.$n3u_inputVars['m'].'</a>', // replace query with link
				'<a href="search__'.urlencode($n3u_inputVars['b']).'_--1.htm" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['b'])) . '">'.$n3u_inputVars['b'].'</a>', // replace query with link
			);
		}else{
			$LinksToReplaceWith = array(
				'<a href="index.php?x=search&amp;m=&amp;b=&amp;q='.urlencode($n3u_inputVars['q']).'&amp;sort=&amp;p=1" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['q'])) . '">'.$n3u_inputVars['q'].'</a>', // replace query with link
				'<a href="index.php?x=search&amp;m='.urlencode($n3u_inputVars['m']).'&amp;b=&amp;q=&amp;sort=&amp;p=1" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['m'])) . '">'.$n3u_inputVars['m'].'</a>', // replace query with link
				'<a href="index.php?x=search&amp;m=&amp;b='.urlencode($n3u_inputVars['b']).'&amp;q=&amp;sort=&amp;p=1" title="' . n3u_TitleCleaner(urlencode($n3u_inputVars['b'])) . '">'.$n3u_inputVars['b'].'</a>', // replace query with link
			);
		}
		if($autolink == TRUE){
			if($sentences > 1){
				// Rebuild, using number defined by $num, and adding period back in.
				while(($i<$num) && ($i < $sentences)){
					$extract .= "\t\t\t\t\t\t" . str_ireplace($WordsToBeReplaced,$LinksToReplaceWith,n3u_AutoLinker($sentence[$i])) . '.' . PHP_EOL;
					if(($i == 4) && ($sentences >= 5)){$extract .= "\t\t\t\t\t\t" . '<br />' . PHP_EOL;} // insert a break
					$i++;
				}
			}else{
				$extract = "\t\t\t\t\t\t" . str_ireplace($WordsToBeReplaced,$LinksToReplaceWith,n3u_AutoLinker($sentence[$i])) . '.' . PHP_EOL;
			}
		}else{
			if($sentences > 1){
				// Rebuild, using number defined by $num, and adding period back in.
				while(($i<$num) && ($i < $sentences)){
					$extract .= "\t\t\t\t\t\t" . $sentence[$i] . '.' . PHP_EOL;
					if(($i == 4) && ($sentences >= 5)){$extract .= "\t\t\t\t\t\t" . '<br />' . PHP_EOL;} // insert a break
					$i++;
				}
			}else{
				$extract = "\t\t\t\t\t\t" . $sentence[$i] . '.' . PHP_EOL;
			}
		}
		return str_replace(array('..','!.',',.'),array('.','!','.'),$extract);
	}
	/**
		n3u_Feed() generates a feed based on the active prosperent data.
		This function is mainly used for the category feeds.
	**/
	function n3u_Feed($val = NULL){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $prosperentApi;
		if($val != NULL){
			echo '<?xml version="1.0"?>' . PHP_EOL
			. '<feed xmlns="http://www.w3.org/2005/Atom">' . PHP_EOL;
			if(isset($n3u_inputVars['q']) && $n3u_inputVars['q'] != $n3u_configVars['defaultKeyword']){
				echo "\t" . '<title>' . str_replace($n3u_configVars['defaultKeyword'] .' ','',$n3u_inputVars['q']) . ' - ' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}elseif($n3u_inputVars['q'] == $n3u_configVars['defaultKeyword']){
				echo "\t" . '<title>' . $n3u_configVars['defaultKeyword'] . ' - ' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}else{
				echo "\t" . '<title>' . $n3u_configVars['SiteName'] . '</title>' . PHP_EOL;
			}
			if($n3u_configVars['CleanUrls'] == TRUE){
				echo "\t" . '<id>' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . str_replace('index.php','',$n3u_configVars['self']) .$n3u_inputVars['x'] . '_'.$n3u_inputVars['m'].'_'.$n3u_inputVars['b'].'_'.urlencode($n3u_inputVars['q']).'-REL-'.$n3u_inputVars['p'].'.xml',FILTER_SANITIZE_URL)) . '</id>' . PHP_EOL // feed id URI
				. "\t" . '<link rel="self" href="' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . str_replace('index.php','',$n3u_configVars['self']) .$n3u_inputVars['x'] . '_'.$n3u_inputVars['m'].'_'.$n3u_inputVars['b'].'_'.urlencode($n3u_inputVars['q']).'-REL-'.$n3u_inputVars['p'].'.xml',FILTER_SANITIZE_URL)) . '" />' . PHP_EOL;
			}else{
				echo "\t" . '<id>' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p='.$n3u_inputVars['p'], FILTER_SANITIZE_URL)) . '</id>' . PHP_EOL // feed id URI
				. "\t" . '<link rel="self" href="' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;m=' . urlencode($n3u_inputVars['m']) . '&amp;b=' . urlencode($n3u_inputVars['b']) . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=REL' . '&amp;p='.$n3u_inputVars['p'], FILTER_SANITIZE_URL)) . '" />' . PHP_EOL;
			}
			echo "\t" . '<updated>'.date('c').'</updated>' . PHP_EOL  // last update of feed
			. "\t" . '<author>' . PHP_EOL
			. "\t\t" . '<name>' . $n3u_configVars['SiteName'] . '</name>' . PHP_EOL // feed author, sitename
			. "\t" . '</author>' . PHP_EOL
			. "\t" . '<contributor>' . PHP_EOL
			. "\t\t" . '<name>' . $n3u_lang['n3u_Niche_Store'] . '</name>' . PHP_EOL
			. "\t" . '</contributor>' . PHP_EOL
			. "\t" . '<contributor>' . PHP_EOL
			. "\t\t" . '<name>' . $n3u_lang['Prosperent'] . '</name>' . PHP_EOL
			. "\t" . '</contributor>' . PHP_EOL;
			foreach($val as $item){
				$item['keyword'] = filter_var($item['keyword'],FILTER_SANITIZE_ENCODED,FILTER_FLAG_ENCODE_HIGH);
				$item['description'] = filter_var($item['description'],FILTER_SANITIZE_ENCODED,FILTER_FLAG_ENCODE_HIGH);
				echo "\t" . '<entry>' . PHP_EOL
				. "\t\t" . '<title>' . filter_var($item['keyword'],FILTER_SANITIZE_ENCODED,FILTER_FLAG_ENCODE_HIGH) . '</title>' . PHP_EOL; // item title
				if($n3u_configVars['CleanUrls'] == TRUE){
					echo "\t\t" . '<id>' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . str_replace('index.php','',$n3u_configVars['self']) . 'item_' . urlencode($item['catalogId']) . '.htm',FILTER_SANITIZE_URL)) . '</id>' . PHP_EOL // feed id URI
					. "\t\t" . '<link href="' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . str_replace('index.php','',$n3u_configVars['self']) . 'item_' . urlencode($item['catalogId']) . '.htm',FILTER_SANITIZE_URL)) . '" />' . PHP_EOL;
				}else{
					echo "\t\t" . '<id>' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=item&amp;item=' . urlencode($item['catalogId']), FILTER_SANITIZE_URL)) . '</id>' . PHP_EOL // feed id URI
					. "\t\t" . '<link href="' . filter_var(preg_replace('((?<!:)(//+))','/',$n3u_configVars['SiteURL'] . $n3u_configVars['self'] . '?x=item&amp;item=' . urlencode($item['catalogId']), FILTER_SANITIZE_URL)) . '" />' . PHP_EOL;	
				}
				echo "\t\t" . '<updated>'.gmdate('Y-m-d\TH:i:sP').'</updated>' . PHP_EOL // item date
				. "\t\t" . '<summary>' . PHP_EOL
				. str_replace("\t\t\t\t\t\t","\t\t\t",n3u_Extract($item['description'],3))
				. "\t\t" . '</summary>' . PHP_EOL // item description
				. "\t" . '</entry>' . PHP_EOL;
			}
			echo '</feed>';
		}
	}
	/**
		n3u_FetchBrands() retrieves all the brand facets.
	**/
	function n3u_FetchBrands($val = NULL){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $prosperentApi;
		@$prosperentApi = new Prosperent_Api(array(
			'imageSize'				=> '60x30',
			'limit'					=> '100',
			'page'					=> $n3u_inputVars['p'],
		));
		if(!$prosperentApi){die($n3u_lang['Prosperent_NoConnect']);} // Check for connection error
		return @$prosperentApi->fetchBrands($val); // Fetch search data, Notices are suppressed with @
	}
	/**
		n3u_FetchCommissions() is used to retreieve commission data.
	**/
	function n3u_FetchCommissions(){
		global $n3u_configVars;
		global $n3u_lang;
		global $prosperentApi;
		global $n3u_stats;
		@$prosperentApi = new Prosperent_Api(array(
			'accessKey'				=> $n3u_configVars['accessKey'], // Required
		//	'clickDateRange'		=> $n3u_configVars['commissionDateRange'], // Required
			'commissionDateRange'	=> $n3u_configVars['commissionDateRange'], // Required
			'enableJsonCompression'	=> $n3u_configVars['enableJsonCompression'],
		//	'enableFullData'		=> False,
		//	'userAgent'				=> $n3u_configVars['userAgent'],
		));
		try{
			if(!$prosperentApi){die($n3u_lang['Prosperent_NoConnect']);} // Check for connection error
			@$prosperentApi->fetchCommissions(); // Fetch search data, Notices are suppressed with @
			$n3u_stats = @$prosperentApi->getData('');
		}catch(Exception $prosperentApi) {
			die('Caught exception: '. $prosperentApi->getMessage());
		}
		return $n3u_stats;
	}
	/**
		n3u_FetchFeed() is used to pull and parse feeds and to limit results.
	**/
	function n3u_FetchFeed($feedurl = NULL,$limit = 3){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		if($n3u_configVars['caching'] == TRUE){
			$url = n3u_HTTP_Host();
			$n3u_FeedcacheFile = $n3u_configVars['cache_dir'] . $url['host'] . '/feed_' . md5($feedurl) . '_' . $limit . '.htm';
			if(file_exists($n3u_FeedcacheFile) && time() - $n3u_configVars['lifetime'] < filemtime($n3u_FeedcacheFile)){
				// $n3u_FeedcacheFileDump =  @filter_var(file_get_contents($n3u_FeedcacheFile),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH);
			//	echo 'old' . $url['host'];
				return require_once($n3u_FeedcacheFile); // Exist and is fresh, require.
			}else{ // old or didnt exist, make new
				if($feedurl != NULL){
			//		echo 'new' . $url['host'];
					ob_start();
					$feedurl = filter_var($feedurl,FILTER_SANITIZE_URL);
					$headers = get_headers($feedurl); // Get headers
					$return = NULL;
					if(substr($headers[0],9,3) == 200){ // if http 200 status (good)
						$rss = new DOMDocument();
						$rss->load($feedurl);
						$feed = array();
						foreach($rss->getElementsByTagName('item') as $node){
							$item = array( 
								'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
								'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
								'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
							);
							array_push($feed,$item);
						}
						for($x=0;$x<$limit;$x++){
							$title = str_replace('&','&amp;',$feed[$x]['title']);
							$link = $feed[$x]['link'];
							if($n3u_configVars['Prosperent_Endpoint'] == 'CA'){ // If Canada
								$date = date('Y-M-d',strtotime($feed[$x]['date']));
							}elseif($n3u_configVars['Prosperent_Endpoint'] == 'UK'){ // If UK
								$date = date('d/m/Y',strtotime($feed[$x]['date']));
							}else{ // Else assume US
								$date = date('M d, Y',strtotime($feed[$x]['date']));
							}
							if($title != NULL || $link != NULL){
								if($n3u_configVars['CleanUrls'] == TRUE){
									$return .= '<p><a class="link" href="go/' . base64_encode(urlencode($link)).'.htm" target="_blank" title="'.$title.'">'.$title.'</a><br /><small><em>'.$n3u_lang['Posted_on'].$date.'</em></small></p>';
								}else{
									$return .= '<p><a class="link" href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode($link)).'" target="_blank" title="'.$title.'">'.$title.'</a><br /><small><em>'.$n3u_lang['Posted_on'].$date.'</em></small></p>';
								}
							}
						}
						echo $return;
					}else{ // http status code was not 200, Most likely 404...
						echo '<span class="error">'.$n3u_lang['Feed_Cannot_Connect'].$feedurl.'.</span>';
					}
					$n3u_FeedcacheFileDump = ob_get_contents(); // Dump contents as var
					ob_end_flush(); // Stop & flush buffer
					@$n3u_FeedcacheFileName = fopen($n3u_FeedcacheFile, 'w'); // open as writeable
					@fwrite($n3u_FeedcacheFileName, $n3u_FeedcacheFileDump); // Write the file from var
					@fclose($n3u_FeedcacheFileName); // Close file
					unset($date,$feed,$link,$n3u_FeedcacheFileDump,$n3u_FeedcacheFileName,$return,$rss,$title);
				}
			}
			unset($url);
		}else{ // caching is disabled.
			if($feedurl != NULL){
				$feedurl = filter_var($feedurl,FILTER_SANITIZE_URL);
				$headers = get_headers($feedurl); // Get headers
				$return = NULL;
				if(substr($headers[0],9,3) == 200){ // if http 200 status (good)
					$rss = new DOMDocument();
					$rss->load($feedurl);
					$feed = array();
					foreach($rss->getElementsByTagName('item') as $node){
						$item = array( 
							'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						//	'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
							'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
							'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
						);
						array_push($feed,$item);
					}
					for($x=0;$x<$limit;$x++){
						$title = str_replace('&','&amp;',$feed[$x]['title']);
						$link = $feed[$x]['link'];
						if($n3u_configVars['Prosperent_Endpoint'] == 'CA'){ // If Canada
							$date = date('Y-M-d',strtotime($feed[$x]['date']));
						}elseif($n3u_configVars['Prosperent_Endpoint'] == 'UK'){ // If UK
							$date = date('d/m/Y',strtotime($feed[$x]['date']));
						}else{ // Else assume US
							$date = date('M d, Y',strtotime($feed[$x]['date']));
						}
					//	$description = $feed[$x]['desc'];
					//	$return .= "\t\t\t\t\t\t\t\t" . '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />' . PHP_EOL
					//	. "\t\t\t\t\t\t\t\t" . '<small><em>'.$n3u_lang['Posted_on'].$date.'</em></small></p>' . PHP_EOL;
						if($title != NULL || $link != NULL){
							if($n3u_configVars['CleanUrls'] == TRUE){
								$return .= '<p><strong><a class="link" href="go/' . base64_encode(urlencode($link)).'.htm" target="_blank" title="'.$title.'">'.$title.'</a></strong><br /><small><em>'.$n3u_lang['Posted_on'].$date.'</em></small></p>';
							}else{
								$return .= '<p><strong><a class="link" href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode($link)).'" target="_blank" title="'.$title.'">'.$title.'</a></strong><br /><small><em>'.$n3u_lang['Posted_on'].$date.'</em></small></p>';
							}
						}
					}
					echo $return;
				}else{ // http status code was not 200, Most likely 404...
					echo '<span class="error">'.$n3u_lang['Feed_Cannot_Connect'].$feedurl.'.</span>';
				}
			}
		}
	}
	/**
		n3u_FetchItem() is used to fetch individual item information.
	**/
	function n3u_FetchItem($endpoint='USA'){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $prosperentApi;
		global $n3u_results;
		$prosperentApi = new Prosperent_Api(array(
		//	'accessKey'				=> $n3u_configVars['accessKey'],
			'api_key'				=> $n3u_configVars['api_key'],
			'cacheBackend'			=> $n3u_configVars['cacheBackend'],
			'cacheOptions' 			=> array(
				'cache_dir'			=> $n3u_configVars['cache_dir'],
				'caching'			=> $n3u_configVars['caching'],
				'cache_id_prefix'	=> 'i'
			),
			'debugMode'				=> $n3u_configVars['debug'],
			'enableCoupons'			=> $n3u_configVars['enableCoupons'],
		//	'enableFullData'		=> TRUE,
			'enableFacets'			=> $n3u_configVars['enableFacets'],
		//	'enableJsonCompression'	=> $n3u_configVars['enableJsonCompression'],
			'filterCatalogId'		=> $n3u_inputVars['item'],
		//	'filterProductId'		=> $n3u_inputVars['compare'],
			'imageSize'				=> $n3u_configVars['img_size'],
			'location'				=> $n3u_configVars['location'],
			'logging'				=> $n3u_configVars['logging'],
			'query'					=> $n3u_inputVars['q'],
			'referrer'				=> $n3u_configVars['referrer'],
			'sid'					=> $n3u_configVars['sid'],
			'userAgent'				=> $n3u_configVars['userAgent'],
			'visitor_ip'			=> $n3u_configVars['visitor_ip']
		));
		try{
			if($endpoint == 'CA'){ // Fetch products for Canada
				@$prosperentApi->fetchCaProducts(); 
			}elseif($endpoint == 'UK'){ // Fetch products for United Kingdom
				@$prosperentApi->fetchUkProducts();
			}else{ // defaults to USA, keeps compatible
				$prosperentApi->fetchProducts(); // Fetch search data, Notices are suppressed with @
			}
			$n3u_results = @$prosperentApi->getData('all');
		}catch(Exception $prosperentApi){
			die('Caught exception: '.  $prosperentApi->getMessage());
		}
		return $n3u_results;
	}
	/**
		n3u_FetchMerchants() retreieves all the Merchant facet data.
	**/
	function n3u_FetchMerchants($val = NULL){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $prosperentApi;
		@$prosperentApi = new Prosperent_Api(array(
			'imageSize'				=> '60x30',
			'limit'					=> '100',
			'page'					=> $n3u_inputVars['p'],
		));
		if(!$prosperentApi){die($n3u_lang['Prosperent_NoConnect']);} // Check for connection error
		return @$prosperentApi->fetchMerchants($val); // Fetch search data, Notices are suppressed with @
	}
	/**
		n3u_FetchSearch() is used to fetch all search information.
	**/
	function n3u_FetchSearch($endpoint='USA'){
		global $n3u_configVars;
		global $n3u_inputVars;
		global $n3u_lang;
		global $prosperentApi;
		global $n3u_extendedSort;
		global $n3u_results;
		global $n3u_brands;
		global $n3u_merchants;
		@$prosperentApi = new Prosperent_Api(array(
		//	'accessKey'				=> $n3u_configVars['accessKey'],
			'api_key'				=> $n3u_configVars['api_key'],
			'cacheBackend'			=> $n3u_configVars['cacheBackend'],
		//	'cacheBackend'			=> 'Memcache',
			'cacheOptions' 			=> array(
				'cache_dir'			=> $n3u_configVars['cache_dir'],
				'caching'			=> $n3u_configVars['caching'],
				'cache_id_prefix'	=> 's_',
				'lifetime'			=> $n3u_configVars['lifetime'],
			//	'servers'			=> array(
				//	array(
					//	'host'				=> $n3u_configVars['cache_host'], // '127.0.0.1',
					//	'persistent'		=> $n3u_configVars['cache_persistent'], // TRUE,
					//	'port'				=> $n3u_configVars['cache_port'], // '11211',
					//	'retry_interval'	=> $n3u_configVars['cache_retry_interval'], // 15,
					//	'status'			=> $n3u_configVars['cache_status'], // TRUE
					//	'timeout'			=> $n3u_configVars['cache_timeout'], // 5,
					//	'weight'			=> $n3u_configVars['cache_weight'], // 1,
				//	),
			//	),
			),
		//	'categoryID'			=> $n3u_configVars['channel_id'],
		//	'channel_id'			=> $n3u_configVars['channel_id'],
			'debugMode'				=> $n3u_configVars['debug'],
			'enableCoupons'			=> $n3u_configVars['enableCoupons'],
			'enableJsonCompression'	=> $n3u_configVars['enableJsonCompression'],
			'enableFacets'			=> $n3u_configVars['enableFacets'],
			'enableFullData'		=> TRUE,
			'enableQuerySuggestion' => $n3u_configVars['enableQuerySuggestion'],
			'extendedQuery'			=> '@keyword '.$n3u_inputVars['q'] . ' !('.$n3u_configVars['CategoryFilters'].')',
		//	'extendedSortMode'		=> '@relevance DESC, price '.$n3u_inputVars['sort'].', @id '.$n3u_inputVars['sort'],
			'extendedSortMode'		=> $n3u_extendedSort,
			'filterBrand'			=> $n3u_inputVars['b'],
			'filterCatalogId'		=> $n3u_inputVars['item'],
			'filterMerchant'		=> $n3u_inputVars['m'],
		//	'filterProductId'		=> $n3u_inputVars['compare'],
			'imageSize'				=> $n3u_configVars['img_size'],
			'limit'					=> $n3u_configVars['limit'],
			'location'				=> $n3u_configVars['location'],
			'logging'				=> $n3u_configVars['logging'],
		//	'maxPrice'				=> $n3u_inputVars['price_max'],
		//	'maxPriceSale'			=> $n3u_inputVars['price_max'],
		//	'minPrice'				=> $n3u_inputVars['price_min'],
		//	'minPriceSale'			=> $n3u_inputVars['price_min'],
			'page'					=> $n3u_inputVars['p'],
		//	'query'					=> $n3u_inputVars['q'],
			'referrer'				=> $n3u_configVars['referrer'],
			'sid'					=> $n3u_configVars['sid'],
			'sortPrice'				=> $n3u_inputVars['sort'],
		//	'sortPriceSale'			=> $n3u_inputVars['sort'],
			'userAgent'				=> $n3u_configVars['userAgent'],
			'visitor_ip'			=> $n3u_configVars['visitor_ip']
		));
		try{
			if(!$prosperentApi){die($n3u_lang['Prosperent_NoConnect']);} // Check for connection error
			if($endpoint == 'CA'){ // Fetch products for Canada
				@$prosperentApi->fetchCaProducts(); 
			}elseif($endpoint == 'UK'){ // Fetch products for United Kingdom
				@$prosperentApi->fetchUkProducts();
			}else{ // defaults to USA, keeps compatible
				@$prosperentApi->fetchProducts(); // Fetch search data, Notices are suppressed with @
			}
			$n3u_results = @$prosperentApi->getData('all');
		}catch(Exception $prosperentApi){
			die('Caught exception: '.  $prosperentApi->getMessage());
		}
		return $n3u_results;
	}
	function n3u_GPL_Credits(){
		$GPL_Credits = "\t\t" . 'n3u Niche Store - Custom Niche PHP Script' . PHP_EOL
		. "\t\t" . 'Copyright (C) 2012-2014 n3u.com' . PHP_EOL . PHP_EOL
		. "\t\t" . 'This program is free software: you can redistribute it and/or modify' . PHP_EOL
		. "\t\t" . 'it under the terms of the GNU General Public License as published by' . PHP_EOL
		. "\t\t" . 'the Free Software Foundation, either version 3 of the License, or' . PHP_EOL
		. "\t\t" . '(at your option) any later version.' . PHP_EOL . PHP_EOL
		. "\t\t" . 'This program is distributed in the hope that it will be useful,' . PHP_EOL
		. "\t\t" . 'but WITHOUT ANY WARRANTY; without even the implied warranty of' . PHP_EOL
		. "\t\t" . 'MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the' . PHP_EOL
		. "\t\t" . 'GNU General Public License for more details.' . PHP_EOL . PHP_EOL
		. "\t\t" . 'You should have received a copy of the GNU General Public License' . PHP_EOL
		. "\t\t" . 'along with this program. If not, see <http://www.gnu.org/licenses/>' . PHP_EOL . PHP_EOL;
		return $GPL_Credits;
	}
	function n3u_HTTP_Host(){
		global $n3u_ServerVars;
		$url = parse_url($n3u_ServerVars['HTTP_HOST']);
		if(!isset($url['host']) || $url['host'] == NULL){
			if(isset($url['path']) && $url['path'] != NULL){ // Check to see if host is classified as path
				$url['host'] = $url['path'];
			}elseif(isset($n3u_ServerVars['HTTP_ZONE_NAME']) && $n3u_ServerVars['HTTP_ZONE_NAME'] != NULL){ // Use HTTP_ZONE_NAME
				$url['host'] = $n3u_ServerVars['HTTP_ZONE_NAME'];
			}else{ // Use SiteURL
				$url['host'] = strtolower(str_replace('http://','',$n3u_configVars['SiteURL']));
			}
		}
		return $url;
	}
	/**
		n3u_IdCleaner() is used to clean data in id tags
	**/
	function n3u_IdCleaner($string){
		// used on $row['keyword'] in ids
		$id_entities = array('%','?','+', '%21', '%22', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$id_replacements = array('','','_', '', '*', "'", "(", ")", ";", ":", "@", "&amp;", "=", '+', "$", ",", "/", "?", "%", "#", "[", "]");
		return str_replace($id_entities, $id_replacements, urlencode($string));
	}
	/**
		n3u_Input() assigns all input varibles into $n3u_inputVars array.
	**/
	function n3u_Input(){ 
		global $n3u_inputVars;
		$n3u_inputArgs = array(
			'b' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'm' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'p' => FILTER_SANITIZE_NUMBER_INT,
			'page' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'q' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'x' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'clearcache' => array('filter' => FILTER_VALIDATE_BOOLEAN),
			'clearimgs' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
		//	'compare' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'error' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'item' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
			'lang' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
		//	'url' => FILTER_SANITIZE_URL,
			'url' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH),
		//	'price_min' => array('filter' => FILTER_SANITIZE_NUMBER_FLOAT,'flags' => FILTER_FLAG_ALLOW_FRACTION),
		//	'price_min' => array('filter' => FILTER_UNSAFE_RAW,'flags' => FILTER_FLAG_STRIP_HIGH),
		//	'price_max' => array('filter' => FILTER_SANITIZE_NUMBER_FLOAT,'flags' => FILTER_FLAG_ALLOW_FRACTION),
			'sort' => array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_HIGH)
		);
		$n3u_inputVars = filter_input_array(INPUT_GET, $n3u_inputArgs);
		$n3u_inputVars['b'] = str_replace('&#44;','',@$n3u_inputVars['b']); // strip ,
		$n3u_inputVars['q'] = str_replace('&#39;','',@$n3u_inputVars['q']); // strip '
		$n3u_inputVars['page'] = preg_replace(array("'\s+'","/[^a-z0-9._\-]/i"),'',strtolower(@$n3u_inputVars['page'])); // Force lowercase
		$n3u_inputVars['x'] = strtolower(@$n3u_inputVars['x']); // Force lowercase
	//	$n3u_inputVars = array_filter($n3u_inputVars);
	//	var_dump($n3u_inputVars);
	}
	function n3u_Messages($msg_limit = NULL){ // Returns array of Messages
		global $n3u_configVars;
		$n3u_Messages = array();
		$i = 1;
		// preprint_r($n3u_configVars['msg_dir']);
		$n3u_MessageFiles = glob($n3u_configVars['msg_dir'] . "msg_*.php");
		usort($n3u_MessageFiles, function($a, $b){
			return filemtime($a) < filemtime($b);
		});
		foreach($n3u_MessageFiles as $n3u_message){
		//	preprint_r($n3u_message);
			if($msg_limit == NULL){ // limits pages returned if specified
				$n3u_Messages[$i]['id'] = str_replace($n3u_configVars['msg_dir'] . 'msg_','',str_replace('.php','',$n3u_message));
				$n3u_Messages[$i]['date'] = date("F d Y H:i:s", filemtime($n3u_message));
				$n3u_Messages[$i]['url'] = $n3u_message;
				$i++;
			}elseif($msg_limit >= $i){
				$n3u_Messages[$i]['id'] = str_replace($n3u_configVars['msg_dir'] . 'msg_','',str_replace('.php','',$n3u_message));
				$n3u_Messages[$i]['date'] = date("F d Y H:i:s", filemtime($n3u_message));
				$n3u_Messages[$i]['url'] = $n3u_message;
				$i++;
			}
		} // finds all messages in inc directory, builds array
	//	preprint_r($n3u_Messages);
		unset($msg_limit,$n3u_message,$i);
		return $n3u_Messages;
	}
	/**
		n3u_Pagination() is used to paginate search results.
	**/
	function n3u_Pagination(){ // Pagination
		global $prosperentApi;
		global $n3u_inputVars;
		global $n3u_configVars;
		global $n3u_lang;
		$n3u_totalItems = @$prosperentApi->gettotalRecordsFound();  // get total records
		if($n3u_totalItems > 1000){$n3u_totalItems = 1000;} // force stop at 1000 results (current prosperent limit)
		$n3u_totalPageCount = ($n3u_totalItems / $n3u_configVars['limit']);  // figure out pages needed
		$n3u_totalPages = ceil($n3u_totalPageCount); // round above up a page to ensure total pages is correct for data
		$n3u_prevPage = $n3u_inputVars['p'] -1; // set Previous page as current page number minus 1
		$n3u_nextPage = $n3u_inputVars['p'] +1; // set Next Page as current page number plus 1
		$n3u_resultStart = ($n3u_configVars['limit'] * $n3u_prevPage + 1); // Figure out first result being displayed for current page
		$n3u_resultEnd = ($n3u_configVars['limit'] * $n3u_inputVars['p']); // Figure out last result being displayed
		$n3u_pageZone = 3; // Set the desired page first/last gap here.
		if($n3u_resultEnd > $n3u_totalItems){$n3u_resultEnd = $n3u_totalItems;} // if $n3u_resultEnd is more than $n3u_totalItems, show $n3u_resultEnd as $n3u_totalRecords
		if($n3u_inputVars['p'] > $n3u_totalPages){echo "\t\t\t\t\t" . $n3u_lang['No_Results'] . PHP_EOL;} // n3u_Error(404);exit;
		echo "\t\t\t\t\t" . '<div class="Pagination">' . PHP_EOL;				
		$i=1;
		if($n3u_inputVars['p'] <= $n3u_totalPages){ // Display statistics
			echo "\t\t\t\t\t\t" . '<span>Displaying <span class="number">' . $n3u_resultStart .'</span>-<span class="number">'. $n3u_resultEnd . '</span> of <span class="number">' . $n3u_totalItems . '</span> total results found. Displaying page <span class="number">' . $n3u_inputVars['p'] . '</span> of <span class="number">' . $n3u_totalPages . '</span>.</span><br />' . PHP_EOL;
		}
		if($n3u_configVars['CleanUrls'] == TRUE){
			if(($n3u_prevPage > $n3u_pageZone) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // Display First Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-' . $n3u_inputVars['sort'] . '-' . '1.htm" rel="prefetch">First</a>&nbsp;' . PHP_EOL;
			}
			if(($n3u_inputVars['p'] > 1) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // Display Previous Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-' . $n3u_inputVars['sort'] . '-' . $n3u_prevPage . '.htm" rel="prev">Previous</a>&nbsp;' . PHP_EOL;
				if($n3u_prevPage > $n3u_pageZone){echo "\t\t\t\t\t\t" . ' ... ' . PHP_EOL;}
			}
			while(($i <= $n3u_totalPages) && ($n3u_totalPages > 1) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // while less than total pages AND total pages is greater than 1 AND  current page is equal to or less than total pages
				if($i == $n3u_inputVars['p']){echo "\t\t\t\t\t\t" . '<span class="current">' . $i . '</span>&nbsp;' . PHP_EOL;} // if current page display no link
				if($i < ($n3u_nextPage + $n3u_pageZone) && ($i > $n3u_prevPage - $n3u_pageZone) && $i !=$n3u_inputVars['p']){
					echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-' . $n3u_inputVars['sort'] . '-' . $i . '.htm" rel="prefetch">' . $i . '</a>&nbsp;' . PHP_EOL;
				}
				$i++;
			}
			if(($n3u_inputVars['p'] >= 1) && ($n3u_inputVars['p'] < $n3u_totalPages)){ // Display Next Link
				if($n3u_totalPages - $n3u_pageZone > $n3u_inputVars['p']){echo "\t\t\t\t\t\t\t" . ' ... ' . PHP_EOL;}
				echo "\t\t\t\t\t\t" . '<a href="' . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-' . $n3u_inputVars['sort'] . '-' . $n3u_nextPage . '.htm" class="next" rel="next">Next</a>&nbsp;' . PHP_EOL;
			}
			if(($n3u_nextPage < $n3u_totalPages) && ($n3u_totalPages > (1 + $n3u_pageZone))){ // Display Last Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_inputVars['x'] . '_' . $n3u_inputVars['m'] . '_' . $n3u_inputVars['b'] . '_' . urlencode($n3u_inputVars['q']) . '-' . $n3u_inputVars['sort'] . '-' . $n3u_totalPages . '.htm" rel="prefetch">Last</a>' . PHP_EOL;
			}
		}else{
			if(($n3u_prevPage > $n3u_pageZone) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // Display First Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;b=' . $n3u_inputVars['b'] . '&amp;m=' . $n3u_inputVars['m'] . '&amp;p=1&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=' . $n3u_inputVars['sort'] . '" rel="prefetch">First</a>&nbsp;' . PHP_EOL;
			}
			if(($n3u_inputVars['p'] > 1) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // Display Previous Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;b=' . $n3u_inputVars['b'] . '&amp;m=' . $n3u_inputVars['m'] . '&amp;p=' . $n3u_prevPage . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=' . $n3u_inputVars['sort'] . '" rel="prev">Previous</a>&nbsp;' . PHP_EOL;
				if($n3u_prevPage > $n3u_pageZone){echo "\t\t\t\t\t\t" . ' ... ' . PHP_EOL;}
			}
			while(($i <= $n3u_totalPages) && ($n3u_totalPages > 1) && ($n3u_inputVars['p'] <= $n3u_totalPages)){ // while less than total pages AND total pages is greater than 1 AND  current page is equal to or less than total pages
				if($i == $n3u_inputVars['p']){echo "\t\t\t\t\t\t" . '<span class="current">' . $i . '</span>&nbsp;' . PHP_EOL;} // if current page display no link
				if($i < ($n3u_nextPage + $n3u_pageZone) && ($i > $n3u_prevPage - $n3u_pageZone) && $i !=$n3u_inputVars['p']){
					echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;b=' . $n3u_inputVars['b'] . '&amp;m=' . $n3u_inputVars['m'] . '&amp;p=' . $i . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=' . $n3u_inputVars['sort'] . '" rel="prefetch">' . $i . '</a>&nbsp;' . PHP_EOL;
				}
				$i++;
			}
			if(($n3u_inputVars['p'] >= 1) && ($n3u_inputVars['p'] < $n3u_totalPages)){ // Display Next Link
				if($n3u_totalPages - $n3u_pageZone > $n3u_inputVars['p']){echo "\t\t\t\t\t\t" . ' ... ' . PHP_EOL; }
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;b=' . $n3u_inputVars['b'] . '&amp;m=' . $n3u_inputVars['m'] . '&amp;p=' . $n3u_nextPage . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=' . $n3u_inputVars['sort'] . '" class="next" rel="next">Next</a>&nbsp;' . PHP_EOL;
			}
			if(($n3u_nextPage < $n3u_totalPages) && ($n3u_totalPages > (1 + $n3u_pageZone))){ // Display Last Link
				echo "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_configVars['self'] . '?x=' . $n3u_inputVars['x'] . '&amp;b=' . $n3u_inputVars['b'] . '&amp;m=' . $n3u_inputVars['m'] . '&amp;p=' . $n3u_totalPages . '&amp;q=' . urlencode($n3u_inputVars['q']) . '&amp;sort=' . $n3u_inputVars['sort'] . '" rel="prefetch">Last</a>' . PHP_EOL;
			}
		}
		echo "\t\t\t\t\t" . '</div>' . PHP_EOL; // div Pagination
		unset($n3u_totalItems,$n3u_totalPageCount,$n3u_totalPages,$n3u_prevPage,$n3u_nextPage,$n3u_resultStart,$n3u_resultEnd,$n3u_pageZone,$i);
	}
	/**
		n3u_return() may be redundant and needs to be checked.

	function n3u_return($val = null){
		global $prosperentApi;
		return @$prosperentApi->getAllData($val); // get data
	}	**/
	/**
		n3u_swap() is redundant and behaves as preg_replace, will be phased out.
	
	function n3u_swap($pattern,$replacement,$string){
		$string = preg_replace($pattern, $replacement, $string);
		return $string;
	}**/
	/**
		n3u_ReturnPriceSymbol() is used to return correct Price Symbol. Used 
		with $n3u_result['currency'] to detect Price symbol that should be displayed.
		Used on Search & Item Pages.
	**/
	function n3u_ReturnPriceSymbol($currency='USD'){
		if($currency == 'GBP'){$n3u_price_symbol = '&pound;';}else{$n3u_price_symbol = '$';}
		return $n3u_price_symbol;
	}
	/**
		n3u_TitleCleaner() is used to clean data in title tags
	**/
	function n3u_TitleCleaner($string){ // used on $row['keyword'] in titles
		$title_entities = array('+', '%21', '%22','%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$title_replacements = array(' ', '!', 'in', '*', '', '(', ')', ';', ':', '@', '&amp;', '=', ' ', '$', ',', '/', '?', '%', '#', '[', ']');
		return str_replace($title_entities, $title_replacements, urlencode($string));
	}
	/**
		n3u_UrlEncode()
	**/
	function n3u_UrlEncode($string){ // used on urls
		$url_entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$url_replacements = array('!', '*', '\'', '(', ')', ';', ':', '@', '&amp;', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']');
		return str_replace($url_entities, $url_replacements, urlencode($string));
	}
	/**
		n3u_VersionChecker() does a domain text record lookup on n3u.com and 
		finds whatever is listed as the current version and compares this
		against the active script version.
	**/
	function n3u_VersionChecker(){
		global $n3u_configVars;
		if($n3u_configVars['caching'] == FALSE){
			$txt_records = dns_get_record('n3u.com', DNS_TXT); // get all txt records
			foreach($txt_records as $txt_record => $txt_values){ // Parse records
				$txt_string = strstr($txt_values['txt'],'=', TRUE); // Set anything before the equal sign as the string to search
				if($txt_string == 'nns-version'){ // Was the string returned nns-version?
					return(str_replace('nns-version=','',$txt_values['txt'])); // Version only is returned
				}
			}
		}else{
			$n3u_cacheFilePath = $n3u_configVars['cache_dir'] . 'version.txt'; // Set cache file name
			if(file_exists($n3u_cacheFilePath) && time() - $n3u_configVars['lifetime'] < filemtime($n3u_cacheFilePath)){ // else if a recent cache file exist
				// Already exist and is recent so do nothing.
				return file_get_contents($n3u_cacheFilePath);
			}else{
				// Not recent or does not exist
				ob_start();
				$txt_records = dns_get_record('n3u.com', DNS_TXT); // get all txt records
				foreach($txt_records as $txt_record => $txt_values){ // Parse records
					$txt_string = strstr($txt_values['txt'],'=', TRUE); // Set anything before the equal sign as the string to search
					if($txt_string == 'nns-version'){ // Was the string returned nns-version?
						echo str_replace('nns-version=','',$txt_values['txt']);
					}
				}
				$n3u_cacheFileDump = ob_get_contents(); // Dump contents as var
				ob_end_clean(); // Stop & flush buffer
				$n3u_cacheFileName = fopen($n3u_cacheFilePath, 'w'); // open as writeable
				fwrite($n3u_cacheFileName, $n3u_cacheFileDump); // Write the file from var
				fclose($n3u_cacheFileName); // Close file
				unset($n3u_cacheFileName,$txt_records);
				return $n3u_cacheFileDump;
			}
		}
	}
	/**
		n3u_WriteConfig() is used to write the current settings of 
		$n3u_configVars to a domain-based configuration file.
	**/
	function n3u_WriteConfig($domainConfig = NULL){
		global $n3u_configArgs;
		global $n3u_configVars;
		$n3u_configVars = filter_var_array($n3u_configVars, $n3u_configArgs);
		$url = n3u_HTTP_Host();
		if(!isset($domainConfig) || $domainConfig == NULL){
			$domainConfig = $url['host'].'_config.php';
		}
		$configFile = '<?php ' . PHP_EOL
		. "\t" . '/**' . PHP_EOL
		. n3u_GPL_Credits()
		. "\t\t" . 'n3u Niche Store - '.$url['host'].'_config.php' . PHP_EOL
		. "\t\t\t" . 'To customize n3u Niche Store, a little configuration is required.' . PHP_EOL
		. "\t\t\t" . 'It\'s best to configure n3u Niche Store from the Admin Panel' . PHP_EOL
		. "\t\t\t" . 'However, Each option below is usually self explanatory.' . PHP_EOL
		. "\t\t\t" . 'Don\'t use a \' character in any option unless you know how to escape properly.' . PHP_EOL
		. "\t\t\t" . 'Again it\'s best to use Admin Panel which is safer.' . PHP_EOL
		. "\t" . '**/' . PHP_EOL
		. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL
		. "\t" . '$n3u_configData = array( // Visit the admin section at ?x=admin to change settings.' . PHP_EOL
		. "\t\t" . '\'accessKey\' => \'' . $n3u_configVars['accessKey'] . '\',' . PHP_EOL
		. "\t\t" . '\'api_key\' => \'' . $n3u_configVars['api_key'] . '\',' . PHP_EOL
		. "\t\t" . '\'blocks_dir\' => \'' . str_replace('//','/',$n3u_configVars['blocks_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'cache_dir\' => \'' . str_replace('//','/',$n3u_configVars['cache_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'cacheImgs\' => \'' . ($n3u_configVars['cacheImgs'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'caching\' => \'' . ($n3u_configVars['caching'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'Categories\' => \'' . $n3u_configVars['Categories'] . '\',' . PHP_EOL
		. "\t\t" . '\'CategoryFilters\' => \'' . str_replace(array(' , ',', ',',',' | ','| ',' '),array('|','|','|','|','|','%20'),$n3u_configVars['CategoryFilters']) . '\',' . PHP_EOL
		. "\t\t" . '\'CleanUrls\' => \'' . ($n3u_configVars['CleanUrls'] ? 'true' : 'false') . '\','  . PHP_EOL
		. "\t\t" . '\'ClearCacheFreq\' => \'' . $n3u_configVars['ClearCacheFreq'] . '\',' . PHP_EOL
		. "\t\t" . '\'ClearImgCacheFreq\' => \'' . $n3u_configVars['ClearImgCacheFreq'] . '\',' . PHP_EOL
		. "\t\t" . '\'commissionDateMonths\' => \'' . $n3u_configVars['commissionDateMonths'] . '\','  . PHP_EOL
		. "\t\t" . '\'debug\' => \'' . ($n3u_configVars['debug'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'defaultKeyword\' => \'' . $n3u_configVars['defaultKeyword'] . '\',' . PHP_EOL
		. "\t\t" . '\'defaultLanguage\' => \'' . $n3u_configVars['defaultLanguage'] . '\',' . PHP_EOL
		. "\t\t" . '\'enableCoupons\' => \'' . ($n3u_configVars['enableCoupons'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'enableFacets\' => \'' . ($n3u_configVars['enableFacets'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'enableJsonCompression\' => \'' . ($n3u_configVars['enableJsonCompression'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'enableQuerySuggestion\' => \'' . ($n3u_configVars['enableQuerySuggestion'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'img_dir\' => \'' . str_replace('//','/',$n3u_configVars['img_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'img_size\' => \'' . $n3u_configVars['img_size'] . '\',' . PHP_EOL
		. "\t\t" . '\'include_dir\' => \'' . str_replace('//','/',$n3u_configVars['include_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'jScroll\' => \'' . ($n3u_configVars['jScroll'] ? 'true' : 'false') . '\',' . PHP_EOL
		. "\t\t" . '\'language_dir\' => \'' . str_replace('//','/',$n3u_configVars['language_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'lifetime\' => \'' . $n3u_configVars['lifetime'] . '\','  . PHP_EOL
		. "\t\t" . '\'logging\' => \'' . $n3u_configVars['logging'] . '\',' . PHP_EOL
		. "\t\t" . '\'limit\' => \'' . $n3u_configVars['limit'] . '\',' . PHP_EOL
		. "\t\t" . '\'msg_dir\' => \'' . str_replace('//','/',$n3u_configVars['msg_dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'password\' => \'' . $n3u_configVars['password'] . '\',' . PHP_EOL
		. "\t\t" . '\'Prosperent_Endpoint\' => \'' . $n3u_configVars['Prosperent_Endpoint'] . '\',' . PHP_EOL
		. "\t\t" . '\'Prosperent_UserID\' => \'' . $n3u_configVars['Prosperent_UserID'] . '\',' . PHP_EOL
		. "\t\t" . '\'reCaptcha_privKey\' => \'' . $n3u_configVars['reCaptcha_privKey'] . '\',' . PHP_EOL
		. "\t\t" . '\'reCaptcha_pubKey\' => \'' . $n3u_configVars['reCaptcha_pubKey'] . '\',' . PHP_EOL
		. "\t\t" . '\'SiteEmail\' => \'' . $n3u_configVars['SiteEmail'] . '\',' . PHP_EOL
		. "\t\t" . '\'SiteIndex\' => \'' . $n3u_configVars['SiteIndex'] . '\',' . PHP_EOL
		. "\t\t" . '\'SiteName\' => \'' . $n3u_configVars['SiteName'] . '\',' . PHP_EOL
		. "\t\t" . '\'SiteURL\' => \'' . $n3u_configVars['SiteURL'] . '\',' . PHP_EOL
		. "\t\t" . '\'Supporter\' => \'' . $n3u_configVars['Supporter'] . '\',' . PHP_EOL
		. "\t\t" . '\'Template_Dir\' => \'' . str_replace('//','/',$n3u_configVars['Template_Dir']) . '\',' . PHP_EOL
		. "\t\t" . '\'Template_Name\' => \'' . $n3u_configVars['Template_Name'] . '\',' . PHP_EOL
		. "\t\t" . '\'username\' => \'' . $n3u_configVars['username'] . '\',' . PHP_EOL
		. "\t" . '); // n3u Niche Store is brought to you by n3u.com' . PHP_EOL
		. '?>';
		$fp = fopen($domainConfig, "w");
		fwrite($fp, $configFile);
		fclose($fp);
		unset($configFile,$url);
	}
	/**
		n3u_WriteLanguage() is used to write language changes to a custom
		language file.
	**/
	function n3u_WriteLanguage($n3u_lang_changes = NULL,$resetlang = FALSE){
		global $n3u_inputVars;
		global $n3u_configVars;
		if(isset($n3u_lang_changes) && $n3u_lang_changes != NULL){
			$langFile = '<?php ' . PHP_EOL
			. "\t" . '/**' . PHP_EOL
			. n3u_GPL_Credits()
			. "\t\t" . 'Custom Language File' . PHP_EOL
			. "\t\t\t" . 'When you edit a language value in the admin panel, it\'s written to' . PHP_EOL
			. "\t\t\t" . 'a custom file. This retains your changes as n3u Niche Store is updated.' . PHP_EOL
			. "\t\t\t" . 'Don\'t use a \' character in any option unless you know how to escape properly.' . PHP_EOL
			. "\t\t\t" . 'Again it\'s best to use Admin Panel which is safer.' . PHP_EOL
			. "\t" . '**/' . PHP_EOL
			. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL
			. "\t" . '$n3u_lang_custom = array( // Visit the admin section at ?x=admin&page=language to change settings.' . PHP_EOL;
			foreach($n3u_lang_changes as $n3u_lang_change_key => $n3u_lang_change_value){
				if($n3u_lang_change_key != 'lang'){
					$langFile .= "\t\t" . '\'' . $n3u_lang_change_key . '\' => \'' . addslashes($n3u_lang_change_value) . '\',' . PHP_EOL;
				}
			}
			$langFile .= "\t" . '); // n3u Niche Store is brought to you by n3u.com' . PHP_EOL
			. '?>';
			$fp = fopen($n3u_configVars['language_dir'] . $n3u_inputVars['lang'] . '/' . $n3u_inputVars['lang'] . '_custom.php', "w");
			fwrite($fp, $langFile);
			fclose($fp);
			unset($langFile);
		}elseif(isset($resetlang) && $resetlang == TRUE){
			unlink($n3u_configVars['language_dir'] . $n3u_inputVars['lang'] . '/' . $n3u_inputVars['lang'] . '_custom.php');
			unset($resetlang);
		}
	}
	function n3u_WriteMessage($name = NULL, $email = NULL, $subject = NULL,$description = NULL){
		global $n3u_inputVars;
		global $n3u_configVars;
		$langFile = '<?php ' . PHP_EOL
		. "\t" . '/**' . PHP_EOL
		. n3u_GPL_Credits()
		. "\t\t" . 'Message' . PHP_EOL
		. "\t\t\t" . 'When a visitor attempts to contact you, this message is created' . PHP_EOL
		. "\t\t\t" . 'so that you can view it later from the Administration Panel.' . PHP_EOL
		. "\t\t\t" . 'Should you wish to delete it, you may do so there or simply by' . PHP_EOL
		. "\t\t\t" . 'deleting this file directly. The message details are encrypted' . PHP_EOL
		. "\t\t\t" . 'with base64 encoding and are automatically decoded from the' . PHP_EOL
		. "\t\t\t" . 'Administration Panel.' . PHP_EOL
		. "\t" . '**/' . PHP_EOL
		. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL
		. "\t" . '$n3u_Messages = array( // Visit the admin section at ?x=admin&page=messages to read messages.' . PHP_EOL
		. "\t\t" . '\'Name\' => \'' . addslashes(base64_encode($name)) . '\',' . PHP_EOL
		. "\t\t" . '\'Email\' => \'' . addslashes(base64_encode($email)) . '\',' . PHP_EOL
		. "\t\t" . '\'Subject\' => \'' . addslashes($subject) . '\',' . PHP_EOL
		. "\t\t" . '\'Description\' => \'' . addslashes(base64_encode($description)) . '\',' . PHP_EOL
		. "\t\t" . '\'SiteURL\' => \'' . addslashes($n3u_configVars['SiteURL']) . '\',' . PHP_EOL
		. "\t\t" . '\'VisitorIP\' => \'' . $n3u_configVars['visitor_ip'] . '\',' . PHP_EOL;
		$langFile .= "\t" . '); // n3u Niche Store is brought to you by n3u.com' . PHP_EOL
		. '?>';
		$fp = fopen($n3u_configVars['msg_dir'] . 'msg_' .md5($name.$email.$description). '.php', "w");
		fwrite($fp, $langFile);
		fclose($fp);
		unset($langFile);
	}
	/**
		n3u_WriteCustomPage() is used to create blank custom pages.
	**/
	function n3u_WriteCustomPage($customPage_name = NULL,$admin_only = FALSE){
		global $n3u_inputVars;
		global $n3u_configVars;
		if(isset($customPage_name) && $customPage_name != NULL){
			$url = n3u_HTTP_Host();
			$customPage = '<?php ' . PHP_EOL
			. "\t" . '/**' . PHP_EOL
			. n3u_GPL_Credits()
			. "\t\t" . 'Custom Page' . PHP_EOL
			. "\t\t\t" . 'Custom pages are loaded within the content div. This means they' . PHP_EOL
			. "\t\t\t" . 'share the header, sides and footer with other pages. Simply edit' . PHP_EOL
			. "\t\t\t" . 'this page template to customise and build your page. You have' . PHP_EOL
			. "\t\t\t" . 'access to many functions found in n3u.php as well as the' . PHP_EOL
			. "\t\t\t" . '$n3u_configVars $n3u_inputVars and $n3u_lang arrays. You may' . PHP_EOL
			. "\t\t\t" . 'enable debugging to see the data provided by those arrays.' . PHP_EOL
			. "\t\t\t" . '' . PHP_EOL
			. "\t" . '**/' . PHP_EOL
			. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL;
			if(isset($admin_only) && $admin_only == TRUE){
				$customPage .= "\t" . 'if(!defined(\'admin\')){ // if not admin, forbidden error' . PHP_EOL
				. "\t\t" . 'n3u_Error(403,\'Not logged into admin.\'); // returns forbidden error and headers with formatting, plus gives a custom message' . PHP_EOL
				. "\t" . '}else{ // if admin, do below . PHP_EOL;' . PHP_EOL
				. "\t\t" . 'echo "\t\t\t\t" . \'<div id="\' . n3u_IdCleaner($customPage_name) . \'">\' . PHP_EOL' . PHP_EOL
				. "\t\t" . '. "\t\t\t\t\t" . \'<h3>\' . $customPage_name . \'</h3>\' . PHP_EOL' . PHP_EOL
				. "\t\t" . '. "\t\t\t\t\t" . \'<hr />\' . PHP_EOL' . PHP_EOL
				. "\t\t" . '. "\t\t\t\t\t" . \'<p>Your custom content would begin here.</p>\' . PHP_EOL' . PHP_EOL
				. "\t\t" . '. "\t\t\t\t" . \'</div>\' . PHP_EOL; // div' . PHP_EOL
				. "\t" . '} . PHP_EOL' . PHP_EOL
				. "\t" . '// n3u Niche Store is brought to you by n3u.com' . PHP_EOL
				. '?>' . PHP_EOL;
			}else{
				$customPage .= "\t" . 'echo "\t\t\t\t" . \'<div id="' . n3u_IdCleaner($customPage_name) . '">\' . PHP_EOL' . PHP_EOL
				. "\t" . '. "\t\t\t\t\t" . \'<h3>' . $customPage_name . '</h3>\' . PHP_EOL' . PHP_EOL
				. "\t" . '. "\t\t\t\t\t" . \'<hr />\' . PHP_EOL' . PHP_EOL
				. "\t" . '. "\t\t\t\t\t" . \'<p>Your custom content would begin here.</p>\' . PHP_EOL' . PHP_EOL
				. "\t" . '. "\t\t\t\t" . \'</div>\' . PHP_EOL; // div' . PHP_EOL
				. "\t" . '// n3u Niche Store is brought to you by n3u.com' . PHP_EOL
				. '?>' . PHP_EOL;
			}
			$fp = fopen($n3u_configVars['include_dir'] . 'custom/'.$url['host'].'_' . $customPage_name . '.php', "w");
			fwrite($fp, $customPage);
			fclose($fp);
			unset($langFile,$url);
		}
	}
	/**
		preprint_r($val) simply wraps the results of print_r() in <pre> tags.
	**/
	function preprint_r($val){
		echo '<pre>' . PHP_EOL;
		print_r($val) . PHP_EOL;
		echo '</pre>' . PHP_EOL;
	}
	if(is_file($n3u_configVars['include_dir'] . 'custom_functions.php')){
		require_once($n3u_configVars['include_dir'] . 'custom_functions.php'); // Get user-defined functions
	}else{
		$CustomFile =
		'<?php ' . PHP_EOL
		. "\t" . '/**' . PHP_EOL
		. n3u_GPL_Credits()
		. "\t\t" . 'NOTES:' . PHP_EOL
		. "\t\t\t" . 'Custom functions file' . PHP_EOL
		. "\t\t\t\t" . 'Keep functions seperate to reduce upgrade headaches' . PHP_EOL
		. "\t\t\t\t" . 'Block-based functions should be stored within corresponding block files' . PHP_EOL . PHP_EOL
		. "\t\t\t" . 'You may interact with the following arrays by calling them globally:' . PHP_EOL
		. "\t\t\t\t" . '$n3u_configVars - Stores all configuration related options' . PHP_EOL
		. "\t\t\t\t" . '$n3u_inputVars - Stores all form input related options' . PHP_EOL
		. "\t\t\t\t" . '$n3u_lang - Stores all language related options' . PHP_EOL
		. "\t\t\t\t" . '$n3u_results - Stores all result information (search page)' . PHP_EOL
		. "\t" . '**/' . PHP_EOL
		. "\t" . 'if(!defined(\'n3u\')){die(\'Direct access is not permitted.\');} // Is n3u defined?' . PHP_EOL
		. "\t" . '// Enter any custom functions you have below the line.' . PHP_EOL . PHP_EOL
		. '?>';
		$fp = fopen($n3u_configVars['include_dir'] . 'custom_functions.php', "w");
		fwrite($fp, $CustomFile);
		fclose($fp);
		unset($CustomFile);
	}

?>