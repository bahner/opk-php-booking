<?php

/**
 * This is the booking system index file.
 *
 * $Id:$
 * @author Stian Aamodt <phpwebsite@aamodt.no>
 */

/* Make sure core is set before executing otherwise it means someone is trying
   to access the module directory directly */

if (!isset($GLOBALS['core'])){
    header('location:../../');
    exit();
}


if (!isset($_SESSION['PHPWS_Booking'])) {
    $_SESSION['PHPWS_Booking'] = new PHPWS_Booking_main;
}


$GLOBALS['CNT_booking'] = array('title'   => '',
				 'content' => $_SESSION['PHPWS_Booking']->action( ) );

?>
