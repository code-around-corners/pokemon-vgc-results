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
		$resultJson = file_get_contents(getBaseUrl() . "api.php?command=eventResults&eventId=" . $eventId);
		
		if ( $resultJson != "" ) {
			$resultData = json_decode($resultJson, true);
			if ( ! isset($resultData["data"]["events"][$eventId]) ) {
				$resultData = null;
			}
		} else {
			$resultData = null;
		}

		if ( $resultData !== null ) {
			$eventName = $resultData["data"]["events"][$eventId]["eventName"];
			$eventCountryCode = strtolower($resultData["data"]["events"][$eventId]["countryCode"]);
			$eventCountry = $resultData["data"]["events"][$eventId]["countryName"];
		} else {
			$eventName = "Unknown Event";
			$eventCountryCode = "xxx";
			$eventCountry = "";
		}
	}
?>
	<div class="grey-header container">
		<h4 class="event-name">
			 <img src="resources/images/flags/<? echo $eventCountryCode; ?>.png" alt="<? echo $eventCountry; ?>" class="icon" />&nbsp;
			 <b><? echo $eventName; ?></b>
		 </h4>
		<h6 class="event-name">
			 <? echo date("F jS Y", strtotime($resultData["data"]["events"][$eventId]["date"])); ?>
<?	if ( $resultData["data"]["events"][$eventId]["playerCount"] > 0 ) { ?>
			 | <? echo $resultData["data"]["events"][$eventId]["playerCount"]; ?> Players
<?	} ?>
<?	if ( isset($_SESSION['apiUser']) && $_SESSION['apiUser'] != "" ) { ?>
		<span class="text-center"> | 
			<a href="edit.php?eventId=<? echo $eventId; ?>">Edit This Event</a>
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
				   <th class="text-center" data-type="number" data-breakpoints="xs">CP</th>
				   <th class="text-center" data-breakpoints="xs"><span class="hide-detail-row">Team</span></th>
				   <th class="text-center" data-breakpoints="xs sm">Export Team</th>
			  </thead>
			  <tbody>

<?	if ( $resultData !== null ) { ?>
<?		foreach($resultData["data"]["results"][$eventId] as $position => $result) { ?>
<?			if ( $result["points"] == 0 && $showOnlyCp ) continue; ?>
				<tr>
					<td></td>
					<td class="text-center"><? echo $position; ?></td>
					<td class="text-center hide-detail-row" data-filter-value="<? echo $result["playerCountryName"]; ?>">
<?			if ( $result["playerCountryCode"] != "" ) { ?>
						<img src="resources/images/flags/<? echo strtolower($result["playerCountryCode"]); ?>.png" title="<? echo $result["playerCountryName"]; ?>" class="icon tttooltip"/>
<?			} ?>
					</td>
					<td class="text-center">
					 	<a href="player.php?id=<? echo $result["playerId"]; ?>">
						  	<span class="d-md-inline d-lg-none"><? echo getFlagEmoji(strtoupper($result["playerCountryCode"])) . " "; ?></span>
						  	<? echo $result["playerName"]; ?>
						</a>
						<span class="d-sm-inline d-md-none">
							<br />
<?			foreach($result["team"] as $pokemon) { ?>
							<span class="tttooltip d-md-inline d-lg-none <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
<?			} ?>
						</span>
					 </td>
					<td class="text-center"><? echo $result["points"]; ?></td>
<?			$pokemonSearch = ""; ?>
<?			$showdownExport = ""; ?>
<?			foreach($result["team"] as $pokemon) { ?>
<?				$pokemonSearch .= decodePokemonLabel($pokemon) . " "; ?>
<?				$showdownExport .= encodePokemonShowdown($pokemon) . "\n"; ?>
<?			} ?>
					<td class="text-center hide-detail-row team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?			foreach($result["team"] as $pokemon) { ?>
						<span class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
<?			} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox('<? echo base64_encode($showdownExport); ?>');"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?			if ( $result["rentalLink"] != "" ) { ?>
						&nbsp;&nbsp;<a href="<? echo $result["rentalLink"]; ?>" target="_new"><i class="fas fas-large fa-qrcode tttooltip" title="Export Rental Team"></i></a>
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
		function showExportBox(teamExport) {
			$("#export").val(atob(teamExport));
			$("#psExport").modal("show");
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
	</script>
	<? echo makeSeasonDropdownJs(null); ?>
</body>
</html>