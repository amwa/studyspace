<?php

// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// Website dashboard

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
    <title> StudySpace dashboard </title>
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
	
	<p>
		My Dashboard 
	</p>
	<p>
		Reminders
	</p>
	<table>
		<tr> <th> Reminder </th> <th> Group </th> <th> Due date </th> <th> Done </th> </tr>
	<?php
		printReminders();
	?>
	</table>
	<form method="post" action="wang_final_dashboard.php">
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
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	if($_POST["whichform"]=="logout")
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

function printReminders()
{
	$db = setUpDB();	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$username = $_SESSION["studygroup_username"];
	$query = "SELECT id FROM awang_students s WHERE s.username = :username"; //convert username to student id
	$statement = $db->prepare($query);
	$statement->execute(array('username'=>$username));
	$studentIDResults = $statement->fetchAll();
	$studentID = $studentIDResults[0]["id"]; 
	//The Scary Query below selects all of the relevant reminders, due dates, "done" booleans and group names and joins them based on the id of the group they belong to
	$query = "SELECT reminders.due_date, reminders.done, reminders.reminder, groups.name, reminders.group_id, reminders.id ".
				 	"FROM awang_group_reminders AS reminders ".
						"JOIN (awang_groups AS groups) ". 
    					"JOIN (awang_student_groups AS studentgroups) ".
							"WHERE groups.id=reminders.group_id AND groups.id=studentgroups.group_id AND studentgroups.student_id=:student_id ".
				    "ORDER BY reminders.due_date";
	$statement = $db->prepare($query);
	$statement->execute(array('student_id' => $studentID)); 
	$reminderResults = $statement->fetchAll(); 
	for ($ii = 0; $ii < count($reminderResults); $ii++) //Fills in the table of reminders with all relevant info
	{
		echo ("<tr> <td>");
		echo $reminderResults[$ii]["reminder"];
		echo ("</td> <td>");
		echo ("<a href=\"wang_final_grouppage.php?whichgroup=" . $reminderResults[$ii]["group_id"] . "\">"); 
		echo $reminderResults[$ii]["name"] . "</a>";
		echo ("</td> <td>");
		echo $reminderResults[$ii]["due_date"];
		echo ("</td> <td>");
		echo ("<form method=\"post\" action=\"wang_final_dashboard.php" . $groupID . "\" >");
		echo ("<input type=\"hidden\" name=\"whichform\" value=\"isdone\">");
		echo ("<input type=\"checkbox\" onclick=\"checkBox(" . $reminderResults[$ii]["id"] . ")\" id=\"checkbox" . $reminderResults[$ii]["id"] . "\" value=\"done\" ");
		
		if ($reminderResults[$ii]["done"])
		{
			echo "checked"; //Sets the completed tasks to "checked"
		}
		echo ("> </form>");
		echo ("</td> </tr>");
		
	} 
}

?>