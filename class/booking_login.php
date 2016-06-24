<?php

require_once ( PHPWS_SOURCE_DIR ."core/Text.php" );
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" );


function login_page()
{
	/*
	<!--
	<form name="login" method="post" onSubmit="return checkPeriod( document.login.currentday.value,document.login.currentmonth.value, document.login.currentyear.value, document.login.fromday.value, document.login.frommonth.value, document.login.fromyear.value, document.login.today.value, document.login.tomonth.value, document.login.toyear.value );">
	-->
	*/

  $membernum = "";
  if ($_SESSION['OBJ_user']->isUser()) 
  {
    $membernum = $_SESSION['OBJ_user']->getUserVar( Booking_membernum_var ); 
  }

	$page = "
		<form name=\"login\" method=\"post\">

		<input type=\"hidden\" name=\"". Form_action_varname ."\" value=\"". Form_action_logon . "\">


		<table width=\"650\" border=\"0\">
		  <tr>
		    <td align=\"center\" class=\"Overskrift1\"><center>Booking Vangen</center></td>
		  </tr>
		
		  <tr>
		    <td>
		      <table cellspacing=\"0\" cellpadding=\"4\">
		        <tr>
		          <td>Medlemsnummer</td>
		          <td>
		          <input type=\"text\" name=\"userid\" size='6' maxlength='6' value='" .$membernum ."'><input type=\"hidden\" name=\"password\" value=\"\">
		          &nbsp;
	";		          

  if ($_SESSION['OBJ_user']->isUser()) 
  { 
    if ( $membernum != NULL )
      $checked = " checked ";
    else
      $checked = "";

    $page .= "<input type=\"checkbox\" name=\"storemember\"" .$checked ."> Lagre medlemsnummer til mitt brukernavn";
  }

		          # Show error message from previous logon atempt
/*
		          if (  ( get_class( $bookingSession ) == "booking_session" )  )
		          {
		          	$error = $bookingSession->getError( );
		          	if ( $error )
		          	{
		          		$page .= $error->toHtml( );
		          	}
		          }
*/

	$page .= "
		          </td>
		        </tr>
		        <tr>
                  <div class=\"BildeHoyre\">
                  <img src=\"mod/booking/img/vangen.jpg\" alt=\"Vangen\" title=\"Vangen\" />
                  </div>		        
		              <p>Logg inn med ditt NAK/NLF medlemsnummer. Booking kan kun gjøres av OPK medlemmer. Det er fullt
		              mulig for andre å overnatte på Vangen, men reservasjonen må gjøres av et OPK medlem.
		              Hvis ordinær gjestebooking ikke er mulig (for mange, kjenner ingen osv.) så kontakt <a href=\"mailto:styret@opk.no\">styret</a>.

		              <p>&nbsp;</p>
		              Vær vennlig å respektere at OPK medlemmer har fortrinnsrett inntil 2 uker før den aktuelle perioden.</p>
		              <p>&nbsp;</p>
	<p>
<em>OBS!</em> Alle husdyr <em>SKAL</em> enhver tid holdes på egne rom (rom 1 og 2). De skal <em>ALDRI</em> oppholde seg i fellesarealer, selv ikke når det ikke er andre gjester tilstede. Det gis <em>IKKE</em> anledning til å spørre andre gjester om det er greit at de oppholder seg i fellesarealer. En del av våre medlemmer er allergikere, og vil kunne reagere på husdyr selv lenge etter at de har vært i rommene.
	</p>
		              <p>&nbsp;</p>
		          </td>
		        </tr>
		        <tr>
		          <td>
		              <p>&nbsp;</p>
		              <p>&nbsp;</p>
		          Hurtigvalg<br>periode
		              <p>&nbsp;</p>
		              <p>&nbsp;</p>
		          </td>
		          <td>
		          <table cellpadding='0' cellspacing='2' border='0'>
	";		  

		            $today = getdate( );
		
		            # Set spesific date
		            # $today = getdate( createTimestamp( 2002, 12, 25 ) );
		
		            $year = $today['year'];
		            $month = $today['mon'];
		            $mday = $today['mday'];
		            $wday = $today['wday'];
		            # 5 is friday
		            $daystoadd = 5 - $wday;
		
		            # Jump to next week if current day is saturday or sunday
					$daystoadd = ( $wday == 6 ? abs( $daystoadd ) + 5 : $daystoadd );
		
		            #if ( ( $wday == 0 ) || ( $wday == 6 ) )
		            #{
		            #  $daystoadd = abs( $daystoadd ) + 7;
		            #}
		
		            for ( $i = 0; $i < 4; $i++ )
		            {
		              $nextfriday = createTimestamp( $year, $month, $mday + $daystoadd + ($i * 7) );
		              $weeknum =  str_pad( date( "W", $nextfriday ), 2, "0", STR_PAD_LEFT );
		              $fromday = date( "d", $nextfriday );
		              $frommonth = date( "m", $nextfriday );
		              $fromyear = date( "Y", $nextfriday );
		              $nextfriday = getFormatedDateShort( $nextfriday );
		              $nextsunday = createTimestamp( $year, $month, $mday + $daystoadd + 2 + ($i * 7) );
		              $today = date( "d", $nextsunday );
		              $tomonth = date( "m", $nextsunday );
		              $toyear = date( "Y", $nextsunday );
		
		              # Store first weekend dates for use as default period.
		              if ( $i == 0 )
		              {
		              	$firstfromday = $fromday;
		              	$firstfrommonth = $frommonth;
		              	$firstfromyear = $fromyear;
		              	$firsttoday = $today;
		              	$firsttomonth = $tomonth;
		              	$firsttoyear = $toyear;
		              }
		
		              $nextsunday = getFormatedDate( $nextsunday );

									$page .= "<tr><td>";
				          $page .= "<a href=\"javascript:quickChoicePeriod( '$fromday', document.login.fromday, '$frommonth', document.login.frommonth, '$fromyear', document.login.fromyear); quickChoicePeriod( '$today', document.login.today, '$tomonth', document.login.tomonth, '$toyear', document.login.toyear);\">Uke $weeknum&nbsp;&nbsp;helgen&nbsp;&nbsp;" .$nextfriday  ." - " .$nextsunday ."</a><br>\n";
									$page .= "</td></tr>";
		            }
							$page .= "</table>";
							$page .= "<input type='hidden' name='currentday' value='$mday'>\n";
	          	$page .= "<input type='hidden' name='currentmonth' value='$month'>\n";
							$page .= "<input type='hidden' name='currentyear' value='$year'>\n";

							$page .= "
		          </td>
		        </tr>
		
		        <tr>
		          <td>Fra dato (dd.mm.yyyy)</td>
		          <td>
		          ";

		          $page .= "<input type='text' name='fromday' size='2' maxlength='2' value='$firstfromday'>.<input type='text' name='frommonth' size='2' maxlength='2' value='$firstfrommonth'>.<select name='fromyear'>\n";
		          for ( $i=$year-1; $i<=($year + 1); $i++ )
		          {
		            	$page .= "<option value='$i'" .( $i == $firstfromyear ? " selected" : "" )  .">$i\n";
		          }

							$page .= "
		          </select>
		
		          &nbsp;&nbsp;Til dato
		          ";

	          	$page .= "<input type='text' name='today' size='2' maxlength='2' value='$firsttoday'>.<input type='text' name='tomonth' size='2' maxlength='2' value='$firsttomonth'>.<select name='toyear'>\n";
		          for ( $i=$year-1; $i<=($year + 1); $i++ )
		          {
		            	$page .= "<option value='$i'" .( $i == $firsttoyear ? " selected" : "" )  .">$i\n";
		          }

			$page .= "
		          </select>
		
		          <br>
		          Her angir du perioden du ønsker å bo på Vangen. Hvis du ønsker å melde deg på for en av de fire førstkommende helgene kan du klikke på et av \"Hurtigvalg periode\" alternativene over for å slippe og angi datoene manuellt.
		              <p>&nbsp;</p>
		          </td>
		        </tr>
		
		        <tr>
		          <td>&nbsp;</td>
		          <td>
		          <input name='login' type='submit' value='" .Booking_login_submit_booking ."'>&nbsp;&nbsp;&nbsp;
		          <input name='login' type='submit' value='" .Booking_login_submit_roomlist ."'>
		          </td>
		        </tr>
		      </table>
		
		    </td>
		  </tr>
		</table>
		
		</form>
		";
		
		return $page;
}
