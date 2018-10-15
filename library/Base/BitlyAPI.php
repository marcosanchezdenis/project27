<?php
class Base_BitlyAPI
{
	public static function make_bitly_url($url,$login='userleduca',$apikey='R_231f1273d8fe49d1f6347f2c4c170b14', $format='json',$version='2.0.1')
	{
		//create the URL
		$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;
		//get the url
		//could also use cURL here
		$response = file_get_contents($bitly);

		//parse depending on desired format
		if(strtolower($format) == 'json')
		{
			$json = @json_decode($response,true);
			return $json['results'][$url]['shortUrl'];
		}
		else //xml
		{
			$xml = simplexml_load_string($response);
			return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
		}
	}

	//usage
	//$short = make_bitly_url('http://www.google.com.py/');
	//echo $short;
	//returns:  http://bit.ly/11Owun
}
