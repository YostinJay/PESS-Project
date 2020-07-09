<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
   
    
        
	<?php require 'nav.php' ?>
    
<?php //if post back
	if (isset($_POST["btnDispatch"]))
	{
		require_once 'db_config.php';
		
		//create database connection
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		//check connection
		if ($mysqli->connect_errno)
		{
			die("Unable to connect to database(MySql): ".$mysqli->connect_errno);
		}
		
		$patrolCarDispatched = $_POST["chkPatrolcar"]; 
       
        // array of patrolcar being dispatched from post back
		$numOfPatrolCarDispatched = count($patrolCarDispatched);
		
		//insert new incident
		$incidentStatus;
		if ($numOfPatrolCarDispatched > 0)
		{
			$incidentStatus='2'; 
            
            //incident status to be set as Dispatched
		}
		else
		{
			$incidentStatus='1'; 
            
        //incident status to be set as Pending
		}
		
		$sql = "INSERT INTO incident (callerName, phoneNumber, 	incidentTypeid, incidentLocation, incidentDesc, 	incidentStatusld)
		VALUES (?, ?, ?, ?, ?, ?)";
		
		if(!($stmt = $mysqli->prepare($sql)))
		{
			die("Prepare failed: ".$mysqli->errno);
		}
		
		if(!$stmt->bind_param('ssssss', 
                              
                              $_POST['callerName'],
							 			$_POST['contactNo'],
							  			$_POST['incidentType'],
							  			$_POST['location'],
							  			$_POST['incidentDesc'],
							 			$incidentStatus))
		
		{
			die("Binding parameters failed: ".$stmt->errno);
		}
		
		if (!$stmt->execute())
		{
			die("Insert incident table failed: ".$stmt->errno);
		}
        
         // retrieve incident_id for the newly inserted incident 
                
$incidentId=mysqli_insert_id($mysqli);; 
// update patrolcar status table and add into dispatch table 

for ($i=0; $i < $numOfPatrolCarDispatched; $i++)
{ 
// update patro car status 
$sql = "UPDATE patrolCar SET patrolCarStatusId ='1' WHERE patrolCarId = ?"; 

if (!($stmt = $mysqli->prepare($sql))) {
    die("Prepare failed: ".$mysqli->errno);
}
    
if (!$stmt->bind_param('s', $patrolCarDispatched[$i])){
    die("Binding parameters failed: ".$stmt->errno); }
    
if (!$stmt->execute()) {
    die("Update patrolCar_status table failed: ".$stmt->errno); 
}
                
// insert dispatch data 
$sql = "INSERT INTO dispatch (incidentId, patrolCarId, timeDispatched) VALUES (?, ?, NOW())"; 

if (!($stmt = $mysqli->prepare($sql))) { 
    die("Prepare failed: ".$mysqli->errno);
}
                        
if (!$stmt->bind_param('ss', $incidentId, $patrolCarDispatched[$i])){ 
    
    die("Binding parameters failed: ".$stmt->errno); 
                                                    
}
if (!$stmt->execute()) {
    die("Insert dispatch table failed: ".$stmt->errno); 
}
                       }
                
                 
                 $stmt->close();
                 $mysqli->close();
    }
    ?>
    
        
        
    
    
    
    
    
    <div class="container white">
    <fieldset>
            <div class="title">
            <h1>Dispatch Panel</h1>
            </div>
    <form name"forml" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?> ">
        <div class="container">
        <table  id="t01" align="center"  table width="960" height="500" border="0" cellpadding="12" cellspacing="0"> 
            
            
            <tr> 
               <td> <label class="label_name" for="callername">Caller's Name</label></td> 
            <td>
               <?php echo $_POST['callerName'] ?>
                <input type="hidden" name="callerName" id="callerName" value="<?php echo $_POST['callerName'] ?>"> </td>
               </tr>
               
        
            
             <tr>
        <td><label class="label_name" for="contacntno">Contact Number</label></td>
        <td><?php echo $_POST['contactNo']?> <input type="hidden" name="contactNo" id="contactNo" value="<?php echo $_POST['contactNo']?>"> </td>
        </tr>
            
            
            
            
            
   <tr>
<td> <label class="label_name" for="location">Location</label></td>
<td><?php echo $_POST['location'] ?>
<input type="hidden" name="location" id="location"
value="<?php echo $_POST['location'] ?>"></td>	
</tr>
            
            <tr>
            <td><label class="label_name" for="incident">Incident Type</label></td>
            <td><?php echo $_POST['incidentType'] ?> <input type="hidden" name="incidentType" id="incidentType" value="<?php echo $_POST['incidentType']?>"> 
        
        </td>
            </tr>
          
            
            
            
            <tr>
                <td><label class="label_name" for="incident">Incident Type</label></td>
                <td><textarea class="inputcss" name="incident Desc" cols="45" rows="5" readonly id="incidentDesc"><?php echo $_POST['incidentDesc']?> </textarea>
            <input name="incidentDesc" type="hidden" id="incidentDesc" value="<?php echo $_POST['incidentDesc']?>"></td>
            </tr>
          
        
        
        </table>
            
            </div>
            </div>
    <?php 
// connect to a database
require_once 'db_config.php';
	
// create database connection
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
// check connection
if($mysqli->connect_errno) 
{
	die("Failed to connect to MySQL: ".$mysqli->connect_errno);
}

// retrieve from patrolcar table those patrol cars that are 2:Patrol or 3:Free
$sql = "SELECT patrolcarId, statusDesc FROM patrolcar JOIN patrolcar_status
ON patrolcar.patrolcarStatusId=patrolcar_status.StatusId
WHERE patrolcar.patrolcarStatusId='2' OR patrolcar.patrolcarStatusId='3'";

	if (!($stmt = $mysqli->prepare($sql)))
	{
		die("Prepare failed: ".$mysqli->errno);
	}
	if (!$stmt->execute())
	{
		die("Cannot run SQL command: ".$stmt->errno);
	}
	if(!($resultset = $stmt->get_result()))
	{
		die("No data in resultset: ".$stmt->errno);
	}
	
	$patrolcarArray; // an array variable
	
	while  ($row = $resultset->fetch_assoc()) 
	{
		$patrolcarArray[$row['patrolcarId']] = $row['statusDesc'];
	}
                   
	
	$stmt->close();
	$resultset->close();
	$mysqli->close();
	?>
   
        
<div class="container white">
         
<table id="t01" border="0" align="center"> 
<h1>Dispatch Patrol Car Panel</h1>
<br><br>          
        <?php 
            foreach($patrolcarArray as $key=>$value){ 
?> 
    <tr> 


    <td width="50%"><input type="checkbox" name="chkPatrolcar[]" value="<?php echo $key?>">
    </td>
    <td class="patrolcar_num"><?php echo $key ?></td>
    <td class="patrolcar_status"><?php echo $value ?></td>
        
    </tr> <?php } ?> 
            </table>
        <table align="center">
    <tr>

    <br><br>
    <td><input class="btn" type="reset" name="btnCancel" id="btnCancel" value="Reset"> </td>
    <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  class="btn" type="submit" name="btnDispatch" value="Dispatch">
</td>
        </tr>                                                              
        </table>
    </form>
    </fieldset>
    </div>

</body>
</html>