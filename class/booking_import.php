<?php
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" );

# Imports membership data from NAK MelWin file
class Booking_import
{

	# Converts file date to database date
	function convertFileDate( $filedate )
	{
		$datefields = explode( ".", $filedate );
		$dbdate = $datefields[ 2 ] ."-" . $datefields[ 1 ] ."-" .$datefields[ 0 ];
		return( $dbdate );
	}


	# Get existing id and userid and returns them as an array with userid as key
	function getUserIds( )
	{
		$sql = "SELECT userid, id FROM " .DB_tbl_booking_user_import ." ORDER BY userid";
		$res = dbSqlRows( $sql );

		$userid = FALSE;
		while (  $row = mysql_fetch_row( $res )  )
		{
			$userid[ $row[0] ] = $row[1];
		}

		mysql_free_result( $res );
		return( $userid );
 	}


	# Return string with list of columnames to be inserted
	function getColnames( $importfields )
	{
		$fieldlist = "";
		reset( $importfields );
		while (  list( $key, $val ) = each ( $importfields )  )
		{
			$fieldlist .=  ( $fieldlist=="" ? "" : ", " ) .$val;
		}
		return( $fieldlist);
	}


	# Inserts users from DB_tbl_booking_user_import that is not present in DB_tbl_booking_user into DB_tbl_booking_user
	function insertMissingUsers( )
	{
		# Get all members not already present in DB_tbl_booking_user
		$sql = " SELECT " .DB_tbl_booking_user_import .".userid AS userid"
		." FROM " .DB_tbl_booking_user_import
		." LEFT JOIN " .DB_tbl_booking_user ." ON " .DB_tbl_booking_user_import .".userid=" .DB_tbl_booking_user .".userid"
		." WHERE " .DB_tbl_booking_user .".userid IS NULL";
		$res = dbSqlRows( $sql );

		# And insert them into DB_tbl_booking_user
		$i = 0;
		while ( $row = mysql_fetch_assoc($res) )
		{
			$sql = "INSERT INTO " .DB_tbl_booking_user ." SET userid='" .$row["userid"] ."'";
			$insres = dbSqlRows( $sql );
			$i++;
		}
		mysql_free_result( $res );
		return( $i );
	}


	# Sets users temporarly active before import to later be able to sort out the ones not present in file and set them inactive
	function setTempActive( )
	{
		$sql = "UPDATE " .DB_tbl_booking_user_import ." SET status='tempactive'";
		$res = dbSqlRows( $sql );
	}


	# Sets users with status 'tempactive' = 'inactive' (because they where not present on import)
	function setInactive( )
	{
		$sql = "UPDATE " .DB_tbl_booking_user_import ." SET status='inactive' WHERE status='tempactive'";
		$res = dbSqlRows( $sql );
	}

	# Imports users from NAK MelWin file and store them in database
	function import( $filename )
	{
		# Make sure Norwegian and others characthers are converted properly to upper/lovercase
		setlocale (LC_ALL, 'no_NO');

		# Get mapping of fieldnames between MelWin file and database
		# echo DB_file_fieldname_mapping ."<br>\n";
		eval( "\$importfields = array( " .DB_file_fieldname_mapping ." );" );

		# Get array with existing userids as key and their id as value
		$userids = $this->getUserIds( );

		if  (! ( $fd = fopen ( $filename, "r" ) ) )
		{
			die( );
		}

		$firstline = TRUE;
		$newinsert = TRUE;
		$nummembers = 0;

		# Sets all current users to status='tempactive'
		$this->setTempActive( );

		#($nummembers < 10) &&
		while ( !feof( $fd ) )
		{
			$line = fgets( $fd, 2048 );

			# Get fieldnames from first row
			if ( $firstline )
			{
				$fieldnames = explode( "\t", $line );
				reset ( $importfields );
				while (  list( $key, $val ) = each ( $importfields )  )
				{
					$fieldpos[ $key ] = array_search( $key, $fieldnames );
				}
				$firstline = FALSE;
			}
			# Put user information into database
			else
			{
				$values = explode( "\t", $line );
				$fieldlist = "";
				$firstfield = TRUE;
				reset( $importfields );
				while (  list( $key, $val ) = each ( $importfields )  )
				{
					$fieldval = $values[ $fieldpos[ $key ] ];
					if ( $val == "userid" ) $userid = $fieldval;
					if ( $val == "fullname" )
					{
						# Set first letters in name to upper case, rest to lovercase
						$fieldval = ucwords(  strtolower( $fieldval )  );
						# Remove membership number from fullname if present
						$fieldval = trim( str_replace( "($userid)", "", $fieldval ) );
						$fullname = $fieldval;
					}
					if ( ( $val == "birthdate" ) || ( $val == "enrolementdate" ) ) $fieldval = $this->convertFileDate( $fieldval );
					$fieldlist .=  ( $firstfield ? " " : ", " ) .$val ."='" .$fieldval ."'";
					$firstfield = FALSE;
				}
				# Set imported user status to 'active'
				$fieldlist .=  ( $firstfield ? " " : ", " ) ."status='active'";

				if ( trim( $userid ) != "" )
				{
					# Existing member, do UPDATE
					if ( $userids && array_key_exists( $userid, $userids ) )
					{
						$operation = "UPDATE ";
						$sql = DB_tbl_booking_user_import ." SET " .$fieldlist ." WHERE id='" .$userids[ $userid ] ."'";
					}
					# New member, do INSERT
					else
					{
						$operation = "INSERT ";
						$sql = DB_tbl_booking_user_import ." SET " .$fieldlist;
					}
					$res = dbSqlRows( $operation . $sql );
					echo $operation .$fullname ."<br>\n";
					flush( );
					$nummembers++;
				}

			}
		}
		fclose ($fd);

		# Sets all users that still has status='tempactive' to inactive since they where not present in import file
		$this->setInactive( );

		echo "Members: " .$nummembers ."<br>\n";
		echo "New members: " .$this->insertMissingUsers( ) ."<p>\n";
	}
}
?>
