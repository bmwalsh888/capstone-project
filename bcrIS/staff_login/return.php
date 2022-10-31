<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

require ('../mysqli_connect.php');

$dvd_title_search = 0;
$conditional = ""; //set conditional for default query view
$conditional_alt = "";
$conditional_movie = " ORDER BY title";



// Search fields checked when DVD Filter Submit
if (isset($_POST['submitted'])) {  // Check if the form has been submitted & set conditional for query

	$conditional = " WHERE ";
		$conditional_alt = " WHERE ";
	$linker = 0;
	
	if (!empty($_POST['dvdNum_filt'])) {
		$conditional = $conditional . "dvdNum = '" . $_POST['dvdNum_filt'] . "'";
		$conditional_alt = $conditional_alt . "d.dvdNum = '" . $_POST['dvdNum_filt'] . "'";
		$linker = 1;
	}

	if ($_POST['checkedOut_filt'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " checkedOut = '" . $_POST['checkedOut_filt'] . "'";
			$conditional_alt = $conditional_alt . " d.checkedOut = '" . $_POST['checkedOut_filt'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND checkedOut = '" . $_POST['checkedOut_filt'] . "'";
			$conditional_alt = $conditional_alt . " AND d.checkedOut = '" . $_POST['checkedOut_filt'] . "'";
		} 
	}

	if ($_POST['writtenOff_filt'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " writtenOff = '" . $_POST['writtenOff_filt'] . "'";
			$conditional_alt = $conditional_alt . " d.writtenOff = '" . $_POST['writtenOff_filt'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND writtenOff = '" . $_POST['writtenOff_filt'] . "'";
			$conditional_alt = $conditional_alt . " AND d.writtenOff = '" . $_POST['writtenOff_filt'] . "'";
		} 
	}

	if (!empty($_POST['titleNum_filt'])) {
		$dvd_title_search = 1;
		if ($linker == 0) {
			$conditional_alt = $conditional_alt . " d.dvdNum = c.dvdNum AND c.titleNum = '" . $_POST['titleNum_filt'] . "'";
		} else {
			$conditional_alt = $conditional_alt . " AND d.dvdNum = c.dvdNum AND c.titleNum = '" . $_POST['titleNum_filt'] . "'";
		}
	}


	if ($linker == 0) {$conditional = "";} //empties the conditional declaration if search fields emptied
	
	
	}







// THE SEARCH QUERY & REPORTING FOR DVDs

if ($dvd_title_search == 0) {//for inner join query
$q = "SELECT dvdNum, category, numOfMovies, checkedOut, custNum, rentalDate, rentalDueDate, writtenOff, writeOffDate FROM dvds " . $conditional;  //the query statement
} else {
	$dvd_title_search = 0;
	$q = "SELECT d.dvdNum, d.category, d.numOfMovies, d.checkedOut, d.custNum, d.rentalDate, d.rentalDueDate, d.writtenOff, d.writeOffDate FROM dvds AS d, dvd_content AS c ". $conditional_alt;
}

$r = @mysqli_query ($dbc, $q);   //the query

$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array

$num_rows = mysqli_num_rows($r);  // checking if zero rows! if not print the results to table



if ($num_rows ==0 ) {} else{

	$info = "";
	if ($result ==0) {$info = "No results found using these filter settings.";}
	else {		
		
		
		$display_contents = "<table id='emp_results_table'>";
		$display_contents = $display_contents . "<tr id='col_lab'><td>" . "Number" . "</td><td>" . "Category" .  "</td><td>" . "Movies on DVD" . "</td><td>" . "Checked Out" . "</td><td>" . "Rented By" . "</td><td>" . "Rented Date" . "</td><td>" . "Due Date" . "</td><td>" . "Witten Off" . "</td><td>" . "Write Off Date" . "</td><td>" . "Title #s on DVD" . "</td></tr>";

		foreach($result as $line){
			$display_contents = $display_contents . "<tr><td>" . ($line['dvdNum']) . "</td><td>" . ($line['category']) .  "</td><td>" . ($line['numOfMovies']) . "</td><td>" . ($line['checkedOut']) . "</td><td>" . ($line['custNum']) . "</td><td>" . ($line['rentalDate']) . "</td><td>" . ($line['rentalDueDate']) . "</td><td>" . ($line['writtenOff']) . "</td><td>" . ($line['writeOffDate']) . "</td>";

				$q_movies_on_dvd = "SELECT titleNum FROM dvd_content WHERE dvdNum=".$line['dvdNum'];

				$r_movies_on_dvd = @mysqli_query ($dbc, $q_movies_on_dvd);
				$result_movies_on_dvd = @mysqli_fetch_all ($r_movies_on_dvd, MYSQLI_ASSOC);
				$num_rows_movies_on_dvd = mysqli_num_rows($r_movies_on_dvd);			
				
				if ($num_rows_movies_on_dvd ==0 ) {
				} else {
					$temp_movies_on_dvd = "";
					foreach($result_movies_on_dvd as $line_movies_on_dvd){
						$temp_movies_on_dvd = $temp_movies_on_dvd."    ". ($line_movies_on_dvd['titleNum']);
					}
					$display_contents = $display_contents . "<td>" . $temp_movies_on_dvd . "</td></tr>";		
				}		
	}
	$display_contents = $display_contents . "</table>";
	$info = $display_contents;
}
}








// EDITTING & ADDING DVD DATA

if (isset($_POST['submitted_edit'])) {  // Check if the return form has been submitted
	if (empty($_POST['dvdNum_returnDVD'])) {  	
		$alert = "<script>alert('RETURNING DVD:    A DVD number is required.');</script>";
		echo $alert;
	} else {  //DVD field populated
		$q = "SELECT checkedOut, custNum, writtenOff, rentalDueDate, category FROM dvds WHERE dvdNum = '". $_POST['dvdNum_returnDVD'] ."'";
		$r = @mysqli_query ($dbc, $q);   //the query
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
		$num_rows = mysqli_num_rows($r); 
		
		if ($num_rows ==0) {  //check if dvd in db
			$alert = "<script>alert('RETURNING DVD:    DVD number not in database.');</script>";
			echo $alert;
		} else {  //yes, in db
			foreach($result as $line){
				$customer = $line['custNum'];  //get customer number
				$writtenOff = $line['writtenOff'];
				$checkedOut = $line['checkedOut'];
				$rentalDueDate = $line['rentalDueDate'];
				$category = $line['category'];
			}

			if ($writtenOff == 'no') { // DVD not written off
				if ($checkedOut == 'yes') { // DVD is currently checked out
				
					$q = "SELECT lateFee,rentTerm FROM rates WHERE category = '".$category."'";
					$r = @mysqli_query ($dbc, $q);	
					$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
					foreach($result as $line){
						$lateFee = $line['lateFee'];
						$rentTerm = $line['rentTerm'];
					}
				
					$rentalDueDate = strtotime($rentalDueDate);
					$rentalDueDate = date("Y-m-d", $rentalDueDate);
					
					$dateNow = strtotime("now -5 hours");
					$dateNow = date("Y-m-d", $dateNow);
				
					$daysLate = round((strtotime($dateNow) - strtotime($rentalDueDate)) / (60 * 60 * 24));
					if ($daysLate <= 0) {  //if negative late days...
						$daysLate = 0;
					}
				
					//update 'rent_history'
					$q = "UPDATE rent_history SET dateReturned='".$dateNow."', daysLate='".$daysLate."' WHERE dvdNum='".$_POST['dvdNum_returnDVD']."' AND custNum = '".$customer."' AND dateReturned = '0000-00-00'";
					$r = @mysqli_query ($dbc, $q);	
				
					//update clients
					$q = "SELECT feesDue, feeTotalEver FROM clients WHERE custNum = '".$customer."'";
					$r = @mysqli_query ($dbc, $q);
					$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
					foreach($result as $line){
						$feesDue = $line['feesDue'];
						$feeTotalEver = $line['feeTotalEver'];
					}
					$acruedFees = ($lateFee * $daysLate);
					$newFeesDue = ($feesDue + $acruedFees);
					$newTotalFees = ($feeTotalEver + $acruedFees);
					$q = "UPDATE clients SET feesDue='".$newFeesDue."', feeTotalEver='".$newTotalFees."' WHERE custNum = '".$customer."'";
					$r = @mysqli_query ($dbc, $q);
					
					//update 'dvds'
					$q = "UPDATE dvds SET checkedOut='no', custNum='0', rentalDate='0000-00-00', rentalDueDate='0000-00-00' WHERE dvdNum='".$_POST['dvdNum_returnDVD']."'";
					$r = @mysqli_query ($dbc, $q);
					
					//event_log - transaction	
					$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
					$q = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey,comments) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','dvd return','dvd','".$_POST['dvdNum_returnDVD']."','')";
					$r = @mysqli_query ($dbc, $q);						

					$alert = "<script>alert('RETURNING DVD:    SUCCESS.');</script>";
					echo $alert;

				} else {  //DVD not checked out
					$alert = "<script>alert('RETURNING DVD:    This DVD is not checked out.  Cannot proceed.');</script>";
					echo $alert;
				}
			} else {  //DVD written off
				$alert = "<script>alert('RETURNING DVD:    This DVD was written off by a manager.  Cannot proceed.');</script>";
				echo $alert;
			} //DVD is written off - must be addressed first
		} //DVD in DB
	} //DVD field populated
} //return submitted


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
		



<h1 id='page_title_dvds'>DVD Return/Check in</h1>



<!-- DVDs to return -->
<h4 id=''>Enter DVD number to be returned:</h4>

<form method="post">
<label id="returnDVD1" for="dvdNum_returnDVD">DVD Number:</label><br>
<input id="returnDVD2" type="text"  name="dvdNum_returnDVD"><br>


<input id="returnDVD8" type="submit" value="Submit">
<input type="hidden" name="submitted_edit" value="TRUE">
</form>




<!-- TITLES -->
<h4 id='return_filter_title'>Filter by DVD number:</h4>

<!-- DVD FILTERS -->
<form method="post">

<label id="returnDVD_filter1" for="dvdNum_filt">DVD Number:</label><br>
<input id="returnDVD_filter2" type="text" name="dvdNum_filt" value="<?php if(!empty($_POST['dvdNum_filt'])) echo $_POST['dvdNum_filt'];?>"><br>

<label id="returnDVD_filter3" for="titleNum_filt">Title Number:</label><br>
<input id="returnDVD_filter4" type="text" name="titleNum_filt" value="<?php if(!empty($_POST['titleNum_filt'])) echo $_POST['titleNum_filt'];?>"><br>

<label id="returnDVD_filter5" for="checkedOut"><br>Checked Out Status:</label><br>
  <select id="returnDVD_filter6" name="checkedOut_filt">
    <option value="all">All</option>
    <option value="yes">Checked Out</option>
    <option value="no">Available</option>
  </select>
 
  <label id="returnDVD_filter7" for="writtenOff_filt"><br>Written Off?:</label><br>
  <select id="returnDVD_filter8" name="writtenOff_filt">
    <option value="no">No</option>
    <option value="yes">Yes</option>
    <option value="all">All</option>
  </select>
  
<br><br>
  <input id="returnDVD_filter9" type="submit" value="Submit">
  <input type="hidden" name="submitted" value="TRUE" />
</form>


<!-- RESULTS DISPLAY -->
<div id="return_dispaybox">
<?php echo $info; ?>
</div>





<div class="return_help1">
<img id="return_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>DVD RETURN<br>Enter the number of the DVD and select the Submit button and the return will be processed.
		<br><br>In the full system the DVD return process will be aided by a barcose reader to eliminate entry mistakes.
		</div></div>
	


<div id="emp2switch">
		<ul>
			<li><a id="nounderline" href="switchboard_manager.php"><span><strong>Back to Switchboard</strong></span></a></li>
		</ul>
	</div>



<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
		
	</div>
</body>