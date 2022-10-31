<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

require ('../mysqli_connect.php');

$info2 = "Ready for DVD rental input.";
$info3 = "";

//$_SESSION['custNum_checkout']

$q = "SELECT lastName, firstName, email, phone, address, feesDue FROM clients WHERE custNum='".$_SESSION['custNum_checkout']."'";
$r = @mysqli_query ($dbc, $q);   //the query
$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
$num_rows = mysqli_num_rows($r);  // checking if zero rows! if not print the results to table

$display_contents = "";//////////////////////////////////////////

if ($num_rows ==0 ) {} else{

	$info = "";
	if ($result ==0) {$info = "There was a problem with the information in the database.";}
	else {
		$display_contents = "<table id='emp_results_table'>";

		foreach($result as $line){
			$display_contents = $display_contents ."<tr><td>Customer Number:</td><td>" . $_SESSION['custNum_checkout'] .  "</td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr><td>Last Name:</td><td>" .  ($line['lastName']) . "</td></tr>";
			$display_contents = $display_contents ."<tr><td>First Name:</td><td>" .  ($line['firstName']) . "</td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr><td>Phone:</td><td>" .  ($line['phone']) . "</td></tr>";
			$display_contents = $display_contents ."<tr><td>Email:</td><td>" .  ($line['email']) . "</td></tr>";
			$display_contents = $display_contents ."<tr><td>Address:</td><td>" .  ($line['address']) . "</td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr><td>Fees Due:</td><td><strong>$ ".number_format($line['feesDue'],2)."</strong></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			
			$feesDue = $line['feesDue'];
		}
	
		$q2 = "SELECT rentDate, dateReturned FROM rent_history WHERE dateReturned = '0000-00-00' AND custNum='".$_SESSION['custNum_checkout']."'";
		$r2 = @mysqli_query ($dbc, $q2);   //the query
		$result2 = @mysqli_fetch_all ($r2, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
		$num_rows2 = mysqli_num_rows($r2);  // checking if zero rows! if not print the results to table

		if ($num_rows2 ==0 ) { //no results = do nothing
		} else{
			$display_contents = $display_contents ."<tr><td>DVDs Checked Out:</td><td>".$num_rows2."</td></tr>";	// number of DVDs current rented
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			
			$tally_late = 0;
			$tally_most_late = 0;
			$now = strtotime("now");
			$now = date("Y-m-d", $now); //is good

			foreach($result2 as $line2){
				
				$dd =  $line2['rentDate'];
				$dd = strtotime($dd);
				$datediff =  date("Y-m-d", $dd); //works
		
				$datediff = round((strtotime($now) - strtotime($datediff)) / (60 * 60 * 24));
			
				if ($datediff > 5) {
					$tally_late = $tally_late + 1;
				}
				if ($datediff > $tally_most_late) {
					$tally_most_late = $datediff;
				}
			}	
			
			$display_contents = $display_contents ."<tr><td>DVDs Overdue:</td><td>".$tally_late."</td></tr>";	// number of DVDs Overdue
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr><td>Most Over Due:</td><td>".$tally_most_late." days</td></tr>";	// most overdue DVD
			$display_contents = $display_contents . "</table>";
		}
	$info = $display_contents;
	}
}




if (isset($_POST['rent_dvd_submitted'])) { 
		//customer info update bc its being a pain
		$qcust = "SELECT lastName, firstName, email, phone, address, feesDue FROM clients WHERE custNum='".$_SESSION['custNum_checkout']."'";
		$rcust = @mysqli_query ($dbc, $qcust);   //the query
		$resultcust = @mysqli_fetch_all ($rcust, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
		$num_rowscust = mysqli_num_rows($rcust);  // checking if zero rows! if not print the results to table

		$display_contents = "";//////////////////////////////////////////

		if ($num_rowscust ==0 ) {} else{

			$info = "";
			if ($resultcust ==0) {$info = "There was a problem with the information in the database.";}
			else {
				$display_contents = "<table id='emp_results_table'>";

				foreach($resultcust as $line){
					$display_contents = $display_contents ."<tr><td>Customer Number:</td><td>" . $_SESSION['custNum_checkout'] .  "</td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
					$display_contents = $display_contents ."<tr><td>Last Name:</td><td>" .  ($line['lastName']) . "</td></tr>";
					$display_contents = $display_contents ."<tr><td>First Name:</td><td>" .  ($line['firstName']) . "</td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
					$display_contents = $display_contents ."<tr><td>Phone:</td><td>" .  ($line['phone']) . "</td></tr>";
					$display_contents = $display_contents ."<tr><td>Email:</td><td>" .  ($line['email']) . "</td></tr>";
					$display_contents = $display_contents ."<tr><td>Address:</td><td>" .  ($line['address']) . "</td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
					$display_contents = $display_contents ."<tr><td>Fees Due:</td><td><strong>$ ".number_format($line['feesDue'],2)."</strong></td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
					$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			
					$feesDue = $line['feesDue'];
				}
			}
		}
		
		$info = $display_contents;


		$q = "SELECT writtenOff, checkedOut FROM dvds WHERE dvdNum = '".$_POST['dvd2rent']."'";
		$r = @mysqli_query ($dbc, $q);   //the query
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
		$num_rows = mysqli_num_rows($r); 
		if ($num_rows ==0 ) {

			$info = $display_contents;
		
			$info2 = "DVD number not in dabase.";
		
			$q = "SELECT * FROM cost_tally";
			$r = @mysqli_query ($dbc, $q);
			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
			$cost_count = mysqli_num_rows($r);
			$costSummary = "";
			if ($cost_count == 1) {
				foreach($result as $line){
					$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($line['rentFee'],2)."</td></tr>";
					$costSummary = $costSummary."<td>Rental Tax:</td><td>$ ".number_format($line['rentTax'],2)."</td></tr>";
					$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
					$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($line['feesTax'],2)."</td></tr>";
					$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($line['totalCost'],2)."</strong></td></tr>";
				}
			}
		$info3 = "<table id='emp_results_table'>".$costSummary."</table>";
		
		} else{  //dvd in db
			foreach($result as $line){
				$wo = $line['writtenOff'];
				$co = $line['checkedOut'];
			}
			if ($wo == "yes") {
				$info = $display_contents;
				$info2 = "DVD is written off.  Cannot proceed.";
			
				$q = "SELECT * FROM cost_tally";
				$r = @mysqli_query ($dbc, $q);
				$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
				$cost_count = mysqli_num_rows($r);
				$costSummary = "";
				if ($cost_count == 1) {
					foreach($result as $line){
						$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($line['rentFee'],2)."</td></tr>";
						$costSummary = $costSummary."<td>Rental Tax:</td><td>$ ".number_format($line['rentTax'],2)."</td></tr>";
						$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
						$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($line['feesTax'],2)."</td></tr>";
						$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($line['totalCost'],2)."</strong></td></tr>";
					}
				}
				$info3 = "<table id='emp_results_table'>".$costSummary."</table>";
		
			} else {  // dvd not writtenOff
				if ($co == "yes") {
				
					$info = $display_contents;
					$info2 = "DVD already checked out.  Cannot proceed.";
				
					$q = "SELECT * FROM cost_tally";
					$r = @mysqli_query ($dbc, $q);
					$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
					$cost_count = mysqli_num_rows($r);
					$costSummary = "";
					if ($cost_count == 1) {
						foreach($result as $line){
							$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($line['rentFee'],2)."</td></tr>";
							$costSummary = $costSummary."<td>Rental Tax:</td><td>$ ".number_format($line['rentTax'],2)."</td></tr>";
							$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
							$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($line['feesTax'],2)."</td></tr>";
							$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($line['totalCost'],2)."</strong></td></tr>";
						}
					}
					$info3 = "<table id='emp_results_table'>".$costSummary."</table>";
				
				} else {  // dvd not already checked out
					$qs = "SELECT * FROM dvd_store WHERE dvdNum = '".$_POST['dvd2rent']."'";   //dvd already scanned?
					$rs = @mysqli_query ($dbc, $qs);
					$num_rowss = mysqli_num_rows($rs); 
					if ($num_rowss <> 0) {

						$info2 = "DVD #".$_POST['dvd2rent']." has already been scanned.";
					
						$q = "SELECT * FROM cost_tally";
						$r = @mysqli_query ($dbc, $q);
						$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
						$cost_count = mysqli_num_rows($r);
						$costSummary = "";
						if ($cost_count == 1) {
							foreach($result as $line){
								$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($line['rentFee'],2)."</td></tr>";
								$costSummary = $costSummary."<td>Rental Tax:</td><td>$ ".number_format($line['rentTax'],2)."</td></tr>";
								$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
								$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($line['feesTax'],2)."</td></tr>";
								$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($line['totalCost'],2)."</strong></td></tr>";
							}
						}
						$info3 = "<table id='emp_results_table'>".$costSummary."</table>";
					
					} else {			//records exist		
					
						$q = "SELECT d.dvdNum, d.category, r.rentPrice, r.rentTerm FROM dvds AS d, rates AS r WHERE d.category = r.category AND dvdNum='".$_POST['dvd2rent']."'";
						$r = @mysqli_query ($dbc, $q);   //the query
						$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
						foreach($result as $line){
							$dnum = $line['dvdNum'];
							$dcat = $line['category'];
							$dprice = $line['rentPrice'];
							$dterm = $line['rentTerm'];
						}
			
						$t = '"+'.$dterm.' Days"';	//setting due date
						$t_plus_term = strtotime("+5 Days");
						$ddueDate = date("Y-m-d", $t_plus_term);

						$q = "SELECT t.titleNum, t.title FROM movies AS t, dvd_content AS d WHERE t.titleNum = d.titleNum AND dvdNum='".$_POST['dvd2rent']."'";
						$r = @mysqli_query ($dbc, $q);   //the query
						$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
				
						$title_count = mysqli_num_rows($r);
						$dtitnum = "";
						$dtitle = "";
						foreach($result as $line){
								$dtitle = $dtitle . $line['title']."<br>";
								$dtitnums = $dtitnum . $line['titleNum']." ";
						}
				
						$display_DVD_contents = "";
						$display_DVD_contents = $display_DVD_contents."<tr><td><strong>DVD:</strong></td><td><strong>".$dnum."</strong></td></tr>";
						$display_DVD_contents = $display_DVD_contents."<td>Rental Category:</td><td>".$dcat."</td></tr>";
						$display_DVD_contents = $display_DVD_contents."<tr><td>Movie:</td><td>".$dtitle."</td></tr>";
						$display_DVD_contents = $display_DVD_contents."<tr><td>Due Date:</td><td>".$ddueDate."</td></tr>";
						$display_DVD_contents = $display_DVD_contents."<td>Price:</td><td>$ ".number_format($dprice,2)."</td></tr>";
					
						$info2 = "<table id='emp_results_table'>".$display_DVD_contents."</table>";

						$eqt = "INSERT INTO dvd_store (dvdNum,cat,title,price,dueD,titleNum,title_count) VALUES ('".$dnum."','".$dcat."','".$dtitle."','".$dprice."','".$ddueDate."','".$dtitnums."','".$title_count."')";
						$r = @mysqli_query ($dbc, $eqt); 
										
						$q = "SELECT * FROM cost_tally";
						$r = @mysqli_query ($dbc, $q);
						$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
						$cost_count = mysqli_num_rows($r);
					
						$rentFee = 0;
						$rentTax = 0;
						//$feesDue was set in Customer Display Section
						$feesTax = 0;
						$totalCost = 0;
					
						if ($cost_count = 1) {
							foreach($result as $line){
								$rentFee = $line['rentFee'];
								$rentTax = $line['rentTax'];
								$feesTax = $line['feesTax'];
								$totalCost = $line['totalCost'];
							}
							$q = "DELETE FROM cost_tally";
							$r = @mysqli_query ($dbc, $q);
						}
					
						$rentFee = round($rentFee + $dprice,2);
						$rentTax = round($rentTax + ($dprice * 0.055),2);  // TAX RATE 5.5%
						$feesDue = round($feesDue,2);//////////////////////////////////////////////////////////////////////////////
						$feesTax = round($feesDue * 0.055,2);
						$totalCost = round($rentFee + $rentTax + $feesDue + $feesTax,2);
					
						$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($rentFee,2)."</td></tr>";
						$costSummary = $costSummary."<td>Rental Tax:</td><td>$ ".number_format($rentTax,2)."</td></tr>";
						$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
						$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($feesTax,2)."</td></tr>";
						$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($totalCost,2)."</strong></td></tr>";
						$info3 = "<table id='emp_results_table'>".$costSummary."</table>";
					
						$q = "INSERT INTO cost_tally (rentFee,rentTax,feesTax,totalCost) VALUES ('".$rentFee."','".$rentTax."','".$feesTax."','".$totalCost."')";
						$r = @mysqli_query ($dbc, $q);
						$info = $display_contents;
					}
				}
			}
		}
}



// METHOD OF PAYMEMENT & COMPLETING THE PURCHASE
if (isset($_POST['complete_tranaction'])) { 
	if ($_POST['pay_method'] == 'empty') {  //method of payment empty
		$alert = "<script>alert('METHOD OF PAYMENT FIELD IS EMPTY');</script>";
		echo $alert;
		
		
		$q = "SELECT * FROM cost_tally";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
		$cost_count = mysqli_num_rows($r);
		$costSummary = "";
		if ($cost_count == 1) {
			foreach($result as $line){
				
				$costSummary = "<tr><td>Rental:</td><td>$ ".number_format($line['rentFee'],2)."</td></tr>";
				$costSummary = $costSummary."<td>Rental Tax:   </td><td>$ ".number_format($line['rentTax'],2)."</td></tr>";
				$costSummary = $costSummary."<tr><td>Fees:</td><td>$ ".number_format($feesDue,2)."</td></tr>";
				$costSummary = $costSummary."<tr><td>Fee Tax:</td><td>$ ".number_format($line['feesTax'],2)."</td></tr>";
				$costSummary = $costSummary."<td><strong>Price:</strong></td><td><strong>$ ".number_format($line['totalCost'],2)."</strong></td></tr>";
			}
		}
		$info3 = "<table id='emp_results_table'>".$costSummary."</table>";		
		
		
	} else {
		$q = "SELECT * FROM cost_tally";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
		$cost_count = mysqli_num_rows($r);
		if ($cost_count == 0 AND $feesDue = 0) {  //empty tranaction?
			$info2 = "There are no ballance due on DVD rentals or fee.";
		} else {  //ballance due

			$rentFee = 0;
			$rentTax = 0;
			$feesTax = 0;
			$totalCost = 0;	
			foreach($result as $line){
				$rentFee = $line['rentFee'];
				$rentTax = $line['rentTax'];
				$feesTax = $line['feesTax'];
				$totalCost = $line['totalCost'];
			}
			$dateNow = strtotime("now");
			$dateNow = date("Y-m-d", $dateNow);
		
			//update 'sales' table
			$q = "INSERT INTO sales (salesDate,rentalCost,rentalTax,feePaid, feeTax,totalCost,payMethod,custNum,staffNum) VALUES ('".$dateNow."','".$rentFee."','".$rentTax."','".$feesDue."','".$feesTax."','".$totalCost."','".$_POST['pay_method']."','".$_SESSION['custNum_checkout']."','".$_SESSION['staffNum_logged_in']."')";
			$r = @mysqli_query ($dbc, $q);
		
			//get unique sales number
			$q = "SELECT MAX(saleNum) FROM sales";
			$r = @mysqli_query ($dbc, $q);
			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings.";
			
			$saleNum = "";
			foreach($result as $line){
				$saleNum = $line['MAX(saleNum)'];			
			}

			//get dvdNum
			$q = "SELECT dvdNum,dueD FROM dvd_store";
			$r = @mysqli_query ($dbc, $q);
			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info2 = "No results found using these filter settings.";
			foreach($result as $line){ 
				$dvdNum = $line['dvdNum']; //works
				$dueD = $line['dueD'];  //works

				//update 'sales_rental_dvds' table
				$q = "INSERT INTO sales_rental_dvds (saleNum,dvdNum) VALUES ('".$saleNum."','".$dvdNum."')";
				$r = @mysqli_query ($dbc, $q);
	
				//update 'dvds' table 
				$q = "UPDATE dvds SET checkedOut='yes', custNum='".$_SESSION['custNum_checkout']."', rentalDate='".$dateNow."', rentalDueDate='".$dueD."' WHERE dvdNum='".$dvdNum."'";
				$r = @mysqli_query ($dbc, $q);
				
				//update 'rent_history' table
				$q = "INSERT INTO rent_history (custNum,rentDate,dvdNum) VALUES ('".$_SESSION['custNum_checkout']."','".$dateNow."','".$dvdNum."')";
				$r = @mysqli_query ($dbc, $q);
				
				//event_log - each DVD rented
				$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");				
				$q = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey,comments) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','dvd rental','dvds','".$dvdNum."','')";
				$r = @mysqli_query ($dbc, $q);	
			}	
				
			//update 'clients' table
			$q = "UPDATE clients SET feesDue='0' WHERE custNum='".$_SESSION['custNum_checkout']."'";
			$r = @mysqli_query ($dbc, $q);			
					
			//event_log - transaction
			$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
			$q = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey,comments) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','transaction','sales','".$saleNum."','')";
			$r = @mysqli_query ($dbc, $q);				
				
			//clear $_SESSION['custNum_checkout']				
			$_SESSION['custNum_checkout'] = 0;
	
			//clear dvd_store
			$q = "DELETE FROM dvd_store";
			$r = @mysqli_query ($dbc, $q);

			//clear cost tally	
			$q = "DELETE FROM cost_tally";
			$r = @mysqli_query ($dbc, $q);

			header("Location:successful_sale.php");  //goto new page

		}
	}
}


// CANCEL TRANSACTION & BACK TO SWITCHBOARD
if (isset($_POST['cancel_tranaction'])) { 
	$q = "DELETE FROM cost_tally";
	$r = @mysqli_query ($dbc, $q);
	$q = "DELETE FROM dvd_store";
	$r = @mysqli_query ($dbc, $q);
	$_SESSION['custNum_checkout'] = '';
	header("Location:loggedin.php");  //goto new page
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
		



<h1 id='page_title_checkout'>DVD Check Out & Transactions</h1>

		


<!-- SCANNED RESULTS DISPLAY -->
<h4 id='rent_dvd_title'>DVDs Being Checked Out</h4> <!-- return_filter_title -->

<div id="dvd_rental_dispaybox"> <!-- return_dispaybox -->
<?php echo $info2; ?>
</div>



<!-- ENTER DVD TO RENT -->
<h4 id='rent_enter_dvd_title'>Enter the DVD Number to be Checked Out:</h4> <!-- return_filter_title -->

<form method="post">
	<input id="rent_dvd_field" type="text" name="dvd2rent" value="<?php if(!empty($_POST['dvd2rent'])) echo $_POST['dvd2rent'];?>">
	<input id="rent_dvd_submit" type="submit" value="Submit">
	<input type="hidden" name="rent_dvd_submitted" value="TRUE">
</form>



<!-- COST RESULTS DISPLAY -->
<div id="cost_dispaybox"> <!-- return_dispaybox -->
<?php echo $info3; ?>
</div>


<!--  PAYMENT METHOD  -->
<form method="post">
<label id="pay_method_label" for="pay_method">Method of Payment:</label><br>
	<select id="pay_method_drop" name="pay_method">
		<option value="empty"></option>
		<option value="cash">Cash</option> 
		<option value="creditcard">Credit Card</option>
		<option value="check">Check</option>
	</select>
	<input id="pay_method_button" type="submit" value="Complete Transaction">
	<input type="hidden" name="complete_tranaction" value="TRUE">
</form>


<!--  CANCEL TRANSACTION  -->
<form method="post">
	<input id="pay_method_cancel" type="submit" value="Cancel Transaction & Return to Switchboard">
	<input type="hidden" name="cancel_tranaction" value="TRUE">
</form>



<!--  FOOTER  -->
<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
	</div>
	
	
<!-- CUST RESULTS DISPLAY -->
<h4 id='cust_info_label'>Customer Information</h4> <!-- return_filter_title -->
<div id="cust_info_dispaybox"> <!-- return_dispaybox      -->
<?php echo $info; ?>
</div>	
	
	
</body>
</html>