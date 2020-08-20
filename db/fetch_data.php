<?php

//fetch_data.php

include('db_connect.php');



$query = "SELECT * FROM Employees ORDER BY id";
$statement = $connect->prepare($query);
if($statement->execute())
{
	while($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		$data[] = $row;
	}

	echo json_encode($data);
}

?>