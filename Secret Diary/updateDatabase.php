<?php
    session_start();//works if i add certain Id
	
  	if(array_key_exists("content", $_POST)){
		include("dbconnection.php");
		$diary=mysqli_real_escape_string($link,$_POST['content']);
		$query = "UPDATE `users` SET `diary` = '".$diary."' WHERE `id` = '".$_SESSION["id"]."' LIMIT 1";
		
    mysqli_query($link, $query);

	
	}
?>