<?php

//user_action.php

include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO church (name, location, user_id, status) 
		VALUES (:name, :location, :user_id, :status)
		";	
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':name'		=>	$_POST["name"],
				':location'	=>	$_POST["location"],
				':user_id'		=>	$_POST["user_id"],
				':status'		=>	'Active'
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'New Church Added';
		}
	}
	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
		SELECT * FROM church WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':id'	=>	$_POST["id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['location'] = $row['location'];
                        $output['name'] = $row['name'];
                         $output['user_id'] = $row['user_id'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
            
	{
            $query = "
		UPDATE church 
		set location = :location, 
		name = :name,
		user_id = :user_id
	
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':location'			=>	$_POST['location'],
				':name'				=>	$_POST['name'],
				':user_id'			=>	$_POST['user_id'],
				':id'			=>	$_POST['id']
			)
		);
		
	
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Church Details Edited ';
		}
	}
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'Active';
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';
		}
		$query = "
		UPDATE church 
		SET status = :status 
		WHERE id = :id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':status'	=>	$status,
				':id'		=>	$_POST["id"]
			)
		);	
		$result = $statement->fetchAll();	
		if(isset($result))
		{
			echo 'Church Status change to ' . $status;
		}
	}
}

?>