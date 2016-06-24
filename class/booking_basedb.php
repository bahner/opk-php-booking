<?php
require_once ( "booking_constants.php" );
require_once ( "booking_tableinfo.php" );
require_once ( "booking_dbcolumn.php" );
require_once ( "booking_message.php" );

# Base class for handling database records
class Booking_basedb
{
	# Contains a single database record
	var $dbRecord = FALSE;

	# Contains field info (type, restrictions and so on)
	var $fieldInfo = NULL;

	# Contains name of table where the record is located
	var $tablename = NULL;

	# TRUE if this is a new database record, FALSE if not
	var $newRecord = TRUE;

	# Array of new values of type Booking_dbcolumn for the record that can be stored in the database.
	var $newValues =NULL;

	# True if any values has been changed.
	var $changed = FALSE;

	# Form action that should be set on this resource
	var $formaction = "";

	# TRUE if record is editable. FALSE if it's just for viewing.
	var $editable = TRUE;

	# Contains the name of a view mode. Can be used to present different visual representations based on the value of this field.
	var $viewmode = "";

	#  Name of function to be called by the toHtml( ) function to present a visual representation.
	var $formatfunction = NULL;

	# Field that can be used for anything. Not stored in database.
	var $internalfield;

	# Error message that can be set by setError( ) and retrived by getError( ). should be a Booking_message class. Not stored in database.
	var $errormessage = FALSE;

	# Constructor. $this->dbRecord is set to $sql_dbrecord if $sql_dbrecord is and array (database record)
	# if $sql_dbrecord is a string it is asumed to be SQL and the record is retrived from the database and $this->dbRecord set to it.
	function Booking_basedb( $tablename, $sql_dbrecord  = NULL )
	{
		# Get from booking_tableinfo
		global $tableinfo;

		#echo "<!--\nBooking_basedb( $sql_dbrecord, $tablename  ) \n";
		$this->dbRecord = FALSE;
		$this->fieldInfo = NULL;
		$this->setTableName( $tablename );
		# $this->fieldInfo = new Booking_tableinfo( );
		$this->fieldInfo = $tableinfo;

		if ( !$this->fieldInfo )
			die("Booking_basedb( $tablename );<br>fieldInfo is NULL<br>");

		if ( $sql_dbrecord != NULL )
		{
			# $sql_dbrecord is SQL statement
			if ( is_string( $sql_dbrecord ) )
			{
				$result = dbSqlRows ( $sql_dbrecord );
			}
			# $sql_dbrecord is query result
			else
			{
				$result = $sql_dbrecord;
			}

			if ( $this->dbRecord = mysql_fetch_array( $result ) )
			{
				$this->newRecord = FALSE;
			}
			else
			{
				$this->newRecord = TRUE;
			}

			$this->setFormatFunction( $this->getDbCol( FormatFunctionColumn, FALSE ) );
			#echo "\nId: " .$this->getId( ) ."\n";
		}

		#echo "--> \n";
	}

	# Returns TRUE if a database record is present. FALSE if not.
	function isEmpty( )
	{
		return ( $this->dbRecord == FALSE );
	}

	# Returns TRUE if this is a new database record that has not been stored in the database yet
	function isNew( )
	{
		return( $this->newRecord );
	}

	# Returns the database record. NULL if not set.
	function getDbRecord( )
	{
		return $this->dbRecord;
	}


	# Returns the table name. NULL if not set.
	function getTableName( )
	{
		return( $this->tablename );
	}

	# Sets the table name
	function setTableName( $tablename )
	{
		$this->tablename = $tablename;
	}

	# Sets form action
	function setFormAction( $formaction="" )
	{
		$this->formaction = $formaction;
	}

	# Gets form action
	function getFormAction( )
	{
		return( $this->formaction );
	}

	# Get value of given database column. If $newvalue == TRUE and a new value is present get the new value instead of the one from the database record
	function getDbCol( $colname, $newvalue = TRUE )
	{
		#echo "<!--\n";
		#echo "Booking_basedb->getDbCol\n";
		#echo "\$newvalue=" .($newvalue ? "TRUE" : "FALSE") ."\n";
		#echo " && is_array( \$this->newValues )=" .(is_array( $this->newValues ) ? "TRUE" : "FALSE") ."\n";

   		# Get newvalue array present
		$found = FALSE;
   		if ( $newvalue && is_array( $this->newValues ) )
   		{
   			# Get new value if present
			#echo "array_key_exists( \"$colname\", \$this->newValues )=" .(array_key_exists( $colname, $this->newValues ) ? "TRUE" : "FALSE") ."\n";
			#echo array_keys ( $this->newValues ) ."\n";
   			if ( array_key_exists( $colname, $this->newValues ) )
   			{
   				return(  $this->newValues[ $colname ]->getValue( ) );
   				$found = TRUE;
   			}
		}
   		# Get value from database record if new value is not found or wanted
   		if ( !$found )
   		{
   		if ( !$this->fieldInfo ) 
				die("fieldInfo is NULL.<br>Could not: getColtype( $this->tablename, $colname);<br>In booking_basedb.getDbCol($colname, $newvalue)<br>");
				
			$coltype = $this->fieldInfo->getColtype( $this->tablename, $colname );


      if ( $coltype ) 
      {
   			switch( $coltype )
   			{
   				case "datetime":
   					$colval = dbDateToTimestamp( $this->dbRecord[ $colname ] );
   					break;
   				default:
   					$colval = $this->dbRecord[ $colname ];
   			}
   		}
   		else $colval = NULL;
			return( $colval );

   		}
		#echo "-->\n";
	}


	# Returns name=value pairs of all columns
	function listDbCols( )
	{
		$result = "Booking_basedb->listDbCols( )<br>\n";
		if ( $this->dbRecord != FALSE )
		{
			$result .= "Database values:<br>\n";
			while ( list ($key, $val) = each ($this->dbRecord) )
			{
				$result .= "$key => $val<br>\n";
			}
			$result .= "<p>\n";
		}
		else
		{
			$result.= "\$this->dbRecord == FALSE<br>\n";
		}

		if ( $this->newValues != NULL )
		{
			$result .= "New values:<br>\n";
			while ( list ($key, $val) = each ($this->newValues) )
			{
				$result .= "$key => " .$val->getValue( ) ."<br>\n";
			}
		}
		else
		{
			$result.= "\$this->newValues == NULL<br>\n";
		}
		return( $result );
	}


	# Get value of given database column. Trim all whitespace.
	function getDbColTrim( $colname, $newvalue = TRUE )
	{
		return(  trim( $this->getDbCol( $colname, $newvalue ) )  );
	}


   	# Returns Id of the this dbRecord
   	function getId( )
   	{
   		return ( $this->getDbCol( "id" ) );
   	}


	# Sets if the resource and it's children should be editable or just for viewing
	function setEditable( $iseditable = TRUE )
	{
		$this->editable = $iseditable;
	}


	# Returns TRUE if the resource is editable. FALSE if it's  just for viewing
	function isEditable( )
	{
		return ( $this->editable );
	}

	# Set the name of a view mode
	function setViewMode( $viewmode="" )
	{
		$this->viewmode = $viewmode;
	}

	# Get the current view mode
	function getViewMode( )
	{
		return( $this->viewmode );
	}


	# Get the formatfunction of the dbrecord
	function getFormatFunction( $noprefix=FALSE )
	{
		if ( $noprefix )
		{
			$prefix = "";
		}
		else
		{
			$prefix = FormatFunctionPrefix;
		}
		return (  ( $this->formatfunction == NULL )  ? ( NULL ) : ( $prefix .$this->formatfunction )  );
	}

	# Set the formatfunction for this dbrecord
	function setFormatFunction( $formatfunction )
	{
		if ( $formatfunction != "" )
		{
			$this->formatfunction = $formatfunction;
			$this->setNewValue( FormatFunctionColumn, $this->formatfunction, DB_coltype_string );
		}
	}

	# Sets the internal fieldvalue. $id contains index if internal field is array.
	function setInternalField( $fieldval, $id=FALSE )
	{
		if ( !$id )
		{
			$this->internalfield = $fieldval;
		}
		else
		{
			$this->internalfield[ $id ] = $fieldval;
		}
	}

	# Gets the internal fieldvalue. $id contains index if internal field is array.
	function getInternalField( $fieldval, $id=FALSE )
	{
		if ( !$id )
		{
			return( $this->internalfield );
		}
		else
		{
			return( $this->internalfield[ $id ] );
		}
	}



	# Get the (HTML) form handling function of the dbrecord
	function getFormHandlingFunction( )
	{
		return (  ( $this->formatfunction == NULL )  ? ( NULL ) : ( FormHandlingFunctionPrefix .$this->formatfunction )  );
	}

	# Get the store function for the dbrecord
	function getStoreFunction( )
	{

		return (  ( $this->formatfunction == NULL )  ? ( NULL ) : ( StoreFunctionPrefix .$this->formatfunction )  );
	}


	# sets the field $columnname to the new value $value. Returns the column
	function setNewValue( $colname, $colval,  $coltype = NULL, $insvalueraw=FALSE )
	{
		#echo "<!--\n";
		#echo "is_array( \$this->newValues )=" .(is_array( $this->newValues ) ? "TRUE" : "FALSE") ."\n";

		$found = FALSE;
		if ( is_array( $this->newValues ) )
		{
			#echo "array_key_exists( $colname, \$this->newValues )=" .(array_key_exists( $colname, $this->newValues ) ? "TRUE" : "FALSE") ."\n";
			if ( array_key_exists( $colname, $this->newValues ) )
			{
				$this->newValues[ $colname ]->setValue( $value, $coltype );
				$found = TRUE;
			}
		}
		if ( !$found )
		{
			$this->newValues[ $colname ] = new Booking_dbcolumn( $colname, $colval,  $coltype, $insvalueraw );
		}

		#echo "Booking_basedb->setNewValue " .array_keys ( $this->newValues ) ."\n";
		#echo "-->\n";
		$this->changed = TRUE;
		return( $this->newValues[ $colname ] );
	}


	# sets the field $colname to the new value $value if the value has changed. Returns TRUE if the field was added. False if not.
	function setNewValueIfChanged( $colname, $colval,  $coltype = NULL )
	{
		$newValue = TRUE;
		if (  ( $this->dbRecord != FALSE ) && ( array_key_exists( $colname, $this->dbRecord ) )  )
		{
			$newValue = ( $this->getDbColTrim( $colname ) != trim( $value ) );
		}
		if ( $newValue )
		{
			$this->setNewValue( $colname, $colval, $coltype );
		}
		return ( $newValue );
	}


	# Sets error message. $errormessage should be a Booking_error class
	function setError( $errormessage=FALSE )
	{
		$this->errormessage = $errormessage;
	}

	# Returns error message in form of Booking_freevalue class. FALSE if none
	function getError( )
	{
		return( $this->errormessage );
	}


	# Returns list of with names and values of changed columns for database INSERT operation
	function getInsertList( )
	{
		$colPairs = "";
		if ( count( $this->newValues ) > 0 )
		{
			reset( $this->newValues );
			while ( list( $key, $column ) = each( $this->newValues ) )
			{
				$colPairs .= ( $colPairs == "" ) ? "" : ", ";
				$colPairs .= $column->getName( ) ."=". $column->getValueEnclosed( );
				# echo $column->getName( ) ."=". $column->getValueEnclosed( ) ."<br>\n";
			}
		}
		return( $colPairs );
	}

	# Returns list of with names and values of changed columns for database INSERT operation
	function getUpdateList( )
	{
		return( $this->getInsertList( ) );
	}


	# Returns a HTML representation of this database record by calling it's format function
	function toHtml (  )
	{
		$formatfunction = $this->getFormatFunction( );
		$classname = get_class( $this );

		# Bug in PHP ? Does not seem to return TRUE when it should.
		$formatfunctionexists = method_exists( $classname, $formatfunction );

		if (	( strlen($formatfunction) > strlen( FormatFunctionPrefix ) )
			&& ( strncmp(FormatFunctionPrefix , $formatfunction, strlen( FormatFunctionPrefix ) )  == 0)
		#	&& ( $formatfunctionexists )
		)
		{
			$formatcall = "\$html = \$this->$formatfunction( );";
			eval( $formatcall ) ;
			return ( $html );
		}
	}


	# Handle values submited on the previous page HTML form
	function handleForm( )
	{
	  $html = "";
		# $html = htmlComment( "Booking_basedb->handleForm( )" );
		$formfunction = $this->getFormHandlingFunction( );
		$classname = get_class( $this );

		# Bug in PHP ? Does not seem to return TRUE when it should.
		$formfunctionexists = method_exists( $classname, $formfunction );

		if (	( strlen($formfunction) > strlen( FormHandlingFunctionPrefix ) )
			&& ( strncmp(FormHandlingFunctionPrefix , $formfunction, strlen( FormHandlingFunctionPrefix ) )  == 0)
		#	&& ( $formatfunctionexists )
		)
		{
			$formcall = "\$html .= \$this->$formfunction( );";
			# $html .= htmlComment( "\$classname=$classname \$formcall=$formcall" );
			eval( $formcall ) ;
			return ( $html );
		}

	}


	# Calls the records store function (store_)
	function store( )
	{
		# echo htmlComment( "Booking_basedb->store( )" );
		$storefunction = $this->getStoreFunction( );

		$classname = get_class( $this );

		# Bug in PHP ? Does not seem to return TRUE when it should.
		$storefunctionexists = method_exists( $classname, $storefunction );
		# echo "$classname -> $storefunction=" .($storefunctionexists ? "TRUE" : "FALSE") ."<br>\n";

		if ( $this->isNew( ) )
		{
			$this->setNewValue( "createdby", 0,  DB_coltype_integer );
			$createdtime = $this->setNewValue( "createdtime", "NOW()",  DB_coltype_time, TRUE );
		}
		$this->setNewValue( "updatedby", 0,  DB_coltype_integer );
		$updatedtime = $this->setNewValue( "updatedtime", "NOW()",  DB_coltype_time, TRUE );

		# If storefunction is set, call it
		if  (  ( $storefunction != NULL ) && ( $storefunctionexists )  )
		{
			if ( ( strlen($storefunction) > strlen( StoreFunctionPrefix ) )
				&& ( strncmp(StoreFunctionPrefix , $storefunction, strlen( StoreFunctionPrefix ) )  == 0)
			)
			{
				$storecall = "\$id = \$this->$storefunction( );";
				eval( $storecall ) ;
				$this->newRecord = FALSE;
				return ( TRUE );
			}
		}
		# use standard store functionality.
		else
		{
			# Insert new record
			if ( $this->isNew( ) )
			{
				$sql = "INSERT " .$this->getTableName( ) ." SET " .$this->getInsertList( );
				$id = dbSqlInsert( $sql );
				$this->setNewValue( "id", $id,  DB_coltype_integer );
				$this->newRecord = FALSE;
			}
			# Update existing record
			else
			{
				$sql = "UPDATE " .$this->getTableName( ) ." SET " .$this->getUpdateList( ) ." WHERE id=" .$this->getId( );
				dbSqlRows( $sql );
			}
		}
	}

}

?>
