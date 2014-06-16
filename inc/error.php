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
	if(!is_int($n3u_errorVars['Number'])){die($n3u_lang['Invalid_Integer']);}
	switch($n3u_errorVars['Number']){ // Is error defined?
		case "400":
			if(!headers_sent()){header("HTTP/1.1 400 Bad Request");}
			$n3u_errorVars['Type'] = 'Bad Request';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The request could not be understood by the server due to malformed syntax.';}
		break;
		case "401":
			if(!headers_sent()){header("HTTP/1.1 401 Unauthorized");}
			$n3u_errorVars['Type'] = 'Unauthorized';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The request requires user authentication.';}
		break;
		case "402":
			if(!headers_sent()){header("HTTP/1.1 402 Payment Required");}
			$n3u_errorVars['Type'] = 'Payment Required';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'This code is reserved for future use.';}
		break;
		case "403":
			if(!headers_sent()){header("HTTP/1.1 403 Forbidden");}
			$n3u_errorVars['Type'] = 'Forbidden';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server understood the request, but is refusing to fulfill it.';}
		break;
		case "404":
			if(!headers_sent()){header("HTTP/1.1 404 Not Found");}
			$n3u_errorVars['Type'] = 'Not Found';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server has not found anything matching the Request-URI. No indication is given of whether the condition is temporary or permanent.';}
		break;
		case "405":
			if(!headers_sent()){header("HTTP/1.1 405 Method Not Allowed");}
			$n3u_errorVars['Type'] = 'Method Not Allowed';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The method specified in the Request-Line is not allowed for the resource identified by the Request-URI.';}
		break;
		case "406":
			if(!headers_sent()){header("HTTP/1.1 406 Not Acceptable");}
			$n3u_errorVars['Type'] = 'Not Acceptable';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request.';}
		break;
		case "407":
			if(!headers_sent()){header("HTTP/1.1 407 Proxy Authentication Required");}
			$n3u_errorVars['Type'] = 'Proxy Authentication Required';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The client must first authenticate itself with the proxy.';}
		break;
		case "408":
			if(!headers_sent()){header("HTTP/1.1 408 Request Timeout");}
			$n3u_errorVars['Type'] = 'Request Timeout';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The client did not produce a request within the time that the server was prepared to wait.';}
		break;
		case "409":
			if(!headers_sent()){header("HTTP/1.1 409 Conflict");}
			$n3u_errorVars['Type'] = 'Conflict';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The request could not be completed due to a conflict with the current state of the resource.';}
		break;
		case "410":
			if(!headers_sent()){header("HTTP/1.1 410 Gone");}
			$n3u_errorVars['Type'] = 'Gone';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The requested resource is no longer available at the server and no forwarding address is known.';}
		break;
		case "500":
			if(!headers_sent()){header("HTTP/1.1 500 Internal Server Error");}
			$n3u_errorVars['Type'] = 'Internal Server Error';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server encountered an unexpected condition which prevented it from fulfilling the request.';}
		break;
		case "501":
			if(!headers_sent()){header("HTTP/1.1 501 Not Implemented");}
			$n3u_errorVars['Type'] = 'Not Implemented';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server does not support the functionality required to fulfill the request. This is the appropriate response when the server does not recognize the request method and is not capable of supporting it for any resource.';}
		break;
		case "502":
			if(!headers_sent()){header("HTTP/1.1 502 Bad Gateway");}
			$n3u_errorVars['Type'] = 'Bad Gateway';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.';}
		break;
		case "503":
			if(!headers_sent()){header("HTTP/1.1 503 Service Unavailable");}
			$n3u_errorVars['Type'] = 'Service Unavailable';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server is currently unable to handle the request due to a temporary overloading or maintenance of the server. The implication is that this is a temporary condition which will be alleviated after some delay. If known, the length of the delay MAY be indicated in a Retry-After header. If no Retry-After is given, the client SHOULD handle the response as it would for a 500 response.';}
		break;
		case "504":
			if(!headers_sent()){header("HTTP/1.1 504 Gateway Timeout");}
			$n3u_errorVars['Type'] = 'Gateway Timeout';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server specified by the URI (e.g. HTTP, FTP, LDAP) or some other auxiliary server (e.g. DNS) it needed to access in attempting to complete the request.';}
		break;
		case "505":
			if(!headers_sent()){header("HTTP/1.1 505 HTTP Version Not Supported");}
			$n3u_errorVars['Type'] = 'HTTP Version Not Supported';
			if(!isset($n3u_errorVars['Description']) || $n3u_errorVars['Description'] == NULL){$n3u_errorVars['Description'] = 'The server does not support, or refuses to support, the HTTP protocol version that was used in the request message. The server is indicating that it is unable or unwilling to complete the request using the same major version as the client, as described in section 3.1, other than with this error message. The response SHOULD contain an entity describing why that version is not supported and what other protocols are supported by that server.';}
		break;
		default:
			echo 'Error is not defined or invalid.';
		break;
	}
?>