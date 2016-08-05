<?php
	
	$ghostTotalCount = 0;
	$ghostTotalCollected = 0;
	$ghostGrimoireTotal = 0;
	$ghostGrimoireCollected = 0;
	
?>

<div class="row">
	<div class="col-md-12">
		<h2>Dead Ghosts <?php echo $subtitle; ?></h2>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="list-group">
	
		<?php
		
			if(!$db_connection) {
				die('Could not connect to database: ' . mysqli_connect_error());
			} else {
				foreach($ghostLocationArray as $location => $array) {
					$db_query = "SELECT cardId,points FROM grimoireCards WHERE (def_type='Dead Ghost' OR def_type='Mystery') AND def_location='" . $location . "'";
					$db_array = mysqli_query($db_connection, $db_query);
					
					$ghostLocationArray[$location][ghostCount] = mysqli_num_rows($db_array);
					
					while($row = mysqli_fetch_assoc($db_array)) {
						$ghostLocationArray[$location][grimoireScore] = $ghostLocationArray[$location][grimoireScore] + $row[points];
						
						if(search_array($row[cardId], $grimoireStatus)) {
							$ghostLocationArray[$location][ghostCollected] = $ghostLocationArray[$location][ghostCollected] + 1;
							$ghostLocationArray[$location][grimoireCollected] = $ghostLocationArray[$location][grimoireCollected] + $row[points];
						}
					}
					
					echo "<a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/ghosts/" . strtolower($location) . "/' class='list-group-item" . (strtolower($location) == $curLocation ? " active" : "") . "'>" . str_replace("-"," ",$location) . "<span class='badge pull-right'>" . $ghostLocationArray[$location][ghostCollected] . " / " . $ghostLocationArray[$location][ghostCount] . "</span></a>";
					
					if($location == "The-Reef") {
						echo "</div><div class='list-group'>";
					}
					
					$ghostTotalCount = $ghostTotalCount + $ghostLocationArray[$location][ghostCount];
					$ghostTotalCollected = $ghostTotalCollected + $ghostLocationArray[$location][ghostCollected];
					$ghostGrimoireTotal = $ghostGrimoireTotal + $ghostLocationArray[$location][grimoireScore];
					$ghostGrimoireCollected = $ghostGrimoireCollected + $ghostLocationArray[$location][grimoireCollected];
				}
			}
		
		?>
		
		</div>
	
		<h3>Statistics</h3>
		<div class="list-group">
			<div class="list-group-item">Ghosts Collected<span class="badge pull-right"><?php echo $ghostTotalCollected . " / " . $ghostTotalCount; ?></span></div>
			<div class="list-group-item">Grimoire Score<span class="badge pull-right"><?php echo $ghostGrimoireCollected . " / " . $ghostGrimoireTotal; ?></span></div>
		</div>
		<div class="well well-sm partner-link">
			<a href="http://planetdestiny.com/" target="_blank"><img src="/assets/img/planetdestiny.png"></a>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
	
		<?php
		
			if(!$db_connection) {
				die('Could not connect to database: ' . mysqli_connect_error());
			} else {
				$db_query = "SELECT cardId,cardName,points,def_order,def_video,def_images,def_area,def_mission,def_expansion,def_description,def_error,def_credits FROM grimoireCards WHERE (def_type='Dead Ghost' OR def_type='Mystery') AND def_location='" . $curLocation . "' ORDER BY def_order ASC";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					
					if(search_array($row[cardId], $grimoireStatus)) {
						$varAcquired_panel = "panel-green";
						$varAcquired_panel_heading = "panel-heading-green";
						$varAcquired_glyphicon = "glyphicon-ok";
						$varAcquired_panel_collapse = "panel-collapse-acquired";
						
						$varAcquired_collapse = "collapsed";
						$varAcquired_collapse_in = "";
					} else {
						$varAcquired_panel = "panel-red";
						$varAcquired_panel_heading = "panel-heading-red";
						$varAcquired_glyphicon = "glyphicon-remove";
						$varAcquired_panel_collapse = "panel-collapse-missing";
						
						$varAcquired_collapse = "";
						$varAcquired_collapse_in = "in";
					}
					
		?>
					
					<div class="col-md-12">
						<div class="panel panel-primary <?php echo $varAcquired_panel; ?>">
							<div class="panel-heading panel-heading-clickable <?php echo $varAcquired_panel_heading; ?>">
								<h4 class="panel-title">
									<a class="accordion-toggle collapse-trigger panel-heading-link <?php echo $varAcquired_collapse; ?>" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $row[cardId]; ?>">
										<span class="glyphicon <?php echo $varAcquired_glyphicon; ?>" aria-hidden="true"></span>
										<?php echo $row[def_order] . ". " . $row[cardName]; ?>
										<i class="pull-right glyphicon glyphicon-chevron-down show-when-open"></i>
										<i class="pull-right glyphicon glyphicon-chevron-left show-when-collapsed"></i>
									</a>
								</h4>
							</div>
							<div id="<?php echo $row[cardId]; ?>" class="<?php echo $varAcquired_panel_collapse; ?> panel-collapse collapse <?php echo $varAcquired_collapse_in; ?>">
								<div class="panel-body panel-body-top">
									<a class="btn btn-default pull-right fancybox-guide-lore" href="/lore/<?php echo $row[cardId]; ?>" role="button" data-fancybox-type="iframe">Lore</a>
									<a class="btn btn-info pull-right fancybox-guide-lore btn-guide-vid" href="/report/<?php echo $row[cardId]; ?>" role="button" data-fancybox-type="iframe">Report</a>
									<?php if($row[def_video] != "") { ?>
										<a class="btn btn-danger pull-right fancybox-guide-vid btn-guide-vid" href="http://www.youtube.com/embed/<?php echo $row[def_video]; ?>?rel=0&autoplay=1" role="button">YouTube</a>
									<?php } ?>
									<?php echo "Area: " . $row[def_area] . " // Grimoire Points: " . $row[points] . " // Required Expansion: " . $row[def_expansion] . "<br>Mission Availability: " . $row[def_mission]; ?>
								</div>
								<div class="panel-body no-bottom-padding">
									<?php if($row[def_error] != "") { ?>
										<div class="alert alert-danger" role="alert"><?php echo $row[def_error]; ?></div>
									<?php } ?>
									<?php echo $row[def_description]; ?>
								</div>
								<div class="panel-body panel-body-gallery">
									<?php for ($i = 1; $i <= $row[def_images]; $i++) { ?>
									
										<div class="col-md-4 col-sm-4">
											<a class="fancybox-guide-img" rel="<?php echo $row[cardId]; ?>" href="/guide/<?php echo $row[cardId] . "/" . $i; ?>.jpg">
												<img style="min-width:100%; max-width:100%;" src="/guide/<?php echo $row[cardId] . "/" . $i; ?>_thumb.jpg">
											</a>
										</div>
										
									<?php } ?>
								</div>
								<?php if($row[def_credits] != "") { ?>
									<div class="panel-body no-top-padding small">
										<?php echo $row[def_credits]; ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
		
		<?php
					
				}
			}
			
		
		?>

		</div>
	</div>
</div>