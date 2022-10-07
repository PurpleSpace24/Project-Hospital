<?php
require_once "../configs/config.php";
/**
 * @var $link
 */

$array_dep = array();
/*------read from table----------*/
//sql for getting info from director
$sql_show_DEP = "SELECT * FROM departments";
$stmt_show_DEP = $link->query($sql_show_DEP);
//check if there is any data
if ($stmt_show_DEP->num_rows > 0) {
    while ($row_dep = $stmt_show_DEP->fetch_assoc()) {
        //store every row in the array
        $array_dep[] = $row_dep;
    }
}
/*----------END-----------*/

$depData['dep_id'] = "";
$depData['dep_name'] = "";
$depData['dep_description'] = "";
$depData['dep_price'] = "";
$dep_id = $dep_name = $dep_description = $dep_price = "";


if (isset($_POST['add_dep'])) {

    if (empty($_POST['dep_name'])) {
        $message = "Fill in all fields first!";
        echo "<script>
                alert('$message');
                window.location.href='crud_departments.php';
                </script>";
    } else {

        $dep_name = $_POST['dep_name'];
        $dep_description = $_POST['dep_description'];
        $dep_price = $_POST['dep_price'];

        $select = mysqli_query($link, "SELECT * FROM departments WHERE dep_name = '" . $dep_name . "'");
        if (mysqli_num_rows($select)) {
            $msg = "Department name = " . $dep_name . " already exist!";
            echo "<script>
                alert('$msg');
                window.location.href='crud_departments.php';
                </script>";

        } else {
            $sql_add_dep = "INSERT INTO departments(dep_name, dep_description, dep_price) VALUES ('$dep_name', '$dep_description', '$dep_price')";

            if (mysqli_query($link, $sql_add_dep)) {
                header("location: crud_departments.php");
            } else {
                $reg_err = "Department failed to be added.";
            }
        }

    }
    mysqli_close($link);
}

if (isset($_POST['update_dep'])) {
    $dep_id = $_POST['dep_id'];
    $sql = "SELECT * FROM departments WHERE dep_id = $dep_id";

    $stmt = $link->query($sql);

    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $depData['dep_id'] = $row['dep_id'];
            $depData['dep_name'] = $row['dep_name'];
            $depData['dep_description'] = $row['dep_description'];
            $depData['dep_price'] = $row['dep_price'];
        }

    } else {
        //databse has no such ID
        $msg = "No such department with ID = " . $dep_id;
        //pop up message warning
        echo "<script>
                    alert('$msg');
                    window.location.href='crud_departments.php';
                 </script>";

    }
   
}

if (isset($_POST['update_info'])) {
    $dep_id = $_POST['dep_id_number'];
    $dep_name = $_POST['dep_name'];
    $dep_description = $_POST['dep_description'];
    $dep_price = $_POST['dep_price'];

    $sql_update = "UPDATE `departments` SET `dep_name`='$dep_name', `dep_description`='$dep_description', `dep_price`='$dep_price' WHERE dep_id = $dep_id";
    mysqli_query($link, $sql_update);

    header("Location: crud_departments.php");

    mysqli_close($link);
}

if (isset($_POST['delete_dep'])) {
    $dep_id = $_POST['dep_id_delete'];

    //sql for deleting from table departments
    $sql_delete_dep = "DELETE FROM departments WHERE `dep_id` = '$dep_id'";
    mysqli_query($link, $sql_delete_dep);
    //sql for deleting from table user_access
    #$sql_mr = "DELETE FROM user_access WHERE `user_id` = '$dir_id'";

    header("Location: crud_departments.php");

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
        <h2 class="title-display-all-info-people">All Departments</h2>
        <div class="style-table">
            <table class="table_read_section">
                <!-- colum names for table -->
                <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Department name</th>
                    <th>Description</th>
                    <th>Price per day</th>
                </tr>
                </thead>

                <?php
                //go through all each value stored in the array
                foreach ($array_dep as $val_dep) {
                    echo "<tr>";
                    echo "<td>" . $val_dep['dep_id'] . "</td>";
                    echo "<td>" . $val_dep['dep_name'] . "</td>";
                    echo "<td>" . $val_dep['dep_description'] . "</td>";
                    echo "<td>" . $val_dep['dep_price'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>

    <h2 class="title-display-all-info-people">Department information</h2>
    <div class="col-names-vertical">
        <input type="hidden" name="dep_id_number" value="<?php echo $depData['dep_id']; ?>">
        <input type="text" value='<?php echo $depData['dep_name']; ?>' name="dep_name" placeholder="Department name: ">
        <input type="number" value='<?php echo $depData['dep_price']; ?>' name="dep_price"
               placeholder="Department price: ">
        <div class="col-names-vertical">
            <textarea class='textarea-doctor' name='dep_description' rows='8' cols='300'
                      placeholder='Input description: '><?php echo $depData['dep_description']; ?></textarea>
        </div>
    </div>


    <?php
    if (isset($_POST['update_dep'])) {
        ?>
        <div class="add_director">
            <button class="btn" name="add_dep" hidden>Add</button>
        </div>

        <div class="add_director">
            <button class="btn" name="update_info">Update info</button>
        </div>

        <div class="add_director">
            <button class="btn" name="cancel">Cancel</button>
        </div>

    <?php } else { ?>
        <div class="add_director">
            <button class="btn" name="add_dep" show>Add</button>
        </div>
    <?php } ?>

    <div class="space-admin">
        <div class="col-names space-crud-button">
            <input type="text" name="dep_id" placeholder="Department ID: ">
            <button class="btn" name="update_dep">Update</button>
        </div>


        <div class="col-names space-crud-button">
            <input type="text" name="dep_id_delete" placeholder="Department ID: ">
            <button class="btn" id="btn_delete" name="delete_dep">Delete department</button>
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