<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?	include_once("resources/php/navigation.php"); ?>
<?	include_once("resources/php/functions.php"); ?>

<?	$eventList = json_decode(file_get_contents("https://results.trainertower.com/api.php?command=listEvents"), true); ?>
<?	$periodData = getSeasonDropdownData(); ?>

    <div class="container">
        Check out all the events that we have here on file at Trainer Tower! You can search for past events, look
        for events won by a particular player or search for events from particular countries.
    </div>
    
<?	makeSearchBarHtml($periodData); ?>

    <div class="container">
	    <table id="events" class="w-100 toggle-circle-filled table-striped period-search" data-sorting="true" data-filtering="true" data-paging="true">
		    <thead>
			    <tr>
				    <th></th>
				    <th data-sorted="true" data-name="eventDate" data-direction="DESC" class="text-center">Date</th>
				    <th data-breakpoints="xs" class="text-center"><span class="hide-detail-row">Country</span></th>
				    <th class="text-center">Event</th>
				    <th data-visible="false" data-name="season" data-type="number" class="text-center">Season</th>
				    <th class="text-center" data-type="number">Players</th>
				    <th data-breakpoints="xs sm" class="text-center">Winner</th>
			    </tr>
		    </thead>
		    <tbody>
        
<?	foreach($eventList["data"] as $eventId => $event) { ?>
				<tr>
					<td></td>
                	<td class="text-center" data-sort-value="<? echo $event["date"]; ?>" data-filter-value="<? echo str_replace("-", "", $event["date"]); ?>">
	                	<? echo date("F jS Y", strtotime($event["date"])); ?>
	                </td>
                	<td class="text-center hide-detail-row" data-filter-value="<? echo $event["countryName"]; ?>">
<?		if ( $event["countryCode"] != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($event["countryCode"]); ?>.png" title="<? echo $event["countryName"]; ?>" class="icon tttooltip" />
<?		} ?>
                	</td>
                	<td class="text-center" data-sort-value="<? echo $event["eventName"]; ?>">
	                	<span class="d-sm-inline d-md-none"><? echo getFlagEmoji(strtoupper($event["countryCode"])) . " "; ?></span>
	                	<a href="standings.php?id=<? echo $eventId; ?>"><? echo $event["eventName"]; ?></a>
	                </td>
                	<td class="text-center"><? echo $event["season"]; ?></td>
                	<td class="text-center"><? echo ($event["playerCount"] == 0 ? "Unknown" : $event["playerCount"]); ?></td>
                	<td class="text-center" data-sort-value="<? echo $event["eventWinner"]; ?>">
	                	<span><? echo getFlagEmoji(strtoupper($event["eventWinnerCountryCode"])) . " "; ?></span>
	                	<a href="player.php?id=<? echo $event["eventWinnerId"]; ?>"><? echo $event["eventWinner"]; ?></a>
	                </td>
				</tr>
<?	} ?>
            </tbody>
        </table>
    </div>

<?	include_once("resources/php/footer.php"); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#events").footable({
		       'on': {
		            'ready.ft.table': function(e, ft) {
		            	$(".tttooltip").tooltipster();
		          	},
		            'after.ft.paging': function(e, ft) {
		            	$(".tttooltip").tooltipster();
		          	},
		            'after.ft.filtering': function(e, ft) {
		            	$(".tttooltip").tooltipster();
		          	},    	
		            'after.ft.sorting': function(e, ft) {
		            	$(".tttooltip").tooltipster();
		          	}		          	
		        },
			});
		});		
	</script>
	<? echo makeSeasonDropdownJs($periodData); ?>
</body>
</html>