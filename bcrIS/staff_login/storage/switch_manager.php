<?php
//session_start();
//for TESTING


?>


	<h1 id="page_title">Switchboard</h1>





<body>

<p id="sw_col1"><b>Employee Resources</b></p>
<p id="sw_col2"><b>Search/Report Options</b></p>
<p id="sw_col3"><b>Manager Resources</b></p>


	<div id="staff1">
		<ul>
			<li><a id="nounderline" href="purchase.php"><span>Client Checkout</span></a></li>
			<li><a id="nounderline" href="return.php"><span>DVD Check in</span></a></li>
			<li><a id="nounderline" href="new_client.php"><span>Add New Client</span></a></li>
		</ul>
	</div>

	<div id="staff2">
		<ul>
			<li><a id="nounderline" href=""><span>Title Search</span></a></li>
			<li><a id="nounderline" href=""><span>DVD Search</span></a></li>
			<li><a id="nounderline" href=""><span>Client Search</span></a></li>
			<li><a id="nounderline" href=""><span>Purchases Search</span></a></li>
			<li><a id="nounderline" href=""><span>Employee Search</span></a></li>
			<li><a id="nounderline" href=""><span>Custom Report</span></a></li>
		</ul>
	</div>
	
	<div id="staff3">
		<ul>
			<li><a id="nounderline" href=""><span>DVD Over 15 Days Late</span></a></li>
			<li><a id="nounderline" href=""><span>Add/Remove DVD</span></a></li>
			<li><a id="nounderline" href=""><span>Total Sales by Employee</span></a></li>
			<li><a id="nounderline" href="trans_add_emp.php"><span>Add/Remove Employee</span></a></li>
			<li><a id="nounderline" href="testing.php"><span>Monthly Revenue</span></a></li>
			<!--      -->
			
		</ul>
	</div>	
	

<div class="sw_help1">
<img id="sw_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>These are the standard day-to-day tasks<br><br>
		CLIENT CHECKOUT is for all transactions<br><br>
		DVD CHECK IN for returning rented DVDs<br><br>
		ADD NEW CLIENT is for adding new clients and editting existing accounts
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
		</div></div>

<div class="sw_help3">
<img id="sw_help3_icon" src="help_icon.jpg" alt="Help"></img>
<div>These tools are only visible to manager accounts.<br><br>
		DVD OVER 15 DAYS LATE is for writeoff monitoring<br><br>
		ADD/REMOVE DVD is for inventory management<br><br>
		TOTAL SALES BY EMPLOYEE is for employee recognition<br><br>
		ADD/REMOVE EMPLOYEE is for staff management<br><br>
		MONTHLY REVENUE is for a accounting
		</div></div>

</body>

<?php
include ('footer.php');
?>