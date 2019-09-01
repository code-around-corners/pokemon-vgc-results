<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	if ( ! $loggedIn ) {
		showLoggedOutError();
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
		$countryList = json_decode(file_get_contents(getBaseUrl() . "api/v1/countries"), true);
		$countryDropdownList = json_decode(file_get_contents(getBaseUrl() . "api/v1/countries?format=dropdown"), true);
		$eventData = json_decode(file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId), true);
		$playerData = json_decode(file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId . "/players"), true);
		$resultData = json_decode(file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId . "/results?format=full"), true);

		$eventName = $eventData["name"];
		$eventCountryCode = $eventData["countryCode"];
		$eventCountry = $eventData["country"];
		$eventHasCp = ($eventData["points"][0] > 0);
		$eventTypeData = json_decode(file_get_contents(getBaseUrl() . "api/v1/event-types?format=dropdown&date=" . date("Y-m-d", strtotime($eventData["date"]))), true);
?>
	<div class="grey-header container">
		<h4 class="event-name">
			 <img src="<? echo $countryList[$eventCountryCode]["flagUrl"]; ?>" alt="<? echo $eventCountry; ?>" class="icon" />&nbsp;
			 <b><? echo $eventName; ?></b>
		 </h4>
		<h6 class="event-name">
			 <? echo date("F jS Y", strtotime($eventData["date"])); ?>
<?		if ( $eventData["playerCount"] > 0 ) { ?>
			 | <? echo $eventData["playerCount"]; ?> Players
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
<?		foreach($resultData as $resultId => $record) { ?>
				<tr data-position="<? echo $record["position"]; ?>" data-event-id="<? echo $eventId; ?>">
					<td class="text-center"><? echo $record["position"]; ?></td>
					<td class="text-center">
						<select class="w-100 player-select-box">
							<option value="<? echo $record["player"]["id"]; ?>">
								<? echo $record["player"]["flagEmoji"]; ?>
								<? echo $record["player"]["name"]; ?> (ID: <? echo $record["player"]["id"]; ?>)
							</option>
						</select>
						<hr />
						<button type="button" class="save-changes" data-result-id="<? echo $resultId; ?>">Save Changes</button>
					</td>
					<td class="text-center">
						<div class="row w-100">
<?		foreach($record["team"] as $pokemon) { ?>
							<div class="col-6 col-sm-4 text-center">
								<div class="tttooltip <? echo $pokemon["class"] ?>" title="<? echo $pokemon["name"]; ?>"></div>
								<br />
								<textarea class="w-100 validate-pokemon" rows=5><? echo $pokemon["showdown"]; ?></textarea>
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
	<?	foreach($countryDropdownList["results"] as $country) { ?>
									<option value="<? echo $country["id"]; ?>"<? echo (strtolower($country["id"]) == strtolower($eventData["countryCode"]) ? " selected" : ""); ?>><? echo $country["text"]; ?></option>
	<?	} ?>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Date</div>
							<div class="col-8">
								<input class="form-control" type="text" id="eventDate" onchange="javascript:updateEventTypes();" value="<? echo date("Y-m-d", strtotime($eventData["date"])); ?>" />
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Type</div>
							<div class="col-8">
								<select class="w-100 form-control" id="eventType">
<?		foreach($eventTypeData["results"] as $eventType) { ?>
									<option value="<? echo $eventType["id"]; ?>"<? echo ($eventType["id"] == $eventData["eventTypeId"] ? " selected" : ""); ?>><? echo $eventType["text"]; ?></option>
<?		} ?>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Player Count</div>
							<div class="col-8">
								<input class="form-control" type="number" id="playerCount" value="<? echo $eventData["playerCount"]; ?>" />
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

<?	if ( isLoggedIn() ) { ?>
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
								url: 'api/v1/players?format=dropdown',
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
							playerId = row.find(".player-select-box").val();
							resultId = $(this).attr("data-result-id");
							
							pokemon = [];
							for ( var index = 0; index < 6; index++ ) {
								pokemon[index] = btoa(row.find("td").eq(2).find("textarea").eq(index).val());
							}
							
							$.ajax({
								url: "api/v1/results",
								type: "PUT",
								contentType: 'application/json',
								data: {
									resultId: resultId,
									eventId: eventId,
									position: position,
									playerId: playerId,
									pokemon1: pokemon[0],
									pokemon2: pokemon[1],
									pokemon3: pokemon[2],
									pokemon4: pokemon[3],
									pokemon5: pokemon[4],
									pokemon6: pokemon[5],
									session: Cookies.get("session")
								}
							}).done(function(data) {
								for ( index = 0; index < 6; index++ ) {
									if ( data["team"][index]["valid"] ) {
										row.find("td").eq(2).find("textarea").eq(index).val(data["team"][index]["showdown"]);
									} else {
										row.find(".save-changes").attr("disabled", true);
									}
									
									row.find("td").eq(2).find(".pkspr").eq(index).attr("class", data["team"][index]["class"]);
									row.find("td").eq(2).find(".pkspr").eq(index).empty();
								}
								
								PkSpr.process_dom();
								alert("Changes have been saved to the event!");
							}).fail(function(data, textStatus, xhr) {
								alert("Error: " + data.responseJSON["error"]);
							});
						});
					}
				}
            });
		});
		
		function updateEventTypes() {
			$.get("api/v1/event-types", {
				format: "dropdown",
				date: $("#eventDate").val()
			}).done(function(data) {
				$("#eventType").find("option").remove();
				
				$.each(data["results"], function(index, eventType) {
					$("#eventType").append("<option value='" + eventType["id"] + "'>" + eventType["text"] + "</option>");
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
			
			$.ajax({
				url: "api/v1/events", 
				type: "PUT",
				contentType: 'application/json',
				data: {
					eventId: eventId,
					eventName: eventName,
					countryCode: countryCode,
					eventDate: eventDate,
					eventTypeId: eventTypeId,
					playerCount: playerCount,
					session: Cookies.get("session")
				}
			}).done(function(data) {
				alert("Event details have been updated!");
				location.reload();
			}).fail(function(data, textStatus, xhr) {
				alert("Validation error!");
			});
		}
	</script>
<?	} ?>
</body>
</html>