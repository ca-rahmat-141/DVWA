<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input and run ping only for a strictly validated IP address.
	$cmd = dvwaPing( $_REQUEST[ 'ip' ] ?? null );

	// Feedback for the end user
	if( $cmd === null ) {
		$html .= '<pre>ERROR: You have entered an invalid IP.</pre>';
	}
	elseif( $cmd === false ) {
		$html .= '<pre>ERROR: Unable to execute ping.</pre>';
	}
	else {
		$html .= "<pre>{$cmd}</pre>";
	}
}

?>
