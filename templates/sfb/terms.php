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
	echo "\t\t\t\t" . '<div id="Terms">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>' . $n3u_lang['TermsConditions'] . '</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL
	. "\t\t\t\t\t" . '<label>INTRODUCTION</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">This Agreement contains the complete Terms &amp; Conditions that apply to you in viewing, searching, buying, and all other activities you will engage in while visiting this website. By using this Web site and its services, you agree to be bound by it\'s Terms &amp; Conditions and shall comply thereof. This Agreement describes and encompasses the entire agreement between ' . $n3u_configVars['SiteName'] . ' and you, and supersedes all prior or contemporaneous agreements, representations, warranties and understandings with respect to the Site, the content, services and computer programs provided by or through the Site, and the subject matter of this Agreement. Amendments to this agreement will be made effective by ' . $n3u_configVars['SiteName'] . ' from time to time without specific notice to you. Therefor, It is your responsibiltiy to stay current with the latest Terms &amp; Conditions for ' . $n3u_configVars['SiteName'] . '. The date posted posted at the bottom of the Terms &amp; Conditions reflects the latest changes of these Terms amp; Conditions and you should carefully review the terms before to use of ' . $n3u_configVars['SiteName'] . ' to determine if they have been updated since any previous visit.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>USE OF THIS SITE &amp; RESTRICTIONS GOVERNING ITS USE</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">This Site may allow you to buy, sell, advertise, bid and compare online. However, you are prohibited to do the following acts, to wit: (a) use our sites, including its services and or tools if you are not able to form legally binding contracts, are under the age of 18, or are temporarily or indefinitely suspended from using our sites, services, or tools (b) posting of an items in inappropriate category or areas on our sites and services; (c) collecting information about users\' personal information; (d) maneuvering the price of any item or interfere with other users\' listings; (f) post false, inaccurate, misleading, defamatory, or libelous content; (g) take any action that may damage the rating system.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">During checkout you may be refered to our partners where you may be asked to complete a registration process, enter payment information, and/or otherwise enter any other relevent information to complete your order. You acknowledge that the transaction takes place between you and the Merchant directly and that ' . $n3u_configVars['SiteName'] . ' only serves as a referral. Each Merchant may have their own Terms &amp; Conditions, Privacy Policy, or other requirements for you to complete prior to the order being processed. You agree not to transmit or utilize any worms, viruses, exploits or any code of a destructive nature when using ' . $n3u_configVars['SiteName'] . '.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>PAYMENTS &amp; ORDERS</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Unless otherwise stated, ' . $n3u_configVars['SiteName'] . ' does not collect payment information directly. ' . $n3u_configVars['SiteName'] . ' is not responsible for pricing, typographical, or other errors in any offer by ' . $n3u_configVars['SiteName'] . ' or it\'s affiliates and reserves the right to update or remove content at any given time.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>PRIVACY</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Use of ' . $n3u_configVars['SiteName'] . ' also requries that you agree to the Privacy Policy found here: <a href="' . $n3u_configVars['self'] . '?x=Privacy" target="_self" title="Privacy Policy">' . $n3u_configVars['self'] . '?x=Privacy</a></p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>EDITING, DELETING &amp; MODIFICATION</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">' . $n3u_configVars['SiteName'] . ' may edit, delete or modify any of the Terms &amp; Conditions contained in this Agreement, at any time and in our sole discretion by updating the date at the bottom of this agreement. YOUR CONTINUED PARTICIPATION IN OUR PROGRAM, VISIT AND SHOPPING ON OUR SITE FOLLOWING ANY CHANGES WILL CONSTITUTE BINDING ACCEPTANCE OF THE CHANGE.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>ACKNOWLEDGMENT OF RIGHTS</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">You hereby acknowledge that all rights, titles and interests, including but not limited to rights covered by the Intellectual Property Rights, in and to the site, and that You will not acquire any right, title, or interest in or to the Program except as expressly set forth in this Agreement. You will not modify, adapt, translate, prepare derivative works from, decompile, reverse engineer, disassemble or otherwise attempt to derive source code from any of our services, software, or documentation, or create or attempt to create a substitute or similar service or product through use of or access to the Program or proprietary information related thereto.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>FRAUD</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">FRAUDULENT ACTIVITIES are highly monitored in our site and if fraud is detected ' . $n3u_configVars['SiteName'] . ' shall resort ro all actions available to us, and you shall be responsible for all costs and legal fees arising from these fraudulent activities.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>WARRANTY DISCLAIMER &amp; LIMITATIONS OF LIABILITY</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We will not be liable for indirect, special, or consequential damages, or any loss of revenue, profits, or data, arising in connection with this Agreement or the Program, even if we have been advised of the possibility of such damages. Further, our aggregate liability arising with respect to this Agreement and the Program will not exceed USD2,000 or the total price of the subject products paid or payable to you whichever is less.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">We make no express or implied warranties or representations with respect to the Program or any products sold and offered in our website (including, without limitation, warranties of fitness, merchantability, non-infringement, or any implied warranties arising out of a course of performance, dealing, or trade usage). In addition, we make no representation that the operation of our site will be uninterrupted or error-free, and we will not be liable for the consequences of any interruptions or errors. This site and its information, contents, materials, products and services are provided on an "as is" and "as available" basis. You and understand and agree that your use of this site is at your own risk.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>CONFIDENTIALITY</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">You agree not to disclose information you obtain from us and or from our clients, advertise and suppliers. All information submitted to by an end-user customer pursuant to a Program is proprietary information of ' . $n3u_configVars['SiteName'] . '. Such customer information is confidential and may not be disclosed. Publisher agrees not to reproduce, disseminate, sell, distribute or commercially exploit any such proprietary information in any manner.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>NON-WAIVER</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Failure of the ' . $n3u_configVars['SiteName'] . ' to insist upon strict performance of any of the terms, conditions and covenants hereof shall not be deemed a relinquishment or waiver of any rights or remedy that the we may have, nor shall it be construed as a waiver of any subsequent breach of the terms, conditions or covenants hereof, which terms, conditions and covenants shall continue to be in full force and effect.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">No waiver by either party of any breach of any provision hereof shall be deemed a waiver of any subsequent or prior breach of the same or any other provision.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<label>MISCELLANEOUS</label>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">This Agreement shall be governed by and construed in accordance with the substantive laws of  ' . $n3u_configVars['SiteURL'] . ', without any reference to conflict-of-laws principles.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Any dispute, controversy or difference which may arise between the parties out of, in relation to or in connection with this Agreement is hereby irrevocably submitted to the exclusive jurisdiction of the courts of ' . $n3u_configVars['SiteURL'] . ', to the exclusion of any other courts without giving effect to its conflict of laws provisions or your actual state or country of residence.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">The entire agreement between the parties with respect to the subject matter hereof is embodied on this agreement and no other agreement relative hereto shall bind either party herein.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">Your rights of whatever nature cannot be assigned nor transferred to anybody, and any such attempt may result in termination of this Agreement, without liability to us. However, we may assign this Agreement to any person at any time without notice.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<p class="explain">In the event that any provision of these Terms and Conditions is found invalid or unenforceable pursuant to any judicial decree or decision, such provision shall be deemed to apply only to the maximum extent permitted by law, and the remainder of these Terms and Conditions shall remain valid and enforceable according to its terms.</p>' . PHP_EOL
	. "\t\t\t\t\t" . '<h5 class="lastupdated">Last updated on February 07, 2014</h5>' . PHP_EOL
	. "\t\t\t\t" . '</div>' . PHP_EOL; // div Terms
?>