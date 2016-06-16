<div class="row">
	<div class="col-md-12">
		<h2>All Grimoire Cards</h2>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="list-group">
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/all/" class="list-group-item<?php if(strtolower($curLocation == 'all')) { echo ' active'; } ?>">All</a>
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/missing/" class="list-group-item<?php if(strtolower($curLocation == 'missing')) { echo ' active'; } ?>">Missing</a>
		</div>
		
		<div class="list-group">
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/exotics/" class="list-group-item<?php if(strtolower($curLocation == 'exotics')) { echo ' active'; } ?>">Exotic Weapons</a>
		</div>
	
		<h3>Statistics</h3>
		<div class="list-group">
			<div class="list-group-item">Grimoire Score<span class="badge pull-right"><?php echo "0 / 0"; ?></span></div>
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
					$db_query = "SELECT cardId,cardName,points,themeName,pageName,rankName,rank1Points,def_description FROM grimoireCards WHERE def_type<>'Dead Ghost' AND def_type<>'Mystery' AND def_type<>'Calcified Fragment'";
					$db_array = mysqli_query($db_connection, $db_query);
					
					while($row = mysqli_fetch_assoc($db_array)) {
						if(!search_array($row[cardId], $grimoireStatus) OR $row[rank1Points] != "") {
							if($row[rank1Points] != 0) {
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
										<span><i class="icon-grimoire"></i> <?php echo $row[points]; ?></span>
										<i class="pull-right glyphicon glyphicon-chevron-down show-when-open"></i>
										<i class="pull-right glyphicon glyphicon-chevron-left show-when-collapsed"></i>
									</a>
								</h4>
							</div>
							<div id="<?php echo $row[cardId]; ?>" class="<?php echo $varAcquired_panel_collapse; ?> panel-collapse collapse <?php echo $varAcquired_collapse_in; ?>">
								<div class="panel-body">
									<a class="btn btn-default pull-right fancybox-guide-lore" href="/lore/<?php echo $row[cardId]; ?>" role="button" data-fancybox-type="iframe">Lore</a>
									<?php if($row[def_video] != "") { ?>
										<a class="btn btn-danger pull-right fancybox-guide-vid btn-guide-vid" href="http://www.youtube.com/embed/<?php echo $row[def_video]; ?>?rel=0&autoplay=1" role="button">YouTube</a>
									<?php } ?>
									<?php echo "Grimoire Points: " . $row[points]; ?>
									<?php if($row[def_error] != "") { ?>
										<div class="alert alert-danger" role="alert"><?php echo $row[def_error]; ?></div>
									<?php } ?>
									<?php echo $row[def_description]; ?>
								</div>
								<?php if($grimoireCard[Credits] != "") { ?>
									<div class="panel-body no-top-padding small">
										<?php echo $grimoireCard[Credits]; ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					
			<?php
						}
					}
				}
			?>
	
		</div>
	</div>
</div>