<?php

//product_action.php

include('database_connection.php');

include('function.php');


if (isset($_POST['btn_action'])) {
    if ($_POST['btn_action'] == 'load_brand') {
        echo fill_brand_list($connect, $_POST['category_id']);
    }

    if ($_POST['btn_action'] == 'Add') {
if ($_SESSION['type']=="user"){
        $query1 = "
		SELECT * FROM asset WHERE product_name = :product_name AND brand_id = :brand_id AND category_id=:category_id AND church_id=:church_id
		";
        $statement1 = $connect->prepare($query1);
        $statement1->execute(
                array(
                    ':product_name' => $_POST["product_name"],
                    ':brand_id' => $_POST["brand_id"],
                    ':category_id' => $_POST["category_id"],
                     ':church_id' => $_SESSION["church"]
                )
        );


        if ($statement1->rowCount() > 0) {
            $result1 = $statement1->fetchAll();
            foreach ($result1 as $row) {
              $quantity= ($_POST['product_quantity']+$row['product_quantity']);
              $id = $row['product_id'];
              
            }
            $query = "
		UPDATE asset 
		set product_quantity = :product_quantity 
		WHERE product_id = :product_id AND church_id=:church_id
		";
            $statement = $connect->prepare($query);
            $statement->execute(
                    array(
                        ':product_quantity' => $quantity,
                        ':product_id' => $id,
                    ':church_id' => $_SESSION['church']
                    )
            );
            $result = $statement->fetchAll();
            if (isset($result)) {
                echo 'Asset Quantity Updated';
            }
            
        }else{
        $query = "
		INSERT INTO asset (category_id, brand_id, product_name, product_description, product_quantity, product_unit, product_enter_by, product_status, product_date,church_id) 
		VALUES (:category_id, :brand_id, :product_name, :product_description, :product_quantity, :product_unit, :product_enter_by, :product_status, :product_date, :church_id)
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                    ':category_id' => $_POST['category_id'],
                    ':brand_id' => $_POST['brand_id'],
                    ':product_name' => $_POST['product_name'],
                    ':product_description' => $_POST['product_description'],
                    ':product_quantity' => $_POST['product_quantity'],
                    ':product_unit' => $_POST['product_unit'],
                    ':product_enter_by' => $_SESSION["user_id"],
                    ':product_status' => 'good',
                    ':church_id' => $_SESSION["church"],
                    ':product_date' => date("Y-m-d")
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'Asset Added.';
        }
    }}
    if ($_SESSION['type']=="master"){
      $query1 = "
		SELECT * FROM asset WHERE product_name = :product_name AND brand_id = :brand_id AND category_id=:category_id AND church_id=:church_id
		";
        $statement1 = $connect->prepare($query1);
        $statement1->execute(
                array(
                    ':product_name' => $_POST["product_name"],
                    ':brand_id' => $_POST["brand_id"],
                    ':category_id' => $_POST["category_id"],
                     ':church_id' => $_POST["church_id"]
                )
        );


        if ($statement1->rowCount() > 0) {
            $result1 = $statement1->fetchAll();
            foreach ($result1 as $row) {
              $quantity= ($_POST['product_quantity']+$row['product_quantity']);
              $id = $row['product_id'];
              
            }
            $query = "
		UPDATE asset 
		set product_quantity = :product_quantity 
		WHERE product_id = :product_id AND church_id=:church_id
		";
            $statement = $connect->prepare($query);
            $statement->execute(
                    array(
                        ':product_quantity' => $quantity,
                        ':product_id' => $id,
                    ':church_id' => $_POST["church_id"]
                    )
            );
            $result = $statement->fetchAll();
            if (isset($result)) {
                echo 'Asset Quantity Updated';
            }
            
        }else{
        $query = "
		INSERT INTO asset (category_id, brand_id, product_name, product_description, product_quantity, product_unit, product_enter_by, product_status, product_date,church_id) 
		VALUES (:category_id, :brand_id, :product_name, :product_description, :product_quantity, :product_unit, :product_enter_by, :product_status, :product_date, :church_id)
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                    ':category_id' => $_POST['category_id'],
                    ':brand_id' => $_POST['brand_id'],
                    ':product_name' => $_POST['product_name'],
                    ':product_description' => $_POST['product_description'],
                    ':product_quantity' => $_POST['product_quantity'],
                    ':product_unit' => $_POST['product_unit'],
                    ':product_enter_by' => $_SESSION["user_id"],
                    ':product_status' => 'good',
                    ':church_id' => $_SESSION["church"],
                    ':product_date' => date("Y-m-d")
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'Asset Added.';
        }
    }}  
    }
    
    if ($_POST['btn_action'] == 'product_details') {
        $query = "
		SELECT * FROM asset 
		INNER JOIN category ON category.category_id = asset.category_id 
		INNER JOIN brand ON brand.brand_id = asset.brand_id 
		INNER JOIN user_details ON user_details.user_id = asset.product_enter_by 
		WHERE asset.product_id = '" . $_POST["product_id"] . "'
		";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';
        foreach ($result as $row) {
            $status = '';
            if ($row['product_status'] == 'good') {
                $status = '<span class="label label-success">Good</span>';
            } else {
                $status = '<span class="label label-danger">Faulty</span>';
            }
            $output .= '
			<tr>
				<td>Product Name</td>
				<td>' . $row["product_name"] . '</td>
			</tr>
			<tr>
				<td>Product Description</td>
				<td>' . $row["product_description"] . '</td>
			</tr>
			<tr>
				<td>Category</td>
				<td>' . $row["category_name"] . '</td>
			</tr>
			<tr>
				<td>Brand</td>
				<td>' . $row["brand_name"] . '</td>
			</tr>
			<tr>
				<td>Available Quantity</td>
				<td>' . $row["product_quantity"] . ' ' . $row["product_unit"] . '</td>
			</tr>
			<tr>
				<td>Enter By</td>
				<td>' . $row["user_name"] . '</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>' . $status . '</td>
			</tr>
			';
        }
        $output .= '
			</table>
		</div>
		';
        echo $output;
    }
    if ($_POST['btn_action'] == 'fetch_single') {
        $query = "
		SELECT * FROM asset WHERE product_id = :product_id
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                    ':product_id' => $_POST["product_id"]
                )
        );
        $result = $statement->fetchAll();
        foreach ($result as $row) {
            $output['category_id'] = $row['category_id'];
            $output['brand_id'] = $row['brand_id'];
            $output["brand_select_box"] = fill_brand_list($connect, $row["category_id"]);
            $output['product_name'] = $row['product_name'];
            $output['product_description'] = $row['product_description'];
            $output['product_quantity'] = $row['product_quantity'];
            $output['product_unit'] = $row['product_unit'];
        }
        echo json_encode($output);
    }

    if ($_POST['btn_action'] == 'Edit') {
        $query = "
		UPDATE asset 
		set category_id = :category_id, 
		brand_id = :brand_id,
		product_name = :product_name,
		product_description = :product_description, 
		product_quantity = :product_quantity, 
		product_unit = :product_unit
		WHERE product_id = :product_id
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                    ':category_id' => $_POST['category_id'],
                    ':brand_id' => $_POST['brand_id'],
                    ':product_name' => $_POST['product_name'],
                    ':product_description' => $_POST['product_description'],
                    ':product_quantity' => $_POST['product_quantity'],
                    ':product_unit' => $_POST['product_unit'],
                    ':product_id' => $_POST['product_id']
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'Asset Details Updated';
        }
    }
    
    if ($_POST['btn_action'] == 'Add Asset') {
         
           $query9 = "
		SELECT * FROM asset WHERE product_id=:product_id
		";
        $statement3 = $connect->prepare($query9);
        $statement3->execute(
                array(
                    ':product_id' => $_POST["asset_id"]
                )
        );

        if ($statement3->rowCount() > 0) {
            $result3 = $statement3->fetchAll();
            foreach ($result3 as $row) {
                $quantity= $_POST['product_quantity']+$row['product_quantity'];
              }
         
        $query = "
		UPDATE asset 
		set product_quantity = :product_quantity 
		WHERE product_id = :product_id
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                   ':product_quantity' =>  $quantity,
                    ':product_id' => $_POST['asset_id']
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo $_POST['product_quantity'] . " " . $row['product_name']. 's Added';
        }
    }
     }
     
    if ($_POST['btn_action'] == 'Remove Asset') {
         
           $query9 = "
		SELECT * FROM asset WHERE product_id=:product_id
		";
        $statement3 = $connect->prepare($query9);
        $statement3->execute(
                array(
                    ':product_id' => $_POST["asset_id"]
                )
        );

        if ($statement3->rowCount() > 0) {
            $result3 = $statement3->fetchAll();
            foreach ($result3 as $row) {
                $quantity= $row['product_quantity']-$_POST['product_quantity'];
              }
         
        $query = "
		UPDATE asset 
		set product_quantity = :product_quantity 
		WHERE product_id = :product_id
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                   ':product_quantity' =>  $quantity,
                    ':product_id' => $_POST['asset_id']
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo $_POST['product_quantity'] . " " . $row['product_name']. 's Removed';
        }
    }
     }
    
    if ($_POST['btn_action'] == 'delete') {
        $status = 'good';
        if ($_POST['status'] == 'good') {
            $status = 'faulty';
        }
        $query = "
		UPDATE asset 
		SET product_status = :product_status 
		WHERE product_id = :product_id
		";
        $statement = $connect->prepare($query);
        $statement->execute(
                array(
                    ':product_status' => $status,
                    ':product_id' => $_POST["product_id"]
                )
        );
        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'Asset status change to ' . $status;
        }
    }
}
?>
