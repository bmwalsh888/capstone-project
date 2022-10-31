<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}


$conditional = ""; //set conditional for default query view



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
	
	if ($_POST['verified'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . "verified = '" . $_POST['verified'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND verified = '" . $_POST['verified'] . "'";   
		} 
	}
		
	if ($linker == 0) {$conditional = "";} //empties the conditional declaration if search fields emptied

}



// THE SEARCH QUERY & REPORTING

require ('../mysqli_connect.php');

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



// EDITTING & ADDING EMPLOYEE DATA

if (isset($_POST['submitted_edit'])) {  // Check if the edit form has been submitted
	if (empty($_POST['custNum_inp'])) {  // THIS WAY TO ADD NEW
		if ((empty($_POST['lname_inp'])) || (empty($_POST['fname_inp']))  || (empty($_POST['verified_inp'])) || (empty($_POST['email_inp']))  || (empty($_POST['phone_inp'])) || (empty($_POST['address_inp'])) ){   //add new client
			$alert = "<script>alert('ADDING NEW CLIENT:                                                                       All fields are required except Client Number.  New recond not created.');</script>";
			echo $alert;
		} else {    //proceed with values enterred
			if (($_POST['verified_inp'] != "yes") and ($_POST['verified_inp'] != "no")) { //check if "position" options enterred correctly
				$alert = "<script>alert('ADDING NEW CLIENT:                                                                       Verification options are:      yes      OR      no                   New record not created because required field missing.');</script>";
				echo $alert;
			} else {
					$edit_query_txt = "INSERT INTO clients (lastName,firstName,verified,email,phone,address) VALUES ('".$_POST['lname_inp']."','".$_POST['fname_inp']."','".$_POST['verified_inp']."','".$_POST['email_inp']."','".$_POST['phone_inp']."','".$_POST['address_inp']."')";
					$r = @mysqli_query ($dbc, $edit_query_txt);   //the query
					$check_rows = ("SELECT * FROM clients");
					$event_r = @mysqli_query ($dbc, $check_rows);
					$for_event_logging = mysqli_num_rows($event_r);  // no client id input so figuring it out
					$for_event_logging = $for_event_logging +1; /////////////////////////////////////////////////////////////////HERE - CONTROLLED BY THE NUMBER OF RECORDS IN CLIENTS DB !!!!!!!!!!!!!!!!!!
					
					if ($r) { 
						$alert = "<script>alert('ADDING NEW CLIENT:   SUCCESSFUL!');</script>";
						echo $alert;
						
						$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
						
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','new client','clients','".$for_event_logging."')";
						$r = @mysqli_query ($dbc, $logging_event);
						echo $logging_event;
					} else {
						$alert = "<script>alert('ADDING NEW CLIENT:                                                                       There was a problem.  The record was not added.');</script>";
						echo $alert;
					}
			}
		}
	} else {  //THIS WAY TO EDIT RECORD
		$check = ("SELECT * FROM clients WHERE custNum=".$_POST['custNum_inp']);
		$r = @mysqli_query ($dbc, $check);   // test query to confirm record exists
		$num_rows = mysqli_num_rows($r);  // checking if zero rows!
		$abort = 0;
			
		if ($num_rows ==0 ) {  //no matching record
			$alert = "<script>alert('EDITTING CLIENT RECORD:                                                                              There is no record matching the client number enterred.');</script>";
			echo $alert;					
		} else {
			$update_string = "UPDATE clients SET";
			$string_ending = " WHERE custNum = ".$_POST['custNum_inp'];
			$is_field_info = 0;
			if (empty($_POST['lname_inp'])) {} else {  //last name
				$update_string = $update_string." lastName='".$_POST['lname_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['fname_inp'])) {} else {  //first name
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." firstName='".$_POST['fname_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['verified_inp'])) {} else {  //position
				if ($_POST['verified_inp'] == "yes" || $_POST['verified_inp'] == "no") {
					if($is_field_info == 1) {
						$update_string = $update_string.",";
					}
					$update_string = $update_string." verified='".$_POST['verified_inp']."'";				
					$is_field_info = 1;
				} else { 
					$alert = "<script>alert('EDITTING CLIENT RECORD:                                                                              Verified must be    yes  OR  no');</script>";
					echo $alert;
					$abort = 1;
				}
			}
			if (empty($_POST['email_inp'])) {} else {  //email
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." email='".$_POST['email_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['phone_inp'])) {} else {  //phone
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." phone='".$_POST['phone_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['address_inp'])) {} else {  //address
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." address='".$_POST['address_inp']."'";				
				$is_field_info = 1;
			}
			if ($is_field_info == 0) { //second check if modification should be aborted
				$alert = "<script>alert('EDITTING EMPLOYEE RECORD:                                                                              No valid fields editted.');</script>";
					echo $alert;
			} else {
				if ($abort == 1) {} else {
					$update_string = $update_string.$string_ending;
					$r = @mysqli_query ($dbc, $update_string);
					if (mysqli_affected_rows($dbc)==1) {
						$alert = "<script>alert('EDITTING EMPLOYEE RECORD: SUCCESSFUL');</script>";
						echo $alert;
						$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','edit client','clients','".$_POST['custNum_inp']."')";
						$r = @mysqli_query ($dbc, $logging_event);
					}
				}
			}
		} 
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
		




<h1 id='page_title'>Add New or Edit Clients</h1>



<!-- FILTERS -->
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
<input type="text" id="address" name="address" value="<?php if(!empty($_POST['address'])) echo $_POST['address'];?>"><br><br>

<label for="verified">Verified:</label><br>
	<select id="position" name="verified">
		<option value="all" $all>All</option>
		<option value="yes" $yes>Yes</option> 
		<option value="no" $no>No</option>
	</select>
  
<br><br>
  <input type="submit" value="Submit">
  <input type="hidden" name="submitted" value="TRUE" />
</form>


<!-- RESULTS DISPLAY -->
<div class="ex1" id="client_dispaybox">
<?php echo $info; ?>
</div>


<!-- ADD OR EDIT -->
<h4 id='cust_add_title'>Add New Employee or Edit Existing</h4>

<h4 id='cust_add_title2'>Search Filters</h4>

<form method="post">
<label id="cust_input_txt1" for="staffNum_inp">Client Number:</label><br>
<input id="cust_input_box" type="text"  name="custNum_inp" value="<?php //if(!empty($_POST['custNum_inp'])) echo $_POST['staffNum_inp'];?>"><br><br>
<label id="cust_input_txt1" for="lname_inp">Last name:</label><br>
<input id="cust_input_box" type="text" name="lname_inp" value="<?php //if(!empty($_POST['lname_inp'])) echo $_POST['lname_inp'];?>"><br><br>
<label id="cust_input_txt1" for="fname_inp">First name:</label><br>
<input id="cust_input_box" type="text" name="fname_inp" value="<?php //if(!empty($_POST['fname_inp'])) echo $_POST['fname_inp'];?>"><br><br>
<label id="cust_input_txt1" for="email_inp">Email:</label><br>
<input id="cust_input_box" type="text" name="email_inp" value="<?php //if(!empty($_POST['email_inp'])) echo $_POST['email_inp'];?>"><br><br>
<label id="cust_input_txt1" for="phone_inp">Phone:</label><br>
<input id="cust_input_box" type="text" name="phone_inp" value="<?php //if(!empty($_POST['phone_inp'])) echo $_POST['phone_inp'];?>"><br><br>
<label id="cust_input_txt1" for="address_inp">Address:</label><br>
<input id="cust_input_box" type="text" name="address_inp" value="<?php //if(!empty($_POST['address_inp'])) echo $_POST['address_inp'];?>"><br><br>
<label id="cust_input_txt1" for="verified_inp">Verified?:</label><br>
<input id="cust_input_box" type="text" name="verified_inp" value="<?php //if(!empty($_POST['verified_inp'])) echo $_POST['verified_inp'];?>"><br><br>
  <input id="cust_input_txt1" type="submit" value="Submit">
  <input type="hidden" name="submitted_edit" value="TRUE" />
</form>

<div class="emp_help1">
<img id="emp_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>CLIENT NUMBER<br>Enter the number of the record to be editted using the fields below<br>OR<br>
		Leave this field empty to add a new client record.
		</div></div>

<div class="cust_help2">
<img id="cust_help2_icon" src="help_icon.jpg" alt="Help"></img>
<div>VERIFIED?<br>Clients must be verified using two forms of ID<br><br>Only use:<br><br>yes  OR  no
		</div></div>

<div id="emp2switch">
		<ul>
			<li><a id="nounderline" href="loggedin.php"><span><strong>Back to Switchboard</strong></span></a></li>
		</ul>
	</div>



<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
		
	</div>
</body>