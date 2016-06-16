<div class="row">
	<div class="col-md-12">
		<h2>All Grimoire Cards</h2>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="list-group">
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/all/" class="list-group-item disabled<?php if(strtolower($curLocation == 'all')) { echo ' active'; } ?>">All</a>
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/missing/" class="list-group-item<?php if(strtolower($curLocation == 'missing')) { echo ' active'; } ?>">Missing</a>
		</div>
		
		<div class="list-group">
			<a href="/<?php echo $curPlatform . '/' . strtolower($curDisplayName); ?>/grimoire/exotics/" class="list-group-item disabled<?php if(strtolower($curLocation == 'exotics')) { echo ' active'; } ?>">Exotic Weapons</a>
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
						if(!search_array($row[cardId], $grimoireStatus)) {
							
			?>
			
							<div class="col-md-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h4 class="panel-title">
											<?php echo $row[cardName]; ?>
											<div class="pull-right">
												<i class="icon-grimoire"></i>
												<?php echo $row[points]; ?>
											</div>
										</h4>
									</div>
									<div class="panel-body">
										<a class="btn btn-default pull-right fancybox-guide-lore" href="/lore/<?php echo $row[cardId]; ?>" role="button" data-fancybox-type="iframe">Lore</a>
										<?php if($row[def_error] != "") { ?>
											<div class="alert alert-danger" role="alert"><?php echo $row[def_error]; ?></div>
										<?php } ?>
										Hej
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