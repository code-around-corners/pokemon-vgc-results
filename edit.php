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
		$eventData = json_decode(file_get_contents(getBaseUrl() . "api.php?command=eventResults&eventId=" . $eventId), true);
?>
    <div class="grey-header container">
        <h4 class="event-name">
	        <b>Edit VGC Results</b>
	    </h4>
		<h6 class="event-name">
			<span class="text-center">
				<a href="standings.php?id=<? echo $eventId; ?>">Return To This Event</a>
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
<?		foreach($eventData["data"]["results"][$eventId] as $position => $record) { ?>
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

<?	} ?>

<?	include_once("resources/php/footer.php"); ?>

	<script lang="text/javascript">
		$(document).ready(function() {
			PkSpr.process_dom();
            $('.tttooltip').tooltipster();
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
	</script>
</body>
</html>