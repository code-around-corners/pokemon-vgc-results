<?	session_start(); ?>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<a class="navbar-brand" href="index.php"><img src="resources/images/banner.png" class="small-icon" /></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item<? echo ((basename($_SERVER['REQUEST_URI']) == "events.php") ? " active" : ""); ?>">
					<a class="nav-link" href="events.php">Past Events Results</a>
				</li>
				<li class="nav-item<? echo ((basename($_SERVER['REQUEST_URI']) == "players.php") ? " active" : ""); ?>">
					<a class="nav-link" href="players.php">Player List</a>
				</li>
<?	if ( isset($_SESSION['apiUser']) && $_SESSION['apiUser'] != "" ) { ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown"
						href="#" role="button" aria-haspopup="true" aria-expanded="false">
						Data Management
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="upload.php">Upload Results</a>
						<a class="dropdown-item" href="merge.php">Merge Player Records</a>
					</div>
<?	} ?>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown"
						href="#" role="button" aria-haspopup="true" aria-expanded="false">
						Pokémon Links
					</a>
					<div class="dropdown-menu">
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/earn-championship-points-vg/">VGC Championship Points</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/pokemon-world-championships/">World Championships</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/international-championships/">International Championships</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/regional-championships/">Regional Championships</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/special-events/">Special Championships</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/midseason-showdown/">Midseason Showdowns</a>
					    <a class="dropdown-item" target="_new" href="http://www.pokemon.com/us/play-pokemon/pokemon-events/pokemon-tournaments/vg-premier-challenge/">Premier Challenges</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://www.pokecal.com/" target="_new">Find Local Events</a>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="https://www.trainertower.com">Return to Trainer Tower</a>
				</li>
			</ul>
		</div>
	</nav>
