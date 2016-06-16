<div class="row">
	<div class="col-md-12">
		<h1>User Search</h1>
	</div>
</div>
	
<?php if (count($userSearch) == 0) { ?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-danger">
				<strong>No users found!</strong> Sorry, but I can't lookup any users with that username. Please try again, and make sure that you are typing your username correct. If this error persist, it may be a problem with the API. Please visit <a href="http://destinystatus.com/" target="_blank">Destiny Status</a> or <a href="http://www.destinyrep.com/" target="_blank">Destiny Rep</a> to verify.
			</div>
		</div>
	</div>
	
<?php } else { ?>

	<div class="row">
		<?php
		
			foreach($userSearch as $user) {
				if($user[membershipType] == "1") { $platform = "xbl"; $platformDisplay = "Xbox"; }
				elseif($user[membershipType] == "2") { $platform = "psn"; $platformDisplay = "PlayStation"; }

		?>
		
				<div class="col-md-6">
					<div class="list-group">
						<div class="list-group-item active">
							 <span class="badge pull-right"><a href="https://www.bungie.net/en/Profile/<?php echo $user[membershipType] . "/" . $user[membershipId]; ?>" target="_blank">Bungie.net</a></span>
							<?php echo $user[displayName]; ?>
						</div>
						<div class="list-group-item"><?php echo "Platform: " . $platformDisplay . "<br>ID: " . $user[membershipId]; ?></div>
						<a href="/select/<?php echo $platform . "/" . $curPath[1] . "/" . $user[membershipId]; ?>/" class="list-group-item list-group-item-info">
							<span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>
							<b>Select</b>
						</a>
					</div>
				</div>
				
		<?php
		
			}
			
		?>
			
	</div>

<?php } ?>