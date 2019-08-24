<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	$eventName = "";
	$resultData = array();
	$showOnlyCp = false;
	$eventId = -1;

	if ( isset($_GET["id"]) ) {
		$eventId = $_GET["id"];
		$countryData = json_decode(@file_get_contents(getBaseUrl() . "api/v1/countries"), true);
		$resultData = json_decode(@file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId . "/results"), true);	
		$eventsData = json_decode(@file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId), true);
		$playersData = json_decode(@file_get_contents(getBaseUrl() . "api/v1/events/" . $eventId . "/players"), true);
		$eventHasCp = ($eventsData["points"][1] > 0);
	}
?>
	<div class="grey-header container">
		<h4 class="event-name">
			 <img src="<? echo $countryData[$eventsData["countryCode"]]["flagUrl"]; ?>" alt="<? echo $eventsData["country"]; ?>" class="icon" />&nbsp;
			 <b><? echo $eventsData["name"]; ?></b>
		 </h4>
		<h6 class="event-name">
			 <? echo date("F jS Y", strtotime($eventsData["date"])); ?>
<?	if ( $eventsData["playerCount"] > 0 ) { ?>
			 | <? echo $eventsData["playerCount"]; ?> Players
<?	} ?>
<?	if ( isset($_COOKIE["key"]) && $_COOKIE["key"] != "" ) { ?>
			<span class="text-center">
				| <a href="edit.php?eventId=<? echo $eventId; ?>">Edit This Event</a>
				| <a href="#!" class="delete-event" onclick="javascript:deleteEvent();">Delete This Event</a>
			</span>
<?	} ?>
		</h6>
	</div>
	
<?	makeSearchBarHtml(null); ?>

	<div class="container">
		 <table id="results" class="w-100 toggle-circle-filled table-striped period-search" data-sorting="true" data-filtering="true" data-paging="false">
			  <thead>
				   <th></th>
				   <th class="text-center" data-sorted="true" data-direction="ASC" data-type="number">#</th>
				   <th class="text-center" data-breakpoints="xs sm"><span class="hide-detail-row">Country</span></th>
				   <th class="text-center">Player</th>
				   <th class="text-center" <? echo ($eventHasCp ? "" : "data-visible='false' "); ?> data-type="number" data-breakpoints="xs">CP</th>
				   <th class="text-center" data-breakpoints="xs"><span class="hide-detail-row">Team</span></th>
				   <th class="text-center" data-breakpoints="xs sm">Export Team</th>
			  </thead>
			  <tbody>

<?	if ( $resultData !== null ) { ?>
<?		foreach($resultData as $resultId => $result) { ?>
				<tr>
					<td></td>
					<td class="text-center"><? echo $result["position"]; ?></td>
					<td class="text-center hide-detail-row" data-filter-value="<? echo $playersData[$result["playerId"]]["country"]; ?>">
						<img src="<? echo $countryData[$playersData[$result["playerId"]]["countryCode"]]["flagUrl"]; ?>" title="<? echo $playersData[$result["playerId"]]["country"]; ?>" class="icon tttooltip"/>
					</td>
					<td class="text-center">
					 	<a href="player.php?id=<? echo $result["playerId"]; ?>">
						  	<span class="d-md-inline d-lg-none"><? echo $countryData[$playersData[$result["playerId"]]["countryCode"]]["flagEmoji"] . " "; ?></span>
						  	<? echo $playersData[$result["playerId"]]["name"]; ?>
						</a>
						<span class="d-sm-inline d-md-none">
							<br />
<?			foreach($result["team"] as $pokemon) { ?>
							<span class="tttooltip d-md-inline d-lg-none <? echo $pokemon["class"]; ?>" title="<? echo $pokemon["name"]; ?>"></span>
<?			} ?>
						</span>
					 </td>
					<td class="text-center"><? echo $result["points"]; ?></td>
<?			$pokemonSearch = ""; ?>
<?			foreach($result["team"] as $pokemon) { ?>
<?				$pokemonSearch .= $pokemon["name"] . " "; ?>
<?			} ?>
					<td class="text-center hide-detail-row team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?			foreach($result["team"] as $pokemon) { ?>
						<span class="tttooltip <? echo $pokemon["class"]; ?>" title="<? echo $pokemon["name"]; ?>"></span>
<?			} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox(<? echo $resultId; ?>);"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?			if ( isset($result["rentalTeamUrl"])) { ?>
						&nbsp;&nbsp;<a href="<? echo $result["rentalTeamUrl"]; ?>" target="_new"><i class="fas fas-large fa-qrcode tttooltip" title="Export Rental Team"></i></a>
<?			} ?>
					</td>
				</tr>
<?		} ?>
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
		
		function deleteEvent() {
			if ( ! confirm("Are you sure you want to delete the event '<? echo $eventsData["name"]; ?>'? All the standings and teams" +
				"for this event will be removed from the system!") ) {
			
				return;
			}
			
			if ( ! confirm("Please confirm again that you want to delete this event. This cannot be undone!") ) {
				return;
			}
			
			$.ajax({
				url: "api/v1/events", 
				type: "DELETE",
				data: {
					eventId:	<? echo $eventId; ?>,
					key: 		$("#currentApiKey").attr("data-api-key")
				}
			}).done(function(data) {
				alert("The event '<? echo $eventsData["name"]; ?>' has been removed from the database.");
				window.location = "index.php";
			});
		}
	</script>
	<? echo makeSeasonDropdownJs(null); ?>
</body>
</html>