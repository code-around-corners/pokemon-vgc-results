<?php
	makeSearchBarHelp();
?>
	<br />
	<div id="login" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Data Entry Login</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>
						Use this form to log into the results database. This will allow you to add or amend
						event information.
					</p>
					<div class="w-100 form-group">
						<div class="row pb-1">
							<div class="col-4">User Name:</div>
							<div class="col-8"><input type="text" class="form-control" id="username" /></div>
						</div>
						<div class="row pb-1">
							<div class="col-4">Password:</div>
							<div class="col-8"><input type="password" class="form-control" id="password" /></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="validate">Login</button>
				</div>
			</div>
		</div>
	</div>
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
	<script type="text/javascript" src="vendor/select2/js/select2.full.min.js"></script>
	<script type="text/javascript" src="vendor/js-cookie/js/js.cookie.js"></script>
	
	<script type="text/javascript">
		$("#validate").click(function() {
			validateLogin();
		});
		
		$("#password").change(function() {
			validateLogin();
		});
		
		function validateLogin() {
			if ( $("#username").val() == "" ) {
				alert("Invalid username!");
				return;
			}
			
			$.ajax({
				type: "GET",
				url: "api/v1/users/" + $("#username").val(),
				contentType: 'application/json'
			}).done(function(data) {
				$.ajax({
					type: "POST",
					url: "api/v1/users/" + data.userId + "/sessions",
					contentType: 'application/json',
					data: {
						password: $("#password").val()
					}
				}).done(function(data) {
					Cookies.set("session", data.sessionKey, { expires: 30 });
					Cookies.set("user", data.userId, { expires: 30 });

					location.reload();
				}).fail(function(data) {
					alert(data.responseJSON.error);
				});
			}).fail(function(data) {
				console.log(data);
				alert(data.responseJSON.error);
			});
		}

		$("#user-login").click(function() {
			$("#login").modal("show");
		});

		$("#user-logout").click(function() {
			if ( confirm("Are you sure you want to log out?") ) {
				Cookies.remove("session");
				location.reload();
			}
		});
	</script>
