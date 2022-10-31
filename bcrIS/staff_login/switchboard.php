<?php
session_start();
// Echo session variables set on previous page for employee name
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

?>


<html>

<head>
	<link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<body>

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
		




	<h1 id="page_title">Switchboard</h1>



<p id="sw_col1"><b>Employee Resources</b></p>
<p id="sw_col2"><b>Search/Report Options</b></p>
<p id="sw_col3"><b>Manager Resources</b></p>


	<div id="staff1">
		<ul>
			<li><a id="nounderline" href="cust_lookup_purchase.php"><span>Client Checkout</span></a></li>
			<li><a id="nounderline" href="return.php"><span>DVD Check in</span></a></li>
			<li><a id="nounderline" href="new_client.php"><span>Add New or Edit Client</span></a></li>
		</ul>
	</div>

	<div id="staff2">
		<ul>
			<li><a id="nounderlineRed" href=""><span>Title Search</span></a></li>
			<li><a id="nounderlineRed" href=""><span>DVD Search</span></a></li>
			<li><a id="nounderlineRed" href=""><span>Client Search</span></a></li>
			<li><a id="nounderlineRed" href=""><span>Purchases Search</span></a></li>
			<li><a id="nounderlineRed" href=""><span>Employee Search</span></a></li>
			<li><a id="nounderline" href="custom_reports.php"><span>Custom Report</span></a></li>
		</ul>
	</div>
		
	

<div class="sw_help1">
<img id="sw_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>These are the standard day-to-day tasks<br><br>
		CLIENT CHECKOUT is for all transactions<br><br>
		DVD CHECK IN for returning rented DVDs<br><br>
		ADD NEW CLIENT is for adding new clients and editting existing accounts
		<br><br>
		NOTE:<br>All are included in the prototype.
		</div></div>

<div class="sw_help2">
<img id="sw_help2_icon" src="help_icon.jpg" alt="Help"></img>
<div>These are common-use reports.<br><br>
		TITLE SEARCH is for movie information<br><br>
		DVD SEARCH is for DVD information<br><br>
		CLIENT SEARCH is for client oriented information<br><br>
		PURCHASES SEARCH is for transaction details<br><br>
		EMPLOYEE SEARCH is for staff information<br><br>
		CUSTOM REPORT is for a full customized search of the system information
		<br><br>NOTE:<br>Only CUSTOM REPORT is active in the prototype and it has been limited to vewing EVENT LOGS and TRANACTION HISTORY
		</div></div>

<div class="sw_help3">
<img id="sw_help3_icon" src="help_icon.jpg" alt="Help"></img>
<div>These tools are only visible to manager accounts.
		</div></div>

<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
	</div>
</body>

