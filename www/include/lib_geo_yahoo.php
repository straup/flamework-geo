<?php

	#
	# $Id$
	#

	#################################################################

	loadlib("http");

	#################################################################

	function geo_yahoo_geocode($string, $more=array()){

		$defaults = array(
			'api_key' => $GLOBALS['cfg']['geo_geocoding_yahoo_apikey']
		);

		$more = array_merge($defaults, $more);

		$query = array(
			'q' => $string,
			'flags' => 'j',
			'appid' => $more['api_key'],
		);

		$url = 'http://where.yahooapis.com/geocode?' . http_build_query($query);

		$http_rsp = http_get($url);
		
		$rsp = array(
			'ok' => 0,
			'error' => 'unknown error'
		);
		
		if (! $http_rsp['ok']){
			return $rsp;
		}

		$geocode_response = json_decode($http_rsp['body'], "as hash");
			
		if ($geocode_response['ResultSet']['Found'] = 0){
			return not_okay("failed to geocode");
		}

		$results = $geocode_response['ResultSet']['Results'][0];

		$rsp = array(
			'latitude' => (float)$results['latitude'],
			'longitude' => (float)$results['longitude'],
			'extras' => array( 'woeid' => $results['woeid'] ),
		);

		return okay($rsp);
	}

	#################################################################

	function geo_yahoo_reverse_geocode($lat, $lon){

		$q = "{$lat},{$lon}";

		$query = "SELECT * FROM geo.placefinder WHERE text=\"{$q}\" and gflags=\"R\"";
		return _geo_yahoo_call_yql($query);
	}

	#################################################################

	function _geo_yahoo_call_yql($query){

		$query = array(
			'q' => $query,
			'format' => 'json',
		);

		$url = "http://query.yahooapis.com/v1/public/yql?" . http_build_query($query);

		$rsp = http_get($url);

		if (! $rsp['ok']){
			return $rsp;
		}

		$json = json_decode($rsp['body'], "fuck off php");

		$rsp = array(
			'ok' => 1,
			'data' => $json,
		);

		return $rsp;
	}

	#################################################################
?>
