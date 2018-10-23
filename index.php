<?php
//index.php
include('database_connection.php');
include('function.php');

 $query = "
	SELECT category_id FROM category 
	WHERE category_status = 'active' AND category_name='Instruments'
	ORDER BY category_name ASC
	";
    $statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
        foreach($result as $row){
            $instruments = $row["category_id"];
            
        }
       

if(!isset($_SESSION["type"]))
{
	header("location:login.php");
}

include('header.php');

?>
	<br />
	<div class="row">
      	<?php
	if($_SESSION['type'] == 'master')
	{
	?>
            <div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Churches</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_churches($connect); ?></h1>
			</div>
                        <a href="church.php">
                          <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
		</div>
	</div>
            <?php
	}
	?>
            
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Instruments</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_instruments($connect); ?></h1>
			</div>
                        <a href="asset.php?category_id=<?php echo $instruments ?>">
                           <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                            </a>
		</div>
	</div>
      
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Furniture</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_furniture($connect); ?></h1>
			</div>
                        <a href="asset.php">
                        <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
		</div>
	</div>
         <div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Library</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_library($connect); ?></h1>
			</div>
                        <a href="asset.php">
                        <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
		</div>
	</div>
             <div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>ICT</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_ict($connect); ?></h1>
			</div>
                        <a href="asset.php">
                         <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
		</div>
	</div>
               <div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Kitchen</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_kitchen($connect); ?></h1>
			</div>
                        <a href="asset.php">
                         <div class="panel-footer">
                                <span class="pull-left">Go to &nbsp;</span>
                                <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Total Items</strong></div>
			<div class="panel-body" align="center">
				<h1><?php echo count_total_product($connect); ?></h1>
			</div>
		</div>
	</div>
	
		
	</div>

<?php
include("footer.php");
?>