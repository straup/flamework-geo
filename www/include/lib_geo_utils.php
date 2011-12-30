<?php

	#
	# $Id$
	#

	#################################################################

	function geo_utils_prepare_coordinate($coord, $collapse=1){

		$coord = geo_utils_trim_coordinate($coord);

		if ($collapse){
			$coord = geo_utils_collapse_coordinate($coord);
		}

		return $coord;
	}

	#################################################################

	function geo_utils_expand_coordinate($coord, $multiplier=1000000){

		return $coord / $multiplier;
	}

	#################################################################

	function geo_utils_collapse_coordinate($coord, $multiplier=1000000){

		return $coord * $multiplier;
	}

	#################################################################

	function geo_utils_trim_coordinate($coord, $offset=6){

		$fmt = "%0{$offset}f";

		return sprintf($fmt, $coord);
	}

	#################################################################

	function geo_utils_is_valid_latitude($lat){

		if (! is_numeric($lat)){
			return 0;
		}

		$lat = floatval($lat);

		if (($lat < -90.) || ($lat > 90.)){
			return 0;
		}

		return 1;
	}

	#################################################################

	function geo_utils_is_valid_longitude($lon){

		if (! is_numeric($lon)){
			return 0;
		}

		$lon = floatval($lon);

		if (($lon < -180.) || ($lont > 180.)){
			return 0;
		}

		return 1;
	}

	#################################################################

	# http://snipplr.com/view.php?codeview&id=2531

	function geo_utils_distance($lat1, $lng1, $lat2, $lng2, $miles=0){

		$pi80 = M_PI / 180;

		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;

		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) *
		sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;

		return ($miles) ? ($km * 0.621371192) : $km;
	}

	#################################################################
?>
