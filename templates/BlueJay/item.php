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
	echo "\t\t\t\t" . '<div id="Item" itemscope itemtype="http://schema.org/Product">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>'.$n3u_lang['Item'].'</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL;
	if(!isset($n3u_results) || $prosperentApi->gettotalRecordsFound() == NULL){
		echo "\t\t\t\t\t" . $n3u_lang['No_Results'] . PHP_EOL;
	}else{
		foreach($n3u_results as $n3u_result){
			$n3u_result['image_url'] = str_replace(array('http:http://','http://http://'),'',$n3u_result['image_url']);
			if(($n3u_result['keyword'] || $n3u_result['keywords']) && $n3u_result['affiliate_url'] && $n3u_result['image_url']){
				if($n3u_configVars['CleanUrls'] == TRUE){
					echo "\t\t\t\t\t" . '<h1 itemprop="name"><a class="link" href="go/'.base64_encode(urlencode($n3u_result['affiliate_url'])).'.htm" id="' . $n3u_result['catalogId'] . '" itemprop="url" rel="nofollow" target="_blank" title="' . n3u_TitleCleaner($n3u_result['keyword']) . '">' . PHP_EOL;
				}else{
					echo "\t\t\t\t\t" . '<h1 itemprop="name"><a class="link" href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode($n3u_result['affiliate_url'])) . '" id="' . $n3u_result['catalogId'] . '" itemprop="url" rel="nofollow" target="_blank" title="' . n3u_TitleCleaner($n3u_result['keyword']) . '">' . PHP_EOL;
				}
				echo "\t\t\t\t\t\t" . $n3u_result['keyword'] . $n3u_result['keywords'] . PHP_EOL;
				if($n3u_configVars['cacheImgs'] == TRUE){
					echo "\t\t\t\t\t\t" . '<img class="image" alt="' . n3u_TitleCleaner($n3u_result['keyword']) . '" id="' . $n3u_result['catalogId'] . '-image" itemprop="image" src="' . @n3u_CacheImage($n3u_result['image_url'],$n3u_result['catalogId']) . '" />' . PHP_EOL;
				}else{
					echo "\t\t\t\t\t\t" . '<img class="image" alt="' . n3u_TitleCleaner($n3u_result['keyword']) . '" id="' . $n3u_result['catalogId'] . '-image" itemprop="image" src="' . $n3u_result['image_url'] . '" />' . PHP_EOL;
				}
				echo "\t\t\t\t\t" . '</a></h1>' . PHP_EOL;
				$item_name = $n3u_result['keyword'] . $n3u_result['keywords'];
			}
			if(isset($n3u_result['description']) && $n3u_result['description'] != NULL){
				echo "\t\t\t\t\t" . '<p class="description" itemprop="description">' . PHP_EOL
				. n3u_Extract($n3u_result['description'],10)
				. "\t\t\t\t\t" . '</p>' . PHP_EOL;
			}else{
				echo "\t\t\t\t\t" . '<p class="description" itemprop="description">'.$n3u_lang['No_Description'].'</p>' . PHP_EOL;
			}
			if($n3u_result['price_sale'] && ($n3u_result['price_sale'] != $n3u_result['price']) && ($n3u_result['price_sale'] != '0.00')){
				$total_savings= $n3u_result['price'] - $n3u_result['price_sale'];
				$percentage= number_format(100 - ($n3u_result['price_sale'] / $n3u_result['price'] * 100),0);
				echo "\t\t\t\t\t" . '<span class="Price_Info" itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price">'.$n3u_lang['Price'].'</span><br />' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price_Normal">' . PHP_EOL
				. "\t\t\t\t\t\t\t" . '<del><span class="Price_Symbol">'.n3u_ReturnPriceSymbol($n3u_result['currency']).'</span>' . $n3u_result['price'] . '</del>' . PHP_EOL
				. "\t\t\t\t\t\t" . '</span><br />' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price_Sale"><span class="Price_Symbol">'.n3u_ReturnPriceSymbol($n3u_result['currency']).'</span><span itemprop="price">' . $n3u_result['price_sale'] . '</span><span class="Price_Currency" itemprop="priceCurrency">' . $n3u_result['currency'] . '</span></span>' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price_Savings">' . $n3u_lang['Save'] . ' ' . n3u_ReturnPriceSymbol($n3u_result['currency']) . $total_savings. ' (' . $percentage . '% off) </span>' . PHP_EOL
				. "\t\t\t\t\t" . '</span>' . PHP_EOL;
			}elseif(isset($n3u_result['price']) && ($n3u_result['price'] != '0.00')){
				echo "\t\t\t\t\t" . '<span class="Price_Info" itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price">'.$n3u_lang['Price'].'</span><br />' . PHP_EOL
				. "\t\t\t\t\t\t" . '<span class="Price_Normal"><span class="Price_Symbol">'.n3u_ReturnPriceSymbol($n3u_result['currency']).'</span><span itemprop="price">' . $n3u_result['price'] . '</span><span class="Price_Currency" itemprop="priceCurrency">' . $n3u_result['currency'] . '</span></span>' . PHP_EOL
				. "\t\t\t\t\t" . '</span>' . PHP_EOL;
			}
			if($n3u_result['affiliate_url']){
				if($n3u_configVars['CleanUrls'] == TRUE){
					echo "\t\t\t\t\t" . '<a class="buy_link" href="go/' . base64_encode(urlencode($n3u_result['affiliate_url'])) . '.htm" id="Buy_' . $n3u_result['catalogId'] . '" itemprop="url" target="_blank" title="Buy ' . n3u_TitleCleaner($n3u_result['keyword']) . '">Buy Now</a>' . PHP_EOL;
				}else{
					echo "\t\t\t\t\t" . '<a class="buy_link" href="' . $n3u_configVars['self'] . '?x=go&amp;url=' . base64_encode(urlencode($n3u_result['affiliate_url'])) . '" id="Buy_' . $n3u_result['catalogId'] . '" itemprop="url" target="_blank" title="Buy ' . n3u_TitleCleaner($n3u_result['keyword']) . '">Buy Now</a>' . PHP_EOL;
				}
			}
			echo "\t\t\t\t\t" . '<div class="Extra_Info">' . PHP_EOL;
			if($n3u_result['brand']){
				$n3u_result['brand_cleaned'] = strtolower(urlencode(str_replace(array('\'','.',',','/','&', '  '),array('',' ','','','and',' '),$n3u_result['brand'])));
				if($n3u_configVars['CleanUrls'] == TRUE){
					echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['Brand'].'</h5>&nbsp;<a href="search__'.$n3u_result['brand_cleaned'].'_'.urlencode($n3u_inputVars['q']).'-'.$n3u_inputVars['sort'].'-1.htm" itemprop="brand" title="' . n3u_TitleCleaner($n3u_result['brand']) . '">'.$n3u_result['brand'].'</a><br />' . PHP_EOL;
				}else{
					echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['Brand'].'</h5>&nbsp;<a href="index.php?x=search&amp;m=&amp;b='.$n3u_result['brand_cleaned'].'&amp;q='.urlencode($n3u_inputVars['q']).'&amp;sort='.$n3u_inputVars['sort'].'&amp;p=1" itemprop="brand" title="' . n3u_TitleCleaner($n3u_result['brand']) . '">'.$n3u_result['brand'].'</a><br />' . PHP_EOL;
				}
			}
			if($n3u_result['merchant']){
				$n3u_result['merchant_cleaned'] = strtolower(urlencode(str_replace(array('\'','.',',','/','&', '  '),array('',' ','','','and',' '),$n3u_result['merchant'])));
				if($n3u_configVars['CleanUrls'] == TRUE){
					echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['Merchant'].'</h5>&nbsp;<a href="search_'.$n3u_result['merchant_cleaned'].'__'.urlencode($n3u_inputVars['q']).'-'.$n3u_inputVars['sort'].'-1.htm" itemprop="isRelatedTo" title="' . n3u_TitleCleaner($n3u_result['merchant']) . '">'.$n3u_result['merchant'].'</a><br />' . PHP_EOL;
				}else{
					echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['Merchant'].'</h5>&nbsp;<a href="index.php?x=search&amp;m='.$n3u_result['merchant_cleaned'].'&amp;b=&amp;q='.urlencode($n3u_inputVars['q']).'&amp;sort='.$n3u_inputVars['sort'].'&amp;p=1" itemprop="isRelatedTo" title="' . n3u_TitleCleaner($n3u_result['merchant']) . '">'.$n3u_result['merchant'].'</a><br />' . PHP_EOL;
				}
			}
			if($n3u_result['upc']){echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['UPC'].'</h5>&nbsp;<span class="UPC_Info" itemprop="gtin13">0' . $n3u_result['upc'] . '</span><br />' . PHP_EOL;}
			if($n3u_result['isbn']){echo "\t\t\t\t\t\t" . '&nbsp;<h5>'.$n3u_lang['ISBN'].'</h5>&nbsp;' . $n3u_result['isbn'] . PHP_EOL;}
			echo "\t\t\t\t\t" . '</div>' . PHP_EOL; // div Extra_Info
			if($n3u_result['keyword'] && $n3u_result['affiliate_url'] && $n3u_result['image_url']){echo "\t\t\t\t\t" . '<hr class="hr" />' . PHP_EOL;}
		}
		echo "\t\t\t\t\t" . '<small>'.$n3u_lang['PricingAvail_Changes'].'</small>' . PHP_EOL
		. "\t\t\t\t\t" . '<div class="prosperent-pa" style="margin:1em auto;width:95%;height:150px;"></div>' . PHP_EOL;
	}
	echo "\t\t\t\t" . '</div>' . PHP_EOL; // div Item

?>