<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	$bulkInput = "";
	$showValidation = false;
	$countryList = json_decode(file_get_contents(getBaseUrl() . "api.php?command=listCountries"), true);

	if ( isset($_POST['bulk']) ) {
		$showValidation = true;
		$bulkInput = $_POST['bulk'];
		
		$bulkRows = explode("\n", $bulkInput);
		
		$bulkBatch = array();
		$currentCount = 0;
		$currentBatch = 0;

		foreach($bulkRows as $bulkRow) {
			$bulkBatch[$currentBatch] .= $bulkRow . "\n";
			
			$currentCount++;
			if ( $currentCount == 8 ) {
				$currentCount = 0;
				$currentBatch++;
			}
		}
		
		$apiData = array();

		foreach($bulkBatch as $batch) {
			$batchApiData = json_decode(file_get_contents(getBaseUrl() . "api.php?command=validate&bulk=" . base64_encode($batch)), true);

			foreach($batchApiData["data"] as $position => $record) {
				$apiData[$position] = $record;
			}
		}
	}
?>
    <div class="grey-header container">
        <h4 class="event-name">
	        <b>Upload VGC Results</b>
	    </h4>
    </div>
    
    <div class="container<? echo ($showValidation ? " collapse" : ""); ?>" id="bulkDataInput">
	    <p>
		    Enter your results into the box below in the following format:<br />
		    Position,Player,Pokemon 1,Pokemon 2,Pokemon 3,Pokemon 4,Pokemon 5,Pokemon 6
	    </p>
	    <form id="bulkInput" name="bulkData" method="post" action="upload.php">
		    <textarea id="bulk" name="bulk" style="width: 100%" rows="10"><? echo $bulkInput; ?></textarea>
		    <div class="text-center">
			    <button form="bulkInput" type="submit">Validate Input Data</button>
			    <button type="button" onclick="javascript:stripMode1();">Strip Input Mode 1</button>
			    <button type="button" onclick="javascript:stripMode2();">Strip Input Mode 2</button>
		    </div>
	    </form>
    </div>

<?	if ( $showValidation ) { ?>
	<div class="container text-center">
		<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#bulkDataInput" aria-expanded="false" aria-controls="collapseExample">
			Toggle Bulk Input Visiblity
		</button>
	</div>
<?	} ?>

<?	if ( $showValidation ) { ?>
    <div class="grey-header container">
        <h4 class="event-name">
	        <b>Bulk Entry Validation</b>
	    </h4>
    </div>

    <div class="container">
	    <p>
		    The inputs from your bulk entry box have been validated and shown below. If you have any Pokemon that were
		    not recognised, please amend your input and revalidate it to correct them. Check that the players the system
		    have matched are who you expect them to be. Any players marked as "New" you will be able to create before
		    creating the event. If you see a player marked as new you expect it already in the system, please check the
		    spelling of their name.
	    </p>
	    <table id="validation" class="w-100 toggle-circle-filled table-striped" data-sorting="false" data-filtering="false" data-paging="false">
		    <thead>
			    <th class="text-center" data-sorted="true" data-direction="ASC" data-type="number" width=10%>Position</th>
			    <th class="text-center" width=30%>Player</th>
			    <th class="text-center" width=60%>Team</th>
		    </thead>
		    <tbody>
<?	$allMapped = true; ?>
<?	foreach($apiData as $record) { ?>
<?		if ( count($record["validPlayerIds"]) == 0 ) $allMapped = false; ?>
				<tr>
					<td class="text-center"><? echo $record["position"]; ?></td>
					<td class="text-center">
						<select class="w-100">
<?		foreach($record["validPlayerIds"] as $validPlayerId => $validPlayer) { ?>
							<option value="<? echo $validPlayerId; ?>"><? echo $validPlayer["playerName"]; ?> [<? echo $validPlayer["countryName"]; ?>] (ID: <? echo $validPlayerId; ?>)</option>
<?		} ?>
							<option value="-1"><? echo $record["playerName"]; ?> (New)</option>
						</select>
					</td>
					<td class="text-center">
						<div class="row w-100">
<?		foreach($record["team"] as $pokemon) { ?>
							<div class="col-6 col-sm-4 text-center">
								<div class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></div>
								<br />
								<textarea class="w-100" rows=5><? echo encodePokemonShowdown($pokemon); ?></textarea>
							</div>
<?		} ?>
						</div>
					</td>
				</tr>
<?	} ?>
		    </tbody>
	    </table>
	    
	    <hr />
	    
	    <div class="container text-center">
		    <button data-toggle="modal" data-target="#playerCreation">Add New Players</button>
<?	if ( $allMapped ) { ?>
		    <button data-toggle="modal" data-target="#eventCreation">Enter Event Details</button>
<?	} ?>
	    </div>
    </div>
	<div id="playerCreation" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Player Creation Tool</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						Use this tool to add in any new players needed to submit this event. Please note that
						once you have added the new players you will need to revalidate your input data to allow
						the system to pick them up.
					</p>
					<table id="playerAdd" class="w-100 toggle-circle-filled table-striped" data-sorting="true">
						<thead>
							<th data-name="playerName" data-sorted="true">Player Name</th>
							<th data-name="country">Country</th>
							<th data-name="twitter">Twitter</th>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:uploadNewPlayers();">Add New Players</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div id="eventCreation" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Event Creation Tool</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						Now that your players are all available in the system, you can create the event information for
						this event and upload it.
					</p>
					<div class="w-100 form-group">
						<div class="row pb-1">
							<div class="col-4">Event Name</div>
							<div class="col-8"><input type="text" class="form-control" id="eventName" /></div>
						</div>
						
						<div class="row pb-1">
							<div class="col-4">Event Country</div>
							<div class="col-8">
								<select class="w-100 form-control" id="eventCountry">
									<option value=""></option>
	<?	foreach($countryList["data"] as $countryCode => $countryName) { ?>
									<option value="<? echo $countryCode; ?>"><? echo $countryName; ?></option>
	<?	} ?>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Date</div>
							<div class="col-8">
								<input class="form-control" type="text" id="eventDate" onchange="javascript:updateEventTypes();" />
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Event Type</div>
							<div class="col-8">
								<select class="w-100 form-control" id="eventType">
									<option value="">Select a date first!</option>
								</select>
							</div>
						</div>

						<div class="row pb-1">
							<div class="col-4">Player Count</div>
							<div class="col-8"><input class="form-control" type="number" id="playerCount" /></div>
							
							<p class="pt-1">
								You can specify 0 for the player count if you don't know the correct count, however
								be aware that this will prevent CP totals from being calculated correctly as the system
								won't have the correct kicker counts.
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="javascript:createNewEvent();">Add New Event</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?	} ?>

<?	include_once("resources/php/footer.php"); ?>

	<script lang="text/javascript">
		function stripMode1() {
			bulkDom = jQuery("<div>" + $("#bulk").val() + "</div>");
			
			strippedText = "";
			
			bulkDom.children("div").each(function() {
				position = $(this).find("div").eq(0).text().replace(/[^0-9]/g, "");
				playerName = jQuery.trim($(this).find("div").eq(1).text());
				pokemon = jQuery.trim($(this).find("div").last().text());
				
				if ( pokemon.toLowerCase().indexOf("[pkmn]") > -1 ) {
					pokemon = pokemon.replace(/\[\/pkmn\]\[pkmn\]/g, ",");
					pokemon = pokemon.replace("[pkmn]", "");
					pokemon = pokemon.replace("[/pkmn]", "");
				} else {
					pkmn = "";
					$(this).find("div").last().find("img").each(function() {
						basePkmn = $(this).attr("src").replace(/.*\//, "").replace(".png", "");
						if ( pkmn == "" ) {
							pkmn = basePkmn;
						} else {
							pkmn += "," + basePkmn;
						}
					});
					
					pokemon = pkmn;
				}
				
				strippedText += position + "," + playerName + "," + pokemon + "\n";
			});
			
			$("#bulk").val(strippedText);
		}
		
		function stripMode2() {
			baseData = $("#bulk").val().replace(/ Victory Road/g, ",").split("\n");
			parsedData = "";
			position = 0;
			
			$.each(baseData, function(id, data) {
				position++;
				rowData = data.split("\t");
				
				parsedData += position + "," + rowData[3] + "," + rowData[5] + "\n";
			});
			
			$("#bulk").val(parsedData);
		}
		
		function getNewPlayerList() {
			var newPlayers = {};
			
			$("#validation").find("tr").each(function() {
				position = $(this).find("td").eq(0).text();
				playerId = $(this).find("select").val();
				
				if ( playerId == -1 ) {
					playerName = $(this).find("select option:selected").text().replace(" (New)", "");
					newPlayers[position] = playerName;
				}
			});
			
			return newPlayers;
		}
		
		$("#playerCreation").on("show.bs.modal", function() {
			playerList = getNewPlayerList();
			
			countrySelect = "<select>";
			countrySelect += "<option value='xxx'>Unknown</option>";
<?	foreach($countryList["data"] as $countryCode => $countryName) { ?>
			countrySelect += "<option value='<? echo $countryCode; ?>'><? echo $countryName; ?></option>";
<?	} ?>
			countrySelect += "</select>";
			
			var playerTable = FooTable.get("#playerAdd");
			
			var newRows = [];
			$.each(playerList, function(position, playerName) {
				newRows[newRows.length] = {
					"playerName": playerName,
					"country": countrySelect,
					"twitter": "<input type='text' class='form-control form-control-sm w-100' />"
				};
			});
			
			playerTable.rows.load(newRows);
		});
		
		function uploadNewPlayers() {
			$("#playerAdd").find("tr").each(function() {
				playerName = $(this).find("td").eq(0).text();
				countryCode = $(this).find("td").eq(1).find("select").eq(0).val();
				twitter = $(this).find("td").eq(2).find("input").eq(0).val();
				
				if ( playerName != "" ) {
					$.get("api.php", {
						command: "addPlayer",
						playerName: playerName,
						countryCode: countryCode,
						twitter: twitter,
						key: $("#currentApiKey").attr("data-api-key")
					});
				}
			});
			
			alert("New players have been added to the database! Please revalidate your data to pick up the changes.");
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
		
		function createNewEvent() {
			eventName = $("#eventName").val();
			countryCode = $("#eventCountry").val();
			eventDate = $("#eventDate").val();
			eventTypeId = $("#eventType").val();
			playerCount = $("#playerCount").val();
			
			if ( eventName == "" || countryCode == "" || eventDate == "" || eventTypeId == "" ) {
				alert("Please make sure all the fields have been filled out!");
				return;
			}
			
			$.get("api.php", {
				command: "addEvent",
				eventName: eventName,
				countryCode: countryCode,
				eventDate: eventDate,
				eventTypeId: eventTypeId,
				playerCount: playerCount,
				key: $("#currentApiKey").attr("data-api-key")
			}).done(function(data) {
				console.log(data);
				
				eventId = data["data"];
				
				$("#validation").find("tbody").find("tr").each(function() {
					position = $(this).find("td").eq(0).text();
					playerId = $(this).find("td").eq(1).find("select").eq(0).val();

					pokemon1 = btoa($(this).find("td").eq(2).find("textarea").eq(0).val());
					pokemon2 = btoa($(this).find("td").eq(2).find("textarea").eq(1).val());
					pokemon3 = btoa($(this).find("td").eq(2).find("textarea").eq(2).val());
					pokemon4 = btoa($(this).find("td").eq(2).find("textarea").eq(3).val());
					pokemon5 = btoa($(this).find("td").eq(2).find("textarea").eq(4).val());
					pokemon6 = btoa($(this).find("td").eq(2).find("textarea").eq(5).val());
					
					$.get("api.php", {
						command: "addResult",
						eventId: eventId,
						position: position,
						playerId: playerId,
						pokemon1: pokemon1,
						pokemon2: pokemon2,
						pokemon3: pokemon3,
						pokemon4: pokemon4,
						pokemon5: pokemon5,
						pokemon6: pokemon6,
						key: $("#currentApiKey").attr("data-api-key")
					}).done(function(data) {
						console.log(data);
					});
				});
				
				alert("New event added! ID " + data["data"]);
				window.location = "upload.php";
			});
		}

		playerTable = null;

		$(document).ready(function() {
			PkSpr.process_dom();
            $('.tttooltip').tooltipster();
            $("#eventDate").datepicker({
	            format: 'yyyy-mm-dd'
			});
			
			resultTable = $("#validation").footable();			
			playerTable = $("#playerAdd").footable();
		});
	</script>
</body>
</html>