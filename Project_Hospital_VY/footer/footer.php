<?php

require_once "configs/config.php";

/**
 * @var $link
 * @var $hospital_name
 * @var $hospital_address
 */

$hospital_name = $hospital_address = "";
$sql_getHospitalName = "SELECT * FROM hospital";
$stmt_getHN = $link->query($sql_getHospitalName);
if ($stmt_getHN->num_rows > 0) {
    while ($row_HD = $stmt_getHN->fetch_assoc()) {
        $hospital_name = $row_HD['hospital_name'];
        $hospital_address = $row_HD['hospital_address'];
    }
}
?>

<footer class="footer">
    <section class="footer-col">
        <article class="footer-logo">
            <img class="inverted" src="pictures/logo.png" alt="logo"/>
        </article>
        <article class="mvyar-lab">
            <h3 class="footer-title"><?php echo $hospital_name; ?></h3>
            <ul class="footer-list">
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="index.php #home">Home</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="index.php #dep">Departments</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="index.php #about">About us</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="index.php #contacts">Contacts</a>
                </li>
            </ul>
        </article>

        <article class="footer-contacts">
            <h3 class="footer-title">Contacts</h3>
            <ul class="footer-list">
                <li class="footer-list-item">
                    <a class="footer-list-item-contacts"><i class="fas fa-city"></i></a><?php echo $hospital_address; ?>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-contacts"><i class="fas fa-phone-alt"></i></a>0887569854
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-contacts"><i class="far fa-envelope"></i></a>mvyarproj@gmail.com
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-contacts"><i class="fas fa-briefcase"></i></a>Every day - 24h
                </li>
            </ul>
        </article>
    </section>
    <article class="copyright">
        <p>&copy; All Rights Reserved <?php echo $hospital_name; ?></p>
    </article>
</footer>