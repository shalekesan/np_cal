<?php 
	
	function getDataset($from) {
		$conn = connect();
		
		$from_sql_1 = clone $from;
		$from_sql_2 = clone $from;
		date_add($from_sql_2, date_interval_create_from_date_string('6 days'));
		
		$date_1 = date_format($from_sql_1, 'Y-m-d');
		$date_2 = date_format($from_sql_2, 'Y-m-d');
		
		$sql = "SELECT * FROM event WHERE date BETWEEN '$date_1' and '$date_2' ";
		$res = mysql_query($sql, $conn);
		
		$events = array();
		while ($row = mysql_fetch_assoc($res)) {
			$events[$row['name']][$row['date']] = $row['notes'];
		}
		mysql_close($conn);
		return $events;
	}

	function connect() {
		$conn = mysql_connect("localhost", "root", "dhunetworks");
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db("nextplus_cal", $conn);
		return $conn;
	}
?>

