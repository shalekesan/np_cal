<?php

	require_once 'lib.php';
	
	$conn = null;

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		echo "ERROR";
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
		$name = empty($_POST['name']) ? null : $_POST['name'];
		$date = empty($_POST['date']) ? null : $_POST['date'];

		$event = empty($_POST['value']) ? null : $_POST['value'];
		
		$conn = connect();
		
		$pre_sql = "SELECT * FROM event WHERE name = '$name' AND date = '$date' ";
		
		$res = mysql_query($pre_sql, $conn);
		$row = mysql_fetch_assoc($res);
		
		if(!$row) {
			$sql = "INSERT INTO event (`name`, `date`, `notes`) VALUES ('$name', '$date', '$event')";
		} else {
			$id = $row['id'];
 			$sql = "UPDATE event SET notes = '$event' WHERE id = '$id'";
		}
		
	 	var_dump($sql);
	 	
		if (!mysql_query($sql, $conn)) {
			die('Error: ' . mysql_error());
		}
		
		mysql_close($conn);	
	}