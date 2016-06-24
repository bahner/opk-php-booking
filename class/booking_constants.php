<?php

require_once(PHPWS_SOURCE_DIR . "conf/config.php"); 

#############################################################################################################
## File/path constants
#############################################################################################################

define( "Booking_php_path", PHPWS_SOURCE_DIR . "mod/booking/class/" );

# Path where images reside
define( "Booking_image_path", Booking_php_path ."../img" );


#############################################################################################################
## ViewMode constants
#############################################################################################################

# View printable (room)list
define("Booking_printlist", "BookingPrintlist");

#############################################################################################################
## Session constants
#############################################################################################################

# Name of cookie used to store the users sessionid
define("Booking_session_id", "opk_booking_sessionid");

# Time out in seconds for sessions
define("Booking_session_timeout", "900");

# Variable used to store users membership number
define("Booking_membernum_var", "NLF_membernum");

#############################################################################################################
## Booking constants
#############################################################################################################

define("Modulename", "booking");

# Time out in seconds for sessions
define("Booking_fromhour", 12);
define("Booking_fromminutes", 0);
define("Booking_fromseconds", 0);
define("Booking_tohour", 11);
define("Booking_tominutes", 59);
define("Booking_toseconds", 59);

#############################################################################################################
## Booking rules constants
#############################################################################################################

# Maximum number of guest bookings Vangen
define("Vangen_max_guest_bookings", 3);

define("Booking_noGuestNumberLimitationsExplenation", " er du untatt fra begrensningene som går på at du må bo på Vangen selv for å booke gjester og at du maksimalt kan booke " .Vangen_max_guest_bookings ." gjester.");

# Defines member numbers (with explenations) that are not bound by the Vangen_max_guest_bookings limitation and the rule that you must stay at Vangen yourself to be able to book guests.
define("Booking_noGuestLimitations",
"
'64151' => 'Som Stian' .Booking_noGuestNumberLimitationsExplenation,
'67257' => 'Som kursansvarlig' .Booking_noGuestNumberLimitationsExplenation,
'70819' => 'Som kursansvarlig' .Booking_noGuestNumberLimitationsExplenation,
'67527' => 'Som kursansvarlig' .Booking_noGuestNumberLimitationsExplenation,
'78040' => 'Som leder' .Booking_noGuestNumberLimitationsExplenation,
'22701' => 'Som medlem av huskomiteen' .Booking_noGuestNumberLimitationsExplenation,
'70370' => 'Som medlem av huskomiteen' .Booking_noGuestNumberLimitationsExplenation,
'45141' => 'Som medlem av styret' .Booking_noGuestNumberLimitationsExplenation,
'71738' => 'Som medlem av styret' .Booking_noGuestNumberLimitationsExplenation,
'105459' => 'Som medlem av styret' .Booking_noGuestNumberLimitationsExplenation
");

# If true, show all membership numbers on Vangen booking page for logged on members defined in Booking_noGuestLimitations above
define("ShowMembershipNumbers", False);

#############################################################################################################
## Database constants
#############################################################################################################

# arrangement table. Arrangement is top level of booking system.
define("DB_tbl_booking_arrangement", "opk_booking_arrangement");

# resource table. Contains groups, subgroups and leaves (that can be booked).
define("DB_tbl_booking_resource", "opk_booking_resource");

# booking table. Conains bookings.
define("DB_tbl_booking_booking", "opk_booking_booking");

# freevaluegroup table. Contains groups of freevalues that can be linked to other tables and records.
define("DB_tbl_booking_freevaluegroup", "opk_booking_freevaluegroup");

# freevalue table. General table that can be used to store messages etc. that can be linked to other tables and records.
define("DB_tbl_booking_freevalue", "opk_booking_freevalue");

# Table with user information that should not be changed when NAK member data is imported. Exception is when membership has ended.
define("DB_tbl_booking_user", "opk_booking_user");

# Table with user information that is imported from NAK MelWin file.
define("DB_tbl_booking_user_import", "opk_booking_user_import");

# Table with user group memberships.
define("DB_tbl_booking_user_group", "opk_booking_user_group");

# Table with groups a user can be member of.
define("DB_tbl_booking_group", "opk_booking_group");

# Table with user sessions.
define("DB_tbl_booking_session", "opk_booking_session");

# maximum number of nesting levels (branches and leafs) on opk_booking_resource.
define("DB_max_resource_levels", 10);


# database column types
define("DB_coltype_string", 1);
define("DB_coltype_integer", 2);
define("DB_coltype_float", 3);
define("DB_coltype_time", 4);
define("DB_coltype_enum", 5);

# legal values for column DB_tbl_booking_booking.status
define("DB_booking_status_booking", "booking");
define("DB_booking_status_canceled", "canceled");
define("DB_booking_status_doublebookingcanceled", "doublebookingcanceled");
define("DB_booking_status_wish", "wish");
define("DB_booking_status_waitinglist", "waitinglist");

# legal values for column DB_tbl_booking_user_import.status
define("DB_user_import_status_active", "active");
define("DB_user_import_status_tempactive", "tempactive");
define("DB_user_import_status_inactive", "inactive");

# database PHP date format
define("DB_dateformat", "Y-m-d H:i:s");

define("DB_dateformat_mysql", 1);


# defines relationships between field names in member import file from NAK MelWin to be used to construct an array.
define("DB_file_fieldname_mapping",
"'Nr' => 'userid', 'Navn' => 'fullname', 'Adresse 1' => 'address1', 'Adresse 2' => 'address2', 'Postnr' => 'postalcode', 'Poststed' => 'postaladdress',
'EPost' => 'email', 'Telefon privat' => 'phoneprivate', 'Telefon arbeid' => 'phonework', 'Mobiltelefon' => 'phonemobile', 'Telefaks' => 'fax',
'Fødselsdato' => 'birthdate', 'Innmeldt' => 'enrolementdate', 'Betalt år' => 'paidyear'"
);


#############################################################################################################
# Language constants
#############################################################################################################

# Not a constant but a global variable that allows the language code for texts to be set. Norwegian is default.
global $defaultLanguageCode;
$defaultLanguageCode= "NO";

# Not a constant but a global variable representing the long default dateformat.
global $defaultDateformat;
$defaultDateformat = "d.m.Y";

# Not a constant but a global variable representing the short default dateformat.
global $defaultDateformatShort;
$defaultDateformatShort = "d.m";


#############################################################################################################
## Formatfunction constants. Format functions are used to generate i visual representation of an object
#############################################################################################################

# Name of column storing the format function name
define( "FormatFunctionColumn", "formatfunction" );

# Prefix added to represent the full formatfunction name
define( "FormatFunctionPrefix", "formatfunction_" );

# Prefix added to represent the full form handling function name
define( "FormHandlingFunctionPrefix", "handleForm_" );

# Sufix added to formatfunction to represent function for storing (INSERT or UPDATE) in database.
define( "StoreFunctionPrefix", "store_" );

#############################################################################################################
## HTML Form constants
#############################################################################################################

# Values used on submit buttons in login form
define( "Booking_login_submit_booking", "Til romreservasjon" );
define( "Booking_login_submit_roomlist", "Til romliste for utskrift" );

# Name of the booking form
define( "Booking_form_name", "booking" );

# Name of the <input type="hidden" variable in HTML forms that defines action after form is submited
define( "Form_action_varname", "booking_op" );

# Log on to access booking

define( "Form_action_logon", "logon" );

# Enable user to book
define( "Form_action_booking", "booking" );

# Handle users bookings. Show updated static view of bookings.
define( "Form_action_booked", "booked" );

#############################################################################################################
## Constants used as Html prefix and suffix in Booking_message
#############################################################################################################

define( "booking_information_html_prefix", "<font color=\"red\">" );
define( "booking_information_html_suffix", "</font><br>\n" );

define( "booking_error_html_prefix", "<font color=\"red\">" );
define( "booking_error_html_suffix", "</font><br>\n" );

?>