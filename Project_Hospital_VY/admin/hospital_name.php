<?php
/**
 * @var $link
 * @var $hospital_name
 * @var $hospital_id
 * @var $hospital_address
 */

//get connection to database
require "../configs/config.php";
include "hospital_name_process.php";


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

    <h2 class="title-second space_above">Hospital information</h2>

    <div class="title-third space_above">
        <h3><?php echo "Name: " . $hospital_name; ?></h3></div>
    <div class="title-third">
        <input type="text" name="hospital_id" value="<?php echo $hospital_id; ?>" hidden>
        <h3><?php echo "Address: " . $hospital_address; ?></h3>
    </div>

    <div class="space_around_button">
        <button class="btn space_under space_around_button" name="change_info">Change</button>
    </div>
    <div class="space_under"></div>

    <?php
    if (isset($_POST['change_info'])) {
        ?>
        <h2 class="title-second space_above">Change information</h2>
        <div class="space-admin">
            <div class="form-group">
                <label>Name</label>
                <input type="text" value='<?php echo $hospital_name; ?>' name="hospital_name"
                       placeholder="Hospital name: ">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" value='<?php echo $hospital_address; ?>' name="hospital_address"
                       placeholder="Hospital address: ">
            </div>
        </div>


        <div class="col-names">
            <button class="btn hospital_manipulation_margin space_under" name="add_hospital">Add</button>

            <button class="btn hospital_manipulation_margin space_under" name="update_hospital">Update</button>

            <button class="btn hospital_manipulation_margin space_under" id="btn_delete" name="delete_hospital">Delete
            </button>

            <button class="btn hospital_manipulation_margin space_under" name="cancel">Cancel</button>
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
