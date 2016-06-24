<?php
require_once ( "booking_libfunctions.php" );

class Booking_tableinfo
{
	var $dbname;
	var $tables = NULL;


	function Booking_tableinfo( $dbname = NULL )
	{
		if ( !$dbname )
		  $dbname = dbGetName( );
		$this->dbname = $dbname;
	}

	# Get info on table if the info is not already present
	function addTable( $tablename )
	{
		if (  ( !$this->tables ) || (!array_key_exists( $tablename, $this->tables )  ) )
		{
			# echo "<br>\n" .$this->dbname ."." .$tablename ."<br>\n";
			$fields = mysql_list_fields( $this->dbname, $tablename, dbGetLink( ) );
			$columns = mysql_num_fields( $fields );

			for ($i = 0; $i < $columns; $i++)
			{
				$this->tables[ $tablename ][ mysql_field_name($fields, $i) ] = mysql_field_type($fields, $i);

			}
		}
	}

	# Returns the column type of $colname. Gets info from database if it is not already present
	function getColtype( $tablename, $colname )
	{
		$this->addTable( $tablename );
		# echo "<br>\n" .$tablename ."." .$colname ."<br>\n";		
		if ( array_key_exists( $colname, $this->tables[ $tablename ] ) )
		  return( $fieldtype = $this->tables[ $tablename ][ $colname ]);
		else return NULL;
	}
}

# Create global variable with table info
global $tableinfo;
$tableinfo = new Booking_tableinfo( );

?>