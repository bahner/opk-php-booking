#
# Class for handling simple <tag>value</tag> XML.
# For storing several keys and values in a string.
# No support for other (legal) XML formats or complicated structures.
#


class Booking_xml
{
  var $xml_array;
  
  function Booking_xml($xml = "")
  {     
      $this->xml_array = to_array($xml);
  }

  
  function get_array()
  {
    return $xml_array;
  }
  
  
  function get_xml()
  {
    $keyval = each($this->xml_array);
    $xml = "";
    while (list ($key, $val) = $keyval) 
    {
      $xml .= "<" . $key . "=\"" . $val . "\"/>\n";
    }
    return $xml;
  }    
  
  
  function to_array($xml)
  {
    $tok = strtok(trim($xml), "<");
    while ($tok)
    {
      if ((substr_count($tok, ">") == 1) && ($tok[1] != "/"))
      {
        $keyval = explode(">", $tok);
        $keyval_array["$keyval[0]"] = $keyval[1];        
      }
        
      $tok = strtok("<");
    }
    
    return $keyval_array;
  }
}

      
/*
                                   
$xml = "<name>Stian</name><name>Even</name>";
$tok = strtok(trim($xml), "<");
while ($tok)
{
  if ((substr_count($tok, ">") == 1) && ($tok[1] != "/"))
  {
    // echo $tok . "<br>";
    $keyval = explode(">", $tok);
    // echo $keyval[0] . "-" . $keyval[1] . "<br>";
    $keyval_array[$keyval[0]] = $keyval[1];
  }
        
  $tok = strtok("<");
}
    
// echo $keyval_array[1] . "<br>";
while (list ($key, $val) = each ($keyval_array)) 
{
     echo "$key => $val<br>";
}
                                   

                                   
                                   
                                   
<name>Stian</name><name>Even</name>



<name="Stian"/>    
<name="Even"/>  

*/      
