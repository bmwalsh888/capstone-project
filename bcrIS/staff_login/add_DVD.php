<?php
session_start();
// Echo session variables for employee name in header
if ( isset($_SESSION["staffFirstName"])) {
	if ( isset($_SESSION["staffLastName"])) {
		$empname = "Hello,   " . $_SESSION["staffFirstName"] . " " . $_SESSION['staffLastName'];
	}
}

require ('../mysqli_connect.php');
$conditional = ""; //set conditional for default query view
$conditional_alt = "";
$conditional_movie = " ORDER BY title";

$dvd_title_search = 0;

// Search fields checked when DVD Filter Submit
if (isset($_POST['submitted'])) {  // Check if the form has been submitted & set conditional for query

	$conditional = " WHERE ";
		$conditional_alt = " WHERE ";
	$linker = 0;
	
	if (!empty($_POST['dvdNum'])) {
		$conditional = $conditional . "dvdNum = '" . $_POST['dvdNum'] . "'";
		$conditional_alt = $conditional_alt . "d.dvdNum = '" . $_POST['dvdNum'] . "'";
		$linker = 1;
	}

	if ($_POST['category'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " category = '" . $_POST['category'] . "'";
			$conditional_alt = $conditional_alt . " d.category = '" . $_POST['category'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND category = '" . $_POST['category'] . "'";
			$conditional_alt = $conditional_alt . " AND d.category = '" . $_POST['category'] . "'";
		} 
	}

	if ($_POST['checkedOut'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " checkedOut = '" . $_POST['checkedOut'] . "'";
			$conditional_alt = $conditional_alt . " d.checkedOut = '" . $_POST['checkedOut'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND checkedOut = '" . $_POST['checkedOut'] . "'";
			$conditional_alt = $conditional_alt . " AND d.checkedOut = '" . $_POST['checkedOut'] . "'";
		} 
	}

	if ($_POST['numOfMovies'] == "any") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " numOfMovies = '" . $_POST['numOfMovies'] . "'";
			$conditional_alt = $conditional_alt . " d.numOfMovies = '" . $_POST['numOfMovies'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND numOfMovies = '" . $_POST['numOfMovies'] . "'";
			$conditional_alt = $conditional_alt . " AND d.numOfMovies = '" . $_POST['numOfMovies'] . "'";			
		} 
	}

	if ($_POST['writtenOff'] == "all") {}
	else {
		if ($linker == 0) {
			$conditional = $conditional . " writtenOff = '" . $_POST['writtenOff'] . "'";
			$conditional_alt = $conditional_alt . " d.writtenOff = '" . $_POST['writtenOff'] . "'";
			$linker = 1;
		} else {
			$conditional = $conditional . " AND writtenOff = '" . $_POST['writtenOff'] . "'";
			$conditional_alt = $conditional_alt . " AND d.writtenOff = '" . $_POST['writtenOff'] . "'";
		} 
	}

	if (!empty($_POST['titleNum_dvd'])) {
		$dvd_title_search = 1;
		if ($linker == 0) {
			$conditional_alt = $conditional_alt . " d.dvdNum = c.dvdNum AND c.titleNum = '" . $_POST['titleNum_dvd'] . "'";
		} else {
			$conditional_alt = $conditional_alt . " AND d.dvdNum = c.dvdNum AND c.titleNum = '" . $_POST['titleNum_dvd'] . "'";
		}
	}


	if ($linker == 0) {$conditional = "";} //empties the conditional declaration if search fields emptied
	
	
	}
//SELECT d.dvdNum, d.category FROM dvds AS d, dvd_content AS c 
//WHERE d.dvdNum = c.dvdNum AND c.titleNum = 1


// Search fields checked when Movie Filter Submit
if (isset($_POST['movie_filter'])) {  // Check if the form has been submitted & set conditional for query

	$conditional_movie = " WHERE ";
	$linker_movie = 0;
	
	if (!empty($_POST['filter_titleNum'])) {
		$conditional_movie = $conditional_movie . "titleNum = '" . $_POST['filter_titleNum'] . "'";     
		$linker_movie = 1;
	}
	
	if (!empty($_POST['filter_title'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "title = '" . $_POST['filter_title'] . "'";   
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND title = '" . $_POST['filter_title'] . "'";   
		} 
	}

	if (!empty($_POST['filter_releaseYR'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "releaseYR = '" . $_POST['filter_releaseYR'] . "'";    
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND releaseYR = '" . $_POST['filter_releaseYR'] . "'";   
		} 
	}

/*	if (!empty($_POST['genre'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "genre = '" . $_POST['genre'] . "'";    
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND genre = '" . $_POST['genre'] . "'";   
		} 
	}*/

	if (!empty($_POST['filter_language'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "language = '" . $_POST['filter_language'] . "'";    
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND language = '" . $_POST['filter_language'] . "'";   
		} 
	}

	if (!empty($_POST['filter_director'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "director = '" . $_POST['filter_director'] . "'";    
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND director = '" . $_POST['filter_director'] . "'";   
		} 
	}

/*	if (!empty($_POST['actor'])) {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "actor = '" . $_POST['actor'] . "'";    
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND actor = '" . $_POST['actor'] . "'";   
		} 
	}*/

	if ($_POST['filter_foreignFilm'] == "all") {}
	else {
		if ($linker_movie == 0) {
			$conditional_movie = $conditional_movie . "foreignFilm = '" . $_POST['filter_foreignFilm'] . "'";   
			$linker_movie = 1;
		} else {
			$conditional_movie = $conditional_movie . " AND foreignFilm = '" . $_POST['filter_foreignFilm'] . "'";   
		} 
	}

	if ($linker_movie == 0) {$conditional_movie = "";} //empties the conditional declaration if search fields emptied
	
	
	$conditional_movie = $conditional_movie . " ORDER BY title";
}





//add column for movie nums on dvd
//		query DVD content on disc number
//		get number of rows
//		if 2 concatinate

// seperate:  querry serched on a specific movie/dvd
//		but still want to report multiple movies in results





// THE SEARCH QUERY & REPORTING FOR DVDs







// the actual querry

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









// THE SEARCH QUERY & REPORTING FOR MOVIES


$qm = "SELECT * FROM movies " . $conditional_movie;
//$qm = "SELECT titleNum, title, releaseYR, duration, foreignFilm, language, director FROM movies " . $conditional_movie;  //the query statement

$rm = @mysqli_query ($dbc, $qm);   //the query

$resultm = @mysqli_fetch_all ($rm, MYSQLI_ASSOC) or $info_movies = "No results found using these filter settings."; //place results in an array

$num_rows_movies = mysqli_num_rows($rm);  // checking if zero rows! if not print the results to table



if ($num_rows_movies ==0 ) {} else{

	$info_movies = "";
	if ($resultm ==0) {$info_movies = "No results found using these filter settings.";}
	else {
		$display_contents_movies = "<table id='emp_results_table'>";
		$display_contents_movies = $display_contents_movies . "<tr id='col_lab'><td>" . "Number" . "</td><td>" . "Title" .  "</td><td>" . "Release Year" . "</td><td>" . "Duration (min)" . "</td><td>" . "Foreign Film?" . "</td><td>" . "Language" . "</td><td>" . "Director" . "</td></tr>";

		foreach($resultm as $line){
			$display_contents_movies = $display_contents_movies . "<tr><td>" . ($line['titleNum']) . "</td><td>" . ($line['title']) .  "</td><td>" . ($line['releaseYR']) . "</td><td>" . ($line['duration']) . "</td><td>" . ($line['foreignFilm']) . "</td><td>" . ($line['language']) . "</td><td>" . ($line['director']) . "</td></tr>";
		}

	$display_contents_movies = $display_contents_movies . "</table>";
	$info_movies = $display_contents_movies;
	}
}



// EDITTING & ADDING DVD DATA

if (isset($_POST['submitted_edit'])) {  // Check if the edit form has been submitted
	if (empty($_POST['dvdNum_inp'])) {  // THIS WAY TO ADD NEW			
		$q_beginning = "INSERT INTO dvds (";
		$q_ending = ") VALUES (";
		$multiple = 0;
		
		if ($_POST['category_inp'] == "empty") { //do nothing
		} else {
			$q_beginning = $q_beginning . "category";
			$q_ending = $q_ending . "'" . $_POST['category_inp'] . "'";
			$multiple = 1;
		}
			
		if ($_POST['numOfMovies_inp'] == "any") { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "numOfMovies";
			$q_ending = $q_ending . "'" . $_POST['numOfMovies_inp'] . "'";
			$multiple = 1;
		}	
			
		if ($_POST['writtenOff_inp'] == "all") { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "writtenOff";
			$q_ending = $q_ending . "'" . $_POST['writtenOff_inp'] . "'";
			$multiple = 1;
		}	
				
		if ($multiple == 0) {
			$q = "INSERT INTO `dvds` () VALUES()";
		} else {
			$q = $q_beginning . " " . $q_ending . ")";//was $q = $q_beginning . ") " . $q_ending . ")";
		}
		
		$r = @mysqli_query ($dbc, $q);   //the query
		
		$check_rows = ("SELECT * FROM dvds");
		$event_r = @mysqli_query ($dbc, $check_rows);		
		$for_event_logging = mysqli_num_rows($event_r);		
		$for_event_logging = $for_event_logging +7; /////////////////////////////////////////////////////////////////HERE - CONTROLLED BY THE NUMBER OF RECORDS IN DVDs DB !!!!!!!!!!!!!!!!!!	
		
		$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
		
		if ($r) { 
			$alert = "<script>alert('ADDING NEW DVD:   SUCCESSFUL!  You still need to add Movie Titles to it');</script>";
			echo $alert;		
			$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','new DVD','dvds','".$for_event_logging."')";
			$r = @mysqli_query ($dbc, $logging_event);
						
		} else {
			$alert = "<script>alert('ADDING NEW DVD:                                                                       There was a problem.  The record was not added.');</script>";
			echo $alert;	
		}		

	} else {  //THIS WAY TO EDIT RECORD
		$check = ("SELECT * FROM dvds WHERE dvdNum=".$_POST['dvdNum_inp']);
		$r = @mysqli_query ($dbc, $check);   // test query to confirm record exists
		$num_rows = mysqli_num_rows($r);  // checking if zero rows!
		$abort = 0;

		if ($num_rows ==0 ) {  //no matching record
			$alert = "<script>alert('EDITTING DVD RECORD:                                                                              There is no record matching the DVD number enterred.');</script>";
			echo $alert;	
			
		} else {
			$update_string = "UPDATE dvds SET";
			$string_ending = " WHERE dvdNum = ".$_POST['dvdNum_inp'];
			$is_field_info = 0;			
			
			if ($_POST['category_inp'] == "empty") { //do nothing
			} else {
				if ($is_field_info == 1) {
					$update_string = $update_string.",";
				}				
				$update_string = $update_string." category='".$_POST['category_inp']."'";				
				$is_field_info = 1;
			}
			
			if ($_POST['numOfMovies_inp'] == "any") { //do nothing
			} else {
				if ($is_field_info == 1) {
					$update_string = $update_string.",";
				}			
				$update_string = $update_string." numOfMovies='".$_POST['numOfMovies_inp']."'";				
				$is_field_info = 1;				
			}
			
			if ($_POST['writtenOff_inp'] == "all") { //do nothing
			} else {
				if ($is_field_info == 1) {
					$update_string = $update_string.",";
				}
				$update_string = $update_string." writtenOff='".$_POST['writtenOff_inp']."'";				
				$is_field_info = 1;
			}			
			
			if ($is_field_info == 0) { //second check if modification should be aborted
				$alert = "<script>alert('EDITTING DVD RECORD:                                                                              No valid fields editted.');</script>";
					echo $alert;
			} else {
				if ($abort == 1) {} else {
					$update_string = $update_string.$string_ending;
					$r = @mysqli_query ($dbc, $update_string);
					if (mysqli_affected_rows($dbc)==1) {
						$alert = "<script>alert('EDITTING DVD RECORD: SUCCESSFUL');</script>";
						echo $alert;
						
						$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
						
						//$_SESSION['staffNum_logged_in']
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','edit DVD','dvds','".$_POST['dvdNum_inp']."')";
						$r = @mysqli_query ($dbc, $logging_event);
						
						//echo $logging_event;
					}
				}
			}
		} 
	}
}




// EDITTING & ADDING MOVIE DATA

if (isset($_POST['submitted_edit_movie'])) {  // Check if the edit form has been submitted
	if (empty($_POST['titleNum_inp_edit'])) {  // THIS WAY TO ADD NEW			
		$q_beginning = "INSERT INTO movies (";
		$q_ending = ") VALUES (";
		$multiple = 0;
	
		if (empty($_POST['title_inp_edit'])) { //do nothing
		} else {
			$q_beginning = $q_beginning . "title";
			$q_ending = $q_ending . "'" . $_POST['title_inp_edit'] . "'";
			$multiple = 1;
		}
			
		if (empty($_POST['releaseYR_inp_edit'])) { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "releaseYR";
			$q_ending = $q_ending . "'" . $_POST['releaseYR_inp_edit'] . "'";
			$multiple = 1;
		}	
			
		if (empty($_POST['duration_inp_edit'])) { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "duration";
			$q_ending = $q_ending . "'" . $_POST['duration_inp_edit'] . "'";
			$multiple = 1;
		}	
		
		if ($_POST['foreignFilm_inp_edit'] == "all") { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}			
			$q_beginning = $q_beginning . "foreignFilm";
			$q_ending = $q_ending . "'" . $_POST['foreignFilm_inp_edit'] . "'";
			$multiple = 1;			
		}
			
		if (empty($_POST['language_inp_edit'])) { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "language";
			$q_ending = $q_ending . "'" . $_POST['language_inp_edit'] . "'";
			$multiple = 1;
		}
			
		if (empty($_POST['director_inp_edit'])) { //do nothing
		} else {
			if ($multiple == 1) {
				$q_beginning = $q_beginning . ",";
				$q_ending = $q_ending . ",";
			}
			$q_beginning = $q_beginning . "director";
			$q_ending = $q_ending . "'" . $_POST['director_inp_edit'] . "'";
			$multiple = 1;
		}			
				
		if ($multiple == 0) {
			$q = "INSERT INTO `dvds` () VALUES()";
		} else {
			$q = $q_beginning . $q_ending . ")";
		}

		$r = @mysqli_query ($dbc, $q);   //the query
		
		$check_rows = ("SELECT * FROM movies");
		$event_r = @mysqli_query ($dbc, $check_rows);		
		$for_event_logging = mysqli_num_rows($event_r);		
		$for_event_logging = $for_event_logging +0; /////////////////////////////////////////////////////////////////HERE - CONTROLLED BY THE NUMBER OF RECORDS IN MOVIES DB !!!!!!!!!!!!!!!!!!	
				
		$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");	
				
		if ($r) { 
			$alert = "<script>alert('ADDING NEW MOVIE:   SUCCESSFUL!');</script>";
			echo $alert;		
			$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','new movie','movies','".$for_event_logging."')";
			$r = @mysqli_query ($dbc, $logging_event);
						
		} else {
			$alert = "<script>alert('ADDING NEW Movie:                                                                       There was a problem.  The record was not added.');</script>";
			echo $alert;	
		}		

	} else {  //THIS WAY TO EDIT RECORD
		$check = ("SELECT * FROM movies WHERE titleNum=".$_POST['titleNum_inp_edit']);
		$r = @mysqli_query ($dbc, $check);   // test query to confirm record exists
		$num_rows = mysqli_num_rows($r);  // checking if zero rows!
		$abort = 0;

		if ($num_rows ==0 ) {  //no matching record
			$alert = "<script>alert('EDITTING MOVIE RECORD:                                                                              There is no record matching the movie number enterred.');</script>";
			echo $alert;	
			
		} else {
			$update_string = "UPDATE movies SET";
			$string_ending = " WHERE titleNum = ".$_POST['titleNum_inp_edit'];
			$is_field_info = 0;			
			
		if (empty($_POST['title_inp_edit'])) { //do nothing
		} else {
			$update_string = $update_string." title='".$_POST['title_inp_edit']."'";				
				$is_field_info = 1;
		}	
			
		if (empty($_POST['releaseYR_inp_edit'])) { //do nothing
		} else {
			if ($is_field_info == 1) {
				$update_string = $update_string.",";
			}
			$update_string = $update_string." releaseYR='".$_POST['releaseYR_inp_edit']."'";				
			$is_field_info = 1;
		}	
			
		if (empty($_POST['duration_inp_edit'])) { //do nothing
		} else {
			if ($is_field_info == 1) {
				$update_string = $update_string.",";
			}
			$update_string = $update_string." duration='".$_POST['duration_inp_edit']."'";				
			$is_field_info = 1;
		}	
			
		if (empty($_POST['language_inp_edit'])) { //do nothing
		} else {
			if ($is_field_info == 1) {
				$update_string = $update_string.",";
			}
			$update_string = $update_string." language='".$_POST['language_inp_edit']."'";				
			$is_field_info = 1;
		}			
			
		if ($_POST['foreignFilm_inp_edit'] == "all") { //do nothing
			} else {
				if ($is_field_info == 1) {
					$update_string = $update_string.",";
				}				
				$update_string = $update_string." foreignFilm='".$_POST['foreignFilm_inp_edit']."'";				
				$is_field_info = 1;
			}	
			
		if (empty($_POST['director_inp_edit'])) { //do nothing
		} else {
			if ($is_field_info == 1) {
				$update_string = $update_string.",";
			}
			$update_string = $update_string." director='".$_POST['director_inp_edit']."'";				
			$is_field_info = 1;
		}			
			
		if ($is_field_info == 0) { //second check if modification should be aborted
			$alert = "<script>alert('EDITTING DVD RECORD:                                                                              No valid fields editted.');</script>";
			echo $alert;
		} else {
			if ($abort == 1) {} else {
				$update_string = $update_string.$string_ending;
				$r = @mysqli_query ($dbc, $update_string);
				if (mysqli_affected_rows($dbc)==1) {
					$alert = "<script>alert('EDITTING MOVIE RECORD: SUCCESSFUL');</script>";
					echo $alert;
						
					$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
						
					$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','edit movie','movies','".$_POST['titleNum_inp_edit']."')";
					$r = @mysqli_query ($dbc, $logging_event);
						

					}
				}
			}
		} 
	}
}


/* ASSOCIATING MOVIES WITH DVDs */

if (isset($_POST['dvdNum_ass']))  {  // Check if the associative form has been submitted
	if ((empty($_POST['titleNum_ass'])) || (empty($_POST['dvdNum_ass'])) ) {
		$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:                                                                            Both the DVD number and title number are required.');</script>";
		echo $alert;
		
	} else {
		$check1 = ("SELECT * FROM dvds WHERE dvdNum=".$_POST['dvdNum_ass']);
		$check2 = ("SELECT * FROM movies WHERE titleNum=".$_POST['titleNum_ass']);
		
		$r_check1 = @mysqli_query ($dbc, $check1);   // test query to confirm record exists
		$r_check2 = @mysqli_query ($dbc, $check2); 
		
		$num_rows_check1 = mysqli_num_rows($r_check1);  // checking if zero rows!
		$num_rows_check2 = mysqli_num_rows($r_check2); 
		
		if (($num_rows_check1 == 0) || ($num_rows_check2 == 0) ) { //making sure both movie and dvd exist
			if ($num_rows_check1 == 0) {
				$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:                                                                            DVD number ".$_POST['dvdNum_ass']." is not in the database.');</script>";
				echo $alert;
			}
			if ($num_rows_check2 == 0) {
				$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:                                                                            Movie number ".$_POST['titleNum_ass']." is not in the database.');</script>";
				echo $alert;
			}
		} else { //all above is making sure the records are in the database
			$check3 = ("SELECT * FROM dvd_content WHERE dvdNum=".$_POST['dvdNum_ass']." AND titleNum=".$_POST['titleNum_ass']);
			$r_check3 = @mysqli_query ($dbc, $check3);
			$num_rows_check3 = mysqli_num_rows($r_check3); 
			
			if ($_POST['mov_dvd'] == "mov_dvd_addedit") { //add record
				if ($num_rows_check3 == 0) {
					$q = "INSERT INTO dvd_content (dvdNum,titleNum) VALUES ('".$_POST['dvdNum_ass']."','".$_POST['titleNum_ass']."')";
					$r = @mysqli_query ($dbc, $q);
						
					$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
						
					if ($r) {
						$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:    SUCCESSFUL');</script>";
						echo $alert;
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','new movie/dvd association','dvd_content','movie ".$_POST['titleNum_ass']." & dvd ".$_POST['dvdNum_ass']."')";
						$r = @mysqli_query ($dbc, $logging_event);
					} else {
						$alert = "<script>alert('ADDING NEW MOVIE/DVD ASSOCIATION:                                                                  There was a problem.  The record was not added.');</script>";
						echo $alert;
					}
				} else {
					$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:                                                                           This record already exists.  No change was implimented.');</script>";
					echo $alert;
				}
			} else { //remove record
				if ($num_rows_check3 == 1) {
					$q = "DELETE FROM dvd_content WHERE dvdNum = '".$_POST['dvdNum_ass']."' AND titleNum ='".$_POST['titleNum_ass']."'";
					$r = @mysqli_query ($dbc, $q);
					
					$eventDate=date_format(date_create("now - 6 hours"),"Y-m-d");
					
					if ($r) {
						$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:    DELETION SUCCESSFUL');</script>";
						echo $alert;
						$logging_event = "INSERT INTO event_log (eventDate,staffNum,eventType,eventDB,eventKey) VALUES ('".$eventDate."','".$_SESSION['staffNum_logged_in']."','delete movie/dvd association','dvd_content','movie ".$_POST['titleNum_ass']." & dvd ".$_POST['dvdNum_ass']."')";
						$r = @mysqli_query ($dbc, $logging_event);
					} else {
						$alert = "<script>alert('DELETING MOVIE/DVD ASSOCIATION:                                                                  There was a problem.  The record was not added.');</script>";
						echo $alert;
					}
				} else {
					$alert = "<script>alert('ASSOCIATING MOVIE WITH DVDs:                                                                           This record does not exists.  No deletion was implimented.');</script>";
					echo $alert;
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
		




<h1 id='page_title_dvds'>Add/Edit DVDs & Movies</h1>



<!-- DVD FILTERS -->
<form method="post">

<label id="dvdfilter1" for="dvdNum">DVD Number:</label><br>
<input id="dvdfilter2" type="text" name="dvdNum" value="<?php if(!empty($_POST['dvdNum'])) echo $_POST['dvdNum'];?>"><br>

<label id="dvdfilter3" for="category">Category:</label><br>
	<select id="dvdfilter4" name="category">
		<option value="all">All</option>
		<option value="Current Hit">Current Hit</option> 
		<option value="Current Release">Current Release</option>
		<option value="Popular">Popular</option> 
		<option value="Regular">Regular</option>
	</select>

<label id="dvdfilter12" for="titleNum_dvd">Title Number:</label><br>
<input id="dvdfilter13" type="text" name="titleNum_dvd" value="<?php if(!empty($_POST['titleNum_dvd'])) echo $_POST['titleNum_dvd'];?>"><br>

<label id="dvdfilter5" for="checkedOut"><br>Checked Out Status:</label><br>
  <select id="dvdfilter6" name="checkedOut">
    <option value="all">All</option>
    <option value="yes">Checked Out</option>
    <option value="no">Available</option>
  </select>
  
<label id="dvdfilter7" for="numOfMovies"><br>Movies on DVD:</label><br>
  <select id="dvdfilter8" name="numOfMovies">
    <option value="any">Any</option>
    <option value="one"> 1</option>
    <option value="multiple">>1</option>
  </select>
  
  <label id="dvdfilter9" for="writtenOff"><br>Written Off?:</label><br>
  <select id="dvdfilter10" name="writtenOff">
    <option value="no">No</option>
    <option value="yes">Yes</option>
    <option value="all">All</option>
  </select>
  
<br><br>
  <input id="dvdfilter11" type="submit" value="Submit">
  <input type="hidden" name="submitted" value="TRUE" />
</form>


<!-- RESULTS DISPLAY -->
<div id="dvd_dispaybox">
<?php echo $info; ?>
</div>

<div id="Movie_dispaybox">
<?php echo $info_movies; ?>
</div>

<!-- TITLES -->
<h4 id='dvd_add_title'>Add New DVD or Edit Existing</h4>

<h4 id='dvd_filter_title'>DVD Search Filters</h4>

<h4 id='movie_filter_title'>Movie Search Filters</h4>

<!-- MOVIE FILTERS -->
<form method="post" id="movie_filter">

<label id="titleNum1" for="filter_titleNum">Title Number:</label><br>
<input type="text" id="titleNum2" name="filter_titleNum" value="<?php if(!empty($_POST['filter_titleNum'])) echo $_POST['filter_titleNum'];?>"><br>
<label id="title1" for="filter_title">Title Name:</label><br>
<input type="text" id="title2" name="filter_title" value="<?php if(!empty($_POST['filter_title'])) echo $_POST['filter_title'];?>"><br>
<label id="releaseYR1" for="filter_releaseYR">Release Year:</label><br>
<input type="text" id="releaseYR2" name="filter_releaseYR" value="<?php if(!empty($_POST['filter_releaseYR'])) echo $_POST['filter_releaseYR'];?>"><br>
<label id="genre1" for="genre">Genre:</label><br>
<input type="text" id="genre2" name="genre" value="not in prototype"><br>
<label id="language1" for="filter_language">Language:</label><br>
<input type="text" id="language2" name="filter_language" value="<?php if(!empty($_POST['filter_language'])) echo $_POST['filter_language'];?>"><br>
<label id="director1" for="filter_director">Director:</label><br>
<input type="text" id="director2" name="filter_director" value="<?php if(!empty($_POST['filter_director'])) echo $_POST['filter_director'];?>"><br>
<label id="actor1" for="actor">Actor:</label><br>
<input type="text" id="actor2" name="actor" value="not in prototype"><br>
<label id="ff" for="filter_foreignFilm"><br>Foreign Film:</label><br>
  <select id="foreignFilm" name="filter_foreignFilm">
    <option value="all">All</option>
    <option value="yes">Only FF</option>
    <option value="no">Exclude FF</option>
  </select>
  
<br><br>
  <input id="mov_button" type="submit" value="Submit" form="movie_filter">
  
  <!-- <input id="mov_button" type="submit" value="Submit"> -->
  <input type="hidden" name="movie_filter" value="TRUE" />
</form>






<!-- ADD OR EDIT DVDs -->

<form method="post">
<label id="dvd_input1" for="dvdNum_imp">DVD Number:</label><br>
<input id="dvd_input2" type="text"  name="dvdNum_inp"><br>
<label id="dvd_input3" for="category_inp">DVD Category:</label><br>
	<select id="dvd_input4" name="category_inp">
		<option value="empty"></option>
		<option value="Current Hit">Current Hit</option> 
		<option value="Current Release">Current Release</option>
		<option value="Popular">Popular</option> 
		<option value="Regular">Regular</option>
	</select>
<label id="dvd_input5" for="numOfMovies_inp">Movies on DVD:</label><br>
	<select id="dvd_input6" name="numOfMovies_inp">
		<option value="any"></option>
		<option value="one"> 1</option>
		<option value="multiple">>1</option>
	</select>
<label id="dvd_input7" for="writtenOff_inp">Written Off?:</label><br>  
	<select id="dvd_input8" name="writtenOff_inp">
		<option value="all"></option>
		<option value="yes">Yes</option>
		<option value="no">No</option>
	</select>
  <input id="dvd_input9" type="submit" value="Submit">
  <input type="hidden" name="submitted_edit" value="TRUE" />
</form>


<!-- ASSOCIATE MOVIES TO DVDs -->

<h4 id='movie_dvd_title'>Associating Movies With DVDs</h4>

<form method="post">
<label id="moviesondvd_input1" for="dvdNum_ass">DVD Number:</label><br>
<input id="moviesondvd_input2" type="text"  name="dvdNum_ass"><br>
<label id="moviesondvd_input3" for="titleNum_ass">Title Number:</label><br>
<input id="moviesondvd_input4" type="text"  name="titleNum_ass"><br>

<input type="radio" id="moviesondvd_input5" name="mov_dvd" value="mov_dvd_addedit" checked>
<label id="moviesondvd_input6" for="mov_dvd_addedit">Add</label><br>
<input type="radio" id="moviesondvd_input7" name="mov_dvd" value="mov_dvd_rem">
<label id="moviesondvd_input8" for="mov_dvd_rem">Remove</label><br>

  <input id="moviesondvd_input9" type="submit" value="Submit">
  <input type="hidden" name="submitted_mov_dvd" value="TRUE" />
</form>









<!-- ADD OR EDIT MOVIES -->

<form method="post">
<label id="mov_input1" for="titleNum_edit">Movie Number:</label><br>
<input id="mov_input2" type="text"  name="titleNum_inp_edit"><br><br>
<label id="mov_input3" for="title_inp_edit">Movie Title:</label><br>
<input id="mov_input4" type="text" name="title_inp_edit"><br><br>
<label id="mov_input5" for="releaseYR_inp_edit">Release Year:</label><br>
<input id="mov_input6" type="text" name="releaseYR_inp_edit"><br><br>
<label id="mov_input7" for="duration_inp_edit">Duration (minutes):</label><br>
<input id="mov_input8" type="text" name="duration_inp_edit"><br><br>
<label id="mov_input9" for="language_inp_edit">Language:</label><br>
<input id="mov_input10" type="text" name="language_inp_edit"><br><br>
<label id="mov_input11" for="foreignFilm_inp_edit">Foreign Film?:</label><br>
  <select id="mov_input12" name="foreignFilm_inp_edit">
    <option value="all"></option>
    <option value="no">No</option>
    <option value="yes">Yes</option>
  </select>

<label id="mov_input13" for="director_inp_edit">Director:</label><br>
<input id="mov_input14" type="text" name="director_inp_edit"><br><br>
<label id="mov_input15" for="genre_inp_edit">Genre:</label><br>
<input id="mov_input16" type="text" name="genre_inp_edit" value="not in prototype"><br><br>
<label id="mov_input17" for="actor_inp_edit">Actor:</label><br>
<input id="mov_input18" type="text" name="actor_inp_edit" value="not in prototype"<br><br>
  <input id="mov_input19" type="submit" value="Submit">
  <input type="hidden" name="submitted_edit_movie" value="TRUE" />
</form>

<h4 id='movie_add_title'>Add New Movie or Edit Existing</h4>


<div class="dvd_help1">
<img id="dvd_help1_icon" src="help_icon.jpg" alt="Help"></img>
<div>DVD RECORD CREATION<br>This section creates a new DVD placeholder.<br>Information can be enterred if known at time of creation or be editted later.<br>
		Movies are associated with DVDs in the next section below.<br><br>There are no required fields.  Select the SUBMIT button with an empty DVD NUMBER field to create a new record
		<br>or<br>Enter a DVD NUMBER with field adjustments to edit an existing record.
		</div></div>

<div class="dvd_help2">
<img id="dvd_help2_icon" src="help_icon.jpg" alt="Help"></img>
<div>ASSOCIATING MOVIES WITH DVDs<br>Set the radio button to<br>ADD a movie to a DVD<br>OR<br>REMOVE a movie from a DVD<br><br>
		Enter the appropriate DVD NUMBER and MOVIE NUMBER.
		</div></div>
		
<div class="dvd_help3">
<img id="dvd_help3_icon" src="help_icon.jpg" alt="Help"></img>
<div>MOVIE RECORD CREATION<br>This section creates a new Movie placeholder.<br>Information can be enterred if known at time of creation or be editted later.<br>
		Movies are associated with DVDs in the section above.<br><br>There are no required fields.  Select the SUBMIT button with an empty MOVIE NUMBER field to create a new record
		<br>or<br>Enter a MOVIE NUMBER with field adjustments to edit an existing record.
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