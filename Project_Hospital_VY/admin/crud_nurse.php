<?php
require_once "../configs/config.php";

/**
 * @var $link
 */

$nurseID = $email = $egn = $fname = $mname = $lname = $gender = $address = $phoneNumber = $department = $curr_department = "";
$nurseData['fname'] = "";
$nurseData['mname'] = "";
$nurseData['lname'] = "";
$nurseData['egn'] = "";
$nurseData['gender'] = "";
$nurseData['address'] = "";
$nurseData['phone_number'] = "";
$nurseData['email'] = "";
$nurseData['department'] = "";

$array = array();
/*------read from table----------*/

//sql for getting info from director
$sql_show = "SELECT id, fname, mname, lname, email, phone_number, address, department FROM nurses";
$stmt_show = $link->query($sql_show);
//check if there is any data
if ($stmt_show->num_rows > 0) {
    while ($row = $stmt_show->fetch_assoc()) {
        //store every row in the array
        $array[] = $row;
    }
}

//sql to get data for the table department
$mysql_dep = "SELECT * FROM departments";
$result_dep = mysqli_query($link, $mysql_dep);
/*----------END-----------*/


if (isset($_POST['add_nurse'])) {

    if (empty($_POST['fname']) || empty($_POST['personalID']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['address'])) {
        $msg = "You have to fill all fields!";
        echo "  <script>
                alert('$msg');
                window.location.href='crud_nurse.php';
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
        $department = $_POST['department'];


        if (empty($department)) {
            $curr_department = $_POST['curr_department'];
            $department = $curr_department;
        }

        $sql = "SELECT id FROM nurses WHERE egn = '$egn'";
        $stmt = $link->query($sql);
        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                $doctor_id = $row['id'];
            }
            $msg_exists = "This EGN already exists.";
            //pop up message warning
            echo "<script>
                    alert('$msg_exists');
                    window.location.href='crud_nurse.php';
                </script>";
        } else {

            $sql_add = "INSERT INTO nurses(fname, mname, lname, egn, gender, address, phone_number, email, department) 
             VALUES ('$fname','$mname','$lname','$egn','$gender','$address','$phoneNumber','$email', '$department')";

            mysqli_query($link, $sql_add);
        }
        header("Location: crud_nurse.php");
        mysqli_close($link);
    }
}

if (isset($_POST['update_nurse'])) {

    $nurseID = $_POST['nurse_id'];
    $sql = "SELECT * FROM nurses WHERE id= '$nurseID'";

    $stmt = $link->query($sql);

    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $nurseData['fname'] = $row['fname'];
            $nurseData['mname'] = $row['mname'];
            $nurseData['lname'] = $row['lname'];
            $nurseData['egn'] = $row['egn'];
            $nurseData['gender'] = $row['gender'];
            $nurseData['address'] = $row['address'];
            $nurseData['phone_number'] = $row['phone_number'];
            $nurseData['email'] = $row['email'];
            $nurseData['department'] = $row['department'];
        }

    } else {
        //databse has no such ID
        $msg = "No such ID = " . $nurseID;
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='crud_nurse.php';
             </script>";
    }

    # echo $nurseData['department'];

}

if (isset($_POST['update_info'])) {

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $egn = $_POST['personalID'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $department = $_POST['department'];

    if (empty($department)) {
        $curr_department = $_POST['curr_department'];
        $department = $curr_department;
    }

    $sql_update = "UPDATE `nurses` SET `fname`='$fname',`mname`='$mname',`lname`='$lname',`egn`='$egn',`address`='$address',
        `phone_number`='$phone_number',`email`='$email', `department`='$department' 
        WHERE egn = '$egn'";

    mysqli_query($link, $sql_update);

    header("Location: crud_nurse.php");

}


if (isset($_POST['delete_nurse'])) {
    //prev director_id
    $nurseID = $_POST['nurse_id_delete'];

    //sql for deleting from table maintenance
    $sql_ua = "DELETE FROM nurses WHERE `id` = '$nurseID'";
    mysqli_query($link, $sql_ua);
    //try and delete from user_access
    /*if (mysqli_query($link, $sql_ua)) {

        //try and delete from made_researches
        if(mysqli_query($link, $sql_mr)){

            //sql for deleting from table users
            $sql = "DELETE FROM users WHERE `id` = $nurseID";

            //try and delete from users
            if(mysqli_query($link, $sql)){
                //redirect to same page for the table to have the new variant
                header("Location: crud_nurse.php");

            }else {
                //error deleting from users
                echo "Error: " . $sql . "" . mysqli_error($link);
            }
        } else {
            //error deleting from made_researches
            echo "Error: " . $sql_mr . "" . mysqli_error($link);
        }

    } else {
        //error deleting from user_access
        echo "Error: " . $sql_ua . "" . mysqli_error($link);
    }*/
    header("Location: crud_nurse.php");
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
        <h2 class="title-display-all-info-people">All Nurses</h2>
        <div class="style-table">
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Nurse ID</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Department</th> <!-- get name of the department -->
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
                    echo "<td>" . $val['email'] . "</td>";
                    echo "<td>" . $val['phone_number'] . "</td>";
                    echo "<td>" . $val['address'] . "</td>";
                    echo "<td>" . $val['department'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>
    <h2 class="title-second">Personal Information</h2>
    <div class="col-names">
        <input type="text" value='<?php echo $nurseData['fname']; ?>' name="fname" placeholder="Name: ">
        <input type="text" value='<?php echo $nurseData['mname']; ?>' name="mname" placeholder="Middle name: ">
        <input type="text" value='<?php echo $nurseData['lname']; ?>' name="lname" placeholder="Last name: ">
        <input type="text" value='<?php echo $nurseData['egn']; ?>' maxlength="10" name="personalID"
               placeholder="EGN: ">
    </div>


    <article class="row-2">
        <div class="col-names">
            <input type="text" value='<?php echo $nurseData['address']; ?>' name="address" placeholder="Address: ">
            <input type="text" value='<?php echo $nurseData['phone_number']; ?>' maxlength="10" id="tel"
                   name="phone_number" placeholder="Phone: ">
            <input type="email" name="email" value='<?php echo $nurseData['email']; ?>' placeholder="Email: ">
            <div class="col-gender_blood">

                <select class="genders" name="gender" id="gender">
                    <option disabled selected value>Select gender</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                </select>
            </div>
        </div>
    </article>


    <article class="row-2">
        <div class="col-names">
            <select class="col-names option_department" name="department" id="department">
                <option disabled selected value>Select department</option>
                <?php
                // use a while loop to fetch data
                // from the $all_categories variable
                // and individually display as an option
                while ($department = mysqli_fetch_array(
                    $result_dep, MYSQLI_ASSOC)):;
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
            <input type="text" name="curr_department" value="<?php echo $nurseData['department']; ?>"
                   placeholder="Your current department is: " readonly>
        </div>
    </article>
    <?php
    if (isset($_POST['update_nurse'])) {
        ?>
        <div class="space_around_button">
            <button class="btn" name="add_maintenance" hidden>Add</button>
        </div>

        <div class="space_around_button">
            <button class="btn" name="update_info">Update info</button>
        </div>
        <div class="space_around_button">
            <button class="btn" name="cancel">Cancel</button>
        </div>
    <?php } else { ?>
        <div class="space_around_button">
            <button class="btn" name="add_nurse" show>Add</button>
        </div>
    <?php } ?>

    <div class="space-admin">
        <div class="col-names space-crud-button">
            <input type="text" name="nurse_id" placeholder="Nurse ID: ">
            <button class="btn" name="update_nurse">Update</button>

        </div>

        <div class="col-names space-crud-button">
            <input type="text" name="nurse_id_delete" placeholder="Nurse ID: ">
            <button class="btn" id="btn_delete" name="delete_nurse">Delete nurse</button>
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


