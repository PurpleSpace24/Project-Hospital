<?php

/**
 * @var $link
 */
//for storing the egn
session_start();

require_once "configs/config.php";

$array_dep = array();
$array_doctors = array();
$array_nurses = array();
$array_maintenance = array();
$array_patients = array();

//------read from table----------//

$sql_show_dep = "SELECT * FROM departments";
$stmt_show_dep = $link->query($sql_show_dep);
//check if there is any data
if ($stmt_show_dep->num_rows > 0) {
    while ($row = $stmt_show_dep->fetch_assoc()) {
        //store every row in the array
        $array_dep[] = $row;
    }
}

//sql for getting info from director
$sql_show_doctors = "SELECT * FROM doctors";
$stmt_show_doctors = $link->query($sql_show_doctors);
//check if there is any data
if ($stmt_show_doctors->num_rows > 0) {
    while ($row = $stmt_show_doctors->fetch_assoc()) {
        //store every row in the array
        $array_doctors[] = $row;
    }
}

$sql_show_nurses = "SELECT * FROM nurses";
$stmt_show_nurses = $link->query($sql_show_nurses);
//check if there is any data
if ($stmt_show_nurses->num_rows > 0) {
    while ($row = $stmt_show_nurses->fetch_assoc()) {
        //store every row in the array
        $array_nurses[] = $row;
    }
}

$sql_show_maintenance = "SELECT * FROM maintenance";
$stmt_show_maintenance = $link->query($sql_show_maintenance);
//check if there is any data
if ($stmt_show_maintenance->num_rows > 0) {
    while ($row = $stmt_show_maintenance->fetch_assoc()) {
        //store every row in the array
        $array_maintenance[] = $row;
    }
}

$sql_show_patients = "SELECT * FROM patients";
$stmt_show_patients = $link->query($sql_show_patients);
//check if there is any data
if ($stmt_show_patients->num_rows > 0) {
    while ($row = $stmt_show_patients->fetch_assoc()) {
        //store every row in the array
        $array_patients[] = $row;
    }
}


//----------END-----------//

?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital</title>
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
    <section class="button-group-responsive">
        <div class="space_above wrap-manipulation">
            <button class="btn" name="show_departments">Show all departments</button>
        </div>
    </section>
    <?php
    if (isset($_POST['show_departments'])) { ?>
    <h2 class="title-second">All Departments</h2>
    <section class="read_section">
        <div class="style-table">
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Department name</th>
                    <th>Department description</th>
                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array_dep as $val_dep) {
                    echo "<tr>";
                    echo "<td>" . $val_dep['dep_name'] . "</td>";
                    echo "<td>" . $val_dep['dep_description'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>
    <section class="button-group-responsive">
        <div class="space-crud-button wrap-manipulation space_under">
            <button class="btn" name="cancel">Cancel</button>
        </div>
        <section class="button-group-responsive">
            <?php } ?>

            <section class="button-group-responsive">
                <div class="wrap-manipulation">
                    <button class="btn" name="show_doctors">Show all doctors</button>
                </div>
            </section>
            <?php
            if (isset($_POST['show_doctors'])) { ?>
                <h2 class="title-second">All Doctors</h2>
                <section class="read_section">
                    <div class="style-table">
                        <table class="table_read_section">
                            <!-- colum names for table -->
                            <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Department</th>
                            </tr>
                            </thead>

                            <?php
                            //go throught all each value stored in the array
                            foreach ($array_doctors as $val_doctors) {
                                echo "<tr>";
                                echo "<td>" . $val_doctors['fname'] . "</td>";
                                echo "<td>" . $val_doctors['mname'] . "</td>";
                                echo "<td>" . $val_doctors['lname'] . "</td>";
                                echo "<td>" . $val_doctors['email'] . "</td>";
                                echo "<td>" . $val_doctors['phone_number'] . "</td>";
                                echo "<td>" . $val_doctors['address'] . "</td>";
                                echo "<td>" . $val_doctors['department'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </section>
                <section class="button-group-responsive">
                    <div class="space-crud-button wrap-manipulation space_under">
                        <button class="btn" name="cancel">Cancel</button>
                    </div>
                </section>
            <?php } ?>
            <section class="button-group-responsive">
                <div class="wrap-manipulation">
                    <button class="btn" name="show_nurses">Show all nurses</button>
                </div>
            </section>
            <?php
            if (isset($_POST['show_nurses'])) { ?>
                <h2 class="title-second">All Nurses</h2>
                <section class="read_section">
                    <div class="style-table">
                        <table class="table_read_section">
                            <!-- colum names for table -->
                            <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Department</th>
                            </tr>
                            </thead>

                            <?php
                            //go throught all each value stored in the array
                            foreach ($array_nurses as $val_nurses) {
                                echo "<tr>";
                                echo "<td>" . $val_nurses['fname'] . "</td>";
                                echo "<td>" . $val_nurses['mname'] . "</td>";
                                echo "<td>" . $val_nurses['lname'] . "</td>";
                                echo "<td>" . $val_nurses['email'] . "</td>";
                                echo "<td>" . $val_nurses['phone_number'] . "</td>";
                                echo "<td>" . $val_nurses['address'] . "</td>";
                                echo "<td>" . $val_nurses['department'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </section>
                <section class="button-group-responsive">
                    <div class="space-crud-button wrap-manipulation space_under">
                        <button class="btn" name="cancel">Cancel</button>
                    </div>
                </section>
            <?php } ?>
            <section class="button-group-responsive">
                <div class="wrap-manipulation">
                    <button class="btn" name="show_maintenance">Show all maintenance</button>
                </div>
            </section>
            <?php
            if (isset($_POST['show_maintenance'])) { ?>
                <h2 class="title-second">All Maintenance</h2>
                <section class="read_section">
                    <div class="style-table">
                        <table class="table_read_section">
                            <!-- colum names for table -->
                            <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Department</th>
                            </tr>
                            </thead>

                            <?php
                            //go throught all each value stored in the array
                            foreach ($array_maintenance as $val_maintenance) {
                                echo "<tr>";
                                echo "<td>" . $val_maintenance['fname'] . "</td>";
                                echo "<td>" . $val_maintenance['mname'] . "</td>";
                                echo "<td>" . $val_maintenance['lname'] . "</td>";
                                echo "<td>" . $val_maintenance['email'] . "</td>";
                                echo "<td>" . $val_maintenance['phone_number'] . "</td>";
                                echo "<td>" . $val_maintenance['address'] . "</td>";
                                echo "<td>" . $val_maintenance['department'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </section>
                <section class="button-group-responsive">
                    <div class="space-crud-button wrap-manipulation space_under">
                        <button class="btn" name="cancel">Cancel</button>
                    </div>
                </section>
            <?php } ?>
            <section class="button-group-responsive">
                <div class="wrap-manipulation">
                    <button class="btn" name="show_patients">Show all patients</button>
                </div>
            </section>
            <?php
            if (isset($_POST['show_patients'])) { ?>
            <h2 class="title-second">All Patients</h2>
            <section class="read_section">
                <div class="style-table">
                    <table class="table_read_section">
                        <!-- colum names for table -->
                        <thead>
                        <tr>
                            <th>First name</th>
                            <th>Middle name</th>
                            <th>Last name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Department</th>
                            <th>Blood Type</th>
                            <th>Disease</th>
                            <th>Health Condition</th>
                            <th>Price per day</th>
                            <th>Days</th>
                            <th>Admission Date</th>
                            <th>Discharge Date</th>
                        </tr>
                        </thead>
                        <?php
                        //go throught all each value stored in the array
                        foreach ($array_patients as $val_patients) {
                            echo "<tr>";
                            echo "<td>" . $val_patients['fname'] . "</td>";
                            echo "<td>" . $val_patients['mname'] . "</td>";
                            echo "<td>" . $val_patients['lname'] . "</td>";
                            echo "<td>" . $val_patients['email'] . "</td>";
                            echo "<td>" . $val_patients['phone_number'] . "</td>";
                            echo "<td>" . $val_patients['address'] . "</td>";
                            echo "<td>" . $val_patients['department'] . "</td>";
                            echo "<td>" . $val_patients['blood_type'] . "</td>";
                            echo "<td>" . $val_patients['disease'] . "</td>";
                            echo "<td>" . $val_patients['health_condition'] . "</td>";
                            echo "<td>" . $val_patients['price_per_day'] . "</td>";
                            echo "<td>" . $val_patients['days'] . "</td>";
                            echo "<td>" . $val_patients['admission_date'] . "</td>";
                            echo "<td>" . $val_patients['discharge_date'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <section class="button-group-responsive">
                    <div class="space-crud-button wrap-manipulation space_under">
                        <button class="btn" name="cancel">Cancel</button>
                    </div>
                </section>
                <?php } ?>
                <section class="button-group-responsive">
                    <section class="statistics space_under">
                        <div class="wrap-manipulation">
                            <button class="btn"><a href="statistic_for_director.php">View Statistics</a></button>
                        </div>
                    </section>
                </section>
            </section>
</form>
<hr>
<?php
include_once("footer/footer.php");
?>
<script src="script.js"></script>
</body>

</html>