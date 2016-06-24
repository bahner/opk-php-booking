<?php

require_once ( "booking_constants.php" );
require_once ( "booking_session.php" );

# Handle HTML form actions

global $formaction;
$formaction = getFormAction( );
global $bookingSession;
$bookingSession = new Booking_session( );

# Set global counter. Used by booking_libfunctions setCounter( ) and getCounter( )
global $bookingCounter;
$bookingCounter = 0;

switch ( $formaction )
{
	case Form_action_logon:
		$userid = getFormVar( "userid" );
		$password = getFormVar( "password" );
		if ( !$bookingSession->create( $userid, $password ) )
		{
			$formaction = "";
		}
		else 
		{
		  if ( getFormVar( "login" ) == Booking_login_submit_roomlist )
  		{
  			# Turn off menus and footer on room page for printing.
        $_REQUEST['lay_quiet'] = 1; 
  		}
    }
		break;
	case Form_action_booking:
	case Form_action_booked:
	default:
		$bookingSessionid = getCookie( Booking_session_id );
		if ( !$bookingSession->get( $bookingSessionid ) )
		{
			$formaction = "";
		}
}
