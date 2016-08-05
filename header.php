<nav class="navbar navbar-inverse navbar-fixed-top">

	<div class="container">
	
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand destinyghosthunter" href="<?php echo $titleUrl; ?>"><span class="navbar-destiny hidden-xs">Destiny </span>Gh<i class="icon-ghost"></i>st Hunter</a>
		</div>
		
		<div id="navbar" class="collapse navbar-collapse">
		
			<?php if($userNotSet != 1) { ?>
			
				<ul class="nav navbar-nav">
					<li class="dropdown<?php if($curPage == "grimoire") { echo " active"; } ?>">
						<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Grimoire <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
						
							<?php
							
								foreach($grimoireCategoryArray as $grimoireCategory) {
									echo "<li><a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/grimoire/" . strtolower($grimoireCategory) . "/'>" . str_replace("-"," ",$grimoireCategory) . "</a></li>";
									
									if($grimoireCategory == "Others" OR $grimoireCategory == "Fallen-Hunted" OR $grimoireCategory == "The-Taken-King") {
										echo "<li class='divider'></li>";
									}
								}
							
							?>
						
						</ul>
					</li>
					<li class="dropdown<?php if($curPage == "ghosts") { echo " active"; } ?>">
						<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ghosts <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
						
							<?php
							
								foreach($ghostLocationArray as $location => $array) {
									echo "<li><a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/ghosts/" . strtolower($location) . "/'>" . str_replace("-"," ",$location) . "</a></li>";
									
									if($location == "The-Reef") {
										echo "<li class='divider'></li>";
									}
								}
							
							?>
						
						</ul>
					</li>
					<li <?php if($curPage == "fragments") { echo "class='active'"; } ?>><a href="/<?php echo $curPlatform; ?>/<?php echo $curDisplayNameUrl; ?>/fragments/grimoire/">Fragments</a></li>
					<li class="dropdown<?php if($curPage == "chests") { echo " active"; } ?>">
						<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Chests <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							
							<?php
							
								foreach($ghostLocationArray as $location => $array) {
									if($array[goldenChests] == 1) {
										echo "<li><a href='/" . $curPlatform . "/" . $curDisplayNameUrl . "/chests/" . strtolower($location) . "/'>" . str_replace("-"," ",$location) . "</a></li>";
									}
								}
							
							?>
							
						</ul>
					</li>
				</ul>
				
			<?php } ?>
		
			<form class="navbar-form navbar-right" action="" method="post">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
						<input type="text" name="displayName" class="form-control" placeholder="Guardian">
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			
		</div><!--/.nav-collapse -->
		
	</div>
	
</nav>