
function quickChoicePeriod(day, dayField, month, monthField, year, yearField)
{
	dayField.value = day;
	monthField.value = month;
	yearField.value = year;
}


function checkPeriod( currentday, currentmonth, currentyear, fromday, frommonth, fromyear, today, tomonth, toyear)
{
	currentdate = new Date(currentyear, currentmonth, currentday);
	fromdate = new Date(fromyear, frommonth, fromday);
	todate = new Date(toyear, tomonth, today);

	ok = true;
	if ( fromdate >= todate )
	{
		message = "Feil ! Fradato må være mindre enn tildato.";
		if ( fromdate.valueOf() == todate.valueOf() )
		{
			message +=  "\nHusk at du booker seng for en natt. Dvs. fra den ene dagen til den andre";
		}
		ok = false;
		alert( message );
	}
	else if ( fromdate < currentdate )
	{
		message = "Fra/tildato som er valgt er eldre enn dagens dato. Du kan ikke booke bakover i tid,\n men du kan se på opp romlisten for perioden. Ønsker du å gjøre dette ?";
		ok = confirm( message );

	}

	return ok;
}
