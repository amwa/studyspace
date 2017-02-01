<?php 

// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// "Start a study group" page

session_start(); 
if($_SESSION["studygroup_username"]==null)
{
?>
	<script> window.location="wang_final_login.php" </script>  <!-- HTML JavaScript redirecting to dashboard -->
<?php
}
?>

<!DOCTYPE html>

<html>
<head>
    <title> StudySpace start a group </title>
    <link rel="stylesheet" href="wang_final_style.css">
    <meta name="description" content="Study better together!"> 
</head>
<body>

	<div id="menu">
	<ul>
		<li> <a href="wang_final_dashboard.php"> Dashboard </a> </li>
		<li> <a href="wang_final_mygroups.php"> Groups </a> </li>
		<li> <a href="wang_final_startgroup.php"> Start a Group </a> </li>
	</ul>
	</div>
	
	<p id="successmsg"></p> <!-- message to be filled in w javascript if necessary -->
	<p>
		Start a group
	</p>
	<form method="post" action="wang_final_startgroup.php">
	<input type="hidden" name="whichform" value="makegroup">
	Enter name: 
	<input type="text" name="groupname" value="" maxlength="20"> <br/>
    Group description:
    <input type="text" name="description" value=""> <br/>
	<input type="submit" value="Start group!"> 
	</form>
	<form method="post" action="wang_final_startgroup.php">
	<input type="hidden" name="whichform" value="logout">
	<input type="submit" value="Log out">
	</form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if($_POST["whichform"]=="makegroup")
	{
		$groupCreator = $_SESSION["studygroup_username"]; //Trim away extra spaces
		$groupName = trim($_POST["groupname"]);
		$groupDescription = $_POST["description"];
		echo $groupCreator;
		
		if(!empty($groupCreator) && !empty($groupName))
		{
			addGroup($groupName, $groupCreator, $groupDescription);
			addUser($groupName, $groupCreator);
?>
		<script> document.getElementById("successmsg").innerHTML="Group added successfully!" </script>
<?php
		}
		else
		{
?>
		<script> document.getElementById("successmsg").innerHTML="Error: invalid input!" </script>
<?php
		}
	}
	else if($_POST["whichform"]=="logout")
	{
		$_SESSION["studygroup_username"] = null; 
	?>
	<script> window.location="wang_final_login.php" </script> <!-- HTML JavaScript redirecting to login -->
	<?php  
	}
}

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

//Adds group to group table
function addGroup($groupName, $groupCreator, $groupDescription)
{
	$db = setUpDB();
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO awang_groups (name, creator, about, active) VALUES (:name, :creator, :about, 1)";
    $statement = $db->prepare($query);
    $statement->execute(array('name'=>$groupName, 'creator'=>$groupCreator, 'about'=>$groupDescription)); //inserts a new row into the array containing info about groups
}

//Links the user to a group
function addUser($groupName, $groupCreator)
{
	require("wang_final_config.php");
	try 
	{
		$db = new PDO("mysql:dbname=" . $GLOBALS["database"] . 
		";host=" . $GLOBALS["hostname"] . ";port=" . $GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);	
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
    	$query = "SELECT id FROM awang_students s WHERE s.username = :username";
		$statement = $db->prepare($query);
		$statement->execute(array('username'=>$groupCreator));
		$studentIDResults = $statement->fetchAll(); 
		$studentID = $studentIDResults[0]["id"];//gets the id of the student
		
		$query = "SELECT id FROM awang_groups g WHERE g.name = :name";
		$statement = $db->prepare($query);
		$statement->execute(array('name'=>$groupName));
		$groupIDResults = $statement->fetchAll(); 
		$groupID = $groupIDResults[0]["id"]; //gets the id of the group
    
    	$query = "INSERT INTO awang_student_groups (student_id, group_id) VALUES (:student_id, :group_id);";
    	$statement = $db->prepare($query);
    	$statement->execute(array('student_id'=>$studentID, 'group_id'=>$groupID)); //puts the student and group ids into a new row in the student_groups table
   }
   catch (PDOException $ex)
   {
   		echo ("Sorry, a database error occurred.");
		echo ("Error details: " . $ex->getMessage());
   }
}
?>