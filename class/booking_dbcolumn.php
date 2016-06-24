<?php
require_once ( "booking_constants.php" );

# Class that represents a database column
class Booking_dbcolumn
{

	var $columnname = NULL;
	var $columnvalue = NULL;
	var $columntype = NULL;
	# TRUE if value are to be inserted as is without enclosing
	var $insertvalueraw = FALSE;

	# Constructor.
	function Booking_dbcolumn( $colname, $colval,  $coltype = DB_coltype_string, $insvalueraw=FALSE )
	{
		#echo htmlComment( "Booking_dbcolumn( \"$colname\", $colval,  $coltype )" );

		$this->columnname = $colname;
		if ( $coltype == NULL )
		{
			$this->columntype = DB_coltype_string;
		}
		else
		{
			$this->columntype = $coltype;
		}
		$this->columnvalue = $colval;
		$this->insertvalueraw = $insvalueraw;
	}

	# Get column name
	function getName(  )
	{
		return ( $this->columnname );
	}

	# Get column value
	function getValue( )
	{
		#echo htmlComment( "Booking_dbcolumn->getValue( )=$this->columnvalue" );
		return ( $this->columnvalue );
	}

	# Set column value
	function setValue( $colval, $coltype = NULL )
	{
		$this->columnvalue = $colval;
		$this->setType( $coltype );
	}

	# Specifies that the column value will be inserted into database as given without enclosing. Mainly used to call database functions
	function setRawInsert( )
	{
		$this->insertvalueraw = TRUE;
	}

	# Get column value in proper format enclosed in start/endsequences of characters required by database
	function getValueEnclosed( )
	{
		#echo ( $this->insertvalueraw ? "TRUE" : "FALSE" ) ."<br>\n";
		#echo $this->columnvalue ."<br>\n";
		if ( $this->insertvalueraw==TRUE )
		{
			return( $this->columnvalue );
		}
		else
		{
			$enclosestart = "";
			$encloseend = "";
			switch( $this->columntype )
			{
				case DB_coltype_string:
				case DB_coltype_enum:
					$enclosestart = "'";
					$encloseend = "'";
					$retval = $this->columnvalue;
					break;
				case DB_coltype_time:
					$enclosestart = "'";
					$encloseend = "'";
					$retval = date( DB_dateformat, $this->columnvalue );
					break;
				default:
					$retval = $this->columnvalue;
			}
			return ( $enclosestart .$retval .$encloseend );
		}
	}

	# Get column type
	function getType(  )
	{
		return ( $this->columntype );
	}

	# Set column type
	function setType( $coltype = NULL )
	{
		if ( $coltype != NULL)
		{
			$this->columntype = $coltype;
		}
	}
}

?>
