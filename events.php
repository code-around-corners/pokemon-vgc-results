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
		<div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="current-season">All Seasons</button>
				<div class="dropdown-menu">
					<a class="dropdown-item season-selection" href="#" data-season="-1">All Seasons</a>
					<a class="dropdown-item season-selection" href="#" data-season="2020">2020</a>
					<a class="dropdown-item season-selection" href="#" data-season="2019">2019</a>
					<a class="dropdown-item season-selection" href="#" data-season="2018">2018</a>
					<a class="dropdown-item season-selection" href="#" data-season="2017">2017</a>
					<a class="dropdown-item season-selection" href="#" data-season="-2">Past Seasons</a>
				</div>
			</div>
			<input type="text" class="form-control" aria-label="Text input with dropdown button" placeholder="Search..." id="searchFilter" />
		</div>
    </div>
    
    <hr />

    <div class="container">
	    <table id="events" class="w-100 toggle-circle-filled table-striped" data-sorting="true" data-filtering="true" data-paging="true">
		    <thead>
			    <tr>
				    <th></th>
				    <th data-sorted="true" data-direction="DESC" class="text-center">Date</th>
				    <th data-breakpoints="xs" class="text-center">Country</th>
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
                	<td class="text-center" data-sort-value="<? echo $event["date"]; ?>"><? echo date("F jS Y", strtotime($event["date"])); ?></td>
                	<td class="text-center" data-filter-value="<? echo $event["countryName"]; ?>">
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
		
		$(".season-selection").click(function() {
			var seasonId = $(this).attr("data-season");
			
			$("#current-season").text($(this).text());
			
			filter = FooTable.get("#events").use(FooTable.Filtering);
			
			if ( seasonId == -1 ) {
				filter.removeFilter("season");
			} else if ( seasonId == -2 ) {
				filter.addFilter("season", "2010 OR 2011 OR 2012 OR 2013 OR 2014 OR 2015 OR 2016", ["season"]);
			} else {
				filter.addFilter("season", seasonId, ["season"]);
			}
			
			filter.filter();
		});
		
		$("#searchFilter").on("keyup", function() {
			filterText = $(this).val();
			filter = FooTable.get("#events").use(FooTable.Filtering);
			
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