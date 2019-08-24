<?php

include_once("resources/php/config.php");
include_once("resources/php/functions.php");
include_once("resources/php/countries.php");
include_once("resources/php/pokemon.php");

const ALLOWED_QUERY_PARAMETERS = array("date", "q");

const ALLOWED_SETTINGS_PARAMETERS = array(
	"format" => "json",
);

if ( $_SERVER['REQUEST_METHOD'] == "PUT" ) {
	parse_str(file_get_contents('php://input'), $_PUT);
} elseif ( $_SERVER['REQUEST_METHOD'] == "DELETE" ) {
	parse_str(file_get_contents('php://input'), $_DELETE);
}

function apiResource() {
	$request = explode("/", $_GET["request"]);
	$method = $_SERVER['REQUEST_METHOD'];
	if ( $request[count($request) - 1] == "" ) unset($request[count($request) - 1]);
	
	$resource = $request[0];
	
	$query = ALLOWED_SETTINGS_PARAMETERS;
	foreach($_GET as $key => $value) {
		if ( in_array($key, ALLOWED_QUERY_PARAMETERS) ) {
			$query[$key] = explode(",", $value);
		}

		if ( isset(ALLOWED_SETTINGS_PARAMETERS[$key]) ) {
			$query[$key] = $value;
		}
	}
	
	switch ( $resource ) {
		case "events":
			return apiResourceEvents($method, $request, $query);
			break;

		case "players":
			return apiResourcePlayers($method, $request, $query);
			break;

		case "results":
			return apiResourceResults($method, $request, $query);
			break;

		case "seasons":
			return apiResourceSeasons($method, $request, $query);
			break;

		case "event-types":
			return apiResourceEventTypes($method, $request, $query);
			break;

		case "pokemon":
			return apiResourcePokemon($method, $request, $query);
			break;

		case "countries":
			return apiResourceCountries($method, $request, $query);
			break;
			
		default:
			apiReturnCode(400);
			return array();
			break;
	}	
}

function apiReturnCode($code) {
	switch ( $code ) {
		case 200:
			header("HTTP/1.1 200 OK");
		case 400:
			header("HTTP/1.1 400 Bad Request");
		case 403:
			header("HTTP/1.1 403 Forbidden");
		case 404:
			header("HTTP/1.1 404 Not Found");
		case 405:
			header("HTTP/1.1 405 Method Not Allowed");
		case 500:
			header("HTTP/1.1 500 Internal Server Error");
	}
}

function addQueryParameter(&$query, $key, $value) {
	if ( ! isset($query[$key]) ) {
		$query[$key] = array();
	}
	
	$query[$key][count($query[$key])] = $value;
}

function openDatabase() {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	mysqli_set_charset($mysqli, "utf8");
	
	return $mysqli;
}

function closeDatabase($mysqli) {
	$mysqli->close();
}

function apiResourceEvents($method, $request, $query) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getEvents($query, false);
				break;
			case "POST":
				return addEvent();
				break;
			case "PUT":
				return updateEvent();
				break;
			case "DELETE":
				return deleteEvent();
				break;
			default:
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$eventId = $request[1];
		addQueryParameter($query, "eventId", $eventId);
		
		if ( ! is_numeric($eventId) ) {
			apiReturnCode(400);
		} else {
			if ( ! isset($request[2]) ) {
				switch ($method) {
					case "GET":
						return getEvents($query, true);
						break;
					default:
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "players":
						return apiResourcePlayers($method, array_slice($request, 2), $query);
						break;
					case "results":
						return apiResourceResults($method, array_slice($request, 2), $query);
						break;
					default:
						apiReturnCode(400);
						return array();
						break;
				}
			}
		}
	}

}

function getEvents($query, $detail) {	
	$mysqli = openDatabase();
	
	$sql = "
	Select
		e.id As eventId,
		e.eventName,
		e.country As eventCountryCode,
		e.date As eventDate,
		e.eventTypeId,
		e.playerCount,
		p.id As winnerId,
		p.playerName As winnerName,
		p.country As winnerCountryCode,
		s.year As season,
		et.label As eventType,
		et.points as points
	From
		events e
			Inner Join results r
				On e.id = r.eventId
			Inner Join players p
				On r.playerId = p.id
			Inner Join eventTypes et
				On e.eventTypeId = et.id
			Inner Join seasons s
				On et.seasonId = s.id
	Where
		r.position = 1";
		
	if ( isset($query["eventId"]) ) {
		$sql .= " And (1=0";

		foreach($query["eventId"] as $eventId) {
			$sql .= " Or e.id = " . $mysqli->real_escape_string($eventId);
		}
		
		$sql .= ")";
	}

	if ( isset($query["countryCode"]) ) {
		$sql .= " And (1=0";

		foreach($query["countryCode"] as $countryCode) {
			$sql .= " Or e.country = '" . strtoupper($mysqli->real_escape_string($countryCode)) . "'";
		}
		
		$sql .= ")";
	}

	if ( isset($query["playerId"]) ) {
		$sql .= " And e.id In (Select eventId From results Where playerId In (-1";

		foreach($query["playerId"] as $playerId) {
			$sql .= ", " . $mysqli->real_escape_string($playerId);
		}
		
		$sql .= "))";
	}

	$events = $mysqli->query($sql);
	$eventJson = array();
	
	while ( $event = $events->fetch_assoc() ) {
		$eventCountryCode = strtoupper($event["eventCountryCode"]);
		if ( $eventCountryCode == "" ) $eventCountryCode = "XXX";
		
		$winnerCountryCode = strtoupper($event["winnerCountryCode"]);
		if ( $winnerCountryCode == "" ) $winnerCountryCode = "XXX";

		$singleEventJson = array(
			"id"				=> (int)$event["eventId"],
			"name"				=> $event["eventName"],
			"countryCode"		=> $eventCountryCode,
			"country"			=> VALID_COUNTRY_CODES[$eventCountryCode],
			"date"				=> $event["eventDate"],
			"eventTypeId"		=> $event["eventTypeId"],
			"playerCount"		=> (int)$event["playerCount"],
			"season"			=> $event["season"],
			"winner"			=> array(
				"id"			=> (int)$event["winnerId"],
				"name"			=> $event["winnerName"],
				"countryCode"	=> $winnerCountryCode,
				"country"		=> VALID_COUNTRY_CODES[$winnerCountryCode]
			)
		);
		
		if ( $detail ) {
			$singleEventJson["points"] = $event["points"];
			$singleEventJson["eventType"] = $event["eventType"];
			$eventJson = $singleEventJson;
			break;
		} else {
			$eventJson[$event["eventId"]] = $singleEventJson;	
		}
	}
	
	$events->free();
	closeDatabase($mysqli);

	return $eventJson;
}

function addEvent() {
	$mysqli = openDatabase();
	
	if ( ! isset($_POST["key"]) || ! isset(API_KEY[$_POST["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	$eventName = "";
	$countryCode = "";
	$eventDate = "";
	$eventTypeId = "";
	$playerCount = "";
	$apiKey = $_POST["key"];
	
	if ( isset($_POST["eventName"]) )	$eventName = $_POST["eventName"];
	if ( isset($_POST["countryCode"]) )	$countryCode = strtoupper($_POST["countryCode"]);
	if ( isset($_POST["eventDate"]) )	$eventDate = $_POST["eventDate"];
	if ( isset($_POST["eventTypeId"]) )	$eventTypeId = $_POST["eventTypeId"];
	if ( isset($_POST["playerCount"]) )	$playerCount = $_POST["playerCount"];
	
	if ( $eventName == "" || $countryCode == "" || $eventDate == "" || $eventTypeId == "" ) {
		apiReturnCode(400);
		return;
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$stmt = $mysqli->prepare("Insert Into events ( eventName, country, date, eventTypeId, playerCount, api ) Values ( ?, ?, ?, ?, ?, ? );");
	$stmt->bind_param("sssiis", $eventName, $countryCode, $eventDate, $eventTypeId, $playerCount, $apiKey);
	$stmt->execute();
	$eventId = $stmt->insert_id;
	$stmt->close();
	
	closeDatabase($mysqli);
	
	return array(
		"id"	=> $eventId
	);
}

function updateEvent() {
	global $_PUT;

	if ( ! isset($_PUT["key"]) || ! isset(API_KEY[$_PUT["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	$eventName = "";
	$countryCode = "";
	$eventDate = "";
	$eventTypeId = "";
	$playerCount = "";
	$apiKey = $_PUT["key"];
	
	if ( isset($_PUT["eventId"]) )		$eventId = $_PUT["eventId"];
	if ( isset($_PUT["eventName"]) )	$eventName = $_PUT["eventName"];
	if ( isset($_PUT["countryCode"]) )	$countryCode = strtoupper($_PUT["countryCode"]);
	if ( isset($_PUT["eventDate"]) )	$eventDate = $_PUT["eventDate"];
	if ( isset($_PUT["eventTypeId"]) )	$eventTypeId = $_PUT["eventTypeId"];
	if ( isset($_PUT["playerCount"]) )	$playerCount = $_PUT["playerCount"];
	
	if ( $eventId == "" || $eventName == "" || $countryCode == "" || $eventDate == "" || $eventTypeId == "" ) {
		apiReturnCode(405);
		return;
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$sql = "Update events Set eventName = ?, country = ?, date = ?, eventTypeId = ?, playerCount = ?, api = ? Where id = ?;";
	$mysqli = openDatabase();
	
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sssiisi", $eventName, $countryCode, $eventDate, $eventTypeId, $playerCount, $apiKey, $eventId);
	$stmt->execute();
	$stmt->close();
	
	closeDatabase($mysqli);
	
	return array();
}

function deleteEvent() {
	global $_DELETE;
	
	if ( ! isset($_DELETE["key"]) || ! isset(API_KEY[$_DELETE["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	if ( isset($_DELETE["eventId"]) )	$eventId = $_DELETE["eventId"];
	
	if ( $eventId == "" ) {
		apiReturnCode(405);
		return;
	}
		
	$mysqli = openDatabase();
	
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
	
	closeDatabase($mysqli);
	
	return array();
}

function apiResourcePlayers($method, $request, $query) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getPlayers($query, false);
				break;
			case "POST":
				return addPlayer();
				break;
			default:
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$playerId = $request[1];
		addQueryParameter($query, "playerId", $playerId);
		
		if ( ! is_numeric($playerId) ) {
			apiReturnCode(400);
		} else {
			if ( ! isset($request[2]) ) {
				switch ($method) {
					case "GET":
						return getPlayers($query, true);
						break;
					case "PUT":
						return updatePlayer($playerId);
						break;
					default:
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "events":
						return apiResourceEvents($method, array_slice($request, 2), $query);
						break;
					case "results":
						return apiResourceResults($method, array_slice($request, 2), $query);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getPlayers($query, $detail) {	
	$mysqli = openDatabase();
	
	$sql = "
	Select
		p.id As playerId,
		p.playerName As playerName,
		p.country As playerCountryCode,
		p.youtube,
		p.twitch,
		p.twitter,
		p.facebook
	From
		players p
	Where
		p.active = 1";
	
	if ( isset($query["playerId"]) ) {
		$sql .= " And (1=0";

		foreach($query["playerId"] as $playerId) {
			$sql .= " Or p.id = " . $mysqli->real_escape_string($playerId);
		}
		
		$sql .= ")";
	}

	if ( isset($query["countryCode"]) ) {
		$sql .= " And (1=0";

		foreach($query["countryCode"] as $countryCode) {
			$sql .= " Or p.country = '" . strtoupper($mysqli->real_escape_string($countryCode)) . "'";
		}
		
		$sql .= ")";
	}
	
	if ( isset($query["eventId"]) ) {
		$sql .= " And p.id In (Select playerId From results Where eventId In (-1";

		foreach($query["eventId"] as $eventId) {
			$sql .= ", " . $mysqli->real_escape_string($eventId);
		}
		
		$sql .= "))";
	}
	
	$sql .= " Order By Trim(p.playerName)";
	
	$players = $mysqli->query($sql);
	$playerJson = array();
	
	while ( $player = $players->fetch_assoc() ) {
		$singlePlayerJson = array();
		
		$playerCountryCode = strtoupper($player["playerCountryCode"]);
		if ( $playerCountryCode == "" ) $playerCountryCode = "XXX";
		
		if ( $query["format"] == "dropdown" ) {
			$includePlayer = true;
			
			if ( isset($query["q"]) && $query["q"][0] != "" ) {
				$includePlayer = false;
				
				foreach($query["q"] as $term) {
					if ( strlen($term) > 0 ) {
						if ( strpos(sanitize($player["playerName"]), sanitize($term)) !== false ) {
							$includePlayer = true;
							break;
						}
					}
				}
			}
			
			if ( $includePlayer ) {
				$singlePlayerJson = array(
					"id"		=> (int)$player["playerId"],
					"text"		=> getFlagEmoji($playerCountryCode) . " " . $player["playerName"] . " [ID: " . $player["playerId"] . "]"
				);
			}
		} else {
			$singlePlayerJson = array(
				"id"			=> (int)$player["playerId"],
				"name"			=> trim($player["playerName"]),
				"countryCode"	=> $playerCountryCode,
				"country"		=> VALID_COUNTRY_CODES[$playerCountryCode],
				"social"		=> ($player["twitter"] == "" ? array() : array("twitter" => $player["twitter"])),
				"flagEmoji"		=> getFlagEmoji($playerCountryCode)
			);
		}

		if ( $query["format"] == "dropdown" ) {
			$playerJson[count($playerJson)] = $singlePlayerJson;
		} elseif ( $detail ) {
			$playerJson[$player["playerId"]] = $singlePlayerJson;
			break;
		} else {
			$playerJson[$player["playerId"]] = $singlePlayerJson;
		}
	}
	
	$players->free();
	
	if ( $query["format"] != "dropdown" && ($detail || $query["format"] == "table") ) {
		$sql = "
		Select
			r.playerId As playerId,
			r.id As resultId,
			e.id As lastEventId,
			e.eventName As lastEventName,
			e.date As lastEventDate,
			r.team As lastUsedTeam
		From
			results r
				Inner Join events e
					On r.eventId = e.id
				Inner Join eventTypes et
					On e.eventTypeId = et.id
		Where
			r.playerId In (-1";
		
		foreach($playerJson as $singlePlayerJson) {
			$sql .= ", " . $mysqli->real_escape_string($singlePlayerJson["id"]);
		}
		
		$sql .= ") Order By e.date Desc";		
		$players = $mysqli->query($sql);

		while ( $player = $players->fetch_assoc() ) {
			$playerId = $player["playerId"];
			
			if ( ! isset($playerJson[$playerId]["lastEvent"]) ) {
				$team = sortPokemonTeam(json_decode($player["lastUsedTeam"], true));
				
				$playerJson[$playerId]["lastEvent"] = array(
					"id"	=> (int)$player["lastEventId"],
					"name"	=> $player["lastEventName"],
					"date"	=> $player["lastEventDate"],
					"team"	=> array()
				);
				
				foreach($team as $pokemon) {
					$pokemonData = decodePokemonShowdown(encodePokemonShowdown($pokemon));
					$pokemonName = preg_replace("/[^a-z0-9\%]/", "", strtolower($pokemonData["pokemon"]));
					
					if ( isset(POKEMON_NAME_TO_ID[$pokemonName]) ) {
						$pokemonId = POKEMON_NAME_TO_ID[$pokemonName];
					} else {
						$pokemonId = -1;
					}
					
					$playerJson[$playerId]["lastEvent"]["team"][count($playerJson[$playerId]["lastEvent"]["team"])] = array(
						"id"		=> $pokemonId,
						"name"		=> decodePokemonLabel($pokemonData),
						"class"		=> getSpriteClass($pokemonData)
					);
				}
			}
		}
	}
	
	closeDatabase($mysqli);
	
	if ( $query["format"] == "dropdown" ) {
		return array("results" => $playerJson);
	} elseif ( $detail ) {
		return $playerJson[$query["playerId"][0]];
	} else {
		return $playerJson;
	}
}

function addPlayer() {
	$mysqli = openDatabase();
	
	if ( ! isset($_POST["key"]) || ! isset(API_KEY[$_POST["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	$playerName = "";
	$countryCode = "";
	$twitter = "";
	$apiKey = $_POST["key"];
	
	if ( isset($_POST["playerName"]) )	$playerName = trim($_POST["playerName"]);
	if ( isset($_POST["countryCode"]) )	$countryCode = strtoupper($_POST["countryCode"]);
	if ( isset($_POST["twitter"]) )		$twitter = $_POST["twitter"];
	
	if ( $playerName == "" || $countryCode == "" ) {
		apiReturnCode(400);
		return;
	}
	
	$stmt = $mysqli->prepare("Insert Into players ( playerName, country, twitter, api ) Values ( ?, ?, ?, ? );");
	$stmt->bind_param("ssss", $playerName, $countryCode, $twitter, $apiKey);
	$stmt->execute();
	
	if ( $mysqli->error != "" ) {
		apiReturnCode(500);
		return;
	}
	
	$playerId = $stmt->insert_id;
	$stmt->close();
	
	closeDatabase($mysqli);
	
	return array(
		"id" => $playerId
	);
}

function updatePlayer($playerId) {
	global $_PUT;
	
	if ( ! isset($_PUT["key"]) || ! isset(API_KEY[$_PUT["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	if ( isset($_PUT["mergeId"]) ) {
		$mergeId = "";		
		if ( isset($_PUT["mergeId"]) )	$mergeId = $_PUT["mergeId"];
		
		if ( $playerId == "" || $mergeId == "" ) {
			apiReturnCode(405);
			return;
		}
		
		$sql = "Select p.id, p.twitter From players p Where id = ? And active = 1";
	
		$mysqli = openDatabase();
	
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i", $playerId);
		$stmt->bind_result($oldPlayerId, $oldTwitter);	
		$stmt->execute();
	
		if ( ! $stmt->fetch() ) {
			apiReturnCode(405);
			return;
		}
		
		$stmt->close();
	
		$sql = "Select p.id, p.playerName, p.country, p.facebook, p.twitter, p.youtube, p.twitch ";
		$sql .= " From players p Where id = ? And active = 1";
	
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i", $mergeId);
		$stmt->bind_result($newPlayerId, $playerName, $countryCode, $facebook, $twitter, $youtube, $twitch);
		$stmt->execute();
	
		if ( ! $stmt->fetch() ) {
			apiReturnCode(405);
			return;
		}
	
		$stmt->close();
		
		if ( ($twitter == "" || $twitter == null ) && $oldTwitter != "" ) $twitter = $oldTwitter;
	
		$stmt = $mysqli->prepare("Insert Into players ( playerName, country, facebook, twitter, youtube, twitch, api ) Values ( ?, ?, ?, ?, ?, ?, ? );");
		$stmt->bind_param("sssssss", $playerName, $countryCode, $facebook, $twitter, $youtube, $twitch, $_PUT["key"]);
		
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
	
		closeDatabase($mysqli);
	
		return array(
			"id"	=> $mergedPlayerId
		);	
	} else {
		$playerName = "";
		$countryCode = "";
		$twitter = "";
		$youtube = "";
		$facebook = "";
		$twitch = "";
		$apiKey = $_PUT["key"];
		
		if ( isset($_PUT["playerName"]) )	$playerName = $_PUT["playerName"];
		if ( isset($_PUT["countryCode"]) )	$countryCode = strtoupper($_PUT["countryCode"]);
		if ( isset($_PUT["twitter"]) )		$twitter = $_PUT["twitter"];
		if ( isset($_PUT["youtube"]) )		$youtube = $_PUT["youtube"];
		if ( isset($_PUT["facebook"]) )		$facebook = $_PUT["facebook"];
		if ( isset($_PUT["twitch"]) )		$twitch = $_PUT["twitch"];
		
		if ( $playerId == "" || $playerName == "" || $countryCode == "" ) {
			apiReturnCode(405);
			return;
		}
		
		$mysqli = openDatabase();
		
		$stmt = $mysqli->prepare("Update players Set playerName = ?, country = ?, twitter = ?, youtube = ?, facebook = ?, " .
			"twitch = ?, api = ? Where id = ?;");
		$stmt->bind_param("sssssssi", $playerName, $countryCode, $twitter, $youtube, $facebook, $twitch, $apiKey, $playerId);
		$stmt->execute();
		$stmt->close();
		
		closeDatabase($mysqli);
	
		return array();
	}
}

function apiResourceResults($method, $request, $query) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getResults($query, false);
				break;
			case "POST":
				return addResult();
				break;
			case "PUT":
				return updateResult();
				break;
			case "DELETE":
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$resultId = $request[1];
		addQueryParameter($query, "resultId", $resultId);
		
		if ( ! is_numeric($resultId) ) {
			apiReturnCode(400);
		} else {
			if ( ! isset($request[2]) ) {
				switch ($method) {
					case "GET":
						return getResults($query, true);
						break;
					case "POST":
					case "PUT":
					case "DELETE":
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getResults($query, $detail) {	
	$mysqli = openDatabase();
	
	$sql = "
	Select
		r.id As resultId,
		r.playerId As playerId,
		r.eventId As eventId,
		r.position As position,
		r.team As team,
		r.qrlink As rentalTeam,
		e.playerCount As playerCount,
		et.points As points,
		e.eventName As eventName,
		p.playerName As playerName,
		p.country As playerCountryCode,
		e.country As eventCountryCode,
		e.date As eventDate
	From
		results r
			Inner Join events e
				On r.eventId = e.id
			Inner Join eventTypes et
				On e.eventTypeId = et.id
			Inner Join players p
				On r.playerId = p.id
	Where
		1=1";
	
	if ( isset($query["playerId"]) ) {
		$sql .= " And r.playerId In (-1";

		foreach($query["playerId"] as $playerId) {
			$sql .= ", " . $mysqli->real_escape_string($playerId);
		}
		
		$sql .= ")";
	}

	if ( isset($query["eventId"]) ) {
		$sql .= " And r.eventId In (-1";

		foreach($query["eventId"] as $eventId) {
			$sql .= ", " . $mysqli->real_escape_string($eventId);
		}
		
		$sql .= ")";
	}
	
	if ( isset($query["q"]) && strlen($query["q"][0]) > 0 ) {
		$sql .= " And (1=0";
		
		foreach(explode(" ", $query["q"][0]) as $term) {
			$sql .= " Or Lower(r.team) Like '%" . $term . "%'";
		}

		$sql .= ")";
	}
	
	$sql .= " Order By e.date DESC, r.position;";
	
	$results = $mysqli->query($sql);
	$resultsJson = array();
	
	while ( $result = $results->fetch_assoc() ) {
		$team = sortPokemonTeam(json_decode($result["team"], true));
		$resultJsonId = $result["resultId"];
		
		$resultsJson[$resultJsonId]["resultId"] = (int)$result["resultId"];
		
		if ( $query["format"] == "full" ) {
			$playerCountryCode = $result["playerCountryCode"];
			if ( $playerCountryCode == "" ) $playerCountryCode = "XXX";
			
			$resultsJson[$resultJsonId]["player"] = array(
				"id"			=> (int)$result["playerId"],
				"name"			=> $result["playerName"],
				"countryCode"	=> $playerCountryCode,
				"flagEmoji"		=> getFlagEmoji($playerCountryCode)
			);

			$eventCountryCode = $result["eventCountryCode"];
			if ( $eventCountryCode == "" ) $eventCountryCode = "XXX";
			
			$resultsJson[$resultJsonId]["event"] = array(
				"id"			=> (int)$result["eventId"],
				"name"			=> $result["eventName"],
				"countryCode"	=> $eventCountryCode,
				"flagEmoji"		=> getFlagEmoji($eventCountryCode),
				"date"			=> $result["eventDate"]
			);
		} else {
			$resultsJson[$resultJsonId]["playerId"] = (int)$result["playerId"];
			$resultsJson[$resultJsonId]["eventId"] = (int)$result["eventId"];
		}
		
		$resultsJson[$resultJsonId]["position"] = (int)$result["position"];
		if ( $result["rentalTeam"] != "" ) {
			$resultsJson[$resultJsonId]["rentalTeamUrl"] = $result["rentalTeam"];
		}
		$resultsJson[$resultJsonId]["points"] = convertPositionToPoints(
			$result["position"],
			$result["playerCount"],
			json_decode($result["points"], true)
		);
		
		$resultsJson[$resultJsonId]["team"] = array();
		
		foreach($team as $pokemon) {
			$pokemonData = decodePokemonShowdown(encodePokemonShowdown($pokemon));
			$pokemonName = preg_replace("/[^a-z0-9\%]/", "", strtolower($pokemonData["pokemon"]));
			
			if ( isset(POKEMON_NAME_TO_ID[$pokemonName]) ) {
				$pokemonId = POKEMON_NAME_TO_ID[$pokemonName];
			} else {
				$pokemonId = -1;
			}
			
			if ( $query["format"] == "full" || $detail ) {
				$teamId = count($resultsJson[$resultJsonId]["team"]);
				
				$resultsJson[$resultJsonId]["team"][$teamId] = $pokemonData;
				$resultsJson[$resultJsonId]["team"][$teamId]["id"] = $pokemonId;
				$resultsJson[$resultJsonId]["team"][$teamId]["name"] = decodePokemonLabel($pokemonData);
				$resultsJson[$resultJsonId]["team"][$teamId]["class"] = getSpriteClass($pokemonData);
				$resultsJson[$resultJsonId]["team"][$teamId]["showdown"] = encodePokemonShowdown($pokemon);
			} else {
				$resultsJson[$resultJsonId]["team"][count($resultsJson[$resultJsonId]["team"])] = array(
					"id"		=> $pokemonId,
					"name"		=> decodePokemonLabel($pokemonData),
					"class"		=> getSpriteClass($pokemonData)
				);
			}
		}
	}
	
	$results->free();
	closeDatabase($mysqli);
	
	if ( $detail ) {
		return $resultsJson[$query["resultId"][0]];
	} else {
		return $resultsJson;
	}
}

function addResult() {
	if ( ! isset($_POST["key"]) || ! isset(API_KEY[$_POST["key"]]) ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	$playerId = "";
	$position = "";
	$team = array();
	$apiKey = $_POST["key"];
	
	if ( isset($_POST["eventId"]) )			$eventId = $_POST["eventId"];
	if ( isset($_POST["playerId"]) )		$playerId = $_POST["playerId"];
	if ( isset($_POST["position"]) )		$position = $_POST["position"];
	
	if ( isset($_POST["pokemon1"]) )		$team[0] = decodePokemonShowdown(base64_decode($_POST["pokemon1"]));
	if ( isset($_POST["pokemon2"]) )		$team[1] = decodePokemonShowdown(base64_decode($_POST["pokemon2"]));
	if ( isset($_POST["pokemon3"]) )		$team[2] = decodePokemonShowdown(base64_decode($_POST["pokemon3"]));
	if ( isset($_POST["pokemon4"]) )		$team[3] = decodePokemonShowdown(base64_decode($_POST["pokemon4"]));
	if ( isset($_POST["pokemon5"]) )		$team[4] = decodePokemonShowdown(base64_decode($_POST["pokemon5"]));
	if ( isset($_POST["pokemon6"]) )		$team[5] = decodePokemonShowdown(base64_decode($_POST["pokemon6"]));
	
	if ( $eventId == "" || $playerId == "" || $position == "" ) {
		apiReturnCode(400);
		return;
	}
	
	$encodedTeam = json_encode($team);
	
	$mysqli = openDatabase();
	
	$stmt = $mysqli->prepare("Insert Into results ( eventId, playerId, position, team, api ) Values ( ?, ?, ?, ?, ? );");
	$stmt->bind_param("iiiss", $eventId, $playerId, $position, $encodedTeam, $apiKey);
	$stmt->execute();
	$resultId = $stmt->insert_id;
	$stmt->close();
	
	return array(
		"id"		=> $resultId
	);
}

function updateResult() {
	global $_PUT;
	
	if ( ! isset($_PUT["key"]) || ! isset(API_KEY[$_PUT["key"]]) ) {
		apiReturnCode(403);
		return;
	}

	$eventId = "";
	$playerId = "";
	$position = "";
	$team = array();
	$apiKey = $_PUT["key"];
	
	if ( isset($_PUT["eventId"]) )	$eventId = $_PUT["eventId"];
	if ( isset($_PUT["playerId"]) )	$playerId = $_PUT["playerId"];
	if ( isset($_PUT["position"]) )	$position = $_PUT["position"];
	
	if ( isset($_PUT["pokemon1"]) )	$team[0] = decodePokemonShowdown(base64_decode($_PUT["pokemon1"]));
	if ( isset($_PUT["pokemon2"]) )	$team[1] = decodePokemonShowdown(base64_decode($_PUT["pokemon2"]));
	if ( isset($_PUT["pokemon3"]) )	$team[2] = decodePokemonShowdown(base64_decode($_PUT["pokemon3"]));
	if ( isset($_PUT["pokemon4"]) )	$team[3] = decodePokemonShowdown(base64_decode($_PUT["pokemon4"]));
	if ( isset($_PUT["pokemon5"]) )	$team[4] = decodePokemonShowdown(base64_decode($_PUT["pokemon5"]));
	if ( isset($_PUT["pokemon6"]) )	$team[5] = decodePokemonShowdown(base64_decode($_PUT["pokemon6"]));
	
	if ( $eventId == "" || $playerId == "" || $position == "" ) {
		apiReturnCode(405);
		return;
	}
	
	for( $index = 0; $index < 6; $index++ ) {
		if ( ! $team[$index]["valid"] ) {
			apiReturnCode(405);
			return;
		} else {
			$team[$index]["name"] = decodePokemonLabel($team[$index]);
			$team[$index]["class"] = getSpriteClass($team[$index]);
			$team[$index]["showdown"] = encodePokemonShowdown($team[$index]);
		}
	}
	
	$encodedTeam = json_encode($team);

	$mysqli = openDatabase();
		
	$stmt = $mysqli->prepare("Delete From results Where eventId = ? And position = ?;");
	$stmt->bind_param("ii", $eventId, $position);
	$stmt->execute();
	$stmt->close();
	
	$stmt = $mysqli->prepare("Insert Into results ( eventId, playerId, position, team, api ) Values ( ?, ?, ?, ?, ? );");
	$stmt->bind_param("iiiss", $eventId, $playerId, $position, $encodedTeam, $apiKey);
	$stmt->execute();
	$resultId = $stmt->insert_id;
	$stmt->close();
	
	closeDatabase($mysqli);
	
	return array(
		"id"		=> $resultId,
		"team"		=> $team
	);
}

function apiResourceEventTypes($method, $request, $query) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getEventTypes($query, false);
				break;
			case "POST":
			case "PUT":
			case "DELETE":
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$eventTypeId = $request[1];
		addQueryParameter($query, "eventTypeId", $eventTypeId);
		
		if ( ! is_numeric($eventTypeId) ) {
			apiReturnCode(400);
		} else {
			if ( ! isset($request[2]) ) {
				switch ($method) {
					case "GET":
						return getEventTypes($query, true);
						break;
					case "POST":
					case "PUT":
					case "DELETE":
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "events":
						return apiResourceEvents($method, array_splice($request, 2), $query);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getEventTypes($query, $detail) {
	$mysqli = openDatabase();
	
	$sql = "
	Select
		et.id As eventTypeId,
		et.label As eventType,
		et.seasonId As seasonId,
		s.year As season,
		et.points as points
	From
		eventTypes et
			Inner Join seasons s
				On et.seasonId = s.id
	Where
		1=1
	";		
	
	if ( isset($query["season"]) ) {
		$sql .= " And s.year In (-1";

		foreach($query["season"] as $season) {
			$sql .= ", " . $mysqli->real_escape_string($season);
		}
		
		$sql .= ")";
	}
	
	if ( isset($query["date"]) ) {
		$sql .= " And (1=0";
		
		foreach($query["date"] as $date) {
			$sql .= " Or (s.startDate <= '" . $mysqli->real_escape_string($date) . "'";
			$sql .= " And s.endDate >= '" . $mysqli->real_escape_string($date) . "')";
		}
		
		$sql .= ")";
	}
	
	$eventTypes = $mysqli->query($sql);
	$eventTypesJson = array();

	while ( $eventType = $eventTypes->fetch_assoc() ) {
		if ( $query["format"] == "dropdown" ) {
			$eventTypesJson[count($eventTypesJson)] = array(
				"id"	=> $eventType["eventTypeId"],
				"text"	=> $eventType["eventType"]
			);
		} else {
			$eventTypesJson[$eventType["eventTypeId"]] = array(
				"id"		=> $eventType["eventTypeId"],
				"season"	=> $eventType["season"],
				"name"		=> $eventType["eventType"]
			);
		}
	}
	
	$eventTypes->free();

	closeDatabase($mysqli);
	
	if ( $query["format"] == "dropdown" ) {
		return array("results" => $eventTypesJson);
	} else {
		return $eventTypesJson;
	}
}

function apiResourceCountries($method, $request, $query) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getCountries($query);
				break;
			case "POST":
			case "PUT":
			case "DELETE":
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$countryCode = $request[1];
		addQueryParameter($query, "countryCode", $countryCode);
		
		if ( ! isset(VALID_COUNTRY_CODES[strtoupper($countryCode)]) ) {
			apiReturnCode(400);
		} else {
			if ( ! isset($request[2]) ) {
				switch ($method) {
					case "GET":
						return getCountry($countryCode);
						break;
					case "POST":
					case "PUT":
					case "DELETE":
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "events":
						return apiResourceEvents($method, array_splice($request, 2), $query);
						break;
					case "players":
						return apiResourcePlayers($method, array_splice($request, 2), $query);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getCountries($query) {
	$countryList = array();
	
	foreach(VALID_COUNTRY_CODES As $countryCode => $country) {
		$countryCode = strtoupper($countryCode);
		
		if ( $query["format"] == "dropdown" ) {
			$countryList[count($countryList)] = array(
				"id"	=> $countryCode,
				"text"	=> getFlagEmoji($countryCode) . " " . $country
			);
		} else {
			$countryList[$countryCode] = array(
				"countryCode"		=> $countryCode,
				"country"			=> $country,
				"flagEmoji"			=> getFlagEmoji($countryCode),
				"flagUrl"			=> "resources/images/flags/" . strtolower($countryCode) . ".png",
				"isoCountryCode"	=> (isset(ISO_3_TO_2[$countryCode]) ? ISO_3_TO_2[$countryCode] : "")
			);
		}
	}
	
	if ( $query["format"] == "dropdown" ) {
		return array("results" => $countryList);
	} else {
		return $countryList;
	}
}

function convertPositionToPoints($position, $playerCount, $points) {
	$currentPoints = 0;

	foreach ( $points as $point ) {
		if ( $position <= $point["position"] && $playerCount >= $point["kicker"] ) {
			if ( $currentPoints < $point["points"] ) {
				$currentPoints = $point["points"];
			}
		}
	}
	
	return $currentPoints;
}

header('Content-Type: application/json; charset=utf-8');
$json = json_encode(apiResource(), JSON_PRETTY_PRINT);
echo $json;

?>