<?php
require_once "../configs/config.php";
/**
 * @var $link
 */

$array = array();
/*------read from table----------*/

//sql for getting info from director
$sql_show = "SELECT id, fname, mname, lname, email, phone_number, address, blood_type FROM patients";
$stmt_show = $link->query($sql_show);
//check if there is any data
if ($stmt_show->num_rows > 0) {
    while ($row = $stmt_show->fetch_assoc()) {
        //store every row in the array
        $array[] = $row;
    }
}
/*----------END-----------*/


$patientData['fname'] = "";
$patientData['mname'] = "";
$patientData['lname'] = "";
$patientData['egn'] = "";
$patientData['gender'] = "";
$patientData['address'] = "";
$patientData['phone_number'] = "";
$patientData['email'] = "";
$patientData['blood_type'] = "";

$email = $egn = $fname = $mname = $lname = $gender = $address = $phoneNumber = $blood_type = "";
$username_err = $password_err = $username = $password = $patient_userID = $user_id = $new_username = $old_username = "";

if (isset($_POST['add_patient'])) {
    if (empty($_POST['fname']) || empty($_POST['personalID']) || empty($_POST['lname']) || empty($_POST['address'])) {
        $msg = "You have to fill all fields!";
        echo "  <script>
                alert('$msg');
                window.location.href='crud_patients.php';
                </script>";
    } else {
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
        $egn = $_POST['personalID'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phone_number'];
        $blood_type = $_POST['blood_type'];

        $sql = "SELECT id FROM patients WHERE egn = '$egn'";

        $stmt = $link->query($sql);
        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                $doctor_id = $row['id'];
            }
            $msg_exists = "This EGN already exists.";
            //pop up message warning
            echo "<script>
                    alert('$msg_exists');
                    window.location.href='crud_patients.php';
                </script>";
        } else {
            $sql_add = "INSERT INTO patients(fname, mname, lname, egn, gender, address, phone_number, email, blood_type) 
        VALUES ('$fname','$mname','$lname','$egn','$gender','$address','$phoneNumber','$email', '$blood_type')";
            mysqli_query($link, $sql_add);

            if (empty(trim($_POST["username"]))) {
                $username_err = "Please enter a username.";
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
                $username_err = "Username can only contain letters, numbers, and underscores.";
            } else {
                $sql = "SELECT id FROM users WHERE username = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

                    $param_username = trim($_POST["username"]);

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            $username_err = "This username is already taken.";
                        } else {
                            $username = trim($_POST["username"]);
                        }
                    } else {
                        $reg_err = "Oops! Something went wrong. Please try again later.";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            if (empty(trim($_POST["password"]))) {
                $password_err = "Please enter a password.";
            } else {
                $password = trim($_POST["password"]);
            }

            if (empty($username_err) && empty($password_err)) {

                if ($egn == $password) {
                    $sql = "INSERT INTO users (username, password, egn) VALUES (?, ?, ?)";

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $egn);

                        $param_username = $username;
                        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                        if (mysqli_stmt_execute($stmt)) {
                            $sql_ui = "SELECT id FROM users WHERE username = '$param_username'";
                            $stmt_ui = $link->query($sql_ui);

                            if ($stmt_ui->num_rows > 0) {
                                while ($row = $stmt_ui->fetch_assoc()) {
                                    $user_id = $row['id'];
                                }
                            }

                            $sql_permission = "INSERT INTO user_access (user_id, permission_id) VALUE ('$user_id', 3)";
                            $stmt_p = mysqli_prepare($link, $sql_permission);

                            if (mysqli_stmt_execute($stmt_p)) {
                                header("location: doctor_page.php");
                            } else {
                                $reg_err = "Permission failed to add.";
                            }
                        } else {
                            $reg_err = "Inccorect input of data";
                        }

                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $egn_passw_error = "EGN and password does not match";
                    //pop up message warning
                    echo "<script>
                alert('$egn_passw_error');
                   window.location.href='read_director.php';
                </script>";
                }
            }
        }
        header("Location: crud_patients.php");
        mysqli_close($link);
    }
}

if (isset($_POST['update_patient'])) {

    $patientID = $_POST['patient_id'];
    $sql = "SELECT * FROM patients WHERE id= '$patientID'";

    $stmt = $link->query($sql);

    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $patientData['fname'] = $row['fname'];
            $patientData['mname'] = $row['mname'];
            $patientData['lname'] = $row['lname'];
            $patientData['egn'] = $row['egn'];
            $patientData['gender'] = $row['gender'];
            $patientData['address'] = $row['address'];
            $patientData['phone_number'] = $row['phone_number'];
            $patientData['email'] = $row['email'];
            $patientData['blood_type'] = $row['blood_type'];

            $egn = $patientData['egn'];
            $sql_getUsernameID = "SELECT id FROM users WHERE egn='$egn'";
            $stmt_GetUsernameID = $link->query($sql_getUsernameID);
            if ($stmt_GetUsernameID->num_rows > 0) {
                while ($row = $stmt_GetUsernameID->fetch_assoc()) {
                    $user_id = $row['id'];
                }
            }
            $sql_getUsername = "SELECT username FROM users WHERE id='$user_id'";
            $stmt_GetUsername = $link->query($sql_getUsername);
            if ($stmt_GetUsername->num_rows > 0) {
                while ($row = $stmt_GetUsername->fetch_assoc()) {
                    $username = $row['username'];
                }
            } else {
                $username = "";
            }

        }

    } else {
        //databse has no such ID
        $msg = "No such ID = " . $patientID;
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='crud_patients.php';
             </script>";
    }

}

if (isset($_POST['update_info'])) {

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $egn = $_POST['personalID'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $blood_type = $_POST['blood_type'];

    $sql_update = "UPDATE `patients` SET `fname`='$fname',`mname`='$mname',`lname`='$lname',`egn`='$egn',`address`='$address',
        `phone_number`='$phone_number',`email`='$email', `blood_type`='$blood_type' 
        WHERE egn = '$egn'";

    mysqli_query($link, $sql_update);

    $sql_getUsername = "SELECT username FROM users WHERE egn='$egn'";
    $stmt_GetUsername = $link->query($sql_getUsername);
    if ($stmt_GetUsername->num_rows > 0) {
        while ($row = $stmt_GetUsername->fetch_assoc()) {
            $old_username = $row['username'];
        }
    }

    $new_username = $_POST['username'];
    if (empty($new_username)) {
        $new_username = $old_username;
    }

    #checks if the username already exist -> works, but doesn't want to show a $message_err_username
    $select = mysqli_query($link, "SELECT * FROM users WHERE username = '$new_username'");
    if (mysqli_num_rows($select)) {
        $sql_updateUsername = "UPDATE users SET username='$old_username' WHERE egn = '$egn'";
    } else {
        $sql_updateUsername = "UPDATE users SET username='$new_username' WHERE egn = '$egn'";
    }

    mysqli_query($link, $sql_updateUsername);
    header("Location: crud_patients.php");
    mysqli_close($link);

}

if (isset($_POST['delete_patient'])) {
    //prev director_id
    $patientID = $_POST['patient_id_delete'];

    $sql = "SELECT egn FROM patients WHERE `id` = '$patientID'";
    $stmt = $link->query($sql);
    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $egn = $row['egn'];
        }
    }

    $sql_selectUserID = "SELECT id FROM users WHERE egn='$egn'";
    $stmt_selectUserID = $link->query($sql_selectUserID);
    if ($stmt_selectUserID->num_rows > 0) {
        while ($row1 = $stmt_selectUserID->fetch_assoc()) {
            $patient_userID = $row1['id'];
        }
    }

    $sql_mr = "DELETE FROM user_access WHERE `user_id` = '$patient_userID'";
    //try and delete from user_access

    //try and delete from made_researches
    if (mysqli_query($link, $sql_mr)) {

        //sql for deleting from table users
        $sql = "DELETE FROM users WHERE `id` = '$patient_userID'";

        //try and delete from users
        if (mysqli_query($link, $sql)) {
            //redirect to same page for the table to have the new variant
            header("Location: crud_patients.php");

        } else {
            //error deleting from users
            echo "Error: " . $sql . "" . mysqli_error($link);
        }
    } else {
        //error deleting from made_researches
        echo "Error: " . $sql_mr . "" . mysqli_error($link);
    }

    $sql_ua = "DELETE FROM patients WHERE `id` = '$patientID'";
    if (mysqli_query($link, $sql_ua)) {

    } else {
        //error deleting from user_access
        echo "Error: " . $sql_ua . "" . mysqli_error($link);
    }

    #header("Location: crud_patients.php");
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
    <link href="https://fonts.googleapis.com/css?family=Montserrat%7CSrisakdi" rel="stylesheet">
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
        <h2 class="title-display-all-info-people">All Patients</h2>
        <div class="style-table">
            <table class="table_read_section">
                <!-- column names for table -->
                <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Blood type</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>

                </tr>
                </thead>

                <?php
                //go throught all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";
                    echo "<td>" . $val['id'] . "</td>";
                    echo "<td>" . $val['fname'] . "</td>";
                    echo "<td>" . $val['mname'] . "</td>";
                    echo "<td>" . $val['lname'] . "</td>";
                    echo "<td>" . $val['blood_type'] . "</td>";
                    echo "<td>" . $val['email'] . "</td>";
                    echo "<td>" . $val['phone_number'] . "</td>";
                    echo "<td>" . $val['address'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>

    <h2 class="title-second">Personal Information</h2>
    <div class="col-names">
        <input type="text" value='<?php echo $patientData['fname']; ?>' name="fname" placeholder="Name: ">
        <input type="text" value='<?php echo $patientData['mname']; ?>' name="mname" placeholder="Middle name: ">
        <input type="text" value='<?php echo $patientData['lname']; ?>' name="lname" placeholder="Last name: ">
        <input type="text" value='<?php echo $patientData['egn']; ?>' maxlength="10" name="personalID"
               placeholder="EGN: ">

    </div>

    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['address']; ?>' name="address" placeholder="Address: ">
            <input type="text" value='<?php echo $patientData['phone_number']; ?>' maxlength="10" id="tel"
                   name="phone_number" placeholder="Phone: ">
            <input type="email" name="email" value='<?php echo $patientData['email']; ?>' placeholder="Email: ">
        </div>
    </article>


    <div class="col-gender_blood">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['blood_type']; ?>' maxlength="3" name="blood_type"
                   placeholder="Blood type: ">
            <div>
                <select class="genders" name="gender" id="gender">
                    <option disabled selected value>Select gender</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                </select>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['update_patient'])) {
        ?>

        <div class="add_director">
            <button class="btn" name="add_patient" hidden>Add</button>
        </div>

        <h2 class="title-second">Login in system</h2>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username"
                   class=" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>

        <div type="hidden" class="form-group">
            <label style="display:none">Password</label>
            <input type="hidden" type="text" name="password"
                   class=" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
            <span hidden class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>


        <div class="add_director">
            <button class="btn" name="update_info">Update info</button>
        </div>
        <div class="add_director">
            <button class="btn" name="cancel">Cancel</button>
        </div>
    <?php } else { ?>
        <h2 class="title-second">Login in system</h2>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class=" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class=" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <p class="red_message">Use EGN for password.</p>

        <div class="add_director">
            <button class="btn" name="add_patient" show>Add</button>
        </div>
    <?php } ?>

    <div class="space-admin">
        <div class="col-names space-crud-button">
            <input type="text" name="patient_id" placeholder="Patient ID: ">
            <button class="btn" name="update_patient">Update</button>
        </div>


        <div class="col-names space-crud-button">
            <input type="text" name="patient_id_delete" placeholder="Patient ID: ">
            <button class="btn" id="btn_delete" name="delete_patient">Delete patient</button>
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