<?php

include_once('MPHTTPUtilities.php');

class MPGoogleGeocoder
{
	const _MAX_CNT_PENDING = 5;
	const _EARTH_RADIUS_KM = 6371.0;
	const _UNIT_KILOMETER = "km";
	const _GOOGLE_GEOCODING_URL = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false";
    
    const _OK = "OK";
    const _ZERO_RESULTS = "ZERO RESULTS";
    const _OVER_QUERY_LIMIT = "OVER QUERIED THEN THE LIMIT";
    const _REQUEST_DENIED = "REQUEST DENIED";
    const _INVALID_REQUEST = "REQUEST IS NOT VALID";
    const _UNKNOWN_ERROR = "SOMETHING WENT WRONG WITH THE REQUEST";
    
    const _MULTIPLE_RESULTS = "MORE THAN ONE RESULTS"; 
    

	protected $_error = "";
    protected $status = "";


	/*static methods */

	public static function getDistance($lat1, $lng1, $lat2, $lng2, $unit=self::_UNIT_KILOMETER)
	{
		$earthRadius = self::getEarthRadius($unit);
		return acos((sin($lat1) * sin($lat2)) + (cos($lat1) * cos($lat2) * cos($lng1 - $lng2))) * $earthRadius;
	}

	public static function getEarthRadius($unit=self::_UNIT_KILOMETER)
	{
		switch ($unit)
		{
			case self::_UNIT_KILOMETER:
			default:
				$earthRadius = self::_EARTH_RADIUS_KM;
				break;
		}
		return $earthRadius;
	}


	/* public methods */

	public function __construct($language="de")
	{
		$this->_language = $language;
	}

	// comma separated address string, e.g. Hauptstr. 8, 80333 Muenchen
	public function geocodeAddress($address)
	{
		try
		{
			$url = self::_GOOGLE_GEOCODING_URL
			."&language=".$this->_language."&address=".urlencode($address);

			echo "<br>This is the URL:   $url<br><br>";
			
            //json response from the google
			$json = MPCurlRequestURLUsingGet($url, $this->_error);
			
			if ($this->_error != '')
			{
			    echo "An internal error occurred : ".$this->_error;
			    return null;
			}
            
            $response= json_decode($json,true);
            
            $status = $response['status']; 
			
            switch ($status)
            {
                case "OK":
                $status = self::_OK;    
                    break;
                
                case "ZERO_RESULTS":
                $status = self::_ZERO_RESULTS;
                   break;
                
                case "OVER_QUERY_LIMIT":
                $status = self::_OVER_QUERY_LIMIT;    
                   break;
                
                case "REQUEST_DENIED":
                $status = self::_REQUEST_DENIED;    
                    break;
                
                case "INVALID_REQUEST":
                $status = self::_INVALID_REQUEST;    
                    break;
                
                case "UNKNOWN_ERROR":
                $status = self::_UNKNOWN_ERROR;    
                    break;
                default:
                    break;
            };	
			
            if($status != self::_OK ) 
            {
                echo "abnormal situation"; 
                return $status;
            }
            
            // if more than 1 response we ignore it.
            if(count($response['results']) > 1)
            {
                $status = self::_MULTIPLE_RESULTS;
                echo count($response['results']). " Results  ";   
                return $status;
            }
                   
		    
             $result = array (
				 'street_number' => null,
				 'street' => null,
				 'zip' => null,
				 'city' => null,
				 'country' => null,
				 'countryCode' => null,
				 'lat' => null,
				 'lng' => null
			 );
			
			
            print_r($response);
            
            echo "<br><br><br>";
            
            //nested array handler to get the required values 
            $address  = $response['results'][0]['address_components'];
            
            print_r($address);
            
            //populate $result array with all the required values
            for($i=0; $i<count($address); $i++)
            {
                if($address[$i]['types'][0] == "street_number")
                {
                   $result['street_number'] = $address[$i]['long_name'];
                }

                if($address[$i]['types'][0] == "route")
                {
                   $result['street'] = $address[$i]['long_name'];
                }

                if($address[$i]['types'][0] == "locality")
                {
                   $result['city'] = $address[$i]['long_name'];
                }

                if($address[$i]['types'][0] == "country")
                {
                   $result['country'] = $address[$i]['long_name'];
                   $result['countryCode'] = $address[$i]['short_name'];
                }

                if($address[$i]['types'][0] == "postal_code")
                {
                   $result['zip'] = $address[$i]['long_name'];
                }
                
            }
            
            $result['lat'] = $response['results'][0]['geometry']['location']['lat'];
            $result['lng'] = $response['results'][0]['geometry']['location']['lng'];
    
			return print_r($result);
		}
		catch (Exeption $ex)
		{
			$this->_error .= "Exception. Can't read XML";
			return null;
		}
	}

	public function getError()
	{
		return $this->_error;
	}

	function debug($msg)
	{
		if ($this->_debug)
		{
			echo $msg;
		}
	}
}

/*

<?xml version="1.0" encoding="UTF-8" ?>
<kml xmlns="http://earth.google.com/kml/2.0"><Response>
<name>Brandenburger Tor</name>
<Status>
<code>200</code>
<request>geocode</request>
</Status>
<Placemark id="p1">

<address>Brandenburger Tor, 14793 Ziesar, Germany</address>
<AddressDetails Accuracy="6" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0"><Country><CountryNameCode>DE</CountryNameCode><CountryName>Germany</CountryName><AdministrativeArea><AdministrativeAreaName>Brandenburg</AdministrativeAreaName><SubAdministrativeArea><SubAdministrativeAreaName>Potsdam-Mittelmark</SubAdministrativeAreaName><Locality><LocalityName>Ziesar</LocalityName><Thoroughfare><ThoroughfareName>Brandenburger Tor</ThoroughfareName></Thoroughfare><PostalCode><PostalCodeNumber>14793</PostalCodeNumber></PostalCode></Locality></SubAdministrativeArea></AdministrativeArea></Country></AddressDetails>
<Point><coordinates>12.2972571,52.2685799,0</coordinates></Point>
</Placemark>

<Placemark id="p2">
<address>Brandenburger Tor, 52393 Hürtgenwald, Germany</address>
<AddressDetails Accuracy="6" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0"><Country><CountryNameCode>DE</CountryNameCode><CountryName>Germany</CountryName><AdministrativeArea><AdministrativeAreaName>Nordrhein-Westfalen</AdministrativeAreaName><SubAdministrativeArea><SubAdministrativeAreaName>Düren</SubAdministrativeAreaName><Locality><LocalityName>Hürtgenwald</LocalityName><Thoroughfare><ThoroughfareName>Brandenburger Tor</ThoroughfareName></Thoroughfare><PostalCode><PostalCodeNumber>52393</PostalCodeNumber></PostalCode></Locality></SubAdministrativeArea></AdministrativeArea></Country></AddressDetails>
<Point><coordinates>6.3594460,50.7102171,0</coordinates></Point>

</Placemark>
<Placemark id="p3">
<address>Brandenburger Tor, 31812 Bad Pyrmont, Germany</address>
<AddressDetails Accuracy="6" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0"><Country><CountryNameCode>DE</CountryNameCode><CountryName>Germany</CountryName><AdministrativeArea><AdministrativeAreaName>Niedersachsen</AdministrativeAreaName><SubAdministrativeArea><SubAdministrativeAreaName>Hameln-Pyrmont</SubAdministrativeAreaName><Locality><LocalityName>Bad Pyrmont</LocalityName><Thoroughfare><ThoroughfareName>Brandenburger Tor</ThoroughfareName></Thoroughfare><PostalCode><PostalCodeNumber>31812</PostalCodeNumber></PostalCode></Locality></SubAdministrativeArea></AdministrativeArea></Country></AddressDetails>
<Point><coordinates>9.2515518,51.9843338,0</coordinates></Point>

</Placemark>
<Placemark id="p4">
<address>Brandenburger Tor, Pariser Platz, 10117 Berlin, Germany</address>
<AddressDetails Accuracy="9" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0">
<Country><CountryNameCode>DE</CountryNameCode><CountryName>Germany</CountryName><AdministrativeArea><AdministrativeAreaName>Berlin</AdministrativeAreaName><SubAdministrativeArea><SubAdministrativeAreaName>Berlin</SubAdministrativeAreaName><Locality><LocalityName>Berlin</LocalityName><DependentLocality><DependentLocalityName>Mitte</DependentLocalityName><Thoroughfare><ThoroughfareName>Pariser Platz</ThoroughfareName></Thoroughfare><PostalCode><PostalCodeNumber>10117</PostalCodeNumber><AddressLine>Brandenburger Tor</AddressLine></PostalCode></DependentLocality></Locality></SubAdministrativeArea></AdministrativeArea></Country></AddressDetails>
<Point><coordinates>13.3777785,52.5162691,0</coordinates></Point>
</Placemark>

<Placemark id="p5">
<address>Brandenburger Tor, Schopenhauerstraße, 14467 Potsdam, Germany</address>
<AddressDetails Accuracy="9" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0">
<Country>
<CountryNameCode>DE</CountryNameCode>
<CountryName>Germany</CountryName>
<AdministrativeArea>
<AdministrativeAreaName>Brandenburg</AdministrativeAreaName>
<SubAdministrativeArea>
<SubAdministrativeAreaName>Potsdam</SubAdministrativeAreaName>
<Locality>
<LocalityName>Potsdam</LocalityName>
<Thoroughfare>
<ThoroughfareName>Schopenhauerstraße</ThoroughfareName>
</Thoroughfare>
<PostalCode>
<PostalCodeNumber>14467</PostalCodeNumber>
<AddressLine>Brandenburger Tor</AddressLine>
</PostalCode>
</Locality>
</SubAdministrativeArea>
</AdministrativeArea>
</Country>
</AddressDetails>
<Point>
<coordinates>13.0480124,52.3995391,0</coordinates>
</Point>
</Placemark>

</Response></kml>

*/

?>