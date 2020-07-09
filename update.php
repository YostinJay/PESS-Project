<html>
<head>
    
    <?php 
if (isset($_POST["btnUpdate"])){ 
    
require_once 'db_config.php'; 
    
// create database connection 
$mysqli = mysqli_connect("localhost", "root", "", "pessdb"); 
// Check connection 
if ($mysqli->connect_errno) { 
    die("Failed to connect to MySQL: ".$mysqli->connect_errno);
}
// update patrol car status 
$sql = "UPDATE patrolcar SET patrolcarStatusId = ? WHERE patrolcarId = ? "; 
    
if (!($stmt = $mysqli->prepare($sql))) { 
    die("Prepare failed: ".$mysqli->errno); 
}
    
if (!$stmt->bind_param('ss', $_POST['patrolCarStatus'], $_POST['patrolCarId'])){ 
    die("Binding parameters failed: ".$stmt->errno); 
}
    
if (!$stmt->execute()) { 
    die("Update patrolCar table failed: ".$stmt->errno); 
} 
if  ($_POST["patrolCarStatus"] == '4')
		{
			$sql = "UPDATE dispatch SET timeArrived = NOW() WHERE timeArrived is NULL AND patrolCarId = ?";
			
			if(!($stmt=$mysqli->prepare($sql)))
			{
				die("Prepare failed: ".$mysqli->errno);
			}
			if(!$stmt->bind_param('s', $_POST['patrolCarId']))
			{
				die("Binding parameter failed: ".$stmt->errno);
			}
			if(!$stmt->execute())
			{
				die("Update dispatch table failed: ".$stmt->errno);
			}

		} 
		
		else if ($_POST["patrolCarStatus"] == '3'){

			$sql = "SELECT incidentId FROM dispatch WHERE timeCompleted IS NULL AND patrolCarId = ?";
			
			if(!($stmt=$mysqli->prepare($sql))) {
				die("Prepare failed: ".$mysqli->errno);
			}
			if(!$stmt->bind_param('s', $_POST['patrolCarId'])){
				die("Binding parameter failed: ".$stmt->errno);
			}
			if(!$stmt->execute()) {
				die("Update dispatch table failed: ".$stmt->errno);
			}
			if (!($resultset = $stmt->get_result())) {
				die("Getting result set failed: ".$stmt->errno);
			}

			$incidentId;

			while ($row = $resultset->fetch_assoc()) {
				$incidentId = $row['incidentId'];
			}

			$sql = "UPDATE dispatch SET timeCompleted = NOW() WHERE timeCompleted is NULL AND patrolcarId = ?";

			if(!($stmt=$mysqli->prepare($sql))) {
				die("Prepare failed: ".$mysqli->errno);
			}
			if(!$stmt->bind_param('s', $_POST['patrolCarId'])) {
				die("Binding parameter failed: ".$stmt->errno);
			}
			if(!$stmt->execute()) {
				die("Update dispatch table failed: ".$stmt->errno);
			}

			$sql = "UPDATE incident SET incidentStatusld = '3' WHERE incidentId = '$incidentId' 
			AND NOT EXISTS (SELECT * FROM dispatch WHERE timeCompleted IS NULL AND incidentId = '$incidentId')";

			if(!($stmt = $mysqli->prepare($sql))) {
				die("Prepare failed: ".$mysqli->errno);
			}
			if(!$stmt->execute()) {
				die("Update dispatch table failed: ".$stmt->errno);
			}

			$resultset->close();

		}

$stmt->close(); 

$mysqli->close(); 
?>

<script>window.location="logcall.php";</script> 
    <?php } ?>

	</head>
<meta charset="utf-8">
<title>Update</title>
    
<link href="style.css" rel="stylesheet" type="text/css">
<body> 
    
<?php require_once 'nav.php'; ?> 
    
    <br><br> 
    
    <?php 
if (!isset($_POST["btnSearch"])){ 
?> 
    
    <!-- create a form to search for patrol car based on id --> 
     <div class="container white">
    <fieldset>
            <div class="title">
							<h1>Update</h1>
						</div>
<form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"> 
    
    <table id="t01" width="50%" border="0" align="center" cellpadding="4" cellspacing="4"> <tr></tr> 
    
    <tr> 
        
        <td><label for="patrolcar_id" class="label_name">Patrol Car ID:</label></td>
        <td><input class="inputcss" type="text" name="patrolCarId" id="patrolCarId"></td> 
        <td><input type="submit" name="btnSearch" id="btnSearch" value="Search"></td> 
    </tr> 
    </table> 
    
    </fieldset>
    
</form>

</div>
<?php } 
   else
	{ // post back here after clicking the btnSearch button
		require_once 'db_config.php';
		
		// create database connection
$mysqli = mysqli_connect("localhost", "root", "", "pessdb");
		// Check connection
		if($mysqli->connect_errno)
		{
			die("Failed to connect to MYSQL: ".$mysqli->connect_errno);
		}
		
		// retrieve patrol car detail
$sql = "SELECT * FROM patrolcar WHERE patrolcarId = ?";
		
		if(!($stmt = $mysqli->prepare($sql)))
		{
			die("Prepare failed: ".$mysqli->errno);
		}
		
		if(!$stmt->bind_param('s', $_POST['patrolCarId']))
		{
			die("Binding parameters failed: ".$stmt->ernno);
		}
		
		if(!$stmt->execute())
		{
			die("Execute failed failed: ".$stmt->errno);
		}
		
		if(!($resultset = $stmt->get_result()))
		{
			die("Getting result set failed: ".$stmt->errno);
		}
		
		// if the patrol car does not exist, redirect back to update.php
		if ($resultset->num_rows == 0)
		{
			?>
				<script>window.location="update.php";</script>
			<?php }
		
		// else if the patrol car found
$patrolCarId;
$patrolCarStatusId;
		
		while($row = $resultset->fetch_assoc())
		{
$patrolCarId = $row['patrolcarId'];
$patrolCarStatusId = $row['patrolcarStatusId'];
		}
		
		//retrieve from patrolcar_status table for populating the combo box
$sql = "SELECT * FROM patrolcar_status";
		if(!($stmt = $mysqli->prepare($sql)))
		{
			die("Prepare failed: ".$mysqli->errno);
		}
		
		if (!$stmt->execute())
		{
			die("Execute failed: ".$stmt->errno);
		}
		
		if(!($resultset = $stmt->get_result()))
		{
			die("Getting result set failed: ".$stmt->errno);
		}
		
$patrolCarStatusArray; // an array variable
		
		while($row = $resultset->fetch_assoc())
		{
$patrolCarStatusArray[$row['statusId']] = $row['statusDesc'];
		}
		
$stmt->close();
		
$resultset->close();
		
$mysqli->close();
	?>

	<div class="container white">
    <form name="form2" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?> "> 
<div class="title">
	<h1>Update Patrol</h1>
</div>
<table id="t01" width="50%" border="0" align="center" cellpadding="4" cellspacing="4">
         <tr></tr> 
    <tr> 
        <td><label for="id" class="label_name">ID:</label></td> 
        <td><?php echo $patrolCarId ?> 
            <input type="hidden" name="patrolCarId" id="patrolCarId" value="<?php echo $patrolCarId ?>"> 
        </td> 
    </tr> 
    <tr> 
        <td><label for="status" class="label_name">Status:</label></td>
			<td><select name="patrolCarStatus" id="patrolCarStatus">
			<?php foreach( $patrolCarStatusArray as $key => $value){ ?>
			<option value="<?php echo $key ?>"
			<?php if ($key==$patrolCarStatusId) {?> seleccted="selected"
				<?php }?>
			>
				<?php echo $value ?>
			</option>
			<?php } ?>
			</select></td>
    </tr> 
    <tr> 
        <td><input class="btn" type="reset" name="btnCancel" id="btnCancel" value="Reset"></td> <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn" type="submit" name="btnUpdate" id="btnUpdate" value="Update"> 
            </td> 
        </tr> 
    </table> 
</form>
</div>
<?php } ?>
</body>
</html>


