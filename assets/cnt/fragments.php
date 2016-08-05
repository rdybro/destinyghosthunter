<?php
	
	$fragmentsTotalCount = 0;
	$fragmentsTotalCollected = 0;
	$fragmentsGrimoireTotal = 0;
	$fragmentsGrimoireCollected = 0;
	
	$fragmentsChecklist = array();
	
	if(!$db_connection) {
		die('Could not connect to database: ' . mysqli_connect_error());
	} else {
		$db_query = "SELECT cardId,points FROM grimoireCards WHERE def_type='Calcified Fragment'";
		$db_array = mysqli_query($db_connection, $db_query);
		
		$fragmentsTotalCount = mysqli_num_rows($db_array);
		
		while($row = mysqli_fetch_assoc($db_array)) {
			$fragmentsGrimoireTotal = $fragmentsGrimoireTotal + $row[points];
		
			if(search_array($row[cardId], $grimoireStatus)) {
				$fragmentsTotalCollected = $fragmentsTotalCollected + 1;
				$fragmentsGrimoireCollected = $fragmentsGrimoireCollected + $row[points];
			}
		}
	}

?>

<div class="row">
	<div class="col-md-12">
		<h2>Calcified Fragments on Dreadnaught</h2>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="list-group">
			<a href="/<?php echo $curPlatform; ?>/<?php echo $curDisplayNameUrl; ?>/fragments/grimoire/" class="list-group-item<?php if($curLocation == "grimoire") { echo " active"; } ?>">Grimoire<span class='badge pull-right'><?php echo $fragmentsTotalCollected . " / " . $fragmentsTotalCount; ?></span></a>
			
	<?php
			
				$characters = getCharacters($curMembershipId, $curPlatform);
				$characterAdvisorsVisibilityEnabled = true;
				
				foreach($characters[data][characters] as $character) {
					$curCharacterId = $character[characterBase][characterId];
				
					$characterClass = getCharacterClass($character[characterBase][classType]);
					$characterRace = getCharacterRace($character[characterBase][raceHash]);
					$characterGender = getCharacterGender($character[characterBase][genderType]);
					
					$characterLevel = $character[characterLevel];
					$characterLight = $character[characterBase][powerLevel];
					
					$fragmentsChecklist[$curCharacterId][checklist] = getCharacterAdvisors($curMembershipId,$curPlatform,$curCharacterId)[data][checklists][0][entries];
					$fragmentsChecklist[$curCharacterId][fragmentsCount] = 0;
					
					if($fragmentsChecklist[$curCharacterId][checklist]) {
						if(!$db_connection) {
							die('Could not connect to database: ' . mysqli_connect_error());
						} else {
							$db_query = "SELECT cardId,points FROM grimoireCards WHERE def_type='Calcified Fragment'";
							$db_array = mysqli_query($db_connection, $db_query);
							
							while($row = mysqli_fetch_assoc($db_array)) {
								foreach($fragmentsChecklist[$curCharacterId][checklist] as $fragment) {
									if($row[cardId] == $fragment[entityId] AND $fragment[state] == "1") {
										$fragmentsChecklist[$curCharacterId][fragmentsCount] = $fragmentsChecklist[$curCharacterId][fragmentsCount] + 1;
									}
								}
							}
							echo "<a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/fragments/" . $curCharacterId . "/' class='list-group-item" . ( $curLocation == $curCharacterId ? " active" : "" ) . "'>" . $characterClass . " " . $characterLevel . " (Light " . $characterLight . ")<span class='badge pull-right'>" . $fragmentsChecklist[$curCharacterId][fragmentsCount] . " / " . $fragmentsTotalCount . "</span></a>";
						}
					} else {
						$characterAdvisorsVisibilityEnabled = false;
					}
				}
				
		echo "</div>";
		
		if(!$characterAdvisorsVisibilityEnabled) {
				
	?>
		
			<div class="panel panel-danger">
				<div class="panel-heading">Privacy Enabled</div>
				<div class="panel-body">
					It seems that your have disabled public visibility for your character advisors. To see your Calcified Fragments per character, please go to <a href="https://www.bungie.net/en/Profile/Settings/<?php echo $curPlatformValue . "/" . $curMembershipId; ?>#tab=privacy" target="_blank">Bungie.net</a> to enable visibility.<br>
					<small>Please note, that it can take several minutes for the changes to apply.</small>
				</div>
			</div>
		
	<?php
		
		}
		
	?>
		
		<h3>Statistics</h3>
		<div class="list-group">
			<div class="list-group-item">Grimoire Score<span class="badge pull-right"><?php echo $fragmentsGrimoireCollected . " / " . $fragmentsGrimoireTotal; ?></span></div>
		</div>
		<div class="well well-sm partner-link">
			<a href="http://planetdestiny.com/" target="_blank"><img src="/assets/img/planetdestiny.png"></a>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">Information</div>
			<div class="panel-body">
				The fragments are sorted in a way that I found meaningful when collecting them the first time. It is intentional, and not an error that the decimal number doesn't match the roman numeral.
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
		
		<?php
		
			if(!$db_connection) {
				die('Could not connect to database: ' . mysqli_connect_error());
			} else {
				$db_query = "SELECT cardId,cardName,points,def_order,def_video,def_images,def_area,def_mission,def_expansion,def_description,def_error,def_credits FROM grimoireCards WHERE def_type='Calcified Fragment' ORDER BY def_order ASC";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					
					if($curLocation == "grimoire") {
						if(search_array($row[cardId], $grimoireStatus)) {
							$fragmentCollected = true;
						} else {
							$fragmentCollected = false;
						}
					} else {
						$fragmentCollected = false;
						
						foreach($fragmentsChecklist[$curLocation][checklist] as $fragment) {
							if($row[cardId] == $fragment[entityId] AND $fragment[state] == "1") {
								$fragmentCollected = true;
							}
						}
					}
					
					if($fragmentCollected) {
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