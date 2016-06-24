<script language="JavaScript">
function quickChoiceBooking( userid, useridField, fullname, fullnameField )
{
	if ( ( useridField.value != "" ) || ( fullnameField.value != "" ) )
	{
		useridField.value = "";
		fullnameField.value = "";
	}
	else
	{
		useridField.value = userid;
		fullnameField.value = fullname;
	}
}
</script>
<?php

require_once ( "booking_constants.php" );
require_once ( "booking_arrangement.php" );
#require_once ( "booking_import.php" );


$user = $bookingSession->getUser();
if ( $user )
{
	$userid = $user->getDbCol( "userid" );
	$fullname = $user->getDbCol( "fullname" );
}

#$import = new Booking_import( );
#$import->import( getenv ( "DOCUMENT_ROOT" ) ."/../php/opk/booking/dev/users.txt" );


#echo getFormVar( "login" ) ."<p>\n";

# Check that todate is larger than fromdate
if ( $formaction == Form_action_logon )
{
	$fromtime = getPeriodFrom( );
	$totime = getPeriodTo( );
	# Invalid periode. Revert to show login page and error message.
	if ( $totime <= $fromtime )
	{
		$formaction = "";
		$error = $user->getError();
		if ( !$error )
		{
			$error = new Booking_message;
			$error->setHtmlPreSuffix( booking_error_html_prefix, booking_error_html_suffix );
		}
		$error->addMessage( "Feil ! Tildato må være større enn fradato." );
		$bookingSession->setError( $error ); 
	}
}


# Initialice booking top level class
$arrangement = new Booking_arrangement ( 1 );

# Show different pages based on value of $formaction
switch ( $formaction )
{
	case Form_action_logon:
		# Show roomlist for printing
		if ( getFormVar( "login" ) == Booking_login_submit_roomlist )
		{
			$arrangement->setEditable( FALSE );
			$arrangement->setViewMode( Booking_printlist );
		}
		else
		{
			# 7 hours
			$addedtime = 60 * 60 * 3;
			# Period backwards in time selected. Disallow editing
			if ( ( getPeriodFrom( ) +  $addedtime ) < time( ) ) $arrangement->setEditable( FALSE );
			$arrangement->setFormAction( Form_action_booking );
		}
		echo $arrangement->toHtml( );
		break;
	case Form_action_booking:
		if ( $arrangement->handleForm( ) )
		{
			$arrangement->setFormAction( Form_action_booked );
			$arrangement->setEditable( FALSE );
		}
		else
		{
			$arrangement->setFormAction( Form_action_booking );
		}
		echo $arrangement->toHtml( );
		break;
	case Form_action_booked:
		break;

	default:
		include( "booking_login.php" );
}

?>