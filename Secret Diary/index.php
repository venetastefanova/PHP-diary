<?php
	//start the session
	session_start();
	$error="";
	$query="";
	//if there is the logout in the URL (checking from $_GET), removes the session and makes the cookie in the past
	if(array_key_exists("logout",$_GET)){
		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"]="";
	} // if there isn't a logged in it redirects to the loggedinpage.php
	else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])){
		header("Location: loggedinpage.php");
	}
	


	//checks if there is a submit in the post array
	if(array_key_exists("submit", $_POST)){
		//connects to DB
		include("dbconnection.php");
		
		if(!$_POST['email']){
			$error = $error."An email address is required";
		}
		if(!$_POST['password']){
			$error = $error."A password is required";
		}
		
		if($error!=""){
			$error="<p>There were error(s) in your form:</p>";
		}
		else{
//******************IF YOU TICK THE CHECKBOX FOR SIGN UP***************			
			if($_POST['signUp'] == '1'){
				 $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
				 $result = mysqli_query($link, $query);

					if (mysqli_num_rows($result) > 0) {
						$error = "That email address is taken.";
					}
					else {
					  $query = "INSERT INTO `users` (`email`, `password`) VALUES 
					  ('".mysqli_real_escape_string($link, $_POST['email'])."','".mysqli_real_escape_string($link, $_POST['password'])."')";

							if(!mysqli_query($link,$query)){
								$error="<p>Could not sign you up, please try again later.</p>";
							}
							else{
								//makes the secure password and saves to the DB
								$query = "UPDATE `users` SET password = '".password_hash($_POST['password'], PASSWORD_DEFAULT)."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
								mysqli_query($link, $query);
								//gets the ID of the user
								$_SESSION['id'] = mysqli_insert_id($link);
									//checks if the checkbox has  A TICK and creates a cookie to stay logged in
								 if(isset($_POST["stayLoggedIn"])) {
								if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
								}
                        } 
								//redirects to the  loggedinpage.php
									header("Location: loggedinpage.php");
							}
						
					}
							
			}
//******************IF YOU don't TICK THE CHECKBOX FOR SIGN UP***************			
			else{ // selects the whole row that has the email, makes an array with the result row
				 $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
				//checks if the id exists and hashes the password
				if(isset($row)){
					$hashedPassword=password_hash($_POST['password'], PASSWORD_DEFAULT);
					// if the password matches the one in the row it makes session id and row id the same
					$hash=$row['password'];
					if(password_verify($_POST['password'],$hash)){
						$_SESSION['id']=$row['id'];
						if(isset($_POST["stayLoggedIn"])) {
						if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
						}
                        } 
								//redirects to the  loggedinpage.php
						header("Location: loggedinpage.php");
					}
					else{//shows if you put wrong email address
						$error="that password/email combo can't be found";
					}
						
					}else{//shows if you put 
					  $error = "That email/password combination could not be found.";
					
				}
                    
                }
            
        }
      
        
    }		
?>
<?php include("header.php");?>


<div id="homePageContainer" class="container">
	    <h1> Secret Diary</h1>
	
		<p><strong>Store your thoughts permanently and securely!</strong></p>
		<div id="error"><?php echo $error; ?></div>
	
	
		<form method="post" id="signUpForm">
			<p>Interested? Sign up now for free!</p>
			 <fieldset class="form-group">
				<input  class="form-control" type="email" name="email" placeholder="Your Email">
			</fieldset>
			
			<fieldset class="form-group">
				<input  class="form-control" type="password" name="password" placeholder="Password">
			</fieldset>
			
			 <div class="checkbox">  
				<label>   
					<input type="checkbox" name="stayLoggedIn" value=1> Stay logged in           
				</label>        
			</div>
    
			<fieldset class="form-group">
				<input type="hidden" name="signUp" value="1">
				<input class="btn btn-success" type="submit" name="submit" value="Sign Up!"> 
			</fieldset>
			
			<p><a class="toggleForms">Already have an account? Log In!</a></p>
		</form>

		<form method="post" id="logInForm">
			<p>Sign up with your email and password!</p>
			  <fieldset class="form-group">
				<input  class="form-control" type="email" name="email" placeholder="Your Email">
			  </fieldset>
			
			  <fieldset class="form-group">
				<input  class="form-control" type="password" name="password" placeholder="Password">
			</fieldset>
			
			  <div class="checkbox">  
				<label>  
					<input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
				 </label>
			</div>
			
			<fieldset class="form-group">
				<input type="hidden" name="signUp" value="0">
				<input  class="btn btn-success" type="submit" name="submit" value="Log In!">
			</fieldset>
			
		<p><a class="toggleForms">Sign Up!</a></p>

		</form>
	  </div>

<?php include("footer.php");?>