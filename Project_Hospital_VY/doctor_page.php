<?php
require_once "configs/config.php";
/**
 * @var $link
 */
session_start();
$patient_attending_doctor = "";
$msg_err = "";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    //  $user_doctor = $_SESSION['id'];
} else {
    echo "Please log in first to see this page.";
}

$array = array();

$patientData['fname'] = "";
$patientData['mname'] = "";
$patientData['lname'] = "";
$patientData['egn'] = "";
$patientData['gender'] = "";
$patientData['address'] = "";
$patientData['phone_number'] = "";
$patientData['email'] = "";
$patientData['blood_type'] = "";
$patientData['disease'] = "";
$patientData['health_condition'] = "";
$patientData['price'] = "";
$patientData['admission_date'] = "";
$patientData['discharge_date'] = "";
$patientData['department'] = "";
$patientData['room_number'] = "";
$patientData['disease'] = "";
$patientData['health_condition'] = "";
$patientData['statistic_condition'] = "";
$patientData['days'] = "";

$email = $egn = $fname = $mname = $lname = $gender = $address = $username = $password = $phoneNumber = $blood_type = $price = $admission_date = $discharge_date = $patient_name = $patient_egn = "";
$room_number = $department = "";
$disease = $health_condition = $days = $totalPrice = $depPrice = $statistic_condition = "";
$username_err = $password_err = $patient_userID = "";

//sql to get data for the table department
$mysql_dep = "SELECT dep_name FROM departments";
$result_dep = $link->query($mysql_dep);

$mysql_room = "SELECT room_number, room_name FROM rooms";
$result_room = $link->query($mysql_room);

if (isset($_POST['add_patient'])) {

    if (empty($_POST['fname']) || empty($_POST['personalID']) || empty($_POST['lname']) || empty($_POST['address'])) {
        $msg_err = "You have to fill all fields!";
        echo "  <script>
                alert('$msg_err');
                window.location.href='doctor_page.php';
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
        $price = $_POST['price'];
        $admission_date = $_POST['admission_date'];
        $discharge_date = $_POST['discharge_date'];
        $department = $_POST['department'];
        $room_number = $_POST['room_number'];
        $disease = $_POST['disease'];
        $health_condition = $_POST['health_condition'];
        $statistic_condition = $_POST['statistic_condition'];
        $days = $_POST['days'];
        $depPrice = $_POST['price'];

        if (empty($discharge_date)) {
            $discharge_date = "";
        }

        if (empty($admission_date)) {
            $admission_date = "";
        }

        // if dates are not empty => calculate days between them
        if (!empty($discharge_date) && !empty($admission_date)) {
            try {
                $datetime1 = new DateTime($discharge_date);
                $datetime2 = new DateTime($admission_date);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
            } catch (Exception $e) {
            }
        }

        // get price from department when empties
        if (empty($depPrice)) {
            $sql_dep = "SELECT dep_price FROM departments WHERE dep_name='$department'";
            $stmt_dep = $link->query($sql_dep);
            if ($stmt_dep->num_rows > 0) {
                while ($row_02 = $stmt_dep->fetch_assoc()) {
                    $dep_price = $row_02['dep_price'];
                }
            }
            $depPrice = $dep_price;
        }

        $sql = "SELECT id FROM patients WHERE egn = '$egn'";

        $stmt = $link->query($sql);
        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                $doctor_id = $row['id'];
            }
            $msg_err = "This EGN already exists.";
            //pop up message warning
            echo "<script>
                    alert('$msg_err');
                    window.location.href='doctor_page.php';
                </script>";
        } else {

            $user_doctor = $_SESSION['id'];

            $sql_add = "INSERT INTO patients(fname, mname, lname, egn, gender, address, phone_number, email, blood_type, price_per_day, admission_date, discharge_date, department, room_number, disease, health_condition, statistic_condition, days) 
        VALUES ('$fname', '$mname', '$lname', '$egn', '$gender', '$address','$phoneNumber','$email', '$blood_type', '$depPrice', '$admission_date', '$discharge_date', '$department', '$room_number', '$disease' , '$health_condition', '$statistic_condition' , '$days')";

            mysqli_query($link, $sql_add);

            $sql_set_doctor = "UPDATE patients SET attending_doctor = $user_doctor WHERE egn = $egn";
            mysqli_query($link, $sql_set_doctor);


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

                            $sql_permission = "INSERT INTO user_access (user_id, permission_id) VALUE ($user_id, 3)";
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

        header("Location: doctor_page.php");
        mysqli_close($link);
    }
}

/*if(isset($_POST['update_patient'])){

    $patientegn = $_POST['patient_egn'];
    $sql = "SELECT * FROM patients WHERE egn= $patientegn";

    $stmt = $link->query($sql);

    if($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()){
            $patientData['fname'] = $row['fname'];
            $patientData['mname'] = $row['mname'];
            $patientData['lname'] = $row['lname'];
            $patientData['egn'] = $row['egn'];
            $patientData['gender'] = $row['gender'];
            $patientData['address'] = $row['address'];
            $patientData['phone_number'] = $row['phone_number'];
            $patientData['email'] = $row['email'];
            $patientData['blood_type'] = $row['blood_type'];
            $patientData['price'] = $row['price'];
            $patientData['paid'] = $row['paid'];
            $patientData['admission_date'] = $row['admission_date'];
            $patientData['discharge_date'] = $row['discharge_date'];
            $patientData['department'] = $row['department'];
            $patientData['room_number'] = $row['room_number'];
        }

    } else {
        //databse has no such ID
        $msg = "No such ID = ".$patientegn;
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='doctor_page.php';
             </script>";
    }

}*/

if (isset($_POST['update'])) {

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $egn = $_POST['personalID'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $blood_type = $_POST['blood_type'];
    $price = $_POST['price'];
    $admission_date = $_POST['admission_date'];
    $discharge_date = $_POST['discharge_date'];
    $department = $_POST['department'];
    $room_number = $_POST['room_number'];
    $disease = $_POST['disease'];
    $health_condition = $_POST['health_condition'];
    $statistic_condition = $_POST['statistic_condition'];
    $days = $_POST['days'];
    $depPrice = $_POST['price'];

    if (empty($admission_date) || empty($discharge_date)) {
        $admission_date = "";
        $discharge_date = "";
    }

    if (empty($days)) {
        if (!empty($discharge_date) && !empty($admission_date)) {
            try {
                $datetime1 = new DateTime($discharge_date);
                $datetime2 = new DateTime($admission_date);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
            } catch (Exception $e) {
            }
        }
    } else {
        $days = 0;
    }

    // get price from department when empties
    if (empty($depPrice)) {
        $sql_dep = "SELECT dep_price FROM departments WHERE dep_name='$department'";
        $stmt_dep = $link->query($sql_dep);
        if ($stmt_dep->num_rows > 0) {
            while ($row_02 = $stmt_dep->fetch_assoc()) {
                $dep_price = $row_02['dep_price'];
            }
        }
        $depPrice = $dep_price;
    }

    $sql_update = "UPDATE `patients` SET `fname`='$fname', `mname`='$mname', `lname`='$lname', `egn`='$egn', `address`='$address', `phone_number`='$phone_number', `email`='$email', `blood_type`='$blood_type', `admission_date`='$admission_date', `discharge_date`='$discharge_date', `department`='$department', `room_number`='$room_number',
                `disease`='$disease', `health_condition` = '$health_condition', `statistic_condition` = '$statistic_condition',`days` = '$days',`price_per_day` = '$depPrice' WHERE egn = $egn";

    mysqli_query($link, $sql_update);

    $user_doctor = $_SESSION['id'];

    $sql_set_doctor = "UPDATE patients SET attending_doctor = $user_doctor WHERE egn = $egn";
    mysqli_query($link, $sql_set_doctor);


    header("Location: doctor_page.php");
}

if (isset($_POST['delete_patient'])) {
    //prev director_id
    $patientEGN = $_POST['patient_egn_delete'];

    $sql_selectUserID = "SELECT id FROM users WHERE egn='$patientEGN'";
    $stmt_selectUserID = $link->query($sql_selectUserID);
    if ($stmt_selectUserID->num_rows > 0) {
        while ($row1 = $stmt_selectUserID->fetch_assoc()) {
            $patient_userID = $row1['id'];
        }
    }

    //try and delete from user_access
    $sql_mr = "DELETE FROM user_access WHERE `user_id` = '$patient_userID'";
    if (mysqli_query($link, $sql_mr)) {
        //sql for deleting from table users
        $sql = "DELETE FROM users WHERE `id` = '$patient_userID'";
        //try and delete from users
        if (mysqli_query($link, $sql)) {
            //redirect to same page for the table to have the new variant
            header("Location: doctor_page.php");

        } else {
            //error deleting from users
            echo "Error: " . $sql . "" . mysqli_error($link);
        }
    } else {
        //error deleting from made_researches
        echo "Error: " . $sql_mr . "" . mysqli_error($link);
    }

    $sql_ua = "DELETE FROM patients WHERE `egn` = '$patientEGN'";
    if (mysqli_query($link, $sql_ua)) {
    } else {
        //error deleting from user_access
        echo "Error: " . $sql_ua . "" . mysqli_error($link);
    }

    mysqli_close($link);
}

if (isset($_POST['check_patient'])) {

    $user_doctor = $_SESSION['id'];

    //get user EGN from input text
    $patient_egn = $_POST['patient_egn'];

    //get user id based on user EGN
    $sql_find = "SELECT id FROM patients WHERE egn = $patient_egn";
    $stmt_find = $link->query($sql_find);
    if ($stmt_find->num_rows > 0) {
        while ($row = $stmt_find->fetch_assoc()) {
            $patient_id = $row['id'];
        }
    } else {
        //databse has no such EGN
        $msg_err = "No such EGN";
        //pop up message warning
        echo "<script>
                    alert('$msg_err');
                    window.location.href='doctor_page.php';
                </script>";
    }

    $sql_find_doctor_pat = "SELECT id, attending_doctor FROM patients WHERE egn = $patient_egn";
    // echo $sql_find_doctor_pat;
    $stmt_find_doctor_pat = $link->query($sql_find_doctor_pat);
    if ($stmt_find_doctor_pat->num_rows > 0) {
        while ($row = $stmt_find_doctor_pat->fetch_assoc()) {
            $patient_id = $row['id'];
            $patient_attending_doctor = $row['attending_doctor'];
        }
    }
    if (empty($patient_attending_doctor)) {
        $patient_attending_doctor = $user_doctor;
    }
    if ($patient_attending_doctor == $user_doctor) {

        $sql_info = "SELECT fname, lname FROM patients WHERE egn =$patient_egn";
        $stmt_info = $link->query($sql_info);
        if ($stmt_info->num_rows > 0) {
            while ($row = $stmt_info->fetch_assoc()) {
                //get name of patient
                $patient_name = $row['fname'] . " " . $row['lname'];
            }
        }

        //get data from tables users AND user_info
        $sql = "SELECT * FROM patients WHERE egn = $patient_egn";
        $stmt = $link->query($sql);

        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                //save data in array
                $patientData['fname'] = $row['fname'];
                $patientData['mname'] = $row['mname'];
                $patientData['lname'] = $row['lname'];
                $patientData['egn'] = $row['egn'];
                $patientData['gender'] = $row['gender'];
                $patientData['address'] = $row['address'];
                $patientData['phone_number'] = $row['phone_number'];
                $patientData['email'] = $row['email'];
                $patientData['blood_type'] = $row['blood_type'];
                $patientData['department'] = $row['department'];
                $patientData['room_number'] = $row['room_number'];
                $patientData['disease'] = $row['disease'];
                $patientData['health_condition'] = $row['health_condition'];
                $patientData['statistic_condition'] = $row['statistic_condition'];
                $patientData['admission_date'] = $row['admission_date'];
                $patientData['discharge_date'] = $row['discharge_date'];
                $patientData['days'] = $row['days'];
                $patientData['price'] = $row['price_per_day'];

            }
        }

        try {
            $datetime1 = new DateTime($patientData['admission_date']);
            $datetime2 = new DateTime($patientData['discharge_date']);
            $interval = $datetime1->diff($datetime2);
            $patientData['days'] = $interval->format('%a');
        } catch (Exception $e) {
        }

        $depname = $patientData['department'];
        $sql_GETDEPPRICE = "SELECT * FROM departments WHERE dep_name IN ('$depname')";
        if ($stmt_DEPprice = $link->query($sql_GETDEPPRICE)) {
            while ($rows = $stmt_DEPprice->fetch_array()) {
                $depPrice = $rows['dep_price'];
            }
        }

        $totalPrice = intval($patientData['days']) * intval($depPrice);

        $sql_show = "SELECT * FROM patients WHERE egn = $patient_egn";
        $stmt_show = $link->query($sql_show);
        //check if there is any data
        if ($stmt_show->num_rows > 0) {
            while ($row = $stmt_show->fetch_assoc()) {
                //store every row in the array
                $array[] = $row;
            }
        }
    } else {
        //Attending doctor doesn't have a permission to view data for this patient
        $msg_err = "You don't have access to this patient.";

    }

    // get username from users to check if patient has username and password for login
    $sql_user_getUsername = "SELECT username FROM users WHERE egn='$patient_egn'";
    $stmt_getUsername = $link->query($sql_user_getUsername);
    //check if there is any data
    if ($stmt_getUsername->num_rows > 0) {
        while ($row_user = $stmt_getUsername->fetch_assoc()) {
            //store every row in the array
            $username = $row_user['username'];
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
    <link href="https://fonts.googleapis.com/css?family=Montserrat%7CSrisakdi" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"/>
    <link rel="stylesheet" href="assets/typography.css"/>
    <link rel="stylesheet" href="assets/responsive.css"/>
</head>

<body>
<?php
include("header/headerAdmin_page.php");
?>
<hr>


<form method="post">
    <h2 class="title-second">Check Patient</h2>
    <?php
    if (!empty($msg_err)) {
        echo '<div class="alert alert-danger">' . $msg_err . '</div>';
    }
    ?>
    <div class="col-names">
        <input type="text" name="patient_egn" maxlength="10" placeholder="Patient EGN:">
    </div>


    <div class="col-names">
        <button class="btn add-inf" name="check_patient" id="check_patient" class="check_patient">Check</button>
    </div>
</form>
<form method="post">
    <br>
    <br>
    <br>

    <div class="res-name-egn-patient">
        <h4 class="res-name-egn-patient-title">Patient: <?php echo $patient_name ?></h4>
        <h4 class="res-name-egn-patient-title">EGN: <?php echo $patient_egn ?></h4>
    </div>
    <br>
    <section class="read_section">
        <div class="style-table">
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Disease</th>
                    <th>Health condition</th>
                    <th>Condition</th>
                    <th>Department</th>
                    <th>Room</th>
                    <th>Admission date</th>
                    <th>Discharge date</th>
                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";
                    echo "<td>" . $val['id'] . "</td>";
                    echo "<td>" . $val['fname'] . "</td>";
                    echo "<td>" . $val['mname'] . "</td>";
                    echo "<td>" . $val['lname'] . "</td>";
                    echo "<td>" . $val['disease'] . "</td>";
                    echo "<td>" . $val['health_condition'] . "</td>";
                    echo "<td>" . $val['statistic_condition'] . "</td>";
                    echo "<td>" . $val['department'] . "</td>";
                    echo "<td>" . $val['room_number'] . "</td>";
                    echo "<td>" . $val['admission_date'] . "</td>";
                    echo "<td>" . $val['discharge_date'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>
    <h2 class="title-second">Personal Information</h2>

    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['fname']; ?>' name="fname" placeholder="Name: ">
            <input type="text" value='<?php echo $patientData['mname']; ?>' name="mname" placeholder="Middle name: ">
            <input type="text" value='<?php echo $patientData['lname']; ?>' name="lname" placeholder="Last name: ">
            <input type="text" value='<?php echo $patientData['egn']; ?>' maxlength="10" name="personalID"
                   placeholder="EGN: ">
        </div>
    </article>

    <article class="row-2">
        <div class="col-names">

            <input type="text" value='<?php echo $patientData['blood_type']; ?>' maxlength="3" name="blood_type"
                   placeholder="Blood type: ">
            <input type="text" name="gender" value='<?php echo $patientData['gender']; ?>' placeholder="Gender: "
                   readonly>


            <select class="genders" name="gender" id="gender">
                <option disabled selected value>Select gender</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
            </select>

        </div>
    </article>

    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['address']; ?>' name="address" placeholder="Address: ">
            <input type="text" value='<?php echo $patientData['phone_number']; ?>' maxlength="10" id="tel"
                   name="phone_number" placeholder="Phone: ">
            <input type="email" name="email" value='<?php echo $patientData['email']; ?>' placeholder="Email: ">
        </div>
    </article>

    <h2 class="title-second">User information</h2>
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class=" <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
               value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>

    <?php if (isset($_POST['check_patient'])) { ?>
        <div class="form-group">
            <label style="display: none">Password</label>
            <input type="hidden" type="password" name="password"
                   class=" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $password; ?>">
            <span class="invalid-feedback" hidden><?php echo $password_err; ?></span>
        </div>
        <p class="red_message" hidden>Use patient's EGN for password.</p>
    <?php } else { ?>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class=" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $password; ?>">
            <span class="invalid-feedback" hidden><?php echo $password_err; ?></span>
        </div>
        <p class="red_message">Use patient's EGN for password.</p>
    <?php } ?>

    <h2 class="title-second">Discharge summary</h2>
    <article class="row-2">
        <div class="col-names">
            <input type="text" name="department" value='<?php echo $patientData['department']; ?>'
                   placeholder="Current department is: " readonly>

            <select name="department" id="department">
                <option disabled selected value>Select department</option>
                <?php
                // use a while loop to fetch data
                // from the $all_categories variable
                // and individually display as an option
                while ($department = mysqli_fetch_array($result_dep, MYSQLI_ASSOC)):;
                    ?>
                    <option value="<?php echo $department["dep_name"]; ?>">
                        <!--// The value we usually set is the primary key-->

                        <?php echo $department["dep_name"];
                        // To show the category name to the user
                        ?>
                    </option>
                <?php
                endwhile;
                // While loop must be terminated
                ?>
            </select>
        </div>
    </article>


    <article class="row-2">
        <div class="col-names">
            <input type="text" name="room_number" value='<?php echo $patientData['room_number']; ?>'
                   placeholder="Current room is: " readonly>
            <div class="col-gender_blood">
                <div>
                    <select name="room_number" id="room_number">
                        <option disabled selected value>Select room</option>
                        <?php
                        // use a while loop to fetch data
                        // from the $all_categories variable
                        // and individually display as an option
                        while ($rooms = mysqli_fetch_array($result_room, MYSQLI_ASSOC)):;
                            ?>
                            <option value="<?php echo $rooms["room_number"] . "-" . $rooms["room_name"] ?>">
                                <!--// The value we usually set is the primary key-->

                                <?php echo $rooms["room_number"];
                                echo " - ";
                                echo $rooms["room_name"];
                                // To show the category name to the user
                                ?>
                            </option>
                        <?php
                        endwhile;
                        // While loop must be terminated
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </article>


    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['statistic_condition']; ?>' name="statistic_condition"
                   placeholder="Condition: " readonly>
            <div>
                <div class="col-gender_blood">
                    <select class="genders" name="statistic_condition" id="statistic_condition">
                        <option disabled selected value>Condition</option>
                        <option value="Recovering">Recovering</option>
                        <option value="Cured">Cured</option>
                        <option value="Untreated">Untreated</option>
                    </select>
                </div>
            </div>
        </div>
    </article>

    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $patientData['disease']; ?>' name="disease" placeholder="Disease: ">

        </div>
        <div class="col-names">
            <textarea class='textarea-doctor' name='health_condition' rows='8' cols='100'
                      placeholder='Health condition: '><?php echo $patientData['health_condition']; ?></textarea>
        </div>
    </article>


    <h2 class="title-second">Hospitalized</h2>
    <p class="about-title">Admission date</p>
    <article class="row-2">
        <div class="col-names">
            <input type="date" id="_date" value='<?php echo $patientData['admission_date']; ?>' name="admission_date"
                   placeholder="Admission date: ">
        </div>
    </article>

    <p class="about-title">Discharge date</p>
    <article class="row-2">
        <div class="col-names">
            <input type="date" id="_date" value='<?php echo $patientData['discharge_date']; ?>' name="discharge_date"
                   placeholder="Discharge date: ">
        </div>
    </article>
    <p class="red_message">Both dates must be applied.</p>

    <article class="row-1">
        <div class="space_under"></div>
        <div class="col-names">
            <div class="form-group-hospitalization">
                <div class="merge_label_input">
                    <label>Days staying </label>
                    <input type="text" value='<?php echo $patientData['days']; ?>' name="days" placeholder="Days: "
                           readonly>
                </div>
            </div>

            <div class="form-group-hospitalization">
                <div class="merge_label_input">
                    <label>Price per day </label>
                    <input type="text" value='<?php echo $depPrice; ?>' name="price" placeholder="Price: " readonly>
                </div>
            </div>
            <div class="form-group-hospitalization">
                <div class="merge_label_input">
                    <label>Total price </label>
                    <input type="text" value='<?php echo $totalPrice ?>' name="totalprice" placeholder="Total price: "
                           readonly>
                </div>
            </div>
        </div>
    </article>

    </article>

    <?php
    if (isset($_POST['check_patient'])) {
        if (empty($msg_err)) {  // if patient exist -> show Update and Cancel
            ?>
            <div class="space_around_button">
                <button class="btn" name="add_patient" hidden>Add Patient</button>
            </div>

            <div class="space_around_button">
                <button class="btn" name="cancel" hidden>Cancel</button>
            </div>

            <div class="space_around_button">
                <button class="btn" name="update">Update</button>
                <button class="btn" name="cancel">Cancel</button>
            </div>
        <?php }
    } else { ?>
        <div class="space_around_button">
            <button class="btn" name="add_patient">Add Patient</button>
        </div>

        <div class="space_around_button">
            <button class="btn" name="update" hidden>Update</button>
            <button class="btn" name="cancel">Cancel</button>
        </div>

    <?php } ?>

    <div class="col-names space_around_button space_under">
        <input type="text" name="patient_egn_delete" maxlength="10" placeholder="Patient EGN: ">
        <button class="btn space-crud-button" id="btn_delete" name="delete_patient">Delete patient</button>
    </div>

</form>
<hr>
<?php
include("footer/footer.php");
?>

<script src="script.js"></script>
</body>
</html>