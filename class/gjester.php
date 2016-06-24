<html>
<title>Gjester Vangen</title>
<body>
<table align="left" cellspacing="0" cellpadding="5" border="0">
<tr bgcolor="lightgray" align="left">
<th>Id</th><th>Status</th><th>År</th><th>Fra-Til</th><th>Rom</th><th>Navn</th><th>Booket av</th><th>Mobil</th><th>Privat</th><th>Jobb</th><th>Epost</th>
</tr>
<?php
if (!defined('PHPWS_SOURCE_DIR')) {
  define('PHPWS_SOURCE_DIR', '../../../');
} 
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" ); 

$sql = "SELECT bk.id,
  YEAR(bk.fromtime) AS År, 
  DATE_FORMAT(bk.fromtime, '%d.%m') AS Fra, 
  DATE_FORMAT(bk.totime, '%d.%m') AS Til, 
  bk.status,
  (SELECT name FROM opk_booking_resource WHERE id=bk.resourceid) AS rom,
  bk.fullname AS booketnavn,
  bui.fullname AS booketav,
	bui.phonemobile AS Mobil, 
	bui.phoneprivate AS 'Privat', 
	bui.phonework AS 'Jobb',
	bui.email AS Epost
FROM opk_booking_booking AS bk	
	LEFT JOIN opk_booking_user AS bu ON bk.useridbookie=bu.userid
		LEFT JOIN opk_booking_user_import AS bui ON bu.userid=bui.userid
WHERE bk.fromtime <= '" .getFormVar( "to" ) ."' AND bk.totime >= '" .getFormVar( "from" ) ."' "
." ORDER BY bk.status, rom, bk.totime";

$res = dbSqlRows( $sql );
$like = true;
while ( $row = dbFetchAssoc( $res ) )
{
  if ($like) {
    $bgcolor="";
  } else {
    $bgcolor="lightgray";
  }
  $like = !$like;
  
  echo "<tr bgcolor=\"$bgcolor\" align=\"left\">\n";
  echo "<td align=\"right\">" .$row[id] .'&nbsp;</td>';
  echo "<td>" .$row[status] .'&nbsp;</td>';
  echo "<td align=\"right\">" .$row[År] .'&nbsp;</td>';
  echo "<td align=\"right\">" .$row[Fra] ."-" .$row[Til] .'&nbsp;</td>';
  echo "<td>" .$row[rom] .'&nbsp;</td>';
  echo "<td>" .$row[booketnavn] .'&nbsp;</td>';
  echo "<td>" .$row[booketav] .'&nbsp;</td>';
  echo "<td>" .$row[Mobil] .'&nbsp;</td>';
  echo "<td>" .$row[Privat] .'&nbsp;</td>';
  echo "<td>" .$row[Jobb] .'&nbsp;</td>';
  echo "<td><a href=\"mailto:" .$row[Epost] .'\">' .$row[Epost] .'</a>&nbsp;</td>';
  echo "</tr>\n";
}

?>
</table>
</body>
</html>