
	<br />
    <footer>
        <nav class="navbar navbar-default fixed-bottom bg-dark text-light text-center" role="navigation">
	        <div class="row w-100 ml-0">
		        <div class="col-10 col-md-4 text-left">
			        <a href="https://www.trainertower.com/"><img class="small-icon" src="resources/images/social/website.png" /></a>&nbsp;
			        <a href="https://www.facebook.com/TrainerTowerVGC/"><img class="small-icon" src="resources/images/social/facebook.png" /></a>&nbsp;
			        <a href="https://www.twitter.com/trainertower"><img class="small-icon" src="resources/images/social/twitter.png" /></a>&nbsp;
			        <strong>Trainer Tower</strong>
		        </div>
		        <div class="col-md-4 d-none d-md-block text-center">
<?	if ( session_status() == PHP_SESSION_ACTIVE ) { ?>
					<span id="currentApiKey" class="text-muted">
						<? echo ((isset($_SESSION['apiKey']) && $_SESSION['apiKey'] != "") ? $_SESSION['apiKey'] : "Set API Key"); ?>
					</span>
<?	} ?>
		        </div>
		        <div class="col-2 col-md-4 text-md-right">
					<a class="text-light" href="https://www.codearoundcorners.com">
						<img class="tttooltip small-icon" src="resources/images/cdac.png" title="Site by Code Around Corners" />
					</a>
		        </div>
	        </div>
        </nav>
    </footer>
    
    <script type="text/javascript" src="vendor/jquery/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="vendor/popper/js/popper.min.js"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="vendor/pokesprite/js/pokesprite.min.js"></script>
	<script type="text/javascript" src="vendor/moment/js/moment.js"></script>
    <script type="text/javascript" src="vendor/footable/js/footable.min.js"></script>
	<script type="text/javascript" src="vendor/tooltipster/js/tooltipster.bundle.min.js"></script>
	<script type="text/javascript" src="vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
	
	<script type="text/javascript">
		$("#currentApiKey").click(function() {
			apiKey = prompt("Please enter your API key:");
			
			$.get("api.php", {
				command: "setSessionKey",
				apiKey: apiKey
			}).done(function() {
				$("#currentApiKey").text(apiKey);
			});
		});
	</script>
