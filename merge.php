<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/navigation.php");

	if ( ! requireApiKey() ) {
		showApiKeyError();
	} else {
?>
	<div class="grey-header container">
		<h4 class="event-name">
			<b>Player Merge Utility</b>
		</h4>
	</div>

    <div class="container">
	    <p>
		    The merge player screen allows you to combine two player records together. This is useful in the event that
		    a player has ended up with two separate profiles due to a slight spelling difference, a shortened version of
		    their name, etc.
	    </p>
	    
	    <p>
		    The first player is the player you will merge from. This means that all their history will be transferred to
		    the second player. Their social media accounts will not be kept.
	    </p>
	    
	    <p>
		    The second player is the player you will merge into. This player doesn't actually change other than receiving
		    a new ID number. Make sure the player with the correct spelling, Twitter handle, etc., is the one you select
		    for the second player.
	    </p>
		    
		<hr />
		
	    <div class="row">
		    <div class="col-12 col-sm-6">
			    <b>Player To Merge From:</b>
				<select class="w-100 player-select-box" id="player1" onchange="javascript:getPlayerData(1);">
				</select>
		    </div>
		    <div class="col-12 col-sm-6">
			    <b>Player To Merge To:</b>
				<select class="w-100 player-select-box" id="player2" onchange="javascript:getPlayerData(2);">
				</select>
		    </div>
	    </div>
	    
	    <hr />

	    <div class="row">
		    <div class="col-12 col-sm-6">
			    <div class="row">
				    <div class="col-12 col-sm-3">Player Name:</div>
				    <div class="col-12 col-sm-9" id="player1name"></div>
				    <div class="col-12 col-sm-3">Country:</div>
				    <div class="col-12 col-sm-9" id="player1country"></div>
				    <div class="col-12 col-sm-3">Last Event:</div>
				    <div class="col-12 col-sm-9" id="player1lastEvent"></div>
				    <div class="col-12 col-sm-3">Twitter:</div>
				    <div class="col-12 col-sm-9" id="player1twitter"></div>
			    </div>
		    </div>
		    <div class="col-12 col-sm-6">
			    <div class="row">
				    <div class="col-12 col-sm-3">Player Name:</div>
				    <div class="col-12 col-sm-9" id="player2name"></div>
				    <div class="col-12 col-sm-3">Country:</div>
				    <div class="col-12 col-sm-9" id="player2country"></div>
				    <div class="col-12 col-sm-3">Last Event:</div>
				    <div class="col-12 col-sm-9" id="player2lastEvent"></div>
				    <div class="col-12 col-sm-3">Twitter:</div>
				    <div class="col-12 col-sm-9" id="player2twitter"></div>
			    </div>
		    </div>
	    </div>
	    
	    <hr />
	    
	    <div class="text-center">
		    <button type="button" onclick="javascript:mergePlayers();">Merge Player Records</button>
	    </div>
    </div>
<?	} ?>

<?	include_once("resources/php/footer.php"); ?>

	<script lang="text/javascript">
		$(document).ready(function() {
	        $(".player-select-box").select2({
				ajax: {
					url: 'api/v1/players?format=dropdown',
					dataType: 'json'
				},
				width: "100%",
				placeholder: 'Search for a player'
			});
		});
		
		function getPlayerData(playerId) {
			selectedPlayer = $("#player" + playerId).val();
			$.get(
				"api/v1/players/" + selectedPlayer
			).done(function(data) {
				$("#player" + playerId + "name").text(data["name"]);
				$("#player" + playerId + "country").text(data["flagEmoji"] + " " + data["country"]);
				
				if ( data["lastEvent"] == undefined ) {
					$("#player" + playerId + "lastEvent").text("");
				} else {
					$("#player" + playerId + "lastEvent").text(data["lastEvent"]["date"]);
				}
				
				if ( data["social"]["twitter"] == undefined ) {
					$("#player" + playerId + "twitter").text("");
				} else {
					twitter = data["social"]["twitter"];
					$("#player" + playerId + "twitter").html("<a href='https://www.twitter.com/" + twitter + "'>" + twitter + "</a>");
				}
			});
		}
		
		function mergePlayers() {
			player1 = $("#player1").val();
			player2 = $("#player2").val();

			player1name = $("#player1name").text();
			player2name = $("#player2name").text();

			if ( player1 == null || player2 == null ) {
				alert("You must select two players to merge!");
				return;
			}
			
			if ( player1 == player2 ) {
				alert("You cannot merge the same player record!");
				return;
			}
			
			if ( ! confirm("Are you sure you want to merge '" + player1name + "' and '" + player2name + "'?") ) {
				return;
			}
			
			$.ajax({
				url: "api/v1/players/" + player1,
				type: "PUT",
				data: {
					mergeId: player2,
					key: $("#currentApiKey").attr("data-api-key")
				}
			}).done(function(data) {
				alert("Player records have been merged using the ID " + data["id"]);
				location.reload();
			});
		}
	</script>
</body>
</html>