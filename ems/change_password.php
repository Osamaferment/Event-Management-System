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
    // Error Message will display if the security answer is incorrect.
    $errorMsg = "";
    // Fetches email of user from previous php page to fetch user information.
    $email = $_SESSION['user_email_change_password'];
    $res=mysql_query("SELECT * FROM users WHERE email='$email'");
    $row=mysql_fetch_array($res);   
    $email = $row['email'];
    $security_question = $row['security_question'];
    $security_answer = $row['security_answer'];

    // If user selected the change password button it is checked whether the inputted security answer is correct
    if( isset($_POST['btn-change_password']) ) {    
        $security_answer_input = $_POST['security_answer'];
        $security_answer_input = strip_tags($security_answer_input);
        $security_answer_input = htmlspecialchars($security_answer_input);
        $pass_input = $_POST['pass'];
        $confirm_pass_input = $_POST['pass_confirm'];
        $res=mysql_query("SELECT * FROM users WHERE email='$email' and security_answer = '$security_answer_input'");
        $row=mysql_fetch_array($res);
        $count = mysql_num_rows($res); 
        // Count will be 0 if the security answer is correct
        if( $count == 1 ) {
            // Since a row is returned, the security answer was correct and the user's password is updated.
            $res=mysql_query("UPDATE users SET password ='$pass_input' WHERE email='$email'");
            $_SESSION['pass_changed'] = "Password successfully changed.";
            header("Location: index.php");
        }
        else{
            $errMSG = "You gave an incorrect security answer.";
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
        <form name="ChangePasswordForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validatePass()" autocomplete="off">
          <div class="col-md-12">
            <div class="form-group">
              <h3 class="">Enter the security question and create a new password.
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
            ?>
            <div>
              <div class="input-group">
                <input type="hidden" name="email" class="form-control" value="<?php echo $email ?>"/>
                <span class="input-group-addon">
                  <span class="" value = "<?php echo $email; ?>">
                  </span> 
                  <?php echo $email; ?>
                </span>
              </div>
            </div>
            <div class="form-group">
              <hr />
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="hidden" name="security_question" class="form-control" value="<?php echo $security_question ?>"/>
              </div>
              <span style="font-weight: bold;">
                <span class="glyphicon">
                </span>Q. 
                <?php echo $security_question; ?>
              </span>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <span class="glyphicon glyphicon-lock">
                </span>
              </span>
              <input type="text" name="security_answer" class="form-control" placeholder="Security Answer" maxlength="45" value=""/>
            </div>
            <span class="text-danger">
              <?php echo $securityAnswerError; ?>
            </span>
          </div>  
          <div class="form-group">
            <hr />
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
          <br>            
          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" name="btn-change_password" value="Change_Password">Change Password
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
<script>
  function validatePass(){
    // It is checked that the two inputted passwords match before checking if the security answer is correct.
    var a = document.forms["ChangePasswordForm"]["pass"].value;
    var b = document.forms["ChangePasswordForm"]["pass_confirm"].value;
    if (a!=b){
      alert("Passwords do not match.");
      return false;
    }
  }
</script>
