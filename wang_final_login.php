<?php
 
// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// Website login
session_start();
?>

<!DOCTYPE html>

<html>
<head>
    <title> StudySpace login </title>
    <link rel="stylesheet" href="wang_final_style.css">
    <meta name="description" content="Study better together!"> 
</head>
<body>
	<h1> Login to StudySpace </h1>
	<p id="errormsg"> </p>
	<form method="post" action="wang_final_login.php">
	Username: 
	<input type="text" name="username" value=""> <br/>
    Password:
    <input type="password" name="password" value=""> <br/>
	<input type="submit" value="Log in"> 
	</form>
	<p>
		Don't have an account? <a href="wang_final_signup.php"> Sign up here! </a> 
	</p>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$username = $_POST["username"];
	$password = hash("sha256", $_POST["password"]);
	validate($username, $password);	 
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

// Checks to see if the user has entered a valid username/password combo
function validate($username, $password)
{
	$db = setUpDB();	
	$query = "SELECT password FROM awang_students s WHERE s.username = :username";
	$statement = $db->prepare($query);
	$statement->execute(array('username'=>$username));
	$results = $statement->fetchAll();
		
	if(empty($_POST["username"]) || empty($_POST["password"]))
	{
?>
    	<script> document.getElementById("errormsg").innerHTML="Error: forgot to enter username or password" </script> <!--changes tag text in HTML -->
<?php
	}  

	else if(empty($results) || $results[0]["password"]!=$password) //Short circuit eval!
	{
?>
		<script> document.getElementById("errormsg").innerHTML="Error: invalid username or password" </script>
<?php
	}
	else
	{
    	$_SESSION["studygroup_username"] = $username;
?>
		<script>window.location="wang_final_dashboard.php"</script> <!--JavaScript redirecting to dashboard -->
<?php
	}
}
?>
 