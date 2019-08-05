<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?	include_once("resources/php/navigation.php"); ?>
<?	include_once("resources/php/functions.php"); ?>

<?	$eventList = json_decode(file_get_contents("https://results.trainertower.com/api.php?command=listEvents"), true); ?>

    <div class="container">
        Check out all the events that we have here on file at Trainer Tower! You can search for past events, look
        for events won by a particular player or search for events from particular countries.
    </div>
    
    <hr />

    <div class="container">
	    <table id="events" width="100%" class="display stripe compact responsive">
		    <thead>
			    <th class="text-center" data-priority=1>Date</th>
			    <th class="text-center" data-priority=2>Country</th>
			    <th class="text-center" data-priority=3>Event</th>
			    <th class="text-center" data-priority=4>Season</th>
			    <th class="text-center not-mobile" data-priority=5>Players</th>
			    <th class="text-center not-mobile" data-priority=4>Winner</th>
		    </thead>
		    <tbody>
        
<?	foreach($eventList["data"] as $eventId => $event) { ?>
				<tr>
                	<td class="text-center" data-sort="<? echo $event["date"]; ?>"><? echo date("F jS Y", strtotime($event["date"])); ?></td>
                	<td class="text-center" data-search="<? echo $event["countryName"]; ?>">
<?		if ( $event["countryCode"] != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($event["countryCode"]); ?>.png" title="<? echo $event["countryName"]; ?>" class="icon tttooltip" />
<?		} ?>
                	</td>
                	<td class="text-center"><a href="standings.php?id=<? echo $eventId; ?>"><? echo $event["eventName"]; ?></a></td>
                	<td class="text-center"><? echo $event["season"]; ?></td>
                	<td class="text-center"><? echo ($event["playerCount"] == 0 ? "Unknown" : $event["playerCount"]); ?></td>
                	<td class="text-center"><a href="player.php?id=<? echo $event["eventWinnerId"]; ?>"><? echo $event["eventWinner"]; ?></a></td>
				</tr>
<?	} ?>
            </tbody>
        </table>
    </div>

<?	include_once("resources/php/footer.php"); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.tttooltip').tooltipster();

			$('#events thead tr').clone(true).appendTo( '#events thead' );
			$('#events thead tr:eq(1) th').each( function (i) {
				var title = $(this).text();
				
				$(this).html('<input class="w-100 text-center" type="text" placeholder="Search '+title+'" />');
				
				$("input", this).on("keyup change", function () {
					if ( eventTable.column(i).search() !== this.value ) {
						eventTable
							.column(i)
							.search( this.value )
							.draw();
						$('.tttooltip').tooltipster();
					}
				});
			});
    
			eventTable = $("#events").DataTable({
				responsive: true,
				"order": [[ 0, "desc" ]],
				orderCellsTop: true,
				fixedHeader: true
			});
			
			eventTable.on('responsive-display', function (e, datatable, row, showHide, update) {
				$(document).ready(function() {
					$('.tttooltip').tooltipster();
				});
			});
			
			eventTable.on('search.dt', function () {
				$(document).ready(function() {
					$('.tttooltip').tooltipster();
				});
			});

			eventTable.on('page.dt', function () {
				$(document).ready(function() {
					$('.tttooltip').tooltipster();
				});
			});

			eventTable.on('order.dt', function () {
				$(document).ready(function() {
					$('.tttooltip').tooltipster();
				});
			});
		});
	</script>
</body>
</html>