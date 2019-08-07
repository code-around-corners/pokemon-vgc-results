<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?	include_once("resources/php/functions.php"); ?>
<?	include_once("resources/php/navigation.php"); ?>

<?	$eventName = ""; ?>
<?	$resultData = array(); ?>
<?	$showOnlyCp = false; ?>
<?	$eventId = -1; ?>

<?	if ( isset($_GET["id"]) ) { ?>
<?		$eventId = $_GET["id"]; ?>
<?		$resultData = json_decode(file_get_contents("https://results.trainertower.com/api.php?command=eventResults&eventId=" . $eventId), true); ?>

<?		$eventName = $resultData["data"]["events"][$eventId]["eventName"];	?>
<?		$eventCountryCode = strtolower($resultData["data"]["events"][$eventId]["countryCode"]);	?>
<?		$eventCountry = $resultData["data"]["events"][$eventId]["countryName"];	?>
<?	} ?>

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
		 </h6>
	</div>
	
	<hr />

	<div class="container">
		<div class="input-group input-group-sm">
			<input type="text" class="form-control" aria-label="Text input with dropdown button" placeholder="Search..." id="searchFilter" />
		</div>
	</div>
	
	<hr />

	<div class="container">
		 <table id="results" class="w-100 toggle-circle-filled table-striped" data-sorting="true" data-filtering="true" data-paging="false">
			  <thead>
				   <th></th>
				   <th class="text-center" data-sorted="true" data-direction="ASC" data-type="number">Position</th>
				   <th class="text-center" data-breakpoints="xs sm"><span class="hide-detail-row">Country</span></th>
				   <th class="text-center">Player</th>
				   <th class="text-center" data-type="number">CP</th>
				   <th class="text-center" data-breakpoints="xs">Team</th>
				   <th class="text-center" data-breakpoints="xs sm">Export Team</th>
			  </thead>
			  <tbody>
		
<?	foreach($resultData["data"]["results"][$eventId] as $position => $result) { ?>
<?		if ( $result["points"] == 0 && $showOnlyCp ) continue; ?>
				<tr>
					<td></td>
					<td class="text-center"><? echo $position; ?></td>
					<td class="text-center hide-detail-row" data-filter-value="<? echo $result["playerCountryName"]; ?>">
<?		if ( $result["playerCountryCode"] != "" ) { ?>
						<img src="resources/images/flags/<? echo strtolower($result["playerCountryCode"]); ?>.png" title="<? echo $result["playerCountryName"]; ?>" class="icon tttooltip"/>
<?		} ?>
					</td>
					<td class="text-center">
					 	<a href="player.php?id=<? echo $result["playerId"]; ?>">
						  	<span class="d-md-inline d-lg-none"><? echo getFlagEmoji(strtoupper($result["playerCountryCode"])) . " "; ?></span>
						  	<? echo $result["playerName"]; ?>
						  </a>
					 </td>
					<td class="text-center"><? echo $result["points"]; ?></td>
<?		$pokemonSearch = ""; ?>
<?		$showdownExport = ""; ?>
<?		foreach($result["team"] as $pokemon) { ?>
<?			$pokemonSearch .= decodePokemonLabel($pokemon) . " "; ?>
<?			$showdownExport .= encodePokemonShowdown($pokemon) . "\n"; ?>
<?		} ?>
					<td class="text-center team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?		$pokemonCount = 0; ?>
<?		foreach($result["team"] as $pokemon) { ?>
<?			$pokemonCount++; ?>
						<span class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
<?			if ( $pokemonCount == 3 ) { ?>
						<br class="phone-line-break" />
<?			} ?>
<?		} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox('<? echo base64_encode($showdownExport); ?>');"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?		if ( $result["rentalLink"] != "" ) { ?>
						&nbsp;&nbsp;<a href="<? echo $result["rentalLink"]; ?>" target="_new"><i class="fas fas-large fa-qrcode tttooltip" title="Export Rental Team"></i></a>
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
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
				
		$("#searchFilter").on("keyup", function() {
			filterText = $(this).val();
			filter = FooTable.get("#results").use(FooTable.Filtering);
			
			if ( filterText == "" || filterText.length < 3 ) {
				filter.removeFilter("generic");
			} else {
				filter.addFilter("generic", filterText);
			}			

			filter.filter();
		});
	</script>
</body>
</html>