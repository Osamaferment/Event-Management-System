<?php
	ob_start();
	session_start();

	if( isset($_SESSION['user'])!="" ){
		header("Location: home.php");
	}

	include_once 'dbconnect.php';
	$error = false;

	if ( isset($_POST['btn-signup']) ) {
		// The user's input is cleaned to prevent SQL injections
		$name = trim($_POST['name']);
		$name = strip_tags($name);
		$name = htmlspecialchars($name);
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		$security_question = trim($_POST['security_question']);
		$security_question = strip_tags($security_question);
		$security_question = htmlspecialchars($security_question);
		$security_answer = trim($_POST['security_answer']);
		$security_answer = strip_tags($security_answer);
		$security_answer = htmlspecialchars($security_answer);

		// Ensuring that the email is of the correct format.
		if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		} 
		else {
			// Checks whether the entered email already exists.
			$query = "SELECT email FROM users WHERE email='$email'";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			if($count!=0){
				$error = true;
				$emailError = "Provided Email is already in use.";
			}
		}
		// Checking that the password is not empty and has a minimum size of 6.
		if (empty($pass)){
			$error = true;
			$passError = "Please enter password.";
		} 
		else if(strlen($pass) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		if (empty($security_question)){
			$error = true;
			$securityQuestionError = "Please fill in the security question.";
		}
		if (empty($security_answer)){
			$error = true;
			$securityAnswerError = "Please fill in the security answer.";
		}
		// if there's no error, continue to signup
		if( !$error ) {
			$query = "INSERT INTO users(email,password,security_question,security_answer) VALUES('$email','$pass','$security_question', '$security_answer')";
			$res = mysql_query($query);
			if ($res) {
				$errTyp = "success";
				$errMSG = "Successfully registered, you may login now";
				unset($email);
				unset($pass);
				unset($security_question);
				unset($security_answer);
			} 
			else {
				$errTyp = "danger";
				$errMSG = $res;
				print_r($res);	
			}	
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Event Management System
    </title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
    <link rel="stylesheet" href="style.css" type="text/css" />
  </head>
  <body>
    <div class="container">
      <div id="login-form">
        <form name="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validatePass()" autocomplete="off">
          <div class="col-md-12">
            <div class="form-group">
              <h2 class="">Sign Up.
              </h2>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <?php
			if ( isset($errMSG) ) {
			?>
            <div class="form-group">
              <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
                <span class="glyphicon glyphicon-info-sign">
                </span> 
                <?php echo $errMSG; ?>
              </div>
            </div>
            <?php
			}
			?>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-envelope">
                  </span>
                </span>
                <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
              </div>
              <span class="text-danger">
                <?php echo $emailError; ?>
              </span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-lock">
                  </span>
                </span>
                <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="15" />
              </div>
              <span class="text-danger">
                <?php echo $passError; ?>
              </span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-lock">
                  </span>
                </span>
                <input type="password" name="pass_confirm" class="form-control" placeholder="Confirm Password" maxlength="100" />
              </div>
              <span class="text-danger">
                <?php echo $passError; ?>
              </span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-lock">
                  </span>
                </span>
                <input type="text" name="security_question" class="form-control" placeholder="Security Question" maxlength="45" value="<?php echo $security_question ?>"/>
              </div>
              <span class="text-danger">
                <?php echo $securityQuestionError; ?>
              </span>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-lock">
                  </span>
                </span>
                <input type="text" name="security_answer" class="form-control" placeholder="Security Answer" maxlength="45" value="<?php echo $security_answer ?>"/>
              </div>
              <span class="text-danger">
                <?php echo $securityAnswerError; ?>
              </span>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up
              </button>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <a href="index.php">Sign in Here...
              </a>
            </div>
          </div>
        </form>
      </div>	
    </div>
  </body>
</html>
<?php ob_end_flush(); ?>
<script>
  function validatePass(){
  	// Validates that the entered passwords match and prompts the user if they do not.
    var a = document.forms["registerForm"]["pass"].value;
    var b = document.forms["registerForm"]["pass_confirm"].value;
    if (a!=b){
      alert("Passwords do not match.");
      return false;
    }
  }
</script>
