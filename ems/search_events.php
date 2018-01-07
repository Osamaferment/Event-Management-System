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
    <title>Search results
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
              <li class="active">
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
              </div>    
              <div class="panel-body">
                <div class="dataTable_wrapper">
                  <h3>Search results
                  </h3>
                  <hr>
                  <br>
                  <div class="table">                                         
                    <table style="font-size: 13px; width: 100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                          <th>Book
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // Returns category search results if category was searched for
                        if( isset($_POST['category']) ) {
                            $category_input = "";
                            if($_POST['category'] != "All"){
                                $category_input = $_POST['category'];
                            }
                            // Category key word searched
                            $query = "SELECT * FROM events, event_categories, addresses WHERE events.address_id = addresses.address_id and events.event_category_id = event_categories.category_id and category_name LIKE '%".$category_input."%'";
                            $res = mysql_query($query);
                        }
                        // Returns events within specified time frame if date filter was applied.
                        if( isset($_POST['search_date_from']) ) {
                            $date_from = $_POST['search_date_from']." 00:00:00";
                            $date_to = $_POST['search_date_to']." 23:59:59"; 
                            $query = "SELECT * FROM events WHERE event_date_time >= '".$date_from."' and event_date_time <= '".$date_to."'";
                            $res = mysql_query($query);
                        }
                        while ($row = mysql_fetch_assoc($res)) {
                        ?>
                        <tr>
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
                          <td style="min-width:100px">
                            <?php 
                            // A "book ticket" button will only appear if the user can still book a ticket for the event
                            // This applies if there are still tickets left and the last purchase date still hasn't passed.
                            $date_time_now = date("Y-m-d h:i:s"); 
                            $tickets_remaining = $row['tickets_remaining'];
                            if ($date_time_now < $row['last_purchase_date'] && $tickets_remaining > 0 && $row['created_by_user_id'] != $_SESSION['user']){
                            ?>
                            <form name="myForm" method="post" action="model/book_ticket.php" onsubmit="bookTicket()" autocomplete="off">
                                <!-- Event ID and ticket remaining values are sent in the POST request to update the events row
                                 and to insert a row to the event bookings table -->
                              <input type="hidden" name="eventId" class="form-control" value="<?php echo $row['event_id'] ?>" />
                              <input type="hidden" name="end_date" class="form-control" value="<?php echo $row['tickets_remaining'] ?>" />                                               
                              <input type="hidden" name="tickets_remaining" class="form-control" value="<?php echo $row['tickets_remaining'] ?>" />
                              <button type="submit" class="btn btn-sm btn-block btn-primary" name="btn-signup">Book ticket
                              </button>                                   
                            </form>
                            <?php 
                            }
                            else{
                                echo "N/A - ";
                                // HTML that appears for the cases where a ticket cannot be booked.
                                if ($tickets_available <= 0)
                                    echo "Sold out.";
                                if ($today > $end_date)
                                    echo "Past end date.";
                            }
                            ?>
                          </td>
                        </tr> 
                        <?php // while loop closing brace
                        }?>
                      </tbody>
                    </table>
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
