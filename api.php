<?php

include_once("resources/php/functions.php");
include_once("resources/php/config.php");

const VALID_API_CALLS = array(
	"listEvents" 		=> "getEventList",
	"eventResults"		=> "getSingleEventResults",
	"playerInfo"		=> "getSinglePlayer",
	"listPlayers"		=> "getAllPlayers",
	"validate"			=> "bulkLoadValidate",
	"listCountries"		=> "getCountryList",
	"addPlayer"			=> "addNewPlayer",
	"listEventTypes"	=> "getAllEventTypesByDate",
	"addEvent"			=> "addNewEvent",
	"addResult"			=> "addNewResult",
	"deleteEvent"		=> "deleteEvent",
	"setSessionKey"		=> "setSessionKey",
	"mergePlayers"		=> "mergePlayers",
);

const VALID_COUNTRY_CODES = array(
	"AFG" => "Afghanistan",
	"ALA" => "Aland Islands",
	"ALB" => "Albania",
	"DZA" => "Algeria",
	"ASM" => "American Samoa",
	"AND" => "Andorra",
	"AGO" => "Angola",
	"AIA" => "Anguilla",
	"ATA" => "Antarctica",
	"ATG" => "Antigua and Barbuda",
	"ARG" => "Argentina",
	"ARM" => "Armenia",
	"ABW" => "Aruba",
	"AUS" => "Australia",
	"AUT" => "Austria",
	"AZE" => "Azerbaijan",
	"BHS" => "Bahamas",
	"BHR" => "Bahrain",
	"BGD" => "Bangladesh",
	"BRB" => "Barbados",
	"BLR" => "Belarus",
	"BEL" => "Belgium",
	"BLZ" => "Belize",
	"BEN" => "Benin",
	"BMU" => "Bermuda",
	"BTN" => "Bhutan",
	"BOL" => "Bolivia",
	"BIH" => "Bosnia and Herzegovina",
	"BWA" => "Botswana",
	"BVT" => "Bouvet Island",
	"BRA" => "Brazil",
	"VGB" => "British Virgin Islands",
	"IOT" => "British Indian Ocean Territory",
	"BRN" => "Brunei Darussalam",
	"BGR" => "Bulgaria",
	"BFA" => "Burkina Faso",
	"BDI" => "Burundi",
	"KHM" => "Cambodia",
	"CMR" => "Cameroon",
	"CAN" => "Canada",
	"CPV" => "Cape Verde",
	"CYM" => "Cayman Islands",
	"CAF" => "Central African Republic",
	"TCD" => "Chad",
	"CHL" => "Chile",
	"CHN" => "China",
	"HKG" => "Hong Kong",
	"MAC" => "Macao",
	"CXR" => "Christmas Island",
	"CCK" => "Cocos (Keeling) Islands",
	"COL" => "Colombia",
	"COM" => "Comoros",
	"COG" => "Congo(Brazzaville)",
	"COD" => "Congo, (Kinshasa)",
	"COK" => "Cook Islands",
	"CRI" => "Costa Rica",
	"CIV" => "Côte d'Ivoire",
	"HRV" => "Croatia",
	"CUB" => "Cuba",
	"CYP" => "Cyprus",
	"CZE" => "Czech Republic",
	"DNK" => "Denmark",
	"DJI" => "Djibouti",
	"DMA" => "Dominica",
	"DOM" => "Dominican Republic",
	"ECU" => "Ecuador",
	"EGY" => "Egypt",
	"SLV" => "El Salvador",
	"GNQ" => "Equatorial Guinea",
	"ERI" => "Eritrea",
	"EST" => "Estonia",
	"ETH" => "Ethiopia",
	"FLK" => "Falkland Islands (Malvinas)",
	"FRO" => "Faroe Islands",
	"FJI" => "Fiji",
	"FIN" => "Finland",
	"FRA" => "France",
	"GUF" => "French Guiana",
	"PYF" => "French Polynesia",
	"ATF" => "French Southern Territories",
	"GAB" => "Gabon",
	"GMB" => "Gambia",
	"GEO" => "Georgia",
	"DEU" => "Germany",
	"GHA" => "Ghana",
	"GIB" => "Gibraltar",
	"GRC" => "Greece",
	"GRL" => "Greenland",
	"GRD" => "Grenada",
	"GLP" => "Guadeloupe",
	"GUM" => "Guam",
	"GTM" => "Guatemala",
	"GGY" => "Guernsey",
	"GIN" => "Guinea",
	"GNB" => "Guinea-Bissau",
	"GUY" => "Guyana",
	"HTI" => "Haiti",
	"HMD" => "Heard and Mcdonald Islands",
	"VAT" => "Vatican",
	"HND" => "Honduras",
	"HUN" => "Hungary",
	"ISL" => "Iceland",
	"IND" => "India",
	"IDN" => "Indonesia",
	"IRN" => "Iran",
	"IRQ" => "Iraq",
	"IRL" => "Ireland",
	"IMN" => "Isle of Man",
	"ISR" => "Israel",
	"ITA" => "Italy",
	"JAM" => "Jamaica",
	"JPN" => "Japan",
	"JEY" => "Jersey",
	"JOR" => "Jordan",
	"KAZ" => "Kazakhstan",
	"KEN" => "Kenya",
	"KIR" => "Kiribati",
	"PRK" => "North Korea",
	"KOR" => "South Korea",
	"KWT" => "Kuwait",
	"KGZ" => "Kyrgyzstan",
	"LAO" => "Lao PDR",
	"LVA" => "Latvia",
	"LBN" => "Lebanon",
	"LSO" => "Lesotho",
	"LBR" => "Liberia",
	"LBY" => "Libya",
	"LIE" => "Liechtenstein",
	"LTU" => "Lithuania",
	"LUX" => "Luxembourg",
	"MKD" => "Macedonia",
	"MDG" => "Madagascar",
	"MWI" => "Malawi",
	"MYS" => "Malaysia",
	"MDV" => "Maldives",
	"MLI" => "Mali",
	"MLT" => "Malta",
	"MHL" => "Marshall Islands",
	"MTQ" => "Martinique",
	"MRT" => "Mauritania",
	"MUS" => "Mauritius",
	"MYT" => "Mayotte",
	"MEX" => "Mexico",
	"FSM" => "Micronesia",
	"MDA" => "Moldova",
	"MCO" => "Monaco",
	"MNG" => "Mongolia",
	"MNE" => "Montenegro",
	"MSR" => "Montserrat",
	"MAR" => "Morocco",
	"MOZ" => "Mozambique",
	"MMR" => "Myanmar",
	"NAM" => "Namibia",
	"NRU" => "Nauru",
	"NPL" => "Nepal",
	"NLD" => "Netherlands",
	"ANT" => "Netherlands Antilles",
	"NCL" => "New Caledonia",
	"NZL" => "New Zealand",
	"NIC" => "Nicaragua",
	"NER" => "Niger",
	"NGA" => "Nigeria",
	"NIU" => "Niue",
	"NFK" => "Norfolk Island",
	"MNP" => "Northern Mariana Islands",
	"NOR" => "Norway",
	"OMN" => "Oman",
	"PAK" => "Pakistan",
	"PLW" => "Palau",
	"PSE" => "Palestinian Territory",
	"PAN" => "Panama",
	"PNG" => "Papua New Guinea",
	"PRY" => "Paraguay",
	"PER" => "Peru",
	"PHL" => "Philippines",
	"PCN" => "Pitcairn",
	"POL" => "Poland",
	"PRT" => "Portugal",
	"PRI" => "Puerto Rico",
	"QAT" => "Qatar",
	"REU" => "Réunion",
	"ROU" => "Romania",
	"RUS" => "Russia",
	"RWA" => "Rwanda",
	"BLM" => "Saint-Barthélemy",
	"SHN" => "Saint Helena",
	"KNA" => "Saint Kitts and Nevis",
	"LCA" => "Saint Lucia",
	"MAF" => "Saint-Martin (French part)",
	"SPM" => "Saint Pierre and Miquelon",
	"VCT" => "Saint Vincent and Grenadines",
	"WSM" => "Samoa",
	"SMR" => "San Marino",
	"STP" => "Sao Tome and Principe",
	"SAU" => "Saudi Arabia",
	"SEN" => "Senegal",
	"SRB" => "Serbia",
	"SYC" => "Seychelles",
	"SLE" => "Sierra Leone",
	"SGP" => "Singapore",
	"SVK" => "Slovakia",
	"SVN" => "Slovenia",
	"SLB" => "Solomon Islands",
	"SOM" => "Somalia",
	"ZAF" => "South Africa",
	"SGS" => "South Georgia and the South Sandwich Islands",
	"SSD" => "South Sudan",
	"ESP" => "Spain",
	"LKA" => "Sri Lanka",
	"SDN" => "Sudan",
	"SUR" => "Suriname",
	"SJM" => "Svalbard and Jan Mayen Islands",
	"SWZ" => "Swaziland",
	"SWE" => "Sweden",
	"CHE" => "Switzerland",
	"SYR" => "Syria",
	"TWN" => "Taiwan",
	"TJK" => "Tajikistan",
	"TZA" => "Tanzania",
	"THA" => "Thailand",
	"TLS" => "Timor-Leste",
	"TGO" => "Togo",
	"TKL" => "Tokelau",
	"TON" => "Tonga",
	"TTO" => "Trinidad and Tobago",
	"TUN" => "Tunisia",
	"TUR" => "Turkey",
	"TKM" => "Turkmenistan",
	"TCA" => "Turks and Caicos Islands",
	"TUV" => "Tuvalu",
	"UGA" => "Uganda",
	"UKR" => "Ukraine",
	"ARE" => "United Arab Emirates",
	"GBR" => "United Kingdom",
	"USA" => "United States of America",
	"UMI" => "US Minor Outlying Islands",
	"URY" => "Uruguay",
	"UZB" => "Uzbekistan",
	"VUT" => "Vanuatu",
	"VEN" => "Venezuela",
	"VNM" => "Vietnam",
	"VIR" => "Virgin Islands, US",
	"WLF" => "Wallis and Futuna Islands",
	"ESH" => "Western Sahara",
	"YEM" => "Yemen",
	"ZMB" => "Zambia",
	"ZWE" => "Zimbabwe"
);

const EVENT_LIST_SQL = "select
		e.id AS eventId,
		e.date AS date,
		e.city AS city,
		e.country AS country,
		p.id AS eventWinnerId,
		p.playerName AS eventWinner,
		p.country AS eventWinnerCountryCode,
		case when e.eventName = '' then concat(e.city,' ',et.label) else e.eventName end AS eventName,
		et.points AS points,
		e.playerCount AS playerCount,
		s.year as season
	from
		events e
			left join results r
				on e.id = r.eventId
			left join (Select * From players Where active = 1) p
				on r.playerId = p.id
			inner join eventTypes et
				on e.eventTypeId = et.id
			inner join seasons s
				on et.seasonId = s.id
	where
		(r.position = 1 or r.position is null) ";

const EVENT_RESULTS_SQL = "select
	r.id AS resultId,
	e.id AS eventId,
	p.country AS playerCountry,
	p.id AS playerId,
	p.playerName AS playerName,
	r.position AS position,
	r.team AS team,
	r.qrlink AS qrlink
from
	events e
		left join results r
			on e.id = r.eventId
		inner join eventTypes et
			on e.eventTypeId = et.id
		left join (Select * From players Where active = 1) p
			on r.playerId = p.id ";

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$apiCommand = $_GET["command"];

header('Content-Type: application/json');

if ( isset(VALID_API_CALLS[$apiCommand]) ) {
	echo json_encode(VALID_API_CALLS[$apiCommand](), JSON_PRETTY_PRINT);
} else {
	echo json_encode([
		"result"	=> "error",
		"error"		=> "Invalid API call.",
		"status"	=> 400
	], JSON_PRETTY_PRINT);
}

function getEventList() {
	global $mysqli;
	
	$countryCode = "";
	if ( isset($_GET["countryCode"]) ) $countryCode = $_GET["countryCode"];
	
	$eventId = -1;
	if ( isset($_GET["eventId"]) ) $eventId = $_GET["eventId"];
	
	if ( ! isset(VALID_COUNTRY_CODES[$countryCode]) && $countryCode != "" ) {
		return [
			"result"	=> "error",
			"error"		=> "'" . $countryCode . "' is not a valid country code.",
			"status"	=> 400
		];
	}
	
	$sql = EVENT_LIST_SQL;
	
	if ( $eventId != -1 ) {
		$sql .= " And eventId = " . $mysqli->real_escape_string($eventId);
	} elseif ( $countryCode != "" ) {
		$sql .= " And country = '" . $mysqli->real_escape_string($countryCode) . "'";
	}
		
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> getEventDetails($sql)
	];
}

function getEventDetails($eventSql) {
	global $mysqli;
	
	$events = $mysqli->query($eventSql);
	
	$eventList = array();
	
	while ( $event = $events->fetch_assoc() ) {
		$eventId = $event["eventId"];
		$eventList[$eventId] = array(
			"date"						=> date($event["date"]),
			"city"						=> $event["city"],
			"countryCode"				=> $event["country"],
			"countryName"				=> VALID_COUNTRY_CODES[$event["country"]],
			"eventWinnerId"				=> (int)$event["eventWinnerId"],
			"eventWinner"				=> $event["eventWinner"],
			"eventName"					=> $event["eventName"],
			"eventWinnerCountryCode"	=> $event["eventWinnerCountryCode"],
			"eventPoints"				=> json_decode($event["points"], true),
			"playerCount"				=> (int)$event["playerCount"],
			"season"					=> (int)$event["season"]
		);
	}
	
	$events->free();
	
	return $eventList;
}

function getEventResultData($sql) {
	global $mysqli;
	
	$results = $mysqli->query($sql);
	$resultsList = array();
	$eventSql = EVENT_LIST_SQL . " And e.id In (-1";
	
	while ( $result = $results->fetch_assoc() ) {
		$position = $result["position"];
		$eventId = $result["eventId"];
		
		if ( ! isset($resultsList[$eventId]) ) {
			$resultsList[$eventId] = array();
			$eventSql .= ", " . $mysqli->real_escape_string($eventId);
		}
		
		$resultsList[$eventId][$position] = array(
			"playerId"			=> (int)$result["playerId"],
			"playerName"		=> $result["playerName"],
			"playerCountryCode"	=> $result["playerCountry"],
			"playerCountryName"	=> VALID_COUNTRY_CODES[$result["playerCountry"]],
			"position"			=> (int)$position,
			"points"			=> 0,
			"prizeMoney"		=> 0,
			"team"				=> sortPokemonTeam(json_decode($result["team"], true)),
			"rentalLink"		=> $result["qrlink"]
		);
	}
	
	$eventSql .= ") Order By e.date;";
	$eventDetails = getEventDetails($eventSql);
	
	$results->free();
	
	foreach( $resultsList as $eventId => $resultData ) {
		$playerCount = $eventDetails[$eventId]["playerCount"];

		foreach( $resultData as $position => $result ) {
			foreach ( $eventDetails[$eventId]["eventPoints"] as $points ) {
				if ( $position <= $points["position"] && $playerCount >= $points["kicker"] ) {
					if ( $resultsList[$eventId][$position]["points"] < $points["points"] ) {
						$resultsList[$eventId][$position]["points"] = $points["points"];
					}
				}
			}
		}
	}
	
	return array(
		"results" => $resultsList,
		"events" => $eventDetails
	);
}

function getSingleEventResults() {
	global $mysqli;
	
	$eventId = -1;
	if ( isset($_GET["eventId"]) ) $eventId = $_GET["eventId"];
	
	if ( ! isset($_GET["eventId"]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires an event ID to be supplied.",
			"status"	=> 400
		];
	}
	
	$sql = EVENT_RESULTS_SQL . "where e.id = " . $mysqli->real_escape_string($eventId) . " order by r.position";

	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> getEventResultData($sql)
	];
}

function getSinglePlayer() {
	global $mysqli;
	
	$playerId = -1;
	if ( isset($_GET["playerId"]) ) $playerId = $_GET["playerId"];
	
	if ( ! isset($_GET["playerId"]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a player ID to be supplied.",
			"status"	=> 400
		];
	}
	
	$sql = "Select * From players Where active = 1 And id = " . $mysqli->real_escape_string($playerId);
	$playerInfo = $mysqli->query($sql);
	
	$playerData = array();
	
	if ( $player = $playerInfo->fetch_assoc() ) {
		$playerData = array(
			"playerId"		=> $playerId,
			"playerName"	=> $player["playerName"],
			"countryCode"	=> $player["country"],
			"countryName"	=> VALID_COUNTRY_CODES[$player["country"]],
			"socialMedia"	=> array()
		);
		
		if ( $player["facebook"] != "" ) {
			$playerData["socialMedia"]["facebook"] = $player["facebook"];
		}

		if ( $player["twitter"] != "" ) {
			$playerData["socialMedia"]["twitter"] = $player["twitter"];
		}

		if ( $player["youtube"] != "" ) {
			$playerData["socialMedia"]["youtube"] = $player["youtube"];
		}

		if ( $player["twitch"] != "" ) {
			$playerData["socialMedia"]["twitch"] = $player["twitch"];
		}
	}
	
	$playerInfo->free();
	
	$sql = EVENT_RESULTS_SQL . " Where playerId = " . $mysqli->real_escape_string($playerId) . ";";

	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> array(
			"player"	=> $playerData,
			"results"	=> getEventResultData($sql)
		)
	];
}

function getAllPlayers() {
	global $mysqli;
	
	$sql = "Select
		p.id,
		p.playerName,
		p.country,
		p.facebook,
		p.youtube,
		p.twitter,
		p.twitch
	From
		players p
	Where
		active = 1
	";
	
	$playerInfo = $mysqli->query($sql);
	$playerData = array();
	
	while ( $player = $playerInfo->fetch_assoc() ) {
		$playerData[$player["id"]] = array(
			"playerId"		=> $player["id"],
			"playerName"	=> $player["playerName"],
			"countryCode"	=> $player["country"],
			"countryName"	=> VALID_COUNTRY_CODES[$player["country"]],
			"socialMedia"	=> array(),
			"lastEventDate"	=> $player["lastEventDate"]
		);
		
		if ( $player["facebook"] != "" ) {
			$playerData[$player["id"]]["socialMedia"]["facebook"] = $player["facebook"];
		}

		if ( $player["twitter"] != "" ) {
			$playerData[$player["id"]]["socialMedia"]["twitter"] = $player["twitter"];
		}

		if ( $player["youtube"] != "" ) {
			$playerData[$player["id"]]["socialMedia"]["youtube"] = $player["youtube"];
		}

		if ( $player["twitch"] != "" ) {
			$playerData[$player["id"]]["socialMedia"]["twitch"] = $player["twitch"];
		}
	}
	
	$playerInfo->free();
	
	$sql = "SELECT
	    pr.playerId,
	    e.id As eventId,
		e.eventName,
		e.date,
	    r.position,
	    r.team
	FROM
		events e
	    	Inner Join (
	            SELECT
	                Max(e.date) As lastEventDate,
	                r.playerId As playerId 
	            FROM
	                events e
	                    Inner Join results r
	                        On e.id = r.eventId
	            GROUP BY
	                r.playerId
	        ) pr
	        	On e.date = pr.lastEventDate
	        Inner Join results r
	        	On r.playerId = pr.playerId
	        		And e.id = r.eventId";

	$playerInfo = $mysqli->query($sql);
	
	while ( $player = $playerInfo->fetch_assoc() ) {
		$playerData[$player["playerId"]]["lastEventDate"] = $player["date"];
		$playerData[$player["playerId"]]["lastTeam"] = sortPokemonTeam(json_decode($player["team"], true));
		$playerData[$player["playerId"]]["lastEventId"] = $player["eventId"];
	}
	
	$playerInfo->free();
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $playerData
	];
}

function bulkLoadValidate() {
	global $mysqli;
	
	$playerList = getAllPlayers();
	
	if ( isset($_GET["bulk"]) ) $bulkData = $_GET["bulk"];
	if ( ! isset($_GET["bulk"]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a bulk data set to be supplied.",
			"status"	=> 400
		];
	}

	$inputData = explode("\n", base64_decode($bulkData));
	$validatedData = array();
	
	foreach($inputData as $inputLine) {
		if ( $inputLine == "" ) continue;
		
		$input = explode(",", $inputLine);
		
		$position = $input[0];
		$playerName = $input[1];
		$validPlayerIds = array();
		
		foreach($playerList["data"] as $player) {
			if ( strtolower($player["playerName"]) == strtolower($playerName) ) {
				$validPlayerIds[$player["playerId"]] = array(
					"playerName"	=> $playerName,
					"countryCode"	=> $player["countryCode"],
					"countryName"	=> $player["countryName"]
				);
			}
		}
		
		$pokemon = array();
		$pokemon[0] = decodePokemonShowdown($input[2]);
		$pokemon[1] = decodePokemonShowdown($input[3]);
		$pokemon[2] = decodePokemonShowdown($input[4]);
		$pokemon[3] = decodePokemonShowdown($input[5]);
		$pokemon[4] = decodePokemonShowdown($input[6]);
		$pokemon[5] = decodePokemonShowdown($input[7]);
		
		$validatedData[$position] = array(
			"position"			=> $position,
			"playerName"		=> $playerName,
			"validPlayerIds"	=> $validPlayerIds,
			"team"				=> $pokemon
		);
	}
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $validatedData
	];
}

function getCountryList() {
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> VALID_COUNTRY_CODES
	];
}

function addNewPlayer() {
	global $mysqli;
	
	if ( ! isset($_GET["key"]) || ! isset(API_KEY[$_GET["key"]]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a valid API key.",
			"status"	=> 400
		];
	}
	
	$playerName = "";
	$countryCode = "";
	$twitter = "";
	$apiKey = $_GET["key"];
	
	if ( isset($_GET["playerName"]) )	$playerName = $_GET["playerName"];
	if ( isset($_GET["countryCode"]) )	$countryCode = strtoupper($_GET["countryCode"]);
	if ( isset($_GET["twitter"]) )		$twitter = $_GET["twitter"];
	
	if ( $playerName == "" || $countryCode == "" ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a minimum of a player name and country code to be specified.",
			"status"	=> 400
		];
	}
	
	$stmt = $mysqli->prepare("Insert Into players ( playerName, country, twitter, api ) Values ( ?, ?, ?, ? );");
	$stmt->bind_param("ssss", $playerName, $countryCode, $twitter, $apiKey);
	$stmt->execute();
	$playerId = $stmt->insert_id;
	$stmt->close();
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $playerId
	];
}

function getAllEventTypesByDate() {
	global $mysqli;
	
	$date = "";
	
	if ( isset($_GET["date"]) )	$date = $_GET["date"];
	
	if ( $date == 0 ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a date be specified.",
			"status"	=> 400
		];
	}
	
	$sql = "Select et.id, et.label From eventTypes et Inner Join seasons s On ";
	$sql .= "et.seasonId = s.id Where s.startDate <= ? And s.endDate >= ?";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ss", $date, $date);
	$stmt->bind_result($eventTypeId, $eventType);
	$stmt->execute();
	
	$eventTypes = array();
	
	while ( $stmt->fetch() ) {
		$eventTypes[$eventTypeId] = $eventType;
	}
	
	$stmt->close();

	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $eventTypes
	];
}

function addNewEvent() {
	global $mysqli;
	
	if ( ! isset($_GET["key"]) || ! isset(API_KEY[$_GET["key"]]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a valid API key.",
			"status"	=> 400
		];
	}
	
	$eventName = "";
	$countryCode = "";
	$eventDate = "";
	$eventTypeId = "";
	$playerCount = "";
	$apiKey = $_GET["key"];
	
	if ( isset($_GET["eventName"]) )	$eventName = $_GET["eventName"];
	if ( isset($_GET["countryCode"]) )	$countryCode = strtoupper($_GET["countryCode"]);
	if ( isset($_GET["eventDate"]) )	$eventDate = $_GET["eventDate"];
	if ( isset($_GET["eventTypeId"]) )	$eventTypeId = $_GET["eventTypeId"];
	if ( isset($_GET["playerCount"]) )	$playerCount = $_GET["playerCount"];
	
	if ( $eventName == "" || $countryCode == "" || $eventDate == "" || $eventTypeId == "" ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a minimum of an event name, a country code, a date and an event type to be specified.",
			"status"	=> 400
		];
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$stmt = $mysqli->prepare("Insert Into events ( eventName, country, date, eventTypeId, playerCount, api ) Values ( ?, ?, ?, ?, ?, ? );");
	$stmt->bind_param("sssiis", $eventName, $countryCode, $eventDate, $eventTypeId, $playerCount, $apiKey);
	$stmt->execute();
	$eventId = $stmt->insert_id;
	$stmt->close();
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $eventId
	];
}

function addNewResult() {
	global $mysqli;
	
	if ( ! isset($_GET["key"]) || ! isset(API_KEY[$_GET["key"]]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a valid API key.",
			"status"	=> 400
		];
	}
	
	$eventId = "";
	$playerId = "";
	$position = "";
	$team = array();
	$apiKey = $_GET["key"];
	
	if ( isset($_GET["eventId"]) )		$eventId = $_GET["eventId"];
	if ( isset($_GET["playerId"]) )		$playerId = $_GET["playerId"];
	if ( isset($_GET["position"]) )		$position = $_GET["position"];
	
	if ( isset($_GET["pokemon1"]) )		$team[0] = decodePokemonShowdown(base64_decode($_GET["pokemon1"]));
	if ( isset($_GET["pokemon2"]) )		$team[1] = decodePokemonShowdown(base64_decode($_GET["pokemon2"]));
	if ( isset($_GET["pokemon3"]) )		$team[2] = decodePokemonShowdown(base64_decode($_GET["pokemon3"]));
	if ( isset($_GET["pokemon4"]) )		$team[3] = decodePokemonShowdown(base64_decode($_GET["pokemon4"]));
	if ( isset($_GET["pokemon5"]) )		$team[4] = decodePokemonShowdown(base64_decode($_GET["pokemon5"]));
	if ( isset($_GET["pokemon6"]) )		$team[5] = decodePokemonShowdown(base64_decode($_GET["pokemon6"]));
	
	if ( $eventId == "" || $playerId == "" || $position == "" ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a minimum of an event ID, a player ID, a finishing position and a team.",
			"status"	=> 400
		];
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$encodedTeam = json_encode($team);
	
	$stmt = $mysqli->prepare("Insert Into results ( eventId, playerId, position, team, api ) Values ( ?, ?, ?, ?, ? );");
	$stmt->bind_param("iiiss", $eventId, $playerId, $position, $encodedTeam, $apiKey);
	$stmt->execute();
	echo $stmt->error;
	$resultId = $stmt->insert_id;
	$stmt->close();
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $resultId
	];
}

function deleteEvent() {
	global $mysqli;
	
	if ( ! isset($_GET["key"]) || ! isset(API_KEY[$_GET["key"]]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a valid API key.",
			"status"	=> 400
		];
	}
	
	$eventId = "";
	
	if ( isset($_GET["eventId"]) )	$eventId = $_GET["eventId"];
	
	if ( $eventId == "" ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires an event ID be specified.",
			"status"	=> 400
		];
	}
		
	$sql = "Delete From results Where eventId = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i", $eventId);
	$stmt->execute();
	$stmt->close();

	$sql = "Delete From events Where id = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i", $eventId);
	$stmt->execute();
	$stmt->close();
	
	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> null
	];
}

function setSessionKey() {
	session_start();
	
	if ( isset($_GET["apiKey"]) && isset(API_KEY[$_GET["apiKey"]]) ) {
		$_SESSION["apiKey"] = $_GET["apiKey"];

		return [
			"result"	=> "success",
			"status"	=> 200,
			"data"		=> API_KEY[$_GET["apiKey"]]
		];
	} else {
		return [
			"result"	=> "error",
			"status"	=> 400,
			"error"		=> "This API call requires a valid API key."
		];
	}
}

function mergePlayers() {
	global $mysqli;
	
	if ( ! isset($_GET["key"]) || ! isset(API_KEY[$_GET["key"]]) ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires a valid API key.",
			"status"	=> 400
		];
	}
	
	$oldPlayerId = "";
	$newPlayerId = "";
	
	if ( isset($_GET["oldPlayerId"]) )	$oldPlayerId = $_GET["oldPlayerId"];
	if ( isset($_GET["newPlayerId"]) )	$newPlayerId = $_GET["newPlayerId"];
	
	if ( $oldPlayerId == "" || $newPlayerId == "" ) {
		return [
			"result"	=> "error",
			"error"		=> "This API call requires an old player ID and a new player ID to merge.",
			"status"	=> 400
		];
	}
	
	$sql = "Select p.id, p.twitter From players p Where id = ? And active = 1";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->bind_result($oldPlayerId, $oldTwitter);

	$id = $oldPlayerId;
	$stmt->execute();

	if ( ! $stmt->fetch() ) {
		return [
			"result"	=> "error",
			"error"		=> "The old player ID could not be found.",
			"status"	=> 400
		];
	}
	
	$stmt->close();

	$sql = "Select p.id, p.playerName, p.country, p.facebook, p.twitter, p.youtube, p.twitch ";
	$sql .= " From players p Where id = ? And active = 1";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->bind_result($newPlayerId, $playerName, $countryCode, $facebook, $twitter, $youtube, $twitch);
	
	$id = $newPlayerId;
	$stmt->execute();

	if ( ! $stmt->fetch() ) {
		return [
			"result"	=> "error",
			"error"		=> "The new player ID could not be found.",
			"status"	=> 400
		];
	}

	$stmt->close();
	
	if ( ($twitter == "" || $twitter == null ) && $oldTwitter != "" ) $twitter = $oldTwitter;

	$stmt = $mysqli->prepare("Insert Into players ( playerName, country, facebook, twitter, youtube, twitch, api ) Values ( ?, ?, ?, ?, ?, ?, ? );");
	$stmt->bind_param("sssssss", $playerName, $countryCode, $facebook, $twitter, $youtube, $twitch, $_GET["key"]);
	
	$stmt->execute();
	$mergedPlayerId = $stmt->insert_id;
	$stmt->close();
	
	$stmt = $mysqli->prepare("Update players Set active = 0 Where id = ? Or id = ?;");
	$stmt->bind_param("ii", $oldPlayerId, $newPlayerId);
	$stmt->execute();
	$stmt->close();

	$stmt = $mysqli->prepare("Update results Set playerId = " . $mergedPlayerId . " Where playerId = ? Or playerId = ?;");
	$stmt->bind_param("ii", $oldPlayerId, $newPlayerId);
	$stmt->execute();
	$stmt->close();

	return [
		"result"	=> "success",
		"status"	=> 200,
		"data"		=> $mergedPlayerId
	];	
}

$mysqli->close();

?>