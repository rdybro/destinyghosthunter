<?php

	error_reporting(0); //E_ALL
	include('functions.php'); # Includes the file where I define some functions
	include('mariadb.php'); # Includes the file where I define the database connection
	
	$getPath = preg_replace("'/'", "", $_SERVER['REQUEST_URI'], 1);
	$curPath = explode("/", $getPath);
	
	$curPlatform = $_COOKIE[dgh3_platform];
	$curDisplayName = $_COOKIE[dgh3_displayName];
	$curDisplayNameUrl = strtolower(str_replace(" ", "", htmlspecialchars($curDisplayName)));
	$curMembershipId = $_COOKIE[dgh3_membershipId];
	$titleUrl = "/" . $curPlatform . "/" . $curDisplayNameUrl . "/";
	
	if($curPlatform == "xbl") { $curPlatformValue = 1; $curPlatformTiger = "TigerXbox"; }
	elseif($curPlatform == "psn") { $curPlatformValue = 2; $curPlatformTiger = "TigerPSN"; }
	
	// http://matthewjamestaylor.com/blog/how-to-post-forms-to-clean-rewritten-urls
	// Collect the posted search query and redirect to the URL rewritten page
	if($_POST[displayName] != "") {
		header("Location: /user/" . strtolower(str_replace(" ", "", htmlspecialchars($_POST[displayName]))) . "/");
	} elseif(($curPath[0] != "user") AND ($curPath[0] != "select")) {
		$urlPlatform = strtolower($curPath[0]);
		$urlDisplayName = strtolower(str_replace(" ", "", htmlspecialchars($curPath[1])));
		
		if(($urlPlatform == "psn" OR $urlPlatform == "xbl") AND $urlDisplayName != "") {
			if($urlPlatform != $curPlatform OR $urlDisplayName != $curDisplayNameUrl) {
				header("Location: /user/" . $urlDisplayName . "/");
			}
		} elseif(($curPlatform != "" OR $curDisplayName != "") AND ($curPath[0] != "psn" AND $curPath[0] != "xbl")) {
			header("Location: /" . $curPlatform . "/" . $curDisplayNameUrl . "/");
		} else {
			$userNotSet = 1;
			
			if($_SERVER['REQUEST_URI'] != "/") {
				header("Location: /");
			}
		}
	}

	if(($curPath[0] == "user") AND ($curPath[1] != "")) {
		$curPage = strtolower($curPath[0]);
		$searchDisplayName = $curPath[1];
		$userSearch = getMembershipId($searchDisplayName);
		
		if (count($userSearch) == 1) {
			if($userSearch[0][membershipType] == 1) { $membershipType = "xbl"; }
			elseif($userSearch[0][membershipType] == 2) { $membershipType = "psn"; }
			
			setUserCookies($membershipType,$userSearch[0][displayName],$userSearch[0][membershipId]);
		}
	} elseif(($curPath[0] == "select") AND ($curPath[1] != "") AND ($curPath[2] != "") AND ($curPath[3] != "")) {
		$searchPlatform = $curPath[1];
		$searchDisplayName = $curPath[2];
		$searchId = $curPath[3];
		
		setUserCookies($searchPlatform,$searchDisplayName,$searchId);
	} elseif(($curPath[0] == "psn" OR $curPath[0] == "xbl") AND ($curPath[2] == "" OR $curPath[2] == "grimoire" OR $curPath[2] == "ghosts" OR $curPath[2] == "fragments" OR $curPath[2] == "chests" OR $curPath[2] == "status")) {
		if($curPath[2] == "") {
			$curPage = "ghosts"; //$curPage = "grimoire";
			$curLocation = "tower"; //$curLocation = "missing";
			$grimoireStatus = getGrimoireStatus($curMembershipId, $curPlatform);
		} elseif($curPath[2] == "chests") {
			$curPage = strtolower($curPath[2]);
			$curLocation = strtolower($curPath[3]);
		} elseif($curPath[2] == "status") {
			$curPage = "status_" . strtolower($curPath[3]);
		} else {
			$curPage = strtolower($curPath[2]);
			$curLocation = strtolower($curPath[3]);
			$grimoireStatus = getGrimoireStatus($curMembershipId, $curPlatform);
		}
	} elseif($userNotSet == 1) {
		$curPage = "nouser";
		$titleUrl = "/";
	} else {
		$curPage = "404";
	}
	
	$ghostLocationArray = array(
		"Tower" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 0, goldenChestsCount => 0),
		"The-Reef" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 0, goldenChestsCount => 0),
		"Earth" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 1, goldenChestsCount => 0),
		"Moon" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 1, goldenChestsCount => 0),
		"Venus" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 1, goldenChestsCount => 0),
		"Mars" => array(ghostCount => 0, ghostCollected => 0, grimoireScore => 0, grimoireCollected => 0, goldenChests => 1, goldenChestsCount => 0)
	);
	
	if($curLocation == "tower") { $subtitle = "in the Tower"; }
	elseif($curLocation == "the-reef") { $subtitle = "in The Reef"; }
	elseif($curLocation == "earth") { $subtitle = "on Earth"; }
	elseif($curLocation == "moon") { $subtitle = "on the Moon"; }
	elseif($curLocation == "venus") { $subtitle = "on Venus"; }
	elseif($curLocation == "mars") { $subtitle = "on Mars"; }
	
	//$memcached = new Memcached();
	//$memcached->addServer("127.0.0.1",11211);

?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="Destiny Ghost Hunter">
		<meta name="author" content="Rasmus Dybro">
		
		<title>Destiny Ghost Hunter</title>
		
		<!-- Favicons -->
		<link rel="icon" href="/assets/favicon.ico">
		<link rel="apple-touch-icon" href="/assets/img/icons/icon-57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/assets/img/icons/icon-72.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/assets/img/icons/icon-114.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/assets/img/icons/icon-144.png" />
		
		<!-- Stylesheets -->
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:500,300"> <!-- Google Fonts (Roboto) -->
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/bootstrap.min.css'); ?>"> <!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/font-awesome.min.css'); ?>"> <!-- Font Awesome -->
		<link rel="stylesheet" type="text/css" href="/assets/css/font-destiny.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/font-destiny.css'); ?>"> <!-- Font Destiny -->
		<link rel="stylesheet" type="text/css" href="/assets/css/stylesheet.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/stylesheet.css'); ?>"> <!-- Custom styles for this template -->
		<link rel="stylesheet" type="text/css" href="/assets/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" /> <!-- fancyBox CSS -->
		<link rel="stylesheet" type="text/css" href="/assets/css/ie10-viewport-bug-workaround.css"> <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		
		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="/assets/js/ie-emulation-modes-warning.js"></script>
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	
		<?php include_once('analyticstracking.php'); ?>
		<?php include('header.php'); ?>
		
		<!-- Main Container -->
		<div id="page-container" class="container">
		
			<?php if($_COOKIE['dgh3_cookie_consent'] != '1') { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-dismissible alert-margin" role="alert">
							<button id="cookie-consent-close" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<strong>This site use cookies</strong> to enhance the user experience. Please leave the site if you won't accept, or close this dialog to hide it.
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php echo $usrSet; ?>
			<?php include('assets/cnt/' . $curPage . '.php'); ?>			
			<?php include('footer.php'); ?>
			<?php mysqli_close($db_connection); ?>
			
		</div><!-- /.container -->
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js" type="text/javascript"></script> <!-- fancyBox MouseWheel Plugin -->
		<script src="/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5" type="text/javascript"></script> <!-- fancyBox JS -->
		<script src="/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6" type="text/javascript"></script> <!-- fancyBox Media Helper -->
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function() {
				$("a.fancybox-guide-img").fancybox({loop: false, nextEffect: 'fade', prevEffect: 'fade'});
				$("a.fancybox-guide-vid").fancybox({helpers: {media: {}}});
				$("a.fancybox-guide-lore").fancybox({scrolling: 'auto', preload: false});
				$('div.panel-collapse').collapse({'toggle': false}); // This is for the View-toggles to work properly
			});
			
			jQuery(function() {
				$('#cookie-consent-close').click(function(e) {
					e.preventDefault();
					$.cookie('dgh3_cookie_consent', '1', { expires: 365, path: '/' });
				});
			})();
		</script>
		
	</body>
</html>