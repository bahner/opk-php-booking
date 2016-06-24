<?php	
   /**
	 * booking module for phpWebSite
	 *
	 * @author Stian Aamodt <phpwebsite@aamodt.no>
	 */


	/* Make sure the user is deity before running this script */
	if (!$_SESSION["OBJ_user"]->isDeity()) {
		header("location:index.php");
			exit();
	}

	$status = 1;

?> 