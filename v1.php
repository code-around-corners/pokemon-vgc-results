<?php

include_once("resources/php/config.php");
include_once("resources/php/functions.php");
include_once("resources/php/countries.php");
include_once("resources/php/pokemon.php");

const ALLOWED_QUERY_PARAMETERS = array("date", "q");

const ALLOWED_SETTINGS_PARAMETERS = array(
	"format" => "json",
);

function apiResource() {
	$request = explode("/", $_GET["request"]);
	$method = $_SERVER['REQUEST_METHOD'];
	if ( $request[count($request) - 1] == "" ) unset($request[count($request) - 1]);
	
	if ( count($request) > 6 ) {
		return array(
			"error" => "Cannot traverse more than 3 levels deep."
		);
	}
	
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
	
	if ( $method == "GET" ) {
		$args = $_GET;
	} else {
		parse_str(file_get_contents('php://input'), $args);
	}
	
	switch ( $resource ) {
		case "events":
			return apiResourceEvents($method, $request, $query, $args);
			break;

		case "players":
			return apiResourcePlayers($method, $request, $query, $args);
			break;

		case "results":
			return apiResourceResults($method, $request, $query, $args);
			break;

		case "seasons":
			return apiResourceSeasons($method, $request, $query, $args);
			break;

		case "event-types":
			return apiResourceEventTypes($method, $request, $query, $args);
			break;

		case "pokemon":
			return apiResourcePokemon($method, $request, $query, $args);
			break;

		case "countries":
			return apiResourceCountries($method, $request, $query, $args);
			break;
			
		case "users":
			return apiResourceUsers($method, $request, $query, $args);
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

function apiResourceEvents($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getEvents($query, false, $args);
				break;
			case "POST":
				return addEvent($args);
				break;
			case "PUT":
				return updateEvent($args);
				break;
			case "DELETE":
				return deleteEvent($args);
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
						return getEvents($query, true, $args);
						break;
					default:
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "players":
						return apiResourcePlayers($method, array_slice($request, 2), $query, $args);
						break;
					case "results":
						return apiResourceResults($method, array_slice($request, 2), $query, $args);
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

function getEvents($query, $detail, $args) {	
	global $mysqli;
	
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
	return $eventJson;
}

function addEvent($args) {
	global $mysqli;
	
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
	
	$eventName = "";
	$countryCode = "";
	$eventDate = "";
	$eventTypeId = "";
	$playerCount = "";
	
	if ( isset($args["eventName"]) )	$eventName = $args["eventName"];
	if ( isset($args["countryCode"]) )	$countryCode = strtoupper($args["countryCode"]);
	if ( isset($args["eventDate"]) )	$eventDate = $args["eventDate"];
	if ( isset($args["eventTypeId"]) )	$eventTypeId = $args["eventTypeId"];
	if ( isset($args["playerCount"]) )	$playerCount = $args["playerCount"];
	
	if ( $eventName == "" || $countryCode == "" || $eventDate == "" || $eventTypeId == "" ) {
		apiReturnCode(400);
		return;
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$stmt = $mysqli->prepare("Insert Into events ( eventName, country, date, eventTypeId, playerCount, createdId, lastUpdatedId ) Values ( ?, ?, ?, ?, ?, ?, ? );");
	$stmt->bind_param("sssiiii", $eventName, $countryCode, $eventDate, $eventTypeId, $playerCount, $userId, $userId);
	$stmt->execute();
	$eventId = $stmt->insert_id;
	$stmt->close();
	
	return array(
		"id"	=> $eventId
	);
}

function updateEvent($args) {
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	$eventName = "";
	$countryCode = "";
	$eventDate = "";
	$eventTypeId = "";
	$playerCount = "";
	
	if ( isset($args["eventId"]) )		$eventId = $args["eventId"];
	if ( isset($args["eventName"]) )	$eventName = $args["eventName"];
	if ( isset($args["countryCode"]) )	$countryCode = strtoupper($args["countryCode"]);
	if ( isset($args["eventDate"]) )	$eventDate = $args["eventDate"];
	if ( isset($args["eventTypeId"]) )	$eventTypeId = $args["eventTypeId"];
	if ( isset($args["playerCount"]) )	$playerCount = $args["playerCount"];
	
	if ( $eventId == "" || $eventName == "" || $countryCode == "" || $eventDate == "" || $eventTypeId == "" ) {
		apiReturnCode(405);
		return;
	}
	
	if ( $playerCount == "" ) $playerCount = 0;
	
	$sql = "Update events Set eventName = ?, country = ?, date = ?, eventTypeId = ?, playerCount = ?, lastUpdatedId = ? Where id = ?;";
	global $mysqli;
	
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sssiisi", $eventName, $countryCode, $eventDate, $eventTypeId, $playerCount, $userId, $eventId);
	$stmt->execute();
	$stmt->close();
	
	return array();
}

function deleteEvent($args) {
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	if ( isset($args["eventId"]) )	$eventId = $args["eventId"];
	
	if ( $eventId == "" ) {
		apiReturnCode(405);
		return;
	}
		
	global $mysqli;
	
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
	
	return array();
}

function apiResourcePlayers($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getPlayers($query, false, $args);
				break;
			case "POST":
				return addPlayer($args);
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
						return getPlayers($query, true, $args);
						break;
					case "PUT":
						return updatePlayer($playerId, $args);
						break;
					default:
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "events":
						return apiResourceEvents($method, array_slice($request, 2), $query, $args);
						break;
					case "results":
						return apiResourceResults($method, array_slice($request, 2), $query, $args);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getPlayers($query, $detail, $args) {	
	global $mysqli;
	
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
		Order By
			e.date Desc";		
		
		$players = $mysqli->query($sql);

		while ( $player = $players->fetch_assoc() ) {
			$playerId = $player["playerId"];
			
			if ( isset($playerJson[$playerId]) ) {
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
	}
	
	if ( $query["format"] == "dropdown" ) {
		return array("results" => $playerJson);
	} elseif ( $detail ) {
		return $playerJson[$query["playerId"][0]];
	} else {
		return $playerJson;
	}
}

function addPlayer($args) {
	global $mysqli;
	
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
		
	$playerName = "";
	$countryCode = "";
	$twitter = "";
	
	if ( isset($args["playerName"]) )	$playerName = trim($args["playerName"]);
	if ( isset($args["countryCode"]) )	$countryCode = strtoupper($args["countryCode"]);
	if ( isset($args["twitter"]) )		$twitter = $args["twitter"];
	
	if ( $playerName == "" || $countryCode == "" ) {
		apiReturnCode(400);
		return;
	}
	
	$stmt = $mysqli->prepare("Insert Into players ( playerName, country, twitter, createdId, lastUpdatedId ) Values ( ?, ?, ?, ?, ? );");
	$stmt->bind_param("sssii", $playerName, $countryCode, $twitter, $userId, $userId);
	$stmt->execute();
	
	if ( $mysqli->error != "" ) {
		apiReturnCode(500);
		return;
	}
	
	$playerId = $stmt->insert_id;
	$stmt->close();
	
	return array(
		"id" => $playerId
	);
}

function updatePlayer($playerId, $args) {
	global $mysqli;
	
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
	
	if ( isset($args["mergeId"]) ) {
		$mergeId = "";		
		if ( isset($args["mergeId"]) )	$mergeId = $args["mergeId"];
		
		if ( $playerId == "" || $mergeId == "" ) {
			apiReturnCode(405);
			return;
		}
		
		$sql = "Select p.id, p.twitter From players p Where id = ? And active = 1";
	
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
	
		$stmt = $mysqli->prepare("Insert Into players ( playerName, country, facebook, twitter, youtube, twitch, createdId, lastUpdatedId ) Values ( ?, ?, ?, ?, ?, ?, ?, ? );");
		$stmt->bind_param("ssssssii", $playerName, $countryCode, $facebook, $twitter, $youtube, $twitch, $userId, $userId);
		
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
		
		if ( isset($args["playerName"]) )	$playerName = $args["playerName"];
		if ( isset($args["countryCode"]) )	$countryCode = strtoupper($args["countryCode"]);
		if ( isset($args["twitter"]) )		$twitter = $args["twitter"];
		if ( isset($args["youtube"]) )		$youtube = $args["youtube"];
		if ( isset($args["facebook"]) )		$facebook = $args["facebook"];
		if ( isset($args["twitch"]) )		$twitch = $args["twitch"];
		
		if ( $playerId == "" || $playerName == "" || $countryCode == "" ) {
			apiReturnCode(405);
			return;
		}
		
		$stmt = $mysqli->prepare("Update players Set playerName = ?, country = ?, twitter = ?, youtube = ?, facebook = ?, " .
			"twitch = ?, lastUpdatedId = ? Where id = ?;");
		$stmt->bind_param("ssssssii", $playerName, $countryCode, $twitter, $youtube, $facebook, $twitch, $userId, $playerId);
		$stmt->execute();
		$stmt->close();
		
		return array();
	}
}

function apiResourceResults($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getResults($query, false, $args);
				break;
			case "POST":
				return addResult($args);
				break;
			case "PUT":
				return updateResult($args);
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
						return getResults($query, true, $args);
						break;
					default:
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

function getResults($query, $detail, $args) {	
	global $mysqli;
	
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
	
	if ( $detail ) {
		return $resultsJson[$query["resultId"][0]];
	} else {
		return $resultsJson;
	}
}

function addResult($args) {
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return;
	}
	
	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return;
	}
	
	$eventId = "";
	$playerId = "";
	$position = "";
	$team = array();
	
	if ( isset($args["eventId"]) )		$eventId = $args["eventId"];
	if ( isset($args["playerId"]) )		$playerId = $args["playerId"];
	if ( isset($args["position"]) )		$position = $args["position"];
	
	if ( isset($args["pokemon1"]) )		$team[0] = decodePokemonShowdown(base64_decode($args["pokemon1"]));
	if ( isset($args["pokemon2"]) )		$team[1] = decodePokemonShowdown(base64_decode($args["pokemon2"]));
	if ( isset($args["pokemon3"]) )		$team[2] = decodePokemonShowdown(base64_decode($args["pokemon3"]));
	if ( isset($args["pokemon4"]) )		$team[3] = decodePokemonShowdown(base64_decode($args["pokemon4"]));
	if ( isset($args["pokemon5"]) )		$team[4] = decodePokemonShowdown(base64_decode($args["pokemon5"]));
	if ( isset($args["pokemon6"]) )		$team[5] = decodePokemonShowdown(base64_decode($args["pokemon6"]));
	
	if ( $eventId == "" || $playerId == "" || $position == "" ) {
		apiReturnCode(400);
		return;
	}
	
	$encodedTeam = json_encode($team);
	
	global $mysqli;
	
	$stmt = $mysqli->prepare("Insert Into results ( eventId, playerId, position, team, createdId, lastUpdatedId ) Values ( ?, ?, ?, ?, ?, ? );");
	$stmt->bind_param("iiisii", $eventId, $playerId, $position, $encodedTeam, $userId, $userId);
	$stmt->execute();
	$resultId = $stmt->insert_id;
	$stmt->close();
	
	return array(
		"id"		=> $resultId
	);
}

function updateResult($args) {
	if ( ! isset($args["session"]) ) {
		apiReturnCode(403);
		return array(
			"error" => "This function requires a valid session key.",
			"data" => $args
		);
	}

	$userId = validateSessionKey($args["session"], $_SERVER["REMOTE_ADDR"]);
	
	if ( ! $userId ) {
		apiReturnCode(403);
		return array(
			"error" => "Invalid session key."
		);
	}

	$eventId = "";
	$playerId = "";
	$position = "";
	$team = array();
	
	if ( isset($args["eventId"]) )	$eventId = $args["eventId"];
	if ( isset($args["playerId"]) )	$playerId = $args["playerId"];
	if ( isset($args["position"]) )	$position = $args["position"];
	
	if ( isset($args["pokemon1"]) )	$team[0] = decodePokemonShowdown(base64_decode($args["pokemon1"]));
	if ( isset($args["pokemon2"]) )	$team[1] = decodePokemonShowdown(base64_decode($args["pokemon2"]));
	if ( isset($args["pokemon3"]) )	$team[2] = decodePokemonShowdown(base64_decode($args["pokemon3"]));
	if ( isset($args["pokemon4"]) )	$team[3] = decodePokemonShowdown(base64_decode($args["pokemon4"]));
	if ( isset($args["pokemon5"]) )	$team[4] = decodePokemonShowdown(base64_decode($args["pokemon5"]));
	if ( isset($args["pokemon6"]) )	$team[5] = decodePokemonShowdown(base64_decode($args["pokemon6"]));
	
	if ( $eventId == "" || $playerId == "" || $position == "" ) {
		apiReturnCode(405);
		return array(
			"error" => "Must specify an event ID, a player ID and a position."
		);
	}
	
	for( $index = 0; $index < 6; $index++ ) {
		if ( ! $team[$index]["valid"] ) {
			apiReturnCode(405);
			return array(
				"error" => "Pokemon #" . (index + 1) . " is invalid."
			);
		} else {
			$team[$index]["name"] = decodePokemonLabel($team[$index]);
			$team[$index]["class"] = getSpriteClass($team[$index]);
			$team[$index]["showdown"] = encodePokemonShowdown($team[$index]);
		}
	}
	
	$encodedTeam = json_encode($team);

	global $mysqli;
		
	$stmt = $mysqli->prepare("Update results Set playerId = ?, team = ?, lastUpdatedId = ? Where eventId = ? And position = ?;");
	$stmt->bind_param("isiii", $playerId, $encodedTeam, $userId, $eventId, $position);
	$stmt->execute();
	$stmt->close();
	
	return array(
		"team"		=> $team
	);
}

function apiResourceEventTypes($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getEventTypes($query, false, $args);
				break;
			default:
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
						return getEventTypes($query, true, $args);
						break;
					default:
						apiReturnCode(405);
						return array();
						break;
				}
			} else {
				switch ( $request[2] ) {
					case "events":
						return apiResourceEvents($method, array_splice($request, 2), $query, $args);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getEventTypes($query, $detail, $args) {
	global $mysqli;
	
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
				"text"	=> "(" . $eventType["season"] . ") " . $eventType["eventType"]
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
	
	if ( $query["format"] == "dropdown" ) {
		return array("results" => $eventTypesJson);
	} else {
		return $eventTypesJson;
	}
}

function apiResourceCountries($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			case "GET":
				return getCountries($query, $args);
				break;
			default:
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
						return getCountry($countryCode, $args);
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
						return apiResourceEvents($method, array_splice($request, 2), $query, $args);
						break;
					case "players":
						return apiResourcePlayers($method, array_splice($request, 2), $query, $args);
						break;
					default:
						apiReturnCode(400);
						break;
				}
			}
		}
	}
}

function getCountries($query, $args) {
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

function apiResourceUsers($method, $request, $query, $args) {
	if ( ! isset($request[1]) ) {
		switch ($method) {
			default:
				apiReturnCode(405);
				return array();
				break;
		}
	} else {
		$username = $request[1];
		addQueryParameter($query, "username", $username);
		
		if ( ! isset($request[2]) ) {
			switch ($method) {
				case "GET":
					return getUserId($username, $args);
					break;
				default:
					apiReturnCode(405);
					return array();
					break;
			}
		} else {
			switch ( $request[2] ) {
				case "sessions":
					switch ( $method ) {
						case "POST":
							return getUserSession($username, $args);
							break;
						case "PUT":
							return validateSession($username, $args);
							break;
						default:
							apiReturnCode(405);
							return array();
							break;
					}
					break;
				default:
					apiReturnCode(400);
					break;
			}
		}
	}
}

function getUserId($username, $args) {
	global $mysqli;
	
	$sql = "
	Select
		u.id,
		u.username,
		u.displayName
	From
		users u
	Where
		u.username = '" . sanitize($username) . "';";
		
	$users = $mysqli->query($sql);
	$userData = array();

	while ( $user = $users->fetch_assoc() ) {
		$userData = array(
			"userId" => (int)$user["id"],
			"username" => $user["username"],
			"name" => $user["displayName"]
		);
	}
	
	$users->free();
	
	if ( count($userData) == 0 ) {
		apiReturnCode(404);
		return array(
			"error" => "Invalid username!"
		);
	}
	
	return $userData;
}

function getUserSession($userId, $args) {
	$password = "";
	
	if ( isset($args["password"]) ) {
		$password = $args["password"];
	}
	
	if ( $password == "" ) {
		apiReturnCode(403);
		return array(
			"error" => "No password specified."
		);
	}
	
	if ( ! is_numeric($userId) ) {
		apiReturnCode(403);
		return array(
			"error" => "Sessions can only be requested by user ID."
		);
	}
	
	global $mysqli;
	
	$sql = "
	Select
		u.id,
		u.username,
		u.displayName,
		u.password,
		u.salt
	From
		users u
	Where
		u.id = " . $userId;

	$users = $mysqli->query($sql);
	$userData = array();

	while ( $user = $users->fetch_assoc() ) {
		$userData = array(
			"userId" => (int)$user["id"],
			"username" => $user["username"],
			"name" => $user["displayName"],
			"password" => $user["password"],
			"salt" => $user["salt"]
		);
	}
	
	$users->free();
	
	if ( count($userData) == 0 ) {
		apiReturnCode(403);
		return array();
	}

	$hash = hash("sha256", $password . $userData["salt"] . PW_PEPPER);
	
	if ( $hash != $userData["password"] ) {
		apiReturnCode(403);
		return array(
			"error" => "Incorrect password."
		);
	}
	
	$sessionKey = hash("sha256", rand());
	$expires = time() + (60 * 60 * 24 * 30);
	
	$sql = "Delete From sessions Where userId = " . $userData["userId"] . ";";
	$mysqli->query($sql);
	
	$sql = "Insert Into sessions ( userId, sessionKey, ip, validUntil ) Values ( " . $userData["userId"] . ", '";
	$sql .= $sessionKey . "', '" . $_SERVER["REMOTE_ADDR"] . "', " . $expires . " );";
	
	$mysqli->query($sql);
	
	return array(
		"userId" => validateSessionKey($sessionKey, $_SERVER["REMOTE_ADDR"]),
		"sessionKey" => $sessionKey
	);
}

function validateSession($userId, $args) {
	if ( ! isset($args["session"]) ) {
		apiReturnCode(405);
		return array(
			"error" => "No session key specified."
		);
	}

	$sessionKey = $args["session"];
	$ip = $args["ip"];
	$validatedUserId = validateSessionKey($sessionKey, $ip);
	
	return array(
		"valid" => ($userId == $validatedUserId)
	);
}

function validateSessionKey($sessionKey, $ip) {
	global $mysqli;
	
	$sql = "Select s.userId From sessions s Where s.sessionKey = '";
	$sql .= $mysqli->real_escape_string($sessionKey) . "' And validUntil >= UNIX_TIMESTAMP() ";
	$sql .= "And ip = '" . $ip . "';";

	$sessions = $mysqli->query($sql);
	$userId = null;

	while ( $session = $sessions->fetch_assoc() ) {
		$userId = (int)$session["userId"];
	}
	
	$sessions->free();
	
	return $userId;
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

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($mysqli, "utf8");

header('Content-Type: application/json; charset=utf-8');
$json = json_encode(apiResource(), JSON_PRETTY_PRINT);
echo $json;

$mysqli->close();

?>