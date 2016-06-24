<?php

require_once ( "booking_constants.php" );
require_once ( "booking_basedb.php" );


class Booking_user extends Booking_basedb
{

	function Booking_user( )
	{
		$this->Booking_basedb( DB_tbl_booking_user_import );
	}


	# Fetch user with userid=$userid from database
	function get( $userid )
	{
		$sql = "SELECT imp.* FROM ". DB_tbl_booking_user_import ." AS imp"
		." INNER JOIN ". DB_tbl_booking_user ." AS usr ON imp.userid=usr.userid"
		." WHERE imp.userid='". $userid ."'";

		$this->Booking_basedb( DB_tbl_booking_user_import, $sql );
	}


	# Fetch user with userid=$userid which is valid member from database. Returns TRUE if valid member was found, FALSE if not
	function getValidMember( $userid )
	{
		$sql = "SELECT imp.* FROM ". DB_tbl_booking_user_import ." AS imp"
		." INNER JOIN ". DB_tbl_booking_user ." AS usr ON imp.userid=usr.userid"
		." WHERE imp.userid='". $userid ."'"
		." AND ( imp.status='" .DB_user_import_status_active ."' OR imp.status='" .DB_user_import_status_tempactive ."' )";

		$this->Booking_basedb( DB_tbl_booking_user_import, $sql );
		return( !$this->isEmpty( ) );
	}



}

?>
