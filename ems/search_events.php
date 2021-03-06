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
                            $query = "SELECT * FROM events, event_categories, addresses WHERE events.address_id = addresses.address_id and events.event_category_id = event_categories.category_id and event_date_time >= '".$date_from."' and event_date_time <= '".$date_to."'";
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
                            // User can only book a ticket if it is still before the last purchase date,
                            $date_time_now = date("Y-m-d h:i:s"); 
                            $tickets_remaining = $row['tickets_remaining'];
                            if ($date_time_now < $row['last_purchase_date'] && $tickets_remaining > 0){
                            ?>
                              <form name="myForm" method="post" action="model/book_ticket.php" onsubmit="return bookTicket()" autocomplete="off">
                                <input type="hidden" name="eventId" class="form-control" value="<?php echo $row['event_id'] ?>" />
                                <input type="hidden" name="end_date" class="form-control" value="<?php echo $row['tickets_remaining'] ?>" />                                               
                                <input type="hidden" name="tickets_remaining" class="form-control" value="<?php echo $row['tickets_remaining'] ?>" />
                                <input type="number" name="tickets_bought" list="tickets_bought" value="">
                                <datalist id="tickets_bought">
                                  <?php
                                $max_bookable = 10;
                                // The maximum number of tickets that can be booked is 10. 
                                // If the remaining tickets is less than 10 then only up to that amount can be booked.
                                if($tickets_remaining < $max_bookable)
                                $max_bookable = $tickets_remaining < $max_bookable;
                                $count = 1;
                                while ($count <= $max_bookable) {
                                ?>
                                  <option value = "<?php echo $count++; ?>" />                           
                                  <?php
                                }
                                ?>
                                </datalist>
                                <input type="hidden" name="max_bookable" class="form-control" value="<?php echo $max_bookable?>" />
                                <button type="submit" class="btn btn-sm btn-block btn-primary" name="btn-signup">Book ticket(s)
                                </button>                                   
                              </form>
                              <?php 
                            }
                            else{
                                // Book ticket button will not be shown if there aren't any tickest remaining or 
                                // it is past the last purchase date.
                                echo "N/A - ";
                                if ($tickets_remaining <= 0)
                                    echo "Sold out.";
                                if ($date_time_now > $row['last_purchase_date'])
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
<script type="text/javascript">
  
        function bookTicket(){
        // Validation checks for ticket count selection
        // User can only book up to a maximum of 10 tickets
        // A negative or empty input will not be accepted and the user will be 
        // prompted to enter a valid input.
        var a = document.forms["myForm"]["tickets_bought"].value;
        var b = document.forms["myForm"]["max_bookable"].value;
        if(a <= 0 || a == ""){
          alert("Please enter a valid number of tickets to buy. " + a);
          return false;
        }
        else{
          alert("Your ticket has been booked!");
        }
      }
    </script>   

</script>