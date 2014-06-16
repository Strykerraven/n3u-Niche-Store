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
	// Figure out which mode we should display.
	if($n3u_position == 'left' || $n3u_position == 'right' || $n3u_position == 'footer'){
		$n3u_block_allowed = 'true';
	}else{
		$n3u_block_allowed = 'false';
	}
	switch($n3u_block_allowed){
		case "true": // If true, Block is allowed so we return data
			switch($n3u_inputVars['x']){ // Page
				case "admin": // If admin page, show admin Categories instead
					echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Categories">' . PHP_EOL
					. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Categories'] . '</h3>' . PHP_EOL
					. "\t\t\t\t\t" . '<hr />' . PHP_EOL
					. "\t\t\t\t\t" . '<ul>' . PHP_EOL
					. "\t\t\t\t\t\t" . '<li><a class="link" href="./" id="' . n3u_IdCleaner($n3u_lang['Home']). '" title="' . n3u_TitleCleaner($n3u_lang['Home']). '">' . $n3u_lang['Home'] . '</a></li>' . PHP_EOL;
					$n3u_configVars['AdminCatList'] = array('dashboard','backup','blocks','cache','language','main','messages','pages','stats','stores','support','templates');
					if($n3u_configVars['CleanUrls'] == TRUE){
						foreach($n3u_configVars['AdminCatList'] as $n3u_Category){
							$n3u_category_url = filter_var('admin_'. urlencode($n3u_Category) . '.htm', FILTER_SANITIZE_URL); // admin_main.htm
							echo "\t\t\t\t\t\t" . '<li><a class="link" href="' . $n3u_category_url . '" id="' . n3u_IdCleaner($n3u_Category). '" title="' . n3u_TitleCleaner($n3u_Category). '">' . ucfirst($n3u_Category) . '</a></li>' . PHP_EOL;
						}
					}else{
						foreach($n3u_configVars['AdminCatList'] as $n3u_Category){
							$n3u_category_url = filter_var($n3u_configVars['self'] . '?x=admin&amp;page='. urlencode($n3u_Category), FILTER_SANITIZE_URL); // ?x=admin&page=main
							echo "\t\t\t\t\t\t" . '<li><a class="link" href="' . $n3u_category_url . '" id="' . n3u_IdCleaner($n3u_Category). '" title="' . n3u_TitleCleaner($n3u_Category). '">' . ucfirst($n3u_Category) . '</a></li>' . PHP_EOL;
						}
					}
					echo "\t\t\t\t\t" . '</ul>' . PHP_EOL
					. "\t\t\t\t\t" . '<form id="categories_form" method="POST" action="' . $n3u_configVars['self'] . '?x=admin">' . PHP_EOL
					. "\t\t\t\t\t\t" . '<fieldset>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<legend>' . $n3u_lang['Category_Settings'] . '</legend>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<label for="Block_defaultKeyword">' . $n3u_lang['Default_Keyword'] . '</label>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Default_Keyword_Explain'] . '</span>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="defaultKeyword" id="Block_defaultKeyword" value="' . $n3u_configVars['defaultKeyword'] . '" required>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<label for="Block_Categories">' . $n3u_lang['Categories'] . '</label>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['Categories_Explain'] . '</span>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<textarea id="Block_Categories" name="Categories" placeholder="Comma,Separated,like,so" required>' . $n3u_configVars['Categories'] . '</textarea>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<label for="Block_CategoryFilters">' . $n3u_lang['CategoryFilters'] . '</label>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<span class="explain">' . $n3u_lang['CategoryFilters_Explain'] . '</span>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<textarea id="Block_CategoryFilters" name="CategoryFilters" placeholder="Comma,Separated,like,so" required>' . $n3u_configVars['CategoryFilters'] . '</textarea>' . PHP_EOL
					. "\t\t\t\t\t\t\t" . '<input class="Button" type="submit" name="submit" value="Update">' . PHP_EOL
					. "\t\t\t\t\t\t" . '</fieldset>' . PHP_EOL
					. "\t\t\t\t\t" . '</form>' . PHP_EOL
					. "\t\t\t\t" . '</div>' . PHP_EOL; // div Categories
				break;
				default: // default mode (Shows Categories)
					echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Categories">' . PHP_EOL
					. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Categories'] . '</h3>' . PHP_EOL
					. "\t\t\t\t\t" . '<hr />' . PHP_EOL
					. "\t\t\t\t\t" . '<ul>' . PHP_EOL;
					$n3u_configVars['CategoriesList'] = explode(',', str_replace(', ',',',$n3u_configVars['Categories']));
					if($n3u_configVars['CleanUrls'] == TRUE){
						foreach($n3u_configVars['CategoriesList'] as $n3u_Category){
							$n3u_category_url = filter_var('search___'. urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) . '-REL-1.htm', FILTER_SANITIZE_URL); // &b=&m=&q=&price_min=&price_max=&sort=&p=
							echo "\t\t\t\t\t\t" . '<li>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_category_url . '" id="' . n3u_IdCleaner($n3u_Category). '" title="' . n3u_TitleCleaner($n3u_Category). '">' . $n3u_Category . '</a>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<a class="atomlink" href="feed___'.urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category).'-REL-1.xml"><img alt="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" height="14" src="' . $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/feed-icon-14x14.png" title="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" width="14"></a>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</li>' . PHP_EOL;
						}
					}else{
						foreach($n3u_configVars['CategoriesList'] as $n3u_Category){
							$n3u_category_url = filter_var($n3u_configVars['self'] . '?x=search&amp;m=&amp;b=&amp;q='. urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) .'&amp;sort=&amp;p=1', FILTER_SANITIZE_URL);
							echo "\t\t\t\t\t\t" . '<li>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_category_url . '" id="' . n3u_IdCleaner($n3u_Category). '" title="' . n3u_TitleCleaner($n3u_Category). '">' . $n3u_Category . '</a>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<a class="atomlink" href="?x=feed&amp;m=&amp;b=&amp;q=' . urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) . '&amp;sort=&amp;p=1"><img alt="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" height="14" src="' . $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/feed-icon-14x14.png" title="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" width="14"></a>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</li>' . PHP_EOL;
						}
					}
					echo "\t\t\t\t\t" . '</ul>' . PHP_EOL
					. "\t\t\t\t" . '</div>' . PHP_EOL; // div Categories
				break;
			}
		break;
		case "false": // If false, Block is not allowed so we return empty
		default:
			if(defined('admin')){
				echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="Categories">' . PHP_EOL
				. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Categories'] . '</h3>' . PHP_EOL
				. "\t\t\t\t\t" . '<hr />' . PHP_EOL
				. "\t\t\t\t\t" . '<span>'.$n3u_lang['Block_Not_Allowed'].'</span>' . PHP_EOL
				. "\t\t\t\t" . '</div>' . PHP_EOL; // div Categories
			}
		break;
	}

?>