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
	// Check to see if the form has been submitted
	if($n3u_ServerVars['REQUEST_METHOD'] == 'POST'){
		$errors = ''; // set errors to empty
		if(isset($n3u_PostVars['emailconfirm']) && $n3u_PostVars['emailconfirm'] != NULL){
			header('Location: index.php?x=contact'); // Redirect to contact page without reason since this shouldnt be filled.
		}
		// Success message
		$success_message = "\t\t\t\t\t\t" . '<li class="success">'.$n3u_lang['Message_Received'].'</li><li class="success">'.$n3u_lang['Safely_Add'].'</li>' . PHP_EOL;
		// Check the reCaptcha response if enabled
		if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)){
			$resp = recaptcha_check_answer($n3u_configVars['reCaptcha_privKey'],$n3u_ServerVars["REMOTE_ADDR"],$n3u_PostVars["recaptcha_challenge_field"],$n3u_PostVars["recaptcha_response_field"]);
			// If the reCaptcha response was invalid set an error.
			if(!$resp->is_valid){
				$errors .= "\t\t\t\t\t\t" . '<li class="fail">'.$n3u_lang['Incorrect_ReCaptcha'].'</li><li class="fail">'.$n3u_lang['Incorrect_ReCaptcha2'].'</li>' . PHP_EOL;
			}
		}
		// Assign the post variable and sanitize it  
		$name = (filter_var($n3u_PostVars['name'],FILTER_SANITIZE_STRING)); 
		if(!isset($name) || $name == NULL){ // Make sure Name field is not empty
			$errors .= "\t\t\t\t\t\t" . '<li class="fail">'.$n3u_lang['No_Name'].'</li>' . PHP_EOL;
		}
		// Sanitize the email address and check that its valid 
		$email = (filter_var($n3u_PostVars['email'],FILTER_SANITIZE_EMAIL));
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors .= "\t\t\t\t\t\t" . '<li class="fail">'.$n3u_lang['No_Email'].'</li>' . PHP_EOL;
		}
		// Sanitize the subject  
		$subject = (filter_var($n3u_PostVars['subject'],FILTER_SANITIZE_STRING));
		if(!isset($subject) || $subject == NULL){
			$errors .= "\t\t\t\t\t\t" . '<li class="fail">'.$n3u_lang['No_Subject'].'</li>' . PHP_EOL;
		}
		// Sanitize the comments  
		$comments = (filter_var($n3u_PostVars['comments'],FILTER_SANITIZE_STRING));
		if(!isset($comments) || $comments == NULL){
			$errors .= "\t\t\t\t\t\t" . '<li class="fail">'.$n3u_lang['No_Comments'].'</li>' . PHP_EOL;
		}
		// If no errors then create the message
		if(!isset($errors) || $errors == NULL){
		//	$message = 'Name: ' . $name . "\r\n" . 'Comments: ' .$comments. "\r\n";
			n3u_WriteMessage($name,$email,$subject,$comments);
		}
	}else{ // Page wasn't submitted
		// Contact Description
		$contact_desc = "\t\t\t\t\t\t" . 'Hello! Do you need to contact ' . $n3u_configVars['SiteName'] . '? If so, You\'ve come to the right place! This form may be used to report bugs, ask product questions, make suggestions, request projects be added/removed and lots more since ' . $n3u_configVars['SiteName'] . ' has made it easy. Simply fill out the fields provided below.<br />' . PHP_EOL;
	}
	echo "\t\t\t\t" . '<div id="Contact">' . PHP_EOL
	. "\t\t\t\t\t" . '<h3>' . $n3u_lang['Contact'] . ' ' . $n3u_configVars['SiteName'] .'</h3>' . PHP_EOL
	. "\t\t\t\t\t" . '<hr />' . PHP_EOL
	. "\t\t\t\t\t" . '<div id="ContactForm">' . PHP_EOL;
	if(isset($contact_desc)){echo $contact_desc;}
	if(isset($errors) && $errors !=NULL){ //Display Error Message 
		echo "\t\t\t\t\t\t" . '<ul>' . PHP_EOL . $errors . "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
	}elseif($n3u_ServerVars['REQUEST_METHOD'] == 'POST'){ // Posted successfully so lets show a success message
		echo "\t\t\t\t\t\t" . '<ul>' . PHP_EOL . $success_message . "\t\t\t\t\t\t" . '</ul>' . PHP_EOL;
	}
	echo "\t\t\t\t\t\t" . '<form method="POST" action="' . $n3u_configVars['self'] . '?x=contact">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<span class="title" style="display:block;text-align:right;width:92.5%;"><span class="required">*</span>'.$n3u_lang['Field_Required'].'</span>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<label class="title" for="name">'.$n3u_lang['Name'].':&nbsp;<span class="required">*</span></label>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="name" id="name" required value="' . @$n3u_PostVars['name'] . '">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<label class="title" for="email">'.$n3u_lang['Email'].':&nbsp;<span class="required">*</span></label>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<input autocomplete="on" type="text" name="email" id="email" required value="' . @$n3u_PostVars['email'] . '">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<input type="hidden" name="emailconfirm" value="">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<label class="title" for="subject">'.$n3u_lang['Subject'].':</label>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<select id="subject" name="subject">' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="General">'.$n3u_lang['General'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="Advertise">'.$n3u_lang['Advertise'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="BugReport">'.$n3u_lang['Report_Bug'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="ProductQuestion">'.$n3u_lang['Product_Question'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="MakeSuggestion">'.$n3u_lang['Make_Suggestion'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t\t" . '<option value="MakeRequest">'.$n3u_lang['Make_Request'].'</option>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '</select>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<label class="title" for="comments">'.$n3u_lang['Comments'].':&nbsp;<span class="required">*</span></label>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<textarea id="comments" name="comments" required>' . @$n3u_PostVars['comments'] . '</textarea>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<span class="explain">'. $n3u_lang['Contact_Confirm'] . '</span>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<label class="title" for="Confirm">'.$n3u_lang['Confirm'].':&nbsp;<span class="required">*</span></label>' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<input id="Confirm" name="Confirm" required type="checkbox" value="">' . PHP_EOL;
	if((isset($n3u_configVars['reCaptcha_pubKey']) && $n3u_configVars['reCaptcha_pubKey'] != NULL) && (isset($n3u_configVars['reCaptcha_privKey']) && $n3u_configVars['reCaptcha_privKey'] != NULL)){
		echo "\t\t\t\t\t\t\t" . '<label class="title">'.$n3u_lang['Anti_Spam'].':<span class="required">*</span></label>' . PHP_EOL
		. "\t\t\t\t\t\t\t" . recaptcha_get_html($n3u_configVars['reCaptcha_pubKey']) . PHP_EOL;
	}
	echo "\t\t\t\t\t\t\t" . '<input class="Button" name="reset" type="reset" value="Reset">' . PHP_EOL
	. "\t\t\t\t\t\t\t" . '<input class="Button" name="submit" type="submit" value="Send Message">' . PHP_EOL
	. "\t\t\t\t\t\t" . '</form>' . PHP_EOL
	. "\t\t\t\t\t" . '</div>' . PHP_EOL// div ContactForm
	. "\t\t\t\t" . '</div>' . PHP_EOL; // div Contact

?>