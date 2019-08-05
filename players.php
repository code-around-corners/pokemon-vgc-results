<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?	include_once("resources/php/navigation.php"); ?>
<?	include_once("resources/php/functions.php"); ?>

<?	$playerList = json_decode(file_get_contents("https://results.trainertower.com/api.php?command=listPlayers"), true); ?>

    <div class="container">
	    The player list is a roster of every player recorded in the Trainer Tower database. You can search
	    for an individual player or search by country. Where available we also have Twitter handles, as well
	    as the last recorded event we have for a player (remember we don't usually track smaller events so this
	    won't be 100% accurate but it reflects the latest event we have on file).
    </div>
    
    <hr />

    <div class="container">
	    <table id="events" width="100%" class="display stripe compact responsive">
		    <thead>
			    <th class="text-center">Country</th>
			    <th class="text-center">Player</th>
			    <th class="text-center">Last Recorded Event</th>
			    <th class="text-center not-mobile">Social Media</th>
		    </thead>
		    <tbody>
        
<?	foreach($playerList["data"] as $playerId => $player) { ?>
				<tr>
                	<td class="text-center" data-search="<? echo $player["countryName"]; ?>">
<?		if ( $player["countryCode"] != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($player["countryCode"]); ?>.png" title="<? echo $player["countryName"]; ?>" class="icon tttooltip" />
<?		} ?>
                	</td>
                	<td class="text-center"><a href="player.php?id=<? echo $playerId; ?>"><? echo $player["playerName"]; ?></a></td>
<?		if ( $player["lastEventDate"] != null ) { ?>
                	<td class="text-center" data-sort="<? echo $player["lastEventDate"]; ?>"><? echo date("F jS Y", strtotime($player["lastEventDate"])); ?></td>
<?		} else { ?>
					<td class="text-center" data-sort=""></td>
<?		} ?>
                	<td class="text-center">
<?		if ( isset($player["socialMedia"]["facebook"]) ) { ?>	            
			            <a href="http://www.facebook.com/<? echo $player["socialMedia"]["facebook"]; ?>" target="_blank">
				            <img src="resources/images/social/facebook.png" alt="facebook" class="small-icon" />
				        </a>
<?		} ?>
<?		if ( isset($player["socialMedia"]["twitter"]) ) { ?>	            
			            <a href="http://www.twitter.com/<? echo $player["socialMedia"]["twitter"]; ?>" target="_blank">
				            <img src="resources/images/social/twitter.png" alt="twitter" class="small-icon" />
				        </a>
<?		} ?>
<?		if ( isset($player["socialMedia"]["youtube"]) ) { ?>	            
			            <a href="http://www.youtube.com/user/<? echo $player["socialMedia"]["youtube"]; ?>" target="_blank">
				            <img src="resources/images/social/youtube.png" alt="youtube" class="small-icon" />
				        </a>
<?		} ?>
<?		if ( isset($player["socialMedia"]["twitch"]) ) { ?>	            
			            <a href="http://www.twitch.com/u/<? echo $player["socialMedia"]["twitch"]; ?>" target="_blank">
				            <img src="resources/images/social/twitch.png" alt="twitch" class="small-icon" />
				        </a>
<?		} ?>
                	</td>
				</tr>
<?	} ?>
            </tbody>
        </table>
    </div>

<?	include_once("resources/php/footer.php"); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.tttooltip').tooltipster();

			eventTable = $("#events").DataTable({
				responsive: true,
				"order": [[ 2, "desc" ], [ 1, "asc" ]]
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