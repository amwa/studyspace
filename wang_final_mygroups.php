<?php 

// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// "Mygroups" page

session_start(); 
if($_SESSION["studygroup_username"]==null)
{
?>
	<script> window.location="wang_final_login.php" </script>  <!-- HTML JavaScript redirecting to dashboard -->
<?php
}
$db = setUpDB();	
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$username = $_SESSION["studygroup_username"];
$query = "SELECT id FROM awang_students s WHERE s.username = :username"; //convert username to student id
$statement = $db->prepare($query);
$statement->execute(array('username'=>$username));
$results = $statement->fetchAll();
$studentID = $results[0]["id"];
?>

<!DOCTYPE html>

<html>
<head>
    <title> StudySpace groups </title>
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
	
	<form method="post" action="wang_final_mygroups.php">
	<input type="hidden" name="whichform" value="whichgroups">
	<input type="radio" name="grouptype" value="mygroups" checked> My groups
	<input type="radio" name="grouptype" value="findgroups"> Find a group
	<input type="submit" value="Go">
	</form>
	<table>
		<tr> <th> Group Name </th> <th> Group Creator </th> </tr>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{ 	
		if($_POST["whichform"]=="logout")
		{
			$_SESSION["studygroup_username"] = null; 
	?>
		<script> window.location="wang_final_login.php" </script> <!-- HTML JavaScript redirecting to login -->
	<?php  
		}
		else if($_POST["whichform"]=="whichgroups")
		{
			$groupType = $_POST["grouptype"];
			printGroups($groupType, $studentID);
		}
	}
?>
	</table>

	<form method="post" action="wang_final_mygroups.php">
	<input type="hidden" name="whichform" value="logout">
	<input type="submit" value="Log out">
	</form>
</body>
</html>

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

function printGroups($groupType, $studentID)
{
	$db = setUpDB();	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
	$query = "";
	$statement = "";
	$results = "";
	if($groupType=="mygroups") //If statement deciding which groups to show: "My groups" or "All groups"
	{
		$query = "SELECT group_id FROM awang_student_groups s WHERE s.student_id = :student_id;";
		$statement = $db->prepare($query);
		$statement->execute(array('student_id'=>$studentID));
		$results = $statement->fetchAll(); //All the groups the student belongs to
		for ($ii = 0; $ii < count($results); $ii++) //Goes through each column of results, getting reminders from all groups the student belongs to
		{
			$query = "SELECT * FROM awang_groups g WHERE g.id = :id";
			$statement = $db->prepare($query);
			$statement->execute(array('id'=>$results[$ii]["group_id"])); 
			$groupInfo = $statement->fetchAll();
			for ($jj = 0; $jj < count($groupInfo); $jj++) //Fill in the table of group names
			{
				echo ("<tr> <td> <a href=\"wang_final_grouppage.php?whichgroup=");
				echo $groupInfo[$jj]["id"];
				echo ("\">");
				echo $groupInfo[$jj]["name"];
				echo ("</a> </td> <td>");
				echo $groupInfo[$jj]["creator"];
				echo ("</td> </tr>");
			}			
		}
	}
	else
	{
		$query = "SELECT * FROM awang_groups";
		$statement = $db->prepare($query);
		$statement->execute();
		$results = $statement->fetchAll();
		for ($ii = 0; $ii < count($results); $ii++) 
		{
			echo ("<tr> <td> <a href=\"wang_final_grouppage.php?whichgroup=");
			echo $results[$ii]["id"];
			echo ("\">");
			echo $results[$ii]["name"];
			echo ("</a> </td> <td>");
			echo $results[$ii]["creator"];
			echo ("</td> </tr>");
		}
	}	
}

?>