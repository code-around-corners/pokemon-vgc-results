<?php
    if ( isset($_GET["id"]) ) {
        if ( ! is_numeric($_GET["id"]) ) {
            header('HTTP/1.1 500 Internal Server Error');
            exit(1);
        }
    }
?>

<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	$playerId = -1;
	$playerData = null;
	$periodData = getSeasonDropdownData();
	$countryList = json_decode(file_get_contents(getBaseUrl() . "api/v1/countries"), true);

	if ( isset($_GET["id"]) ) {
		$playerId = (int)$_GET["id"];
		
		$playerJson = @file_get_contents(getBaseUrl() . "api/v1/players/" . $playerId);
		if ( $playerJson != "" ) {
			$playerData = json_decode($playerJson, true);
		}
		
		$eventsJson = @file_get_contents(getBaseUrl() . "api/v1/players/" . $playerId . "/events");
		if ( $eventsJson != "" ) {
			$eventsData = json_decode($eventsJson, true);
		}

		$resultsJson = @file_get_contents(getBaseUrl() . "api/v1/players/" . $playerId . "/results");
		if ( $resultsJson != "" ) {
			$resultsData = json_decode($resultsJson, true);
		}
	}
?>
    <div class="grey-header container">
        <h4 class="event-name">
<?	if ( $playerData["countryCode"] != "" ) { ?>
            <img src="<? echo $countryList[$playerData["countryCode"]]["flagUrl"]; ?>" class="icon" />
<?	} ?>
            <strong><? echo $playerData["name"]; ?></strong>
<?	if ( isset($playerData["social"]["facebook"]) ) { ?>	            
	        <a href="http://www.facebook.com/<? echo $playerData["social"]["facebook"]; ?>" target="_blank">
		        <img src="resources/images/social/facebook.png" alt="facebook" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["social"]["twitter"]) ) { ?>	            
	        <a href="http://www.twitter.com/<? echo $playerData["social"]["twitter"]; ?>" target="_blank">
		        <img src="resources/images/social/twitter.png" alt="twitter" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["social"]["youtube"]) ) { ?>	            
	        <a href="http://www.youtube.com/user/<? echo $playerData["social"]["youtube"]; ?>" target="_blank">
		        <img src="resources/images/social/youtube.png" alt="youtube" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["social"]["twitch"]) ) { ?>	            
	        <a href="http://www.twitch.com/u/<? echo $playerData["social"]["twitch"]; ?>" target="_blank">
		        <img src="resources/images/social/twitch.png" alt="twitch" class="icon" />
		    </a>
<?	} ?>
        </h4>
<?	if ( $loggedIn ) { ?>
		<h6 class="event-name">
			<span class="text-center">
				<a href="#!" data-toggle="modal" data-target="#playerEdit">Edit This Player</a>
			</span>
		</h6>
<?	} ?>
    </div>
    
<?	makeSearchBarHtml($periodData); ?>

    <div class="container">
	    <table id="results" class="w-100 toggle-circle-filled table-striped period-search" data-sorting="true" data-filtering="true" data-paging="true">
		    <thead>
			    <th></th>
			    <th class="text-center" data-name="eventDate" data-sorted="true" data-direction="DESC">Date</th>
			    <th class="text-center" data-breakpoints="xs"><span class="hide-detail-row">Country</span></th>
			    <th class="text-center">Tournament</th>
			    <th class="text-center" data-breakpoints="all" data-name="season" data-type="number">Season</th>
			    <th class="text-center" data-type="number">Position</th>
			    <th class="text-center" data-breakpoints="xs sm" data-type="number">CP</th>
			    <th class="text-center" data-breakpoints="xs" class="team-column">Team</th>
			    <th class="text-center" data-breakpoints="xs sm">Export Team</th>
		    </thead>
		    <tbody>
<?	$recordCount = 0; ?>
<?	foreach($resultsData as $resultId => $result) { ?>
<?		$recordCount++; ?>
<?		$eventCountryCode = $eventsData[$result["eventId"]]["countryCode"]; ?>
<?		$eventCountryName = $eventsData[$result["eventId"]]["country"]; ?>

				<tr>
					<td></td>
					<td class="text-center" data-sort-value="<? echo $eventsData[$result["eventId"]]["date"]; ?>" data-filter-value="<? echo str_replace("-", "", $eventsData[$result["eventId"]]["date"]); ?>">
						<? echo date("F jS Y", strtotime($eventsData[$result["eventId"]]["date"])); ?>
					</td>
                	<td class="text-center hide-detail-row" data-filter-value="<? echo $eventCountryName; ?>">
<?		if ( $eventCountryCode != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($eventCountryCode); ?>.png" title="<? echo $eventCountryName; ?>" class="icon tttooltip" />
<?		} ?>
                	</td>
					<td class="text-center" data-sort-value="<? echo $eventsData[$result["eventId"]]["name"]; ?>">
			            <a href="standings.php?id=<? echo $result["eventId"]; ?>">
				            <span class="d-sm-inline d-md-none"><? echo getFlagEmoji(strtoupper($eventCountryCode)) . " "; ?></span>
							<? echo $eventsData[$result["eventId"]]["name"]; ?>
			            </a>
					</td>
					<td class="text-center"><? echo $eventsData[$result["eventId"]]["season"]; ?></td>
					<td class="text-center"><? echo $result["position"]; ?></td>
					<td class="text-center"><? echo $result["points"]; ?></td>
<?		$searchText = ""; ?>
<?		foreach($result["team"] as $pokemon) { ?>
<?			$searchText .= $pokemon["name"] . " "; ?>
<?		} ?>
					<td class="text-center team-column" data-filter-value="<? echo trim($searchText); ?>">
<?		$pokemonCount = 0; ?>
<?		foreach($result["team"] as $pokemon) { ?>
<?			$pokemonCount++; ?>
						<span class="pkspr-gen8-box"><span class="tttooltip <? echo $pokemon["class"]; ?>" title="<? echo $pokemon["name"]; ?>"></span></span>
<?			if ( $pokemonCount == 3 ) { ?>
						<br class="phone-line-break" />
<?			} ?>
<?		} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox(<? echo $resultId; ?>);"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?		if ( isset($result["rentalTeamUrl"]) ) { ?>
						&nbsp;&nbsp;<a href="<? echo $result["rentalTeamUrl"]; ?>" target="_new"><i class="fas fas-large fa-qrcode tttooltip" title="Export Rental Team"></i></a>
<?		} ?>
					</td>
				</tr>
<?	} ?>
		    </tbody>
	    </table>
    </div>
	<div id="psExport" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Pokemon Showdown Team Export</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						The information below can be copy and pasted into the Import function of Pokemon Showdown. Please
						be aware that if the movesets, abilities and items were not provided with this team that they won't
						be available to export.
					</p>
					<textarea id="export" style="width: 100%" rows="20"></textarea>
				</div>
			</div>
		</div>
	</div>
<?	if ( $loggedIn ) { ?>
	<div id="playerEdit" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Player Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="w-100 form-group">
						<div class="row pb-1">
							<div class="col-4">Player Name</div>
							<div class="col-8">
								<input type="text" class="form-control" id="playerName" value="<? echo $playerData["name"]; ?>" />
							</div>
						</div>
						
						<div class="row pb-1">
							<div class="col-4">Player Country</div>
							<div class="col-8">
								<select class="w-100 form-control" id="playerCountry">
									<option value=""></option>
	<?	foreach($countryList as $countryCode => $country) { ?>
									<option value="<? echo $countryCode; ?>"<? echo (($countryCode == $playerData["countryCode"]) ? " selected" : ""); ?>><? echo $country["flagEmoji"] . " " . $country["country"]; ?></option>
	<?	} ?>
								</select>
							</div>
						</div>
						
						<hr />
						
						<p>Social Media Accounts</p>

						<div class="row pb-1">
							<div class="col-4">Twitter</div>
							<div class="col-8">
								<input type="text" class="form-control" id="twitter" value="<? echo (isset($playerData["social"]["twitter"]) ? $playerData["social"]["twitter"] : "") ; ?>" />
							</div>
						</div>
						<div class="row pb-1">
							<div class="col-4">YouTube</div>
							<div class="col-8">
								<input type="text" class="form-control" id="youtube" value="<? echo (isset($playerData["social"]["youtube"]) ? $playerData["social"]["youtube"] : ""); ?>" />
							</div>
						</div>
						<div class="row pb-1">
							<div class="col-4">Facebook</div>
							<div class="col-8">
								<input type="text" class="form-control" id="facebook" value="<? echo (isset($playerData["social"]["facebook"]) ? $playerData["social"]["facebook"] : ""); ?>" />
							</div>
						</div>
						<div class="row pb-1">
							<div class="col-4">Twitch</div>
							<div class="col-8">
								<input type="text" class="form-control" id="twitch" value="<? echo (isset($playerData["social"]["twitch"]) ? $playerData["social"]["twitch"] : ""); ?>" />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:updatePlayer();">Update Player Details</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?	} ?>
<?	include_once("resources/php/footer.php"); ?>
	<script type="text/javascript">
		function showExportBox(resultId) {
			$.get("<? echo getBaseUrl(); ?>api/v1/results/" + resultId).done(function(data) {
				teamData = "";
				$.each(data["team"], function(index, team) {
					teamData += team["showdown"] + "\r\n";
				});

				$("#export").val(teamData);
				$("#psExport").modal("show");
			});
		}
		
		$(document).ready(function() {
			$("#results").footable({
		       'on': {
		            'ready.ft.table': function(e, ft) {
						PkSpr.process_dom();
		            	$(".tttooltip").tooltipster();
		          	},
		            'after.ft.paging': function(e, ft) {
						PkSpr.process_dom();
		            	$(".tttooltip").tooltipster();
		          	},
		            'after.ft.filtering': function(e, ft) {
						PkSpr.process_dom();
		            	$(".tttooltip").tooltipster();
		          	},    	
		            'after.ft.sorting': function(e, ft) {
						PkSpr.process_dom();
		            	$(".tttooltip").tooltipster();
		          	}		          	

		        }
			});
		});
		
		function updatePlayer() {
			playerId = <? echo $playerId; ?>;
			playerName = $("#playerName").val();
			playerCountry = $("#playerCountry").val();
			twitter = $("#twitter").val();
			youtube = $("#youtube").val();
			facebook = $("#facebook").val();
			twitch = $("#twitch").val();
			
			$.ajax({
				url: "api/v1/players/" + playerId,
				type: "PUT",
				data: {
					playerName:		playerName,
					countryCode:	playerCountry,
					twitter:		twitter,
					youtube:		youtube,
					facebook:		facebook,
					twitch:			twitch,
					session:		Cookies.get("session")
				}
			}).done(function(data) {
				alert("Player details have been updated!");
				location.reload();
			}).fail(function(data, textStatus, xhr) {
				alert("Validation failed!");
			});
		}
	</script>
	<? echo makeSeasonDropdownJs($periodData); ?>
</body>
</html>
