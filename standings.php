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

    <div class="container">
	    <table id="results" width="100%" class="display stripe compact responsive nowrap">
		    <thead>
			    <th class="text-center" data-priority=1>Position</th>
			    <th class="text-center not-mobile" data-priority=5>Country</th>
			    <th class="text-center" data-priority=2>Player</th>
			    <th class="text-center" data-priority=3>CP</th>
			    <th class="text-center team-column" data-priority=4>Team</th>
			    <th class="text-center not-mobile" data-priority=6>Export Team</th>
		    </thead>
		    <tbody>
        
<?	foreach($resultData["data"]["results"][$eventId] as $position => $result) { ?>
<?		if ( $result["points"] == 0 && $showOnlyCp ) continue; ?>
				<tr>
                	<td class="text-center"><? echo $position; ?></td>
                	<td class="text-center" data-search="<? echo $result["playerCountryName"]; ?>">
<?		if ( $result["playerCountryCode"] != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($result["playerCountryCode"]); ?>.png" title="<? echo $result["playerCountryName"]; ?>" class="icon tttooltip"/>
<?		} ?>
                	</td>
                	<td class="text-center">
	                	<a href="player.php?id=<? echo $result["playerId"]; ?>"><? echo $result["playerName"]; ?></a>
	                </td>
                	<td class="text-center"><? echo $result["points"]; ?></td>
<?		$pokemonSearch = ""; ?>
<?		$showdownExport = ""; ?>
<?		foreach($result["team"] as $pokemon) { ?>
<?			$pokemonSearch .= decodePokemonLabel($pokemon) . ","; ?>
<?			$showdownExport .= encodePokemonShowdown($pokemon) . "\n"; ?>
<?		} ?>
                	<td class="text-center" data-search="<? echo $pokemonSearch; ?>">
<?		foreach($result["team"] as $pokemon) { ?>
						<span class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
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
			PkSpr.process_dom();
            $('.tttooltip').tooltipster();

			resultTable = $("#results").DataTable({
				responsive: true,
				"order": [[ 0, "asc" ]],
				"lengthMenu": [ [8, 16, 32, 64, 128, -1], ["Top 8", "Top 16", "Top 32", "Top 64", "Top 128", "All"] ]
			});
			
			resultTable.on('responsive-display', function (e, datatable, row, showHide, update) {
				$(document).ready(function() {
					PkSpr.process_dom();
					$('.tttooltip').tooltipster();
				});
			});
			
			resultTable.on('search.dt', function () {
				$(document).ready(function() {
					PkSpr.process_dom();
					$('.tttooltip').tooltipster();
				});
			});

			resultTable.on('page.dt', function () {
				$(document).ready(function() {
					PkSpr.process_dom();
					$('.tttooltip').tooltipster();
				});
			});

			resultTable.on('order.dt', function () {
				$(document).ready(function() {
					PkSpr.process_dom();
					$('.tttooltip').tooltipster();
				});
			});
		});
	</script>
</body>
</html>