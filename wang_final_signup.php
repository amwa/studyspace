<?php 
// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// Website dashboard
session_start(); 
?>

<!DOCTYPE html>

<html>
<head>
    <title> StudySpace signup </title>
    <link rel="stylesheet" href="wang_final_style.css">
    <meta name="description" content="Study better together!"> 
</head>
<body>
	<p id="errormsg"> </p>
	<form method="post" action="wang_final_signup.php">
	Enter username: 
	<input type="text" name="username" value="" maxlength="20"> <br/>
    Enter name:
    <input type="text" name="firstname" value="" maxlength="20"> <br/>
    Enter password:
    <input type="password" name="password" value=""> <br/>
    Confirm password:
    <input type="password" name="passwordconfirm" value=""> <br/>
	<input type="submit" value="Create account"> 
	</form>
	<p>
		Already have an account? <a href="wang_final_login.php"> Log in here! </a> 
	</p>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$db = setUpDB();
	$username = trim($_POST["username"]);
	$firstname = trim($_POST["firstname"]);
	$password = hash("sha256", $_POST["password"]);
	$passwordConfirm = hash("sha256", $_POST["passwordconfirm"]);
	if(empty($username) || empty($firstname) || empty($_POST["password"]) || empty($_POST["passwordconfirm"]))
	{
	?>
		<script> document.getElementById("errormsg").innerHTML="Error: not all fields have been filled!" </script> <!--Javascript filling out error message tag in HTML-->	
	<?php
	}  
	else 
	{
		if ($password != $passwordConfirm) 
		{
    ?>
		<script> document.getElementById("errormsg").innerHTML="Error: password improperly confirmed!" </script> <!--Javascript filling out error message tag in HTML-->	
	<?php
		} 
		else
		{
			$_SESSION["studygroup_username"] = $username;
			addAccount($username, $firstname, $password);
		}
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

//Adds account info to data table "students"
function addAccount($username, $firstname, $password) 
{
	try
	{
	$db = setUpDB();
    $query = "INSERT INTO awang_students (username, name, password) VALUES (:username, :name, :password)";
	$statement = $db->prepare($query);
	$statement->execute(array('username'=>$username, 'name'=>$firstname, 'password'=>$password));
	?>
			<script> window.location="wang_final_login.php" </script> <!-- HTML JavaScript redirecting to login -->
	<?php
	}
	catch (Exception $e)
	{
	?>
		<script> document.getElementById("errormsg").innerHTML="Error: username already taken" </script> <!--Javascript filling out error message tag in HTML-->	
	<?php
	}
}
?>