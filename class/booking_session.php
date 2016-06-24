<?php

require_once ( "booking_constants.php" );
require_once ( "booking_basedb.php" );
require_once ( "booking_message.php" );
require_once ( "booking_user.php" );


class Booking_session extends Booking_basedb
{
	var $user=FALSE;

	function Booking_session( )
	{
		$this->Booking_basedb( DB_tbl_booking_session );
	}


	# Removes timed out sessions
	function removeOld( )
	{
		$sql = "DELETE FROM ". DB_tbl_booking_session
		." WHERE UNIX_TIMESTAMP(updatedtime) + ". Booking_session_timeout
		." < UNIX_TIMESTAMP()";
		dbSqlRows( $sql );
	}

	# Fetch session $sessionid from database. Returns TRUE if session is found, FALSE if not
	function get( $sessionid )
	{
		$this->removeOld( );
		if ( $sessionid != "" )
		{
			$sql = "SELECT * FROM ". DB_tbl_booking_session ." WHERE sessionid='$sessionid'";
			$this->Booking_basedb( DB_tbl_booking_session, $sql );
			if ( !$this->isEmpty( ) )
			{
				# Update time
				$this->store( );
				$this->user = new Booking_user( );
				$this->user->get( $this->getDbCol( "userid" ) );
				# setcookie( Booking_session_id, $sessionid );
				return( TRUE );
			}
			else return( FALSE );
		}
		else return( FALSE );
	}


	# Returns the session user
	function getUser( )
	{
		return( $this->user );
	}


  function create_session_id()
  {
    # seed the random number generator
    srand((double)microtime() * 1000000);
    # return the session id
    return md5(uniqid(rand()));
  }


	# Creates a session for user $userid. Returns FALSE if $userid/$password is not ok else returns TRUE
	function create( $userid, $password )
	{
		global $HTTP_HOST, $REQUEST_URI;

		$error = new Booking_message;
		$error->setHtmlPreSuffix( booking_error_html_prefix, booking_error_html_suffix );
		$sql = "SELECT * FROM " .DB_tbl_booking_user ." WHERE userid='$userid'";
		$user = new Booking_user( );

		if ( ( $row = dbSqlOneRow( $sql ) ) && $user->getValidMember( $userid ) )
		{
			# Create session id
			$sid = $this->create_session_id( );
			setcookie( Booking_session_id, $sid );
			$this->setNewValue( "sessionid", $sid );
			$this->setNewValue( "userid", $userid );
			$this->setNewValue( "passwordprovided", "no" );

			if ( $password != "" )
			{
				if ( md5( $password ) == $row[ "password" ] )
				{
					$this->setNewValue( "passwordprovided", "yes" );
					$this->user = new Booking_user( );
					$this->user->get( $userid );
					$this->store( );
				}
				else $error->addMessage( "Feil passord !" );
			}
			else
			{
				$this->user = new Booking_user( );
				$this->user->get( $userid );
				$this->store( );
			}
		}
		else $error->addMessage(  "Feil ! Ugyldig medlemsnummer." );
		$this->setError( $error );
		return( $error->isEmpty( ) );
	}

}

?>