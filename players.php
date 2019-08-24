<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/navigation.php");
	include_once("resources/php/functions.php");

	$countryList = json_decode(file_get_contents(getBaseUrl() . "api/v1/countries"), true);
	$playerList = json_decode(@file_get_contents(getBaseUrl() . "api/v1/players?format=table"), true);
	$periodData = getSeasonDropdownData();
?>
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

<?	if ( $playerList !== null ) { ?>
<?		foreach($playerList as $playerId => $player) { ?>
				<tr>
					<td></td>
                	<td class="text-center hide-detail-row" data-filter-value="<? echo $player["country"]; ?>">
                		<img src="<? echo $countryList[$player["countryCode"]]["flagUrl"]; ?>" title="<? echo $player["country"]; ?>" class="icon tttooltip" />
                	</td>
                	<td class="text-center" data-sort-value="<? echo $player["name"]; ?>">
	                	<span class="d-sm-inline d-md-none"><? echo $countryList[$player["countryCode"]]["flagEmoji"] . " "; ?></span>
	                	<a href="player.php?id=<? echo $playerId; ?>"><? echo $player["name"]; ?></a>
	                </td>
<?			if ( isset($player["lastEvent"]) ) { ?>	                
                	<td class="text-center" data-sort-value="<? echo $player["lastEvent"]["date"]; ?>">
	                	<a href="standings.php?id=<? echo $player["lastEvent"]["id"]; ?>">
		                	<? echo date("F jS Y", strtotime($player["lastEvent"]["date"])); ?>
	                	</a>
	                </td>
<?				$pokemonSearch = ""; ?>
<?				foreach($player["lastEvent"]["team"] as $pokemon) { ?>
<?					$pokemonSearch .= $pokemon["name"] . " "; ?>
<?				} ?>
					<td class="text-center team-column" data-filter-value="<? echo $pokemonSearch; ?>">
<?				$pokemonCount = 0; ?>
<?				foreach($player["lastEvent"]["team"] as $pokemon) { ?>
<?					$pokemonCount++; ?>
						<span class="tttooltip <? echo $pokemon["class"]; ?>" title="<? echo $pokemon["name"]; ?>"></span>
<?					if ( $pokemonCount == 3 ) { ?>
						<br class="phone-line-break" />
<?					} ?>
<?				} ?>
					</td>
<?			} else { ?>
					<td></td>
					<td></td>
<?			} ?>
                	<td class="text-center">
<?			if ( isset($player["social"]["facebook"]) ) { ?>	            
			            <a href="http://www.facebook.com/<? echo $player["social"]["facebook"]; ?>" target="_blank">
				            <img src="resources/images/social/facebook.png" alt="facebook" class="small-icon" />
				        </a>
<?			} ?>
<?			if ( isset($player["social"]["twitter"]) ) { ?>	            
			            <a href="http://www.twitter.com/<? echo $player["social"]["twitter"]; ?>" target="_blank">
				            <img src="resources/images/social/twitter.png" alt="twitter" class="small-icon" />
				        </a>
<?			} ?>
<?			if ( isset($player["social"]["youtube"]) ) { ?>	            
			            <a href="http://www.youtube.com/user/<? echo $player["social"]["youtube"]; ?>" target="_blank">
				            <img src="resources/images/social/youtube.png" alt="youtube" class="small-icon" />
				        </a>
<?			} ?>
<?			if ( isset($player["social"]["twitch"]) ) { ?>	            
			            <a href="http://www.twitch.com/u/<? echo $player["social"]["twitch"]; ?>" target="_blank">
				            <img src="resources/images/social/twitch.png" alt="twitch" class="small-icon" />
				        </a>
<?			} ?>
                	</td>
				</tr>
<?		} ?>
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