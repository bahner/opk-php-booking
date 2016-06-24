<html>
<head>
<title>P&aring;melding</title>

<link rel=stylesheet href="css/ns4.css" type="text/css">
<link rel=stylesheet href="booking.css" type="text/css">

<script language="JavaScript">

function fillInName(inputField)
{
	var firstname = "PG";
	var lastname = "Pilot";

	inputField.value = firstname + " " + lastname;
	// alert(inputField.value);
}

</script>
</head>

<body>
<form enctype='multipart/form-data' name="rooms" method='post' action=''>

<table width="700" border="0">
  <tr>
    <td align="center"><h1 align="center">Påmelding Vangen</h1></td>
  </tr>

  <tr>
    <td>
      <table cellspacing="0" cellpadding="4" border="0">
        <tr>
          <th>Pålogget</th>
          <td>PG Pilot</td>
        </tr>
        <tr>
          <th>Periode</th>
          <td>1.8.2002 - 3.8.2002</td>
        </tr>
        <tr>
          <th>Melding<br>bussen</th>
          <td>Bussen går fra tollboden i Oslo til Hemsedal den xx.xx.xxxx kl. 18.00. Retur på søndag.</td>
        </tr>

        <tr>
          <th>Ønske om<br>buss</th>
          <td><a class="roombed" href="javascript:fillInName(document.rooms.busname[0])">Plass 1</a>
              <input type="hidden" name="busid" value=""><input name="busname" type="text" size="10" maxlength="40" value="">&nbsp;&nbsp;
              Plass 2<input type="hidden" name="busid" value=""><input name="busname" type="text" size="10" maxlength="40" value="">&nbsp;&nbsp;
              Plass 3<input type="hidden" name="busid" value=""><input name="busname" type="text" size="10" maxlength="40" value="">&nbsp;&nbsp;
              <br>
              Når det gjelder bussen er det mulig å ytre ønske om plass for deg selv og eventuelle gjester over. Dette betyr imidlertid ikke at bussen faktisk går. Om, og i så fall hvor, bussen går er avhengig av en rekke faktorer som antall som har ytret ønske om buss, tilgang på sjåfør, turer som arrangeres osv. For informasjon om bussen, se melding over. Hvis bussen går regnes eventuelle ønsker som påmelding.
          </td>
        </tr>
      </table>
    </td>
  </tr>      
  
  <tr>
    <td>Du kan melde deg på ved å klikke på et av NavnX feltene under, navnet til deg som er pålogget vil da bli fyllt inn automatisk.
        Hvis du ønsker å melde på andre personer skriver du inn navn på ønsket plass. Et medlem kan maksimalt ha med seg 3 gjester og må selv bo på Vangen. 
        For å melde noen av sletter du navnet fra feltet. Du kan kun slette påmeldinger du selv har registrert tidligere. 
        Når du er ferdig, klikk på <b>Bekreft endringer</b> knappen nederst for å bekrefte registreringer.
    </td>
  </tr>

  <tr>
    <td>
  
      <table class="room" cellspacing="0" cellpadding="0" border="1">
        <tr>
          <td  class="room" colspan="2">
            <table>
              <tr>
                <th>Jomfruburet</th>
              </tr>
              <tr>
                <td class="room"> 
                  <input type="hidden" name="fieldindex" value="0">
                  <input type="hidden" name="roomid" value="11">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[0])">Navn1</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><p>
                  Trangt men ingen varme, men trenger man et sted å sove på sommerstid så...
                </td>
              </tr>
            </table>
          </td>
        </tr>
  
        <tr>
          <td class="room">
            <table>
              <tr>
                <td class="room">
                <table cellpadding="0" cellspacing="0" border="0">

                  <tr>
                    <th colspan="3">Rom 9</th>
                  </tr>

                  <tr>
                    <th>&nbsp;</th><td>Medl. nr.</td><td>Navn</td>
                  </tr>

                
                  <tr>
                    <td class="room">
                      <input type="hidden" name="fieldindex" value="1">
                      <input type="hidden" name="roomid" value="9">
                      <input type="hidden" name="bedid" value="1">
                      <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[1])">Plass1</a>:
                    </td>
                    <td class="room">
                      <input name="fainr"
                             type="text"'
                             size="5"
                             maxlength="40"
                             value="">                
                    </td>
                    <td class="room">
                      <input name="occupantname"
                             type="text"'
                             size="20"
                             maxlength="40"
                             value="">
                    </td>
                  </tr>

                  <tr>
                    <td class="room">
                      <input type="hidden" name="fieldindex" value="2">
                      <input type="hidden" name="roomid" value="9">
                      <input type="hidden" name="bedid" value="2">
                      <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[2])">Plass2</a>:
                    </td>
                    <td class="room">
                      <input name="fainr"
                             type="text"'
                             size="5"
                             maxlength="40"
                             value="">                
                    </td>
                    <td class="room">
                      <input name="occupantname"
                             type="text"'
                             size="20"
                             maxlength="40"
                             value="">
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="room">
                      <input type="hidden" name="fieldindex" value="3">
                      <input type="hidden" name="roomid" value="9">
                      <input type="hidden" name="bedid" value="3">
                      <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[3])">Plass3</a>:
                    </td>
                    <td class="room">
                      <input name="fainr"
                             type="text"'
                             size="5"
                             maxlength="40"
                             value="">                
                    </td>
                    <td class="room">
                      <input name="occupantname"
                             type="text"'
                             size="20"
                             maxlength="40"
                             value="">
                    </td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
          </td>
    
          <td class="room">
            <table>
              <tr>
                <th>Rom 5</th>
              </tr>
              <tr>
                <td class="room">
                  Dette rommet okkuperes fast av Anders.
                </td>
              </tr>
            </table>
          </td>
        </tr> 


        <tr>
          <td class="room">
            <table>
              <tr>
                <th>Rom 8</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="4">
                  <input type="hidden" name="roomid" value="8">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[4])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="5">
                  <input type="hidden" name="roomid" value="8">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[5])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="6">
                  <input type="hidden" name="roomid" value="8">
                  <input type="hidden" name="bedid" value="3">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[6])">Navn3</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="7">
                  <input type="hidden" name="roomid" value="8">
                  <input type="hidden" name="bedid" value="4">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[7])">Navn4</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="8">
                  <input type="hidden" name="roomid" value="8">
                  <input type="hidden" name="bedid" value="5">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[8])">Navn5</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value="">
                </td>
              </tr>
            </table>
          </td>
    
          <td class="room">
            <table>
              <tr>
                <th>Rom 4</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="9">
                  <input type="hidden" name="roomid" value="4">
                  <input type="hidden" name="bedid" value="1">
                  Navn1:
                  <input name="occupantname"
                         type="hidden"'
                         size="20"
                         maxlength="40"
                         value="Stian Aamodt"><a href="mailto:stian@aamodt.no">Stian Aamodt</a>&nbsp;7.6-9.6<br>
                  <input type="hidden" name="fieldindex" value="10">
                  <input type="hidden" name="roomid" value="4">
                  <input type="hidden" name="bedid" value="2">
                  Navn2:
                  <input name="occupantname"
                         type="hidden"'
                         size="20"
                         maxlength="40"
                         value="Pelle Pilot">Pelle Pilot&nbsp;7.6-9.6<br>
                  <input type="hidden" name="fieldindex" value="11">
                  <input type="hidden" name="roomid" value="4">
                  <input type="hidden" name="bedid" value="3">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[11])">Navn3</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="12">
                  <input type="hidden" name="roomid" value="4">
                  <input type="hidden" name="bedid" value="4">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[12])">Navn4</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                </td>
              </tr>
            </table>
          </td>
        </tr>



        <tr>
          <td class="room">
            <table>
              <tr>
                <th>Rom 7</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="13">
                  <input type="hidden" name="roomid" value="7">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[13])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="14">
                  <input type="hidden" name="roomid" value="7">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[14])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="15">
                  <input type="hidden" name="roomid" value="7">
                  <input type="hidden" name="bedid" value="3">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[15])">Navn3</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                </td>
              </tr>
            </table>
          </td>
    
          <td class="room">
            <table>
              <tr>
                <th>Rom 3</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="16">
                  <input type="hidden" name="roomid" value="3">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[16])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="17">
                  <input type="hidden" name="roomid" value="3">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[17])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                </td>
              </tr>
            </table>
          </td>
        </tr>



        <tr>
          <td class="room">
            <table>
              <tr>
                <th>Rom 6</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="18">
                  <input type="hidden" name="roomid" value="6">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[18])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="19">
                  <input type="hidden" name="roomid" value="6">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[19])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                </td>
              </tr>
            </table>
          </td>
    
          <td class="room">
            <table>
              <tr>
                <th>Rom 2</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="20">
                  <input type="hidden" name="roomid" value="2">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[20])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="21">
                  <input type="hidden" name="roomid" value="2">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[21])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="22">
                  <input type="hidden" name="roomid" value="2">
                  <input type="hidden" name="bedid" value="3">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[22])">Navn3</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="23">
                  <input type="hidden" name="roomid" value="2">
                  <input type="hidden" name="bedid" value="4">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[23])">Navn4</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  Mulig romalternativ for de<br>som har med seg hund.
                </td>
              </tr>
            </table>
          </td>
        </tr>



        <tr>
          <td class="room">
            <table>
              <tr>
                <th>Rom 10</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="24">
                  <input type="hidden" name="roomid" value="10">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[24])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value="">
                </td>
              </tr>
            </table>
          </td>
    
          <td class="room">
            <table>
              <tr>
                <th>Rom 1</th>
              </tr>
              <tr>
                <td class="room">
                  <input type="hidden" name="fieldindex" value="25">
                  <input type="hidden" name="roomid" value="1">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[25])">Navn1</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="26">
                  <input type="hidden" name="roomid" value="1">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[26])">Navn2</a>:
                  <input name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  Mulig romalternativ for de<br>som har med seg hund.
                </td>
              </tr>
            </table>
          </td>
        </tr>


<!-- 1. etasje -->

        <tr>
          <td class="room" width="50%">
            <table>
              <tr>
                <th>Bak kjøkken</th>
              </tr>
              <tr>
                <td class="room"> 
                  <input type="hidden" name="fieldindex" value="27">
                  <input type="hidden" name="roomid" value="0">
                  <input type="hidden" name="bedid" value="1">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[27])">Navn1</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="28">
                  <input type="hidden" name="roomid" value="0">
                  <input type="hidden" name="bedid" value="2">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[28])">Navn2</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="29">
                  <input type="hidden" name="roomid" value="0">
                  <input type="hidden" name="bedid" value="3">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[29])">Navn3</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br> 
                  <input type="hidden" name="fieldindex" value="30">
                  <input type="hidden" name="roomid" value="0">
                  <input type="hidden" name="bedid" value="4">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[30])">Navn4</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value=""><br>
                  <input type="hidden" name="fieldindex" value="31">
                  <input type="hidden" name="roomid" value="0">
                  <input type="hidden" name="bedid" value="5">
                  <a class="roombed" href="javascript:fillInName(document.rooms.occupantname[31])">Navn5</a>: 
                  <input class="nameroom"
                         name="occupantname"
                         type="text"'
                         size="20"
                         maxlength="40"
                         value="">
                </td>
              </tr>
            </table>
          </td>
          <td class="roomsubmit"> 
          <input type="button" value=" Bekreft endringer ">
          </td>
        </tr>
      </table>


    </td>
  </tr>
</table>

</form>
</body>

</html>