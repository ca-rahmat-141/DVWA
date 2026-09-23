<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Run ping through the shared, validated process helper.
	$cmd = dvwaExecPing( $_REQUEST[ 'ip' ] ?? null );

	// Feedback for the end user
	if( $cmd === false ) {
		$html .= '<pre>ERROR: You have entered an invalid IP.</pre>';
	}
	elseif( $cmd !== null ) {
		$html .= "<pre>{$cmd}</pre>";
	}
}

?>
