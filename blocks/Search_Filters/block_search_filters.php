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
	if(in_array($n3u_inputVars['x'],array('admin','index','item','search'))){ // 2nd limit by page
		if($n3u_position == 'left' || $n3u_position == 'right' || $n3u_position == 'footer'){ // 1st check to limit areas.
			$n3u_block_allowed = 'true';
		}else{
			$n3u_block_allowed = 'false'; // Not allowed because not left or right
		}
	}else{
		$n3u_block_allowed = 'false'; // Not allowed because not search,index, or item page
	}

			switch($n3u_inputVars['x']){
				case "admin": // If admin, do this
					switch($n3u_block_allowed){
						case "true": // If true, Block is allowed so we return data
							echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="SearchFilters">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Search_Filters'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_No_Settings'].'</span>' . PHP_EOL	
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div SearchFilters
						break;
						case "false": // If false, Block is not allowed
							echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="SearchFilters">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Search_Filters'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_Not_Allowed'].'</span>' . PHP_EOL	
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div SearchFilters
						default:
						break;
					}
				break;
				case "index": // If index, do this
				case "item": // If item, do this
				case "search": // If search, do this
				default:
					switch($n3u_block_allowed){
						case "true": // If true, Block is allowed so we return data
							n3u_FetchSearch($n3u_configVars['Prosperent_Endpoint']);
							// Query Suggestion
							/*
							 * you can retrieve the query from the search engine results page
							 * by using the static method Prosperent_Api::getQueryFromReferrer()
							*/ 
							if(isset($n3u_configVars['referrer']) && ($n3u_configVars['referrer'] != '') && ($n3u_configVars['querySuggestion'] == @Prosperent_Api::getQueryFromReferrer($n3u_configVars['referrer']))){
								$n3u_configVars['querySuggestion'] = @Prosperent_Api::getQueryFromReferrer($n3u_configVars['referrer']); // Set the Suggestion to be suggest to the visitor rather than override what the search engine expects to be displayed.
							}elseif(isset($n3u_configVars['referrer']) && ($n3u_configVars['referrer'] != '')){ // else if not, referred by search engine
								$n3u_config['querySuggestion'] = @$prosperentApi->getquerySuggestion(); // has the Api provided a suggestion possibly from a mistyped query?
							}else{$n3u_configVars['querySuggestion'] = NULL;}
							echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="SearchFilters">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Search_Filters'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
					//		. '<script type="text/javascript">' . PHP_EOL
					//		. 'function showMin(newValue)' . PHP_EOL
					//		. '{' . PHP_EOL
					//		. "\t" . 'document.getElementById("min_range").innerHTML=newValue;' . PHP_EOL
					//		. '}' . PHP_EOL
					//		. 'function showMax(newValue)' . PHP_EOL
					//		. '{' . PHP_EOL
					//		. "\t" . 'document.getElementById("max_range").innerHTML=newValue;' . PHP_EOL
					//		. '}' . PHP_EOL
					//		. '</script>' . PHP_EOL
							. "\t\t\t\t\t" . '<form accept-charset="UTF-8" action="' . $n3u_configVars['self'] . '" autocomplete="on" id="Search" method="GET" name="search" target="_self">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<input name="x" type="hidden" value="search">' . PHP_EOL
							. "\t\t\t\t\t\t" . '<dl>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<dt><label for="BlockSearchBox">' . $n3u_lang['Query'] . '</label></dt>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<dd><input class="SearchBox" id="BlockSearchBox" maxlength="200" name="q" size="25" type="text" title="Search Products" value="'. str_replace(array($n3u_configVars['defaultKeyword'].' ',$n3u_configVars['defaultKeyword']),'',urldecode($n3u_inputVars['q'])) .'"></dd>' . PHP_EOL;
							$n3u_brands = $prosperentApi->getFacets('brand');
							arsort($n3u_brands);
							if(!isset($n3u_brands) || $n3u_brands == ''){
								echo "\t\t\t\t\t\t\t" . '<dt><label for="BlockBrand">' . $n3u_lang['Brand'] . '</label></dt>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<dd>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<select id="BlockBrand" form="Search" name="b">' . PHP_EOL
								. "\t\t\t\t\t\t\t\t\t" . '<option label="Any" selected value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '</dd>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t\t" . '<dt><label for="BlockBrand">' . $n3u_lang['Brand'] . '</label></dt>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<dd>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<select id="BlockBrand" form="Search" name="b">' . PHP_EOL;
								if(isset($n3u_inputVars['b']) && ($n3u_inputVars['b'] !='')){
									echo "\t\t\t\t\t\t\t\t\t" . '<option label="Any" value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t\t\t\t" . '<option label="Any" selected value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL;
								}
								foreach($n3u_brands as $n3u_row){
									if(!isset($n3u_row['value']) || $n3u_row['value'] == ''){ // if value is not set or empty
										if(!isset($n3u_row['extendedFieldValue']) || $n3u_row['extendedFieldValue'] == ''){  // check to see if extendedFieldValue is not set or empty
											$n3u_row['value'] = 'Unknown'; // if true, Lets name these Unknown
										}else{
											$n3u_row['value'] = $n3u_row['extendedFieldValue']; // if false, Lets name these the value of extendedFieldValue
										}
									}
									if(!isset($n3u_row['extendedFieldValue']) || $n3u_row['extendedFieldValue'] == ''){ // if extendedFieldValue is not set or empty
										if(!isset($n3u_row['value']) || $n3u_row['value'] == ''){ // check to see if value is not set or empty
											$n3u_row['extendedFieldValue'] = 'Unknown'; // if true, Lets name these Unknown
										}else{
											$n3u_row['extendedFieldValue'] = $n3u_row['value']; // if false, Lets name these the value of value
										}
									}
									if($n3u_row['count'] >= 1){ // if the value of items by brand is 1 or higher
										if($n3u_row['extendedFieldValue'] == $n3u_inputVars['b']){ // Check to see if this is the active brand
											echo "\t\t\t\t\t\t\t\t\t" . '<option selected value="' . $n3u_row['extendedFieldValue'] . '">' . str_replace( '&', '&amp;', $n3u_row['value']) . ' (' . $n3u_row['count'] . ')</option>' . PHP_EOL;
										}else{
											echo "\t\t\t\t\t\t\t\t\t" . '<option value="' . $n3u_row['extendedFieldValue'] . '">' . str_replace( '&', '&amp;', $n3u_row['value']) . ' (' . $n3u_row['count'] . ')</option>' . PHP_EOL;
										}
									}
								}
								echo "\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '</dd>' . PHP_EOL;
							}
							if(isset($n3u_inputVars['querySuggestion']) && ($n3u_inputVars['querySuggestion'] !='')){
								echo "\t\t\t\t\t\t\t" . '<dt><label>' . $n3u_lang['Query_Suggest'] . '</label></dt>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<dd><a class="link" href="' . $n3u_configVars['self'] . '?x=search&amp;b=' . isset($n3u_inputVars['b']) . '&amp;m=' . isset($n3u_inputVars['m']) . '&amp;p=' . $n3u_inputVars['p'] . '&amp;q=' . $n3u_inputVars['querySuggestion'] . '&amp;sort=' . isset($n3u_inputVars['sort']) .'">' . $n3u_inputVars['querySuggestion'] . '</a></dd>' . PHP_EOL;
							}
							$n3u_merchants = $prosperentApi->getFacets('merchant');
							arsort($n3u_merchants);
							if(!isset($n3u_merchants) || $n3u_merchants == ''){
								echo "\t\t\t\t\t\t\t" . '<dt><label for="BlockMerchant">' . $n3u_lang['Merchant'] . '</label></dt>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<dd>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<select form="Search" id="BlockMerchant" name="m">' . PHP_EOL
								. "\t\t\t\t\t\t\t\t\t" . '<option label="Any" selected value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '</dd>' . PHP_EOL;
							}else{
								echo "\t\t\t\t\t\t\t" . '<dt><label for="BlockMerchant">' . $n3u_lang['Merchant'] . '</label></dt>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '<dd>' . PHP_EOL
								. "\t\t\t\t\t\t\t\t" . '<select form="Search" id="BlockMerchant" name="m">' . PHP_EOL;
								if(isset($n3u_inputVars['m']) && ($n3u_inputVars['m'] !='')){
									echo "\t\t\t\t\t\t\t\t\t" . '<option label="Any" value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL;
								}else{
									echo "\t\t\t\t\t\t\t\t\t" . '<option label="Any" selected value="">' . $n3u_lang['Any'] . '</option>' . PHP_EOL;
								}
								foreach($n3u_merchants as $n3u_row){
									if(!isset($n3u_row['value']) || $n3u_row['value'] == ''){ // if value is not set or empty
										if(!isset($n3u_row['extendedFieldValue']) || $n3u_row['extendedFieldValue'] == ''){  // check to see if extendedFieldValue is not set or empty
											$n3u_row['value'] = 'Unknown'; // if true, Lets name these Unknown
										}else{
											$n3u_row['value'] = $n3u_row['extendedFieldValue']; // if false, Lets name these the value of extendedFieldValue
										}
									}
									if(!isset($n3u_row['extendedFieldValue']) || $n3u_row['extendedFieldValue'] == ''){ // if extendedFieldValue is not set or empty
										if(!isset($n3u_row['value']) || $n3u_row['value'] == ''){ // check to see if value is not set or empty
											$n3u_row['extendedFieldValue'] = 'Unknown'; // if true, Lets name these Unknown
										}else{
											$n3u_row['extendedFieldValue'] = $n3u_row['value']; // if false, Lets name these the value of value
										}
									}
									if($n3u_row['count'] >= 1){ // if the value of items by merhcant is 1 or higher
										if($n3u_row['extendedFieldValue'] == $n3u_inputVars['m']){ // Check to see if this is the active merchant
											echo "\t\t\t\t\t\t\t\t\t" . '<option selected value="' . $n3u_row['extendedFieldValue'] . '">' . str_replace( '&', '&amp;', $n3u_row['value']) . ' (' . $n3u_row['count'] . ')</option>' . PHP_EOL;
										}else{
											echo "\t\t\t\t\t\t\t\t\t" . '<option value="' . $n3u_row['extendedFieldValue'] . '">' . str_replace( '&', '&amp;', $n3u_row['value']) . ' (' . $n3u_row['count'] . ')</option>' . PHP_EOL;
										}
									}
								}
								echo "\t\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
								. "\t\t\t\t\t\t\t" . '</dd>' . PHP_EOL;
							}
							// The following is disabled because results returned are not expected
					// 		echo "\t\t\t\t\t\t\t\t" . '<dt><label>' . $n3u_lang['Price_Min'] . '</label></dt>' . PHP_EOL;
					//		if($n3u_inputVars['price_min'] !=''){
					//			echo "\t\t\t\t\t\t\t\t" . '<dd><input class="price_min" max="100" min="0" step="5" name="price_min" type="range" title="Minimum Price" value="'. $n3u_inputVars['price_min'] .'" onchange="showMin(this.value)"><output name="min_range" id="min_range">'. $n3u_inputVars['price_min'] .'</output></dd>' . PHP_EOL;
					//		}else{
					//			echo "\t\t\t\t\t\t\t\t" . '<dd><input class="price_min" max="100" min="0" step="5" name="price_min" type="range" title="Minimum Price" value="" onchange="showMin(this.value)"><output name="min_range" id="min_range">0</output></dd>' . PHP_EOL;
					//		}
					//		echo "\t\t\t\t\t\t\t\t" . '<dt><label>' . $n3u_lang['Price_Max'] . '</label></dt>' . PHP_EOL;
					//		if($n3u_inputVars['price_max'] !=''){
					//			echo "\t\t\t\t\t\t\t\t" . '<dd><input class="price_max" max="1000" min="0" step="50" name="price_max" type="range" title="Maximum Price" value="'. $n3u_inputVars['price_max'] .'" onchange="showMax(this.value)"><output name="max_range" id="max_range">'. $n3u_inputVars['price_max'] .'</output></dd>' . PHP_EOL;
					//		}else{
					//			echo "\t\t\t\t\t\t\t\t" . '<dd><input class="price_max" max="1000" min="0" step="50" name="price_max" type="range" title="Maximum Price" value="" onchange="showMax(this.value)"><output name="max_range" id="max_range">0</output></dd>' . PHP_EOL;
					//		}
							echo "\t\t\t\t\t\t\t" . '<dt><label for="BlockSort">' . $n3u_lang['Sort'] . '</label></dt>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<dd><select form="Search" id="BlockSort" name="sort">';
							if($n3u_inputVars['sort'] == 'ASC'){
								echo '<option label="Relevance" value="REL">Relevance</option>'
								. '<option label="Price ASC" selected value="ASC">Price Ascending</option>'
								. '<option label="Price DESC" value="DESC">Price Descending</option>';
							}elseif($n3u_inputVars['sort'] == 'DESC'){
								echo '<option label="Relevance" value="REL">Relevance</option>'
								. '<option label="Price ASC" value="ASC">Price Ascending</option>'
								. '<option label="Price DESC" selected value="DESC">Price Descending</option>';
							}else{
								echo '<option label="Relevance" selected value="REL">Relevance</option>'
								. '<option label="Price ASC" value="ASC">Price Ascending</option>'
								. '<option label="Price DESC" value="DESC">Price Descending</option>';					
							}
							echo '</select></dd>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<dd><input name="p" type="hidden" value="1"></dd>' . PHP_EOL
							. "\t\t\t\t\t\t\t" . '<dd><input class="Button" id="SearchButton" type="submit" value="Search"></dd>' . PHP_EOL
							. "\t\t\t\t\t\t" . '</dl>' . PHP_EOL
							. "\t\t\t\t\t" . '</form>' . PHP_EOL
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div SearchFilters
						break;
						case "false": // If false, Block is not allowed
							echo "\t\t\t\t" . '<div class="block_'.$n3u_position.'" id="SearchFilters">' . PHP_EOL
							. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Search_Filters'] . '</h3>' . PHP_EOL
							. "\t\t\t\t\t" . '<hr />' . PHP_EOL
							. "\t\t\t\t\t" . '<span class="explain">'.$n3u_lang['Block_Not_Allowed'].'</span>' . PHP_EOL	
							. "\t\t\t\t" . '</div>' . PHP_EOL; // div SearchFilters
						default:
						break;
					}
				break;
			}

?>