<?php

# Includes content from a file
function get_include_contents( $filename ) 
{
	if (is_file($filename)) 
	{
		ob_start();
		include $filename;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	return false;
}


function get_random_number($from, $to)
{
  # seed the random number generator
  mt_srand((double)microtime() * 1000000);
  # return the random number
  return mt_rand($from, $to);
}



# Sets the language code used for the system.
function setLanguageCode( $langcode )
{
	global $languagecode;
	$languagecode = $langcode;
}


# Gets the present language code used for the system.
function getLanguageCode( )
{
	global $languagecode;
	return ( $languagecode );
}


# Create timestamp from date/time values
function createTimestamp( $year, $month, $day, $hour=0, $minute=0, $second=0 )
{
	return( mktime ( $hour, $minute, $second, $month, $day, $year ) );
}


function getFormatedDate( $timestamp )
{
	global $defaultDateformat;
	return date( $defaultDateformat, $timestamp );
}


function getFormatedDateShort( $timestamp )
{
	global $defaultDateformatShort;
	return date( $defaultDateformatShort, $timestamp );
}


# Converts database date/time to PHP timestamp
function dbDateToTimestamp( $dbDate, $dbDateFormat=DB_dateformat_mysql )
{
	$php_timestamp = "";
	if ( $dbDate != NULL)
	{
		switch( $dbDateFormat )
		{
			case DB_dateformat_mysql:
				// mysql date looks like "yyyy-mm-dd hh:mm:ss"
				$year = substr( $dbDate, 0, 4 );
				$month = substr( $dbDate, 5, 2 );
				$day = substr( $dbDate, 8, 2 );
				$hour = substr( $dbDate, 11, 2 );
				$min = substr( $dbDate, 14, 2 );
				$sec = substr( $dbDate, 17, 2 );
				$php_timestamp = mktime( $hour, $min, $sec, $month, $day, $year );
		}
	}

	return $php_timestamp;
}


# Converts a timestamp to a database date
function timestampToDbDate( $timestamp )
{
	return( date( DB_dateformat, $timestamp ) );
}

# Get timestamp fromdate from the period the user has selected
function getPeriodFrom( )
{
	$hour = Booking_fromhour;
	$min = Booking_fromminutes;
	$sec = Booking_fromseconds;
	$month = getFormVar( "frommonth" );
	$day = getFormVar( "fromday" );
	$year = getFormVar( "fromyear" );
	return( mktime( $hour, $min, $sec, $month, $day, $year ) );
}

# Get timestamp todate from the period the user has selected
function getPeriodTo( )
{
	$hour = Booking_tohour;
	$min = Booking_tominutes;
	$sec = Booking_toseconds;
	$month = getFormVar( "tomonth" );
	$day = getFormVar( "today" );
	$year = getFormVar( "toyear" );
	return( mktime( $hour, $min, $sec, $month, $day, $year ) );
}

# Returns hidden HTML form fields representing selected period
function getPeriodHiddenInput( )
{
	$html = "<input type=\"hidden\" name=\"fromyear\" value=\"" .getFormVar( "fromyear" ) ."\">";
	$html .= "\n<input type=\"hidden\" name=\"frommonth\" value=\"" .getFormVar( "frommonth" ) ."\">";
	$html .= "\n<input type=\"hidden\" name=\"fromday\" value=\"" .getFormVar( "fromday" ) ."\">";
	$html .= "\n<input type=\"hidden\" name=\"toyear\" value=\"" .getFormVar( "toyear" ) ."\">";
	$html .= "\n<input type=\"hidden\" name=\"tomonth\" value=\"" .getFormVar( "tomonth" ) ."\">";
	$html .= "\n<input type=\"hidden\" name=\"today\" value=\"" .getFormVar( "today" ) ."\">\n";
	return $html;
}


# Returns $message inside and HTML comment
function htmlComment( $message )
{
	return "\n<!--\n$message\n-->\n";
}


# Returns database name
function dbGetName( )
{
	$configFile = PHPWS_SOURCE_DIR . "conf/config.php";
  if( $configFile && file_exists( $configFile ) ) require( $configFile ); 
  return $dbname;
}


# Returns database connection link
function dbGetLink( )
{
	global $g_db_con_write;

	if ( !$g_db_con_write )
	{
		$configFile = PHPWS_SOURCE_DIR . "conf/config.php";
  	if( $configFile && file_exists( $configFile ) ) require( $configFile );
  	
		$g_db_con_write = mysql_pconnect($dbhost, $dbuser, $dbpass) or die("Could not connect to: " .$dbhost);
  	mysql_select_db($dbname, $g_db_con_write);
	}	
	return $g_db_con_write;
}


# Perform SQL statement in database.
function dbSqlRows( $sql )
{
  $con = dbGetLink( );
  $res = mysql_query($sql, $con);
  if(!$res) {
    $errno = mysql_errno($con);
    $errmsg = mysql_error($con);
    die("$errno $errmsg<br>$sql");
  }
  return $res;
}


# Get one row from database
function dbSqlOneRow( $sql )
{
  $res = dbSqlRows($sql);
  if($res)
  {
    $row = mysql_fetch_array($res);
  }
  return $row;
}


# Fetch a result row as an associative array
function dbFetchAssoc( $result )
{
	return( mysql_fetch_assoc( $result ) );
}


# Run INSERT query and return id
function dbSqlInsert( $sql )
{
	$ok = dbSqlRows ( $sql );
	return( mysql_insert_id ( dbGetLink( ) ) );
}

# Returns the number of rows in $result.
function dbNumRows( $result )
{
	return( mysql_num_rows( $result ) ) ;
}

# Move internal result pointer
function dbDataSeek( &$result, $rownum )
{
	return( mysql_data_seek( $result, $rownum ) );
}

# Free result memory
function dbFreeResult( $result )
{
	return( mysql_free_result ( $result ) );
}

function logedOnUser( )
{
	global $bookingSession;

	# return( $_POST["userid"] );
	$user = $bookingSession->getUser( );
	$userid = "";
	if ( $user )
	{
		$userid = $user->getDbCol( "userid" );
	}

	return( $userid );
}


# Returns session for logged on user
function getBookingSession( )
{
	global $bookingSession;
	return( $bookingSession );
}


# Return action set on HTML form on calling page
function getFormAction( )
{
  if ( array_key_exists( Form_action_varname, $_REQUEST ) )  
	  return( $_REQUEST[Form_action_varname] );
	else return ( NULL );
}

# Return HTML formvariable $varname
function getFormVar( $varname )
{
  if ( array_key_exists( $varname, $_REQUEST ) )
	  return( $_REQUEST[$varname] );
	else return ( NULL );
}


# Return HTML cookie $varname
function getCookie( $varname )
{
  if ( array_key_exists( $varname, $_COOKIE ) )
	  return( $_COOKIE[$varname] );
	else return ( NULL );  	
}

# Sets global counter that can be used for anything
function setBookingCounter( $value )
{
	global $bookingCounter;

	$bookingCounter = $value;
}

# Gets global counter that can be used for anything
function getBookingCounter( )
{
	global $bookingCounter;

	return( $bookingCounter );
}

?>