<?php

/**
 * booking module for phpWebSite
 *
 * @author Stian Aamodt <phpwebsite@aamodt.no>
*/ 

$image['name'] = 'booking.jpg';
$image['alt']  = 'Module Author: Stian Aamodt';

/* Create a link to your module */
$link[] = array ('label'       => 'Booking Module',
		 'module'      => 'booking',
		 'url'         => 'index.php?module=booking',
		 'image'       => $image,
		 'admin'       => TRUE,
		 'description' => 'This is the Vangen booking module.',
		 'tab'         => 'content');

?>