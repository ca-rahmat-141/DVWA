<?php

/**
 * Run ping only for a strictly parsed IP address, without passing a command
 * string to a shell.
 *
 * @param mixed $target
 * @return string|false|null Ping output, false for an invalid address, or null
 *                           when the process could not be started.
 */
function dvwaExecPing( $target ) {
	if( !is_string( $target ) || filter_var( $target, FILTER_VALIDATE_IP ) === false ) {
		return false;
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
	);
	$process = proc_open( $command, $descriptors, $pipes, null, null, array( 'bypass_shell' => true ) );
	if( !is_resource( $process ) ) {
		return null;
	}

	fclose( $pipes[0] );
	$output = stream_get_contents( $pipes[1] );
	fclose( $pipes[1] );
	proc_close( $process );

	return $output;
}

?>
