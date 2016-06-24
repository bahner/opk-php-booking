<?php

/**
 * booking module for phpWebSite
 *
 * @author Stian Aamodt <phpwebsite@aamodt.no>
*/


  /* The version of your module. Make sure to increment this on updates */
  $version = "1.0.6";

  /* The unix style name for your module */
  $mod_title = "booking";

  /* The proper name for your module to be shown to users */
  $mod_pname = "Booking";

  $mod_directory = "booking";
  $mod_filename = "index.php";

  /* An array of class files used by your module */
  $mod_class_files = array("booking_main.php");

  /*
	$mod_sessions = array("OBJ_booking");
	$init_object = array("OBJ_latestposts"=>"LatestpostsForums");
  */

  /* The modules you wish to allow your module to be viewed on */
  $allow_view = array("booking"=>1);

  /* The priority of your module when being loaded.  Leave at 50 if you're unsure */
  $priority = 50;

  /* Whether or not your module is active when it is initially installed */
  $active = "on";
  $admin_mod = 1;
  $deity_mod = 1;
  $user_mod = 1;
?>
