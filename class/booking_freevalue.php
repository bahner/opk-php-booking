<?php

require_once ( "booking_constants.php" );
require_once ( "booking_basedb.php" );

class Booking_freevalue extends Booking_basedb
{
	# True if the field longval should be used
	var $showLongVal = FALSE;

	function Booking_freevalue( $sql_dbrecord  = NULL )
	{
		$this->Booking_basedb( DB_tbl_booking_freevalue, $sql_dbrecord );
	}

	function setLongVal( $showLongVal=TRUE )
	{
		$this->showLongVal = $showLongVal;
	}

	function useLongVal( )
	{
		return( $this->showLongVal );
	}


	function toHtml( )
	{
		$html = "";
		# $html .= htmlComment( "Booking_freevalue->toHtml()" );
		if ( !$this->isEmpty( ) )
		{
			$fieldval = ( $this->useLongVal( )==TRUE ? $this->getDbCol( "fieldlongval" ) : $this->getDbCol( "fieldval" ) );
			$html .= $this->getDbCol( "fieldstartval") .$fieldval .$this->getDbCol( "fieldendval" );
		}
		return( $html );
	}

}

?>