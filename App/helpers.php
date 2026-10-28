<?php
declare( strict_types=1 );
function formatDate( string $date ): string {
	return date( 'd F Y', strtotime( $date ) );
}

function formatDollarAmount( float $amount ): string {
	$isNegative = $amount < 0;

	return $isNegative
		? sprintf( '-$%.2f', number_format( abs( $amount ) ) )
		: sprintf( '$%.2f', number_format( abs( $amount ) ) );

}