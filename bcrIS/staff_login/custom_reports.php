<?php
session_start();
// Echo session variables set on previous page for employee name
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

$info = "Select the report criteria."; 
$GLOBALS['report'] = '';                                     //$_SESSION['report'] = '';
require ('../mysqli_connect.php');


// TRANSACTION REPORT SUBMITTED
if (isset($_POST['sales_submitted'])) {

	$sales_conditional = "SELECT ";
	$mult_cols = 0;
	$mult_cols2 = 0;
	$error_message = "";
	$columHeaders = "<tr id='col_lab'>";
	$print_report = "Sales Report\n";
	$san = 0;
	$sd = 0;
	$tc = 0;
	$rc = 0;
	$rt = 0;
	$fp = 0;
	$ft = 0;
	$pm = 0;
	$cn = 0;
	$stn = 0;
	
	if (isset($_POST['show_saleNum'])) { //checkboxes selected for showing in report
		$sales_conditional = $sales_conditional."saleNum";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Sale Number"."</td>";
		$san = 1;
		$print_report = $print_report."Sale Number\t";
	}

	if (isset($_POST['show_salesDate'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."salesDate";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Sale Date"."</td>";
		$sd = 1;
		$print_report = $print_report."Sale Date\t";
	}
	if (isset($_POST['show_totalCost'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."totalCost";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Sale Total"."</td>";
		$tc = $mult_cols;
		$print_report = $print_report."Total Cost\t"; 
	}
	if (isset($_POST['show_rentalCost'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."rentalCost";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Rental Cost"."</td>";
		$rc = $mult_cols;
		$print_report = $print_report."Rental Cost\t";
	}
	if (isset($_POST['show_rentalTax'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."rentalTax";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Rental Tax"."</td>";
		$rt = $mult_cols;
		$print_report = $print_report."Rental Tax\t";
	}
	if (isset($_POST['show_feePaid'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."feePaid";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Fee Paid"."</td>";
		$fp = $mult_cols;
		$print_report = $print_report."Fee Paid\t";
	}
	if (isset($_POST['show_feeTax'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."feeTax";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Fee Tax"."</td>";
		$ft = $mult_cols;
		$print_report = $print_report."Fee Tax\t";
	}
	if (isset($_POST['show_payMethod'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."payMethod";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Pay Method"."</td>";
		$pm = 1;
		$print_report = $print_report."Payment Method\t";
	}
	if (isset($_POST['show_custNum'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."custNum";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Client Number"."</td>";
		$cn = 1;
		$print_report = $print_report."Client Number\t";
	}
	if (isset($_POST['show_staffNum'])) {
		if ($mult_cols != 0) {
			$sales_conditional = $sales_conditional.",";
		}
		$sales_conditional = $sales_conditional."staffNum";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Staff Number"."</td>";
		$stn = 1;
		$print_report = $print_report."Staff Number\t";	
	}
	
	
	$sales_conditional = $sales_conditional." FROM sales ";
	
	$sales_conditional2 = " WHERE ";
	
	
	if(!empty($_POST['saleNum'])){ //saleNum filter value submitted
		$sales_conditional2 = $sales_conditional2."staffNum='".$_POST['saleNum']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE saleNum ='".$_POST['saleNum']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Sale Number enterred is not in the system.<br>";
	}	
	if(!empty($_POST['salesDate'])){ //saleNum filter value submitted
	
	
	
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$mult_cols2 = 1;
		if (!empty($_POST['salesDate_range'])){ //range query
		
		
			$sales_conditional2 = $sales_conditional2."salesDate BETWEEN '".$_POST['salesDate']."' AND '".$_POST['salesDate_range']."'";
			$q = "SELECT * FROM sales WHERE salesDate BETWEEN '".$_POST['salesDate']."' AND '".$_POST['salesDate_range']."'";
			$r = @mysqli_query ($dbc, $q);
			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Sale Date enterred is not recognized by the system.<br>";
		} else { //date query
			$sales_conditional2 = $sales_conditional2."salesDate='".$_POST['salesDate']."'";
			$q = "SELECT * FROM sales WHERE salesDate='".$_POST['salesDate']."'";
			$r = @mysqli_query ($dbc, $q);
			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Sale Number enterred is not in the system.<br>";
		}
	}	
	if(!empty($_POST['totalCost'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."totalCost LIKE '".$_POST['totalCost']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE totalCost LIKE '".$_POST['totalCost']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Total Cost enterred is not in the system.<br>";
	}
	if(!empty($_POST['rentalCost'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."rentalCost LIKE '".$_POST['rentalCost']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE rentalCost LIKE '".$_POST['rentalCost']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Rental Cost enterred is not in the system.<br>";
	}
	if(!empty($_POST['rentalTax'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."rentalTax LIKE '".$_POST['rentalTax']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE rentalTax LIKE '".$_POST['rentalTax']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Rental Tax enterred is not in the system.<br>";
	}
	if(!empty($_POST['feePaid'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."feePaid LIKE '".$_POST['feePaid']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE feePaid LIKE '".$_POST['feePaid']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Fees Paid enterred is not in the system.<br>";
	}
	if(!empty($_POST['feeTax'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."feeTax LIKE '".$_POST['feeTax']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE feeTax LIKE '".$_POST['feeTax']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Fees Tax enterred is not in the system.<br>";
	}
	if ($_POST['payMethod'] == 'all') {
		} else {
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."payMethod='".$_POST['payMethod']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE payMethod='".$_POST['payMethod']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Payment Method enterred is not in the system.<br>";
	}
	if(!empty($_POST['custNum'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."custNum='".$_POST['custNum']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE custNum='".$_POST['custNum']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Customer Number enterred is not in the system.<br>";
	}
	if(!empty($_POST['staffNum'])){ //
		if ($mult_cols2 == 1) {
			$sales_conditional2 = $sales_conditional2." AND ";
		}
		$sales_conditional2 = $sales_conditional2."staffNum='".$_POST['staffNum']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM sales WHERE staffNum='".$_POST['staffNum']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Staff Number enterred is not in the system.";
	}
		
	if ($sales_conditional2 == 	" WHERE ") {
		$sales_conditional2 = "";
	}
		
	$sales_conditional = $sales_conditional.$sales_conditional2;	
		
	if ($error_message == "") {
		$q = $sales_conditional." ORDER BY ".$_POST['sortby'];
		$r = @mysqli_query ($dbc, $q);

		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results meet the query criteria.";
		//$num_rows = mysqli_num_rows($r);
		
		$display_contents = "<table id='emp_results_table'>".$columHeaders."</tr>";
		
		$tc_total = 0;
		$rc_total = 0;
		$rt_total = 0;
		$fp_total = 0;
		$ft_total = 0;
		
		foreach($result as $line){
			$display_contents = $display_contents."<tr>";
			$print_report = $print_report."\n";
			if ($san == 1) {
				$display_contents = $display_contents."<td>".$line['saleNum']."</td>";
				$print_report = $print_report.$line['saleNum']."\t";
			}
			if ($sd == 1) {
				$display_contents = $display_contents."<td>".$line['salesDate']."</td>";
				$print_report = $print_report.$line['salesDate']."\t";
			}
			if ($tc > 0) {
				$display_contents = $display_contents."<td>$ ".number_format($line['totalCost'],2)."</td>";
				$print_report = $print_report.number_format($line['totalCost'],2)."\t";
				$tc_total = $tc_total + number_format($line['totalCost'],2);
			}
			if ($rc > 0) {
				$display_contents = $display_contents."<td>$ ".number_format($line['rentalCost'],2)."</td>";
				$print_report = $print_report.number_format($line['rentalCost'],2)."\t";
				$rc_total = $rc_total + number_format($line['rentalCost'],2);
			}
			if ($rt > 0) {
				$display_contents = $display_contents."<td>$ ".number_format($line['rentalTax'],2)."</td>";
				$print_report = $print_report.number_format($line['rentalTax'],2)."\t";
				$rt_total = $rt_total + number_format($line['rentalTax'],2);
			}
			if ($fp > 0) {
				$display_contents = $display_contents."<td>$ ".number_format($line['feePaid'],2)."</td>";
				$print_report = $print_report.number_format($line['feePaid'],2)."\t";
				$fp_total = $fp_total + number_format($line['feePaid'],2);
			}
			if ($ft > 0) {
				$display_contents = $display_contents."<td>$ ".number_format($line['feeTax'],2)."</td>";
				$print_report = $print_report.number_format($line['feeTax'],2)."\t";
				$ft_total = $ft_total + number_format($line['feeTax'],2);
			}
			if ($pm == 1) {
				$display_contents = $display_contents."<td>".$line['payMethod']."</td>";
				$print_report = $print_report.$line['payMethod']."\t";
			}
			if ($cn == 1) {
				$display_contents = $display_contents."<td>".$line['custNum']."</td>";
				$print_report = $print_report.$line['custNum']."\t";
			}
			if ($stn == 1) {
				$display_contents = $display_contents."<td>".$line['staffNum']."</td>";
				$print_report = $print_report.$line['staffNum']."\t";
			}
			$display_contents = $display_contents."</tr>";
			$print_report = $print_report."\n";
		}

		if ($tc > 0 or $rc > 0 or $rt > 0 or $fp > 0 or $ft > 0) { //sum totals at bottom of columns
			$display_contents = $display_contents."<tr>";
			$pos_one = "";
			$pos_two = "";
			$pos_three = "";
			$pos_four = "";
			$pos_five = "";
			$pos_six = "";
			$pos_seven = "";
			
			if ($tc == 1) {$pos_one = "$tc_total";}
			if ($tc == 2) {$pos_two = "$tc_total";}	
			if ($tc == 3) {$pos_three = "$tc_total";}
			if ($rc == 1) {$pos_one = "$rc_total";}
			if ($rc == 2) {$pos_two = "$rc_total";}
			if ($rc == 3) {$pos_three = "$rc_total";}
			if ($rc == 4) {$pos_four = "$rc_total";}
			if ($rt == 1) {$pos_one = "$rt_total";}
			if ($rt == 2) {$pos_two = "$rt_total";}
			if ($rt == 3) {$pos_three = "$rt_total";}
			if ($rt == 4) {$pos_four = "$rt_total";}
			if ($rt == 5) {$pos_five = "$rt_total";}
			if ($fp == 1) {$pos_one = "$fp_total";}
			if ($fp == 2) {$pos_two = "$fp_total";}	
			if ($fp == 3) {$pos_three = "$fp_total";}
			if ($fp == 4) {$pos_four = "$fp_total";}
			if ($fp == 5) {$pos_five = "$fp_total";}
			if ($fp == 6) {$pos_six = "$fp_total";}	
			if ($ft == 1) {$pos_one = "$ft_total";}
			if ($ft == 2) {$pos_two = "$ft_total";}
			if ($ft == 3) {$pos_three = "$ft_total";}
			if ($ft == 4) {$pos_four = "$ft_total";}
			if ($ft == 5) {$pos_five = "$ft_total";}
			if ($ft == 6) {$pos_six = "$ft_total";}
			if ($ft == 7) {$pos_seven = "$ft_total";}
			
			$print_report = $display_contents;//altered report componant
			
			if ($mult_cols >= 1) {
				if ($pos_one == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_one,2)."</td>";
					//$print_report = $print_report.number_format($pos_one,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_one,2)."</td>";
				}
			}
			if ($mult_cols >= 2) {
				if ($pos_two == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_two,2)."</td>";
					//$print_report = $print_report.number_format($pos_two,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_two,2)."</td>";
				}
			}
			if ($mult_cols >= 3) {
				if ($pos_three == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_three,2)."</td>";
					//$print_report = $print_report.number_format($pos_three,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_three,2)."</td>";
				}
			}
			if ($mult_cols >= 4) {
				if ($pos_four == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_four,2)."</td>";
					//$print_report = $print_report.number_format($pos_four,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_four,2)."</td>";
				}
			}
			if ($mult_cols >= 5) {
				if ($pos_five == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_five,2)."</td>";
					//$print_report = $print_report.number_format($pos_five,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_five,2)."</td>";
				}
			}
			if ($mult_cols >= 6) {
				if ($pos_six == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_six,2)."</td>";
					//$print_report = $print_report.number_format($pos_six,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_six,2)."</td>";
				}
			}
			if ($mult_cols >= 7) {
				if ($pos_seven == "") {
					$display_contents = $display_contents."<td></td>";
					//$print_report = $print_report."\t";
					$print_report = $print_report."<td>-</td>";
				} else {
					$display_contents = $display_contents."<td>$ ".number_format($pos_seven,2)."</td>";
					//$print_report = $print_report.number_format($pos_seven,2)."\t";
					$print_report = $print_report."<td>$ ".number_format($pos_seven,2)."</td>";
				}
			}
			if ($mult_cols >= 8) {
				$display_contents = $display_contents."<td></td>";
				//$print_report = $print_report."\t";
				$print_report = $print_report."<td>-</td>";
			}
			if ($mult_cols >= 9) {
				$display_contents = $display_contents."<td></td>";
				//$print_report = $print_report."\t";
				$print_report = $print_report."<td>-</td>";
			}
			if ($mult_cols == 10) {
				$display_contents = $display_contents."<td></td>";
				//$print_report = $print_report."\t";
				$print_report = $print_report."<td>-</td>";
			}
			$display_contents = $display_contents."</tr>";
			$print_report = $print_report."</tr>";
		}
		
		
		$display_contents = $display_contents."</table>";
		$print_report = $print_report."</table>";
		$info = $display_contents;
		//$info = $print_report;


		//$q = "DELETE FROM print_report";
		//$r = @mysqli_query ($dbc, $q);
		//$q = "INSERT INTO print_report (Report) VALUES ('".$print_report."')";
		//$r = @mysqli_query ($dbc, $q);
		
	} else {
		$info = $error_message;
	}
}




// I intended to save a copy of a report on the users computer but I have no idea how to identify the local computer to save a report to.
// The report is generated on the server...
/*
if (isset($_POST['print_report'])) {
	$q = "SELECT Report FROM print_report";
	$r = @mysqli_query ($dbc, $q);
	$num_rows = mysqli_num_rows($r);
	if ($num_rows == 0) {
		$info = "Please generate a report first.";
	} else {
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results meet the query criteria.";
		$reportData = "";
		foreach($result as $line){
			$reportData = $line['Report'];
		}
		$info = $reportData;
		//$myReport = "Report_".strtotime("now").".txt";
		//$handling = fopen($myReport,'w');

		//fwrite($handling, $reportData);
		//fclose($handling);
	
		//$image = file_get_contents('https://walsh37.uwmsois.com/bcr/staff_login/'.$myReport);
		//file_put_contents('C:/Users/Public/Downloads/'.$myReport, $image);
	}
}*/



// EVENT REPORT SUBMITTED
if (isset($_POST['eventTime_range'])) {
	
	$event_conditional = "SELECT ";
	$mult_cols = 0;
	$mult_cols2 = 0;
	$error_message = "";
	$columHeaders = "<tr id='col_lab'>";
	$en = 0;
	$eti = 0;
	$sn = 0;
	$ety = 0;
	$edb = 0;
	$ek = 0;
	$co = 0;
	$event_time_truncate = 0;

	if (isset($_POST['show_eventNum'])) { //checkboxes selected for showing in report
		$event_conditional = $event_conditional."eventNum";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Event Number"."</td>";
		$en = 1;
	}

	if (isset($_POST['show_eventTime'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."eventTime";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Event Time"."</td>";
		$eti = 1;
	}
	if (isset($_POST['show_staffNum'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."staffNum";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Staff Number"."</td>";
		$sn = 1;
	}
	if (isset($_POST['show_eventType'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."eventType";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Event Type"."</td>";
		$ety = 1;
	}
	if (isset($_POST['show_eventDB'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."eventDB";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Event DB"."</td>";
		$edb = 1;
	}
	if (isset($_POST['show_eventKey'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."eventKey";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Event Key"."</td>";
		$ek = 1;
	}
	if (isset($_POST['show_comments'])) {
		if ($mult_cols != 0) {
			$event_conditional = $event_conditional.",";
		}
		$event_conditional = $event_conditional."comments";
		$mult_cols = $mult_cols + 1;
		$columHeaders = $columHeaders."<td>"."Comments"."</td>";
		$co = 1;
	}
	
	
	$event_conditional = $event_conditional." FROM event_log ";
	
	$event_conditional2 = " WHERE ";
	
	
	if(!empty($_POST['eventNum'])){ //eventNum filter value submitted
		$event_conditional2 = $event_conditional2."eventNum='".$_POST['eventNum']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE eventNum ='".$_POST['eventNum']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Number enterred is not in the system.<br>";
	}	

	if(!empty($_POST['eventTime'])){ 
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$mult_cols2 = 1;
		if (!empty($_POST['eventTime_range'])){ //range query
			$q = "SELECT * FROM event_log WHERE eventTime BETWEEN '".$_POST['eventTime']."' AND '".$_POST['eventTime_range']."'";
			$r = @mysqli_query ($dbc, $q);
			$num_rows = mysqli_num_rows($r); 
			if ($num_rows > 0) {
				$event_conditional2 = $event_conditional2."eventTime BETWEEN '".$_POST['eventTime']."' AND '".$_POST['eventTime_range']."'";
			} else {
				$q = "SELECT * FROM event_log WHERE eventDate BETWEEN '".$_POST['eventTime']."' AND '".$_POST['eventTime_range']."'";
				$r = @mysqli_query ($dbc, $q);
				$num_rows = mysqli_num_rows($r); 
				if ($num_rows > 0) {
					$event_conditional2 = $event_conditional2."eventDate BETWEEN '".$_POST['eventTime']."' AND '".$_POST['eventTime_range']."'";
				} else {
					$error_message = $error_message."The Event Numbers enterred are not in the system.<br>";
				}
			}
			//$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Date enterred is not recognized by the system.<br>";
		} else { //date query
			$q = "SELECT * FROM event_log WHERE eventTime LIKE '".$_POST['eventTime']."'";
			$r = @mysqli_query ($dbc, $q);
			$num_rows = mysqli_num_rows($r); 
			if ($num_rows > 0) {
				$event_conditional2 = $event_conditional2."eventTime LIKE '".$_POST['eventTime']."'";
				//$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Number enterred is not in the system.<br>";
			} else {
				$q = "SELECT * FROM event_log WHERE eventDate = '".$_POST['eventTime']."'";
				$r = @mysqli_query ($dbc, $q);
				$num_rows = mysqli_num_rows($r);
					
				if ($num_rows > 0) {
					$event_conditional2 = $event_conditional2."eventDate = '".$_POST['eventTime']."'";
				} else {
					$error_message = $error_message."The Event Number enterred is not in the system.<br>";
				}
			}
		}
	}	
	//either by formatted date or full sequence
	
	
//	$event_time_truncate = 0;
	
	
	
	
	if(!empty($_POST['staffNum'])){ 
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$event_conditional2 = $event_conditional2."staffNum = '".$_POST['staffNum']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE staffNum = '".$_POST['staffNum']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Staff Number enterred is not in the system.<br>";
	}
	if ($_POST['eventType'] == 'all') {
		} else {
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$event_conditional2 = $event_conditional2."eventType = '".$_POST['eventType']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE eventType = '".$_POST['eventType']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Type enterred is not in the system.<br>";
	}
	if ($_POST['eventDB'] == 'all') {
		} else {
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$event_conditional2 = $event_conditional2."eventDB = '".$_POST['eventDB']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE eventDB = '".$_POST['eventDB']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Database enterred is not in the system.<br>";
	}
	if(!empty($_POST['eventKey'])){ 
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$event_conditional2 = $event_conditional2."eventKey = '".$_POST['eventKey']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE eventKey = '".$_POST['eventKey']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Event Key enterred is not in the system.<br>";
	}
	if(!empty($_POST['comments'])){ 
		if ($mult_cols2 == 1) {
			$event_conditional2 = $event_conditional2." AND ";
		}
		$event_conditional2 = $event_conditional2."comments = '".$_POST['comments']."'";
		$mult_cols2 = 1;
		$q = "SELECT * FROM event_log WHERE comments = '".$_POST['comments']."'";
		$r = @mysqli_query ($dbc, $q);
		$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $error_message = $error_message."The Comments enterred is not in the system.<br>";
	}


	if ($event_conditional2 == 	" WHERE ") {
		$event_conditional2 = "";
	}

	$event_conditional = $event_conditional.$event_conditional2;

	if ($error_message == "") {
		$q = $event_conditional." ORDER BY ".$_POST['sortby2'];
		$r = @mysqli_query ($dbc, $q);
        $num_rows = mysqli_num_rows($r);
		if ($num_rows > 0) {

			$result = @mysqli_fetch_all ($r, MYSQLI_ASSOC) or $info = "No results meet the query criteria.";
		
			$display_contents = "<table id='emp_results_table'>".$columHeaders."</tr>";

			foreach($result as $line){
				$display_contents = $display_contents."<tr>";
				if ($en == 1) {
					$display_contents = $display_contents."<td>".$line['eventNum']."</td>";
				}
				if ($eti == 1) {
					$display_contents = $display_contents."<td>".$line['eventTime']."</td>";
				}
				if ($sn == 1) {
					$display_contents = $display_contents."<td>".$line['staffNum']."</td>";
				}
				if ($ety == 1) {
					$display_contents = $display_contents."<td>".$line['eventType']."</td>";
				}
				if ($edb == 1) {
					$display_contents = $display_contents."<td>".$line['eventDB']."</td>";
				}
				if ($ek == 1) {
					$display_contents = $display_contents."<td>".$line['eventKey']."</td>";
				}
				if ($co == 1) {
					$display_contents = $display_contents."<td>".$line['comments']."</td>";
				}
				$display_contents = $display_contents."</tr>";
			}			
		
			$display_contents = $display_contents."</table>";


			$info = $display_contents;
		} else {
			$info = "No results meet the query criteria.";
		}
	} else {
		$info = $error_message;
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
		

	<h1 id="custom_page_title">Custom Reporting</h1>


<h4 id='sale_filter_title'>Transaction Record Filters:</h4>

<!-- TRANSACTION FILTERS -->
<form method="post">
	<label id="sales_filter_1" for="saleNum">Sale Number:</label>
	<input id="sales_filter_input_1" type="text"  name="saleNum" value="<?php if(!empty($_POST['saleNum'])) echo $_POST['saleNum'];?>"><br>

	<label id="sales_filter_2" for="salesDate">Sale Date:</label>
	<input id="sales_filter_input_2" type="text" name="salesDate" value="<?php if(!empty($_POST['salesDate'])) echo $_POST['salesDate'];?>"><br>

	<label id="sales_filter_3" for="totalCost">Total Cost:</label>
	<input id="sales_filter_input_3" type="text" name="totalCost" value="<?php if(!empty($_POST['totalCost'])) echo $_POST['totalCost'];?>"><br>

	<label id="sales_filter_4" for="rentalCost">Rental Cost:</label>
	<input id="sales_filter_input_4" type="text" name="rentalCost" value="<?php if(!empty($_POST['rentalCost'])) echo $_POST['rentalCost'];?>"><br>

	<label id="sales_filter_5" for="rentalTax">Rental Tax:</label>
	<input id="sales_filter_input_5" type="text" name="rentalTax" value="<?php if(!empty($_POST['rentalTax'])) echo $_POST['rentalTax'];?>"><br>

	<label id="sales_filter_6" for="feePaid">Fees Paid:</label>
	<input id="sales_filter_input_6" type="text" name="feePaid" value="<?php if(!empty($_POST['feePaid'])) echo $_POST['feePaid'];?>"><br>

	<label id="sales_filter_7" for="feeTax">Fee Tax:</label>
	<input id="sales_filter_input_7" type="text" name="feeTax" value="<?php if(!empty($_POST['feeTax'])) echo $_POST['feeTax'];?>"><br>

	<label id="sales_filter_8" for="payMethod">Pay Method:</label>
		<select id="sales_filter_input_8" name="payMethod">
			<option value="all">All</option>
			<option value="cash">Cash</option> 
			<option value="creditcard">Credit Card</option>
			<option value="check">Check</option>
		</select><br>

	<label id="sales_filter_9" for="custNum">Cust Number:</label>
	<input id="sales_filter_input_9" type="text" name="custNum" value="<?php if(!empty($_POST['custNum'])) echo $_POST['custNum'];?>"><br>

	<label id="sales_filter_10" for="staffNum">Staff Number:</label>
	<input id="sales_filter_input_10" type="text" name="staffNum" value="<?php if(!empty($_POST['staffNum'])) echo $_POST['staffNum'];?>"><br>

	<input id="sale_filter_button" type="submit" value="Submit">
	<input type="hidden" name="sales_submitted" value="TRUE">
	
	<input type="checkbox" id="salescheck1" name="show_saleNum" value="yes" checked>
	<input type="checkbox" id="salescheck2" name="show_salesDate" value="yes" checked>
	<input type="checkbox" id="salescheck3" name="show_totalCost" value="yes" checked>
	<input type="checkbox" id="salescheck4" name="show_rentalCost" value="yes" checked>
	<input type="checkbox" id="salescheck5" name="show_rentalTax" value="yes" checked>
	<input type="checkbox" id="salescheck6" name="show_feePaid" value="yes" checked>
	<input type="checkbox" id="salescheck7" name="show_feeTax" value="yes" checked>
	<input type="checkbox" id="salescheck8" name="show_payMethod" value="yes" checked>
	<input type="checkbox" id="salescheck9" name="show_custNum" value="yes" checked>
	<input type="checkbox" id="salescheck10" name="show_staffNum" value="yes" checked>
	
	<input id="sales_filter_input_11" type="text" name="salesDate_range" value="<?php if(!empty($_POST['salesDate_range'])) echo $_POST['salesDate_range'];?>"><br>
	
	<input id="salessort1" type="radio" name="sortby" value="saleNum" checked>
	<input id="salessort2" type="radio" name="sortby" value="salesDate">
	<input id="salessort3" type="radio" name="sortby" value="totalCost">
	<input id="salessort4" type="radio" name="sortby" value="rentalCost">
	<input id="salessort5" type="radio" name="sortby" value="rentalTax">
	<input id="salessort6" type="radio" name="sortby" value="feePaid">
	<input id="salessort7" type="radio" name="sortby" value="feeTax">
	<input id="salessort8" type="radio" name="sortby" value="payMethod">
	<input id="salessort9" type="radio" name="sortby" value="custNum">
	<input id="salessort10" type="radio" name="sortby" value="staffNum">
</form>



<h4 id='event_filter_title'>Event Record Filters:</h4>

<!-- TRANSACTION FILTERS -->
<form method="post">
	<label id="event_filter_1" for="eventNum">Event Number:</label>
	<input id="event_filter_input_1" type="text"  name="eventNum" value="<?php if(!empty($_POST['eventNum'])) echo $_POST['eventNum'];?>"><br>

	<label id="event_filter_2" for="eventTime">Event Time:</label>
	<input id="event_filter_input_2" type="text" name="eventTime" value="<?php if(!empty($_POST['eventTime'])) echo $_POST['eventTime'];?>"><br>

	<label id="event_filter_3" for="staffNum">Staff Number:</label>
	<input id="event_filter_input_3" type="text" name="staffNum" value="<?php if(!empty($_POST['staffNum'])) echo $_POST['staffNum'];?>"><br>

	<label id="event_filter_4" for="eventType">Event Type:</label>
		<select id="event_filter_input_4" name="eventType">
			<option value="all">All</option>
			<option value="new client">New Client</option> 
			<option value="edit client">Edit Client</option>
			<option value="new DVD">New DVD</option>
			<option value="edit DVD">Edit DVD</option> 
			<option value="new movie">New Movie</option>
			<option value="edit movie">Edit Movie</option>
			<option value="new movie/dvd associ">New Movie/DVD Association</option>
			<option value="delete movie/dvd ass">Remove Movie/DVD Association</option>
			<option value="add employee">Add Employee</option>
			<option value="edit employee">Edit Employee</option>
			<option value="dvd rental">DVD Rental</option>
			<option value="dvd return">DVD Return</option>
			<option value="transaction">Transaction</option>
		</select><br>

	<label id="event_filter_5" for="eventDB">Event Database:</label>
		<select id="event_filter_input_5" name="eventDB">
			<option value="all">All</option>
			<option value="clients">Clients</option> 
			<option value="employees">Employees</option>
			<option value="dvds">DVDs</option>
			<option value="movies">Movies</option> 
			<option value="dvd_content">DVD Content</option>
			<option value="sales">Sales</option>
		</select><br>

	<label id="event_filter_6" for="eventKey">Event Key:</label>
	<input id="event_filter_input_6" type="text" name="eventKey" value="<?php if(!empty($_POST['eventKey'])) echo $_POST['eventKey'];?>"><br>

	<label id="event_filter_7" for="comments">Comments:</label>
	<input id="event_filter_input_7" type="text" name="comments" value="<?php if(!empty($_POST['comments'])) echo $_POST['comments'];?>"><br>

	<input id="event_filter_button" type="submit" value="Submit">
	<input type="hidden" name="event_submitted" value="TRUE">
	
	<input type="checkbox" id="eventcheck1" name="show_eventNum" value="yes" checked>
	<input type="checkbox" id="eventcheck2" name="show_eventTime" value="yes" checked>
	<input type="checkbox" id="eventcheck3" name="show_staffNum" value="yes" checked>
	<input type="checkbox" id="eventcheck4" name="show_eventType" value="yes" checked>
	<input type="checkbox" id="eventcheck5" name="show_eventDB" value="yes" checked>
	<input type="checkbox" id="eventcheck6" name="show_eventKey" value="yes" checked>
	<input type="checkbox" id="eventcheck7" name="show_comments" value="yes" checked>
	
	<input id="eventsort1" type="radio" name="sortby2" value="eventNum" checked>
	<input id="eventsort2" type="radio" name="sortby2" value="eventTime">
	<input id="eventsort3" type="radio" name="sortby2" value="staffNum">
	<input id="eventsort4" type="radio" name="sortby2" value="eventType">
	<input id="eventsort5" type="radio" name="sortby2" value="eventDB">
	<input id="eventsort6" type="radio" name="sortby2" value="eventKey">
	<input id="eventsort7" type="radio" name="sortby2" value="comments">

	<input id="event_filter_input_8" type="text" name="eventTime_range" value="<?php if(!empty($_POST['eventTime_range'])) echo $_POST['eventTime_range'];?>"><br>
</form>


<!--  PRINT REPORT  -->
<form method="post">
	<input id="print_report_button" type="submit" value="Print Report">
	<input type="hidden" name="print_report_DISABLED" value="TRUE">
</form>


<!--  RETURN TO SWITCH  -->
<div id="emp2switch">
		<ul>
			<li><a id="nounderline" href="loggedin.php"><span><strong>Back to Switchboard</strong></span></a></li>
		</ul>
	</div>


<!-- RESULTS DISPLAY -->
<div class="ex1" id="custom_dispaybox">
<?php echo $info; ?>
</div>


<!-- HELP 1 -->
<div class="cr_help1">
<img id="cr_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>The Transaction Records are a record of all tranactions, whether they include DVD rental costs or a customer only paying off their overdue rental fees.<br><br><br>
		The RADIO BUTTONS are for marking which column the data should be ordered by.<br><br>
		Marked CHECK BOXES will be included as columns in the report.<br><br>
		Monetary columns include a summed total on the last row.
		</div></div>

<!-- HELP 2 -->
<div class="cr_help2">
<img id="cr_help2_icon" src="help_icon.jpg" alt="Help"></img>
<div>The EVENT Records are a record of all alterations to the database, whether adding, removing, or editting data.  Queries are not recorded.<br><br>
		EVENT TIME is a timestamp so simultanious events can be identified.<br>
		STAFF NUMBER identifies the employee associated with the event.<br>
		EVENT BD indicates which database is involved and EVENT KEY indicates the unique record associated with the listed database.<br><br><br>
		The RADIO BUTTONS are for marking which column the data should be ordered by.<br><br>
		Marked CHECK BOXES will be included as columns in the report.<br><br>
		Monetary columns include a summed total on the last row.
		</div></div>

<!-- HELP 3 -->
<div class="cr_help3">
<img id="cr_help3_icon" src="help_icon.jpg" alt="Help"></img>
<div>SALES DATE can be used in two ways.<br>A date in the left field with right firld empty will querry for records with the specified date.<br>
		If both fields include a date, then the query will present records from the specificed range.
		</div></div>

<!-- HELP 4 -->
<div class="cr_help4">
<img id="cr_help4_icon" src="help_icon.jpg" alt="Help"></img>
<div>EVENT TIME can be used in two ways.<br>A date in the left field with right firld empty will querry for records with the specified date.<br>
		If both fields include a date, then the query will present records from the specificed range.<br><br>
		Note, the fields will accomodate time as XXXX-XX-XX or as XXXX-XX-XX XX:XX:XX
		</div></div>

<!-- HELP 5 -->
<div class="cr_help5">
<img id="cr_help5_icon" src="help_icon.jpg" alt="Help"></img>
<div>PRINT REPORT<br>
		For this prototype, report printouts will need to be generated by selecting the contents of the report, copying the text, and pasting the contents into Excel or a text file (as text, not html).<br><br>
		In this prototype I was able to successfully generate a tab-delimited text file of the report shown on the screen.  However, since PHP is a server based language, the report is created on the server and not on the users computer.<br>
		As such, the report is inaccessable to regualr users and I haven't been able to figure out how to transfer the file to a local computer using the prototype.  None of my FLEX classes have covered this nor do I have personal experience in anything like this.
		</div></div>
		

<div id="footer">
		<p>Copyright &copy; 2022 | Designed by <a href="mailto:walsh37@uwm.edu">Brian Walsh</a><br>Please contact about bugs or technical issues</p>
	</div>
</body>
</html>