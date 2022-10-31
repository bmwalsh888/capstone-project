<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

$conditional = " WHERE UNIX_TIMESTAMP(`endDate`) = 0"; //set conditional for default query view


// Search fields checked when Submit
if (isset($_POST['submitted'])) {  // Check if the form has been submitted & set conditional for query

	$conditional = " WHERE ";
	$linker = 0;
	
	if (!empty($_POST['staffNum'])) {
		$conditional = $conditional . "staffNum = '" . $_POST['staffNum'] . "'";     
		$linker = 1;
	}

	if (!empty($_POST['lname'])) {
		if ($linker == 0) {
			$conditional = $conditional . "staffLastName = '" . $_POST['lname'] . "'";   
			$linker = 1;
	} else {
		$conditional = $conditional . " AND staffLastName = '" . $_POST['lname'] . "'";  
	} }
	
	if (!empty($_POST['fname'])) {
		if ($linker == 0) {
			$conditional = $conditional . "staffFirstName = '" . $_POST['fname'] . "'";   
			$linker = 1;
	} else {
		$conditional = $conditional . " AND staffFirstName = '" . $_POST['fname'] . "'";   
	} }
	
	if ($_POST['position'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . "position = '" . $_POST['position'] . "'";   
			$linker = 1;
		} else {
			$conditional = $conditional . " AND position = '" . $_POST['position'] . "'";   
		} }
	
	if ($_POST['current'] == "all") {}
	else {
		if ($linker == 1) {
			$conditional = $conditional . " AND";
		}
		
		if ($_POST['current'] == "current") {
			$conditional = $conditional . " UNIX_TIMESTAMP(`endDate`) = 0";
			$linker = 1;
		} else {
			$conditional = $conditional . " UNIX_TIMESTAMP(`endDate`) > 0";
			$linker = 1;
		}
		}
		
	if ($linker == 0) {$conditional = "";} //empties the conditional declaration if search fields emptied

	}



// THE SEARCH QUERY & REPORTING

require ('../mysqli_connect.php');

$q = "SELECT staffNum, staffLastName, staffFirstName, position, startDate, endDate, staffEmail, staffPhone, staffAddress FROM employees " . $conditional;  //the query statement

$r = @mysqli_query ($dbc, $q);   //the query

$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array


$num_rows = mysqli_num_rows($r);  // checking if zero rows! if not print the results to table
if ($num_rows ==0 ) {} else{

	$info = "";
	if ($result ==0) {$info = "No results found using these filter settings.";}
	else {
		$display_contents = "<table id='emp_results_table'>";
		$display_contents = $display_contents . "<tr id='col_lab'><td>" . "Number" . "</td><td>" . "Last Name" .  "</td><td>" . "First Name" . "</td><td>" . "Position" . "</td><td>" . "Start Date" . "</td><td>" . "End Date" . "</td><td>" . "Email" . "</td><td>" . "Phone" . "</td><td>" . "Address" . "</td></tr>";

		foreach($result as $line){
			$display_contents = $display_contents . "<tr><td>" . ($line['staffNum']) . "</td><td>" . ($line['staffLastName']) .  "</td><td>" . ($line['staffFirstName']) . "</td><td>" . ($line['position']) . "</td><td>" . ($line['startDate']) . "</td><td>" . ($line['endDate']) . "</td><td>" . ($line['staffEmail']) . "</td><td>" . ($line['staffPhone']) . "</td><td>" . ($line['staffAddress']) . "</td></tr>";
		}

	$display_contents = $display_contents . "</table>";
	$info = $display_contents;
	}
}



// EDITTING & ADDING EMPLOYEE DATA

if (isset($_POST['submitted_edit'])) {  // Check if the edit form has been submitted
	if (empty($_POST['staffNum_inp'])) {  // THIS WAY TO ADD NEW
		if ((empty($_POST['lname_inp'])) || (empty($_POST['fname_inp']))  || (empty($_POST['position_inp'])) || (empty($_POST['start_date_inp'])) || (empty($_POST['email_inp']))  || (empty($_POST['phone_inp'])) || (empty($_POST['address_inp'])) ){   //add new employee
			$alert = "<script>alert('ADDING NEW EMPLOYEE:                                                                       All fields are required except Employee Number and End Date.  New recond not created.');</script>";
			echo $alert;
		} else {    //proceed with values enterred
			if (($_POST['position_inp'] != "staff") and ($_POST['position_inp'] != "manager")) { //check if "position" options enterred correctly
				$alert = "<script>alert('ADDING NEW EMPLOYEE:                                                                       Position options are:      staff      OR      manager                   New record not created because required field missing.');</script>";
				echo $alert;
			} else {
				if (empty($_POST['end_date_inp'])) {$end_date = 0;} else {$end_date = $_POST['end_date_inp'];} // format "end date"
				
					$edit_query_txt = "INSERT INTO employees (staffLastName,staffFirstName,position,startDate,endDate,staffEmail,staffPhone,staffAddress) VALUES ('".$_POST['lname_inp']."','".$_POST['fname_inp']."','".$_POST['position_inp']."','".$_POST['start_date_inp']."','".$end_date."','".$_POST['email_inp']."','".$_POST['phone_inp']."','".$_POST['address_inp']."')";
					$r = @mysqli_query ($dbc, $edit_query_txt);   //the query

					$check_rows = ("SELECT * FROM employees");
					$event_r = @mysqli_query ($dbc, $check_rows);
					$for_event_logging = mysqli_num_rows($event_r);  // no employ id added so figuring it out
					$for_event_logging = $for_event_logging +2; /////////////////////////////////////////////////////////////////HERE - CONTROLLED BY THE NUMBER OF RECORDS IN EMPLOYEES DB !!!!!!!!!!!!!!!!!!
					echo $for_event_logging;
					
					$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
					
					if ($r) { 
						$alert = "<script>alert('ADDING NEW EMPLOYEE:   SUCCESSFUL!');</script>";
						echo $alert;
						
						
						
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','new employee','employees','".$for_event_logging."')";
						$r = @mysqli_query ($dbc, $logging_event);
						
					} else {
						$alert = "<script>alert('ADDING NEW EMPLOYEE:                                                                       There was a problem.  The record was not added.');</script>";
						echo $alert;
					}
			}
		}
	} else {  //THIS WAY TO EDIT RECORD
		$check = ("SELECT * FROM employees WHERE staffNum=".$_POST['staffNum_inp']);
		$r = @mysqli_query ($dbc, $check);   // test query to confirm record exists
		$num_rows = mysqli_num_rows($r);  // checking if zero rows!
		$abort = 0;
			
		if ($num_rows ==0 ) {  //no matching record
			$alert = "<script>alert('EDITTING EMPLOYEE RECORD:                                                                              There is no record matching the employee number enterred.');</script>";
			echo $alert;					
		} else {
			$update_string = "UPDATE employees SET";
			$string_ending = " WHERE staffNum = ".$_POST['staffNum_inp'];
			$is_field_info = 0;
			if (empty($_POST['lname_inp'])) {} else {  //last name
				$update_string = $update_string." staffLastName='".$_POST['lname_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['fname_inp'])) {} else {  //first name
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." staffFirstName='".$_POST['fname_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['position_inp'])) {} else {  //position
				if ($_POST['position_inp'] == "staff" || $_POST['position_inp'] == "manager") {
					if($is_field_info == 1) {
						$update_string = $update_string.",";
					}
					$update_string = $update_string." position='".$_POST['position_inp']."'";				
					$is_field_info = 1;
				} else { 
					$alert = "<script>alert('EDITTING EMPLOYEE RECORD:                                                                              Position must be    staff  OR  manager');</script>";
					echo $alert;
					$abort = 1;
				}
			}
			if (empty($_POST['start_date_inp'])) {} else {  //start date
				if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['start_date_inp'])) {

					if($is_field_info == 1) {
						$update_string = $update_string.",";
					}
					$update_string = $update_string." startDate='".$_POST['start_date_inp']."'";				
					$is_field_info = 1;
				} else {
					$alert = "<script>alert('EDITTING EMPLOYEE RECORD:                                                                              Dates must be formatted as  YYYY-MM-DD');</script>";
					echo $alert;
					$abort = 1;
				}
			}
			if (empty($_POST['end_date_inp'])) {} else {  //end date
				//if (preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/g",$_POST['end_date_inp'])) {
				if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['end_date_inp'])) {
					if($is_field_info == 1) {
						$update_string = $update_string.",";
					}
					$update_string = $update_string." endDate='".$_POST['end_date_inp']."'";				
					$is_field_info = 1;
				} else {
					$alert = "<script>alert('EDITTING EMPLOYEE RECORD:                                                                              Dates must be formatted as  YYYY-MM-DD');</script>";
					echo $alert;
					$abort = 1;
				}
			}
			if (empty($_POST['email_inp'])) {} else {  //email
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." staffEmail='".$_POST['email_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['phone_inp'])) {} else {  //phone
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." staffPhone='".$_POST['phone_inp']."'";				
				$is_field_info = 1;
			}
			if (empty($_POST['address_inp'])) {} else {  //address
				if($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." staffAddress='".$_POST['address_inp']."'";				
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
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','edit employee','employees','".$_POST['staffNum_inp']."')";
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
		




<h1 id='page_title'>Add/Remove Employee</h1>



<!-- FILTERS -->
<form method="post">

<label for="staffNum">Employee Number:</label><br>
<input type="text" id="staffNum" name="staffNum" value="<?php if(!empty($_POST['staffNum'])) echo $_POST['staffNum'];?>"><br><br>
<label for="lname">Last name:</label><br>
<input type="text" id="lname" name="lname" value="<?php if(!empty($_POST['lname'])) echo $_POST['lname'];?>"><br><br>
<label for="fname">First name:</label><br>
<input type="text" id="fname" name="fname" value="<?php if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>

<label for="position">Position:</label><br>
	<select id="position" name="position">
		<option value="all" $all>All</option>
		<option value="staff" $staff>Staff</option> 
		<option value="manager" $manager>Manager</option>
	</select>
	
<label for="current"><br><br>Employee Status:</label><br>
  <select id="current" name="current">
    <option value="current">Current</option>
    <option value="former">Former</option>
    <option value="all">All</option>
  </select>
  
<br><br>
  <input type="submit" value="Submit">
  <input type="hidden" name="submitted" value="TRUE" />
</form>


<!-- RESULTS DISPLAY -->
<div class="ex1" id="staff_dispaybox">
<?php echo $info; ?>
</div>


<!-- ADD OR EDIT -->
<h4 id='emp_add_title'>Add New Employee or Edit Existing</h4>

<h4 id='emp_add_title2'>Search Filters</h4>

<form method="post">
<label id="input_txt1" for="staffNum_inp">Employee Number:</label><br>
<input id="input_box" type="text"  name="staffNum_inp" value="<?php //if(!empty($_POST['staffNum_inp'])) echo $_POST['staffNum_inp'];?>"><br><br>
<label id="input_txt1" for="lname_inp">Last name:</label><br>
<input id="input_box" type="text" name="lname_inp" value="<?php //if(!empty($_POST['lname_inp'])) echo $_POST['lname_inp'];?>"><br><br>
<label id="input_txt1" for="fname_inp">First name:</label><br>
<input id="input_box" type="text" name="fname_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="position_inp">Position:</label><br>
<input id="input_box" type="text" name="position_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="start_date_inp">Start Date:</label><br>
<input id="input_box" type="text" name="start_date_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="end_date_inp">End Date:</label><br>
<input id="input_box" type="text" name="end_date_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="email_inp">Email:</label><br>
<input id="input_box" type="text" name="email_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="phone_inp">Phone:</label><br>
<input id="input_box" type="text" name="phone_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label id="input_txt1" for="address_inp">Address:</label><br>
<input id="input_box" type="text" name="address_inp" value="<?php //if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
  <input id="input_txt1" type="submit" value="Submit">
  <input type="hidden" name="submitted_edit" value="TRUE" />
</form>

<div class="emp_help1">
<img id="emp_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>EMPLOYEE NUMBER<br>Enter the number of the record to be editted using the fields below<br>OR<br>
		Leave this field empty to add a new employee record.<br><br>
		NOTE: The default password is password.
		</div></div>

<div class="emp_help2">
<img id="emp_help2_icon" src="help_icon.jpg" alt="Help"></img>
<div>POSITION<br>Employees are classified as<br>staff<br>OR<br>
		manager<br><br>
		Manager status grants access to some additional functions.
		</div></div>
		
<div class="emp_help3">
<img id="emp_help3_icon" src="help_icon.jpg" alt="Help"></img>
<div>DATES<br>All dates use the following format:<br><br>yyyy-mm-dd
		</div></div>


<div id="emp2switch">
		<ul>
			<li><a id="nounderline" href="switchboard_manager.php"><span><strong>Back to Switchboard</strong></span></a></li>
		</ul>
	</div>
<!-- <a id="emp2switch" href="switchboard_manager.php"><strong>Back to Switchboard</strong></a>
<button id="emp2switch" type="button" href="switchboard_manager.php"><strong>Back to Switchboard</strong></button>		-->


<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
		
	</div>
</body>