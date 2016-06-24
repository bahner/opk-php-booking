<?php
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" );
require_once ( "booking_basedb.php" );


class Booking_booking extends Booking_basedb
{
	# Constructor. $sql_dbrecord is either a SQL statement (string) or a database record (array).
	function Booking_booking( $sql_dbrecord = NULL )
	{
		# echo htmlComment( Booking_booking( $sql_dbrecord ) );
		$this->Booking_basedb( DB_tbl_booking_booking, $sql_dbrecord );
	}


   	# Returns CSS (Cascading Style Sheets) class of this resource
   	function getCssClass()
   	{
   		$cssclass = $this->getDbCol( "cssclass" );
   		if ( $cssclass != "" )
   		{
   			return ( " class=\"$cssclass\"" );
   		}
   		else
   		{
   			return ( "" );
   		}
   	}


	# Returns placenumber of this booking.
	function getPlaceNum( )
	{
		return $this->getDbCol( "placenum" );
	}


	# Sets the period the booking is valid for. $fromtime and $totime should be timestamps
	function setPeriod( $fromtime, $totime )
	{
		$this->setNewValue( "fromtime", $fromtime,  DB_coltype_time );
		$this->setNewValue( "totime", $totime,  DB_coltype_time );
	}


	# Returns TRUE if this is a guest booking
	function isGuestBooking( )
	{
		return( trim( $this->getDbCol( "useridbooked" ) ) == "" );
	}

	# Returns period if it does not match the one currently selected
	function periodMatch( $fromtime, $totime )
	{
		if (  ( $fromtime != getPeriodFrom( ) ) || ( $totime != getPeriodTo( ) )  )
		{
			$fromtime = getFormatedDateShort( $fromtime );
			$totime = getFormatedDateShort( $totime );
		 	return( "$fromtime-$totime" );
		 }
		 else
		 {
		 	return "";
		 }
	}


  # Checks filled out name in booking and replace if missing first/lastname. Add name of host on guest bookings
  function fixName( )
  {
    $fullname = $this->getDbColTrim( "fullname" );
    $name = $fullname;
    
    if ( !$this->isGuestBooking( ) ) 
    {
    
      # Split name string removing repeated commas and spacing
      $fullnames = preg_split("/[\s,]+/", $fullname );
      
      $member = new Booking_user( );
      $member->get( $this->getDbCol( "useridbooked" ) );
      $realname = $member->getDbColTrim( "fullname" );      
      $name = $realname;
      
      if ( $fullname != "" ) # Check for blank name
      {
    
        $realnames = preg_split( "/[\s,]+/", $realname );
        if ( $realname != "" )   # Avoid blank real name. Should not happen
        { # Compare all filled out names with all real names
          $match = 0;
          foreach ( $realnames as $rname )
          {
            # $bestmatch[ $rname ] = 0;
            foreach ( $fullnames as $fname )
            {
              $match++;
              $similarity = similar_text( $rname, $fname );
              
              if ( $similarity < strlen( $rname ) - 1 )
              {
                $match--;
              }
            }
          }
        }
        if ( $match > 1 )
        {
          $name = $fullname;
        }
      }
    }
    else # Guest booking
    {
        $useridbookie = $this->getDbCol( "useridbookie" );
				
				# Create array with members that are not bound by the guest booking rules
				eval( "\$noGuestLimitations = array( " .Booking_noGuestLimitations ." );" );

				# Add name of host only if booking has been made by normal user
				if ( !array_key_exists( $useridbookie, $noGuestLimitations ) )
				{
          $member = new Booking_user( );
          $member->get( $useridbookie );
          $name .= " (Vert: " .$member->getDbColTrim( "fullname" ) .")";            
        }
    }
    return( $name );
  }


	# Extends Booking_basedb->store( ) function to prevent double bookings. Returns TRUE if booking is ok, FALSE if booking was cancelled as doublebooking
	function store( )
	{
		# echo htmlComment( "Booking_booking->store( )" );

		# Insert new booking and prevent doublebooking
		$thisbookingcanceled = FALSE;
		if ( $this->isNew( ) )
		{
			parent::store( );
			$id = $this->getId( );

			$sql = "SELECT id FROM " .$this->getTableName( )
			." WHERE arrangementid=" .$this->getDbCol( "arrangementid", TRUE )
			." AND resourceid=" .$this->getDbCol( "resourceid", TRUE )
			." AND placenum=" .$this->getDbCol( "placenum", TRUE )
			." AND status='". DB_booking_status_booking ."'"
			." AND fromtime < '" . timestampToDbDate( $this->getDbCol( "totime", TRUE ) ) ."'"
			." AND totime > '" . timestampToDbDate( $this->getDbCol( "fromtime", TRUE ) ) ."'"
			." ORDER BY id DESC";
			$result = dbSqlRows( $sql );
			$numrows = dbNumRows( $result );

			$thisbookingcanceled = FALSE;
			# We have double bookings. Cancel all but one on a first come first serve basis.
			if ( $numrows > 1 )
			{
				$cancellist = "";
				for ( $i = 1; $i < $numrows; $i++ )
				{
    					$row = dbFetchAssoc( $result );
					$cancellist .= ( $cancellist == "" ? "" : ", " ) .$row[ "id" ];
					$thisbookingcanceled = ( $thisbookingcanceled || ( trim( $id) == trim( $row[ "id" ] ) ) );
				}
				$sql = "UPDATE " .$this->getTableName( ) ." SET status='" .DB_booking_status_doublebookingcanceled ."'"
				." WHERE id IN (" .$cancellist .")";
				dbSqlRows( $sql );

				# This booking was canceled.
				if ( $thisbookingcanceled )
				{
					$this->setNewValue( "status", DB_booking_status_doublebookingcanceled );
				}
			}
			dbFreeResult( $result );
		}
		# Existing booking
		else
		{
			parent::store( );
		}
		return( !$thisbookingcanceled );
	}



	# Returns  HTML representation of booking with member number, name and period.
	function formatfunction_member( )
	{
		global $bookingSession;

		# $html = htmlComment( get_class ( $this ) ."->formatfunction_member( )" );
		$viewmode = $this->getViewMode( );
		# Show booking as editable if set to editable and ( loged on user made the booking OR loged on user is the one booked OR no booking is present (empty) ).
		$html = "";
		$cssclass = "";
		if (  $this->isEditable( ) && (  ( $this->getDbCol( "useridbookie" ) == logedOnUser( ) )  || ( $this->getDbCol( "useridbooked" ) == logedOnUser( ) )  || $this->isEmpty( ) )  )
		{
		  $cssclass = $this->getCssClass();
			$html .= "  <tr$cssclass>\n";
			$html .= "    <td$cssclass nowrap>\n";
			$html .= "      <input type=\"hidden\" name=\"arrangementid[]\" value=\"" .$this->getDbCol( "arrangementid" ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"resourceid[]\" value=\"" .$this->getDbCol( "resourceid" ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"placenum[]\" value=\"" .$this->getPlaceNum( ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"bookingid[]\" value=\"" .$this->getId( ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"formatfunction[]\" value=\"" .$this->getFormatFunction( TRUE ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"fromtime[]\" value=\"" .$this->getDbCol( "fromtime" ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"totime[]\" value=\"" .$this->getDbCol( "totime" ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"olduseridbooked[]\" value=\"" .$this->getDbCol( "useridbooked" ) ."\">\n";
			$html .= "      <input type=\"hidden\" name=\"oldfullname[]\" value=\"" .$this->getDbCol( "fullname" ) ."\">\n";

			# Show placenumber as quick choiche link for filling in membership number and name
			$index = getBookingCounter( );
			$formname = "document." .Booking_form_name;
			$html .= "      <a href=\"javascript:quickChoiceBooking( $formname.useridbookie.value, $formname" ."['useridbooked[]'][" .$index ."], $formname.username.value, $formname" ."['fullname[]'][" .$index ."] );\">Plass " .$this->getPlaceNum( ) ."</a>\n";
			setBookingCounter( $index + 1 );

			$html .= "    </td>\n";

			# Show membership number
			$html .= "    <td$cssclass align=\"left\" nowrap>\n";
			$html .= "      <input type=\"text\" name=\"useridbooked[]\" size=\"5\" maxlength=\"6\" value=\"" .$this->getDbCol( "useridbooked" ) . "\">\n";
			$html .= "    </td>\n";

			# Show full name
			$html .= "    <td$cssclass align=\"left\" nowrap>\n";
			$html .= "      <input type=\"text\" name=\"fullname[]\" size=\"17\" maxlength=\"40\" value=\"" .$this->getDbCol( "fullname" ) ."\">";

			# Display period if different from the one currently selected
			$html .= $this->periodMatch($this->getDbCol( "fromtime" ) , $this->getDbCol( "totime" ) ) . "\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
		}
		# It's not editable but present. Show static view.
		# elseif ( !$this->isEmpty( ) )
		else
		{
			$tdstyle = ( $viewmode == Booking_printlist ? "style=\"border-bottom: thin solid\"" : "" );

			$html .= "  <tr$cssclass>\n";
			$html .= "    <td$cssclass $tdstyle nowrap>\n";
			if ( $viewmode != Booking_printlist )
			{
				$html .= "      Plass ";
			}
			$html .= $this->getPlaceNum( ) ."\n";
			$html .= "    </td>\n";

			# Show * instead of membership number, space if none
			if (  $this->isEditable( ) )
			{
				$shownum = "";
				# Show * if member booking
				if ( $this->getDbColTrim( "useridbooked" ) != "" ) $shownum="*";

				# if ShowMembershipNumbers defined in booking_constants.php is true, show membership number for selected users
				if ( ShowMembershipNumbers )
				{
					# Create array with members that are not bound by the guest booking rules
					eval( "\$noGuestLimitations = array( " .Booking_noGuestLimitations ." );" );
					# Show member number if logged on user is not bound by standard booking rules
					if ( array_key_exists( logedOnUser( ), $noGuestLimitations ) )
					{
						$shownum = $this->getDbColTrim( "useridbooked" );
					}
				}

				$html .= "    <td$cssclass $tdstyle nowrap><center>$shownum</center></td>\n";
			}
			$html .= "    <td$cssclass $tdstyle width=\"100%\" nowrap>\n";

			# Show full name and period
			if ( $this->getDbCol( "useridbookie" ) != "")
			{
        # Adjust name
        $fullname = $this->fixName( );
        
				if ( $viewmode != Booking_printlist )
				{
					$email = $this->getDbCol( "email" );

					$phonemobile = $this->getDbCol( "phonemobile" );
					$phoneprivate = $this->getDbCol( "phoneprivate" );

					# Show mobile phone number if present
					if ( trim( $phonemobile ) != "" )
					{
						$html .= "      <img src=\"mobile_small.gif\" alt=\"Tlf. mobil: $phonemobile\">";
					}
					# Show private phone number if present
					else if ( trim( $phoneprivate ) != "" )
					{
						$html .= "      <img src=\"mobile_small.gif\" alt=\"Tlf. privat: $phonemobile\">";
					}

					# Email link if email address is present
					if ( $email != "" )
					{
						$html .= " <a href=\"mailto:$email\" alt=\"$phonemobile\">$fullname</a>";
					}
					# Show name of occupant
					else
					{
						$html .= "      " .$fullname;
					}
				}
				else
				{
					$html .= "      " .$fullname;
				}
				# Display period if different from the one currently selected
				$html .= "&nbsp;&nbsp;" .$this->periodMatch( $this->getDbCol( "fromtime" ), $this->getDbCol( "totime" ) ) ."\n";
			}
			else
			{
				$html .= "&nbsp;";
			}

			$html .= "    </td>\n";
			# Add column for payment
			if ( $viewmode == Booking_printlist )
			{
				$html .= "    <td$cssclass style=\"border-bottom: thin solid; border-left: thin solid\" nowrap>&nbsp;</td>\n";
			}
			$html .= "  </tr>\n";
		}
		return ( $html );
	}

}



?>
