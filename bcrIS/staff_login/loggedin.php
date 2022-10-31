<?php
session_start(); 

$check = $_SESSION['position'];

if ($check == "manager") {
		include ('switchboard_manager.php');
	} else {
		include ('switchboard.php');
	}

?>




