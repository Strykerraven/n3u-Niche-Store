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
	switch($n3u_inputVars['x']){
		case 'admin': // Is admin the requested page?
			switch(defined('admin')){ // Is admin logged in?
				case TRUE: // If Admin is logged in, do this
					$url = n3u_HTTP_Host();
					if(!file_exists($n3u_configVars['include_dir'] . 'configs/' . $url['host'] . '_config_' . date('y.m.d') .  '.php')){
						if(file_exists($url['host'] . '_config.php')){
							if(!copy($url['host'] . '_config.php', $n3u_configVars['include_dir'] . 'configs/' . $url['host'] . '_config_' . date('y.m.d') . '.php')){
								echo "\t\t\t\t" . $n3u_lang['Backup_Failed'] . PHP_EOL;
							}
						}
					} // Auto creates backup of hostname_config.php when admin is logged in, but only if config exist.
					$all_config_files = array();
					$old_config_files = array();
					$recent_config_files = array();
					foreach(glob($n3u_configVars['include_dir'] . 'configs/' . $url['host'] . "_config_*.*.*.php") as $config_filename){
						$all_config_files[] = $config_filename;
					} // finds all config files in include directory, builds array
					arsort($all_config_files); // reverses sorting based on filename (date)
					$i=0;
					foreach($all_config_files as $config_filename){
						if ($i < 3){
							$recent_config_files[] = $config_filename; // Builds array of recent config files
						}else{
							$old_config_files[] = $config_filename; // Builds array of old config files
						}
						$i++;
					}
					foreach($old_config_files as $config_filename){unlink($config_filename);} // Deletes old config files
					$n3u_customPages = n3u_CustomPages(); // Get array of custom pages
					switch($n3u_inputVars['page']){
						case 'backup':
							// Cache uploaded config
							if(isset($_FILES['RestoreConfig']) && $_FILES['RestoreConfig'] != NULL){
								$uploadfile = $url['host'] . '_config.php';
								if(!move_uploaded_file($_FILES['RestoreConfig']['tmp_name'], $uploadfile)){
									echo "\t\t\t\t" . $n3u_lang['Restore_Failed'] . PHP_EOL;
								}
							}
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" enctype="multipart/form-data" method="POST" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=backup">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Backup'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Backup_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
							if($n3u_configVars['CleanUrls'] == TRUE){
								echo "\t\t\t\t\t\t\t" . '<li class="DownloadLink">'.$n3u_lang['Download'].'&nbsp;<a href="' . str_replace('index.php','',$n3u_configVars['self']) . 'download/'. urlencode(base64_encode($url['host'] . '_config.php')) .'" title="'.n3u_TitleCleaner($n3u_lang['Download_Config']).'">'.$n3u_lang['Download_Config'].'</a></li>' . PHP_EOL;
								foreach($recent_config_files as $file){
									echo "\t\t\t\t\t\t\t" . '<li class="DownloadLink">'.$n3u_lang['Download'].'&nbsp;<a href="' . str_replace('index.php','',$n3u_configVars['self']) . 'download/'. urlencode(base64_encode($file)) .'" title="'.n3u_TitleCleaner(basename($file)).'">'.basename($file).'</a></li>' . PHP_EOL;
								}
							}else{
								echo "\t\t\t\t\t\t\t" . '<li class="DownloadLink">'.$n3u_lang['Download'].'&nbsp;<a href="' . $n3u_configVars['self'] . '?x=download&amp;url='. urlencode(base64_encode($url['host'] . '_config.php')) .'" title="'.n3u_TitleCleaner($n3u_lang['Download_Config']).'">'.$n3u_lang['Download_Config'].'</a></li>' . PHP_EOL;
								foreach($recent_config_files as $file){
									echo "\t\t\t\t\t\t\t" . '<li class="DownloadLink">'.$n3u_lang['Download'].'&nbsp;<a href="' . $n3u_configVars['self'] . '?x=download&amp;url='. urlencode(base64_encode($file)) .'" title="'.n3u_TitleCleaner(basename($file)).'">'.basename($file).'</a></li>' . PHP_EOL;
								}
							}
							echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Restore'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Restore_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input type="hidden" name="MAX_FILE_SIZE" value="30720" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="file" name="RestoreConfig" id="RestoreConfig" value="">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Upload Config">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'blocks':
							// Block Management
							// Admins can move blocks either via selection.
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=blocks">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['Blocks_Manage'].'</label>' . PHP_EOL
							. "\t\t\t\t\t\t" .'<span class="explain">'.$n3u_lang['Blocks_Manage_Explain'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t" .'<span style="display:block;margin:auto;text-align:center;width:80%;">' . PHP_EOL
							. "\t\t\t\t\t\t\t" .'<span style="font-weight:bolder;" class="DashboardRow">' . PHP_EOL
							. "\t\t\t\t\t\t\t\t" .'<span style="display:inline-block;margin:auto;width:20%;">'.$n3u_lang['Order'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t\t\t" .'<span style="display:inline-block;margin:auto;width:45%;">'.$n3u_lang['Name'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t\t\t" .'<span style="display:inline-block;margin:auto;width:30%;">'.$n3u_lang['Position'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t\t" .'</span>' . PHP_EOL;
						//	$n3u_BlockList = n3u_Block('*',TRUE);
						//	ksort($n3u_BlockList);
							$n3u_BlockList = n3u_BlockConfig();
						//	n3u_WriteBlockConfig();
							foreach($n3u_BlockList as $n3u_BlockListName => $n3u_BlockListArray){
								echo "\t\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" .'<span style="display:inline-block;margin:auto;width:20%;">' . PHP_EOL
								. "\t\t\t\t\t\t\t\t\t" .'<select name="' . $n3u_BlockListName . '_order" onchange="this.form.submit()">' . PHP_EOL;
								if($n3u_BlockListArray['SortOrder'] == '0'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="0">0</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="0">0</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '1'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="1">1</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="1">1</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '2'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="2">2</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="2">2</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '3'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="3">3</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="3">3</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '4'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="4">4</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="4">4</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '5'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="5">5</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="5">5</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '6'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="6">6</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="6">6</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '7'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="7">7</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="7">7</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '8'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="8">8</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="8">8</option>' . PHP_EOL;}
								if($n3u_BlockListArray['SortOrder'] == '9'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="9">9</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="9">9</option>' . PHP_EOL;}
								echo "\t\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<span style="display:inline-block;margin:auto;width:45%;">' . $n3u_BlockListName . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<span style="display:inline-block;margin:auto;width:30%;">' . PHP_EOL
								. "\t\t\t\t\t\t\t\t\t" . '<select name="' . $n3u_BlockListName . '_position" onchange="this.form.submit()">' . PHP_EOL;
								if($n3u_BlockListArray['Position'] == 'Disabled'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="#disabled">'.$n3u_lang['Disabled'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="#disabled">'.$n3u_lang['Disabled'].'</option>' . PHP_EOL;}
								if($n3u_BlockListArray['Position'] == 'header'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="header">'.$n3u_lang['Header'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="header">'.$n3u_lang['Header'].'</option>' . PHP_EOL;}
								if($n3u_BlockListArray['Position'] == 'left'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="left">'.$n3u_lang['Left'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="left">'.$n3u_lang['Left'].'</option>' . PHP_EOL;}
								if($n3u_BlockListArray['Position'] == 'right'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="right">'.$n3u_lang['Right'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="right">'.$n3u_lang['Right'].'</option>' . PHP_EOL;}
								if($n3u_BlockListArray['Position'] == 'footer'){echo "\t\t\t\t\t\t\t\t\t\t\t" . '<option selected value="footer">'.$n3u_lang['Footer'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t\t\t" . '<option value="footer">'.$n3u_lang['Footer'].'</option>' . PHP_EOL;}
								echo "\t\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							}
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars)){
						//		preprint_r($n3u_blockData);
						//		preprint_r($n3u_PostVars);
								foreach($n3u_BlockList as $n3u_BlockListName => $n3u_BlockListArray){
									if(isset($n3u_PostVars[$n3u_BlockListName . '_order']) && $n3u_PostVars[$n3u_BlockListName . '_order'] != $n3u_BlockListArray['SortOrder'] && $n3u_BlockListArray['SortOrder'] != NULL){
										$n3u_blockData[$n3u_BlockListName]['SortOrder'] = $n3u_PostVars[$n3u_BlockListName . '_order'];
									}elseif(isset($n3u_PostVars[$n3u_BlockListName . '_order']) && $n3u_PostVars[$n3u_BlockListName . '_order'] != $n3u_BlockListArray['SortOrder'] && $n3u_BlockListArray['SortOrder'] == NULL){
										$n3u_blockData[$n3u_BlockListName]['SortOrder'] = '3';
									}
									if(isset($n3u_PostVars[$n3u_BlockListName . '_position']) && $n3u_PostVars[$n3u_BlockListName . '_position'] != $n3u_BlockListArray['Position'] && $n3u_BlockListArray['Position'] != NULL){
										$n3u_blockData[$n3u_BlockListName]['Position'] = $n3u_PostVars[$n3u_BlockListName . '_position'];
									}
								}
								n3u_WriteBlockConfig();
							}
							echo "\t\t\t\t\t\t" . '</span>' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'cache':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
								n3u_WriteConfig();
							}
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=cache">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="caching">' . $n3u_lang['Enable_Caching'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Enable_Caching_Explain'] . '</span>' . PHP_EOL;
							if($n3u_configVars['caching'] == TRUE){
								echo "\t\t\t\t\t\t" . '<input checked id="caching" name="caching" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input name="caching" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t" . '<input name="caching" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input checked id="caching" name="caching" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}
							if($n3u_configVars['caching'] == TRUE){
								$i = 0;
								$i_max = 500;
								echo "\t\t\t\t\t\t" . '<label for="ClearCacheFreq">' . $n3u_lang['ClearCache_Files_Freq'] . '</label>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<select id="ClearCacheFreq" name="ClearCacheFreq">' . PHP_EOL;
								while ($i++ <= $i_max){
									if($i % 50 == 0){
										if($i == $n3u_configVars['ClearCacheFreq']){
											echo "\t\t\t\t\t\t\t" . '<option selected value="'.$i.'">1 out of ' . $i . ' ('. number_format(1 / $i * 100,2) .'%) page loads</option>' . PHP_EOL;
										}else{
											echo "\t\t\t\t\t\t\t" . '<option value="'.$i.'">1 out of ' . $i . ' ('. number_format(1 / $i * 100,2) .'%) page loads</option>' . PHP_EOL;
										}
									}
								}
								echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<label for="cacheImgs">' . $n3u_lang['Enable_Caching_Imgs'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Enable_Caching_Imgs_Explain'] . '</span>' . PHP_EOL;
							if($n3u_configVars['cacheImgs'] == TRUE){
								echo "\t\t\t\t\t\t" . '<input checked id="cacheImgs" name="cacheImgs" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input name="cacheImgs" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t" . '<input name="cacheImgs" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input checked id="cacheImgs" name="cacheImgs" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}
							if($n3u_configVars['cacheImgs'] == TRUE){
								$i = 0;
								$i_max = 500;
								echo "\t\t\t\t\t\t" . '<label for="ClearImgCacheFreq">' . $n3u_lang['ClearCache_Imgs_Freq'] . '</label>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<select id="ClearImgCacheFreq" name="ClearImgCacheFreq">' . PHP_EOL;
								while ($i++ <= $i_max){
									if($i % 50 == 0){
										if($i == $n3u_configVars['ClearImgCacheFreq']){
											echo "\t\t\t\t\t\t\t" . '<option selected value="'.$i.'">1 out of ' . $i . ' page loads</option>' . PHP_EOL;
										}else{
											echo "\t\t\t\t\t\t\t" . '<option value="'.$i.'">1 out of ' . $i . ' page loads</option>' . PHP_EOL;
										}
									}
								}
								echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<label for="lifetime">' . $n3u_lang['Lifetime'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Lifetime_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" id="lifetime" name="lifetime" required type="text" value="' . $n3u_configVars['lifetime'] . '">' . PHP_EOL;
//							. "\t\t\t\t\t\t" .'<br /><hr class="hr" />' . PHP_EOL;
//							. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['Disk_Usage'].'</label>' . PHP_EOL
//							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Disk_Usage_Explain'] . '</span>' . PHP_EOL
//							. "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
//							. "\t\t\t\t\t\t\t" . '<span class="RowOption" style="font-weight:bold;text-decoration:underline;">'.$n3u_lang['Option'].'</span>' . PHP_EOL
//							. "\t\t\t\t\t\t\t" . '<span class="RowValue" style="font-weight:bold;text-decoration:underline;">'.$n3u_lang['Size'].'</span>' . PHP_EOL
//							. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
//							$DirectorySizes = array();
//							$DirectoryNames = array(
//								$n3u_lang['Blocks_Directory'],
//								$n3u_lang['Cache_Directory'],
//								$n3u_lang['Include_Directory'],
//								$n3u_lang['Image_Directory'],
//								$n3u_lang['Language_Directory'],
//								$n3u_lang['Template_Directory'],
//								$n3u_lang['Total_Size']
//							);		
//							$DirectoryPaths = array(
//								$n3u_configVars['blocks_dir'],
//								$n3u_configVars['cache_dir'],
//								$n3u_configVars['include_dir'],
//								$n3u_configVars['img_dir'],
//								$n3u_configVars['language_dir'],
//								$n3u_configVars['Template_Dir'],
//								NULL,
//							);
//							$n3u_dirtxt = $n3u_configVars['cache_dir'] .$url['host']. '/'.md5($n3u_configVars['password'].'dirsizes').'.cache';
//							if($n3u_configVars['caching'] == TRUE){
//								if(file_exists($n3u_dirtxt) && (time() - ($n3u_configVars['lifetime'] / 4) < filemtime($n3u_dirtxt))){ // require existing file
//									$DirectorySizes = require_once $n3u_dirtxt;
//								}else{ // make new file
//									foreach($DirectoryPaths as $DirectoryPath){
//										$DirectorySizes[] = n3u_DirSize($DirectoryPath,TRUE);
//									}
//									$i=0;
//									ob_start();
//									foreach($DirectoryNames as $DirectoryName){
//										echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
//										. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$DirectoryName.'</span>' . PHP_EOL
//										. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.$DirectorySizes[$i].'</span>' . PHP_EOL
//										. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
//										$i++;
//									}
//									$n3u_dirSizesDump = ob_get_contents(); // Dump contents as var
//									ob_end_flush(); // Stop & flush buffer
//									$n3u_dirtxtName = fopen($n3u_dirtxt, 'w'); // Open file as writeable
//									fwrite($n3u_dirtxtName,$n3u_dirSizesDump); // Write the file
//									fclose($n3u_dirtxtName); // Close file
//								}
//							}else{ // Process as normal
//								foreach($DirectoryPaths as $DirectoryPath){
//									$DirectorySizes[] = n3u_DirSize($DirectoryPath,TRUE);
//								}
//								$i=0;
//								foreach($DirectoryNames as $DirectoryName){
//									echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
//									. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$DirectoryName.'</span>' . PHP_EOL
//									. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.$DirectorySizes[$i].'</span>' . PHP_EOL
//									. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
//									$i++;
//								}
//							}
//							unset($DirectoryPaths,$DirectoryNames,$DirectorySizes,$i); // Cleanup (no longer needed)
							echo "\t\t\t\t\t\t" . '<label for="ClearCache">' . $n3u_lang['Clear_Cache'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Clear_Cache_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<select id="ClearCache" name="ClearCache">' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<option value="ClearCacheFiles">' . $n3u_lang['ClearCache_Files'] . '</option>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<option value="ClearAllImages">' . $n3u_lang['ClearCache_Imgs_All'] . '</option>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<option selected value="ClearLastAccessedImages">' . $n3u_lang['ClearCache_Imgs_Acc'] . '</option>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<option value="ClearLastModifiedImages">' . $n3u_lang['ClearCache_Imgs_Mod'] . '</option>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</select>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" name="clear" type="submit" value="'.$n3u_lang['ClearCache'].'">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" name="submit" type="submit" value="Update Config">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'dashboard':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
								n3u_WriteConfig();
								n3u_ClearCache(); // empties the file caches
							}
							$n3u_stats = n3u_FetchCommissions();
							if(isset($n3u_configVars['accesskey']) && $n3u_configVars['accesskey'] != NULL){
								$n3u_daterange = explode(',',$n3u_configVars['commissionDateRange']);
								// Recent Sales (within last month)
								$total_earnings = 00.00;
								$sales = '';
								foreach($n3u_stats as $n3u_sale){
									$total_earnings = $total_earnings + $n3u_sale['paymentAmount'];
								}
							}else{
								$total_earnings = $n3u_lang['No_AccessKey'];
							}
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<div id="Dashboard">' . PHP_EOL
							. "\t\t\t\t\t\t" .'<span class="explain">' . $n3u_lang['Dashboard_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" .'<br /><hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['Overview'].'</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<span class="RowOption" style="font-weight:bold;text-decoration:underline;">'.$n3u_lang['Option'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<span class="RowValue" style="font-weight:bold;text-decoration:underline;">'.$n3u_lang['Value'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							$OverviewNames = array(
								$n3u_lang['Your_Version'],
								$n3u_lang['Current_Version'],
								$n3u_lang['Default_Keyword'],
								$n3u_lang['Search_Endpoint'],
								$n3u_lang['Site_Name'],
								$n3u_lang['Site_URL'],
								$n3u_lang['Template'],
								$n3u_lang['Caching'],
								$n3u_lang['CleanUrls'],
								$n3u_lang['Debug_Mode'],
							);
							$OverviewValues = array(
								$n3u_configVars['Version'],
								n3u_VersionChecker(),
								$n3u_configVars['defaultKeyword'],
								$n3u_configVars['Prosperent_Endpoint'],
								$n3u_configVars['SiteName'],
								$n3u_configVars['SiteURL'],
								$n3u_configVars['Template_Name'],
								Boolean2String($n3u_configVars['caching']),
								Boolean2String($n3u_configVars['CleanUrls']),
								Boolean2String($n3u_configVars['debug']),
							);
							$i=0;
							foreach($OverviewNames as $OverviewName){
								echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$OverviewName.'</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.$OverviewValues[$i].'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
								$i++;
							}
							unset($OverviewNames,$OverviewValues,$i); // Cleanup (no longer needed)
							if(phpversion() >= 5.3){
								echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$n3u_lang['Prosperent_Version'].'</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">';echo $prosperentApi::VERSION;echo '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$n3u_lang['Supporter'].'</span>' . PHP_EOL;
							if(is_numeric($n3u_configVars['Supporter']) && $n3u_configVars['Supporter'] != 0){
								echo "\t\t\t\t\t\t\t" . '<span class="RowValue" style="color:green;">1 out of '.$n3u_configVars['Supporter']. ' (' . number_format(1 / $n3u_configVars['Supporter'] * 100,2) .'%)</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t\t" . '<span class="RowValue" style="color:red;">'.$n3u_lang['False'].'</span>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							if(isset($n3u_configVars['accessKey']) && $n3u_configVars['accessKey'] != NULL && $total_earnings > 0){
								echo "\t\t\t\t\t\t" . '<span class="DashboardRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">'.$n3u_lang['Total_Earned_Last'].'</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">$'.$total_earnings.'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" .'<br /><hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>'.$n3u_lang['Recent_News'].'</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<div style="display:block;margin:auto;width:90%;">' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<div style="clear:left;display:block;float:left;margin:auto;width:45%;">' . PHP_EOL
							. "\t\t\t\t\t\t\t\t" . '<h5>'.$n3u_lang['n3u_News'].'</h5>' . PHP_EOL
							. "\t\t\t\t\t\t\t\t";n3u_FetchFeed('http://n3u.com/feed/');echo PHP_EOL
							. "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<div style="clear:right;display:block;float:right;margin:auto;width:45%;">' . PHP_EOL
							. "\t\t\t\t\t\t\t\t" . '<h5>'.$n3u_lang['Prosperent_News'].'</h5>' . PHP_EOL
							. "\t\t\t\t\t\t\t\t";n3u_FetchFeed('http://community.prosperent.com/external.php?type=RSS2&forumids=2');echo PHP_EOL
							. "\t\t\t\t\t\t\t" . '</div>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</div>' . PHP_EOL
							. "\t\t\t\t\t" . '</div>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'language':
							// Language
							// Will load current language values
							// Likely will do so by loading en_us.php and then en_us_custom.php
							// The custom file would contain stock language settings
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=language">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="defaultLanguage">' . $n3u_lang['Default_Language'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Default_Language_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<select id="defaultLanguage" name="defaultLanguage">' . PHP_EOL;
							foreach(glob($n3u_configVars['language_dir'] . "*",GLOB_ONLYDIR) as $folderpath){
								$foldername = str_replace($n3u_configVars['language_dir'],'',$folderpath);
								echo "\t\t\t\t\t\t\t" . '<option value="'.$foldername.'">'.$foldername.'</option>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>'.$n3u_configVars['defaultLanguage'].'</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Language_Edit_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<div class="LngsWrapper">'
							. "\t\t\t\t\t\t\t" . '<span class="LngsLeft">Key</span>'
							. "\t\t\t\t\t\t\t" . '<span class="LngsRight">Value</span>'
							. "\t\t\t\t\t\t" . '</div>' . PHP_EOL;
							ksort($n3u_lang);
							foreach($n3u_lang as $n3u_lang_key => $n3u_lang_string){
								echo "\t\t\t\t\t\t" . '<div class="LngsWrapper">'
								. '<label class="LngsLeft" for="lang_'.n3u_IdCleaner($n3u_lang_key).'">' . $n3u_lang_key .'</label>'
								. '<input class="LngsRight" id="lang_'.n3u_IdCleaner($n3u_lang_key).'" type="text" name="'.n3u_IdCleaner($n3u_lang_key).'" value="'.  filter_var($n3u_lang_string, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH).'">'
								. '</div>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="resetlang" value="Reset all to Defaults">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="lang" value="Update Language">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'main': // Is main the requested subpage?
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL;
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								if((isset($n3u_PostVars["password"]) && $n3u_PostVars["password"] != NULL) || (isset($n3u_PostVars["password_confirm"]) && $n3u_PostVars["password_confirm"] != NULL)){
									if(md5($n3u_PostVars["password"]) == md5($n3u_PostVars["password_confirm"])){
										$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
										n3u_WriteConfig();
									}else{
										echo "\t\t\t\t\t" . '<span class="fail">' . $n3u_lang['Password_Mismatch'] . '</span>' . PHP_EOL;
									}
								}else{
									$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
									n3u_WriteConfig();
								}
								n3u_ClearCache(); // empties the file caches
							}
							echo "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=main">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['Prosperent_Settings'] . '</h4>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="accessKey">' . $n3u_lang['Access_Key'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Access_Key_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="accessKey" id="accessKey" value="' . @$n3u_configVars['accessKey'] . '">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="api_key">' . $n3u_lang['API_Key'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['API_Key_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="api_key" id="api_key" value="' . $n3u_configVars['api_key'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="Prosperent_UserID">' . $n3u_lang['Prosperent_UserID'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Prosperent_UserID_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="Prosperent_UserID" id="Prosperent_UserID" value="' . $n3u_configVars['Prosperent_UserID'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['Niche_Store_Settings'] . '</h4>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="CleanUrls">' . $n3u_lang['CleanUrls'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['CleanUrls_Explain'] . '</span>' . PHP_EOL;
							if($n3u_configVars['CleanUrls'] == TRUE){
								echo "\t\t\t\t\t\t" . '<input checked id="CleanUrls" name="CleanUrls" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input name="CleanUrls" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t" . '<input name="CleanUrls" type="radio" value="TRUE"><span cl type="radio"ass="True">' . $n3u_lang['True'] . '</span><input checked id="CleanUrls" name="CleanUrls" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<label for="Prosperent_Endpoint">' . $n3u_lang['Debug_Mode'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" .'<span class="explain">' . $n3u_lang['Debug_Mode_Explain'] . '</span>' . PHP_EOL;
							if($n3u_configVars['debug'] == TRUE){
								echo "\t\t\t\t\t\t" . '<input checked id="debug" name="debug" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input name="debug" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t" . '<input name="debug" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input checked id="debug" name="debug" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}
							echo "\t\t\t\t\t\t" . '<label for="SiteEmail">' . $n3u_lang['Site_Email_Address'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Site_Email_Address_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="SiteEmail" id="SiteEmail" value="' . @$n3u_configVars['SiteEmail'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="SiteIndex">' . $n3u_lang['Site_Index'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Site_Index_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="SiteIndex" id="SiteIndex" value="' . $n3u_configVars['SiteIndex'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="SiteName">' . $n3u_lang['Site_Name'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Site_Name_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="SiteName" id="SiteName" value="' . $n3u_configVars['SiteName'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="SiteURL">' . $n3u_lang['Site_URL'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Site_URL_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="SiteURL" id="SiteURL" value="' . $n3u_configVars['SiteURL'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="username">' . $n3u_lang['Username'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Username_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="username" id="username" value="' . $n3u_configVars['username'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="password">' . $n3u_lang['Password'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Password_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="password" name="password" id="password" value="' . $n3u_configVars['password'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="password_confirm">' . $n3u_lang['Password_Confirm'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Password_Confirm_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="password" name="password_confirm" id="password_confirm" value="' . $n3u_configVars['password'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['Search_Settings'] . '</h4>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="Prosperent_Endpoint">' . $n3u_lang['Search_Endpoint'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Search_Endpoint_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<select id="Prosperent_Endpoint" name="Prosperent_Endpoint">' . PHP_EOL;
							if($n3u_configVars['Prosperent_Endpoint'] == 'CA'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="CA">'.$n3u_lang['Country_CA'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="CA">'.$n3u_lang['Country_CA'].'</option>' . PHP_EOL;}
							if($n3u_configVars['Prosperent_Endpoint'] == 'UK'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="UK">'.$n3u_lang['Country_UK'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="UK">'.$n3u_lang['Country_UK'].'</option>' . PHP_EOL;}
							if($n3u_configVars['Prosperent_Endpoint'] == 'USA'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="USA">'.$n3u_lang['Country_USA'].'</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="USA">'.$n3u_lang['Country_USA'].'</option>' . PHP_EOL;}
							echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="img_size">' . $n3u_lang['Image_Size'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Image_Size_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<select id="img_size" name="img_size">' . PHP_EOL;
							if($n3u_configVars['img_size'] == '75x75'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="75x75">75x75</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="75x75">75x75</option>' . PHP_EOL;}
							if($n3u_configVars['img_size'] == '125x125'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="125x125">125x125</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="125x125">125x125</option>' . PHP_EOL;}
							if($n3u_configVars['img_size'] == '250x250'){echo "\t\t\t\t\t\t\t\t" . '<option selected value="250x250">250x250</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="250x250">250x250</option>' . PHP_EOL;}
							echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="limit">' . $n3u_lang['Limit'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Limit_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="limit" id="limit" value="' . $n3u_configVars['limit'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="enterdefaultKeyword">' . $n3u_lang['Default_Keyword'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Default_Keyword_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" id="enterdefaultKeyword" name="defaultKeyword" required type="text" value="' . $n3u_configVars['defaultKeyword'] . '">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="enterCategories">' . $n3u_lang['Categories'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Categories_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<textarea id="enterCategories" name="Categories" placeholder="Comma,Separated,like,so" required>' . $n3u_configVars['Categories'] . '</textarea>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="enterCategoryFilters">' . $n3u_lang['CategoryFilters'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['CategoryFilters_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<textarea id="enterCategoryFilters" name="CategoryFilters" placeholder="Comma,Separated,like,so" required>' . $n3u_configVars['CategoryFilters'] . '</textarea>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="jScrollenable">' . $n3u_lang['jScroll'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['jScroll_Explain'] . '</span>' . PHP_EOL;
							if($n3u_configVars['jScroll'] == TRUE){
								echo "\t\t\t\t\t\t\t" . '<input checked id="jScrollenable" name="jScroll" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input name="jScroll" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t\t" . '<input name="jScroll" type="radio" value="TRUE"><span class="True">' . $n3u_lang['True'] . '</span><input checked id="jScrollenable" name="jScroll" type="radio" value="FALSE"><span class="False">' . $n3u_lang['False'] . '</span>' . PHP_EOL;
							}	
							echo "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['reCaptcha_Settings'] . '</h4>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['reCaptcha_Settings_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="reCaptcha_pubKey">' . $n3u_lang['reCaptcha_pubKey'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['reCaptcha_pubKey_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="reCaptcha_pubKey" id="reCaptcha_pubKey" value="' . $n3u_configVars['reCaptcha_pubKey'] . '">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="reCaptcha_privKey">' . $n3u_lang['reCaptcha_privKey'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['reCaptcha_privKey_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="reCaptcha_privKey" id="reCaptcha_privKey" value="' . $n3u_configVars['reCaptcha_privKey'] . '">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['Directory_Settings'] . '</h4>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Directory_Settings_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="blocks_dir">' . $n3u_lang['Blocks_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Blocks_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="blocks_dir" name="blocks_dir" type="text" value="' . $n3u_configVars['blocks_dir'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="cache_dir">' . $n3u_lang['Cache_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Cache_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="cache_dir" name="cache_dir" type="text" value="' . $n3u_configVars['cache_dir'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="img_dir">' . $n3u_lang['Image_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Image_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="img_dir" name="img_dir" type="text" value="' . $n3u_configVars['img_dir']. '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="include_dir">' . $n3u_lang['Include_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Include_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="include_dir" name="include_dir" type="text" value="' . $n3u_configVars['include_dir']. '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="language_dir">' . $n3u_lang['Language_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Language_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="language_dir" name="language_dir" type="text" value="' . $n3u_configVars['language_dir']. '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="Template_Dir">' . $n3u_lang['Template_Directory'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Template_Directory_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="SiteURL">' . rtrim($n3u_configVars['SiteURL'],'/') . str_replace('index.php','',$n3u_configVars['self']) . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" class="SitePath" id="Template_Dir" name="Template_Dir" type="text" value="' . $n3u_configVars['Template_Dir'] . '" required>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update Config">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin		
						break;
						case 'messages':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['DeleteMessage']) && $n3u_PostVars['DeleteMessage'] != NULL){
								// Detect message deleted
								$message_id = $n3u_PostVars['MessageID'];
								if(isset($message_id) && $message_id != NULL){
									if(unlink($n3u_configVars['include_dir'] . 'messages/msg_' . $message_id . '.php')){
										$n3u_MessageStatus = $n3u_configVars['include_dir'] . 'messages/msg_' . $message_id . '.php has been successfully deleted.'; // Write the pubid to txt file
									}else{
										$n3u_MessageStatus = 'Unable to delete file ' . $n3u_configVars['include_dir'] . 'messages/msg_' . $message_id . '.php from server. File already removed or permission error.';
									}
								}
							}
							$temp_url = base64_decode(filter_input(INPUT_GET,'url',FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH));
							if(isset($temp_url) && $temp_url != NULL){
								if(file_exists($temp_url)){
									require_once $temp_url;
								}else{
									n3u_Error(404);
								}
								if(is_array($n3u_Messages)){
									$n3u_Messages['Name'] = filter_var(base64_decode($n3u_Messages['Name']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); //Sanitize
									$n3u_Messages['Email'] = filter_var(base64_decode($n3u_Messages['Email']), FILTER_SANITIZE_EMAIL); //Sanitize
									$n3u_Messages['Subject'] = filter_var($n3u_Messages['Subject'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); //Sanitize
									$n3u_Messages['Description'] = filter_var(base64_decode($n3u_Messages['Description']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); //Sanitize
									if(!filter_var($n3u_Messages['Email'], FILTER_VALIDATE_EMAIL)){$n3u_Messages['Email'] = 'Email was not valid.';} // Verify Email
									echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
									. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
									. "\t\t\t\t\t" . '<hr />' . PHP_EOL
									. "\t\t\t\t\t" . '<p class="explain">Below is a message that was submitted through your sites contact form. This message has passed through php\'s Sanitize filters. Should you wish to reply, Click the email address to open in your operating systems default email client and much of the information will be auto-filled.</p>' . PHP_EOL
									. "\t\t\t\t\t" . '<h1>Message From: ' . $n3u_Messages['Name'] . '</h1>' . PHP_EOL
									. "\t\t\t\t\t" . '<h2>Email Address: <a href="mailto:'.filter_var($n3u_Messages['Email'],FILTER_SANITIZE_ENCODED).'?bcc='.filter_var($n3u_configVars['SiteEmail'],FILTER_SANITIZE_ENCODED).'&amp;subject=RE%3A%20'.$n3u_Messages['Subject'].'&amp;body=%0D%0DRE%3A%0A'.filter_var($n3u_Messages['Description'],FILTER_SANITIZE_ENCODED).'" target="_blank" title="Send email via external client">'.$n3u_Messages['Email'] . '</a></h2>' . PHP_EOL
									. "\t\t\t\t\t" . '<h4>Sender IP Address: '.$n3u_Messages['VisitorIP'].'</h4>' . PHP_EOL
									. "\t\t\t\t\t" . '<h4>Originated From: <a href="'.$n3u_Messages['SiteURL'].'" target="_blank" title="Click to view website">'.$n3u_Messages['SiteURL'].'</a></h4>' . PHP_EOL
									. "\t\t\t\t\t" . '<h4>Subject: ' . $n3u_Messages['Subject'] . '</h4>' . PHP_EOL
									. "\t\t\t\t\t" . '<h5>Description:</h5><p>' . $n3u_Messages['Description'] . '</p>' . PHP_EOL
									. "\t\t\t\t\t" . '<form id="admin_form_tools" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=messages">' . PHP_EOL
									. "\t\t\t\t\t\t" . '<input type="hidden" name="MessageID" value="'.str_replace(array($n3u_configVars['include_dir'] . 'messages/msg_','.php'),'',$temp_url).'">' . PHP_EOL
									. "\t\t\t\t\t\t" . '<input class="Button" type="button" onclick="window.history.back();" value="Go Back">' . PHP_EOL
									. "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="DeleteMessage" value="Delete Message">' . PHP_EOL
									. "\t\t\t\t\t" . '</form>' . PHP_EOL
									. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
								}else{
									echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
									. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
									. "\t\t\t\t\t" . '<hr />' . PHP_EOL
									. "\t\t\t\t\t" . '<p class="fail" style="text-align:center;">'.$n3u_MessageStatus.' Could not retrieve message data. Please try viewing the message directly via a file manager or ftp.</p>' . PHP_EOL
									. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
								}
							}else{
								// Messages (From Contact Form)
								echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
								. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
								. "\t\t\t\t\t" . '<hr />' . PHP_EOL
								. "\t\t\t\t\t" . '<p class="explain">This section displays all messages submitted via the contact form.</p>' . PHP_EOL;
								$n3u_Messages = n3u_Messages();
						//		preprint_r($n3u_Messages);
								if(isset($n3u_Messages) && $n3u_Messages != NULL){
									echo "\t\t\t\t\t" . '<ul class="MessagesWrapper">' . PHP_EOL
									. "\t\t\t\t\t\t" . '<li class="MessagesLeft"><h4>Message ID</h4></li>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<li class="MessagesRight"><h4>Sent On</h4></li>' . PHP_EOL
									. "\t\t\t\t\t" . '</ul>' . PHP_EOL;
									foreach($n3u_Messages as $n3u_Message){
										echo "\t\t\t\t\t" . '<ul class="MessagesWrapper">' . PHP_EOL;
										if($n3u_configVars['CleanUrls'] == TRUE){
											echo "\t\t\t\t\t\t" . '<li class="MessagesLeft"><a href="admin_messages_'.base64_encode($n3u_Message['url']).'.htm" title="Read Message">' . $n3u_Message['id'] . '</a></li>' . PHP_EOL;
										}else{
											echo "\t\t\t\t\t\t" . '<li class="MessagesLeft"><a href="index.php?x=admin&amp;page=messages&amp;url='.base64_encode($n3u_Message['url']).'" title="Read Message">' . $n3u_Message['id'] . '</a></li>' . PHP_EOL;
										}
										echo "\t\t\t\t\t\t" . '<li class="MessagesRight">' . $n3u_Message['date'] . '</li>' . PHP_EOL
										. "\t\t\t\t\t" . '</ul>' . PHP_EOL;
									}
									if(isset($n3u_MessageStatus) && $n3u_MessageStatus != NULL){
										echo "\t\t\t\t\t" . '<p class="success" style="text-align:center;">'.$n3u_MessageStatus.'</span>' . PHP_EOL;
									}
								}else{
									echo "\t\t\t\t\t" . '<p class="fail" style="text-align:center;">No Messages were found.</span>' . PHP_EOL;
								}

								echo "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
							}
						break;
						case 'pages':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								// Detect created pages
								if(isset($n3u_PostVars['custom_create_pagename']) && $n3u_PostVars['custom_create_pagename'] != NULL){
									n3u_WriteCustomPage($n3u_PostVars['custom_create_pagename']); // Write the pubid to txt file
								}
								// Detect deleted pages
								if(isset($n3u_PostVars['custom_remove_pagename']) && $n3u_PostVars['custom_remove_pagename'] != NULL){	
									if(!unlink($n3u_configVars['include_dir'] . '/custom/'.$url['host'] . '_' . $n3u_PostVars['custom_pagename_'.str_replace('.php','',$n3u_PostVars['custom_remove_pagename'])] . '.php')){
										echo 'Failed to delete page '.$n3u_PostVars['custom_remove_pagename'].'.php.';
									}
								}
							}
							// Custom Pages
							// create bare pages and view existing pages
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form action="' . $n3u_configVars['self'] . '?x=admin&amp;page=pages" id="admin_form" method="post">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="custom_pagename">' . $n3u_lang['Page_Create'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Page_Create_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" id="custom_pagename" name="custom_create_pagename" type="text" value="">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Pages_Rename'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Pages_Rename_Explain'] . '</span>' . PHP_EOL;
							foreach($n3u_customPages as $n3u_custom_page){
								if(isset($n3u_PostVars['submit'])){
									// Detect renamed pages
									if($n3u_PostVars['custom_pagename_'.$n3u_custom_page['name']] != $n3u_PostVars['custom_renamed_pagename_'.$n3u_custom_page['name']]){
										if(!copy($n3u_configVars['include_dir'] .  'custom/'.$url['host'] . '_' . $n3u_PostVars['custom_pagename_'.$n3u_custom_page['name']] . '.php', $n3u_configVars['include_dir'] . '/custom/'.$url['host'] . '_' . $n3u_PostVars['custom_renamed_pagename_'.$n3u_custom_page['name']] . '.php')){
											echo $n3u_lang['Page_Rename_Failed'];
										}else{ // page copied correctly so it's safe to delete
											if(!unlink($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_PostVars['custom_pagename_'.$n3u_custom_page['name']] . '.php')){
												echo $n3u_lang['Page_Delete_Failed'] . $n3u_PostVars['custom_pagename_'.$n3u_custom_page['name']]. '.php.';
											}
										}
									}
								}
								// preprint_r($n3u_custom_page);
								echo "\t\t\t\t\t\t" . '<span style="display:block;margin:auto;text-align:center;width:75%;">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<input id="custom_pagename_'.n3u_idCleaner($n3u_custom_page['name']).'" name="custom_pagename_'.n3u_idCleaner($n3u_custom_page['name']).'" type="hidden" value="'.$n3u_custom_page['name'].'">' . PHP_EOL;
								if($n3u_configVars['CleanUrls'] == TRUE){
									echo "\t\t\t\t\t\t\t" . 'Rename <a href="./page_' . $n3u_custom_page['name'] . '.htm" target="_blank" title="'.  n3u_TitleCleaner($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_custom_page['name'] . '.php').'">' . $n3u_custom_page['name'] . '.php</a> to ' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t\t" . 'Rename <a href="' . $n3u_configVars['self'] . '?x=page&amp;page='.$n3u_custom_page['name'].'" target="_blank" title="'.  n3u_TitleCleaner($n3u_configVars['include_dir'] . 'custom/'.$url['host'] . '_' . $n3u_custom_page['name'] . '.php').'">' . $n3u_custom_page['name'] . '.php</a> to ' . PHP_EOL;
								}
								echo "\t\t\t\t\t\t\t" . '<input autocomplete="on" id="custom_renamed_pagename_'.n3u_idCleaner($n3u_custom_page['name']).'" name="custom_renamed_pagename_'.n3u_idCleaner($n3u_custom_page['name']).'" style="display:inline-block;text-align:right;width:20%;" type="text" value="'.$n3u_custom_page['name'].'">.php' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
								
							}
							echo "\t\t\t\t\t\t" . '<label for="custom_remove_pagename">' . $n3u_lang['Page_Delete'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Page_Delete_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input autocomplete="on" id="custom_remove_pagename" name="custom_remove_pagename" type="text" value="">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" name="submit" type="submit" value="Update">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'stats':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['month'])){
								$n3u_configVars['commissionDateMonths'] = $n3u_PostVars['month'];
								$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
								n3u_WriteConfig();
							}
							// Various detailed statistics
							// Some easy things to display may include Prosperent sales data, tracking of go clicks... etc
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=stats">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Stats'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Stats_Explain'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<span class="RowOption" style="font-weight:bold;text-decoration:underline;">' . $n3u_lang['Option'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<span class="RowValue" style="font-weight:bold;text-decoration:underline;">' . $n3u_lang['Value'] . '</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</span>' . PHP_EOL;
							if(isset($n3u_configVars['accessKey']) && $n3u_configVars['accessKey'] != NULL){
								$n3u_stats = n3u_FetchCommissions();
								$n3u_daterange = explode(',',$n3u_configVars['commissionDateRange']);
								$total_earnings && $total_referral_earnings && $pending_total_earnings && $pending_total_referral_earnings = 00.00;
								$total_referrals && $total_sold && $pending_total_referrals && $pending_total_sold = 0;
								$sales = array();
								$referral_sales = array();
								$pending_sales = array();
								$pending_referral_sales = array();
								foreach($n3u_stats as $n3u_sale){
							//		preprint_r($n3u_sale);
									if($n3u_sale['currentTransactionStatus'] == 'closed'){
										if($n3u_sale['commissionType'] == 'mine'){
											if($n3u_sale['paymentAmount'] > 0){
												$total_earnings = $total_earnings + $n3u_sale['paymentAmount'];
												if($n3u_configVars['CleanUrls'] == TRUE){
													$GoLink = 'go/'.base64_encode(urlencode('http://'.$n3u_sale['httpHost'] . $n3u_sale['requestUri'])).'.htm';
												}else{
													$GoLink = $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode('http://'.$n3u_sale['httpHost'] . $n3u_sale['requestUri']));
												}
												$sales[$n3u_sale['commissionDate']]= "\t\t\t\t\t\t\t" . '<li>Sold <a href="'. $GoLink . '">'. $n3u_sale['keyword'] . '</a> and earned $' . $n3u_sale['paymentAmount'] . ' on '.$n3u_sale['commissionDate'].'</li>' . PHP_EOL;
												$total_sold++;
											}
										}elseif($n3u_sale['commissionType'] == 'referral'){
											if($n3u_sale['paymentAmount'] > 0){
												$total_referral_earnings = $total_referral_earnings + $n3u_sale['paymentAmount'];
												$referral_sales[$n3u_sale['commissionDate']]= "\t\t\t\t\t\t\t" . '<li>A referral commission occured and earned you $' . $n3u_sale['paymentAmount'] . ' on '.$n3u_sale['commissionDate'].'</li>' . PHP_EOL;
												$total_referrals++;
											}
										}
									}elseif(in_array($n3u_sale['currentTransactionStatus'],array('adjusted','extended','locked','new'))){
										if($n3u_sale['commissionType'] == 'mine'){
											if($n3u_sale['paymentAmount'] > 0){
												if($n3u_configVars['CleanUrls'] == TRUE){
													$GoLink = 'go/'.base64_encode(urlencode('http://'.$n3u_sale['httpHost'] . $n3u_sale['requestUri'])).'.htm';
												}else{
													$GoLink = $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode('http://'.$n3u_sale['httpHost'] . $n3u_sale['requestUri']));
												}
												$pending_total_earnings = $pending_total_earnings + $n3u_sale['paymentAmount'];
												$pending_sales[$n3u_sale['commissionDate']]= "\t\t\t\t\t\t\t" . '<li>A sale for <a href="'. $GoLink . '">'. $n3u_sale['keyword'] . '</a> that earned $' . $n3u_sale['paymentAmount'] . ' on '.$n3u_sale['commissionDate'].' is pending.</li>' . PHP_EOL;
												$pending_total_sold++;
											}
										}elseif($n3u_sale['commissionType'] == 'referral'){
											if($n3u_sale['paymentAmount'] > 0){
												$pending_total_referral_earnings = $pending_total_referral_earnings + $n3u_sale['paymentAmount'];
												$pending_referral_sales[$n3u_sale['commissionDate']]= "\t\t\t\t\t\t\t" . '<li>A referral commission that you earned $' . $n3u_sale['paymentAmount'] . ' for on '.$n3u_sale['commissionDate'].' is pending.</li>' . PHP_EOL;
												$pending_total_referrals++;
											}
										}
									}
								}
								unset($n3u_sale);
								if($sales != NULL){krsort($sales);}
								if($referral_sales != NULL){krsort($referral_sales);}
								echo "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">' . $n3u_lang['Total_Sold'] . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.var_export($total_sold,TRUE).'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">' . $n3u_lang['Total_Earned'] . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'. var_export($total_earnings,TRUE) .'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">Referral Commissions Earned:</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.var_export($total_referral_earnings,TRUE).'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">Transactions Pending:</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.var_export($pending_total_earnings,TRUE).'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<span class="StatsRow">' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowOption">Referral Commissions Pending:</span>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<span class="RowValue">'.var_export($pending_total_referral_earnings,TRUE).'</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<label for="StatsRange">' . $n3u_lang['Stats_Range'] . '</label>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Stats_Range_Explain'] . '</span>' . PHP_EOL
								. "\t\t\t\t\t\t" . '<select id="StatsRange" name="month" onchange="this.form.submit()">' . PHP_EOL;
								$month=1;
								$months_max=12;
								while($month <= $months_max){
									if($n3u_configVars['commissionDateRange'] == date('Ymd', strtotime('-'.$month.' months -1 day')).','.date('Ymd', strtotime('yesterday'))){
										echo "\t\t\t\t\t\t\t" . '<option selected value="'.$month.'">' . $month . ' months ('.n3u_date($n3u_daterange[0],'M jS Y').' to '.n3u_date($n3u_daterange[1],'M jS Y') . ')</option>' . PHP_EOL;
									}else{
										echo "\t\t\t\t\t\t\t" . '<option value="'.$month.'">' . $month . ' months ('.date('M jS Y',strtotime('-'.$month.' month')).' to '.date('M jS Y',strtotime('yesterday')) . ')</option>' . PHP_EOL;
									}
									$month++;
								}
								echo "\t\t\t\t\t\t" . '</select>' . PHP_EOL;
								if($total_earnings > 0){
									echo "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Sold'] . '</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">These are sales from '.n3u_date($n3u_daterange[0],'M jS Y').' to '.n3u_date($n3u_daterange[1],'M jS Y').' that you\'ve earned a commission for. Note that on average it takes around 7 days from the click before merchants report earnings to Prosperent. However, this is dependant on the merchant. Pending transations can be found in the Pending Sales section listed below instead.</span>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
									foreach($sales as $sale){
										echo $sale;
									}
									echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t" . '<label>'. $n3u_lang['Sold'] . ' (' . $n3u_lang['No_Commissions'] . ')</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['No_Commissions_Explain'] . '</span>' . PHP_EOL;
								}
								if($total_referral_earnings > 0){
									echo "\t\t\t\t\t\t" . '<label>Referrals</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">These are referral commissions from '.n3u_date($n3u_daterange[0],'M jS Y').' to '.n3u_date($n3u_daterange[1],'M jS Y').' that you\'ve earned a commission for. Referrals that are still pending can be found under the Pending Referrals section below instead.</span>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
									foreach($referral_sales as $referral_sale){
										echo $referral_sale;
									}
									echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t" . '<label>Referrals (' . $n3u_lang['No_Commissions'] . ')</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['No_Commissions_Explain'] . '</span>' . PHP_EOL;
								}
								if($pending_total_earnings > 0){
									echo "\t\t\t\t\t\t" . '<label>' . $n3u_lang['Recently_Sold'] . ' (Pending)</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">These are recent pending sales from '.n3u_date($n3u_daterange[0],'M jS Y').' to '.n3u_date($n3u_daterange[1],'M jS Y').' that you\'ve earned a commission for. Note that usually it takes around 7 days from the click before merchants report earnings to Prosperent. However, this is dependant on the merchant and as a result, those commissions will show here until they are marked as completed assuming they are eligble.</span>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
									foreach($pending_sales as $pending_sale){
										echo $pending_sale;
									}
									echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t" . '<label>'. $n3u_lang['Pending_Sales'] . ' (' . $n3u_lang['No_Commissions'] . ')</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['No_Commissions_Explain'] . '</span>' . PHP_EOL;
								}
								if($pending_total_referral_earnings > 0){
									echo "\t\t\t\t\t\t" . '<label>'. $n3u_lang['Pending_Referrals'] . '</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">These are recent pending referral commissions from '.n3u_date($n3u_daterange[0],'M jS Y').' to '.n3u_date($n3u_daterange[1],'M jS Y').' that you\'ve earned a commission for. Only commissions that are pending and have not yet been completed will be listed below.</span>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
									foreach($pending_referral_sales as $pending_referral_sale){
										echo $pending_referral_sale;
									}
									echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t" . '<label>'. $n3u_lang['Pending_Referrals'] . ' (' . $n3u_lang['No_Commissions'] . ')</label>' . PHP_EOL
									. "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['No_Commissions_Explain'] . '</span>' . PHP_EOL;
								}
							}else{
								echo "\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['No_AccessKey'] . PHP_EOL;
							}	
							echo "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'stores':
							// Multiple Stores
							$n3u_AdditionalStores = n3u_AdditionalStores();
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<p class="explain">'.$n3u_lang['Stores_Explain'].'</p>' . PHP_EOL
							. "\t\t\t\t\t" . '<ul>' . PHP_EOL;
							if($n3u_configVars['CleanUrls'] == TRUE){
								foreach($n3u_AdditionalStores as $n3u_AdditionalStore){
									echo "\t\t\t\t\t\t" . '<li><a href="http://' . $n3u_AdditionalStore['url'] . '" target="_blank" title="' . n3u_TitleCleaner($n3u_AdditionalStore['name']).'">' . $n3u_AdditionalStore['name'] . '</a> - <a href="http://' . $n3u_AdditionalStore['url'] . '/admin.htm" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Admin_Panel']) . '">' . $n3u_lang['Admin_Panel'] . '</a> (<a href="' . str_replace('index.php','',$n3u_configVars['self']) . 'download/'. urlencode(base64_encode($n3u_AdditionalStore['path'])) .'">'.$n3u_lang['Download'].'</a>)</li>' . PHP_EOL;
								}
							}else{
								foreach($n3u_AdditionalStores as $n3u_AdditionalStore){
									echo "\t\t\t\t\t\t" . '<li><a href="http://' . $n3u_AdditionalStore['url'] . '" target="_blank" title="' . n3u_TitleCleaner($n3u_AdditionalStore['name']).'">' . $n3u_AdditionalStore['name'] . '</a> - <a href="http://' . $n3u_AdditionalStore['url'] . '?x=admin" target="_blank" title="' . n3u_TitleCleaner($n3u_lang['Admin_Panel']) . '">' . $n3u_lang['Admin_Panel'] . '</a> (<a href="' . $n3u_configVars['self'] . '?x=download&amp;url='. urlencode(base64_encode($n3u_AdditionalStore['path'])) .'">'.$n3u_lang['Download'].'</a>)</li>' . PHP_EOL;
								}
							}
							echo "\t\t\t\t\t" . '</ul>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
							unset($n3u_AdditionalStores);
						break;
						case 'support':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
								n3u_WriteConfig();
							}
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
						//	. "\t\t\t\t\t" . '<p style="text-align:center;">Coming Soon... This has yet to be implemented.</p><p style="text-align:center;">The purpose of this page will be to gather ways to support for favorite Niche Store. The default method of API_Key revenue sharing will be expanded to be more configurable. Rather than assuming 1 out of 10 visits (10%), You\'ll be able to configure this directly. ;)</p>' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=support">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="SupporterValue">' . $n3u_lang['Supporter'] . '</label>' . PHP_EOL
							. "\t\t\t\t\t\t" .'<span class="explain">' . $n3u_lang['Supporter_Explain'] . '</span>' . PHP_EOL;
							if(isset($n3u_configVars['Supporter']) && $n3u_configVars['Supporter'] != NULL){
								echo "\t\t\t\t\t\t" . '<span style="display:block;width:40%;margin:auto;"><select id="SupporterValue" name="Supporter">' . PHP_EOL;
								if($n3u_configVars['Supporter'] == '3'){echo "\t\t\t\t\t\t\t" . '<option selected value="3">1 out of 3 ('. number_format(1 / 3 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="3">1 out of 3 ('. number_format(1 / 3 * 100,3) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '4'){echo "\t\t\t\t\t\t\t" . '<option selected value="4">1 out of 4 ('. number_format(1 / 4 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="4">1 out of 4 ('. number_format(1 / 4 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '5'){echo "\t\t\t\t\t\t\t" . '<option selected value="5">1 out of 5 ('. number_format(1 / 5 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="5">1 out of 5 ('. number_format(1 / 5 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '8'){echo "\t\t\t\t\t\t\t" . '<option selected value="8">1 out of 8 ('. number_format(1 / 8 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="8">1 out of 8 ('. number_format(1 / 8 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '10'){echo "\t\t\t\t\t\t\t" . '<option selected value="10">1 out of 10 ('. number_format(1 / 10 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="10">1 out of 10 ('. number_format(1 / 10 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '15'){echo "\t\t\t\t\t\t\t" . '<option selected value="20">1 out of 15 ('. number_format(1 / 15 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="15">1 out of 15 ('. number_format(1 / 15 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '20'){echo "\t\t\t\t\t\t\t" . '<option selected value="20">1 out of 20 ('. number_format(1 / 20 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="20">1 out of 20 ('. number_format(1 / 20 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '25'){echo "\t\t\t\t\t\t\t" . '<option selected value="25">1 out of 25 ('. number_format(1 / 25 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="25">1 out of 25 ('. number_format(1 / 25 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '33'){echo "\t\t\t\t\t\t\t" . '<option selected value="33">1 out of 33 ('. number_format(1 / 33 * 100,2) .'%)</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="33">1 out of 33 ('. number_format(1 / 33 * 100,2) .'%)</option>' . PHP_EOL;}
								if($n3u_configVars['Supporter'] == '0'){echo "\t\t\t\t\t\t\t" . '<option selected value="0">Off :(</option>' . PHP_EOL;}else{echo "\t\t\t\t\t\t\t\t" . '<option value="0">Off :(</option>' . PHP_EOL;}
								echo "\t\t\t\t\t\t" . '</select></span>' . PHP_EOL
								. "\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update">' . PHP_EOL;
							}
							echo "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t\t" . '<div style="display:block;margin:auto;width:100%;text-align:center;"><a href="https://twitter.com/n3ucom" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @n3ucom</a></div>' . PHP_EOL
							. "\t\t\t\t\t" . '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="Paypal_Support" style="margin-top:1em;text-align:center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input type="hidden" name="cmd" value="_s-xclick">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHyQYJKoZIhvcNAQcEoIIHujCCB7YCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB0+wEpiDJaE6TWDJU5O8kvtpD/4MfCeW09GE3H+vPvvDsFPg3hXznGzNVthcOjRZ4cgnc921xwhMhOy9OAzOvqUaPPigGeG2ksQi4LxefFxU/4aib/HKgLOW9Q6N2L6pxtQWCOxrdcr5c0YlbPeqoOhaWuIE/7r1JbgovSwXY20TELMAkGBSsOAwIaBQAwggFFBgkqhkiG9w0BBwEwFAYIKoZIhvcNAwcECC3chW+STscJgIIBIOGXPiMV96CtyLDz+BC/uPVlGDrg43pj9XBlf+41buIDcnXAdQem7bRkaYtYrWsqtS7kEKhD0HUNLpMqqmpZilAwgLFZ4qP9ecD0I2PntTtyKa4ynkvbMS0Sxx+qBP5OY8e/0gyvLMXzKOf9jFRohUQRRINNJwM0aFK4GUJ5vtOQv3+0a+LZm77a/jucxXWUW8DI7v64LZ0N+DaWlZNiuFi6BgT6Dt1YyhjIzJwuZUFjoqNWmk8Bv4eivo9FoUrQLSzHIDEBsYgFC9w5ALHp2pljq6lE4cEB0+rqe5u5RqCVAs+ebHws8Q407TdkymNbrwG7TuPgjAMNPtXRYdPN6ESDguyAWjgzqKtkxy4NxRC6LCjv/o0wRx+BHPMw26N1vKCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTEzMDUwNTEwMDAyNVowIwYJKoZIhvcNAQkEMRYEFFqoSj6uV1/luB7HklKFMGzWrGI1MA0GCSqGSIb3DQEBAQUABIGAoiNO5Abg4ZN8LINncEJtkGpJERTHCANr4WLRoXfQGM0bYXxybEvsTnjPvVOV46SVBMbTiZgm8+IO4BSUuMsbBOtb2n/3p/nhYsWNeXGKvL8DgDsEyHu/vEQP+iYN2hxdWJDYI0WhPDRp+aQyXWzWkSTHTYJ8IFZpfE6v/rHVrcI=-----END PKCS7-----">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input type="image" src="'.$n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/donate.gif" style="border:0;" name="submit" alt="PayPal - The safer, easier way to pay online!">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<img alt="" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" style="border:0;" width="1" >' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						case 'templates':
							$n3u_PostVars = filter_input_array(INPUT_POST);
							if(isset($n3u_PostVars['submit'])){
								$n3u_configVars = array_merge($n3u_configVars,$n3u_PostVars);
								n3u_WriteConfig();
								n3u_ClearCache(); // empties the file caches
							}
							// Template chooser, Gives a more user friendly way to changing style instead of manually specifying path.
							echo "\t\t\t\t" . '<div id="Admin">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . ucfirst($n3u_inputVars['page']) . ' - ' . $n3u_lang['Administration'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<form id="admin_form" method="post" action="' . $n3u_configVars['self'] . '?x=admin&amp;page=templates">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<label for="ChooseTemplate">'.$n3u_lang['Template_Select'].'</label>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Templates_Explain'].'</span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<span style="display:block;width:25%;margin:auto;"><select id="ChooseTemplate" name="Template_Name">' . PHP_EOL;
							foreach(glob($n3u_configVars['Template_Dir'] . '*/',GLOB_ONLYDIR) as $folderpath){ // get template css files
								$foldername = str_replace(array($n3u_configVars['Template_Dir'],'/'),'',$folderpath);
								if(($foldername == $n3u_configVars['Template_Name']) && $foldername != 'admin'){
									echo "\t\t\t\t\t\t\t" . '<option selected value="' . $foldername . '">' . $foldername . '</option>' . PHP_EOL;
								}elseif($foldername != 'admin'){
									echo "\t\t\t\t\t\t\t" . '<option value="' . $foldername . '">' . $foldername . '</option>' . PHP_EOL;
								}
							}
							echo "\t\t\t\t\t\t" . '</select></span>' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update">' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div Admin
						break;
						default:
							n3u_Error(404,'Page <em>' . $n3u_inputVars['page'] . '</em> doesn\'t exist.');
						break;
					} // Switch $n3u_inputVars['page']
					unset($url);
				break;
				default: // Not Admin, Force Login
					session_start();
					session_destroy();
					header('Location: index.php?x=login');
					exit;
				break;
			} // Switch Admin Logged In
		break; // Switch $n3u_inputVars['x'] = 'admin';
		default:
			// Do Nothing.
		break;
	} // Switch $n3u_inputVars['x']

?>