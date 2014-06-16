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
	echo "\t\t\t\t" . '<div id="Home">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>Home - Welcome!</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL;
	$brand_array && $merchant_array && $brand_img && $merchant_img = array();
	$n3u_brands = $prosperentApi->getFacets('brand');
	$n3u_merchants = $prosperentApi->getFacets('merchant');
	foreach($n3u_brands as $n3u_row => $n3u_brand){
		$brand_array[] = $n3u_brand['extendedFieldValue'];
	}
	unset($n3u_brands);
	foreach($n3u_merchants as $n3u_row => $n3u_merchant){
		$merchant_array[] = $n3u_merchant['extendedFieldValue'];
	}
	unset($n3u_merchants);
	if((isset($n3u_configVars['referrer']) && ($n3u_configVars['referrer'] != NULL)) && ($n3u_inputVars['querySuggestion'] = @Prosperent_Api::getQueryFromReferrer($n3u_configVars['referrer']))){
		$n3u_inputVars['querySuggestion'] = @Prosperent_Api::getQueryFromReferrer($n3u_configVars['referrer']); // Set the Suggestion to be suggest to the visitor rather than override what the search engine expects to be displayed.
	}elseif(isset($n3u_configVars['referrer']) && ($n3u_configVars['referrer'] != NULL)){ // else if not, referred by search engine
		$n3u_inputVars['querySuggestion'] = @$prosperentApi->getquerySuggestion(); // has the Api provided a suggestion possibly from a mistyped query?
	}else{
		$n3u_inputVars['querySuggestion'] = '';
	}
	echo "\t\t\t\t\t" . '<div id="SearchIndex">' . PHP_EOL
	. "\t\t\t\t\t\t" . '<h4>' . $n3u_lang['BrowseBySearch'] . '</h4>' . PHP_EOL
	. "\t\t\t\t\t\t" . '<div id="SearchBar">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<form accept-charset="UTF-8" action="' . $n3u_configVars['self'] . '" autocomplete="on" id="search_box" method="GET">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input name="x" type="hidden" value="search">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input name="m" type="hidden" value="">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input name="b" type="hidden" value="">' . PHP_EOL;
	if(isset($n3u_inputVars['querySuggestion']) && ($n3u_inputVars['querySuggestion'] !='')){
		echo "\t\t\t\t\t\t\t\t" . $n3u_lang['querySuggested'] . ' <a class="link" href="' . $n3u_configVars['self'] . '?x=search&amp;b=' . @$n3u_inputVars['b'] . '&amp;m=' . @$n3u_inputVars['m'] . '&amp;p=' . $n3u_inputVars['p'] . '&amp;q=' . @$n3u_inputVars['q'] . '&amp;sort=' . @$n3u_inputVars['sort'] .'" id="querySuggestion" title="' . $n3u_lang['querySuggested'] . ' ' . @$n3u_inputVars['q'] . '?">' . @$n3u_inputVars['q'] . '</a>?' . PHP_EOL;
	}					
	echo "\t\t\t\t\t\t\t\t" . '<input autocomplete="on" maxlength="200" name="q" required size="25" title="Search Products" type="text" value="'. str_replace(array($n3u_configVars['defaultKeyword'].' ',$n3u_configVars['defaultKeyword']),'',urldecode(@$n3u_inputVars['q'])) .'">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input name="sort" type="hidden" value="">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input name="p" type="hidden" value="1">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<input class="Button" type="submit" value="Search">' . PHP_EOL		
	. "\t\t\t\t\t\t\t" . '</form>' . PHP_EOL
	. "\t\t\t\t\t\t" . '</div>' . PHP_EOL // div SearchBar		
	. "\t\t\t\t\t" . '</div>' . PHP_EOL // div SearchIndex	
	. "\t\t\t\t\t" . '<div id="Brands">' . PHP_EOL
	. "\t\t\t\t\t\t" . '<h4>'.$n3u_lang['BrowseByBrand'].'</h4>' . PHP_EOL;
	n3u_FetchBrands($brand_array);
	unset($brand_array);
	$n3u_configVars['img_size'] = '60x30';
	$n3u_img_size = explode('x',$n3u_configVars['img_size']);
	foreach($prosperentApi->getData('') as $row){
		if($n3u_configVars['CleanUrls'] == TRUE){
			$n3u_brand_url = filter_var('search__' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','+','',' '),$row['brand']))) . '_-REL-1.htm', FILTER_SANITIZE_URL); // x_merchant_brand_query-sort-page.htm
			if($n3u_configVars['cacheImgs'] == TRUE){
				$brand_img[] = "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_brand_url . '" title="' . n3u_TitleCleaner($row['brand']) . '"><img alt="' . n3u_TitleCleaner($row['brand']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . n3u_CacheImage(filter_var($row['logoUrl'], FILTER_SANITIZE_URL),n3u_IdCleaner($row['brand'])) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}else{
				$brand_img[] = "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_brand_url . '" title="' . n3u_TitleCleaner($row['brand']) . '"><img alt="' . n3u_TitleCleaner($row['brand']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . filter_var($row['logoUrl'], FILTER_SANITIZE_URL) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}
		}else{
			if($n3u_configVars['cacheImgs'] == TRUE){
				$brand_img[] = "\t\t\t\t\t\t" . '<a class="link" href="index.php?x=search&amp;q=&amp;b=' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','','',' '),$row['brand']))) . '&amp;m=&amp;sort=' . @$n3u_inputVars['sort'] . '&amp;p=1" title="' . n3u_TitleCleaner($row['brand']) . '"><img alt="' . n3u_TitleCleaner($row['brand']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . @n3u_CacheImage(filter_var($row['logoUrl'], FILTER_SANITIZE_URL),n3u_IdCleaner($row['brand'])) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}else{
				$brand_img[] = "\t\t\t\t\t\t" . '<a class="link" href="index.php?x=search&amp;q=&amp;b=' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','','',' '),$row['brand']))) . '&amp;m=&amp;sort=' . @$n3u_inputVars['sort'] . '&amp;p=1" title="' . n3u_TitleCleaner($row['brand']) . '"><img alt="' . n3u_TitleCleaner($row['brand']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . filter_var($row['logoUrl'], FILTER_SANITIZE_URL) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}
		}
	}
	sort($brand_img);
	$i=0;
	while($i < count(@$brand_img)){
		echo $brand_img[$i];
		$i++;
	}
	echo "\t\t\t\t\t" . '</div>' . PHP_EOL // div Brands
	. "\t\t\t\t\t" . '<div id="Merchants">' . PHP_EOL
	. "\t\t\t\t\t\t" . '<h4>'.$n3u_lang['BrowseByMerchant'].'</h4>' . PHP_EOL;
	n3u_FetchMerchants($merchant_array);
	unset($merchant_array);
	foreach($prosperentApi->getData('') as $row){	
		if($n3u_configVars['CleanUrls'] == TRUE){
			$n3u_merchant_url = filter_var('search_' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','+','',' '),$row['merchant']))) . '__-REL-1.htm', FILTER_SANITIZE_URL); // x_merchant_brand_query-sort-page.htm
			if($n3u_configVars['cacheImgs'] == TRUE){
				$merchant_img[] = "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_merchant_url . '" title="' . n3u_TitleCleaner($row['merchant']) . '"><img alt="' . n3u_TitleCleaner($row['merchant']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . n3u_CacheImage(filter_var($row['logoUrl'], FILTER_SANITIZE_URL),n3u_IdCleaner($row['merchant'])) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}else{
				$merchant_img[] = "\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_merchant_url . '" title="' . n3u_TitleCleaner($row['merchant']) . '"><img alt="' . n3u_TitleCleaner($row['merchant']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . filter_var($row['logoUrl'], FILTER_SANITIZE_URL) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL;
			}
		}else{
			if($n3u_configVars['cacheImgs'] == TRUE){
				$merchant_img[] = "\t\t\t\t\t\t" . '<a href="index.php?x=search&amp;q=&amp;b=&amp;m=' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','','',' '),$row['merchant']))) . '&amp;sort=' . @$n3u_inputVars['sort'] . '&amp;p=1" title="' . n3u_TitleCleaner($row['merchant']) . '"><img alt="' . n3u_TitleCleaner($row['merchant']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . n3u_CacheImage(filter_var($row['logoUrl'], FILTER_SANITIZE_URL),n3u_IdCleaner($row['merchant'])) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL; // index.php?x=&q=&b=&m=&sort=&p=
			}else{
				$merchant_img[] = "\t\t\t\t\t\t" . '<a href="index.php?x=search&amp;q=&amp;b=&amp;m=' . strtolower(n3u_UrlEncode(str_replace(array(',','.','/','  '),array('','','',' '),$row['merchant']))) . '&amp;sort=' . @$n3u_inputVars['sort'] . '&amp;p=1" title="' . n3u_TitleCleaner($row['merchant']) . '"><img alt="' . n3u_TitleCleaner($row['merchant']) . '" class="image" height="' . $n3u_img_size['1'] . '" src="' . filter_var($row['logoUrl'], FILTER_SANITIZE_URL) . '" width="' . $n3u_img_size['0'] . '" /></a>' . PHP_EOL; // index.php?x=&q=&b=&m=&sort=&p=
			}
		}
	}
	sort($merchant_img);
	$i=0;
	while($i < count(@$merchant_img)){
		echo $merchant_img[$i];
		$i++;
	}
	echo "\t\t\t\t\t" . '</div><hr class="hr" />' . PHP_EOL // div Merchants	
	. "\t\t\t\t\t" . '<div id="CategoriesIndex">' . PHP_EOL
	. "\t\t\t\t\t\t" . '<h4>'.$n3u_lang['BrowseByCategory'].'</h4>' . PHP_EOL;
	$n3u_configVars['CategoriesList'] = explode(',', str_replace(', ',',',$n3u_configVars['Categories']));
	echo "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
	if($n3u_configVars['CleanUrls'] == TRUE){
		foreach($n3u_configVars['CategoriesList'] as $n3u_Category){
			$n3u_category_url = filter_var('search___'. urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) . '-REL-1.htm', FILTER_SANITIZE_URL); // &b=&m=&q=&price_min=&price_max=&sort=&p=
			echo "\t\t\t\t\t\t\t" . '<li>' . PHP_EOL
			. "\t\t\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_category_url . '" title="' . n3u_TitleCleaner($n3u_Category). '">' . $n3u_Category . '</a>' . PHP_EOL
			. "\t\t\t\t\t\t\t\t" . '<a class="atomlink" href="feed___'.urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category).'-REL-1.xml"><img alt="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" height="14" src="' . $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/feed-icon-14x14.png" title="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" width="14"></a>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '</li>' . PHP_EOL;
		}
	}else{
		foreach($n3u_configVars['CategoriesList'] as $n3u_Category){
			$n3u_category_url = filter_var($n3u_configVars['self'] . '?x=search&amp;m=&amp;b=&amp;q='. urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) .'&amp;sort=&amp;p=1', FILTER_SANITIZE_URL);
			echo "\t\t\t\t\t\t\t" . '<li>' . PHP_EOL
			. "\t\t\t\t\t\t\t\t" . '<a class="link" href="' . $n3u_category_url . '" title="' . n3u_TitleCleaner($n3u_Category). '">' . $n3u_Category . '</a>' . PHP_EOL
			. "\t\t\t\t\t\t\t\t" . '<a class="atomlink" href="?x=feed&amp;m=&amp;b=&amp;q=' . urlencode($n3u_configVars['defaultKeyword'] . ' ' . $n3u_Category) . '&amp;sort=&amp;p=1"><img alt="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" height="14" src="' . $n3u_configVars['Template_Dir'] . $n3u_configVars['Template_Name'] . '/images/feed-icon-14x14.png" title="Atom feed for ' . n3u_TitleCleaner($n3u_Category) . '" width="14"></a>' . PHP_EOL
			. "\t\t\t\t\t\t\t" . '</li>' . PHP_EOL;
		}
	}
	echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL
	. "\t\t\t\t\t" . '</div>' . PHP_EOL // div CategoriesIndex
	. "\t\t\t\t\t" . '<div id="OtherLinksIndex">' . PHP_EOL
	. "\t\t\t\t\t\t" . '<h4>'.$n3u_lang['BrowseByOther'].'</h4>' . PHP_EOL;
	$n3u_customPages = n3u_CustomPages(); // Get array of custom pages
	echo "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
	foreach($n3u_customPages as $n3u_customPage){
		echo "\t\t\t\t\t\t\t" . '<li><a href="'.filter_var($n3u_customPage['url'], FILTER_SANITIZE_URL).'" target="_self" title="' . n3u_TitleCleaner($n3u_customPage['name']) . '" type="text/html">' . $n3u_customPage['name'] . '</a></li>' . PHP_EOL;
	}
	if($n3u_configVars['CleanUrls'] == TRUE){
		echo "\t\t\t\t\t\t\t" . '<li><a href="privacy.htm" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
		. "\t\t\t\t\t\t\t" . '<li><a href="terms.htm" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
		. "\t\t\t\t\t\t\t" . '<li><a href="contact.htm" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL;
	}else{
		echo "\t\t\t\t\t\t\t" . '<li><a href="' . filter_var($n3u_configVars['self'] . '?x=privacy', FILTER_SANITIZE_URL) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['PrivacyPolicy']) . '" type="text/html">' . $n3u_lang['PrivacyPolicy'] . '</a></li>' . PHP_EOL
		. "\t\t\t\t\t\t\t" . '<li><a href="' . filter_var($n3u_configVars['self'] . '?x=terms', FILTER_SANITIZE_URL) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['TermsConditions']) . '" type="text/html">' . $n3u_lang['TermsConditions'] . '</a></li>' . PHP_EOL
		. "\t\t\t\t\t\t\t" . '<li><a href="' . filter_var($n3u_configVars['self'] . '?x=contact', FILTER_SANITIZE_URL) . '" target="_self" title="' . n3u_TitleCleaner($n3u_lang['Contact']) . '" type="text/html">' . $n3u_lang['Contact'] . '</a></li>' . PHP_EOL;
	}
	echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL
	. "\t\t\t\t\t" . '</div>' . PHP_EOL; // div OtherLinksIndex
	if(defined('admin')){
		echo "\t\t\t\t\t" . '<div id="AdminLinksIndex">' . PHP_EOL
		. "\t\t\t\t\t\t" . '<h4>'.$n3u_lang['BrowseByAdmin'].'</h4>' . PHP_EOL
		. "\t\t\t\t\t\t" . '<ul>' . PHP_EOL;
		$n3u_configVars['AdminCatList'] = array('dashboard','blocks','main','messages','pages','templates');
		if($n3u_configVars['CleanUrls'] == TRUE){
			foreach($n3u_configVars['AdminCatList'] as $n3u_Category){
				$n3u_category_url = filter_var('admin_'. urlencode($n3u_Category) . '.htm', FILTER_SANITIZE_URL); // admin_main.htm
				echo "\t\t\t\t\t\t\t" . '<li><a class="link" href="' . $n3u_category_url . '" title="' . n3u_TitleCleaner($n3u_Category). '">' . ucfirst($n3u_Category) . '</a></li>' . PHP_EOL;
			}
		}else{
			foreach($n3u_configVars['AdminCatList'] as $n3u_Category){
				$n3u_category_url = filter_var($n3u_configVars['self'] . '?x=admin&amp;page='. urlencode($n3u_Category), FILTER_SANITIZE_URL); // ?x=admin&page=main
				echo "\t\t\t\t\t\t\t" . '<li><a class="link" href="' . $n3u_category_url . '" title="' . n3u_TitleCleaner($n3u_Category). '">' . ucfirst($n3u_Category) . '</a></li>' . PHP_EOL;
			}
		}
		echo "\t\t\t\t\t\t" . '</ul>' . PHP_EOL
		. "\t\t\t\t\t" . '</div>' . PHP_EOL; // div AdminLinksIndex
	}
	echo "\t\t\t\t\t" . '<div class="prosperent-pa" style="margin:1em auto;width:95%;height:90px;"></div>' . PHP_EOL
	. "\t\t\t\t" . '</div>' . PHP_EOL; // div Home

?>