<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
    // If the SESSION is not set, the user is redirected to the homepage to login.
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: index.php");
		exit;
	}
	$error = false;
	if( isset($_POST['btn-forgot_password']) ) {	
		// If the forgot password link is selected the user is prompted to enter their email.
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		if(empty($email)){
			$error = true;
			$emailError = "Please enter your email address.";
		} 
		else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		}
		// If input is not empty, it is checked whether the email exists in the users table.
		if (!$error) {
			$res=mysql_query("SELECT * FROM users WHERE email='$email'");
			$row=mysql_fetch_array($res);
			$count = mysql_num_rows($res); 
			// If the count is 1, the entered email exists and therefore we store it in SESSIONs to retrieve in the 
			// next php page: change_password.php
			if( $count == 1 ) {
				$_SESSION['user_email_change_password'] = $row['email'];
				empty($_SESSION['user']);
				header("Location: change_password.php");
			} 
			else {
				$error = true;
				// Otherwise the error message is set to be displayed
				$errMSG = "The entered email does not exst. Try again.";
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
        <form method="post" autocomplete="off">
          <div class="col-md-12">
            <div class="form-group">
              <h2 class="">Forgot your password?
              </h2>
              <h4 class="">Enter your email to answer your security question
                </h2>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <?php
			if ( isset($errMSG) ) {
				// If the email does not exist an appropriate message is shown on the same page.
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
			?>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-envelope">
                  </span>
                </span>
                <input type="text" name="email" class="form-control" placeholder="Your Email" maxlength="40" />
              </div>
              <span class="text-danger">
                <?php echo $emailError; ?>
              </span>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-block btn-primary" name="btn-forgot_password" value="Next">Next
              </button>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <a href="index.php">Back
              </a>
            </div>
          </div>
        </form>
      </div>	
    </div>
  </body>
</html>
<?php ob_end_flush(); ?>
