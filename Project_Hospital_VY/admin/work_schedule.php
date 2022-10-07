<?php
/**
 * @var $link
 */
require_once "../configs/config.php";
$array = array();
$array1 = array();
$array2 = array();
$array3 = array();
$depName = $shift = $date = $lname = $fname = $mname = $egn = $phone_number = $department = "";

$doctorData['fname'] = "";
$doctorData['mname'] = "";
$doctorData['lname'] = "";
$doctorData['egn'] = "";
$doctorData['phone_number'] = "";
$doctorData['department'] = "";
$nurseData['fname'] = "";
$nurseData['mname'] = "";
$nurseData['lname'] = "";
$nurseData['egn'] = "";
$nurseData['phone_number'] = "";
$nurseData['department'] = "";
$maintenanceData['fname'] = "";
$maintenanceData['mname'] = "";
$maintenanceData['lname'] = "";
$maintenanceData['egn'] = "";
$maintenanceData['phone_number'] = "";
$maintenanceData['department'] = "";

$shiftData['shift_id'] = "";
$shiftData['department'] = "";
$shiftData['shift_date'] = "";
$shiftData['egn'] = "";

//sql to get data for the table department
$mysql_dep = "SELECT dep_name FROM departments";
$result_dep = $link->query($mysql_dep);

if (isset($_POST['submit'])) {
    $depName = $_POST['department'];
    if (!isset($_POST['department'])) {
        echo "  <script>
                alert('You have to select a department first!');
                window.location.href='work_schedule.php';
                </script>";
    }

    $sql1 = "SELECT fname,mname,lname,phone_number,department,egn FROM doctors WHERE department IN ('$depName')";
    $result1 = $link->query($sql1);
    $sql2 = "SELECT fname,mname,lname,phone_number,department,egn FROM nurses WHERE department IN ('$depName')";
    $result2 = $link->query($sql2);
    $sql3 = "SELECT fname,mname,lname,phone_number,department,egn FROM maintenance WHERE department IN ('$depName')";
    $result3 = $link->query($sql3);

    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            //store every row in the array
            $array1[] = $row;
        }
    }
    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            //store every row in the array
            $array2[] = $row;
        }
    }
    if ($result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            //store every row in the array
            $array3[] = $row;
        }
    }

}

if (isset($_POST['add_shift'])) {
    $egn = $_POST['egn'];
    $date = $_POST['date'];
    $shift = $_POST['shift'];

    if (empty($egn) || empty($date) || empty($shift)) {
        $msg = "You have to fill all the fields!";
        echo "  <script>
                alert('$msg');
                window.location.href='work_schedule.php';
                </script>";
    }

    $sql_doc = "SELECT * FROM doctors WHERE egn='$egn'";
    $stmt = $link->query($sql_doc);
    if (mysqli_num_rows($stmt) != 0) {
        while ($row = $stmt->fetch_assoc()) {
            $doctorData['fname'] = $row['fname'];
            $doctorData['mname'] = $row['mname'];
            $doctorData['lname'] = $row['lname'];
            $doctorData['phone_number'] = $row['phone_number'];
            $doctorData['department'] = $row['department'];
        }
        $date = $_POST['date'];
        $shift = $_POST['shift'];
        $egn = $_POST['egn'];
        $fname = $doctorData['fname'];
        $mname = $doctorData['mname'];
        $lname = $doctorData['lname'];
        $phone_number = $doctorData['phone_number'];
        $department = $doctorData['department'];

        $sql_add = "INSERT INTO shifts(shift_date,shift,fname,mname,lname,egn,phone_number,department) VALUES ('$date','$shift','$fname','$mname','$lname','$egn','$phone_number','$department')";
        mysqli_query($link, $sql_add);
    }

    $sql_nur = "SELECT * FROM nurses WHERE egn='$egn'";
    $stmt_n = $link->query($sql_nur);
    if (mysqli_num_rows($stmt_n) != 0) {
        while ($row = $stmt_n->fetch_assoc()) {
            $nuresData['fname'] = $row['fname'];
            $nuresData['mname'] = $row['mname'];
            $nuresData['lname'] = $row['lname'];
            $nuresData['phone_number'] = $row['phone_number'];
            $nuresData['department'] = $row['department'];
        }
        $date = $_POST['date'];
        $shift = $_POST['shift'];
        $egn = $_POST['egn'];
        $fname = $nuresData['fname'];
        $mname = $nuresData['mname'];
        $lname = $nuresData['lname'];
        $phone_number = $nuresData['phone_number'];
        $department = $nuresData['department'];

        $sql_add = "INSERT INTO shifts(shift_date,shift,fname,mname,lname,egn,phone_number,department) VALUES ('$date','$shift','$fname','$mname','$lname','$egn','$phone_number','$department')";
        mysqli_query($link, $sql_add);
    }

    $sql_maint = "SELECT * FROM maintenance WHERE egn='$egn'";
    $stmt_m = $link->query($sql_maint);
    if (mysqli_num_rows($stmt_m) != 0) {
        while ($row = $stmt_m->fetch_assoc()) {
            $maintenanceData['fname'] = $row['fname'];
            $maintenanceData['mname'] = $row['mname'];
            $maintenanceData['lname'] = $row['lname'];
            $maintenanceData['phone_number'] = $row['phone_number'];
            $maintenanceData['department'] = $row['department'];
        }
        $date = $_POST['date'];
        $shift = $_POST['shift'];
        $egn = $_POST['egn'];
        $fname = $maintenanceData['fname'];
        $mname = $maintenanceData['mname'];
        $lname = $maintenanceData['lname'];
        $phone_number = $maintenanceData['phone_number'];
        $department = $maintenanceData['department'];

        $sql_add = "INSERT INTO shifts(shift_date,shift,fname,mname,lname,egn,phone_number,department) VALUES ('$date','$shift','$fname','$mname','$lname','$egn','$phone_number','$department')";
        mysqli_query($link, $sql_add);
    }

    header("Location: work_schedule.php");
    mysqli_close($link);
}

if (isset($_POST['view'])) {
    $depName = $_POST['department'];

    if (!isset($_POST['department'])) {
        echo "  <script>
                alert('In order to view the schedule, select a department!');
                window.location.href='work_schedule.php';
                </script>";
    }

    $slq_getshifts = "SELECT * FROM shifts WHERE department IN ('$depName')";
    $stmt_shifts = $link->query($slq_getshifts);
    if ($stmt_shifts->num_rows > 0) {
        while ($row = $stmt_shifts->fetch_assoc()) {
            //store every row in the array
            $array[] = $row;
            $shiftData['department'] = $row['department'];
        }
    }

}

if (isset($_POST['update_shift'])) {
    $shiftID = $_POST['shift_id'];
    $sql = "SELECT * FROM shifts WHERE shift_id= '$shiftID'";

    $stmt = $link->query($sql);

    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $shiftData['shift_id'] = $row['shift_id'];
            $shiftData['shift_date'] = $row['shift_date'];
            $shiftData['egn'] = $row['egn'];
            $shiftData['shift'] = $row['shift'];
        }

    } else {
        //databse has no such ID
        $msg = "No such ID = " . $shiftID;
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='work_schedule.php';
             </script>";
    }
}

if (isset($_POST['update_info'])) {
    $shiftID = $_POST['shift_id'];
    $date = $_POST['date'];
    $shift = $_POST['shift'];


    $sql_update = "UPDATE `shifts` SET `shift_date`='$date',`shift`='$shift'WHERE shift_id = '$shiftID'";

    mysqli_query($link, $sql_update);
    header("Location: work_schedule.php");
    mysqli_close($link);

}

if (isset($_POST['delete_shift'])) {
    $shiftID = $_POST['shift_id_delete'];

    //sql for deleting from table shifts
    $sql_delete_shift = "DELETE FROM shifts WHERE `shift_id` = '$shiftID'";
    mysqli_query($link, $sql_delete_shift);


    header("Location: work_schedule.php");

}

if (isset($_POST['delete_shift_all'])) {
    $depName = $_POST['shift_department'];

    $slq_delete_all_shifts = "DELETE FROM shifts WHERE department='$depName'";
    mysqli_query($link, $slq_delete_all_shifts);

    header("Location: work_schedule.php");
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
    <h2 class="title-display-all-info-people">Work schedule for employees</h2>

    <div class="col-gender_blood">
        <div>
            <select class="select" name="department" id="department">
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
        </div>
    </div>

    <section class="space-admin">
        <div class="col-names space-crud-button">
            <button class="btn" name="submit">Submit</button>
        </div>

        <div class="col-names space-crud-button">
            <button class="btn" name="view">View shift</button>
        </div>
        <div class="manipulation"></div>
    </section>

    <?php if (isset($_POST['submit'])) { ?>
        <div class="style-table">
            <section class="read_section">
                <h2>
                    <div class="title-second">Doctors</div>
                </h2>
                <table class="table_read_section">
                    <!-- colum names for table -->
                    <thead>
                    <tr>
                        <th>First name</th>
                        <th>Middle name</th>
                        <th>Last name</th>
                        <th>EGN</th>
                        <th>Phone Number</th>
                        <th>Department</th>
                    </tr>
                    </thead>

                    <?php
                    //go through all each value stored in the array
                    foreach ($array1 as $val_doc) {
                        echo "<tr>";
                        echo "<td>" . $val_doc['fname'] . "</td>";
                        echo "<td>" . $val_doc['mname'] . "</td>";
                        echo "<td>" . $val_doc['lname'] . "</td>";
                        echo "<td>" . $val_doc['egn'] . "</td>";
                        echo "<td>" . $val_doc['phone_number'] . "</td>";
                        echo "<td>" . $val_doc['department'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </section>
        </div>

        <div class="style-table">
            <section class="read_section">
                <h2>
                    <div class="title-second">Nurses</div>
                </h2>
                <table class="table_read_section">
                    <?php
                    //go through all each value stored in the array
                    foreach ($array2 as $val_n) {
                        echo "<tr>";
                        echo "<td>" . $val_n['fname'] . "</td>";
                        echo "<td>" . $val_n['mname'] . "</td>";
                        echo "<td>" . $val_n['lname'] . "</td>";
                        echo "<td>" . $val_n['egn'] . "</td>";
                        echo "<td>" . $val_n['phone_number'] . "</td>";
                        echo "<td>" . $val_n['department'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </section>
        </div>


        <div class="style-table">
            <section class="read_section">
                <h2>
                    <div class="title-second">Maintenance workers</div>
                </h2>
                <table class="table_read_section">

                    <?php
                    //go throught all each value stored in the array
                    foreach ($array3 as $val_m) {
                        echo "<tr>";
                        echo "<td>" . $val_m['fname'] . "</td>";
                        echo "<td>" . $val_m['mname'] . "</td>";
                        echo "<td>" . $val_m['lname'] . "</td>";
                        echo "<td>" . $val_m['egn'] . "</td>";
                        echo "<td>" . $val_m['phone_number'] . "</td>";
                        echo "<td>" . $val_m['department'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </section>
        </div>

        <article class="space-admin">
            <h2>
                <div class="title-second">Add a shift</div>
            </h2>
            <div class="col-names">
                <input type="date" id="_date" value='' name="date" placeholder="Date: ">
            </div>

            <div class="col-gender_blood">
                <div>
                    <select class="genders" name="shift" id="shift">
                        <option disabled selected value>Select shift</option>
                        <option value="1 shift (06:00 - 18:00)">1 shift (06:00 - 18:00)</option>
                        <option value="2 shift (18:00 - 06:00)">2 shift (18:00 - 06:00)</option>
                        <option value="24 hour (06:00 - 06:00)">24 hour (06:00 - 06:00)</option>
                    </select>
                </div>
            </div>

            <div class="col-names">
                <input type="text" id="egn" name="egn" placeholder="EGN: ">
            </div>

            <div class="space_around_button">
                <button class="btn" name="add_shift" show>Add</button>
            </div>

            <div class="space_around_button">
                <button class="btn" name="cancel" show>Cancel</button>
            </div>
        </article>
    <?php } ?>

    <?php if (isset($_POST['view'])){ ?>

    <div class="style-table">
        <section class="read_section">
            <h2>
                <div class="title-second">Department Work Shifts</div>
            </h2>
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Shift ID</th>
                    <th>Date</th>
                    <th>Shift</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>EGN</th>
                    <th>Phone Number</th>
                    <th>Department</th>
                </tr>
                </thead>

                <?php
                //go throught all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";
                    echo "<td>" . $val['shift_id'] . "</td>";
                    echo "<td>" . $val['shift_date'] . "</td>";
                    echo "<td>" . $val['shift'] . "</td>";
                    echo "<td>" . $val['fname'] . "</td>";
                    echo "<td>" . $val['mname'] . "</td>";
                    echo "<td>" . $val['lname'] . "</td>";
                    echo "<td>" . $val['egn'] . "</td>";
                    echo "<td>" . $val['phone_number'] . "</td>";
                    echo "<td>" . $val['department'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </section>


        <div class="space_under"></div>
        <div class="col-names">
            <input type="text" name="shift_id" placeholder="Shift ID: ">
            <button class="btn" name="update_shift">Update</button>
        </div>


        <div class="col-names">
            <input type="text" name="shift_id_delete" placeholder="Shift ID: ">
            <button class="btn" name="delete_shift">Delete</button>
        </div>


        <div class="col-names">
            <input type="text" name="shift_department" value='<?php echo $shiftData['department']; ?>'
                   placeholder="Current department: " readonly>
            <button class="btn" name="delete_shift_all">Delete All</button>
        </div>

        <div class="col-names">
            <button class="btn" name="cancel" show>Cancel</button>
        </div>
        <?php } ?>
        <?php if (isset($_POST['update_shift'])) { ?>
            <div class="col-names">
                <input type="text" name="shift_id" value='<?php echo $shiftData['shift_id']; ?>'
                       placeholder="Shift ID: ">
            </div>
            <div class="col-names">
                <input type="date" id="_date" value='<?php echo $shiftData['shift_date']; ?>' name="date"
                       placeholder="Date: ">
            </div>
            <div class="col-names">
                <input type="text" name="shift" value='<?php echo $shiftData['shift']; ?>' placeholder="Current shift: "
                       readonly>
            </div>

            <div class="col-gender_blood">
                <div>
                    <select class="genders" name="shift" id="shift">
                        <option disabled selected value>Select shift</option>
                        <option value="1 shift (06:00 - 18:00)">1 shift (06:00 - 18:00)</option>
                        <option value="2 shift (18:00 - 06:00)">2 shift (18:00 - 06:00)</option>
                        <option value="24 hour (06:00 - 06:00)">24 hour (06:00 - 06:00)</option>
                    </select>
                </div>
            </div>

            <div class="col-names">
                <input type="text" id="egn_update" value='<?php echo $shiftData['egn']; ?>' name="egn_update"
                       placeholder="EGN: ">
            </div>

            <div class="add_director">
                <button class="btn" name="update_info">Update info</button>
            </div>

            <div class="add_director">
                <button class="btn" name="cancel" show>Cancel</button>
            </div>
        <?php } ?>


</form>

<hr>
<?php
include("../footer/footer_Admin.php");
?>
<script src="../script.js"></script>
</body>
</html>
