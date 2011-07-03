<?php

	#
	# $Id$
	#

	# THIS IS STILL A WORK IN FLUX. USE WITH CAUTION.
	# (20101220/straup)

	#################################################################

	loadlib("http");

	#################################################################

	function geo_reverse_geocode_service_map($string_keys=0){

		# 0 means 'not reverse geocoded'

		$map = array(
			1 => 'yahoo',
		);

		if ($string_keys){
			$map = array_flip($map);
		}

		return $map;
	}

	#################################################################

	function geo_reverse_geocode($lat, $lon, $more=array()){

		$defaults = array(
			'service' => 'yahoo',	# please to read from $GLOBALS['cfg']
		);

		$more = array_merge($defaults, $more);

		$func = "geo_reverse_geocode_{$more['service']}";

		if ((! $more['service']) || (! is_callable($func))){

			return array(
				'ok' => 0,
				'error' => 'Unknown or undefined service',
			);
		}

		$rsp = call_user_func_array($func, array($lat, $lon));

		$map = geo_reverse_geocode_service_map('string keys');
		$rsp['service_id'] = $map['service'];

		return $rsp;
	}

	#################################################################

	function geo_reverse_geocode_yahoo($lat, $lon){

		$q = "{$lat},{$lon}";

		$query = "SELECT * FROM geo.placefinder WHERE text=\"{$q}\" and gflags=\"R\"";
		return _geo_reverse_geocode_call_yql($query);
	}

	#################################################################

	function _geo_reverse_geocode_call_yql($query){

		$url = "http://query.yahooapis.com/v1/public/yql?q=";
		$url .= urlencode($query);
		$url .= "&format=json";

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
