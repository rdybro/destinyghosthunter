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
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='new' OR def_status='review')";
				$db_array = mysqli_query($db_connection, $db_query);
				
				echo "<li class='list-group-item active'><span class='badge'>" . mysqli_num_rows($db_array) . "</span>For Review</li>";
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<a class='list-group-item fancybox-guide-lore' href='/report/" . $row[cardId] . "' data-fancybox-type='iframe'>" . $row[cardId];
					
					if($row[def_status] == "new") {
						echo "<span class='badge'>new</span>";
					}
					
					echo "</a>";
					
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND (def_type='Dead Ghost' OR def_type='Mystery')";
				$db_array = mysqli_query($db_connection, $db_query);
				
				echo "<li class='list-group-item active'><span class='badge'>" . mysqli_num_rows($db_array) . "</span>Dead Ghosts</li>";
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<a class='list-group-item fancybox-guide-lore' href='/report/" . $row[cardId] . "' data-fancybox-type='iframe'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</a>";
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND def_type='Calcified Fragment'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				echo "<li class='list-group-item active'><span class='badge'>" . mysqli_num_rows($db_array) . "</span>Calcified Fragments</li>";
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<a class='list-group-item fancybox-guide-lore' href='/report/" . $row[cardId] . "' data-fancybox-type='iframe'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</a>";
				}
			
			?>
			
		</ul>
	</div>
	
	<div class="col-md-3">
		<ul class="list-group">
			
			<?php
			
				$db_query = "SELECT cardId,def_status FROM grimoireCards WHERE (def_status='no' OR def_status='in_progress') AND def_type<>'Dead Ghost' AND def_type<>'Mystery' AND def_type<>'Calcified Fragment'";
				$db_array = mysqli_query($db_connection, $db_query);
				
				echo "<li class='list-group-item active'><span class='badge'>" . mysqli_num_rows($db_array) . "</span>Others</li>";
				
				while($row = mysqli_fetch_assoc($db_array)) {
					echo "<a class='list-group-item fancybox-guide-lore' href='/report/" . $row[cardId] . "' data-fancybox-type='iframe'>" . $row[cardId];
					
					if($row[def_status] == "in_progress") {
						echo "<span class='badge'>in progress</span>";
					}
					
					echo "</a>";
				}
			
			?>
			
		</ul>
	</div>
	
</div>

<?php
	
	}
	
	mysqli_close($db_connection);

?>