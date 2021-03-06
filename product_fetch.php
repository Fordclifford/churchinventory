<?php

//product_fetch.php

include('database_connection.php');
include('function.php');
$query = '';

$output = array();


     $query .= "
	SELECT * FROM asset 
INNER JOIN brand ON brand.brand_id = asset.brand_id
INNER JOIN church ON church.id = asset.church_id
INNER JOIN category ON category.category_id = asset.category_id 
INNER JOIN user_details ON user_details.user_id = asset.product_enter_by 
";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE brand.brand_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR category.category_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR asset.product_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR asset.product_quantity LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_details.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR asset.product_id LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR church.name LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY product_id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{
	$status = '';
	if($row['product_status'] == 'good')
	{
		$status = '<span class="label label-success">Good</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Faulty</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['product_id'];
	$sub_array[] = $row['category_name'];
	$sub_array[] = $row['brand_name'];
	$sub_array[] = $row['product_name'];
	$sub_array[] = available_product_quantity($connect, $row["product_id"]) . ' ' . $row["product_unit"];
	$sub_array[] = $row['user_name'];
          if ($_SESSION['type']=="master"){
        $sub_array[] = $row['name'];
          }
	$sub_array[] = $status;       
        $sub_array[] = '<button type="button" name="add_asset" id="'.$row["product_id"].'" class="btn btn-success btn-xs add_asset">Add</button>';
	$sub_array[] = '<button type="button" name="remove_asset" id="'.$row["product_id"].'" class="btn btn-danger btn-xs remove_asset">Remove</button>';
        $sub_array[] = '<button type="button" name="view" id="'.$row["product_id"].'" class="btn btn-info btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["product_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["product_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["product_status"].'">Delete</button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM asset');
        $statement->execute();
        if ($_SESSION['type']=="user"){
        $statement = $connect->prepare('SELECT * FROM asset WHERE church_id=:church_id');
        $statement->execute( array(
                    ':church_id' => $_SESSION["church"]
            )
        );
        }
	
	return $statement->rowCount();
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);

echo json_encode($output);

?>