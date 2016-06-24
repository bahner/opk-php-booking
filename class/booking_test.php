<?php

require_once ( "booking_constants.php" );
require_once ( "booking_arrangement.php" );

$arrangement = new Booking_arrangement ( 1 );
$arrangement->show( );

?>
