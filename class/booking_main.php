<?php
require_once(PHPWS_SOURCE_DIR . "core/Item.php"); 
require_once ( "booking_header.php" );
require_once ( "booking_constants.php" );
require_once ( "booking_login.php" );
require_once ( "booking_arrangement.php" );
#require_once ( "booking_import.php" );

# Main class in booking system.
class PHPWS_Booking_main extends PHPWS_Item
{

  function action( )
  {
    # $user = $bookingSession->getUser();
    $user = NULL;
    global $formaction;    

    if ( $user )
    {
    	$userid = $user->getDbCol( "userid" );
    	$fullname = $user->getDbCol( "fullname" );
    }

#    $import = new Booking_import( );
#    $import->import( getenv ( "DOCUMENT_ROOT" ) ."/users.txt" );


    #echo getFormVar( "login" ) ."<p>\n";

    # Check that todate is larger than fromdate
    if ( $formaction == Form_action_logon )
    {
    	$fromtime = getPeriodFrom( );
    	$totime = getPeriodTo( );
    	# Invalid periode. Revert to show login page and error message.
    	if ( $totime <= $fromtime )
    	{
    		$formaction = "";
    		$error = $user->getError();
    		if ( !$error )
    		{
    			$error = new Booking_message;
    			$error->setHtmlPreSuffix( booking_error_html_prefix, booking_error_html_suffix );
    		}
    		$error->addMessage( "Feil ! Tildato må være større enn fradato." );
    		# $bookingSession->setError( $error );
    	}
    }


    # Initialice booking top level class
    $arrangement = new Booking_arrangement ( 1 );

    # echo $formaction;
    # Show different pages based on value of $formaction
    switch ( $formaction )
    {
    	case Form_action_logon:

  		  # Store membership number if selected by logged in user
  		  if ( ($_SESSION['OBJ_user']->isUser( ) ) ) 
  		  {
  		    if ( ( "" .getFormVar( "storemember" ) ) == "on" ) 
    		    $_SESSION['OBJ_user']->setUserVar( Booking_membernum_var, "" .getFormVar( "userid" ) );
    		  else
    		    $_SESSION['OBJ_user']->dropUserVar( Booking_membernum_var );
  	    }

    		# Show roomlist for printing
    		if ( getFormVar( "login" ) == Booking_login_submit_roomlist )
    		{
    			$arrangement->setEditable( FALSE );
    			$arrangement->setViewMode( Booking_printlist );
          echo $arrangement->toHtml( );
    		}
    		else
    		{
  				$dir = PHPWS_Template::getTemplateDir(Modulename, "booking_javascript.tpl");
  				$jsContent = get_include_contents( "$dir/booking_javascript.tpl");
      		$_SESSION['OBJ_layout']->addJavaScript($jsContent); 

    			# 3 hours
    			$addedtime = 60 * 60 * 3;
    			# Period backwards in time selected. Disallow editing
    			if ( ( getPeriodFrom( ) +  $addedtime ) < time( ) ) $arrangement->setEditable( FALSE );
    			$arrangement->setFormAction( Form_action_booking );
    		  return( $arrangement->toHtml( ) );
    		}

    		break;
    	case Form_action_booking:
    		if ( $arrangement->handleForm( ) )
    		{
    			$arrangement->setFormAction( Form_action_booked );
    			$arrangement->setEditable( FALSE );
    		}
    		else
    		{
    			$arrangement->setFormAction( Form_action_booking );
    		}
    		return( $arrangement->toHtml( ) );
    		break;
    	case Form_action_booked:
    		break;

    	default:
				$dir = PHPWS_Template::getTemplateDir(Modulename, "login_javascript.tpl");
				$jsContent = get_include_contents( "$dir/login_javascript.tpl");
    		$_SESSION['OBJ_layout']->addJavaScript($jsContent); 

    		$login = login_page();
    		return $login;
    }

  }
}

?>