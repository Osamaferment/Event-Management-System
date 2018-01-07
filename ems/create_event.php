<?php
    ob_start();
    session_start();
    require_once 'dbconnect.php';
    // If the SESSION is not set, the user is redirected to the homepage to login.
    if( !isset($_SESSION['user']) ) {
        header("Location: index.php");
        exit;
    }
    // User's details are retrieved.
    $res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']);
    $userRow=mysql_fetch_array($res);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Create an event
    </title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
    <link rel="stylesheet" href="style.css" type="text/css" />
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li>
              <a href="browse_events.php">Browse Events
              </a>
            </li>
            <li>
              <a href="my_tickets.php">My Tickets
              </a>
            </li>
            <li>
              <a href="my_events.php">My Events
              </a>
            </li>
            <li  class="active">
              <a href="create_event.php">Create an Event
              </a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user">
                </span>&nbsp;
                <!-- Display user's email in the top right corner. -->
                <?php echo $userRow['email']; ?>&nbsp;
                <span class="caret">
                </span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="logout.php?logout">
                    <span class="glyphicon glyphicon-log-out">
                    </span>&nbsp;Sign Out
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!--/.nav-collapse -->
      </div>
    </nav> 
    <div id="login-form">
      <form name="myForm" method="post" action="model/create_event.php" onsubmit="return validateForm()" autocomplete="off">
        <div id="wrapper">
          <div class="container">
            <div class="row">
              <div class="col-lg-9" style="width: 500px">
                <body>
                  <div class="col-md-12">
                    <div class="form-group">
                      <h2 class="">Create your event
                      </h2>
                    </div>
                    <div class="form-group">
                      <hr />
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="text" name="name" class="form-control" placeholder="Name of Event" maxlength="50" value="<?php echo $name ?>" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="text" name="description" class="form-control" placeholder="Event Description" maxlength="40" value="<?php echo $description ?>" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="text" name="building_name_no" class="form-control" placeholder="Building Name/No." maxlength="40" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="text" name="post_code" class="form-control" placeholder="Post Code" maxlength="40" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">Event date
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="datetime-local" name="date" class="form-control" placeholder="Enter the date" maxlength="40" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input  name="category" placeholder="Enter the category" class="form-control" list="categories"  maxlength="40" value="">
                        <datalist id="categories">
                        <?php
                        // Retrieve all event categories for the category drop down menu.
                        $query = "SELECT * FROM event_categories";
                        $res = mysql_query($query);
                        $categories = array();
                        while ($row = mysql_fetch_assoc($res)) {
                          array_push($categories, $row['category_name']);
                        ?>
                          <option value = "<?php echo $row['category_name']; ?>" />                           
                          <?php
                        }
                        ?>
                        </datalist>
                        <input  type="hidden" name="categories_list" value="<?php echo implode(", ",$categories); ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="number" name="tickets_available" class="form-control" placeholder="No. tickets available" maxlength="40" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon">Last purchase date
                          <span class="glyphicon ">
                          </span>
                        </span>
                        <input type="datetime-local" name="end_date" class="form-control" placeholder="Enter the end date" maxlength="40" />
                      </div>
                    </div>
                    <div class="form-group">
                      <hr />
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Submit and create event
                      </button>
                    </div>
                  </div>
                </body>
              </div>
            </div>
          </div>
          </form>
        </div>  
    </div>
    <script src="assets/jquery-1.11.3-jquery.min.js">
    </script>
    <script src="assets/js/bootstrap.min.js">
    </script>
  </body>
</html>
<?php ob_end_flush(); ?>
<script>
  function validateForm() {
    var categories_list = document.forms["myForm"]["categories_list"].value;
    // Validates and prompts all fields be non-empty.
    // Validates and prompts for a final purchase date that comes before the event date.
    var a = document.forms["myForm"]["name"].value;
    var b = document.forms["myForm"]["description"].value;
    var c1 = document.forms["myForm"]["building_name_no"].value;
    var c2 = document.forms["myForm"]["post_code"].value;
    var d = document.forms["myForm"]["category"].value;
    var e = document.forms["myForm"]["tickets_available"].value;
    var x = new Date(document.forms["myForm"]["date"].value);
    var y = new Date(document.forms["myForm"]["end_date"].value);

    var str = "";

    if(!categories_list.search(d))
      str = str.concat("Category must be selected from list.\n");

    if (a == "" || b == "" || c1 == "" || c2 == "" || d == ""  || e == "" || x == ""  || y == "") {
      str = str.concat("Please correctly fill in all the fields");
      if ( y > x )
        str = str.concat("\nFinal purchase date must be before the event date");
      alert(str);
      return false;
    }
    else if ( y > x ){
      str = "\nFinal purchase date must be before the event date";
      alert(str);
      return false;
    }
    else{
      alert("Your event has been created!\n");
    }
  }
</script>
