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
    
<?	makeSearchBarHtml($periodData); ?>
    
    <div class="container">
	    <table id="events" class="w-100 toggle-circle-filled table-striped period-search" data-sorting="true" data-filtering="true" data-paging="true">
		    <thead>
			    <th></th>
			    <th class="text-center" data-breakpoints="xs sm"><span class="hide-detail-row">Country</span></th>
			    <th class="text-center" data-sorted="true" data-direction="ASC">Player</th>
			    <th class="text-center" data-breakpoints="xs">Last Recorded Event</th>
			    <th class="text-center team-column" data-breakpoints="xs">Last Recorded Team</th>
			    <th class="text-center">Social Media</th>
		    </thead>
		    <tbody>
        
<?	foreach($playerList["data"] as $playerId => $player) { ?>
				<tr>
					<td></td>
                	<td class="text-center hide-detail-row" data-filter-value="<? echo $player["countryName"]; ?>">
<?		if ( $player["countryCode"] != "" ) { ?>
                		<img src="resources/images/flags/<? echo strtolower($player["countryCode"]); ?>.png" title="<? echo $player["countryName"]; ?>" class="icon tttooltip" />
<?		} ?>
                	</td>
                	<td class="text-center" data-sort-value="<? echo $player["playerName"]; ?>">
	                	<span class="d-sm-inline d-md-none"><? echo getFlagEmoji(strtoupper($player["countryCode"])) . " "; ?></span>
	                	<a href="player.php?id=<? echo $playerId; ?>"><? echo $player["playerName"]; ?></a>
	                </td>
<?		if ( $player["lastEventDate"] != null ) { ?>
                	<td class="text-center" data-sort-value="<? echo $player["lastEventDate"]; ?>">
	                	<a href="standings.php?id=<? echo $player["lastEventId"]; ?>">
		                	<? echo date("F jS Y", strtotime($player["lastEventDate"])); ?>
	                	</a>
	                </td>
<?			$pokemonSearch = ""; ?>
<?			$showdownExport = ""; ?>
<?			foreach($player["lastTeam"] as $pokemon) { ?>
<?				$pokemonSearch .= decodePokemonLabel($pokemon) . " "; ?>
<?				$showdownExport .= encodePokemonShowdown($pokemon) . "\n"; ?>
<?			} ?>
					<td class="text-center team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?			$pokemonCount = 0; ?>
<?			foreach($player["lastTeam"] as $pokemon) { ?>
<?				$pokemonCount++; ?>
						<span class="tttooltip <? echo getSpriteClass($pokemon); ?>" title="<? echo decodePokemonLabel($pokemon); ?>"></span>
<?				if ( $pokemonCount == 3 ) { ?>
						<br class="phone-line-break" />
<?				} ?>
<?			} ?>
					</td>
<?		} else { ?>
					<td class="text-center" data-sort-value=""></td>
					<td class="text-center" data-sort-value=""></td>
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
			$("#events").footable({
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