<?php
//https://walsh37.uwmsois.com/bcr/staff_login/login.php

// Send NOTHING to the Web browser prior to the session_start() line!
// Check if the form has been submitted.
if (isset($_POST['submitted'])) {
	
	require_once ('../mysqli_connect.php'); // Connect to the db.
	
	$errors = array(); // Initialize error array.
	// Check for a staff number.
	if (empty($_POST['staffNum_logged_in'])) {
		$errors[] = 'Please enter Staff Number.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['staffNum_logged_in']));
	}
	// Check for a password.
	if (empty($_POST['staffPass'])) {
		$errors[] = 'You forgot to enter your password.';
	} else {
		$p = mysqli_real_escape_string($dbc, $_POST['staffPass']);
	}
	if (empty($errors)) { // If everything's OK.
		/* Retrieve the staffNum, staffFirstName, and staffLastName for 
		that staffNum_logged_in/password combination. */

		$query = "SELECT * FROM employees WHERE staffNum='$e' AND staffPass='$p'"; 
		$result = mysqli_query ($dbc, $query); // Run the query.
		$row = mysqli_fetch_array($result, MYSQLI_NUM);							
		if ($row) { // A record was pulled from the database.
			//Set the session data:
			session_start(); 
			$_SESSION['staffNum_logged_in'] = $row[0];
			$_SESSION['staffFirstName'] = $row[2];
			$_SESSION['staffLastName'] = $row[1];
			$_SESSION['position'] = $row[3];
			
			// Redirect:
			//echo '</p><p>staffNum</p>';
			header("Location:loggedin.php");
			exit(); // Quit the script.
		} else { // No record matched the query.
			$errors[] = 'The Staff Number and/or password entered do not match those on file.'; // Public message.
		}
	} // End of if (empty($errors))
	mysqli_close($dbc); // Close the database connection.***
} else { // Form has not been submitted.
	$errors = NULL;
} // End of the main Submit conditional.

// Begin logged in page
$page_title = 'Login';

if (!empty($errors)) { // Print any error messages.
	echo '<h1 id="mainhead">Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) { // Print each error.
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Create the form.
?>
<html>


<head>
		
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<body>
<img id="logo" src="bcr_icon.jpg" alt="Brew City Rental" title="Brew City Rental" /><br>

<h3>Please, login here.</h3>
<form autocomplete="off" action="login.php" method="post" autocomplete="off">
<!-- <p>Staff Number: <input type="text" name="staffNum" size="20" maxlength="40" /> </p> -->
<p>Staff Number: <input type="text" name="staffNum_logged_in" size="20" maxlength="40" /> 

<p>Password: <input type="password" name="staffPass" size="20" maxlength="20" /></p>
<p><input type="submit" name="submit" value="Login" /></p>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</body>
