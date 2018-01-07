<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
    // If the SESSION is set, the user is redirected to the homepage: Browse events page
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: browse_events.php");
		exit;
	}
	$error = false;
	if( isset($_POST['btn-login']) ) {	
		// If the user clicked the login button, the email and password from the inputs are retrieved.
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
    
		// To prevent sql injections we ensure the inputs are valid / non-empty and of the right email format.
		if(empty($email)){
			$error = true;
			$emailError = "Please enter your email address.";
		} 
		else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		}
		if(empty($pass)){
			$error = true;
			$passError = "Please enter your password.";
		}
		// If there's no error, continue to login
		if (!$error) {
			$res=mysql_query("SELECT user_id, email, password FROM users WHERE email='$email'");
			$row=mysql_fetch_array($res);
			$count = mysql_num_rows($res); 
			// If the count is 1 the user has inputted a correct email and password combination and redirected 
			// to the Browse events page via a recursive call to this page.
			if( $count == 1 && $row['password']==$pass) {
				$_SESSION['user'] = $row['user_id'];
				header("Location: index.php");
			} 
			else {
				$errMSG = "Incorrect Credentials, Try again...";
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
          <div class="col-md-12">
            <div class="form-group">
              <h2 class="">Sign In.
              </h2>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <?php
if ( isset($errMSG) ) {
?>
            <div class="form-group">
              <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign">
                </span> 
                <?php echo $errMSG; ?>
              </div>
            </div>
            <?php
			}
			else if ( isset($_SESSION['pass_changed']) ){
				unset($_SESSION['pass_changed']);
			?>
            <div class="form-group">
              <div class="alert alert-success">
                <span class="glyphicon glyphicon-info-sign">
                </span> Your password has successfully changed. Please login.
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
                <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="40" />
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
                <input type="password" name="pass" class="form-control" placeholder="Your Password" maxlength="15" />
              </div>
              <span class="text-danger">
                <?php echo $passError; ?>
              </span>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-block btn-primary" name="btn-login">Sign In
              </button>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <a href="register.php">Sign Up Here...
              </a>
            </div>
            <div class="form-group">
              <a href="forgot_password.php">Forgot password?
              </a>
            </div>
          </div>
        </form>
      </div>	
    </div>
  </body>
</html>
<?php ob_end_flush(); ?>
