<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

$_SESSION['conditional'] = "";



require ('../mysqli_connect.php');



//$q = "SELECT COUNT(staffNum) FROM employees";    //the query of how many records in total
//$r = @mysqli_query ($dbc, $q);                   // Run the query on DB
//$row = @mysqli_fetch_array ($r, MYSQLI_NUM);	 // the number of records
//$numRecords = $row[0];
//echo $numRecords;  // = 2



//$q = "SELECT staffLastName, staffFirstName, staffNum, position FROM employees" . $_SESSION['conditional'];
//echo $q." under $q  /  ";


//$q = "SELECT staffLastName, staffNum, staffNum, position FROM employees WHERE staffNum = '1' AND staffLastName = 'Walsh' AND staffFirstName = 'Brian' AND position = 'manager'";


//$row = @mysqli_fetch_array ($r, MYSQLI_NUM) or die("Bad Query: $q");
	//$result = @mysqli_fetch_array ($r, MYSQLI_NUM) or die("Bad Query: $q");
	
//$numRecords = $row[0];                           //what does this do?
//echo $numRecords;  // = Walsh
//print_r($result);

	//mysqli_free_result($result); 	//free result from mem
	//mysqli_close($dbc);				//close connection




// HERE WE GO!!!!!!!!
$q = "SELECT staffNum, staffLastName, staffFirstName, position FROM employees";
$r = @mysqli_query ($dbc, $q);
$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or die("Bad Query: $q");
$info = "";
foreach($result as $line){
		$info = $info . ($line['staffNum']) . ($line['staffLastName']) . ($line['staffFirstName']) . ($line['position']) . "<br>";
	}



	//echo "<table>";
	//while($row = mysqli_fetch_assoc($result)) {
	//while($row = $r->fetch_assoc()) {
	//echo "<tr><td>{$row["staffNum"]}</td><td>{$row["staffLastName"]}</td><td>{$row["staffFirstName"]}</td><td>{$row["position"]}</td></tr>]\n";
	//echo $row["staffNum"] . $row["staffLastName"] . $row["staffFirstName"] . " " . $row["position"];
	//}
	//echo "</table>";

//$info = "";
//while($row = $r->fetch_assoc()) {
//	$info = $info . $row["staffNum"] . $row["staffLastName"]. $row["staffFirstName"]. " " . $row["position"] . "<br>" ;
//	echo $info;
//}



// THIS WORKS - well it used to

//if ($numRecords > 0) {
//    // output data of each row
//    while($row = $r->fetch_assoc()) {
//        echo "<br> id: ". $row["staffLastName"]. " - Name: ". $row["staffFirstName"]. " " . $row["position"];
//}}





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
<label for="fname">First name:</label><br>
<input type="text" id="fname" name="fname" value="<?php if(!empty($_POST['fname'])) echo $_POST['fname'];?>"><br><br>
<label for="lname">Last name:</label><br>
<input type="text" id="lname" name="lname" value="<?php if(!empty($_POST['lname'])) echo $_POST['lname'];?>"><br><br>

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