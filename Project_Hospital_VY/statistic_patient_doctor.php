<?php
require_once "configs/config.php";
/**
 * @var $link
 */

$array = array();
$array1 = array();

//sql to get data for the table department
$mysql_dep = "SELECT dep_name FROM departments";
$result_dep = $link->query($mysql_dep);

if (isset($_POST['view'])) {
    if (empty($_POST['department'])) {
        $msg01 = "You have to select a department first.";
        echo "  <script>
                alert('$msg01');
                window.location.href='statistic_patient_doctor.php';
                </script>";
    } else {


        // count every patient and doctor from set department
        $dep_name = $_POST['department'];
        $sql_pat = "SELECT COUNT(department) AS `statistic` FROM patients WHERE department LIKE '$dep_name'";
        $result_pat = mysqli_query($link, $sql_pat);
        if (!($row = mysqli_fetch_assoc($result_pat))) {
            $row['statistic'] = 0;
        }

        $sql_doc = "SELECT COUNT(department) AS `statistic` FROM doctors WHERE department LIKE '$dep_name'";
        $result_doc = mysqli_query($link, $sql_doc);
        if (!($row1 = mysqli_fetch_assoc($result_doc))) {
            $row1['statistic'] = 0;
        }

        // display result
        $dataPoints = array(
            array("label" => "Patients", "y" => $row['statistic']),
            array("label" => "Doctors", "y" => $row1['statistic'])
        );

        if ($row['statistic'] == 0 && $row1['statistic'] == 0) {
            $msg = "No data to display.";
            echo "  <script>
                alert('$msg');
                window.location.href='statistic_patient_doctor.php';
                </script>";
        }

        // get information for patient and doctor
        $sql_getPat_info = "SELECT * FROM patients WHERE department='$dep_name'";
        $stmt_show = $link->query($sql_getPat_info);
        //check if there is any data
        if ($stmt_show->num_rows > 0) {
            while ($row = $stmt_show->fetch_assoc()) {
                //store every row in the array
                $array[] = $row;
            }
        }
        $sql_getDoc_info = "SELECT * FROM doctors WHERE department='$dep_name'";
        $stmt_show_doc = $link->query($sql_getDoc_info);
        //check if there is any data
        if ($stmt_show_doc->num_rows > 0) {
            while ($row = $stmt_show_doc->fetch_assoc()) {
                //store every row in the array
                $array1[] = $row;
            }
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
include("header/headerAdmin_statistic.php");
?>
<hr>

<form method="post">
    <h2>
        <div class="title-second">Statistics for all patients and doctors</div>
    </h2>
    <div class="col-gender_blood">
        <div>
            <select name="department" id="department">
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
    <div class="form-group">
        <button class="btn space_around_button" name="view">View</button>
        <div class="space_under"></div>
    </div>


    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Department <?php echo $dep_name;?>"
                },
                data: [{
                    type: "pie",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "#percent%",
                    //"##0"
                    yValueFormatString: "0",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <h2 class="title-second">Patient Information</h2>
    <div class="style-table">
        <section class="read_section">
            <table class="table_read_section">
                <!-- column names for table -->
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>EGN</th>
                    <th>Blood type</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>

                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array as $val) {
                    echo "<tr>";
                    echo "<td>" . $val['fname'] . "</td>";
                    echo "<td>" . $val['mname'] . "</td>";
                    echo "<td>" . $val['lname'] . "</td>";
                    echo "<td>" . $val['egn'] . "</td>";
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

    <h2 class="title-second">Doctor Information</h2>
    <section class="read_section">
        <div class="style-table">
            <table class="table_read_section">
                <!-- column names for table -->
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>EGN</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>

                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array1 as $val) {
                    echo "<tr>";
                    echo "<td>" . $val['fname'] . "</td>";
                    echo "<td>" . $val['mname'] . "</td>";
                    echo "<td>" . $val['lname'] . "</td>";
                    echo "<td>" . $val['egn'] . "</td>";
                    echo "<td>" . $val['email'] . "</td>";
                    echo "<td>" . $val['phone_number'] . "</td>";
                    echo "<td>" . $val['address'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <div class="space_under"></div>
    </section>
</form>

<hr>
<?php
include("footer/footer.php");
?>

<script src="script.js"></script>
</body>
</html>
