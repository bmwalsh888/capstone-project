<?php 
// This page lets the user logout.
session_start(); 

// If no session variable exists, redirect the user.
if (isset($_SESSION['staffNum'])) {
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
}

//redirect to login page
include ('login.php');
?>