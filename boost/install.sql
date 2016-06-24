CREATE TABLE `mod_booking_arrangement`
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',
  cssclass varchar(30) NOT NULL default '',
  formatfunction varchar(100) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  type enum('continuous','onetime','repeating') NOT NULL default 'continuous',
  fromtime datetime NOT NULL default '0000-00-00 00:00:00',
  totime datetime NOT NULL default '0000-00-00 00:00:00',
  parentarrangementid mediumint(8) unsigned NOT NULL default '0',
  places mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY idxname (name(30)),
  KEY idxfromtime (fromtime),
  KEY idxtotime (totime),
  KEY idxparentarrangementid (parentarrangementid)
) TYPE=MyISAM;

CREATE TABLE mod_booking_freevaluegroup 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',
  cssclass varchar(30) NOT NULL default '',
  name varchar(30) NOT NULL default '',
  foreigntable varchar(100) NOT NULL default '',
  foreignkey varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY idxname (name(15)),
  KEY idxforeigntable (foreigntable(15)),
  KEY idxforeignkey (foreignkey(15))
) TYPE=MyISAM;

CREATE TABLE mod_booking_freevalue 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  countrycode varchar(5) NOT NULL default 'NO',
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',
  cssclass varchar(30) NOT NULL default '',
  foreigntable varchar(100) NOT NULL default '',
  foreignkey varchar(30) NOT NULL default '',
  status enum('active','inactive') NOT NULL default 'active',
  fromtime datetime NOT NULL default '0000-00-00 00:00:00',
  totime datetime NOT NULL default '0000-00-00 00:00:00',  
  sortorder mediumint(8) unsigned NOT NULL default '0',
  fieldname varchar(30) NOT NULL default '',
  fieldstartval varchar(50) NOT NULL default '',
  fieldendval varchar(50) NOT NULL default '',
  fieldtitle varchar(100) NOT NULL default '',
  fieldval varchar(255) NOT NULL default '',
  fieldval2 varchar(255) NOT NULL default '',
  fieldlongval text NOT NULL default '',
  PRIMARY KEY  (id, countrycode),
  KEY idxforeigntable (foreigntable(15)),
  KEY idxforeignkey (foreignkey(15)),
  KEY idxstatus (status),
  KEY idxfromtime (fromtime),
  KEY idxtotime (totime),
  KEY idxfieldname (fieldname(15))
) TYPE=MyISAM;

CREATE TABLE mod_booking_resource 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',
  cssclass varchar(30) NOT NULL default '',
  formatfunction varchar(100) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  parentresourceid mediumint(8) unsigned NOT NULL default '0',
  isleaf enum('y','n') NOT NULL default 'n',
  arrangementid mediumint(8) unsigned NOT NULL default '0',
  places mediumint(8) unsigned NOT NULL default '0',
  placecssclass varchar(30) NOT NULL default '',
  rows mediumint(8) unsigned NOT NULL default '0',
  cols mediumint(8) unsigned NOT NULL default '0',
  row mediumint(8) unsigned NOT NULL default '0',
  col mediumint(8) unsigned NOT NULL default '0',
  rowspan mediumint(8) unsigned NOT NULL default '1',
  colspan mediumint(8) unsigned NOT NULL default '1',  
  bookable enum('y','n') NOT NULL default 'n',
  PRIMARY KEY  (id),
  KEY idxname (name(15)),
  KEY idxparentresourceid (parentresourceid),
  KEY idxarrangementid (arrangementid)
) TYPE=MyISAM;

CREATE TABLE mod_booking_booking 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',
  cssclass varchar(30) NOT NULL default '',
  formatfunction varchar(100) NOT NULL default '',  
  arrangementid mediumint(8) unsigned NOT NULL default '0',
  resourceid mediumint(8) unsigned NOT NULL default '0',
  placenum mediumint(8) unsigned NOT NULL default '0',
  status enum('booking','canceled','doublebookingcanceled','wish','waitinglist') NOT NULL default 'booking',
  useridbookie varchar(10) NOT NULL default '',
  useridbooked varchar(10) NOT NULL default '',
  fromtime datetime NOT NULL default '0000-00-00 00:00:00',
  totime datetime NOT NULL default '0000-00-00 00:00:00',
  fullname varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY idxarrangementid (arrangementid),
  KEY idxresourceid (resourceid),
  KEY idxstatus (status),
  KEY idxuseridbookie (useridbookie),
  KEY idxfromtime (fromtime),
  KEY idxtotime (totime)
) TYPE=MyISAM;

CREATE TABLE mod_booking_user 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',  
  userid varchar(10) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  /*
  'active' can log in, 'inactive' can not log in, 'noimport' can not log in and data about person should not be imported into mod_booking_user_import
  */
  status enum('active','inactive','noimport') NOT NULL default 'active',
  PRIMARY KEY  (id),
  KEY idxuserid (userid),
  KEY idxstatus (status)
) TYPE=MyISAM;

CREATE TABLE mod_booking_user_import
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',  
  # On import all users are set to 'tempactive' all users present in import file is again set to 'active'. User which still has 'tempactive' when import is finished is set to 'inactive'
  status enum('active', 'tempactive', 'inactive') NOT NULL default 'active',
  userid varchar(10) NOT NULL default '',
  fullname varchar(100) NOT NULL default '',
  firstname varchar(50) NOT NULL default '',
  lastname varchar(50) NOT NULL default '',
  address1 varchar(50) NOT NULL default '',
  address2 varchar(50) NOT NULL default '',  
  postalcode varchar(10) NOT NULL default '',
  postaladdress varchar(50) NOT NULL default '',  
  email varchar(100) NOT NULL default '',
  phoneprivate varchar(20) NOT NULL default '',
  phonework varchar(20) NOT NULL default '',
  phonemobile varchar(20) NOT NULL default '',
  fax varchar(20) NOT NULL default '',
  birthdate datetime NOT NULL default '0000-00-00 00:00:00',
  enrolementdate datetime NOT NULL default '0000-00-00 00:00:00',
  paidyear varchar(5) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY idxuserid (userid),
  KEY idxpaidyear (paidyear)
) TYPE=MyISAM;

CREATE TABLE mod_booking_user_group
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',  
  userid varchar(10) NOT NULL default '',
  groupname varchar(30) NOT NULL default '',
  status enum('active','inactive') NOT NULL default 'active',
  PRIMARY KEY  (id),
  KEY idxuserid (userid),
  KEY idxgroupname (groupname)  
) TYPE=MyISAM;

CREATE TABLE mod_booking_group
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',  
  name varchar(30) NOT NULL default '',
  status enum('active','inactive') NOT NULL default 'active',
  # Determines if users that is a member of this group must supply password to gain access to this groups facilities.
  passwordrequired enum('yes', 'no') NOT NULL default 'no',
  PRIMARY KEY  (id),
  KEY idxuserid (name),
  KEY idxstatus (status)
) TYPE=MyISAM;

CREATE TABLE mod_booking_session 
(
  id mediumint(8) unsigned NOT NULL auto_increment,
  createdby mediumint(8) unsigned NOT NULL default '0',
  createdtime datetime NOT NULL default '0000-00-00 00:00:00',
  timestamp timestamp(14) NOT NULL,
  updatedby mediumint(8) unsigned NOT NULL default '0',
  updatedtime datetime NOT NULL default '0000-00-00 00:00:00',  
  sessionid varchar(32)  NOT NULL default '',
  userid varchar(10) NOT NULL,
  passwordprovided enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (id),
  KEY idxsessionid (sessionid),
  KEY idxuserid (userid),
  KEY idxupdatedtime (updatedtime)
) TYPE=MyISAM;


# Create booking Vangen arrangement
INSERT INTO mod_booking_arrangement (createdtime, createdby, formatfunction, name)
VALUES (NOW(), 0, 'vangen', 'Booking Vangen');


# Create Vangen house
INSERT INTO mod_booking_resource (createdtime, createdby, name, arrangementid, formatfunction, cols, isleaf)
VALUES (NOW(), 0, 'Vangen', 1, 'vangen', 2, 'n');


# Create Vangen rooms
INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, colspan, bookable)
VALUES (NOW(), 0, 'Jomfruburet', 'room', 1, 1, 'y', 0, 1, 1, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 9', 'room', 1, 1, 'y', 3, 2, 1, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 5', 'room', 1, 1, 'y', 2, 2, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 8', 'room', 1, 1, 'y', 5, 3, 1, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 4', 'room', 1, 1, 'y', 4, 3, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 7', 'room', 1, 1, 'y', 2, 4, 1, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 3', 'room', 1, 1, 'y', 2, 4, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 6', 'room', 1, 1, 'y', 2, 5, 1, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 2', 'room', 1, 1, 'y', 4, 5, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 10', 'room', 1, 1, 'y', 1, 6, 1, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, bookable)
VALUES (NOW(), 0, 'Rom 1', 'room', 1, 1, 'y', 2, 6, 2, 'y');

INSERT INTO mod_booking_resource (createdtime, createdby, name, formatfunction, parentresourceid, arrangementid, isleaf, places, row, col, colspan, bookable)
VALUES (NOW(), 0, 'Bak kjøkken', 'room', 1, 1, 'y', 5, 7, 1, 2, 'y');


# Create Vangen room bottom texts
INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '2', 'bottomtext', 'Ikke i bruk');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '3', 'bottomtext', 'En køyeseng + en enkel.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '4', 'bottomtext', 'To enkle senger.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '5', 'bottomtext', 'To køyesenger + en enkel.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '6', 'bottomtext', 'En køyeseng + en dobbeltseng.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '7', 'bottomtext', 'En køyeseng (1 1/2 nederst).');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '8', 'bottomtext', 'En køyeseng.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '9', 'bottomtext', 'En køyeseng.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '10', 'bottomtext', 'Hunderom med to køyesenger.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '11', 'bottomtext', 'En enkeltseng.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '12', 'bottomtext', 'Hunderom med en dobbeltseng.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, fieldname, fieldval)
VALUES (NOW(), 0, 'mod_booking_resource', '13', 'bottomtext', 'To køyesenger + en enkel.');

# Create error messages
INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, cssclass, fieldname, fieldtitle, fieldval)
VALUES (NOW(), 0, 'mod_booking_arrangement', '1', 'bookingerror', 'multiplebookings', 'Feilmelding !', 'Følgende medlemer er booket inn på flere plasser innenfor samme periode: %s');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, cssclass, fieldname, fieldtitle, fieldval)
VALUES (NOW(), 0, 'mod_booking_arrangement', '1', 'bookingerror',  'guestmissinghost', 'Feilmelding !', 'Du kan ikke booke gjester hvis du ikke skal bo på Vangen selv i samme periode.');

INSERT INTO mod_booking_freevalue (createdtime, createdby, foreigntable, foreignkey, cssclass, fieldname, fieldtitle, fieldval)
VALUES (NOW(), 0, 'mod_booking_arrangement', '1', 'bookingerror',  'toomanyguests', 'Feilmelding !', 'Du har booket inn for mange gjester (de som det ikke er registrert medlemsnummer på). Maks antall gjester er: %d');