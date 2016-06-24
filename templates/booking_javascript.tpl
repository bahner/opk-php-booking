
function quickChoiceBooking( userid, useridField, fullname, fullnameField )
{
	if ( ( useridField.value != "" ) || ( fullnameField.value != "" ) )
	{
		useridField.value = "";
		fullnameField.value = "";
	}
	else
	{
		useridField.value = userid;
		fullnameField.value = fullname;
	}
}
