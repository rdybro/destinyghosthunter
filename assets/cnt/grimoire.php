<div class="row">
	<div class="col-md-12">
		<h2>All Grimoire Cards</h2>
	</div>
</div>

<div class="alert alert-warning" role="alert">
	<strong>This section is still in beta!</strong> It may display some incorrect values, and all planned features is not yet implemented.
</div>

<div class="row">
	<div class="col-md-3">
		<div class="list-group">
	
		<?php
		
			$grimoireDbType = "";
		
			foreach($grimoireCategoryArray as $grimoireCategory) {
				
				
				echo "<a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/grimoire/" . strtolower($grimoireCategory) . "/' class='list-group-item" . (strtolower($grimoireCategory) == $curLocation ? " active" : "") . "'>" . str_replace("-"," ",$grimoireCategory) . "<span class='badge pull-right'></span></a>";
				
				if($grimoireCategory == "Others" OR $grimoireCategory == "Fallen-Hunted" OR $grimoireCategory == "The-Taken-King") {
					echo "</div><div class='list-group'>";
				}
				
				if(strtolower($grimoireCategory) == $curLocation) {
					$grimoireDbType = str_replace("-"," ",$grimoireCategory);
				}
				
			}
			
		?>
		
		</div>
		
		<h3>Statistics</h3>
		<div class="list-group">
			<div class="list-group-item">Grimoire Score<span class="badge pull-right"><?php echo $grimoireStatus[data][score]; ?></span></div>
		</div>
		<div class="well well-sm partner-link">
			<a href="http://planetdestiny.com/" target="_blank"><img src="/assets/img/planetdestiny.png"></a>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">Information</div>
			<div class="panel-body">
				This site displays information about all Grimoire Cards in the game, except for those accounted for elsewhere on this site, this includes Dead Ghosts and Calcified Fragments. To see information about these Grimoire cards, please use the navigation.
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
		
			<?php
			
				if(!$db_connection) {
					die('Could not connect to database: ' . mysqli_connect_error());
				} else {
					
					$db_query = "SELECT cardId,cardName,points,themeName,pageName,stat1Name,stat1Rank1Threshold,stat1Rank1Points,stat1Rank2Threshold,stat1Rank2Points,stat1Rank3Threshold,stat1Rank3Points,stat2Name,stat2Rank1Threshold,stat2Rank1Points,stat2Rank2Threshold,stat2Rank2Points,stat2Rank3Threshold,stat2Rank3Points,stat3Name,stat3Rank1Threshold,stat3Rank1Points,stat3Rank2Threshold,stat3Rank2Points,stat3Rank3Threshold,stat3Rank3Points,def_description FROM grimoireCards WHERE def_type='" . $grimoireDbType . "'";
					$db_array = mysqli_query($db_connection, $db_query);
					//echo mysqli_num_rows($db_array);
					
					while($row = mysqli_fetch_assoc($db_array)) {
						
						$varAcquired = false;
						$statArray = [];
						
						$statArray[1][name] = $row[stat1Name];
						$statArray[1][rank][1][threshold] = $row[stat1Rank1Threshold];
						$statArray[1][rank][1][points] = $row[stat1Rank1Points];
						$statArray[1][rank][2][threshold] = $row[stat1Rank2Threshold];
						$statArray[1][rank][2][points] = $row[stat1Rank2Points];
						$statArray[1][rank][3][threshold] = $row[stat1Rank3Threshold];
						$statArray[1][rank][3][points] = $row[stat1Rank3Points];
						$statArray[2][name] = $row[stat2Name];
						$statArray[2][rank][1][threshold] = $row[stat2Rank1Threshold];
						$statArray[2][rank][1][points] = $row[stat2Rank1Points];
						$statArray[2][rank][2][threshold] = $row[stat2Rank2Threshold];
						$statArray[2][rank][2][points] = $row[stat2Rank2Points];
						$statArray[2][rank][3][threshold] = $row[stat2Rank3Threshold];
						$statArray[2][rank][3][points] = $row[stat2Rank3Points];
						$statArray[3][name] = $row[stat3Name];
						$statArray[3][rank][1][threshold] = $row[stat3Rank1Threshold];
						$statArray[3][rank][1][points] = $row[stat3Rank1Points];
						$statArray[3][rank][2][threshold] = $row[stat3Rank2Threshold];
						$statArray[3][rank][2][points] = $row[stat3Rank2Points];
						$statArray[3][rank][3][threshold] = $row[stat3Rank3Threshold];
						$statArray[3][rank][3][points] = $row[stat3Rank3Points];
						
						if(search_array($row[cardId], $grimoireStatus)) {
							$varAcquired = true;
							foreach($grimoireStatus[data][cardCollection] as $card) {
								if($card[cardId] == $row[cardId]) {
									foreach($card[statisticCollection] as $statisticCollection) {
										$statArray[$statisticCollection[statNumber]][value] = $statisticCollection[value];
										foreach($statisticCollection[rankCollection] as $rankCollection) {
											$statArray[$statisticCollection[statNumber]][rank][$rankCollection[rank]][unlocked] = $rankCollection[points];
											if(!$rankCollection[points]) { $varAcquired = false; }
										}
									}
								}
							}
						}
						
						foreach($statArray as $stat) {
							foreach($stat[rank] as $rank) {
								if($rank[points] != 0) {
									if(!$rank[unlocked]) { $varAcquired = false; }	
								}
							}
						}
						
						if($varAcquired == true) {
							$varAcquired_panel = "panel-green";
							$varAcquired_panel_heading = "panel-heading-green";
							$varAcquired_glyphicon = "glyphicon-ok";
							$varAcquired_panel_collapse = "panel-collapse-acquired";
							$varAcquired_span = "span-green";
							
							$varAcquired_collapse = "collapsed";
							$varAcquired_collapse_in = "";
						} else {
							$varAcquired_panel = "panel-red";
							$varAcquired_panel_heading = "panel-heading-red";
							$varAcquired_glyphicon = "glyphicon-remove";
							$varAcquired_panel_collapse = "panel-collapse-missing";
							$varAcquired_span = "span-red";
							
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
										<?php echo $row[cardName]; ?>
										<span class="hidden-xs <?php echo $varAcquired_span;?>"><?php echo " // " . $row[pageName] . " // " . $row[themeName]; ?></span>
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
									<?php echo "Grimoire Points: " . $row[points]; ?>
								</div>
								<div class="panel-body">
									<?php if($row[def_error] != "") { ?>
										<div class="alert alert-danger" role="alert"><?php echo $row[def_error]; ?></div>
									<?php } ?>
									<?php echo $row[def_description]; ?>
									
									<?php
									
										foreach($statArray as $stat) {
											if($stat[name] != "" AND $stat[value] != "") {
												
												echo "<br><br><strong>" . $stat[name] . ": " . $stat[value] . "</strong><br>";
												
												foreach($stat[rank] as $curRank => $rank) {
													
													if($curRank == "1") { $percentComplete = ($stat[value] / $rank[threshold]) * 100; }
													else { $percentComplete = (($stat[value] - $stat[rank][$curRank - 1][threshold]) / ($rank[threshold] - $stat[rank][$curRank - 1][threshold])) * 100; }
													
													if($percentComplete > "100") { $percentComplete = "100"; }
													
													if($rank[points] != 0) {
											
									?>
												
														<div class="col-md-4<?php if($curRank != "2") { echo " no-padding"; } ?>">
															<?php echo "Rank " . $curRank . ": " . $rank[threshold]; ?>
															<div class="progress">
																<div class="progress-bar progress-bar-danger" role="progressbar" style="min-width:3em; width:<?php echo $percentComplete ?>%;">
																	<i class="icon-grimoire"></i> <?php echo $rank[points]; ?>
																</div>
															</div>
														</div>
												
									<?php
									
													}
												}
											}
										}
									
									?>
									
								</div>
								<?php if($grimoireCard[Crreports] != "") { ?>
									<div class="panel-body no-top-padding small">
										<?php echo $grimoireCard[Crreports]; ?>
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