<?php

include_once("countries.php");
include_once("pokemon.php");

function sortPokemonTeam($pokemon) {
	$sortedPokemon = array();
	
	$checkOrder = array(
		"0"		=>	MYTHICAL_POKEMON,
		"1"		=>	RESTRICTED_POKEMON,
		"2"		=>	"mega",
		"3"		=>	INTIMIDATE_USERS,
		"4"		=>	LEGENDARY_POKEMON,
		"5"		=>	ULTRA_BEASTS
	);
	
	foreach($pokemon as $pkmn) {
		if ( $pkmn["valid"] ) {
			$shortName = preg_replace("/[^a-z0-9\%]/", "", strtolower($pkmn["pokemon"]));
			$pokemonId = POKEMON_NAME_TO_ID[$shortName];
			$priority = "";
			
			foreach($checkOrder as $check) {
				if ( $check == "mega" ) {
					$isMegaEvolution = false;
					
					if ( stripos($pkmn["forme"], "mega") !== false ) {
						$isMegaEvolution = true;
					}
		
					if ( stripos($pkmn["forme"], "primal") !== false ) {
						$isMegaEvolution = true;
					}
					
					$priority .= ($isMegaEvolution ? "0" : "1");
				} else {
					$foundMatch = false;
					
					foreach($check as $checkPkmn) {
						if ( $shortName == $checkPkmn ) {
							$priority .= "0";
							$foundMatch = true;
						}
					}
					
					if ( ! $foundMatch ) $priority .= "1";
				}
			}

			while ( strlen($pokemonId) < 4 ) $pokemonId = "0" . $pokemonId;
			$priority .= $pokemonId;
		} else {
			$priority = "111119999";
		}
		
		$sortedPokemon[(int)$priority] = $pkmn;
		$sortedPokemon[(int)$priority]["priority"] = (int)$priority;
	}
	
	ksort($sortedPokemon);
	
	$team = array();
	
	foreach($sortedPokemon as $pkmn) {
		$team[count($team)] = $pkmn;
	}
	
	while ( count($team) < 6 ) {
		$team[count($team)] = array();
	}
	
	return $team;
}

function decodePokemonName($pokemon) {
	$data = preg_replace("/[^a-z0-9\%]/", "", strtolower($pokemon));
	$data = str_replace("mew-two", "mewtwo", $data);
	
	$pokemonName = null;
	$pokemonId = null;
	$matchLength = 0;
	
	if ( is_numeric($data) ) {
		$data = preg_replace("/[^a-z0-9\%]/", "", strtolower(POKEMON_ID_TO_NAME[$data]));
	}
	
	foreach(POKEMON_NAME_TO_ID as $pkmn => $pkmnId) {
		if ( strpos($data, $pkmn) !== false && $matchLength < strlen($pkmn) ) {
			$pokemonId = $pkmnId;
			$pokemonName = $pkmn;
			$matchLength = strlen($pkmn);
		}
	}
	
	if ( $pokemonId ) {
		$validName = POKEMON_ID_TO_NAME[$pokemonId];
		$forme = ucwords(str_replace($pokemonName, "", $data));
		
		if ( $forme == "Megax" ) {
			$forme = "Mega-X";
		} elseif ( $forme == "Megay" ) {
			$forme = "Mega-Y";
		}
	} else {
		$validName = $pokemon;
		$forme = "";
	}
	
	return array(
		"pokemon" => $validName,
		"forme" => $forme,
		"valid" => ($pokemonId != null)
	);
}

function decodePokemonLabel($pokemonData) {
	if ( !isset($pokemonData["forme"]) || !isset($pokemonData["pokemon"]) ) {
		return "";
	}
	
	if ( $pokemonData["forme"] == "Mega-X" ) {
		$label = "Mega " . $pokemonData["pokemon"] . " X";

	} elseif ( $pokemonData["forme"] == "Mega-Y" ) {
		$label = "Mega " . $pokemonData["pokemon"] . " Y";

	} elseif ( $pokemonData["forme"] == "Mega" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Alola" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Primal" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Dawn" && $pokemonData["pokemon"] == "Necrozma" ) {
		$label = "Dawn Wings " . $pokemonData["pokemon"];
	} elseif ( $pokemonData["forme"] == "Dusk" && $pokemonData["pokemon"] == "Necrozma" ) {
		$label = "Dusk Mane " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Therian" || $pokemonData["forme"] == "Incarnate" ) {
		$label = $pokemonData["pokemon"] . "-" . $pokemonData["forme"];

	} elseif ( $pokemonData["forme"] != "" ) {
		$label = $pokemonData["pokemon"] . " " . $pokemonData["forme"];

	} else {
		$label = $pokemonData["pokemon"];
	}

	return $label;	
}

function getSpriteClass($pokemonData) {
	$class = "pkspr";
	
	if ( isset($pokemonData["valid"]) && $pokemonData["valid"] ) {
		$class .= " pkmn-" . str_replace(" ", "-", strtolower($pokemonData["pokemon"]));
		
		if ( $pokemonData["forme"] != "" ) {
			$class .= " form-" . str_replace(" ", "-", strtolower($pokemonData["forme"]));
		}
		
		if ( isset($pokemonData["shiny"]) && $pokemonData["shiny"] ) {
			$class .= " color-shiny";
		}
	} else {
		$class .= " pkmn-unknown";
	}
	
	return $class;
}

function decodePokemonShowdown($showdown) {
	$lines = explode("\n", str_replace("\r", "", $showdown));
	
	$pokemonData = array(
		"pokemon"	=> "",
		"forme"		=> "",
		"nickname"	=> "",
		"heldItem"	=> "",
		"level"		=> 50,
		"ability"	=> "",
		"ivs"		=> array(),
		"evs"		=> array(),
		"nature"	=> "",
		"moves"		=> array("1" => "", "2" => "", "3" => "", "4" => ""),
		"gender"	=> "",
		"shiny"		=> false,
		"valid"		=> false
	);
	
	$moveCount = 1;
	
	foreach($lines as $line) {
		$data = trim($line);
		
		if ( $data == "" ) {
			// Empty line
		} elseif ( stripos($data, "Ability:") !== false ) {
			$pokemonData["ability"] = ucwords(strtolower(preg_replace("/Ability: */", "", $data)));
		} elseif ( stripos($data, "Level:") !== false ) {
			$pokemonData["level"] = preg_replace("/Level: */", "", $data);
		} elseif ( stripos($data, " Nature") !== false && substr($data, 0, 1) !== "-" ) {
			$pokemonData["nature"] = ucwords(strtolower(preg_replace("/ *Nature.*/", "", $data)));
		} elseif ( stripos($data, "EVs:") !== false ) {
			$evData = preg_replace("/EVs: */", "", $data);
			$evSplit = explode("/", $evData);
			
			foreach($evSplit as $ev) {
				$evValue = (int)(preg_replace("/[^0-9]/", "", $ev));
				
				if ( stripos($ev, "HP") !== false )		$pokemonData["evs"]["hp"] = $evValue;
				if ( stripos($ev, "ATK") !== false )	$pokemonData["evs"]["atk"] = $evValue;
				if ( stripos($ev, "DEF") !== false )	$pokemonData["evs"]["def"] = $evValue;
				if ( stripos($ev, "SPA") !== false )	$pokemonData["evs"]["spa"] = $evValue;
				if ( stripos($ev, "SPD") !== false )	$pokemonData["evs"]["spd"] = $evValue;
				if ( stripos($ev, "SPE") !== false )	$pokemonData["evs"]["spe"] = $evValue;
			}
		} elseif ( stripos($data, "IVs:") !== false ) {
			$ivData = preg_replace("/IVs: */", "", $data);
			$ivSplit = explode("/", $ivData);
			
			foreach($ivSplit as $iv) {
				$ivValue = (int)(preg_replace("/[^0-9]/", "", $iv));
				
				if ( stripos($iv, "HP") !== false )		$pokemonData["ivs"]["hp"] = $ivValue;
				if ( stripos($iv, "ATK") !== false )	$pokemonData["ivs"]["atk"] = $ivValue;
				if ( stripos($iv, "DEF") !== false )	$pokemonData["ivs"]["def"] = $ivValue;
				if ( stripos($iv, "SPA") !== false )	$pokemonData["ivs"]["spa"] = $ivValue;
				if ( stripos($iv, "SPD") !== false )	$pokemonData["ivs"]["spd"] = $ivValue;
				if ( stripos($iv, "SPE") !== false )	$pokemonData["ivs"]["spe"] = $ivValue;
			}
		} elseif ( substr($data, 0, 1) == "-" ) {
			$move = preg_replace("/^\- */", "", $data);
			$pokemonData["moves"][$moveCount] = ucwords(strtolower($move));
			$moveCount++;
		} elseif ( stripos($data, "Shiny:") !== false ) {
			$pokemonData["shiny"] = (stripos($data, "Yes") !== false);
		} elseif ( stripos($data, "Happiness:") !== false ) {
			$pokemonData["happiness"] = (int)(preg_replace("/[^0-9]/", "", $iv));
		} else {
			if ( stripos($data, "(M)") !== false ) {
				$pokemonData["gender"] = "M";
			} elseif ( stripos($data, "(F)") !== false ) {
				$pokemonData["gender"] = "F";
			}
			
			if ( stripos($data, "@") !== false ) {
				$pokemonData["heldItem"] = ucwords(strtolower(preg_replace("/.*@ */", "", $data)));
			}
			
			$pokemon = preg_replace("/ *@.*/", "", $data);
			$nickname = "";
			
			if ( strpos($pokemon, "(") !== false ) {
				$nickname = preg_replace("/ *\(.*/", "", $pokemon);
				$pokemon = preg_replace("/.*\(/", "", preg_replace("/\).*/", "", $pokemon));
			}
			
			$pokemonData["nickname"] = $nickname;
			
			$nameData = decodePokemonName($pokemon);
			$pokemonData["pokemon"] = $nameData["pokemon"];
			$pokemonData["forme"] = $nameData["forme"];
			$pokemonData["valid"] = $nameData["valid"];
		}
	}
	
	return $pokemonData;
}

function encodePokemonShowdown($pokemonData) {
	$output = "";
	
	if ( !isset($pokemonData["pokemon"]) ) {
		return "";
	}

	if ( !isset($pokemonData["valid"]) || $pokemonData["valid"] === false ) {
		return "";
	}
	
	if ( isset($pokemonData["nickname"]) && $pokemonData["pokemon"] != $pokemonData["nickname"] && $pokemonData["nickname"] != "" ) {
		$output .= $pokemonData["nickname"] . " (" . $pokemonData["pokemon"] . "-" . $pokemonData["forme"] . ")";
	} else {
		$output .= $pokemonData["pokemon"];
		if ( isset($pokemonData["forme"]) && $pokemonData["forme"] != "" ) $output .= "-" . $pokemonData["forme"];
	}
	
	if ( isset($pokemonData["gender"]) ) {
		if ( $pokemonData["gender"] != "" ) $output .= " (" . $pokemonData["gender"] . ")";
	}
	
	if ( isset($pokemonData["heldItem"]) ) {
		if ( $pokemonData["heldItem"] != "" ) {
			$output .= " @ " . $pokemonData["heldItem"];
		}
	}
	
	$output .= "\r\n";
	
	if ( isset($pokemonData["shiny"]) ) {
		if ( $pokemonData["shiny"] ) $output .= "Shiny: Yes\r\n";
	}
	
	$output .= "Ability: " . (isset($pokemonData["ability"]) ? $pokemonData["ability"] : "") . "\r\n";
	
	if ( isset($pokemonData["level"]) ) {
		$output .= "Level: " . $pokemonData["level"] . "\r\n";
	}
	
	if ( isset($pokemonData["happiness"]) ) {
		$output .= "Happiness: " . $pokemonData["happiness"] . "\r\n";
	}
	
	if ( isset($pokemonData["evs"]) ) {
		$evData = "";
	
		foreach($pokemonData["evs"] as $evType => $evValue) {
			$evText = "";
			if ( $evType == "hp"  ) $evText = "HP";
			if ( $evType == "atk" ) $evText = "Atk";
			if ( $evType == "def" ) $evText = "Def";
			if ( $evType == "spa" ) $evText = "SpA";
			if ( $evType == "spd" ) $evText = "SpD";
			if ( $evType == "spe" ) $evText = "Spe";
			
			$evData .= $evValue . " " . $evText . " / ";
		}
		
		if ( $evData != "" ) {
			$output .= "EVs: " . substr($evData, 0, -3) . "\r\n";
		}
	}
	
	if ( isset($pokemonData["nature"]) ) {
		if ( $pokemonData["nature"] != "" ) $output .= $pokemonData["nature"] . " Nature\r\n";
	}
	
	if ( isset($pokemonData["ivs"]) ) {
		$ivData = "";
		
		foreach($pokemonData["ivs"] as $ivType => $ivValue) {
			$ivText = "";
			if ( $ivType == "hp"  ) $ivText = "HP";
			if ( $ivType == "atk" ) $ivText = "Atk";
			if ( $ivType == "def" ) $ivText = "Def";
			if ( $ivType == "spa" ) $ivText = "SpA";
			if ( $ivType == "spd" ) $ivText = "SpD";
			if ( $ivType == "spe" ) $ivText = "Spe";
	
			$ivData .= $ivValue . " " . $ivText . " / ";
		}
		
		if ( $ivData != "" ) {
			$output .= "IVs: " . substr($ivData, 0, -3) . "\r\n";
		}
	}
	
	if ( isset($pokemonData["moves"]) ) {
		if ( $pokemonData["moves"]["1"] != "" ) $output .= "- " . $pokemonData["moves"]["1"] . "\r\n";
		if ( $pokemonData["moves"]["2"] != "" ) $output .= "- " . $pokemonData["moves"]["2"] . "\r\n";
		if ( $pokemonData["moves"]["3"] != "" ) $output .= "- " . $pokemonData["moves"]["3"] . "\r\n";
		if ( $pokemonData["moves"]["4"] != "" ) $output .= "- " . $pokemonData["moves"]["4"] . "\r\n";
	}
	
	return $output;
}

function getFlagEmoji($countryCode) {
	if ( $countryCode == "XXX" ) return "🏳️";
	if ( ! isset(ISO_3_TO_2[$countryCode]) ) return "🏴";
	
	$flagOffset = 0x1F1E6;
	$asciiOffset = 0x41;

	$country = ISO_3_TO_2[$countryCode];
	$firstChar = ord(substr($country, 0, 1)) - $asciiOffset + $flagOffset;
	$secondChar = ord(substr($country, 1, 1)) - $asciiOffset + $flagOffset;

	$flag = mb_chr($firstChar) . mb_chr($secondChar);
	return $flag;
}

function getSeasonDropdownData() {
	$seasonJson = "";
	
	$defaultSocketTimeout = ini_get('default_socket_timeout');
	ini_set('default_socket_timeout', 5);
	$seasonJson = @file_get_contents("https://pokecal-dev.codearoundcorners.com/api.php?command=listPeriods&product=Video%20Game&onlyFormat");
	ini_set('default_socket_timeout', $defaultSocketTimeout);
	
	if ( $seasonJson == "" ) {
		return null;
	}
	
	$seasonPeriods = json_decode($seasonJson, true);
	
	$maximumSeason = 0;	
	$maximumHistory = 3;

	$searchData = array();
	
	foreach( $seasonPeriods["data"] as $season => $seasonPeriod ) {
		if ( $season > $maximumSeason ) $maximumSeason = $season;
	}
	
	for( $currentSeason = $maximumSeason; $currentSeason > ($maximumSeason - $maximumHistory); $currentSeason-- ) {
		$searchData[$currentSeason] = array();
		
		foreach( $seasonPeriods["data"] as $season => $seasonPeriod ) {
			if ( $season == $currentSeason ) {
				foreach ( $seasonPeriod["periods"] as $periodId => $period ) {
					$arrayId = $period["startDate"] . "-" . $periodId;
					$searchData[$currentSeason][$arrayId] = $period;
				}
			}
		}
	}
	
	return array(
		"maximumSeason"		=> $maximumSeason,
		"maximumHistory"	=> $maximumHistory,
		"periods"			=> $seasonPeriods,
		"data"				=> $searchData
	);
}

function makeSearchBarHtml($periodData) {
?>
	<hr />
    <div class="container">
		<div class="input-group input-group-sm">
<?
	if ( $periodData !== null ) {
?>
			<div class="input-group-prepend">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="current-season">All Seasons</button>
				<? echo makeSeasonDropdownHtml($periodData); ?>
			</div>
<?
	}
?>
			<input type="text" class="form-control" aria-label="Search" placeholder="Search..." id="search-filter" />
			<div class="input-group-append">
				<button class="btn btn-primary" type="button" id="erase-search"><i class="fas fa-eraser"></i></button>
				<button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
				<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#searchHelp"><i class="fas fa-question-circle"></i></button>
			</div>
  		</div>
    </div>
    <hr />
<?
}

function makeSeasonDropdownHtml($periodData) {
	$dropdownHtml = "<a href='#!' class='dropdown-item season-selection' data-season='-1'>All Seasons</a>\n";
	
	for( $currentSeason = $periodData["maximumSeason"]; $currentSeason > ($periodData["maximumSeason"] - $periodData["maximumHistory"]); $currentSeason-- ) {
		$dropdownHtml .= "<h6 class='dropdown-header'>" . $currentSeason . " Season</h6>\n";
		
		if ( isset($periodData["periods"]["data"][$currentSeason]) ) {
			$startDate = $periodData["periods"]["data"][$currentSeason]["startDate"];
			$endDate = $periodData["periods"]["data"][$currentSeason]["endDate"];
		} else {
			$startDate = "";
			$endDate = "";
		}
		
		$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='" . $currentSeason . "' data-start='";
		$dropdownHtml .= $startDate . "' data-end='" . $endDate . "'>All Events</a>\n";
		
		foreach($periodData["data"][$currentSeason] as $arrayId => $period) {
			$startDate = $period["startDate"];
			$endDate = $period["endDate"];
			$periodName = $period["name"];
		
			$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='" . $currentSeason . "' data-start='";
			$dropdownHtml .= $startDate . "' data-end='" . $endDate . "'>" . $periodName . "</a>\n";
		}
	}
	
	$dropdownHtml .= "<h6 class='dropdown-header'>Older Events</h6>";
	$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='-2'>Older Seasons</a>\n";
	
	return "<div class='dropdown-menu' style='font-size: 12px;'>" . $dropdownHtml . "</div>";
}

function makeSeasonDropdownJs($periodData) {
?>
	<script type="text/javascript">
<?
	if ( $periodData !== null ) {
		$firstSeason = 2000;
		$olderSeasonFilter = $firstSeason;
		
		for( $season = ($firstSeason + 1); $season <= ($periodData["maximumSeason"] - $periodData["maximumHistory"]); $season++ ) {
			$olderSeasonFilter .= " OR " . $season;
		}
?>
		$(".season-selection").click(function() {
			var season = $(this).attr("data-season");
			var dateStart = $(this).attr("data-start");
			var dateEnd = $(this).attr("data-end");
			var dataLabel = $(this).text();
			
			if ( season > 0 ) {
				$("#current-season").text("(" + season + ") " + $(this).text());
			} else {
				$("#current-season").text($(this).text());
			}
			
			filter = FooTable.get(".period-search").use(FooTable.Filtering);
			filter.removeFilter("season");
			
			if ( season == -1 ) {
				
			} else if ( season == -2 ) {
				filter.addFilter("season", "<? echo $olderSeasonFilter; ?>", ["season"]);
			} else if ( dateStart == "" && dateEnd == "" ) {
				filter.addFilter("season", season, ["season"]);
			} else {
				var filterText = dateStart.replace(/\-/g, "");
				var checkDate = new Date(dateStart);
				var lastDate = new Date(dateEnd);
				
				while ( checkDate <= lastDate ) {
					checkDate.setDate(checkDate.getDate() + 1);
					filterText += " OR " + checkDate.toISOString().substr(0, 10).replace(/\-/g, "");
				}
				
				filter.addFilter("season", filterText, ["eventDate"]);
			}
			
			filter.filter();
		});
<?
	}
?>
		$("#search-filter").on("change", function() {
			filterText = $(this).val();
			filter = FooTable.get(".period-search").use(FooTable.Filtering);
			
			if ( filterText == "" || filterText.length < 3 ) {
				filter.removeFilter("generic");
			} else {
				filter.addFilter("generic", filterText);
			}			

			filter.filter();
		});
		
		$("#erase-search").click(function() {
			$("#search-filter").val("");
			$("#current-season").text("All Seasons");
			
			filter = FooTable.get(".period-search").use(FooTable.Filtering);
			filter.removeFilter("generic");
			filter.removeFilter("season");

			filter.filter();
		});
	</script>
<?php
}

function makeSearchBarHelp() {
?>
	<div id="searchHelp" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Search Bar Help</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						The search bar allows you to quickly search through the Trainer Tower results page for whatever
						teams, events or players you might be looking for.
					</p>
					<p>
						If you want to limit the range of events being returned to a specific period (for example, you
						only want to see the 2019 Ultra Series events), click on the "All Seasons" dropdown and select the
						time period you're interested in. Only events from that time period will be shown. (You can't
						use this on the player list or the standings screens).
					</p>
					<p>
						To search for a specific player, event name or country, type what you are searching for into the
						search box and either press enter or click the <i class="fas fa-search"></i> icon. Clicking the
						<i class="fas fa-eraser"></i> icon will clear your current search.
					</p>
					<p>
						You can also search for teams with specific Pokémon this way! Enter the name of the Pokémon you want
						to find and only teams with that Pokémon will be returned. You can search for multiple Pokémon this
						way as well by typing <code>xerneas AND groudon</code> into the search bar (replace Xerneas and
						Groudon with the Pokémon you are looking for). This will return all the teams that have both Pokémon
						in them. This doesn't only apply to Pokémon, searching for <code>umbreon AND japan</code> would return
						all the Japanese teams with Umbreon in them.
					</p>
					<p>
						When searching for multiple items, the <code>AND</code> must be in capitals for it to be recognised.
						You can also search for more than two items. <code>kangaskhan AND magikarp AND australia</code> would
						look for anyone from Australia crazy enough to run both a Kangaskhan and a Magikarp in their team.
					</p>
				</div>
			</div>
		</div>
	</div>
<?php
}

function getBaseUrl() {
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" .
		$_SERVER["HTTP_HOST"] . substr($_SERVER["REQUEST_URI"], 0,
		strlen($_SERVER["REQUEST_URI"]) - strlen(basename($_SERVER["REQUEST_URI"])));
}

function requireApiKey() {
	if ( (isset($_SESSION['apiKey']) && $_SESSION['apiKey'] != "") ) {
		return true;
	} else {
		return false;
	}
}

function showApiKeyError() {
?>
    <div class="grey-header container">
        <h4 class="event-name">
	        <b>API Key Required</b>
	    </h4>
	    <h6 class="text-center">This section of the website requires you to have a valid API key.</h6>
    </div>
<?
}

?>