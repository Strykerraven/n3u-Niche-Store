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
	echo "\t\t\t\t" . '<div id="Privacy">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>' . $n3u_lang['PrivacyPolicy'] . '</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">This Privacy Policy governs the manner in which ' . $n3u_configVars['sid'] . ' collects, use, maintain and disclose information collected from each visitor of the ' . $n3u_configVars['sid'] . ' website ("Site"). This privacy policy applies to the Site and all products and services offered by ' . $n3u_configVars['sid'] . '.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Personal identification information</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We may collect personal identification information from Visitors in a variety of ways, including, but not limited to, when Visitors visit the site, responds to a survey, fills out a form, and in connection with other activities, services, features or resources we make available on our Site. Visitors may be asked for, as appropriate, an email address or username. Visitors may, however, visit our Site anonymously. We will collect personal identification information from Visitors only if they voluntarily submit such information to us. Visitors can always refuse to supply personally identification information, except that it may prevent them from engaging in certain Site related activities.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Non-personal identification information</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We may collect non-personal identification information about Visitors whenever they interact with our Site. Non-personal identification information may include the browser name, the type of computer and technical information such as how the visitor came to our our Site, and the internet service providers utilized or the operating system and other similar information.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Web browser cookies</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Our Site may use "cookies" to enhance Visitor experience. A Visitor\'s web browser places cookies on the visitors hard drive for record-keeping purposes and sometimes to track information about them. Visitors may choose to set their web browser to refuse cookies, or to alert you when cookies are being sent from the site. If they do so, note that some parts of the Site may not function properly.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>How we use collected information</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">' . $n3u_configVars['SiteName'] . ' collects and uses Visitors personal information for the following purposes:</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To personalize Visitor experience</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We may use information made available from aggregated data to better understand how our Visitors as a whole use the services and resources provided on the Site.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To improve ' . $n3u_configVars['sid'] . '</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We continually strive to improve our website offerings based on the information and feedback we receive from you.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To improve customer service</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Your information helps us to more effectively respond to your customer service requests and support needs.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To process transactions</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We may use the information Visitors provide about themselves when placing an order only to provide service to that order. We do not share this information with outside parties except to the extent necessary to provide the service.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To administer content, promotions, surveys or other Site features</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">To send Visitors information they agreed to receive about topics we think will be of interest to them.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5>To send periodic emails</h5>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">The email address Visitors provide may be used to respond to their inquiries, other requests or questions, or to announce new features or services made available by this website.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>How we protect your information</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We adopt appropriate data collection, storage and processing practices and security measures to protect against unauthorized access, alteration, disclosure or destruction of your personal information, username, password, transaction information and data stored on our Site.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Sharing your personal information</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We do not sell, trade, or rent Users personal identification information to others. We may share generic aggregated demographic information not linked to any personal identification information regarding visitors and users with our business partners, trusted affiliates and advertisers for the purposes outlined above. We may use third party service providers to help us operate our business and the Site or administer activities on our behalf, such as sending out newsletters or surveys. We may share your information with these third parties for those limited purposes provided that you have given us your permission.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Third party websites</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Users may find advertising or other content on our Site that link to the sites and services of our partners, suppliers, advertisers, sponsors, licensors and other third parties. We do not control the content or links that appear on these sites and are not responsible for the practices employed by websites linked to or from our Site. In addition, these sites or services, including their content and links, may be constantly changing. These sites and services may have their own privacy policies and customer service policies. Browsing and interaction on any other website, including websites which have a link to our Site, is subject to that website\'s own terms and policies.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Advertising</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Ads appearing on our site may be delivered to Users by advertising partners, who may set cookies. These cookies allow the ad server to recognize your computer each time they send you an online advertisement to compile non personal identification information about you or others who use your computer. This information allows ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you. This privacy policy does not cover the use of cookies by any advertisers.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Google Adsense</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Some of the ads may be served by Google. Google\'s use of the DART cookie enables it to serve ads to Users based on their visit to our Site and other sites on the Internet. DART uses "non personally identifiable information" and does NOT track personal information about you, such as your name, email address, physical address, etc. You may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy at <a href="http://www.google.com/privacy_ads.html" target="_blank">http://www.google.com/privacy_ads.html</a></p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Compliance with children\'s online privacy protection act (COPPA)</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Protecting the privacy of the very young is especially important. For that reason, we never collect or maintain information at our Site from those we actually know are under the age of 13, and no part of our website is structured to attract anyone under the age of 13.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Changes to this privacy policy</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We have the discretion to update this privacy policy at any time. When we do, we will revise the updated date at the bottom of this page. We encourage Users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect. You acknowledge and agree that it is your responsibility to review this privacy policy periodically and become aware of modifications.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Your acceptance of these terms</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">By using this Site, you signify your acceptance of this policy and <a href="' . $n3u_configVars['self'] . '?x=Terms" target="_self" title="Terms">Terms &amp; Conditions</a>. If you do not agree to this policy, please do not use our Site. Your continued use of the Site following the posting of changes to this policy will be deemed your acceptance of those changes.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>Contacting '.$n3u_configVars['SiteName'].'</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">If you have any questions about this Privacy Policy, the practices of this site, or your dealings with this site, please contact '.$n3u_configVars['SiteName'].' at:<br />' . PHP_EOL
	. "\t\t\t\t\t" . '<a href="' . $n3u_configVars['self'] . '?x=Contact" id="Contact_' . n3u_IdCleaner($n3u_configVars['SiteName']) . '" target="_self" title="' . $n3u_lang['Contact'] . '">' . $n3u_lang['Contact'] . '&nbsp;' . $n3u_configVars['SiteName'] . '</a></p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5 class="lastupdated">Last updated on February 07, 2014</h5>' . PHP_EOL
	. "\t\t\t\t" . '</div>' . PHP_EOL; // div Privacy
?>