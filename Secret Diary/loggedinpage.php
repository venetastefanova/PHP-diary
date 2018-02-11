<?php
	session_start();
	//looking for id in the cookie array
	if(array_key_exists("id", $_SESSION)){
		//if the value id in session arary exists it shows we are logged in and shows link to logout
		echo "Logged in! <a href='index.php?logout=1'> Log out</a></p>";
	}

	//if session ID exists
	if($_SESSION['id']){
		include("dbconnection.php");
		$query = "SELECT `email`, `diary` FROM `users` WHERE id='$_SESSION[id]'"; //selects the email, diary from users ACCORDING TO SESSION IDs
        $row = mysqli_fetch_array( mysqli_query( $link, $query ) );
		$diary=$row["diary"];
		$email=$row["email"];
	}
	else{
		header("Location: index.php");
	}

	include("header.php");
?>
	<div class="container-fluid">
		<textarea id="diary" name ="diary-content" class="form-control"></textarea>
	</div>

<?php	
	include("footer.php");
?>