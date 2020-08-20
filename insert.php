<?php

//insert.php

include('db_connect.php');

$form_data = json_decode(file_get_contents("php://input"));



$error = '';
$message = '';
$validation_error = '';
$first_name = '';
$last_name = '';
$department_name = '';
$employee_file = '';


if($form_data->action == 'employee_data')

{
	$query = "SELECT * FROM Employees WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['first_name'] = $row['first_name'];
		$output['last_name'] = $row['last_name'];
		$output['department_name'] = $row['department_name'];
		$output['employee_file'] = $row['employee_file'];

	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM Employees WHERE id='".$form_data->id."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'The Record Has Been Deleted';
	}
}
else
{
	if(empty($form_data->first_name))
	{
		$error[] = 'First Name is Required';
	}
	else
	{
		$first_name = $form_data->first_name;
	}

	if(empty($form_data->last_name))
	{
		$error[] = 'Last Name is Required';
	}
	else
	{
		$last_name = $form_data->last_name;
	}



	if(empty($form_data->department_name))
	{
		$error[] = 'Department is Required';
	}
	else
	{
		$department_name = $form_data->department_name;
	}


	if(empty($form_data->employee_file))
	{
		$error[] = 'File is Required';
	}
	else
	{
		$employee_file = $form_data->employee_file;
	}





	if(empty($error))
	{
		if($form_data->action == 'Insert')
		{
			$data = array(
				':first_name'		=>	$first_name,
				':last_name'		=>	$last_name,
				':department_name'	=>	$department_name,
				':employee_file'	=>	$employee_file	
			);
			$query = "
			INSERT INTO Employees 
				(first_name, last_name, department_name, employee_file) VALUES 
				(:first_name, :last_name, :department_name, :employee_file)
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Data Inserted';
			}
		}
		if($form_data->action == 'Edit')
		{
			$data = array(
				':first_name'	=>	$first_name,
				':last_name'	=>	$last_name,
				':department_name'	=>	$department_name,
				':employee_file'	=>	$employee_file,
				':id'			=>	$form_data->id
			);
			$query = "
			UPDATE Employees 
			SET first_name = :first_name, last_name = :last_name, department_name = :department_name, employee_file = :employee_file
			WHERE id = :id
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Data Edited';
			}
		}
	}
	else
	{
		$validation_error = implode(", ", $error);
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}



echo json_encode($output);

?>