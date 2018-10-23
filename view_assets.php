<?php


require_once 'pdf.php';
include('database_connection.php');
include('function.php');
if (!isset($_SESSION['type'])) {
    header('location:login.php');
}
    $statement = $connect->prepare("SELECT * FROM asset 
			INNER JOIN brand ON brand.brand_id = asset.brand_id
INNER JOIN category ON category.category_id = asset.category_id 
INNER JOIN user_details ON user_details.user_id = asset.product_enter_by where asset.church_id=:church_id
		");
    $statement->execute(
            array(
                ':church_id' => $_SESSION["church"]
            )
    );
       
    $product_result = $statement->fetchAll();

$output = '';

    $output .= '
		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td colspan="2" align="center" style="font-size:18px"><b>' . $_SESSION['church_name'] . ' Assets</b></td>
			</tr>
			<tr>
				<td colspan="2">
				<table width="100%" cellpadding="5">
					<tr>
						<td width="65%">
							
							<b>Printed By </b><br />
							Name : ' . $_SESSION["user_name"] . '<br />	
							
						</td>
						<td width="35%">
							Details Charge<br />
							Date : ' . date("d-m-Y") . '<br />
						</td>
					</tr>
				</table>
				<br />
				<table width="100%" border="1" cellpadding="5" cellspacing="0">
					<tr>
						<th rowspan="2">Category.</th>
						<th rowspan="2">Brand</th>
						<th rowspan="2">Name</th>
						<th rowspan="2">Description</th>
						<th rowspan="2">Quantity</th>
						<th colspan="2">Entered by</th>
						<th rowspan="2">Status</th>
                                                <th rowspan="2">Date</th>
					</tr>
					
		';
 

    foreach ($product_result as $sub_row) {
        
              $output .= '
				<tr>
					<td>' . $sub_row['category_name'] . '</td>
					<td>' . $sub_row['brand_name'] . '</td>
					<td>' . $sub_row["product_name"] . '</td>
					<td'. $sub_row["product_description"] . '</td>
                                        <td>' . $sub_row['product_quantity']. $sub_row['product_unit']. '</td>
					<td>' . $sub_row['user_name'] . '</td>
					<td>' . $sub_row["product_status"] . '</td>
					<td>'. $sub_row["product_date"] . '</td>
					
					
				</tr>
                                </table>
			';
    }   

$pdf = new Pdf();
$file_name = 'Assets.pdf';
$pdf->loadHtml($output);
$pdf->render();
$pdf->stream($file_name, array("Attachment" => false));
    
?>