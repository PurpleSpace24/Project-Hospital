<?php
//for storing the egn
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital VY</title>
    <link rel="stylesheet" href="assets/style.css"/>
    <link rel="stylesheet" href="assets/typography.css"/>
    <link rel="stylesheet" href="assets/responsive.css"/>
</head>

<body>
<?php
include("header/headerAdmin_page.php");
?>
<hr>

<section class="admin-panel-section">
    <form class="admin-form" method="POST">
        <h2 class="title-admin_page">People information</h2>
        <article class="wrap-manipulation">


            <article class="manipulation">
                <button class="btn crud_director" name="crud_director"><a href="admin/crud_director.php">Director</a>
                </button>
            </article>

            <article class="manipulation">
                <button class="btn crud_doctor" name="crud_doctor"><a href="admin/crud_doctor.php">Doctor</a></button>
            </article>

            <article class="manipulation">
                <button class="btn crud_nurse" name="crud_nurse"><a href="admin/crud_nurse.php">Nurse</a></button>
            </article>

            <article class="manipulation">
                <button class="btn crud_maintenance" name="crud_maintenance"><a href="admin/crud_maintenance.php">Maintenance</a>
                </button>
            </article>

            <article class="manipulation">
                <button class="btn crud_patient" name="crud_patient"><a href="admin/crud_patients.php">Patients</a>
                </button>
            </article>


        </article>
        <h2 class="title-admin_page">Hospital information</h2>
        <article class="wrap-manipulation">

            <article class="manipulation">
                <button class="btn crud_department" name="crud_department"><a href="admin/crud_departments.php">Department</a>
                </button>
            </article>

            <article class="manipulation">
                <button class="btn crud_rooms" name="crud_rooms"><a href="admin/crud_rooms.php">Rooms</a></button>
            </article>

            <article class="manipulation">
                <button class="btn crud work schedule" name="crud_director"><a href="admin/hospital_name.php">Hospital
                        name and address</a></button>
            </article>

        </article>
        <h2 class="title-admin_page">Other information</h2>
        <article class="wrap-manipulation">
            <article class="manipulation">
                <button class="btn crud update permission" name="crud_director"><a href="admin/update_permission.php">Update
                        permission</a></button>
            </article>

            <article class="manipulation">
                <button class="btn crud work schedule" name="crud_director"><a href="admin/work_schedule.php">Work
                        schedule</a></button>
            </article>

            <article class="manipulation">
                <button class="btn crud work schedule" name="crud_director"><a href="statistic_patient_doctor.php">Statistic
                        for patient and attending doctor</a></button>
            </article>
        </article>


    </form>
</section>
<hr>
<?php
include("footer/footer.php");
?>

<script src="script.js"></script>
</body>

</html>