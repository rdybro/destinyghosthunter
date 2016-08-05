<!DOCTYPE html>
<html lang="en">
	<head>
	
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		
		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" type="text/css" href="/assets/css/lore.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/lore.css'); ?>">
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<!-- reCAPTCHA -->
		<script src='https://www.google.com/recaptcha/api.js'></script>
		
	</head>
	<body>
		
		<?php
		
			error_reporting(0);
			
			include('../functions.php');
			include('../mariadb.php');
			
			$getPath = preg_replace("'/'", "", $_SERVER['REQUEST_URI'], 1);
			$curPath = explode("/", $getPath);
			$getCardId = $curPath[1];
			
			if(!$db_connection) {
				die('Could not connect to database: ' . mysqli_connect_error());
			} else {
				$db_query = "SELECT cardId,cardName,def_status FROM grimoireCards WHERE cardId='" . $getCardId . "'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				if(mysqli_num_rows($db_array) == 1) {
					while($row = mysqli_fetch_assoc($db_array)) {
						
						echo "<h3>" . $row[cardName] . " (" . $row[cardId] . ")</h3>";
						
						if($row[def_status] == "complete") {
							echo "<div class='alert alert-success' role='alert'>This Grimoire card is marked <strong>complete</strong>. This means the data is considered fulfilling and accurate. Please report if you find any possible errors.</div>";
						} elseif($row[def_status] == "in_progress") {
							echo "<div class='alert alert-warning' role='alert'>This Grimoire card is marked <strong>in progress</strong>. This means the data is not yet finished, but we are working on it.</div>";
						} elseif($row[def_status] == "new") {
							echo "<div class='alert alert-info' role='alert'>This Grimoire card is marked <strong>new</strong>. This means that the Grimoire card is brand new, and that we haven't categorized it yet.</div>";
						} elseif($row[def_status] == "review") {
							echo "<div class='alert alert-info' role='alert'>This Grimoire card is marked <strong>for review</strong>. This means that the Grimoire card previously has been considered as complete, but now for some reason may isn't anymore. Data may be incorrect.</div>";
						} else { # Status == "no" or anything else
							echo "<div class='alert alert-danger' role='alert'>This Grimoire card is marked <strong>not complete</strong>. This means that the data for this card is not complete.</div>";
						}
						
						echo "<p>Reporting function is an experiment, and is currently implemented to see what kind of responses I'll get. It may be removed or changed without further notice. Please note that anything you submit here may be posted on the site, either in it's full entirety or in an edited form.</p>";

						$showForm = true;
						
						if($_POST["inputSubmit"] == "true") {
							if(getRecaptchaResponse($_POST["g-recaptcha-response"], $_POST["inputIp"])) {
								if($_POST["inputDescription"] == "" OR $_POST["inputYoutube"] == "" OR $_POST["inputName"] == "" OR $_POST["inputLink"] == "" OR $_POST["inputCreditsName"] == "" OR $_POST["inputCreditsLink"] == "") {
									echo "<div class='alert alert-danger' role='alert'><strong>There was a problem with your entry.</strong> Please fill in all the fields.</div>";
								} else {
									if(!$db_connection) {
										die('Could not connect to database: ' . mysqli_connect_error());
									} else {
										$secure_description = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputDescription"]));
										$secure_youtube = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputYoutube"]));
										$secure_name = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputName"]));
										$secure_link = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputLink"]));
										$secure_creditsname = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputCreditsName"]));
										$secure_creditslink = htmlspecialchars(mysqli_real_escape_string($db_connection,$_POST["inputCreditsLink"]));
										
										$db_query = "INSERT INTO grimoireReport (ip,cardid,report_description,report_youtube,report_name,report_link,credit_name,credit_link) VALUES ('" . $_POST["inputIp"] . "','" . $_POST[inputCardId] . "','" . $secure_description . "','" . $secure_youtube . "','" . $secure_name . "','" . $secure_link . "','" . $secure_creditsname . "','" . $secure_creditslink . "')";
										
										if(mysqli_query($db_connection, $db_query)) {
											$showForm = false;
											
											echo "<div class='alert alert-success' role='alert'>";
											echo "<h4><span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span> Success!</h4>";
											echo "Your entry is received. Thank you very much for your contribution.";
											echo "</div>";
										} else {
											echo "ERROR wrting to database.";
										}
									
										mysqli_close($db_connection);
									}
								}
							} else {
								echo "<div class='alert alert-danger' role='alert'><strong>Your reCAPTCHA was not accepted.</strong> Please make sure you checked the checkbox and that you got a green checkmark.</div>";
							}
						}
						
						if($showForm) {
							echo "<p><strong>All fields are mandatory!</strong></p>";
						
		?>
		
							<form method="post">
								<div class="form-group">
									<label>Description</label>
									<textarea class="form-control custom-control" rows="3" style="resize:none" name="inputDescription" placeholder="Description"></textarea>
								</div>
								<div class="form-group">
									<label>YouTube</label>
									<div class="input-group">
										<span class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></span>
										<input type="text" class="form-control" name="inputYoutube" placeholder="YouTube link">
									</div>
								</div>
								<div class="form-group">
									<label>Your nickname</label>
									<div class="form-inline">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
											<input type="text" class="form-control" name="inputName" placeholder="Name">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></span>
											<input type="text" class="form-control" name="inputLink" placeholder="Link">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Credits</label>
									<div class="form-inline">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
											<input type="text" class="form-control" name="inputCreditsName" placeholder="Name">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></span>
											<input type="text" class="form-control" name="inputCreditsLink" placeholder="Link">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class='g-recaptcha' data-sitekey='6LdXMCUTAAAAALMZg6kBkin8MW4kok1_lVA67Tv9'></div>
								</div>
								<input type="hidden" name="inputIp" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
								<input type="hidden" name="inputCardId" value="<?php echo $row[cardId]; ?>">
								<input type="hidden" name="inputSubmit" value="true">
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
		
		<?php
		
						}						
					}
				} else {
					echo "<p><strong>Sorry!</strong> I couldn't find this Grimoire card in the database.</p>";
				}
			}
		
		?>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		
	</body>
	
</html>