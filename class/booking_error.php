<?php
require_once ( "booking_constants.php" );

class Booking_error
{
	var $errormessages = FALSE;

	function Booking_error( $errormessage=FALSE )
	{
		if ( $errormessage )
		{
			addError( $errormessage );
		}
	}

	# True if no error is set
	function isEmpty( )
	{
		return ( !$this->errormessages );
	}

	# Adds an error message
	function addError( $errormessage )
	{
		$this->errormessages[ ] = $errormessage;
	}


	# Clears all previously set error messages
	function clear( )
	{
		# Errors exists
		if ( !$this->isEmpty( ) )
		{
			foreach ( $this->errormessages as $key => $value)
			{
				unset( $this->errormessages[ $key ] );
			}
		}
	}

	# Returns errors in HTML format. $errnum contains error number if one spesific error
	# should be returned. FALSE is returned if error does not exist
	function toHtml( $errnum=FALSE )
	{
		$html = FALSE;

		# Errors exists
		if ( !$this->isEmpty( ) )
		{
			#Return all errors
			if ( !$errnum )
			{
				$html = "";
				foreach ( $this->errormessages as $key => $value)
				{
					$html .= booking_error_html_prefix .$value .booking_error_html_suffix;
				}
			}
			# Return spesific error
			else
			{
				if ( array_key_exists( $errnum, $this->errormessages ) )
				{
					$html = booking_error_html_prefix .$this->errormessages[ $errnum ] .booking_error_html_suffix;
				}
			}
		}

		return( $html );
	}
}

?>