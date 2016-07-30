<?php

	if(!defined('n3u')){
		die('Direct access is not permitted.');
	} // Is n3u defined?
	$n3u_block_allowed='true'; // Block is allowed in all positions.
	switch($n3u_inputVars['x']){
		default: // default mode (Shows Back To Top)
			echo "\t\t\t\t" . '<div id="BackToTop"><a href="#Top" title="Back to the Top">' . $n3u_lang['Back_To_Top'] . '</a></div>' . PHP_EOL;
			break;
	}
?>