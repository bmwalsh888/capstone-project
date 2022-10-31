<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

require ('../mysqli_connect.php');

// Identified Account
if (isset($_POST['submitted_account'])) {  // Check if the form has been submitted & set conditional for query
	if (empty($_POST['custNum_account'])) {
		$alert = "<script>alert('CLIENT LOOK UP:   No account number enterred');</script>";
		echo $alert;
	} else {
		$q = "SELECT verified FROM clients WHERE custNum ='".$_POST['custNum_account']."'";
		$r = @mysqli_query ($dbc, $q);   //the query
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
		$num_rows = mysqli_num_rows($r);  // checking if zero rows!

		if ($num_rows ==0 ) {
			$alert = "<script>alert('CLIENT LOOK UP:   Account number not found');</script>";
			echo $alert;
		} else{
			foreach($result as $line){
				$ver = $line['verified'];
			}

			if ($ver == "no") {
				$alert = "<script>alert('CLIENT LOOK UP:   The Client has not been verified to rent.');</script>";
				echo $alert;
			} else {
				$_SESSION['custNum_checkout'] = $_POST['custNum_account'];  // this is setup as one checkout station & ***this variable tracks the sale occuring
				header("Location:purchase.php");  //goto new page
				
			}
		}

	}
}


$conditional = "";


// Search fields checked when Submit
if (isset($_POST['submitted'])) {  // Check if the form has been submitted & set conditional for query

	$conditional = " WHERE ";
	$linker = 0;
	
	if (!empty($_POST['custNum'])) {
		$conditional = $conditional . "custNum = '" . $_POST['custNum'] . "'";     
		$linker = 1;
	}

	if (!empty($_POST['lname'])) {
		if ($linker == 0) {
			$conditional = $conditional . "lastName = '" . $_POST['lname'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND lastName = '" . $_POST['lname'] . "'";  
		} 
	}
	
	if (!empty($_POST['fname'])) {
		if ($linker == 0) {
			$conditional = $conditional . "firstName = '" . $_POST['fname'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND firstName = '" . $_POST['fname'] . "'";   
		} 
	}
	
	if (!empty($_POST['email'])) {
		if ($linker == 0) {
			$conditional = $conditional . "email = '" . $_POST['email'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND email = '" . $_POST['email'] . "'";   
		} 
	}
	
	if (!empty($_POST['phone'])) {
		if ($linker == 0) {
			$conditional = $conditional . "phone = '" . $_POST['phone'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND phone = '" . $_POST['phone'] . "'";   
		} 
	}
	
	if (!empty($_POST['address'])) {
		if ($linker == 0) {
			$conditional = $conditional . "address = '" . $_POST['address'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND address = '" . $_POST['address'] . "'";   
		} 
	}
	
		
	if ($linker == 0) {$conditional = "";} //empties the conditional declaration if search fields emptied

}




// THE SEARCH QUERY & REPORTING



$q = "SELECT custNum, lastName, firstName, verified, email, phone, address, feesDue, feeTotalEver FROM clients " . $conditional;  //the query statement

$r = @mysqli_query ($dbc, $q);   //the query

$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array


$num_rows = mysqli_num_rows($r);  // checking if zero rows! if not print the results to table
if ($num_rows ==0 ) {} else{

	$info = "";
	if ($result ==0) {$info = "No results found using these filter settings.";}
	else {
		$display_contents = "<table id='emp_results_table'>";
		$display_contents = $display_contents . "<tr id='col_lab'><td>" . "Number" . "</td><td>" . "Last Name" .  "</td><td>" . "First Name" . "</td><td>" . "Verified?" . "</td><td>" . "Email" . "</td><td>" . "Phone" . "</td><td>" . "Address" . "</td></tr>";

		foreach($result as $line){
			$display_contents = $display_contents . "<tr><td>" . ($line['custNum']) . "</td><td>" . ($line['lastName']) .  "</td><td>" . ($line['firstName']) . "</td><td>" . ($line['verified']) . "</td><td>" . ($line['email']) . "</td><td>" . ($line['phone']) . "</td><td>" . ($line['address']) . "</td></tr>";
		}

	$display_contents = $display_contents . "</table>";
	$info = $display_contents;
	}
}



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
		

<h1 id='page_title_checkOut'>DVD Check Out & Transactions</h1>

<div id="body">

<h2 id='page_title_checkOut_subtitle'>Customer Account Look Up</h2>


<h4 id=''>Customer Filters:</h4>

<form method="post">

<label for="custNum">Client Number:</label><br>
<input type="text" id="custNum" name="custNum" value="<?php if(!empty($_POST['custNum'])) echo $_POST['custNum'];?>"><br><br>
<label for="lname">Last name:</label><br>
<input type="text" id="lname" name="lname" value="<?php if(!empty($_POST['lname'])) echo $_POST['lname'];?>"><br><br>
<label for="fname">First name:</label><br>
<input type="text" id="fname" name="fname" value="<?php if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label for="email">Email:</label><br>
<input type="text" id="email" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>"><br><br>
<label for="phone">Phone:</label><br>
<input type="text" id="phone" name="phone" value="<?php if(!empty($_POST['phone'])) echo $_POST['phone'];?>"><br><br>
<label for="address">Address:</label><br>
<input type="text" id="address" name="address" value="<?php if(!empty($_POST['address'])) echo $_POST['address'];?>"><br>
<br><br>
  <input type="submit" value="Submit">
  <input type="hidden" name="submitted" value="TRUE" />
</form>


<!-- RESULTS DISPLAY -->
<div id="rent_find_cust_dispaybox"> <!-- return_dispaybox -->
<?php echo $info; ?>
</div>

<form method="post">
	<label id='cust_account_lable'><strong>Enter Customer Account Number:</strong></label>
	<input id="cust_account_input" type="text" name="custNum_account" value="<?php if(!empty($_POST['custNum_account'])) echo $_POST['custNum_account'];?>">
	<input id="cust_account_submit" type="submit" value="Submit">
	<input type="hidden" name="submitted_account" value="TRUE" />
</form>




















<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
		
	</div>
	
</div>
</body>