<?php

	if(!$db_connection) {
		die('Could not connect to database: ' . mysqli_connect_error());
	} else {

?>

<div class="row">

	<div class="col-md-12">
		<h2>Guide Status</h2>
	</div>

	<div class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item active">For Review</li>
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='new' OR def_status='review')";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<li class='list-group-item'>" . $row[cardId];
					
					if($row[def_status] == "new") {
						echo "<span class='badge'>new</span>";
					}
					
					echo "</li>";
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item active">Dead Ghosts</li>
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND (def_type='Dead Ghost' OR def_type='Mystery')";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<li class='list-group-item'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</li>";
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item active">Calcified Fragments</li>
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND def_type='Calcified Fragment'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<li class='list-group-item'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</li>";
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item active">Others</li>
			<li class='list-group-item'>Here will come an incredible long list of Grimoire cards missing a guide.</li>
			
			<?php /*
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND def_type<>'Dead Ghost' AND def_type<>'Mystery' AND def_type<>'Calcified Fragment'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<li class='list-group-item'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</li>";
				}
			
			*/ ?>
			
		</ul>
	</div>
	
</div>

<?php
	
	}
	
	mysqli_close($db_connection);

?>