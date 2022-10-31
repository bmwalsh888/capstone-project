<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

$alert = "<script>alert('Transaction Successful.');</script>";
echo $alert;
					
?>



<html>

<head>
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<body>

                          <!-- header stuff -->
<div> 

	<img id="logo" src="bcr_icon.jpg" alt="Brew City Rental" title="Brew City Rental" /><br>

<h5></h5>

	<!--Right Corner Info -->
	<h5 id="welcome_banner"><?php echo $empname; ?></h5>

	
	<!-- LOG OUT -->
	<div id="banner_logout">
		<ul>
			<li><a href="logout.php">Log off</a></li>
		</ul>
	</div>
	        
</div>		
		



<h1 id='page_title_checkout'>DVD Check Out & Transactions</h1>

<div id="to_rental">
	<ul>
		<li><a id="nounderline" href="cust_lookup_purchase.php"><span><strong>Another Check Out</strong></span></a></li>
	</ul>
</div>
	
	
<div id="to_switch">
	<ul>
		<li><a id="nounderline" href="loggedin.php"><span><strong>Back to Switchboard</strong></span></a></li>
	</ul>
</div>

<!--  FOOTER  -->
<div id="footer">
	<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
</div>
	
</body>
</html>