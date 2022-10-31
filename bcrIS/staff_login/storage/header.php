<html>

<head>
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<body>

<div id="header"> <!-- id not in use -->
<!--
	<img id="logo" src="bcr_icon.jpg" alt="Brew City Rental" title="Brew City Rental" /><br>
-->

<?php
session_start();
// Echo session variables set on previous page for employee name
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

?>
<h5><?php //echo $_SESSION['staffFirstName']; ?></h5>

	<!--Right Corner Info -->
	<h5 id="welcome_banner"><?php echo $empname; ?></h5>

	
	<!-- LOG OUT -->
	<div id="banner_logout">
		<ul>
			<li><a href="logout.php">Log off</a></li>
		</ul>
	</div>
	        
</div>			
		
</body>

<?php

echo "PAGE";



//session_start();
//echo "in page direct";
//echo $_SESSION["body"];
//if (isset($_SESSION['body'])) {
//

	if ($_SESSION["body"] = "switch") {
				require ('switch_manager.php');
		} elseif ($_SESSION["body"] = "addemp"){
				include ('add_employee.php');
		} else {
			echo "shouldn't be here";
		}

			//echo "shouldn't be here";
//		if (isset($_POST['position'])) {
//			if($_SESSION['position'] = "gmanager") {
//				echo "this is manager";


//			echo "not manager";
//			echo "this is not manager";
			//require ('switch.php');
			
//		}
//	}
	
//} elseif ($_SESSION["body"] = "add_employee") {
//	echo "this is add employee";
	
//} else {
//	
//	include ('y.php');

//}
?>