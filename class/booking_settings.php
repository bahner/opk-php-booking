<?php
require_once ( "booking_constants.php" );

# Class that holds text and other constants for booking system base mainly on language.
class Booking_settings
{
	global $defaultLanguageCode;
	# Current language code.
	var $languagecode = $defaultLanguageCode;

	# Current settings.
	var $settings = NULL;


	# Constructor. Initialize class with language code $bookinglanguagecode.
	function Booking_settings( $bookinglanguagecode = NULL )
	{
		if ( $bookinglanguagecode != NULL )
		{
			$languagecode = $bookinglanguagecode;
		}
	}


	# Sets language code.
	function setLanguage( $languadecode )
	{
		$this->languagecode = $languagecode;
		$this->getSettings( );
	}

	# Return current language code.
	function getLanguage( )
	{
		return $this->languagecode;
	}

	# Retrive settings (from databasse, if present).
	function getSettings( )
	{
		if ( $settings != NULL )
		{
			mysql_free_result( $this->settings );
		}
		$this->>settings = dbSqlRows( $sql );
	}

	# Get setting.
	function getSetting( $settingname)
	{

	}

}

?>