<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Service System</title>
<link href="style.css" rel="stylesheet" type="text/css">
          
</head>

<body> 
    
    <script> function validation() 
        { 
            var x=document.forms["frmLogCall"]["callerName"].value; 
            if (x==null || x=="") 
        { 
alert("Caller Name is required.");
return false; 
                                               }
                                               }
// may add code for validating other inputs 
            
        function validation() {
		var x = document.forms["frmLogCall"]['description'].value;
		if(x == "") {
			alert("Description Must Be Filled Out!");
			return false;
		}
	}
        
</script> 

    
    
    
    <?php require 'nav.php';?> 
    <?php require 'db_config.php'; 
    
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE); 
if ($mysqli->connect_errno) 
{ 
die("Failed to connect to MySQL: ".$mysqli->connect_errno); 
} 
    
    
    
    $sql = "SELECT * FROM incidenttype"; 
if (!($stmt = $mysqli->prepare($sql))) 
{
die("Prepare failed: ".$mysqli->errno); 
} 

    if (!$stmt->execute()) 
    {
die("Cannot run SQL command: ".$stmt->orrno); 
} 
    //Check any data in resultset 
    
    if (!($resultset = $stmt->get_result())) {
        die("No data in resultset: ".$stmt->errno); 
    }

    $incidentType;
    
    while ($row = $resultset->fetch_assoc()) { 
        $incidentType[$row['incidentTypeId']] = $row['incidentTypeDesc']; 
} 
    $stmt->close(); 
    
    $resultset->close(); 
    
    $mysqli->close(); 

    ?>
    
    <div class="container white">
    <form name="frmLogCall" method="post" action="dispatch.php" onSubmit="return validate();">
      <div class="title">
        <h1>Log Call</h1>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="label_name" for="callername">Caller's Name</label>
          <input type="text" class="form-control inputcss" name="callerName" id="callerName" placeholder="Caller Name">
        </div>
        <div class="form-group col-md-6">
          <label class="label_name" for="contacntno">Contact Number</label>
          <input type="number" class="form-control inputcss" name="contactNo" id="contactNo" placeholder="Contact No.">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label class="label_name" for="location">Location</label>
          <input type="text" class="form-control inputcss" name="location" id="location placeholder=" Location">
        </div>
        <div class="form-group col-md-6">
          <label class="label_name" for="incident">Incident Type</label>
          <select name="incidentType" id="incidentType" class="form-control inputcss">
            <?php foreach ($incidentType as $key => $value) { ?>
              <option value="<?php echo $key ?> ">
                <?php echo $value ?> </option>
            <?php } ?>

          </select>
        </div>
        <div class="form-group col-md-12">
          <label class="label_name" for="desc">Description</label>
          <textarea placeholder="Input description here" class="form-control inputcss" name="incidentDesc" id="incidentDesc" rows="3"></textarea>
        </div>
      </div>

      <button class="btn" type="reset">Reset</button>
      <button class="btn" type="submit">Process Call</button>

    </form>
  </div>
</body>
</html>
