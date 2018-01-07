    <?php
        ob_start();
        session_start();
        require_once '../dbconnect.php';

        // if session is not set this will redirect to login page
        if( !isset($_SESSION['user']) ) {
            header("Location: ../index.php");
            exit;
        }

        if( isset($_POST['name']) && isset($_POST['description']) &&
            isset($_POST['building_name_no']) && isset($_POST['post_code']) && isset($_POST['date']) && 
            isset($_POST['category']) && isset($_POST['tickets_available']) &&
            isset($_POST['end_date']) ) {
            
            // Retrieves category_id using category_name
            $res=mysql_query("SELECT * FROM event_categories WHERE category_name='".$_POST['category']."'");
            $row=mysql_fetch_array($res);
            $category_id = $row['category_id'];

            // Retrieves address_id using address (if it exists)
            $res=mysql_query("SELECT `address_id` FROM `addresses` WHERE `building_name_number`= '".$_POST['building_name_no']."' and `post_code`='".$_POST['post_code']."' LIMIT 1");
            $row=mysql_fetch_array($res);

            $address_id;
            // Checks if the address already exists and if not then
            // it is inserted into the Addresses table
            if(!$row){
                $result = mysql_query("INSERT INTO addresses (building_name_number, post_code)
                 VALUES ('".$_POST['building_name_no']."','".$_POST['post_code']."')");
                if (!$result) {
                    // if query fails
                    die('Invalid query: ' . mysql_error());
                }
                else{
                    // Retrieve newly created address id.
                    $res=mysql_query("SELECT `address_id` FROM `addresses` WHERE `building_name_number`= '".$_POST['building_name_no']."' and `post_code`='".$_POST['post_code']."' LIMIT 1");
                    $row=mysql_fetch_array($res);
                    $address_id = $row['address_id'];
                }
            }
            else{
                $address_id = $row['address_id'];
            }

            $user_id = $_SESSION['user'];

            $result = mysql_query("INSERT INTO events (created_by_user_id, name, description, event_date_time, event_category_id, ticket_allocation, tickets_remaining, address_id, last_purchase_date)
                VALUES ('".$_SESSION['user']."', 
                        '".$_POST['name']."', 
                        '".$_POST['description']."', 
                        '".$_POST['date']."', 
                        '".$category_id."', 
                        '".$_POST['tickets_available']."',
                        '".$_POST['tickets_available']."', 
                        '".$address_id."', 
                        '".$_POST['end_date']."')");
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }
            header("Location: ../my_events.php");
            exit; 
            
        }
        else{
            print_r($_POST);
            exit;  
        }
    ?>
    <?php ob_end_flush(); ?>