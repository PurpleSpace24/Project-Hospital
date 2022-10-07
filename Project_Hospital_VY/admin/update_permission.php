<?php
/**
 * @var $link
 */

//get connection to database
require_once "../configs/config.php";

$array = array();
$array_permissions = array();

$sql_permission = "SELECT * FROM `permissions`";
$stmt_permission = $link->query($sql_permission);
//check if there is any data
if ($stmt_permission->num_rows > 0) {
    while ($row = $stmt_permission->fetch_assoc()) {
        //store every row in the array
        $array_permissions[] = $row;
    }
}

//sql for getting all info from user_access and for username from users to display in update_permission.php
$sql = "SELECT ua.user_id, ua.permission_id, u.username 
        FROM user_access ua 
        JOIN users u 
        ON u.id = ua.user_id
        ORDER BY ua.permission_id ASC";

$stmt = $link->query($sql);

//check if there is any data
if ($stmt->num_rows > 0) {
    while ($row = $stmt->fetch_assoc()) {
        //store every row in the array
        $array[] = $row;
    }
}


//chceck if button 'update_permission' is pressed
if (isset($_POST['update_permission'])) {
    //get user_id AND permission_id from the input text
    $user_id = $_POST['user_id'];
    $permission_id = $_POST['permission_id'];

    //dont allow the admin to change its own permission OR add said permission to someone
    if ($user_id == 9 || $permission_id == 4) {
        //do something? error?
        header("Location: update_permission.php");

    } else {
        //sql for updating permission based on user_id
        $sql = "UPDATE user_access 
                SET permission_id = '$permission_id' 
                WHERE `user_id` = '$user_id'";

        $stmt = $link->query($sql);

        //attempt to update permission
        if (mysqli_query($link, $sql)) {
            $txt = "User $user_id permission was updated to $permission_id";
            $_SESSION['show'] = "<label>$txt</label>";

            //redirect to same page for the table to have the new variant
            header("Location: update_permission.php");
        } else {
            //error updating permision
            echo "Error: " . $sql . "" . mysqli_error($link);
        }
    }
}

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id_delete'];
    $person_egn = $director_egn = $doctor_egn = $patient_egn = "";

    $sql_getPersonEGN = "SELECT egn FROM users WHERE id='$user_id'";
    $stmt_getPersonEGN = $link->query($sql_getPersonEGN);
    if ($stmt_getPersonEGN->num_rows > 0) {
        while ($row1 = $stmt_getPersonEGN->fetch_assoc()) {
            $person_egn = $row1['egn'];
        }
    }

    //sql for deleting from table user_access
    $sql_ua = "DELETE FROM user_access WHERE `user_id` = '$user_id'";


    $sql_updateFromPat = "UPDATE patients SET `attending_doctor`='' WHERE `attending_doctor`= '$user_id'";
    mysqli_query($link, $sql_updateFromPat);


    //sql for deleting from table users
    $sql = "DELETE FROM users WHERE `id` = '$user_id'";

    //try and delete from user_access
    if (mysqli_query($link, $sql_ua)) {

        //try and delete from made_researches
        if (mysqli_query($link, $sql)) {
            header("Location: update_permission.php");
        } else {
            //error deleting from made_researches
            echo "Error: " . $sql . "" . mysqli_error($link);
        }

    } else {
        //error deleting from user_access
        echo "Error: " . $sql_ua . "" . mysqli_error($link);
    }

    $sql_deleteFromDirector = "SELECT * FROM director WHERE egn='$person_egn'";
    $stmt_getPersonFromDirector = $link->query($sql_deleteFromDirector);
    if ($stmt_getPersonFromDirector->num_rows > 0) {
        while ($row1 = $stmt_getPersonFromDirector->fetch_assoc()) {
            $director_egn = $row1['egn'];
        }
        $sql_deletePerson = "DELETE FROM director WHERE egn='$director_egn'";
        if (mysqli_query($link, $sql_deletePerson)) {
        } else {
            //error deleting from user_access
            echo "Error: " . $sql_deletePerson . "" . mysqli_error($link);
        }
    }

    $sql_deleteFromDoctor = "SELECT * FROM doctors WHERE egn='$person_egn'";
    $stmt_getPersonFromDoctor = $link->query($sql_deleteFromDoctor);
    if ($stmt_getPersonFromDoctor->num_rows > 0) {
        while ($row1 = $stmt_getPersonFromDoctor->fetch_assoc()) {
            $doctor_egn = $row1['egn'];
        }
        $sql_deletePerson = "DELETE FROM doctors WHERE egn='$doctor_egn'";
        if (mysqli_query($link, $sql_deletePerson)) {
        } else {
            //error deleting from user_access
            echo "Error: " . $sql_deletePerson . "" . mysqli_error($link);
        }
    }

    $sql_deleteFromPatient = "SELECT * FROM patients WHERE egn='$person_egn'";
    $stmt_getPersonFrom = $link->query($sql_deleteFromPatient);
    if ($stmt_getPersonFrom->num_rows > 0) {
        while ($row1 = $stmt_getPersonFrom->fetch_assoc()) {
            $patient_egn = $row1['egn'];
        }
        $sql_deletePerson = "DELETE FROM patients WHERE egn='$patient_egn'";
        if (mysqli_query($link, $sql_deletePerson)) {
        } else {
            //error deleting from user_access
            echo "Error: " . $sql_deletePerson . "" . mysqli_error($link);
        }
    }


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
<section class="update_permission">
    <h2 class="title-display-all-info-people">Permissions</h2>
    <div class="style-table">
        <table class="table_read_section">
            <!-- colum names for table -->
            <thead>
            <tr>
                <th>Permission ID</th>
                <th>Permission code</th>
            </tr>
            </thead>

            <?php
            //go through all each value stored in the array
            foreach ($array_permissions as $val_permission) {
                echo "<tr>";

                echo "<td>" . $val_permission['permission_id'] . "</td>";
                echo "<td>" . $val_permission['permission_code'] . "</td>";

                echo "</tr>";
            }
            ?>
        </table>
    </div>
</section>
<section class="update_permission">
    <h2 class="title-display-all-info-people">Given Permissions</h2>
    <div class="style-table scroll_permission">
        <table class="table_update_permission">
            <!-- colum names for table -->
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Permission ID</th>
            </tr>

            <?php
            //go through all each value stored in the array
            foreach ($array as $val) {
                echo "<tr>";
                echo "<td>" . $val['user_id'] . "</td>";
                echo "<td>" . $val['username'] . "</td>";
                echo "<td>" . $val['permission_id'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <article class="row-2">
        <div class="col-names">
            <form class="form_update_permission" method="post">
                <input type="text" name="user_id" placeholder="User ID: " required>
                <input type="text" name="permission_id" placeholder="Permission ID: " required>
                <button class="btn" name="update_permission">Update Permission</button>
            </form>
        </div>
    </article>

    <article class="row-2">
        <div class="col-names">
            <form class="form_update_permission" action="" method="post">
                <input type="text" name="user_id_delete" placeholder="User ID: " required>
                <button class="btn" id="btn_delete" name="delete_user">Delete User</button>
            </form>
        </div>
    </article>
</section>

<hr>
<?php
include("../footer/footer_Admin.php");
?>

<script src="../script.js"></script>
</body>
</html>