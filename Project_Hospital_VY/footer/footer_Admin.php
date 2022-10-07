<?php

/**
 * @var $link
 * @var $hospital_name
 * @var $hospital_address
 */
require_once "../configs/config.php";

/*------read from table----------*/
$hospital_name = $hospital_address = "";
//sql for getting info from director
$sql_show_HD = "SELECT * FROM hospital";
$stmt_show_HD = $link->query($sql_show_HD);
//check if there is any data
if ($stmt_show_HD->num_rows > 0) {
    while ($row_hd = $stmt_show_HD->fetch_assoc()) {
        //store every row in the array
        $hospital_name = $row_hd['hospital_name'];
        $hospital_address = $row_hd['hospital_address'];
    }
}
?>

<footer class="footer">
    <section class="footer-col">
        <article class="footer-logo">
            <img class="inverted" src="../pictures/logo.png" alt="logo"/>
        </article>
        <article class="mvyar-lab">
            <h3 class="footer-title"><?php echo $hospital_name; ?></h3>
            <ul class="footer-list">
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="../index.php #home">Home</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="../index.php #dep">Departments</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="../index.php #about">About us</a>
                </li>
                <li class="footer-list-item">
                    <a class="footer-list-item-link" href="../index.php #contacts">Contacts</a>
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