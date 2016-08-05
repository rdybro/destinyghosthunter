<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="/assets/css/lore.css?v=<?php echo filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/lore.css'); ?>">
	</head>
	
	<body>
	
		<?php
		
			# http://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/Definition/
			# cardIntro, cardDescription
			error_reporting(0);
			
			include('../functions.php');
			include('../mariadb.php');
			
			$getPath = preg_replace("'/'", "", $_SERVER['REQUEST_URI'], 1);
			$curPath = explode("/", $getPath);
			$getCardId = $curPath[1];
			
			$isGrimoireFound = 0;
			
			if(!$db_connection) {
				die('Could not connect to database: ' . mysqli_connect_error());
			} else {
				$db_query = "SELECT cardId,cardName,themeName,pageName,cardIntro,cardIntroAttribution,cardDescription,themeSheetPath,themeSheetX,themeSheetY,themeSheetHeight,themeSheetWidth,pageSheetPath,pageSheetX,pageSheetY,pageSheetHeight,pageSheetWidth,cardSheetPath,cardSheetX,cardSheetY,cardSheetHeight,cardSheetWidth FROM grimoireCards WHERE cardId='" . $getCardId . "'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				if(mysqli_num_rows($db_array) == 1) {
					while($row = mysqli_fetch_assoc($db_array)) {
						echo "<center>";
						echo "<canvas class='sprite' data-src='http://www.bungie.net" . $row[themeSheetPath] . "' data-x='" . $row[themeSheetX] . "' data-y='" . $row[themeSheetY] . "' height='" . $row[themeSheetHeight] . "' width='" . $row[themeSheetWidth] . "'></canvas>";
						echo "<canvas class='sprite' data-src='http://www.bungie.net" . $row[pageSheetPath] . "' data-x='" . $row[pageSheetX] . "' data-y='" . $row[pageSheetY] . "' height='" . $row[pageSheetHeight] . "' width='" . $row[pageSheetWidth] . "'></canvas>";
						echo "<canvas class='sprite' data-src='http://www.bungie.net" . $row[cardSheetPath] . "' data-x='" . $row[cardSheetX] . "' data-y='" . $row[cardSheetY] . "' height='" . $row[cardSheetHeight] . "' width='" . $row[cardSheetWidth] . "'></canvas>";
						echo "</center>";
						echo "<p><b>" . $row[themeName] . " // " . $row[pageName] . " // " . $row[cardName] . " (" . $row[cardId] . ")</b></p>";
						echo "<p>" . $row[cardIntro];
						
						if($row[cardIntroAttribution] != "") { echo " " . $row[cardIntroAttribution]; }
						
						echo "</p>";
						
						echo "<p>" . $row[cardDescription] . "</p>";
					}
				} else {
					echo "<p><strong>Sorry!</strong> I couldn't find any lore for this Grimoire card.</p>";
				}
			}
		
		?>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>
			//Canvas Sprite-script from destinystatus.com
			$("canvas.sprite").each(function(a,b){
				var c=$(b),
				d=c.attr("width"),
				e=c.attr("height"),
				f=c.data("x"),
				g=c.data("y"),
				h=c.data("src"),
				i=c[0].getContext("2d"),
				
				j=new Image;
				j.onload=function(){i.drawImage(j,f,g,d,e,0,0,d,e)},
				j.src=h
			})
		</script>
	
	</body>
	
</html>