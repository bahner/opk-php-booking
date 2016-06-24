<?php
require_once ( "booking_constants.php" );
require_once ( "booking_resource.php" );


class Booking_resources
{
	var $arrangementId = NULL;
	var $resource = NULL;


	function Booking_resources( $bookingArrangementId = NULL )
	{
		$this->arrangementId = $bookingArrangementId;
		$sql = "SELECT * FROM ". DB_tbl_booking_resource ." WHERE arrangementid=$bookingArrangementId AND parentresourceid=0";
		$this->resource = new Booking_resource( $sql );
	}


	# Sets if the resource and it's children should be editable or just for viewing
	function setEditable( $iseditable = TRUE )
	{
		$this->resource->setEditable( $iseditable );
	}


	# Returns TRUE if the resource is editable. FALSE if it's  just for viewing
	function isEditable( )
	{
		return ( $this->resource->isEditable( ) );
	}

	# Sets form action
	function setFormAction( $formaction="" )
	{
		$this->resource->setFormAction( $formaction );
	}

	# Gets form action
	function getFormAction( )
	{
		return( $this->resource->getFormAction( ) );
	}

	# Set the name of a view mode
	function setViewMode( $viewmode="" )
	{
		$this->resource->setViewMode( $viewmode );
	}

	# Get the current view mode
	function getViewMode( )
	{
		return( $this->resource->getViewMode( ) );
	}


	# Returns a visual representation of this resource and all it's children
	function toHtml( )
	{
		return( $this->resource->toHtml( ) );
	}
}

?>
