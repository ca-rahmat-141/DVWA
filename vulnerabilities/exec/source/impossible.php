<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Run ping through the shared, validated process helper.
	$cmd = dvwaExecPing( $_REQUEST[ 'ip' ] ?? null );

	if( $cmd === false ) {
		$html .= '<pre>ERROR: You have entered an invalid IP.</pre>';
	}
	elseif( $cmd !== null ) {
		$html .= "<pre>{$cmd}</pre>";
	}
}

// Generate Anti-CSRF token
generateSessionToken();

?>
