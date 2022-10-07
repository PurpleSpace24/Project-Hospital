<?php
require_once "configs/config.php";
/**
 * @var $link
 * @var $dep_name
 * @var $dataPoints
 */

$array = array();

//sql to get data for the table department
$mysql_dep = "SELECT dep_name FROM departments";
$result_dep = $link->query($mysql_dep);


if (isset($_POST['view'])) {
    $dep_name = $_POST['department'];

    $sql_cured = "SELECT COUNT(statistic_condition) AS `statistic` FROM patients WHERE admission_date>=date_sub(now(), interval 1 month) AND department LIKE '$dep_name' GROUP BY `statistic_condition`  HAVING `statistic_condition` = 'Cured'";
    $result_cured = mysqli_query($link, $sql_cured);
    if (!($row = mysqli_fetch_assoc($result_cured))) {
        $row['statistic'] = 0;
    }


    $sql_recovering = "SELECT COUNT(statistic_condition) AS `statistic` FROM patients WHERE admission_date>=date_sub(now(), interval 1 month) AND department LIKE '$dep_name' GROUP BY `statistic_condition`  HAVING `statistic_condition` = 'Recovering'";
    $result_recovering = mysqli_query($link, $sql_recovering);
    if (!($row1 = mysqli_fetch_assoc($result_recovering))) {
        $row1['statistic'] = 0;
    }


    $sql_untreated = "SELECT COUNT(statistic_condition) AS `statistic` FROM patients WHERE admission_date>=date_sub(now(), interval 1 month) AND department LIKE '$dep_name' GROUP BY `statistic_condition`  HAVING `statistic_condition` = 'Untreated'";
    $result_untreated = mysqli_query($link, $sql_untreated);
    if (!($row2 = mysqli_fetch_assoc($result_untreated))) {
        $row2['statistic'] = 0;
    }


    $dataPoints = array(

        array("label" => "Recovering", "y" => $row1['statistic']),
        array("label" => "Untreated", "y" => $row2['statistic']),
        array("label" => "Cured", "y" => $row['statistic']),
    );

    if ($row['statistic'] == 0 && $row1['statistic'] == 0 && $row2['statistic'] == 0) {
        $msg = "No data to display.";
        echo "  <script>
                alert('$msg');
                window.location.href='statistic_for_director.php';
                </script>";
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
include("header/headerDirector.php");
?>
<hr>

<form method="post"><h2 class="title-second">Statistics for patients for the last month</h2>
    <div class="wrap-manipulation-statistics">

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

        <div class="wrap-manipulation-statistics space-crud-button">
            <button class="btn  space_under" name="view">View</button>
        </div>
    </div>

    <script>
        window.onload = function () {

            const chart = new CanvasJS.Chart("chartContainer", {
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
                    yValueFormatString: "0",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</form>
<hr>
<?php
include("footer/footer.php");
?>

<script src="script.js"></script>
</body>
</html>