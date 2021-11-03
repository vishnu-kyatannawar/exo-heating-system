<?php

class HeatingManagerImpl {
	function manageHeating( string $t, string $threshold, bool $active ): void {
		try {
			$dt = floatval( $t );
			$dThreshold = floatval( $threshold );
			$m = '';

			if (!$active) {
				return;
			}

			if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
				die( 'could not create socket' );
			}
			if ( !socket_connect( $s, 'heater.home', 9999 ) ) {
				die( 'could not connect!' );
			}

			if ( $dt < $dThreshold) {
				$m = "on";
			} elseif ( $dt > $dThreshold) {
				$m = "off";
			}

			socket_send( $s, $m, strlen( $m ), 0 );
			socket_close( $s );
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
}
