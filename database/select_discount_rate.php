<?php  

	include 'config.php';

	$records = array();

	$query = "SELECT discount_rate FROM master_discount_table WHERE discount_id = '1'";

	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$records[] = $row;
		}

		echo json_encode($records);
	}


?>