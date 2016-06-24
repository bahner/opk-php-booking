<html>
<title>Husansvarlige Vangen</title>
<body>
<table align="left" cellspacing="0" cellpadding="5" border="0">
<tr bgcolor="lightgray" align="left">
<th>År</th><th>Uke</th><th>Fra-Til</th><th>Gjester</th><th>Husansvarlig</th><th>Mobil</th><th>Privat</th><th>Jobb</th><th>Epost</th>
</tr>
<?php
if (!defined('PHPWS_SOURCE_DIR')) {
  define('PHPWS_SOURCE_DIR', '../../../');
} 
require_once ( "booking_constants.php" );
require_once ( "booking_libfunctions.php" ); 

$sql = "SELECT YEAR(fv.fromtime) AS År, WEEK(fv.fromtime)+1 AS Uke, DATE_FORMAT(fv.fromtime, '%d.%m') AS Fra, DATE_FORMAT(fv.totime, '%d.%m') AS Til, 
  DATE_FORMAT(fv.fromtime, '%Y-%m-%d') fradato, DATE_FORMAT(fv.totime, '%Y-%m-%d %H:%i:%S') tildato,
  (SELECT count(*) FROM opk_booking_booking AS bk WHERE bk.fromtime <= fv.totime AND bk.totime >= fv.fromtime AND status='booking') AS Gjester, 
	bui.fullname AS Husansvarlig,
	bui.phonemobile AS Mobil, 
	bui.phoneprivate AS 'Privat', 
	bui.phonework AS 'Jobb',
	bui.email AS Epost
FROM opk_booking_freevalue AS fv	
	LEFT JOIN opk_booking_user AS bu ON fv.fieldval=bu.userid
		LEFT JOIN opk_booking_user_import AS bui ON bu.userid=bui.userid
WHERE fv.foreigntable='opk_booking_resource'
	AND fv.status='active'
	AND fv.fieldname = 'housekeeper'
ORDER BY fv.totime DESC";

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
  echo "<td align=\"right\">" .$row[År] .'&nbsp;</td>';
  echo "<td align=\"right\">" .$row[Uke] .'&nbsp;</td>';
  echo "<td align=\"right\">" .$row[Fra] ."-" .$row[Til] .'&nbsp;</td>';
  echo "<td align=\"right\"><a href=\"gjester.php?from=" .$row[fradato] ."&to=" .$row[tildato] ."\">" .$row[Gjester] .'</a>&nbsp;</td>';
  echo "<td>" .$row[Husansvarlig] .'&nbsp;</td>';
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