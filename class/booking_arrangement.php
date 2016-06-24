<?php

require_once ( "booking_constants.php" );
require_once ( "booking_basedb.php" );
require_once ( "booking_message.php" );
require_once ( "booking_resources.php" );
require_once ( "booking_resource.php" );


class Booking_arrangement extends Booking_basedb
{
	function Booking_arrangement( $arrangementId = NULL )
	{
		$sql = "SELECT * FROM ". DB_tbl_booking_arrangement ." WHERE id=". $arrangementId;
		$this->Booking_basedb( DB_tbl_booking_arrangement, $sql );
	}


	function formatfunction_vangen ( )
	{
		global $bookingSession;

		$html = "\n<!-- Booking_arrangement->formatfunction_vangen( ) -->\n";

		$viewmode = $this->getViewMode( );
		if ($this->getDbRecord( ) != NULL)
		{
		  $html .= "<table widht=\"500\">\n";
			$html .= "<form name=\"" .Booking_form_name ."\" method=\"post\">\n";
			$html .= "<table widht=\"300\">\n";
			$html .= "<tr><td>\n";
			$html .= "\n<h2 align=\"center\">". $this->getDbColTrim( "name" );

			$html .= " " .getFormatedDateShort( getPeriodFrom( ) );
			$html .= "-" .getFormatedDateShort( getPeriodTo( ) );
			# Show submit button
			if ( $this->isEditable( ) )
			{
				$html .= " <input type=\"submit\" value=\" Lagre bookinger \">";
			}
			$html .= "</h2>\n";

			$user = $bookingSession->getUser( );
			$userid = $user->getDbColTrim( "userid" );
			$username = $user->getDbCol( "fullname" );

			# Show name of logged on user
			if ( $viewmode != Booking_printlist )
			{
				$html .= "<center>Innlogget: <b>$username</b></center><p>\n";
				if ( $this->getFormAction( ) == Form_action_booking )
					$html .= include( "booking_instructions.php" );
			}

			# Show error messages
			if ( $bookingError = $this->getError( ) )
			{
				if ( !$bookingError->isEmpty( ) )
				{
					$html .= "</td></tr>\n"
					."<tr><td>"
					."<table>"
					."<tr><td><b>Feilmelding</b></td><td>" .$bookingError->toHtml( ) ."</td></tr>\n"
					."</table>\n";
				}
			}
			# Show information messages
			else
			{
				# Create array with members that are not bound by the guest booking rules
				eval( "\$noGuestLimitations = array( " .Booking_noGuestLimitations ." );" );

				# Show information message if user is not bound by guest booking rules
				if ( ( $viewmode != Booking_printlist ) && array_key_exists( $userid, $noGuestLimitations ) )
				{
					$information = new Booking_message;
					$information->setHtmlPreSuffix( booking_information_html_prefix, booking_information_html_suffix );
					$information->addMessage( $noGuestLimitations[ $userid ] );
					$html .= "</td></tr>\n"
					."<tr><td>"
					."<table>"
					."<tr><td><b>OBS!</b></td><td>" .$information->toHtml( ) ."</td></tr>\n"
					."</table>\n";
				}
			}


			setBookingCounter( 0 );
			$resources = new Booking_resources( $this->getId( ) );
			$resources->setFormAction( $this->getFormAction( ) );
			$resources->setEditable( $this->isEditable( ) );
			$resources->setViewMode( $this->getViewMode( ) );
			$html .= $resources->toHtml();
			$html .= "</td></tr>\n";

			# Show payment fields
			if ( $viewmode == Booking_printlist )
			{
				$html .= "<tr>\n<td>\n";
				$html .= include( "booking_paymentinfo.php" );
				$html .= "</td>\n</tr>\n";
			}

			if ( $this->isEditable( ) )
			{
				$html .= "<tr><td><center><input type=\"submit\" value=\" Lagre bookinger \"></center></td></tr>";
			}
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= "</table>\n";			
		}
		return $html;
	}


	# Check bookings for validity. Returns Booking_message object with error messages
	function checkBookingRules_vangen( $memberBookings, $guestBookings, $cancels )
	{
		$bookingSession = getBookingSession( );
		$user = $bookingSession->getUser( );
		$userid = $user->getDbColTrim( "userid" );

		$bookingErrors = new Booking_message;
		$bookingErrors->setHtmlPreSuffix( booking_error_html_prefix, booking_error_html_suffix );
		$member = new Booking_user( );
		$room1 = new Booking_resource( );
		$room2 = new Booking_resource( );
		$ownbookingfound = FALSE;

		# Check for valid membership numbers
		if ( is_array( $memberBookings ) )
		{
			while ( list( $key, $booking ) = each( $memberBookings )  )
			{
				if ( !$member->getValidMember( $booking->getDbCol( "useridbooked" ) ) )
				{
					$room1->getResource( $booking->getDbCol( "arrangementid" ), $booking->getDbCol( "resourceid" ) );

					$errormsg = "Medlemsnummer: " .$booking->getDbCol( "useridbooked" ) ." på "
					.$room1->getDbCol( "name" ) ." plass "
					.$memberBookings[ $key ]->getDbCol( "placenum" ) ." er ugyldig.";
					$bookingErrors->addMessage( $errormsg );
				}
				else
				{
					$ownbookingfound = ( $ownbookingfound || ( $userid == $booking->getDbColTrim( "useridbooked" ) ) );
				}
			}
		}

		# If no errors present check for multiple bookings of the same member
		if ( $bookingErrors->isEmpty( ) )
		{
			if ( is_array( $memberBookings ) )
			{
				for ( $i = 0; $i < count( $memberBookings ); $i++ )
				{
					for ( $j = $i+1; $j < count( $memberBookings ); $j++ )
					{
						if (  $memberBookings[ $i ]->getDbCol( "useridbooked" ) == $memberBookings[ $j ]->getDbCol( "useridbooked" )  )
						{
							$room1->getResource( $memberBookings[ $i ]->getDbCol( "arrangementid" ), $memberBookings[ $i ]->getDbCol( "resourceid" ) );
							$room2->getResource( $memberBookings[ $j ]->getDbCol( "arrangementid" ), $memberBookings[ $j ]->getDbCol( "resourceid" ) );

							$errormsg = "Medlemsnummer: " .$memberBookings[ $i ]->getDbCol( "useridbooked" ) ." er forsøkt booket flere ganger innenfor perioden. "
							.$room1->getDbColTrim( "name" ) ." plass " .$memberBookings[ $i ]->getDbCol( "placenum" ) ." og "
							.$room2->getDbColTrim( "name" ) ." plass " .$memberBookings[ $j ]->getDbCol( "placenum" ) .".";
							$bookingErrors->addMessage( $errormsg );
						}
					}
				}
			}
		}


		# Check of guest bookings
		if ( $bookingErrors->isEmpty( ) && is_array( $guestBookings ) )
		{
			# Create array with members that are not bound by the guest booking rules
			eval( "\$noGuestLimitations = array( " .Booking_noGuestLimitations ." );" );

			# Check if guest booking limitations should apply
			if ( !array_key_exists( $userid, $noGuestLimitations ) )
			{
				# Check for guest bookings without own booking
				if ( !$ownbookingfound )
				{
					$errormsg = "Du kan ikke booke gjester (booking hvor medlemsnummer ikke er registrert) uten å bo på Vangen selv i samme periode.";
					$bookingErrors->addMessage( $errormsg );
				}

				# Check for too many guest bookings
				if ( $bookingErrors->isEmpty( ) && ( count( $guestBookings ) > Vangen_max_guest_bookings ) )
				{
					$errormsg = " Du har booket for mange gjester (booking hvor medlemsnummer ikke er registrert). Maks antall gjester er " .Vangen_max_guest_bookings .".";
					# $errormsg .= "<br>\n" . count( $memberBookings ) ."-" .count( $guestBookings ) ."-" .count( $cancels );
					$bookingErrors->addMessage( $errormsg );
				}
			}
		}


		return( $bookingErrors );
	}


	# Stores bookings in database checking for doublebookins. Returns Booking_message object with error messages
	function storeBookings_vangen( $memberBookings, $guestBookings, $cancels )
	{

		$bookingErrors = new Booking_message;
		$bookingErrors->setHtmlPreSuffix( booking_error_html_prefix, booking_error_html_suffix );
		$room = new Booking_resource( );
		$bookinglists = array( "\$cancels", "\$memberBookings", "\$guestBookings" );

		# Traverse all booking arrays
		while (  ( list( $key, $bookinglistname ) = each( $bookinglists )  ) && ( $bookingErrors->isEmpty( ) ) )
		{
			# Select booking array
			eval( "\$bookings = $bookinglistname;" );

			if ( is_array( $bookings ) )
			{
				# Store all bookings in array checking for doublebookings
				while  ( list( $key, $booking ) = each( $bookings )  )
				{
					# Add name of member booking if it is missing
					if (  ( !$booking->isGuestBooking( ) ) &&  ( trim( $booking->getDbColTrim( "fullname" ) ) == "" )  )
					{
						$member = new Booking_user( );
						$member->get( $booking->getDbCol( "useridbooked" ) );
						$booking->setNewValue( "fullname", $member->getDbCol( "fullname" ) );
					}

					if ( !$booking->store( ) )
					{
						$room->getResource( $booking->getDbCol( "arrangementid" ), $booking->getDbCol( "resourceid" ) );
						$errormsg = "Forsøk på dobbeltbooking på "
						.$room->getDbCol( "name" ) ." plass " .$booking->getDbCol( "placenum" ) .". "
						." En annen har okkupert denne plassen mens du holdt på å booke.";
						$bookingErrors->addMessage( $errormsg );
					}
				}
			}
		}

		return( $bookingErrors );
	}



	# Handles HTML form data from previous page for member booking. Returns FALSE if there are errors, TRUE if not.
	function handleForm_vangen( )
	{
		global $bookingSession;
		$bookings = array( );
		$numbookings = array( );

		$html = htmlComment( "Booking_booking->formHandling_member( )" );

		$arrangementid = getFormVar( "arrangementid" );
		$resourceid = getFormVar( "resourceid" );
		$placenum = getFormVar( "placenum" );
		$bookingid = getFormVar( "bookingid" );
		$formatfunction = getFormVar( "formatfunction" );
		$fromtime = getFormVar( "fromtime" );
		$totime = getFormVar( "totime" );
		$olduseridbooked = getFormVar( "olduseridbooked" );
		$oldfullname = getFormVar( "oldfullname" );
		$useridbooked = getFormVar( "useridbooked" );
		$fullname = getFormVar( "fullname" );


		# Array with bookings of members
		$memberBookings = FALSE;
		# Array with guest bookings
		$guestBookings = FALSE;
		# Array with booking cancels
		$cancels = FALSE;

		$user = $bookingSession->getUser( );
		$userid = $user->getDbColTrim( "userid" );
		for ( $i=0; $i < count( $arrangementid ); $i++ )
		{

			# If something is filled in or has changed in the fields (includes canceling)
			if (      ( trim( $useridbooked[ $i ] ) != "" )
			      || ( trim( $fullname[ $i ] ) != "" )
			      || ( ( trim( $olduseridbooked[ $i ] ) != trim( $useridbooked[ $i ] ) ) && ( trim( $bookingid[ $i ] ) != "" ) )
			      || ( ( trim( $oldfullname[ $i ] ) != trim( $fullname[ $i ] ) ) && ( trim( $bookingid[ $i ] ) != "" ) )
			   )
			{
				# New booking
				if ( trim( $bookingid[ $i ] ) == "" )
				{
					$booking = new Booking_booking( );
					$booking->setNewValue( "arrangementid", $arrangementid[ $i ],  DB_coltype_integer );
					$booking->setNewValue( "resourceid", $resourceid[ $i ],  DB_coltype_integer );
					$booking->setNewValue( "placenum", $placenum[ $i ], DB_coltype_integer );
					$booking->setFormatFunction( $formatfunction[ $i ] );
					$booking->setNewValue( "useridbookie", $userid );
					$booking->setNewValue( "useridbooked", $useridbooked[ $i ] );
					$booking->setNewValue( "fullname", $fullname[ $i ] );
					$booking->setNewValue( "status", DB_booking_status_booking );
					$booking->setPeriod( $fromtime[ $i ], $totime[ $i ] );
				}
				# Existing booking
				else
				{
					$sql = "SELECT *"
					." FROM " .DB_tbl_booking_booking
					." WHERE arrangementid=" .$arrangementid[ $i ]
					." AND resourceid=" .$resourceid[ $i ]
					." AND id=" .$bookingid[ $i ];
					$booking = new Booking_booking( $sql );

					# Existing booking changed to blank.Cancel it.
					if ( ( trim( $useridbooked[ $i ] ) == "" ) && ( trim( $fullname[ $i ] == "" ) )  )
					{
						$booking->setNewValue( "useridbookie", $userid );
						$booking->setNewValue( "status", DB_booking_status_canceled );
					}
					# Change of existing booking
					else
					{
						$booking->setNewValue( "useridbooked", $useridbooked[ $i ] );
						$booking->setNewValue( "fullname", $fullname[ $i ] );
					}
				}

				# Place booking or cancel in correct category
				# Booking is canceled
				if ( $booking->getDbCol( "status" ) == DB_booking_status_canceled )
				{
					$cancels[ ] = $booking;
				}
				# Guest booking
				else if ( $booking->isGuestBooking( ) )
				{
					$guestBookings[ ] = $booking;
				}
				# Booking of member
				else
				{
					$memberBookings[ ] = $booking;
				}
			}
		}

		# Checks if any rules has been broken
		$bookingErrors = $this->checkBookingRules_vangen( $memberBookings, $guestBookings, $cancels );

		# No errors proceed storing bookings
		if ( $bookingErrors->isEmpty( ) )
		{
			$bookingErrors = $this->storeBookings_vangen( $memberBookings, $guestBookings, $cancels );
		}

		$this->setError( $bookingErrors );
		return( $bookingErrors->isEmpty( ) );
	}



}


?>
