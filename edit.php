<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	if ( ! requireApiKey() ) {
		showApiKeyError();
	} elseif ( ! isset($_GET["eventId"]) ) {
?>
    <div class="grey-header container">
        <h4 class="event-name">
	        <b>No Event Selected</b>
	    </h4>
	    <h6 class="text-center">No event was specified to edit.</h6>
    </div>
<?	
	} else {
		$eventId = $_GET["eventId"];
		$countryList = json_decode(file_get_contents(getBaseUrl() . "api.php?command=listCountries"), true);
		$resultData = json_decode(file_get_contents(getBaseUrl() . "api.php?command=eventResults&eventId=" . $eventId), true);

		if ( $resultData !== null ) {
			$eventName = $resultData["data"]["events"][$eventId]["eventName"];
			$eventCountryCode = strtolower($resultData["data"]["events"][$eventId]["countryCode"]);
			$eventCountry = $resultData["data"]["events"][$eventId]["countryName"];
			$eventHasCp = ($resultData["data"]["results"][$eventId][1]["points"]) > 0;
			$eventTypeData = json_decode(file_get_contents(getBaseUrl() . "api.php?command=listEventTypes&date=" . date("Y-m-d", strtotime($resultData["data"]["events"][$eventId]["date"]))), true);
		} else {
			$eventName = "Unknown Event";
			$eventCountryCode = "xxx";
			$eventCountry = "";
			$eventHasCp = false;
			$eventTypeData = array("data" => array());
		}
?>
	<div class="grey-header container">
		<h4 class="event-name">
			 <img src="resources/images/flags/<? echo $eventCountryCode; ?>.png" alt="<? echo $eventCountry; ?>" class="icon" />&nbsp;
			 <b><? echo $eventName; ?></b>
		 </h4>
		<h6 class="event-name">
			 <? echo date("F jS Y", strtotime($resultData["data"]["events"][$eventId]["date"])); ?>
<?		if ( $resultData["data"]["events"][$eventId]["playerCount"] > 0 ) { ?>
			 | <? echo $resultData["data"]["events"][$eventId]["playerCount"]; ?> Players
<?		} ?>
			<span class="text-center">
				| <a href="#!" data-toggle="modal" data-target="#eventCreation">Edit Event Details</a>
				| <a href="standings.php?id=<? echo $eventId; ?>">Return To This Event</a>
			</span>
		</h6>
	</div>

    <div class="container">
	    <table id="validation" class="w-100 toggle-circle-filled table-striped" data-sorting="false" data-filtering="false" data-paging="false">
		    <thead>
			    <th class="text-center" data-sorted="true" data-direction="ASC" data-type="number" width=10%>Position</th>
			    <th class="text-center" width=30%>Player</th>
			    <th class="text-center" width=60%>Team</th>
		    </thead>
		    <tbody>
<?		foreach($resultData["data"]["results"][$eventId] as $position => $record) { ?>
				<tr data-position="<? echo $record["position"]; ?>" data-event-id="<? echo $eventId; ?>">
					<td class="text-center"><? echo $record["position"]; ?></td>
					<td class="text-center">
						<select class="w-100 player-select-box">
							<option value="<? echo $record["playerId"]; ?>"><? echo $record["playerName"]; ?> [<? echo $record["playerCountryName"]; ?>] (ID: <? echo $record["playerId"]; ?>)</option>
						</select>
						<hr />
						<button type="button" class="validate-team">Validate Team</button>
						<button type="button" class="save-changes" disabled="true">Save Changes</button>
					</td>
					<td class="text-center">
						<div class="row w-100">
<?		foreach($record["team"] as $pokemon) { ?>
							<div class="col-6 col-sm-4 text-center">
								<div class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></div>
								<br />
								<textarea class="w-100 validate-pokemon" rows=5><? echo encodePokemonShowdown($pokemon); ?></textarea>
							</div>
<?		} ?>
						</div>
					</td>
				</tr>
<?		} ?>
		    </tbody>
	    </table>
	    
	    <hr />
    </div>
	<div id="eventCreation" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Event Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="w-100 form-group">
						<div class="row pb-1">
							<div class="col-4">Event Name</div>
							<div class="col-8">
								<input type="text" class="form-control" id="eventName" value="<? echo $eventName; ?>" />
							</div>
						</div>
						
						<div class="row pb-1">
							<div class="col-4">Event Country</div>
							<div class="col-8">
								<select class="w-100 form-control" id="eventCountry">
									<option value=""></option>
	<?	foreach($countryList["data"] as $countryCode => $countryName) { ?>
									<option value="<? echo $countryCode; ?>"<? echo (strtolower($countryCode) == strtolower($eventCountryCode) ? " selected" : ""); ?>><? echo $countryName; ?></option>
	<?	} ?>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Date</div>
							<div class="col-8">
								<input class="form-control" type="text" id="eventDate" onchange="javascript:updateEventTypes();" value="<? echo date("Y-m-d", strtotime($resultData["data"]["events"][$eventId]["date"])); ?>" />
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Type</div>
							<div class="col-8">
								<select class="w-100 form-control" id="eventType">
<?		foreach($eventTypeData["data"] as $eventTypeId => $eventType) { ?>
									<option value="<? echo $eventTypeId; ?>"<? echo ($eventTypeId == $resultData["data"]["events"][$eventId]["eventTypeId"] ? " selected" : ""); ?>><? echo $eventType; ?></option>
<?		} ?>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Player Count</div>
							<div class="col-8">
								<input class="form-control" type="number" id="playerCount" value="<? echo $resultData["data"]["events"][$eventId]["playerCount"]; ?>" />
							</div>
							
							<p class="pt-1">
								You can specify 0 for the player count if you don't know the correct count, however
								be aware that this will prevent CP totals from being calculated correctly as the system
								won't have the correct kicker counts.
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:updateEvent();">Update Event Details</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?	} ?>

<?	include_once("resources/php/footer.php"); ?>

	<script lang="text/javascript">
		$(document).ready(function() {
			PkSpr.process_dom();
            $('.tttooltip').tooltipster();
            $("#eventDate").datepicker({
	            format: 'yyyy-mm-dd'
			});
			$("#validation").footable({
				'on': {
					'ready.ft.table': function(e, ft) {
				        $(".player-select-box").select2({
							ajax: {
								url: 'api.php?command=listPlayersOnly',
								dataType: 'json'
							},
							width: "100%"
						});
												
						$(".validate-team").on("click", function() {
							row = $(this).parent().parent();
							position = row.attr("data-position");
							row.find(".save-changes").attr("disabled", false);
		
							pokemon = [];
							for ( var index = 0; index < 6; index++ ) {
								pokemon[index] = btoa(row.find("td").eq(2).find("textarea").eq(index).val());
								validatePokemon(row, index);
							}
						});
						
						$(".save-changes").on("click", function() {
							row = $(this).parent().parent();
							position = row.attr("data-position");
							eventId = row.attr("data-event-id");
							playerId = row.find("td").eq(1).find("select").eq(0).val();
							
							row.find(".save-changes").attr("disabled", true);
		
							pokemon = [];
							for ( var index = 0; index < 6; index++ ) {
								pokemon[index] = btoa(row.find("td").eq(2).find("textarea").eq(index).val());
							}
							
							$.get("api.php", {
								command: "updateResult",
								eventId: eventId,
								position: position,
								playerId: playerId,
								pokemon1: pokemon[0],
								pokemon2: pokemon[1],
								pokemon3: pokemon[2],
								pokemon4: pokemon[3],
								pokemon5: pokemon[4],
								pokemon6: pokemon[5],
								key: $("#currentApiKey").attr("data-api-key")
							}).done(function(data) {
								alert("Changes have been saved to the event!");
							});
						});
					}
				}
            });
		});
		
		function validatePokemon(row, index) {
			$.get("api.php", {
				command: "validateShowdown",
				pokemon: pokemon[index]
			}).done(function(data) {
				if ( data["status"] == 200 ) {
					if ( data["data"]["valid"] ) {
						row.find("td").eq(2).find("textarea").eq(index).val(data["showdown"]);
					} else {
						row.find(".save-changes").attr("disabled", true);
					}
					
					row.find("td").eq(2).find(".pkspr").eq(index).attr("class", data["class"]);
					row.find("td").eq(2).find(".pkspr").eq(index).empty();
					PkSpr.process_dom();
				} else {
					row.find(".save-changes").attr("disabled", true);
				}
			});
		}

		function updateEventTypes() {
			$.get("api.php", {
				command: "listEventTypes",
				date: $("#eventDate").val()
			}).done(function(data) {
				$("#eventType").find("option").remove();
				
				$.each(data["data"], function(eventTypeId, eventType) {
					$("#eventType").append("<option value='" + eventTypeId + "'>" + eventType + "</option>");
				});
			});
		}

		function updateEvent() {
			eventId = <? echo $eventId; ?>;
			eventName = $("#eventName").val();
			countryCode = $("#eventCountry").val();
			eventDate = $("#eventDate").val();
			eventTypeId = $("#eventType").val();
			playerCount = $("#playerCount").val();
			
			if ( eventId == "" || eventName == "" || countryCode == "" || eventDate == "" || eventTypeId == "" ) {
				alert("Please make sure all the fields have been filled out!");
				return;
			}
			
			$.get("api.php", {
				command: "updateEvent",
				eventId: eventId,
				eventName: eventName,
				countryCode: countryCode,
				eventDate: eventDate,
				eventTypeId: eventTypeId,
				playerCount: playerCount,
				key: $("#currentApiKey").attr("data-api-key")
			}).done(function(data) {
				alert("Event details have been updated!");
				location.reload();
			});
		}
	</script>
</body>
</html>