<?php
//connect to database
require_once "configs/config.php";
/**
 * @var $link
 */
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$curr_user_id = $_SESSION['id'];
#echo $curr_user_id;
$egn = $patientData['health_condition'] = $attending_doctor_id = $doctor_egn = $doctor_fname = $doctor_lname = "";
$array = array();

$sql_getEGN_fromUsers = "SELECT * FROM users WHERE id='$curr_user_id'";
$stmt_getEGN = $link->query($sql_getEGN_fromUsers);
if ($stmt_getEGN->num_rows > 0) {
    while ($row = $stmt_getEGN->fetch_assoc()) {
        //store every row in the array
        $egn = $row['egn'];
    }
}


$slq_infoPat = "SELECT * FROM patients WHERE egn='$egn'";
$stmt_showPat = $link->query($slq_infoPat);
if ($stmt_showPat->num_rows > 0) {
    while ($row1 = $stmt_showPat->fetch_assoc()) {
        //store every row in the array
        $array[] = $row1;
        $patientData['health_condition'] = $row1['health_condition'];
        $attending_doctor_id = $row1['attending_doctor'];
    }
}
#echo $attending_doctor_id;
$sql_getEGND = "SELECT egn FROM users WHERE id='$attending_doctor_id'";
$stmt_showDoc = $link->query($sql_getEGND);
if ($stmt_showDoc->num_rows > 0) {
    while ($row2 = $stmt_showDoc->fetch_assoc()) {
        $doctor_egn = $row2['egn'];
    }
}
#echo $doctor_egn;

$sql_getDName = "SELECT * FROM doctors WHERE egn='$doctor_egn'";
$stmt_showDocName = $link->query($sql_getDName);
if ($stmt_showDocName->num_rows > 0) {
    while ($row3 = $stmt_showDocName->fetch_assoc()) {
        $doctor_fname = $row3['fname'];
        $doctor_lname = $row3['lname'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital</title>
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
<h2 class="title-second">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h2>
<h2 class="title-second-result-page"> Welcome to your result page</h2>

<form method="post">
    <section class="read_section">
        <div class="style-table">
            <table class="table_read_section">

                <?php
                //go through all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";

                    echo "<tr><th>First name</th> <td>" . $val['fname'] . "</td> </tr>";
                    echo "<tr><th>Middle name</th> <td>" . $val['mname'] . "</td> </tr>";
                    echo "<tr><th>Last name</th> <td>" . $val['lname'] . "</td> </tr>";
                    echo "<tr><th>Disease</th> <td>" . $val['disease'] . "</td> </tr>";
                    echo "<tr><th>Condition</th> <td>" . $val['statistic_condition'] . "</td> </tr>";
                    echo "<tr><th>Department</th> <td>" . $val['department'] . "</td> </tr>";
                    echo "<tr><th>Room</th> <td>" . $val['room_number'] . "</td> </tr>";
                    echo "<tr><th>Admission date</th> <td>" . $val['admission_date'] . "</td> </tr>";
                    echo "<tr><th>Discharge date</th> <td>" . $val['discharge_date'] . "</td> </tr>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>
    <h2 class="title-second">Attending Doctor:<p><?php echo $doctor_fname . " " . $doctor_lname; ?></p></h2>
    <h2 class="title-second">Health condition: <p><?php echo $patientData['health_condition']; ?></p></h2>

</form>
<hr>
<?php
include("footer/footer.php");
?>
<script src="script.js"></script>
</body>
</html>