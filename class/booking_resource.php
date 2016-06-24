<?php
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" );
require_once ( "booking_basedb.php" );
require_once ( "booking_freevalue.php" );
require_once ( "booking_booking.php" );


class Booking_resource extends Booking_basedb
{
	# Contains the child resources of this resource.
	var $children = NULL;

	# Constructor. $sql_dbrecord is either a SQL statement (string) or a database record (array).
	function Booking_resource( $sql_dbrecord = NULL )
	{
		$fromtime = getPeriodFrom( );
		$totime = getPeriodTo( );

		# echo "\n<!--\nBooking_resource( $sql_dbrecord  );\n--> \n";
		if ($sql_dbrecord != NULL)
		{
			$this->Booking_basedb(DB_tbl_booking_resource, $sql_dbrecord );
			if  ( !$this->isleaf () )
			{
				$sql = "SELECT * FROM ". DB_tbl_booking_resource
				." WHERE arrangementid=" .$this->getDbCol( "arrangementid" ) ." AND parentresourceid=". $this->getId( )  ."\n"
				." AND '" .timestampToDbDate( $fromtime ) ."' >= fromtime\n"
				." AND ( ( totime IS NULL ) OR ( '" .timestampToDbDate( $totime ) ."' <= totime ) )\n"
				." ORDER BY row, col";
				# echo "\n<!--\n $sql \n-->\n";
				$this->children = dbSqlRows ( $sql );
			}
		}
	}


	# Gets resource with given $arrangementid and $resourceid
	function getResource( $arrangementid, $resourceid )
	{
		$sql = "SELECT * FROM " .DB_tbl_booking_resource
		." WHERE arrangementid=$arrangementid AND id=$resourceid";
		$this->Booking_resource( $sql );
	}


	# Returns true if this resource is a leaf (no children). Returns false if it is a branch (has children)
	function isLeaf( )
	{
		return( $this->getDbCol( "isleaf" ) == 'y' );
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


	# Returns a two dimmensional array of rows and columns with the children of this resource
	function getChildrenRowCol( )
	{
		$childArray = array( );
		$previousRownum = 0;
		$ok = mysql_data_seek( $this->children, 0);
		$numRows = mysql_num_rows( $this->children );

		for ( $i=0; $i<$numRows; $i++ )
		{
			$childResource = new Booking_resource( $this->children );
			$childResource->setEditable( $this->isEditable( ) );
			$childResource->setViewMode( $this->getViewMode( ) );
			$rownum = $childResource->getDbCol( "row" );
			$colnum = $childResource->getDbCol( "col" );
			$childArray[$rownum][$colnum] = $childResource;
		}
		return ( $childArray );
	}


	# Assign a housekeeper to Vangen for weeked
	function getHousekeeper( $fromtime=0, $totime=0 )
	{
		$html = "";
		$html .= htmlComment( "booking_resource->getHousekeeper( )" );

		$periodfrom = getPeriodFrom( );
		$periodto = getPeriodTo( );
		$today = getdate( );
		$currenttime = time( );

		# Set spesific date and time. For testing only.
		# $today = getdate( createTimestamp( 2003, 3, 1 ) );
		# $currenttime = strtotime ("last Saturday");

		$wday = $today[ "wday" ];
		$hours = $today[ "hours" ];
		$viewmode = $this->getViewMode( );

		# Assign housekeeper if time has passed 16:00 on friday in current week. ( Sunday is day 0 )
		if (   ( $viewmode == Booking_printlist ) &&
			(  ( $currenttime >= $periodfrom ) && ( $currenttime <= $periodto )  ) &&
			(  ( ( $wday == 5 ) && ( $hours >= 16 ) ) || ( $wday > 5 ) || ( $wday == 0 )  )
		   )
		{
			$year = $today[ "year" ];
			$month = $today[ "mon" ];
			$mday = $today[ "mday" ];

			# Weekday is sunday
			if ( $wday == 0 )
			{
				$daystoadd = -2;
			}
			else
			{
				# 5 is friday
				$daystoadd = 5 - $wday;
			}

			if ( $fromtime == 0 )
			{
				$fromtime = createTimestamp( $year, $month, $mday + $daystoadd, Booking_fromhour, Booking_fromminutes, Booking_fromseconds );
			}
			if ( $totime == 0 )
			{
				$totime = createTimestamp( $year, $month, $mday + $daystoadd + 2, Booking_tohour, Booking_tominutes, Booking_toseconds );
			}

			# Search for already assigned housekeeper
			$sql = "SELECT * FROM ". DB_tbl_booking_freevalue ."\n"
			." WHERE foreigntable='" .DB_tbl_booking_resource ."'\n"
			." AND foreignkey='". $this->getId( ) ."'\n"
			." AND status='active'"
			." AND fieldname = 'housekeeper'\n"
			." AND fromtime <= '" .timestampToDbDate( $totime ) ."'\n"
			." AND totime >= '" .timestampToDbDate( $fromtime ) ."'\n";
			#$html .= htmlComment( $sql );

			$housekeeper = new Booking_freevalue( $sql );

			# No existing housekeeper found. Assign one.
			if ( $housekeeper->isEmpty( ) )
			{
				# Get existing bookings for the period.
				$sql = "SELECT useridbooked, fullname\n"
				." FROM ". DB_tbl_booking_booking ."\n"
				." WHERE arrangementid=" .$this->getDbCol( "arrangementid" ) ."\n"
				." AND status = '" .DB_booking_status_booking ."'\n"
				." AND useridbooked != ''\n"
				." AND fromtime <= '" .timestampToDbDate( $totime ) ."'\n"
				." AND totime > '" .timestampToDbDate( $fromtime ) ."'\n";
				#$html .= htmlComment( $sql );

				$rows = dbSqlRows( $sql );
				$numrows = dbNumRows( $rows );
				#$html .= htmlComment( "Rows: $numrows" );
				if ( $numrows > 0 )
				{
					$rownum = get_random_number( 0, ( $numrows - 1 ) );
					dbDataSeek( $rows, $rownum );
					$booking = new Booking_booking( $rows );
					$housekeeper->setNewValue( "foreigntable", DB_tbl_booking_resource );
					$housekeeper->setNewValue( "foreignkey", $this->getId( ) );
					$housekeeper->setNewValue( "status", "active" );
					$housekeeper->setNewValue( "fieldname", "housekeeper" );
					$housekeeper->setNewValue( "fromtime", $fromtime, DB_coltype_time );
					$housekeeper->setNewValue( "totime", $totime, DB_coltype_time );
					$useridbooked = $booking->getDbCol( "useridbooked" );
					$housekeeper->setNewValue( "fieldval", $useridbooked );
					$fullname = $booking->getDbColTrim( "fullname" );
					if ( $fullname == "" )
					{
						$user = new Booking_user( );
						$user->get( $useridbooked );
						$fullname = $user->getDbColTrim( "fullname" );
					}
					$housekeeper->setNewValue( "fieldval2", $fullname );
					$housekeeper->store( );
				}
			}
			$html .= "<center><font size=\"+1\">Husansvarlig: <b>" .$housekeeper->getDbCol( "fieldval2" ) ."</b></font></center><br>\n";
		}
		else if ( $viewmode == Booking_printlist )
		{
			$html .= "<table width=\"500\" border=\"0\">\n";
			$html .= "<tr><td><font size=\"1\">Husansvarlig tildeles automatisk av systemet ved trekning hver fredag etter kl. 16:00 for helgen. For andre perioder (midtuke osv.) må dere som bor på Vangen selv utpeke en </font> Husansvarlig:</td><tr>";
			$html .= "</table>\n";
		}
		return( $html );
	}



	# Returns a visual representation of Vangen (a house).
	function formatfunction_vangen ( )
	{
		global $bookingSession;

		# echo "Booking_resource->formatfunction_vangen( )<br>\n ";

		$cssclass = $this->getCssClass( );

		$html = htmlComment( "formatfunction_vangen( )" );

		$html .= $this->getHousekeeper( );
		$html .= "<input type=\"hidden\" name=\"" .Form_action_varname ."\" value=\"" .$this->getFormAction( ) ."\">\n";
		$html .= getPeriodHiddenInput( );

		$user = $bookingSession->getUser( );
		$userid = $user->getDbCol( "userid" );
		$username = $user->getDbCol( "fullname" );
		$html .= "<input type=\"hidden\" name=\"useridbookie\" value=\"$userid\">\n";
		$html .= "<input type=\"hidden\" name=\"username\" value=\"$username\">\n";

		# $html .=  "<table$cssclass width=\"500\" style=\"border: thin solid\" border=\"1\">\n";
		$html .=  "<table$cssclass width=\"500\" style=\"border: none\">\n";

		# Get two dimensonal array (rows and columns) with this resource's children (rooms)
		$childArray = $this->getChildrenRowCol( );
		
		$maxrownum = $this->getDbCol( "rows" );
		if ( $maxrownum == 0 )
		{
			$lastrow = end( $childArray );
			$lastcol = end( $lastrow );
			$maxrownum = $lastcol->getDbCol( "row" );
		}
		$maxcolnum = $this->getDbCol( "cols" );

		$rownum = 0;
		while ( ++$rownum <= $maxrownum )
		{

			if ( array_key_exists( "$rownum", $childArray ) )
			{
				$row = $childArray["$rownum"];
			}
			else
			{
				$row = NULL;
			}
			$html .= "<tr$cssclass>\n";

			$colnum = 0;
			while ( ++$colnum <= $maxcolnum )
			{
				if (   ( $row != NULL ) &&  ( array_key_exists( "$colnum", $row )  )    )
				{
					$col = $row["$colnum"];
					$html .= "  <td$cssclass valign=\"top\" width=\"250\"";
					$rowspan = $col->getDbCol( "rowspan" );
					$colspan = $col->getDbCol( "colspan" );

					if ( $rowspan > 1 )
					{
						$html .= " rowspan=\"$rowspan\"";
					}

					if ( $colspan > 1 )
					{
						$html .= " colspan=\"$colspan\"";
						$colnum += $colspan - 1;
					}
					$html .= ">\n";

					$html .= $col->toHtml( );

					$html .= "\n </td>\n";
				}
				else
				{
					$html .= "<td$cssclass>&nbsp;</td>\n";
				}
			}
			$html .= "</tr>\n";
		}
		$html .= "</table>\n";

		return $html;
	}


	# Get value from form on previous page. Returns value if found, FALSE if not.
	function getFormValue( $arrangementid, $resourceid, $placenum, $varname )
	{
		$arrangementids = getFormVar( "arrangementid" );
		$resourceids = getFormVar( "resourceid" );
		$placenums = getFormVar( "placenum" );
		$values = getFormVar( $varname );
		$value = FALSE;

		if (  is_array( $arrangementids ) && is_array( $resourceids ) && is_array( $placenums ) && is_array( $values )  )
		{
			$i = 0;
			while (  ( $i < count( $arrangementids ) ) && !$value  )
			{
				if (  	( trim( $arrangementids[ $i ] ) == trim( $arrangementid ) )
					&& ( trim( $resourceids[ $i ] ) == trim( $resourceid ) )
					&& ( trim( $placenums[ $i ]) == trim( $placenum ) )
				)
				{
					$value = $values[ $i ];
				}
				$i++;
			}
		}
		return( $value );
	}


	# Create an empty booking
	function createEmptyBooking( $placenum )
	{
		$newBooking = new Booking_booking( );
		$newBooking->setFormatFunction( "member" );
		$newBooking->setNewValue( "arrangementid", $this->getDbCol( "arrangementid" ),  DB_coltype_integer );
		$newBooking->setNewValue( "resourceid", $this->getId( ),  DB_coltype_integer );
		$newBooking->setNewValue( "placenum", $placenum, DB_coltype_integer );
		$newBooking->setPeriod( getPeriodFrom( ), getPeriodTo( ) );

		# Set booked userid from form on previous page if present
		if ( $formuseridbooked = $this->getFormValue( $this->getDbCol( "arrangementid" ), $this->getId( ), $placenum, "useridbooked" ) )
		{
			$newBooking->setNewValue( "useridbooked", $formuseridbooked );
		}

		# Set booked fullname from form on previous page if present
		if ( $formfullname = $this->getFormValue( $this->getDbCol( "arrangementid" ), $this->getId( ), $placenum, "fullname" ) )
		{
			$newBooking->setNewValue( "fullname", $formfullname );
		}
		$newBooking->setEditable( $this->isEditable( ) );
		$newBooking->setViewMode( $this->getViewMode( ) );
		return( $newBooking );
	}



	# Returns a visual representation of a room
	function formatfunction_room ( )
	{
		$html = htmlComment( "booking_resource->formatfunction_room( )" );

		$fromtime = getPeriodFrom( );
		$totime = getPeriodTo( );
		$viewmode = $this->getViewMode( );

		if (  ( $viewmode != Booking_printlist )  || ( $this->getDbCol( "places" ) > 0 )  )
		{

			# Get existing bookings for the period.

			$sql = "SELECT book.*, user.email, user.phonemobile\n"
			." FROM ". DB_tbl_booking_booking ." AS book\n"
			." LEFT JOIN ". DB_tbl_booking_user_import ." AS user ON book.useridbooked=user.userid"
			." WHERE book.arrangementid=" .$this->getDbCol( "arrangementid" ) ."\n"
			." AND book.resourceid=". $this->getId( ) ."\n"
			." AND book.status = '" .DB_booking_status_booking ."'\n"
			." AND book.fromtime <= '" .timestampToDbDate( $totime ) ."'\n"
			." AND book.totime > '" .timestampToDbDate( $fromtime ) ."'\n"
			." ORDER BY book.placenum, book.fromtime\n";

			# $html .= htmlComment( $sql );
			$rows = dbSqlRows( $sql );

			# Show resource name
			$cssclass = $this->getCssClass( );
			$border = ( $viewmode == Booking_printlist ? "thin solid black" : "none" );
			$tablewidth = ( $viewmode == Booking_printlist ? "width=\"100%\"" : "" );
			$html .=  "<table$cssclass $tablewidth cellpadding=\"1\" cellspacing=\"1\" style=\"border: $border\">\n";
			$html .= "  <tr$cssclass>\n";
			$name = $this->getDbColTrim( "name" );

			$colspan = ( $viewmode == Booking_printlist ? "2" : "3" );
			$html .= "    <th$cssclass width=\"100%\" align=\"left\" colspan=\"$colspan\">$name</th>\n";

			if (  ( $viewmode == Booking_printlist )  && ( $this->getDbCol( "places" ) > 0)  )
			{
				$html .= "    <td$cssclass style=\"text-align: right\" nowrap><b>Klipp/kr</b></td>\n";
			}
			$html .= "  </tr>\n";

			# Show member number and name headlines
			if (  $this->isEditable( ) && ( $this->getDbCol( "places" ) > 0)  )
			{
				$html .= "  <tr$cssclass>\n";
				$html .= "    <th$cssclass>&nbsp;</th>";
				$html .= "<td$cssclass nowrap>Medlnr</td>";
				$html .= "<td$cssclass nowrap>Navn</td>\n";
				$html .= "  </tr>\n";
			}


			$places = $this->getDbCol( "places" );
			$id = $this->getId( );
			$rownum = 0;
			$numrows = mysql_num_rows( $rows );
			if ( $numrows > 0 )
			{
				$booking = new Booking_booking( $rows );
			}
			# Show places
			for ($i = 1; $i <= $places; $i++)
			{
				$placefound = FALSE;
				$moreplaces = TRUE;
				$previoustotime = 0;
				$currenttotime = 0;
				# display existing bookings for the period
				while (  ( $rownum < $numrows ) && $moreplaces  )
				{
					$moreplaces = FALSE;
					if ( $booking->getPlaceNum( ) == $i )
					{
						$previoustotime = $currenttotime;
						$currentfromtime = $booking->getDbCol( "fromtime" );
						$currenttotime = $booking->getDbCol( "totime" );

						# Add blank booking if there is a suitable hole between two other bookings
						if (  ( $previoustotime > 0 ) && ( $fromtime > $previoustotime ) && ( $totime < $currentfromtime ) )
						{
							$newBooking = $this->createEmptyBooking( $i );
							$html .= $newBooking->toHtml( );
						}

						$booking->setEditable( $this->isEditable( ) );
						$booking->setViewMode( $this->getViewMode( ) );
						$html .= $booking->toHtml( );
						$placefound = TRUE;
						$rownum++;
						if ( $rownum < $numrows )
						{
							$booking = new Booking_booking( $rows );
							$moreplaces =  ( $booking->getPlaceNum( ) == $i );
						}
					}
				}

				# No existing bookings found. Create empty place.
				if ( !$placefound )
				{
					$newBooking = $this->createEmptyBooking( $i );
					$html .= $newBooking->toHtml( );
				}
			}
			$html .= "  </td>\n";
			$html .= "  </tr>\n";

			# Show room bottom text
			$sql = "SELECT * FROM ". DB_tbl_booking_freevalue ."\n"
			." WHERE foreigntable='" .DB_tbl_booking_resource ."'\n"
			." AND foreignkey='". $this->getId( ) ."'\n"
			." AND status='active'"
			." AND fieldname = 'bottomtext'\n"
			." AND fromtime <= NOW()\n"
			." AND (  ( totime > NOW()) OR ( totime=0 )  )\n";
			# $html .= htmlComment( $sql );

			$bottomtext = new Booking_freevalue( $sql );
			if ( !$bottomtext->isEmpty( ) && ( $viewmode != Booking_printlist ) )
			{
				$html .= "  <tr$cssclass>\n";
				$html .= "    <td nowrap>&nbsp;</td><td$cssclass align=\"left\" colspan=\"2\">" .$bottomtext->toHtml() ."</td>\n";
				$html .= "  </tr>\n";
			}

			$html .= "</tr>\n";
			$html .= "</table>\n";
		}
		return $html;
	}
}



?>
