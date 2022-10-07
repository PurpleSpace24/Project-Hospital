<?php
require_once "../configs/config.php";

/**
 * @var $link
 */

$room_id = $room_number = $room_name = "";

$roomData['room_number'] = "";
$roomData['room_name'] = "";
$roomData['room_id'] = "";
$array = array();
/*------read from table----------*/

//sql for getting info from director
$sql_show = "SELECT room_id, room_number, room_name FROM rooms";
$stmt_show = $link->query($sql_show);
//check if there is any data
if ($stmt_show->num_rows > 0) {
    while ($row = $stmt_show->fetch_assoc()) {
        //store every row in the array
        $array[] = $row;
    }
}
/*----------END-----------*/

if (isset($_POST['add_room'])) {

    if (empty($_POST['room_number']) || empty($_POST['room_name'])) {
        $message = "Fill in all fields first!";
        echo "<script>
                alert('$message');
                window.location.href='crud_rooms.php';
                </script>";
    } else {

        $room_number = $_POST['room_number'];
        $room_name = $_POST['room_name'];

        $select = mysqli_query($link, "SELECT * FROM rooms WHERE room_number = '" . $room_number . "'");
        if (mysqli_num_rows($select)) {
            $msg = "Room number = " . $room_number . " already exist!";
            echo "<script>
                alert('$msg');
                window.location.href='crud_rooms.php';
                </script>";

        } else {
            $sql_add_room = "INSERT INTO rooms(room_number, room_name) VALUES ('$room_number', '$room_name')";

            if (mysqli_query($link, $sql_add_room)) {
                header("location: crud_rooms.php");
            } else {
                $reg_err = "Room failed to be added.";
            }
        }

    }
    mysqli_close($link);
}

if (isset($_POST['update_room'])) {
    $roomID = $_POST['room_id'];
    $sql = "SELECT * FROM rooms WHERE room_id = '$roomID'";

    $stmt = $link->query($sql);

    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $roomData['room_id'] = $row['room_id'];
            $roomData['room_number'] = $row['room_number'];
            $roomData['room_name'] = $row['room_name'];
        }

    } else {
        //databse has no such ID
        $msg = "No such room with ID = " . $roomID;
        //pop up message warning
        echo "<script>
                    alert('$msg');
                    window.location.href='crud_rooms.php';
                 </script>";
    }
}

if (isset($_POST['update_info'])) {
    $room_number = $_POST['room_number'];
    $new_room_name = $_POST['room_name'];
    $old_room_name = $_POST['old_room_name'];


    if (empty($new_room_name)) {
        $new_room_name = $room_name;
    }

    $sql_update = "UPDATE `rooms` SET `room_name`='$new_room_name' WHERE `room_number`='$room_number'";
    mysqli_query($link, $sql_update);

    header("Location: crud_rooms.php");

    mysqli_close($link);
}

if (isset($_POST['delete_room'])) {
    $room_id = $_POST['room_id_delete'];

    //sql for deleting from table rooms
    $sql_delete_room = "DELETE FROM rooms WHERE `room_id` = '$room_id'";
    mysqli_query($link, $sql_delete_room);
    //sql for deleting from table user_access
    #$sql_mr = "DELETE FROM user_access WHERE `user_id` = '$dir_id'";

    header("Location: crud_rooms.php");

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital VY</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Srisakdi" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css"/>
    <link rel="stylesheet" href="../assets/typography.css"/>
    <link rel="stylesheet" href="../assets/responsive.css"/>

</head>

<body>
<?php
include("../header/headerAdmin.php");
?>
<hr>
<form method="post">
    <section class="read_section">
        <h2 class="title-display-all-info-people">All Rooms</h2>
        <div class="style-table">
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Number</th>
                    <th>Type</th>
                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";

                    echo "<td>" . $val['room_id'] . "</td>";
                    echo "<td>" . $val['room_number'] . "</td>";
                    echo "<td>" . $val['room_name'] . "</td>";

                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>

    <h2 class="title-second">Room Information</h2>
    <div class="col-names">
        <input type="text" value='<?php echo $roomData['room_number']; ?>' name="room_number" placeholder="Number: ">
    </div>
    <div class="col-gender_blood">
        <div>
            <select class="genders" name="room_name" id="room_name">
                <option disabled selected value>Select type of room</option>
                <option value="Operating room">Operating room</option>
                <option value="ICU">ICU</option>
            </select>
        </div>
    </div>
    <!------------------------- CHANGE ADD_DIRECTOR TO ADD_ROOM IN CSS LATER ------------------------------->
    <?php
    if (isset($_POST['update_room'])) {
        ?>
        <div class="space_around_button">
            <button class="btn" name="add_room" hidden>Add</button>
        </div>
        <div class="col-names">
            <input type="text" value='<?php echo $roomData['room_name']; ?>' name="old_room_name"
                   placeholder="Type: " readonly>
        </div>
        <div class="space_around_button">
            <button class="btn" name="update_info">Update info</button>
        </div>

        <div class="space_around_button">
            <button class="btn" name="cancel">Cancel</button>
        </div>

    <?php } else { ?>
        <div class="space_around_button">
            <button class="btn" name="add_room">Add</button>
        </div>
    <?php } ?>

    <div class="space-admin">
        <div class="col-names space-crud-button">
            <input type="text" name="room_id" placeholder="Room ID: ">
            <button class="btn" name="update_room">Update</button>
        </div>


        <div class="col-names space-crud-button">
            <input type="text" name="room_id_delete" placeholder="Room ID: ">
            <button class="btn" id="btn_delete" name="delete_room">Delete room</button>
        </div>
    </div>
</form>
<hr>
<?php
include("../footer/footer_Admin.php");
?>
<script src="../script.js"></script>
</body>

</html>