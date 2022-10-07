<?php
/**
 * @var $link
 */

//get connection to database
require_once "../configs/config.php";

$hospital_name = $hospital_address = $hospital_id = "";
$new_hospital_name = $new_hospital_address = "";
$add_hospital_name = $add_hospital_address = "";

# show everything for hospital name and address
$sql_show = "SELECT * FROM hospital";
$stmt_show = $link->query($sql_show);
//check if there is any data
if ($stmt_show->num_rows > 0) {
    while ($row = $stmt_show->fetch_assoc()) {
        //store every row in the array
        $hospital_name = $row['hospital_name'];
        $hospital_id = $row['hospital_id'];
        $hospital_address = $row['hospital_address'];
    }
}

# add information about hospital name and address
if (isset($_POST['add_hospital'])) {
    $add_hospital_name = $_POST['hospital_name'];
    $add_hospital_address = $_POST['hospital_address'];

    if (empty($add_hospital_name) || empty($add_hospital_address)) {
        $msg = "You have to fill all...";
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='hospital_name.php';
             </script>";
    } else {
        $sql = "SELECT hospital_id FROM hospital WHERE hospital_name LIKE '$add_hospital_name' AND hospital_address LIKE '$add_hospital_address'";
        $stmt = $link->query($sql);
        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                $hospital_id = $row['id'];
            }
            $msg_exists = "This hospital already exists.";
            //pop up message warning
            echo "<script>
                    alert('$msg_exists');
                    window.location.href='hospital_name.php';
                </script>";
        } else {
            $sql = "INSERT INTO `hospital`(`hospital_name`, `hospital_address`) VALUES ('$add_hospital_name','$add_hospital_address')";
            mysqli_query($link, $sql);
            header("Location: hospital_name.php");
            mysqli_close($link);
        }
    }
}

# update information about hospital name and address
if (isset($_POST['update_hospital'])) {
    $hospital_name = $_POST['hospital_name'];
    $hospital_address = $_POST['hospital_address'];
    $hospital_id = $_POST['hospital_id'];

    if (empty($hospital_name) || empty($hospital_address)) {
        $msg = "You have to fill all...";
        //pop up message warning
        echo "<script>
                alert('$msg');
                window.location.href='hospital_name.php';
             </script>";
    } else {
        $sql = "UPDATE `hospital` SET `hospital_name`='$hospital_name', `hospital_address`='$hospital_address' WHERE hospital_id='$hospital_id'";
        mysqli_query($link, $sql);
        header("Location: hospital_name.php");
        mysqli_close($link);
    }

}

# delete information about hospital name and address
if (isset($_POST['delete_hospital'])) {
    $hospital_id = $_POST['hospital_id'];

    $sql_delete = "DELETE FROM hospital WHERE hospital_id='$hospital_id'";
    mysqli_query($link, $sql_delete);
    header("Location: hospital_name.php");
    mysqli_close($link);

}


?>