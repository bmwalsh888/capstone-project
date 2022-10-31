<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}






$GLOBALS['counter'] = 0;
















require ('../mysqli_connect.php');

//for showing scanning dvds



$display_DVD_contents = "<table id='emp_results_table'>";

 												//counts scanned dvds
   //table form to be displayed, minus the ending </table>
$info2 = "Ready for DVD rental input.";


class DVDrent {
	//properties
	public $num;
	public $cat;
	public $title;
	public $price;
	public $dueD;
	public $title_number;
	public $title_count;

	//methods
	function __construct ($num,$cat,$title,$price,$dueD,$title_number,$title_count) {
		$this->num = $num;
		$this->cat = $cat;
		$this->title = $title;
		$this->price = $price;
		$this->term = $dueD;
		$this->titnum = $title_number;
		$this->titcont = $title_count;
	}

	
	function get_num() {
		return $this->num;
	}
		
}

//$_SESSION['custNum_checkout']

$q = "SELECT lastName, firstName, email, phone, address, feesDue FROM clients WHERE custNum='".$_SESSION['custNum_checkout']."'";
$r = @mysqli_query ($dbc, $q);   //the query
$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
$num_rows = mysqli_num_rows($r);  // checking if zero rows! if not print the results to table

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
			$display_contents = $display_contents ."<tr><td>Fees Due:</td><td><strong>$ ".($line['feesDue'])."</strong></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
			$display_contents = $display_contents ."<tr id='col_lab2'><td>1</td><td></td></tr>";
		}
	
		$q2 = "SELECT rentDate, dateReturned FROM rent_history WHERE dateReturned < 1 AND custNum='".$_SESSION['custNum_checkout']."'";
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
			$now = time(); // todays date
			
			foreach($result2 as $line2){
				$datediff = $now - $your_date;
				round($datediff / (60 * 60 * 24));
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
	$q = "SELECT writtenOff, checkedOut FROM dvds WHERE dvdNum = '".$_POST['dvd2rent']."'";
	$r = @mysqli_query ($dbc, $q);   //the query
	$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results found using these filter settings."; //place results in an array
	$num_rows = mysqli_num_rows($r); 
	if ($num_rows ==0 ) {
		$alert = "<script>alert('DVD SCAN:   DVD number not in daabase.');</script>";
		echo $alert;
	} else{  //dvd in db
		foreach($result as $line){
			$wo = $line['writtenOff'];
			$co = $line['checkedOut'];
		}
		if ($wo == "yes") {
			$alert = "<script>alert('DVD SCAN:   DVD is written off.  Cannot proceed.');</script>";
			echo $alert;
		} else {  // dvd not writtenOff
			if ($co == "yes") {
				$alert = "<script>alert('DVD SCAN:   DVD already checked out.  Cannot proceed.');</script>";
				echo $alert;
			} else {  // dvd not already checked out
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
					if ($dtitnum != "") {
						$dtitle = $dtitle."_";
						$dtitnum = $dtitnum."_";
					} else {
						$dtitle = $dtitle . $line['title'];
						$dtitnums = $dtitnum . $line['titleNum'];
					}
				}

	
				if ($GLOBALS['counter'] == 9) {
						$dvd10 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;
				} elseif ($GLOBALS['counter'] == 8) {
						$dvd9 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 7) {
						$dvd8 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 6) {
						$dvd7 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;
				} elseif ($GLOBALS['counter'] == 5) {
						$dvd6 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 4) {
						$dvd5 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 3) {
						$dvd4 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 2) {
						$dvd3 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;
				} elseif ($GLOBALS['counter'] == 1) {
						$dvd2 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;	
				} elseif ($GLOBALS['counter'] == 0) {
						$dvd1 = new DVDrent($dnum,$dcat,$dtitle,$dprice,$ddueDate,$dtitnums,$title_count);
						$GLOBALS['counter'] = $GLOBALS['counter'] +1;
				} else {
						$alert = "<script>alert('DVD SCAN:   A maximum of 10 DVDs can be rented in one transaction.  Limit Reached.  DVD not accepted.');</script>";
						echo $alert;
				}
			
						
/*				
				$display_DVD_contents = $display_DVD_contents."<tr><td><strong>DVD:</strong></td><td><strong>".$dnum."</strong></td><td></td><td>Rental Category:</td><td>".$dcat."</td></tr>";
				$display_DVD_contents = $display_DVD_contents."<tr><td>Movie:</td><td>".$dtitle."</td><td></td><td></td><td></td></tr>";
				$display_DVD_contents = $display_DVD_contents."<tr><td>Due Date:</td><td>".$ddueDate."</td><td></td><td>Price:</td><td>$ ".$dprice."</td></tr>";
				$display_DVD_contents = $display_DVD_contents."<tr id='col_lab2'><td>1</td><td></td><td></td><td></td><td></td></tr>";
*/
				$display_DVD_contents = $display_DVD_contents."<tr><td><strong>DVD:</strong></td><td><strong>".$dnum."</strong></td></tr>";
				$display_DVD_contents = $display_DVD_contents."<td>Rental Category:</td><td>".$dcat."</td></tr>";
				$display_DVD_contents = $display_DVD_contents."<tr><td>Movie:</td><td>".$dtitle."</td></tr>";
				$display_DVD_contents = $display_DVD_contents."<tr><td>Due Date:</td><td>".$ddueDate."</td></tr>";
				$display_DVD_contents = $display_DVD_contents."<td>Price:</td><td>$ ".$dprice."</td></tr>";
				
				$info2 = $display_DVD_contents."</table>";
				echo $GLOBALS['counter']. " counter/ ";

			}
		}
	}
}



//dvd2rent

//DVD: use POST
//Rental Category: dvds
//Movie(s):  pull from movies via dvd_content
//Date Due: 5 days from today
//Price: pull from rates using category

//infor recorded to 
//	sales 	salesDate, rentalCost, rentalTax, feeCost, feeTax, custNum, staffNum
//	rent_history 	custNum, rentDate, dvdNum
//	sales_rental_dvds 	salesNum, dvdNum
//	dvds	checkedOut, custNum, rentalDate, rentalDueDate

//event_log		purchase event w # dvds, additional event checkout for each dvd
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


<h1 id='page_title_dvds'>DVD Check Out & Transactions</h1>

	

<h4 id='rent_dvd_title'>DVDs Being Checked Out</h4> <!--  return_filter_title -->


<!-- CUST RESULTS DISPLAY -->
<div id="dvd_rental_dispaybox"> <!-- return_dispaybox -->
<?php echo $info2; ?>
</div>



<h4 id='rent_enter_dvd_title'>Enter the DVD Number to be Checked Out:</h4> <!-- return_filter_title -->


<form method="post">
	<input id="rent_dvd_field" type="text" name="dvd2rent" value="<?php if(!empty($_POST['dvd2rent'])) echo $_POST['dvd2rent'];?>">
	<input id="rent_dvd_submit" type="submit" value="Submit">
	<input type="hidden" name="rent_dvd_submitted" value="TRUE" />
</form>



<!--  -->
<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
</div>

<h4 id=''>Customer Information</h4>  <!-- return_filter_title -->

<!-- CUST RESULTS DISPLAY -->
<div id="cust_info_dispaybox"> <!-- return_dispaybox -->
<?php echo $info; ?>
</div>

</body>