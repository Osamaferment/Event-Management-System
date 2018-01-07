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
    <title>My Events
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
            <ul class="nav navbar-nav">
              <li>
                <a href="browse_events.php">Browse Events
                </a>
              </li>
              <li>
                <a href="my_tickets.php">My Tickets
                </a>
              </li>
              <li class="active">
                <a href="my_events.php">My Events
                </a>
              </li>
              <li>
                <a href="create_event.php">Create an Event
                </a>
              </li>
            </ul>
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
    <div id="wrapper">
      <div class="container">
        <div class="row">
          <div class="col-lg-12" style="width: 500px">
            <body>
              <div id="event_view">
                <div class="panel-body">
                  <div class="dataTable_wrapper">
                    <h3>My Events
                    </h3>
                    <hr>
                    <div class="table">                                         
                      <table style="font-size: 13px; width: 100%; " class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                          <tr>
                            <th>Event ID
                            </th>
                            <th style="min-width: 100px;">Name
                            </th>
                            <th style="min-width: 150px;">Description
                            </th>
                            <th style="min-width: 100px;">Location
                            </th>
                            <th style="min-width: 100px;">Event Date
                            </th>
                            <th style="min-width: 100px;">Category
                            </th>
                            <th>Tickets Allocated
                            </th>
                            <th>Tickets Remaining
                            </th>
                            <th style="min-width: 100px;">Last Purchase Date
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Retrieve all events from the events table where the user ID is the logged in user.
                        $query = "SELECT * FROM events, event_categories, addresses WHERE events.address_id = addresses.address_id and events.event_category_id = event_categories.category_id and events.created_by_user_id=".$userRow['user_id'];
                        $res = mysql_query($query);
                        while ($row = mysql_fetch_assoc($res)) {
                        ?>
                          <tr>
                            <td>
                              <?php echo $row['event_id']; ?>
                            </td> 
                            <td>
                              <?php echo $row['name']; ?>
                            </td> 
                            <td>
                              <?php echo $row['description']; ?>
                            </td>
                            <td>
                              <?php echo $row['building_name_number'].", ".$row['post_code'] ?>
                            </td>                                
                            <td>
                              <?php echo $row['event_date_time']; ?>
                            </td>
                            <td>
                              <?php echo $row['category_name']; ?>
                            </td>
                            <td>
                              <?php echo $row['ticket_allocation']; ?>
                            </td>
                            <td>
                              <?php echo $row['tickets_remaining']; ?>
                            </td>
                            <td>
                              <?php echo $row['last_purchase_date']; ?>
                            </td>
                          </tr> 
                        <?php // while loop closing brace
                        }?>
                        </tbody>
                      </table>
                    </div>
                  </div>                       
                </div>                   
              </div>    
            </body>
          </div>
        </div>
      </div>
    </div>
    <script src="assets/jquery-1.11.3-jquery.min.js">
    </script>
    <script src="assets/js/bootstrap.min.js">
    </script>
  </body>
</html>
<?php ob_end_flush(); ?>
