<?php 

// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// Website group page 

session_start(); 
if($_SESSION["studygroup_username"]==null)
{
?>
	<script> window.location="wang_final_login.php" </script>  <!-- HTML JavaScript redirecting to dashboard -->
<?php
}
//The big chunk of PHP below is included before the start of the HTML, as it establishes a lot of variables that will be utilized later in the code
$db = setUpDB(); 
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = "SELECT id FROM awang_students s WHERE s.username = :username"; 
$statement = $db->prepare($query);
$statement->execute(array('username'=>$_SESSION["studygroup_username"]));
$results = $statement->fetchAll();
$studentID = $results[0]["id"]; //Convert username to student id
$groupID = $_GET["whichgroup"]; 
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$db = setUpDB();
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($_POST["whichform"]=="joingroup")
	{
		$query = "";
		if(!isInGroup($studentID, $groupID))
		{
			$query = "INSERT INTO awang_student_groups (student_id, group_id) VALUES (:student_id, :group_id)";
		}
		else
		{
			$query = "DELETE FROM awang_student_groups WHERE student_id = :student_id AND group_id = :group_id";
		}
		$statement = $db->prepare($query);
        $statement->execute(array('student_id'=>$studentID, 'group_id'=>$groupID));
	}
	if($_POST["whichform"]=="addreminder")
	{
		if(empty($_POST["reminder"]) || empty($_POST["duedate"]))
		{
    		echo "<p style=\"margin-left:300px;\"> Error: not all fields have been filled! </p>";
		} 
		else
		{
			$reminder = $_POST["reminder"];
			$dueDate = $_POST["duedate"];
			$query = "INSERT INTO awang_group_reminders VALUES (null, :reminder, :group_id, :due_date, 0, :added_by)";
			$statement = $db->prepare($query);
        	$statement->execute(array('reminder'=>$reminder, 'group_id'=>$groupID, 'due_date'=>$dueDate, 'added_by'=>$_SESSION["studygroup_username"]));
		}
	}
	if($_POST["whichform"]=="logout")
	{
		$_SESSION["studygroup_username"] = null; 
	?>
	<script> window.location="wang_final_login.php" </script> <!-- HTML JavaScript redirecting to login -->
	<?php  
	}
}
?>

<!DOCTYPE html>

<html>
<head>
    <title> StudySpace group </title>
    <link rel="stylesheet" href="wang_final_style.css">
    <meta name="description" content="Study better together!"> 
</head>
<body>
	<a href="wang_final_dashboard.php"> Back to Dashboard </a> </li>
	<h1 id="groupname"> </h1> <!-- to be filled in w js -->
	<?php
	echo "<form method=\"post\" action=\"wang_final_grouppage.php?whichgroup=" . $groupID . "\" >";
	?>
	<input type="hidden" name="whichform" value="joingroup">
	<?php
		echo "<input type=\"submit\" value=";
		if(!isInGroup($studentID, $groupID))
		{
			echo "\"Join group\"";
		}
		else
		{
			echo "\"Leave group\"";
		}
		echo " />";
	?>
	</form>	
	<?php
		generateInfo($groupID);
	?>
	<h3> Reminders </h3>
	<table>
	<tr> <th> Reminder </th> <th> Date due </th> <th> Added by </th> <th> Done? </th> </tr> 
	<?php
		if(isInGroup($studentID, $groupID))
		{
			printReminders($groupID);
		}
	?>
	</table>
	<?php
	if(isInGroup($studentID, $groupID))
	{
		echo "<p> Add a reminder? </p> ";
		echo "<form method=\"post\" action=\"wang_final_grouppage.php?whichgroup=" . $groupID . "\" >"; 
	?>
		<input type="hidden" name="whichform" value="addreminder">
		<input type="text" name="reminder" size="50">
		<input type="datetime-local" name="duedate" value="Due?">
		<input type="submit" value="Add reminder!" />
		</form>
	<?php
		echo "<form method=\"post\" action=\"wang_final_grouppage.php?whichgroup=" . $groupID . "\" >"; 
	}
	else
	{
		echo "<p> Join the group to view and add reminders! </p>";
	}
	?>
	<input type="hidden" name="whichform" value="logout">
	<input type="submit" value="Log out">
	</form>
</body>
</html>
	
<script> //Javascript to handle checkbox clicks - uses AJAX to talk to the MySQL server
	function checkBox(id)
	{
		var xhttp = new XMLHttpRequest();
		var isChecked = document.getElementById("checkbox" + id).checked;
		xhttp.open("GET", "wang_final_done.php?id=" + id + "&ischecked=" + (isChecked ? 1:0), true);
		xhttp.send();
	}
</script>
	
<?php

function setUpDB()
{
	if($db==null)
	{
		require("wang_final_config.php");
		try
		{
			$db = new PDO("mysql:dbname=" . $GLOBALS["database"] . 
			";host=" . $GLOBALS["hostname"] . ";port=" . $GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);	
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		}
		catch (PDOException $ex)
		{
	 		echo ("Sorry, a database error occurred.");
			echo ("Error details: " . $ex->getMessage());
		}
	}
}

function isInGroup($studentID, $groupID)
{	
	$db = setUpDB();
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT student_id FROM awang_student_groups s WHERE s.group_id = :groupID";
	$statement = $db->prepare($query);
	$statement->execute(array('groupID'=>$groupID));
	$results = $statement->fetchAll();
	$isInArray = false;
	for($ii=0; $ii<count($results); $ii++) //for loop checking for the user id in each array element within "results"
	{
		if(in_array($studentID, $results[$ii]))
		{
			$isInArray = true;
		}
	}
	return $isInArray;
}

function printReminders($groupID)
{
	$db = setUpDB();	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
	$query = "SELECT * FROM awang_group_reminders r WHERE r.group_id = :groupID"; 
	$statement = $db->prepare($query);
	$statement->execute(array('groupID'=>$groupID));
	$results = $statement->fetchAll();
	for ($ii = 0; $ii < $statement->rowCount(); $ii++) //Fill in the table of group names
	{
		echo ("<tr> <td>");
		echo $results[$ii]["reminder"];
		echo ("</td> <td>");
		echo $results[$ii]["due_date"];
		echo ("</td> <td>");
		echo $results[$ii]["added_by"];
		echo ("</td> <td>");
		echo ("<input type=\"checkbox\" onclick=\"checkBox(" . $results[$ii]["id"] . ")\" id=\"checkbox" . $results[$ii]["id"] .  "\" value=\"done\" ");
		if ($results[$ii]["done"])
		{
			echo "checked"; //Sets the checkbox input to "checked" if complete
		}
		echo ("> </td> </tr>");
	}
}

//Prints the "about" section for the group page
function generateInfo($groupID)
{
	$db = setUpDB();	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
	$query = "SELECT * FROM awang_groups r WHERE r.id = :groupID"; 
	$statement = $db->prepare($query);
	$statement->execute(array('groupID'=>$groupID));
	$results = $statement->fetchAll();
	echo "<h1>" . $results[0]["name"] . "</h1>";
	echo "<p id=\"about\">" . $results[0]["about"] . "</p>";
}

?>