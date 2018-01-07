    <?php
        ob_start();
        session_start();
        require_once '../dbconnect.php';

        if( !isset($_SESSION['user']) ) {
            header("Location: ../index.php");
            exit;
        }

        if( isset($_POST['eventId']) ) {

            $date_time_now = date("Y-m-d h:i:s"); 

            $result = mysql_query("INSERT INTO event_bookings (booking_date_time, no_tickets_booked, event_id, user_id)
                VALUES (
                '".$date_time_now."',
                '".$_POST['tickets_bought']."', 
                '".$_POST['eventId']."',  
                '".$_SESSION['user']."')");
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }

            $success = mysql_query("UPDATE events SET tickets_remaining = tickets_remaining - ".$_POST['tickets_bought']." WHERE event_id = ".$_POST['eventId'].
                                  " AND tickets_remaining > 0");
            
                 
            header("Location: ../my_tickets.php");
            exit; 
            
        }
        else{
            // do nothing, show popup?
            exit;  
        }
    ?>
    <?php ob_end_flush(); ?>