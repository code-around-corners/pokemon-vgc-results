<!doctype html>
<html lang="en">

<head>
<?	include_once("resources/php/header.php"); ?>
</head>

<body>
<?php
	include_once("resources/php/functions.php");
	include_once("resources/php/config.php");
	include_once("resources/php/navigation.php");
	
	$filters = array(
	    "premierGroup"  => array("Regional Championship", "Special Championship", "International Championship"),
	    "startDate"     => date("Y-m-d"),
	    "product"		=> array("Video Game")
	);
	
	$defaultSocketTimeout = ini_get('default_socket_timeout');
	ini_set('default_socket_timeout', 5);
	$filtersEncoded = base64_encode(json_encode($filters));
	$baseTournamentData = @file_get_contents("https://www.pokecal.com/api.php?command=listEvents&filters=" . $filtersEncoded);
	ini_set('default_socket_timeout', $defaultSocketTimeout);

	if ( $baseTournamentData != "" ) {
		$tournaments = json_decode($baseTournamentData, true); 
	} else {
		$tournaments = array(
			"data"	=> array()
		);
	}
?>

<div class="container">
	<hr />
	<h4 class="text-center">Trainer Tower Global Team Search</h4>
	<hr />

	<p>
		Welcome to the Trainer Tower results site! This is your one stop shop for VGC tournament results from around
		the world. Check what teams are doing well and prepare for your next event! Use the box below to search for teams
		using specific Pokémon (and where available, movesets, items or abilities). Enter your search terms and hit the 
		search button!
	</p>
	
	<form action="teams.php" method="post" accept-charset="utf-8">
	    <div>
			<div class="input-group input-group-sm">
				<input type="text" class="form-control" aria-label="Search" placeholder="Search for teams with specific Pokémon..." id="search-filter" name="search-filter" />
				<div class="input-group-append">
					<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
				</div>
	  		</div>
	    </div>
	</form>

	<hr />
	<h4 class="text-center">Upcoming Regional & Special Championships</h4>
	<hr />
	
	<p>
		You can also check below for upcoming Regional & Special events - this data comes directly from the Pokemon
		website, so whilst some events may have been announced, they won't appear below until they've been formally sanctioned.
	</p>
	
	<div class="row w-100">
		<div class="col-12">
			<div class="card-columns small my-3">
				<div class="card">
					<a class="twitter-timeline" data-tweet-limit=1 href="https://twitter.com/TrainerTower?ref_src=twsrc%5Etfw&"></a>
				</div>
<?	foreach($tournaments["data"] as $tournament) { ?>
<?		$address = ""; ?>
<?		foreach ( array("venueName", "addressLine1", "addressLine2", "city", "provinceState", "countryName", "postalZipCode") as $field ) { ?>
<?			if ( isset($tournament[$field]) && $tournament[$field] != "" ) { ?>
<?				$address .= $tournament[$field] . ", "; ?>
<?			} ?>
<?		} ?>
<?		$address = preg_replace("/, $/", "", $address); ?>

				<div class="card">
					<div class="card-header">
						<i class="fas fa-gamepad fa-1x"></i> <? echo $tournament["premierEvent"]; ?><br />
						<? echo $tournament["tournamentName"]; ?>
					</div>
					<div class="card-body">
						<h4 class="card-title">
							<img src="resources/images/flags/<? echo strtolower($tournament["isoCountryCode"]); ?>.png" title="<? echo $tournament["country"]; ?>" class="small-icon tttooltip"/>&nbsp;
							<? echo $tournament["venueName"]; ?>
						</h4>
						<h6 class="card-subtitle mb-2 text-muted"><? echo date("F jS Y", $tournament["date"]); ?></h6>
						<p class="card-text">
							<? echo preg_replace("/<p><\/p>/", "", "<p>" . implode("</p><p>", $tournament["details"]) . "</p>"); ?>
						</p>
						<i class="fas fa-globe fa-1x"></i> <a target="_blank" class="" href="https://www.pokemon.com/us/play-pokemon/pokemon-events/<? echo preg_replace("/(..)(..)(......)/", "$1-$2-$3", $tournament["tournamentID"]); ?>/">View on Pokemon.com</a>
<?		if ( $tournament["website"] != "" ) { ?>
						<br>
						<i class="fas fa-external-link-alt fa-1x"></i> <a target="_blank" class="" href="<? echo $tournament["website"]; ?>">Event Website</a>
<?		} ?>
					</div>
					<div class="card-footer">
						<? echo $address; ?>
					</div>
				</div>
<?	} ?>
			</div>
		</div>
	</div>
</div>

<?	include_once("resources/php/footer.php"); ?>
	<script async src="vendor/twitter/js/widgets.js" charset="utf-8"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
            $('.tttooltip').tooltipster();
        });
    </script>
</body>
</html>