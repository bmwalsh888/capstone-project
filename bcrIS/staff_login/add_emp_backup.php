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
		//$staNumb = $_POST['staffNum'];
		$conditional = $conditional . "staffNum = '" . $_POST['staffNum'] . "'";   
		//$conditional = $conditional . "staffNum = '" . $staNumb . "'";  
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



// NEED TO HANDLE NO RESULTS!!!!!!!!!!!!!!!!!



require ('../mysqli_connect.php');


echo $conditional;
$q = "SELECT staffNum, staffLastName, staffFirstName, position, startDate, endDate, staffEmail, staffPhone, staffAddress FROM employees " . $conditional;


$r = @mysqli_query ($dbc, $q);
$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or die("Bad Query: $q");
$info = "";
/*
// THIS IS FUNCTIONAL OLDER DISPLAY
foreach($result as $line){
		$info = $info . ($line['staffNum']) . ($line['staffLastName']) . ($line['staffFirstName']) . ($line['position']) . ($line['startDate']) . ($line['endDate']) . ($line['staffEmail']) . ($line['staffPhone']) . ($line['staffAddress']) . "<br>";
	}
*/

// PERHAPS set columns and echo back using individual variables, based on above






// DISPLAY TESTS - this works, but can I get it into a table?
//$result = @mysqli_query($r, MYSQLI_ASSOC);

echo "<table>";
foreach($result as $line){
echo "<tr><td>";
echo ($line['staffNum']);
echo "</td><td>";
echo ($line['staffLastName']);
echo "</td><td>";
echo ($line['staffFirstName']);
echo "</td></tr>";
}
echo "</table>";






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
		





<h1 id="page_title">Add/Remove Employee</h1>
<!--
<form action="">
<input id="emp_pref1" type="radio" checked="checked" value="edit" name="emp_display">Search/Edit<br>
<input id="emp_pref1" type="radio" value="add" name="emp_display">Add<br>
</form>
-->


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

  

<!--		<option <?php //if (isset($example) && $example=="position") echo "selected";?>>Staff</option>     trying to get it to retain selection
<option <?php //if (isset($position) && $position=="staff") echo "selected";?> value="staff">Staff</option> -->

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



<h2>overflow: scroll:</h2>
<div class="ex1" id="staff_dispaybox"><?php echo $info; ?></div>

<!--
<h2>overflow: scroll:</h2>
<div class="ex1" id="staff_dispaybox">a<br>aaaa<br>aaaaaa<br>aaaaaaaaaa<br>aaa<br>aaaaaaa<br>aaaaaaaa<br>aaaaaaaaa<br>aaa<br>a<br>aaaa<br>aaa<br>aaaaaa<br>aaaa<br>aaaaa<br>aaa<br>aa<br>aaa<br>aaaaaa<br>aaaaa<br>aaaa<br>aaa<br>aaaaa<br>aaaaaa<br>aaaaa<br>aaaaa<br>a<br>aaa<br>aaaa<br>aaa<br>aaaa<br>aaa<br>aaaa<br>aaaa<br>aa<br>aa<br>a<br>aaa<br>aaa<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</div>
-->


</body>