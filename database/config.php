<?php  

	$connection = mysqli_connect("localhost", "ftploeyp_manager", "hotel@123", "ftploeyp_hotel");

	if (!($connection)) {
		die(mysqli_error($connection));
	}
	
?>