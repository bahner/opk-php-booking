<?php
require_once ( "booking_constants.php" );

class Booking_message
{
	var $messages = FALSE;
	var $htmlPrefix = "";
	var $htmlSuffix = "";

	function Booking_message( $message=FALSE )
	{
		if ( $message )
		{
			addMessage( $message );
		}
	}

	# Sets Html code that should be added before and after the message
	function setHtmlPreSuffix( $htmlPrefix, $htmlSuffix )
	{
		$this->htmlPrefix = $htmlPrefix;
		$this->htmlSuffix = $htmlSuffix;
	}

	# True if no message is set
	function isEmpty( )
	{
		return ( !$this->messages );
	}

	# Adds a message
	function addMessage( $message )
	{
		$this->messages[ ] = $message;
	}


	# Clears all previously set messages
	function clear( )
	{
		# Message exists
		if ( !$this->isEmpty( ) )
		{
			foreach ( $this->messages as $key => $value)
			{
				unset( $this->messages[ $key ] );
			}
		}
	}

	# Returns message(s) in HTML format. $messagenum contains message number if a spesific
	# message should be returned. FALSE is returned if message(s) does not exist
	function toHtml( $messagenum=FALSE )
	{
		$html = FALSE;

		# Message exists
		if ( !$this->isEmpty( ) )
		{
			#Return all messages
			if ( !$messagenum )
			{
				$html = "";
				foreach ( $this->messages as $key => $value)
				{
					$html .= $this->htmlPrefix .$value .$this->htmlSuffix;
				}
			}
			# Return spesific message
			else
			{
				if ( array_key_exists( $messagenum, $this->messages ) )
				{
					$html = $this->htmlPrefix .$this->messages[ $messagenum ] .$this->htmlSuffix;
				}
			}
		}

		return( $html );
	}
}

?>