<?php

/**
 * Run a bounded ping command for a validated IP address.
 *
 * @return string|null|false Command output, null for invalid input, or false if the process could not be started.
 */
function dvwaPing( $target ) {
	if( !is_string( $target ) || filter_var( $target, FILTER_VALIDATE_IP ) === false ) {
		return null;
	}

	if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
		$command = array( 'ping', '-n', '4', $target );
	}
	else {
		$command = array( 'ping', '-c', '4', $target );
	}

	$descriptors = array(
		0 => array( 'pipe', 'r' ),
		1 => array( 'pipe', 'w' ),
		2 => array( 'pipe', 'w' ),
	);
	$pipes = array();
	$process = proc_open( $command, $descriptors, $pipes );
	if( !is_resource( $process ) ) {
		return false;
	}

	fclose( $pipes[0] );
	$output = stream_get_contents( $pipes[1] );
	$error = stream_get_contents( $pipes[2] );
	fclose( $pipes[1] );
	fclose( $pipes[2] );
	proc_close( $process );

	return $output . $error;
}

?>
