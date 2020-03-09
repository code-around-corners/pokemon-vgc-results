<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	$resultJson = @file_get_contents(getBaseUrl() . "api/v1/results?format=full&q=" . urlencode($_POST["search-filter"]));
	
	if ( $resultJson != "" ) {
		$resultData = json_decode($resultJson, true);
	} else {
		$resultData = null;
	}

?>
	<div class="grey-header container">
		<h4 class="event-name">
			 <b>Team Search Results</b>
		</h4>
		<h6 class="event-name">
			 <b>Search Terms</b> - <? echo $_POST["search-filter"]; ?>
		</h6>
	</div>
	
<?	makeSearchBarHtml(null); ?>

	<div class="container">
		<table id="results" class="w-100 toggle-circle-filled table-striped period-search" data-sorting="true" data-filtering="true" data-paging="true">
			<thead>
				<th></th>
				<th data-sorted="true" data-name="eventDate" data-direction="DESC" class="text-center">Date</th>
				<th class="text-center">Event</th>
				<th class="text-center">Player</th>
				<th class="text-center" data-breakpoints="xs"><span class="hide-detail-row">Team</span></th>
				<th class="text-center" data-breakpoints="xs sm">Export Team</th>
			</thead>
			<tbody>

<?	if ( $resultData !== null ) { ?>
<?		foreach($resultData as $resultId => $result) { ?>
				<tr>
					<td></td>
					<td class="text-center"><? echo $result["event"]["date"]; ?></td>
					<td class="text-center">
					 	<a href="standings.php?id=<? echo $result["event"]["id"]; ?>">
							<? echo $result["event"]["flagEmoji"] . " " . $result["event"]["name"]; ?>
					 	</a>
					</td>
					<td class="text-center">
					 	<a href="player.php?id=<? echo $result["player"]["id"]; ?>">
						  	<? echo $result["player"]["flagEmoji"] . " " . $result["player"]["name"]; ?>
						</a>
					</td>
<?			$pokemonSearch = ""; ?>
<?			$showdownExport = ""; ?>
<?			foreach($result["team"] as $pokemon) { ?>
<?				$pokemonSearch .= $pokemon["pokemon"] . " "; ?>
<?			} ?>
					<td class="text-center hide-detail-row team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?			foreach($result["team"] as $pokemon) { ?>
						<span class="pkspr-gen8-box"><span class="tttooltip <? echo $pokemon["class"]; ?>" title="<? echo $pokemon["name"]; ?>"></span></span>
<?			} ?>
					</td>
					<td class="text-center">
						<a href="javascript:showExportBox('<? echo $resultId ?>');"><i class="fas fas-large fa-globe tttooltip" title="Export Pokemon Showdown"></i></a>
<?			if ( isset($result["rentalTeamUrl"]) ) { ?>
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
	</script>
	<? echo makeSeasonDropdownJs(null); ?>
</body>
</html>
