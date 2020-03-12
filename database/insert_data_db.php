<?php  

include 'config.php';

$obj = json_decode(file_get_contents('php://input'));

if(count($obj) > 0) {
	$order_desc = mysqli_real_escape_string($connection, $obj->order_desc);
	$order_qty = mysqli_real_escape_string($connection, $obj->order_qty);
	$order_amt = mysqli_real_escape_string($connection, $obj->order_amt);

	$query = "INSERT INTO order_table(`order_desc`, `order_qty`, `order_amt`) VALUES('$order_desc', '$order_qty', '$order_amt')";


	if(mysqli_query($connection, $query)) {
		echo "Tuple Inserted";
	} else {
		echo "No Insertion done";
	}

}




?>