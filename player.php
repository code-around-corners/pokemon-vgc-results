<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?	include_once("resources/php/functions.php"); ?>
<?	include_once("resources/php/navigation.php"); ?>

<?	$playerId = -1; ?>
<?	$playerData = array(); ?>

<?	if ( isset($_GET["id"]) ) { ?>
<?		$playerId = (int)$_GET["id"]; ?>
<?		$playerData = json_decode(file_get_contents("https://results.trainertower.com/api.php?command=playerInfo&playerId=" . $playerId), true); ?>
<?	} ?>

    <div class="grey-header container">
        <h4 class="event-name">
<?	if ( $playerData["data"]["player"]["countryCode"] != "" ) { ?>
            <img src="resources/images/flags/<? echo strtolower($playerData["data"]["player"]["countryCode"]); ?>.png" class="icon" />
<?	} ?>
            <strong><? echo $playerData["data"]["player"]["playerName"]; ?></strong>
<?	if ( isset($playerData["data"]["player"]["socialMedia"]["facebook"]) ) { ?>	            
	        <a href="http://www.facebook.com/<? echo $playerData["data"]["player"]["socialMedia"]["facebook"]; ?>" target="_blank">
		        <img src="resources/images/social/facebook.png" alt="facebook" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["data"]["player"]["socialMedia"]["twitter"]) ) { ?>	            
	        <a href="http://www.twitter.com/<? echo $playerData["data"]["player"]["socialMedia"]["twitter"]; ?>" target="_blank">
		        <img src="resources/images/social/twitter.png" alt="twitter" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["data"]["player"]["socialMedia"]["youtube"]) ) { ?>	            
	        <a href="http://www.youtube.com/user/<? echo $playerData["data"]["player"]["socialMedia"]["youtube"]; ?>" target="_blank">
		        <img src="resources/images/social/youtube.png" alt="youtube" class="icon" />
		    </a>
<?	} ?>
<?	if ( isset($playerData["data"]["player"]["socialMedia"]["twitch"]) ) { ?>	            
	        <a href="http://www.twitch.com/u/<? echo $playerData["data"]["player"]["socialMedia"]["twitch"]; ?>" target="_blank">
		        <img src="resources/images/social/twitch.png" alt="twitch" class="icon" />
		    </a>
<?	} ?>
        </h4>
    </div>

    <div class="container">
	    <table id="results" width="100%" class="display stripe compact responsive">
		    <thead>
			    <th class="text-center" data-priority=1>Date</th>
			    <th class="text-center not-mobile" data-priority=5>Country</th>
			    <th class="text-center" data-priority=2>Tournament</th>
			    <th class="text-center" data-priority=3>Position</th>
			    <th class="text-center not-mobile" data-priority=4>CP</th>
			    <th class="text-center not-mobile" data-priority=6>Team</th>
			    <th class="text-center not-mobile">Export Team</th>
		    </thead>
		    <tbody>
<?	$recordCount = 0; ?>
<?	foreach($playerData["data"]["results"]["results"] as $eventId => $event) { ?>
<?		foreach($event as $resultId => $result) { ?>
<?			if ( $result["points"] == 0 && $showOnlyCp ) continue; ?>
<?			$recordCount++; ?>
<?			$eventCountryCode = $playerData["data"]["results"]["events"][$eventId]["countryCode"]; ?>
<?			$eventCountryName = $playerData["data"]["results"]["events"][$eventId]["countryName"]; ?>

				<tr>
					<td class="text-center" data-sort="<? echo $playerData["data"]["results"]["events"][$eventId]["date"]; ?>">
						<? echo date("F jS Y", strtotime($playerData["data"]["results"]["events"][$eventId]["date"])); ?>
					</td>
                	<td class="text-center" data-search="<? echo $eventCountryName; ?>">
<?			if ( $eventCountryCode != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($eventCountryCode); ?>.png" title="<? echo $eventCountryName; ?>" class="icon tttooltip" />
<?			} ?>
                	</td>
					<td class="text-center">
			            <a href="standings.php?id=<? echo $eventId; ?>">
<?			echo $playerData["data"]["results"]["events"][$eventId]["eventName"]; ?>
			            </a>
					</td>
					<td class="text-center"><? echo $result["position"]; ?></td>
					<td class="text-center"><? echo $result["points"]; ?></td>
					<td class="text-center">
<?			$showdownExport = ""; ?>
<?			foreach($result["team"] as $pokemon) { ?>
						<span class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
<?				$showdownExport .= encodePokemonShowdown($pokemon) . "\n"; ?>
<?			} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox('<? echo base64_encode($showdownExport); ?>');"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?		if ( $result["rentalLink"] != "" ) { ?>
						&nbsp;&nbsp;<a href="<? echo $result["rentalLink"]; ?>" target="_new"><i class="fas fas-large fa-qrcode tttooltip" title="Export Rental Team"></i></a>
<?		} ?>
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
				"order": [[ 0, "desc" ]]
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